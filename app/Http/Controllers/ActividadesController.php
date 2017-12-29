<?php

namespace App\Http\Controllers;

use App\Http\Helpers\EnumGlobales;
use App\Http\Servicios\ServicioOtisa;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class ActividadesController extends Controller
{

    private $path = "actividades";

    public function busqueda(Request $request)
    {
// dd($request);

        $servicioOtisa = new ServicioOtisa();
        $EnumGlobales  = new EnumGlobales();
        $idSessionWSDL = ObtenerSession();
        $actividades   = null;
        $checkIn       = $request->input('checkIn');
        $numAdultos    = $request->input('adultos');
        $menores       = $request->input('menores');
        $destino       = $request->input('destino');
        $edadMenores   = $request->input("edades1") != null ? $request->input("edades1") : array();
        $categoria     = $request->input('categoria') != null ? $request->input('categoria') : 1;
        $tipoServicio  = $request->input('tipo') != null ? $request->input('tipo') : 1;
        $idioma        = 1;

//    $respuestaTours = $servicioOtisa->ObtenerTours($idSessionWSDL,$numAdultos,$numNinos,$edadMenores,"",$destino,$tipoServicio,$idioma,$checkIn,null,null);

        $respuestaActividades = $servicioOtisa->ObtenerActividades2($idSessionWSDL, $numAdultos, $menores, $edadMenores, 0, $destino, $EnumGlobales->tipoServicio->COMPARTIDO, $idioma, $checkIn, null, null);

        if (!empty($respuestaActividades->xml->obtenerServiciosResult->ActividadesRespuesta->ListaDeActividades->Actividad)) {

            $actividadesXml = objetoParseArray($respuestaActividades->xml->obtenerServiciosResult->ActividadesRespuesta->ListaDeActividades->Actividad);

            foreach ($actividadesXml as $actividad) {
                $actividades["" . $actividad->Id . ""] = $actividad;
            }

            session(['actividades' => $actividades]);

            $busqueda = [
                "adultos"      => $numAdultos,
                "menores"      => $menores,
                "destino"      => getNombreDestino($destino),
                "idioma"       => $categoria,
                "tipoServicio" => $tipoServicio,
                "edadMenores"  => $edadMenores,
                "idioma"       => $idioma,
                "categoria"    => $categoria,
                "checkIn"      => $checkIn,
            ];

            // dd($busqueda);
            session(['oBusquedaActividades' => $busqueda]);

            return view($this->path . ".listaActividades", [
                "actividadesXML" => $actividadesXml,
                "busqueda"       => $busqueda,
                "from"           => "resultados",
            ]);

        } else {
            return view("estructura.error");
        }

    }

    public function detalleActividad(Request $request)
    {

        $idSessionWSDL  = ObtenerSession();
        $servicioOtisa  = new ServicioOtisa();
        $idActividad    = $request->get("actividad");
        $actividadesXml = null;
        $paquetes       = null;
        $busqueda       = session("oBusquedaActividades");

        $respuestaActividades = $servicioOtisa->ObtenerActividades2($idSessionWSDL, $busqueda["adultos"], $busqueda["menores"], $busqueda["edadMenores"], $idActividad, $busqueda["destino"], $busqueda["tipoServicio"], $busqueda["idioma"], $busqueda["checkIn"], null, null);
        dd($respuestaActividades);
        $actividadesXml = $respuestaActividades->xml->obtenerServiciosResult->ActividadesRespuesta->ListaDeActividades->Actividad;
        // dd($actividadesXml);
        if (isset($actividadesXml->Packages->PackageActividad)) {
            $paquetes = $actividadesXml->Packages->PackageActividad;
        }

        session(['packageroomsactividades' => $paquetes]);

        return view($this->path . ".vistaDetalle", [
            "paquetes"  => $paquetes,
            "actividad" => $actividadesXml,

        ]);
    }

    public function getNombreDestino($destino)
    {
        $path     = app_path();
        $Client   = new Client();
        $response = $Client->request('GET', 'https://otisab2b.com/secciones/home/buscador/1_0/autocomplete/rellenar_autocomplete.php?tipo=1&id=' . $destino, ['verify' => $path . '\cacert.pem']);
        $json;
        $destinos = json_decode($response->getBody()->getContents());
        foreach ($destinos as $destino) {
            # code...
            $json[] = [
                "id"   => $destino->destino_id,
                "text" => $destino->value,
            ];
        }
        return $json;
    }

}
