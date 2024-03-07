@extends("layouts.default", ['title' => 'Test Backend '])
@section('content')
    <div id="content-wrapper">
        <div id="main">
            <div class="container">
                <div class="row">
                    <div class="col-md-4 col-xl-6 ">
                        <div class="card bg-c-blue order-card">
                            <div class="card-block">
                                <h6 class="m-b-20">Listagem de Redirects</h6>
                                <a href="{{ route('redirects.index') }}">
                                    <button class="button" type="button" >
                                        <span class="button__text">Visualizar</span>
                                        <span class="button__icon"><svg class="svg" fill="none" height="24" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg"><line x1="12" x2="12" y1="5" y2="19"></line><line x1="5" x2="19" y1="12" y2="12"></line></svg></span>
                                    </button>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-xl-6">
                        <div class="card bg-c-green order-card">
                            <div class="card-block">
                                <h6 class="m-b-20">Criar Redirects</h6>
                                <a href="{{ route('redirects.create') }}">
                                    <button class="button" type="button">
                                        <span class="button__text">Adicionar</span>
                                        <span class="button__icon"><svg class="svg" fill="none" height="24" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg"><line x1="12" x2="12" y1="5" y2="19"></line><line x1="5" x2="19" y1="12" y2="12"></line></svg></span>
                                    </button>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
