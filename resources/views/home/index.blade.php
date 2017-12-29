@extends("estructura.app")
@push('stylesheets')
<!--<link href="{{ asset('bower_components/AdminLTE/plugins/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />-->
<link href="{{ asset('bower_components/AdminLTE/plugins/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('bower_components/AdminLTE/plugins/select2/s2-docs.css') }}" rel="stylesheet" type="text/css" />

@endpush
@push('scripts')
<script src="{{ asset('bower_components/AdminLTE/plugins/select2/select2.min.js') }}" ></script>
<script src="{{ asset('bower_components/AdminLTE/plugins/select2/i18n/es.js') }}"></script>
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
    cMenores($(this).val());
});

$("#habitaciones").on("change", function() {

    cHabitaciones($(this).val());
});

$(".btn-app").on('click', function() {
    $(".btn-app").each(function() {
        $(this).removeClass("teal");
    });
    $(this).addClass("teal");
    var to = $(this).data("buscador");

    var route = "";
    if (to === "hoteles") {
        route = "{{ route('buscadorHoteles') }}";
    } else if (to === "tours") {
        route = "{{ route('buscadorTours') }}";
    } else if (to === "circuitos") {
        route = "{{ route('buscadorCircuitos') }}";
    } else if (to === "actividades") {
        route = "{{ route('buscadorActividades') }}";
    }
    $.ajax({
        method: "GET",
        url: route,
        success: function(data) {
            $(".contenidoBuscadores").html(data);
            initDestinos();
            if (to === "hoteles") {
                initTwoInputs();
            } else {
                initFechaSalida();
            }

            $(".menores").on("change", function() {
                cMenores($(this).val());
            });
            $("#habitaciones").on("change", function() {

                cHabitaciones($(this).val());
            });

        }
    });

});


@endpush

@push("scripts");
<script type="text/javascript">
$(document).ready(function(){

      var x= {{ session('data') !== null ? session('data') : 0 }} ;
      if(x == 1){
        $('#modal1').modal('open');
      }
});
</script>
@endpush
@section('parallax1')

    {{-- <div class="row hide" id="error">
      <div class="col s3 m12">
        <div class="card-panel  red lighten-3">
          <span class="black-text">
          <h3>Opss! no se encontraron resultados...</h3>
          </span>
        </div>
      </div>
    </div> --}}
      <!-- Modal Trigger -->
  {{-- <a class="waves-effect waves-light btn" href="#modal1">Modal</a> --}}

  <!-- Modal Structure -->
  <div id="modal1" class="modal bottom-sheet">
      <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat">cerrar</a>
    <div class="modal-content  red lighten-4">
      <h4>Opss!! no se encontraron resultados...</h4>
    </div>
   
  </div>

<div id="index-banner" class="parallax-container">
    <div class="section no-pad-bot">

        <div class="buscadores">
            <div class="row left">
                <a class="btn btn-app teal" data-buscador="hoteles">
                    <i class="fa fa-home"></i> Hoteles
                </a>
                <a class="btn btn-app" data-buscador="tours">
                    <i class="fa fa-map-o"></i> Tours
                </a>
                <a class="btn btn-app" data-buscador="circuitos">
                    <i class="fa fa-map-o"></i> Circuitos
                </a>
                <a class="btn btn-app" data-buscador="actividades">
                    <i class="fa fa-map-o"></i> Actividades
                </a>
            </div>

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
@endsection
@section('contenido.container')
@endsection

@section('contenido.ofertas')
 @include('home.ofertas')
@endsection