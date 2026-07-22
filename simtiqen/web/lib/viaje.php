<?php
 class Viaje{
 	var $idViaje;
    var $fecha;
    var $chofer;
    var $cobrador;
    var $bus;
    var $ruta;
    var $extra;
 	var $activo;
 	var $estaciones=array();
 	var $bitacora;
    var $estadi;
    var $horaini;
	var $tipoviaje;
    var $fruta;
    var $color;
    var $noweb;
    var $liquidado;
    var $fechaliq;
    var $liqenco;
    var $fecliqenco;

    
//Contructor
 	function __construct(){
 	   $this->Limpiar();
 	}
    
//Limpia las propiedades    
 	function Limpiar() {
 		$this->idViaje=0;
        $this->fecha= '1900-01-01';
        $this->chofer = new Chofer();
        $this->cobrador = new Cobrador();
		$this->tipoviaje = new TipoViaje();
        $this->tipoviaje->idTipoViaje=1;
        $this->bus = new Bus();
        $this->extra=0;
 		$this->activo=1;
        $this->ruta = new Ruta();
        $this->estadi =new Estacion();
        $this->horaini="";
		$this->noweb=0;
		$this->liquidado=0;
		$this->fechaliq='1900-01-01';
		$this->liqenco=0;
		$this->fecliqenco='1900-01-01';

	}
   
//Guarda los datos , recibe como paremetro la transacci'on si existe 
	function Guardar($link){
		$this->Validar();
		//try {
		$bit='';
        $this->horaini=$this->estaciones[0]->hora;
		$nueva= ($this->idViaje==0);
		if($this->idViaje==0) {
 
            $frutas=array("Mango","Manzana","Banano",
                "Melocoton","Sandia","Pera",
                "Guanabana","Naranja","Pinna");
            $colores=array("Azul","Morada","Verde","Roja","Negra","Anaranjada","Amarilla","Violeta");
            $lafruta = $frutas[rand(0,8)];
            $elcolor = $colores[rand(0,7)];
            
			$sql =sprintf("insert into viaje (fecha,idchofer,idcobrador,idbus,idruta,
              extra,activo,fechadi,estadi,horaini,fruta,color,noweb,idtipoviaje)
			  value ('%s',%d,%d,%d,%d,%d,%d,'%s',%d,'%s','%s', '%s',%d,%d)",
			    $this->fecha,
                $this->chofer->idChofer,
                $this->cobrador->idCobrador,
                $this->bus->idBus,
                $this->ruta->idRuta,
                $this->extra,                
			    $this->activo,
			    date("Y-m-d H:i:s",time()),
                $this->estadi->idEstacion,
                $this->horaini,
                $lafruta,
                $elcolor,
                $this->noweb,
				$this->tipoviaje->idTipoViaje);
			 $bit= $bit . "Crea Viaje";
		}
		else 
		{
			$sql=sprintf("update viaje set 
			fecha ='%s',
            idchofer=%d,
            idcobrador=%d,
            idbus=%d,
            idruta=%d,
            extra=%d,
			activo=%d,
            estadi=%d,
            horaini='%s',
            noweb=%d,
            idtipoviaje=%d 
			where idViaje=%d",
			$this->fecha,
            $this->chofer->idChofer,
            $this->cobrador->idCobrador,
            $this->bus->idBus,
            $this->ruta->idRuta,
            $this->extra,
			$this->activo,
            $this->estadi->idEstacion,
            $this->horaini,
            $this->noweb,
			$this->tipoviaje->idTipoViaje,
			$this->idViaje          );
			$bit.= "Actualiza Viaje " . $this->idViaje . " " . $this->fecha; 
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
			$this->idViaje=$link->bdUltimoId();
		}
		$this->GuardaEstaciones($link);
		if($cerrartran){
			$link->bdCierraTran();
		}
       	$this->bitacora->bitacorizar($bit);
		return $this->idViaje;
	}
     
    function GuardaEstaciones($link){
        $modificados="";
        for($x=0;$x<count($this->estaciones);$x++)
        {
            $sql=sprintf("select idViajexesta from viajexesta where idViaje=%d and idestacion=%d",
            $this->idViaje,$this->estaciones[$x]->idEstacion);
            $res=$link->bdEjecutar($sql);
            $id=$link->PrimerValor($res);
            
            if($link->bdCantLineas($res)==0){
               $sql=sprintf("insert into viajexesta (idViaje,idestacion,hora,activo,fechadi) 
                values (%d,%d,'%s',%d,'%s')"
                ,$this->idViaje
                ,$this->estaciones[$x]->idEstacion
                ,$this->estaciones[$x]->hora
                ,$this->estaciones[$x]->activo
                ,date("Y-m-d H:i:s"));
               $link->bdEjecutar($sql);
               $modificados.= $link->bdUltimoId() . ",";

			}
            else{
                $sql=sprintf("update viajexesta set hora='%s' , activo=%d where idViajexesta=%d"
                ,$this->estaciones[$x]->hora
                ,$this->estaciones[$x]->activo
                ,$id);
                $link->bdEjecutar($sql);
                $modificados.= $id . ",";

            }
        }
        if(strlen($modificados)>1){
            $modificados=substr($modificados,0,strlen($modificados)-1);
            $link->bdEjecutar("delete from viajexesta where idViaje= ".$this->idViaje .
             " and not idViajexesta in (" . $modificados . ")");  
            }
        else
            $link->bdEjecutar("delete from viajexesta where idViaje=" . $this->idViaje);
    }
        
    function NuevaEstacion($idestacion,$hora,$activo){
        $nueva = new ViajexEsta();
        $nueva->idEstacion = $idestacion;
        $nueva->hora = $hora;
        $nueva->activo = $activo;
        array_push($this->estaciones,$nueva);
    }
    
   //Carga los datos de la Viaje 
	function Cargar(){
        $sql = sprintf("select * from viaje where idViaje=%d",$this->idViaje);
        $link = new tiqmysql();
        $res = $link->bdEjecutar($sql);
        if($link->bdCantLineas($res)>0){
           $linea = mysqli_fetch_array($res);    
           $this->fecha= $linea["fecha"];
           $this->chofer->idChofer=$linea["idchofer"];
           $this->cobrador->idCobrador=$linea["idcobrador"];
           $this->tipoviaje->idTipoViaje=$linea["idtipoviaje"];
           $this->bus->idBus = $linea["idbus"];
           $this->extra= $linea["extra"];
           $this->activo = $linea["activo"];
           $this->estadi->idEstacion=$linea["estadi"];
           $this->ruta->idRuta=$linea["idruta"];
           $this->fruta = $linea["fruta"];
           $this->color = $linea["color"] ;
           $this->horaini=$linea["horaini"];
           $this->noweb = $linea["noweb"];
           $this->liquidado= $linea["liquidado"];
           $this->fechaliq = $linea["fechaliq"];
           $this->liqenco = $linea["liqenco"];
           $this->fecliqenco = $linea["fecliqenco"];
           $this->CargaEstaciones();
           return true;
        }
        else
          return false;
    }
    
    //Carga las estaciones
    function CargaEstaciones(){
        $sql=sprintf("select * from viajexesta where idviaje=%d",$this->idViaje);
        $link = new tiqmysql();
        $res = $link->bdEjecutar($sql);
        $this->estaciones=array();
        if($link->bdCantLineas($res)>0){
            while($linea=mysqli_fetch_array($res))    
                $this->NuevaEstacion($linea["idestacion"],$linea["hora"],$linea["activo"]);
        }
    }
     
   //Valida los campos  
	function Validar() {
		$errores="";
		if(trim($this->fecha)=="")
		  $errores.="Fecha en blanco";
        foreach($this->estaciones as $nodo)
            if(!$nodo->Cargar())
                $errores.="Estación no existe";
        if(!$this->chofer->Cargar())
          $errores.="Chofer no existe ";
        if(!$this->cobrador->Cargar())
          $errores.="Cobrador no existe ";
        if(!$this->tipoviaje->Cargar())
          $errores.="Tipo de Viaje no existe ".$this->tipoviaje->idTipoViaje;
        if(!$this->bus->Cargar())
          $errores.="Bus no existe " . $this->bus->idBus;
        if(!$this->estadi->Cargar())
          $errores.="Estación de digitación no existe";
          
		if($errores!="")		
			throw new SimError($errores);
            
	}
//Borra la Viaje
    function Borrar(){
        
        $link = new tiqmysql();
        $link->bdAbreTran();
        $sql=sprintf("delete from viajexesta where idViaje=%d",$this->idViaje);
        $res = $link->bdEjecutar($sql);
        $sql=sprintf("delete from viaje where idViaje=%d",$this->idViaje);
        $res = $link->bdEjecutar($sql);
        $link->bdCierraTran();
        $this->bitacora->Bitacorizar("Elimina Viaje " . $this->idViaje);
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
     $cad = "ID " . $this->idViaje  . " /n" .
     " fecha: ".$this->fecha." /n".
     " Activo: " . $this->activo . "/n" .
     " Hora inicio: ". $this->horaini;
     foreach($this->estaciones as $nodo)
       $cad .= $nodo->ToString();
     return $cad;
    }  

//Devuelve el siguiente viaje que pase por la parada que viene por parametro
function SiguienteViaje($pestacion,$pparada,$pfecha,$pruta,$ptipo){
    /*echo "estos son los datos que llegan a siguienteviaje estacion:".$pestacion." parada ".$pparada.
    " fecha ".$pfecha." ruta ".$pruta;
    */
    $link = new tiqmysql();
    $par1= new Parametro();
    $otrosfiltros="";
    if($ptipo==1)
    $tiempo = $par1->Retorna('TRES',1);
    else{ 
    $tiempo = $par1->Retorna('tiempoenco',1);
    $otrosfiltros=" and (v.idtipoviaje!= 2 or v.idruta != 2) and v.liqenco = 0";
    } 
    //en caso de que sean tiquetes vendidos por internet debe sugerir el siguiente
    if($ptipo==3){
        $otrosfiltros=" and ifnull(j.vendidos,0)<b.cantpas"; 
    }
    
    $pfechan = strtotime($pfecha)+(60*$tiempo);
    //esto es para optimizar el select , no se fijará si hay viajes de más de 5 días para adelante
    $pfemax = strtotime($pfecha)+(7*60*60*24);
    $sql=sprintf("select v.idviaje ,v.fecha,x.hora,j.vendidos,b.cantpas
        from viaje v 
        inner join lista l
        on v.idruta = l.idruta
	  inner join viajexesta x
	  on v.idviaje=x.idviaje
       left join (
		select t.idviaje,count(*) as vendidos 
		from tiquete t inner join viaje v2
        on t.idviaje=v2.idviaje
		where v2.fecha >= '%s' and t.estado=0 and t.asiento>0 and t.factura!='APARTA'
        and v2.fecha<'%s'
		group by t.idviaje
		) j
	  on j.idviaje=v.idviaje
	  inner join Bus b
	  on v.idbus=b.idbus
        where x.idestacion=%d
        and l.idparada2= %d
        and ((v.fecha='%s' and x.hora>'%s')
	    or v.fecha>'%s')
        and v.fecha<'%s' 
        and v.idruta=%d " . $otrosfiltros.  "
        order by 2,3 LIMIT 1 ",
        date('Y-m-d',$pfechan),date('Y-m-d',$pfemax),$pestacion,$pparada,
        date('Y-m-d',$pfechan),date('H:i',$pfechan),date('Y-m-d',$pfechan),
        date('Y-m-d',$pfemax), $pruta);
        
       //echo $sql;
	
        $res = $link->PrimerValorD($sql);
        return $res;
}

function AsientosDisponibles(){
    $link = new tiqmysql();
    $link->bdEjecutar("update bloqueo set fechabloqueo=now()");
    $this->LiberaApartados();
    $sql= sprintf("select b.cantpas-ifnull(x.cantidad,0) as saldo
    from viaje v inner join  Bus b 
    on v.idbus=b.idbus
    left join (
  select t.idviaje,count(*) as cantidad
    from tiquete t
	where t.idviaje=%d
	and t.estado=0 
	and t.asiento>0
	group by t.idviaje
  ) x
on v.idviaje=x.idviaje
where v.idviaje=%d",$this->idViaje,$this->idViaje);
    return $link->PrimerValorD($sql);
}

function AsientosDisponiblesEspeciales(){
    
    $link = new tiqmysql();
    $this->bus->Cargar();
    $astos = explode(" ", $this->bus->asientosespe);
   
    $sql= sprintf("select ".count($astos)."-ifnull(x.cantidad,0) as saldo
    from viaje v inner join  Bus b 
    on v.idbus=b.idbus
    left join (
  select t.idviaje,count(*) as cantidad
    from tiquete t
	where t.idviaje=%d
	and t.estado=0 
	and t.asiento in (".$this->bus->asientosespe.")
	group by t.idviaje
  ) x
on v.idviaje=x.idviaje
where v.idviaje=%d",$this->idViaje,$this->idViaje);
    return $link->PrimerValorD($sql);


}

function AsientosDisponiblesDP(){
    
    $link = new tiqmysql();
    $sql= sprintf("
  select count(*) as cantidad
    from tiquete t
	where t.idviaje=%d
	and t.estado=0 
	and t.asiento=-1
  ",$this->idViaje);
    return 10-$link->PrimerValorD($sql);

}

function LiberaApartados(){
    $link = new tiqmysql();
    $par1=new Parametro();
    $horacorte = time()- ($par1->Retorna('HCOR',1)*60);
     $sql=sprintf("select idTiquete 
        from tiquete
        where idviaje=%d
        and factura='APARTA'
        and UNIX_TIMESTAMP(fechadi)< %d
        and estado=0",$this->idViaje,$horacorte);
    $res=$link->bdEjecutar($sql);    
    while($fila=mysqli_fetch_array($res))
    {
         $tiq = new Tiquete();
         crealog(1,"Libera ".$horacorte);
         
         $tiq->idTiquete=$fila["idTiquete"];
         $tiq->HeredaBitacora($this->bitacora);
         $tiq->Anular("Libera Apartado por timeout");
         
    }
}

function SiguienteAsiento($pcantidad,$link){
    $this->bus->Cargar();
    $especiales = explode(",", $this->bus->asientosespe);

    if($link==null)
        $link = new tiqmysql();
   $this->LiberaApartados();
   $link->bdEjecutar("update bloqueo set fechabloqueo=now()");
    $sql=sprintf("select idTiquete,asiento 
        from tiquete
        where idViaje=%d
        and estado=0
        and not asiento in (".$this->bus->asientosespe.") and asiento>0
        order by asiento",$this->idViaje);
    $res=$link->bdEjecutar($sql);
    $x=1;
/*
    while($fila=mysqli_fetch_array($res))
    {
      

        echo " x = ".$x." asiento = ". $fila["asiento"]  ."<br/> ";
        if($x<$fila["asiento"]     )
        {
            while ($x<$fila["asiento"]){  //va aumentando la $x hasta llenar el hueco
            $hueco=$fila["asiento"]-$x;
    
            if($hueco>=$pcantidad){
                echo " entró en el hueco <br/>";
                 if(( $pcantidad==1) || ( (($x % 2)!=0)  && ($pcantidad!=1)  )) //esto es para asegurarse que si es juntos el asiento sea impar
                    return $x;
             }
             $x++;
          }
         }
         $x=$fila["asiento"]+1;
     }
  */   
     $tambus=$link->PrimerValorD(sprintf("select cantpas from Bus where idBus=%d",$this->bus->idBus));
      
      //nuevo busca hueco
     $asientos=array();
     for($z=1;$z<=$tambus;$z++)
        $asientos[$z]=0;
     
    //rellena la matriz
    while($fila=mysqli_fetch_array($res))
       $asientos[$fila["asiento"]]=1;
    
    
    //rellena los especiales
    for($z=0;$z<count($especiales);$z++){
        $asientos[$especiales[$z]]=1;
    }

 // esta nueva rutina solo cuenta los espacios vacios entre huecos y si es mayor o igual a lo buscado lo devuelve
    $k=1;
    while($k<=count($asientos)){
      $cont=0;
      while(($k+$cont)<=count($asientos) && $asientos[$k+$cont]==0){
        $cont++;
      }
      if($cont>=$pcantidad)
        return $k;
      else
        $k=$k+$cont+1;
    }
   

 
    //recorre buscando huecos
    $x=1;
    $salir=false;
    while($x<=$tambus && !$salir){
        if(($pcantidad==1) && ($asientos[$x]==0))
            $salir=true;
        else
            if(($asientos[$x]==0) && (($x % 2)!=0)) //si la cantidad es m[as de uno es que busca juntos y debe ser impar
                $salir=true;
        if(!$salir)
            $x++;
    }
   // echo "encontro hueco en ".$x;


    
     if($x+$pcantidad<=$tambus+1)
       if($pcantidad==1) //si no esta marcado para juntos
           return $x;
       else
         if(($x%2)!=0 )  //si es impar
          return $x;
         else   
            if( $x+1+$pcantidad<=$tambus+1)  //si es para pero los dos siguientes están libres
                return $x+1;
            else
               return -1;
           
    else
      return -1;
     
}

function ApartarTiquetes2($tiq,$matrizasi){
/*
    if($this->liquidado==1){
      throw new SimError("Este viaje ya esta liquidado por lo que no se puede vender tiquetes");
      return;
    }
*/

//ojo parche para asegurar que el precio es el correcto
     $tiq->monto = $this->ruta->PrecioProducto($tiq->parada1->idParada,$tiq->parada2->idParada,$tiq->producto->idProducto);

if($tiq->monto==0  && $tiq->producto->idProducto!=99 && $tiq->producto->idProducto!=2){
        throw new Exception("\r\n\r\nNo puede vender un tiquete en cero que no sea cortesia\r\n\r\n", 1);

      }


    $tiquetes = array();
     $link= new tiqmysql();

/// esto es para revisar si ya está cerrado por estación
/*
     $elsql = sprintf("select cerrado from viajexesta 
      where idviaje=%d and idestacion=%d",$this->idViaje,$tiq->estacion->idEstacion);
*/
     $elsql = "select liquidado from viaje where idviaje=".$this->idViaje;
     $sicerrado = $link->PrimerValorD($elsql);
     if($sicerrado==1){
      throw new Exception("\r\n\r\nEste viaje ya esta liquidado para esta
       estación por lo que no se puede vender tiquetes\r\n\r\n", 1);
      return;
     }


     $link->bdAbreTran();
     $link->bdEjecutar("update bloqueo set fechabloqueo=now()" );
     for($x=0;$x<count($matrizasi);$x++){
        if($matrizasi[$x]!=""){
            $elsql = sprintf("select ifnull(count(*),0) 
                from tiquete 
                where idviaje=%d 
                and asiento=%d 
                and estado=0",$this->idViaje,$matrizasi[$x]);
            $cantcon = $link->PrimerValorD($elsql);
            if($cantcon>0){
                $link->bdRevierteTran();
                throw new SimError("El asiento ".$matrizasi[$x]." ya esta ocupado");
                return;
            }
            else{
                $tiq2 = clone $tiq;
                $tiq2->viaje = $this;
                $tiq2->asiento=$matrizasi[$x];
                $tiq2->Apartar($link);
                array_push($tiquetes,$tiq2->idTiquete);
            }
        }

     }
    $link->bdCierraTran();
    $this->bitacora->Bitacorizar("Se apartan  tiquetes para el viaje " . $this->idViaje. " " .$this->fecha);
    return $tiquetes;

}

function ApartarTiquetes($tiq,$cantidad,$numasie,$juntos,$depie){

/*
    if($this->liquidado==1){
      throw new SimError("Este viaje ya esta liquidado por lo que no se puede vender tiquetes");
      return;
    }
*/

    if($this->AsientosDisponibles()<$cantidad && $cantidad!=0)
       throw new SimError("No existen suficientes asientos disponibles para esta cantidad de tiquetes ->".$cantidad);
//ojo parche para asegurar que el precio es el correcto
     $tiq->monto = $this->ruta->PrecioProducto($tiq->parada1->idParada,$tiq->parada2->idParada,$tiq->producto->idProducto);
     
if($tiq->monto==0  && $tiq->producto->idProducto!=99 && $tiq->producto->idProducto!=2){
        throw new Exception("\r\n\r\nNo puede vender un tiquete en cero que no sea cortesia\r\n\r\n", 1);

      }


    $tiquetes = array();
     $link= new tiqmysql();
//esto revisa que no se puedan vender más de 10 de pie
     if(($depie>0) && ($this->tipoviaje->idTipoViaje!=2)){
      $elcanpie = $link->PrimerValorD("select count(*) as cant from tiquete where idviaje=".$this->idViaje." and asiento=-1 and factura!='APARTA' ");
      if(($elcanpie+$depie)>10)
        throw new SimError("No puede vender más tiquetes de pie ");
     }


/// esto es para revisar si ya está cerrado por estación
     $elsql = sprintf("select cerrado from viajexesta 
      where idviaje=%d and idestacion=%d",$this->idViaje,$tiq->estacion->idEstacion);
     $sicerrado = $link->PrimerValorD($elsql);
     if($sicerrado==1){
      throw new Exception("\r\nEste viaje ya esta liquidado para 
        esta estación por lo que no se puede vender tiquetes\r\n\r\n", 1);
      return;
     }
    
     


     $link->bdAbreTran();
     $link->bdEjecutar("update bloqueo set fechabloqueo=now()");
    if ($cantidad!=0){  //significa que solo se va a vender de pie
      if($numasie==null)
        if($juntos)
            $asiento= $this->SiguienteAsiento($cantidad,$link);
        else
          $asiento=$this->SiguienteAsiento(1,$link);
    else{
       $tambus=$link->PrimerValorD(sprintf("select cantpas from Bus where idBus=%d",$this->bus->idBus));
       if($numasie>$tambus ) 
             $asiento=-1;  //está tratando de vender un asiento que no existe en el bus
       else{
        if($juntos)
            $asiento= $this->SiguienteAsiento($cantidad,$link);
        else
            $asiento=$numasie;
        }
      }  
     if($asiento==-1  ){
         $link->bdRevierteTran();
         throw new SimError("No hay asientos disponibles");
         return;
    }
    }
    for($xk=1;$xk<=$cantidad;$xk++)
    {

        $tiq2 = clone $tiq;
        $tiq2->viaje = $this;
        $tiq2->asiento=$asiento;
        $tiq2->Apartar($link);
        array_push($tiquetes,$tiq2->idTiquete);
        if($juntos)
            $asiento++;
        else{
            if($xk!=$cantidad){  //esto es porque cuando es el último no hace falta verificar el siguiente
            $asiento=$this->SiguienteAsiento(1,$link);
            if($asiento==-1){
                $link->bdRevierteTran();
                throw new SimError("No hay asientos disponibles<br/>Si desea vender los asiento 1 y 2 debe marcarlos manualmente");
                return;
                }
            }
    
            }
    }
    for($xk=1;$xk<=$depie;$xk++)
    {
        $tiq2 = clone $tiq;
        $tiq2->viaje = $this;
        $tiq2->asiento=-1;
        $tiq2->Apartar($link);
        array_push($tiquetes,$tiq2->idTiquete);
    }
    
    $link->bdCierraTran();
    $this->bitacora->Bitacorizar("Se apartan ". $cantidad. " tiquetes para el viaje " . $this->idViaje. " " .$this->fecha);
    return $tiquetes;
          
}

function ListaAsientos(){
    $link= new tiqmysql();
    $sql=sprintf(" select  asiento,idestacion,factura,idProducto 
       from tiquete 
       where idviaje=%d and asiento>0 and estado=0 order by 1" ,$this->idViaje);
    $res = $link->bdEjecutar($sql);
    $asientos=  array();
    while($fila=mysqli_fetch_array($res)) {
      array_push($asientos,array("asiento"=> $fila["asiento"],"idestacion"=> $fila["idestacion"] ,"factura"=> $fila["factura"],"idProducto"=> $fila["idProducto"]) );
      }
    return $asientos;
}

//s Basado en un arreglo carga los datos para el mantenimiento
   // $parametros: arreglo que trae los datos
   // $opcion: tipo de operación que se realizará en el mantenimiento
   //          1- Agregar, 2- Modificar, 3- Borrar
   function CargarArreglo($parametros,$opcion){
       $retorno = null;
       switch($opcion) {
       	case 1:
            $this->fecha= $parametros["fecha"];
            $this->idViaje=$parametros["idviaje"];
            $this->ruta->idRuta=$parametros["idruta"];
            $this->chofer->idChofer=$parametros["idchofer"]+0;
            $this->cobrador->idCobrador=$parametros["idcobrador"]+0;
            $this->tipoviaje->idTipoViaje=$parametros["idtipoviaje"]+0;
            $this->bus->idBus = $parametros["idbus"]+0;
            $this->extra= $parametros["extra"]+0;
            $this->estadi->idEstacion=$parametros["estadi"];
            $this->activo = 1;
            $this->noweb= $parametros["noweb"]+0;
            $horas=$parametros["horas"];
            $llaves=array_keys($horas);
            for($x=0;$x<count($llaves);$x++){
                $ides = $llaves[$x];
              $this->NuevaEstacion($ides,$horas[$llaves[$x]],1);
              }
              
              $retorno = true;
       	        break;
       	case 2:
            $this->fecha= $parametros["fecha"];
            $this->idViaje=$parametros["idviaje"];
            $this->ruta->idRuta=$parametros["idruta"];
            $this->chofer->idChofer=$parametros["idchofer"]+0;
            $this->tipoviaje->idTipoViaje=$parametros["idtipoviaje"]+0;
            $this->cobrador->idCobrador=$parametros["idcobrador"]+0;
            $this->bus->idBus = $parametros["idbus"]+0;
            $this->extra= $parametros["extra"]+0;
            $this->noweb= $parametros["noweb"]+0;            
            $this->estadi->idEstacion=$parametros["estadi"];            
            $this->activo = 1;
            $horas=$parametros["horas"];
            $llaves=array_keys($horas);
            for($x=0;$x<count($llaves);$x++){
                $ides = $llaves[$x];
              $this->NuevaEstacion($ides,$horas[$llaves[$x]],1);
              }
              
              $retorno = true;
       	        break;
         case 3:
       	        $this->idViaje = $parametros["idviaje"];
 	              $retorno = $this->Cargar();
       	        break;
   }
    
       return $retorno;
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
/*       $elsql="select v.idviaje,v.idruta,v.idbus,v.fecha,v.estadi,
                        v.idchofer,v.idcobrador,v.fechadi,v.activo,v.extra,min(x.hora  ) as hora,
						v.noweb,v.idtipoviaje 
                        from viaje v inner join viajexesta x 
                        on v.idviaje= x.idviaje 
                         " . $tira . " group by 
                        v.idviaje,v.idruta,v.idbus,v.fecha,v.idchofer,v.idcobrador,v.fechadi,v.activo,v.extra 
                        order by 4,11 ";
  */

         $elsql="select v.idviaje,v.idruta,v.idbus,v.fecha,v.estadi,
                        v.idchofer,v.idcobrador,v.fechadi,v.activo,v.extra,v.horaini as hora,
                        v.noweb,v.idtipoviaje ,tp.destipoviaje as tipoviaje
                        from viaje v inner join viajexesta x 
                        on v.idviaje= x.idviaje 
                        left join tipoviaje tp on tp.idtipoviaje=v.idtipoviaje
                         " . $tira . " group by 
                        v.idviaje,v.idruta,v.idbus,v.fecha,v.idchofer,v.idcobrador,v.fechadi,v.activo,v.extra 
                        order by 4,11 ";
     
       return $link->bdEjecutar($elsql. $limite);
    }	

function ListarActivos($filtro){
       $link=new tiqmysql();
       $tira = "";
       $limite = "";
       $paginacion=null;
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
       
       return $link->bdEjecutar("select v.idviaje,v.idruta,v.idbus,v.fecha,v.estadi,
                        v.idchofer,v.idcobrador,v.fechadi,v.activo,v.extra,min(x.hora  ) as hora,
						v.noweb,v.idtipoviaje ,v.liquidado,v.fechaliq,v.liqenco,v.fecliqenco,tp.destipoviaje as tipoviaje
                        from viaje v inner join viajexesta x 
                        on v.idviaje= x.idviaje 
                        left join tipoviaje tp on tp.idtipoviaje=v.idtipoviaje
                         " . $tira . " group by 
                        v.idviaje,v.idruta,v.idbus,v.fecha,v.idchofer,v.idcobrador,v.fechadi,v.activo,v.extra 
                        order by 4,11 ". $limite);
    }	
	
function Remision($pestacion,$parada){
	if(is_null($parada) || ($parada==""))
		$condi="";
	else
		$condi=" and e.idparada=".$parada;
	$sql=sprintf("select v.idviaje,v.fecha,x.hora,ch.nombre as chofer,v.idtipoviaje 
		,cb.nombre as cobrador,b.placa
		,e.idencomienda,e.factura,e.fecha as fechaenco,e.remitente,e.destinatario
		,e.cedremi,e.ceddesti,e.monto,e.nota,e.cantbul,e.declarado
		,e.telremi,e.teldesti1,e.teldesti2,e.fechadi,e.idusuario,e.idtipotrack
		,p.nombre as parada,pr.nombre as producto,u.nombre as usuario,v.liqenco
		from viaje v
		inner join viajexesta x
		on v.idviaje=x.idviaje
		inner join chofer ch
		on v.idchofer = ch.idchofer
		inner join cobrador cb
		on v.idcobrador=cb.idcobrador
		inner join Bus b
		on v.idbus= b.idbus
		inner join encomienda e
		on v.idviaje=e.idviaje
		inner join parada p
		on e.idparada=p.idparada
		inner join producto pr
		on e.idproducto=pr.idproducto
		inner join usuario u
		on u.idusuario=e.idusuario
		where v.idviaje=%d
		and x.idestacion=%d and e.estado=0 and e.idestacion=%d
		order by p.nombre,e.idencomienda ".$condi,$this->idViaje, $pestacion,$pestacion);
	$link=new tiqmysql();
	return $link->bdEjecutar($sql);
}

function ToArray(){
     $res= array(
        "idViaje"=>$this->idViaje,
        "fecha"=>$this->fecha,
        "idChofer"=>$this->chofer->idChofer,
        "chofer"=>$this->chofer->nombre,
        "idCobrador"=>$this->cobrador->idCobrador,
        "cobrador"=>$this->cobrador->nombre,
        "idBus"=>$this->bus->idBus,
        "bus"=>$this->bus->placa,
        "idRuta"=>$this->ruta->idRuta,
        "ruta"=>$this->ruta->nombre,
        "extra"=>$this->extra,
        "liquidado"=>$this->liquidado,
        "fechaliq"=>$this->fechaliq,
        "liqenco"=>$this->liqenco,
        "fecliqenco"=>$this->fecliqenco
        ); 
    return $res;
}

	function Liquida(){
		$link=new tiqmysql();
		$tira = "";
		/*$sql = "select tiquete.asiento,tiquete.idestacion,tiquete.factura,tiquete.monto,
			estacion.nombre as estacion,if(asiento<0,1,0) as orden,if(asiento<0,'De Pie',asiento) as tasiento,
			producto.nombre as producto,impresora.nombre as impresora
			from tiquete 
			inner join estacion on tiquete.idestacion = estacion.idestacion
			inner join producto on tiquete.idproducto = producto.idproducto
			inner join impresora on tiquete.idestacion = impresora.idimpresora
			where idviaje= " . $this->idViaje . "  and asiento != 0 and estado=0 order by 6,1";
		*/
		$sql = "select r.abreviatura as ruta,par.nombre as parada, case when asiento=-1 then 'De pie' else pro.nombre end as producto 
			,t.monto as precio, sum(t.monto) as vendido,count(*) as cantidad
      ,sum(case when t.idEstacion=4 then 1 else 0 end ) as web
      ,sum(case when t.idEstacion=4 then 0 else 1 end ) as terminal
			from viaje v
			inner join ruta r on r.idruta=v.idruta
			inner join tiquete t on t.idviaje= v.idviaje
			inner join parada par on par.idparada=t.idparada2
			inner join producto pro on pro.idproducto=t.idproducto
			where v.idviaje=" . $this->idViaje . "  and t.asiento!=0 and t.estado=0
			group by r.nombre ,3 
			,par.nombre
			, pro.nombre
			,t.monto
			order by 1,2,3 ";      
		return $link->bdEjecutar($sql);
    }


	function CierraViaje($pestacion){
		$link=new tiqmysql();
		$sql=" update viaje set liquidado=1, fechaliq=now() where idviaje=".$this->idViaje;
		$link->bdEjecutar($sql);

		$sql = "update viajexesta set cerrado=1 where idviaje=".$this->idViaje." and idestacion=".$pestacion;
                $link->bdEjecutar($sql);
		$this->bitacora->Bitacorizar("Cierra viaje " .
		 $this->idViaje. " " .$this->fecha." ".
		$this->horaini." estacion ".$pestacion);
		return "";
	}
  
	function CierraEncomienda(){
		$link=new tiqmysql();
		$sql=" update viaje set liqenco=1, fecliqenco=now() where idviaje=".$this->idViaje;
		$link->bdEjecutar($sql);
		$this->bitacora->Bitacorizar("Cierra Encomienda " . $this->idViaje. " " .$this->fecha." ".$this->horaini);
		return "";
	}

function CantidadVendida(){
  $sql="select count(idtiquete) as can
    from tiquete 
    where idviaje=".$this->idViaje."
    and estado=0
    and factura!='APARTA';";
  $link=new tiqmysql();
  return $link->bdEjecutar($sql);
}



}

?>
