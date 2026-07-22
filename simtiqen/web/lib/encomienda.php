<?php

class Encomienda{
 var $idEncomienda;
 var $bitacora;
 var $fecha;
 var $fechadi;
 var $monto;
 var $viaje;
 var $estacion;
 var $producto;
 var $cierre;
 var $estado;
 var $parada;
 var $usuario;
 var $cliente;
 var $impresora;
 var $factura;
 var $peso;
 var $remitente;
 var $destinatario;
 var $cedremi;
 var $ceddesti;
 var $emailremi;
 var $telremi;
 var $teldesti1;
 var $teldesti2;
 var $nota;
 var $cantbul;
 var $declarado;
 var $esmanual;
 var $retirada;
 var $autocedula;
 var $autonombre;
 var $idautoriza;
 var $formapago;
 var $idtipotrack;
 var $fecharetiro;
 var $estaretiro;
 var $serialretiro;
 var $entcierre;
 var $entimpresora;
 var $entusuario;
 var $detprod;
 var $facturafe;
 var $clavefe;
 var $numeronc;
 var $facturancfe;
 var $clavencfe;
 var $fechanc;
 var $tipocedula;
 var $provincia;
 var $canton;
 var $distrito;
 var $barrio;
 var $otrassenas;
 var $ubicacion;
 var $razon;
 var $iva;
 var $fechafe;
 var $tipocedfa;
 var $cedulafa;
 var $nombrefa;
 var $correofa;
 var $actividad;

    //Contructor
 function __construct(){
   $this->Limpiar();
 }

//Limpia las propiedades    
 function Limpiar() {
   $this->idEncomienda=0;
   $this->fecha=date("Y-m-d H:i:s");
   $this->viaje = new Viaje();
   $this->estacion = new Estacion();
   $this->producto = new Producto();
   $this->formapago = new FormaPago();
   $this->cierre = new Cierred();
   $this->estado=0;
   $this->parada= new Parada();
   $this->usuario = new Usuario();
   $this->cliente = new Cliente();
   $this->factura="";
   $this->impresora = new Impresora();
   $this->peso=0;
   $this->remitente="";
   $this->destinatario="";
   $this->cedremi="";
   $this->ceddesti="";
   $this->telremi="";
   $this->teldesti1="";
   $this->teldesti2="";
   $this->nota="";
   $this->cantbul=0;
   $this->declarado=0;
   $this->emailremi="";
   $this->esmanual=0;
   $this->retirada = 0;
   $this->autocedula = "";
   $this->autonombre = "";
   $this->idautoriza = 0;
   $this->idtipotrack = 0;
   $this->estaretiro =0;
   $this->serialretiro="";
   $this->entcierre =  new Cierred();
   $this->entimpresora = new Impresora();
   $this->entusuario = new Usuario();
   $this->detprod ="";
   $this->facturafe="";
   $this->clavefe="";
   $this->numeronc="";
   $this->facturancfe="";
   $this->clavencfe="";
   $this->fechanc="1900-01-01";
   $this->tipocedula='00';
  $this->provincia="1";
  $this->canton="01";
  $this->distrito="01";
  $this->barrio="01";
  $this->otrassenas="";
  $this->ubicacion="";
  $this->razon="";
  $this->iva=0;
  $this->fechafe="1900-01-01";
  $this->tipocedfa='00';
  $this->cedulafa='';
  $this->nombrefa='';
  $this->correofa='';
  $this->actividad='';
 }
function asignafe($link){
  $this->impresora->Cargar();
  $par=new Parametro(); 
  $nomcia = $par->Retorna("nombreciae",3);
  $this->factura=str_pad($this->factura, 7, "0", STR_PAD_LEFT);
  if($this->tipocedfa=='00'){
    $confe =  $siguiente= $this->impresora->SiguienteConsecutivoTI( $link);
    $tipfe = '04';
  }
  else{
    $confe =  $siguiente= $this->impresora->SiguienteConsecutivoFA( $link);
    $tipfe = '01';
  }
  $lfacturafe=str_pad($confe, 7, "0", STR_PAD_LEFT);
  $ced=str_replace("-", "", $par->Retorna("cedulae",3));
  $nimp = $this->impresora->terminal;
  $numfa = $this->impresora->sucursal.$nimp.$tipfe.str_pad($lfacturafe,10,"0", STR_PAD_LEFT);
  $fecha = str_replace(" ", "T", $this->fechadi).".999" ;
  $nram = str_pad($this->factura,8,"0",STR_PAD_LEFT);
  $clave ="506".Date("dm").substr(Date("Y"), 2,2)."00".$ced.$numfa.'1'.$nram;
  $this->clavefe = $clave;
  $this->facturafe= $numfa;
  $sql="update encomienda 
    set facturafe='".$this->facturafe."'
    , clavefe='".$this->clavefe."'
    , fechafe=now()  
    where idEncomienda=".$this->idEncomienda;
  $link->bdEjecutar($sql);
}
//Guarda los datos , recibe como paremetro la transacci'on si existe 
 function Guardar($link){
  $this->Validar();
  $bit='';
  if($link==null){
   $link =new tiqmysql();
   $link->bdAbreTran();  
   $cerrartran=true;
 }
 else 
  $cerrartran=false;
    //aqui guarda los datos del cliente
  if($this->cliente->idCliente==1 && strlen($this->cedremi)>0)
            $this->cliente->idCliente=0; //esto es para que lo cree como nuevo
  $this->cliente->HeredaBitacora($this->bitacora);            
  $this->cliente->CargaCedula($this->cedremi);
  $this->cliente->cedula=$this->cedremi;
  $this->cliente->nombre = $this->remitente;
  $this->cliente->telefono = $this->telremi;
  $this->cliente->email = $this->emailremi;
	$this->cliente->provincia = $this->provincia;
  $this->cliente->canton = $this->canton;
  $this->cliente->distrito = $this->distrito;
  $this->cliente->barrio = $this->barrio;
  $this->cliente->otrasSenas = $this->otrassenas;
  $this->cliente->actividad = $this->actividad;
  if($this->cliente->idCliente!=1) //para que no est[e] updteando el cliente 1
        $this->cliente->Guardar($link);
  $nueva= ($this->idEncomienda==0);
  if($this->idEncomienda==0) {
        /// en caso de que sea una factura manual nada mas se env'ia el n'umero de factura
        if($this->factura=="" || $this->esmanual==0){
           $siguiente= $this->impresora->SiguienteConsecutivoe( $link);
           $this->factura=str_pad($siguiente,6,"0",STR_PAD_LEFT);
         }
         $sql =sprintf("insert into encomienda (fecha,idviaje,idestacion,
          idproducto,idcierre,estado,idparada,idusuario,idcliente,factura,peso,
          remitente,destinatario,cedremi,ceddesti,telremi,teldesti1,teldesti2,emailremi,
          nota,cantbul,declarado,fechadi,monto,idimpresora,esmanual
          ,idautoriza,autocedula,autonombre,retirada,idtipotrack,idformapago
          ,fecharetiro,estaretiro,entcierre,entimpresora,entusuario,detprod
          ,facturafe,clavefe
          ,tipocedula,provincia,canton,distrito,barrio,otrassenas,iva
          ,tipocedfa,cedulafa,nombrefa,correofa,actividad)
          value ('%s',%d,%d,%d,%d,%d,%d,%d,%d,'%s',
          %d,'%s','%s','%s','%s','%s','%s','%s','%s','%s',%d,%d,'%s',%d,%d,%d,%d,'%s','%s',%d,%d,%d,'1900-01-01',%d,%d,%d,%d,'%s','%s','%s','%s','%s','%s','%s','%s','%s',%f,'%s','%s','%s','%s','%s')",
          $this->fecha,
          $this->viaje->idViaje,
          $this->estacion->idEstacion,
          $this->producto->idProducto,
          $this->cierre->idCierre,
          $this->estado,
          $this->parada->idParada,
          $this->usuario->idUsuario,
          $this->cliente->idCliente,
          $this->factura,
          $this->peso,
          $this->remitente,
          $this->destinatario,
          $this->cedremi,
          $this->ceddesti,
          $this->telremi,
          $this->teldesti1,
          $this->teldesti2,
          $this->emailremi,
          $this->nota,
          $this->cantbul,
          $this->declarado,
          date("Y-m-d H:i:s"),
          $this->monto,
          $this->impresora->idImpresora,
          $this->esmanual,
          $this->idautoriza,
          $this->autocedula,
          $this->autonombre,
          $this->retirada,
          $this->idtipotrack,
          $this->formapago->idFormaPago,
          $this->estaretiro,
          $this->entcierre->idCierre,
          $this->entimpresora->idImpresora,
          $this->entusuario->idUsuario,
          $this->detprod,
          $this->facturafe,
          $this->clavefe,
          $this->tipocedula,
            $this->provincia,
            $this->canton,
            $this->distrito,
            $this->barrio,
            $this->otrassenas,
         $this->iva,
         $this->tipocedfa,
         $this->cedulafa,
         $this->nombrefa,
         $this->correofa,
         $this->actividad
          );
         $bit= $bit . "Crea Encomienda " . $this->idEncomienda . " " . $this->factura;
       }
       else {
         $sql=sprintf("update encomienda set 
           fecha ='%s',
           idviaje=%d,
           idestacion=%d,
           idproducto=%d,
           idcierre=%d,
           estado=%d,
           idparada=%d,
           idusuario=%d,
           idcliente=%d,
           factura='%s',
           peso=%d,
           remitente='%s',
           destinatario='%s',
           cedremi='%s',
           ceddesti='%s',
           telremi='%s',
           teldesti1='%s',
           teldesti2='%s',
           emailremi='%s',
           nota='%s',
           cantbul=%d,
           declarado=%d,
           monto=%d,
           idimpresora=%d, 
           esmanual=%d,
           idautoriza=%d,
           autocedula='%s',
           autonombre='%s',
           retirada=%d, 
           idtipotrack=%d,  
           idformapago=%d,
           fecharetiro='%s',
           estaretiro = %d  ,
           entcierre = %d ,
           entimpresora = %d,
           entusuario = %d,
           detprod = '%s',
           razon = '%s',
           iva = %f,
           tipocedfa='%s',
           cedulafa='%s',
           nombrefa='%s',
           correofa='%s',
           actividad='%s'
           where idEncomienda=%d",
           $this->fecha,
           $this->viaje->idViaje,
           $this->estacion->idEstacion,
           $this->producto->idProducto,
           $this->cierre->idCierre,
           $this->estado,
           $this->parada->idParada,
           $this->usuario->idUsuario,
           $this->cliente->idCliente,
           $this->factura,
           $this->peso,
           $this->remitente,
           $this->destinatario,
           $this->cedremi,
           $this->ceddesti,
           $this->telremi,
           $this->teldesti1,
           $this->teldesti2,
           $this->emailremi,
           $this->nota,
           $this->cantbul,
           $this->declarado,
           $this->monto,
           $this->impresora->idImpresora,
           $this->esmanual,
           $this->idautoriza,
           $this->autocedula,
           $this->autonombre,
           $this->retirada,
           $this->idtipotrack,
           $this->formapago->idFormaPago,
           $this->fecharetiro,
           $this->estaretiro,
           $this->entcierre->idCierre,
           $this->entimpresora->idImpresora,
           $this->entusuario->idUsuario,
           $this->detprod,
           $this->razon,
           $this->iva,
           $this->tipocedfa,
           $this->cedulafa,
           $this->nombrefa,
           $this->correofa,
           $this->actividad,
           $this->idEncomienda);
         $bit.= "Actualiza Encomienda " . $this->idEncomienda . " " . $this->factura; 
       }

       $res = $link->bdEjecutar($sql);
        if($nueva){
          $this->idEncomienda=$link->bdUltimoId();
          
          
        }
      if($this->formapago->idFormaPago!=3 && $this->facturafe=="")
        $this->asignafe($link);

      $link->bdCierraTran();
      $this->bitacora->obPadre = $this;
      $this->bitacora->Bitacorizar($bit);
      return $this->idEncomienda;
    }
    
    //Carga los datos de la ruta 
    function Cargar(){
      $sql = sprintf("select * from encomienda where idEncomienda=%d",$this->idEncomienda);
      $link = new tiqmysql();
      $res = $link->bdEjecutar($sql);
      if($link->bdCantLineas($res)>0){
       $linea = mysqli_fetch_array($res);    
       $this->fecha=$linea["fecha"];
       $this->fechadi=$linea["fechadi"];
       $this->viaje->idViaje=$linea["idviaje"];
       $this->estacion->idEstacion=$linea["idestacion"];
       $this->producto->idProducto=$linea["idproducto"];
       $this->cierre->idCierre=$linea["idcierre"];
       $this->estado=$linea["estado"];
       $this->parada->idParada=$linea["idparada"];
       $this->usuario->idUsuario=$linea["idusuario"];
       $this->cliente->idCliente=$linea["idcliente"];
       $this->factura=$linea["factura"];
       $this->impresora->idImpresora= $linea["idimpresora"];
       $this->peso=$linea["peso"];
       $this->remitente=$linea["remitente"];
       $this->destinatario=$linea["destinatario"];
       $this->cedremi=$linea["cedremi"];
       $this->ceddesti=$linea["ceddesti"];
       $this->telremi=$linea["telremi"];
       $this->teldesti1=$linea["teldesti1"];
       $this->teldesti2=$linea["teldesti2"];
       $this->emailremi=$linea["emailremi"];
       $this->nota=$linea["nota"];
       $this->cantbul=$linea["cantbul"];
       $this->declarado=$linea["declarado"];
       $this->monto=$linea["monto"];
       $this->retirada = $linea["retirada"];
       $this->autocedula = $linea["autocedula"];
       $this->autonombre = $linea["autonombre"];
       $this->idautoriza = $linea["idautoriza"];
       $this->idtipotrack = $linea["idtipotrack"];
       $this->formapago->idFormaPago = $linea["idformapago"];
       $this->fecharetiro = $linea["fecharetiro"];
       $this->estaretiro = $linea["estaretiro"];
       $this->serialretiro = $linea["serialretiro"];
       $this->entcierre->idCierre = $linea["entcierre"];
       $this->entimpresora->idImpresora=$linea["entimpresora"];
       $this->entusuario->idUsuario = $linea["entusuario"];
       $this->detprod = $linea["detprod"];
       $this->facturafe = $linea["facturafe"];
       $this->clavefe = $linea["clavefe"];
       $this->numeronc = $linea["numeronc"];
       $this->facturancfe = $linea["facturancfe"];
       $this->clavencfe = $linea["clavencfe"];
       $this->fechanc = $linea["fechanc"];
       $this->tipocedula = $linea["tipocedula"];
       $this->provincia = $linea["provincia"];
       $this->canton = $linea["canton"];
       $this->distrito = $linea["distrito"];
       $this->otrassenas = $linea["otrassenas"];
       $this->ubicacion = $linea["ubicacion"];
       $this->iva = $linea["iva"];
       $this->fechafe = $linea["fechafe"];
       $this->tipocedfa = $linea["tipocedfa"];
       $this->cedulafa =  $linea["cedulafa"];
       $this->nombrefa =  $linea["nombrefa"];
       $this->correofa = $linea["correofa"];
       $this->actividad = $linea["actividad"];
       return true;
     }
     else
      return false;
  }
  
    //Valida los campos  
  function Validar() {
    $errores="";
    if(trim($this->fecha)=="")
      $errores.="La fecha no puede quedar en blanco \n";
    if(!$this->viaje->Cargar())
      $errores.="El viaje que escogió no existe ".$this->viaje->idViaje;
    if($this->idEncomienda==0){
      if($this->viaje->liqenco == "1")
        $errores.="El viaje que escogió ya fue liquidado ";
    }

    if(!$this->estacion->Cargar())
      $errores.="La estación que escogió no existe";
    if(!$this->producto->Cargar())
      $errores.="El producto que escogió no existe [".$this->producto->idProducto."]";
    if(!$this->cierre->Cargar())
      $errores.="El cierre que escogió no existe";
    if(!$this->parada->Cargar())
      $errores.="La parada de salida que escogió no existe";
    if(!$this->usuario->Cargar())
      $errores.="El usuario que escogió no existe " . $this->usuario->idUsuario;
    if(!$this->impresora->Cargar())
      $errores.="La impresora que escogió no existe";
    if(!$this->formapago->Cargar())
      $errores.="La forma de pago que escogió no existe";

    if(strlen($this->factura)==0 && $this->idEncomienda>0)
      $errores.="No puede dejar el número de factura en blanco";

          /*if(strlen($this->cedremi)==0)
          $errores.=" No puede dejar la cédula del remitente en blanco" ;*/

          /*if(strlen($this->ceddesti)==0)
          $errores.=" No puede dejar la cédula del destinatario en blanco" ;*/

          if(strlen($this->remitente)==0)
           $errores.=" No puede dejar el nombre del remitente en blanco" ;

         if(strlen($this->destinatario)==0)
           $errores.=" No puede dejar el nombre del destinatario en blanco" ;

          /*if(strlen($this->teldesti1)==0)
          $errores.=" No puede dejar el teléfono del destintario en blanco" ;*/
          
          if($this->formapago->idFormaPago==2){
            $empre = new Empresa();
            $empre->CargarCedula($this->cedremi);
            if($empre->credito!=1)
              $errores .= "La empresa ".$empre->nombre." no tiene credito";
          }

          if($errores!="")    
           throw new SimError($errores);

       }
//Borra la ruta
       function Borrar(){
        if($this->cierre->idCierre>0)
          throw new SimError("La encomienda ya pertenece a un cierre no se puede eliminar");

        $sql=sprintf("delete from encomienda where idencomienda=%d",$this->idEncomienda);
        $link = new tiqmysql();
        $res = $link->bdEjecutar($sql);
        $this->bitacora->Bitacorizar("Elimina Encomienda " . $this->factura);
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
       $cad = "ID " . $this->idEncomienda  . "/n" .
       "Fecha: " . $this->fecha . "/n" .
       "Viaje: " . $this->viaje->ToString() . "/n".
       "Estación de emisión: ". $this->estacion->ToString() . "/n".
       "Tipo de encomienda: " . $this->producto->ToString() . "/n".
       "Cierre: " . $this->cierre->ToString() . "/n" .
       "Parada destino:  " . $this->parada->ToString()."/n".
       "Usuario que creó el encomienda: " . $this->usuario->ToString()."/n".
       "Cliente estadística: " . $this->cliente->ToString()."/n" .
       "Factura : " . $this->factura . "/n".
       "Peso: " . $this->peso . "/n" .
       "Remitente: " . $this->remitente . " /n" .
       "Destinatario: " . $this->destinatario . "/n" .
       "Cédula remitente: " . $this->cedremi ."/n" .
       "Cédula destinatario: " . $this->ceddesti . "/n" .
       "Teléfono remitente: " . $this->telremi . "/n" . 
       "Teléfono1 destinatario: " . $this->teldesti1 . "/n".
       "Teléfono2 destinatario: " . $this->teldesti2 . "/n" .
       "Notas: " . $this->nota. " /n" .
       "Cantidad de bultos: ". $this->cantbul ."/n".
       "Forma de Pago: " . $this->formapago->desFormaPago . " /n" .
       "Monto Declarado: " . $this->declarado. " /n" ;
       if($this->estado==0)
        $cad.="Estado: normal";
      else
        $cad.="Estado: nulo";
      if($this->retirada==1){
        $cad.= "Retirada por: ";
        $cad.= $this->autocedula . " ";
        $cad.= $this->autonombre . " /n" ;
      }
      return $cad;
    } 
   /// Anular encomienda

    function Anular($prazon){
     $this->Cargar();
     /*
     if($this->cierre->idCierre>0)
       throw new SimError("No se puede anular este encomienda porque pertenece a un cierre");
     $this->viaje->Cargar();
     if($this->viaje->liqenco == "1")
      throw new SimError("El viaje de esta encomienda ya fue liquidado ");
    $this->estado=1;
    */
    if($this->cierre->idCierre==0)
      $this->estado=1;
    $this->razon=$prazon;
    $res=$this->Guardar(null);
    if($res)
      $this->bitacora->Bitacorizar("Se anula Encomienda ".$this->factura ." motivo: ".$prazon);
    if($res){
  $this->bitacora->Bitacorizar("Se anula Encomienda ".$this->factura ." motivo: ".$prazon);
   ///parte de nota de credito fe
      $link = new tiqmysql();
      $siguiente= $this->impresora->SiguienteConsecutivoNC( $link);
      $this->numeronc=str_pad($siguiente,7,"0",STR_PAD_LEFT);
      $this->fechanc=Date('Y-m-d H:i:s');
      if($this->facturafe!=''){
        $this->impresora->Cargar();
        $par=new Parametro(); 
        $nomcia = $par->Retorna("nombreciae",3);
        $ced=str_replace("-", "", $par->Retorna("cedulae",3));
        $nimp = $this->impresora->terminal;
        $numfa = $this->impresora->sucursal.$nimp."03".str_pad($this->numeronc,10,"0", STR_PAD_LEFT);
        $fecha = str_replace(" ", "T", $this->fechanc).".999" ;
        $nram = str_pad($this->idEncomienda,8,"0",STR_PAD_LEFT);
        $clave ="506".Date("dm").substr(Date("Y"), 2,2)."00".$ced.$numfa.'1'.$nram;
        $this->clavencfe = $clave;
        $this->facturancfe= $numfa;
        $sql="update encomienda set facturancfe='".$this->facturancfe."'
        , clavencfe='".$this->clavencfe."'
        , numeronc='".$this->numeronc."'
        , fechanc=now() 
        where idEncomienda=".$this->idEncomienda;
        $link->bdEjecutar($sql);
      }
}
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
  $sql="select e.idencomienda,e.factura,e.idparada,e.peso
  ,e.remitente,e.destinatario,e.cedremi,e.ceddesti
  ,e.monto,e.fecha,e.fecha,e.idviaje,e.nota,e.cantbul
  ,e.cantbul,e.estado,e.idcierre,e.declarado,e.telremi
  ,e.teldesti1,e.teldesti2,e.idcliente,e.idestacion
  ,e.idproducto,e.idimpresora,e.emailremi,e.fecharetiro,e.autonombre,e.autocedula
  ,p.nombre as parada, r.nombre as ruta
  ,x.hora as hora,c.nombre as cliente,v.fecha as fechaviaje
  ,o.nombre as estacion
  ,d.nombre as producto
  ,i.nombre as impresora
  ,u.nombre as usuario
  ,e.estaretiro
  ,e.entcierre
  ,e.entimpresora
  ,e.entusuario
  ,e.detprod
  ,e.tipocedula
  ,e.provincia
  ,e.canton
  ,e.distrito
  ,e.barrio
  ,e.otrassenas
  ,e.iva
  from encomienda e 
  inner join parada p
  on e.idparada=p.idparada
  inner join viaje v
  on e.idviaje=v.idviaje
  inner join ruta r
  on v.idruta = r.idruta 
  inner join viajexesta x
  on v.idviaje=x.idviaje 
  inner join cliente c
  on e.idcliente=c.idcliente
  inner join estacion o
  on e.idestacion=o.idestacion
  inner join producto d
  on e.idproducto = d.idproducto
  inner join impresora i
  on e.idimpresora=i.idimpresora
  inner join usuario u
  on e.idusuario=u.idusuario
  where e.idestacion=x.idestacion
  ".$tira. " order by v.fecha desc,x.hora desc ,e.factura desc " ;
  return $link->bdEjecutar($sql);
} 

function idxFactura($pimpresora,$pfactura){
  $link = new tiqmysql();
  $sql =sprintf("select idencomienda from encomienda 
   where idimpresora=%d and factura='%s'",$pimpresora,$pfactura);
  return $link->PrimerValorD($sql);

}

function Buscar($txtfactura,$txtremi,$txtcedr,$txtdesti,$txtcedd,$txtfecha,$txtfechaf){
 $tira = "";
    //$tira .= "txtfactura: " . $txtfactura . "<br/>";
    //$tira .= "txtremi: " . $txtremi . "<br/>";
    //$tira .= "txtcedr: " . $txtcedr . "<br/>";
    //$tira .= "txtdesti: " . $txtdesti . "<br/>";
    //$tira .= "txtcedd: " . $txtcedd . "<br/>";
    //$tira .= "txtfecha: " . $txtfecha . "<br/>";
    //$donde[] = "";
 $titulo = "B&uacute;squeda: ";
 if (strlen(ltrim(rtrim($txtfactura))) > 0){
   $donde[] = " e.factura = '" . ltrim(rtrim($txtfactura)) . "' ";
   $titulo .= " Número de Encomienda = <b>" . ltrim(rtrim($txtfactura)) . "</b>,";
 }
 if (strlen(ltrim(rtrim($txtremi))) > 0){
   $donde[] = " e.remitente like '%" . ltrim(rtrim($txtremi)) . "%' ";
   $titulo .= " Remitente = <b>" . ltrim(rtrim($txtremi)) . "</b>,";
 }
 if (strlen(ltrim(rtrim($txtcedr))) > 0){
   $donde[] = " e.cedremi like '%" . ltrim(rtrim($txtcedr)) . "%' ";
   $titulo .= " Cédula del Remitente = <b>" . ltrim(rtrim($txtcedr)) . "</b>,";
 }
 if (strlen(ltrim(rtrim($txtdesti))) > 0){
   $donde[] = " e.destinatario like '%" . ltrim(rtrim($txtdesti)) . "%' ";
   $titulo .= " Destinatario = <b>" . ltrim(rtrim($txtdesti)) . "</b>,";
 }
 if (strlen(ltrim(rtrim($txtcedd))) > 0){
   $donde[] = " e.ceddesti like '%" . ltrim(rtrim($txtcedd)) . "%' ";
   $titulo .= " Cédula del Destinatario = <b>" . ltrim(rtrim($txtcedd)) . "</b>,";
 }
 if (strlen(ltrim(rtrim($txtfecha))) > 0){
   $fechaUtc = fechatoUTC($txtfecha);
   $fecha2Utc = fechatoUTC($txtfechaf);
      //$tira .= "FechaUTC: " . $fechaUtc . "<br/>";
   $donde[] = " e.fecha >= '" . $fechaUtc . "' and e.fecha <= '" . $fecha2Utc . "' ";
   $titulo .= " Entre <b>" . ltrim(rtrim($txtfecha)) . " y " . ltrim(rtrim($txtfechaf)) . "</b>,";
 }
 else{
   if (strlen(ltrim(rtrim($txtfecha))) > 0){
    $fechaUtc = fechatoUTC($txtfecha);
        //$tira .= "FechaUTC: " . $fechaUtc . "<br/>";
    $donde[] = " e.fecha = '" . $fechaUtc . "' ";
    $titulo .= " Fecha = <b>" . ltrim(rtrim($txtfecha)) . "</b>,";
  }
}
    //$tira .= $donde + "<br />";
$titulo = "<h3 align='center'>" . substr($titulo, 0, -1) . "</h3>";
return $tira . $this->devuelvetabla($donde,$titulo);
}

function Excel($txtremi,$txtcedr,$txtfecha,$txtfechaf,$formato){
 $tira = "";
    //$tira .= "txtfactura: " . $txtfactura . "<br/>";
    //$tira .= "txtremi: " . $txtremi . "<br/>";
    //$tira .= "txtcedr: " . $txtcedr . "<br/>";
    //$tira .= "txtdesti: " . $txtdesti . "<br/>";
    //$tira .= "txtcedd: " . $txtcedd . "<br/>";
    //$tira .= "txtfecha: " . $txtfecha . "<br/>";
    //$donde[] = "";
 $titulo = "B&uacute;squeda: ";
 if (strlen(ltrim(rtrim($txtremi))) > 0){
   $donde[] = " e.remitente like '%" . ltrim(rtrim($txtremi)) . "%' ";
   $titulo .= " Remitente = <b>" . ltrim(rtrim($txtremi)) . "</b>,";
 }
 if (strlen(ltrim(rtrim($txtcedr))) > 0){
   $donde[] = " e.cedremi like '%" . ltrim(rtrim($txtcedr)) . "%' ";
   $titulo .= " Cédula del Remitente = <b>" . ltrim(rtrim($txtcedr)) . "</b>,";
 }
 if (strlen(ltrim(rtrim($txtfechaf))) > 0){
   $fechaUtc = fechatoUTC($txtfecha);
   $fecha2Utc = fechatoUTC($txtfechaf);
      //$tira .= "FechaUTC: " . $fechaUtc . "<br/>";
   $donde[] = " e.fecha >= '" . $fechaUtc . "' and e.fecha <= '" . $fecha2Utc . "' ";
   $titulo .= " Entre <b>" . ltrim(rtrim($txtfecha)) . " y " . ltrim(rtrim($txtfechaf)) . "</b>,";
 }
 else{
   if (strlen(ltrim(rtrim($txtfecha))) > 0){
    $fechaUtc = fechatoUTC($txtfecha);
        //$tira .= "FechaUTC: " . $fechaUtc . "<br/>";
    $donde[] = " e.fecha = '" . $fechaUtc . "' ";
    $titulo .= " Fecha = <b>" . ltrim(rtrim($txtfecha)) . "</b>,";
  }
}
    //$tira .= $donde + "<br />";
$titulo = "<h3 align='center'>" . substr($titulo, 0, -1) . "</h3>";
if ($formato==1){
 return $tira . $this->ExcelResumido($donde,$titulo);
}else{
 return $tira . $this->devuelvetabla($donde,$titulo);
}
}

function devuelvetabla($donde,$titulo){
  $res =  $this->ListarActivos($donde);
  $tabla = $titulo . "No existen datos para listar";
  $primero = 1;
  $lasventas = 0;
  $lostiq = 0;
  if(mysqli_num_rows($res)>0){
   $tabla = $titulo;
   $tabla .= "<table border='1' class='centrado' style='border-collapse:collapse'> ";
   $tabla .= "<tr> ";
   $tabla .= "<th class='encabTabla' scope='col'>Ref.</th> ";
   $tabla .= "<th class='encabTabla' scope='col'>Factura</th> ";
   $tabla .= "<th class='encabTabla' scope='col'>Fecha</th> ";
   $tabla .= "<th class='encabTabla' scope='col'>Remitente</th> ";
   $tabla .= "<th class='encabTabla' scope='col'>Destinatario</th> ";
   $tabla .= "<th class='encabTabla' scope='col'>Parada</th> ";
   $tabla .= "<th class='encabTabla' scope='col'>Ruta</th> ";
   $tabla .= "<th class='encabTabla' scope='col'>Viaje</th> ";
   $tabla .= "<th class='encabTabla' scope='col'>Estación</th> ";
   $tabla .= "<th class='encabTabla' scope='col'>Producto</th> ";
   $tabla .= "<th class='encabTabla' scope='col'>Monto</th> ";
   $tabla .= "</tr> ";
   while($linea =  mysqli_fetch_array($res)){
     $tabla .= "<tr> ";
     $tabla .= "<td><a href='buscarenco.php?id=" . $linea["idencomienda"] . "'>" .  $linea["idencomienda"] . "</a></td> ";
     $tabla .= "<td>" . $linea["factura"] . "</td> ";
     $tabla .= "<td>" . date_format(new DateTime($linea["fecha"]), 'd/m/Y') . "</td> ";
     $tabla .= "<td>" . $linea["remitente"] . "</td> ";
     $tabla .= "<td>" . $linea["destinatario"] . "</td> ";
     $tabla .= "<td>" . $linea["parada"] . "</td> ";
     $tabla .= "<td>" . $linea["ruta"] . "</td> ";
     $tabla .= "<td>" . date_format(new DateTime($linea["fechaviaje"]), 'd/m/Y') . " " . $linea["hora"] . "</td> ";
     $tabla .= "<td>" . $linea["estacion"] . "</td> ";
     $tabla .= "<td>" . $linea["producto"] . "</td> ";
     $tabla .= "<td class='izq' style=\"mso-number-format:'#,##0.00';\">" . number_format($linea["monto"], 2, ".", ",") . "</td> ";
     $tabla .= "</tr> ";
   }
   $tabla .= "</table>";
 }
 return $tabla;
}

function HTML($elId){
  $this->idEncomienda = $elId;
  $this->Cargar();
  $this->estacion->cargar(); 
  $this->producto->cargar();
  $this->parada->cargar();
  $this->usuario->cargar();
  $this->cliente->cargar();
  $this->formapago->Cargar();
  $this->viaje->cargar();
  $cad = "<table border='1'>";
  $cad .= "<tr><td>Ubicación </td><td>" . $this->ubicacion  . "</td></tr>";
  $cad .= "<tr><td>Estado </td><td>" . ($this->retirada==1?'Retirada':'Pendiente de retiro')  . "</td></tr>";
  $cad .= "<tr><td>Factura </td><td>" . $this->factura . "</td></tr>";
  $cad .= "<tr><td>ID </td><td>" . $this->idEncomienda  . "</td></tr>";
  $cad .= "<tr><td>Factura </td><td>" . $this->factura . "</td></tr>";
  $cad .= "<tr><td>Fecha </td><td>" . date_format(new DateTime($this->fecha), 'd/m/Y') . "</td></tr>";
  $cad .= "<tr><td>Remitente </td><td>" . $this->remitente . " </td></tr>";
  $cad .= "<tr><td>Cédula remitente </td><td>" . $this->cedremi ."</td></tr>";
  $cad .= "<tr><td>Teléfono remitente </td><td>" . $this->telremi . "</td></tr>";
  $cad .= "<tr><td>Destinatario </td><td>" . $this->destinatario . "</td></tr>";
  $cad .= "<tr><td>Cédula destinatario </td><td>" . $this->ceddesti . "</td></tr>";
  $cad .= "<tr><td>Teléfono1 destinatario </td><td>" . $this->teldesti1 . "</td></tr>";
  $cad .= "<tr><td>Teléfono2 destinatario </td><td>" . $this->teldesti2 . "</td></tr>";
  $cad .= "<tr><td>Monto </td><td>" . number_format($this->monto, 2, ".", ",") . "</td></tr>";
  $cad .= "<tr><td>Forma de Pago </td><td>" . $this->formapago->desFormaPago . "</td></tr>";
  $cad .= "<tr><td>Viaje </td><td>" . date_format(new DateTime($this->viaje->fecha), 'd/m/Y') . " " . $this->viaje->horaini . "</td></tr>";
  $cad .= "<tr><td>Estación de emisión </td><td>". $this->estacion->nombre . "</td></tr>";
  $cad .= "<tr><td>Tipo de encomienda </td><td>" . $this->producto->nombre . "</td></tr>";
  $cad .= "<tr><td>Parada destino </td><td>" . $this->parada->nombre ."</td></tr>";
  $cad .= "<tr><td>Usuario que creó el encomienda </td><td>" . $this->usuario->nombre."</td></tr>";
  $cad .= "<tr><td>Cliente estadística </td><td>" . $this->cliente->nombre . "</td></tr>";
  $cad .= "<tr><td>Notas </td><td>" . $this->nota. " </td></tr>";
  if($this->producto->tipo!=3){
    $cad .= "<tr><td>Peso </td><td>" . $this->peso . "</td></tr>";
    $cad .= "<tr><td>Cantidad de bultos </td><td>". $this->cantbul ."</td></tr>";
    $cad .= "<tr><td>Monto Declarado </td><td>" . $this->declarado. " </td></tr>" ;
  }
  else
  {
   $cad .= "<tr><td>Monto Transferencia </td><td><h2>" . $this->declarado. "</h2> </td></tr>" ; 
  }
  $cad .= "<tr><td>Clave FE </td><td><a href=\"https://www.fe-cr.com/server/feadmin/publico/reenviar/" . $this->clavefe . "\" target=\"_blank\">" . $this->clavefe. "</a></td></tr>" ;
  $cad .= "<tr><td>Nota Cr&eacute;dito Electr&oacute;nica</td><td><a href=\"https://www.fe-cr.com/server/feadmin/publico/reenviar/" . $this->clavencfe . "\" target=\"_blank\">" . $this->clavencfe. "</a></td></tr>" ;
  if($this->estado==0)
   $cad.="<tr><td>Estado </td><td>Normal</td></tr>";
 else
   $cad.="<tr><td>Estado </td><td>Nula</td></tr>";
 if($this->retirada==1){
   $cad.= "<tr><td>Retirada por </td><td>";
   $cad.= $this->autocedula . " ";
   $cad.= $this->autonombre . "</td></tr>" ;
   $cad.= "<tr><td>Fecha Retiro</td><td>".
   date_format( new DateTime($this->fecharetiro),'d/m/Y H:m')."</td></tr>";
 }
 $cad .= "<tr><td colspan='2'><div style='width:200px'>".$this->DevuelveFirma()."</div></td></tr>";
 $cad.="</table>";
 $cad.=$this->Tracking($elId);
 return $cad;
} 

function HTMLCorto($elId){
  $this->idEncomienda = $elId;
  $this->cargar();
  $this->estacion->cargar(); 
  $this->impresora->cargar();
  $this->producto->cargar();
  $this->parada->cargar();
  $this->usuario->cargar();
  $this->cliente->cargar();
  $this->viaje->cargar();
  $this->formapago->cargar();

  $cad = "<table border='1' colspan='0'>";
  $cad .= "<tr><td>Ubicación </td><td>" . $this->ubicacion . "</td></tr>";
   $cad .= "<tr><td>Estado </td><td>" . ($this->retirada==1?'Retirada':'Pendiente de retiro')  . "</td></tr>";
  $cad .= "<tr><td>Impresora </td><td>" . $this->impresora->nombre . "</td></tr>";
  $cad .= "<tr><td>Factura </td><td>" . $this->factura . "</td></tr>";
  $cad .= "<tr><td>Fecha </td><td>" . date_format(new DateTime($this->fecha), 'd/m/Y') . "</td></tr>";
  $cad .= "<tr><td>Forma de Pago </td><td>" . $this->formapago->desFormaPago . "</td></tr>";
  $cad .= "<tr><td>Tipo de encomienda </td><td>" . $this->producto->nombre . "</td></tr>";
  $cad .= "<tr><td>Remitente </td><td>" . $this->remitente . " </td></tr>";
  $cad .= "<tr><td>Destinatario </td><td>" . $this->destinatario . "</td></tr>";
  $cad .= "<tr><td>Monto</td><td>".number_format($this->monto)."</td></tr>";
  $cad .= "<tr><td>Bultos</td><td>".$this->cantbul."</td></tr>";
  $cad .= "<tr><td>Fecha del viaje </td><td>" . fechatoNormal($this->viaje->fecha) . " " . $this->viaje->horaini . "</td></tr>";
  $cad .= "<tr><td>Parada destino </td><td>" . $this->parada->nombre ."</td></tr>";
  $cad .= "<tr><td>Notas </td><td>" . $this->nota ."</td></tr>";
  
  if($this->producto->tipo==3){
    $cad .= "<tr><td>Transferencia </td><td  style='text-align:right;padding:5px;'><h2>" . 
      number_format($this->declarado) .
      "</h2></td></tr>";
  }
  $cad .= "<tr><td colspan='2'><div style='width:200px'>".$this->DevuelveFirma()."</div></td></tr>";
  $cad.="</table>";
  return $cad;
} 

function ExcelResumido($filtro,$titulo){
 $link=new tiqmysql();
 $tira = "";
 $primero = 1;
 if ($filtro == null){
   $tira = "";
 }else{
   for($i = 0;$i<count($filtro);$i++){ 
    $valor = $filtro[$i];
    if ($primero==1){
     $tira .= " where " . $valor;
     $primero = 0;
   }else{
     $tira .= " and " . $valor;
   }
 }
}
$sql = "select cedula,nombre,sum(monto) as monto from (select e.monto,ifnull(ifnull(f.cedulasic,j.cedulasic),e.cedremi) as cedula, ";
$sql .= "ifnull(ifnull(f.nombre,j.nombre),e.remitente) as nombre ";
$sql .= "from encomienda e  ";
$sql .= "left join cedfissic f on e.cedremi = f.cedula ";
$sql .= "left join cedjursic j on e.cedremi = j.cedula ";
$sql .= $tira." ) a group by cedula,nombre order by cedula,nombre";
$res =  $link->bdEjecutar($sql);
$tabla = $titulo . "No existen datos para listar";
$primero = 1;
$lasventas = 0;
$lostiq = 0;
if(mysqli_num_rows($res)>0){
 $tabla = $titulo;
 $tabla .= $sql;
 $tabla = "<table border='1' class='centrado' style='border-collapse:collapse'> ";
 $tabla .= "<tr> ";
 $tabla .= "<th class='encabTabla' scope='col'>Cedula</th> ";
 $tabla .= "<th class='encabTabla' scope='col'>Nombre</th> ";
 $tabla .= "<th class='encabTabla' scope='col'>Monto</th> ";
 $tabla .= "</tr> ";
 while($linea =  mysqli_fetch_array($res)){
   $tabla .= "<tr> ";
   $tabla .= "<td>" . $linea["cedula"] . "</td> ";
   $tabla .= "<td>" . $linea["nombre"] . "</td> ";
   $tabla .= "<td class='izq' style=\"mso-number-format:'#,##0.00';\">" . number_format($linea["monto"], 2, ".", ",") . "</td> ";
   $tabla .= "</tr> ";
 }
 $tabla .= "</table>";
}
return $tabla;
}

function AgregaTracking($pIdTipoTrack){
 $this->bitacora = NuevaBitacora();
 $link =new tiqmysql();
 $this->idtipotrack = $pIdTipoTrack;
 $link =new tiqmysql();
 $link->bdAbreTran();  
 $sql =sprintf("insert into trackenco(fecha,idtipotrack,idencomienda,idviaje,idestacion,idparada)
   values ('%s',%d,%d,%d,%d,%d)",
   date("Y-m-d H:i:s"),
   $pIdTipoTrack,
   $this->idEncomienda,
   $this->viaje->idViaje,
   $this->estacion->idEstacion,
   $this->parada->idParada
   );
 $res = $link->bdEjecutar($sql);
 $res = $this->Guardar($link);
 $link->bdCierraTran();
 return $res;
}

function Retira($pCombo,$pCedula,$pNombre,$pestaretiro,$pimpresora){
 $this->bitacora = NuevaBitacora();
 $link =new tiqmysql();
 if ($pCombo!="0"){
   $this->idautoriza = $pCombo;
   $sql = "select nombre,cedula from autoriza where idautoriza = " . $pCombo;
   $res = $link->bdEjecutar($sql);
    if($link->bdCantLineas($res)>0){
      $linea = mysqli_fetch_array($res);    
      $pCedula=$linea["cedula"];
      $pNombre=$linea["nombre"];
    }
  }
  $this->retirada = 1;
  $this->autocedula = $pCedula;
  $this->autonombre = $pNombre;
  $this->fecharetiro = Date("Y-m-d H:i:s");
  $this->estaretiro = $pestaretiro;
  $this->entimpresora->idImpresora = $pimpresora;
  $this->entusuario->idUsuario = $this->bitacora->usuario->idUsuario;
  $res =  $this->Guardar($link);
  if($this->facturafe=='')
    $this->asignafe($link);
  return $res;
}

function GuardaFirma($datosFirma){
   $link =new tiqmysql();
   $sql="select idencomienda from encomienda where serialretiro='".$this->serialretiro."' and penfirma=1";

   $resx= $link->bdEjecutar($sql);

   while($linea =  mysqli_fetch_array($resx)){
      $sql = "insert into firma (idencomienda,tipo,datos,fecha) 
      values (".$linea["idencomienda"].",1,' ".$datosFirma." ',now()) ";
      $res = $link->bdEjecutar($sql);
      $sql = "update encomienda set penfirma=0 where idencomienda=".$linea["idencomienda"];
      $res = $link->bdEjecutar($sql);
      $this->bitacora->Bitacorizar("Guarda Firma Encomienda " . $linea["idencomienda"] );
    }
  }

function DevuelveFirma(){
 $link =new tiqmysql();
 $sql = "select datos from firma where idencomienda=".$this->idEncomienda." and tipo=1 ";
 $res = $link->bdEjecutar($sql);
 if($link->bdCantLineas($res)>0){
  $linea = mysqli_fetch_array($res);
  return $linea["datos"];
}
else
 return "";

}

function PonerColaFirma($lserial){
  $link =new tiqmysql();
  $sql = "update encomienda set penfirma=1, serialretiro='".$lserial."' where idencomienda=".$this->idEncomienda;
  $res = $link->bdEjecutar($sql);
  $this->bitacora->Bitacorizar("Pone en cola para firmar ".$this->idEncomienda);
}

function EncomiendasSinFirma($pesta){
 $link =new tiqmysql();
 $sql = " select e.idencomienda,e.factura,e.idparada,e.peso
 ,e.remitente,e.destinatario,e.cedremi,e.ceddesti
 , e.monto,e.fecha,e.fecha,e.idviaje,e.nota,e.cantbul
 ,e.cantbul,e.estado,e.idcierre,e.declarado,e.telremi
 ,e.teldesti1,e.teldesti2,e.idcliente,e.idestacion
 ,e.idproducto,e.idimpresora,e.emailremi
 from encomienda e 
 where penfirma=1  and e.estaretiro=".$pesta;

 $res = $link->bdEjecutar($sql);
 return $res;
}

function Tracking($elId){
  $this->idEncomienda = $elId;
  $this->cargar();
  $this->estacion->cargar(); 
  $this->producto->cargar();
  $this->parada->cargar();
  $this->usuario->cargar();
  $this->cliente->cargar();
  $this->viaje->cargar();
  $this->impresora->Cargar();
  $cad = "<table border='1'>";
  $cad .= "<tr><td>Ubicación </td><td>" . $this->ubicacion . "</td></tr>";
   $cad .= "<tr><td>Estado </td><td>" . ($this->retirada==1?'Retirada':'Pendiente de retiro')  . "</td></tr>";
  $cad .= "<tr><td>Impresora </td><td>" . $this->impresora->nombre . "</td></tr>";
  $cad .= "<tr><td>Factura </td><td>" . $this->factura . "</td></tr>";
  $cad .= "<tr><td>Fecha </td><td>" . date_format(new DateTime($this->fecha), 'd/m/Y') . "</td></tr>";
  $cad .= "<tr><td>Remitente </td><td>" . $this->remitente . " </td></tr>";
  $cad .= "<tr><td>Destinatario </td><td>" . $this->destinatario . "</td></tr>";
  $cad .= "<tr><td>Parada destino </td><td>" . $this->parada->nombre ."</td></tr>";
  $cad .= "<tr><td colspan='2'><div style='width:200px'>".$this->DevuelveFirma()."</div></td></tr>";
  $seg = "<table border='1'>";
  $seg .= "<tr><th>Estado</th><th>Estaci&oacute;n</th><th>Fecha</th></tr>";
  $seg .= "<tr><td>Recibido en Estaci&oacute;n</td><td>" . $this->estacion->nombre . "</td><td>" . fechatoNormal(substr($this->fechadi,0,10)) . " " . substr($this->fechadi,11) . "</td></tr>";
  $fechaevento = substr($this->viaje->fecha,0,10) . " " . $this->viaje->horaini . ":00";
  $fechaactual = Date("Y-m-d H:i:s");
  if ($fechaactual > $fechaevento) {
      //$seg .= "<tr><td>" . $fechaactual . " " . $fechaevento . " En tr&aacute;nsito a Estaci&oacute;n de Destino</td><td>" . $this->estacion->nombre . "</td><td>" . fechatoNormal($this->viaje->fecha) . " " . $this->viaje->horaini . "</td></tr>";
   $seg .= "<tr><td> En tr&aacute;nsito a Estaci&oacute;n de Destino</td><td>" . $this->estacion->nombre . "</td><td>" . fechatoNormal($this->viaje->fecha) . " " . $this->viaje->horaini . "</td></tr>";
 } else {
      //$seg .= "<tr><td>" . $fechaactual . " " . $fechaevento . " Preparando env&iacute;o a Estaci&oacute;n de Destino</td><td>" . $this->estacion->nombre . "</td><td>" . fechatoNormal($this->viaje->fecha) . " " . $this->viaje->horaini . "</td></tr>";
      //$seg .= "<tr><td>" . $fechaactual . " " . $fechaevento . " Listo para ser retirado en la Estaci&oacute;n de Destino</td><td>" . $this->estacion->nombre . "</td><td>" . fechatoNormal($this->viaje->fecha) . " " . $this->viaje->horaini . "</td></tr>";
   $seg .= "<tr><td>Preparando env&iacute;o a Estaci&oacute;n de Destino</td><td>" . $this->estacion->nombre . "</td><td>" . fechatoNormal($this->viaje->fecha) . " " . $this->viaje->horaini . "</td></tr>";
   $seg .= "<tr><td>Listo para ser retirado en la Estaci&oacute;n de Destino</td><td>" . $this->estacion->nombre . "</td><td>" . fechatoNormal($this->viaje->fecha) . " " . $this->viaje->horaini . "</td></tr>";
 }
 $est .= "En tr&aacute;nsito a Estaci&oacute;n de Destino";
 $link =new tiqmysql();
 $sql="select trackenco.idtipotrack,trackenco.idparada,trackenco.fecha,
 parada.nombre as parada, tipotrack.destipotrack
 from trackenco 
 inner join parada on parada.idparada = trackenco.idparada
 inner join tipotrack on tipotrack.idtipotrack = trackenco.idtipotrack
 where idencomienda = '" . $this->idEncomienda . "' order by trackenco.fecha,trackenco.idtipotrack " ;
 $res =  $link->bdEjecutar($sql);
 if(mysqli_num_rows($res)>0){
   while($linea =  mysqli_fetch_array($res)){
    $seg .= "<tr><td>" . $linea["destipotrack"] . "</td><td>" . $linea["parada"] . "</td><td>" . $linea["fecha"] . "</td></tr>";
    $est = $linea["destipotrack"];
  }
}


if ($this->retirada==1){
  $otraesta = new Estacion();
  $otraesta->idEstacion = $this->estaretiro;
  
  $otraesta->Cargar();
  
  $seg .= "<tr><td>Retirada</td><td>" . $otraesta->nombre . "</td><td>" . date_format( new DateTime($this->fecharetiro),'d/m/Y H:m') . "</td></tr>";
  $est = 'Retirada';
}




$seg.="</table>";
$cad .= "<tr><td>Estado Actual </td><td>" . $est ."</td></tr>";
$cad.="</table>";
$cad.=$seg;
return $cad;
}

function EncomiendasAbiertas($pdesde,$phasta){
  $link =new tiqmysql();
  $sql = " select e.idencomienda,e.factura,e.idparada,e.peso
  ,e.remitente,e.destinatario,e.cedremi,e.ceddesti
  , e.monto,e.fecha,e.fecha,e.idviaje,e.nota,e.cantbul
  ,e.cantbul,e.estado,e.idcierre,e.declarado,e.telremi
  ,e.teldesti1,e.teldesti2,e.idcliente,e.idestacion
  ,e.idproducto,e.idimpresora,e.emailremi
  ,i.nombre as impresora
  ,p.nombre as destino
  ,c.tipo, e.ubicacion
  from encomienda e 
  inner join impresora i on i.idimpresora=e.idimpresora
  inner join parada p on p.idparada=e.idparada
  inner join producto c on c.idProducto=e.idProducto
  where e.estado=0 and retirada=0 and e.fecha between '".$pdesde."' and '".$phasta."' 
  order by e.destinatario,e.idparada";
  
  $res = $link->bdEjecutar($sql);
  return $res;

}
function setUbicacion($pUbicacion) {
    $link =new tiqmysql();
    $sql ="update encomienda set ubicacion='".$pUbicacion."' 
    where idEncomienda=".$this->idEncomienda;
    $link->bdEjecutar($sql);
    $bit=NuevaBitacora();
    $this->HeredaBitacora($bit);
    $this->bitacora->Bitacorizar("Actualiza Ubicacion " .$this->idEncomienda . " " . $this->factura . " " . $pUbicacion);
  }

function Nulas($filtro){
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
  $sql="select e.idencomienda,e.factura,e.idparada,e.peso
  ,e.remitente,e.destinatario,e.cedremi,e.ceddesti
  ,e.monto,e.fecha,e.fecha,e.idviaje,e.nota,e.cantbul
  ,e.cantbul,e.estado,e.idcierre,e.declarado,e.telremi
  ,e.teldesti1,e.teldesti2,e.idcliente,e.idestacion
  ,e.idproducto,e.idimpresora,e.emailremi,e.fecharetiro,e.autonombre,e.autocedula
  ,p.nombre as parada, r.nombre as ruta
  ,x.hora as hora,c.nombre as cliente,v.fecha as fechaviaje
  ,o.nombre as estacion
  ,d.nombre as producto
  ,i.nombre as impresora
  ,u.nombre as usuario
  ,e.estaretiro
  ,e.entcierre
  ,e.entimpresora
  ,e.entusuario
  ,e.detprod
  ,e.tipocedula,e.provincia,e.canton,e.distrito,e.barrio,e.otrassenas
  ,e.razon,e.fechanc, e.numeronc
  from encomienda e 
  inner join parada p
  on e.idparada=p.idparada
  inner join viaje v
  on e.idviaje=v.idviaje
  inner join ruta r
  on v.idruta = r.idruta 
  inner join viajexesta x
  on v.idviaje=x.idviaje 
  inner join cliente c
  on e.idcliente=c.idcliente
  inner join estacion o
  on e.idestacion=o.idestacion
  inner join producto d
  on e.idproducto = d.idproducto
  inner join impresora i
  on e.idimpresora=i.idimpresora
  inner join usuario u
  on e.idusuario=u.idusuario
  where e.idestacion=x.idestacion
  ".$tira. " order by e.fechanc desc,x.hora desc ,e.factura desc " ;
  
  return $link->bdEjecutar($sql);
}

function actualizafa(){
  $link =new tiqmysql();
  $sql="update encomienda 
    set tipocedfa='".$this->tipocedfa."'
    ,cedulafa='".$this->cedulafa."'
    ,nombrefa='".$this->nombrefa."'
    ,correofa='".$this->correofa."'
    where idencomienda=".$this->idEncomienda." and facturafe!='' ";
    $link->bdEjecutar($sql);
} 

}
?>
