<?php
session_start();
session_unset();
session_destroy();
include_once ('lib/nucleo.php');
include_once('lib/parchebuses.php');
/*$us= new Usuario();
$us->nombrelargo="pruebas";
$us->nombre='prueba';
$us->email='';
$us->impresora->idImpresora=1;
$us->ruta->idRuta=1;
$us->estacion->idEstacion=1;
$us->activo=1;
$bit=NuevaBitacora();
$us->HeredaBitacora($bit);
$us->Guardar(null);
$us->CambiarClave("1234");

session_start();
echo session_id();
*/
error_reporting(E_ALL);
ini_set('error_reporting', E_ALL);
ini_set('display_errors','On');
parchearhoras();
parchearlista();

?>
