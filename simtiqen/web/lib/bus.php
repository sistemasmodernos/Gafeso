<?php
class Bus{
 	var $idBus;
 	var $placa;
 	var $activo;
 	var $color;
 	var $chofer;
  var $socio;
  var $cantpas;
 	var $bitacora;
  var $esquema;
  var $asientosespe;
    
//Contructor
 	function __construct(){
 	 $this->Limpiar();
 	}
    
//Limpia las propiedades    
 	function Limpiar() {
 		$this->idBus=0;
 		$this->placa="";
 		$this->activo=1;
 		$this->color=0;
 		$this->cantpas=0;
    $this->chofer = new Chofer();
    $this->socio = new socio();
    $this->esquema="";
    $this->asientosespe='';

	}
    
//Guarda los datos , recibe como paremetro la transacci'on si existe 
	function Guardar($link){
  	$this->Validar();
		try {
		$bit='';
		$nueva= ($this->idBus==0);
		if($this->idBus==0) {
			$sql =sprintf("insert into Bus (placa,activo,color,cantpas,idchofer,idsocio,esquema,fechadi,asientosespe)
			  values ('%s',%d,%d,%d,%d,%d,'%s','%s','%s')",
			    $this->placa,
			    $this->activo,
			    $this->color,
			    $this->cantpas,
          $this->chofer->idChofer,
          $this->socio->idsocio,
          $this->esquema,
			    date("Y-m-d H:i:s"),
          $this->asientosespe);
			 $bit= $bit . "Crea Bus";
			}
		else {
			echo "2";
			$sql=sprintf("update Bus set 
			placa ='%s',
			activo=%d,
			color=%d,
			cantpas=%d ,
      idchofer=%d,
      idsocio=%d,
      esquema='%s',
      asientosespe = '%s'
			where idBus=%d",
			$this->placa,
			$this->activo,
			$this->color,
			$this->cantpas,
      $this->chofer->idChofer,
      $this->socio->idsocio,
      $this->esquema,
      $this->asientosespe,
			$this->idBus );
			$bit.= "Actualiza Bus " . $this->idBus . " " . $this->placa; 
			}
		if($link==null){
			$link =new tiqmysql();
		  $cerrartran=true;
		}
		else 
		  $cerrartran=false;
		$link->bdAbreTran();
		$res = $link->bdEjecutar($sql,$link);
		if($nueva)
		   $this->idBus=$link->bdUltimoId();
		$this->bitacora->bitacorizar($bit);
      if($cerrartran)
         $link->bdCierraTran();
		}
		catch(SimError $ex){
			throw $ex;
	   }
	  return $this->idBus;
	}
    
   //Carga los datos de la Bus 
	function Cargar(){
        $sql = sprintf("select * from Bus where idBus=%d",$this->idBus);
        $link = new tiqmysql();
        $res = $link->bdEjecutar($sql);
        if($link->bdCantLineas($res)>0){
           $linea = mysqli_fetch_array($res);    
           $this->placa = $linea["placa"];
           $this->activo = $linea["activo"];
           $this->color = $linea["color"];
           $this->cantpas=$linea["cantpas"];
           $this->chofer->idChofer=$linea["idchofer"];
           $this->socio->idsocio=$linea["idsocio"];
           $this->esquema = $linea["esquema"];
           $this->asientosespe=$linea["asientosespe"];
           return true;
        }
        else
          return false;
     }
     
   // Basado en un arreglo carga los datos para el mantenimiento
   // $parametros: arreglo que trae los datos
   // $opcion: tipo de operación que se realizará en el mantenimiento
   //          1- Agregar, 2- Modificar, 3- Borrar
   function CargarArreglo($parametros,$opcion){
       $retorno = null;
       switch($opcion) {
       	case 1:
      	        $this->placa = $parametros["placa"];
      	        $this->color = $parametros["color"];
      	        $this->cantpas = $parametros["cantpas"];
      	        $this->chofer->idChofer = $parametros["idchofer"];
                $this->socio->idsocio = $parametros["idsocio"];
 	              $this->activo = $parametros["activo"];
                $this->asientosespe = $parametros["asientosespe"];
 	              $retorno = true;
       	        break;
       	case 2:
       	        $this->idBus = $parametros["idBus"];
      	        $this->placa = $parametros["placa"];
      	        $this->color = $parametros["color"];
      	        $this->cantpas = $parametros["cantpas"];
      	        $this->chofer->idChofer = $parametros["idchofer"];
                $this->socio->idsocio = $parametros["idsocio"];
 	              $this->activo = $parametros["activo"];
                $this->asientosespe = $parametros["asientosespe"];
 	              $retorno = true;
       	        break;
       	case 3:
       	        $this->idBus = $parametros["idBus"];
 	              $retorno = $this->Cargar();
       	        break;
       }
       return $retorno;
   }
     
     
   //Valida los campos  
	function Validar() {
 		$errores="";
   	if(trim($this->placa)=="")
		  $errores.="Detalle de la Bus en blanco";
    if(!$this->chofer->Cargar())
      $errores.="El chofer no existe";
    if(!$this->socio->Cargar())
      $errores.="El Socio no existe";
		if($errores!="")		
			throw new SimError($errores);
	}
//Borra la Bus
    function Borrar(){
        $sql=sprintf("delete from Bus where idBus=%d",$this->idBus);
        $link = new tiqmysql();
        $res = $link->bdEjecutar($sql);
        $this->bitacora->Bitacorizar("Elimina Bus " . $this->placa);
        return true;
     }    
//Hereda el objeto bitac'acora    
	function HeredaBitacora($pbit){
		$pbit->nivel++;		
		$this->bitacora=$pbit;
        $this->bitacora->obPadre=$this;
		
	}
//Pasa el objeto a un string 
   function ToString(){
     $cad = "ID " . $this->idBus  . "/n" .
     "Placa: " . $this->placa . "/n" .
     "Activo: " . $this->activo . "/n" .
     "Color : "  . $this->color . "/n" . 
     "Cantidad de pasajeros: " . $this->cantpas ."/n" .
     "Chofer : " . $this->chofer->nombre;
     "Socio : " . $this->socio->nombre;
     return $cad;
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
       return $link->bdEjecutar("select Bus.*,chofer.Nombre as chofer,socio.Nombre as socio from Bus left join chofer on Bus.idchofer = chofer.idChofer left join socio on Bus.idsocio = socio.idsocio " . $tira . " order by placa ". $limite);
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
       return $link->bdEjecutar("select idbus,placa,idchofer from Bus where activo ".$tira." order by placa ");
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
       return $link->bdCantLineas($link->bdEjecutar("select idbus from Bus " . $tira));
    }
	
}
 
 
?>