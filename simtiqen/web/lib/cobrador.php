<?php

/* Funciona Giovanni, 10-05-2011 */

/* Cobradores */
class Cobrador{
  var $idCobrador;
  var $nombre;
  var $cedula;
  var $activo;
  var $fechadi;
  var $bitacora;
  
  //Contructor
  function __construct(){
     $this->Limpiar();
  }
  
  //Limpia las propiedades    
  function Limpiar(){
     $this->idCobrador = 0;
     $this->nombre = "";
     $this->cedula = "";
     $this->activo = 0;
     $this->fechadi = date("Y-m-d H:i:s");
  }

  //Guarda los datos , recibe como paremetro la transacción si existe 
  function Guardar($link){
  	   $this->Validar();
  	   try {
  	     $nueva = ($this->idCobrador == 0);
        if ($this->idCobrador == 0){
          $consulta = sprintf("insert into cobrador (nombre,cedula,activo,fechadi) values ('%s','%s',%s,'%s')",
	            sqlClearText($this->nombre),
	            sqlClearText($this->cedula),
	            $this->activo,
	            sqlClearText($this->fechadi));
          $bit = "Agrega el Cobrador ".$this->nombre;
        }else{
          $consulta = sprintf("update cobrador set 
	            nombre = '%s',
	            cedula = '%s',
	            activo = %s 
	            where idCobrador = %s",
	            sqlClearText($this->nombre),
	            sqlClearText($this->cedula),
	            $this->activo,
	            sqlClearText($this->idCobrador));
          $bit = "Modifica el Cobrador ".$this->idCobrador." ".$this->nombre;
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
		      $this->idCobrador=$link->bdUltimoId();
		  $this->bitacora->bitacorizar($bit);
        if($cerrartran)
            $link->bdCierraTran();
		}
		catch (SimError $ex) {
			throw $ex;
	   }
	   return $this->idCobrador;
    }

  
  //Carga los datos del Cobrador
  function Cargar(){
    $consulta = sprintf($this->Seleccion()." from cobrador where idcobrador = %s",sqlClearText($this->idCobrador));
  	 $objMy = new tiqmysql();
    $resulquery = $objMy->bdEjecutar($consulta);
    if ($objMy->bdCantLineas($resulquery)>0){
       $linea = mysqli_fetch_array($resulquery);
       $this->nombre = $linea["nombre"];
       $this->cedula = $linea["cedula"];
       $this->activo = $linea["activo"];
       $this->fechadi = $linea["fechadi"];
       return true;
    }else{
      return false;
    }
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
 	              $this->activo = $parametros["activo"];
 	              $retorno = true;
       	        break;
       	case 2:
       	        $this->idCobrador = $parametros["idcobrador"];
      	        $this->nombre = $parametros["nombre"];
      	        $this->cedula = $parametros["cedula"];
 	              $this->activo = $parametros["activo"];
 	              $retorno = true;
       	        break;
       	case 3:
       	        $this->idCobrador = $parametros["idcobrador"];
 	              $retorno = $this->Cargar();
       	        break;
       }
       return $retorno;
   }
   
    function CargarId($pIdCobrador){
      $this->idCobrador = $pIdCobrador;
      return $this->Cargar();
  }
     
    //Valida los campos  
    function Validar(){
      $errores="";
		if(strlen($this->nombre) <= 0)
		  $errores.="Detalle del Cobrador en blanco";
		if(strlen($this->cedula) <= 0)
		  $errores.="Cédula del Cobrador en blanco";
		if($errores!="")		
			throw new SimError($errores);      
    }
    
    //Borra el Cobrador
    function Borrar(){
        if ($this->idCobrador > 0){
        	  $objMy = new tiqmysql();
           $consulta = sprintf("delete from cobrador where idCobrador = %s",sqlClearText($this->idCobrador));
           $resultado = $objMy->bdEjecutar($consulta);
           if ($resultado > 0){
              $this->bitacora->Bitacorizar("Elimina el Cobrador ".$this->idCobrador." ".$this->nombre);
           }
           return $resultado;
        }else{
           return 0;
        }
    }
    
    
     function ListarActivos($filtro){
       $link=new tiqmysql();
       $tira = "";
       if ($filtro == null){
       	$tira = "";
       }else{
          for($i = 0;$i<count($filtro);$i++){ 
              $valor = $filtro[$i];
              $tira .= " and " . $valor;
          }
       }
       return $link->bdEjecutar("select * from cobrador where activo ".$tira." order by nombre ");
    } 

    
    
    //Pasa el objeto a un string 
    function ToString(){
      $tira = "Id = " . $this->idCobrador . "\n";
      $tira .= "Nombre = " . $this->nombre . "\n";
      $tira .= "Cédula = " . $this->cedula . "\n";
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
       return $link->bdEjecutar("select * from cobrador " . $tira . " order by nombre ". $limite);
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
       return $link->bdCantLineas($link->bdEjecutar("select idcobrador from cobrador " . $tira));
    }
    
  function Seleccion(){
    return "Select idcobrador,nombre,cedula,activo,fechadi ";
  }
  
  }
?>
