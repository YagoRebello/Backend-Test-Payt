@extends("layouts.default", ['title' => 'Criar Redirect'])
@section('content')

    <head>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    </head>

    <body>
    <div class="container mt-5">
        <h1>Criar Redirect</h1>
        <div class="text-right mb-3">
            <a href="{{ route('redirects.index') }}" class="btn btn-secondary">Voltar para Listagem</a>
        </div>
        <form action="{{ route('redirects.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="url_destino">URL Destino</label>
                <input type="text" class="form-control" id="url_destino" name="url_destino" value="{{ old('url_destino') }}" required>
                @error('url_destino')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary float-right">Salvar</button>
        </form>
    </div>
    </body>
@endsection
