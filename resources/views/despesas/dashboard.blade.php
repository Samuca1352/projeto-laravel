@extends('layouts.main')

@section('title', 'Dashboard')

@section('content')

{{-- Estilo CSS para garantir que o botão de upload fica bonito --}}
<style>
    .custom-file-upload-container {
        display: flex;
        align-items: center;
    }
    .custom-file-upload {
        position: relative;
        overflow: hidden;
        display: inline-block;
    }
    .custom-file-upload input[type="file"] {
        position: absolute;
        left: 0;
        top: 0;
        opacity: 0;
        width: 100%;
        height: 100%;
        cursor: pointer;
    }
    .file-name {
        font-size: 0.8rem;
        color: #6c757d;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 120px;
        display: inline-block;
        vertical-align: middle;
        margin-left: 8px;
    }
</style>

<div class="col-md-10 offset-md-1 dashboard-title-container">
    <h1>Minhas Despesas</h1>
</div>
<div class="col-md-10 offset-md-1 dashboard-despesas-container">
    @if(count($despesas) > 0)
    <table class="table">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Descrição</th>
                <th scope="col">Valor (R$)</th>
                <th scope="col">Vencimento</th>
                <th scope="col">Status</th>
                <th scope="col" class="text-center">Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach($despesas as $despesa)
                <tr>
                    <td scropt="row">{{ $loop->index + 1 }}</td>
                    <td><a href="/despesas/{{ $despesa->id }}">{{ $despesa->descricao }}</a></td>
                    <td>R$ {{ number_format($despesa->valor, 2, ',', '.') }}</td>
                    <td>{{ date('d/m/Y', strtotime($despesa->data_vencimento)) }}</td>
                    <td>
                        @if($despesa->pagamento)
                            <span class="badge badge-success">Paga</span>
                        @else
                            <span class="badge badge-warning">Pendente</span>
                        @endif
                    </td>
                    <td class="d-flex justify-content-center align-items-center">
                        @if($despesa->pagamento)
                            {{-- Botão Desfazer e Link do Comprovante --}}
                            <form action="{{ route('pagamentos.destroy', $despesa->pagamento->id) }}" method="POST" class="mr-2">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-secondary btn-sm">Desfazer</button>
                            </form>
                            @if($despesa->pagamento->imagem)
                                <a href="/img/comprovantes/{{ $despesa->pagamento->imagem }}" target="_blank" class="btn btn-light btn-sm">Ver Comprovante</a>
                            @endif
                        @else
                            {{-- Formulário para Pagar com Comprovante --}}
                            <form action="{{ route('pagamentos.store') }}" method="POST" enctype="multipart/form-data" class="custom-file-upload-container">
                                @csrf
                                <input type="hidden" name="despesa_id" value="{{ $despesa->id }}">
                                
                                <div class="custom-file-upload mr-2">
                                    <label for="imagem-{{ $despesa->id }}" id="label-imagem-{{ $despesa->id }}" class="btn btn-outline-secondary btn-sm mb-0">
                                        <ion-icon name="cloud-upload-outline"></ion-icon> Anexar
                                    </label>
                                    <input type="file" name="imagem" id="imagem-{{ $despesa->id }}" onchange="updateFileName(this)">
                                </div>
                                <span class="file-name" id="file-name-{{ $despesa->id }}"></span>

                                <button type="submit" class="btn btn-success btn-sm ml-2">Pagar</button>
                            </form>
                        @endif
                        <a href="/despesas/edit/{{ $despesa->id }}" class="btn btn-info btn-sm ml-2">Editar</a>

                        {{-- BOTÃO EXCLUIR ADICIONADO AQUI --}}
                        <form action="/despesas/{{ $despesa->id }}" method="POST" class="ml-2">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Excluir</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <p>Você ainda não tem despesas cadastradas, <a href="/despesas/create">criar nova despesa</a>.</p>
    @endif
</div>

{{-- Adicionando um pouco de JavaScript para o nome do ficheiro e feedback visual --}}
@push('scripts')
<script>
    function updateFileName(input) {
        const fileId = input.id.split('-').pop();
        const fileNameSpan = document.getElementById('file-name-' + fileId);
        const label = document.getElementById('label-imagem-' + fileId);

        if (input.files.length > 0) {
            fileNameSpan.textContent = input.files[0].name;
            label.classList.remove('btn-outline-secondary');
            label.classList.add('btn-success');
            label.innerHTML = '<ion-icon name="checkmark-circle-outline"></ion-icon> Anexado!';
        } else {
            fileNameSpan.textContent = '';
            label.classList.add('btn-outline-secondary');
            label.classList.remove('btn-success');
            label.innerHTML = '<ion-icon name="cloud-upload-outline"></ion-icon> Anexar';
        }
    }
</script>
@endpush

@endsection
