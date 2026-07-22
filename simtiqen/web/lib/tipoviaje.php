<?php

/* Tipo de Viaje */
class TipoViaje{
	var $idTipoViaje;
	var $desTipoViaje;
	var $activo;
	var $bitacora;
  
	//Contructor
	function __construct(){
		$this->Limpiar();
	}
  
	//Limpia las propiedades    
	function Limpiar(){
		$this->idTipoViaje = 0;
		$this->desTipoViaje = "";
		$this->activo = 0;
	}
  
	//Carga los datos del TipoViaje
	function Cargar(){
		$objMy = new tiqmysql();
		$conexion = $objMy->bdAbrir();
		$consulta = sprintf($this->Seleccion()." from tipoviaje where idtipoviaje = %s",sqlClearText($this->idTipoViaje));
		$resulquery = $objMy->bdEjecutar($consulta,$conexion);
		if ($objMy->bdCantLineas($resulquery)==1){
			$linea = mysqli_fetch_array($resulquery);
			$this->desTipoViaje = $linea["destipoviaje"];
			$this->activo = $linea["activo"];
			return $this->idTipoViaje;
		}else{
			return 0;
		}
	}
    
	function CargarId($pidTipoViaje){
		$this->idTipoViaje = $pidTipoViaje;
		return $this->Cargar();
	}
  
	// Basado en un arreglo carga los datos para el mantenimiento
	// $parametros: arreglo que trae los datos
	// $opcion: tipo de operación que se realizará en el mantenimiento
	//          1- Agregar, 2- Modificar, 3- Borrar
	function CargarArreglo($parametros,$opcion){
		$retorno = null;
		switch($opcion) {
			case 1:
				$this->desTipoViaje = $parametros["destipoviaje"];
 	            $this->activo = $parametros["activo"];
 	            $retorno = true;
       	        break;
			case 2:
       	        $this->idTipoViaje = $parametros["idTipoViaje"];
      	        $this->desTipoViaje = $parametros["destipoviaje"];
 	            $this->activo = $parametros["activo"];
				$retorno = true;
       	        break;
			case 3:
       	        $this->idTipoViaje = $parametros["idTipoViaje"];
 	            $retorno = $this->Cargar();
       	        break;
		}
		return $retorno;
	}
  
	//Guarda los datos , recibe como paremetro la transacción si existe 
	function Guardar($link){
		$this->Validar();
		try {
			$nueva = ($this->idTipoViaje == 0);
			if ($this->idTipoViaje == 0){
				$consulta = sprintf("insert into tipoviaje (destipoviaje,activo) values ('%s',%s)",
					sqlClearText($this->desTipoViaje),
					$this->activo);
				$bit = "Agrega el TipoViaje ".$this->desTipoViaje;
			}else{
				$consulta = sprintf("update tipoviaje set 
					destipoviaje = '%s',
					activo = %s 
					where idtipoviaje = %s",
					sqlClearText($this->desTipoViaje),
					$this->activo,
					sqlClearText($this->idTipoViaje));
				$bit = "Modifica el TipoViaje ".$this->idTipoViaje." ".$this->desTipoViaje;
			}
			if($link==null){
				$link = new tiqmysql();
				$cerrartran=true;
			}
			else 
				$cerrartran=false;
			$link->bdAbreTran();
			$res = $link->bdEjecutar($consulta);
			if($nueva)
				$this->idTipoViaje = $link->bdUltimoId();
			$this->bitacora->bitacorizar($bit);
			if($cerrartran)
				$link->bdCierraTran();
		}
		catch (SimError $ex) {
			throw $ex;
		}
		return $this->idTipoViaje;
    }
    
    //Valida los campos  
    function Validar(){
		$errores="";
		if(strlen($this->desTipoViaje) <= 0)
			$errores.="Detalle del Tipo de Viaje en blanco";
		if($errores!="")		
			throw new SimError($errores);      
    }
    
    //Borra el TipoViaje
    function Borrar(){
		if ($this->idTipoViaje > 0){
			$link = new tiqmysql();
			$consulta = sprintf("delete from tipoviaje where idtipoviaje = %s",sqlClearText($this->idTipoViaje));
			$resultado = $link->bdEjecutar($consulta);	
			if ($resultado > 0){
				$this->bitacora->Bitacorizar("Elimina el TipoViaje ".$this->idTipoViaje." ".$this->desTipoViaje);
			}
			return $resultado;
		}else{
			return 0;
        }
    }
    
    //Pasa el objeto a un string 
    function ToString(){
		$tira = "Id = " . $this->idTipoViaje . "\n";
		$tira .= "Tipo de Viaje = " . $this->desTipoViaje . "\n";
		if ($this->activo==1){
			$tira .= "Estado = Activo\n";
		}else{
			$tira .= "Estado = Inactivo\n";
		}
		return $tira;
    }
    
    //Hereda el objeto bitácora    
	function HeredaBitacora($pbit){
		$pbit->nivel++;		
		$this->bitacora=$pbit;
		$this->bitacora->obPadre=$this;
	}
	
	//Listar
    function Listar($filtro,$paginacion){
		$link=new tiqmysql();
		$tira = "";
		$limite = "";
		if ($paginacion != null){
			if ($paginacion["pagina"] > 0){
				$limite = "limit " . $paginacion["registros"] * ($paginacion["pagina"] - 1);
			}else{
				$limite = "limit 0";
			}
			$limite .= "," . $paginacion["registros"];
		}
		if ($filtro == null){
			$tira = "";
		}else{
			$primero = true;
			for($i = 0;$i<count($filtro);$i++){ 
				$valor = $filtro[$i];
				if ($primero){
					$tira .= " where " . $valor;
					$primero = false;
				}else{
					$tira .= " and " . $valor;
				}
			}
		}
		return $link->bdEjecutar("select * from tipoviaje " . $tira . " order by destipoviaje ". $limite);
    }      

	//Listar
    function ListarActivos($filtro){
		$link=new tiqmysql();
		$tira = "";
		$limite = "";
		if ($filtro == null){
			$tira = "";
		}else{
			$primero = true;
			for($i = 0;$i<count($filtro);$i++){ 
				$valor = $filtro[$i];
				if ($primero){
					$tira .= " and " . $valor;
					$primero = false;
				}else{
					$tira .= " and " . $valor;
				}
			}
		}
		return $link->bdEjecutar("select * from tipoviaje where activo = 1 " . $tira . " order by destipoviaje ". $limite);
	}      


   function Cantidad($filtro){
		$link=new tiqmysql();
		$tira = "";
		if ($filtro == null){
			$tira = "";
		}else{
			$primero = true;
			for($i = 0;$i<count($filtro);$i++){ 
				$valor = $filtro[$i];
				if ($primero){
					$tira .= " where " . $valor;
					$primero = false;
				}else{
					$tira .= " and " . $valor;
				}
			}
		}
		return $link->bdCantLineas($link->bdEjecutar("select idtipoviaje from tipoviaje " . $tira));
    }
  
  function Seleccion(){
    return "select idtipoviaje,destipoviaje,activo ";
  }
  
  }
?>
