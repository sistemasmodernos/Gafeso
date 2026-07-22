<?php

/* Funciona Giovanni, 10-05-2011 */

/* Choferes */
class Chofer{
  var $idChofer;
  var $nombre;
  var $cedula;
  var $correo;
  var $activo;
  var $fechadi;
  var $bitacora;
  
  //Contructor
  function __construct(){
     $this->Limpiar();
  }
  
  //Limpia las propiedades    
  function Limpiar(){
     $this->idChofer = 0;
     $this->nombre = "";
     $this->cedula = "";
     $this->correo = "";
     $this->activo = 0;
     $this->fechadi = date("Y-m-d H:i:s");
  }
  
  //Carga los datos del Chofer
  function Cargar(){
  	 $objMy = new tiqmysql();
    $conexion = $objMy->bdAbrir();
    $consulta = sprintf($this->Seleccion()." from chofer where idChofer = %s",sqlClearText($this->idChofer));
    $resulquery = $objMy->bdEjecutar($consulta,$conexion);
    if ($objMy->bdCantLineas($resulquery)==1){
       $linea = mysqli_fetch_array($resulquery);
       $this->nombre = $linea["nombre"];
       $this->cedula = $linea["cedula"];
       $this->correo = $linea["correo"];
       $this->activo = $linea["activo"];
       $this->fechadi = $linea["fechadi"];
       return $this->idChofer;
    }else{
      return 0;
    }
  }
    
  function CargarId($pIdChofer){
      $this->idChofer = $pIdChofer;
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
      	        $this->nombre = $parametros["nombre"];
      	        $this->cedula = $parametros["cedula"];
      	        $this->correo = $parametros["correo"];
 	            $this->activo = $parametros["activo"];
 	            $retorno = true;
       	        break;
       	case 2:
       	        $this->idChofer = $parametros["idchofer"];
      	        $this->nombre = $parametros["nombre"];
      	        $this->cedula = $parametros["cedula"];
      	        $this->correo = $parametros["correo"];
 	            $this->activo = $parametros["activo"];
				$retorno = true;
       	        break;
       	case 3:
       	        $this->idChofer = $parametros["idchofer"];
 	            $retorno = $this->Cargar();
       	        break;
       }
       return $retorno;
   }
  
  //Guarda los datos , recibe como paremetro la transacción si existe 
  function Guardar($link){
  	   $this->Validar();
  	   try {
  	     $nueva = ($this->idChofer == 0);
        if ($this->idChofer == 0){
          $consulta = sprintf("insert into chofer (nombre,cedula,activo,fechadi,correo) values ('%s','%s',%s,'%s','%s')",
	            sqlClearText($this->nombre),
	            sqlClearText($this->cedula),
	            $this->activo,
	            sqlClearText($this->fechadi),
				sqlClearText($this->correo));
          $bit = "Agrega el Chofer ".$this->nombre;
        }else{
          $consulta = sprintf("update chofer set 
	            nombre = '%s',
	            cedula = '%s',
	            correo = '%s',
	            activo = %s 
	            where idChofer = %s",
	            sqlClearText($this->nombre),
	            sqlClearText($this->cedula),
	            sqlClearText($this->correo),
	            $this->activo,
	            sqlClearText($this->idChofer));
          $bit = "Modifica el Chofer ".$this->idChofer." ".$this->nombre;
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
		      $this->idChofer=$link->bdUltimoId();
		  $this->bitacora->bitacorizar($bit);
        if($cerrartran)
            $link->bdCierraTran();
		}
		catch (SimError $ex) {
			throw $ex;
	   }
	   return $this->idChofer;
    }
    
    //Valida los campos  
    function Validar(){
      $errores="";
		if(strlen($this->nombre) <= 0)
		  $errores.="Detalle del Chofer en blanco";
		if(strlen($this->cedula) <= 0)
		  $errores.="Cédula del Chofer en blanco";
		if($errores!="")		
			throw new SimError($errores);      
    }
    
    //Borra el Chofer
    function Borrar(){
        if ($this->idChofer > 0){
        	  $link = new tiqmysql();
           $consulta = sprintf("delete from chofer where idChofer = %s",sqlClearText($this->idChofer));
           $resultado = $link->bdEjecutar($consulta);
           if ($resultado > 0){
              $this->bitacora->Bitacorizar("Elimina el Chofer ".$this->idChofer." ".$this->nombre);
           }
           return $resultado;
        }else{
           return 0;
        }
    }
    
    //Pasa el objeto a un string 
    function ToString(){
      $tira = "Id = " . $this->idChofer . "\n";
      $tira .= "Nombre = " . $this->nombre . "\n";
      $tira .= "Cédula = " . $this->cedula . "\n";
      $tira .= "Correo = " . $this->correo . "\n";
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
       return $link->bdEjecutar("select * from chofer " . $tira . " order by nombre ". $limite);
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
       return $link->bdEjecutar("select * from chofer where activo = 1 " . $tira . " order by nombre ". $limite);
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
       return $link->bdCantLineas($link->bdEjecutar("select idchofer from chofer " . $tira));
    }
  
  function Seleccion(){
    return "Select idChofer,nombre,cedula,correo,activo,fechadi ";
  }
  
  }
?>
