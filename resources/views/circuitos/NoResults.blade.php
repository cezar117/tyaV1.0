@extends("estructura.app")
@section('parallax1')
@overwrite
@section('contenido')

<div class="row">
        <div class="col s12 m6">
          <div class="card blue-grey darken-1">
            <div class="card-content white-text">
              <span class="card-title">Oops !!</span>
              <h2>No se encontraron Resultados</h2>
            </div>
            <div class="card-action">
              <a href="javascript:window.history.back();" >volver a Buscar</a>
              <a href="#">This is a link</a>
            </div>
          </div>
        </div>
      </div>
@endsection