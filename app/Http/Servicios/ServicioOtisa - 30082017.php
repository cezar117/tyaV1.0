<?php

# JALAPEÑO TOURS
# OTISA WEB SERVICES (Cliente)
# Ultima Actualización: 28 de NOviembre 2016
#  Se Agrego el metodo: "convertirPrereservaAReserva"

namespace App\Http\Servicios;

use SoapClient;
use SoapVar;
use stdClass;
use DateTime;

class ServicioOtisa {
    # Atributos

    private $cliente;
    private $error;

    # Constructor

    public function __construct() {
        $this->ServicioOtisa();
    }

    public function ServicioOtisa() {
        try {
            # Creando Cliente Soap
            $this->cliente = new SoapClient("http://www.etravelcloud.com/service/Service.svc?singleWsdl", array('trace' => 1,'encoding'=>'UTF-8'));
            /*$this->cliente = new SoapClient("http://192.168.1.67/b2b/Service.svc?singleWsdl", array('trace' => 1,'encoding'=>'UTF-8'));*/
        } catch (SoapFault $fault) {
            $this->error = "Ocurrio un error : " . $fault->getMessage();
        } catch (Exception $e) {
            $this->error = "Ocurrio un error : " . $fault->getMessage();
        }
    }

    # Comprobar si existe algun error en la instancia actual del servicio Otisa
    # Está validación es útil despues de crear la instancia del Servicio Otisa.
    # para validar si se pudo o no conectar con el servicio Web.

    public function comprobarError() {
        $respuesta = new stdclass();

        if (isset($this->error)) {
            $respuesta->tipo = 1;
            $respuesta->error = $this->error;
            $respuesta->resultado = TRUE;
        } else {
            $respuesta->tipo = 0;
            $respuesta->error = "";
            $respuesta->resultado = FALSE;
        }

        return $respuesta;
    }

    # Realiza las llamadas al metodo obtenerServicios del Web Services

    private function obtenerServicios($funcion, $parametros) {
        $respuesta = new stdclass();
        $respuesta->tipo = 0;

        try {
            $resultado = $this->cliente->obtenerServicios($parametros);
            $respuesta->xml = $resultado;

            // QUITAR COMENTARIOS
            // PARA IMPRIMIR XML DE PETICION Y/O RESPUESTA
            // echo "<br/>XML PETICION:<br/><br/>";
            // print_r($this->obtenerSolicitudXML());
            // echo "<br/>XML RESPUESTA:<br/>";
            // print_r($this->obtenerRespuestaXML());

            return $respuesta;
        } catch (soapFault $fault) {
            $respuesta->error = "<b>Error en la llamada " . $funcion . " [SOAP]</b>: " . $fault->getMessage() . $fault->getTraceAsString();
            $respuesta->tipo = 1;
            return $respuesta;
        } catch (Exception $e) {
            $respuesta->error = "<b>Error en la llamada " . $funcion . " [Excepcion php]</b>: " . $e->getMessage() . $fault->getTraceAsString();
            $respuesta->tipo = 1;
            return $respuesta;
        }
    }

    # Obtener Opciones de Pago

    public function obtenerOpcionesDePago($idSession) {
        $respuesta = new stdclass();
        $respuesta->tipo = 0;

        if ($this->error) {
            $respuesta->error = $this->error;
            $respuesta->tipo = 1;
            return $respuesta;
        }

        $peticion = new stdclass();

        $requerimiento = new stdclass();
        $requerimiento->IdSession = $idSession;
        $requerimiento->Peticion = new SoapVar($peticion, SOAP_ENC_OBJECT, "OpcionesDePagoRequerimiento", "http://schemas.datacontract.org/2004/07/Serviciob2b");
        $requerimiento->ServicioTipo = 0;
        $requerimiento->Tipo = 0;

        $parametros = array("req" => $requerimiento);
        $resultado = $this->obtenerServicios("OpcionesDePago", $parametros);

        return $resultado;
    }

    # Obtener datos de un producto vendido

    public function DatosDeProductoVendido($idSession, $indice, $idVenta) {
        $respuesta = new stdclass();
        $respuesta->tipo = 0;

        if ($this->error) {
            $respuesta->error = $this->error;
            $respuesta->tipo = 1;
            return $respuesta;
        }

        $peticion = new stdclass();
        $peticion->Indice = $indice;
        $peticion->IdVenta = $idVenta;


        $requerimiento = new stdclass();
        $requerimiento->IdSession = $idSession;
        $requerimiento->Peticion = new SoapVar($peticion, SOAP_ENC_OBJECT, "DatosProductoVendidoRequerimiento", "http://schemas.datacontract.org/2004/07/Serviciob2b");
        $requerimiento->ServicioTipo = 0;
        $requerimiento->Tipo = 0;

        $parametros = array("req" => $requerimiento);
        $resultado = $this->obtenerServicios("DatosProductoVendidoRequerimiento", $parametros);

        return $resultado;
    }

    # Obtener Total a pagar en carrito

    public function totalCarrito($idSession) {
        $respuesta = new stdclass();
        $respuesta->tipo = 0;

        if ($this->error) {
            $respuesta->error = $this->error;
            $respuesta->tipo = 1;
            return $respuesta;
        }

        $peticion = new stdclass();

        $requerimiento = new stdclass();
        $requerimiento->IdSession = $idSession;
        $requerimiento->Peticion = new SoapVar($peticion, SOAP_ENC_OBJECT, "TotalCarritoRequerimientos", "http://schemas.datacontract.org/2004/07/Serviciob2b");
        $requerimiento->ServicioTipo = 0;
        $requerimiento->Tipo = 0;

        $parametros = array("req" => $requerimiento);
        $resultado = $this->obtenerServicios("totalCarrito", $parametros);

        return $resultado;
    }

    # Obtener Total a pagar en carrito

    public function estatusReserva($IdReserva, $idSession) {
        $respuesta = new stdclass();
        $respuesta->tipo = 0;

        if ($this->error) {
            $respuesta->error = $this->error;
            $respuesta->tipo = 1;
            return $respuesta;
        }

        $peticion = new stdclass();
        $peticion->IdReserva = $IdReserva;

        $requerimiento = new stdclass();
        $requerimiento->IdSession = $idSession;
        $requerimiento->Peticion = new SoapVar($peticion, SOAP_ENC_OBJECT, "EstatusReservaRequerimiento", "http://schemas.datacontract.org/2004/07/Serviciob2b");
        $requerimiento->ServicioTipo = 0;
        $requerimiento->Tipo = 0;

        $parametros = array("req" => $requerimiento);
        $resultado = $this->obtenerServicios("totalCarrito", $parametros);

        return $resultado;
    }

    # Agrega a carrito un producto Cotizado.

    public function AgregarCotizado($idProducto, $idSession) {
        $respuesta = new stdclass();
        $respuesta->tipo = 0;

        if ($this->error) {
            $respuesta->error = $this->error;
            $respuesta->tipo = 1;
            return $respuesta;
        }

        $requerimiento = new stdclass();
        $peticion = new stdclass();
        $peticion->IdProducto = $idProducto;

        $requerimiento->IdSession = $idSession;
        $requerimiento->Peticion = new SoapVar($peticion, SOAP_ENC_OBJECT, "AgregarCotizado", "http://schemas.datacontract.org/2004/07/Serviciob2b");
        $requerimiento->ServicioTipo = 0;
        $requerimiento->Tipo = 1;

        $parametros = array("req" => $requerimiento);
        $resultado = $this->obtenerServicios("AgregarCotizado", $parametros);
        return $resultado;
    }

    # obtine voucher del web services

    public function crearVaucher($venta, $descripcion, $producto, $paquete, $tipo, $grupo, $idSession) {
        $respuesta = new stdclass();
        $respuesta->tipo = 0;

        if ($this->error) {
            $respuesta->error = $this->error;
            $respuesta->tipo = 1;
            return $respuesta;
        }

        $requerimiento = new stdclass();
        $peticion = new stdclass();
        $peticion->Grupo = $grupo;
        $peticion->IdDescripcion = $descripcion;
        $peticion->IdProducto = $producto;
        $peticion->IdVenta = $venta;
        $peticion->Paquete = $paquete;
        $peticion->Tipo = $tipo;

        $requerimiento->IdSession = $idSession;
        $requerimiento->Peticion = new SoapVar($peticion, SOAP_ENC_OBJECT, "VoucherRequerimiento", "http://schemas.datacontract.org/2004/07/Serviciob2b");
        $requerimiento->ServicioTipo = 0;
        $requerimiento->Tipo = 1;

        $parametros = array("req" => $requerimiento);
        $resultado = $this->obtenerServicios("VoucherRequerimiento", $parametros);
        return $resultado;
    }

    # obtine Resumen de la reserva

    public function obtenerResumenDeReserva($venta, $indice, $idSession) {
        $respuesta = new stdclass();
        $respuesta->tipo = 0;

        if ($this->error) {
            $respuesta->error = $this->error;
            $respuesta->tipo = 1;
            return $respuesta;
        }

        $requerimiento = new stdclass();
        $peticion = new stdclass();
        #$peticion->Grupo=$grupo;
        #$peticion->IdProveedorDelServicioXML=$idProveedorXML;
        #$peticion->IdProducto=$idProducto;
        $peticion->IdVenta = $venta;
        $peticion->Indice = $indice;

        $requerimiento->IdSession = $idSession;
        $requerimiento->Peticion = new SoapVar($peticion, SOAP_ENC_OBJECT, "ResumenDeReservaRequerimiento", "http://schemas.datacontract.org/2004/07/Serviciob2b");
        #$requerimiento->ServicioTipo=0;
        #$requerimiento->Tipo=1;
        $parametros = array("req" => $requerimiento);
        $resultado = $this->obtenerServicios("ResumenDeReservaRequerimiento", $parametros);

        return $resultado;
    }

    # convierte una pre-reserva a Reserva

    public function convertirPrereservaAReserva($venta, $idSession) {
        $respuesta = new stdclass();
        $respuesta->tipo = 0;

        if ($this->error) {
            $respuesta->error = $this->error;
            $respuesta->tipo = 1;
            return $respuesta;
        }

        $requerimiento = new stdclass();
        $peticion = new stdclass();
        $peticion->IdVenta = $venta;

        $requerimiento->IdSession = $idSession;
        $requerimiento->Peticion = new SoapVar($peticion, SOAP_ENC_OBJECT, "ConvertirPreToReservaRequerimiento", "http://schemas.datacontract.org/2004/07/Serviciob2b");
        $requerimiento->ServicioTipo = 0;
        $requerimiento->Tipo = 1;

        $parametros = array("req" => $requerimiento);
        $resultado = $this->obtenerServicios("ConvertirPreToReservaRequerimiento", $parametros);

        return $resultado;
    }

    # Comprueba la session del web services

    public function estatusSession($key) {
        $respuesta = new stdclass();
        $respuesta->tipo = 0;

        try {
            $parametros = array("key" => $key);
            $resultado = $this->cliente->estatusSession($parametros);
            $respuesta->xml = $resultado;
        } catch (soapFault $fault) {
            $respuesta->error = "<b>Error en la llamada estatusSession [SOAP]</b>: " . $fault->getMessage() . $fault->getTraceAsString();
            $respuesta->tipo = 1;
            return $respuesta;
        } catch (Exception $e) {
            $respuesta->error = "<b>Error en la llamada estatusSession [Excepcion php]</b>: " . $e->getMessage() . $fault->getTraceAsString();
            $respuesta->tipo = 1;
            return $respuesta;
        }

        return $respuesta;
    }

    # Obtener Destinos

    public function buscarDestinos($destino, $limite) {
        $respuesta = new stdclass();
        $respuesta->tipo = 0;

        try {
            $requerimiento = new stdclass();
            $requerimiento->Destino = $destino;
            $requerimiento->Limite = $limite;

            $parametros = array("req" => $requerimiento);

            $resultado = $this->cliente->buscarDestinoAutos($parametros);
            $respuesta->xml = $resultado;
            return $respuesta;
        } catch (soapFault $fault) {
            $respuesta->error = "<b>Error en la llamada buscarDestinoAutos [SOAP]</b>: " . $fault->getMessage() . $fault->getTraceAsString();
            $respuesta->tipo = 1;
            return $respuesta;
        } catch (Exception $e) {
            $respuesta->error = "<b>Error en la llamada buscarDestinoAutos [Excepcion php]</b>: " . $e->getMessage() . $fault->getTraceAsString();
            $respuesta->tipo = 1;
            return $respuesta;
        }
    }

    # LOGIN: (Crea una session en el Web Services de Otisa)

    public function login($usuario = null, $pass = null) {
        $respuesta = new stdclass();
        $respuesta->tipo = 0;

        if ($this->error) {
            $respuesta->error = $this->error;
            $respuesta->tipo = 1;
            return $respuesta;
        } else {
            $loginRequerimiento = new stdclass();
            $loginRequerimiento->Usuario = $usuario;
            $loginRequerimiento->Contrasena = $pass;

            $requerimiento = new stdclass();
            $requerimiento->Peticion = new SoapVar($loginRequerimiento, SOAP_ENC_OBJECT, "LoginRequerimiento", "http://schemas.datacontract.org/2004/07/Serviciob2b");
            $requerimiento->ServicioTipo = "0";
            $requerimiento->Tipo = "1";
            $requerimiento->IdSession = null;

            $parametros = array("req" => $requerimiento);

            $resultado = $this->obtenerServicios("Login", $parametros);
            return $resultado;
        }
    }

    # Obtener Hoteles
    //public function obtenerHoteles($IdSession,$categoria=null,$fechaEntrada,$fechaSalida,$idHotel=0,$idDestino=0,$tipoHospedaje,$habitaciones=array(),$NivelDetalles='DEFAULT',$moneda='MXN',$residencia='MX')

    public function obtenerHoteles($IdSession, $categoria = null, $fechaEntrada, $fechaSalida, $idHotel = 0, $idDestino = 0, $tipoHospedaje, $habitaciones = array(), $NivelDetalles = null, $moneda = 'MXN', $residencia = 'MX', $productoParaGrupo = -1) {
        $respuesta = new stdclass();
        $respuesta->tipo = 0;

        if ($this->error) {
            $respuesta->error = $this->error;
            $respuesta->tipo = 1;
            return $respuesta;
        }

        $peticion = new stdclass();
        $peticion->Categoria = $categoria;
        $peticion->CheckIn = new DateTime($fechaEntrada);
        $peticion->CheckIn = $peticion->CheckIn->format("Y-m-d");
        $peticion->CheckOut = new DateTime($fechaSalida);
        $peticion->CheckOut = $peticion->CheckOut->format("Y-m-d");
        $peticion->DesiredResultCurrency = $moneda;
        $peticion->IncludeHotelSupplierDetails = true;
//        $peticion->Nights;
        $peticion->Residency = $residencia;
        $peticion->Destino = $idDestino;
        $peticion->IdHotel = $idHotel;
        $peticion->Tipo = $tipoHospedaje;
        $peticion->cuarto = new stdclass();
        $peticion->cuarto->habitacion = $habitaciones;
        $peticion->NivelDetalles = $NivelDetalles;
        $peticion->ProductoDeGrupo = $productoParaGrupo;

        $requerimiento = new stdclass();
        $requerimiento->Peticion = new SoapVar($peticion, SOAP_ENC_OBJECT, "HotelesRequerimiento", "http://schemas.datacontract.org/2004/07/Serviciob2b");
        $requerimiento->ServicioTipo = 0;
        $requerimiento->Tipo = 1;
        $requerimiento->IdSession = $IdSession;

        $parametros = array("req" => $requerimiento);

        $resultado = $this->obtenerServicios("obtenerHoteles", $parametros);
        return $resultado;
    }

    # Obtener Hoteles2

    public function obtenerHoteles2($IdSession, $categoria = null, $fechaEntrada, $fechaSalida, $idHotel = 0, $idDestino = 0, $tipoHospedaje, $habitaciones = array(), $NivelDetalles = null, $moneda = 'MXN', $residencia = 'MX', $productoParaGrupo = -1) {
        $respuesta = new stdclass();
        $respuesta->tipo = 0;

        if ($this->error) {
            $respuesta->error = $this->error;
            $respuesta->tipo = 1;
            return $respuesta;
        }

        $peticion = new stdclass();
        $peticion->Categoria = $categoria;
        $peticion->CheckIn = new DateTime($fechaEntrada);
        $peticion->CheckIn = $peticion->CheckIn->format("Y-m-d");
        $peticion->CheckOut = new DateTime($fechaSalida);
        $peticion->CheckOut = $peticion->CheckOut->format("Y-m-d");
        $peticion->DesiredResultCurrency = $moneda;
        $peticion->IncludeHotelSupplierDetails = true;
//        $peticion->Nights;
        $peticion->Residency = $residencia;
        $peticion->Tipo = $tipoHospedaje;
        $peticion->cuarto = new stdclass();
        $peticion->cuarto->habitacion = $habitaciones;
        $peticion->TipoDeBusqueda = new stdclass();
        $tipoDeBusqueda= new stdclass();
        $stringTipoDeBusqueda="";
        if($idHotel!==0){
            $stringTipoDeBusqueda="PRODUCTO";
            $tipoDeBusqueda->IdProducto=$idHotel;
        }
        else{
            $stringTipoDeBusqueda="DESTINO";
            $tipoDeBusqueda->Destino=$idDestino;
        }

        $peticion->TipoDeBusqueda=new SoapVar($tipoDeBusqueda, SOAP_ENC_OBJECT, $stringTipoDeBusqueda, "http://schemas.datacontract.org/2004/07/Serviciob2b");
        $peticion->NivelDetalles = $NivelDetalles;
        $peticion->ProductoDeGrupo = $productoParaGrupo;
        
        $requerimiento = new stdclass();
        $requerimiento->Peticion = new SoapVar($peticion, SOAP_ENC_OBJECT, "HotelesRequerimiento", "http://schemas.datacontract.org/2004/07/Serviciob2b");
        $requerimiento->ServicioTipo = 0;
        $requerimiento->Tipo = 1;
        $requerimiento->IdSession = $IdSession;

        $parametros = array("req" => $requerimiento);

        $resultado = $this->obtenerServicios("obtenerHoteles", $parametros);
        return $resultado;
    }

    # Obtener detalles del hotel

    public function ObtenerHotelDetalles($idSession, $idHotel, $PackageId = "", $SupplierID = 0) {
        $respuesta = new stdclass();
        $respuesta->tipo = 0;

        if ($this->error) {
            $respuesta->error = $this->error;
            $respuesta->tipo = 1;
            return $respuesta;
        }

        $requerimiento = new stdclass();
        $peticion = new stdclass();
        $peticion->IdHotel = $idHotel;
        $peticion->PackageID = $PackageId;
        $peticion->SupplierId = $SupplierID;

        $requerimiento->IdSession = $idSession;
        $requerimiento->Peticion = new SoapVar($peticion, SOAP_ENC_OBJECT, "HotelDetallesRequerimiento", "http://schemas.datacontract.org/2004/07/Serviciob2b");
        $requerimiento->ServicioTipo = 0;
        $requerimiento->Tipo = 1;

        $parametros = array("req" => $requerimiento);
        $resultado = $this->obtenerServicios("ObtenerHotelDetalles", $parametros);
        return $resultado;
    }

    # Obtener listado de Tours

    public function ObtenerTours($idSession, $adultos, $menores, $edadMenores = array(), $idTour, $idDestino, $tipoServicio, $idioma, $fecha, $tipo = null, $servicioTipo = null) {
        $respuesta = new stdclass();
        $respuesta->tipo = 0;

        if ($this->error) {
            $respuesta->error = $this->error;
            $respuesta->tipo = 1;
            return $respuesta;
        }

        $peticion = new stdclass();
        $peticion->CantAdultos = $adultos;
        $peticion->CantMenores = $menores;
        $peticion->Destino = $idDestino;
        $peticion->EdadMenores = $edadMenores;
        $peticion->Fecha = new DateTime($fecha);
        $peticion->Fecha = $peticion->Fecha->format("Y-m-d");
        $peticion->IdTour = $idTour;
        $peticion->TipoServicio = $tipoServicio;
        $peticion->Idioma = $idioma;

        $requerimiento = new stdclass();
        $requerimiento->IdSession = $idSession;
        $requerimiento->Peticion = new SoapVar($peticion, SOAP_ENC_OBJECT, "ToursRequerimiento", "http://schemas.datacontract.org/2004/07/Serviciob2b");
        $requerimiento->ServicioTipo = 0;
        $requerimiento->Tipo = 0;

        $parametros = array("req" => $requerimiento);
        $resultado = $this->obtenerServicios("ObtenerTours", $parametros);
        return $resultado;
    }

    # Obtener listado de Tours2

    public function ObtenerTours2($idSession, $adultos, $menores, $edadMenores = array(), $idTour=0, $idDestino, $tipoServicio, $idioma, $fecha, $tipo = null, $servicioTipo = null) {
        $respuesta = new stdclass();
        $respuesta->tipo = 0;

        if ($this->error) {
            $respuesta->error = $this->error;
            $respuesta->tipo = 1;
            return $respuesta;
        }

        $peticion = new stdclass();
        $peticion->CantAdultos = $adultos;
        $peticion->CantMenores = $menores;
        $peticion->EdadMenores = $edadMenores;
        $peticion->Fecha = new DateTime($fecha);
        $peticion->Fecha = $peticion->Fecha->format("Y-m-d");
        
        $peticion->TipoServicio = $tipoServicio;
        $peticion->Idioma = $idioma;

        $tipoDeBusqueda= new stdclass();
        $stringTipoDeBusqueda="";
        if($idTour==="")$idTour=0;
        if($idTour!==0){
            $stringTipoDeBusqueda="PRODUCTO";
            $tipoDeBusqueda->IdProducto=$idTour;
        }
        else{
            $stringTipoDeBusqueda="DESTINO";
            $tipoDeBusqueda->Destino=$idDestino;
        }

        $peticion->TipoDeBusqueda=new SoapVar($tipoDeBusqueda, SOAP_ENC_OBJECT, $stringTipoDeBusqueda, "http://schemas.datacontract.org/2004/07/Serviciob2b");
        
        $requerimiento = new stdclass();
        $requerimiento->IdSession = $idSession;
        $requerimiento->Peticion = new SoapVar($peticion, SOAP_ENC_OBJECT, "ToursRequerimiento", "http://schemas.datacontract.org/2004/07/Serviciob2b");
        $requerimiento->ServicioTipo = 0;
        $requerimiento->Tipo = 0;

        $parametros = array("req" => $requerimiento);
        $resultado = $this->obtenerServicios("ObtenerTours", $parametros);
        return $resultado;
    }

    # Obtener detalles de un Tour

    public function ObtenerTourDetalle($idSession, $idTour) {
        $respuesta = new stdclass();
        $respuesta->tipo = 0;

        if ($this->error) {
            $respuesta->error = $this->error;
            $respuesta->tipo = 1;
            return $respuesta;
        }

        $peticion = new stdclass();
        $peticion->IdTour = $idTour;

        $requerimiento = new stdclass();
        $requerimiento->IdSession = $idSession;
        $requerimiento->Peticion = new SoapVar($peticion, SOAP_ENC_OBJECT, "TourDetallesRequerimiento", "http://schemas.datacontract.org/2004/07/Serviciob2b");
        $requerimiento->ServicioTipo = 0;
        $requerimiento->Tipo = 1;

        $parametros = array("req" => $requerimiento);
        $resultado = $this->obtenerServicios("ObtenerTourDetalle", $parametros);
        return $resultado;
    }

    # Obtener listado de Circuitos

    public function obtenerCircuitos($idSession, $fechaInicio, $idDestino, $idCircuito, $idioma, $tipoCircuito, $tipoServicio, $grupo = array()) {
        $respuesta = new stdclass();
        $respuesta->tipo = 0;

        if ($this->error) {
            $respuesta->error = $this->error;
            $respuesta->tipo = 1;
            return $respuesta;
        }

        $peticion = new stdclass();
        $peticion->CheckIn = new DateTime($fechaInicio);
        $peticion->CheckIn = $peticion->CheckIn->format("Y-m-d");
        $peticion->Destino = $idDestino;
        $peticion->IdCircuito = $idCircuito;
        $peticion->Idioma = $idioma;
        $peticion->TipoCircuito = $tipoCircuito;
        $peticion->TipoServicio = $tipoServicio;

        $peticion->grupo = array();

        for ($elemento = 0; $elemento < count($grupo); $elemento++) {
            $pasajeros = new stdclass();
            if ($tipoCircuito == 1) {
                $tipoDeDatos = "habitaciones";
            } else {
                $tipoDeDatos = "pasajeros";
            }
            $pasajeros = new SoapVar($grupo[$elemento], SOAP_ENC_OBJECT, "habitaciones", "http://schemas.datacontract.org/2004/07/Serviciob2b");
            $peticion->grupo[$elemento] = $pasajeros;
        }

        $requerimiento = new stdclass();
        $requerimiento->IdSession = $idSession;
        $requerimiento->Peticion = new SoapVar($peticion, SOAP_ENC_OBJECT, "CircuitosRequerimiento", "http://schemas.datacontract.org/2004/07/Serviciob2b");
        $requerimiento->ServicioTipo = 0;
        $requerimiento->Tipo = 0;

        $parametros = array("req" => $requerimiento);
        $resultado = $this->obtenerServicios("obtenerCircuitos", $parametros);

        return $resultado;
    }

    # Obtener listado de Circuitos2

    public function obtenerCircuitos2($idSession, $fechaInicio, $idDestino, $idCircuito=0, $idioma, $tipoCircuito, $tipoServicio, $grupo = array()) {
        $respuesta = new stdclass();
        $respuesta->tipo = 0;

        if ($this->error) {
            $respuesta->error = $this->error;
            $respuesta->tipo = 1;
            return $respuesta;
        }

        $peticion = new stdclass();
        $peticion->CheckIn = new DateTime($fechaInicio);
        $peticion->CheckIn = $peticion->CheckIn->format("Y-m-d");
        $peticion->Idioma = $idioma;
        $peticion->TipoCircuito = $tipoCircuito;
        $peticion->TipoServicio = $tipoServicio;

        $tipoDeBusqueda= new stdclass();
        $stringTipoDeBusqueda="";
        if($idCircuito==="")$idCircuito=0;
        if($idCircuito!==0){
            $stringTipoDeBusqueda="PRODUCTO";
            $tipoDeBusqueda->IdProducto=$idCircuito;
        }
        else{
            $stringTipoDeBusqueda="DESTINO";
            $tipoDeBusqueda->Destino=$idDestino;
        }

        $peticion->TipoDeBusqueda=new SoapVar($tipoDeBusqueda, SOAP_ENC_OBJECT, $stringTipoDeBusqueda, "http://schemas.datacontract.org/2004/07/Serviciob2b");

        $peticion->grupo = array();

        for ($elemento = 0; $elemento < count($grupo); $elemento++) {
            $pasajeros = new stdclass();
            if ($tipoCircuito == 1) {
                $tipoDeDatos = "habitaciones";
            } else {
                $tipoDeDatos = "pasajeros";
            }
            $pasajeros = new SoapVar($grupo[$elemento], SOAP_ENC_OBJECT, "habitaciones", "http://schemas.datacontract.org/2004/07/Serviciob2b");
            $peticion->grupo[$elemento] = $pasajeros;
        }

        $requerimiento = new stdclass();
        $requerimiento->IdSession = $idSession;
        $requerimiento->Peticion = new SoapVar($peticion, SOAP_ENC_OBJECT, "CircuitosRequerimiento", "http://schemas.datacontract.org/2004/07/Serviciob2b");
        $requerimiento->ServicioTipo = 0;
        $requerimiento->Tipo = 0;

        $parametros = array("req" => $requerimiento);
        $resultado = $this->obtenerServicios("obtenerCircuitos", $parametros);

        return $resultado;
    }

    # Obtener Detalles de un Circuito

    public function obtenerDetallesCircuito($idSession, $idCircuito, $idioma, $tipoCircuito, $tipoServicio, $CircuitoParaGrupos) {
        $respuesta = new stdclass();
        $respuesta->tipo = 0;

        if ($this->error) {
            $respuesta->error = $this->error;
            $respuesta->tipo = 1;
            return $respuesta;
        }

        $peticion = new stdclass();
        $peticion->IdCircuito = $idCircuito;
        $peticion->Idioma = $idioma;
        $peticion->TipoCircuito = $tipoCircuito;
        $peticion->TipoServicio = $tipoServicio;
        $peticion->CircuitoParaGrupos = $CircuitoParaGrupos;

        $requerimiento = new stdclass();
        $requerimiento->IdSession = $idSession;
        $requerimiento->Peticion = new SoapVar($peticion, SOAP_ENC_OBJECT, "CircuitoDetallesRequerimiento", "http://schemas.datacontract.org/2004/07/Serviciob2b");
        $requerimiento->ServicioTipo = 0;
        $requerimiento->Tipo = 0;

        $parametros = array("req" => $requerimiento);

        $resultado = $this->obtenerServicios("obtenerDetallesCircuito", $parametros);
        return $resultado;
    }

    # Obtener listado de Traslados

    public function obtenerTraslados($idSession, $adultos, $menores, $edadMenores = array(), $fechaInicio, $fechaFinal, $idDestino, $idTraslado, $tipoServicio, $tipoTraslado, $zonaInicio, $zonFinal, $idDestinoFinal = 0) {
        $respuesta = new stdclass();
        $respuesta->tipo = 0;

        if ($this->error) {
            $respuesta->error = $this->error;
            $respuesta->tipo = 1;
            return $respuesta;
        }

        $peticion = new stdclass();
        $peticion->CantAdultos = $adultos;
        $peticion->CantMenores = $menores;
        $peticion->Destino = $idDestino;
        $peticion->DestinoFin = $idDestinoFinal;
        $peticion->EdadMenores = $edadMenores;

        $peticion->FechaInicio = new DateTime($fechaInicio);
        $peticion->FechaInicio = $peticion->FechaInicio->format("Y-m-d");
        $peticion->FechaFin = new DateTime($fechaFinal);
        $peticion->FechaFin = $peticion->FechaFin->format("Y-m-d");
        $peticion->IdTraslado = $idTraslado;
        $peticion->TipoServicio = $tipoServicio;
        $peticion->TipoTraslado = $tipoTraslado;
        $peticion->ZonaFin = $zonFinal;
        $peticion->ZonaIni = $zonaInicio;

        $requerimiento = new stdclass();
        $requerimiento->IdSession = $idSession;
        $requerimiento->Peticion = new SoapVar($peticion, SOAP_ENC_OBJECT, "TrasladosRequerimiento", "http://schemas.datacontract.org/2004/07/Serviciob2b");
        $requerimiento->ServicioTipo = 0;
        $requerimiento->Tipo = 0;

        $parametros = array("req" => $requerimiento);
        $resultado = $this->obtenerServicios("obtenerTraslados", $parametros);
        return $resultado;
    }

    # Obtener listado de Traslados2

    public function obtenerTraslados2($idSession, $adultos, $menores, $edadMenores = array(), $fechaInicio, $fechaFinal, $idDestino, $idTraslado=0, $tipoServicio, $tipoTraslado, $zonaInicio, $zonFinal, $idDestinoFinal = 0) {
        $respuesta = new stdclass();
        $respuesta->tipo = 0;

        if ($this->error) {
            $respuesta->error = $this->error;
            $respuesta->tipo = 1;
            return $respuesta;
        }

        $peticion = new stdclass();
        $peticion->CantAdultos = $adultos;
        $peticion->CantMenores = $menores;
        
        $peticion->DestinoFin = $idDestinoFinal;
        $peticion->EdadMenores = $edadMenores;

        $peticion->FechaInicio = new DateTime($fechaInicio);
        $peticion->FechaInicio = $peticion->FechaInicio->format("Y-m-d");
        $peticion->FechaFin = new DateTime($fechaFinal);
        $peticion->FechaFin = $peticion->FechaFin->format("Y-m-d");
        
        $peticion->TipoServicio = $tipoServicio;
        $peticion->TipoTraslado = $tipoTraslado;
        $peticion->ZonaFin = $zonFinal;
        $peticion->ZonaIni = $zonaInicio;

        $tipoDeBusqueda= new stdclass();
        $stringTipoDeBusqueda="";
        if($idTraslado==="")$idTraslado=0;
        if($idTraslado!==0){
            $stringTipoDeBusqueda="PRODUCTO";
            $tipoDeBusqueda->IdProducto=$idTraslado;
        }
        else{
            $stringTipoDeBusqueda="DESTINO";
            $tipoDeBusqueda->Destino=$idDestino;
        }

        $peticion->TipoDeBusqueda=new SoapVar($tipoDeBusqueda, SOAP_ENC_OBJECT, $stringTipoDeBusqueda, "http://schemas.datacontract.org/2004/07/Serviciob2b");

        $requerimiento = new stdclass();
        $requerimiento->IdSession = $idSession;
        $requerimiento->Peticion = new SoapVar($peticion, SOAP_ENC_OBJECT, "TrasladosRequerimiento", "http://schemas.datacontract.org/2004/07/Serviciob2b");
        $requerimiento->ServicioTipo = 0;
        $requerimiento->Tipo = 0;

        $parametros = array("req" => $requerimiento);
        $resultado = $this->obtenerServicios("obtenerTraslados", $parametros);
        return $resultado;
    }

    # Obtener Detalles de traslado

    public function obtenerTrasladosDetalles($idSession, $idTraslado) {
        $respuesta = new stdclass();
        $respuesta->tipo = 0;

        if ($this->error) {
            $respuesta->error = $this->error;
            $respuesta->tipo = 1;
            return $respuesta;
        }

        $peticion = new stdclass();
        $peticion->IdTraslado = $idTraslado;

        $requerimiento = new stdclass();
        $requerimiento->IdSession = $idSession;
        $requerimiento->Peticion = new SoapVar($peticion, SOAP_ENC_OBJECT, "TrasladoDetallesRequerimiento", "http://schemas.datacontract.org/2004/07/Serviciob2b");
        $requerimiento->ServicioTipo = 0;
        $requerimiento->Tipo = 0;

        $parametros = array("req" => $requerimiento);
        $resultado = $this->obtenerServicios("obtenerTrasladosDetalles", $parametros);

        return $resultado;
    }

    

    #Obtener listado de actividades

    public function ObtenerActividades($idSession, $adultos, $menores, $edadMenores = array(), $idActividad, $idDestino, $tipoServicio, $idioma, $fecha, $tipo = null, $servicioTipo = null) {
        $respuesta = new stdclass();
        $respuesta->tipo = 0;

        if ($this->error) {
            $respuesta->error = $this->error;
            $respuesta->tipo = 1;
            return $respuesta;
        }

        $peticion = new stdclass();
        $peticion->CantAdultos = $adultos;
        $peticion->CantMenores = $menores;
        $peticion->Destino = $idDestino;
        $peticion->EdadMenores = $edadMenores;
        $peticion->Fecha = new DateTime($fecha);
        $peticion->Fecha = $peticion->Fecha->format("Y-m-d");
        $peticion->IdActividad = $idActividad;
        //$peticion->TipoServicio=$tipoServicio;
        $peticion->Idioma = $idioma;

        $requerimiento = new stdclass();
        $requerimiento->IdSession = $idSession;
        $requerimiento->Peticion = new SoapVar($peticion, SOAP_ENC_OBJECT, "ActividadesRequerimiento", "http://schemas.datacontract.org/2004/07/Serviciob2b");
        $requerimiento->ServicioTipo = 0;
        $requerimiento->Tipo = 0;

        $parametros = array("req" => $requerimiento);
        $resultado = $this->obtenerServicios("ObtenerActividades", $parametros);
        return $resultado;
    }

    #Obtener listado de actividades2

    public function ObtenerActividades2($idSession, $adultos, $menores, $edadMenores = array(), $idActividad=0, $idDestino, $tipoServicio, $idioma, $fecha, $tipo = null, $servicioTipo = null) {
        $respuesta = new stdclass();
        $respuesta->tipo = 0;

        if ($this->error) {
            $respuesta->error = $this->error;
            $respuesta->tipo = 1;
            return $respuesta;
        }

        $peticion = new stdclass();
        $peticion->CantAdultos = $adultos;
        $peticion->CantMenores = $menores;
        
        $peticion->EdadMenores = $edadMenores;
        $peticion->Fecha = new DateTime($fecha);
        $peticion->Fecha = $peticion->Fecha->format("Y-m-d");
        
        //$peticion->TipoServicio=$tipoServicio;
        $peticion->Idioma = $idioma;

        $tipoDeBusqueda= new stdclass();
        $stringTipoDeBusqueda="";
        if($idActividad==="")$idActividad=0;
        if($idActividad!==0){
            $stringTipoDeBusqueda="PRODUCTO";
            $tipoDeBusqueda->IdProducto=$idActividad;
        }
        else{
            $stringTipoDeBusqueda="DESTINO";
            $tipoDeBusqueda->Destino=$idDestino;
        }

        $peticion->TipoDeBusqueda=new SoapVar($tipoDeBusqueda, SOAP_ENC_OBJECT, $stringTipoDeBusqueda, "http://schemas.datacontract.org/2004/07/Serviciob2b");
        
        $requerimiento = new stdclass();
        $requerimiento->IdSession = $idSession;
        $requerimiento->Peticion = new SoapVar($peticion, SOAP_ENC_OBJECT, "ActividadesRequerimiento", "http://schemas.datacontract.org/2004/07/Serviciob2b");
        $requerimiento->ServicioTipo = 0;
        $requerimiento->Tipo = 0;

        $parametros = array("req" => $requerimiento);
        $resultado = $this->obtenerServicios("ObtenerActividades", $parametros);
        return $resultado;
    }

    # Agrega Productos a cache (carrito) en el Web Services

    public function preReserva($idSession, $idPaquete, $idProducto) {
        $respuesta = new stdclass();
        $respuesta->tipo = 0;

        if ($this->error) {
            $respuesta->error = $this->error;
            $respuesta->tipo = 1;
            return $respuesta;
        }

        $peticion = new stdclass();
        $peticion->IdPaquete = $idPaquete;
        $peticion->IdProducto = $idProducto;

        $requerimiento = new stdclass();
        $requerimiento->IdSession = $idSession;
        $requerimiento->Peticion = new SoapVar($peticion, SOAP_ENC_OBJECT, "PreReservaRequerimiento", "http://schemas.datacontract.org/2004/07/Serviciob2b");
        $requerimiento->ServicioTipo = 0;
        $requerimiento->Tipo = 0;

        $parametros = array("req" => $requerimiento);
        $resultado = $this->obtenerServicios("preReserva", $parametros);

        return $resultado;
    }

    # Devuelve la lista de productos en cache del web services (Carrito)

    public function resumenPreReserva($idSession) {
        $respuesta = new stdclass();
        $respuesta->tipo = 0;

        if ($this->error) {
            $respuesta->error = $this->error;
            $respuesta->tipo = 1;
            return $respuesta;
        }

        $peticion = new stdclass();

        $requerimiento = new stdclass();
        $requerimiento->IdSession = $idSession;
        $requerimiento->Peticion = new SoapVar($peticion, SOAP_ENC_OBJECT, "ResumenPreReservaRequerimiento", "http://schemas.datacontract.org/2004/07/Serviciob2b");
        $requerimiento->ServicioTipo = 0;
        $requerimiento->Tipo = 0;

        $parametros = array("req" => $requerimiento);
        $resultado = $this->obtenerServicios("resumenPreReserva", $parametros);

        return $resultado;
    }

    # Quita un producto de la cache (carrito) en el Web Services

    public function quitarProducto($idSession, $idPaquete, $idProducto) {
        $respuesta = new stdclass();
        $respuesta->tipo = 0;

        if ($this->error) {
            $respuesta->error = $this->error;
            $respuesta->tipo = 1;
            return $respuesta;
        }

        $peticion = new stdclass();
        $peticion->IdPaquete = $idPaquete;
        $peticion->IdProducto = $idProducto;

        $requerimiento = new stdclass();
        $requerimiento->IdSession = $idSession;
        $requerimiento->Peticion = new SoapVar($peticion, SOAP_ENC_OBJECT, "QuitarProductoRequerimiento", "http://schemas.datacontract.org/2004/07/Serviciob2b");
        $requerimiento->ServicioTipo = 0;
        $requerimiento->Tipo = 0;

        $parametros = array("req" => $requerimiento);
        $resultado = $this->obtenerServicios("quitarProducto", $parametros);

        return $resultado;
    }

    # COMPLETARDATOS
    # El objeto $producto que recibe la siguiente función es polimorfico,
    # tiene diferentes atributos dependiendo el tipo de producto
    # VER "El Apendice A" al final de este documento para ver la estructura de cada Producto

    public function CompletarDatos($idSession, $idPaquete, $idProducto, $datosPasajero, $producto) {
        $respuesta = new stdclass();
        $respuesta->tipo = 0;

        if ($this->error) {
            $respuesta->error = $this->error;
            $respuesta->tipo = 1;
            return $respuesta;
        }

        $DatosPaquete = new stdclass();
        # Datos del pasajero
        $DatosPaquete->Nombre = $datosPasajero->Nombre;
        $DatosPaquete->Apellido = $datosPasajero->Apellido;
        $DatosPaquete->Correo = $datosPasajero->Correo;
        $DatosPaquete->Localizador = $datosPasajero->Localizador;
        $DatosPaquete->Observaciones = $datosPasajero->Observaciones;
        $DatosPaquete->Telefono = $datosPasajero->Telefono;

        # Construyendo petición
        switch ($producto->tipo) {
            case 1:
                # HOTEL
                $ProductoSolicitud = "DatosPaqueteHotel";
                $CuartoHuesped = new stdclass();
                $CuartoHuesped = $producto->Habitaciones;
                $DatosPaquete->CuartoHuesped = $CuartoHuesped;
                break;
            case 2:
                # TOUR
                $ProductoSolicitud = "DatosPaqueteTour";

                if (isset($producto->PickUp) && !empty($producto->PickUp)) {
                    $pickUp = $this->construirPickUp($producto->PickUp);
                    if ($pickUp->tipo == 1)
                        return $pickUp;

                    $DatosPaquete->LugarInicio = $pickUp->result;
                }

                if (isset($producto->DropOff) && !empty($producto->DropOff)) {
                    $dropOff = $this->construirDropOff($producto->DropOff);
                    if ($dropOff->tipo == 1)
                        return $dropOff;

                    $DatosPaquete->LugarTermino = $dropOff->result;
                }

                break;
            case 3:
                # TRASLADOS
                $ProductoSolicitud = "DatosPaqueteTransfer";

                if (isset($producto->TipoTraslado)) {
                    $TipoTraslado = new stdclass();
                    # TipoTraslado 1:sencillo, 2: Redondo

                    switch ($producto->TipoTraslado) {
                        # Sencillo
                        case 1:
                            $TipoDeTransportacion = "TransportacionSencilla";

                            $pickUp = $this->construirPickUp($producto->LugarInicioIda);
                            if ($pickUp->tipo == 1)
                                return $pickUp;
                            $TipoTraslado->LugarInicioIda = $pickUp->result;

                            $dropOff = $this->construirDropOff($producto->LugarTerminoIda);
                            if ($dropOff->tipo == 1)
                                return $dropOff;

                            $TipoTraslado->LugarTerminoIda = $dropOff->result;
                            break;
                        # Redondo
                        case 2:

                            $TipoDeTransportacion = "TransportacionRedonda";

                            # Datos de Ida
                            $pickUp = $this->construirPickUp($producto->LugarInicioIda);
                            if ($pickUp->tipo == 1)
                                return $pickUp;
                            $TipoTraslado->LugarInicioIda = $pickUp->result;

                            $dropOff = $this->construirDropOff($producto->LugarTerminoIda);
                            if ($dropOff->tipo == 1)
                                return $dropOff;
                            $TipoTraslado->LugarTerminoIda = $dropOff->result;

                            # DATOS DE VUELTA
                            $pickUp = $this->construirPickUp($producto->LugarInicioVuelta);
                            if ($pickUp->tipo == 1)
                                return $pickUp;
                            $TipoTraslado->LugarInicioVuelta = $pickUp->result;

                            $dropOff = $this->construirDropOff($producto->LugarTerminoVuelta);
                            if ($dropOff->tipo == 1)
                                return $dropOff;

                            $TipoTraslado->LugarTerminoVuelta = $dropOff->result;

                            break;
                        default:
                            $respuesta->tipo = 1;
                            $respuesta->error = "El tipo del Traslado no es valido";
                            return $respuesta;
                    }

                    $DatosPaquete->TipoTraslado = new SoapVar($TipoTraslado, SOAP_ENC_OBJECT, $TipoDeTransportacion, "http://schemas.datacontract.org/2004/07/Serviciob2b");
                }
                else {
                    $respuesta->tipo = 1;
                    $respuesta->error = "El campo Tipo de Traslado es requerido";
                    return $respuesta;
                }
                break;
            case 4:
                # CIRCUITOS
                $ProductoSolicitud = "DatosPaqueteCircuito";

                # PICKUPS Y DROPOFFS
                # LISTA DE PICK UPS
                if (isset($producto->LugaresDePickUp)) {
                    $LugaresDePickUp = array();

                    $i = 0;
                    foreach ($producto->LugaresDePickUp as $pickups) {
                        $pickUp = $this->construirPickUp($pickups);

                        if ($pickUp->tipo == 1)
                            return $pickUp;

                        $LugaresDePickUp[$i] = $pickUp->result;

                        $i++;
                    }

                    $DatosPaquete->LugaresDePickUp = new stdclass();
                    $DatosPaquete->LugaresDePickUp = $LugaresDePickUp;
                }

                # LISTA DE DROP OFFS
                if (isset($producto->LugaresDeDropOff)) {
                    $LugaresDeDropOff = array();

                    $i = 0;
                    foreach ($producto->LugaresDeDropOff as $dropOffs) {
                        $dropOff = $this->construirDropOff($dropOffs);

                        if ($dropOff->tipo == 1)
                            return $dropOff;

                        $LugaresDeDropOff[$i] = $dropOff->result;

                        $i++;
                    }

                    $DatosPaquete->LugaresDeDropOff = new stdclass();
                    $DatosPaquete->LugaresDeDropOff = $LugaresDeDropOff;
                }

                # Construir estructura en base al tipo de cirucito
                if (isset($producto->IncluyeHotel)) {
                    if ($producto->IncluyeHotel) {
                        $TipoDeCircuito = "ConAlojamineto";
                        $TipoCircuito = new stdclass();
                        $TipoCircuito->CuartoHuesped = new stdclass();
                        $TipoCircuito->CuartoHuesped->CuartoDetallesHuesped = $producto->Hospedaje;
                    } else {
                        $TipoDeCircuito = "SinAlojamiento";
                        $TipoCircuito = new stdclass();
                        $TipoCircuito->Hoteles = new stdclass();
                        $TipoCircuito->Hoteles->HotelesDetalles = $producto->Hospedaje;
                    }
                    $DatosPaquete->TipoCircuito = new SoapVar($TipoCircuito, SOAP_ENC_OBJECT, $TipoDeCircuito, "http://schemas.datacontract.org/2004/07/Serviciob2b");
                } else {
                    $respuesta->tipo = 1;
                    $respuesta->error = "El Campo: InclueyHotel es requerido y debe tomar valores: true o false";
                    return $respuesta;
                }
                break;

            case 5:
                # Vuelos
                $ProductoSolicitud = "DatosPaqueteVuelo";

                if (!isset($producto->Pasajeros)) {
                    $respuesta->tipo = 1;
                    $respuesta->error = "El Objeto Pasajero no es valido";
                    return $respuesta;
                }

                $DatosPaquete->Pasajero = $producto->Pasajeros;

                break;

            case 6:
                # Autos
                $ProductoSolicitud = "DatosPaqueteAuto";

                if (!isset($producto->DatosPersonales)) {
                    $respuesta->tipo = 1;
                    $respuesta->error = "El Objeto Producto no es valido";
                    return $respuesta;
                }

                $DatosPaquete->DatosPersonales = $producto->DatosPersonales;
                $DatosPaquete->DetallesDePasaporte = $producto->Pasaporte;
                $DatosPaquete->Direccion = $producto->Direccion;
                # $DatosPaquete->IdPasajero=$producto->IdPasajero;

                $DatosPaquete->Extras = $producto->Extras;

                break;

            case 12:
                # COTIZACION
                $ProductoSolicitud = "DatosPaqueteCotizado";

                # PICKUPS Y DROPOFFS
                # LISTA DE PICK UPS
                if (isset($producto->LugaresDePickUp)) {
                    $LugaresDePickUp = array();

                    $i = 0;
                    foreach ($producto->LugaresDePickUp as $pickups) {
                        $pickUp = $this->construirPickUp($pickups);

                        if ($pickUp->tipo == 1)
                            return $pickUp;

                        $LugaresDePickUp[$i] = $pickUp->result;

                        $i++;
                    }

                    $DatosPaquete->LugaresDePickUp = new stdclass();
                    $DatosPaquete->LugaresDePickUp = $LugaresDePickUp;
                }

                # LISTA DE DROP OFFS
                if (isset($producto->LugaresDeDropOff)) {
                    $LugaresDeDropOff = array();

                    $i = 0;
                    foreach ($producto->LugaresDeDropOff as $dropOffs) {
                        $dropOff = $this->construirDropOff($dropOffs);

                        if ($dropOff->tipo == 1)
                            return $dropOff;

                        $LugaresDeDropOff[$i] = $dropOff->result;

                        $i++;
                    }

                    $DatosPaquete->LugaresDeDropOff = new stdclass();
                    $DatosPaquete->LugaresDeDropOff = $LugaresDeDropOff;
                }

                # Construir estructura en base al tipo de cirucito
                if (isset($producto->IncluyeHotel)) {
                    if ($producto->IncluyeHotel) {
                        $TipoDeCotizado = "ConAlojamineto";
                        $TipoCotizado = new stdclass();
                        $TipoCotizado->CuartoHuesped = new stdclass();
                        $TipoCotizado->CuartoHuesped->CuartoDetallesHuesped = $producto->Hospedaje;
                    } else {
                        $TipoDeCotizado = "SinAlojamiento";
                        $TipoCotizado = new stdclass();
                        $TipoCotizado->Hoteles = new stdclass();
                        $TipoCotizado->Hoteles->HotelesDetalles = $producto->Hospedaje;
                    }
                    $DatosPaquete->TipoCotizado = new SoapVar($TipoCotizado, SOAP_ENC_OBJECT, $TipoDeCotizado, "http://schemas.datacontract.org/2004/07/Serviciob2b");
                } else {
                    $respuesta->tipo = 1;
                    $respuesta->error = "El Campo: InclueyHotel es requerido y debe tomar valores: true o false";
                    return $respuesta;
                }

                break;

            default:
                $respuesta->tipo = 1;
                $respuesta->error = "El tipo de producto no es valido.";
                return $respuesta;
        }

        $peticion = new stdclass();
        $peticion->IdProducto = $idProducto;
        $peticion->IdPaquete = $idPaquete;
        $peticion->DatosPaquete = new SoapVar($DatosPaquete, SOAP_ENC_OBJECT, $ProductoSolicitud, "http://schemas.datacontract.org/2004/07/Serviciob2b");

        $requerimiento = new stdclass();
        $requerimiento->IdSession = $idSession;
        $requerimiento->Peticion = new SoapVar($peticion, SOAP_ENC_OBJECT, "CompletarDatosPaquete", "http://schemas.datacontract.org/2004/07/Serviciob2b");
        $requerimiento->ServicioTipo = 0;
        $requerimiento->Tipo = 0;

        $parametros = array("req" => $requerimiento);

        #print_r($parametros);
        #exit();
        $resultado = $this->obtenerServicios("CompletarDatosPaquete", $parametros);

        return $resultado;
    }

    # Construye la peticion para PickUp

    private function construirPickUp($pickUp) {
        $respuesta = new stdclass();

        # Eligiendo PickUp
        $LugarInicio = new stdclass();
        if (isset($pickUp)) {
            $LugarInicio->Hora = $pickUp->Hora;
            $LugarInicio->IdHora = $pickUp->IdHora;
            $LugarInicio->IdLugar = isset($pickUp->IdLugar) ? $pickUp->IdLugar : 0;
            $tipo = (int) $pickUp->tipo;
            $LugarInicio->Tipo = $tipo;
            $LugarInicio->Aerolinea = isset($pickUp->Aerolinea) ? $pickUp->Aerolinea : null;
            $LugarInicio->NoVuelo = isset($pickUp->NoVuelo) ? $pickUp->NoVuelo : 0;
        }
        $respuesta->result = $LugarInicio;
        $respuesta->tipo = 0;
        return $respuesta;
    }

    # Construye la peticion para DropOff

    private function construirDropOff($dropOff) {
        $respuesta = new stdclass();
        $LugarTermino = new stdclass();

        if (isset($dropOff)) {
            if ($dropOff->tipo == 4) {
                # Eligiendo DropOffs
                $TipoLugar = new stdclass();
                $TipoLugar->Hora = $dropOff->Hora;
                $TipoLugar->IdLugar = isset($dropOff->IdLugar) ? $dropOff->IdLugar : 0;
                $TipoLugar->Aerolinea = isset($dropOff->Aerolinea) ? $dropOff->Aerolinea : null;
                $TipoLugar->NoVuelo = isset($dropOff->NoVuelo) ? $dropOff->NoVuelo : 0;
                $TipoLugar->Tipo = $dropOff->tipo;

                $respuesta->result = $TipoLugar;
            } else {
                # Eligiendo DropOffs
                $LugarTermino = new stdclass();
                $LugarTermino->Hora = $dropOff->Hora;
                $LugarTermino->IdHora = $dropOff->IdHora;
                $LugarTermino->IdLugar = isset($dropOff->IdLugar) ? $dropOff->IdLugar : 0;
                $LugarTermino->Aerolinea = isset($dropOff->Aerolinea) ? $dropOff->Aerolinea : null;
                $LugarTermino->NoVuelo = isset($dropOff->NoVuelo) ? $dropOff->NoVuelo : 0;
                $LugarTermino->Tipo = $dropOff->tipo;

                $respuesta->result = $LugarTermino;
            }
        } else {
            $respuesta->result = $LugarTermino;
        }

        $respuesta->tipo = 0;
        return $respuesta;
    }

    # Completar Compra

    public function comprar($idSession, $idFormaDePago = 0, $idMetodoDePago = 0) {
        #1 #1
        $respuesta = new stdclass();
        $respuesta->tipo = 0;

        if ($this->error) {
            $respuesta->error = $this->error;
            $respuesta->tipo = 1;
            return $respuesta;
        }

        $peticion = new stdclass();
        $peticion->IdFormaDePago = $idFormaDePago;
        $peticion->IdMetodoDePago = $idMetodoDePago;

        $requerimiento = new stdclass();
        $requerimiento->IdSession = $idSession;
        $requerimiento->Peticion = new SoapVar($peticion, SOAP_ENC_OBJECT, "CompletarCompra", "http://schemas.datacontract.org/2004/07/Serviciob2b");
        $requerimiento->ServicioTipo = 0;
        $requerimiento->Tipo = 0;

        $parametros = array("req" => $requerimiento);
        $resultado = $this->obtenerServicios("CompletarCompra", $parametros);

        return $resultado;
    }

    # Establecer Moneda

    public function establecerMoneda($idSession, $moneda) {
        $respuesta = new stdclass();
        $respuesta->tipo = 0;

        if ($this->error) {
            $respuesta->error = $this->error;
            $respuesta->tipo = 1;
            return $respuesta;
        }

        $peticion = new stdclass();
        $peticion->SetTipoMoneda = $moneda;

        $requerimiento = new stdclass();
        $requerimiento->IdSession = $idSession;
        $requerimiento->Peticion = new SoapVar($peticion, SOAP_ENC_OBJECT, "TipoMonedaRequerimiento", "http://schemas.datacontract.org/2004/07/Serviciob2b");
        $requerimiento->ServicioTipo = 0;
        $requerimiento->Tipo = 0;

        $parametros = array("req" => $requerimiento);
        $resultado = $this->obtenerServicios("TipoMonedaRequerimiento", $parametros);

        return $resultado;
    }

    # Devuelve la ultima Peticion XML realizada al Web Services

    public function obtenerSolicitudXML() {
        return htmlentities($this->cliente->__getLastRequest());
    }

    # Devuelve la ultima Respuesta devuelta por el Web Services

    public function obtenerRespuestaXML() {
        return htmlentities($this->cliente->__getLastResponse());
    }

    ##############################################################
    ################      APENDICE          #####################
    #                 APENDICE (A):
    # Estructura de objeto $Producto para 'CompletarDatos'
    # ******* [ ESTRUCTURA PARA TOUR ]
    # $producto=new stdclass();
    # $producto->tipo=3;# 1:Hotel, 2: Tour, 3:Traslado, 4:Circuito
    # $producto->TipoTraslado=1; # 1: sencillo, 2: Redondo
    # PickUps
    # $producto->PickUp=new stdclass();
    # $producto->PickUp->tipo=1; # 1:Hotel, 2:AutoTransporte, 3:Aeropuerto
    # $producto->PickUp->IdLugar=1;
    # $producto->PickUp->Hora="07:45";
    # Si es en Terminal Aerea incluir los siguientes 2 datos
    # $producto->PickUppickup->Aerolinea="interjet";
    # $producto->PickUppickup->NoVuelo=2626;
    # DropOffs
    # $producto->DropOff=new stdclass();
    # $producto->DropOff->tipo=1; # 1:Hotel, 2:AutoTransporte, 3:Aeropuerto
    # $producto->DropOff->IdLugar=154;
    # $producto->DropOff->Hora="15:30";
    # Si es en Terminal Aerea incluir los siguientes 2 datos
    # $producto->DropOff->Aerolinea="Aeromexico";
    # $producto->DropOff->NoVuelo=23;


}

?>