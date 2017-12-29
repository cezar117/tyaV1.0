<?php

namespace App\Http\Controllers;

use App\Http\Helpers\EnumGlobales;
use App\Http\Servicios\ServicioOtisa;
use Illuminate\Http\Request;

class CircuitosController extends Controller
{

    private $path = "circuitos";
    private $ServicioOtisa;
    public $idSessionWSDL;

    public function busqueda(Request $request)
    {
// dd($request);
        $ServicioOtisa = new ServicioOtisa();
        $EnumGlobales  = new EnumGlobales();
        $idSessionWSDL = ObtenerSession();
        $checkIn       = $request->input('checkIn');
        $destino       = $request->input('destino');
        $idioma        = 2;
        $tipoServicio  = 1;
        $categoria     = $request->input('categoria') != null ? $request->input('categoria') : 1;
        $tipo_circuito = 1;
        $Adultos       = $request->input('adultos');
        $Ninos         = $request->input('menores');
        $edadMenores   = $request->input("edades1") != null ? $request->input("edades1") : array();

        $grupo = array(
            "Adultos"     => $Adultos,
            "Menores"     => $Ninos,
            "edadMenores" => $edadMenores,
        );

        $circuitos = null;

        $RespuestaCircuitos = $ServicioOtisa->obtenerCircuitos2($idSessionWSDL, $checkIn, $destino, 0, $idioma, $tipo_circuito, $tipoServicio, array($grupo));
        if (!empty($RespuestaCircuitos->xml->obtenerServiciosResult->CircuitoRespuesta->ListadeCircuitos->Circuito)) {

            $CircuitosXml = $RespuestaCircuitos->xml->obtenerServiciosResult->CircuitoRespuesta->ListadeCircuitos->Circuito;
            if (!empty($CircuitosXml)) {
                $CircuitosX = objetoParseArray($CircuitosXml);
                foreach ($CircuitosX as $circuito) {
                    $circuitos["" . $circuito->Id . ""] = $circuito;}

                session(['circuitos' => $circuitos]);

                $busquedaCircuitos = [
                    "adultos"      => $Adultos,
                    "menores"      => $Ninos,
                    "destino"      => getNombreDestino($destino),
                    "tipo"         => $tipo_circuito,
                    "checkIn"      => $checkIn,
                    "edadMenores"  => $edadMenores,
                    "idioma"       => $idioma,
                    "tipoServicio" => $tipoServicio,
                ];

                session(['oBusquedaCircuitos' => $busquedaCircuitos]);

                return view($this->path . ".listaCircuitos", [
                    "busqueda"     => $busquedaCircuitos,
                    "from"         => "resultados",
                    "circuitosXML" => objetoParseArray($CircuitosXml),
                ]);

            } else {

                return view("estructura.error");
            }
        } else {return view("estructura.error");}

    }

    public function detalleCircuito(Request $request)
    {
        $ServicioOtisa = new ServicioOtisa();
        $idSessionWSDL = ObtenerSession();
        $idCircuito    = $request->get("circuito");
        // dd($idCircuito);
        $circuitos = session('circuitos');
        $busqueda  = session('oBusquedaCircuitos');
        //dd($busqueda);
        $grupo = array(
            "Adultos"     => $busqueda["adultos"],
            "Menores"     => $busqueda["menores"],
            "edadMenores" => $busqueda["edadMenores"],
        );
        $CircuitosXml = null;

        $RespuestaCircuitos = $ServicioOtisa->obtenerCircuitos2($idSessionWSDL, $busqueda["checkIn"], 0, $idCircuito, $busqueda["idioma"], $busqueda["tipo"], $busqueda["tipoServicio"], array($grupo));
        // dd($RespuestaCircuitos);
        if (!empty($RespuestaCircuitos->xml->obtenerServiciosResult->CircuitoRespuesta->ListadeCircuitos->Circuito)) {

            $CircuitosXml = objetoparseArray($RespuestaCircuitos->xml->obtenerServiciosResult->CircuitoRespuesta->ListadeCircuitos->Circuito);

            foreach ($CircuitosXml as $circuito) {
                $circuitos["" . $circuito->Id . ""] = $circuito;}
            session(['circuitos' => $circuitos]);
            $circuitos = session('circuitos');
            //dd($circuitos);
        }

        $detallesCircuitos = $ServicioOtisa->obtenerDetallesCircuito($idSessionWSDL, $idCircuito, 2, 1, 1, 0);

        $CircuitoDetalles = $detallesCircuitos->xml->obtenerServiciosResult->CircuitoDetalleRespuesta->ListaDeCircuitosDetalles->CircuitoDetalles;
//dd($CircuitoDetalles);
        if (!empty($circuitos[$idCircuito]->Package->PackageCircuito)) {
            $paquete = $circuitos[$idCircuito]->Package->PackageCircuito;
            session(['packageroomscircuito' => $paquete]);
        } else {
            $paquete = null;
        }
// dd(session('packageroomscircuito'));
        $CircuitoRutas = $circuitos[$idCircuito]->rutas->RutaItinerario;
        return view($this->path . ".vistaDetalle", [
            "paquetes"         => objetoParseArray($paquete),
            "circuito"         => $circuitos,
            "circuitoss"       => $circuitos[$idCircuito],
            "rutas"            => $CircuitoRutas,
            "CircuitoDetalles" => $CircuitoDetalles,
            "busqueda"         => $busqueda,
        ]);
    }

}
