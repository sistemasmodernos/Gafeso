<?php
  include_once('controles.php');
  inicio("protiq");
  
?>
<div>
<div id='datosres'>
  <?php
  

  $datos=  array(
       "fecha"=>$_POST["fecha"],
       "monto"=>str_replace(",","",$_POST["monto"]),
       "idEstacion"=>$_POST["idEstacion"],
       "idProducto"=>$_POST["idProducto"],
       "idParada1"=>$_POST["idParada1"],
       "idParada2"=>$_POST["idParada2"],
       "idCliente"=>$_POST["idCliente"],
       "idImpresora"=>$_POST["idImpresora"],
       "cedula"=>$_POST["cedula"],
       "nombre"=>$_POST["nombre"],
       "comentario"=>$_POST["comentario"],
       "fechanac"=>$_POST["fechanac"],
       "numtar"=> ""
      );

   $juntos=($_POST["juntos"]=='1');
   $cantidad=$_POST["cantasiento"];
   $asiento=$_POST["asiento"];
   $viaje = $_POST["idViaje"];
   $depie = $_POST["cantasientop"];
   $marcados = explode(',',$_POST["marcados"]);
   
   if(count($marcados)>1 &&  $marcados[0]!="") //si trae la matriz datos y no estan vacios
    $res = ApartarTiquetes2($viaje,$datos,$marcados);
   else
    $res = ApartarTiquetes($viaje,$cantidad,$asiento,$datos,$juntos,$depie);


  if($res==null)
     echo "Ocurri&oacute; un error al apartar los asientos: ".$_SESSION["parametros"]["mensaje_error"];
  else{
    $res2=VenderTiquetes($res);
    if(!$res2)
    echo "Ocurri&oacute; un error al vender los tiquetes, los asientos se liberar&aacute; en unos segundos ".
    "<br/>Detalle del error: ".pasaHtml($_SESSION["parametros"]["mensaje_error"]);
    else{
        
             
        $cc = '"';
     $detalle="<table><thead><tr><th>Tiquete</><th>Asiento</th><th>Fecha</th><th>Monto</th></tr></thead><tbody>";
      $total=0; 
      for($x=0;$x<count($res);$x++){
        $cc .= $res[$x];
        $eltiq=ListaActivos("Tiquete",array("idtiquete=".$res[$x]));
        $detalle.="<tr><td>".$eltiq[0]["factura"] ."</td>".
        "<td>".$eltiq[0]["asiento"]."</td>".
        "<td>". fechatoNatural(substr($eltiq[0]["fechaviaje"],0,10))." ".$eltiq[0]["hora"]."</td>".
        "<td>". number_format($eltiq[0]["monto"],2) ."</td></tr>";
         $total+= $eltiq[0]["monto"];
          if($x!=count($res)-1)
            $cc.=',';
        }
        $detalle.="</tbody><tfoot><tr><th colspan='3'>Total</th><th>".number_format($total,2)."</td></tr></tfoot>";
        $detalle.="</table>";
      $cc .= '"';
      echo "<input type='hidden' id='txttotalvta'  value='".$total."'>";
      echo "<input type='hidden' id='txttiquetesvendidos'  value=".$cc.">";
      echo "Los tiquetes se vendieron correctamente <br/>";
      
      echo $detalle.
      "<br/>Desea imprimirlos ? <input type='button' value='Imprimir' onclick='imprimetiq(".$cc.");'> ";
      }
  }
  
  ?>
  <br>
  <textarea rows="3" cols="40" id='txtmsg' visible='false'>
  </textarea>
</div>
</div>