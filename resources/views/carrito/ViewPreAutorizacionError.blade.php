@extends("estructura.app")
@section('parallax1')
@overwrite
@section('contenido')

@push('scripts')
<script>


  $('#Actualizar').click(function(){
    location.reload();

  });


</script>
@endpush
{{--  <h5>Mensaje Error PreAutorizacion : </h5> <br> --}}

 @if(!empty($SessionPreAutorizacion))
<div class="row">
      <div class="col s12 m10">
        <div class="card-panel  red lighten-4">
          <span class="black-text">


  {{-- <h3>Mensaje Error PreAutorizacion : </h3> <br>
   <h5>PReAutorizacion Error: </h5><br> --}}
                
{{--               <span>Codigo :{{$SessionPreAutorizacion->codigo}}</span><br>
                <span>Descripcion :{{$SessionPreAutorizacion->descripcion}}</span><br>
                <span>tipo :{{$SessionPreAutorizacion->mensajes[0]->tipo}}</span><br> --}}
                <h3>{{$SessionPreAutorizacion->mensajes[0]->mensaje}}</h3><br>     
                <h4>Error al Procesar la operacion, consulte a su operador financiero</h4><br>

          </span>
        </div>
      </div>
    </div>
@endif

@endsection