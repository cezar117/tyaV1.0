@extends("estructura.app")
@push('stylesheets')
<link href="{{ asset('bower_components/AdminLTE/plugins/datatables/jquery.dataTables.min.css') }}" type="text/css" rel="stylesheet">
<link href="{{ asset('bower_components/AdminLTE/plugins/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('bower_components/AdminLTE/plugins/select2/s2-docs.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('css/nouislider/nouislider.css') }}" rel="stylesheet" type="text/css" />

<style>
    #map {
        height: 300px;
        width: 100%;
    }
</style>

@endpush
@push('scripts')
<script src="{{ asset('js/nouislider/nouislider.js') }}"></script>
<script src="{{ asset('bower_components/AdminLTE/plugins/datatables/jquery.dataTables.min.js') }}" ></script>
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAA1lwpDR28QuxwEIFcKT96Z8mvVN8EnxA&callback=initMap"></script>
<script src="{{ asset('bower_components/AdminLTE/plugins/select2/select2.min.js') }}" ></script>
<script src="{{ asset('bower_components/AdminLTE/plugins/select2/i18n/es.js') }}"></script>
<script>
var map;
var markers = [];
var listaResultados;
var directionsDisplay;
var directionsService;
var slPrecio;

function initMap() {
    directionsService = new google.maps.DirectionsService;
    directionsDisplay = new google.maps.DirectionsRenderer;


    var uluru = {lat: 16.75279549, lng: -93.12273322};

    map = new google.maps.Map(document.getElementById('map'), {
        zoom: 17,
        center: uluru
    });
    directionsDisplay.setMap(map);
    // Adds a marker at the center of the map.
    //addMarker(uluru);
}

function addMarker(location) {
    var marker = new google.maps.Marker({
        position: location,
        map: map
    });
    markers.push(marker);
}

// Sets the map on all markers in the array.
function setMapOnAll(map) {
    for (var i = 0; i < markers.length; i++) {
        markers[i].setMap(map);
    }
}

// Removes the markers from the map, but keeps them in the array.
function clearMarkers() {
    setMapOnAll(null);
}

// Shows any markers currently in the array.
function showMarkers() {
    setMapOnAll(map);
}

// Deletes all markers in the array by removing references to them.
function deleteMarkers() {
    clearMarkers();
    markers = [];
}
$(document).ready(function() {


    $(".btnMap").click(function() {
        var destinos = $(this).data("mapa");
        var waypts = [];

        deleteMarkers();
        for (var destino in destinos["Rutas"]) {
            var lati = parseFloat(destinos["Rutas"][destino]["Latitud"]);
            var longi = parseFloat(destinos["Rutas"][destino]["Longitud"]);
            var localizacion = {lat: lati, lng: longi};
            waypts.push({
                location: localizacion,
                stopover: true
            });
        }
        var inicio = waypts[0]["location"];
        var final = waypts[waypts.length - 1]["location"];
        waypts.shift();
        waypts.pop();

        directionsService.route({
            origin: inicio,
            destination: final,
            waypoints: waypts,
            optimizeWaypoints: true,
            travelMode: 'DRIVING'
        }, function(response, status) {
            if (status === 'OK') {
                directionsDisplay.setDirections(response);
            } else {
                directionsService.route({
                    origin: inicio,
                    destination: final,
                    waypoints: waypts,
                    optimizeWaypoints: true,
                    travelMode: 'BICYCLING'
                }, function(response, status) {
                    if (status === 'OK') {
                        directionsDisplay.setDirections(response);

                    } else {
                        console.log('Directions request failed due to ' + status);
                    }
                });
            }
        });
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
@include('buscadores.scripts');
@endpush

@push("document.ready")

slPrecio= document.getElementById('slPrecios');
noUiSlider.create(slPrecios, {
start: [0, 10000],
connect: true,
step: 100,
range: {
'min': 0,
'max': 10000
},

});

slPrecio.noUiSlider.on('update', function( values, handle ) {
var min=$(".min");var max=$(".max");
var value = values[handle];

if ( handle ) {
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
                (min <= precio && (precio >= max || precio <= max) ) ) {

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
        @include('tours.componenteBusquedaTours')
        <span style="visibility: hidden">--</span>
    </div>
    <ul class="collapsible" data-collapsible="expandable">


        <li class="active">
            <div class="collapsible-header active grey lighten-3" style="padding: 15px 1rem;"><i class="material-icons">filter_list</i>Filtrar resultados</div>
            <div class="collapsible-body" style="display: block; padding: 1rem;">
                <div class="center">
                    {{ count($toursXML) }} tours
                </div>

            </div>
        </li>
        <li class="active">
            <div class="collapsible-header active"><i class="material-icons">text_format</i>Por nombre</div>
            <div class="collapsible-body" style="display: block;">
                <div class="">
                    <!--<label for="busqueda" class="">Busqueda</label>-->
                    <input id="busqueda" class="browser-default form-control" type="text" placeholder="Nombre del tour">
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

            @foreach($toursXML as $tour)
            <tr>
                <td>
                    <div class="">
                        <div class="card horizontal" style="margin: 0px !important;">
                            <div class="card-image" style="background-image: url('{{ asset('images/sin_imagen.jpg') }}');">
                                <img class="imagenListado" src="@if($tour->Imagen) {{ $tour->Imagen }} @else images/snImagen.jpg @endif  ">
                            </div>
                            <div class="card-stacked">
                                <div class="card-content">
                                    <div class="col s12 m8">
                                        <a href="{{ route('tours.busquedaDetalles') }}?tour={{$tour->Id}}"><h6>{{ $tour->Nombre }}</h6></a>

                                        <p>{{ $tour->Categorias }}</p>

                                        <span><b>Inicio: </b>{{ $tour->CiudadSalida }}</span><br>
                                        <span><b>Fin: </b>{{ $tour->CiudadLlegada }}</span><br>
                                        <span><b>Servicio: </b>{{ $tour->TipoServicio }}</span>
                                    </div>
                                    <div class="col s12 m4 card-image">

                                        <span><b>Nivel: </b>{{ $tour->Dificultad }}</span><br>

                                        <a class="waves-effect waves-light btn btnMap" href="#modalMapa" data-mapa='{"Rutas": [@foreach(objetoParseArray($tour->rutas->RutaItinerario->Destinos->DestinoRuta) as $destino) {"Nombre":"{{ $destino->DestinoNombre }}","Latitud":"{{ $destino->Latitud }}","Longitud":"{{ $destino->Longitud }}"}@if($loop->last)@else,@endif @endforeach]}'>Ruta</a>

                                    </div>

                                </div>
                                <div class="card-action">
                                    @if(isset($tour->Precio_final)) <span class="left precioDesde">Desde <b>$ {{ formatoMoneda($tour->Precio_final) }} {{ $tour->Moneda }}</b></span>@endif

                                    <a href="{{ route('tours.busquedaDetalles') }}?tour={{$tour->Id}}" class="right">SELECCIONAR</a>
                                    <!--<input type="sumbit">-->
                                </div>
                            </div>
                        </div>
                    </div>
                </td>
                <td style="visibility: hidden;">@if(isset($tour->Precio_final)) {{ $tour->Precio_final }} @else 0 @endif</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Modal Structure -->
    <div id="modalMapa" class="modal bottom-sheet">
        <div class="modal-content">
            <center><h5>Ubicacion</h5></center>
            <div id="map"></div>
        </div>
        <div class="modal-footer">
            <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat">Cerrar</a>
        </div>
    </div>




</div>


@endsection
