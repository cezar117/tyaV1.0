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
            // swal("Eliminado!", "El articulo se ha eliminado.", "success");
           
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

<div>
@if(!empty($carrito))
{{--  {{dd($carrito)}}  --}}
{{-- @foreach($carrito as $elemento)
{{$elemento["packagerooms"]->PricePackage}}
@endforeach --}}
       {{-- {{dd($carrito)}}  --}}
<div class="container-fluid">       
<div class="col s3" >
      
<!--############################################################ -->


<div class="card-panel blue lighten-5" id="stiker" >
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
            <form action="{{ route('carrito.CompletarDatos') }}" method="POST" name="form" id="form">
            <input name="_token" value="{{ csrf_token() }}" type="hidden">
           <h5>TOTAL: <b>$<span id="total" class="grey lighten-4">{{obtenerTotalCarrito()}}
           </span class="grey lighten-4"></b>
           </h5>
           <!-- volar el input total y obtenerlo desde helper -->
           <input type="hidden" name="total" id="total" value="{{-- {{obtenerTotalCarrito()}} --}}">
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
        <div class="card white darken-1 hoverable" id="{{$key}}">
            <div class="card-content black-text">
                <div class="col s3 m3 l3">

                @if($elemento["tipo"] == 'Tour')
                    <img src="{{$elemento["datos"]->Imagen}}" class="responsive-img  ">
                   
                    @elseif($elemento["tipo"] == 'Actividad')
                    <img src="{{$elemento["datos"]->Galeria->Imagen->Nombre}}" class="responsive-img  ">
                  @else
                     <img src="{{$elemento["datos"]->Imagen_main}}" class="responsive-img  ">
                 @endif   
                </div>
                <div class="col s9 m9 l9">
                   {{--  <span><b>{{$elemento["datos"]->Nombre}}</b></span> --}}
            @if($elemento["tipo"] == 'Circuito')
                    <div class="col s4 m4 l4"><span><span><b>Tipo producto : </b><b>{{$elemento["tipo"]}}</b></span><br>
                            <span><b>Nombre : </b>{{$elemento["datos"]->Nombre}}</span><br>
                            <span><b>Tipo : </b>{{$elemento["datos"]->TipoServicio}}</span><br>
                            <span><b>Lugar de Origen : </b>{{$elemento["datos"]->CiudadSalida}}</span><br>
                            <span><b>Lugar de Destino : </b>{{$elemento["datos"]->CiudadLlegada}}</span><br>
                           
                            <span><b>Duracion : </b>{{$elemento["datos"]->Dias}}</span><br>
                    </div>
                    <div class="col s4 m4 l4">
                        <span><b>Tarifa : </b>{{$elemento["packagerooms"]->CircuitoTarifas->CircuitoTarifa->Nombre}}</span><br>
                        <span><b>Adultos : </b>{{$elemento["packagerooms"]->CircuitoTarifas->CircuitoTarifa->Adultos}}</span><br>
                        <span><b>Menores : </b>{{$elemento["packagerooms"]->CircuitoTarifas->CircuitoTarifa->Menores}}</span><br>
                         <span><b>Fecha Inicio : </b>{{$elemento["checkIn"]}}</span><br>
                            <span><b>Fecha Fin : </b></span><br>
                        <span><b>Precio Final : $ </b> {{formatoMoneda($elemento["packagerooms"]->CircuitoTarifas->CircuitoTarifa->PrecioOriginal)}} {{$elemento["datos"]->Moneda}}</span><br>
                    </div>

              @elseif($elemento["tipo"] == 'Hotel')

                            <div class="col s12 m4">
                            <span><b>Nombre :</b></span>{{$elemento["datos"]->Nombre}}<br>
                            <span><b>Tipo Alojamiento :</b><span> {{$elemento["datos"]->Aloj_tipo}}<br>
                            <span><b>Ciudad :</b></span>{{$elemento["datos"]->Ciudad}} <br>
                            <span><b>Direccion :</b></span>{{$elemento["datos"]->Direccion}}<br>
                            <span><b>Num. de Noches:</b> {{$elemento["noches"]}}</span><br>
                            </div>
                            <span><b>Categoria : {!! getEstrellas($elemento["datos"]->Categoria) !!}</b></span><br> 
   @if(is_array($elemento["packagerooms"]->Habitaciones->HabitacionRespuesta))                     
        @foreach($elemento["packagerooms"]->Habitaciones->HabitacionRespuesta as $keyy => $data)
                         <div class="col s12 m4">   
                         <span class="amber lighten-4"><b>Habitacion {{$keyy+1}}</b></span><br>
                             <span><b>Regimen :</b></span> {{$data->Regimen->TipoDeRegimen}}<br>
                             <span><b>Tipo de Habitacion :</b></span> {{$data->TipoHabitacion}} <br>
                        <span><b>Num. Adultos :</b></span> {{$data->Adultos}}<br>
                        <span><b>Num. Menores :</b></span> {{$data->Menores}}<br>
                        <span><b>Tarifa por Noche : $</b>{{formatoMoneda($data->TarifaPorNoche)}}</span><br>
                      <span><b>Precio Total : ${{formatoMoneda($data->TarifaTotal)}}</b></span>
                             <br>
                       </div>               
        @endforeach 
       
    @else
           
        <div class="col s12 m4">   
                         <span class="amber lighten-4"><b>Habitacion 1</b></span><br>
                             <span><b>Regimen :</b></span> {{$elemento["packagerooms"]->Habitaciones->HabitacionRespuesta->Regimen->TipoDeRegimen}}<br>
                             <span><b>Tipo de Habitacion :</b></span> {{$elemento["packagerooms"]->Habitaciones->HabitacionRespuesta->TipoHabitacion}} <br>
                        
                       
                       </div> 
                       <div class="col s12 m4">
                       <span><b>Num. Adultos :</b></span> {{$elemento["packagerooms"]->Habitaciones->HabitacionRespuesta->Adultos}}<br>
                        <span><b>Num. Menores :</b></span> {{$elemento["packagerooms"]->Habitaciones->HabitacionRespuesta->Menores}}<br>  
                             <span><b>Num. de Noches:</b> {{$elemento["noches"]}}</span><br>
                             <span><b>Tarifa por Noche : $</b>{{formatoMoneda($elemento["packagerooms"]->Habitaciones->HabitacionRespuesta->TarifaPorNoche)}}</span><br>
                             <span><b>Precio Total : ${{formatoMoneda($elemento["packagerooms"]->Habitaciones->HabitacionRespuesta->TarifaTotal)}}</b></span>
                             <br>
                       </div>
     
@endif
                @elseif($elemento["tipo"] == 'Tour')
                       <div class="col s12 m3">
                            <span><b>Tipo  :</b><span> {{$elemento["tipo"]}}<br>
                            <span><b>Nombre Tour :</b></span>{{$elemento["datos"]->Nombre}} <br>
                            <span><b>Ruta :</b></span>{{$elemento["datos"]->Ruta}} <br>
                            <span><b>Dias de opreacion :</b></span>{{$elemento["datos"]->DiasDeOperacion}}<br>
                            
                            </div>
                        <div class="col s12 m3">
                            <span><b>Categoria :</b></span>{{$elemento["datos"]->Categorias}}<br>
                             <span><b>Fecha de Inicio :</b></span> {{$elemento["datos"]->FechaInicio}}<br>
                             <span><b>Tipo Servicio:</b></span> {{$elemento["datos"]->TipoServicio}} <br>
                             <span><b>Precio Total : $</b></span> 
                            {{formatoMoneda($elemento["packagerooms"]->PricePackage)}} {{$elemento["datos"]->Moneda}}<br>

                        </div> 
                         <div class="col s12 m3">
                            <span><b>Num. Adultos :</b></span> {{$elemento["adultos"]}}<br>
                        <span><b>Num. Menores :</b></span> {{$elemento["menores"]}}<br>
                         </div>
                
                  @elseif($elemento["tipo"] == 'Actividad')
                        <div class="col s12 m3">
                            <span><b>Tipo  :</b><span> {{$elemento["tipo"]}}<br>
                            <span><b>Nombre Actividad :</b></span>{{$elemento["datos"]->Nombre}} <br>
                            <span><b>Ruta :</b></span>{{$elemento["datos"]->Nombre}} <br>
                            <span><b>Dias de opreacion :</b></span>{{$elemento["datos"]->Duraciones->string}}<br>
                            
                            </div>
                        <div class="col s12 m3">
                            <span><b>Categoria :</b></span>{{$elemento["packagerooms"]->ActividadTarifas->ActividadTarifas->Nombre}}<br>
                             <span><b>Fecha de Inicio :</b></span> {{$elemento["checkIn"]}}<br>
                             <span><b>Tipo Servicio:</b></span> {{$elemento["datos"]->Tipo}} <br>
                             <span><b>Precio Total : $</b></span> 
                            {{formatoMoneda($elemento["packagerooms"]->ActividadTarifas->ActividadTarifas->Total)}} {{$elemento["datos"]->Moneda}}<br>

                        </div> 
                         <div class="col s12 m3">
                            <span><b>Num. Adultos :</b></span> {{$elemento["adultos"]}}<br>
                        <span><b>Num. Menores :</b></span> {{$elemento["menores"]}}<br>
                         </div>

                 @endif
                </div>
                <div class="row">
                </div>               
            </div>
            <div class="card-action ">
               {{--  <button onclick=""
                class="" >eliminar </button> --}}
                <div class="">
                    <button class="waves-effect waves-light btn "
                    onclick="javascript:quitarcarrito({{$key}});">Eliminar</button>
                </div>
            </div>
        </div>
        @endforeach
        
    </div>

    @else
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
    <div id="fin">xxx</div>
</div>

</div>
@endsection