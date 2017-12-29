<?php

namespace App\Http\Controllers;

use App\Http\Servicios\ServicioOtisa;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Psr7;
use GuzzleHttp\Psr7\Request as Requestt;
use Illuminate\Http\Request;
use stdClass;

class ServiciosController extends Controller
{
    private $total;
    private $DatosdelCarrito;
    private $token;
    private $folio;
    private $IdRespuestaTF;
    private $ccnum;
    private $cvc;
    private $expiry;
    public $carrito_temp = array();

    public function AddActividad($elemento)
    {
        $SessionItinerario = VerificarItinerario();
        dd($SessionItinerario);
        if ($elemento["tipo"] == 'Hotel') {
            $TipoActividadId                                        = 1;
            $Calificacion                                           = $elemento["datos"]->Categoria;
            $FechaInicio                                            = date_format(date_create($elemento["checkIn"]), "Y-m-d");
            $FechaFinal                                             = date_format(date_create($elemento["checkOut"]), "Y-m-d");
            $DetalleItinerario                                      = array();
            $DetalleItinerario[0]                                   = new stdclass();
            $DetalleItinerario[0]->TipoDetalleItinerarioActividadId = 1;
            $DetalleItinerario[0]->DatosDetalle                     = 'Detalle_Itinerario_Hotel_Ejemplo_Cesar';
            $LatitudInicio                                          = $elemento["datos"]->Latitud;
            $LatitudFinal                                           = $elemento["datos"]->Latitud;
            $LongitudInicio                                         = $elemento["datos"]->Longitud;
            $LongitudFinal                                          = $elemento["datos"]->Longitud;
            $Lugar                                                  = $elemento["datos"]->Ciudad;
            $Descripcion                                            = array();
            $Descripcion[0]                                         = new stdclass();
            $Descripcion[0]->TipoDetallesActividadId                = 1;
            $Descripcion[0]->DataActividad                          = substr($elemento["datos"]->Descripcion, 0, 100);

        } elseif ($elemento["tipo"] == 'Tour') {
            $TipoActividadId                                        = 3;
            $Calificacion                                           = null;
            $FechaInicio                                            = $elemento["datos"]->FechaInicio;
            $FechaFinal                                             = $elemento["datos"]->FechaInicio;
            $DetalleItinerario                                      = array();
            $DetalleItinerario[0]                                   = new stdclass();
            $DetalleItinerario[0]->TipoDetalleItinerarioActividadId = 1;
            $DetalleItinerario[0]->DatosDetalle                     = $elemento["datos"]->Ruta;
            $Lugar                                                  = $elemento["datos"]->CiudadSalida;
            $count                                                  = count($elemento["datos"]->rutas->RutaItinerario->Destinos->DestinoRuta);
            $LatitudInicio                                          = $elemento["datos"]->rutas->RutaItinerario->Destinos->DestinoRuta[0]->Latitud;
            $LatitudFinal                                           = $elemento["datos"]->rutas->RutaItinerario->Destinos->DestinoRuta[$count - 1]->Latitud;
            $LongitudInicio                                         = $elemento["datos"]->rutas->RutaItinerario->Destinos->DestinoRuta[0]->Longitud;
            $LongitudFinal                                          = $elemento["datos"]->rutas->RutaItinerario->Destinos->DestinoRuta[$count - 1]->Longitud;
            $Descripcion                                            = array();
            $Descripcion[0]                                         = new stdclass();
            $Descripcion[0]->TipoDetallesActividadId                = 1;
            $Descripcion[0]->DataActividad                          = $elemento["datos"]->Ruta;

        } elseif ($elemento["tipo"] == 'Circuito') {
            $TipoActividadId = 4;
            $Calificacion    = null;
            $FechaInicio     = date_format(date_create($elemento["checkIn"]), "Y-m-d");
            $FechaFinal      = strtotime($elemento["datos"]->Dias . ' days', strtotime($FechaInicio));
            $FechaFinal      = date("Y-m-d", $FechaFinal);
            $Lugar           = $elemento["datos"]->CiudadSalida;
            $count           = count($elemento["datos"]->rutas->RutaItinerario);
            $count2          = count($elemento["datos"]->rutas->RutaItinerario[$count - 1]->Destinos->DestinoRuta);
            $LatitudInicio   = $elemento["datos"]->rutas->RutaItinerario[0]->Destinos->DestinoRuta[0]->Latitud;
            $LatitudFinal    = $elemento["datos"]->rutas->RutaItinerario[$count - 1]->Destinos->DestinoRuta[$count2 - 1]->Latitud;
            $LongitudInicio  = $elemento["datos"]->rutas->RutaItinerario[0]->Destinos->DestinoRuta[0]->Longitud;
            $LongitudFinal   = $elemento["datos"]->rutas->RutaItinerario[$count - 1]->Destinos->DestinoRuta[$count2 - 1]->Longitud;
            $Descripcion     = array();
            foreach ($elemento["datos"]->rutas->RutaItinerario as $ruta) {
                array_push($Descripcion, $arrayName = array('TipoDetallesActividadId' => 1, 'DataActividad' => $ruta->RutaCadena));
            }
            $DetalleItinerario = array();
            foreach ($elemento["datos"]->rutas->RutaItinerario as $ruta) {
                array_push($DetalleItinerario, $arrayName = array('TipoDetalleItinerarioActividadId' => 1, 'DatosDetalle' => $ruta->RutaCadena));
            }

        } elseif ($elemento["tipo"] == 'Actividad') {
            $TipoActividadId                                        = 5;
            $Calificacion                                           = null;
            $FechaInicio                                            = date_format(date_create($elemento["checkIn"]), "Y-m-d");
            $FechaFinal                                             = date_format(date_create($elemento["checkIn"]), "Y-m-d");
            $Lugar                                                  = $elemento["datos"]->Ciudad;
            $LatitudInicio                                          = $elemento["datos"]->Latitud;
            $LatitudFinal                                           = $elemento["datos"]->Latitud;
            $LongitudInicio                                         = $elemento["datos"]->Longitud;
            $LongitudFinal                                          = $elemento["datos"]->Longitud;
            $Descripcion                                            = array();
            $Descripcion[0]                                         = new stdclass();
            $Descripcion[0]->TipoDetallesActividadId                = 1;
            $Descripcion[0]->DataActividad                          = substr($elemento["datos"]->Descripcion, 0, 100);
            $DetalleItinerario                                      = array();
            $DetalleItinerario[0]                                   = new stdclass();
            $DetalleItinerario[0]->TipoDetalleItinerarioActividadId = 1;
            $DetalleItinerario[0]->DatosDetalle                     = 'Actividad :' . $elemento["packagerooms"]->ActividadTarifas->ActividadTarifas->Nombre;

        } elseif ($elemento["datos"] == 'Vuelos') {
            # code...
        } elseif ($elemento["datos"] == 'Autos') {
            # code...
        }

        $actividad                                   = array();
        $actividad[0]                                = new stdclass();
        $actividad[0]->ItinerarioActividadesPadreId  = 0;
        $actividad[0]->ItinerarioId                  = $SessionItinerario->data->ItinerarioId;
        $actividad[0]->FechaInicio                   = $FechaInicio;
        $actividad[0]->FechaFinal                    = $FechaFinal;
        $actividad[0]->DetalleItinerarioActividad    = $DetalleItinerario;
        $actividad[0]->ProveedorId                   = 6;
        $actividad[0]->TipoActividadId               = $TipoActividadId;
        $actividad[0]->CodigoProducto                = $elemento["id"];
        $actividad[0]->CodigoPaquete                 = $elemento["idPaquete"];
        $actividad[0]->Calificacion                  = $Calificacion;
        $actividad[0]->Nom_producto                  = $elemento["datos"]->Nombre;
        $actividad[0]->Lugar                         = $Lugar;
        $actividad[0]->Latitud                       = array();
        $actividad[0]->Latitud[0]                    = new stdclass();
        $actividad[0]->Latitud[0]->CoordenadaTipoId  = 1;
        $actividad[0]->Latitud[0]->Latitudes         = $LatitudInicio;
        $actividad[0]->Latitud[1]                    = new stdclass();
        $actividad[0]->Latitud[1]->CoordenadaTipoId  = 2;
        $actividad[0]->Latitud[1]->Latitudes         = $LatitudFinal;
        $actividad[0]->Longitud                      = array();
        $actividad[0]->Longitud[0]                   = new stdclass();
        $actividad[0]->Longitud[0]->TipoCoordenadaId = 1;
        $actividad[0]->Longitud[0]->Longitudes       = $LongitudInicio;
        $actividad[0]->Longitud[1]                   = new stdclass();
        $actividad[0]->Longitud[1]->TipoCoordenadaId = 2;
        $actividad[0]->Longitud[1]->Longitudes       = $LongitudFinal;
        $actividad[0]->DetallesActividad             = $Descripcion;
        $actividad[0]->Precios                       = $elemento["packagerooms"]->PricePackage;
        // dd(json_encode($actividad));
        $headers = array(
            'Token'        => '2a64025c-6a49-484a-99fc-fe285f0ae7a9',
            'Language'     => 'ES-MX',
            'Currency'     => 'MXN',
            'content-type' => 'application/json');
        try {
            $Client   = new Client();
            $request  = new Requestt('POST', 'http://192.168.1.86:5050/Proyecto/Api/v1/ActividadesItinerarios', $headers, json_encode($actividad));
            $response = $Client->send($request, ['timeout' => 0]);
            $datos    = json_decode($response->getBody()->getContents());

            if ((session()->has('RespuestaItinerarioActividad')) && (!empty(session("RespuestaItinerarioActividad")))) {
                $RespuestaItinerarioActividad = session("RespuestaItinerarioActividad");
                array_push($RespuestaItinerarioActividad, objetoParseArray($datos));
                session(["RespuestaItinerarioActividad" => $RespuestaItinerarioActividad]);
            } elseif ((session()->has('RespuestaItinerarioActividad')) && (empty(session("RespuestaItinerarioActividad")))) {
                session()->forget('RespuestaItinerarioActividad');
                session(['RespuestaItinerarioActividad' => objetoParseArray($datos)]);
            } else {
                session(['RespuestaItinerarioActividad' => objetoParseArray($datos)]);
            }
        } catch (BadResponseException $e) {
            echo Psr7\str($e->getRequest());
            echo Psr7\str($e->getResponse());
            if ($e->hasResponse()) {
                echo Psr7\str($e->getResponse());
            }
        }
    }

    public function PostAutorizar()
    {
        $idSession      = ObtenerSession();
        $ServicioOtisa  = new ServicioOtisa();
        $idFormadePago  = 2; #2->pago directo #1->pre-reserva
        $idMetodoDepago = 1; #5->targeta_de_credito #4->paypal #2->deposito_bancario #3->tranferencia_bancaria
        $UUIDCliente    = null;
        $DatosPreauth   = null;
        $datos          = null;
        #################################### POST AUTH ##################################
        $DatosPreauth = session('SessionPreAutorizacion');
        $folio        = $DatosPreauth[0]->data->Folio;
        $UUIDCliente  = session('UUIDCliente');
        $headers      = [
            "Token"        => '205c4bb1-b5ab-4b15-82b7-257fee9e5382',
            "Currency"     => 'MXN',
            "Language"     => 'ES-MX',
            "content-type" => 'application/json'];

        $body = array(
            'IdParteRoleProveedor'        => 1,
            'Monto'                       => 1, #1 por el momento
            'UUIDCliente'                 => $UUIDCliente,
            'Folio'                       => $folio,
            'IdTipoTransaccionFinanciera' => 4,
        );
        try {
            $Client                  = new Client();
            $request                 = new Requestt('POST', 'http://192.168.1.113/pagos/api/v1/api/transaccion-financiera', $headers, json_encode($body));
            $response                = $Client->send($request, ['timeout' => 0]);
            $code                    = $response->getStatusCode(); // 200
            $reason                  = $response->getReasonPhrase();
            $datos                   = json_decode($response->getBody()->getContents());
            $SessionPostAutorizacion = array();
            array_push($SessionPostAutorizacion, $datos);
            session(["SessionPostAutorizacion" => $SessionPostAutorizacion]);
            // dd($datos);
            if ($datos->codigo === 0) {
################ COMPRAR ################# Reservar ##################################
                $rs = $ServicioOtisa->comprar($idSession, $idFormadePago, $idMetodoDepago); ##este metodo debe devolver el id de venta etc.
                // dd($rs); ##error de sistema
                // if($rs->xml->obtenerServiciosResult->ErrorRespuesta->Error !== null){
                //   $datos = $rs->xml->obtenerServiciosResult->ErrorRespuesta->Error->Error->Mensaje;
                //   Eliminar_Sesiones();
                //   return view("carrito.ViewPostAutorizacionError", ["datos" => $datos]);
                // }
                if ($rs->xml->obtenerServiciosResult->CompletarCompraRespuesta->Status === true) {
                    $DatosComprar = $rs->xml->obtenerServiciosResult->CompletarCompraRespuesta;
                    session(["ComprarRespuesta" => $DatosComprar]);
                    $IdReserva        = $rs->xml->obtenerServiciosResult->CompletarCompraRespuesta->DistincionOperacional->IdOperacion;
                    $VoucherRespuesta = $rs->xml->obtenerServiciosResult->CompletarCompraRespuesta->Vouchers->VoucherRespuesta;
                    // $CodigoReserva = $rs->xml->obtenerServiciosResult->CompletarCompraRespuesta->Vouchers->VoucherRespuesta->DetallesGenerales->CodigoReserva;# CodigoReserva 16713A
                    // $CodigoReserva = objetoParseArray($rs->xml->obtenerServiciosResult->CompletarCompraRespuesta->Vouchers->VoucherRespuesta);
                    // foreach ($CodigoReserva as $key => $codRes) {
                    //       $codRes->DetallesGenerales->CodigoReserva;
                    // }
                    // $IdReserva = $CodigoReserva;
                    ###################################### ESTATUS RESERVA ########################
                    $rss = $ServicioOtisa->estatusReserva($IdReserva, $idSession);
                    // dd($rss);
                    $EstadoReservaRespuesta = $rss->xml->obtenerServiciosResult->EstadoReservaRespuesta;
                    session(["EstadoReservaRespuesta" => $EstadoReservaRespuesta]);
                    $Estado = $rss->xml->obtenerServiciosResult->EstadoReservaRespuesta->Estado;
                    $venta  = $IdReserva;
                    if (isset($rss->xml->obtenerServiciosResult->EstadoReservaRespuesta->Productos->ProductoReservaEstatus)) {
                        $ProductoReservaEstatus = objetoParseArray($rss->xml->obtenerServiciosResult->EstadoReservaRespuesta->Productos->ProductoReservaEstatus);
                    } else {
                        dd($IdReserva);
                    }
                    $voucherData = array();
                    $vouData     = null;
                    foreach ($ProductoReservaEstatus as $key => $prodRespEstatus) {
                        $indice  = $prodRespEstatus->Indice;
                        $vouData = $ServicioOtisa->crearVaucher($venta, $indice, $idSession);
                        #$voucher = objetoParseArray($vouData->xml->obtenerServiciosResult->Voucher);
                        $voucher = $vouData->xml->obtenerServiciosResult->Voucher;
                        array_push($voucherData, $voucher);
                    }
###################################VOUCHER  ########################################
                    session(["VoucherDatos" => $voucherData]);
                    return true;
                } else {
                    $mensaje = $rs->xml->obtenerServiciosResult->ErrorRespuesta->Error->Error->Mensaje;
                    return $mensaje;
                }
            } else {
                return view("carrito.ViewPostAutorizacionError", $datos);
            }
        } catch (BadResponseException $e) {
            $d1 = Psr7\str($e->getRequest());
            $d2 = Psr7\str($e->getResponse());
            return view("carrito.ViewPostAutorizacionError", [
                "request"  => $d1,
                "response" => $d2]);
        }
    }

    public function PreAutorizar(Request $request)
    {

        $this->total  = $request->input('monto_total');
        $currency     = $request->input('currency');
        $language     = $request->input('language');
        $this->token  = $request->input('token');
        $first_name   = $request->input('first _name');
        $last_name    = $request->input('last_name');
        $this->ccnum  = str_replace(' ', '', trim($request->input('ccnum')));
        $this->cvc    = $request->input('cvc');
        $this->expiry = str_replace(' / ', '', trim($request->input('expiry')));
        $email        = $request->input('email');
        $UUIDCliente  = rand(500, 700); //random temporalmente
        session(["UUIDCliente" => $UUIDCliente]);

        $headers = [
            "Token"        => $this->token,
            "Currency"     => $currency,
            "Language"     => $language,
            "content-type" => 'application/json'];

        $body                                                                      = new stdclass();
        $body->IdParteRoleProveedor                                                = 1;
        $body->IdTipoTransaccionFinanciera                                         = 3;
        $body->Monto                                                               = 1;
        $body->UUIDCliente                                                         = $UUIDCliente;
        $body->DetalleTransaccionFinanciera                                        = array();
        $body->DetalleTransaccionFinanciera[0]                                     = new stdclass;
        $body->DetalleTransaccionFinanciera[0]->IdTipoDetalleTransaccionFinanciera = 1;
        $body->DetalleTransaccionFinanciera[0]->Data                               = 'RESER_' . $UUIDCliente;
        $body->DetalleTransaccionFinanciera[1]                                     = new stdclass;
        $body->DetalleTransaccionFinanciera[1]->IdTipoDetalleTransaccionFinanciera = 7;
        $body->DetalleTransaccionFinanciera[1]->Data                               = $this->ccnum;
        $body->DetalleTransaccionFinanciera[2]                                     = new stdclass;
        $body->DetalleTransaccionFinanciera[2]->IdTipoDetalleTransaccionFinanciera = 8;
        $body->DetalleTransaccionFinanciera[2]->Data                               = $this->expiry;
        $body->DetalleTransaccionFinanciera[3]                                     = new stdclass;
        $body->DetalleTransaccionFinanciera[3]->IdTipoDetalleTransaccionFinanciera = 9;
        $body->DetalleTransaccionFinanciera[3]->Data                               = $this->cvc;

        $rrs = null;
        try {
            $Client   = new Client();
            $request  = new Requestt('POST', 'http://192.168.1.113/pagos/api/v1/api/transaccion-financiera', $headers, json_encode($body));
            $response = $Client->send($request, ['timeout' => 0]);
            $code     = $response->getStatusCode(); // 200
            $reason   = $response->getReasonPhrase();
            $datos    = json_decode($response->getBody()->getContents());
            // dd($datos);
            if ($datos->codigo == 0) {
                $SessionPreAutorizacion = array();
                array_push($SessionPreAutorizacion, $datos);
                session(["SessionPreAutorizacion" => $SessionPreAutorizacion]);
                ############################################# PostAutorizacion ########################################
                $rrs = $this->PostAutorizar();
                // dd($rrs );
                if ($rrs === true) {
                    $SessionPostAutorizacion = session('SessionPostAutorizacion');
                    $VoucherDatos            = session('VoucherDatos');
                    // dd($SessionPostAutorizacion);
                    return view("carrito.ViewPostAutorizacionSuccess", ["SessionPostAutorizacion" => $SessionPostAutorizacion,
                        "VoucherDatos"                                                                => $VoucherDatos]);
                } else {
                    return view("carrito.ViewPostAutorizacionError", ["SessionPostAutorizacion" => $rrs]);
                } ############################## FIN POST AUTH ###############################################
            } else {return view("carrito.ViewPreAutorizacionError", ["SessionPreAutorizacion" => $datos]);}
        } catch (BadResponseException $e) {
            $resp = json_decode($e->getResponse()->getBody()->getContents());
            // dd($resp);
            return redirect()->route("carrito.finalizar")->with("error", $resp)->with("totalCarrito", $this->total);
        }
    }

    public function CompletarDatos(Request $request)
    {
        $this->total = $request->input('total');
        if ((session()->has('carrito_temp')) && (!empty(session("carrito_temp")))) {
            $this->carrito_temp = session('carrito_temp');
        } else if ((session()->has('carrito_temp')) && (empty(session("carrito_temp")))) {
            $request->session()->forget('carrito_temp');
        } else {
            $request->session()->forget('carrito_temp');
            $this->DatosdelCarrito = session("carrito");
            session(["carrito_temp" => $this->DatosdelCarrito]);
            $this->carrito_temp = session('carrito_temp');
        }
        if ($request->input('enviar') == 'Enviar') {
            $DatosCliente = session()->has('DatosCliente') ? session('DatosCliente') : array();
            $Datos        = array(
                "idPaquete"        => $request->input('idPaquete'),
                "idProducto"       => $request->input('idProducto'),
                "tipo"             => $request->input('tipo'),
                "NombreProducto"   => $request->input('nombre_producto'),
                "Nombre_titular"   => $request->input('nombre_titular'),
                "apellido_titular" => $request->input('apellido_titular'),
                "Nombres"          => $request->input('nombre'),
                "Apellidos"        => $request->input('apellido'),
                "telefono"         => $request->input('telefono'),
                "email"            => $request->input('email'),
                "observaciones"    => $request->input('textarea1'));
            array_push($DatosCliente, $Datos);
            session(["DatosCliente" => $DatosCliente]);
            $idSession     = ObtenerSession();
            $datosPasajero = new stdclass();
            # Datos del pasajero
            $datosPasajero->Nombre        = $request->input('nombre_titular');
            $datosPasajero->Apellido      = $request->input('apellido_titular');
            $datosPasajero->Correo        = $request->input('email');
            $datosPasajero->Localizador   = null;
            $datosPasajero->Observaciones = $request->input('textarea1');
            $datosPasajero->Telefono      = $request->input('telefono');
            $tipo                         = null;
            $paquete                      = null;
            if ($request->input('tipo') == 'Hotel') {
                ############ Tipo Hotel #############
                $ddc = session("carrito");
                // dd($ddc);
                foreach ($ddc as $paq) {
                    if ($paq["tipo"] == 'Hotel') {
                        $paquete = objetoParseArray($paq["packagerooms"]->Habitaciones->HabitacionRespuesta);
                    }
                }
                $producto               = new stdclass();
                $producto->Habitaciones = array();

                for ($i = 0; $i <= count($paquete) - 1; $i++) {
                    $producto->Habitaciones[$i]            = new stdclass();
                    $producto->Habitaciones[$i]->IdCuarto  = $paquete[$i]->IdHabitacion;
                    $producto->Habitaciones[$i]->Huespedes = array();
                    #cuantos ocupantes hay en la habitacion suma de adultos y niños
                    for ($j = 1; $j <= ($paquete[$i]->Adultos + $paquete[$i]->Menores); $j++) {
                        $Huesped                                       = new stdclass();
                        $Huesped->Nombres                              = $Datos["Nombres"][$j - 1];
                        $Huesped->Apellidos                            = $Datos["Apellidos"][$j - 1];
                        $j <= $paquete[$i]->Adultos ? $Huesped->Tipo   = 1 : $Huesped->Tipo   = 0;
                        $producto->Habitaciones[$i]->Huespedes[$j - 1] = $Huesped;
                    }
                }
                $producto->tipo = 1;
            } elseif ($request->input('tipo') == 'Tour') {
                ########## Tipo Tour #########
                $producto       = new stdclass();
                $producto->tipo = 2;
                # 1:Hotel, 2: Tour, 3:Traslado, 4:Circuito
                $producto->TipoTraslado = 1; # 1: sencillo, 2: Redondo
                #PickUps
                $producto->PickUp            = new stdclass();
                $producto->PickUp->tipo      = $request->input('TipoLugar_pickup');
                $producto->PickUp->IdLugar   = $request->input('id_lugar_pickup');
                $producto->PickUp->Hora      = $request->input('horario_pickup');
                $producto->PickUp->IdHora    = $request->input('id_horario_pickup');
                $producto->PickUp->Aerolinea = null;
                $producto->PickUp->NoVuelo   = null;
                #DropOffs
                $producto->DropOff            = new stdclass();
                $producto->DropOff->tipo      = $request->input('TipoLugar_dropoff');
                $producto->DropOff->IdLugar   = $request->input('id_lugar_dropoff');
                $producto->DropOff->Hora      = $request->input('horario_dropoff');
                $producto->DropOff->IdHora    = $request->input('id_horario_dropoff');
                $producto->DropOff->Aerolinea = null;
                $producto->DropOff->NoVuelo   = null;

            } elseif ($request->input('tipo') == 'Actividad') {
                ########## Tipo Actividad #########
                $producto       = new stdclass();
                $producto->tipo = 13;

                $datosPasajero->Pasajeros = array();
                for ($i = 0; $i < count($request->input("nombre_pasajero")); $i++) {
                    $datosPasajero->Pasajeros[$i]           = new stdclass();
                    $datosPasajero->Pasajeros[$i]->Apellido = $request->input("apellido_pasajero")[$i];
                    $datosPasajero->Pasajeros[$i]->Nombre   = $request->input("nombre_pasajero")[$i];
                    $datosPasajero->Pasajeros[$i]->Edad     = $request->input("edad_pasajero")[$i];
                    $datosPasajero->Pasajeros[$i]->Tipo     = $request->input("tipo_pasajero")[$i] == 'ADULTO' ? 1 : 0;
                }
                $datosPasajero->Requisitos = array();
                for ($k = 0; $k < count($request->input("requisito_respuesta")); $k++) {
                    $datosPasajero->Requisitos[$k]            = new stdclass();
                    $datosPasajero->Requisitos[$k]->Requerido = $request->input("requisito_req")[$k];
                    $datosPasajero->Requisitos[$k]->Respuesta = $request->input("requisito_respuesta")[$k];
                    $datosPasajero->Requisitos[$k]->Texto     = $request->input("requisito_texto")[$k];
                    $datosPasajero->Requisitos[$k]->Tipo      = $request->input("requisito_tipo")[$k];
                    $datosPasajero->Requisitos[$k]->index     = $request->input("requisito_index")[$k];
                }
            }
            $ServicioOtisa = new ServicioOtisa();
########################### Completar Datos en servicio Otisa !###############################
            $Response = $ServicioOtisa->CompletarDatos($idSession, $request->input('idPaquete'), $request->input('idProducto'), $datosPasajero, $producto);
            // dd($Response);
            ##########################################################################################
        }

        if (!empty($this->carrito_temp)) {

            $elemento = array_pop($this->carrito_temp);
            session(["carrito_temp" => $this->carrito_temp]);
            $this->carrito_temp = session('carrito_temp');
            ##borrar
            $ResPre            = null;
            $ResumenPreReserva = null;
            $listaDropOff      = null;
            $listaPickUp       = null;
            if ($elemento["tipo"] == 'Tour') {
                $ResumenPreReserva = session('ResumenPreReserva');
                $ResPre            = $ResumenPreReserva[$elemento["id"]];
                if (isset($ResPre->ListaDeDropOffs->ListaHoteles->DropOff) && isset($ResPre->ListaDePickUps->ListaHoteles->PickUp)) {
                    $listaDropOff = $ResPre->ListaDeDropOffs;
                    $listaPickUp  = $ResPre->ListaDePickUps;
                }
            }
            $data = array('elemento' => $elemento,
                'monto_total'            => $this->total,
                'DropOff'                => $listaDropOff,
                'PickUp'                 => $listaPickUp,
                'ResPre'                 => $ResPre);
            return view("carrito.CompletarDatos", $data);
        } else {
            $this->total = $request->input('total');
            return redirect()->route("carrito.finalizar")->with("totalCarrito", $this->total);

        }

    }

    public function finalizar(Request $request)
    {
        $this->total           = session("totalCarrito");
        $error                 = session("error");
        $this->DatosdelCarrito = session("carrito");
        $datos                 = array('carrito' => $this->DatosdelCarrito, 'monto_total' => $this->total, 'errores' => $error);
        return view("carrito.finalizar", $datos);
    }

    public function carrito()
    {
        // Eliminar_Sesiones();
        // obtenerTotalCarrito();
        carritoOtisa();
        if ((session()->has('carrito_temp')) && (empty(session("carrito_temp")))) {
            session()->forget('carrito_temp');}
        $carrito = session("carrito");
        return view("carrito.index", ["carrito" => $carrito]);
    }

    public function destinos(Request $request)
    {
        try {
            $path     = app_path();
            $term     = $request->get('term');
            $Client   = new Client();
            $response = $Client->request('GET', 'https://otisab2b.com/secciones/home/buscador/1_0/autocomplete/rellenar_autocomplete.php?tipo=1&term=' . $term, ['verify' => $path . '\cacert.pem']);
            $json;
            $destinos = json_decode($response->getBody()->getContents());
            foreach ($destinos as $destino) {
                $json[] = [
                    "id"   => $destino->destino_id,
                    "text" => $destino->value,
                ];
            }
            return json_encode($json);
        } catch (BadResponseException $e) {
            $text   = json_decode($e->getResponse()->getBody()->getContents());
            $json[] = [
                "id"   => null,
                "text" => $text,
            ];
            return json_encode($json);
        }
    }

    public function deleteToCart(Request $request)
    {
        $id         = $request->input("index");
        $carrito    = session("carrito");
        $idPaquete  = $carrito[$id]["idPaquete"];
        $idProducto = $carrito[$id]["id"];

        if ($carrito[$id]["tipo"] == 'Tour') {
            $ResumenPre = session('ResumenPreReserva');
            unset($ResumenPre[$idProducto]);
            session(["ResumenPreReserva" => $ResumenPre]);
        }
        try {
            $ServicioOtisa = new ServicioOtisa();
            $idSessionWSDL = ObtenerSession();
            $Respuesta     = $ServicioOtisa->quitarProducto($idSessionWSDL, $idPaquete, $idProducto);
            unset($carrito[$id]);
            session(["carrito" => $carrito]);

            $count        = count($carrito);
            $total        = obtenerTotalCarrito();
            $DatosCarrito = array('count' => $count,
                'total'                       => $total,
                'respuesta'                   => $Respuesta,
            );
            return $DatosCarrito;
        } catch (Exception $e) {
            $Respuesta    = $e->getMessage();
            $count        = count($carrito);
            $total        = obtenerTotalCarrito();
            $DatosCarrito = array('count' => $count,
                'total'                       => $total,
                'respuesta'                   => $Respuesta,
            );
            return $DatosCarrito;
        }
    }

    public function addToCart(Request $request)
    {
        $idSessionWSDL = ObtenerSession();
        $ServicioOtisa = new ServicioOtisa();
        $tipo          = $request->input("tipo");
        $carrito       = session('carrito');
        if (!$carrito) {
            $carrito = array();
        }
        switch ($tipo) {

            case 'Hotel':
                try {
                    $idHotel   = $request->input("idHotel");
                    $idPaquete = $request->input("idPaquete");
                    //  ##### quitar el pruducto si existe
                    // $ServicioOtisa->quitarProducto($idSessionWSDL, $idPaquete, $idHotel);
                    ###########PreReserva ######################################
                    $Respuesta = $ServicioOtisa->preReserva($idSessionWSDL, $idPaquete, $idHotel);
                    $Response  = $Respuesta->xml->obtenerServiciosResult->PreReservaRespuesta;
                    // dd($Respuesta);
                    if ($Response->Status === true) {

                        $mensaje   = 'El Hotel se ah PreReservado!';
                        $status    = $Response->Status;
                        $busqueda  = session('oBusqueda');
                        $hoteles   = session('hoteles');
                        $packrooms = session('packagerooms');

                        if (!is_array($packrooms)) {
                            $packrooms = objetoParseArray($packrooms);
                        }
                        $pr = null;
                        foreach ($packrooms as $packagerooms) {
                            $pr["" . $packagerooms->PackageId . ""] = $packagerooms;}

                        $elemento = [
                            "id"           => $idHotel,
                            "idPaquete"    => $idPaquete,
                            "tipo"         => $tipo,
                            "datos"        => $hoteles[$idHotel],
                            "packagerooms" => $pr[$idPaquete],
                            "checkIn"      => $busqueda["checkIn"],
                            "checkOut"     => $busqueda["checkOut"],
                            "adultos"      => $busqueda["adultos"],
                            "menores"      => $busqueda["menores"],
                            "noches"       => $busqueda["noches"],
                            "edad_menores" => $busqueda["edadMenores"],
                        ];
################################ agregando la actividad al itinerario ####################
                        // $this->AddActividad($elemento);
                        #########################################################################################
                        array_push($carrito, $elemento);

                        foreach ($carrito as $value) {
                            unset($value["datos"]->Packages);
                        }

                        session(["carrito" => $carrito]);

                        $data = array('num_elementos' => sizeof($carrito), 'estatus' => $status, 'mensaje' => $mensaje);
                        return json_encode($data);
                    } else {
                        $status  = $Response->Status;
                        $mensaje = $Respuesta->xml->obtenerServiciosResult->ErrorRespuesta->Error->Error->Mensaje;
                        $data    = array('num_elementos' => sizeof($carrito), 'estatus' => $status, 'mensaje' => $mensaje);
                        return json_encode($data);
                    }

                } catch (Exception $e) {
                    dd($e);
                    $status  = null;
                    $mensaje = $e->getMessage();
                    $data    = array('num_elementos' => sizeof($carrito), 'estatus' => $status, 'mensaje' => $mensaje);
                    return json_encode($data);
                }

                break;

            case 'Tour':
                try {
                    $idTour    = $request->input("idTour");
                    $idPaquete = $request->input("idPaquete");
                    //$ServicioOtisa->quitarProducto($idSessionWSDL, $idPaquete, $idTour);
                    $Respuesta = $ServicioOtisa->preReserva($idSessionWSDL, $idPaquete, $idTour);
                    $Response  = $Respuesta->xml->obtenerServiciosResult->PreReservaRespuesta;

                    if ($Response->Status === true) {

                        $rs = resumenPreReserva($idSessionWSDL); #helper
                        // dd($rs);
                        $respre            = $rs->xml->obtenerServiciosResult->ResumenPreReservaRespuesta->ListaDeTours;
                        $ResumenPreReserva = null;
                        // dd($respre->TourPreReserva);
                        foreach (objetoParseArray($respre->TourPreReserva) as $TourPreRe) {
                            $ResumenPreReserva["" . $TourPreRe->TourId . ""] = $TourPreRe;}
                        // dd($ResumenPreReserva);
                        // dd($respre);
                        session(['ResumenPreReserva' => $ResumenPreReserva]);
                        session(['DatosResumenPreReserva' => $respre]);

                        // $x = session('ResumenPreReserva');
                        // dd($x);
                        $mensaje  = 'El Tour se ah PreReservado!';
                        $status   = $Response->Status;
                        $busqueda = session('oBusquedaTours');
                        $tours    = session('tours');

                        $prt            = null;
                        $packroomstours = session('packageroomstours');

                        // foreach ($packroomstours as $packageroomstours) {
                        //  $prt["" . $packageroomstours->PackageId . ""] = $packageroomstours;
                        //  }
                        $elemento = [
                            "id"           => $idTour,
                            "idPaquete"    => $idPaquete,
                            "tipo"         => $tipo,
                            //"packagerooms" => $prt[$idPaquete],
                            "packagerooms" => $packroomstours,
                            "datos"        => $tours[$idTour],
                            "checkIn"      => $busqueda["checkIn"],
                            "adultos"      => $busqueda["adultos"],
                            "menores"      => $busqueda["menores"],
                            "edad_menores" => $busqueda["edadMenores"],
                        ];

                        // $this->AddActividad($elemento);

                        array_push($carrito, $elemento);

                        foreach ($carrito as $value) {
                            unset($value["datos"]->Packages);}

                        session(["carrito" => $carrito]);

                        $data = array('num_elementos' => sizeof($carrito), 'estatus' => $status, 'mensaje' => $mensaje);
                        return json_encode($data);
                    } else {
                        $status  = $Response->Status;
                        $mensaje = $Respuesta->xml->obtenerServiciosResult->ErrorRespuesta->Error->Error->Mensaje;
                        // $else ='else';
                        $datos = '';
                        $data  = array('num_elementos' => sizeof($carrito), 'estatus' => $status, 'mensaje' => $mensaje);
                        return json_encode($data);
                    }
                } catch (Exception $e) {
                    $status  = null;
                    $mensaje = $e->getMessage();
                    $data    = array('num_elementos' => sizeof($carrito), 'estatus' => $status, 'mensaje' => $mensaje);
                    return json_encode($data);
                }
                break;

            case 'Circuito':
                try {
                    $idCircuito = $request->input("idCircuito");
                    $idPaquete  = $request->input("idPaquete");

                    $Respuesta = $ServicioOtisa->preReserva($idSessionWSDL, $idPaquete, $idCircuito);

                    $Response = $Respuesta->xml->obtenerServiciosResult->PreReservaRespuesta;

                    if ($Response->Status === true) {

                        $mensaje  = 'El Circuito se ah PreReservado!';
                        $status   = $Response->Status;
                        $status   = true;
                        $busqueda = session('oBusquedaCircuitos');
                        // dd($busqueda);
                        $circuitos = session('circuitos');

                        $prt          = null;
                        $packcircuito = objetoParseArray(session('packageroomscircuito'));
                        // dd($packcircuito);
                        foreach ($packcircuito as $packageroomscircuito) {
                            $prc["" . $packageroomscircuito->PackageId . ""] = $packageroomscircuito;
                        }
                        $elemento = [
                            "id"           => $idCircuito,
                            "idPaquete"    => $idPaquete,
                            "tipo"         => $tipo,
                            "datos"        => $circuitos[$idCircuito],
                            "packagerooms" => $prc[$idPaquete],
                            "checkIn"      => $busqueda["checkIn"],
                            "adultos"      => $busqueda["adultos"],
                            "menores"      => $busqueda["menores"],
                            "edad_menores" => $busqueda["edadMenores"],
                        ];

                        //dd($elemento);
                        $this->AddActividad($elemento);
                        array_push($carrito, $elemento);

                        foreach ($carrito as $value) {
                            unset($value["datos"]->Package);}

                        session(["carrito" => $carrito]);

                        $data = array('num_elementos' => sizeof($carrito), 'estatus' => $status, 'mensaje' => $mensaje);
                        return json_encode($data);

                    } else {
                        $status  = $Response->Status;
                        $mensaje = $Respuesta->xml->obtenerServiciosResult->ErrorRespuesta->Error->Error->Mensaje;
                        $data    = array('num_elementos' => sizeof($carrito), 'estatus' => $status, 'mensaje' => $mensaje);
                        return json_encode($data);
                    }
                } catch (Exception $e) {
                    $status  = null;
                    $mensaje = $e->getMessage();
                    $data    = array('num_elementos' => sizeof($carrito), 'estatus' => $status, 'mensaje' => $mensaje);
                    return json_encode($data);
                }
                break;

            case 'Actividad':
                try {
                    $idActividad = $request->input("idActividad");
                    $idPaquete   = $request->input("idPaquete");
                    // ## quitar el pruducto si existe
                    // $ServicioOtisa->quitarProducto($idSessionWSDL, $idPaquete, $idActividad);
                    $Respuesta = $ServicioOtisa->preReserva($idSessionWSDL, $idPaquete, $idActividad);
                    $Response  = $Respuesta->xml->obtenerServiciosResult->PreReservaRespuesta;

                    if ($Response->Status === true) {
                        //        $rs=resumenPreReserva($idSessionWSDL); #helper
                        $mensaje  = 'La Actividad se ah PreReservado!';
                        $status   = $Response->Status;
                        $status   = true;
                        $busqueda = session('oBusquedaActividades');
                        // dd($busqueda);
                        $actividades = session('actividades');

                        $pra            = null;
                        $packcActividad = objetoParseArray(session('packageroomsactividades'));
                        // dd($packcircuito);
                        foreach ($packcActividad as $packageActividades) {
                            $pra["" . $packageActividades->PackageId . ""] = $packageActividades;
                        }
                        $elemento = [
                            "id"           => $idActividad,
                            "idPaquete"    => $idPaquete,
                            "tipo"         => $tipo,
                            "datos"        => $actividades[$idActividad],
                            "packagerooms" => $pra[$idPaquete],
                            "checkIn"      => $busqueda["checkIn"],
                            "adultos"      => $busqueda["adultos"],
                            "menores"      => $busqueda["menores"],
                            "edad_menores" => $busqueda["edadMenores"],
                        ];
                        //dd($elemento);
                        $this->AddActividad($elemento);
                        array_push($carrito, $elemento);

                        foreach ($carrito as $value) {
                            unset($value["datos"]->Package);}

                        session(["carrito" => $carrito]);

                        $data = array('num_elementos' => sizeof($carrito), 'estatus' => $status, 'mensaje' => $mensaje);
                        return json_encode($data);

                    } else {
                        $status  = $Response->Status;
                        $mensaje = $Respuesta->xml->obtenerServiciosResult->ErrorRespuesta->Error->Error->Mensaje;
                        $data    = array('num_elementos' => sizeof($carrito), 'estatus' => $status, 'mensaje' => $mensaje);
                        return json_encode($data);
                    }
                } catch (Exception $e) {
                    $status  = null;
                    $mensaje = $e->getMessage();
                    $data    = array('num_elementos' => sizeof($carrito), 'estatus' => $status, 'mensaje' => $mensaje);
                    return json_encode($data);
                }
                break;

        }
    }

}
