<?php

namespace App\Http\Controllers;

use App\Http\Helpers\EnumGlobales;
use App\Http\Servicios\ServicioOtisa;
use Illuminate\Http\Request;

class ToursController extends Controller
{

    private $path = "tours";

    public function busqueda(Request $request)
    {
// dd($request);

        $servicioOtisa = new ServicioOtisa();
        $EnumGlobales  = new EnumGlobales();
        $idSessionWSDL = ObtenerSession();
        $tours         = null;
        $checkIn       = $request->input('checkIn');
        $numAdultos    = $request->input('adultos');
        $numNinos      = $request->input('menores');
        $destino       = $request->input('destino');
        $edadMenores   = $request->input("edades") != null ? $request->input("edades") : array();
        $categoria     = $request->input('categoria') != null ? $request->input('categoria') : 1;
        $tipoServicio  = $request->input('tipo') != null ? $request->input('tipo') : 1;
        $idioma        = 2;

        $respuestaTours = $servicioOtisa->ObtenerTours2($idSessionWSDL, $numAdultos, $numNinos, $edadMenores, 0, $destino, $EnumGlobales->tipoServicio->COMPARTIDO, $idioma, $checkIn, null, null);

        if (!empty($respuestaTours->xml->obtenerServiciosResult->TourRespuesta->ListaDeTours->Tour)) {

            $toursXml = $respuestaTours->xml->obtenerServiciosResult->TourRespuesta->ListaDeTours->Tour;

            foreach ($toursXml as $tour) {
                if (isset($tour->Packages->PackageTours)) {
                    $tour->Precio_final = getPrecioMinimoPaquete(objetoParseArray($tour->Packages->PackageTours));
                }

                $tours["" . $tour->Id . ""] = $tour;
            }

            session(['tours' => $tours]);
            //
            $busqueda = [
                "adultos"      => $numAdultos,
                "menores"      => $numNinos,
                "destino"      => getNombreDestino($destino),
                "idioma"       => $categoria,
                "tipoServicio" => $tipoServicio,
                "edadMenores"  => $edadMenores,
                "idioma"       => $idioma,
                "categoria"    => $categoria,
                "checkIn"      => $checkIn,
            ];

            // dd($busqueda);
            session(['oBusquedaTours' => $busqueda]);

            return view($this->path . ".listaTours", [
                "toursXML" => $tours,
                "busqueda" => $busqueda,
                "from"     => "resultados",
            ]);
        } else {
            return redirect('/')->with('data', 1);
        }
    }

    public function detalles(Request $request)
    {

        $idSessionWSDL = ObtenerSession();
        $servicioOtisa = new ServicioOtisa();
        $idTour        = $request->get("tour");
        $toursXml      = null;
        $busqueda      = session("oBusquedaTours");

        $respuestaTours = $servicioOtisa->ObtenerTours2($idSessionWSDL, $busqueda["adultos"], $busqueda["menores"], $busqueda["edadMenores"], $idTour, $busqueda["destino"][0]["id"], $busqueda["tipoServicio"], $busqueda["idioma"], $busqueda["checkIn"], null, null);

        if (!empty($respuestaTours->xml->obtenerServiciosResult->TourRespuesta->ListaDeTours->Tour)) {
            $toursXml = $respuestaTours->xml->obtenerServiciosResult->TourRespuesta->ListaDeTours->Tour;
        }

        $detallesTour = $servicioOtisa->ObtenerTourDetalle($idSessionWSDL, $idTour);
        // dd($detallesTour);
        $tour = $detallesTour->xml->obtenerServiciosResult->TourDetalleRespuesta->ListaDeToursDetalle->TourDetalles;

        if (isset($toursXml->Packages->PackageTours)) {
            $packageTours = $toursXml->Packages->PackageTours;
            session(['packageroomstours' => $packageTours]);
            if (!isset($toursXml->Precio_final)) {
                $toursXml->Precio_final = getPrecioMinimoPaquete(objetoParseArray($packageTours));
            }
        }

        $packageTours = session("packageroomstours");
        return view($this->path . ".vistaDetalle", [
            "paquetes" => $packageTours,
            "tour"     => $toursXml,
            "detalle"  => $tour,
        ]);
    }

}
