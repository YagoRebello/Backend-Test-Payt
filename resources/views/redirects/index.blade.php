@extends("layouts.default", ['title' => 'Listagem de Redirects'])
@section('content')

    <head>
        <title>Listagem de Redirects</title>
        <link rel="stylesheet" href="{{ asset('site/css/style.css') }}">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <style>
            .btn-group {
                display: flex;
            }

            .btn-group .btn {
                margin-right: 5px;
            }
        </style>
    </head>
    <body>
    <div class="container mt-5">
        <h1>Listagem de Redirects</h1>
        <a href="{{ route('redirects.create') }}" class="btn btn-primary">Criar Redirect</a>
        <a href="{{ route('home') }}" class="btn btn-secondary btn-sm float-right">Voltar para Home</a>
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        <table class="table">
            <thead>
            <tr>
                <th>Código</th>
                <th>Status</th>
                <th>URL de Destino</th>
                <th>Último Acesso</th>
                <th>Data de Criação</th>
                <th>Ações</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($redirects as $redirect)
                <tr>
                    <td>{{ $redirect->code }}</td>
                    <td>{{ $redirect->status ? 'Ativo' : 'Inativo' }}</td>
                    <td>{{ $redirect->url_destino }}</td>
                    <td>{{ $redirect->updated_at }}</td>
                    <td>{{ $redirect->created_at }}</td>
                    <td>
                        <div class="btn-group">
                            <a href="{{ route('redirects.edit', $redirect->code) }}" class="btn btn-primary">Editar</a>
                            <form action="{{ route('redirects.destroy', $redirect->code) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Deletar</button>
                            </form>
                            @if($redirect->status)
                                <a href="{{ route('redirects.redirect', $redirect->code) }}" class="btn btn-success">Redirecionar</a>
                            @endif
                            <a href="{{ route('redirects.stats', $redirect->id) }}" class="btn btn-info">Estatísticas</a>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    </body>
@endsection
