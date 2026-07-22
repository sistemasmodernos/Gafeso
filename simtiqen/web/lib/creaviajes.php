<?php
include_once('nucleo.php');

$desde=strtotime("+60 day",strtotime(date('Y-m-d')));
$hasta=$desde;

$horarios = simplexml_load_file('horariosfijos.xml');
  $start = strtotime("-1 day",strtotime($desde)); 
  $end = strtotime($hasta); 
  $oviaje= new Viaje();
  $date = $start; 
   while($date < $end) 
  { 
   $date = strtotime("+1 day", $date);
   foreach($horarios->ruta as $lruta){
         
   	    $ruta = $lviaje["id"];
		foreach($lruta->viaje as $lviaje){
   	     //verifica que no exista ya
		    if(date($date,"w")==$lviaje["dia"]){
            	$xres=$oviaje->Listar(array("idruta=".$ruta, " fecha='".date('Y-m-d',$date)."'","hora='".$lviaje. "'"), null);
        
         		if(mysqli_num_rows($xres)==0){
            		$parametros=array(
              		"idviaje"=>$viaje,
              		"idruta"=>$ruta,
              		"fecha"=>date("Y-m-d",$date),
              		"horas"=>array("1"=>$$lviaje),
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
      }
   } 


?>
