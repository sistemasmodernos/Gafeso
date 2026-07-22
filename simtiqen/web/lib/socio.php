<?php

/* Funciona Giovanni, 10-05-2011 */

/* socioes */
class socio{
  var $idsocio;
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
     $this->idsocio = 0;
     $this->nombre = "";
     $this->cedula = "";
     $this->correo = "";
     $this->activo = 0;
     $this->fechadi = date("Y-m-d H:i:s");
  }
  
  //Carga los datos del socio
  function Cargar(){
  	 $objMy = new tiqmysql();
    $conexion = $objMy->bdAbrir();
    $consulta = sprintf($this->Seleccion()." from socio where idsocio = %s",sqlClearText($this->idsocio));
    $resulquery = $objMy->bdEjecutar($consulta,$conexion);
    if ($objMy->bdCantLineas($resulquery)==1){
       $linea = mysqli_fetch_array($resulquery);
       $this->nombre = $linea["nombre"];
       $this->cedula = $linea["cedula"];
       $this->correo = $linea["correo"];
       $this->activo = $linea["activo"];
       $this->fechadi = $linea["fechadi"];
       return $this->idsocio;
    }else{
      return 0;
    }
  }
    
  function CargarId($pIdsocio){
      $this->idsocio = $pIdsocio;
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
       	        $this->idsocio = $parametros["idsocio"];
      	        $this->nombre = $parametros["nombre"];
      	        $this->cedula = $parametros["cedula"];
                $this->correo = $parametros["correo"];
 	              $this->activo = $parametros["activo"];
 	              $retorno = true;
       	        break;
       	case 3:
       	        $this->idsocio = $parametros["idsocio"];
 	              $retorno = $this->Cargar();
       	        break;
       }
       return $retorno;
   }
  
  //Guarda los datos , recibe como paremetro la transacción si existe 
  function Guardar($link){
  	   $this->Validar();
  	   try {
  	     $nueva = ($this->idsocio == 0);
        if ($this->idsocio == 0){
          $consulta = sprintf("insert into socio (nombre,cedula,correo,activo,fechadi) values ('%s','%s','%s',%s,'%s')",
	            sqlClearText($this->nombre),
	            sqlClearText($this->cedula),
              sqlClearText($this->correo),
	            $this->activo,
	            sqlClearText($this->fechadi));
          $bit = "Agrega el socio ".$this->nombre;
        }else{
          $consulta = sprintf("update socio set 
	            nombre = '%s',
	            cedula = '%s',
              correo = '%s',
	            activo = %s 
	            where idsocio = %s",
	            sqlClearText($this->nombre),
	            sqlClearText($this->cedula),
              sqlClearText($this->correo),
	            $this->activo,
	            sqlClearText($this->idsocio));
          $bit = "Modifica el socio ".$this->idsocio." ".$this->nombre;
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
		      $this->idsocio=$link->bdUltimoId();
		  $this->bitacora->bitacorizar($bit);
        if($cerrartran)
            $link->bdCierraTran();
		}
		catch (SimError $ex) {
			throw $ex;
	   }
	   return $this->idsocio;
    }
    
    //Valida los campos  
    function Validar(){
      $errores="";
		if(strlen($this->nombre) <= 0)
		  $errores.="Detalle del socio en blanco";
		if(strlen($this->cedula) <= 0)
		  $errores.="Cédula del socio en blanco";
		if($errores!="")		
			throw new SimError($errores);      
    }
    
    //Borra el socio
    function Borrar(){
        if ($this->idsocio > 0){
        	  $link = new tiqmysql();
           $consulta = sprintf("delete from socio where idsocio = %s",sqlClearText($this->idsocio));
           $resultado = $link->bdEjecutar($consulta);
           if ($resultado > 0){
              $this->bitacora->Bitacorizar("Elimina el socio ".$this->idsocio." ".$this->nombre);
           }
           return $resultado;
        }else{
           return 0;
        }
    }
    
    //Pasa el objeto a un string 
    function ToString(){
      $tira = "Id = " . $this->idsocio . "\n";
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
       return $link->bdEjecutar("select * from socio " . $tira . " order by nombre ". $limite);
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
       return $link->bdEjecutar("select * from socio where activo = 1 " . $tira . " order by nombre ". $limite);
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
       return $link->bdCantLineas($link->bdEjecutar("select idsocio from socio " . $tira));
    }
  
  function Seleccion(){
    return "Select idsocio,nombre,cedula,correo,activo,fechadi ";
  }
  
  }
?>
