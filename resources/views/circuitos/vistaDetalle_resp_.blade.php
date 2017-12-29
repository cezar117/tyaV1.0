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
            title: "Agregar al carrito",
            text: "agregar al carritooo!",
            type: "info",
            showCancelButton: true,
          closeOnConfirm: false,
          showLoaderOnConfirm: true,
           },
          function(isConfirm){
            if(isConfirm){
                $.ajax({
                method: "POST",
                        url: "{{route('addToCart')}}",
                        dataType: 'JSON',
                        data: form.serialize()
                }).done(function(data) {
                    console.log(data);
                    if (data.estatus) {
                    Materialize.toast(data.mensaje, 4000);
                $(".carritoBadge").html(data.num_elementos);
                swal('Agregado al Carrito!',data.mensaje,"success");
             }else{
                Materialize.toast(data.mensaje, 4000);
                 $(".carritoBadge").html(data.num_elementos);
                  swal('Opss!',data.mensaje,"error");
                    }
                });
            }
        });
    }
// var directionsDisplay;
// var directionsService;
var directionsService;
var directionsDisplay;

function initMap() {
   var wps = [];
     // var dat = JSON.parse(datos);

           // alert({{ count($rutas[0]->Destinos->DestinoRuta)}});

           //      alert( {{ $rutas[0]->Destinos->DestinoRuta[0]->Latitud }});
           //      alert( {{ $rutas[0]->Destinos->DestinoRuta[0]->Longitud }});

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


        // alert( {{ $rutas[0]->Destinos->DestinoRuta[count($rutas[0]->Destinos->DestinoRuta)-1]->Latitud }});
        // alert( {{ $rutas[0]->Destinos->DestinoRuta[count($rutas[0]->Destinos->DestinoRuta)-1]->Longitud }});


 
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


                    // alert('Could not display directions due to: ' + status);

          }
        });
      }

// function computeTotalDistance(result) {
//         var total = 0;
//         var myroute = result.routes[0];
//         for (var i = 0; i < myroute.legs.length; i++) {
//           total += myroute.legs[i].distance.value;
//         }
//         total = total / 1000;
//         document.getElementById('total').innerHTML = total + ' km';
//       }
function plegardesplegar(identificacion) {
        var elemento = document.getElementById(identificacion);
        if (elemento.className == "visible") {
            elemento.className = "invisible";
        } else {
            elemento.className = "visible";
        }
    }

    $(document).ready(function() {
        $('#galeria').carousel({fullWidth: true});
        $('#galeria').carousel({fullWidth: true});
//        $('.carousel.carousel-slider').carousel({fullWidth: true});
    });</script>


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
<div class="card white darken-1 hoverable">
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
  </div>
           

           
        </div>
        <div class="row"></div>
    </div>
    <div class="card-action">
        <a class="btn-floating halfway-fab waves-effect waves-light red"><i class="material-icons">add</i></a>
        <a href="#" class="right">Agregar a itinerario</a>
        <br>

    </div>
</div>

<div class="card white darken-1 hoverable">
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
     {{--  {{dd($paquetes)}} --}}
        @foreach($paquetes as $paquete)
          @if(!empty($paquete->CircuitoTarifas->CircuitoTarifa) )
                @foreach(objetoParseArray($paquete->CircuitoTarifas->CircuitoTarifa) as $data)
                {{-- {{dd($paquete)}} --}}
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
</div>

<div class="card white darken-1 hoverable">
    <div class="card-content black-text">
        <div onclick="javascript:plegardesplegar('divMapUp');" class="visible" id="divMapUp"></div>
        <div id="mapa">
            <div id="map"></div>
         <div id="right-panel" style="overflow-x: hidden;overflow-y: scroll; height: 300px;">
       <!--  <a href="#!" class="collection-item">Alvin</a>
        <a href="#!" class="collection-item active">Alvin</a>
        <a href="#!" class="collection-item">Alvin</a>
        <a href="#!" class="collection-item">Alvin</a> -->
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
    <div class="card-action">
        <div class="">
            <a href="#">Footer</a>
        </div>
    </div>

</div>
 
@endsection