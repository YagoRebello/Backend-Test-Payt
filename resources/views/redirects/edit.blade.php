@extends("layouts.default", ['title' => 'Edit Redirect'])
@section('content')
<body>
    <div class="container mt-5">
        <h1>Edit Redirect</h1>
        <form action="{{ route('redirects.update', $redirect->code) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="url_destino">URL Destino</label>
                <input type="text" class="form-control" id="url_destino" name="url_destino" value="{{ old('url_destino', $redirect->url_destino) }}" required>
                @error('url_destino')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label for="status">Status</label>
                <select class="form-control" id="status" name="status">
                    <option value="1" @if($redirect->status == 1) selected @endif>Ativo</option>
                    <option value="0" @if($redirect->status == 0) selected @endif>Inativo</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>
</body>
@endsection
