<?php
 $tiqs=explode(",",$_GET["id"]);
  include_once('lib/nucleo.php');
  include_once('lib/mascaras.php');
 $cad="";
 for($x=0;$x<count($tiqs);$x++)
   $cad.= ImprimeSticker($tiqs[$x]);
 echo $cad;
?>
