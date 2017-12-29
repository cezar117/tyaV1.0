@extends("estructura.app")
@section('parallax1')
@overwrite
@section('contenido')
<h5>Mensaje Respuesta Error PostAutorizacion : </h5> <br>
<div class="row">
      <div class="col s12 m8">
        <div class="card-panel red lighten-5">
          <span class="black-text">
@if(!empty($request))
{{$request}}<br>
{{$response}}
@endif
@if(!empty($SessionPostAutorizacion))

  <h3>{{ $SessionPostAutorizacion }}</h3>

 @endif
 @if(!empty($datos))
 <h3>{{$datos}}</h3>
 @endif
          </span>
        </div>
      </div>
    </div>


@endsection