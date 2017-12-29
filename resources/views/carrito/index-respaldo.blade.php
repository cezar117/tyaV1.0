@extends("estructura.app")
@section('parallax1')
@overwrite
@section('contenido')
@push('scripts')

<script >
    $(function() {
   
    $('.materialboxed').materialbox();

 
});

</script>
<script>

    function quitarcarrito(dato) {
        var index = dato;
            $.ajax({
            url:"{{ route('deleteToCart')}}",
            data: {
            "_token": "{{ csrf_token() }}",
            "index": index
            },
            method:'POST',
            success:function(data){
                  console.log(data);
              $(".carritoBadge").html(data.count);

              $("#total").html(data.total);
              
                $('#'+index).fadeOut();
                if (data == 0) {
                    Materialize.toast('El carrito está vacio!', 4000);                   
                }
             // alert('producto eliminado');  
              }
            });
    }

</script>
@endpush
<style type="text/css">
    
.wizard,
.tabcontrol {
  display: block;
  width: 100%;
  overflow: hidden; }

.wizard a,
.tabcontrol a {
  outline: 0; }

.wizard ul,
.tabcontrol ul {
  list-style: none !important;
  padding: 0;
  margin: 0; }

.wizard ul > li, .tabcontrol ul > li {
  display: block;
  padding: 0; }

/* Accessibility */
.wizard > .steps .current-info,
.tabcontrol > .steps .current-info,
.wizard > .content > .title,
.tabcontrol > .content > .title {
  position: absolute;
  left: -999em; }

.wizard > .steps {
  position: relative;
  display: block;
  width: 100%; }

.wizard.vertical > .steps {
  float: left;
  }

.wizard.vertical > .steps > ul > li {
  float: none;
  width: 100%; }

.wizard.vertical > .content {
  float: left;
  margin: 0 0 0.5em 0;
  width: 70%; }

.wizard.vertical > .actions {
  float: right;
  width: 100%; }

.wizard.vertical > .actions > ul > li {
  margin: 0 0 0 1em; }

.wizard > .steps .number {
  font-size: 1.429em; }

.wizard > .steps > ul > li {
  width: 25%;
  float: left; }

.wizard > .actions > ul > li {
  float: left; }

.wizard > .steps a {
  display: block;
  width: auto;
  margin: 0 0.5em 0.5em;
  padding: 1em 1em;
  text-decoration: none;
  -webkit-border-radius: 5px;
  -moz-border-radius: 5px;
  border-radius: 5px; }
  .wizard > .steps a:hover, .wizard > .steps a:active {
    display: block;
    width: auto;
    margin: 0 0.5em 0.5em;
    padding: 1em 1em;
    text-decoration: none;
    -webkit-border-radius: 5px;
    -moz-border-radius: 5px;
    border-radius: 5px; }

.wizard > .steps .disabled a {
  background: #eee;
  color: #aaa;
  cursor: default; }
  .wizard > .steps .disabled a:hover, .wizard > .steps .disabled a:active {
    background: #eee;
    color: #aaa;
    cursor: default; }

.wizard > .steps .current a {
  background: #2184be;
  color: #fff;
  cursor: default; }
  .wizard > .steps .current a:hover, .wizard > .steps .current a:active {
    background: #2184be;
    color: #fff;
    cursor: default; }

.wizard > .steps .done a {
  background: #9dc8e2;
  color: #fff; }
  .wizard > .steps .done a:hover, .wizard > .steps .done a:active {
    background: #9dc8e2;
    color: #fff; }

.wizard > .steps .error a {
  background: #ff3111;
  color: #fff; }
  .wizard > .steps .error a:hover, .wizard > .steps .error a:active {
    background: #ff3111;
    color: #fff; }

.wizard > .content {
  border: 1px solid #ddd;
  display: block;
  margin: 0.5em;
  min-height: 35em;
  overflow: hidden;
  position: relative;
  width: auto; }

.wizard > .actions {
  position: relative;
  display: block;
  text-align: right;
  width: 100%; }

.wizard > .actions > ul {
  display: inline-block;
  text-align: right; }
  .wizard > .actions > ul > li {
    margin: 0 0.5em; }

.wizard > .actions a {
  background: #009688;
  color: #fff;
  display: block;
  padding: 0.5em 1em;
  text-decoration: none;
  -webkit-border-radius: 0;
  -moz-border-radius: 0;
  -ms-border-radius: 0;
  border-radius: 0; }
  .wizard > .actions a:hover, .wizard > .actions a:active {
    background: #009688;
    color: #fff;
    display: block;
    padding: 0.5em 1em;
    text-decoration: none;
    -webkit-border-radius: 0;
    -moz-border-radius: 0;
    -ms-border-radius: 0;
    border-radius: 0; }

.wizard > .actions .disabled a {
  background: #eee;
  color: #aaa; }
  .wizard > .actions .disabled a:hover, .wizard > .actions .disabled a:active {
    background: #eee;
    color: #aaa; }

.tabcontrol > .steps {
  position: relative;
  display: block;
  width: 100%; }
  .tabcontrol > .steps > ul {
    position: relative;
    margin: 6px 0 0 0;
    top: 1px;
    z-index: 1; }
    .tabcontrol > .steps > ul > li {
      float: left;
      margin: 5px 2px 0 0;
      padding: 1px;
      -webkit-border-top-left-radius: 5px;
      -webkit-border-top-right-radius: 5px;
      -moz-border-radius-topleft: 5px;
      -moz-border-radius-topright: 5px;
      border-top-left-radius: 5px;
      border-top-right-radius: 5px; }
      .tabcontrol > .steps > ul > li:hover {
        background: #edecec;
        border: 1px solid #bbb;
        padding: 0; }
      .tabcontrol > .steps > ul > li.current {
        background: #fff;
        border: 1px solid #bbb;
        border-bottom: 0 none;
        padding: 0 0 1px 0;
        margin-top: 0; }
        .tabcontrol > .steps > ul > li.current > a {
          padding: 15px 30px 10px 30px; }
      .tabcontrol > .steps > ul > li > a {
        color: #5f5f5f;
        display: inline-block;
        border: 0 none;
        margin: 0;
        padding: 10px 30px;
        text-decoration: none; }
        .tabcontrol > .steps > ul > li > a:hover {
          text-decoration: none; }

.tabcontrol > .content {
  position: relative;
  display: inline-block;
  width: 100%;
  height: 35em;
  overflow: hidden;
  border-top: 1px solid #bbb;
  padding-top: 20px; }
  .tabcontrol > .content > .body {
    float: left;
    position: absolute;
    width: 95%;
    height: 95%;
    padding: 2.5%; }
    .tabcontrol > .content > .body ul {
      list-style: disc !important; }
      .tabcontrol > .content > .body ul > li {
        display: list-item; }

.wizard .content {
  min-height: 245px;
  -webkit-border-radius: 0;
  -moz-border-radius: 0;
  -ms-border-radius: 0;
  border-radius: 0;
  overflow-y: auto; }
  .wizard .content .body {
    padding: 15px; }

.wizard .steps a {
  -webkit-border-radius: 0;
  -moz-border-radius: 0;
  -ms-border-radius: 0;
  border-radius: 0;
  -moz-transition: 0.5s;
  -o-transition: 0.5s;
  -webkit-transition: 0.5s;
  transition: 0.5s; }
  .wizard .steps a:active, .wizard .steps a:focus, .wizard .steps a:hover {
    -webkit-border-radius: 0;
    -moz-border-radius: 0;
    -ms-border-radius: 0;
    border-radius: 0; }

.wizard .steps .done a {
  background-color: rgba(0, 150, 136, 0.6); }
  .wizard .steps .done a:hover, .wizard .steps .done a:active, .wizard .steps .done a:focus {
    background-color: rgba(0, 150, 136, 0.5); }

.wizard .steps .error a {
  background-color: #F44336 !important; }

.wizard .steps .current a {
  background-color: #009688; }
  .wizard .steps .current a:active, .wizard .steps .current a:focus, .wizard .steps .current a:hover {
    background-color: #009688; }
</style>
<div>
@if(!empty($carrito))
{{--  {{dd($carrito)}}  --}}
{{-- @foreach($carrito as $elemento)
{{$elemento["packagerooms"]->PricePackage}}
@endforeach --}}
       {{-- {{dd($carrito)}}  --}}
<div class="col s3" >
      
<!--############################################################ -->


<div class="card-panel blue lighten-5">
         <div class="form-group form-float">
            <div class="form-line">
            <form action="{{ route('carrito.CompletarDatos') }}" method="POST" name="form" id="form">
            <input name="_token" value="{{ csrf_token() }}" type="hidden">

           <h4>TOTAL : <b>$<span id="total">
           @php
           $x=0;
           foreach($carrito as $data){
              $x += $data["packagerooms"]->PricePackage;
           }          
           print formatoMoneda($x);             
           @endphp
           </span></b>
           </h4>
           <input type="hidden" name="total" id="total" value="@php print $x; @endphp">
                <div class="input-field col s12 "> 
                
                   <input class="btn right" value="Siguiente" type="submit"> 
                </div>
                </form>
            </div>

        </div>
        <span style="visibility: hidden">--</span>
        </div>

<!--########################################################## -->

    </div>
    <div class="col s9 m9 l9">   
        @foreach($carrito as $key => $elemento)
        <div class="card white darken-1 hoverable" id="{{$key}}">
            <div class="card-content black-text">
                <div class="col s3 m3 l3">

                @if($elemento["tipo"] == 'Tour')
                    <img src="{{$elemento["datos"]->Imagen}}" class="materialboxed ">
                   
                    @elseif($elemento["tipo"] == 'Actividad')
                    <img src="{{$elemento["datos"]->Galeria->Imagen->Nombre}}" class="materialboxed ">
                  @else
                     <img src="{{$elemento["datos"]->Imagen_main}}" class="materialboxed ">
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
                            <span><b>Direccion :</b></span>{{$elemento["datos"]->Direccion}} <br>
                            <span><b>Categoria : {!! getEstrellas($elemento["datos"]->Categoria) !!}</b></span><br> 
                            </div>
   @if(is_array($elemento["packagerooms"]->Habitaciones->HabitacionRespuesta))                     
 
        @foreach($elemento["packagerooms"]->Habitaciones->HabitacionRespuesta as $keyy => $data)
                         <div class="col s12 m4">   
                         <span><b>Habitacion {{$keyy+1}}</b></span><br>
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
                         <span><b>Habitacion 1</b></span><br>
                             <span><b>Regimen :</b></span> {{$elemento["packagerooms"]->Habitaciones->HabitacionRespuesta->Regimen->TipoDeRegimen}}<br>
                             <span><b>Tipo de Habitacion :</b></span> {{$elemento["packagerooms"]->Habitaciones->HabitacionRespuesta->TipoHabitacion}} <br>
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
</div>

@endsection