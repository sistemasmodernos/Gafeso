<?php
 class Estacion{
 	var $idEstacion;
 	var $nombre;
 	var $activo;
 	var $parada;
 	var $bitacora;
    
//Contructor
 	function __construct(){
 	 $this->Limpiar();
 	}
    
//Limpia las propiedades    
 	function Limpiar() {
 		$this->idEstacion=0;
 		$this->nombre="";
 		$this->activo=1;
      $this->parada= new Parada();

	}
    
//Guarda los datos , recibe como paremetro la transacci'on si existe 
	function Guardar($link){
		$this->Validar();
		try {
		$bit='';
		$nueva= ($this->idEstacion==0);
		if($nueva) {
			$sql =sprintf("insert into estacion (nombre,activo,idparada,fechadi)
			  value ('%s',%d,%d,'%s')",
			    $this->nombre,
			    $this->activo,
			    $this->parada->idParada,
			    date("Y-m-d H:i:s"));
			 $bit= $bit . "Crea Estacion " . $this->nombre;
			}
		else {
			$sql=sprintf("update estacion set 
			nombre ='%s',
			activo=%d,
			idparada=%d 
			where idEstacion=%d",
			$this->nombre,
			$this->activo,
			$this->parada->idParada,
			$this->idEstacion );
			$bit.= "Actualiza Estacion " . $this->idEstacion . " " . $this->nombre; 
			}
		if($link==null){
			$link =new tiqmysql();
            $link->bdAbreTran();
		   $cerrartran=true;
		}
		else 
		   $cerrartran=false;
		$res = $link->bdEjecutar($sql);
		if($nueva){
		  $this->idEstacion=$link->bdUltimoId();
		}
		$this->bitacora->bitacorizar($bit);
      if($cerrartran)
         $link->bdCierraTran();
		}
		catch(SimError $ex){
			throw $ex;
	   }
	  return $this->idEstacion;
	}
    
   //Carga los datos de la Estacion 
	function Cargar(){
        $sql = sprintf("select * from estacion where idEstacion=%d",$this->idEstacion);
        $link = new tiqmysql();
        $res = $link->bdEjecutar($sql);
        if($link->bdCantLineas($res)>0){
           $linea = mysqli_fetch_array($res);    
           $this->nombre = $linea["nombre"];
           $this->activo = $linea["activo"];
           $this->parada->idParada = $linea["idparada"];
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
      	        $this->nombre = $parametros["nombre"];
 	              $this->activo = $parametros["activo"];
 	              $this->parada->idParada = $parametros["parada"];
 	              $retorno = true;
       	        break;
       	case 2:
       	        $this->idEstacion = $parametros["idestacion"];
      	        $this->nombre = $parametros["nombre"];
 	              $this->activo = $parametros["activo"];
 	              $this->parada->idParada = $parametros["parada"];
 	              $retorno = true;
       	        break;
       	case 3:
       	        $this->idEstacion = $parametros["idestacion"];
 	              $retorno = $this->Cargar();
       	        break;
       }
       return $retorno;
   }
   
   //Valida los campos  
	function Validar() {
		$errores="";
		if(trim($this->nombre)=="")
		  $errores.="Detalle de la Estacion en blanco";
        if(!$this->parada->Cargar())
          $errores.="La parada no existe";
          
		if($errores!="")		
			throw new SimError($errores);
	}
//Borra la Estacion
    function Borrar(){
        
        $link = new tiqmysql();
        $sql=sprintf("delete from viajexesta where idEstacion=%d",$this->idEstacion);
        $res = $link->bdEjecutar($sql);
        $sql=sprintf("delete from estacion where idEstacion=%d",$this->idEstacion);
        $res = $link->bdEjecutar($sql);
        $this->bitacora->Bitacorizar("Elimina Estacion " . $this->nombre);
        return true;
     }       
// Viajes de esta estaci'on de un d'ia y una ruta determinada
  function Viajes($pidRuta,$pfecha,$ptipo){
      $otrosfiltros="";
   //   if($ptipo==2)
   //    $otrosfiltros=" and (v.idtipoviaje!= 2 or v.idruta != 2)  ";
      $sql=sprintf("select v.idviaje,c.nombre as chofer,b.placa,r.nombre as cobrador,x.hora
            ,tp.destipoviaje as  tipo , v.extra 
            from viaje v inner join Bus b
            on v.idbus=b.idbus
            inner join chofer c
            on v.idchofer= c.idchofer
            inner join cobrador r
            on v.idcobrador=r.idcobrador
            inner join viajexesta x
            on v.idviaje=x.idviaje
            inner join tipoviaje tp on tp.idtipoviaje=v.idtipoviaje
            where x.idestacion=%d
            and v.fecha='%s' ".$otrosfiltros. "
            and v.idruta=%d order by x.hora ",$this->idEstacion,$pfecha,$pidRuta);
       $link= new tiqmysql();
       $res = $link->bdEjecutar($sql);
       return $res;

  }     
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
       return $link->bdEjecutar("select idestacion,nombre,idparada,activo from estacion " . $tira . " order by nombre " . $limite);
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
       return $link->bdCantLineas($link->bdEjecutar("select idestacion as cuenta from estacion " . $tira));
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
       return $link->bdEjecutar("select idestacion,nombre from estacion where activo ".$tira." order by nombre ");
    } 
//Hereda el objeto bitac'acora    
	function HeredaBitacora($pbit){
		
        $pbit->nivel++;		
		$this->bitacora=$pbit;
		$this->bitacora->obPadre=$this;
        
	}

//Pasa el objeto a un string 
   function ToString(){
     $cad = "ID " . $this->idEstacion  . "/n" .
     "Nombre: " . $this->nombre . "/n" .
     "Activo: " . $this->activo . "/n" .
     "Parada predeterminada: " . $this->parada->nombre;
     return $cad;
    }    
}
 
 
?>
