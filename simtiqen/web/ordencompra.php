<?php
  include_once('controles.php');
  inicio("ocompra");

  $orden = new Ocompra();
  if(isset($_SESSION["productos"])){
    $productos=$_SESSION["productos"];
    }
  else  {
     $productos=Array();
}
$idcompra=0;
$proveedor="";
$correo="";
$credito=0;
$notas="";
$solicitado=$_SESSION["parametros"]["nombrelargo"]; 
$rescorreo=""; 
$placa="";
$idremitente="1";
$remitente="Transportes Jaco S.A. ";

  if(isset($_GET["id"])){
    $orden->idCompra=$_GET["id"];
    $idcompra=$_GET["id"];
    $orden->Cargar();

      $proveedor=$orden->proveedor;
      $correo=$orden->correo;
      $credito=$orden->credito;
      $notas=$orden->notas;
      if($orden->idCompra!=0){
        $solicitado=$orden->solicitado;    
        $remitente=$orden->remitente;        
        $placa=$orden->placa;
        $idremitente = $orden->idremitente;
        
      }
      
      
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
   
}

  if(isset($_GET["do"])) {
      $idcompra=$_POST["txtidcompra"];
      $proveedor=$_POST["txtproveedor"];
      $correo=$_POST["txtcorreo"];
      $credito=$_POST["txtcredito"];
      $notas=$_POST["txtnotas"];
      $solicitado=$_POST["txtsolicitado"];
      $placa = $_POST["txtplaca"];
      $remitente = $_POST["txtremitente"];
      $idremitente = $_POST["txtidremitente"];

if ($_GET["do"]=='guardar' ){
      $nuevoid=$idcompra;

      if($nuevoid=="0" || $nuevoid=="NUEVO" || $nuevoid=="")
         $orden->idCompra=0;
      else
         {
             $orden->idCompra=$nuevoid;
             $orden->Cargar();
             }
      $orden->proveedor=$proveedor;
      $orden->correo=$correo;
      $orden->credito=$credito;
      $orden->notas=$notas;
      $orden->solicitado=$solicitado;
      $orden->placa=$placa;
      $orden->remitente=$remitente;
      $orden->idremitente=$idremitente;
      $orden->lineas=Array();
      for($x=0;$x<count($productos);$x++){
          $lin=$orden->NuevaLinea();
          $lin->idLinea=$productos[$x]["idLinea"];
          $lin->codigo=$productos[$x]["codigo"];
          $lin->descripcion=$productos[$x]["producto"];
          $lin->cantidad=$productos[$x]["cantidad"];
          $lin->precio=$productos[$x]["precio"];
          $lin->descuento=$productos[$x]["descuento"];
          $lin->exento=$productos[$x]["exento"];
          $lin->impuesto=$productos[$x]["impuesto"];
          
}
    $bit=NuevaBitacora();
    $orden->HeredaBitacora($bit);
     $res =$orden->Guardar();
     if($res>0)
        $idCompra=$res;
     $idcompra=$orden->idCompra;
     
     //vuuelve a cargar los productos para que actualice el n[umero de linea
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
        "bruto"=>0,"total"=>0);
        array_push($productos,$lin);
    }
     
     
  }
   if($_GET["do"]=='agregar'){       
     
     if($_POST["ckexento"]==NULL)
        $exento=0;
      else
        $exento=1;
       $nuevalin= array(
         "idLinea"=>0,
         "codigo"=>$_POST["txtcodigo"],
         "producto"=>$_POST["txtproducto"],
         "cantidad"=>$_POST["txtcantidad"],
         "precio"=>$_POST["txtprecio"],
         "descuento"=>$_POST["txtdescuento"],
         "exento"=>$exento,
         "impuesto"=>$_POST["txtimpuesto"],
         "bruto"=>$_POST["txtcantidad"]*$_POST["txtprecio"],
         "total"=>round($_POST["txtcantidad"]*$_POST["txtprecio"],2)-$_POST["txtdescuento"]+$_POST["txtimpuesto"]
       );
       array_push($productos,$nuevalin);
    }
    if($_GET["do"]=='borrar'){
          unset($productos[$_GET["linea"]]);
          $productos = array_values($productos);
     }
    if($_GET["do"]=="enviar")
    {
            $para      = $correo;
            $titulo = "Orden de compra ".$idcompra;
            $mensaje = $_POST["datoscorreo"];
            $cabeceras = 'From: info@transportesjacoruta655.com' . "\r\n" .
            'Reply-To: info@transportesjacoruta655.com' . "\r\n" .
            'X-Mailer: PHP/' . phpversion(). "\r\n";
            $cabeceras .= 'MIME-Version: 1.0' . "\r\n";
            $cabeceras .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
            if(mail($para, $titulo, $mensaje, $cabeceras))
              $rescorreo="<h1>Se envi&oacute; esta orden al correo ".$correo."</h1>";
            else
              $rescorreo="No se pudo enviar el correo";
        }

}
//recalcula
for($x=0;$x<count($productos);$x++)
{
    $productos[$x]["bruto"]=$productos[$x]["cantidad"]*$productos[$x]["precio"];
    $productos[$x]["total"]=$productos[$x]["bruto"]-$productos[$x]["descuento"]+$productos[$x]["impuesto"];

}
$_SESSION["productos"]=$productos;
//echo var_dump($productos);


?>
<html>
<head>
   <meta http-equiv="content-type" content="text/html; charset=utf-8" /> 
  <title>Sistemas de ventas de tiquetes</title> 
  <link rel="stylesheet" href="css/general.css" type="text/css" />
  <link rel="stylesheet" href="style.css" type="text/css" />
  		<link type="text/css" href="css/redmond/jquery-ui-1.8.13.custom.css" rel="stylesheet" />
        <link type="text/css" href="css/jquery-ui-numeric.css" rel="stylesheet" />
        <link type="text/css" href="css/ordencompra.css" rel="stylesheet" />
        <script type="text/javascript" src="js/sfunciones.js"></script>
		<script type="text/javascript" src="js/jquery-1.5.1.min.js"></script>
		<script type="text/javascript" src="js/jquery-ui-1.8.13.custom.min.js"></script>
        <script type="text/javascript" src="js/jquery.ui.datepicker-es.js"></script>
        <script type="text/javascript" src="js/jquery-ui-numeric.js"></script>
        <script type="text/javascript" src="js/jquery-ui-numeric-min.js"></script>
        <script type="text/javascript" src="js/ordencompra.js"></script>
 		<script type="text/javascript">
//Inicia todos los objetos de la p'agina
            $(function(){
                
			});
		</script>
</head>
<body onLoad="MM_preloadImages('images/salir_2.jpg')">

<?php
   Encabezado();
   
?>
<div class="wrap">
<?php Menu(); ?>

<div id='cuerpo' class='content'>
<form method='post' id='formoc' action='ordencompra.php?do=guardar'>
<h1>Ordenes de compra</h1>
<div id='divencaorden'>
  <table>
     <tr>
        <th> Num. Orden</th>
        <td> <input type='text' id='txtidcompra' name='txtidcompra' readonly value='<?php echo $idcompra; ?>' 
        style='width:75px;' ></td>
     </tr>
  <tr>
       <th>Emisor</th>
       <td>
         <select id='txtidremitente' name='txtidremitente' onchange='cambiacia();'>
            <option value ='1' <?php if($idremitente=="1") { echo "selected"; } ?>  > Transportes Jaco S.A. </option>
            <option value ='2' <?php if($idremitente=="2") { echo "selected"; } ?> > Transportes Gonz&aacute;lez y Villegas Ltda </option>
         </select>
          <input type='hidden' id='txtremitente' name='txtremitente' value='<?php echo $remitente; ?>'> 
        </td>
     </tr>     
     <tr>
       <th>Proveedor</th>
       <td><input type='text' id='txtproveedor' name='txtproveedor' value='<?php echo $proveedor; ?>'></td>
     </tr>
     <tr>
       <th>Correo</th>
       <td><input type='text' id='txtcorreo' name='txtcorreo' value='<?php echo $correo; ?>'> </td>
     </tr>
     <tr>
       <th>Cr&eacute;dito</th>
       <td><input  type='number' min='0' id='txtcredito' name='txtcredito' value='<?php echo $credito; ?>'></td>
     </tr>
       <tr>
       <th>Placa</th>
       <td><input type='text' id='txtplaca' name='txtplaca' value='<?php echo $placa; ?>'> </td>
     </tr>
  </table>
</div>
<hr with='100%'/>
<div id='divproductos'>

<div id='cuadropro'>

<h3>Agregar productos</h3>

C&oacute;digo <input type='text' id='txtcodigo' name='txtcodigo' >
  Detalle <input type='text' id='txtproducto' name='txtproducto'><br/>
  Cantidad <input  type='number' min='0' id='txtcantidad' name='txtcantidad' value='0' onblur='impuesto();'>
  Precio <input type='text' id='txtprecio' name='txtprecio' value='0.00'  onblur='impuesto();'>
  Descuento <input type='number' id='txtpordesc' name='txtpordesc' onchange='caldescuento();' style='width:50px'><input type='text' id='txtdescuento' name='txtdescuento' value='0.00'  onblur='impuesto();'>
  Exento <input type='checkbox' id='ckexento' name='ckexento'>
  Impuesto <input type='text' id='txtimpuesto' name='txtimpuesto' value='0.00'>
  <div id='divagregar'>
     <input type='button' id='btagregar' value='Agregar esta l&iacute;nea' onclick='agregar();';>
  </div>
</div>
<hr with='100%'/>
<div id='divdetalle'>
   <table id='tablapro' border='1'>
      <thead>
         <th>c&oacute;digo</th>
         <th>Producto</th>
         <th>Cantidad </th>
         <th>Precio</th>
         <th>Descuento</th>
         <th>Monto Bruto</th>
         <th>Exento</th>
         <th>Impuesto</th>
         <th style='width:100px;'>Total</th>
         <th></th>
      </thead>
      <tbody>
      <?php
      $tbruto=0;
      $tdescuento=0;
      $timpuesto=0;
     
      
      for($x=0;$x<count($productos);$x++){
        echo "<tr><td>".$productos[$x]["codigo"]."</td>";
        echo "<td>".$productos[$x]["producto"]."</td>";
        echo "<td class='tdmonto'>".number_format($productos[$x]["cantidad"],2)."</td>";
        echo "<td class='tdmonto'>".number_format($productos[$x]["precio"],2)."</td>";
        echo "<td class='tdmonto'>".number_format($productos[$x]["descuento"],2)."</td>";
        echo "<td class='tdmonto'>".number_format($productos[$x]["bruto"],2)."</td>";
        if($productos[$x]["exento"]=='0')
          echo "<td>No</td>";
        else
          echo "<td>Si</td>";
        echo "<td class='tdmonto'>".number_format($productos[$x]["impuesto"],2)."</td>";  
        echo "<td class='tdmonto'>".number_format($productos[$x]["total"],2)."</td>";
        echo "<td><a href='#' onclick='eliminar(".$x.");' >Eliminar</a></td>";
        echo "</tr>";
        $tbruto+=$productos[$x]["bruto"];
        $tdescuento+=$productos[$x]["descuento"];
        $timpuesto+=$productos[$x]["impuesto"];
    }
    
      ?>
      </tbody>
      <tfoot>
        <tr>
           <td colspan='7'>&nbsp;</td>
           <th>Monto Bruto</th>
           <td class='tdmonto'><?php echo number_format($tbruto,2); ?></td>
        </tr>
        <tr>
           <td colspan='7'>&nbsp;</td>
           <th>Descuento</th>
           <td class='tdmonto'><?php echo number_format($tdescuento,2); ?></td>
        </tr>
        <tr>
           <td colspan='7'>&nbsp;</td>        
           <th>Subtotal</th>
           <td class='tdmonto'><?php echo number_format($tbruto-$tdescuento,2); ?></td>
        </tr>
        <tr>
           <td colspan='7'>&nbsp;</td>        
           <th>Impuesto</th>
           <td class='tdmonto'><?php echo number_format($timpuesto,2); ?></td>
        </tr>
        <tr>
           <td colspan='7'>&nbsp;</td>        
           <th>Total</th>
           <td class='tdmonto'><?php echo number_format($tbruto-$tdescuento+$timpuesto,2); ?></td>
        </tr>

    </tfoot>
   </table>
</div>
</div>
<div id='divpieorden'>
Notas <textarea name="txtnotas" id='textnotas' rows="5" cols="30"><?php echo $notas; ?></textarea><br/>
Solicitado <input type='text' id='txtsolicitado' name='txtsolicitado' value='<?php echo $solicitado; ?>'>
</div>
<input type='button' value='Guardar Orden de Compra' onclick='guardar();'>
<input type='button' value='Enviar por correo' onclick='enviarcorreo();'>
<a href='imporden.php?id=<?php echo $idcompra; ?>'  target='_blank'>Imprimir</a>

<?php echo $rescorreo; ?>

<p><a href='listaoc.php'>Volver a la lista de &oacute;rdenes </a> </p>
</div>
<input type='hidden' id='datoscorreo' name='datoscorreo'>


</form>

</div>
<?php
  Pie();
?>
</body>
</html>
