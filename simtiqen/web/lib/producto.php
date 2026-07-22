<?php

 class Producto{
 	var $idProducto;
 	var $nombre;
 	var $tipo;
 	var $activo;
    var $precio;
 	var $bitacora;
    
//Contructor
 	function __construct(){
 	 $this->Limpiar();
 	}
    
//Limpia las propiedades    
 	function Limpiar() {
 		$this->idProducto=0;
 		$this->nombre="";
 		$this->tipo=1;
 		$this->activo=1;
        $this->precio=0;

	}
    
//Guarda los datos , recibe como paremetro la transacci'on si existe 
	function Guardar($link){

		$this->Validar();
		try {
		$bit='';
		$nueva= ($this->idProducto==0);
		if($this->idProducto==0) {
			$sql =sprintf("insert into producto (nombre,activo,tipo,precio,fechadi)
			  value ('%s',%d,%d,%d,'%s')",
			    $this->nombre,
			    $this->activo,
			    $this->tipo,
                $this->precio,
			    date("Y-m-d H:i:s"));
			 $bit= $bit . "Crea Producto";
			}
		else {
			$sql=sprintf("update producto set 
			nombre ='%s',
			tipo=%d,
			activo=%d,
            precio=%d
			where idproducto=%d",
			$this->nombre,
			$this->tipo,
			$this->activo,
            $this->precio,
			$this->idProducto );
			$bit.= "Actualiza Producto " . $this->idProducto . " " . $this->nombre; 
			}
		if($link==null){

			$link =new tiqmysql();

		   $cerrartran=true;
		}
		else 
		  $cerrartran=false;

		$link->bdAbreTran();

		$res = $link->bdEjecutar($sql);

		if($nueva)
		  $this->idProducto=$link->bdUltimoId();
		$this->bitacora->bitacorizar($bit);
      if($cerrartran)
         $link->bdCierraTran();
		}
		catch(SimError $ex){
			throw $ex;
	   }
	  return $this->idProducto;
	}
    
   //Carga los datos de la Producto 
	function Cargar(){
        $sql = sprintf("select * from producto where idproducto=%d",$this->idProducto);
        $link = new tiqmysql();
        $res = $link->bdEjecutar($sql);
        if($link->bdCantLineas($res)>0){
           $linea = mysqli_fetch_array($res);    
           $this->nombre = $linea["nombre"];
           $this->tipo = $linea["tipo"];
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
 	              $this->tipo = $parametros["tipo"];
                  $this->precio=$parametros["precio"] ;
 	              $retorno = true;
       	        break;
       	case 2:
       	        $this->idProducto = $parametros["idProducto"];
      	        $this->nombre = $parametros["nombre"];
 	              $this->tipo = $parametros["tipo"];
 	              $this->activo = $parametros["activo"];
                  $this->precio=$parametros["precio"] ;                  
 	              $retorno = true;
       	        break;
       	case 3:
       	        $this->idProducto = $parametros["idProducto"];
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
        $sql=sprintf("delete from producto where idproducto=%d",$this->idProducto);
        $link = new tiqmysql();
        $res = $link->bdEjecutar($sql);
        $this->bitacora->Bitacorizar("Elimina Producto " . $this->nombre);
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
       return $link->bdEjecutar("select idProducto,nombre,activo,tipo,precio from producto ".$tira." order by nombre ". $limite);
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
       return $link->bdEjecutar("select idProducto,nombre,tipo,precio,activo from producto where activo ".$tira." order by nombre ");
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
       return $link->bdCantLineas($link->bdEjecutar("select idProducto as cuenta from producto " . $tira));
    }

     
//Hereda el objeto bitac'acora    
	function HeredaBitacora($pbit){
		$pbit->nivel++;		
		$this->bitacora=$pbit;
		$this->bitacora->obPadre=$this;
	}
//Pasa el objeto a un string 
   function ToString(){
     $cad = "ID " . $this->idProducto  . "/n" .
     "Nombre: " . $this->nombre . "/n" .
     "Activo: " . $this->activo . "/n" ;

     return $cad;
    }    
}
?>