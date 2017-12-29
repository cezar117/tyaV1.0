
<!DOCTYPE html>


<html lang="{{ config('app.locale') }}" class="firefox">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="content-type" content="text/html; charset=iso-8859-1">
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <title>TripYa</title>

        <!-- CSS  -->
        <!--<link href="{{ asset('bower_components/AdminLTE/plugins/select2/select2-materialize.css') }}" type="text/css" rel="stylesheet" >-->
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link href="{{ asset('css/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">

        <!--<link href="{{ asset('bower_components/AdminLTE/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />-->
        <link href="{{ asset('css/bootstrap/bootstrap.css') }}" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" href="{{ asset('css/datepicker/daterangepicker.min.css') }}">
        <link href="{{ asset('css/materialize/materialize.css') }}" type="text/css" rel="stylesheet" media="screen,projection,print">
        <link href="{{ asset('css/materialize/style.css') }}" type="text/css" rel="stylesheet" media="screen,projection,print">
        <link href="{{ asset('css/style.css') }}" type="text/css" rel="stylesheet" media="screen,projection,print">
        <link href="{{ asset('css/slider.css') }}" type="text/css" rel="stylesheet" media="screen,projection">
        <!--<link href="{{ asset('bower_components/AdminLTE/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />-->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">

        <link href="{{ asset('css/sweetalert2.min.css') }}" type="text/css" rel="stylesheet" media="screen,projection">
        @stack('stylesheets')
    </head>
    <body>
        <nav class="white principal" role="navigation">
            <div class="nav-wrapper container">
                <a id="logo-container" href="{{ route('home') }}" class="brand-logo">
                    <img border="0" alt="TripYa" src="{{asset('images/logo-10.png')}}"></a>
                <ul class="right hide-on-med-and-down">
                    <li><a href="{{ route('hoteles') }}">Hoteles</a></li>
                    <li><a href="{{ route('tours') }}">Tours</a></li>
                    <li><a href="{{ route('circuitos') }}">Circuitos</a></li>
                    <li><a href="{{ route('actividades') }}">Actividades</a></li>
                    <li><a href="{{ route('carrito') }}"><i class="material-icons" style="margin-left: 0px;
                                                            position: absolute;" >shopping_cart</i><span class="new badge carritoBadge" style="margin-left: 25px;" data-badge-caption="">@if(session('carrito') !==null ) {{ count( session('carrito') ) }} @else 0 @endif</span></a></li>
                </ul>

                <ul id="nav-mobile" class="side-nav">
                    <li><a href="{{ route('hoteles') }}">Hoteles</a></li>
                    <li><a href="{{ route('tours') }}">Tours</a></li>
                    <li><a href="{{ route('circuitos') }}">Circuitos</a></li>
                    <li><a href="{{ route('carrito') }}"><i class="material-icons" >shopping_cart</i><span  class="new badge carritoBadge"  data-badge-caption="">@if(session('carrito') !==null ) {{ count( session('carrito') ) }} @else 0 @endif</span></a></li>
                </ul>
                <a href="#" data-activates="nav-mobile" class="button-collapse"><i class="material-icons">menu</i></a>
            </div>
        </nav>
        @yield('navBuscador')






        @yield('parallax1')
        @section('parallax1')
        @include('estructura.seccionParallax', ['titulo' => 'TripYa','descripcion'=>'Descripcion de ejemplo','imagen'=>'background1.jpg'])
        @endsection


        @yield('contenido.ofertas')
        <!--   Icon Section   -->
        <div class="container-fluid">
            <div class="row">
                @yield('contenido')

            </div>
        </div>
        <div class="container">
            <div class="row">
                @yield('contenido.container')

            </div>
        </div>

        @include('estructura.chat')


        <footer class="page-footer teal">
            <div class="container">
                <div class="row">
                    <div class="col l6 s12">
                        <h5 class="white-text">TripYa</h5>
                        <p class="grey-text text-lighten-4">Esta es una pequeña descripcion de nuestro sitio web.</p>
                    </div>
                    <div class="col l3 s12">
                        <h5 class="white-text">Ajustes</h5>
                        <ul>
                            <li><a class="white-text" href="#!">Link 1</a></li>
                            <li><a class="white-text" href="#!">Link 2</a></li>

                        </ul>
                    </div>
                    <div class="col l3 s12">
                        <h5 class="white-text">Conecta</h5>
                        <ul>
                            <li><a class="white-text" href="#!">Link 1</a></li>
                            <li><a class="white-text" href="#!">Link 2</a></li>

                        </ul>
                    </div>
                </div>
            </div>
            <div class="footer-copyright">
                <div class="container">
                    Made by <a class="brown-text text-lighten-3" href="http://materializecss.com">TripYa</a>
                </div>
            </div>
        </footer>
        <!--  Scripts-->
        <!--<script src="{{ asset('bower_components/AdminLTE/bootstrap/js/bootstrap.min.js') }}"></script>-->
        <script src="{{ asset('js/jquery-3.2.1.min.js') }}"></script>
        <script src="{{ asset('js/materialize/materialize.js') }}"></script>
        <script src="{{ asset('js/scripts.js') }}"></script>
        <script src="{{ asset('js/autosize.min.js') }}"></script>

        <script src="{{ asset('js/materialize/init.js') }}"></script>
        <script type="text/javascript" src="{{asset('js/datepicker/moment.min.js')}}"></script>
        <script type="text/javascript" src="{{asset('js/datepicker/jquery.daterangepicker.min.js')}}"></script>

        <script src="{{ asset('js/jquery.payform.js') }}"></script>
        <script src="{{ asset('js/sweetalert2.min.js') }}"></script>
        <script src="{{ asset('js/jquery.steps.min.js') }}"></script>
        <script src="{{ asset('js/sticky-kit.min.js') }}"></script>

        
        <!--<script src="{{ asset('js/live.js') }}"></script>-->





        @stack('scripts')
        <script>
            $(document).ready(function() {
//            autosize($("#msg"));
            @stack('document.ready')
            }
            );
        </script>
    </body>
</html>
