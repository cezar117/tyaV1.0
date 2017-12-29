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
var filtro;
var slPrecioNoche;

function initMap() {
    var uluru = {lat: 16.75279549, lng: -93.12273322};

    map = new google.maps.Map(document.getElementById('map'), {
        zoom: 17,
        center: uluru
    });
    // Adds a marker at the center of the map.
    addMarker(uluru);
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

function filterStars(estrellas) {
    listaResultados.column(2).search("[" + estrellas.join() + "]", true, false);
    listaResultados.column(2).order('desc').draw();
}


$(document).ready(function() {

    //google.maps.event.trigger(map, 'resize');
    $('#selectPrecio').material_select();
    $(".btnMap").click(function() {
        var lati = parseFloat($(this).data("latitud"));
        var longi = parseFloat($(this).data("longitud"));
        var localizacion = {lat: lati, lng: longi};

//        console.log(lati);
//        console.log(longi);
        map.setZoom(17);
        map.setCenter(localizacion);
        deleteMarkers();
        addMarker(localizacion);
        google.maps.event.trigger(map, 'resize');
        $("#modalMapa").modal({
            ready: function() {
                google.maps.event.trigger(map, 'resize');
            }
        });

    });

//
//    'l' - Length changing
//    'f' - Filtering input
//    't' - The table!
//    'i' - Information
//    'p' - Pagination
//    'r' - pRocessing

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
$('.cbStars').on('click', function() {
    if ($(".cbStars").is(":checked")) {
        var filtroEstrellas = [];
        $("#allStar").prop("disabled", false);
        $("#allStar").prop("checked", false);
        $('.cbStars').each(function() {
            if ($(this).is(":checked")) {
                filtroEstrellas.push(parseInt($(this).data("stars")));
            }

        });
        filterStars(filtroEstrellas);
    } else {
        $(".cbAllStars").click();
    }

});
$('.cbAllStars').on('click', function() {
    var filtroEstrellas = [1, 2, 3, 4, 5];
    $("#allStar").prop("disabled", true);
    $("#allStar").prop("checked", true);
    $(".cbStars").prop("checked", false);
    filterStars(filtroEstrellas);
});
//$('#selectPrecio').on('change', function() {
//
//    if (this.value == 1) {
//        listaHoteles.column('1').order('asc').draw();
//    } else {
//        listaHoteles.column('1').order('desc').draw();
//    }
//
//
//});

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

slPrecioNoche= document.getElementById('precioNoches');
noUiSlider.create(slPrecioNoche, {
start: [0, 10000],
connect: true,
step: 100,
range: {
'min': 0,
'max': 10000
},

});

slPrecioNoche.noUiSlider.on('update', function( values, handle ) {
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


$('#form').on('submit', function() {

if ($('#adultos').val() === 0 || undefined || null) {
alert('seleccione adultos');
return false;
}

});

initFechaSalida();
initDestinos();

$(".menores").on("change", function() {
cMenores($(this).val(),"resultados");

});

$("#habitaciones").on("change", function() {
cHabitaciones($(this).val(),"resultados");
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
cMenores($(this).val(),"resultados");
});
$("#habitaciones").on("change", function() {

cHabitaciones($(this).val(),"resultados");
});

}
});

});


@endpush




@section('contenido')


<div class="col s12 m12 l3 ">
    <div class="card-panel teal lighten-5">
        @include('actividades.componenteBusquedaActividades')
        <span style="visibility: hidden">--</span>
    </div>

    <ul class="collapsible" data-collapsible="expandable">


        <li class="active">
            <div class="collapsible-header active grey lighten-3" style="padding: 15px 1rem;"><i class="material-icons">filter_list</i>Filtrar resultados</div>
            <div class="collapsible-body" style="display: block; padding: 1rem;">
                <div class="center">
                    {{ count($actividadesXML) }} actividades
                </div>

            </div>
        </li>
        <li class="active">
            <div class="collapsible-header active"><i class="material-icons">text_format</i>Por nombre</div>
            <div class="collapsible-body" style="display: block;">
                <div class="">
                    <!--<label for="busqueda" class="">Busqueda</label>-->
                    <input id="busqueda" class="browser-default form-control" type="text" placeholder="Nombre del hotel">
                </div>
            </div>
        </li>
        <li class="active">
            <div class="collapsible-header active"><i class="material-icons">&#xE227;</i>Precio</div>
            <div class="collapsible-body" style="display: block;">
                <div class="center">
                    <div class="preciosNoches"><span class="abrevMoneda">MXN </span><span class="min">0</span> a <span class="abrevMoneda">MXN </span><span class="max">10,000</span></div><br>
                    <div id="precioNoches" class="noUi-target noUi-ltr noUi-horizontal"></div>
                </div>


            </div>
        </li>

        <!--        <li class="">
                    <div class="collapsible-header"><i class="material-icons">place</i>Second</div>
                    <div class="collapsible-body" style="display: none;"><span>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</span></div>
                </li>
                <li class="">
                    <div class="collapsible-header"><i class="material-icons">whatshot</i>Third</div>
                    <div class="collapsible-body" style="display: none;"><span>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</span></div>
                </li>-->
    </ul>
</div>
<div class="col s12 m12 l9">
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

    <table id="datatable" style="width: 100%">
        <thead>
            <tr>
                <th></th>
                <th><!--Preciov--></th>
            </tr>
        </thead>
        <tbody>
            {{--dd($actividadesXML)--}}
            @foreach($actividadesXML as $actividad)
            <tr>
                <td>
                    <div class="">
                        <div class="card horizontal" style="margin: 0px !important;">
                            <div class="card-image waves-effect waves-block waves-light" style="background-image: url('{{ asset('images/sin_imagen.jpg') }}');">
                                <!--<a href="#" class="btn-floating btn-large btn-price waves-effect waves-light  pink accent-2">$189</a>-->

                                <img class="imagenListado" src="@if($actividad->Galeria->Imagen->Nombre) {{ $actividad->Galeria->Imagen->Nombre }} @else images/snImagen.jpg @endif  ">
                            </div>
                            <div class="card-stacked">
                                <div class="card-content">
                                    <div class="col s12 m12 l8">
                                        <a href="{{ route('actividades.busquedaDetalles') }}?actividad={{$actividad->Id}}"><h6>{{ $actividad->Nombre }}</h6></a>

                                        <p class="descripcionListado">{!! str_limit(quitarHTML($actividad->Descripcion), $limit = 250, $end = '...') !!}</p>
                                        {{--    <p class="truncate">{!! $actividad->Descripcion !!}</p>--}}

                                        {{--<span><b>Para: </b>{{getListaByComas($actividad->ActividadPara)}}</span><br>--}}
                                        @isset($actividad->ActividadPara->string)
                                        <span><b>Para: </b>
                                            @if(count($actividad->ActividadPara->string) > 1)
                                            @foreach(objetoParseArray($actividad->ActividadPara->string) as $para)
                                            {{$para}}@if (!$loop->last),@endif
                                            @endforeach
                                            @else
                                            {{$actividad->ActividadPara->string}}
                                            @endif
                                        </span><br>
                                        @endisset
                                        <span><b>Inicio: </b>{{$actividad->Ciudad}}</span><br>

                                        @isset($actividad->Duraciones->string)
                                        <span><b>Duración: </b>
                                            @if(count($actividad->Duraciones->string)>1)
                                            @foreach(objetoParseArray($actividad->Duraciones->string) as $duracion)
                                            {{$duracion}},
                                            @endforeach
                                            @else
                                            {{$actividad->Duraciones->string}}
                                            @endif


                                        </span><br>
                                        @endisset

                                    </div>
                                    <div class="col s12 m12 l4 card-image">

                                        <!--                                        <a class="waves-effect waves-light btn" href="#modalMapa">Modal</a>-->
                                        @if($actividad->Latitud > 0 and $actividad->Longitud>0)
                                        <a class="waves-effect waves-light btnMap" href="#modalMapa" data-latitud="{{ $actividad->Latitud }}" data-longitud="{{ $actividad->Longitud }}">
                                            <img src="https://maps.googleapis.com/maps/api/staticmap?center={{ $actividad->Latitud }},{{ $actividad->Longitud }}&zoom=18&size=640x400&markers=color:red%7Clabel:A%7C{{ $actividad->Latitud }},{{ $actividad->Longitud }}&key=AIzaSyAA1lwpDR28QuxwEIFcKT96Z8mvVN8EnxA">
                                        </a>
                                        @endif
                                    </div>

                                </div>
                                <div class="card-action">

                                    <span class="left">Desde $ {{ formatoMoneda($actividad->Precio_final) }} {{ $actividad->Moneda }}</span>
                                    <a href="{{ route('actividades.busquedaDetalles') }}?actividad={{$actividad->Id}}" class="right">SELECCIONAR</a>
                                    <!--<input type="sumbit">-->
                                </div>
                            </div>
                        </div>
                    </div>
                </td>
                <td style="visibility: hidden;">{{ $actividad->Precio_final }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div id="modalMapa" class="modal">
        <div class="modal-content">
            <h4>Ubicacion</h4>
            <div id="map"></div>
        </div>
        <div class="modal-footer">
            <a class="modal-action modal-close waves-effect waves-green btn-flat">Cerrar</a>
        </div>
    </div>




</div>


@endsection
