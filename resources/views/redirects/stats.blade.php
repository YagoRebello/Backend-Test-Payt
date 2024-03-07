@extends('layouts.default', ['title' => 'Estatísticas de Redirect'])

@section('content')
    <div class="container mt-5">
        <h1>Estatísticas de Redirect</h1>
        <div class="row">
            <div class="col-md-6">
                <h3>Redirect: {{ $redirect->url_destino }}</h3>
                <p><strong>Total de Acessos:</strong> {{ $totalAccesses }}</p>
                <p><strong>Acessos Únicos:</strong> {{ $uniqueAccesses }}</p>
            </div>
            <div class="col-md-6">
                <h3>Top Referrers</h3>
                <ul>
                    @foreach ($topReferrers as $referrer)
                        <li>{{ $referrer->referer }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-md-12">
                <h3>Acessos nos Últimos 10 Dias</h3>
                <table class="table">
                    <thead>
                    <tr>
                        <th>Data</th>
                        <th>Total</th>
                        <th>Únicos</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($accessesLast10Days as $access)
                        <tr>
                            <td>{{ $access->date }}</td>
                            <td>{{ $access->total }}</td>
                            <td>{{ $access->unique_accesses }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <a href="{{ route('redirects.index') }}" class="btn btn-primary">Voltar para Listagem</a>
    </div>
@endsection
