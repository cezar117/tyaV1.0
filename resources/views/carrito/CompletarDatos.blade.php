@extends("estructura.app")

@section('contenido')

@push('scripts')
{{-- {{dd($PickUp)}} --}}
{{-- {{dd($DropOff)}} --}}
<script>

$(function() {
   $('select').material_select();
    // $('#textarea1').val('New Text');
  $('#textarea1').trigger('autoresize');
    var stickyNavTop = $('#stiker2').offset().top;
     var  stickyWidth = $(window).outerWidth();
      // var scrollWidth = $(window).outerWidth()
     // alert(stickyWidth);
var stickyNav = function(){
var scrollTop = $(window).scrollTop();

if (scrollTop > stickyNavTop) { 
  
    $('#stiker2').addClass('sticky2');
} else {
    
    $('#stiker2').removeClass('sticky2'); 
}
if (stickyWidth  < 600) {
  // alert('<');
  $('#stiker2').addClass('stickyAbajo');
}else{
  // alert('>');
  $('#stiker2').removeClass('stickyAbajo'); 
}

};
 
stickyNav();
 
$(window).scroll(function() {
  stickyNav();
});

});
  
</script>
<script>

@if($elemento["tipo"] == 'Tour' and $PickUp !== null)
$(document).ready(function(){

 $("#tipo_de_pickup").on("change",function(){

var tipo = $(this).find("option:selected").val();

if(tipo=="Hotel"){
  llenaLugaresHotel();
   }
  });

 $("#tipo_de_dropoff").on("change",function(){

var tipo = $(this).find("option:selected").val();

if(tipo=="Hotel"){
  llenaLugaresHotelDropOff();
   }
  });

 $("#lugar_pickup").on("change",function(){
  var tipo = $(this).find("option:selected").val();
  var dataId=   $(this).find("option:selected").data("id");
  // alert(dataId);
  switch(dataId){

    @foreach(objetoParseArray($PickUp->ListaHoteles->PickUp) as $key => $lugares)
    case {{$lugares->Id}}:
        horarioPickUp{{$lugares->Id}}();
        $("#extra_pickup").parent().find("label").addClass("active");
         $('#extra_pickup').val({{$lugares->Horarios->Horario->Costo}});

        break;
    @endforeach
  }
});


$("#lugar_dropoff").on("change",function(){
  var tipo = $(this).find("option:selected").val();
  var dataId=   $(this).find("option:selected").data("id");
  // alert(dataId);
  switch(dataId){
    @foreach(objetoParseArray($DropOff->ListaHoteles->DropOff) as $key => $lugares)
    case {{$lugares->Id}}:
        horarioDropOff{{$lugares->Id}}();
        $("#extra_dropoff").parent().find("label").addClass("active");
         $('#extra_dropoff').val({{$lugares->Horarios->Horario->Costo}});   
        break;
    @endforeach
  }
});

});

function llenaLugaresHotel(){
   $("#lugar_pickup").html("");
  @foreach(objetoParseArray($PickUp->ListaHoteles->PickUp) as $key => $lugares)

  $("#lugar_pickup").append('<option  value="{{$lugares->Lugar}}" data-id="{{$lugares->Id}}">{{$lugares->Lugar}}</option>');

  @endforeach
   $('#lugar_pickup').material_select();
  }

  function llenaLugaresHotelDropOff(){
  $("#lugar_dropoff").html("");
  @foreach(objetoParseArray($DropOff->ListaHoteles->DropOff) as $key => $lugares)

  $("#lugar_dropoff").append('<option  value="{{$lugares->Lugar}}" data-id="{{$lugares->Id}}">{{$lugares->Lugar}}</option>');

  @endforeach
   $('#lugar_dropoff').material_select();

  }

  @foreach(objetoParseArray($PickUp->ListaHoteles->PickUp) as $key => $lugares)
   function horarioPickUp{{$lugares->Id}}(){
      
       $("#horario_pickup").html("");
      @foreach(objetoParseArray($lugares->Horarios->Horario) as $key=> $horario)
        $("#horario_pickup").append('<option  value="{{$horario->Horariocompleto}}">{{$horario->Horariocompleto}}</option>');      
      @endforeach      
      $('#horario_pickup').material_select();
     
   }
  @endforeach

@foreach(objetoParseArray($DropOff->ListaHoteles->DropOff) as $key => $lugares)
   function horarioDropOff{{$lugares->Id}}(){
      
       $("#horario_dropoff").html("");
      @foreach(objetoParseArray($lugares->Horarios->Horario) as $key=> $horario)
        $("#horario_dropoff").append('<option  value="{{$horario->Horariocompleto}}">{{$horario->Horariocompleto}}</option>');      
      @endforeach      
      $('#horario_dropoff').material_select();
   }
  @endforeach


  @endif
</script>
@endpush


{{-- {{dd($request)}}  --}}
{{-- {{dd($monto_total)}} --}}
{{-- {{dd($elemento["datos"]->Nombre)}} --}}
{{-- {{dd($elemento)}} --}}

{{-- {{dd($DropOff)}} --}}
{{-- {{dd($PickUp)}} --}}
<div class="container">
<div class="col s3" >
      
<!--############################################################ -->


<div class="card-panel blue lighten-5" id="stiker2" >
<div id="wizard_vertical" class="wizard clearfix vertical">
        <div class="steps clearfix">
          <ul role="tablist">
            <li role="tab" class="last done" aria-disabled="false" aria-selected="true"><a id="wizard_vertical-t-0" href="#" aria-controls="wizard_vertical-p-0"><span class="current-info audible">current step: </span><span class="number">1.</span> Listado de Compras</a></li>

            <li role="tab" class="first current" aria-disabled="true"><a id="wizard_vertical-t-1" href="#" aria-controls="wizard_vertical-p-1"><span class="number">2.</span> Completar Datos</a></li>
            
            <li role="tab" class="disabled" aria-disabled="true"><a id="wizard_vertical-t-2" href="#w" aria-controls="wizard_vertical-p-2"><span class="number">3.</span> Forma de Pago</a></li>

           {{--  <li role="tab" class="disabled last" aria-disabled="true"><a id="wizard_vertical-t-3" href="#wizard_vertical-h-3" aria-controls="wizard_vertical-p-3"><span class="number">4.</span> Forth Step</a></li> --}}
           <li role="tab" class="disabled" aria-disabled="false" aria-selected="false"><a id="wizard_vertical-t-3" href="#" aria-controls="wizard_vertical-p-3"><span class="number">4.</span> Compra Exitosa</a></li>

          </ul>
        </div>
      </div>
         <div class="form-group form-float">
           {{--  <form action="{{ route('carrito.CompletarDatos') }}" method="POST" name="form" id="form"> --}}
          {{--   <input name="_token" value="{{ csrf_token() }}" type="hidden"> --}}
           <h5>TOTAL: <b>$<span id="total" class="grey lighten-4">{{obtenerTotalCarrito()}}
           </span></b>
           </h5>
           <!-- volar el input total y obtenerlo desde helper -->
 {{--           <input type="hidden" name="total" id="total" value="{{-- {{obtenerTotalCarrito()}} ">--}}
               {{--  <div class="input-field">                 
                   <input class="btn" value="Siguiente" type="submit">  --}}
              {{--  </div> --}}
                {{-- </form> --}}
        </div>
     <div class="row"></div>
  </div>

<!--########################################################## -->

    </div>
<div class="row col s3 m9">
     
    <form class="" action="{{ route('carrito.CompletarDatos') }}" method="GET" name="form" id="form">

      <input name="_token" id="_token" value="{{ csrf_token() }}" type="hidden">
      <input type="hidden" name="total" value="{{$monto_total}}">
      <input type="hidden" name="idPaquete" value="{{$elemento["idPaquete"]}}">
      <input type="hidden" name="idProducto" value="{{$elemento["id"]}}">
      <input type="hidden" name="tipo" value="{{$elemento["tipo"]}}">
@if($elemento["tipo"] == 'Tour' && $PickUp != null)
  @if(!empty($PickUp->ListaHoteles) && !is_array($PickUp->ListaHoteles->PickUp))
  
      <input type="hidden" name="id_lugar_pickup" value="{{$PickUp->ListaHoteles->PickUp->Id}}">
      <input type="hidden" name="id_horario_pickup" value="{{$PickUp->ListaHoteles->PickUp->Horarios->Horario->IdHorario}}">
      <input type="hidden" name="TipoLugar_pickup" value="{{$PickUp->ListaHoteles->PickUp->TipoLugar}}">
   
      <input type="hidden" name="id_lugar_dropoff" value="{{$DropOff->ListaHoteles->DropOff->Id}}">
    
      <input type="hidden" name="id_horario_dropoff" value="{{$DropOff->ListaHoteles->DropOff->Horarios->Horario->IdHorario}}">
      
      <input type="hidden" name="TipoLugar_dropoff" value="{{$DropOff->ListaHoteles->DropOff->TipoLugar}}">

@elseif(!empty($PickUp->listaAeropuertos))

@elseif(!empty($PickUp->ListaOtros))

@elseif(!empty($PickUp->ListaTerminalAutobuses))
@endif
@endif

       <div class="row" style="padding-top: 10px">
          <div class="col s3 m12">
            <div class="grey lighten-4">
           
            <div class="input-field col s4"><input id="nombre_producto" name="nombre_producto" type="text" readonly="" value="{{$elemento["datos"]->Nombre}}"><label for="nombre_producto" class="active"><i class="material-icons md-18">info_outline</i> {{$elemento["tipo"]}}</label></div>

            <div class="input-field col s4"><input id="checkIn" name="checkIn" type="text" readonly="" value="{{$elemento["checkIn"]}}"><label for="checkIn" class="active"><i class="material-icons md-18">date_range</i> Llegada</label></div>

            @if($elemento["tipo"] == 'Hotel')

            <div class="input-field col s4"><input id="checkOut" name="checkOut" type="text" readonly="" value="@isset($elemento["checkOut"]){{$elemento["checkOut"]}} @endisset"><label for="checkOut" class="active"><i class="material-icons md-18">date_range</i> Salida</label></div>

           @elseif($elemento["tipo"] == 'Tour')

            <div class="input-field col s4"><input id="checkOut" name="checkOut" type="text" readonly="" value="@isset($elemento["checkIn"]){{$elemento["checkIn"]}} @endisset"><label for="checkOut" class="active"><i class="material-icons md-18">date_range</i> Salida</label></div>
           @elseif($elemento["tipo"] == 'Circuito')

               <div class="input-field col s4"><input id="checkOut" name="checkOut" type="text" readonly="" value="@isset($elemento["checkOut"]){{$elemento["checkOut"]}} @endisset"><label for="checkOut" class="active"><i class="material-icons md-18">date_range</i> Salida</label></div>

           @endif
            <div class="row"></div>
            </div>            
          </div>
        </div>

      <div class="row">
          <div class="col s3 m12">
            <div class="grey lighten-4">
               <div> <h5>Ingresa el nombre del titular de la reservación</h5></div>
                <div class="row">
  
        <div class="input-field col s6">
        <i class="material-icons prefix">account_circle</i>
          <input id="nombre_titular" name="nombre_titular" type="text" class="validate" required="">
          <label for="nombre_titular">Nombre (s)</label>
        </div>
        <div class="input-field col s6">
        {{-- <i class="material-icons prefix">phone</i> --}}
          <input id="apellido_titular" name="apellido_titular" type="text" class="validate">
          <label for="apellido_titular">Apellido (s)</label>
        </div>
       </div>        
      </div>
      </div>
     </div>
<!--######################## INICIO  HOTEL #################################-->
@if($elemento["tipo"] == 'Hotel')
      <div class="row">
       <div class="col col s3 m12">
        <div class="card-panel   cyan lighten-5">
      <div> <h5>Nombre de los ocupantes de las habitaciones</h5></div>
 <div class="row">
 
  @if(is_array($elemento["packagerooms"]->Habitaciones->HabitacionRespuesta))
        @foreach($elemento["packagerooms"]->Habitaciones->HabitacionRespuesta as $key => $data)
<div class="row"><h5>Habitacion {{$key+1}}:</h5></div>
    <div class="input-field col s4"><input id="tipo_habitacion" name="tipo_habitacion" type="text" readonly="" value="{{$data->TipoHabitacion}}"><label for="tipo_habitacion" class="active">Tipo de Habitacion</label></div>
    <div class="input-field col s4"><input id="adultos" name="adultos" type="text" readonly="" value="{{$data->Adultos}}"><label for="adultos" class="active"># Adultos</label></div>
    <div class="input-field col s4"><input id="menores" name="menores" type="text" readonly="" value="{{$data->Menores}}"><label for="menores" class="active"># Menores</label></div>
      
<div class="row"></div>
  @for ($i = 1; $i <= ($data->Adultos + $data->Menores); $i++)

   <div class="input-field col s4">
     
          <input id="nombre" name="nombre[]" type="text" class="validate" required="">
          <label for="nombre">Nombre (s)</label>
        </div>
        <div class="input-field col s4">
          <input id="apellido" name="apellido[]" type="text" class="validate">
          <label for="apellido">Apellido (s)</label>
        </div>
        @if($i <= $data->Adultos)
        <div class="input-field col s4">
        <i class="material-icons prefix">person</i>
          <input id="tipo_persona" name="tipo_persona[]" type="text" readonly value="ADULTO">
          
        </div>
        @else
        <div class="input-field col s4">
        <i class="material-icons prefix">person</i>
          <input id="tipo_persona" name="tipo_persona[]" type="text" readonly value="NIÑO">
          </div>
        @endif

        @endfor

      @endforeach

      @else
    
<h5><span>Habitacion 1</span></h5>
<div class="input-field col s4">
    <input id="tipo_habitacion" name="tipo_habitacion" type="text" readonly="" value="{{$elemento["packagerooms"]->Habitaciones->HabitacionRespuesta->TipoHabitacion}}"><label for="tipo_habitacion" class="active">Tipo de Habitacion</label></div>
    <div class="input-field col s4"><input id="adultos" name="adultos" type="text" readonly="" value="{{$elemento["packagerooms"]->Habitaciones->HabitacionRespuesta->Adultos}}"><label for="adultos" class="active"># Adultos</label></div>
    <div class="input-field col s4"><input id="menores" name="menores" type="text" readonly="" value="{{$elemento["packagerooms"]->Habitaciones->HabitacionRespuesta->Menores}}"><label for="menores" class="active"># Menores</label></div>
        <div class="row"></div>
         @for ($i = 1; $i <= ($elemento["packagerooms"]->Habitaciones->HabitacionRespuesta->Adultos + $elemento["packagerooms"]->Habitaciones->HabitacionRespuesta->Menores); $i++)

   <div class="input-field col s4">

   <i class="material-icons prefix">person</i>
          <input id="nombre" name="nombre[]" type="text" class="validate" required="">
          <label for="nombre">Nombre (s)</label>
        </div>
        <div class="input-field col s4">
          <input id="apellido" name="apellido[]" type="text" class="validate">
          <label for="apellido">Apellido (s)</label>
        </div>
        @if($i <= $elemento["packagerooms"]->Habitaciones->HabitacionRespuesta->Adultos)
        <div class="input-field col s4">
          <input id="tipo_persona" name="tipo_persona[]" type="text" readonly="" value="ADULTO">
          
        </div>
        @else
        <div class="input-field col s4">
          <input id="tipo_persona" name="tipo_persona[]" type="text" readonly="" value="NIÑO">
          </div>
        @endif

        @endfor


     @endif
  

 </div>     
<div class="row"> </div>
</div>

</div>
</div>
<!--################################### FIN HOTEL #####################################-->

<!-- ########################################### TOUR ########################################-->
@elseif($elemento["tipo"] == 'Tour' and $PickUp != null)

<div class="row">

      <div class="col s3 m12">
     
        <div class="card-panel blue lighten-5">
        <div class="row">
         <h5><span>Lugar de inicio</span></h5>
          <div class="input-field col s3">
            <select id="tipo_de_pickup" name="tipo_de_pickup" class="validate" required="">
            <option value="">--seleccione--</option>
            @if(!empty($PickUp->listaAeropuertos))
              <option value="">Aeropuerto</option>
            @elseif(!empty($PickUp->ListaHoteles))
              <option value="Hotel">Hotel</option>
            @elseif(!empty($PickUp->ListaOtros))
            <option>otros</option>
            @elseif(!empty($PickUp->ListaTerminalAutobuses))
            <option>Terminal de autobus</option>
            @endif
              
            </select> 
            <label for="tipo_de_pickup"><i class="material-icons">info_outline</i> Tipo</label>
          </div>
          <div class="input-field col s3">
          <select id="lugar_pickup" name="lugar_pickup"  class="validate ">
          <option >--seleccione--</option> 
          </select>
            <label for="lugar_pickup"><i class="material-icons">location_on</i> Lugar</label>
          </div>
          <div class="input-field col s3">
            <select id="horario_pickup" name="horario_pickup" class="validate">
            <option value="">--seleccione--</option>
      
            </select><label for="horario_pickup"><i class="material-icons">alarm</i> Horario</label>
          </div>
          <div class="input-field col s3">
            <input id="extra_pickup" name="extra_pickup" type="text" class="validate"  readonly="">
           
            <label for="extra_pickup"><i class="fa fa-plus fa-lg" aria-hidden="true"></i> Extra</label>
          </div>
          </div>
          <div class="row">
          <h5><span>Lugar de termino</span></h5>
          <div class="input-field col s3">
            <select id="tipo_de_dropoff" name="tipo_de_dropoff"  class="validate">
            <option value="">--seleccione--</option>
            @if(!empty($DropOff->listaAeropuertos))
              <option value="">Aeropuerto</option>
            @elseif(!empty($DropOff->ListaHoteles))
              <option value="Hotel">Hotel</option>
            @elseif(!empty($DropOff->ListaOtros))
            <option>otros</option>
            @elseif(!empty($DropOff->ListaTerminalAutobuses))
            <option>Terminal de autobus</option>
            @endif
       
            </select><label for="tipo_de_dropoff">Tipo</label>
          </div>
          <div class="input-field col s3">
            <select id="lugar_dropoff" name="lugar_dropoff" class="validate">
              <option >--seleccione--</option> 
         
            </select>
            <label for="lugar_dropoff"><i class="material-icons">info_outline</i> Lugar</label>
          </div>
          <div class="input-field col s3">
            <select id="horario_dropoff" name="horario_dropoff"  class="validate">
              <option value="">--seleccione--</option>
            </select>
            <label for="horario_dropoff"><i class="material-icons">alarm</i> Horario</label>
          </div>
          <div class="input-field col s3">
          <input id="extra_dropoff" name="extra_dropoff" type="text" class="validate" readonly=""> 
            <label for="extra_dropoff"><i class="fa fa-plus fa-lg" aria-hidden="true"></i> Extra</label>
          </div>
          </div>
        </div>
      </div>
    </div>
<!--######################################### FIN TOUR ########################################-->

<!--####################################CIRCUITO######################################### -->
@elseif($elemento["tipo"] == 'Circuito')
  <div class="row">
      <div class="col s3 m12">
        <div class="card-panel">
          
          
        </div>
      </div>
    </div>

<!--#################################### FIN CIRCUITO ######################################## -->


@elseif($elemento["tipo"] == 'Actividad')<!--#################################### ACTIVIDAD ######################################## -->
<div class="row">
      <div class="col s3 m12">
        <div class="card-panel blue lighten-5">
        <div class="row">
         <h5><span>Ingresa los datos de los pasajeros de la reservación</span></h5>
          <div class="input-field col s3 m12">

@for($i=1; $i <= ($elemento["adultos"] + $elemento["menores"]);  $i++)

  <div class="input-field col s3 m3">
    <input type="text" name="nombre_pasajero[]" >
    <label for="nombre_pasajero"><i class="fa fa-user" aria-hidden="true"></i> Nombre(s)</label>
  </div>

   <div class="input-field col s3 m3">
    <input type="text" name="apellido_pasajero[]" >
    <label for="apellido_pasajero">Apeliido(s)</label>
  </div>

 
@if($i <= $elemento["adultos"])
  <div class="input-field col s3 m3">
    <input type="text" name="edad_pasajero[]" >
    <label for="edad_pasajero">Edad</label>
  </div>

   <div class="input-field col s3 m3">
    <input type="text" name="tipo_pasajero[]" readonly="" value="ADULTO">
    <label for="tipo_pasajero">Tipo</label>
  </div>
  @else
@foreach($elemento["edad_menores"] as $edad)
   <div class="input-field col s3 m3">
    <input type="text" name="edad_pasajero[]" readonly="" value="{{$edad}}">
    <label for="edad_pasajero">Edad</label>
  </div>
@endforeach
  <div class="input-field col s3 m3">
    <input type="text" name="tipo_pasajero[]" readonly="" value="NIÑO">
    <label for="tipo_pasajero">Tipo</label>
   </div>
    @endif
@endfor

    @if(isset($elemento['packagerooms']->Requisitos->Requisito) && !empty($elemento['packagerooms']->Requisitos->Requisito))
    @foreach(objetoParseArray($elemento['packagerooms']->Requisitos->Requisito) as $Requisito)
        <div class="input-field col s3 m12">
        <input type="hidden" name="requisito_req[]" value="{{$Requisito->Requerido}}">
        <input type="hidden" name="requisito_texto[]" value="{{$Requisito->Texto}}">
        <input type="hidden" name="requisito_tipo[]" value="{{$Requisito->Tipo}}">
        <input type="hidden" name="requisito_index[]" value="{{$Requisito->index}}">
        <input type="text" name="requisito_respuesta[]" id="{{Minusculas($Requisito->Tipo)}}" required=""> 
        <label for="{{Minusculas($Requisito->Tipo)}}">{!! iconos($Requisito->Tipo) !!}{{Minusculas($Requisito->Texto,true)}}</label>
        </div>
    @endforeach
  @endif
    </div>
    </div>
    </div>
  </div>
</div>

<!--#################################### FIN ACTIVIDAD ######################################## -->
@endif
<div class="row">
      <div class="col col s3 m12">
        <div class="grey lighten-4">
          <div> <h5>Contacto</h5></div>
            <div class="row">
     
        <div id="input-field" class="input-field col s4">
         <i class="material-icons prefix">contact_phone</i>
          <input type="text"  id="telefono" name="telefono" class="validate">
          <div class="errorTxt1"></div>
          <label for="telefono">Telefono</label>
        </div>
        <div class="input-field col s4">
         <i class="material-icons prefix">contact_mail</i>
          <input id="email" name="email" type="text" class="validate">
          <div class="errorTxt2"></div>
          <label for="email">Email</label>
        </div>


        <div class="input-field col s4">
          <i class="material-icons prefix">mode_edit</i>
          <textarea id="textarea1" name="textarea1" class="materialize-textarea" data-length="120"></textarea>
          <label for="textarea1">Observaciones</label>
        </div>



      </div>
     
        </div>
      </div>
    </div>

      <div class="row">
           
          <input type="submit" class="btn" name="enviar" id="enviar" value="Enviar">
       
      </div>
    </form>
 
    </div>
</div>

  @endsection