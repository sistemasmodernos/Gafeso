<?php

/* Forma de Pago */
class FormaPago{
	var $idFormaPago;
	var $desFormaPago;
	var $activo;
	var $entraCierre;
	var $bitacora;
  
	//Contructor
	function __construct(){
		$this->Limpiar();
	}
  
	//Limpia las propiedades    
	function Limpiar(){
		$this->idFormaPago = 0;
		$this->desFormaPago = "";
		$this->entraCierre = 0;
		$this->activo = 0;
	}
  
	//Carga los datos de la Forma de Pago
	function Cargar(){
		$objMy = new tiqmysql();
		$conexion = $objMy->bdAbrir();
		$consulta = sprintf($this->Seleccion()." from formapago where idformapago = %s",sqlClearText($this->idFormaPago));
		$resulquery = $objMy->bdEjecutar($consulta,$conexion);
		if ($objMy->bdCantLineas($resulquery)==1){
			$linea = mysqli_fetch_array($resulquery);
			$this->desFormaPago = $linea["desformapago"];
			$this->activo = $linea["activo"];
			$this->entraCierre = $linea["entracierre"];
			return $this->idFormaPago;
		}else{
			return 0;
		}
	}
    
	function CargarId($pidFormaPago){
		$this->idFormaPago = $pidFormaPago;
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
				$this->desFormaPago = $parametros["desFormaPago"];
 	            $this->activo = $parametros["activo"];
				$this->entraCierre = $parametros["entracierre"];
 	            $retorno = true;
       	        break;
			case 2:
       	        $this->idFormaPago = $parametros["idFormaPago"];
      	        $this->desFormaPago = $parametros["desFormaPago"];
				$this->entraCierre = $parametros["entracierre"];
 	            $this->activo = $parametros["activo"];
				$retorno = true;
       	        break;
			case 3:
       	        $this->idFormaPago = $parametros["idFormaPago"];
 	            $retorno = $this->Cargar();
       	        break;
		}
		return $retorno;
	}
  
	//Guarda los datos , recibe como paremetro la transacción si existe 
	function Guardar($link){
		$this->Validar();
		try {
			$nueva = ($this->idFormaPago == 0);
			if ($this->idFormaPago == 0){
				$consulta = sprintf("insert into formapago (desformapago,activo,entracierre) values ('%s',%s,%s)",
					sqlClearText($this->desFormaPago),
					$this->activo,
					$this->entraCierre);
				$bit = "Agrega el FormaPago ".$this->desFormaPago;
			}else{
				$consulta = sprintf("update formapago set 
					desformapago = '%s',
					activo = %s,
					entracierre = %s 
					where idformapago = %s",
					sqlClearText($this->desFormaPago),
					$this->activo,
					$this->entraCierre,
					sqlClearText($this->idFormaPago));
				$bit = "Modifica el FormaPago ".$this->idFormaPago." ".$this->desFormaPago;
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
				$this->idFormaPago = $link->bdUltimoId();
			$this->bitacora->bitacorizar($bit);
			if($cerrartran)
				$link->bdCierraTran();
		}
		catch (SimError $ex) {
			throw $ex;
		}
		return $this->idFormaPago;
    }
    
    //Valida los campos  
    function Validar(){
		$errores="";
		if(strlen($this->desFormaPago) <= 0)
			$errores.="Detalle del Tipo de Viaje en blanco";
		if($errores!="")		
			throw new SimError($errores);      
    }
    
    //Borra el FormaPago
    function Borrar(){
		if ($this->idFormaPago > 0){
			$link = new tiqmysql();
			$consulta = sprintf("delete from formapago where idformapago = %s",sqlClearText($this->idFormaPago));
			$resultado = $link->bdEjecutar($consulta);	
			if ($resultado > 0){
				$this->bitacora->Bitacorizar("Elimina el FormaPago ".$this->idFormaPago." ".$this->desFormaPago);
			}
			return $resultado;
		}else{
			return 0;
        }
    }
    
    //Pasa el objeto a un string 
    function ToString(){
		$tira = "Id = " . $this->idFormaPago . "\n";
		$tira .= "Forma de Pago = " . $this->desFormaPago . "\n";
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
		return $link->bdEjecutar("select * from formapago " . $tira . " order by desformapago ". $limite);
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
		return $link->bdEjecutar("select * from formapago where activo = 1 " . $tira . " order by entracierre desc, desformapago ". $limite);
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
		return $link->bdCantLineas($link->bdEjecutar("select idFormaPago from formapago " . $tira));
    }
  
  function Seleccion(){
    return "select idformapago,desformapago,activo,entracierre ";
  }
  
  }
?>
