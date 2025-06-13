<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Despesa;
use Illuminate\Support\Facades\File;

class DespesaController extends Controller
{
    /**
     * Exibe a página inicial APENAS com despesas pendentes.
     */
    public function index()
    {
        $search = request("search");

        if ($search) {
            // Se houver busca, procura nas despesas pendentes
            $despesas = Despesa::whereDoesntHave('pagamento')
                                ->where('descricao', 'like', '%' . $search . '%')
                                ->get();
        } else {
            // Caso contrário, pega todas as despesas que não têm pagamento
            $despesas = Despesa::whereDoesntHave('pagamento')->get();
        }

        return view('welcome', ["despesas" => $despesas, "search" => $search]);
    }

    /**
     * Mostra os detalhes de uma despesa específica.
     */
    public function show($id)
    {
        // Usar with('pagamento', 'user') otimiza a consulta, já carregando os dados relacionados
        $despesa = Despesa::with('pagamento', 'user')->findOrFail($id);
        return view("despesas.show", ["despesa" => $despesa]);
    }

    public function dashboard()
    {
        $user = auth()->user();
        $despesas = $user->despesas()->with('pagamento')->get();

        return view("despesas.dashboard", ["despesas" => $despesas]);
    }

    public function create()
    {
        return view('despesas.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'descricao' => 'required|string|max:255',
            'valor' => 'required|numeric|min:0',
            'data_vencimento' => 'required|date',
            'categoria' => 'nullable|string|max:100',
        ]);

        $data = $request->all();
        $data['user_id'] = auth()->id();
        Despesa::create($data);

        return redirect('/dashboard')->with("msg", "Despesa criada com sucesso!");
    }

    public function edit($id)
    {
        $user = auth()->user();
        $despesa = Despesa::findOrFail($id);

        if ($user->id != $despesa->user_id) {
            return redirect('/dashboard')->with('msg', 'Acesso negado!');
        }

        return view("despesas.edit", ["despesa" => $despesa]);
    }

    public function update(Request $request, $id)
    {
        $despesa = Despesa::findOrFail($id);

        if(auth()->id() != $despesa->user_id) {
            return redirect('/dashboard')->with('msg', 'Ação não permitida!');
        }

        $request->validate([
            'descricao' => 'required|string|max:255',
            'valor' => 'required|numeric|min:0',
            'data_vencimento' => 'required|date',
            'categoria' => 'nullable|string|max:100',
        ]);

        $despesa->update($request->all());

        return redirect("/dashboard")->with("msg", "Despesa editada com sucesso!");
    }

    public function destroy($id)
    {
        $despesa = Despesa::findOrFail($id);

        if (auth()->id() != $despesa->user_id) {
            return redirect('/dashboard')->with('msg', 'Ação não permitida!');
        }
        
        if ($despesa->pagamento && $despesa->pagamento->imagem) {
            $image_path = public_path('img/comprovantes/' . $despesa->pagamento->imagem);
            if (File::exists($image_path)) {
                File::delete($image_path);
            }
        }
        
        $despesa->delete();

        return redirect("/dashboard")->with("msg", "Despesa excluída com sucesso!");
    }
}
