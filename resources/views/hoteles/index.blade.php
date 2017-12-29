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

initTwoInputs();
initDestinos();

$(".menores").on("change", function() {
    cMenores($(this).val(),"buscadores");
});

$("#habitaciones").on("change", function() {
    cHabitaciones($(this).val(),"buscadores");
});




@endpush

@section('parallax1')
<div id="index-banner" class="parallax-container">
    <div class="section no-pad-bot">

        <div class="buscadores">
            <h4 class="col wizard-title">Buscar Hoteles</h4>
            <div class="row">
                <div class="contenidoBuscadores">
                    @include('hoteles.componenteBusquedaHoteles',["from"=>"buscadores"])
                </div>
            </div>

        </div>

    </div>
    <div class="parallax"><img src="{{ asset('images/background5.jpg') }}" alt="Unsplashed background img 1"></div>
</div>

@overwrite
@section('contenido')
<div class="container">
    <div class="section">
        <div class="container">
            <div class="col offset-s4 s12 m12 ">

            </div>

        </div>
    </div>
</div>
@endsection