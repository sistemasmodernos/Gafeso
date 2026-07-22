<?php

class Tiquete{
   var $idTiquete;
   var $fecha;
   var $asiento;
   var $monto;
   var $viaje;
   var $estacion;
   var $producto;
   var $cierre;
   var $estado;
   var $parada1;
   var $parada2;
   var $usuario;
   var $cliente;
   var $factura;
   var $impresora;
   var $bitacora;
   var $serial;
   var $anombre;
   var $cedula;
   var $numtar;
   var $comentario;
   var $impreso;
    //Contructor
    
 	function __construct(){
 	   $this->Limpiar();
 	}
    
//Limpia las propiedades    
 	function Limpiar() {
 		$this->idTiquete=0;
        $this->fecha=time();
        $this->asiento=0;
        $this->viaje = new Viaje();
        $this->estacion = new Estacion();
        $this->producto = new Producto();
        $this->cierre = new Cierred();
        $this->estado=0;
        $this->parada1= new Parada();
        $this->parada2= new Parada();
        $this->usuario = new Usuario();
        $this->cliente = new Cliente();
        $this->factura="";
        $this->impresora = new Impresora();
        $this->monto=0;
        $this->serial='';
        $this->anombre='';
        $this->cedula='';
        $this->numtar='';
        $this->comentario='';
        $this->impreso=0;
        

	}
    
    function Apartar($link2){
        if($link2==null){
			$link2 =new tiqmysql();
            $link2->bdAbreTran();
            
		  $cerrartran=true;
		}
		else 
		  $cerrartran=false;

        if($this->idTiquete!=0){
            $link2->bdRevierteTran();
          throw new SimError("No se puede apartar un tiquete ya creado");
          }
        $this->factura="APARTA";
        
        $res =$this->Guardar($link2);

        if($cerrartran)
          $link2->bdCierraTran();
        return $res;
        
    }
    
    
    function Vender($link){
        if($link==null){
			$link =new tiqmysql();
            $link->bdAbreTran();
		    $cerrartran=true;
		}
		else 
		  $cerrartran=false;
  
            $siguiente= $this->impresora->SiguienteConsecutivo( $link);
           $this->factura=str_pad($siguiente,6,"0",STR_PAD_LEFT);

        $res = $this->Guardar($link);
        if($cerrartran)
            $link->bdCierraTran();
        $this->AvisaVta();
        return $res;
}
function AvisaVta(){
    $link =new tiqmysql();
    $sql=sprintf("select v.idviaje,v.avisado,count(*) as vendido
      from viaje v inner join tiquete t on t.idviaje=v.idviaje
      where v.idviaje=%d 
      and t.estado=0 
      and t.factura!='APARTA'
      and t.asiento>0 
      group by v.idviaje ",$this->viaje->idViaje);
    $res = $link->bdEjecutar($sql);
    $linea=mysqli_fetch_array($res);
    if(($linea["avisado"]==0) && ($linea["vendido"]>=40))
    {
              $this->viaje->Cargar();
              $this->viaje->bus->Cargar();
              $this->viaje->ruta->Cargar();
            $para      = "tommy@rialze.com";
            $titulo = "Viaje ha llegado a los 40 campos vendidos ";
            $mensaje = "El viaje del ". fechatoNormal($this->viaje->fecha)." ".$this->viaje->horaini.
            " ha alcanzado los 40 lugares vendidos, ".
            " Ruta: ".$this->viaje->ruta->nombre.
            ", Placa: ".$this->viaje->bus->placa;
            $cabeceras = 'From: info@transportesjacoruta655.com' . "\r\n" .
            'Reply-To: info@transportesjacoruta655.com' . "\r\n" .
            'X-Mailer: PHP/' . phpversion(). "\r\n";
            $cabeceras .= 'MIME-Version: 1.0' . "\r\n";
            $cabeceras .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
//            if(mail($para, $titulo, $mensaje, $cabeceras)){
//                $link->bdEjecutar("update viaje set avisado=1,fecavisado=now() where idviaje=".$this->viaje->idViaje);
//                }
     }
}
    
  function Cambiar($link,$nuevoapartado){
        if($link==null){
			$link =new tiqmysql();
            $link->bdAbreTran();
		    $cerrartran=true;
		}
		else 
		  $cerrartran=false;
         $nuevoapartado->factura=$this->factura ;
         $nuevoapartado->impresora->idimpresora=$this->impresora->idimpresora;
         $nuevoapartado->cierre->idcierre = $this->cierre->idcierre;
         $nuevoapartado->monto = $this->monto;
         $this->factura='C'.substr($this->factura,1,strlen($this->factura)-1);
         $this->estado=1;
        $res = $this->Guardar($link);
        $nuevoapartado->Guardar($link);
        $this->bitacora->Bitacorizar("Se cambia de horario ".$this->factura );        
        if($cerrartran)
            $link->bdCierraTran();
        return $res;
    }
    
      
    
    
    
//Guarda los datos , recibe como paremetro la transacci'on si existe 
	function Guardar($link){
  
        	$this->Validar();
        if($link==null)
			$link =new tiqmysql();            
		//try {
		$bit='';
        if($this->asiento!=-1){
           $sql=sprintf("Select idTiquete 
                from  tiquete 
                where asiento=%d 
                    and idTiquete!=%d
                    and idViaje=%d and estado=0",$this->asiento,$this->idTiquete,$this->viaje->idViaje);

            $xx = $link->PrimerValorD($sql);
       
         if($xx!=null){
               $err=new SimError("El asiento ya fue vendido o apartado ID: " . $xx);
               $err->tipo=1;
               $err->detalletag= $xx;
               throw $err;
               return;
             }
        }

        if($this->idTiquete==0) {
            $str = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";
            $this->serial = "";
            for($i=0;$i<6;$i++) {
               $this->serial .= substr($str,rand(0,62),1);
             }
            
			$sql =sprintf("insert into tiquete (fecha,asiento,idviaje,idestacion,
            idproducto,idcierre,estado,idparada1,idparada2,idusuario,idcliente,
            factura,idimpresora,fechadi,monto,serial,anombre,cedula,numtar,comentario)
			  value ('%s',%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,'%s',%d,'%s',%d,'%s',
              '%s','%s','%s','%s')",
			    $this->fecha,
			    $this->asiento,
                $this->viaje->idViaje,
                $this->estacion->idEstacion,
                $this->producto->idProducto,
                $this->cierre->idCierre,
                $this->estado,
                $this->parada1->idParada,
                $this->parada2->idParada,
                $this->usuario->idUsuario,
                $this->cliente->idCliente,
                $this->factura,
                $this->impresora->idImpresora,
			    date("Y-m-d H:i:s"),
                $this->monto,
                $this->serial,
                $this->anombre,
                $this->cedula,
                $this->numtar,
                $this->comentario
                );
                
			 $bit= $bit . "Crea Tiquete " ;
			}
		else {
			$sql=sprintf("update tiquete set 
			fecha ='%s',
            asiento=%d,
            idviaje=%d,
            idestacion=%d,
            idproducto=%d,
            idcierre=%d,
            estado=%d,
            idparada1=%d,
            idparada2=%d,
            idusuario=%d,
            idcliente=%d,
            factura='%s',
            idimpresora=%d,
            monto=%d,
            comentario='%s'
			where idTiquete=%d",
			$this->fecha,
			$this->asiento,
            $this->viaje->idViaje,
            $this->estacion->idEstacion,
            $this->producto->idProducto,
            $this->cierre->idCierre,
            $this->estado,
            $this->parada1->idParada,
            $this->parada2->idParada,
            $this->usuario->idUsuario,
            $this->cliente->idCliente,
            $this->factura,
            $this->impresora->idImpresora,
            $this->monto,
            $this->comentario,
			$this->idTiquete );
			$bit.= "Actualiza Tiquete " . $this->idTiquete ; 
			}
  
        $nueva= ($this->idTiquete==0);
		$res = $link->bdEjecutar($sql);
		if($nueva){
		  $this->idTiquete=$link->bdUltimoId();
		}
		$this->bitacora->Bitacorizar($bit);
	
		//}
		//catch(Exception $ex){
		//	throw $ex;
	 //  }
	  return $this->idTiquete;
    }
    
    function idxFactura($pimpresora,$pfactura){
        $link = new tiqmysql();
        $sql =sprintf("select idtiquete from tiquete where idimpresora=%d and factura='%s'",$pimpresora,$pfactura);
        return $link->PrimerValorD($sql);
        
    }
    //Carga los datos de la ruta 
	function Cargar(){
        $sql = sprintf("select idtiquete,fecha
            ,asiento,idviaje,idestacion,idproducto,idcierre,estado
            ,idparada1,idparada2,idusuario,idcliente,factura,idimpresora,monto,serial
            ,anombre,cedula,numtar,comentario,impreso
            from tiquete 
            where idtiquete=%d",$this->idTiquete);
        $link = new tiqmysql();
        $res = $link->bdEjecutar($sql);
        if($link->bdCantLineas($res)>0){
           $linea = mysqli_fetch_array($res);    
			$this->fecha=$linea["fecha"];
			$this->asiento=$linea["asiento"];
            $this->viaje->idViaje=$linea["idviaje"];
            $this->estacion->idEstacion=$linea["idestacion"];
            $this->producto->idProducto=$linea["idproducto"];
            $this->cierre->idCierre=$linea["idcierre"];
            $this->estado=$linea["estado"];
            $this->parada1->idParada=$linea["idparada1"];
            $this->parada2->idParada=$linea["idparada2"];
            $this->usuario->idUsuario=$linea["idusuario"];
            $this->cliente->idCliente=$linea["idcliente"];
            $this->factura=$linea["factura"];
            $this->monto=$linea["monto"];
            $this->impresora->idImpresora=$linea["idimpresora"];
            $this->serial = $linea["serial"];             
            $this->anombre = $linea["anombre"] ;
            $this->numtar = $linea["numtar"] ; 
            $this->comentario= $linea["comentario"];
            $this->impreso=$linea["impreso"];
         return true;
        }
        else
          return false;
     }
    function Clonar(){
        $clon= new Tiquete();
			$clon->fecha =  $this->fecha;
			$clon->asiento=    $this->asiento;
            $clon->viaje->idViaje=   $this->viaje->idViaje;
            $clon->estacion->idEstacion=    $this->estacion->idEstacion;
            $clon->producto->idProducto=   $this->producto->idProducto;
            $clon->cierre->idCierre=   $this->cierre->idCierre;
            $clon->estado=    $this->estado;
            $clon->parada1->idParada=   $this->parada1->idParada;
            $clon->parada2->idParada=   $this->parada2->idParada;
            $clon->usuario->idUsuario=    $this->usuario->idUsuario;
            $clon->cliente->idCliente=  $this->cliente->idCliente;
            $clon->factura=   $this->factura;
            $clon->monto=   $this->monto;
            $clon->impresora->idImpresora=   $this->impresora->idImpresora;
            $clon->serial=   $this->serial ;             
            $clon->anombre=   $this->anombre ;
            $clon->numtar=   $this->numtar ; 
            $clon->comentario = $this->comentario;
        return $clon;
    }
    
       //Valida los campos  
	function Validar() {
		$errores="";
		if(trim($this->fecha)=="")
		  $errores.="La fecha no puede quedar en blanco \n";
        if($this->asiento==0)
          $errores.="No ha definido el número de asiento \n";
        if(!$this->viaje->Cargar())
          $errores.="El viaje que escogió no existe";
        if(!$this->estacion->Cargar())
          $errores.="La estación que escogió no existe";
        if(!$this->producto->Cargar())
          $errores.="El producto que escogió no existe";
        if(!$this->cierre->Cargar())
          $errores.="El cierre que escogió no existe";
        if(!$this->parada1->Cargar())
          $errores.="La parada de salida que escogió no existe";
        if(!$this->parada2->Cargar())
          $errores.="La parada de llegada que escogió no existe";
        if(!$this->usuario->Cargar())
          $errores.="El viaje que escogió no existe";
        if(!$this->impresora->Cargar())
          $errores.="La impresora que escogió no existe";
          
         if(strlen($this->factura)==0)
          $errores.="No puede dejar el número de factura en blanco";

        
		if($errores!="")		
			throw new SimError($errores);
            
	}
//Borra la ruta
    function Borrar(){
        if($this->cierre->idCierre>0)
          throw new SimError("El tiquete ya pertenece a un cierre no se puede eliminar");
          
        $sql=sprintf("delete from tiquete where idtiquete=%d",$this->idTiquete);
        $link = new tiqmysql();
        $res = $link->bdEjecutar($sql);
        $this->bitacora->Bitacorizar("Elimina Tiquete " . $this->factura);
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
     $cad = "ID " . $this->idTiquete  . "/n" .
     "Fecha: " . $this->fecha . "/n" .
     "Asiento: " . $this->asiento . "/n".
     "Viaje: " . $this->viaje->ToString() . "/n".
     "Estación de emisión: ". $this->estacion->ToString() . "/n".
     "Tipo de tiquete: " . $this->producto->ToString() . "/n".
     "Cierre: " . $this->cierre->ToString() . "/n" .
     "Parada de abordaje: " . $this->parada1->ToString() . "/n" . 
     "Parada destino:  " . $this->parada2->ToString()."/n".
     "Usuario que creo el tiquete: " . $this->usuario->ToString()."/n".
     "Monto : ". $this->monto .
     "Cliente estadística: " . $this->cliente->ToString()."/n";
     if($this->estado==0)
      $cad.="Estado: normal";
    else
      $cad.="Estado: nulo";
      
     return $cad;
    } 
   /// Anular tiquete
 function Anular($prazon){
     $this->Cargar();
     if($this->cierre->idCierre>0)
       throw new SimError("No se puede anular este tiquete porque pertenece a un cierre");
     $this->viaje->Cargar();
     if($this->viaje->liquidado==1)
       throw new SimError("No se puede anular este tiquete porque pertenece a una liquidacion");
    
    $this->estado=1;
    $this->comentario=$prazon;
    $res=$this->Guardar(null);
    if($res)
    $this->bitacora->Bitacorizar("Se anula tiquete ".$this->factura ." motivo: ".$prazon);
    return $res;
    
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
       $sql="select t.idtiquete,t.fecha,t.asiento,t.idviaje,
t.idestacion,t.idproducto,t.idcierre,t.estado,
t.idparada1,t.idcliente,t.factura,t.idparada2,t.fecha,
t.fechadi,t.idimpresora,t.monto,t.comentario,v.idruta,
r.nombre as ruta,e1.nombre as estacion,
p.nombre as producto,p1.nombre as parada1,
p2.nombre as parada2,i.nombre as impresora,
x.hora,c.nombre as cliente,v.fecha as fechaviaje,v.horaini as hora,
u.nombre as usuario,t.serial,t.anombre,t.cedula,t.numtar
from tiquete t 
inner join viaje v
on t.idviaje=v.idviaje
inner join ruta r
on v.idruta = r.idruta
inner join viajexesta x
on v.idviaje=x.idviaje 
inner join cliente c
on t.idcliente = c.idcliente
inner join estacion e1
on t.idestacion=e1.idestacion
inner join producto p
on t.idproducto = p.idproducto
inner join parada p1
on t.idparada1=p1.idparada
inner join parada p2
on t.idparada2=p2.idparada
inner join impresora i
on t.idimpresora = i.idimpresora
inner join usuario u
on t.idusuario=u.idusuario
where t.idestacion=x.idestacion and t.factura!='APARTA' 
".$tira. " order by v.fecha desc,x.hora desc ,t.factura desc " ;
    
       return $link->bdEjecutar($sql);
    } 

function GuardaImpreTiq()   {
    $link=new tiqmysql();
    $usu= $this->bitacora->usuario->idUsuario;
    if($usu==null)
      $usu=1;
    if($this->idTiquete!=null){
    $sql=" update tiquete set impreso=1 where idtiquete=".$this->idTiquete;
    
     $link->bdEjecutar($sql);  
     }
  }

function MarcaReimpreTiq($prazon)   {
    $link=new tiqmysql();
    $usu= $this->bitacora->usuario->idUsuario;
    if($usu==null)
      $usu=1;
    if($this->idTiquete!=null){
        $sql=" update tiquete set impreso=0 where idtiquete=".$this->idTiquete;
        $link->bdEjecutar($sql);  
        $this->bitacora->Bitacorizar("Se Marca para reimprimir tiquete ".$this->factura ." motivo: ".$prazon);
     }
  }


   function GuardaReimpreTiq()   {
    $link=new tiqmysql();
    $usu= $this->bitacora->usuario->idUsuario;
    if($usu==null)
      $usu=1;
    if($this->idTiquete!=null){
    $sql=" insert into reimpresion (idtiquete,idusuario,fecha) 
       values (".$this->idTiquete.",".$usu.",now()) ";
    
     $link->bdEjecutar($sql);  
     }
  }
    
}

?>
