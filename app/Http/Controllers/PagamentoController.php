<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pagamento;
use App\Models\Despesa;
use Illuminate\Support\Facades\File;

class PagamentoController extends Controller
{
public function store(Request $request)
{
$request->validate([
    'despesa_id' => 'required|exists:despesas,id',
    'imagem' => 'nullable|image|mimes:jpg,jpeg,png,pdf|max:2048'
]);

$despesa = Despesa::findOrFail($request->despesa_id);

if (auth()->id() != $despesa->user_id || $despesa->pagamento) {
    return redirect('/dashboard')->with('msg', 'Ação não permitida!');
}

$data = [
    'despesa_id' => $despesa->id,
    'data_pagamento' => now(),
    'imagem' => null,
];

if ($request->hasFile('imagem') && $request->file('imagem')->isValid()) {
    $requestImage = $request->imagem;
    $extension = $requestImage->extension();
    $imageName = md5($requestImage->getClientOriginalName() . strtotime("now")) . "." . $extension;
    $requestImage->move(public_path('img/comprovantes'), $imageName);
    $data['imagem'] = $imageName;
}

Pagamento::create($data);
return redirect('/dashboard')->with('msg', 'Despesa marcada como paga!');
}

public function destroy($id)
{
$pagamento = Pagamento::findOrFail($id);

if (auth()->id() != $pagamento->despesa->user_id) {
    return redirect('/dashboard')->with('msg', 'Ação não permitida!');
}

if ($pagamento->imagem && File::exists(public_path('img/comprovantes/' . $pagamento->imagem))) {
    File::delete(public_path('img/comprovantes/' . $pagamento->imagem));
}

$pagamento->delete();
return redirect('/dashboard')->with('msg', 'Pagamento desfeito com sucesso!');
}
}
