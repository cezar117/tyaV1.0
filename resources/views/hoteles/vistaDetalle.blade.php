@extends("estructura.app")

@push('stylesheets')
<style>
    #mapa {
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

<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAA1lwpDR28QuxwEIFcKT96Z8mvVN8EnxA"></script>
<script>
    var map;
    var markers = [];
    function initMap() {
    var uluru = {lat: {{ $detalle -> Latitud }}, lng: {{ $detalle -> Longitud }} };
    map = new google.maps.Map(document.getElementById('mapa'), {
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

    function plegardesplegar(identificacion) {
    var elemento = document.getElementById(identificacion);
    if (elemento.className == "visible") {
    elemento.className = "invisible";
    } else {
    elemento.className = "visible";
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
                },
                allowOutsideClick: false
            });
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

    $(document).ready(function() {
    $('#galeria').carousel({fullWidth: true});
    $('#galeria').carousel({fullWidth: true});
        $("#aUbicacion").one("click", function(){
        initMap();
        });




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

<div class="card white darken-1 hoverable">
    <div class="card-content">

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

            <h5>{{ $detalle->Nombre }}</h5>
            <span>{{$hotel->Direccion}}</span>
            <div class="col l12">{!! getEstrellas($hotel->Categoria,$hotel->CategoriaString,"") !!}</div>
            <div class="col s12 m6">
                <div><b>Lugar: </b>{{ $hotel->Ciudad }}</div><br>
                <div><span><b>Entrada: </b> {{ getCheckIn() }}</span></div><br>
            </div>
            <div class="col s12 m6">
                <div><b>Noches: </b>{{ getNumeroNoches() }}</div><br>
                <div><b>Salida: </b> {{ getCheckOut() }}</div><br>
            </div>
            <div class="col s12 m12">
                <div><b>Amenidades: </b> @isset($detalle->Amenidades->Amenidad){{ getAmenidadesMin( $detalle->Amenidades->Amenidad) }}@endisset</div><br>
                <div class="center iconosAmenidades">{!!getIconosAmenidades($detalle->Amenidades->Amenidad)!!}</div>

            </div>
            <div class="col l12 center">
                @if($hotel->Precio_final>0)
                <h5 class="precioDesde">Desde<br><b> $ {{ formatoMoneda($hotel->Precio_final) }} {{ $hotel->Moneda }}</b></h5>
                @endif
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
                    <th>Habitación</th>
                    <th>Régimen</th>
                    <th>Datos</th>
                    <th>Por noche</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>

                @foreach(objetoParseArray($paquete->Habitaciones->HabitacionRespuesta) as $habitacion)
                <tr>
                    <td>{{ $habitacion->TipoHabitacion }}</td>
                    <td>{{ $habitacion->Regimen->TipoDeRegimen }}</td>
                    <td>
                        @if($habitacion->Promocion)
                        <a class="tooltipped" data-position="top" data-delay="50" data-tooltip="{{$habitacion->ProcentajeDescuento}} % de descuento">
                        <div class="chip green white-text">
                            ¡Oferta!
                        </div></a><br>
                        @endif
                        @isset($habitacion->PoliticasCancelacion->politica)
                            @if(count($habitacion->PoliticasCancelacion->politica)>0)
                                <a class="tooltipped" data-position="top" data-delay="50" data-html="true" data-tooltip=" @foreach(objetoParseArray($habitacion->PoliticasCancelacion->politica) as $politica) {{ getTextoPoliticas($politica) }}<br> @endforeach">Politicas de cancelación</a>
                            @endif
                        @endisset
                    </td>
                    <td><span class="precioDesde"><b>$ {{ formatoMoneda($habitacion->TarifaPorNoche) }} {{ $hotel->Moneda }}</b></span>
                        @if($habitacion->Promocion)
                        <br><a class="tooltipped" data-position="bottom" data-delay="50" data-tooltip="{{$habitacion->ProcentajeDescuento}} % de descuento"><span class="precioSinDescuento">$ {{ getTarifaNocheSD($habitacion->PrecioOriginal) }} {{ $hotel->Moneda }}</span></a>
                        @endif
                    </td>
                    <td><span class="precioDesde"><b>$ {{ formatoMoneda($habitacion->TarifaTotal) }} {{ $hotel->Moneda }}</b></span>
                        @if($habitacion->Promocion)
                        <br><a class="tooltipped" data-position="bottom" data-delay="50" data-tooltip="¡$ {{formatoMoneda($habitacion->MontoDescuento)}} {{ $hotel->Moneda }} de ahorro!"><span class="precioSinDescuento">$ {{ $habitacion->PrecioOriginal }} {{ $hotel->Moneda }}</span></a>
                        @endif
                    </td>
                </tr>
                @endforeach
                <tr>
                    <th colspan="1"><a href="#">Ver Comentarios</a> </th>
                    <th colspan="1"> TOTAL: $ {{ formatoMoneda($paquete->PricePackage) }}</th>

            <form id="form{{$paquete->PackageId}}" action="{{route('addToCart')}}" method="POST">
                <input name="_token" value="{{ csrf_token() }}" type="hidden">
                <input type="hidden" name="tipo" value="Hotel">
                <input type="hidden" name="idHotel" value="{{$hotel->Id}}">
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
        <div class="row">
            <div class="col s12">
                <ul class="tabs">
                    <li class="tab col s3"><a class="active" href="#detalles">Detalles</a></li>
                    <li class="tab col s3"><a id="aUbicacion" href="#ubicacion">Ubicación</a></li>
                    <li class="tab col s3"><a href="#amenidades">Amenidades</a></li>
                    <li class="tab col s3"><a href="#politicas">Políticas</a></li>
                </ul>
            </div>
            <div id="detalles" class="col s12">
                <div class="row"></div>

                <p>{!! $detalle->Descripciones->Descripcion->Descripcioncompleta !!} </p>
            </div>
            <div id="ubicacion" class="col s12">
                <div onclick="javascript:plegardesplegar('divMapUp');" class="visible" id="divMapUp"></div>
                <div id="mapa"></div>
            </div>
            <div id="amenidades" class="col s12">
                <ul class="twoColumns">
                    @isset($detalle->Amenidades->Amenidad)
                    @foreach(objetoParseArray($detalle->Amenidades->Amenidad) as $amenidad)
                    <li>{{$amenidad->Descripcion}}</li>
                    @endforeach
                    @endisset
                </ul>
            </div>
            <div id="politicas" class="col s12">
@if(!empty($hotel->PoliticasCancelacion->politica))
                @foreach(objetoParseArray($hotel->PoliticasCancelacion->politica) as $politica)

                {{getTextoPoliticas($politica)}}<br>
                @endforeach
@endif
            </div>
        </div>
    </div>
    <div class="card-action">
        <div class="">
            <a href="#">Footer</a>
        </div>
    </div>
</div>


@endsection