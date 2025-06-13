<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DespesaController;
use App\Http\Controllers\PagamentoController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Aqui é onde você pode registrar as rotas web para sua aplicação.
|
*/

// Rota principal - Agora exibe a lista de despesas públicas ou busca
Route::get('/', [DespesaController::class, 'index']);

// --- ROTAS DE DESPESAS (CRUD) ---

// Rota para a página de criação de despesa
Route::get('/despesas/create', [DespesaController::class, 'create'])->middleware("auth");

// Rota para salvar uma nova despesa no banco de dados
Route::post('/despesas', [DespesaController::class, 'store'])->middleware("auth");

// Rota para exibir os detalhes de uma despesa específica
Route::get('/despesas/{id}', [DespesaController::class, 'show']);

// Rota para a página de edição de uma despesa
Route::get('/despesas/edit/{id}', [DespesaController::class, 'edit'])->middleware("auth");

// Rota para atualizar uma despesa no banco de dados
Route::put('/despesas/update/{id}', [DespesaController::class, 'update'])->middleware("auth");

// Rota para deletar uma despesa
Route::delete('/despesas/{id}', [DespesaController::class, 'destroy'])->middleware("auth");


// --- ROTAS DO USUÁRIO E DASHBOARD ---

// Rota para o painel do usuário, onde ele vê suas despesas
Route::get('/dashboard', [DespesaController::class, 'dashboard'])->middleware("auth");

// Rota para marcar uma despesa como paga
Route::patch('/despesas/pagar/{id}', [DespesaController::class, 'marcarComoPaga'])->middleware('auth')->name('despesas.pagar');


// --- ROTA ESTÁTICA (Exemplo: Contato) ---
Route::get('/contact', function () {
    return view('contact');
});


Route::post('/pagamentos', [PagamentoController::class, 'store'])->middleware('auth')->name('pagamentos.store');
    
    // Rota para excluir um pagamento (desfazer)
Route::delete('/pagamentos/{id}', [PagamentoController::class, 'destroy'])->middleware('auth')->name('pagamentos.destroy');
    