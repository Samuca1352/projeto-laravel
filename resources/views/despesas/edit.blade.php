@extends('layouts.main')

@section('title', 'Editando: ' . $despesa->descricao)

@section('content')

<div id="despesa-create-container" class="col-md-6 offset-md-3">
  <h1>Editando: {{ $despesa->descricao }}</h1>
  <form action="/despesas/update/{{ $despesa->id }}" method="POST">
    @csrf
    @method('PUT') {{-- Diretiva para informar que é uma requisição de atualização --}}
    
    {{-- Campo para a Descrição da Despesa --}}
    <div class="form-group">
      <label for="descricao">Descrição:</label>
      <input type="text" class="form-control" id="descricao" name="descricao" placeholder="Ex: Conta de Luz" value="{{ $despesa->descricao }}" required>
    </div>
    
    {{-- Campo para o Valor da Despesa --}}
    <div class="form-group">
      <label for="valor">Valor (R$):</label>
      <input type="number" step="0.01" class="form-control" id="valor" name="valor" placeholder="Ex: 150.75" value="{{ $despesa->valor }}" required>
    </div>
    
    {{-- Campo para a Data de Vencimento --}}
    <div class="form-group">
      <label for="data_vencimento">Data de Vencimento:</label>
      {{-- Formata a data para o padrão Y-m-d que o input[type=date] espera --}}
      <input type="date" class="form-control" id="data_vencimento" name="data_vencimento" value="{{ $despesa->data_vencimento->format('Y-m-d') }}" required>
    </div>

    <input type="submit" class="btn btn-primary" value="Salvar Edições">
  </form>
</div>

@endsection
