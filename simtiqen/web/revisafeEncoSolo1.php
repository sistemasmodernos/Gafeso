<?php 
	require __DIR__.'/vendor/autoload.php';
	use Spipu\Html2Pdf\Html2Pdf;
	$html2pdf = new Html2Pdf();
	session_start();
session_unset();
session_destroy();
include_once ('lib/nucleo.php');
include_once('lib/parchebuses.php');
include_once('lib/facturaenco.php');
  $link= new tiqmysql();
  $res =  $link->bdEjecutar("select idEncomienda 
  		from encomienda 
  		where facturafe!='' and hacienda=0 order by idEncomienda limit 1");

  echo "Econtró ".$link->bdCantLineas($res)." facturas <br/> \n";

  while($lin =  mysqli_fetch_array($res))
  {
  	 $html2pdf = new Html2Pdf();
  	 $fa = new FacturaEnco();
     $fa->html2pdf = $html2pdf;
  	 echo "Enviando ".$lin["idEncomienda"]." <br/> \n";
     $res2 = $fa->generafactura($lin["idEncomienda"]);
     echo "\n\rRespuesta -> ".$res2 ."\n";
 if((strpos($res2, '<estado>OK</estado>')!==false) || (strpos($res2,'0. El documento ya existe en la base de datos')!==false) )
     	$link->bdEjecutar("update encomienda set hacienda=1 
     		where idEncomienda=".$lin["idEncomienda"]);
  }
  $res =  $link->bdEjecutar("select idEncomienda 
      from encomienda 
      where facturancfe!='' and haciendanc=0 order by idEncomienda limit 10");

  echo "Econtró ".$link->bdCantLineas($res)." Notas de credito <br/> \n";

  while($lin =  mysqli_fetch_array($res))
  {
     $html2pdf = new Html2Pdf();
     $fa = new FacturaEnco();
     $fa->html2pdf = $html2pdf;
     echo "Enviando ".$lin["idEncomienda"]." <br/> \n";
     $res2 = $fa->generanotacredito($lin["idEncomienda"]);
     echo $res2 ."\n";
     if((strpos($res2, '<estado>OK</estado>')!==false) || (strpos($res2,'0. El documento ya existe en la base de datos')!==false) )
      $link->bdEjecutar("update encomienda set haciendanc=1 
        where idEncomienda=".$lin["idEncomienda"]);
  }


  echo "Fin de rutina ";
  echo "\n\n";
?>
