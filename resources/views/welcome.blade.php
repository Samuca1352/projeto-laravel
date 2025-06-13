@extends('layouts.main')

@section('title', 'Controle de Despesas')

@section('content')

<div id="search-container" class="col-md-12">
    <h1>Busque por uma despesa</h1>
    <form action="/" method="GET">
        <input type="text" id="search" name="search" class="form-control" placeholder="Procurar...">
    </form>
</div>
<div id="despesas-container" class="col-md-12">
    @if($search)
    <h2>Buscando por: {{ $search }}</h2>
    @else
    <h2>Próximas Despesas</h2>
    {{-- NOVO: Exibe o nome do usuário se ele estiver logado --}}
    @auth
        <h4 class="subtitle text-center my-3">Bem-vindo(a), {{ auth()->user()->name }}!</h4>
    @endauth
    <p class="subtitle">Veja as despesas com vencimento próximo</p>
    @endif
    <div id="cards-container" class="row">
        @foreach($despesas as $despesa)
        <div class="card col-md-3">
            <div class="card-body">
                <p class="card-date">{{ date('d/m/Y', strtotime($despesa->data_vencimento)) }}</p>
                <h5 class="card-title">{{ $despesa->descricao }}</h5>
                <p class="card-owner"><ion-icon name="person-outline"></ion-icon> {{ $despesa->user->name }}</p>
                <p class="card-participants">Valor: R$ {{ number_format($despesa->valor, 2, ',', '.') }}</p>

                <a href="/despesas/{{ $despesa->id }}" class="btn btn-primary">Saber mais</a>
            </div>
        </div>
        @endforeach
        @if(count($despesas) == 0 && $search)
            <p>Não foi possível encontrar nenhuma despesa com '{{ $search }}'! <a href="/">Ver todas</a></p>
        @elseif(count($despesas) == 0)
            <p>Não há despesas pendentes no momento.</p>
        @endif
    </div>
</div>

@endsection
