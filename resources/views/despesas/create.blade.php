@extends('layouts.main')

@section('title', 'Criar Nova Despesa')

@section('content')

<div id="despesa-create-container" class="col-md-6 offset-md-3">
  <h1>Crie uma nova despesa</h1>
  {{-- Adicionado o enctype para permitir upload de arquivos --}}
  <form action="/despesas" method="POST" enctype="multipart/form-data">
    @csrf
    
    <div class="form-group">
      <label for="descricao">Descrição:</label>
      <input type="text" class="form-control" id="descricao" name="descricao" placeholder="Ex: Conta de Luz" required>
    </div>
    
    <div class="form-group">
      <label for="valor">Valor (R$):</label>
      <input type="number" step="0.01" class="form-control" id="valor" name="valor" placeholder="Ex: 150.75" required>
    </div>
    
    <div class="form-group">
      <label for="data_vencimento">Data de Vencimento:</label>
      <input type="date" class="form-control" id="data_vencimento" name="data_vencimento" required>
    </div>

    {{-- Novo campo para Categoria --}}
    <div class="form-group">
      <label for="categoria">Categoria:</label>
      <input type="text" class="form-control" id="categoria" name="categoria" placeholder="Ex: Contas de Casa">
    </div>

    <!-- {{-- Novo campo para Imagem (Comprovante) --}}
    <div class="form-group">
      <label for="imagem">Comprovante (opcional):</label>
      <input type="file" id="imagem" name="imagem" class="form-control-file">
    </div> -->

    <input type="submit" class="btn btn-primary" value="Criar Despesa">
  </form>
</div>

@endsection
