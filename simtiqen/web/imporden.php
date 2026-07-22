<?php
  include_once('controles.php');
  inicio("proenco");

  $orden = new Ocompra();
  if(isset($_SESSION["productos"])){
    $productos=$_SESSION["productos"];
    }
  else  {
     $productos=Array();
     }
$idcompra=0;
$proveedor="";
$fecha="";
$correo="";
$credito=0;
$notas="";
$solicitado=""; 
 $rescorreo=""; 
 $remitente="";
 $placa="";
 $cedula="";
  if(isset($_GET["id"])){
    $orden->idCompra=$_GET["id"];
    $idcompra=$_GET["id"];
    $orden->Cargar();

      $proveedor=$orden->proveedor;
      $fecha =substr($orden->fecha,0,10);
      $correo=$orden->correo;
      $credito=$orden->credito;
      $notas=$orden->notas;
      $solicitado=$orden->solicitado; 
      $remitente=$orden->remitente;
      $placa=$orden->placa;
      
      if($orden->idremitente==1)
        $cedula="3-101-077448";
      else
        $cedula="3-102-010686";
    $productos=Array();
    for($x=0;$x<count($orden->lineas);$x++){
        $lin=Array("idLinea"=>$orden->lineas[$x]->idLinea,
        "codigo"=>$orden->lineas[$x]->codigo,
        "producto"=>$orden->lineas[$x]->descripcion,
        "cantidad"=>$orden->lineas[$x]->cantidad,
        "precio"=>$orden->lineas[$x]->precio,
        "descuento"=>$orden->lineas[$x]->descuento,
        "exento"=>$orden->lineas[$x]->exento,
        "impuesto"=>$orden->lineas[$x]->impuesto,
        "bruto"=>0,"total"=>0
        );
        array_push($productos,$lin);
    
}
//recalcula
for($x=0;$x<count($productos);$x++)
{
    $productos[$x]["bruto"]=$productos[$x]["cantidad"]*$productos[$x]["precio"];
    $productos[$x]["total"]=$productos[$x]["bruto"]-$productos[$x]["descuento"]+$productos[$x]["impuesto"];

}
}
?>

<html>
<head>
   <meta http-equiv="content-type" content="text/html; charset=utf-8" /> 
  <title>Orden de Compra</title> 
  <style>
       .monto{
           text-align:right;
}
  #tablapro{margin:10px;}
   #tablapro td {
    padding:2px 5px 2px 5px;
}

    #tablapro {
    border-collapse:collapse;
}
  #tablapro tfoot th{text-align:right;
   padding-right:5px;}

  #tablainfo th{ text-align:right;
   padding-right:5px;
}
  
.encabezado{
    font-size:200%;
    font-weight:bold;
}
.numerooc{
    float:right;
    margin-right:20px;
    font-weight:bold;
    font-size:150%;
    }
  </style>
</head>
<body>
<div> 
   <span class='encabezado'><?php echo $remitente."<br/><span style='font-size:80%;'>".$cedula."</span><br/><br/>";  ?></span>
</div>
<div>
 <span style='font-weight:bold;'> ORDEN DE COMPRA </span>
 <div class='numerooc'>N&uacute;mero de OC: <?php echo $idcompra; ?></div>
</div>
<hr style='100%'/>
<div>
    <table id='tablainfo'>
      <tr><th>Proveedor:</th><td><?php echo $proveedor; ?> </td></tr>
      <tr><th>Fecha:</th><td><?php echo FechatoNatural($fecha); ?></td></tr>
      <tr><th>Correo:</th><td><?php echo $correo; ?></td></tr>
      <tr><th>Cr&eacute;dito:</th><td><?php echo $credito; ?> d&iacute;as</td></tr>
      <tr><th>Placa</th><td><?php echo $placa; ?></td></tr>
    </table>
</div>
<hr style='100%'/>
<div>
S&iacute;rvanse despachar los siguientes productos a nuestro nombre:
  <table id='tablapro' border='1px'>
    <thead>
       <tr>
         <th>C&oacute;digo</th>
         <th>Producto</th>
         <th>Cantidad</th>
         <th>Precio</th>
         <th>Monto Bruto</th>
         <th>Exento</th>
       </tr>
    </thead>
    <tbody>
      <?php 
      
       for($x=0;$x<count($productos);$x++){
           $lin=$productos[$x];
       echo "<tr>
          <td>".$lin["codigo"]."</td>
          <td style='minweigth:300px;' >".$lin["producto"]." </td>
          <td>".$lin["cantidad"]."</td>
          <td style='text-align:right;'>".number_format($lin["precio"],2)."</td>
          <td style='text-align:right;'>".number_format($lin["bruto"],2)."</td>
           <td>No</td>
       </tr>
       ";
        $tbruto+=$productos[$x]["bruto"];
        $tdescuento+=$productos[$x]["descuento"];
        $timpuesto+=$productos[$x]["impuesto"];

   }
   
       ?>

  </tbody>
        <tfoot>
        <tr>
           <td colspan='3'>&nbsp;</td>
           <th>Monto Bruto</th>
           <td class='monto'><?php echo number_format($tbruto,2); ?></td>
        </tr>
        <tr>
           <td colspan='3'>&nbsp;</td>
           <th>Descuento</th>
           <td class='monto'><?php echo number_format($tdescuento,2); ?></td>
        </tr>
        <tr>
           <td colspan='3'>&nbsp;</td>        
           <th>Subtotal</th>
           <td class='monto'><?php echo number_format($tbruto-$tdescuento,2); ?></td>
        </tr>
        <tr>
           <td colspan='3'>&nbsp;</td>        
           <th>Impuesto</th>
           <td class='monto'><?php echo number_format($timpuesto,2); ?></td>
        </tr>
        <tr>
           <td colspan='3'>&nbsp;</td>        
           <th>Total</th>
           <td class='monto'><?php echo number_format($tbruto-$tdescuento+$timpuesto,2); ?></td>
        </tr>

    </tfoot>

  </table>
</div>
<hr style='100%'/>
<div>
<p><span style='font-weight:bold' >Solicitado por:</span> <span><?php echo $solicitado; ?> <span></p>
<span style='font-weight:bold' >Notas: </span><span><?php  echo $notas; ?></span>
</div>
</body>
</html>
