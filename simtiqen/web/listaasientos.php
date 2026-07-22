 <?php
    include_once('lib/nucleo.php');
     $astos = ListaAsientos($_GET["viaje"]);
     for($x=0;$x<count($astos);$x++){
         echo $astos[$x]["asiento"]."-".$astos[$x]["idestacion"]."-".$astos[$x]["factura"]."-".$astos[$x]["idProducto"];
         if($x+1<count($astos))
            echo ",";
       }
       
  ?>