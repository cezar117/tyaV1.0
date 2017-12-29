
<?php
if($datos->codigo == 0){}

  $rs = $ServicioOtisa->comprar($idSession,$idFormadePago,$idMetodoDepago); ##este metodo debe devolver el id de venta etc.   
    
    #if($rs->xml->obtenerServiciosResult->CompletarCompraRespuesta != null ){
        if($rs->xml->obtenerServiciosResult->CompletarCompraRespuesta->Status === true){
          $DatosComprar = $rs->xml->obtenerServiciosResult->CompletarCompraRespuesta; 
// dd($rs);
        session(["ComprarRespuesta" => $DatosComprar ]);

          $IdReserva = $rs->xml->obtenerServiciosResult->CompletarCompraRespuesta->DistincionOperacional->IdOperacion; #IdOperacion 16713

          $CodigoReserva = $rs->xml->obtenerServiciosResult->CompletarCompraRespuesta->Vouchers->VoucherRespuesta->DetallesGenerales->CodigoReserva;# CodigoReserva 16713A
$IdReserva = $CodigoReserva;
// $indice = explode("A",$CodigoReserva);
// dd($indice);
              #crearVaucher($venta, $descripcion, $producto, $paquete, $tipo, $grupo, $idSession)
              // $rsss = $ServicioOtisa->obtenerResumenDeReserva($IdReserva,1, $idSession);
              //      dd($rsss);
              $rss = $ServicioOtisa->estatusReserva($IdReserva, $idSession);
             // dd($rss);
              $EstadoReservaRespuesta = $rss->xml->obtenerServiciosResult->EstadoReservaRespuesta;
              session(["EstadoReservaRespuesta" => $EstadoReservaRespuesta]);

              $Estado = $rss->xml->obtenerServiciosResult->EstadoReservaRespuesta->Estado;
              ##probar indice con 1 ## 
              $indice = $rss->xml->obtenerServiciosResult->EstadoReservaRespuesta->Productos->ProductoReservaEstatus->Indice;

              $Estado_prod = $rss->xml->obtenerServiciosResult->EstadoReservaRespuesta->Productos->ProductoReservaEstatus->Estado;
            $venta = $IdReserva;

###########VOUCHER  #################
              #crearVaucher($venta, $descripcion, $producto, $paquete, $tipo, $grupo, $idSession)
              $voucher = $ServicioOtisa->crearVaucher($venta, null, null, null, null, null, $idSession);
                   dd($voucher);


              $rsss = $ServicioOtisa->obtenerResumenDeReserva($venta, $indice, $idSession);
                   $ResumenDeReserva = $rss->xml->obtenerServiciosResult->ResumenDeReserva;

                session(["ResumenDeReserva" => $ResumenDeReserva]);

                  session(["CompletarCompraSession" => $rs->xml->obtenerServiciosResult->CompletarCompraRespuesta]);

return true;
                  }else{
                  $mensaje = $rs->xml->obtenerServiciosResult->ErrorRespuesta->Error->Error->Mensaje;
                    return $mensaje;
                   
                  }

?>