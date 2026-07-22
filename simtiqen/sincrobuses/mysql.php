<?php

class mimysql{
 var $basedatos;
 var $con;
  var $tranabierta;
  var $servidor;
  var $user;
  var $pass;
  
  
function bdAbrir()
{
  $this->con = mysql_connect($this->servidor, $this->user, $this->pass,true)
        or die("No se pudo establecer conexión con la base de datos, intente de nuevo");
  mysql_select_db($this->basedatos)
   or die("No se pudo accesar la base de datos test");        
}
/*
function bdEscoger($pBD,$link){
	return mysql_select_db ($pBD,$link);
}
*/
function bdCerrar(){
	if($this->con!=null){
	  mysql_close($this->con);
	 $this->con=null;
	}
} 

function bdEjecutar($tira)
{
 
 if($this->con==null){
  $this->bdAbrir();
  $conabierta=false;  
}else
  $conabierta=true;
  $resultado = mysql_query($tira,$this->con);
  if($this->errno()){
         //hay error
         die( $this->debugDB());
    //or die("Existen problemas con la base de datos (" .$tira. ")" );
  }
  if(!$conabierta)
    $this->bdCerrar($conabierta);  
  return $resultado ;
}

function bdCantLineas($resulquery1)
{
 $resu1 = mysql_num_rows($resulquery1);
   return $resu1;
}

function bdUltimoId(){
	if($this->con==null){
		$this->bdAbrir();
		$conabierta=false;  
	}else
		$conabierta=true;
	$resid= mysql_insert_id($this->con);
    if(!$conabierta)
		$this->bdCerrar($conabierta);  
	return $resid;
}

function bdAbreTran() {
	if($this->con==null){
		$this->bdAbrir();
		$conabierta=false;  
	}else
		$conabierta=true;

	$this->bdEjecutar("BEGIN");
	return true;
}
function bdCierraTran() {
	$this->bdEjecutar("COMMIT");
	$this->bdCerrar();
	return true;
}
function bdRevierteTran() {
	$this->bdEjecutar("ROLLBACK");
	$this->bdCerrar();
	return true;

}
function PrimerValor($result){
    $row = mysql_fetch_array($result, MYSQL_NUM);
    return $row[0];
}

function PrimerValorD($xsql){
    $result=$this->bdEjecutar($xsql);
    $row = mysql_fetch_array($result, MYSQL_NUM);
    return $row[0];
}

 function errno(){
      $error = mysql_errno($this->con);
      return $error;
   }
   
   function error(){
      $error = mysql_error($this->con);
      return $error;
   }
   
   function debugDB(){
      $errores = array(
                  1062 => array(
                           "/Duplicate entry ('.*') for key [0-9]/",
                           'El valor \\1 esta siendo utilizado por otro registro '
                           ),
                  1216 => array(
                           "/Cannot add or update a child row: a foreign key constraint fails/",
                           "No se pudo agregar/actualizar hay datos que referencian a otras tablas y no existen"
                           ),
                  1217 => array(
                           "/Cannot delete or update a parent row: a foreign key constraint fails/",
                           "No se puede borrar/actualzar este registro porque esta relacionado con otros conceptos"
                           ),
                  1451 => array(
                           "/Cannot delete or update a parent row: a foreign key constraint fails/",
                           "No se puede borrar/actualzar este registro porque esta relacionado con otros conceptos"
                           )
                  );
      $numer=$this->errno();            
      if(array_key_exists ($numer,$errores))
         $texto =$errores[$numer][1];
      else
         $texto = $this->error()." numerror -> ".$numer;
      //echo "Error al cargar en la base de datos<br/>".$texto."<br/>";
      return $texto;
   }


}


class tiqmysql extends mimysql{
	function __construct() {
		$this->basedatos="dbtiquetes";
        $this->servidor="64.15.136.237";
        $this->user="conexion.rialzeprueba";
        $this->pass="123456";
	}
}

class tiqmysql2 extends mimysql{
	function __construct() {
		$this->basedatos="simosa";
        $this->servidor="64.15.136.237";
        $this->user="conexion.simosa";
        $this->pass="enTnWa";
	}
}

?>
