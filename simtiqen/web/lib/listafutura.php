<?php

class ListaFutura{
	var $idListaFutura;
	var $fechadi;
	var $aplicada;
	var $fechaapli;
	var $listas;

	function Guardar($link){
		if($link==null){
			$link =new tiqmysql();
            $link->bdAbreTran();  
		  $cerrartran=true;
		}
		else 
		  $cerrartran=false;
		if($idListaFutura==0){
			$sql=" insert into listafutura (fechadi,fechapli,aplicada,fechareal)
				values (now(),'".$this->fechapli."',0,'1900-01-01') ";
			$nueva=true;
			$bit="Crea nueva lista futura ";
		}
		else{
			$sql=sprintf("update listafutura set fechapli='%s', aplicada=%d, fechareal='%'
		   where idlistafutura=%d",$this->fechapli,$this->aplicada,$this->fechareal,$this->idListaFutura);
			$nueva=false;
			$bit="Actualiza o aplica lista futura";
		}

		$res = $link->bdEjecutar($sql);
		if($nueva){
		  $this->idListaFutura=$link->bdUltimoId();
		}

		for($x=0;$x<count($this->listas);$x++){
			$sql=sprintf("insert into dlistafutura  (idlistafutura,idruta,idproducto,idparada1,idparada2,precio,fechadi) 
			values (%d,%d,%d,%d,%d,%f,now())",
			$this->idlistafutura,
			$this->listas[$x]["idruta"],
			$this->listas[$x]["idproducto"],
			$this->listas[$x]["idparada1"],
			$this->listas[$x]["idparada2"],
			

			)
		}



		if($cerrartran)
			$link->bdCierraTran();

		$this->bitacora->bitacorizar($bit);
		return $this->idListaFutura;


	}

}

?>