<?php
ini_set('error_reporting', E_ALL);

 class Ruta{
 	var $idRuta;
 	var $nombre;
 	var $activo;
    var $estasale;
    var $estallega;
    var $estaciones;
	var $abreviatura;
	var $montosocio;
  var $color;
 	var $listas=array();
 	var $bitacora;
  var $empresa;
    
//Contructor
 	function __construct(){
 	   $this->Limpiar();
 	}
    
//Limpia las propiedades    
 	function Limpiar() {
		$this->idRuta=0;
 		$this->nombre="";
 		$this->activo=1;
		$this->abreviatura = "";
		$this->montosocio = 0;
		$this->estasale =new Estacion();
		$this->estallega = new Estacion();
    $this->empresa = new Empresa();
		$this->estaciones=array();
    $this->color="";
	}
    
   //Guarda los datos , recibe como paremetro la transacci'on si existe 
	function Guardar($link){
    
		$this->Validar();
		//try {
		$bit='';
		$nueva= ($this->idRuta==0);
		if($this->idRuta==0) {
			$sql =sprintf("insert into ruta (nombre,activo,fechadi,estasale,estallega,abreviatura,montosocio,idempresa)
				value ('%s',%d,'%s',%d,%d,'%s',%d, %d)",
			    $this->nombre,
			    $this->activo,
			    date("Y-m-d H:i:s"),
                $this->estasale->idEstacion,
                $this->estallega->idEstacion,
                $this->abreviatura,
                $this->montosocio,
                $this->empresa->idEmpresa);
			$bit= $bit . "Crea ruta";
		}
		else {
			$sql=sprintf("update ruta set 
			nombre ='%s',
			activo=%d,
            estasale=%d,
            estallega=%d,
            abreviatura='%s',
            montosocio=%d,
            idempresa=%d
			where idruta=%d",
			$this->nombre,
			$this->activo,
            $this->estasale->idEstacion,
            $this->estallega->idEstacion,
            $this->abreviatura,
            $this->montosocio,
            $this->empresa->idEmpresa,
			$this->idRuta );
			$bit.= "Actualiza ruta " . $this->idRuta . " " . $this->nombre; 
		}
		if($link==null){
			$link =new tiqmysql();
			$cerrartran=true;
		}
		else 
			$cerrartran=false;
		$link->bdAbreTran();  
		$res = $link->bdEjecutar($sql);
		if($nueva){
			$this->idRuta=$link->bdUltimoId();
		}
		$this->GuardaEstaciones($link);
		$this->GuardaListas($link);
		$this->bitacora->bitacorizar($bit);
		if($cerrartran)
			$link->bdCierraTran();	
		//}
		//catch(Exception $ex){
		//	throw $ex;
		//  }
		return $this->idRuta;
	}
    
     function GuardaListas($link){
        $modificados="";
        for($x=0;$x<count($this->listas);$x++)
        {
            $sql=sprintf("select idlista from lista 
            where idruta=%d 
            and idparada1=%d
            and idparada2=%d
            and idproducto=%d"
            ,$this->idRuta
            ,$this->listas[$x]->parada1->idParada
            ,$this->listas[$x]->parada2->idParada
            ,$this->listas[$x]->producto->idProducto);
            
            $res=$link->bdEjecutar($sql);
            $id=$link->PrimerValor($res);
          if($this->listas[$x]->precio!=-1){
            if($link->bdCantLineas($res)==0){
               $sql=sprintf("insert into lista (idruta,idparada1,idparada2,idproducto,precio,activo,fechadi,fechaapli) 
                values (%d,%d,%d,%d,%d,%d,'%s','1900-01-01')"
                ,$this->idRuta
                ,$this->listas[$x]->parada1->idParada
                ,$this->listas[$x]->parada2->idParada
                ,$this->listas[$x]->producto->idProducto
                ,$this->listas[$x]->precio
                ,$this->listas[$x]->activo
                ,date("Y-m-d H:i:s"));
                $link->bdEjecutar($sql);
                $id =$link->bdUltimoId();
              }
            else{
                $sql=sprintf("update lista set precio=%d , activo=%d, preciofuturo=%d, fechaapli='%s' 
                  where idlista=%d"
                ,$this->listas[$x]->precio
                ,$this->listas[$x]->activo
                ,$this->listas[$x]->preciofuturo
                ,$this->listas[$x]->fechaapli
                ,$id);
                 $link->bdEjecutar($sql);
              }
             $modificados.= $id . ",";
           }
           else{
            if($link->bdCantLineas($res)>0) 
             $link->bdEjecutar("delete from lista where idlista=".$id);
           }
        }
        /*
        if(strlen($modificados)>1){
            $modificados=substr($modificados,0,strlen($modificados)-1);
            $link->bdEjecutar("delete from lista where idruta= ".$this->idRuta .
             " and not idlista in (" . $modificados . ")");  
            }
        else
            $link->bdEjecutar("delete from lista where idruta=" . $this->idRuta);
   */
    }  
    
    function GuardaEstaciones($link){
        $modificados="";
        for($x=0;$x<count($this->estaciones);$x++)
        {
            $sql=sprintf("select idrutaxesta from rutaxesta where idruta=%d and idestacion=%d",
            $this->idRuta,$this->estaciones[$x]->idEstacion);
            $res=$link->bdEjecutar($sql);
            $id=$link->PrimerValor($res);
            if($link->bdCantLineas($res)==0)
               $sql=sprintf("insert into rutaxesta (idruta,idestacion,hora,activo,fechadi) 
                values (%d,%d,'%s',%d,'%s')"
                ,$this->idRuta
                ,$this->estaciones[$x]->idEstacion
                ,$this->estaciones[$x]->hora
                ,$this->estaciones[$x]->activo
                ,date("Y-m-d H:i:s",time()));
            else
                $sql=sprintf("update rutaxesta set hora='%s' , activo=%d where idrutaxesta=%d"
                ,$this->estaciones[$x]->hora
                ,$this->estaciones[$x]->activo
                ,$id);
             
             $link->bdEjecutar($sql);
             $modificados.= $id . ",";
        }
        if(strlen($modificados)>1){
            $modificados=substr($modificados,0,strlen($modificados)-1);
            $link->bdEjecutar("delete from rutaxesta where idruta= ".$this->idRuta .
             " and not idrutaxesta in (" . $modificados . ")");  
            }
        else
            $link->bdEjecutar("delete from rutaxesta where idruta=" . $this->idRuta);
    }
    
    function NuevaLista($idparada1,$idparada2,$idproducto,$precio){
        $nueva = new Lista();
        $nueva->parada1->idParada= $idparada1;
        $nueva->parada2->idParada= $idparada2;
        $nueva->producto->idProducto=$idproducto;
        $nueva->precio = $precio;
        array_push($this->listas,$nueva);
        return $nueva;

    }
    function NuevaListaFutura($pfechaapli,$idparada1,$idparada2,$idproducto,$precio){

      
      for($x=0;$x<count($this->listas);$x++){
        
        if($this->listas[$x]->parada1->idParada==$idparada1
          && $this->listas[$x]->parada2->idParada==$idparada2
          && $this->listas[$x]->producto->idProducto==$idproducto
          ){
        
          $this->listas[$x]->preciofuturo=$precio;
          $this->listas[$x]->fechaapli = $pfechaapli;
          
        }
      }
      

    }

    
    function NuevaEstacion($idestacion,$hora,$activo){
        $nueva = new RutaxEsta();
        $nueva->idEstacion = $idestacion;
        $nueva->hora = $hora;
        $nueva->activo = $activo;
        array_push($this->estaciones,$nueva);
    }
    
   //Carga los datos de la ruta 
	function Cargar(){
        $sql = sprintf("select * from ruta where idruta=%d",$this->idRuta);
        $link = new tiqmysql();
        $res = $link->bdEjecutar($sql);
        if($link->bdCantLineas($res)>0){
           $linea = mysqli_fetch_array($res);    
           $this->nombre = $linea["nombre"];
           $this->activo = $linea["activo"];
           $this->estasale->idEstacion = $linea["estasale"];
           $this->estallega->idEstacion =$linea["estallega"];
           $this->empresa->idEmpresa = $linea["idempresa"];
           $this->abreviatura =$linea["abreviatura"];
           $this->montosocio =$linea["montosocio"];
           $this->color = $linea["color"];
           $this->CargaEstaciones();
           $this->CargaLista();
           return true;
        }
        else
          return false;
     }
     
     //Carga las estaciones
     function CargaEstaciones(){
         $sql=sprintf("select * from rutaxesta where idRuta=%d",$this->idRuta);
        $link = new tiqmysql();
        $res = $link->bdEjecutar($sql);
        $this->estaciones=array();
        if($link->bdCantLineas($res)>0){
            while($linea=mysqli_fetch_array($res))    
                $this->NuevaEstacion($linea["idestacion"],$linea["hora"],$linea["activo"]);
        }
    }


     //Carga las estaciones
     function CargaLista(){
         $sql=sprintf("select * from lista where idRuta=%d",$this->idRuta);
        $link = new tiqmysql();
        $res = $link->bdEjecutar($sql);
        $this->estaciones=array();
        if($link->bdCantLineas($res)>0){
            while($linea=mysqli_fetch_array($res))  {  
                $nueva = $this->NuevaLista($linea["idparada1"]
                  ,$linea["idparada2"]
                  ,$linea["idproducto"]
                  ,$linea["precio"]);
                $nueva->idLista=$linea["idlista"];
                $nueva->preciofuturo=$linea["preciofuturo"];
                $nueva->fechaapli=$linea["fechaapli"];
            }

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
 	            $this->estasale->idEstacion = $parametros["estasale"];
 	            $this->estallega->idEstacion = $parametros["estallega"];
 	            $this->abreviatura = $parametros["abreviatura"];
 	            $this->montosocio = $parametros["montosocio"];
              $this->empresa->idEmpresa = $parametros["idempresa"];
 	            $retorno = true;
       	        break;
       	case 2:
       	        $this->idRuta = $parametros["idruta"];
      	        $this->nombre = $parametros["nombre"];
 	            $this->activo = $parametros["activo"];
 	            $this->estasale->idEstacion = $parametros["estasale"];
 	            $this->estallega->idEstacion = $parametros["estallega"];
 	            $this->abreviatura = $parametros["abreviatura"];
 	            $this->montosocio = $parametros["montosocio"];
              $this->empresa->idEmpresa = $parametros["idempresa"];
				$retorno = true;
       	        break;
       	case 3:
       	        $this->idRuta = $parametros["idruta"];
 	              $retorno = $this->Cargar();
       	        break;
       }
       return $retorno;
   }

    
    function PrecioProducto($pidparada1,$pidparada2,$pidproducto){
        $link= new tiqmysql();
        $sql=sprintf("select precio from lista
            where idruta=%d
            and idparada1=%d
            and idparada2=%d
            and idproducto=%d",
            $this->idRuta,$pidparada1,$pidparada2,$pidproducto);
            
        $res= $link->PrimerValorD($sql);
        if($res==null)
            return 0;
        else
          return $res;
    }


   //Valida los campos  
	function Validar() {
		$errores="";
		if(trim($this->nombre)=="")
		  $errores.="Detalle de la ruta en blanco";
        foreach($this->estaciones as $nodo)
            if(!$nodo->Cargar())
                $errores.="Estación no existe";
          
		if($errores!="")		
			throw new SimError($errores);
            
	}
	
    //Borra la ruta
    function Borrar(){
        $sql=sprintf("delete from ruta where idruta=%d",$this->idRuta);
        $link = new tiqmysql();
        $res = $link->bdEjecutar($sql);
        $this->bitacora->Bitacorizar("Elimina ruta " . $this->nombre);
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
       return $link->bdEjecutar("SELECT ruta.*,a.nombre as nomestasale,b.nombre as nomestallega,c.nombre as empresa FROM ruta left join estacion a on ruta.estasale = a.idestacion  left join estacion b on ruta.estallega = b.idestacion left join empresa c on ruta.idempresa = c.idempresa " . $tira . " order by nombre ". $limite);
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
       return $link->bdEjecutar("SELECT ruta.*,a.nombre as nomestasale,
                b.nombre as nomestallega,a.idparada as idparada1,c.nombre as empresa 
               FROM ruta left join estacion a 
                   on ruta.estasale = a.idestacion  
               left join estacion b 
                   on ruta.estallega = b.idestacion 
               left join empresa c 
                   on ruta.idempresa = c.idempresa 
               where ruta.activo=1 ".$tira." order by nombre ");
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
       return $link->bdCantLineas($link->bdEjecutar("select idruta from ruta " . $tira));
    }

  function ListaPrecios($pidproducto){
         $sql=sprintf("select l.idlista,l.idparada1,l.idparada2,l.precio,
          p1.nombre as parada1 , p2.nombre as parada2,l.preciofuturo,l.fechaapli
          from lista l 
          inner join parada p1 on p1.idParada=l.idparada1
          inner join parada p2 on p2.idParada=l.idparada2
           where l.idRuta=%d and l.idproducto=%d" ,$this->idRuta,$pidproducto);
        $link = new tiqmysql();
        $res = $link->bdEjecutar($sql);
        return $res;
    } 
  function ParadasxRuta($ptipo){
       if($ptipo==1)
          $sql=sprintf("select  distinct l.idparada1 as idparada,p.nombre as parada
          from lista l 
          inner join parada p on p.idParada=l.idparada1
          where l.idRuta=%d order by 2" ,$this->idRuta);
        else
          $sql=sprintf("select  distinct l.idparada2 as idparada,p.nombre as parada
          from lista l 
          inner join parada p on p.idParada=l.idparada2
          where l.idRuta=%d order by 2" ,$this->idRuta);          
        $link = new tiqmysql();
        $res = $link->bdEjecutar($sql);
        return $res;
  }



//Hereda el objeto bitac'acora    
	function HeredaBitacora($pbit){
		$pbit->nivel++;		
		$this->bitacora=$pbit;
		$this->bitacora->obPadre=$this;
	}
//Pasa el objeto a un string 
   function ToString(){
     $cad = "ID " . $this->idRuta  . "/n" .
     "Nombre: " . $this->nombre . "/n" .
     "Activo: " . $this->activo . "/n" ;
     foreach($this->estaciones as $nodo)
       $cad .= $nodo->ToString();
    foreach($this->listas as $nodo)
       $cad .= $nodo->ToString();
     return $cad;
    }    
	
}
 
 
?>
