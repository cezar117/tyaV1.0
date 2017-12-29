@extends("estructura.app")

@push('stylesheets')
<style>
    #right-panel {
        font-family: 'Roboto','sans-serif';
        line-height: 30px;
        padding-left: 10px;
      }

      #right-panel select, #right-panel input {
        font-size: 15px;
      }

      #map {
        height: 100%;
        float: left;
        width: 70%;
        height: 100%;
      }
      .panel {
        height: 100%;
        overflow: auto;
      }
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
        width: 90%;
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

<script async defer src="https://maps.googleapis.com/maps/api/js?waypoints=optimize:true&key=AIzaSyAA1lwpDR28QuxwEIFcKT96Z8mvVN8EnxA&callback=initMap"></script>
<script>

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
var directionsService;
var directionsDisplay;

function initMap() {
   var wps = [];

@for ($i = 1; $i <= count($rutas[0]->Destinos->DestinoRuta)-2; $i++)

     var latitud = {{ $rutas[0]->Destinos->DestinoRuta[$i]->Latitud }} ;
        var longitud = {{ $rutas[0]->Destinos->DestinoRuta[$i]->Longitud }} ;


        var localizacion = {lat: latitud, lng: longitud};

           wps.push({
              location: localizacion,
              stopover: true
            });
@endfor
 console.log(wps);

    var latilong = new google.maps.LatLng(16.2461442,-92.818142);
    var map = new google.maps.Map(document.getElementById('map'), {
          zoom: 4,
          center: latilong 
        });

    directionsService = new google.maps.DirectionsService;
    directionsDisplay = new google.maps.DirectionsRenderer({
          draggable: true,
          map: map
        });

var destino  = new google.maps.LatLng(16.7588452,-93.1277854);
var origen = new google.maps.LatLng(16.7419474,-92.6487087);

  displayRoute(origen, destino, directionsService,directionsDisplay, wps);
      }

function datamapa(mapa){
     // var datos = $(this).data("mapa");
    
     var datos = mapa.getAttribute("data-mapa");
     var dat = JSON.parse(datos);
    var waypts = []; 

    var LatitudInicio= parseFloat(dat.Rutas[0].Latitud);
    var LongitudInicio = parseFloat(dat.Rutas[0].Longitud);

    var LatitudFinal =  parseFloat(dat.Rutas[dat.Rutas.length - 1].Latitud);
    var LongitudFinal = parseFloat(dat.Rutas[dat.Rutas.length - 1].Longitud);

    for ( var i = 1; i <= dat.Rutas.length - 2; i++) {

        var latitud = parseFloat(dat.Rutas[i].Latitud);
        var longitud = parseFloat(dat.Rutas[i].Longitud);
        var localizacion = {lat: latitud, lng: longitud};

           waypts.push({
              location: localizacion,
              stopover: true
            });
    }

var origen = new google.maps.LatLng(LatitudInicio, LongitudInicio);
var destino  = new google.maps.LatLng(LatitudFinal, LongitudFinal);

displayRoute(origen, destino, directionsService,
            directionsDisplay,waypts);

}

function displayRoute(origin, destination, service, display, waypts) {

        service.route({
          origin: origin,
          destination: destination,
          waypoints: waypts,
          travelMode: 'DRIVING',
          avoidTolls: false //esqyuivar casetas
        }, function(response, status) {
          if (status === 'OK') {
            display.setDirections(response);
          } else {
                service.route({
                    origin: origin,
                    destination: destination,
                    waypoints: waypts,
                    travelMode: 'WALKING',
                    avoidTolls: false
                },function(response){
                    display.setDirections(response);
                });

          }
        });
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

    $(document).ready(function() {
        $('#galeria').carousel({fullWidth: true});
        $('#galeria').carousel({fullWidth: true});

         $("#rutas").one("click", function(){
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


    });
   </script>


    <script type="text/javascript">
        
  $(document).ready(function(){
    $('ul.tabs').tabs('select_tab', 'test1');
  });
    </script>
@endpush

@section('parallax1')
@endsection
@section('contenido.container')
{{-- {{dd($CircuitoDetalles)}} --}}


<!--############################################################################################################## -->

<div class="card white darken-1 hoverable">
    <div class="card-content">

        <div class="col s12 m8 l7">
            <div class="slider-container">
                <div class="slider-content">
                    @if(isset($CircuitoDetalles->Galeria->Imagen))
                        @forelse($CircuitoDetalles->Galeria->Imagen as $key => $galeria)
                            <div class="slider-single">
                                <img class="slider-single-image" src="{{ $galeria->Nombre  }}" alt="{{ $key }}" >
                            </div>
                        @empty
                            <div class="slider-single">
                                <img class="slider-single-image" src="{{ asset('images/sin_foto.png') }}" alt="{{ $key }}" >
                            </div>
                        @endforelse
                    @else
                        <!-- <div class="slider-single active">
                            <img class="slider-single-image" src="{{ $hotel->Imagen_main }}" alt="1" >
                        </div> -->
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

            <h5 class="blue-text">{{ $CircuitoDetalles->Nombre }}</h5>
           {{-- <span>{{$hotel->Direccion}}</span> --}}
           {{--  <div class="col l12">{!! getEstrellas($hotel->Categoria,$hotel->CategoriaString,"") !!}</div> --}}
            <div class="col s12 m6">
                <div><b>Inicio: </b><span class="">{{ $CircuitoDetalles->CiudadSalida }}</span></div><br>
                <div><span><b>Fin: </b>{{ $CircuitoDetalles->CiudadLlegada}}</span></div><br>
            </div>
            <div class="col s12 m6">
            <div><b>Salida: </b> {{ $busqueda["checkIn"] }}</div><br>
            <div><b>Llegada: </b>{{ fechaFinal($CircuitoDetalles->DiasDuracion,$busqueda["checkIn"]) }}</div><br>
                
            </div>
            <div class="col s12 m6">
                <div>
                <span><b>Num. Adultos : </b></span><span class="">{{$busqueda["adultos"]}}</span><br> 
                <span><b>Num Menores :  </b></span><span class="">{{$busqueda["menores"]}}</span><br>
                </div>
            </div>
            <div class="col s12 m6">
            <span><b> Circuito : </b></span><span class="">{{$CircuitoDetalles->TipoCircuito}}</span><br>
             <span><b>Servicio : </b></span><span class="">{{$CircuitoDetalles->TipoServicio}}</span> <br>
            </div>
        </div>
        <div class="row"></div>
    </div>
    <div class="card-action">
        @if($CircuitoDetalles->Descripciones->Descripcion->Descripcioncompleta !== "")
        <div class="descripcionCard">
            <span>{!! $CircuitoDetalles->Descripciones->Descripcion->Descripcioncompleta !!} </span>
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


<!--################################################################################################################## -->
{{-- <div class="card white darken-1 hoverable">
    <div class="card-content">
        <!--<div class="col s12 m4" style="position: relative; top: 15% !important;">-->
        <div class="col s12 m4">

            <div id="galeria" class="carousel carousel-slider">
                @foreach($CircuitoDetalles->Galeria->Imagen as $key => $galeria)
                <a class="carousel-item" href="#sl{{ $key }}"><img src="{{ $galeria->Nombre }}"></a>
                @endforeach
             </div>

        </div>

 <div class="col s12 m8">
 <div class="row">
    <div class="col s12">
      <ul class="tabs">
        <li class="tab col s3"><a href="#test1">Info</a></li>
        <li class="tab col s3"><a class="active" href="#test2">Descripcion</a></li>
      </ul>
    </div>
    <div id="test1" class="col s12"> 
    <br>
        <b>Nombre :</b><span>{{ $circuitoss->Nombre }}</span><br>
        <b>Categoria : </b>{{$circuitoss->ListaCategoria}}</br>
    <b>Inicio: </b>{{$circuitoss->CiudadSalida}}</br>
    <b>Tipo de Circuito:</b>{{$circuitoss->TipoCircuito}}</br>
    <b>Tipo de Servicio :</b>{{$circuitoss->TipoServicio}} </br>
    </div>
    <div id="test2" class="col s12"> 
    <p>{!! $CircuitoDetalles->Descripciones->Descripcion->Descripcioncompleta !!} </p></div>
    <div id="test3" class="col s12">
    
</div>
    {{-- <div id="test4" class="col s12">Test 4</div> --}}
{{--  --  </div>
           

           
        </div>
        <div class="row"></div>
    </div>
    <div class="card-action">
        <a class="btn-floating halfway-fab waves-effect waves-light red"><i class="material-icons">add</i></a>
        <a href="#" class="right">Agregar a itinerario</a>
        <br>

    </div>
</div> --}}
<!-- ################################PAQUETES################################################## -->
<div class="card white darken-1 hoverable">
    <div class="card-content black-text">
        <span>PAQUETES</span>
        <table class="tablaPaquetes striped">
@if($paquetes !== null)
            @forelse(objetoParseArray($paquetes) as $paquete)
            <thead>
                <tr>
                    <th>Habitación</th>
                    <th>Régimen</th>
                    <th>Dias de Operacion</th>
                    <th>Por Adulto</th>
                    <th>Por Menor</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>

                @foreach(objetoParseArray($paquete->CircuitoTarifas->CircuitoTarifa) as $data)
                <tr>
                    <td>{{ $data->Nombre }}</td>
                    <td>{{$CircuitoDetalles->TipoCircuito}}</td>
                    <td> {{ $data->DiasOperacion }} </td>
                    <td>$ {{ formatoMoneda($data->PrecioAdulto) }}</td>
                    <td>$ {{ formatoMoneda($data->PrecioMenor) }}</td>
                    <td>$ {{ formatoMoneda($data->Total) }}</td>
                </tr>
                @endforeach
                <tr>
                    <th colspan="1"><a href="#">Ver Comentarios</a> </th>
                    <th colspan="1"> TOTAL: $ {{ formatoMoneda($paquete->PricePackage) }}</th>

            <form id="form{{$paquete->PackageId}}" action="{{route('addToCart')}}" method="POST">
                <input name="_token" value="{{ csrf_token() }}" type="hidden">
                <input type="hidden" name="tipo" value="Circuito">
                <input type="hidden" name="idCircuito" value="{{$CircuitoDetalles->Id}}">
                <input type="hidden" name="idPaquete" value="{{$paquete->PackageId}}">

            </form>
            <th colspan="1"> <a class="waves-effect waves-light btn" onclick="addToCart($('#form{{$paquete->PackageId}}'));">Agregar</a></th>
            </tr>
            </tbody>
            @empty
                <center><h4>No hay paquetes disponibles en este hotel.</h4></center>
            @endforelse

           @endif 
        </table>
    </div>
    <div class="card-action">
        <div class="">
            <a href="#">Footer</a>
        </div>
    </div>
</div>

<!--############################################################################################# -->
{{-- <div class="card white darken-1 hoverable">
    <div class="card-content black-text">
        <span>PAQUETES</span>
        <table class="tablaPaquetes">
                <thead>
                <tr>              
                    <th>Tarifa</th>
                    <th>dias de operacion</th>
                    <th>Precio x persona</th>
                    <th>cantidad seleccionada</th>
                    <th>precio final x todas las personas</th>
                    <th>&nbsp</th>
                </tr>
            </thead>
            <tbody>


 @if(!empty($paquetes)) 
      {{dd($paquetes)}}
        @foreach($paquetes as $paquete)
          @if(!empty($paquete->CircuitoTarifas->CircuitoTarifa) )
                @foreach(objetoParseArray($paquete->CircuitoTarifas->CircuitoTarifa) as $data)
              
                  <tr>
                    <td>{{ $data->Nombre }}</td>
                    <td>{{ $data->DiasOperacion }}</td>
                    <td>$ {{ formatoMoneda($data->PrecioAdulto) }}</td>
                    <td>$ {{ $data->Adultos }}</td>
                    <td>$ {{ formatoMoneda($data->Total) }}</td>
                </tr>
                <tr>
                    <td colspan="1"> </td>
                    <td colspan="1"></td>
                    <td colspan="1"> </td>
                    <td colspan="1"> </td>
                    <td colspan="1"> </td>
                    <td colspan="2">TOTAL: <strong>$ {{ formatoMoneda($data->Total) }} </strong>
                @endforeach
              @endif
             <form id="form{{$paquete->PackageId}}" action="{{route('addToCart')}}" method="POST">
                <input name="_token" value="{{ csrf_token() }}" type="hidden">
                <input type="hidden" name="tipo" value="Circuito">
                <input type="hidden" name="idCircuito" value="{{$circuitoss->Id}}">
                <input type="hidden" name="idPaquete" value="{{$paquete->PackageId}}">
            </form>       
             <a class="waves-effect waves-light btn" onclick="addToCart($('#form{{$paquete->PackageId}}'));">Agregar</a> 
             </td>
                </tr>
                
         @endforeach
       @endif 
                
            </tbody>

        </table>

    </div>
    <div class="card-action">
  
    </div>
</div> --}}
<!-- ################################################################################## -->
<div class="card white darken-1 hoverable">
    <div class="card-content black-text">
        <div class="row">
            <div class="col s12">
                <ul class="tabs">
                    
                    <li class="tab col s3"><a class="active" id="rutas" href="#arutas">Rutas</a></li>
                    <li class="tab col s3"><a href="#detalles">Detalles</a></li>
                    <li class="tab col s3"><a href="#amenidades">Plan de Alimentos</a></li>
                    <li class="tab col s3"><a href="#politicas">Políticas</a></li>
                </ul>
            </div>
            
            <div id="arutas" class="col s12">
                <div onclick="javascript:plegardesplegar('divMapUp');" class="visible" id="divMapUp"></div>
               
                  <div id="mapa">
            <div id="map"></div>
         <div id="right-panel" style="overflow-x: hidden;overflow-y: scroll; height: 300px;">
                 <div class="collection">
                     @foreach ($rutas as $ruta)
        <div class="collection-item" style="cursor: pointer;">dia {{$loop->index+1}}
                        @foreach ($ruta->Destinos as $destinos)                   
        <a class="collection-item" href="#!" onclick="datamapa(this)" data-mapa='{"Rutas": [@foreach($destinos as $data) {"DestinoNombre":"{{ $data->DestinoNombre }}","Latitud":"{{ $data->Latitud }}","Longitud":"{{ $data->Longitud }}"}@if($loop->last)@else,@endif @endforeach]}'>
        {{$ruta->RutaCadena}}
        </a>
             @endforeach
           </div>
                @endforeach
          </div>
      </div>
         </div>
            </div>
            <div id="detalles" class="col s12">
                <div class="row"></div>

                <p>{!! $CircuitoDetalles->Descripciones->Descripcion->Descripcioncompleta !!} </p>
            </div>
            <div id="amenidades" class="col s12">
                <ul class="fourColumns">
                    @isset($CircuitoDetalles->DiasAlimentos->DiaAlimentos)
                      @foreach($CircuitoDetalles->DiasAlimentos->DiaAlimentos as $alimentos)
                <div class="col s12 m6 l9">
                    <span>Descripcion :</span><strong>{{$alimentos->PlanDeAlimentos->PlanDeAlimento->Descripcion}} </strong><br>
                    <span>Dia :</span><strong> {{$alimentos->PlanDeAlimentos->PlanDeAlimento->Dia}}</strong><br>
                    <span>Lugar :</span><strong> {{$alimentos->PlanDeAlimentos->PlanDeAlimento->Lugar}}</strong><br>
                    <span> Tipo :</span><strong> {{$alimentos->PlanDeAlimentos->PlanDeAlimento->Tipo}}</strong><br>
                 </div>   
                @endforeach
                    @endisset
                </ul>
            </div>
            <div id="politicas" class="col s12">
@if(!empty($CircuitoDetalles->PoliticasCancelacion->politica))
                @foreach(objetoParseArray($hotel->PoliticasCancelacion->politica) as $politica)

                {{getTextoPoliticas($politica)}}
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