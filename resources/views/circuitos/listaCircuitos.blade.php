@extends("estructura.app")
@push('stylesheets')
<link href="{{ asset('bower_components/AdminLTE/plugins/datatables/jquery.dataTables.min.css') }}" type="text/css" rel="stylesheet">
<link href="{{ asset('css/style.css') }}" type="text/css" rel="stylesheet">
<link href="{{ asset('bower_components/AdminLTE/plugins/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('bower_components/AdminLTE/plugins/select2/s2-docs.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('css/nouislider/nouislider.css') }}" rel="stylesheet" type="text/css" />
<style>
    #map {
        height: 300px;
        width: 100%;
    }
    @media (min-width: 600px) {
        .imagenListado {
            display: none;
        }
    }
</style>

@endpush
@push('scripts')
<script src="{{ asset('js/nouislider/nouislider.js') }}"></script>
<script src="{{ asset('bower_components/AdminLTE/plugins/datatables/jquery.dataTables.min.js') }}" ></script>
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAA1lwpDR28QuxwEIFcKT96Z8mvVN8EnxA&callback=initMap"></script>

@include('buscadores.scripts');
<script>
var map;
var latilong;
var listaResultados;
var directionsDisplay;
var directionsService;
var slPrecio;

window.initMap = function(){

    latilong = new google.maps.LatLng(16.2461442, -92.818142);
    map = new google.maps.Map(document.getElementById('map'));

    directionsService = new google.maps.DirectionsService;
    directionsDisplay = new google.maps.DirectionsRenderer({
        draggable: true,
        map: map
    });

    // directionsDisplay.addListener('directions_changed', function() {
    //   computeTotalDistance(directionsDisplay.getDirections());
    // });
    var destino = new google.maps.LatLng(16.7588452, -93.1277854);
    var origen = new google.maps.LatLng(16.7419474, -92.6487087);

    displayRoute(origen, destino, directionsService,
            directionsDisplay);
}

function displayRoute(origin, destination, service, display) {

    var wp2 = new google.maps.LatLng(16.7869428, -92.6875907);
    var wp1 = new google.maps.LatLng(16.7594787, -92.7216306);

    service.route({
        origin: origin,
        destination: destination,
        waypoints: [{location: wp1}, {location: wp2}],
        travelMode: 'DRIVING',
        avoidTolls: false
    }, function(response, status) {
        if (status === 'OK') {
            display.setDirections(response);
        } else {
            alert('Could not display directions due to: ' + status);
        }
    });
}

</script>

<script>

    $(document).ready(function() {

        function plegardesplegar(identificacion) {
            var elemento = document.getElementById(identificacion);
            if (elemento.className == "visible") {
                elemento.className = "invisible";
            } else {
                elemento.className = "visible";
            }
        }
        $(".btnMap").click(function() {
            map.setCenter(latilong);
            map.setZoom(10);
            $("#modalMapa").modal({
                ready: function() {
                    google.maps.event.trigger(map, 'resize');
                }
            });

        });

        listaResultados = $('#datatable').DataTable({
            sDom: 'lrtip',
            "columnDefs": [{"targets": [1], "visible": false}],
            "language": {
                "url": "{{ asset('bower_components/AdminLTE/plugins/datatables/Spanish.json') }}"
            }
        });
    });

    $('#busqueda').on('keyup', function() {
        listaResultados.search(this.value).draw();
    });

    function orderPriceMin() {
        $('#ordenPrecio').html('Precio (de menor a mayor)');
        $(".btnOrder").addClass("aplicado");
        listaResultados.column('1').order('asc').draw();
    }

    function orderPriceMax() {
        $('#ordenPrecio').html('Precio (de mayor a menor)');
        $(".btnOrder").addClass("aplicado");
        listaResultados.column('1').order('desc').draw();
    }
</script>

@endpush

@push("document.ready")

slPrecio = document.getElementById('slPrecios');
noUiSlider.create(slPrecios, {
    start: [0, 10000],
    connect: true,
    step: 100,
    range: {
        'min': 0,
        'max': 10000
    },

});

slPrecio.noUiSlider.on('update', function(values, handle) {
    var min = $(".min");
    var max = $(".max");
    var value = values[handle];

    if (handle) {
        max.html(value);
    } else {
        min.html(value);
    }
    listaResultados.draw();
});

$.fn.dataTable.ext.search.push(
    function(settings, data, dataIndex) {
        var min = parseInt($('.min').html());
        var max = parseInt($('.max').html());
        var precio = parseFloat(data[1]) || 0; // use data for the age column
        if (max >= 10000) {
            if ((min == '' && max == '') ||
                (min == '' && precio >= max) ||
                (min <= precio && '' == max) ||
                (min <= precio && (precio >= max || precio <= max))) {

                return true;
            }
            return false;
        } else {
            if ((min == '' && max == '') ||
                (min == '' && precio <= max) ||
                (min <= precio && '' == max) ||
                (min <= precio && precio <= max)) {
                return true;
            }
            return false;
        }
    }
);

$('form').on('submit', function() {

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
@section('contenido')


<div class="col xs12 m3 ">
    <div class="card-panel teal lighten-5">
        @include('circuitos.componenteBusquedacircuitos')
        <span style="visibility: hidden">--</span>
    </div>
    <ul class="collapsible" data-collapsible="expandable">


        <li class="active">
            <div class="collapsible-header active grey lighten-3" style="padding: 15px 1rem;"><i class="material-icons">filter_list</i>Filtrar resultados</div>
            <div class="collapsible-body" style="display: block; padding: 1rem;">
                <div class="center">
                    {{ count($circuitosXML) }} circuitos
                </div>

            </div>
        </li>
        <li class="active">
            <div class="collapsible-header active"><i class="material-icons">text_format</i>Por nombre</div>
            <div class="collapsible-body" style="display: block;">
                <div class="">
                    <!--<label for="busqueda" class="">Busqueda</label>-->
                    <input id="busqueda" class="browser-default form-control" type="text" placeholder="Nombre del circuito">
                </div>
            </div>
        </li>
        <li class="active">
            <div class="collapsible-header active"><i class="material-icons">&#xE227;</i>Precio</div>
            <div class="collapsible-body" style="display: block;">
                <div class="center">
                    <div class="preciosNoches"><span class="abrevMoneda">MXN </span><span class="min">0</span> a <span class="abrevMoneda">MXN </span><span class="max">10,000</span></div><br>
                    <div id="slPrecios" class="noUi-target noUi-ltr noUi-horizontal"></div>
                </div>


            </div>
        </li>
    </ul>
</div>
<div class="col xs12 m9">
    <div class="card-panel grey lighten-5">
        <div class="col s6 m4 l2">
            <b style="line-height: 35px;">Ordenar por: </b>
        </div>
        <div class="col s6 m4 l3 dividerLeft">

            <!-- Dropdown Trigger -->
            <a class='dropdown-button btn btnOrder' href='#' data-beloworigin="true" data-hover="true" data-activates='dropdown1'><label id="ordenPrecio" class="teal-text">Por precio</label> <i class="material-icons right teal-text">expand_more</i></a>

            <!-- Dropdown Structure -->
            <ul id='dropdown1' class='dropdown-content'>
                <li><a class="cursorPointer" onclick="orderPriceMin();">Menor</a></li>
                <li><a class="cursorPointer" onclick="orderPriceMax();">Mayor</a></li>
            </ul>

        </div>

        <div class="row" style="margin-bottom: 0px;"></div>




    </div>

    <table id="datatable">
        <thead>
            <tr>
                <th></th>
                <th>Precio</th>
            </tr>
        </thead>
        <tbody>
             {{-- {{dd($circuitosXML)}} --}}
            @foreach($circuitosXML as $circuito)
            <tr>
                <td>
                    <div class="">
                        <div class="card horizontal" style="margin: 0px !important;">
                            <div class="card-image" style="background-image: url('{{ asset('images/sin_imagen.jpg') }}');">
                                <img class="imagenListado"  src="{{ $circuito->Imagen_main }}">
                            </div>
                            <div class="card-stacked">
                                <div class="card-content">
                                    <div class="col s12 m8">
                                        <a href="{{ route('circuitos.busquedaDetalles') }}?circuito={{$circuito->Id}}"><h6>{{ $circuito->Nombre }}</h6></a>
                                        {!! getEstrellas($circuito->Categoria) !!}
                                        <span><strong>Inicio:</strong>{{ $circuito->CiudadSalida }}</span></br>
                                        <span><strong>Fin:</strong> {{ $circuito->CiudadLlegada }}</span></br>
                                        <span><strong>Servicio:</strong>{{ $circuito->TipoServicio }}</span></br>
                                        <span><strong>Circuito:</strong> {{ $circuito->TipoCircuito }}</span></br>
                                    </div>
                                    <div class="col s12 m4 card-image" >
                                        <div style="position: absolute;">

                                            <a class="waves-effect waves-light btn btnMap" href="#modalMapa">Ruta</a>

                                        </div>
                                        <div style="position: absolute;">
                                            <a class="waves-effect waves-light btn btnMap" href="#modalMapa">Ruta</a>


                                        </div>

                                    </div>
                                </div>
                                <div class="card-action">

                                    @if(isset($circuito->Package->PackageCircuito)) <span class="left">Desde $ {{ formatoMoneda(getPrecioMinimoPaquete(objetoParseArray($circuito->Package->PackageCircuito))) }} {{ $circuito->Moneda }}</span> @endif
                                    <a href="{{ route('circuitos.busquedaDetalles') }}?circuito={{$circuito->Id}}" class="right">Detalles</a>
                                    <!--<input type="sumbit">-->
                                </div>
                            </div>
                        </div>
                    </div>
                </td>
                <td style="visibility: hidden;">@if(isset($circuito->Package->PackageCircuito)) {{ getPrecioMinimoPaquete(objetoParseArray($circuito->Package->PackageCircuito)) }} @else 0 @endif</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div id="modalMapa" class="modal">
        <div class="modal-content">
            <h4>Rutas</h4>
            <div id="map"></div>
        </div>
        <div class="modal-footer">
            <a class="modal-action modal-close waves-effect waves-green btn-flat">Cerrar</a>
        </div>
    </div>




</div>


@endsection
