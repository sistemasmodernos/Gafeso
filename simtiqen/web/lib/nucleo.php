<?php
if(!isset($rutapri))
  $rutapri='';
include_once($rutapri.'lib/viaje.php');
include_once($rutapri.'lib/producto.php');
include_once($rutapri.'lib/estacion.php');
include_once($rutapri.'lib/parada.php');
include_once($rutapri.'lib/usuario.php');
include_once($rutapri.'lib/cliente.php');
include_once($rutapri.'lib/tiquete.php');
include_once($rutapri.'lib/encomienda.php');
include_once($rutapri.'lib/impresora.php');
include_once($rutapri.'lib/mysql.php');
include_once($rutapri.'lib/bitacora.php');
include_once($rutapri.'lib/lista.php');
include_once($rutapri.'lib/ruta.php');
include_once($rutapri.'lib/rutaxesta.php');
include_once($rutapri.'lib/viajexesta2.php');
include_once($rutapri.'lib/chofer.php');
include_once($rutapri.'lib/empresa.php');
include_once($rutapri.'lib/cobrador.php');
include_once($rutapri.'lib/bus.php');
include_once($rutapri.'lib/simerror.php');
include_once($rutapri.'lib/producto.php');
include_once($rutapri.'lib/estadistica.php');
include_once($rutapri.'lib/cierred.php');
include_once($rutapri.'lib/parchebuses.php');
include_once($rutapri.'lib/perfil.php');
include_once($rutapri.'lib/seguridad.php');
include_once($rutapri.'lib/parametro.php');
include_once($rutapri.'lib/utiles.php');
include_once($rutapri.'lib/mascaras.php');
include_once($rutapri.'lib/ocompra.php');
include_once($rutapri.'lib/dcompra.php');
include_once($rutapri.'lib/cedulasic.php');
include_once($rutapri.'lib/cedulajur.php');
include_once($rutapri.'lib/vacacion.php');
include_once($rutapri.'lib/tipoviaje.php');
include_once($rutapri.'lib/socio.php');
include_once($rutapri.'lib/formapago.php');
include_once($rutapri.'lib/prodenco.php');
/*Esta biblioteca se encarga de utlizar los objetos 
Ningun objeto debería ser utilizado por otra capa
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
*/ 

function Mantenimiento($objeto,$parametros,$opcion){
    $ob = new $objeto();
    $bit=NuevaBitacora();
    $retorno = null;
    if ($ob->CargarArreglo($parametros,$opcion)){
    $ob->HeredaBitacora($bit);        
    	switch($opcion) {
   		/* 1: Agregar un registo
   		   2: Modificar un registro
   		   3: Borrar un registro
    		 */
    		case 1: $retorno = $ob->Guardar(null) > 0;
    		        break;
    		case 2: 
            $retorno = $ob->Guardar(null) > 0;

    		        break;
    		case 3: $retorno = $ob->Borrar();
    		        break;
    	}
    }
    //parche para buses que no maneja varios precio ni varios horarios
    $lista=array("Estacion","Viaje" );
    if($objeto=="Viaje")
       parchearhoras($ob->idViaje);
       
     if($objeto=="Estacion")
       parchearhoras(0);
  
  /*  $lista=array("parada","Producto","ruta" );
	echo $objeto; 
	if(in_array($objeto,$lista)){
		parchearlista();
		echo "Lista Parchada";
	}
*/
       
    return $retorno;
}

function ListaMantenimiento($objeto,$filtro,$paginacion){
    $ob = new $objeto();
    $result=$ob->Listar($filtro,$paginacion);
    $kk=ResultArray($result);
    return $kk;
}

function Paginas($objeto,$filtro){
    $ob = new $objeto();
    $result=$ob->Cantidad($filtro);
    return $result;
}

function ListaActivos($objeto,$filtro){
    $ob = new $objeto();
    $result=$ob->ListarActivos($filtro);
    $kk=ResultArray($result);
    return $kk;
}

function ParadasWeb(){
    $ob = new Parada();
    $result=$ob->ParadaWeb();
    $kk=ResultArray($result);
    return $kk;
}


function ArrayMantenimiento($objeto,$idobjeto){
    
}


///funciones de los viajes


/***********************  <<Siguiente Viaje>>
Funcionalidad
    Delvuelve el siguiente viaje disponible
Parametros de entrada
    idestacion: Estación donde se venderá el tiquete
    idparada: Parada de destino, es necesario porque no todos los viajes pueden pasar por esa parada
    fecha: La fecha en formato UTC a partir de la cual se buscaran viajes hacia arriba
Retorno
  array con los datos del viaje
*******************************************************/
function SiguienteViaje($idestacion,$idparada,$fecha,$idruta,$ptipo){
    $viaje = new Viaje();
    if($fecha==date("Y-m-d"))
      $fecha=$fecha." ".date("H:i");
    $viaje =$viaje->SiguienteViaje($idestacion,$idparada,$fecha,$idruta,$ptipo);
    if($viaje==null)
            return 0;
    return $viaje;  
}




/***********************  <<ASIENTOS DISPONIBLES>>
Funcionalidad
    Calcula la cantidad de asientos disponibles en un viaje
Parametros de entrada
    idviaje: ID del viaje a calcular
Retorno
  numero con la cantidad de asientos disponibles
*******************************************************/
function AsientosDisponibles($idviaje){
    $viaje = new Viaje();
    $viaje->idViaje=$idviaje;
   
    if(!$viaje->Cargar()){
      ElevaError(new SimError("El viaje no existe"));
      return -1;
    }
    $bit=NuevaBitacora();
    $viaje->HeredaBitacora($bit);
    $res=$viaje->AsientosDisponibles();
    return $res;
}

/***********************  <<ASIENTOS DISPONIBLES ESPECIALES>>
Funcionalidad
    Calcula la cantidad de asientos disponibles en un viaje del tipo especial (adulto mayor ,embarazadas, discapacitados,etc)
Parametros de entrada
    idviaje: ID del viaje a calcular
Retorno
  numero con la cantidad de asientos especiales disponibles
*******************************************************/
function AsientosDisponiblesEspeciales($idviaje){
    $viaje = new Viaje();
    $viaje->idViaje=$idviaje;
    if(!$viaje->Cargar()){
      ElevaError(new SimError("El viaje no existe"));
      return -1;
    }
    $res=$viaje->AsientosDisponiblesEspeciales();
    return $res;
}

/***********************  <<ASIENTOS De Pie>>
Funcionalidad
    Calcula la cantidad de asientos disponibles de pie en un viaje 
Parametros de entrada
    idviaje: ID del viaje a calcular
Retorno
  numero con la cantidad de ventas de pie disponibles
*******************************************************/
function AsientosDisponiblesDP($idviaje){
    $viaje = new Viaje();
    $viaje->idViaje=$idviaje;
    if(!$viaje->Cargar()){
      ElevaError(new SimError("El viaje no existe"));
      return -1;
    }
    $res=$viaje->AsientosDisponiblesDP();
    return $res;
}



/***********************  <<SIGUIENTE ASIENTO>>
Funcionalidad
    Trae el numero del primero asiento disponible en viaje , si se recibe como parametro de cantidad 
    mas de uno se retornará el primero asiento disponible de forma que todos quepan seguidos
Parametros de entrada
    idviaje: ID del viaje a calcular
    pcantidad: cantidad de asientos que se requieren juntos
Retorno
  numero del asiento disponible
*******************************************************/
function SiguienteAsiento($idviaje,$pcantidad){
    $viaje = new Viaje();
    $viaje->idViaje=$idviaje;
    if(!$viaje->Cargar()){
      ElevaError(new SimError("El viaje no existe"));
      return -1;
    }
    $bit=NuevaBitacora();
    $viaje->HeredaBitacora($bit);
    $res=$viaje->SiguienteAsiento($pcantidad,null);
    return $res;
}



/***********************  <<LIBERA APARTADOS>>
Funcionalidad
    Anula todos los apartados que hayan complido su tiempo de vida según el parámetro
Parametros de entrada
    idviaje: ID del viaje a calcular
Retorno
  nada
*******************************************************/
function LiberaApartados($idviaje){
    $viaje = new Viaje();
    $viaje->idViaje=$idviaje;
    if(!$viaje->Cargar()){
      ElevaError( new SimError("El viaje no existe"));
      return;
    }
    $bit=NuevaBitacora();
    $viaje->HeredaBitacora($bit);
    $viaje->LiberaApartados();
}



function ListaViajes($opciones){
}



//funciones de Tiquetes
/***********************  <<APARTA TIQUETES>>
Funcionalidad
    Apartas una cantidad x de tiquetes con los datos recibidos por parámetro
Parametros de entrada
    idviaje: ID del viaje al cual se va a apartar tiquetes
    cantidad: Cantidad de tiquetes
    asiento: número de asiento que se desea vender en caso que no sea automático  o por ejemplo para los asientos especiales 1 y 2
    optiquete: Array con los datos del tiquete
            fecha;
            asiento;
            monto;
            idViaje;
            idEstacion;
            idProducto;
            idParada1;
            idParada2;
            idCliente;
    juntos: Si los tiquetes tienen que ir en consecutivo o no
Retorno
  Array con los números de tiquetes
*******************************************************/
function ApartarTiquetes($idviaje,$cantidad,$asiento,$optiquete,$juntos,$depie){
    
    //revisa si vende cortesia y si es asi que tenga permiso
    if($optiquete["idProducto"]==99)
      if(!ObjetoValido('cortesia'))
      {
            throw new SimError("No tiene permiso para vender tiquetes de cortesía");
          }
    
    $viaje = new Viaje();
    $viaje->idViaje=$idviaje;
    try{
    if(!$viaje->Cargar()){
      throw new SimError("El viaje no existe");
      return;
    }
    $bit=NuevaBitacora();

    //aqui revisa que tenga permiso de vender esa ruta
    $lasrutasper = explode(",",UsuarioxRuta($bit->usuario->idUsuario));
    if(!in_array($viaje->ruta->idRuta, $lasrutasper)){
     throw new SimError("El usuario no tiene permiso para vender esta ruta ");
      return;   
    }





    $viaje->HeredaBitacora($bit);
    $sig =$viaje->SiguienteAsiento(1,null) ;
    //esto es por si ha avanzado el consecutivo pone el siguiente asiento
    //si son los asientos especiales los trata de apartar
    if($asiento<$sig&& $asiento>2)
        $asiento=$sig;
    $tiquete = new Tiquete();
   // $tiquete->fecha= $optiquete["fecha"];
    $tiquete->fecha= date("Y-m-d" , time());
    $tiquete->monto=$optiquete["monto"];
    $tiquete->viaje->idViaje=$idviaje;
    $tiquete->estacion->idEstacion=$optiquete["idEstacion"];
    $tiquete->producto->idProducto=$optiquete["idProducto"];
    $tiquete->parada1->idParada=$optiquete["idParada1"];
    $tiquete->parada2->idParada=$optiquete["idParada2"];
    $tiquete->usuario->idUsuario=$bit->usuario->idUsuario;
    $tiquete->cliente->idCliente=$optiquete["idCliente"];
    $tiquete->impresora->idImpresora=$optiquete["idImpresora"];
    $tiquete->anombre = $optiquete["nombre"] ;
    $tiquete->comentario= $optiquete["comentario"];
    $tiquete->cedula =  $optiquete["cedula"] ;
    $tiquete->numtar=  $optiquete["numtar"] ;

    if($tiquete->parada2->idParada==0){
        $viaje->ruta->Cargar();
        $viaje->ruta->estallega->Cargar();
        $tiquete->parada2->idParada = $viaje->ruta->estallega->parada->idParada;
    }

    if(trim($optiquete["cedula"])>0){
        $cli = new Cliente();
        $jj = $cli->CargaCedula($optiquete["cedula"]);
        if($jj==null){
            $cli->nombre = $optiquete["nombre"];
            $cli->cedula= $optiquete["cedula"];
            $cli->fechanac=$optiquete["fechanac"];
            $cli->HeredaBitacora($bit);
            $jj= $cli->Guardar(null);
        }
        $tiquete->cliente->idCliente=$jj;
    }
    $tiquete->HeredaBitacora($bit);
    $res=$viaje->ApartarTiquetes($tiquete,$cantidad,$asiento,$juntos,$depie);
    }
    catch(SimError $ex)
    {
        ElevaError($ex);
        return null;
        }
    return $res;
}



//funciones de Tiquetes
/***********************  <<APARTA TIQUETES>>
Funcionalidad
    Apartas una cantidad x de tiquetes con los datos recibidos por parámetro
Parametros de entrada
    idviaje: ID del viaje al cual se va a apartar tiquetes
    cantidad: Cantidad de tiquetes
    asiento: número de asiento que se desea vender en caso que no sea automático  o por ejemplo para los asientos especiales 1 y 2
    optiquete: Array con los datos del tiquete
            fecha;
            asiento;
            monto;
            idViaje;
            idEstacion;
            idProducto;
            idParada1;
            idParada2;
            idCliente;
    juntos: Si los tiquetes tienen que ir en consecutivo o no
Retorno
  Array con los números de tiquetes
*******************************************************/
function ApartarTiquetes2($idviaje,$optiquete,$marcas){
    
    //revisa si vende cortesia y si es asi que tenga permiso
    if($optiquete["idProducto"]==99)
      if(!ObjetoValido('cortesia'))
      {
            throw new SimError("No tiene permiso para vender tiquetes de cortesía");
          }
    
    $viaje = new Viaje();
    $viaje->idViaje=$idviaje;
    try{
    if(!$viaje->Cargar()){
      throw new SimError("El viaje no existe ".$idviaje);
      return;
    }
    $bit=NuevaBitacora();
    $viaje->HeredaBitacora($bit);


//aqui revisa que tenga permiso de vender esa ruta
    $lasrutasper = explode(",",UsuarioxRuta($bit->usuario->idUsuario));
    if(!in_array($viaje->ruta->idRuta, $lasrutasper)){
     throw new SimError("El usuario no tiene permiso para vender esta ruta ");
      return;   
    }


    $tiquete = new Tiquete();
   // $tiquete->fecha= $optiquete["fecha"];
    $tiquete->fecha= date("Y-m-d" , time());
    $tiquete->monto=$optiquete["monto"];
    $tiquete->idViaje=$idviaje;
    $tiquete->estacion->idEstacion=$optiquete["idEstacion"];
    $tiquete->producto->idProducto=$optiquete["idProducto"];
    $tiquete->parada1->idParada=$optiquete["idParada1"];
    $tiquete->parada2->idParada=$optiquete["idParada2"];
    $tiquete->usuario->idUsuario=$bit->usuario->idUsuario;
    $tiquete->cliente->idCliente=$optiquete["idCliente"];
    $tiquete->impresora->idImpresora=$optiquete["idImpresora"];
    $tiquete->anombre = $optiquete["nombre"] ;
    $tiquete->comentario= $optiquete["comentario"];
    $tiquete->cedula =  $optiquete["cedula"] ;
    $tiquete->numtar=  $optiquete["numtar"] ;
    if(trim($optiquete["cedula"])>0){
        $cli = new Cliente();
        $jj = $cli->CargaCedula($optiquete["cedula"]);
        if($jj==null){
            $cli->nombre = $optiquete["nombre"];
            $cli->cedula= $optiquete["cedula"];
            $cli->fechanac=$optiquete["fechanac"];
            $cli->HeredaBitacora($bit);
            $jj= $cli->Guardar(null);
        }
        $tiquete->cliente->idCliente=$jj;
    }
    $tiquete->HeredaBitacora($bit);
    $res=$viaje->ApartarTiquetes2($tiquete,$marcas);
    }
    catch(SimError $ex)
    {
        ElevaError($ex);
        return null;
        }
    return $res;
}


/***********************  <<VENDER TIQUETES>>
Funcionalidad
    Convierte a facturados una serie de tiquetes antes apartados
Parametros de entrada
        lista:  array con los id de los tiquetes a validar
Retorno
  true si se completó , false si no se pudo completar
*******************************************************/
function VenderTiquetes($lista){
    
    $link =new tiqmysql();
    $link->bdAbreTran();
    $res=true;
    try{
    for($x=0;$x<count($lista);$x++)
    {
        $tiq = new Tiquete();
        $tiq->idTiquete=$lista[$x];
        if(!$tiq->Cargar())
          throw new SimError("Uno de los tiquetes apartados no existe ".$lista[$x]);
          
        if($tiq->estado==1)
          throw new SimError("Uno de los tiquetes apartados ya se venció ". $lista[$x]);
        $tiq->HeredaBitacora(NuevaBitacora());
        $res=($tiq->Vender($link)>0);
    }
    if(!$res)
      $link->bdRevierteTran();
    else
      $link->bdCierraTran();
    }
    catch(SimError $ex){
        $link->bdRevierteTran();
        ElevaError($ex);
        return false;
    }
    return $res;
}




/***********************  <<DEVOLVER TIQUETE>>
Funcionalidad
    Convierte un tiquete en un array
Parametros de entrada
        idTiquete:  Id del tiquete en cuesti'on
Retorno
  Array con los datos del tiquete
*******************************************************/
function DevTiquete($idTiquete){
    $tiq = new Tiquete();
    $tiq->idTiquete = $idTiquete;
    if(!$tiq->Cargar()){
	    ElevaError( new SimError("El tiquete  no existe ".$idTiquete));
    }
    return ResultArray(($tiq->ListarActivos(array(" t.idtiquete=".$tiq->idTiquete." "))))[0];
//    return $tiq->ToArray();
    
}

/***********************  <<DEVOLVER TIQUETE>>
Funcionalidad
    Convierte un tiquete en un array
Parametros de entrada
        imp:  impresora
        fac:  numero de factura
Retorno
  Array con los datos del tiquete
*******************************************************/
function DevTiqueteF($imp,$fac){
    $tiq = new Tiquete();
    $tiq->idTiquete = $tiq->idxFactura($imp,$fac);;
    if(!$tiq->Cargar()){
       ElevaError( new SimError("El tiquete  no existe ".$fac));
       return array();
       }
    
    return ResultArray(($tiq->ListarActivos(array(" t.idtiquete=".$tiq->idTiquete." "))))[0];
    
}


function ListaTiquetes($opciones){
}


/***********************  <<ANULAR TIQUETE>>
Funcionalidad
    Anula un tiquete
Parametros de entrada
        idTiquete:  Id del tiquete en cuesti'on
Retorno
  true si se pudo anular, false sino
*******************************************************/
function AnularTiquete($idTiquete,$razon){
    $tiq = new Tiquete();
    $tiq->idTiquete = $idTiquete;
    if(!$tiq->Cargar())
       ElevaError(new SimError("El tiquete  no existe ".$idTiquete));
    $tiq->HeredaBitacora(NuevaBitacora());
    return $tiq->Anular($razon);
    
}

//Funciones de Encomiendas
/***********************  <<VENDE ENCOMIENDA   >>
Funcionalidad
    Crea encomienda con los datos recibidos por parámetro
Parametros de entrada
   oParam=
    fecha: fecha de la factura
    monto: monto de la encomienda
    idviaje : ID del viaje en que viajara la encomienda
    idestacion: estaci'on donde se vende
    idproducto: ID del producto
    idparada: parada donde se recoger'a la encomienda
    idcliente: Cliente
    peso;
    remitente;
    destinatario;
    cedremi;
    ceddesti;
    telremi;
    teldesti1;
    teldesti2;
    nota;
    cantbul: cantidad de bultos
   declarado;
Retorno
  ID de encomienda
*******************************************************/
function VenderEncomienda($opar){
    $viaje = new Viaje();
    $viaje->idViaje=$opar["idviaje"];
    try{
    if(!$viaje->Cargar()){
      throw new SimError("El viaje no existe");
      return;
    }
    if($opar["monto"]>0 && $opar["iva"]==0){
		 if(getPorIvaXEmpresa($opar["cedulafa"])>0){
				throw new SimError("Error con el impuesto");
				return;
			}	
		}
	$errores="";
    $bit=NuevaBitacora();
    $viaje->HeredaBitacora($bit);
    $enco = new Encomienda();
    $enco->HeredaBitacora($bit);
    $enco->fecha = $opar["fecha"];
    $enco->monto = $opar["monto"];
    $enco->viaje->idViaje=$opar["idviaje"];
	 if(!$enco->viaje->Cargar())
          $errores.="El viaje que escogió no existe ".$enco->viaje->idViaje;
	 if($enco->viaje->liqenco == "1")
          $errores.="El viaje que escogió ya fue liquidado ";
    $enco->estacion->idEstacion = $opar["idestacion"];
    $enco->producto->idProducto = $opar["idproducto"];
    $enco->formapago->idFormaPago = $opar["idformapago"];
    $enco->parada->idParada = $opar["idparada"];
    $enco->cliente->idCliente=$opar["idcliente"];
    $enco->peso = $opar["peso"];
    $enco->remitente= $opar["remitente"];
    $enco->destinatario=$opar["destinatario"];
    $enco->cedremi = $opar["cedremi"];
    $enco->ceddesti  = $opar["ceddesti"];
    $enco->telremi = $opar["telremi"];
    $enco->emailremi= $opar["emailremi"];
    $enco->teldesti1 = $opar["teldesti1"];
    $enco->teldesti2 = $opar["teldesti2"];
    $enco->nota = $opar["nota"];
    $enco->cantbul = $opar["cantbul"];
    $enco->declarado = $opar["declarado"];
    $enco->factura=$opar["factura"];
    $enco->usuario->idUsuario=$bit->usuario->idUsuario;
    $enco->impresora->idImpresora = $opar["idimpresora"];
    $enco->detprod = $opar["detprod"];
    $enco->esmanual=$opar["esmanual"];
    $enco->tipocedula= $opar["tipocedula"];
    $enco->provincia = $opar["provincia"];
    $enco->canton = $opar["canton"];
    $enco->distrito = $opar["distrito"];
    $enco->barrio = $opar["barrio"];
    $enco->otrassenas = $opar["otrassenas"];
    $enco->iva = $opar["iva"];
    $enco->tipocedfa = $opar["tipocedfa"];
    $enco->cedulafa = $opar["cedulafa"];
    $enco->nombrefa = $opar["nombrefa"];
    $enco->correofa = $opar["correofa"];
    $enco->actividad = $opar["actividad"];
    $res=$enco->Guardar(null);

	if($errores!="")		
			throw new SimError($errores);
    }
    catch(SimError $ex)
    {
        ElevaError($ex);
        return null;
        }
    return $res;
}
/***********************  <<ANULAR ENCOMIENDA>>
Funcionalidad
    Anula una encomienda
Parametros de entrada
        idEncomienda:  Id del tiquete en cuesti'on
Retorno
  true si se pudo anular, false sino
*******************************************************/
function AnularEncomienda($idEncomienda,$razon){
    $enco = new Encomienda();
    $enco->idEncomienda = $idEncomienda;
    if(!$enco->Cargar())
       ElevaError(new SimError("La encomienda  no existe ".$idEncomienda));
    $enco->HeredaBitacora(NuevaBitacora());
    return $enco->Anular($razon);
    
}

//Funciones de Encomiendas
/***********************  <<PRECIO PRODUCTO  >>
Funcionalidad
    Devuelve el precio de un producto entre dos paradas de una ruta
Parametros de entrada
   oParam=
        pidruta= id de la ruta
        pidparada1= id de la parada de donde sale
        pidparada2 = id de la parada destino
        pidproducto = id del tipo de producto
Retorno
  precio del boleto
*******************************************************/
function precioProducto($pidruta,$pidparada1,$pidparada2,$pidproducto){
    $rut= new Ruta();
    $rut->idRuta=$pidruta;
    $res = $rut->precioProducto($pidparada1,$pidparada2,$pidproducto);
    return $res;
}


//funciones generales
function CapturaUsuario(){
    if(!isset($_SESSION["idusuario"])){
      return null;
    }
    $usuario = new Usuario();
    $usuario->idUsuario = $_SESSION["idusuario"];
    return $usuario;
}

function NuevaBitacora(){
        $bit = new Bitacora();
    $bit->nivel=1;
    $usu = CapturaUsuario();
    $bit->usuario=$usu;
    $bit->idPadre=0;
    return $bit;
}
function Bitacorizar($detalle){
    $bit=NuevaBitacora();
    if($bit->usuario==null)
      $bit->usuario=CapturaUsuario();
    $bit->Bitacorizar($detalle);
}

function AutorizarSesion($pusername,$pass){
    $usuario= new Usuario();
    
    $res=$usuario->AutorizarSesion($pusername,$pass,session_id(), $_SERVER['REMOTE_ADDR']);
    if($res>0){
       $_SESSION["idusuario"]=$res;
       //Corregir esto
       $_SESSION["estaciondefault"]=1;
       }
     return $res;  
}
function SesionActiva(){
    $usuario=CapturaUsuario();
    if($usuario==null)
      return false;
    return $usuario->SesionActiva(session_id());
}
function ViajesxEstacion($pestacion,$pidruta,$pfecha,$ptipo){
      $esta=new Estacion();
      $esta->idEstacion=$pestacion;
      return ResultArray($esta->Viajes($pidruta,$pfecha,$ptipo));
}

function FechatoNatural($pfecha){
    $ar=explode("-",$pfecha);
    return $ar[2] ."/" . $ar[1] . "/" . $ar[0];
}

function ResultArray($result){
    $res = array();
    while($rows = mysqli_fetch_array($result,MYSQLI_ASSOC))
        array_push($res,$rows);
    return $res;    
}

function ElevaError($ex){
    $_SESSION["parametros"]["mensaje_error"] = $ex->getMessage();
}

function Viajes($pviaje,$estacion,$inicio,$fin){
    $es = new Estadistica();
    $res = $es->Viajes($pviaje,$estacion,$inicio,$fin);
    return ResultArray($res);
}
function DatosDefault($pruta,$pestacion){
     $usu =CapturaUsuario();
     $rut = new Ruta();
     $rut->idRuta=$pruta;
     $rut->Cargar();
     $rut->estasale->Cargar();
     $rut->estallega->Cargar();
     $viaje = new Viaje();
     
     $idviaje =$viaje->SiguienteViaje( $pestacion,$rut->estallega->parada->idParada,date('Y-m-d H:i' ),$pruta,1);

     if($idviaje!=null){
      $viaje->idViaje=$idviaje;
     $viaje->Cargar();
     }
     else
      $viaje = new Viaje();
     $precio=precioProducto($rut->idRuta,
         $rut->estasale->parada->idParada,
         $rut->estallega->parada->idParada,
         $_SESSION["parametros"]["productodefault"]);
     
     $res = array("fecha"=>$viaje->fecha,
     "idviaje"=>$viaje->idViaje,
     "parada1"=>$rut->estasale->parada->idParada,
     "parada2"=>$rut->estallega->parada->idParada,
     "producto"=>$_SESSION["parametros"]["productodefault"],
     "precio"=>$precio);
     
     return $res;
}
function SiguienteFactura($impresora){
    $imp = new Impresora();
    $imp->idImpresora= $impresora;
    $imp->Cargar();
    return  str_pad($imp->consecutivo, 6, "0", STR_PAD_LEFT); 
}
function SiguienteFacturae($impresora){
    $imp = new Impresora();
    $imp->idImpresora= $impresora;
    $imp->Cargar();
    return  str_pad($imp->consecutivoe, 6, "0", STR_PAD_LEFT); 
}
function ListaAsientos($viaje){
    $vi = new Viaje();
    $vi->idViaje=$viaje;
    return $vi->ListaAsientos();
}
function ClienteCedula($pcedula){
    $dat = array(
            "nombre"=>"",
            "telefono"=>"",
            "email"=>"",
            "fechanac"=>"",
            "provincia"=>"1",
            "canton"=>"01",
            "distrito"=>"01",
            "barrio"=>"01",
            "otrasSenas"=>"",
            "poriva"=>13
        );
    $cli = new Cliente();
    $res= $cli->CargaCedula($pcedula);
    $poriva = 13;
    if($res!=null)
    {
        $dat["nombre"]=$cli->nombre;
        $dat["telefono"]=$cli->telefono;
        $dat["email"]=$cli->email;
        $dat["fechanac"]=$cli->fechanac;
        $dat["provincia"]=$cli->provincia;
        $dat["canton"]=$cli->canton;
        $dat["distrito"]=$cli->distrito;
        $dat["barrio"]=$cli->barrio;
        $dat["otrasSenas"]=$cli->otrasSenas;
        $dat["poriva"]=$poriva;
    }
    $empre = new Empresa();
    if($empre->CargarCedula($pcedula)>0){
        $poriva = $empre->getPorIva();
			$par=new Parametro();
  			$newexo = $par->Retorna("newcalexo",1);
		  	if($empre->exodoc!=''){
      		if($newexo==1)
        			$poriva=$poriva-$empre->exoporce;
        		else
        			$poriva=$poriva-round($poriva*$empre->exoporce/100,5);
 			}

        $dat["nombre"]= $empre->nombre;
        $dat["poriva"]= $poriva;
    }

    return $dat;

}

function ClienteSIC($pcedula){
    $dat = array(
            "nombre"=>"",
            "telefono"=>"",
            "email"=>"",
            "fechanac"=>"",
            "provincia"=>"1",
            "canton"=>"01",
            "distrito"=>"01",
            "barrio"=>"01",
            "otrasSenas"=>"",
            "poriva"=>13,
            "actividad"=>""
        );
    $cli = new Cliente();
    $res= $cli->CargaCedula($pcedula);
    $poriva = 13;

    if($res!=null)
    {
        $dat["nombre"]=$cli->nombre;
        $dat["telefono"]=$cli->telefono;
        $dat["email"]=$cli->email;
        $dat["fechanac"]=$cli->fechanac;
        $dat["provincia"]=$cli->provincia;
        $dat["canton"]=$cli->canton;
        $dat["distrito"]=$cli->distrito;
        $dat["barrio"]=$cli->barrio;
        $dat["otrasSenas"]=$cli->otrasSenas;
        $dat["poriva"]=$poriva;
        $dat["actividad"]=$cli->actividad;
    }
    else{
    $cli = new CedulaSIC();
    $res= $cli->CargarId("0".$pcedula);

    if($res!=null)
    {
            $dat["nombre"]=$cli->nombre;
            $dat["provincia"]=$cli->provincia;
            $dat["canton"]=$cli->canton;
            $dat["distrito"]=$cli->distrito;
            $dat["barrio"]=$cli->barrio;
            $dat["otrasSenas"]=$cli->otrasSenas;
            $dat["poriva"]=$poriva;
    }
    else{
      $cli = new CedulaJurSIC();
      $res= $cli->CargarId($pcedula);
      if($res!=null)
      {
                $dat["nombre"]=$cli->nombre;
                $dat["provincia"]=$cli->provincia;
                $dat["canton"]=$cli->canton;
                $dat["distrito"]=$cli->distrito;
                $dat["barrio"]=$cli->barrio;
                $dat["otrasSenas"]=$cli->otrasSenas;
                $dat["poriva"]=$poriva;
      }
    }
  }
    $empre = new Empresa();
    if($empre->CargarCedula($pcedula)>0){
        $poriva = $empre->getPorIva();

         $par=new Parametro();
         $newexo = $par->Retorna("newcalexo",1);
         if($empre->exodoc!=''){
            if($newexo==1)
               $poriva=$poriva-$empre->exoporce;
            else
               $poriva=$poriva-round($poriva*$empre->exoporce/100,5);
         }


        $dat["nombre"]= $empre->nombre;
        $dat["poriva"]= $poriva;
    }
    return $dat;
}
function ClienteJurSIC($pcedula){
  $cli = new CedulaJurSIC();
  $res= $cli->CargarId($pcedula);
    $poriva = 13;
    $empre = new Empresa();
    if($empre->CargarCedula($pcedula)>0){
        $poriva = $empre->getPorIva();
         $par=new Parametro();
         $newexo = $par->Retorna("newcalexo",1);
         if($empre->exodoc!=''){
            if($newexo==1)
               $poriva=$poriva-$empre->exoporce;
            else
               $poriva=$poriva-round($poriva*$empre->exoporce/100,5);
         }

    }
  if($res!=null)
  {
    $dat = array(
      "nombre"=>$cli->nombre,
      "telefono"=>"",
      "email"=>"",
      "fechanac"=>"",
            "provincia"=>$cli->provincia,
            "canton"=>$cli->canton,
            "distrito"=>$cli->distrito,
            "barrio"=>$cli->barrio,
            "otrasSenas"=>$cli->otrasSenas,
            "poriva"=>$poriva
          );
    return $dat;
  }
  else{
    return null;
  }
}




function ResumenCierre($pcierre,$pestacion,$pimpresora,$fechai,$fechaf){
   $kk = array();
   $cie = new Cierred();
   $primero = array();
    if($pcierre>0){
       $cie->idCierre=$pcierre;
       $cie->Cargar();
       $cie->usuario->Cargar();
    }
    else
    {
        $cie->estacion->idEstacion=$pestacion;
        $cie->impresora->idImpresora = $pimpresora;
        $cie->fechai = $fechai;
        $cie->fechaf = $fechaf;
    }
       $primero= array(
          "estacion"=>$cie->estacion->idEstacion
         ,"impresora"=>$cie->impresora->idImpresora
         ,"fechai"=>$cie->fechai
         ,"fechaf"=>$cie->fechaf
         ,"usuario"=>$cie->usuario->nombrelargo
         );
    $result = $cie->Resumen();
    array_push($kk,$primero);
    array_push($kk,ResultArray($result));
    array_push($kk,$cie->ingresos);
    array_push($kk,$cie->egresos);    
	 array_push($kk,$cie->cajaCRC);
    array_push($kk,$cie->cajaUSD);
	 array_push($kk,$cie->tc);

    return $kk;       
}

function CierreDeCierres($fechai,$fechaf){
    $cie = new Cierred();
    $result = $cie->TodosCierres($fechai,$fechaf);
    return $result;
}
function CreaCierre($pestacion,$pimpresora,$fechai,$fechaf,$pingresos,$pegresos,$pcajacrc,$pcajausd,$ptc){
   $cierrehasta= DevParametro("cierrahasta", 1);
   if($cierrehasta==1)
      $fechai='1900-01-01';
   $cie = new Cierred();
   $cie->estacion->idEstacion=$pestacion;
   $cie->impresora->idImpresora = $pimpresora;
   $cie->fechai = $fechai;
   $cie->fechaf = $fechaf;
   $bit=NuevaBitacora();
   $cie->HeredaBitacora($bit);
   $cie->usuario->idUsuario=$bit->usuario->idUsuario;
   $cie->ingresos=$pingresos;
   $cie->egresos=$pegresos;
   $cie->cajaUSD = $pcajausd;
   $cie->cajaCRC = $pcajacrc;
   $cie->tc = $ptc;

   return $cie->Guardar();
}


function Remision($pviaje,$pestacion,$parada){
    $vi = new Viaje();
    $vi->idViaje=$pviaje;
    $vi->Cargar();
    $res =$vi->Remision($pestacion,$parada);
    return ResultArray($res);
}

function Asientosxnum($pviaje){
    $vi = new Viaje();
    $vi->idViaje=$pviaje;
    $vi->Cargar();
    $vi->bus->Cargar();
    $asi = array();
    for($x=0;$x<=$vi->bus->cantpas;$x++)
      array_push($asi,array("asiento"=>$x,
            "estado"=>"vac&iacute;o",
            "factura"=>"",
            "nombre"=>"",
            "parada"=>"z No vendidos",
            "cantidad"=>0,
            "estacion"=>"",
            "serial"=>"",
            "idestacion"=>0));
            
    $tiq = new Tiquete();
    $res = $tiq->ListarActivos(array("t.idviaje=".$pviaje,"t.estado=0","t.factura!='APARTA'"));
    $bol = ResultArray($res);
    //echo var_dump($bol);
//    $asi[0]["parada"]="y De pie";
    for($x=0;$x<count($bol);$x++){
       if($bol[$x] ["asiento"]<1){
//           $asi[0]["cantidad"]++;
      array_push($asi,array("asiento"=>0,
            "estado"=>"vendido",
            "factura"=>$bol[$x]["factura"],
            "nombre"=>$bol[$x]["anombre"],
            "parada"=>"a ".$bol[$x]["parada1"],
            "cantidad"=>1,
            "estacion"=>$bol[$x]["estacion"],
            "serial"=>$bol[$x]["serial"],
            "idestacion"=>$bol[$x]["idestacion"]));
       }
       else{
           if($bol[$x]["asiento"]<=$vi->bus->cantpas){
           $asi[$bol[$x]["asiento"]]["estado"]="vendido";
           $asi[$bol[$x]["asiento"]]["factura"]=$bol[$x]["factura"];
           $asi[$bol[$x]["asiento"]]["parada"]="a ".$bol[$x]["parada1"];
           $asi[$bol[$x]["asiento"]]["cantidad"]++;
           $asi[$bol[$x]["asiento"]]["estacion"]=$bol[$x]["estacion"];
           $asi[$bol[$x]["asiento"]]["nombre"]=$bol[$x]["anombre"];
           $asi[$bol[$x]["asiento"]]["serial"]=$bol[$x]["serial"];
           $asi[$bol[$x]["asiento"]]["idestacion"]=$bol[$x]["idestacion"];
           }
           else
             echo "Problema as procesar tiquete ".$bol[$x]["factura"].
             " el asiento vendido es el ".$bol[$x]["asiento"]." y los par&aacute;metros del bus indican que es de ".
             $vi->bus->cantpas." pasajeros <br/>";
       }
    }
 //   $asi[0]["nombre"]=" ".$asi[0]["cantidad"];
   // echo var_dump($asi);
    return $asi;
}

function ListaCambios($pviaje){
    $vi = new Viaje();
    $vi->idViaje=$pviaje;
    $vi->Cargar();
    return $vi->ListaCambios();
}

function AsientosxWeb($pviaje){
    $vi = new Viaje();
    $vi->idViaje=$pviaje;
    $vi->Cargar();
    $vi->bus->Cargar();
    $asi = array();
    // for($x=0;$x<=$vi->bus->cantpas;$x++)
    //   array_push($asi,array("asiento"=>$x,
    //         "estado"=>"vac&iacute;o",
    //         "factura"=>"",
    //         "nombre"=>"",
    //         "parada"=>"z No vendidos",
    //         "cantidad"=>0,
    //         "estacion"=>"",
    //        "serial"=>"",
    //        "idestacion"=>0));
    $tiq = new Tiquete();
    $res = $tiq->ListarActivos(array("t.idviaje=".$pviaje,"t.estado=0","t.idestacion=99"));
    $bol = ResultArray($res);
    //    $asi[0]["parada"]="y De pie";
    for($x=0;$x<count($bol);$x++){
        if($bol[$x] ["asiento"]<1){
            //$asi[0]["cantidad"]++;
            array_push($asi,array("asiento"=>0,
                "estado"=>"vendido",
                "factura"=>$bol[$x]["factura"],
                "nombre"=>$bol[$x]["anombre"],
                "parada"=>"a ".$bol[$x]["parada1"],
                "cantidad"=>1,
                "estacion"=>$bol[$x]["estacion"],
                "serial"=>$bol[$x]["serial"],
                "idestacion"=>$bol[$x]["idestacion"]));
        }
        else{
            array_push($asi,array("asiento"=>$bol[$x]["asiento"],
                "estado"=>"vendido",
                "factura"=>$bol[$x]["factura"],
                "nombre"=>$bol[$x]["anombre"],
                "parada"=>"a ".$bol[$x]["parada1"],
                "cantidad"=>1,
                "estacion"=>$bol[$x]["estacion"],
                "serial"=>$bol[$x]["serial"],
                "idestacion"=>$bol[$x]["idestacion"]));

        }
    }
    //   $asi[0]["nombre"]=" ".$asi[0]["cantidad"];
    // echo var_dump($asi);
    return $asi;
}


function DatosViaje($pviaje,$pestacion){
    $vi = new Viaje();
    $vi->idViaje=$pviaje;
    $vi->Cargar();
    $vi->chofer->Cargar();
    $vi->bus->Cargar();
    $vi->cobrador->Cargar();
    $vi->bus->socio->Cargar();
    $vi->tipoviaje->Cargar();
    $hora="";
    for($x=0;$x<count($vi->estaciones);$x++)
      if($vi->estaciones[$x]->idEstacion==$pestacion)
        $hora=$vi->estaciones[$x]->hora;
    return array(
     "idviaje"=>$pviaje,
     "cobrador"=>$vi->cobrador->nombre,
     "chofer"=>$vi->chofer->nombre,
     "bus"=>$vi->bus->placa,
     "fecha"=>$vi->fecha,
     "hora"=>$hora,
     "tamano"=>$vi->bus->cantpas,
     "fruta"=>$vi->fruta,
     "color"=>$vi->color,
     "liquidado"=>$vi->liquidado,
     "fechaliq"=>$vi->fechaliq,
     "tipoviaje"=>$vi->tipoviaje->idTipoViaje,
     "destipoviaje"=>$vi->tipoviaje->desTipoViaje,
     "extra"=>$vi->extra,
     "socio"=>$vi->bus->socio->nombre
    );
    
}

function CambiaClave($passant,$passnuevo){
    $us = new Usuario();
    $us->HeredaBitacora(NuevaBitacora());
    $res=$us->VerificaUsuario(sqlClearText($_SESSION["parametros"]["nombreusuario"]),sqlClearText($passant));
    
    if($res>0){
         $us->idUsuario=$res;
         $us->Cargar();
         $cam=$us->CambiarClave(sqlClearText($passnuevo));
         if($cam)
           return "La contrase&ntilde;a se actualiz&oacute; correctamente";
        else
          return "Ocurri&oacute; un problema al actualizar la contrase&ntilde;a";
    }
    else
      return "La contrase&ntilde;a anterior no es la correcta";

}

function RepoD151($pfechai,$pfechaf){
    $es = new Estadistica();
    $res=$es->repoD151($pfechai,$pfechaf);
    return ResultArray($res);
}

function ObjetoValido($pobjeto){
    $seg= new Seguridad();
    $bit=NuevaBitacora();
    $bit->usuario->Cargar();
   // echo "Objeto valido ".$pobjeto." usuario ".$bit->usuario->perfil->idperfil;
    $res2=$seg->ObjetoValido($pobjeto,$bit->usuario->perfil->idperfil);
    return $res2;
    
}

function DevObjetos($pidusuario){
    $seg= new Seguridad();
    $bit=NuevaBitacora();
    if($pidusuario)
      $pidusuario=$bit->usuario->idUsuario;
    $res=$seg->DevObjetos($pidusuario);
    return ResultArray($res);
}

function TodosObjetos($pidperfil){
        $seg= new Seguridad();
        if($pidperfil==null){
             $bit=NuevaBitacora();
             $bit->usuario->Cargar();
             $pidperfil=$bit->usuario->idUsuario->perfil->idperfil;
        }
        
        $res=$seg->TodosObjetos($pidperfil);
        return ResultArray($res);
}
function MarcarObjeto($pidperfil,$pidobjeto,$pvalor){
    
    $seg= new Seguridad();
    $bit=NuevaBitacora();
    $seg->HeredaBitacora($bit);
    $res=$seg->MarcarObjeto($pidperfil,$pidobjeto,$pvalor);
    return $res;

}

function AdultoMayor($pfechai,$pfechaf){
    $es = new Estadistica();
    $res=$es->AdultoMayor($pfechai,$pfechaf);
    return ResultArray($res);
}

function revisaAdultoMayor($pcedula,$pfecha,$pruta){
    $es = new Estadistica();
    return $es->revisaAdultoMayor($pcedula,$pfecha,$pruta);
}



function DevParametro($pnombre,$ptipo){
    $par1= new Parametro();
    return $par1->Retorna($pnombre,$ptipo);
}
function CambiaParametro($pnombre,$ptipo,$pvalor){
    $par1= new Parametro();
     $bit=NuevaBitacora();
     $par1->HeredaBitacora($bit);
    $par1->Establece($pnombre,$ptipo,$pvalor);
    return true;
}

function Precios($pviaje,$pproducto,$pcantidad,$pparada1,$pparada2){

    $vi = new Viaje();
    $vi->idViaje=$pviaje;
    $vi->Cargar();
    $pre = precioProducto($vi->ruta->idRuta,$pparada1,$pparada2,$pproducto);
    return $pre;
}

function ClonaViajes($desde,$hasta){
    
	$extra=0;
	$viaje = 0;
	$ruta = 1 ; 
	$start = strtotime("-1 day",strtotime($desde)); 
	$end = strtotime($hasta); 
	$oviaje= new Viaje();
	$horarios = simplexml_load_file('lib/horariosfijos.xml');
	$start = strtotime("-1 day",strtotime($desde)); 
	$end = strtotime($hasta); 
	$oviaje= new Viaje();
	$date = $start; 
	while($date < $end) 
	{ 
		$date = strtotime("+1 day", $date);
	//	echo "<h1>Cambia de fecha ".$date." </h1> ";
		foreach($horarios->ruta as $lruta){
	//	 echo "la ruta ".$lruta["id"]."<br/>" ;
			$ruta = $lruta["id"];
			foreach($lruta->viaje as $lviaje){
	//			   echo "el viaje ".$lviaje." dia ".$lviaje["dia"]." dia del ciclo ".date("w",$date)."<br/>";
				//verifica que no exista ya
				if(in_array(date("w",$date),explode(',',$lviaje["dia"]))){
	//			           echo "entro a revisar <br/>";
					$xres=$oviaje->Listar(array(" idruta=".$ruta, " fecha='".date('Y-m-d',$date)."'"," horaini='".$lviaje. "'"," v.idtipoviaje='".$lviaje["tipo"]. "'"), null);
					if(mysqli_num_rows($xres)==0){
						$parametros=array(
							"idviaje"=>$viaje,
							"idruta"=>$ruta,
							"fecha"=>date("Y-m-d",$date),
							"horas"=>array("1"=>$lviaje),
							"idchofer"=>"1",
							"idcobrador"=>"1",
							"idbus"=>"2",
							"estadi"=>"1",
							"extra"=>"0",
                            "noweb"=>"0",
							"idtipoviaje"=>$lviaje["tipo"]
						);
						$res= Mantenimiento("Viaje",$parametros,1);
					}
				}
			} 
		}
	} 
	/*
	$ahoras= array("07:00","09:00","11:00","13:00","15:00","17:00","19:00");
	$date = $start; 
	while($date < $end) 
		{ 
		$date = strtotime("+1 day", $date);
		for($x=0;$x<count($ahoras);$x++){
        //verifica que no exista ya
        $xres=$oviaje->Listar(array("idruta=".$ruta, " fecha='".date('Y-m-d',$date)."'","hora='".$ahoras[$x]. "'"), null);
			if(mysqli_num_rows($xres)==0){
				$parametros=array(
					"idviaje"=>$viaje,
					"idruta"=>$ruta,
					"fecha"=>date("Y-m-d",$date),
					"horas"=>array("1"=>$ahoras[$x]),
					"idchofer"=>"1",
					"idcobrador"=>"1",
					"idbus"=>"1",
					"estadi"=>"1",
					"extra"=>"0",
					"idtipoviaje"=>"1"
					);
				$res= Mantenimiento("Viaje",$parametros,1);
			}
        } 
	} 
	$ruta = 2 ; 
	$ahoras= array("05:00","07:00","09:00","11:00","13:00","15:00","17:00");
	$date = $start; 
	while($date < $end) 
		{ 
		$date = strtotime("+1 day", $date);
		for($x=0;$x<count($ahoras);$x++){
			//verifica que no exista ya
			$xres=$oviaje->Listar(array("idruta=".$ruta, " fecha='".date('Y-m-d',$date)."'","hora='".$ahoras[$x]. "'"), null);
			if(mysqli_num_rows($xres)==0){
				$parametros=array(
					"idviaje"=>$viaje,
					"idruta"=>$ruta,
					"fecha"=>date("Y-m-d",$date),
					"horas"=>array("1"=>$ahoras[$x]),
					"idchofer"=>"1",
					"idcobrador"=>"1",
					"idbus"=>"1",
					"estadi"=>"1",
					"extra"=>"0",
					"idtipoviaje"=>"1"
				);
				$res= Mantenimiento("Viaje",$parametros,1);
			}
        }
	} 
	return 1;
*/
	return 1;
}

function cambiaparada($pimpresora,$pfactura,$pidparada1,$pidparada2){
     $tiq = new Tiquete();
     $tiq->idTiquete=$tiq->idxFactura($pimpresora,$pfactura);
     if($tiq->Cargar())
     {
         
         $tiq->parada1->idParada=$pidparada1;
         $tiq->parada2->idParada=$pidparada2;
         $bit=NuevaBitacora();

         $tiq->HeredaBitacora($bit);
 //       $tiq->Bitacorizar("Cambio de paradas");         
         return $tiq->Guardar(null);
         }
     else{
         
         return 0;
         }
}

function cambiahora($pimpresora,$pfactura,$pidviaje,$pparada,$pparada2)
{
    $tiqv = new Tiquete();
    $tiqv->idTiquete = $tiqv->idxFactura($pimpresora,$pfactura);
 //   echo "va a cargar el tiquete ".$tiqv->idTiquete."<br/>";
 //   echo "va a cargar el  viaje ".$pidviaje."<br/>";
    if(!$tiqv->Cargar())
      return 0;
    $bit=NuevaBitacora();
    $tiqv->HeredaBitacora($bit);
    $tiqn = $tiqv->Clonar();
    $tiqn->HeredaBitacora($bit);    
    $tiqn->parada1->idParada = $pparada;
	 $tiqn->parada2->idParada = $pparada2;
    $viaje = new Viaje();
    $viaje->HeredaBitacora($bit);
    $viaje->idViaje=$pidviaje;
    if(!$viaje->Cargar())
     return 0;
    $matres=$viaje->ApartarTiquetes($tiqn,1,null,0,0);
    $tiqn->idTiquete=$matres[0];
    $tiqn->Cargar();
    $tiqv->Cambiar(null,$tiqn);
    $detbit="Cambio de horario en tiquete ".$tiqn->factura." Horario anterior:  ".$tiqv->viaje->fecha ." " .$tiqv->viaje->horaini.
    " Parada anterior ".$tiqv->parada1->nombre."  Horario nuevo :".
    $tiqn->viaje->fecha ." " .$tiqn->viaje->horaini.
    " Parada anterior ".$tiqn->parada1->nombre;
    $bit->Bitacorizar($detbit);
    return $tiqn->idTiquete;
      
    }

function ImprimeTiq2($ptiquetes){
  $tiqs=explode(",",$ptiquetes);
  $cad="";
  for($x=0;$x<count($tiqs);$x++){
    $cad.= "<div class='divtiq'>".
       str_replace("\n","<br/>",ImprimeTiquete($tiqs[$x])).
       "</div> "; 

}
return $cad;
}

function GuardaReimpreTiq($pidtiquete){
        $bit=NuevaBitacora();
        $tiq = new Tiquete();
        $tiq->idTiquete = $pidtiquete;
        $tiq->HeredaBitacora($bit);
        $tiq->GuardaReimpreTiq();
}

function GuardaReimpreTiqF($imp,$fac){
        $bit=NuevaBitacora();
        $tiq = new Tiquete();
        $tiq->idTiquete =  $tiq->idxFactura($imp,$fac);
        $tiq->HeredaBitacora($bit);
        $tiq->GuardaReimpreTiq();
}

function GuardaImpreTiq($pidtiquete){
        $bit=NuevaBitacora();
        $tiq = new Tiquete();
        $tiq->idTiquete = $pidtiquete;
        $tiq->HeredaBitacora($bit);
        $tiq->GuardaImpreTiq();
}

function GuardaImpreTiqF($imp,$fac){
        $bit=NuevaBitacora();
        $tiq = new Tiquete();
        $tiq->idTiquete =  $tiq->idxFactura($imp,$fac);
        $tiq->HeredaBitacora($bit);
        $tiq->GuardaImpreTiq();
}

/***********************  <<Reimprime TIQUETE>>
Funcionalidad
    Marcar para reimprimir un tiquete
Parametros de entrada
        idTiquete:  Id del tiquete en cuesti'on
Retorno
  true si se pudo anular, false sino
*******************************************************/
function MarcaReimpreTiq($idTiquete,$razon){
    $tiq = new Tiquete();
    $tiq->idTiquete = $idTiquete;
    if(!$tiq->Cargar())
       ElevaError(new SimError("El tiquete  no existe ".$idTiquete));
    $tiq->HeredaBitacora(NuevaBitacora());
    return $tiq->MarcaReimpreTiq($razon);
    
}

function ValidaReimpresion($imp,$fac){
        if (ObjetoValido('todareimpre'))
          return true;
        $bit=NuevaBitacora();
        $tiq = new Tiquete();
        $tiq->idTiquete =  $tiq->idxFactura($imp,$fac);
        $tiq->Cargar();
        if($tiq->impreso==0)
          return true;
        else
         return false;
    
}

function ListaReimpresion($pfechai,$pfechaf){
    $es = new Estadistica();
    $pfechaf=$pfechaf." 23:59";
    $res=$es->listaReimpresion($pfechai,$pfechaf,"");
    return ResultArray($res);
}


function ListaCortesia($pfechai,$pfechaf,$filtro){
    $es = new Estadistica();
    $pfechaf=$pfechaf." 23:59";
    $res=$es->listaCortesia($pfechai,$pfechaf,$filtro);
    return ResultArray($res);
}

function listaNulo($pfechai,$pfechaf,$filtro){
    $es = new Estadistica();
    $pfechaf=$pfechaf." 23:59";
    $res=$es->listaNulo($pfechai,$pfechaf,$filtro);
    return ResultArray($res);
}

function crealog($numero,$texto){
//$ddf = fopen('error.log','a');
//fwrite($ddf,"[".date("r")."] Error $numero: $textorn");
//fclose($ddf);
}

function GuardaFirma($pidencomienda,$pdatos){
  $enco = new Encomienda();
  $enco->idEncomienda = $pidencomienda;
  $enco->Cargar();
  $bit= NuevaBitacora();
  $enco->HeredaBitacora($bit);
  $enco->GuardaFirma($pdatos);
}
function DevuelveFirma($pidencomienda){
    $enco = new Encomienda();
    $enco->idEncomienda = $pidencomienda;
    return $enco->DevuelveFirma();
}
function EncomiendasSinFirma($pestacion){
    $enco= new Encomienda();
     $res = $enco->EncomiendasSinFirma($pestacion);
    return ResultArray($res);
}
function PonerColaFirma($pidencomiendas){
       $enco= new Encomienda();
    $lasid= explode(',',$pidencomiendas);
    $numero_aleatorio = generateRandomString();
    for($x=0;$x<count($lasid);$x++){
        $enco->idEncomienda = $lasid[$x];
        $bit= NuevaBitacora();
        $enco->HeredaBitacora($bit);
        $enco->PonerColaFirma($numero_aleatorio);
   }
}

function ListaCarreras($pfechai,$pfechaf){
    $es = new Estadistica();
    $pfechaf=$pfechaf." 23:59";
    $res=$es->listaCarreras($pfechai,$pfechaf);
    return ResultArray($res);
}

function RecibeEncomienda($pArregloEncomiendas){
    $enco= new Encomienda();
    $count = count($pArregloEncomiendas);
    for ($i = 0; $i < $count; $i++) {
		$bit= NuevaBitacora();
		$enco->HeredaBitacora($bit);
		$enco->idEncomienda = $pArregloEncomiendas[$i];
		$enco->Cargar();
		$enco->AgregaTracking(4);
		$enco->AgregaTracking(5);
    }
}

function RepChoferes($pfechai,$pfechaf,$pmonto,$lunchofe,$lchofer){
    $es = new Estadistica();
    $res=$es->RepChoferes($pfechai,$pfechaf,$pmonto,$lunchofe,$lchofer);
    return ResultArray($res);
}

function RepEsta1($pfechai,$pfechaf){
    $es = new Estadistica();
    $res=$es->RepEsta1($pfechai,$pfechaf);
    return ResultArray($res);
}

function CreaVacionesGlobales($pDias,$pAnno){
    $vaca = new Vacacion();
    $res=$vaca->CreaVacionesGlobales($pDias,$pAnno);
    return $res;
}

function CreaMovimientoVaca($pChofer,$pIdTipo,$pDias,$pAnno,$pFechaini,$pFechafin,$pRazon){
    $vaca = new Vacacion();
    $res=$vaca->CreaMovimiento($pChofer,$pIdTipo,$pDias,$pAnno,$pFechaini,$pFechafin,$pRazon);
    return $res;
}

function BoletaPlanilla($idPlanilla){
    $vaca = new Vacacion();
    $res=$vaca->BoletaPlanilla($idPlanilla);
    return $res;
}

function CargaBoletaVaca($idPlanilla){
    $res = new Vacacion();
    $res->Cargar($idPlanilla);
    return $res;
}

function GuardaFirmaVaca($pidmovi,$pdatos){
  $vaca = new Vacacion();
  $vaca->idmovi = $pidmovi;
  $vaca->GuardaFirma($pdatos);
}

function VacaEstadodeCuenta($idChofer){
    $res = new Vacacion();
    return $res->EstadodeCuenta($idChofer);
}

function ActuaPrecio($pidruta,$pidparada1,$pidparada2,$pidproducto,$pprecio){
    $rut = new Ruta();
    $rut->idRuta=$pidruta;
    $rut->Cargar();
    $rut->NuevaLista($pidparada1,$pidparada2,$pidproducto,$pprecio);
}

function ActuaPrecioMatriz($pidruta,$pmatriz){
    $rut = new Ruta();
    $rut->idRuta=$pidruta;
    $rut->Cargar();
    
    for($x=0;$x<count($pmatriz);$x++){
        $rut->NuevaLista($pmatriz[$x][0],$pmatriz[$x][1],$pmatriz[$x][2],$pmatriz[$x][3]);
    }
    $bit= NuevaBitacora();
    $rut->HeredaBitacora($bit);
    $rut->Guardar(null);
}
function ActuaPrecioFuturoMatriz($pidruta,$lfechafuturo,$pmatriz){
    $rut = new Ruta();
    $rut->idRuta=$pidruta;
    $rut->Cargar();
    
    for($x=0;$x<count($pmatriz);$x++){
        $rut->NuevaListaFutura($lfechafuturo,$pmatriz[$x][0],$pmatriz[$x][1],$pmatriz[$x][2],$pmatriz[$x][3]);
    }
    $bit= NuevaBitacora();
    $rut->HeredaBitacora($bit);
    $rut->Guardar(null);
}

function ListaPrecios($pidruta,$pidproducto){
    $rut = new Ruta();
    $rut->idRuta=$pidruta;
    $rut->Cargar();
    $res = $rut->ListaPrecios($pidproducto);
     return ResultArray($res);
}

function ParadasxRuta($pidruta,$ptipo){
    $rut = new Ruta();
    $rut->idRuta=$pidruta;
    $rut->Cargar();
    $res = $rut->ParadasxRuta($ptipo);
     return ResultArray($res);
}

function LiquidaViaje($pidviaje){
    $via = new Viaje();
    $via->idViaje=$pidviaje;
    $via->Cargar();
    $res = $via->Liquida();
    return ResultArray($res);
}

function VentasGenerales($pfechai,$pfechaf,$pfiltros){
    $est = new Estadistica();
    return ResultArray($est->VentasGenerales($pfechai,$pfechaf,$pfiltros));
}

function VentaGralCajero($pfechai,$pfechaf,$pfiltros){
    $est = new Estadistica();
    return ResultArray($est->VentaGralCajero($pfechai,$pfechaf,$pfiltros));
}

function LiquidacionxPeriodo($pfechai,$pfechaf,$pfiltros){
    $est = new Estadistica();
    return ResultArray($est->LiquidacionxPeriodo($pfechai,$pfechaf,$pfiltros));
}

function CierraViaje($pviaje,$pestacion){
    $vi = new Viaje();
    $vi->idViaje=$pviaje;
    $vi->Cargar();
        
    if($vi->bus->idBus==1)
        return "No puede cerrar este viaje si no ha asignado la placa correcta ";

    if($vi->liquidado==1)
        return "Este viaje ya est&aacute; liquidado , no puede liquidarlo nuevamente";

    $bit= NuevaBitacora();
    $vi->HeredaBitacora($bit);
    return $vi->CierraViaje($pestacion);

}

function CierraEncomienda($pviaje){
    $vi = new Viaje();
    $vi->idViaje=$pviaje;
    $vi->Cargar();
        
    if($vi->bus->idBus==1)
        return "No puede cerrar las encomiendas de este viaje si no ha asignado la placa correcta ";

    if($vi->liqenco==1)
        return "Este viaje ya est&aacute; liquidado para encomiendas , no puede liquidarlo nuevamente";

    $bit= NuevaBitacora();
    $vi->HeredaBitacora($bit);
    return $vi->CierraEncomienda();

}


function EncomiendasAbiertas($pdesde,$phasta){
    
    $encomi = new Encomienda();
    return ResultArray($encomi->EncomiendasAbiertas($pdesde,$phasta));
}

function RetiraEncomienda($pidencomiendas,$pCombo,$pCedula,$pNombre,$ptipocedfa,$pcedulafa,$pnombrefa,$pcorreofa){
    $enco = new Encomienda();
    $lasenco = explode(',', $pidencomiendas);
    $todobien=true;
    for($x=0;$x<count($lasenco);$x++){
        $enco->idEncomienda=$lasenco[$x];
        $enco->HeredaBitacora(NuevaBitacora());
        if(!$enco->Cargar())
            $todobien=false;
        $pestaretiro=$_SESSION["parametros"]["estaciondefault"];
        $pimpresora = $_SESSION["parametros"]["impresoradefault"];
        if($enco->facturafe==''){
            $enco->tipocedfa = $ptipocedfa;
            $enco->cedulafa = str_replace("-", "", $pcedulafa);
            $enco->nombrefa = replace_sc($pnombrefa);
            $enco->correofa = $pcorreofa;
            $enco->actualizafa();
        }
        if(!$enco->Retira($pCombo,$pCedula,$pNombre,$pestaretiro,$pimpresora))
            $todobien=false;
    }
    if($todobien)
        return "Correcto";
    else
        return "Fallo";
}

function PerfilesInicio($pidusuario){
    $usu = new Usuario();
    $usu->idUsuario=$pidusuario;
    return $usu-> PerfilesInicio();
}

function AgregaPerfilInicio($pidusuario,$pperfil){
    $usu = new Usuario();
    $usu->idUsuario=$pidusuario;
    return $usu->AgregaPerfilInicio($pperfil);
}
function QuitaPerfilInicio($pidusuario,$pperfil){
    $usu = new Usuario();
    $usu->idUsuario=$pidusuario;
   return $usu->QuitaPerfilInicio($pperfil);
}
function DevCierre(){
  $cie = new Cierred();
  $res = $cie->DevCierre();
  return ResultArray($res);
}

function UsuarioxRuta($pidusuario){
    $usu = new Usuario();
    $usu->idUsuario=$pidusuario;
    return $usu-> RutasxUsuario();
}
function AgregaRutaxUsuario($pidusuario,$pruta){
    $usu = new Usuario();
    $usu->idUsuario=$pidusuario;
    return $usu->AgregaRutaxUsuario($pruta);
}
function QuitaRutaxUsuario($pidusuario,$pruta){
    $usu = new Usuario();
    $usu->idUsuario=$pidusuario;
   return $usu->QuitaRutaxUsuario($pruta);
}
function getConsecutivo($pidImpresora){
    $imp = new Impresora();
    $imp->idImpresora=$pidImpresora;
    $imp->Cargar();
    return $imp->consecutivo;
}
function setConsecutivo($pidImpresora,$pconsecutivo){
   
    $imp = new Impresora();
    $imp->idImpresora=$pidImpresora;
    $imp->Cargar();
    $imp->consecutivo =$pconsecutivo;
    $bit= NuevaBitacora();
    $imp->HeredaBitacora($bit);
    $imp->Guardar(null);
    return $pconsecutivo;

}
function setUbicacion($pidEncomienda,$pUbicacion){
    $en = new Encomienda();
    $en->idEncomienda=$pidEncomienda;
    $en->Cargar();
    $en->setUbicacion($pUbicacion);

}
 function EncomiendasAnuladas($filtro){
    $ob = new Encomienda();
    $result=$ob->Nulas($filtro);
    $kk=ResultArray($result);
    return $kk;
}
function debeImprimir($pidEncomienda){
    $enco= new Encomienda();
    $enco->idEncomienda=$pidEncomienda;
    $enco->Cargar();
    $res=0;
    if($enco->formapago->idFormaPago==3)
        $res=2;
    if($enco->producto->idProducto==102)
        $res=1;
    return $res;
}

 function revisaEncomiendaFE(){
    $sql="select count(*) as cuenta,min(fechadi) as fecha from encomienda where hacienda=0 and clavefe != ' ';
";
    $link= new tiqmysql();
    $res = $link->bdEjecutar($sql); 
    $tira = "";
    if($link->bdCantLineas($res)){
      $linea = mysqli_fetch_array($res);
      if($linea["cuenta"]>0){
        $tira = $linea["cuenta"] . " Encomiendas por enviar a Hacienda, fecha mas antigua por enviar " . $linea["fecha"];
      }else{
        $tira="Sin Encomiendas por enviar a Hacienda";
      }
    }
    return $tira;
  }

function getPorIvaXEmpresa($pcedula){
  $emp= new Empresa();
  if($emp->CargarCedula($pcedula)==0)
     return 13;
  $poriva = $emp->getPorIva();
  $par=new Parametro();
  $newexo = $par->Retorna("newcalexo",1);
  if($emp->exodoc!=''){
    if($newexo==1)
       $poriva=$poriva-$emp->exoporce;
    else
       $poriva=$poriva-round($poriva*$emp->exoporce/100,5);
   }

  return  $poriva;
}

function replace_sc($s) {
    $s = preg_replace("/�~A|�~@|�~B|�~C/","A",$s);
    $s = preg_replace("/é|è|ê/","e",$s);
    $s = preg_replace("/�~I|�~H|�~J/","E",$s);
    $s = preg_replace("/í|ì|î/","i",$s);
    $s = preg_replace("/�~M|�~L|�~N/","I",$s);
    $s = preg_replace("/ó|ò|ô|õ|º/","o",$s);
    $s = preg_replace("/�~S|�~R|�~T|�~U/","O",$s);
    $s = preg_replace("/ú|ù|û/","u",$s);
    $s = str_replace("ñ","n",$s);
    $s = str_replace("�~Q","N",$s);

    $s = preg_replace('/[^a-zA-Z0-9\s_.-]/', ' ', $s);
    return $s;
  }

function VendidosxViaje($idViaje){
  $vi = new Viaje();
  $vi->idViaje=$idViaje;
  $res = ResultArray( $vi->CantidadVendida());
  return $res[0]["can"];
}

function ListaCambio($pfechai,$pfechaf){
          $es = new Estadistica();
          $pfechaf=$pfechaf." 23:59";
          $res=$es->listaCambio($pfechai,$pfechaf);
          return ResultArray($res);
}

/***********************  <<CAMBIAR TIQUETES>>
Funcionalidad
    Convierte a facturados una serie de tiquetes antes apartados
Parametros de entrada
        lista:  array con los id de los tiquetes a validar
Retorno
  true si se completó , false si no se pudo completar
*******************************************************/
function CambiarTiquetes($lista){

    $link =new tiqmysql();
    $link->bdAbreTran();
    $res=true;
    try{
            for($x=0;$x<count($lista);$x++)
            {
                    //viejo
                    $tiq = new Tiquete();
                    $tiq->idTiquete=$lista[$x][1];
                    if(!$tiq->Cargar())
                            throw new SimError("Uno de los tiquetes apartados no existe ".$tiq->idTiquete);

                    $sql = "INSERT INTO bitcambio ";
                    $sql .= "(factura,idimpresora,oldidviaje,oldruta,oldidparada1,oldidparada2,oldparada1,oldparada2, ";
                    $sql .= "oldfecha,oldhoraini,oldasiento,newidviaje,newruta,newidparada1,newidparada2,newparada1, ";
                    $sql .= "newparada2,newfecha,newhoraini,newasiento,anombre) VALUES ";
                    $sql .= "('" . $tiq->factura . "','" . $tiq->impresora->idImpresora . "', ";
                    $sql .= "'" . $tiq->viaje->idViaje . "', ";
                    $tiq->viaje->Cargar();
                    $tiq->viaje->ruta->Cargar();
                    $tiq->parada1->Cargar();
                    $tiq->parada2->Cargar();
                    $sql .= "'" . $tiq->viaje->ruta->nombre . "', ";
                    $sql .= "'" . $tiq->parada1->idParada . "', ";
                    $sql .= "'" . $tiq->parada2->idParada . "', ";
                    $sql .= "'" . $tiq->parada1->nombre . "', ";
                    $sql .= "'" . $tiq->parada2->nombre . "', ";
                    $sql .= "'" . $tiq->viaje->fecha . "', ";
                    $sql .= "'" . $tiq->viaje->horaini . "', ";
                    $sql .= "'" . $tiq->asiento . "', ";

                    //nuevo
                    $tiq2 = new Tiquete();
                    $tiq2->idTiquete=$lista[$x][0];
                    if(!$tiq2->Cargar())
                            throw new SimError("Uno de los tiquetes apartados no existe ".$tiq2->idTiquete);

                    $sql .= "'" . $tiq2->viaje->idViaje . "', ";
                    $tiq2->viaje->Cargar();
                    $tiq2->viaje->ruta->Cargar();
                    $tiq2->parada1->Cargar();
                    $tiq2->parada2->Cargar();
                    $sql .= "'" . $tiq2->viaje->ruta->nombre . "', ";
                    $sql .= "'" . $tiq2->parada1->idParada . "', ";
                    $sql .= "'" . $tiq2->parada2->idParada . "', ";
                    $sql .= "'" . $tiq2->parada1->nombre . "', ";
                    $sql .= "'" . $tiq2->parada2->nombre . "', ";
                    $sql .= "'" . $tiq2->viaje->fecha . "', ";
                    $sql .= "'" . $tiq2->viaje->horaini . "', ";
                    $sql .= "'" . $tiq2->asiento . "', ";
                    $sql .= "'" . $tiq2->anombre . "') ";


                    if($tiq2->estado==1)
                            throw new SimError("Uno de los tiquetes apartados ya se venció ".$tiq2->idTiquete);
                    $tiq->HeredaBitacora(NuevaBitacora());
                    $tiq2->HeredaBitacora(NuevaBitacora());
                    $res=($tiq->Cambiar($link,$tiq2)>0);

                    $xotrores = $link->bdEjecutar($sql);

            }
            if(!$res)
                    $link->bdRevierteTran();
            else
                    $link->bdCierraTran();
    }
    catch(SimError $ex){
            $link->bdRevierteTran();
            ElevaError($ex);
            return false;
    }
    return $res;
}


?>
