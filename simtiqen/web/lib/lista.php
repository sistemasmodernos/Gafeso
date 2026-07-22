<?php
 class Lista{
        var $idLista;
        var $parada1;
        var $parada2;
        var $precio;
        var $activo;
        var $producto;
        var $fechadi;
        var $preciofuturo;
        var $fechaapli;
 //Contructor
 	function __construct(){
 	   $this->Limpiar();
 	}
    
//Limpia las propiedades    
 	function Limpiar() {
 		$this->parada1 = new Parada();
 		$this->parada2 = new Parada();
 		$this->activo=1;
                $this->producto = new Producto();
                $this->fechadi = date("Y-m-d H:i:s");
                $this->preciofuturo=0;
                $this->fechaapli="1900-01-01";

	}
	function ToString(){
		return "";
	}
}
?>