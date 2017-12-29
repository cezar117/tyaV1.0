
<?php

// use App\Http\Servicios\ServicioOtisa;
// // // // use GuzzleHttp\Exception\ClientException;
// // use GuzzleHttp\Psr7\Request as Requestt;
// use GuzzleHttp\Client;
// use GuzzleHttp\Psr7;
// use Illuminate\Http\Request;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7;
use GuzzleHttp\Psr7\Request as Requestt;
use App\Http\Servicios\ServicioOtisa;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
function carritoOtisa()
{
    $cantidad            = count(session('carrito'));
    $rs                  = resumenPreReserva();
    $totalOtisaHotel     = 0;
    $totalOtisaTour      = 0;
    $totalOtisaActividad = 0;
    $totalOtisaCircuito  = 0;
    $hoteles             = null;
    $tours               = null;
    $actividades         = null;
    $circuitos           = null;

    if (isset($rs->xml->obtenerServiciosResult->ResumenPreReservaRespuesta->ListaHoteles->HotelPreReserva)) {
        $totalOtisaHotel = count(objetoParseArray($rs->xml->obtenerServiciosResult->ResumenPreReservaRespuesta->ListaHoteles->HotelPreReserva));
        $hoteles         = objetoParseArray($rs->xml->obtenerServiciosResult->ResumenPreReservaRespuesta->ListaHoteles->HotelPreReserva);

    }
    if (isset($rs->xml->obtenerServiciosResult->ResumenPreReservaRespuesta->ListaDeTours->TourPreReserva)) {
        $totalOtisaTour = count(objetoParseArray($rs->xml->obtenerServiciosResult->ResumenPreReservaRespuesta->ListaDeTours->TourPreReserva));
        $tours          = objetoParseArray($rs->xml->obtenerServiciosResult->ResumenPreReservaRespuesta->ListaDeTours->TourPreReserva);
    }
    if (isset($rs->xml->obtenerServiciosResult->ResumenPreReservaRespuesta->ListaDeActividades->ActividadPreReserva)) {
        $totalOtisaActividad = count(objetoParseArray($rs->xml->obtenerServiciosResult->ResumenPreReservaRespuesta->ListaDeActividades->ActividadPreReserva));
        $actividades         = objetoParseArray($rs->xml->obtenerServiciosResult->ResumenPreReservaRespuesta->ListaDeActividades->ActividadPreReserva);
    }
    if (isset($rs->xml->obtenerServiciosResult->ResumenPreReservaRespuesta->ListaDeCircuitos->CircuitoPreReserva)) {
        $totalOtisaCircuito = count(objetoParseArray($rs->xml->obtenerServiciosResult->ResumenPreReservaRespuesta->ListaDeCircuitos->CircuitoPreReserva));
        $citcuitos          = objetoParseArray($rs->xml->obtenerServiciosResult->ResumenPreReservaRespuesta->ListaDeCircuitos->CircuitoPreReserva);
    }

    $totalOtisa = $totalOtisaHotel + $totalOtisaTour + $totalOtisaActividad + $totalOtisaCircuito;
    if ($cantidad != $totalOtisa) {
        session()->forget('carrito');
        session()->forget('carrito_temp');
        $carrito = array();

        if ($hoteles != null) {
            foreach ($hoteles as $hotel) {
                $elementoH = [
                    "id"           => $hotel->Id,
                    "idPaquete"    => $hotel->Paquetes->PackageRooms->PackageId,
                    "tipo"         => 'Hotel',
                    "datos"        => $hotel,
                    "packagerooms" => $hotel->Paquetes->PackageRooms,
                    "checkIn"      => $hotel->FechaInicio,
                    "checkOut"     => $hotel->FechaFin,
                    "adultos"      => $hotel->Paquetes->PackageRooms->Habitaciones->HabitacionRespuesta->Adultos,
                    "menores"      => $hotel->Paquetes->PackageRooms->Habitaciones->HabitacionRespuesta->Menores,
                    "noches"       => $hotel->Noches,
                    "edad_menores" => $hotel->Paquetes->PackageRooms->Habitaciones->HabitacionRespuesta->edadMenores,
                ];
                array_push($carrito, $elementoH);
            }
        }
        if ($tours != null) {
            foreach ($tours as $tour) {
                $elementoT = [
                    "id"           => $tour->TourId,
                    "idPaquete"    => $tour->Paquetes->PackageTours->PackageId,
                    "tipo"         => 'Tour',
                    "packagerooms" => $tour->Paquetes->PackageTours,
                    "datos"        => $tour,
                    "checkIn"      => $tour->FechaInicio,
                    "adultos"      => $tour->Paquetes->PackageTours->TourTarifas->TourTarifas->Adultos,
                    "menores"      => $tour->Paquetes->PackageTours->TourTarifas->TourTarifas->Menores,
                    "edad_menores" => null,
                ];
            }
            array_push($carrito, $elementoT);
        }
        if ($actividades != null) {
            foreach ($actividades as $actividad) {
                $elementoA = [
                    "id"           => $actividad->Id,
                    "idPaquete"    => $actividad->Packages->PackageActividad->PackageId,
                    "tipo"         => 'Actividad',
                    "datos"        => $actividad,
                    "packagerooms" => $actividad->Packages->PackageActividad,
                    "checkIn"      => null,
                    "adultos"      => $actividad->Packages->PackageActividad->ActividadTarifas->ActividadTarifas->Adultos,
                    "menores"      => $actividad->Packages->PackageActividad->ActividadTarifas->ActividadTarifas->Menores,
                    "edad_menores" => null,
                ];
            }
            array_push($carrito, $elementoA);
        }
        if ($circuitos != null) {
            foreach ($circuitos as $circuito) {
                $elementoC = [];
            }
            array_push($carrito, $elementoC);
        }
        session(["carrito" => $carrito]);
    }

}
function quitarProducto($idSession, $idPaquete, $idProducto)
{
    $ServicioOtisa = new ServicioOtisa();
    $idSession     = ObtenerSession();
    $response      = $ServicioOtisa->quitarProducto($idSession, $idPaquete, $idProducto);
}

function iconos($str)
{
    switch ($str) {
        case 'BIRTH':
            return '<i class="fa fa-calendar fa-2x" aria-hidden="true"></i> ';
            break;
        case 'CITIZENSHIP':
            return '<i class="fa fa-globe fa-2x" aria-hidden="true"></i> ';
            break;
        case 'HOTEL':
            return '<i class="fa fa-h-square fa-2x" aria-hidden="true"></i> ';
            break;
        case 'NACIONALIDAD':
            return '<i class="fa fa-globe fa-2x" aria-hidden="true"></i> ';
            break;
        case 'NAMEPASSPORT':
            return '<i class="fa fa-id-card fa-2x" aria-hidden="true"></i> ';
            break;
        case 'PHONENUMBER':
            return '<i class="fa fa-mobile fa-2x" aria-hidden="true"></i> ';
            break;
        default:
            return '<i class="fa fa-info-circle" aria-hidden="true"></i> ';
            break;
    }

}
function Minusculas($str, $bool = "")
{
    $str = utf8_decode($str);
    $str = strtolower($str);
    $str = utf8_encode($str);
    if ($bool === true) {
        $str = ucwords($str);
    }
    return $str;
}
function obtenerTotalCarrito()
{
    $ServicioOtisa = new ServicioOtisa();
    $idSession     = ObtenerSession();

    $response = $ServicioOtisa->totalCarrito($idSession);
    // dd($response);
    if (isset($response->xml->obtenerServiciosResult->TotalCarrito->TotalAPagar)) {
        $Total = $response->xml->obtenerServiciosResult->TotalCarrito->TotalAPagar;
        session(['TotalCarrito' => $Total]);
        if ($Total == 0) {
            Eliminar_carrito();
        }
        return formatoMoneda($Total);
    } else {
        // Eliminar_carrito();
        return 0;
    }
}

function getIconosAmenidades($amenidades, $size = "")
{
    $iconos      = [];
    $aAmenidades = collect([
        ['nombre' => 'wifi, Wi-fi, Internet Inalámbrico', 'icono' => "wifi"],
        ['nombre' => 'bar, Bar (es)', 'icono' => "local_bar"],
        ['nombre' => 'Estacionamiento', 'icono' => "local_parking"],
        ['nombre' => 'Lavanderia,Lavandería', 'icono' => "local_laundry_service"],
        ['nombre' => 'no fumar', 'icono' => "smoke_free"],
        ['nombre' => 'area para fumar', 'icono' => "smoking_rooms"],
        ['nombre' => 'Restaurante (s), restaurante', 'icono' => "restaurant"],
        ['nombre' => 'SPA', 'icono' => "spa"],
        ['nombre' => 'telefono', 'icono' => "phone"],
        ['nombre' => 'tv', 'icono' => "live_tv"],
        ['nombre' => 'Gimnasio', 'icono' => "fitness_center"],
        ['nombre' => 'Alberca', 'icono' => "pool"],
        ['nombre' => 'Desayuno', 'icono' => "free_breakfast"],
        ['nombre' => 'Bañera', 'icono' => "hot_tub"],
        ['nombre' => 'Accesos para discapacitados', 'icono' => "accessible"],
    ]);


    foreach ($amenidades as $amenidad) {
        $strAmenidad = $amenidad->Descripcion;
        $encontrados = $aAmenidades->filter(function ($a) use ($strAmenidad) {
            if (stripos($a["nombre"], $strAmenidad) !== false) {
                return true;
            }
            return false;
        });
        foreach ($encontrados as $encontrado) {

            if (!array_key_exists($encontrado["icono"], $iconos)) {
                $iconos["" . $encontrado['icono'] . ""] = '<i class="material-icons teal-text cursorPointer">' . $encontrado["icono"] . '</i>';
            }
        }
    }
    return implode("", $iconos);
}

function fechaFinal($Dias, $checkIn) {

    $checkOut = strtotime($Dias . ' days', strtotime($checkIn));
    return $checkOut = date("d-m-Y", $checkOut);
}

function ResumenPreReserva() {
    $ServicioOtisa = new ServicioOtisa();
    $idSession = ObtenerSession();

    return $Respuesta = $ServicioOtisa->resumenPreReserva($idSession);
}

function getNombreDestino($destino) {
    $path = app_path();
    $Client = new Client();
    $response = $Client->request('GET', 'https://www.etravelcloud.com/secciones/home/buscador/1_0/autocomplete/rellenar_autocomplete.php?tipo=1&id=' . $destino, ['verify' => $path . '\cacert.pem']);
    $json = [];
    $destinos = json_decode($response->getBody()->getContents());
    foreach ($destinos as $destino) {
        # code...
        $json[] = [
            "id" => $destino->destino_id,
            "text" => $destino->value
        ];
    }
    return $json;
}

function Eliminar_carrito()
{
    session()->forget('carrito');
    session()->forget('carrito_temp');
}

function Eliminar_Sesiones()
{
    // session()->forget('idSessionWSDL');
    // session()->forget('carrito');
    // session()->forget('tours');
    // session()->forget('oBusquedaTours');
    // session()->forget('hoteles');
    // session()->forget('oBusqueda');
    //  session()->forget('packagerooms');
    //  session()->forget('packageroomstours');
    // session()->forget('carrito_temp');
    //  session()->forget('UUIDCliente');
    //   session()->forget('ItinerarioSession');
    //   session()->forget('RespuestaItinerarioActividad');
    session()->flush();
}

function VerificarItinerario() {

    $Itinerario_Token = '2a64025c-6a49-484a-99fc-fe285f0ae7a9';
    $Currency         = 'MXN';
    $language         = 'ES-MX';

    if (session()->has('ItinerarioSession')) {
        //return $ItinerarioSession=session('ItinerarioSession');
        return session('ItinerarioSession');
    } else {
        $headers = array('Token' => $Itinerario_Token,
            'Language'               => $language,
            'Currency'               => $Currency,
            'content-type'           => 'application/json');

        $body = array(
            'ItinerarioPadreId' => 0,
            'TipoItinerarioId'  => 1,
            'Desc_titulo'       => 'Prueba_de_Itinerario_cesar',
            'IdRolCliente'      => 3,
        );
        // dd(json_encode($body));

        try {
            $Client = new Client();

            $request  = new Requestt('POST', 'http://192.168.1.86:5050/Proyecto/api/v1/Itinerario', $headers, json_encode($body));
            $response = $Client->send($request, ['timeout' => 0]);

            $code   = $response->getStatusCode(); // 200
            $reason = $response->getReasonPhrase();
            $datos  = json_decode($response->getBody()->getContents());

            session(['ItinerarioSession' => $datos]);
        } catch (ClientException $e) {
            echo Psr7\str($e->getRequest()); 
            // echo Psr7\str($e->getResponse());

            if ($e->hasResponse()) {

                echo Psr7\str($e->getResponse());
            }
        }
        return $ItinerarioSession = session('ItinerarioSession');
    }
}

function ObtenerSession() {
    $ServicioOtisa = new ServicioOtisa();
    if (session()->has('idSessionWSDL')) {

        $idSessionWSDL = session('idSessionWSDL');

        $respuesta = $ServicioOtisa->estatusSession($idSessionWSDL);
        $estatus = $respuesta->xml->estatusSessionResult;
        if (!$estatus) {
            Eliminar_Sesiones();
            ##posiblemente recuperar los productos del carrito y guardarlos con la nueva session en la b2b
            $respuesta = $ServicioOtisa->$idSessionWSDL = $respuesta->xml->obtenerServiciosResult->LoginRespuesta->IdSession;
            session(['idSessionWSDL' => $idSessionWSDL]);
            return $idSessionWSDL = session('idSessionWSDL');
        } else {
            return $idSessionWSDL;
        }
    } else {

        $respuesta     = $ServicioOtisa->login("otisademo.otisa@otisab2b.com", "chiapas123");
        $idSessionWSDL = $respuesta->xml->obtenerServiciosResult->LoginRespuesta->IdSession;
        session(['idSessionWSDL' => $idSessionWSDL]);
        // dd('else '.$this->idSessionWSDL=session('idSessionWSDL'));
        return $idSessionWSDL = session('idSessionWSDL');
    }
}

function getPrecioMinimoPaquete($paquetes)
{
    $precios = [];
    foreach ($paquetes as $paquete) {
        $precios[] = str_replace(',', '', $paquete->PricePackage);
    }
    if (count($precios) > 0) {
        return min($precios);
    } else {
        return 0;
    }
}

function getTarifaNocheSD($tarifaPorNoche) {
    return formatoMoneda($tarifaPorNoche / 2);
}

function getTotal($total) {
    $array = array();
    array_push($array, $total);
    $x = array_sum($array);
    return $x;
}

function getCategoria($categoria, $categoriaString) {
    $respuesta = new stdclass();
    $tokens = explode(" ", $categoriaString);
    $json = [];
    switch (count($tokens)) {
        case 1:
//            getCat1Token($tokens);
            $respuesta->valorCategoria = $categoria;
            $respuesta->tipoCategoria  = "Estrellas";
            $respuesta->extras         = "";
//            $json = ["valorCategoria" => $categoria, "tipoCategoria" => "Estrellas", "extras" => ""];
            break;
        case 2:
            $respuesta->valorCategoria = $tokens[0];
            $respuesta->tipoCategoria  = $tokens[1];
            $respuesta->extras         = "";
//            $json = ["valorCategoria" => $tokens[0], "tipoCategoria" => $tokens[1], "extras" => ""];
            //            getCat2Token($tokens);
            break;
        case 3:
//            $json = getCat3Token($categoria, $tokens);
            $respuesta = getCat3Token($categoria, $tokens);
            break;
        case 4:
//            $json = getCat4Token($categoria, $tokens);
            $respuesta = getCat4Token($categoria, $tokens);
            break;
    }

//    return json_encode($json);
    return $respuesta;
}

function getCat3Token($categoria, $tokens) {
//    $json = [];
    $respuesta = new stdclass();
    switch ($tokens[0]) {
        case "SIN":
            $respuesta->valorCategoria=$categoria;
            $respuesta->tipoCategoria="Estrellas";
            $respuesta->extras="";
//            $json = ["valorCategoria" => $categoria, "tipoCategoria" => "Estrellas", "extras" => ""];
            break;
        default :

            if ($tokens[1] === "½") {
                $respuesta->valorCategoria=$tokens[0] . ".5";
                $respuesta->tipoCategoria=$tokens[2];
                $respuesta->extras="";
//                $json = ["valorCategoria" => $tokens[0] . ".5", "tipoCategoria" => $tokens[2], "extras" => ""];
            } else if (stripos($tokens[1], "estrella") !== false) {
                $respuesta->valorCategoria=$tokens[0];
                $respuesta->tipoCategoria=$tokens[1];
                $respuesta->extras=$tokens[2];

//                $json = ["valorCategoria" => $tokens[0], "tipoCategoria" => $tokens[1], "extras" => $tokens[2]];
            } else {
                $respuesta->valorCategoria=$tokens[0];
                $respuesta->tipoCategoria=$tokens[2];
                $respuesta->extras="";
//                $json = ["valorCategoria" => $tokens[0], "tipoCategoria" => $tokens[2], "extras" => ""];
            }
            break;
    }
//    return $json;
    return $respuesta;
}

function getCat4Token($categoria, $tokens) {
//    $json = [];
    $respuesta = new stdclass();
    switch ($tokens[2]) {
        case "Y":
            if ($tokens[3] === "MEDIA") {
                $respuesta->valorCategoria=$tokens[0]. ".5";
                $respuesta->tipoCategoria=$tokens[1];
                $respuesta->extras="";
//                $json = ["valorCategoria" => $tokens[0] . ".5", "tipoCategoria" => $tokens[1], "extras" => ""];
            } else {
                $respuesta->valorCategoria=$tokens[0];
                $respuesta->tipoCategoria=$tokens[1];
                $respuesta->extras="";
//                $json = ["valorCategoria" => $tokens[0], "tipoCategoria" => $tokens[1], "extras" => ""];
            }
            break;
        case "DE":
            $respuesta->valorCategoria=$tokens[0];
            $respuesta->tipoCategoria=$tokens[1];
            $respuesta->extras=$tokens[3];
//            $json = ["valorCategoria" => $tokens[0], "tipoCategoria" => $tokens[1], "extras" => $tokens[3]];
            break;
    }
//    return $json;
    return $respuesta;
}

function getIconosCategoria($categoria, $size = "tiny") {

    $valor = $categoria->valorCategoria;
    $tipo = $categoria->tipoCategoria;
    $extra = $categoria->extras;
    $categoria = '';
    for ($i = 1; $i <= intval($valor); $i++) {
        if (stripos($tipo, "estrella") !== false) {
            $categoria .= '<i class="' . $size . ' material-icons activeStar">star</i>';
        } else {
            $categoria .= '<i class="' . $size . ' material-icons activeStar">vpn_key</i>';
        }
    }
    if (strpos($valor, '.') !== false) {
        if (stripos($tipo, "estrella") !== false) {
            $categoria .= '<i class="' . $size . ' material-icons activeStar">star_half</i>';
        } else {
            $categoria .= '<i class="' . $size . ' material-icons activeStar">vpn_key</i>';
        }
    }
    if ($extra !== "") {
        switch ($extra) {
            case "LUJO":
                $categoria .= '<i class="' . $size . ' material-icons teal-text" title="' . $extra . '">assistant</i>';
                break;
        }
    }
    return $categoria;
}

function getEstrellas($estrellas, $estrallasString = "", $size = "tiny") {
    if ($estrellas == 0) {
        $strE = explode(" ", trim($estrallasString));
        $estrellas = $strE[0];
    }
//    $stars = '<div class="estrellas">';
    $stars = '';
    for ($i = 1; $i <= 5; $i++) {
        if ($i <= $estrellas) {
//            $stars .= '<i class="" style="color:#F39C12;">&#9733;</i>';
            $stars .= '<i class="' . $size . ' material-icons activeStar">star</i>';
        } else {
//            $stars .= '<i class="" style="color:#95A5A6;">&#9733;</i>';
//            $stars .= '<i class="'.$size.' material-icons">star_border</i>';
        }
    }
//    $stars .= '</div>';
    return $stars;
}

function getCheckIn() {
    $busqueda = session('oBusqueda');
    return $busqueda["checkIn"];
}

function getCheckOut() {
    $busqueda = session('oBusqueda');
    return $busqueda["checkOut"];
}

function getNumeroNoches() {
    $busqueda = session('oBusqueda');

    $fecha_inicio_detalle = new DateTime($busqueda["checkIn"]);

    $fecha_fin_detalle = new DateTime($busqueda["checkOut"]);

    $num_noches = $fecha_inicio_detalle->diff($fecha_fin_detalle);

    return $num_noches->d;
}

function getAmenidadesMin($obj) {
    $stringAmenidades = "";
    $amenidades = objetoParseArray($obj);
    foreach ($amenidades as $amenidad) {
        $stringAmenidades .= $amenidad->Descripcion . ", ";
    }

    return substr($stringAmenidades, 0, 150) . "...";
}

function getListaByComas($obj) {
    $string = "";
    $lista = objetoParseArray($obj);
    foreach ($lista[0]->string as $elemento) {

        $string .= $elemento . ", ";
    }
    if (strlen($string) > 150) {
        return substr($string, 0, 150) . "...";
    } else {
        return $string;
    }
}

function quitarHTML($string) {
    return strip_tags($string);
}

function getTextoPoliticas($politica) {
//    dd($politica);
    $fechaHora = explode("T", $politica->Hasta);
    $fechaHora1 = explode("T", $politica->Desde);
    $horarioGMT = explode("-", $fechaHora[1]);

    $texto = "En caso de Cancelar/Modificar esta reserva a partir de las " . $fechaHora[1] . " horas entre las fechas " . $fechaHora1[0]
            . " al " . $fechaHora[0] . ", Generaran un gasto del " . $politica->Comision . "% sobre el importe de la reserva."
    ;
//    dd($texto);
    return $texto;
}

function getTextoPoliticasMonto($politica) {
    $fechaHora = explode("T", $politica->Hasta);
    $fechaHora1 = explode("T", $politica->Desde);
    $horarioGMT = explode("-", $fechaHora[1]);

    $texto = "En caso de Cancelar/Modificar esta reserva a partir de las " . $fechaHora[1] . " horas entre las fechas " . $fechaHora1[0]
            . " al " . $fechaHora[0] . ", Generaran un cobro de $ " . formatoMoneda($politica->Monto) . " sobre el importe de la reserva."
    ;
    return $texto;
}

function objetoParseArray($Objeto) {
    $respuesta = new stdclass();
    $respuesta->tipo = 0;
    $respuesta->resultado = 0;

    if (is_object($Objeto)) {
        if (!is_array($Objeto)) {
            $arreglo = array();
            array_push($arreglo, $Objeto);
            return $arreglo;
        }
    }
    return $Objeto;
}

function validarRespuestaXML($mensaje, $respuestaXML) {
    $respuesta = array();
    global $variablesGlobales;

    # comprobando resultado del Web Services.
    if ($respuestaXML->tipo == 1) {
        # Respuesta del Web services incorrecta.
        try {
            # Error al conectar con el Web Services.
            throw new excepcionesXml($mensaje, 1000);
        } catch (excepcionesXml $e) {
            $variablesGlobales->errores = $e->errores;
        }
    } else {
        # Respuesta correcta.
        $resultadoXML = $respuestaXML->xml->obtenerServiciosResult;
        $errorRespuesta = $resultadoXML->ErrorRespuesta;

        # Validando Errores devueltos por el Web Services.
        if (isset($errorRespuesta->Error->Error)) {
            $listaDeErrores = self::objetoParseArray($errorRespuesta->Error->Error);

            try {
                throw new excepcionesXml($mensaje, $listaDeErrores);
            } catch (excepcionesXml $e) {
                $variablesGlobales->errores = $e->errores;
            }
        }

        $respuesta = $resultadoXML;
    }

    return $respuesta;
}

# El primer parametro, es una fecha en formato date(d-m-y).
# El segundo parametro es para sumar o quitar dias a la fecha.

function obtenerFecha($fecha, $numeroDeDias) {
    list($dia, $mes, $ano) = explode('/', $fecha);

    return date('d/m/Y', mktime(0, 0, 0, $mes, $dia + $numeroDeDias, $ano));
}

#Convierte a formato para la BD

function formatoFechaBd($fecha) {
    list($dia, $mes, $ano) = explode('/', $fecha);

    return date('Y/m/d', mktime(0, 0, 0, $mes, $dia, $ano));
}

# CONVIERTE UNA CANTIDAD EN FORMATO DE CENTAVOS A PESOS
# Ejemplo: 34800 centavos serán 348 pesos

function centavosAPesos($PrecioEnCentavos) {
    if ($PrecioEnCentavos > 0) {
        $resultadoXML = $PrecioEnCentavos / 100;
    }

    return $resultadoXML;
}

function PesosACentavos($CantidadEnPesos) {
    if ($CantidadEnPesos > 0) {
        $resultadoConversion = $CantidadEnPesos * 100;
    }

    return $resultadoConversion;
}

#Genera una cadena aleatoria.

function randomString($chars = 8) {
    $letters = 'abcefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
    return substr(str_shuffle($letters), 0, $chars);
}

# Formato de moneda para paypal.

function formatoMonedaPaypal($monto) {
    $montoFormateado = number_format($monto, 2, '.', '');

    return $montoFormateado;
}

# Formato de moneda para el Sitio.

function formatoMoneda($monto) {
    $montoFormateado = number_format($monto, 2);

    return $montoFormateado;
}

# Calcular montos con Impuestos

function montoConImpuestos($monto) {
    $respuesta = new stdclass();
    $respuesta->porcentaje = 0;
    $respuesta->impuestos = 0;
    $respuesta->montoConImpuestos = $monto;

    $precioConIVA = $monto;

    if (isset($_SESSION['IVA'])) {
        # Calcular IVA
        if ($monto > 0) {
            $iva = $_SESSION['IVA'];
            $totalIVA = ($iva * $monto) / 100;
            $precioConIVA = $monto + $totalIVA;

            $respuesta->porcentaje        = $iva;
            $respuesta->impuestos         = $totalIVA;
            $respuesta->montoConImpuestos = $precioConIVA;
        }
    }

    return $respuesta;
}

# Obtiene la informacion de los impuestos a un monto especificado

function desgloseDeImpuestos($monto = 0)
{
    $respuesta                    = new stdclass();
    $respuesta->porcentaje        = 16;
    $respuesta->impuestos         = 0;
    $respuesta->montoNeto         = 0;
    $respuesta->montoConImpuestos = $monto;

    if (is_numeric($monto)) {
        if ($monto > 0) {
            $respuesta->porcentaje        = 16;
            $respuesta->montoNeto         = $monto * .84;
            $respuesta->impuestos         = $monto - $respuesta->montoNeto;
            $respuesta->montoConImpuestos = $monto;
        }
    } else {
        $respuesta->montoConImpuestos = 0;
    }

    return $respuesta;
}
