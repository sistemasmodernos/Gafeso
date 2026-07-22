<?php



/* perfils */
class Perfil{
  var $idPerfil;
  var $nombre;
  var $activo;
  var $fechadi;
  var $bitacora;
  
  //Contructor
  function __construct(){
     $this->Limpiar();
  }
  
  //Limpia las propiedades    
  function Limpiar(){
     $this->idPerfil = 0;
     $this->nombre = "";
     $this->activo = 0;
     $this->fechadi = date("Y-m-d H:i:s");
  }
  
  //Carga los datos de la Perfil
  function Cargar(){
  	 $objMy = new tiqmysql();
    $conexion = $objMy->bdAbrir();
    $consulta = sprintf($this->Seleccion()." from perfil where idPerfil = %s",sqlClearText($this->idPerfil));
    $resulquery = $objMy->bdEjecutar($consulta,$conexion);
    if ($objMy->bdCantLineas($resulquery)==1){
       $linea = mysqli_fetch_array($resulquery);
       $this->nombre = $linea["nombre"];
       $this->activo = $linea["activo"];
       $this->fechadi = $linea["fechadi"];
       return $this->idPerfil;
    }else{
      return 0;
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
 	              $this->activo = $parametros["activo"];
 	              $retorno = true;
       	        break;
       	case 2:
       	        $this->idPerfil = $parametros["idperfil"];
      	        $this->nombre = $parametros["nombre"];
 	              $this->activo = $parametros["activo"];
 	              $retorno = true;
       	        break;
       	case 3:
       	        $this->idPerfil = $parametros["idperfil"];
 	              $retorno = $this->Cargar();
       	        break;
       }
       return $retorno;
   }
    
    
  function CargarId($pIdPerfil){
      $this->idPerfil = $pIdPerfil;
      return $this->Cargar();
  }
  
  //Guarda los datos , recibe como paremetro la transacción si existe 
  function Guardar($link){
  	   $this->Validar();
  	   try {
  	     $nueva = ($this->idPerfil == 0);
        if ($this->idPerfil == 0){
          $consulta = sprintf("insert into perfil (nombre,activo,fechadi) values ('%s',%s,'%s')",
	            sqlClearText($this->nombre),
	            $this->activo,
	            sqlClearText($this->fechadi));
          $bit = "Agrega la Perfil ".$this->nombre;
        }else{
          $consulta = sprintf("update perfil set 
	            nombre = '%s',
	            activo = %s 
	            where idPerfil = %s",
	            sqlClearText($this->nombre),
	            $this->activo,
	            sqlClearText($this->idPerfil));
          $bit = "Modifica la Perfil ".$this->idPerfil." ".$this->nombre;
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
		      $this->idPerfil=$link->bdUltimoId();
		  $this->bitacora->bitacorizar($bit);
        if($cerrartran)
            $link->bdCierraTran();
		}
		catch (SimError $ex) {
			throw $ex;
	   }
	   return $this->idPerfil;
    }
    
    //Valida los campos  
    function Validar(){
      $errores="";
		if(strlen($this->nombre) <= 0)
		  $errores.="Detalle de la Perfil en blanco";
		if($errores!="")		
			throw new SimError($errores);      
      
    }
    
    //Borra la Perfil
    function Borrar(){
        if ($this->idPerfil > 0){
        	  $objMy = new tiqmysql();
           $consulta = sprintf("delete from perfil where idPerfil = %s",sqlClearText($this->idPerfil));
           $resultado = $objMy->bdEjecutar($consulta);
           if ($resultado > 0){
              $this->bitacora->Bitacorizar("Elimina el Perfil ".$this->idPerfil." ".$this->nombre);
           }
           return $resultado;
        }else{
           return 0;
        }
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
       return $link->bdEjecutar("select * from perfil " . $tira . " order by nombre ". $limite);
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
       return $link->bdCantLineas($link->bdEjecutar("select idPerfil from perfil " . $tira));
    }
     
function ListarActivos($opciones){
       $link=new tiqmysql();
       return $link->bdEjecutar("select idperfil,nombre from perfil where activo order by nombre ");
    }      

    
    //Pasa el objeto a un string 
    function ToString(){
      $tira = "Id = " . $this->idPerfil . "\n";
      $tira .= "Nombre = " . $this->nombre . "\n";
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
  
  function Seleccion(){
    return "Select idPerfil,nombre,activo,fechadi ";
  }
  
  }
?>
