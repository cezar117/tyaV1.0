@extends("estructura.app")
@section('parallax1')
@overwrite
@section('contenido')

@push('scripts')
<script>
  function imprSelec(imprime)
{
    var ficha=document.getElementById(imprime);var ventimp=window.open(' ','popimpr');ventimp.document.write(ficha.innerHTML);ventimp.document.close();ventimp.print();ventimp.close();
}

</script>
@endpush
 <div class="container">        
@if(!empty($SessionPostAutorizacion))
<div class="col s3 m4" >
  <div class="card-panel blue lighten-5" id="stiker2" >
      <div id="wizard_vertical" class="wizard clearfix vertical">
        <div class="steps clearfix">
          <ul role="tablist">
            <li role="tab" class="last done" aria-disabled="false" aria-selected="true"><a id="wizard_vertical-t-0" href="#" aria-controls="wizard_vertical-p-0"><span class="current-info audible">current step: </span><span class="number">1.</span> Listado de Compras</a></li>

            <li role="tab" class="last done" aria-disabled="true"><a id="wizard_vertical-t-1" href="#" aria-controls="wizard_vertical-p-1"><span class="number">2.</span> Completar Datos</a></li>
            
            <li role="tab" class="last done" aria-disabled="true"><a id="wizard_vertical-t-2" href="#w" aria-controls="wizard_vertical-p-2"><span class="number">3.</span> Forma de Pago</a></li>

           <li role="tab" class="first current" aria-disabled="false" aria-selected="false"><a id="wizard_vertical-t-3" href="#" aria-controls="wizard_vertical-p-3"><span class="number">4.</span> Compra Exitosa</a></li>
          </ul>
        </div>
      </div>
</div>

          @foreach($SessionPostAutorizacion as  $value) 
          		@if($value->codigo == 1)

     <div class="row">
      <div class="col s3 m8">
        <div class="card-panel red lighten-4">
          <span class="black-text">

          		  {{-- <h4>PostAutorizacion Error: </h3><br> --}}
                <h4>{{$value->mensajes[0]->mensaje}} :</h4><br> 
          		  <h5>Error al Procesar la operacion, consulte a su operador financiero.</h5><br>
          		{{-- <span>Codigo :{{$value->codigo}}</span><br> --}}
          			<span>Descripcion : <b>{{$value->descripcion}}</b></span><br>
          			{{-- <span>tipo :{{$value->mensajes[0]->tipo}}</span><br> --}}
          </span>
        </div>
      </div>
    </div>

        @else
 {{Eliminar_carrito()}}

           <div class="row">
            <div class="col s3 m12">
              <div class="card-panel green lighten-4">
                <span class="black-text">

          	  {{-- <h3>PostAutorizacion Success: </h3><br> --}}
          	  <h5>{{$value->descripcion}} :</h5><br>
	          {{-- <span>Codigo :{{$value->codigo}}</span><br> --}}
	          {{-- <span>Descripcion :</span><br> --}}
	          <span>Folio :</span><b>{{$value->data->Folio}}</b><br>
	          <span>Fecha :</span><b>{{$value->data->FechaTransaccion}}</b><br>
	         {{--  <span>IdtipoRespuestaTF :</span>{{$value->data->DetallesAdicionales[0]->IdTipoRespuestaTransaccionFinanciera}}<br>
 --}}
	       		        </span>
                 </div>
              </div>
            </div>
</div>
          		  @endif
            @endforeach
          @endif
   @if(!empty($VoucherDatos))   

   {{-- {{dd($VoucherDatos)}} --}}
  <div class="" align="center">

 <div class="row">
        <div class="col s12 m7">
          <div class="card">         
            <div style="background-color: #f9fbe7" class="card-content" id="imprime">
           {{--  <p>{{dd($VoucherDatos)}}</p> --}}
            @foreach($VoucherDatos as $key => $value)   

            <!--########################################################-->   
    @if($value->DetallesGenerales->TipoProducto == 'HOTEL')   
            <table class="responsive-table" style="border: 3">
            <thead>
              <tr>
                <th>OTISA</th>
                <th>DEMO</th>
                <th>VOUCHER</th>
              </tr>
            </thead>
 <tbody>
  <tr>
    <th><strong>PAIS:</strong><span>  {{$value->DetallesGenerales->Pais}}</span></th>
    <th><strong>Ciudad: </strong>{{$value->DetallesGenerales->Ciudad}}</th>
    <th><strong>Nombre del Agente: </strong> {{$value->NombreAgente}}</th>
  </tr>
  <tr>
    <td><strong>Clave de Reservacion: </strong><span style="background-color: #f2f2f2;">{{$value->DetallesGenerales->CodigoReserva}}</span></td>
    <td><strong>Tipo de Servicio: </strong>{{$value->TipoServicio}}</td>
    <td><strong>Fecha de compra: </strong>{{$value->DetallesGenerales->FechaCompra}}</td>
  </tr>
  <tr>
    <td colspan="2"><strong>Titula de la Reservacion:</strong><span style="background-color: #f2f2f2;">{{$value->DetallesGenerales->Titular}}</span></td>
    <td><strong>Fecha de Iniciio:</strong>{{$value->DetallesGenerales->FechaEntrada}}</td>
  </tr>
  <tr>
    <td colspan="2"><strong>Nombre del producto</strong>{{$value->DetallesGenerales->NombreProducto}}</td>
    <td><strong>Fecha de Final: </strong>{{$value->DetallesGenerales->FechaSalida}}</td>
  </tr>
  <tr>
    <td><strong>Adultos: </strong>{{$value->DetallesGenerales->Adultos}}</td>
    <td colspan="2"><strong>Menores :</strong>{{$value->DetallesGenerales->Menores}}</td>
  </tr>
  <tr>
    <td colspan="3"><strong>Direccion:</strong>{{$value->DetallesGenerales->DireccionHotel}}</td>
  </tr>
  <tr>
    <td colspan="3"></td>
  </tr>
  </tbody>
</table>
{{-- {{dd($value)}} --}}
  @foreach(objetoParseArray($value->DetallesHabitaciones->ListaDeHabitaciones->Habitacion) as $listaHabitacion) 
  <table class="responsive-table" style="border: 3">
    <tr style="border: 3">
      <th>Nombre del titular por Habitacion</th>
      <th>Numero de<br>Habitaciones</th>
      <th>Tipo de<br> Habitacion</th>
      <th>Adultos</th>
      <th>Menores</th>
      <th></th>
    </tr>
    <tr style="border: 3">
      <td style="background-color: #f2f2f2; border: 3">{{$listaHabitacion->Titular}}</td>
      <td style="background-color: #f2f2f2; border: 3">{{$listaHabitacion->NumeroHabitaciones}}</td>
      <td style="background-color: #f2f2f2; border: 3">{{$listaHabitacion->TipoHabitacion}}</td>
      <td style="background-color: #f2f2f2; border: 3">{{$listaHabitacion->Adultos}} Adulto(s)</td>
      <td style="background-color: #f2f2f2; border: 3">{{$listaHabitacion->Menores}} Menor(es)</td>
      <td style="background-color: #f2f2f2; border: 3">{{$listaHabitacion->PlanAlimentos}}</td>
    </tr> 
  </table>
@endforeach
<table class="responsive-table">
  <tbody>
     <tr>
 <td><span>Observaciones : </span><p style="background-color: #f2f2f2;">{{$value->Observacion}}</p></td>
 </tr>
<tr>
 <td><span><strong>Correo Electronico: </strong></span><span style="background-color: #f2f2f2;">{{$value->Email}}</span></td>
 <td> <span><strong>No. Telefonico: </strong></span><span style="background-color: #f2f2f2;">{{$value->Telefono}}</span></td>
 <td><span><strong>localizador: </strong></span><span style="background-color: #f2f2f2;">{{$value->Localizador}}</span></td>
 </tr>
  </tbody>
</table>
{{-- <span>Observaciones : {{$value->Observacion}}</span>
<span>Incluye</span>
<p></p>
<span>Amenidades :</span>
<p style="background-color: #f2f2f2;">  @foreach($value->Amenidades->string as  $Amenidades)
         {{$Amenidades}},
        @endforeach</p>
<span><strong>Correo Electronico: </strong>{{$value->Email}}</span><span><strong>No. Telefonico: </strong>{{$value->Telefono}}</span><span><strong>localizador: </strong>{{$value->Localizador}}</span>
 --}}
<hr>


@elseif($value->DetallesGenerales->TipoProducto == 'TOUR') 
        <table class="responsive-table" style="border: 3">
                <thead>
                  <tr>
                    <th>OTISA</th>
                    <th>DEMO</th>
                    <th>VOUCHER</th>
                  </tr>
                </thead>
     <tbody>
      <tr>
        <th><strong>PAIS:</strong>  <span style="background-color: #f2f2f2;">{{$value->DetallesGenerales->Pais}}</span></th>
        <th><strong>Ciudad: </strong><span style="style=background-color: #f2f2f2;">{{$value->DetallesGenerales->Ciudad}}</span></th>
        <th><strong>Nombre del Agente: </strong><span style="background-color: #f2f2f2;"> {{$value->NombreAgente}}</span></th>
      </tr>
      <tr>
        <td><strong>Clave de Reservacion: </strong><span style="background-color: #f2f2f2;">{{$value->DetallesGenerales->CodigoReserva}}</span></td>
        <td><strong>Tipo de Servicio: </strong><span style="background-color: #f2f2f2;">{{$value->TipoServicio}}</span></td>
        <td><strong>Fecha de compra: </strong><span style="background-color: #f2f2f2;">{{$value->DetallesGenerales->FechaCompra}}</span></td>
      </tr>
      <tr>
        <td colspan="2"><strong>Titula de la Reservacion:</strong><span style="background-color: #f2f2f2;">{{$value->DetallesGenerales->Titular}}</span></td>
        <td><strong>Fecha de Inicio:</strong><span style="background-color: #f2f2f2;">{{$value->DetallesGenerales->FechaEntrada}}</span></td>
      </tr>
      <tr>
        <td colspan="2"><strong>Nombre del producto</strong><span style="background-color: #f2f2f2;">{{$value->DetallesGenerales->NombreProducto}}</span></td>
        <td><strong>Fecha de Final: </strong><span style="background-color: #f2f2f2;">{{$value->DetallesGenerales->FechaSalida}}</span></td>
      </tr>
      <tr>
        <td><strong>Adultos: </strong><span style="background-color: #f2f2f2;">{{$value->DetallesGenerales->Adultos}}</span></td>
        <td colspan="2"><strong>Menores :</strong><span style="background-color: #f2f2f2;">{{$value->DetallesGenerales->Menores}}</span></td>
      </tr>
  {{--     <tr>
        <td colspan="3"><strong>Direccion:</strong>{{$value->DetallesGenerales->DireccionHotel}}</td>
      </tr> --}}
      <tr>
        <td colspan="3"></td>
      </tr>

  <tr style="border: 3"><td>PickUp</td></tr>
  <tr><td style="background-color: #f2f2f2; border: 3">Destino: </td></tr>
  <tr><td style="background-color: #f2f2f2; border: 3">Fecha: </td></tr>
  <tr><td style="background-color: #f2f2f2; border: 3">Hora de llegada</td></tr>

<tr style="border:3;background-color: #f2f2f2;"><td>DropOff</td></tr>
  <tr><td>Fecha: </td></tr>
  <tr><td>Hora de llegada</td></tr>
  <tr><td>Horigen</td></tr>


@foreach(objetoParseArray($value->DetallesItinerarios->ListaDeItinerarios) as $listaItinerario)
<tr><td><strong>Fecha</strong></td><td><strong>Itinerario</strong></td><td><strong>DropOff</strong></td></tr>
<tr><td style="background-color: #f2f2f2; border: 3">{{$listaItinerario->Itinerario->Fecha}}</td><td style="background-color: #f2f2f2; border: 3">{{$listaItinerario->Itinerario->Ruta}}</td><td style="background-color: #f2f2f2; border: 3">{{$listaItinerario->Itinerario->Fecha}}</td></tr>
 @endforeach 

 <tr>
 <td><strong>Observaciones : </strong><p style="background-color: #f2f2f2;">{{$value->Observacion}}</p></td>
 </tr>
<tr>
 <td><span><strong>Correo Electronico: </strong></span><span style="background-color: #f2f2f2;">{{$value->Email}}</span></td>
 <td> <span><strong>No. Telefonico: </strong></span><span style="background-color: #f2f2f2;">{{$value->Telefono}}</span></td>
 <td><span><strong>localizador: </strong></span><span style="background-color: #f2f2f2;">{{$value->Localizador}}</span></td>
 </tr>
      </tbody>
    </table>
    <hr>
@else
<!-- generica solo hotel -->


<table class="responsive-table" style="border: 3">
            <thead>
              <tr>
                <th>OTISA</th>
                <th>DEMO</th>
                <th>VOUCHER</th>
              </tr>
            </thead>
 <tbody>
  <tr>
    <th><strong>PAIS:</strong><span style="background-color: #f2f2f2;">  {{$value->DetallesGenerales->Pais}}</span></th>
    <th><strong>Ciudad: </strong><span style="background-color: #f2f2f2;">{{$value->DetallesGenerales->Ciudad}}</span></th>
    <th><strong>Nombre del Agente: </strong> <span style="background-color: #f2f2f2;">{{$value->NombreAgente}}</span></th>
  </tr>
  <tr>
    <td><strong>Clave de Reservacion: </strong><span style="background-color: #f2f2f2;">{{$value->DetallesGenerales->CodigoReserva}}</span></td>
    <td><strong>Tipo de Servicio: </strong> <span style="background-color: #f2f2f2;">{{$value->TipoServicio}}</span></td>
    <td><strong>Fecha de compra: </strong><span style="background-color: #f2f2f2;">{{$value->DetallesGenerales->FechaCompra}}</span></td>
  </tr>
  <tr>
    <td colspan="2"><strong>Titula de la Reservacion: </strong><span style="background-color: #f2f2f2;">{{$value->DetallesGenerales->Titular}}</span></td>
    <td><strong>Fecha de Iniciio: </strong><span style="background-color: #f2f2f2;">{{$value->DetallesGenerales->FechaEntrada}}</span></td>
  </tr>
  <tr>
    <td colspan="2"><strong>Nombre del producto : </strong><span style="background-color: #f2f2f2;">{{$value->DetallesGenerales->NombreProducto}}</span></td>
    <td><strong>Fecha de Final: </strong><span style="background-color: #f2f2f2;">{{$value->DetallesGenerales->FechaSalida}}</span></td>
  </tr>
  <tr>
    <td><strong>Adultos: </strong><span style="background-color: #f2f2f2;">{{$value->DetallesGenerales->Adultos}}</span></td>
    <td colspan="2"><strong>Menores :</strong><span style="background-color: #f2f2f2;">{{$value->DetallesGenerales->Menores}}</span></td>
  </tr>
  <tr>
    <td colspan="3"><strong>Direccion:</strong><span style="background-color: #f2f2f2;">{{$value->DetallesGenerales->DireccionHotel}}</span></td>
  </tr>
  <tr>
    <td colspan="3"></td>
  </tr>
  </tbody>
</table>
{{-- {{dd($value)}} --}}
 @foreach(objetoParseArray($value->DetallesHabitaciones->ListaDeHabitaciones->Habitacion) as $listaHabitacion) 
  <table class="responsive-table" style="border: 3">
    <tr style="border: 3">
      <th>Nombre del titular por Habitacion</th>
      <th>Numero de<br>Habitaciones</th>
      <th>Tipo de<br> Habitacion</th>
      <th>Adultos</th>
      <th>Menores</th>
      <th></th>
    </tr>
    <tr style="border: 3">
      <td style="background-color: #f2f2f2; border: 3">{{$listaHabitacion->Titular}}</td>
      <td style="background-color: #f2f2f2; border: 3">{{$listaHabitacion->NumeroHabitaciones}}</td>
      <td style="background-color: #f2f2f2; border: 3">{{$listaHabitacion->TipoHabitacion}}</td>
      <td style="background-color: #f2f2f2; border: 3">{{$listaHabitacion->Adultos}} Adulto(s)</td>
      <td style="background-color: #f2f2f2; border: 3">{{$listaHabitacion->Menores}} Menor(es)</td>
      <td style="background-color: #f2f2f2; border: 3">{{$listaHabitacion->PlanAlimentos}}</td>
    </tr> 
  </table>
@endforeach
<table class="responsive-table">
  <tbody>
     <tr>
 <td><span>Observaciones : </span><p style="background-color: #f2f2f2;">{{$value->Observacion}}</p></td>
 </tr>
<tr>
 <td><span><strong>Correo Electronico: </strong></span><span style="background-color: #f2f2f2;">{{$value->Email}}</span></td>
 <td> <span><strong>No. Telefonico: </strong></span><span style="background-color: #f2f2f2;">{{$value->Telefono}}</span></td>
 <td><span><strong>localizador: </strong></span><span style="background-color: #f2f2f2;">{{$value->Localizador}}</span></td>
 </tr>
  </tbody>
</table>


{{-- {{dd($VoucherDatos)}} --}}



    @endif 
  @endforeach 
@endif 
  <!-- ########################################################-->

            </div>
            <div class="card-action">
            <input type="button" class="btn btn-default btn-md" value="Imprimir Voucher" onclick="javascript:imprSelec('imprime');function imprSelec(imprime){var ficha=document.getElementById(imprime);var ventimp=window.open(' ','popimpr');ventimp.document.write(ficha.innerHTML);ventimp.document.close();ventimp.print();ventimp.close();};" />
            </div>
          </div>
        </div>
      </div>

</div>
    @endsection