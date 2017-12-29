<?php
namespace App\Http\Helpers;


use stdClass;

class Enumglobales{

	public $grupoEtario;

	public $tipoProducto;
	
	public $formaDePago;

	public $metodoDePago;

	public $tipoServicio;

	public $tipoCircuito;
	
	public $tipoTraslado;

	public $estadoVenta;

	public $categoriaHotel;

	public $tipoLugar;

	public $busquedaPorGrupo;

	public function __construct() {

		$this->grupoEtario= new stdclass();
		$this->tipoProducto = new stdclass();
		$this->formaDePago = new stdclass();
		$this->metodoDePago = new stdclass();
		$this->tipoServicio= new stdclass();
		$this->tipoCircuito= new stdclass();
		$this->tipoTraslado= new stdclass();
		$this->estadoVenta= new stdclass();
		$this->categoriaHotel= new stdclass();
		$this->tipoLugar= new stdclass();
		$this->busquedaPorGrupo= new stdclass();

		$this->grupoEtario->ADULTO = 1;
		$this->grupoEtario->MENOR = 0;

		$this->tipoProducto->HOTEL = 1;
		$this->tipoProducto->TOUR = 2;
		$this->tipoProducto->TRASLADO = 3;
		$this->tipoProducto->CIRCUITO = 4;
		$this->tipoProducto->VUELO = 5;
		$this->tipoProducto->AUTO = 6;
		$this->tipoProducto->ATRACCION = 7;
		$this->tipoProducto->CRUCERO = 8;
		$this->tipoProducto->RESTAURANTE = 10;
		$this->tipoProducto->GRUPOS = 11;
		$this->tipoProducto->COTIZACION = 12;
		$this->tipoProducto->ACTIVIDAD = 13;       

		$this->formaDePago->PRERESERVA = 1;
		$this->formaDePago->PAGO_DIRECTO = 2;
		$this->formaDePago->COMPRA_DIRECTA = 3;

		$this->metodoDePago->CREDITO_B2B = 1;
		$this->metodoDePago->DEPOSITO = 2;
		$this->metodoDePago->TRANSFERENCIA = 3;
		$this->metodoDePago->PAYPAL = 4;
		$this->metodoDePago->TARJETA_CREDITO = 5;
		$this->metodoDePago->COMPRA_DIRECTA = 6;

		$this->tipoServicio->PRIVADO = 0;
		$this->tipoServicio->COMPARTIDO = 1;

		$this->tipoCircuito->NO_APLICA = -1;
		$this->tipoCircuito->TERRESTRE = 0;
		$this->tipoCircuito->CON_HOTEL = 1;

		$this->tipoTraslado->SENCILLO = 1;
		$this->tipoTraslado->REDONDO = 2;

		$this->estadoVenta->COMPRADO = 1;
		$this->estadoVenta->PRERESERVA = 2;
		$this->estadoVenta->CANCELADO = 3;
		$this->estadoVenta->REVISION = 4;


		$this->categoriaHotel->TODOS = -1;     

		$this->tipoLugar->HOTEL = 1;
		$this->tipoLugar->AUTOTRANSPORTE = 2;
		$this->tipoLugar->AEROPUERTO = 3;
		$this->tipoLugar->OTRO = 4;

		$this->busquedaPorGrupo->TODO = -1;
		$this->busquedaPorGrupo->SIN_GRUPO = 0;
		$this->busquedaPorGrupo->GRUPO = 1;


	}

}

?>