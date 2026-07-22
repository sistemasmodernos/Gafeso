<?php

class mimysql{
 var $basedatos;
 var $con;
  var $tranabierta;
  
function bdAbrir()
{
  /* deprecate 
  $this->con = mysqli_connect("localhost", "root", "tomk57",true)
        or die("No se pudo establecer conexión con la base de datos, intente de nuevo");
  mysqli_select_db($this->basedatos)
   or die("No se pudo accesar la base de datos 1");        
   */
   
   $this->con = new mysqli("localhost", "root", "root",  $this->basedatos);
   //var_dump($this->con);
if ($this->con->connect_errno) {
    echo "Fallo al contenctar a MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}

}
/*
function bdAbrir()
{
  $this->con = mysqli_connect("10.0.0.7", "root", "twrwe3343",true)
        or die("No se pudo establecer conexión con la base de datos, intente de nuevo");
  mysqli_select_db($this->basedatos)
   or die("No se pudo accesar la base de datos test");        
}
*/
/*
function bdEscoger($pBD,$link){
	return mysqli_select_db ($pBD,$link);
}
*/
function bdCerrar(){
	if($this->con!=null){
	  $this->con->close();
	 $this->con=null;
	}
} 

function bdEjecutar($tira)
{
 error_reporting(E_ALL);
ini_set('display_errors', '1');
date_default_timezone_set('America/Costa_Rica');
 if($this->con==null){
  $this->bdAbrir();
  $conabierta=false;  
}else
  $conabierta=true;
  //deprecate
 // $resultado = mysqli_query($tira,$this->con);
  $resultado = $this->con->query($tira);
  if($this->con->errno){
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
 $resu1 = mysqli_num_rows($resulquery1);
   return $resu1;
}

function bdUltimoId(){
	if($this->con==null){
		$this->bdAbrir();
		$conabierta=false;  
	}else
		$conabierta=true;
	$resid= mysqli_insert_id($this->con);
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
    $row = $result->fetch_array();
    return $row[0];
}

function PrimerValorD($xsql){
    $result=$this->bdEjecutar($xsql);
    var_dump($result);
    $row = $result->fetch_array();
    return $row[0];
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
      $numer=$this->con->errno;     
      echo var_dump($this->con->error());
      if(array_key_exists ($numer,$errores))
         $texto =$errores[$numer][1];
      else
         $texto = $this->con->error()." numerror -> ".$numer;
      //echo "Error al cargar en la base de datos<br/>".$texto."<br/>";
      return $texto;
   }


}


class tiqmysql extends mimysql{
	function tiqmysql() {
		$this->basedatos="transtusa";
	}
}


?>
