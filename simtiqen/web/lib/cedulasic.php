<?php

/* Funciona Giovanni 10-05-2011 */

/* Clientes */
class CedulaSIC{
  var $idCedula;
  var $nombre;
  var $cedula;
  var $provincia;
  var $canton;
  var $distrito;
  var $barrio;
  var $otrasSenas;
    
  //Contructor
  function Cliente(){
     $this->Limpiar();
  }
  
  //Limpia las propiedades    
  function Limpiar(){
     $this->idCedula = "";
     $this->nombre = "";
     $this->cedula = "";
     $this->provincia ="1";
     $this->canton="01";
     $this->distrito="01";
     $this->barrio="01";
     $this->otrasSenas="";
  }
  
  //Carga los datos del Cliente
  function Cargar(){
  	 $objMy = new tiqmysql();
    $conexion = $objMy->bdAbrir();
    $consulta = sprintf($this->Seleccion()." from cedfissic where cedula = '%s'",sqlClearText($this->idCedula));
    $resulquery = $objMy->bdEjecutar($consulta,$conexion);
    if ($objMy->bdCantLineas($resulquery)==1){
       $linea = mysqli_fetch_array($resulquery);
       $this->nombre = $linea["nombre"];
       $this->cedula = $linea["cedulasic"];
       return $this->idCedula;
    }else{
      return 0;
    }
  }
  
  function CargarId($pidCedula){
      $this->idCedula = $pidCedula;
      return $this->Cargar();
  }
  
  function Seleccion(){
    return "Select  cedula,nombre,cedulasic ";
  }
  
  function ListarActivos($filtro){
       $link=new tiqmysql();
       $tira = "";
	   $otrocampo = "";
	   $xorden = " order by nombre " ;
       if ($filtro == null){
       	$tira = "";
       }else{
			$primero = true;
			for($i = 0;$i<count($filtro);$i++){ 
				$valor = $filtro[$i];
				if (strpos($valor,"like")){
				   $busca = "match(" . substr($valor,0,strpos($valor,"like")) . ") against (" . substr($valor,strpos($valor,"like")+4) . ")";
				   $valor = $busca;
				   $otrocampo = ", " . $busca . " as coincidencia ";
				   $xorden = " order by 4 desc";
				}
				if (primero){
					$tira .= " where " . $valor;
				}else{
					$tira .= " and " . $valor;
				}
			}
       }
       $sql=$this->Seleccion() . $otrocampo ." from cedfissic ".$tira . $xorden . " limit 0,100 ";
       return $link->bdEjecutar($sql);
    }
}
?>
