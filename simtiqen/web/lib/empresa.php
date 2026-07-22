<?php

/*  Giovanni, 12-12-2014 */

/* Empresaes */
class Empresa{
  var $idEmpresa;
  var $nombre;
  var $cedula;
  var $tele1;
  var $tele2;
  var $direccion;
  var $encargado;
  var $activo;
  var $credito;
  var $fechadi;
  var $bitacora;
  var $tarifaimp;
  var $exodoc;
  var $exofecha;
  var $exoentidad;
  var $exoporce;
  var $exotipo;
  
  //Contructor
  function __construct(){
   $this->Limpiar();
 }

  //Limpia las propiedades    
 function Limpiar(){
   $this->idEmpresa = 0;
   $this->nombre = "";
   $this->cedula = "";
   $this->tele1 = "";
   $this->tele2 = "";
   $this->direccion = "";
   $this->encargado = "";
   $this->activo = 0;
   $this->credito = 0;
   $this->fechadi = date("Y-m-d H:i:s");
   $this->tarifaimp='08';
   $this->exodoc="";
   $this->exofecha="1900-01-01";
   $this->exoentidad="";
   $this->exoporce=100;
   $this->exotipo='03';
 }

  //Carga los datos del Empresa
 function Cargar(){
   $objMy = new tiqmysql();
   $conexion = $objMy->bdAbrir();
   $consulta = sprintf($this->Seleccion()." from empresa where idEmpresa = %s",sqlClearText($this->idEmpresa));
   $resulquery = $objMy->bdEjecutar($consulta,$conexion);
   if ($objMy->bdCantLineas($resulquery)==1){
    $linea = mysqli_fetch_array($resulquery);
    $this->nombre = $linea["nombre"];
    $this->cedula = $linea["cedula"];
    $this->tele1 = $linea["tele1"];
    $this->tele2 = $linea["tele2"];
    $this->direccion = $linea["direccion"];
    $this->encargado = $linea["encargado"];
    $this->activo = $linea["activo"];
    $this->credito = $linea["credito"];
    $this->fechadi = $linea["fechadi"];
    $this->tarifaimp = $linea["tarifaimp"];
    $this->exodoc = $linea["exodoc"];
    $this->exofecha = substr($linea["exofecha"], 0,10);
    $this->exoentidad = $linea["exoentidad"];
    $this->exoporce = $linea["exoporce"];
    $this->exotipo = $linea["exotipo"];
    $this->poneGuiones();
    return $this->idEmpresa;
  }else{
    return 0;
  }
}

function CargarId($pIdEmpresa){
  $this->idEmpresa = $pIdEmpresa;
  return $this->Cargar();
}

function CargarCedula($pCedula){
  $sql = "select idempresa from empresa where cedula = '" . $pCedula . "'";
  $link = new tiqmysql();
  $valor = $link->PrimerValorD($sql);
  if ($valor==null){
   return 0;
 }else{
   return $this->CargarId($link->PrimerValorD($sql));
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
  $this->cedula = $parametros["cedula"];
  $this->tele1 = $parametros["tele1"];
  $this->tele2 = $parametros["tele2"];
  $this->direccion = $parametros["direccion"];
  $this->encargado = $parametros["encargado"];
  $this->credito = $parametros["credito"];
  $this->activo = $parametros["activo"];
  $this->tarifaimp = $parametros["tarifaimp"];
  $this->exodoc = $parametros["exodoc"];
  $this->exofecha = $parametros["exofecha"];
  $this->exoentidad = $parametros["exoentidad"];
  $this->exoporce = $parametros["exoporce"];
  $this->exotipo = $parametros["exotipo"];
  $retorno = true;
  break;
  case 2:
  $this->idEmpresa = $parametros["idempresa"];
  $this->nombre = $parametros["nombre"];
  $this->tele1 = $parametros["tele1"];
  $this->tele2 = $parametros["tele2"];
  $this->direccion = $parametros["direccion"];
  $this->encargado = $parametros["encargado"];
  $this->credito = $parametros["credito"];
  $this->cedula = $parametros["cedula"];
  $this->activo = $parametros["activo"];
  $this->tarifaimp = $parametros["tarifaimp"];
  $this->exodoc = $parametros["exodoc"];
  $this->exofecha = $parametros["exofecha"];
  $this->exoentidad = $parametros["exoentidad"];
  $this->exoporce = $parametros["exoporce"];
  $this->exotipo = $parametros["exotipo"];
  $retorno = true;
  break;
  case 3:
  $this->idEmpresa = $parametros["idempresa"];
  $retorno = $this->Cargar();
  break;
  case 3:
  $this->idEmpresa = $parametros["idempresa"];
  $retorno = $this->Cargar();
  break;
}
return $retorno;
}

  //Guarda los datos , recibe como paremetro la transacción si existe 
function Guardar($link){
  $this->Validar();
  try {
   $nueva = ($this->idEmpresa == 0);
   $this->quitaGuiones();
   if ($this->idEmpresa == 0){
    $consulta = sprintf("insert into empresa (nombre,cedula,tele1,tele2,encargado,
      direccion,activo,credito,fechadi,tarifaimp,exodoc,exofecha,exoentidad,exoporce,exotipo) 
      values ('%s','%s','%s','%s','%s','%s',%s,%s,'%s','%s','%s','%s','%s',%d,'%s')",
     sqlClearText($this->nombre),
     sqlClearText($this->cedula),
     sqlClearText($this->tele1),
     sqlClearText($this->tele2),
     sqlClearText($this->encargado),
     sqlClearText($this->direccion),
     $this->activo,
     $this->credito,
     sqlClearText($this->fechadi),
     sqlClearText($this->tarifaimp),
     sqlClearText($this->exodoc),
     sqlClearText($this->exofecha),
     sqlClearText($this->exoentidad),
     $this->exoporce,
     sqlClearText($this->exotipo)
    );
    $bit = "Agrega el Empresa ".$this->nombre;
  }else{
    $consulta = sprintf("update empresa set 
     nombre = '%s',
     cedula = '%s',
     tele1 = '%s',
     tele2 = '%s',
     direccion = '%s',
     encargado = '%s',
     activo = %s,
     credito = %s ,
     tarifaimp = '%s',
     exodoc='%s',
     exofecha = '%s',
     exoentidad = '%s',
     exoporce= %d,
     exotipo='%s'
     where idEmpresa = %s",
     sqlClearText($this->nombre),
     sqlClearText($this->cedula),
     sqlClearText($this->tele1),
     sqlClearText($this->tele2),
     sqlClearText($this->direccion),
     sqlClearText($this->encargado),
     $this->activo,
     $this->credito,
     sqlClearText($this->tarifaimp),
     sqlClearText($this->exodoc),
     sqlClearText($this->exofecha),
     sqlClearText($this->exoentidad),
     $this->exoporce,
     sqlClearText($this->exotipo),
     sqlClearText($this->idEmpresa)
     );
    $bit = "Modifica el Empresa ".$this->idEmpresa." ".$this->nombre;
  }

  if($link==null){
   $link = new tiqmysql();
   $cerrartran=true;
 }
 else 
   $cerrartran=false;
 $link->bdAbreTran();
 $res = $link->bdEjecutar($consulta);
 if($nueva)
  $this->idEmpresa=$link->bdUltimoId();
$this->bitacora->bitacorizar($bit);
if($cerrartran)
  $link->bdCierraTran();
}
catch (SimError $ex) {
 throw $ex;
}
return $this->idEmpresa;
}

    //Valida los campos  
function Validar(){
  $errores="";
  if(strlen($this->nombre) <= 0)
    $errores.="Detalle de la Empresa en blanco";
  if(strlen($this->cedula) <= 0)
    $errores.="Cédula de la Empresa en blanco";
  if($errores!="")		
   throw new SimError($errores);      
}

    //Borra el Empresa
function Borrar(){
  if ($this->idEmpresa > 0){
   $link = new tiqmysql();
   $consulta = sprintf("delete from empresa where idEmpresa = %s",sqlClearText($this->idEmpresa));
   $resultado = $link->bdEjecutar($consulta);
   if ($resultado > 0){
    $this->bitacora->Bitacorizar("Elimina la Empresa ".$this->idEmpresa." ".$this->nombre);
  }
  return $resultado;
}else{
 return 0;
}
}

    //Pasa el objeto a un string 
function ToString(){
  $tira = "Id = " . $this->idEmpresa . "\n";
  $tira .= "Nombre = " . $this->nombre . "\n";
  $tira .= "Cédula = " . $this->cedula . "\n";
  $tira .= "Teléfono 1 = " . $this->tele1 . "\n";
  $tira .= "Teléfono 2 = " . $this->tele2 . "\n";
  $tira .= "Encargado = " . $this->encargado . "\n";
  $tira .= "Dirección = " . $this->direccion . "\n";
  $tira .= "Tarifa de impuesto = ".$this->tarifaimp."\n";
  $tira .= "Documento que exonera = ".$this->exodoc."\n";
  $tira .= "Fecha de la exoneración = ".$this->exofecha."\n";
  $tira .= "Entidad que exonera = ".$this->exoentidad."\n";
  $tira .= "Porcentaje exonerado = ".$this->exoporce."\n";
  if ($this->credito==1){
   $tira .= "Tiene crédito = SI\n";
 }else{
   $tira .= "Tiene crédito = NO\n";
 }
 if ($this->activo==1){
   $tira .= "Estado = Activo\n";
 }else{
   $tira .= "Estado = Inactivo\n";
 }
 return $tira;
}

    //Hereda el objeto bitácora    
function HeredaBitacora($pbit){
  $pbit->nivel++;		
  $this->bitacora=$pbit;
  $this->bitacora->obPadre=$this;
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
return $link->bdEjecutar("select * from empresa " . $tira . " order by nombre ". $limite);
}      

	 //Listar
function ListarActivos($filtro){
 $link=new tiqmysql();
 $tira = "";
 $limite = "";
 if ($filtro == null){
  $tira = "";
}else{
 $primero = true;
 for($i = 0;$i<count($filtro);$i++){ 
  $valor = $filtro[$i];
  if ($primero){
   $tira .= " and " . $valor;
   $primero = false;
 }else{
   $tira .= " and " . $valor;
 }
}
}
return $link->bdEjecutar("select * from empresa where activo = 1 " . $tira . " order by nombre ". $limite);
}      

function autorizados(){
 $link=new tiqmysql();
 $tira = "select idempresa,idautoriza,nombre,cedula from autoriza where idempresa = " . $this->idEmpresa . " order by nombre ";
 return ResultArray($link->bdEjecutar($tira));
}      

function EliminaAutoriza($pidAutoriza){
  if ($pidAutoriza > 0){
   $link = new tiqmysql();
   $consulta = sprintf("delete from autoriza where idAutoriza = %s",sqlClearText($pidAutoriza));
   $resultado = $link->bdEjecutar($consulta);
   return $resultado;
 }else{
   return 0;
 }
}

function GuardarAutorizado($pCedula,$pNombre){
  $retorno = 0;
  try {
   $consulta = sprintf("insert into autoriza (idempresa,nombre,cedula) values (%s,'%s','%s')",
    sqlClearText($this->idEmpresa),
    sqlClearText($pNombre),
    sqlClearText($pCedula));
   $link = new tiqmysql();
   $cerrartran=true;
   $link->bdAbreTran();
   $res = $link->bdEjecutar($consulta);
   $retorno=$link->bdUltimoId();
   if($cerrartran)
    $link->bdCierraTran();
}
catch (SimError $ex) {
 throw $ex;
}
return $retorno;
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
return $link->bdCantLineas($link->bdEjecutar("select idempresa from empresa " . $tira));
}

function Seleccion(){
  return "Select idEmpresa,nombre,cedula,tele1,tele2,direccion
  ,encargado,activo,credito,fechadi,tarifaimp
  ,exodoc,exofecha,exoentidad,exoporce,exotipo ";
}

function quitaGuiones(){
 $xlaced = $this->cedula;
 $res = $this->cedula;
 if (strpos($xlaced, '-')==2 && strpos($xlaced, '-', 3)==7){
   $xlaced =  substr($res,0,2) . substr($res,3,4) . substr($res,8,4);
 }
 if (strpos($xlaced, '-')==1 && strpos($xlaced, '-', 3)==5){
   $xlaced = substr($res,0,1) . substr($res,2,3) . substr($res,6,6);
 }
 $this->cedula = $xlaced;
}

function poneGuiones(){
  $xlaced =  substr($this->cedula,0,1) . "-" . substr($this->cedula,1,3) . "-" . substr($this->cedula,4,6);
  $this->cedula = $xlaced;
}

function getPorIva(){
  $poriva=13;
  switch ($this->tarifaimp) {
    case '01':
      $poriva=0;
      break;
    case '02':
      $poriva=1;
      break;
    case '03':
      $poriva=2;
      break;
    case '04':
      $poriva=4;
      break;
    case '05':
      $poriva=0;
      break;
    case '06':
      $poriva=4;
      break;
    case '07':
      $poriva=8;
      break;
    default:
      $poriva=13;
      break;
  }
  return $poriva;
}


}
?>
