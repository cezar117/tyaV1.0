@extends("estructura.app")

@push('stylesheets')
<style>
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
        width: 100%;
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

<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAA1lwpDR28QuxwEIFcKT96Z8mvVN8EnxA"></script>
<script>
    var map;
    var markers = [];
    function initMap() {
    {{--var uluru = {lat: {{ $detalle - > Latitud }}, lng: {{ $detalle - > Longitud }} }; --}}
    var uluru = {lat: 0, lng: 0 };
    map = new google.maps.Map(document.getElementById('mapa'), {
    zoom: 17,
            center: uluru
    });
    // Adds a marker at the center of the map.
    addMarker(uluru);
    }

    function addMarker(location) {
    var marker = new google.maps.Marker({
    position: location,
            map: map
    });
    markers.push(marker);
    }

    function plegardesplegar(identificacion) {
    var elemento = document.getElementById(identificacion);
    if (elemento.className == "visible") {
    elemento.className = "invisible";
    } else {
    elemento.className = "visible";
    }
    }

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

    function toggleDescripcion(){
    $(".readMinus").toggle();
    $(".readMore").toggle();
    if ($(".descripcionCard").hasClass("active")){
    $(".descripcionCard").removeClass("active");
    } else{
    $(".descripcionCard").addClass("active");
    }


    }

    $(document).ready(function() {
    $('#galeria').carousel({fullWidth: true});
    $('#galeria').carousel({fullWidth: true});
    $("#aUbicacion").one("click", function(){
    initMap();
    });
    var slide = $('.slider-single');
    var slideTotal = slide.length - 1;
    var slideCurrent = - 1;
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
    });</script>
@endpush

@section('parallax1')
@endsection
@section('contenido.container')
{{--dd($actividad)--}}

<div class="card white darken-1 hoverable">
    <div class="card-content">

        <div class="col s12 m12 l7">
            <div class="slider-container">
                <div class="slider-content">
                    @if(isset($actividad->Galeria->Imagen))
                    @forelse($actividad->Galeria->Imagen as $key => $imagen)
                    <div class="slider-single">
                        <img class="slider-single-image" src="{{ $imagen->Nombre }}" alt="{{ $key }}" >
                    </div>
                    @empty
                    <div class="slider-single">
                        <img class="slider-single-image" src="{{ asset('images/sin_foto.png') }}" alt="{{ $key }}" >
                    </div>
                    @endforelse
                    @else
                    <div class="slider-single active">
                        <img class="slider-single-image" src="{{ $actividad->Galeria->Imagen->Nombre }}" alt="1" >
                    </div>
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
        <div class="col s12 m12 l5">

            <h5>{{ $actividad->Nombre }}</h5>
            <hr>

            <div class="col s12 m6">
                <div><b>Inicio: </b>{{ $actividad->Ciudad }}</div><br>
                @isset($actividad->Duraciones->string)
                <div><span><b>Duración: </b>
                        @if(count($actividad->Duraciones->string)>1)
                        @foreach(objetoParseArray($actividad->Duraciones->string) as $duracion)
                        {{$duracion}}@if (!$loop->last),@endif
                        @endforeach
                        @else
                        {{$actividad->Duraciones->string}}
                        @endif
                    </span></div><br>
                @endisset
                @isset($actividad->ActividadPara->string)
                <div><span><b>Para: </b>

                        @if(count($actividad->ActividadPara->string) > 1)
                        @foreach(objetoParseArray($actividad->ActividadPara->string) as $para)
                        {{$para}}@if (!$loop->last),@endif
                        @endforeach
                        @else
                        {{$actividad->ActividadPara->string}}
                        @endif

                    </span></div><br>
                @endisset
                @isset($actividad->Incluye->string)
                <div><span><b>Incluye: </b>


                        @if(count($actividad->Incluye->string) > 1)
                        @foreach(objetoParseArray($actividad->Incluye->string) as $incluye)
                        {{$incluye}}@if (!$loop->last),@endif
                        @endforeach
                        @else
                        {{$actividad->Incluye->string}}
                        @endif

                    </span></div><br>
                @endisset
            </div>
            {{--
            <div class="col s12 m6">
                <div><b>Noches: </b>{{ getNumeroNoches() }}</div><br>
        <div><b>Salida: </b> {{ getCheckOut() }}</div><br>
    </div>

    <div class="col s12 m12">
        <div><b>Amenidades: </b> @isset($detalle->Amenidades->Amenidad){{ getAmenidadesMin( $detalle->Amenidades->Amenidad) }}@endisset</div>
    </div>
    --}}
</div>
<div class="row"></div>
</div>
<div class="card-action">

    @if($actividad->Descripcion !== "")
    <div class="descripcionCard">
        <span>{!! $actividad->Descripcion !!} </span>
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

        <div class="card white darken-1 hoverable">
            <div class="card-content black-text">
                <span>PAQUETES</span>
                <table class="tablaPaquetes striped">

                    @forelse(objetoParseArray($paquetes) as $paquete)
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Duración</th>
                            <th>Datos</th>
                            <th>Tarifa adulto</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach(objetoParseArray($paquete->ActividadTarifas->ActividadTarifas) as $tarifa)
                        <tr>
                            <td>{{ $tarifa->Nombre }}</td>
                            <td>{{ $tarifa->Comentarios }}</td>
                            <td><a class="tooltipped" data-position="top" data-delay="50" data-tooltip="@isset($tarifa->PoliticasCancelacion->politica){{ getTextoPoliticas($tarifa->PoliticasCancelacion->politica) }}@endisset">Politicas de cancelación</a></td>
                            <td>$ {{ formatoMoneda($tarifa->TarifaPrecioAdulto) }}</td>
                            <td>$ {{ formatoMoneda($tarifa->Total) }}</td>
                        </tr>
                        @endforeach
                        <tr>
                            <th colspan="1"><a href="#">Comentarios</a> </th>
                            <th colspan="1"> TOTAL: $ {{ formatoMoneda($paquete->PricePackage) }}</th>

                    <form id="form{{$paquete->PackageId}}" action="{{route('addToCart')}}" method="POST">
                        <input name="_token" value="{{ csrf_token() }}" type="hidden">
                        <input type="hidden" name="tipo" value="Actividad">
                        <input type="hidden" name="idActividad" value="{{$actividad->Id}}">
                        <input type="hidden" name="idPaquete" value="{{$paquete->PackageId}}">

                    </form>
                    <th colspan="1"> <a class="waves-effect waves-light btn" onclick="addToCart($('#form{{$paquete->PackageId}}'));">Agregar</a></th>
                    </tr>

                    </tbody>
                    @empty
                    <center><h4>No hay paquetes disponibles en este hotel.</h4></center>
                    @endforelse
                </table>
            </div>
            <div class="card-action">
                <div class="">
                    <a href="#">Footer</a>
                </div>
            </div>
        </div>
        {{--
<div class="card white darken-1 hoverable">
    <div class="card-content black-text">
        <div class="row">
            <div class="col s12">
                <ul class="tabs">
                    <li class="tab col s3"><a class="active" href="#detalles">Detalles</a></li>
                    <li class="tab col s3"><a id="aUbicacion" href="#ubicacion">Ubicación</a></li>
                    <li class="tab col s3"><a href="#amenidades">Amenidades</a></li>
                    <li class="tab col s3"><a href="#politicas">Políticas</a></li>
                </ul>
            </div>
            <div id="detalles" class="col s12">
                <div class="row"></div>

        <p>{!! $detalle->Descripciones->Descripcion->Descripcioncompleta !!} </p>
            </div>
            <div id="ubicacion" class="col s12">
                <div onclick="javascript:plegardesplegar('divMapUp');" class="visible" id="divMapUp"></div>
                <div id="mapa"></div>
            </div>
            <div id="amenidades" class="col s12">
                <ul class="twoColumns">
                    @isset($detalle->Amenidades->Amenidad)
                    @foreach(objetoParseArray($detalle->Amenidades->Amenidad) as $amenidad)
                                                                                <li>{{$amenidad->Descripcion}}</                                                                                                li>
                    @endfo                                                                                                    reach
                                                                                                                        @endisset
                                                                                             </ul>
            </div>
            <                                                                                                                                                        div id="politicas" class="col s12">
@if(!empty($hote                                                                                                                                                        l->PoliticasCancelacion->politica))
                @foreach(objetoParseArray($hotel->PoliticasCa                                      ncelacion->politica) as $politica)

                                                                         {{getTextoPoliticas($poli                                                                                                tica)}}
                @e                                                                                            ndforeach
@endi                                                                f
                                                                           </div>
        </div>
    <                                                                    /div>
    <div class="c                                                                                              ard-action">
        <div class="">
            <a                                                href="#">F                                                                            ooter</a>
 </div>
    </div>
</div>--}}


@endsection