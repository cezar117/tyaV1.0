@extends("estructura.app")
@section('parallax1')
@overwrite
@section('contenido')
@push('scripts')

<script >
    $(function() {
   $('#enviar').removeClass('disabled');
   // $('#enviar').addClass('disabled');
    $('.materialboxed').materialbox();
    // $('#stiker').stick_in_parent();

    var stickyNavTop = $('#stiker').offset().top;
 

var finBottom = $('#fin').offset().top;
// var scrollBottom = $(window).scrollTop() + $(window).height();
var scrollBottom = $(window).scrollTop() + finBottom;
console.log(scrollBottom );
console.log(stickyNavTop );

var stickyNav = function(){
var scrollTop = $(window).scrollTop();

if (scrollTop > stickyNavTop) { 
  
    $('#stiker').addClass('sticky');
} else {
    
    $('#stiker').removeClass('sticky'); 
}
};

stickyNav();
 
$(window).scroll(function() {
  stickyNav();

});

});

</script>
<script>
    function quitarcarrito(dato) {
       swal({
   title: 'Eliminando..',
   text: "quitando el producto del carrito",
   type: 'warning',
   showCancelButton: false,
   showConfirmButton: false,
   onOpen: function(){
    swal.showLoading();
        var index = dato;
            $.ajax({
            url:"{{ route('deleteToCart')}}",
            data: {
            "_token": "{{ csrf_token() }}",
            "index": index
            },
            method:'POST',
            success:function(data){
              swal.hideLoading(); 
              console.log(data.respuesta);
                console.log(data);
              $(".carritoBadge").html(data.count);
              $("#total").html(data.total);
              $('#'+index).fadeOut();
           if (data.count == 0) {
                    Materialize.toast('El carrito está vacio!', 4000); 
                    $('#enviar').addClass('disabled');
                }
            swal.closeModal();
              }
            });
                },
                allowOutsideClick: false
            });
    }

</script>
@endpush
{{--  {{dd(ResumenPreReserva())}} --}}
@if(!empty($carrito))
{{-- {{dd($carrito)}} --}}
<div class="container">
    <div class="col s3">
        <!--############################################################ -->
        <div class="card-panel light-green lighten-5" id="stiker">
            <div id="wizard_vertical" class="wizard clearfix vertical">
                <div class="steps clearfix">
                    <ul role="tablist">
                        <li role="tab" class="first current" aria-disabled="false" aria-selected="true"><a id="wizard_vertical-t-0" href="#wizard_vertical-h-0" aria-controls="wizard_vertical-p-0"><span class="current-info audible">current step: </span><span class="number">1.</span> Listado de Compras</a></li>
                        <li role="tab" class="disabled" aria-disabled="true"><a id="wizard_vertical-t-1" href="#wizard_vertical-h-1" aria-controls="wizard_vertical-p-1"><span class="number">2.</span> Completar Datos</a></li>
                        <li role="tab" class="disabled" aria-disabled="true"><a id="wizard_vertical-t-2" href="#wizard_vertical-h-2" aria-controls="wizard_vertical-p-2"><span class="number">3.</span> Forma de Pago</a></li>
                        <li role="tab" class="disabled" aria-disabled="false" aria-selected="false"><a id="wizard_vertical-t-3" href="#wizard_vertical-h-3" aria-controls="wizard_vertical-p-3"><span class="number">4.</span> Compra Exitosa</a></li>
                    </ul>
                </div>
            </div>
            <div class="form-group form-float">
                <form action="{{ route('carrito.CompletarDatos') }}" method="GET" name="form" id="form">
                    <input name="_token" value="{{ csrf_token() }}" type="hidden">
                    <h5>TOTAL: <b>$<span id="total" class="amber lighten-4">{{obtenerTotalCarrito()}}
           </span class="grey lighten-4"></b>
           </h5>
                    <!-- volar el input total y obtenerlo desde helper -->
                    {{-- <input type="hidden" name="total" id="total" value="{{obtenerTotalCarrito()}}"> --}}
                    <div class="input-field col s12 ">
                        <input class="btn right" id="enviar" value="Siguiente" type="submit">
                    </div>
                </form>
            </div>
            <div class="row"></div>
        </div>
        <!--########################################################## -->
    </div>
    <div class="col s3 m9 l9">
        @foreach($carrito as $key => $elemento)
        <div class="card light-green lighten-5 hoverable" id="{{$key}}">
            <div class="card-content">
                <div class="col s3 m3 l3">
                    @if($elemento["tipo"] == 'Tour')
                    <img src="{{$elemento["datos"]->Imagen}}" class="responsive-img  "> 
                    @elseif($elemento["tipo"] == 'Actividad')
                    <img src="{{$elemento["datos"]->Galeria->Imagen->Nombre}}" class="responsive-img  "> 
                    @elseif(isset($elemento["datos"]->Imagen_main))
                    <img src="{{$elemento["datos"]->Imagen_main}}" class="responsive-img"> 
                    @else
                    <img src="{{$elemento["datos"]->Galeria->Imagen[0]->Nombre}}" class="responsive-img"> 
                  @endif
                </div>
                <div class="col s9 m9 l9">
                  @if($elemento["tipo"] == 'Circuito')
                    <div class="col s4 m4 l4"><span><span><b>Tipo producto : </b><b>{{$elemento["tipo"]}}</b></span>
                        <br>
                        <span><b>Nombre : </b>{{$elemento["datos"]->Nombre}}</span>
                        <br>
                        <span><b>Tipo : </b>{{$elemento["datos"]->TipoServicio}}</span>
                        <br>
                        <span><b>Lugar de Origen : </b>{{$elemento["datos"]->CiudadSalida}}</span>
                        <br>
                        <span><b>Lugar de Destino : </b>{{$elemento["datos"]->CiudadLlegada}}</span>
                        <br>
                        <span><b>Duracion : </b>{{$elemento["datos"]->Dias}}</span>
                        <br>
                    </div>
                    <div class="col s4 m4 l4">
                        <span class=""><b>Tarifa : </b>{{$elemento["packagerooms"]->CircuitoTarifas->CircuitoTarifa->Nombre}}</span>
                        <br>
                        <span><b>Adultos : </b>{{$elemento["packagerooms"]->CircuitoTarifas->CircuitoTarifa->Adultos}}</span>
                        <br>
                        <span><b>Menores : </b>{{$elemento["packagerooms"]->CircuitoTarifas->CircuitoTarifa->Menores}}</span>
                        <br>
                        <span><b>Fecha Inicio : </b>{{$elemento["checkIn"]}}</span>
                        <br>
                        <span><b>Fecha Fin : </b></span>
                        <br>
                        <span><b>Precio Final : $ </b> {{formatoMoneda($elemento["packagerooms"]->CircuitoTarifas->CircuitoTarifa->PrecioOriginal)}} {{$elemento["datos"]->Moneda}}</span>
                        <br>
                    </div>
                    @elseif($elemento["tipo"] == 'Hotel')
                    <div class="col s12 m4">
                        <span class="red-text accent-4"><b><i class="fa fa-h-square fa-lg" aria-hidden="true"></i> </b></span> : <span class="brown-text darken-4"><b>{{$elemento["datos"]->Nombre}}</b></span>
                        <br>
                       <span class=" light-blue-text darken-1"><b><i class="fa fa-globe fa-lg" aria-hidden="true"></i></b></span> : <span class="brown-text darken-4"><b>{{$elemento["datos"]->Ciudad}}</b></span>
                        <br>
                        <span class="light-green-text accent-2"><b><i class="fa fa-map-marker fa-lg" aria-hidden="true"></i></b></span> : <span class="brown-text darken-4" style="font-size: 13px;"><b>{{$elemento["datos"]->Direccion}}</b></span>
                        <br>
                        <span class="grey-text lighten-1"><b><i class="fa fa-moon-o fa-lg" aria-hidden="true"></i></b></span> : <span class="brown-text darken-4"><b>{{$elemento["noches"]}}</b></span>
                        <br>
                        <span><b>{!! getEstrellas($elemento["datos"]->Categoria) !!}</b></span><br>
                    </div>
                   
                  @if(is_array($elemento["packagerooms"]->Habitaciones->HabitacionRespuesta)) 
                    @foreach($elemento["packagerooms"]->Habitaciones->HabitacionRespuesta as $keyy => $data)
                    <div class="col s12 m4">
                        <span class="amber lighten-4"><b>Habitacion {{$keyy+1}}</b></span>
                        <br>
                        <span class=" blue-grey-text darken-4"><b><i class="fa fa-check-square-o fa-lg" aria-hidden="true"></i></b></span> : <span class="brown-text darken-4"><b>{{$data->Regimen->TipoDeRegimen}}</b></span>
                        <br>
                         <span class=" blue-grey-text darken-4"><b><i class="fa fa-bed fa-lg" aria-hidden="true"></i></b></span> : <span class="brown-text darken-4"><b>{{$data->TipoHabitacion}}</b></span>
                        <br>
                        <span class="blue-grey-text darken-4"><b><i class="fa fa-hashtag" aria-hidden="true"></i><i class="fa fa-male fa-lg" aria-hidden="true"></i></b></span> : <span class="brown-text darken-4"><b>{{$data->Adultos}}</b></span>
                        <br>
                       <span class="blue-grey-text darken-4"><b><i class="fa fa-hashtag" aria-hidden="true"></i><i class="fa fa-user" aria-hidden="true"></i></b></span> : <span class="brown-text darken-4"><b>{{$data->Menores}}</b></span>
                        <br>
                         <span class="blue-grey-text darken-4"><b><i class="fa fa-usd" aria-hidden="true"></i>por Noche</b></span> : <span class="brown-text darken-4"><b>${{formatoMoneda($data->TarifaPorNoche)}}</b></span>
                        <br>
                        <span class="blue-grey-text darken-4"><b><i class="fa fa-usd" aria-hidden="true"></i>Total</b></span> : <span class="brown-text darken-4"><b>{{formatoMoneda($data->TarifaTotal)}}</b></span>
                        <br>
                    </div>
                    @endforeach
                     @else
                    <div class="col s12 m4">
                        <span class="amber lighten-4"><b>Habitacion 1</b></span>
                        <br>
                         <span class="blue-grey-text darken-4"><i class="fa fa-calendar-check-o fa-lg" aria-hidden="true"></i><i class="fa fa-sign-in fa-lg" aria-hidden="true"></i> </span> : <span class="brown-text darken-4"><b>{{$elemento["checkIn"]}}</b></span><br>
                        <span class=" blue-grey-text darken-4"><b><i class="fa fa-check-square-o fa-lg" aria-hidden="true"></i></b></span> : <span class="brown-text darken-4"><b>{{$elemento["packagerooms"]->Habitaciones->HabitacionRespuesta->Regimen->TipoDeRegimen}}</b></span>
                        <br>
                        <span class=" blue-grey-text darken-4"><b><i class="fa fa-bed fa-lg" aria-hidden="true"></i></b></span> : <span class="brown-text darken-4"><b>{{$elemento["packagerooms"]->Habitaciones->HabitacionRespuesta->TipoHabitacion}}</b></span>
                        <br>
                        <span class="blue-grey-text darken-4"><b><i class="fa fa-usd" aria-hidden="true"></i>por Noche</b></span> : <span class="brown-text darken-4"><b>${{formatoMoneda($elemento["packagerooms"]->Habitaciones->HabitacionRespuesta->TarifaPorNoche)}}</b></span>
                        <br>
                    </div>
                    <div class="col s12 m4"><br>
                      <span class="blue-grey-text darken-4"><i class="fa fa-calendar-check-o fa-lg" aria-hidden="true"></i><i class="fa fa-sign-out fa-lg" aria-hidden="true"></i></span> : <span class="brown-text darken-4"><b>{{$elemento["checkOut"]}}</b></span><br>
                      <span class="blue-grey-text darken-4"><b><i class="fa fa-hashtag" aria-hidden="true"></i><i class="fa fa-male fa-lg" aria-hidden="true"></i></b></span> : <span class="brown-text darken-4"><b>{{$elemento["packagerooms"]->Habitaciones->HabitacionRespuesta->Adultos}}</b></span>
                        <br>
                        <span class="blue-grey-text darken-4"><b><i class="fa fa-hashtag" aria-hidden="true"></i><i class="fa fa-child" aria-hidden="true"></i></b></span> : <span class="brown-text darken-4"><b>{{$elemento["packagerooms"]->Habitaciones->HabitacionRespuesta->Menores}}</b></span>
                        <br>
                        <span class="blue-grey-text darken-4"><b><i class="fa fa-usd" aria-hidden="true"></i>Total</b></span> : <span class="brown-text darken-4"><b>{{formatoMoneda($elemento["packagerooms"]->Habitaciones->HabitacionRespuesta->TarifaTotal)}}</b></span>
                        <br>
                    </div>
                    @endif 
                    @elseif($elemento["tipo"] == 'Tour')
                    <div class="col s3 m4">
                        <span class="blue-text"><b><i class="fa fa-map fa-lg" aria-hidden="true"></i></b></span> : <span class="brown-text darken-4"><b>{{$elemento["tipo"]}} {{$elemento["datos"]->Nombre}}</b></span><br>
                        <span class="red-text"><b><i class="material-icons">directions</i></b></span><span class="brown-text darken-4" style="font-size: 12.5px;"><b>{{$elemento["datos"]->Ruta}}</b></span>
                                               
                    </div>
                    <div class="col s3 m4">
                    <span class="blue-grey-text darken-4"><i class="fa fa-calendar-check-o fa-lg" aria-hidden="true"></i><i class="fa fa-sign-in fa-lg" aria-hidden="true"></i> </span> : <span class="brown-text darken-4"><b>{{$elemento["datos"]->FechaInicio}}</b></span>
                        <br>
                        <span class="blue-grey-text darken-4"><b>Categoria</b></span> : <span class="brown-text darken-4"><b>{{$elemento["datos"]->Categorias}}</b></span>
                        <br>                        
                        <span class="blue-grey-text darken-4"><b><i class="material-icons">room_service</i></b></span> : <span class="brown-text darken-4"><b>{{$elemento["datos"]->TipoServicio}}</b></span>
                        <br>
                        <span class="blue-grey-text darken-4"><b><i class="material-icons">translate</i></b></span> : <span class="brown-text darken-4"><b>{{$elemento["datos"]->Idioma}}</b></span>
                        
                    </div>
                    <div class="col s3 m4">
                    <span class="blue-grey-text darken-4"><i class="fa fa-calendar-check-o fa-lg" aria-hidden="true"></i><i class="fa fa-sign-out fa-lg" aria-hidden="true"></i> </span> : <span class="brown-text darken-4"><b>{{$elemento["datos"]->FechaInicio}}</b></span>
                    <br>
                        <span class="blue-grey-text darken-4"><b><i class="fa fa-hashtag" aria-hidden="true"></i><i class="fa fa-male fa-lg" aria-hidden="true"></i></b></span> : <span class="brown-text darken-4"><b>{{$elemento["adultos"]}}</b></span>
                        <br>
                        <span class="blue-grey-text darken-4"><b><i class="fa fa-hashtag" aria-hidden="true"></i><i class="fa fa-child" aria-hidden="true"></i></b></span> : <span class="brown-text darken-4"><b>{{$elemento["menores"]}}</b></span>
                        <br>
                        <span class="blue-grey-text darken-4"><b><i class="fa fa-usd" aria-hidden="true"></i>Total</b></span> : <span class="brown-text darken-4"><b>${{formatoMoneda($elemento["packagerooms"]->PricePackage)}} {{$elemento["datos"]->Moneda}}</b></span>
                       
                    </div>
                    @elseif($elemento["tipo"] == 'Actividad')
                    <div class="col s3 m4">
                        <span class="blue-text"><b><i class="fa fa-arrows fa-lg" aria-hidden="true"></i></b></span> : <span class="brown-text darken-4"><b>{{$elemento["tipo"]}} {{$elemento["datos"]->Nombre}}</b></span>
                        <br>
                         <span class="red-text"><b><i class="material-icons">directions</i></b></span> : <span class="brown-text darken-4" style="font-size: 13px;"><b>{{$elemento["packagerooms"]->ActividadTarifas->ActividadTarifas->Nombre}}</b></span>
                        <br>
                        <span class="light-blue-text darken-1"><b><i class="fa fa-globe fa-lg" aria-hidden="true"></i></b></span> : <span class="brown-text darken-4"><b>{{$elemento["datos"]->Ciudad}}</b></span>
                    </div>
                    <div class="col s3 m4">
                    <span class="blue-grey-text darken-4"><i class="fa fa-calendar-check-o fa-lg" aria-hidden="true"></i><i class="fa fa-sign-in fa-lg" aria-hidden="true"></i> </span> : <span class="brown-text darken-4"><b>{{$elemento["checkIn"]}}</b></span>
                    <br>
                    <span class="blue-grey-text darken-4"><b><i class="material-icons">room_service</i></b></span> : <span class="brown-text darken-4"><b>{{$elemento["datos"]->Tipo}}</b></span>
                        <br>
                     <span class="blue-grey-text darken-4"><b><i class="material-icons">timer</i></b></span> : <span class="brown-text darken-4"><b>{{$elemento["datos"]->Duraciones->string}}</b></span> 
                    </div>
                    <div class="col s3 m4">
                    <span class="blue-grey-text darken-4"><i class="fa fa-calendar-check-o fa-lg" aria-hidden="true"></i><i class="fa fa-sign-out fa-lg" aria-hidden="true"></i> </span> : <span class="brown-text darken-4"><b>{{$elemento["checkIn"]}}</b></span>
                    <br>
                        <span class="blue-grey-text darken-4"><b><i class="fa fa-hashtag" aria-hidden="true"></i><i class="fa fa-male fa-lg" aria-hidden="true"></i></b></span> : <span class="brown-text darken-4"><b>{{$elemento["adultos"]}}</b></span>
                        <br>
                        <span class="blue-grey-text darken-4"><b><i class="fa fa-hashtag" aria-hidden="true"></i><i class="fa fa-child" aria-hidden="true"></i></b></span> : <span class="brown-text darken-4"><b>{{$elemento["menores"]}}</b></span>
                        <br>
                        <span class="blue-grey-text darken-4"><b><i class="fa fa-usd" aria-hidden="true"></i>Total</b></span> : <span class="brown-text darken-4"><b>${{formatoMoneda($elemento["packagerooms"]->PricePackage)}} {{$elemento["datos"]->Moneda}}</b></span>
                    </div>
                    @endif
                </div>
                <div class="row">
                </div>
            </div>
            <div class="card-action ">
                {{--
                <button onclick="" class="">eliminar </button> --}}
                <div class="">
                    <button class="waves-effect waves-light btn " onclick="javascript:quitarcarrito({{$key}});">Eliminar</button>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @else

    {{-- {{dd(ResumenPreReserva())}} --}}
    <div class="row">
        <div class="col s12 m6 ">
            <div class="card-panel blue-grey darken-1">
                <h4 class="white-text">
          El Carrito está vacio  :/ 
          </h4>
            </div>
        </div>
    </div>
    @endif
    <div id="fin"> </div>
</div>
@endsection