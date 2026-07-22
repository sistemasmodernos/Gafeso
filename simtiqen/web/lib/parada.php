<?php
/* Funciona, Giovanni 10-05-2011 */

/* Paradas */
class Parada{
  var $idParada;
  var $nombre;
  var $activo;
  var $fechadi;
  var $bitacora;
  var $noextra;
  
  //Contructor
  function __construct(){
     $this->Limpiar();
  }
  
  //Limpia las propiedades    
  function Limpiar(){
     $this->idParada = 0;
     $this->nombre = "";
     $this->activo = 0;
     $this->fechadi = date("Y-m-d H:i:s");
  }
  
  //Carga los datos de la parada
  function Cargar(){
  	 $objMy = new tiqmysql();
    $conexion = $objMy->bdAbrir();
    $consulta = sprintf($this->Seleccion()." from parada where idparada = %s",sqlClearText($this->idParada));
    $resulquery = $objMy->bdEjecutar($consulta,$conexion);
    if ($objMy->bdCantLineas($resulquery)==1){
       $linea = mysqli_fetch_array($resulquery);
       $this->nombre = $linea["nombre"];
       $this->activo = $linea["activo"];
       $this->fechadi = $linea["fechadi"];
       $this->noextra = $linea["noextra"] ;
       return $this->idParada;
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
                 $this->noextra = $parametros["noextra"];                  
 	              $retorno = true;
       	        break;
       	case 2:
       	        $this->idParada = $parametros["idparada"];
      	        $this->nombre = $parametros["nombre"];
 	              $this->activo = $parametros["activo"];
                 $this->noextra = $parametros["noextra"];                                    
 	              $retorno = true;
       	        break;
       	case 3:
       	        $this->idParada = $parametros["idparada"];
 	              $retorno = $this->Cargar();
       	        break;
       }
       return $retorno;
   }
    
    
  function CargarId($pIdParada){
      $this->idParada = $pIdParada;
      return $this->Cargar();
  }
  
  //Guarda los datos , recibe como paremetro la transacción si existe 
  function Guardar($link){
  	   $this->Validar();
  	   try {
  	     $nueva = ($this->idParada == 0);
        if ($this->idParada == 0){
          $consulta = sprintf("insert into parada (nombre,activo,fechadi,noextra) values ('%s',%s,'%s',%d)",
	            sqlClearText($this->nombre),
	            $this->activo,
	            sqlClearText($this->fechadi),
                $this->noextra);
          $bit = "Agrega la Parada ".$this->nombre;
        }else{
          $consulta = sprintf("update parada set 
	            nombre = '%s',
	            activo = %s ,
                noextra = %d
	            where idparada = %s",
	            sqlClearText($this->nombre),
	            $this->activo,
                $this->noextra,
	            sqlClearText($this->idParada));
          $bit = "Modifica la Parada ".$this->idParada." ".$this->nombre;
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
		      $this->idParada=$link->bdUltimoId();
		  $this->bitacora->bitacorizar($bit);
        if($cerrartran)
            $link->bdCierraTran();
		}
		catch (SimError $ex) {
			throw $ex;
	   }
	   return $this->idParada;
    }
    
    //Valida los campos  
    function Validar(){
      $errores="";
		if(strlen($this->nombre) <= 0)
		  $errores.="Detalle de la parada en blanco";
		if($errores!="")		
			throw new SimError($errores);      
      
    }
    
    //Borra la parada
    function Borrar(){
        if ($this->idParada > 0){
        	  $objMy = new tiqmysql();
           $consulta = sprintf("delete from lista where idparada1 = %s",sqlClearText($this->idParada));   
           $objMy->bdEjecutar($consulta);
           $consulta = sprintf("delete from lista where idparada2 = %s",sqlClearText($this->idParada));   
           $objMy->bdEjecutar($consulta);
           $consulta = sprintf("delete from parada where idparada = %s",sqlClearText($this->idParada));
           $resultado = $objMy->bdEjecutar($consulta);
           if ($resultado > 0){
              $this->bitacora->Bitacorizar("Elimina la Parada ".$this->idParada." ".$this->nombre);
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
       return $link->bdEjecutar("select * from parada " . $tira . " order by nombre ". $limite);
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
       return $link->bdCantLineas($link->bdEjecutar("select idparada from parada " . $tira));
    }
     
function ListarActivos($opciones){
       $link=new tiqmysql();
       return $link->bdEjecutar("select idparada,nombre,noextra,idruta from parada where activo order by nombre ");
    }      

    
    //Pasa el objeto a un string 
    function ToString(){
      $tira = "Id = " . $this->idParada . "\n";
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
    return "Select idparada,nombre,activo,fechadi ,noextra,idruta ";
  }
  
  }
?>
