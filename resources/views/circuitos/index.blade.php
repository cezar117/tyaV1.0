@extends("estructura.app")

@push('stylesheets')
<!--<link href="{{ asset('bower_components/AdminLTE/plugins/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />-->
<link href="{{ asset('bower_components/AdminLTE/plugins/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('bower_components/AdminLTE/plugins/select2/s2-docs.css') }}" rel="stylesheet" type="text/css" />

@endpush
@push('scripts')
@include('buscadores.scripts');
@endpush
@push("document.ready")
$('#form').on('submit', function() {

    if ($('#adultos').val() === 0 || undefined || null) {
        alert('seleccione adultos');
        return false;
    }

});

initFechaSalida();
initDestinos();

$(".menores").on("change", function() {
    cMenores($(this).val());
});

@endpush

@section('parallax1')
<div id="index-banner" class="parallax-container">
    <div class="section no-pad-bot">

        <div class="buscadores">
            <h4 class="">Buscar Circuitos</h4>
            <div class="row">
                <div class="contenidoBuscadores">
                    @include('circuitos.componenteBusquedaCircuitos',["from"=>"buscadores"])
                </div>
            </div>
        </div>
    </div>
    <div class="parallax"><img src="{{ asset('images/background4.jpg') }}" alt="Unsplashed background img 1"></div>
</div>

@overwrite
@section('contenido')
<div class="container">
    <div class="section">
        <div class="container">

        </div>
    </div>
</div>
@endsection