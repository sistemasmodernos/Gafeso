<?php


function dar_fecha() {
   $today = getdate(); 
   $month = $today['mon']; 
   $mday = $today['mday']; 
   $year = $today['year']; 
   return "$mday/$month/$year";
}
function dia_mayor($mes) {
switch ($mes) {
case 1:return 31 ; break;
case 2:return 28 ; break;
case 3:return 31 ; break;
case 4:return 30 ; break;
case 5:return 31 ; break;
case 6:return 30 ; break;
case 7:return 31 ; break;
case 8:return 31 ; break;
case 9:return 30 ; break;
case 10:return 31 ; break;
case 11:return 30 ; break;
case 12:return 31 ; break;
}
}

function diasemana($parafecha) {
$unavariable = date("w",strtotime($parafecha)) ;
switch ($unavariable) {
case 0:return "Domingo " ; break;
case 1:return "Lunes " ; break;
case 2:return "Martes " ; break;
case 3:return "Miercoles " ; break;
case 4:return "Jueves " ; break;
case 5:return "Viernes " ; break;
case 6:return "Sábado " ; break;
}
}

function letrames($parafecha) {
$unavariable = date("m",strtotime($parafecha)) ;
switch ($unavariable) {
case 1:return "Enero " ; break;
case 2:return "Febrero " ; break;
case 3:return "Marzo " ; break;
case 4:return "Abril " ; break;
case 5:return "Mayo " ; break;
case 6:return "Junio " ; break;
case 7:return "Julio " ; break;
case 8:return "Agosto " ; break;
case 9:return "Setiembre " ; break;
case 10:return "Octubre " ; break;
case 11:return "Noviembre " ; break;
case 12:return "Diciembre " ; break;
}
}

function sqlClearText($sqltext){
 $nopermitidos = array("'",'\\','<','>',"\"",";"); 
 $mensaje = str_replace($nopermitidos, "", $sqltext); 
 return $mensaje; 
 //return addcslashes(mysqli_real_escape_string($sqltext),'%_');
}

function fechatoUTC($fecha){
    // pasa de 31/12/2000 a 2000-12-31
    $cad = explode("/",substr($fecha,0,10));
    $res=$cad[2]."-".$cad[1]."-".$cad[0];
    
    return $res;
}
function fechatoNormal($fecha){
    $cad=explode("-",substr($fecha,0,10));
    $res=$cad[2]."/".$cad[1]."/".$cad[0];
    return $res;
}

function fechatoMes($parafecha) {
  return date("m",strtotime($parafecha)) ;
}

function fechatoDia($parafecha) {
  return date("d",strtotime($parafecha)) ;
}

function fechatoAnno($parafecha) {
  return date("Y",strtotime($parafecha)) ;
}

function limpianum($numero){
     return str_replace(",","",$numero);
}

function ordenaMatriz($dat,$campo){
    if(count($dat)==0)
      return $dat;
     $menor=$dat[0][$campo];
       for($y=0;$y<count($dat);$y++){
        for($x=$y+1;$x<count($dat);$x++){
           if($dat[$x][$campo]<$dat[$y][$campo]){
               $temp=$dat[$y];
               $dat[$y]=$dat[$x];
               $dat[$x]=$temp;
           }
        }
       }
       return $dat;
}


function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

?>