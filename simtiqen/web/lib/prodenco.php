<?php

 class Prodenco{
 	var $idProdenco;
 	var $nombre;
 	var $activo;
  var $precio;
 	var $bitacora;
    
//Contructor
 	function __construct(){
 	 $this->Limpiar();
 	}
    
//Limpia las propiedades    
 	function Limpiar() {
 		$this->idProdenco=0;
 		$this->nombre="";
 		$this->activo=1;
    $this->precio=0;

	}
    
//Guarda los datos , recibe como paremetro la transacci'on si existe 
	function Guardar($link){

		$this->Validar();
		try {
  		$bit='';
	   	$nueva= ($this->idProdenco==0);
		  if($this->idProdenco==0) {
			 $sql =sprintf("insert into prodenco (nombre,activo,precio,fechadi)
			  value ('%s',%d,%d,'%s')",
			    $this->nombre,
			    $this->activo,
          $this->precio,
			    date("Y-m-d H:i:s"));
			 $bit= $bit . "Crea Producto de Encomienda";
			}
		  else {
			 $sql=sprintf("update prodenco set 
			 nombre ='%s',
			 activo=%d,
       precio=%d
			 where idprodenco=%d",
			 $this->nombre,
       $this->activo,
       $this->precio,
			 $this->idProdenco );

			 $bit.= "Actualiza Producto de encomienda " . $this->idProdenco . " " . $this->nombre; 
			}
      echo $sql;
		if($link==null){

			$link =new tiqmysql();

		   $cerrartran=true;
		}
		else 
		  $cerrartran=false;

		$link->bdAbreTran();

		$res = $link->bdEjecutar($sql);

		if($nueva)
		  $this->idProdenco=$link->bdUltimoId();
		$this->bitacora->bitacorizar($bit);
      if($cerrartran)
         $link->bdCierraTran();
		}
		catch(SimError $ex){
			throw $ex;
	   }
	  return $this->idProdenco;
	}
    
   //Carga los datos de la Producto 
	function Cargar(){
        $sql = sprintf("select * from prodenco where idprodenco=%d",$this->idProdenco);
        $link = new tiqmysql();
        $res = $link->bdEjecutar($sql);
        if($link->bdCantLineas($res)>0){
           $linea = mysqli_fetch_array($res);    
           $this->nombre = $linea["nombre"];
           $this->activo = $linea["activo"];
           $this->precio = $linea["precio"];
           return true;
        }
        else
          return false;
     }
     
       function CargarArreglo($parametros,$opcion){
       $retorno = null;
       switch($opcion) {
       	case 1:
      	        $this->nombre = $parametros["nombre"];
 	              $this->activo = $parametros["activo"];
                $this->precio=$parametros["precio"] ;
 	              $retorno = true;
       	        break;
       	case 2:
       	        $this->idProdenco = $parametros["idProdenco"];
      	        $this->nombre = $parametros["nombre"];
 	              $this->activo = $parametros["activo"];
                $this->precio=$parametros["precio"] ;                  
 	              $retorno = true;
       	        break;
       	case 3:
       	        $this->idProdenco = $parametros["idProdenco"];
 	              $retorno = $this->Cargar();
       	        break;
       }
       return $retorno;
   }

     
   //Valida los campos  
	function Validar() {
		$errores="";
		if(trim($this->nombre)=="")
		  $errores.="Detalle de la Producto en blanco";
          
		if($errores!="")		
			throw new SimError($errores);
	}
//Borra la Producto
    function Borrar(){
        $sql=sprintf("delete from prodenco where idprodenco=%d",$this->idProdenco);
        $link = new tiqmysql();
        $res = $link->bdEjecutar($sql);
        $this->bitacora->Bitacorizar("Elimina Producto de encomienda " . $this->nombre);
        return true;
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
       return $link->bdEjecutar("select idProdenco,nombre,activo,precio from prodenco ".$tira." order by nombre ". $limite);
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
       return $link->bdEjecutar("select idProdenco,nombre,precio,activo from prodenco where activo ".$tira." order by nombre ");
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
       return $link->bdCantLineas($link->bdEjecutar("select idProdenco as cuenta from prodenco " . $tira));
    }

     
//Hereda el objeto bitac'acora    
	function HeredaBitacora($pbit){
		$pbit->nivel++;		
		$this->bitacora=$pbit;
		$this->bitacora->obPadre=$this;
	}
//Pasa el objeto a un string 
   function ToString(){
     $cad = "ID " . $this->idProdenco  . "/n" .
     "Nombre: " . $this->nombre . "/n" .
     "Activo: " . $this->activo . "/n" ;

     return $cad;
    }    
}
?>