@extends('layouts.main')

{{-- O título da página agora usa a descrição da despesa --}}
@section('title', $despesa->descricao)

@section('content')

<div class="col-md-10 offset-md-1">
    <div class="row">
        <div id="info-container" class="col-md-6">
            <h1>{{ $despesa->descricao }}</h1>
            <p class="despesa-info"><ion-icon name="calendar-outline"></ion-icon> <strong>Vencimento:</strong> {{ date('d/m/Y', strtotime($despesa->data_vencimento)) }}</p>
            <p class="despesa-info"><ion-icon name="cash-outline"></ion-icon> <strong>Valor:</strong> R$ {{ number_format($despesa->valor, 2, ',', '.') }}</p>
            <p class="despesa-info"><ion-icon name="person-outline"></ion-icon> <strong>Dono da despesa:</strong> {{ $despesa->user->name }}</p>
            
            <p class="despesa-info">
                <ion-icon name="information-circle-outline"></ion-icon> 
                <strong>Status:</strong>
                @if($despesa->status == 'Paga')
                    <span class="badge badge-success">Paga</span>
                    @if($despesa->data_pagamento)
                        (em {{ date('d/m/Y', strtotime($despesa->data_pagamento)) }})
                    @endif
                @else
                    <span class="badge badge-warning">Pendente</span>
                @endif
            </p>

            {{-- O botão de "Confirmar Presença" foi removido, pois não se aplica --}}

        </div>
        <div class="col-md-12" id="description-container">
            <h3>Sobre a despesa:</h3>
            {{-- Aqui você pode adicionar mais detalhes se a despesa tiver um campo de descrição longo.
                 Se não, esta seção pode ser removida. Por enquanto, vou deixar um texto padrão. --}}
            <p class="despesa-description">Esta é uma despesa referente a: {{ $despesa->descricao }}.</p>
        </div>
    </div>
</div>

@endsection
