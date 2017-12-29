<?php

/*
|------------------------------------------------------------------------—
| Web Routes
|------------------------------------------------------------------------—
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

Route::get('/', function () {

    return view('home.index');
})->name("home");

Route::get('admin', function () {
    return view('admin_template');
});
//Route::get('pruebas', function () {
//    $name = "Chamula y Zinacantán[[Desde el aeropuerto]]";
//    $pos = strpos($name, "[[");
//    if ($pos) {
//        $nombre = substr($name, 0, $pos);
//        $thirdTxt = substr($name, $pos, strlen($name));
//        $thirdTxt = str_replace("[[", "", $thirdTxt);
//        $thirdTxt = str_replace("]]", "", $thirdTxt);
//    }
//    else{
//        $nombre="TEXT";
//    }
//
//    echo "<br>";
//    echo $nombre;
//    echo "<br>";
//    echo $thirdTxt;
//    echo "<br>";
//
//    //return view('admin_template');
//});
Route::get('pruebas', function () {

    echo "<br>5 ESTRELLAS DE LUJO<br>";
    var_dump(getCategoria(5, "5 ESTRELLAS LUJO"));

    if (stripos("ESTRELLAS", "estrella") !== false) {
        echo "SIIIIIII";
    }

    dd("______________________________________");

    foreach ($tokens as $token) {
        echo "<br>" . $token;
        if ($token === "½") {
            echo 'MEDIO';
        }
    }

    dd(count($tokens));

    return view('pruebas');
});

Route::get('buscadorHoteles', function () {
    $busqueda = session("oBuscador");
    return view('hoteles.componenteBusquedaHoteles', ["from" => "buscadores", "busqueda" => $busqueda]);
})->name("buscadorHoteles");
Route::get('buscadorTours', function () {
    $busqueda = session("oBuscadorTours");
    return view('tours.componenteBusquedaTours', ["from" => "buscadores", "busqueda" => $busqueda]);
})->name("buscadorTours");
Route::get('buscadorCircuitos', function () {
    $busqueda = session("oBuscadorCircuitos");
    return view('circuitos.componenteBusquedaCircuitos', ["from" => "buscadores", "busqueda" => $busqueda]);
})->name("buscadorCircuitos");
Route::get('buscadorActividades', function () {
    $busqueda = session("oBuscadorActividades");
    return view('actividades.componenteBusquedaActividades', ["from" => "buscadores", "busqueda" => $busqueda]);
})->name("buscadorActividades");

Route::get('hoteles', function () {
    return view('hoteles.index', ["from" => "buscadores"]);
})->name('hoteles');

Route::get('tours', function () {
    return view('tours.index', ["from" => "buscadores"]);
})->name('tours');

Route::get('circuitos', function () {
    return view('circuitos.index', ["from" => "buscadores"]);
})->name('circuitos');

Route::get('actividades', function () {
    return view('actividades.index', ["from" => "buscadores"]);
})->name('actividades');

Route::get('error', function () {
    return view('estructura.error');
})->name('error');

Route::get('carrito', function () {
    return view('carrito.index');
})->name('carrito');

// Route::get('carrito/PostAuthSuccess', function() {
//     return view('carrito.ViewPostAutorizacionSuccess');
// })->name('PostAuthSuccess');

Route::post('carrito/PostAuthSuccess', function () {
    return view('carrito.ViewPostAutorizacionSuccess');
})->name('ViewPostAutorizacionSuccess');

Route::post('carrito/PostAuthError', function () {
    return view('carrito.ViewPostAutorizacionError');
})->name('ViewPostAutorizacionError');

Route::post('carrito/PreAuthError', function () {
    return view('carrito.ViewPreAutorizacionError');
})->name('ViewPreAutorizacionError');
// Route::post('carrito/CompletarDatos', function(){
//   return view('carrito.CompletarDatos');
// })->name('carrito.CompletarDatos');
// Route::get('finalizar', function () {
//     return view('carrito.finalizar');
// })->name('finalizar');
//
//Route::get('hotel', function () {
//    return view('hoteles.busqueda');
//});
Route::post('hoteles/busqueda', 'HotelesController@busqueda')->name('hoteles.busqueda');

// Route::get('hoteles/busqueda', function () {
//     return view('hoteles.index');
// })->name('Busqueda');

Route::get('hoteles/busqueda/detalles', 'HotelesController@detalleHotel')->name('hoteles.busquedaDetalles');

Route::post('tours/busqueda', 'ToursController@busqueda')->name('tours.busqueda');
Route::get('tours/busqueda/detalles', 'ToursController@detalles')->name('tours.busquedaDetalles');

// Route::post('tours/busqueda', 'HotelesController@busqueda')->name('tours.busqueda');
Route::get('circuitos/busqueda/detalles', 'CircuitosController@detalleCircuito')->name('circuitos.busquedaDetalles');
Route::post('circuitos/busqueda', 'CircuitosController@busqueda')->name('circuitos.busqueda');

Route::get('actividades/busqueda/detalles', 'ActividadesController@detalleActividad')->name('actividades.busquedaDetalles');
Route::post('actividades/busqueda', 'ActividadesController@busqueda')->name('actividades.busqueda');

Route::post('destinos', 'ServiciosController@destinos')->name('destinos');
Route::post('addToCart', 'ServiciosController@addToCart')->name('addToCart');
// Route::get('getDestinos', 'CircuitosController@Destinos');
// Route::post('addToCart', 'HotelesController@addToCart')->name('addToCart');

Route::get('carrito', 'ServiciosController@carrito')->name('carrito');
Route::post('deleteToCart', 'ServiciosController@deleteToCart')->name('deleteToCart');
// Route::get('getDestinos', 'HotelesController@getDestinos');
// Route::post('carrito/CompletarDatos')->name('carrito.CompletarDatos');
// Route::post('carrito/Reservacion', 'ServiciosController@CompletarDatos')->name('carrito.CompletarDatos');

Route::get('carrito/Reservacion', 'ServiciosController@CompletarDatos')->name('carrito.CompletarDatos');

Route::get('carrito/finalizar', 'ServiciosController@finalizar')->name('carrito.finalizar');

Route::post('carrito/PreAutorizar', 'ServiciosController@PreAutorizar')->name('carrito.PreAutorizar');

Route::post('TourDatos', 'ServiciosController@TourDatos')->name('TourDatos');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
