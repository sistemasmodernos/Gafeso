<?php 
$rutapri=$_SERVER['DOCUMENT_ROOT'].'/tiquetes/';
include_once($rutapri.'lib/nucleo.php');

class SimpleXMLExtended extends SimpleXMLElement
{
  public function addCData($cdata_text)
  {
    $node = dom_import_simplexml($this); 
    $no   = $node->ownerDocument; 
    $node->appendChild($no->createCDATASection($cdata_text)); 
  } 
}



    function wsPrecios($pusuario,$pclave,$pdatos){
        if(!wsValidarUsuario($pusuario,$pclave))
          return "ERROR: usuario inv&aacute;lido ff".$pusuario." ".$pclave;
        $datos = xml2array($pdatos);
        $elxml= new SimpleXMLElement("<?xml version='1.0' ?>\n<tabla></tabla>");         
         for($x=0;$x<count($datos);$x++){
              $linea = $elxml->addChild("reg"); 
              $linea->addChild("idlincompra",$datos[$x]["idlincompra"]);
              $linea->addChild("idruta",$datos[$x]["idruta"]);
              $linea->addChild("idviaje",$datos[$x]["idviaje"]);
              $linea->addChild("idparada1",$datos[$x]["idparada1"]);
              $linea->addChild("idparada2",$datos[$x]["idparada2"]);
              $linea->addChild("idproducto",$datos[$x]["idproducto"]);
              $linea->addChild("cantidad",$datos[$x]["cantidad"]);
              $elmonto = Precios(
                    $datos[$x]["idviaje"],
                    $datos[$x]["idproducto"],
                    $datos[$x]["cantidad"],
                    $datos[$x]["idparada1"],
                    $datos[$x]["idparada2"]
                    );
              $linea->addChild("precio",$elmonto);          
              
         }
        return $elxml->asXML();
}


    function wsApartar($pusuario,$pclave,$pdatos,$nombre){
        $datos = xml2array($pdatos);

        $dev="";
        for($xj=0;$xj<count($datos);$xj++){

            $pviaje=$datos[$xj]["idviaje"]+0;
            $pcantidad=$datos[$xj]["cantidad"]+0;
            $depie = $datos[$xj]["depie"]+0;
            $pparada1=$datos[$xj]["idparada1"]+0;
            $pparada2=$datos[$xj]["idparada2"]+0;
            $pidproducto=$datos[$xj]["idproducto"]+0;
            $pprecio = $datos[$xj]["precio"]+0;
            $nomruta =  $datos[$xj]["nomruta"];
            try{
            if(!wsValidarUsuario($pusuario,$pclave))
               return "ERROR: usuario inv&aacute;lido";
            $vi = new Viaje();
            $vi->idViaje=$pviaje;
            if($vi->Cargar()<1)
              return "";
            $optiquete=array(); 
            $optiquete["monto"]=$pprecio;
            $optiquete["idEstacion"]=$_SESSION["parametros"]["estaciondefault"]+0;
            $optiquete["idProducto"]=$pidproducto;
            $optiquete["idParada1"]=$pparada1;
            $optiquete["idParada2"]=$pparada2;
            $optiquete["idCliente"]=1;
            $optiquete["idImpresora"]=$_SESSION["parametros"]["impresoradefault"]+0;
            $optiquete["cedula"]="";
            $optiquete["nombre"]=$nombre;
            $optiquete["fechanac"]="";
            $optiquete["numtar"]="";
            $mat=  ApartarTiquetes($pviaje,$pcantidad,3,$optiquete,1,$depie);

            if($mat==null){
               $elerro= "ERROR: No se pudo apartar los asientos ;".$xj.";".$nomruta.";".$_SESSION["parametros"]["mensaje_error"];
              if($dev!=""){
               $poranular=explode(",",$dev);
               for($b=0;$b<count($poranular);$b++)

                 if($poranular[$b]!="")
                  AnularTiquete($poranular[$b],"No se pudieron apartar todos los tiquetes");
              }
              return $elerro;
             }

            for($x=0;$x<count($mat);$x++){
                $dev.=$mat[$x].",";
           }
  
        
         }
            
             catch(Exception $ex){
                return "ERROR:".$ex->getMessage();
             }
    }
    if(strlen($dev)>0)
                $dev=substr($dev,0,strlen($dev)-1);
        return $dev;
    }
    
    
    function wsVender($pusuario,$pclave,$tiquetes){
        
        if(!wsValidarUsuario($pusuario,$pclave))
          return "ERROR: usuario inv&aacute;lido ".$pusuario."--".$pclave ;
          $res=explode(",",$tiquetes);
          
          if(!VenderTiquetes($res))
            return "ERROR: ocurri&oacute; un error al realizar la venta de los tiquetes ->".$_SESSION["parametros"]["mensaje_error"];
      $detalle =new  SimpleXMLExtended("<?xml version='1.0' ?>\n<venta></venta>");
      for($x=0;$x<count($res);$x++){
        $eltiq=ListaActivos("Tiquete",array("idtiquete=".$res[$x]));
        $linea = $detalle->addChild("reg");
        $linea->addChild("factura",$eltiq[0]["factura"]);
        $linea->addChild("asiento",$eltiq[0]["asiento"]);
        $linea->addChild("fecha",fechatoNatural(substr($eltiq[0]["fechaviaje"],0,10)));
        $linea->addChild("hora",$eltiq[0]["hora"]);
        $linea->addChild("monto",$eltiq[0]["monto"]);
         $linea->addChild("idtiquete",$res[$x]);
         $laimpre = $linea->addChild("impresion","");
         $laimpre->addCData( ImprimeTiq2($res[$x]));
        }
        $dev = $detalle->asXML();
        
        return $dev;
    }
    
    function wsRutas($pusuario,$pclave){
        
        try {
       if(!wsValidarUsuario($pusuario,$pclave))
            return "ERROR: usuario inv&aacute;lido ".$pusuario."--".$pclave ;
        //  return "paso";
        
        $res=ListaActivos("Ruta",null) ;
        
        $elxml= new SimpleXMLElement("<?xml version='1.0' ?>\n<tabla></tabla>");
        for($x=0;$x<count($res);$x++){
          $linea=$elxml->addChild("reg");
          $linea->addChild("idruta",$res[$x]["idruta"]);
          $linea->addChild("nombre",$res[$x]["nombre"]);
          $linea->addChild("idparada1",$res[$x]["idparada1"]);
        }
         
        $dev=$elxml->asXML();
        return $dev;
    }
    catch (Exception $e) {
    echo 'Excepción capturada: ',  $e->getMessage(), "\n";
}
    }
    
    
    function wsParadas($pusuario,$pclave){
        if(!wsValidarUsuario($pusuario,$pclave))
          return "ERROR: --Paradas-- usuario inv&aacute;lido";
        $res=ListaActivos("Parada",null) ;
        $elxml= new SimpleXMLElement("<?xml version='1.0' ?>\n<tabla></tabla>");
        for($x=0;$x<count($res);$x++){
          $linea=$elxml->addChild("reg");
          $linea->addChild("idparada",$res[$x]["idparada"]);
          $linea->addChild("nombre",$res[$x]["nombre"]);
          $linea->addChild("noextra",$res[$x]["noextra"]);
	      $linea->addChild("idruta",$res[$x]["idruta"]);
        }
        $dev=$elxml->asXML();
        return $dev;

}


    function wsProductos($pusuario,$pclave){
        if(!wsValidarUsuario($pusuario,$pclave))
          return "ERROR: --Productos-- usuario inv&aacute;lido";
        $res=ListaActivos("Producto",array(" tipo=1 ")) ;
        $elxml= new SimpleXMLElement("<?xml version='1.0' ?>\n<tabla></tabla>");
        for($x=0;$x<count($res);$x++){
          $linea=$elxml->addChild("reg");
          $linea->addChild("idproducto",$res[$x]["idProducto"]);
          $linea->addChild("nombre",$res[$x]["nombre"]);
          $linea->addChild("precio",$res[$x]["precio"]);
        }
        $dev=$elxml->asXML();
        return $dev;

    }





    function wsViajes($pusuario,$pclave,$pruta,$pdesde,$phasta){
        if(!wsValidarUsuario($pusuario,$pclave))
          return "ERROR: --Viajes-- usuario inv&aacute;lido";
	$proximo=wsSiguienteViaje($pusuario,$pclave,$pruta,1);
   	$vis= ListaActivos("Viaje",array("v.idviaje=".$proximo));

	$corte=substr($vis[0]["fecha"],0,10).$vis[0]["hora"];
  
       $res = Viajes($pruta,$_SESSION["parametros"]["estaciondefault"],$pdesde,$phasta)  ;
       $ahora = strtotime('now');
       $fe= date('Y-m-dH:i',$ahora+(20*60));  //  restringe 20 minutos antes 
       $elxml= new SimpleXMLElement("<?xml version='1.0' ?>\n<tabla></tabla>");
        for($x=0;$x<count($res);$x++){
	  if((substr($res[$x]["fecha"],0,10).$res[$x]["hora"]>=$corte) 
             &&  ($res[$x]["noweb"]=="0") 
             && (substr($res[$x]["fecha"],0,10).$res[$x]["hora"]>=$fe)){  //solo horarios mayores al proximo viaje
             
            $linea=$elxml->addChild("reg");
            $linea->addChild("idviaje",$res[$x]["idviaje"]);
            $linea->addChild("fecha",$res[$x]["fecha"]);
            $linea->addChild("hora",$res[$x]["hora"]);
            $linea->addChild("disponible",$res[$x]["cantpas"]-$res[$x]["vendidos"]-2);
            $linea->addChild("extra",$res[$x]["extra"]);
            $linea->addChild("noweb",$res[$x]["noweb"]);
	    
	  }
        }
        $dev=$elxml->asXML();
        return $dev;
        
    }
    
  
    
    function wsValidarUsuario($puser,$pclave){
        return 10;
         $res= (AutorizarSesion($puser,$pclave)>0);
         return $res;
    }
 
 
   
  function wsDispo($pusuario,$pclave,$pviaje){

         if(!wsValidarUsuario($pusuario,$pclave))
          return "ERROR: --Dispo-- usuario inv&aacute;lido";
        $dispo =0;
        $res=AsientosDisponibles($pviaje);
        if($res==null)
         return " ";
        else 
         $dispo= $res;
        $resx=AsientosDisponiblesEspeciales($pviaje);         
        $dispo = $dispo - $resx;
        return $dispo."";
    }
  function wsDispoDP($pusuario,$pclave,$pviaje){

         if(!wsValidarUsuario($pusuario,$pclave))
          return "ERROR: --DispoDP-- usuario inv&aacute;lido";
        $dispo =0;
        $res=AsientosDisponiblesDP($pviaje);
        if($res==null)
         return " ";
        else 
         $dispo= $res;
        return $dispo."";
    }
  function wsSiguienteViaje($pusuario,$pclave,$idruta,$idparada){
        if(!wsValidarUsuario($pusuario,$pclave))
          return "ERROR: usuario inv&aacute;lido";
      return SiguienteViaje($_SESSION["parametros"]["estaciondefault"],$idparada,date('Y-m-d'),$idruta,1);
  }

    function xml2array($contents) { 
        if(!$contents) return array(); 
        
        $res=array();
        $tabla=  new SimpleXMLElement($contents);
       
        foreach($tabla->reg as $reg){
            $nreg=array();
            foreach($reg->children() as $reg2){
               $nom = $reg2->getName();
              $nreg[$nom] =(string) $reg->$nom ;
            }
            array_push($res,$nreg);
        }
     return $res;
    }

?>
