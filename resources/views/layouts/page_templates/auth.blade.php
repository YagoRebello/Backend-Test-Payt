<div class="wrapper">
    <div class="stiky-top">
        @include('layouts.header.auth')
    </div>
    <div class="container-fluid" style="background-color:#FAF9F6;height:91vh;overflow-x:auto;box-shadow: inset 1px 5px 10px 1px rgba(0,0,0,0.05);">
        <div class='p-2 col row'>
            <div class="panel panel-default" style="background-color:#FAF9F6;">
                @yield('content')
                <!-- Adicione os scripts ou rodapé conforme necessário -->
            </div>
        </div>
    </div>
    @include('layouts.footer.auth')
</div>

