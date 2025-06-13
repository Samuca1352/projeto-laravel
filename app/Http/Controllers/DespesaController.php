<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Despesa;
use Illuminate\Support\Facades\File; // Importante para deletar o comprovante

class DespesaController extends Controller
{
    /**
     * Exibe a página inicial com todas as despesas ou uma busca.
     * ESTE É O MÉTODO QUE FALTAVA.
     */
    public function index()
    {
        $search = request("search");

        if ($search) {
            $despesas = Despesa::where('descricao', 'like', '%' . $search . '%')->get();
        } else {
            $despesas = Despesa::all();
        }

        return view('welcome', ["despesas" => $despesas, "search" => $search]);
    }

    /**
     * Exibe o painel do usuário com suas despesas pessoais.
     */
    public function dashboard()
    {
        $user = auth()->user();
        // Usar with('pagamento') otimiza a consulta para evitar o problema N+1
        $despesas = $user->despesas()->with('pagamento')->get();

        return view("despesas.dashboard", ["despesas" => $despesas]);
    }

    /**
     * Mostra o formulário para criar uma nova despesa.
     */
    public function create()
    {
        return view('despesas.create');
    }

    /**
     * Salva uma nova despesa no banco de dados.
     */
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

    /**
     * Mostra os detalhes de uma despesa específica.
     */
    public function show($id)
    {
        $despesa = Despesa::findOrFail($id);
        return view("despesas.show", ["despesa" => $despesa]);
    }

    /**
     * Mostra o formulário para editar uma despesa.
     */
    public function edit($id)
    {
        $user = auth()->user();
        $despesa = Despesa::findOrFail($id);

        if ($user->id != $despesa->user_id) {
            return redirect('/dashboard')->with('msg', 'Acesso negado!');
        }

        return view("despesas.edit", ["despesa" => $despesa]);
    }

    /**
     * Atualiza uma despesa existente no banco de dados.
     */
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

    /**
     * Deleta uma despesa e seu pagamento/comprovante associado.
     */
    public function destroy($id)
    {
        $despesa = Despesa::findOrFail($id);

        if (auth()->id() != $despesa->user_id) {
            return redirect('/dashboard')->with('msg', 'Ação não permitida!');
        }
        
        // Apaga o comprovante do servidor, se existir
        if ($despesa->pagamento && $despesa->pagamento->imagem) {
            $image_path = public_path('img/comprovantes/' . $despesa->pagamento->imagem);
            if (File::exists($image_path)) {
                File::delete($image_path);
            }
        }
        
        // A exclusão do pagamento é feita em cascata pelo banco de dados (onDelete('cascade'))
        // Então, só precisamos apagar a despesa.
        $despesa->delete();

        return redirect("/dashboard")->with("msg", "Despesa excluída com sucesso!");
    }
}
