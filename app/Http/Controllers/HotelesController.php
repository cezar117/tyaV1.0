<?php

namespace App\Http\Controllers;

use App\Http\Requests\BusquedaHotelesRequest;
use App\Http\Servicios\ServicioOtisa;
use Illuminate\Http\Request;

// use App\Http\Controllers\ServiciosController;
class HotelesController extends Controller
{

    private $path = "hoteles";

    /**
     * Store the incoming blog post.
     *
     * @param  BusquedaHotelesRequest  $request
     * @return Response
     */
    public function busqueda(BusquedaHotelesRequest $request)
    {
        // dd($request);
        $servicioOtisa = new ServicioOtisa();
        $idSessionWSDL = ObtenerSession();
        $tipoAloj      = [];
        $tipoCategoria = collect();

        $hoteles          = null; //variable de sesion
        $destino          = $request->input("destino");
        $checkIn          = $request->input("checkIn");
        $checkOut         = $request->input("checkOut");
        $habitaciones     = $request->input("habitaciones");
        $sendHabitaciones = array();
        $aAdultos         = $request->input("adultos");
        $aMenores         = $request->input("menores");
        $edadMenores      = [];
        for ($i = 1, $ii = 0; $i <= $habitaciones; $i++, $ii++) {

            $edades        = $request->input("edades" . $i) != null ? $request->input("edades" . $i) : array();
            $edadMenores[] = $edades;

            $sendHabitaciones[] = array(
                "Adultos"     => intval($aAdultos[$ii]),
                "Menores"     => intval($aMenores[$ii]),
                "edadMenores" => $edades,
            );
        }

        $categoria            = -1;
        $idHotel              = 0;
        $fecha_inicio_detalle = new \DateTime($checkIn);
        $fecha_fin_detalle    = new \DateTime($checkOut);
        $num_noches           = $fecha_inicio_detalle->diff($fecha_fin_detalle);
        $respuestaHoteles     = $servicioOtisa->obtenerHoteles2($idSessionWSDL, $categoria, $checkIn, $checkOut, $idHotel, $destino, $sendHabitaciones);
        //dd($respuestaHoteles);
        if (!empty($respuestaHoteles->xml->obtenerServiciosResult->HotelRespuesta->ListadeHoteles->Hotel)) {
            $hotelesXml = $respuestaHoteles->xml->obtenerServiciosResult->HotelRespuesta->ListadeHoteles->Hotel;
            foreach ($hotelesXml as $hotel) {
                $hotel->estrellasFormato       = getCategoria($hotel->Categoria, $hotel->CategoriaString);
                $hoteles["" . $hotel->Id . ""] = $hotel;

                if (!in_array($hotel->Aloj_tipo, $tipoAloj)) {
                    $tipoAloj[] = $hotel->Aloj_tipo;
                }
                if (!$tipoCategoria->contains("valorCategoria", $hotel->estrellasFormato->valorCategoria . "")) {
                    $tipoCategoria->push($hotel->estrellasFormato);
                }
            }

            session(['hoteles' => $hoteles]);

            $busqueda = [
                "adultos"         => $aAdultos,
                "menores"         => $aMenores,
                "edadMenores"     => $edadMenores,
                "categoria"       => $categoria,
                "destino"         => getNombreDestino($destino),
                "checkIn"         => $checkIn,
                "checkOut"        => $checkOut,
                "habitaciones"    => $habitaciones,
                "habitacionesObj" => $sendHabitaciones,
                "noches"          => $num_noches->d,
            ];
            session(['oBusqueda' => $busqueda]);

//            dd($hotelesXml);

            return view($this->path . ".listaHoteles", [
                "hotelesXML"        => $hoteles,
                "busqueda"          => $busqueda,
                "from"              => "resultados",
                "tiposAlojamientos" => $tipoAloj,
                "tiposCategorias"   => $tipoCategoria->sortByDesc("valorCategoria"),
            ]);
        } else {
            // dd($respuestaHoteles);
            return redirect('/')->with('data', 1);
        }
    }

    public function detalleHotel(Request $request)
    {

        $servicioOtisa = new ServicioOtisa();
        $idSessionWSDL = ObtenerSession();
        $hotelesXml    = null;

        $idHotel          = $request->get("hotel");
        $busqueda         = session("oBusqueda");
        $respuestaHoteles = $servicioOtisa->obtenerHoteles2($idSessionWSDL, $busqueda["categoria"], $busqueda["checkIn"], $busqueda["checkOut"], $idHotel, $busqueda["destino"][0]["id"], $busqueda["habitacionesObj"]);
        $hotelesXml       = $respuestaHoteles->xml->obtenerServiciosResult->HotelRespuesta->ListadeHoteles->Hotel;

        $detallesHotel = $servicioOtisa->ObtenerHotelDetalles($idSessionWSDL, $idHotel, $hotelesXml->IdPaquetes);

        $hotel = $detallesHotel->xml->obtenerServiciosResult->HotelDetalleRespuesta->ListaDetalles->HotelDetalles;

        if (isset($hotelesXml->Packages->PackageRooms)) {
            $packageRooms = $hotelesXml->Packages->PackageRooms;
            session(['packagerooms' => $packageRooms]);
        } else {
            $packageRooms = [];
        }
        return view($this->path . ".vistaDetalle", [
            "paquetes" => $packageRooms,
            "hotel"    => $hotelesXml,
            "detalle"  => $hotel,
        ]);
    }

}
