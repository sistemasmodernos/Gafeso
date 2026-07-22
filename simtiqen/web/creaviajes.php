<?php
include_once('./lib/nucleo.php');
session_start() ;
$_SESSION["idusuario"]=4;
$desde=date('Y-m-d',strtotime("+60 day",strtotime(date('Y-m-d'))));
$hasta=date('Y-m-d',strtotime("+61 day",strtotime(date('Y-m-d'))));

$res=ClonaViajes($desde,$hasta);
if($res>0)
   echo "<h1> Los horarios fueron creados correctamente </h>";
else
   echo "<h1> Ocurrió un error al crear los horarios </h>";




?>
