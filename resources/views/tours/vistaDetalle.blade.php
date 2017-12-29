@extends("estructura.app")

@push('stylesheets')
<style>
    #map {
        height: 300px;
        width: 100%;
    }

    #divMapUp {
        background: transparent none repeat scroll 0 0;
        height: 300px;
        margin-top: -300px;
        position: relative;
        top: 300px;
        width: 100%;
        z-index: 1;
    }
    .visible {
        display: block;
    }
    .invisible {
        display: none;
    }
</style>
@endpush
@push('scripts')

<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAA1lwpDR28QuxwEIFcKT96Z8mvVN8EnxA&callback=initMap"></script>
<script>
    var map;
    var markers = [];
    var directionsDisplay;
    var directionsService;

    function initMap() {
        directionsService = new google.maps.DirectionsService;
        directionsDisplay = new google.maps.DirectionsRenderer;
        var waypts = [];
        var inicio;
        var final;

        var uluru = {lat: 16.75279549, lng: -93.12273322};

        map = new google.maps.Map(document.getElementById('map'), {
            zoom: 17,
            center: uluru
        });
        directionsDisplay.setMap(map);

{{--dd($tour)--}}
        @foreach(objetoParseArray($tour->rutas->RutaItinerario->Destinos->DestinoRuta) as $destino)
        @if($loop->first)
        inicio={lat: {{ $destino->Latitud }}, lng: {{ $destino->Longitud }}};
        @elseif($loop->last)
            final={lat: {{ $destino->Latitud }}, lng: {{ $destino->Longitud }}};
        @else
            waypts.push({
                location: {lat: {{ $destino->Latitud }}, lng: {{ $destino->Longitud }}},
                stopover: true
            });
        @endif

        @endforeach

        directionsService.route({
            origin: inicio,
            destination: final,
            waypoints: waypts,
            optimizeWaypoints: true,
            travelMode: 'DRIVING',
            avoidTolls: false
        }, function(response, status) {
            if (status === 'OK') {
                directionsDisplay.setDirections(response);
            } else {
                console.log('Cambio a ruta en bicicleta ' + status);
                directionsService.route({
                    origin: inicio,
                    destination: final,
                    waypoints: waypts,
                    optimizeWaypoints: true,
                    travelMode: 'BICYCLING',
                    avoidTolls: false
                }, function(response, status) {
                    if (status === 'OK') {
                        directionsDisplay.setDirections(response);

                    } else {
                        console.log('Directions request failed due to ' + status);
                    }
                });
            }
        });

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

    function plegardesplegar(identificacion) {
        var elemento = document.getElementById(identificacion);
        if (elemento.className == "visible") {
            elemento.className = "invisible";
        } else {
            elemento.className = "visible";
        }
    }

    function toggleDescripcion(){
        $(".readMinus").toggle();
        $(".readMore").toggle();
        if($(".descripcionCard").hasClass("active")){
            $(".descripcionCard").removeClass("active");
        }else{
            $(".descripcionCard").addClass("active");
        }


    }

   function addToCart(form){
swal({
   title: 'Agregando al carrito..',
   text: "agregando el producto al carrito",
   type: 'info',
   showCancelButton: false,
   showConfirmButton: false,
   onOpen: function(){
    swal.showLoading();
        $.ajax({
            method: "POST",
                    url: "{{route('addToCart')}}",
                    dataType: 'JSON',
                    data: form.serialize()
            }).done(function(data) {
       swal.hideLoading();           
                console.log(data);
                if (data.estatus) {
                    Materialize.toast(data.mensaje, 4000);
                    $(".carritoBadge").html(data.num_elementos);
                    swal({title:'Agregado al Carrito!', html: data.mensaje, type:"success"});
                } else{
                    Materialize.toast(data.mensaje, 4000);
                    $(".carritoBadge").html(data.num_elementos);
                    swal({title:'Opss!', html:data.mensaje, type:"error"});
                        }
                    });
                }
            });
     }

    $(document).ready(function() {

        var slide = $('.slider-single');
  var slideTotal = slide.length - 1;
  var slideCurrent = -1;

  function slideInitial() {
    slide.addClass('proactivede');
    setTimeout(function() {
      slideRight();
    }, 500);
  }

  function slideRight() {
    if (slideCurrent < slideTotal) {
      slideCurrent++;
    } else {
      slideCurrent = 0;
    }

    if (slideCurrent > 0) {
      var preactiveSlide = slide.eq(slideCurrent - 1);
    } else {
      var preactiveSlide = slide.eq(slideTotal);
    }
    var activeSlide = slide.eq(slideCurrent);
    if (slideCurrent < slideTotal) {
      var proactiveSlide = slide.eq(slideCurrent + 1);
    } else {
      var proactiveSlide = slide.eq(0);

    }

    slide.each(function() {
      var thisSlide = $(this);
      if (thisSlide.hasClass('preactivede')) {
        thisSlide.removeClass('preactivede preactive active proactive').addClass('proactivede');
      }
      if (thisSlide.hasClass('preactive')) {
        thisSlide.removeClass('preactive active proactive proactivede').addClass('preactivede');
      }
    });
    preactiveSlide.removeClass('preactivede active proactive proactivede').addClass('preactive');
    activeSlide.removeClass('preactivede preactive proactive proactivede').addClass('active');
    proactiveSlide.removeClass('preactivede preactive active proactivede').addClass('proactive');
  }

  function slideLeft() {
    if (slideCurrent > 0) {
      slideCurrent--;
    } else {
      slideCurrent = slideTotal;
    }

    if (slideCurrent < slideTotal) {
      var proactiveSlide = slide.eq(slideCurrent + 1);
    } else {
      var proactiveSlide = slide.eq(0);
    }
    var activeSlide = slide.eq(slideCurrent);
    if (slideCurrent > 0) {
      var preactiveSlide = slide.eq(slideCurrent - 1);
    } else {
      var preactiveSlide = slide.eq(slideTotal);
    }
    slide.each(function() {
      var thisSlide = $(this);
      if (thisSlide.hasClass('proactivede')) {
        thisSlide.removeClass('preactive active proactive proactivede').addClass('preactivede');
      }
      if (thisSlide.hasClass('proactive')) {
        thisSlide.removeClass('preactivede preactive active proactive').addClass('proactivede');
      }
    });
    preactiveSlide.removeClass('preactivede active proactive proactivede').addClass('preactive');
    activeSlide.removeClass('preactivede preactive proactive proactivede').addClass('active');
    proactiveSlide.removeClass('preactivede preactive active proactivede').addClass('proactive');
  }
  var left = $('.slider-left');
  var right = $('.slider-right');
  left.on('click', function() {
    slideLeft();
  });
  right.on('click', function() {
    slideRight();
  });
  slideInitial();


    });</script>
@endpush

@section('parallax1')
@endsection
@section('contenido.container')
{{--dd($detalle)--}}
{{--dd($tour)--}}

<div id="cardDetalle1" class="card white darken-1 hoverable">
    <div class="card-content">
        <!--<div class="col s12 m4" style="position: relative; top: 15% !important;">-->
        <div class="col s12 m8 l7">

            <div class="slider-container">
                <div class="slider-content">
                    @if(isset($detalle->Galeria->Imagen))
                    @forelse($detalle->Galeria->Imagen as $key => $imagen)
                    <div class="slider-single">
                        <img class="slider-single-image" src="{{ $imagen->Nombre }}" alt="{{ $key }}" >
                    </div>
                    @empty
                    <div class="slider-single">
                        <img class="slider-single-image" src="{{ asset('images/sin_foto.png') }}" alt="{{ $key }}" >
                    </div>
                    @endforelse
                    @else
                    <div class="slider-single active">
                        <img class="slider-single-image" src="{{ $hotel->Imagen_main }}" alt="1" >
                    </div>
                    <div class="slider-single">
                        <!--<img class="slider-single-image" src="{{ asset('images/sin_foto.png') }}" alt="2" >-->
                        <img class="slider-single-image" src="{{ asset('images/sin_imagen.jpg') }}" alt="2" >
                    </div>
                    @endif
                </div>
                <a class="slider-left" href="javascript:void(0);"><i class="fa fa-arrow-left"></i></a>
                <a class="slider-right" href="javascript:void(0);"><i class="fa fa-arrow-right"></i></a>
            </div>


        </div>
        <div class="col s12 m4 l5">
            <h5>{{ $tour->Nombre }}</h5>
            <br>
            <div class="col s6">
                <div><span><b>Inicio: </b>{{ $tour->CiudadSalida }}</span></div><br>
                <div><span><b>Fin: </b>{{ $tour->CiudadLlegada }}</span></div><br>
                <div><span><b>Dificultad: </b>{{ $tour->Dificultad }}</span></div>
            </div>
            <div class="col s6">
                <div><span><b>Servicio: </b>{{ $tour->TipoServicio }}</span></div><br>
                <div><span><b>Idioma: </b>{{ $tour->Idioma }}</span></div><br>
                <div><span><b>Clasificacion: </b>{{ $tour->Categorias }}</span></div>
            </div>
            <div class="col l12 center">
                @isset($tour->Precio_final)
                @if($tour->Precio_final>0)
                    <h5 class="precioDesde">Desde<br><b> $ {{ formatoMoneda($tour->Precio_final) }} {{ $tour->Moneda }}</b></h5>
                @endif
                @endisset
            </div>
        </div>
        <div class="row"></div>
    </div>
    <div class="card-action">
        @if($detalle->Descripciones->Descripcion->Descripcioncompleta !== "")
        <div class="descripcionCard">
            <span>{!! $detalle->Descripciones->Descripcion->Descripcioncompleta !!} </span>
        </div>
        <div class="center readMore">
            <a onclick="toggleDescripcion();"><p>Leer más descripción <i class="material-icons">&#xE5CF;</i></p></a>
        </div>
        <div class="center readMinus">
            <a onclick="toggleDescripcion();"><p>Leer menos descripción <i class="material-icons">&#xE5CE;</i></p></a>
        </div>
        @else
        <div class="center"><p>Sin descripción disponible</p></div>
        @endif

    </div>
</div>

<div class="card white darken-1 hoverable">
    <div class="card-content black-text">
        <span>PAQUETES</span>
        <table class="tablaPaquetes striped">
            @forelse(objetoParseArray($paquetes) as $paquete)
            <thead>
                <tr>
                    <th></th>
                    <th>Días de operación</th>
                    <th>Precio x persona</th>
                    <th>Cantidad Seleccionada</th>
                    <th>Precio Final</th>
                </tr>
            </thead>
            <tbody>
                @foreach(objetoParseArray($paquete->TourTarifas->TourTarifas) as $tarifa)
                <tr>
                    <td><a href="#"></a></td>
                    <td>{{ $tarifa->DiasDeOperacion }}</td>
                    <td>$ {{ formatoMoneda($tarifa->TarifaPrecioAdulto) }} {{ $tour->Moneda }} Adultos</td>
                    <td> {{ $tarifa->Adultos }} Adultos</td>
                    <td>
                        <span class="precioDesde"><b>$ {{ formatoMoneda($tarifa->Total) }} {{ $tour->Moneda }}</b></span>
                        @if($tarifa->Promocion)
                        <br><a class="tooltipped" data-position="bottom" data-delay="50" data-tooltip="¡$ {{formatoMoneda($tarifa->MontoDescuento)}} {{ $tour->Moneda }} de ahorro!"><span class="precioSinDescuento">$ {{ $tarifa->PrecioOriginal }} {{ $tour->Moneda }}</span></a>
                        @endif
                    </td>
                </tr>
                @endforeach
                <tr>
                    <th colspan="2"> </th>
                    <th colspan="2"> </th>
            <form id="form{{$paquete->PackageId}}" action="{{route('addToCart')}}" method="POST">
                <input name="_token" value="{{ csrf_token() }}" type="hidden">
                <input type="hidden" name="idTour" value="{{$tour->Id}}">
                <input type="hidden" name="tipo" value="Tour">
                <input type="hidden" name="idPaquete" value="{{$paquete->PackageId}}">
            </form>
            <th colspan="1"> <a class="waves-effect waves-light btn" onclick="addToCart($('#form{{$paquete->PackageId}}'));">Agregar</a></th>
            </tr>
            </tbody>
            @empty
                <center><h4>No hay paquetes disponibles en este hotel.</h4></center>
            @endforelse
        </table>
    </div>
    <div class="card-action">
        <div class="">
            <a href="#">Footer</a>
        </div>
    </div>
</div>

<div class="card white darken-1 hoverable">
    <div class="card-content black-text">
        <div onclick="javascript:plegardesplegar('divMapUp');" class="visible" id="divMapUp"></div>
        <div id="map"></div>
    </div>
    <div class="card-action">
        <div class="">
            <a href="#">Rutas</a>
        </div>
    </div>
</div>


@endsection