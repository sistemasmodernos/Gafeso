<?php
  include_once('controles.php');
  inicio("procierre");
  include_once('lib/nucleo.php');
  include_once('lib/utiles.php');

  if(isset($_POST["cbestacion"]))
    $lestacion=$_POST["cbestacion"];
  else
    $lestacion=$_SESSION["parametros"]["estaciondefault"];

  if(isset($_POST["cbimpresora"])){
    $limpresora=$_POST["cbimpresora"];
    
    }
  else{
    $limpresora=$_SESSION["parametros"]["impresoradefault"];
    }

 if(isset($_POST["txtfechai"]))
   $lfechai=$_POST["txtfechai"];
 else
   $lfechai=date("d/m/Y",time());

 if(isset($_POST["txtfechaf"]))
   $lfechaf=$_POST["txtfechaf"];
 else
   $lfechaf=date("d/m/Y",time());

if(isset($_POST["txtidcierre"]))
   $lidcierre=$_POST["txtidcierre"];
 else
   $lidcierre=0;

if(!isset($_SESSION["matrizing"]))
  $_SESSION["matrizing"]= array();

if(!isset($_SESSION["matrizegr"]))
  $_SESSION["matrizegr"]= array();

$limpiar=false;

if(isset($_GET["do"])){
 if($_GET["do"]=='cerrar')
  {
      $lidcierre=CreaCierre($lestacion,$limpresora,fechatoUTC($lfechai),fechatoUTC($lfechaf),$_SESSION["matrizing"] ,$_SESSION["matrizegr"]);
    }
}    
$datcierre = ResumenCierre($lidcierre,$lestacion,$limpresora,fechatoUTC($lfechai),fechatoUTC($lfechaf)) ;
$resum=$datcierre[1];
$nomusu ="";

 if($lidcierre!=0){
    $jj = $datcierre[0];
   $lestacion=  $jj["estacion"]  ;
   $limpresora= $jj["impresora"];
   $lfechai = fechatoNormal($jj["fechai"]);
   $lfechaf = fechatoNormal($jj["fechaf"]);
   $nomusu = $jj["usuario"];
   $_SESSION["matrizing"] = $datcierre[2];
   $_SESSION["matrizegr"] = $datcierre[3];
   $limpiar=true;
}
if(isset($_GET["do"])){
  
  if($_GET["do"]=='agring')
     array_push($_SESSION["matrizing"],array($_POST["txtotroi"],limpianum($_POST["txtmontoi"])));
     
  if($_GET["do"]=='agregr')
     array_push($_SESSION["matrizegr"],array($_POST["txtotroe"],limpianum($_POST["txtmontoe"])));     
  if($_GET["do"]=='limpiar')
    {
        $_SESSION["matrizing"]= array();
       $_SESSION["matrizegr"]= array();
        }
 
}
$totaling=0;
$totalegr=0;
$totalvta=0;
$totalvtapos=0;
$totaldep=0;
$totalgen=0;
$totaliva=0;
$totalivapos=0;
?>
<html>
<head>
   <meta http-equiv="content-type" content="text/html; charset=utf-8" /> 
  <title>Sistemas de ventas de tiquetes</title> 
  <link rel="stylesheet" href="css/general.css" type="text/css" />
  <link rel="stylesheet" href="style.css" type="text/css" />
  <link rel="stylesheet" href="css/cierreenco.css" type="text/css" />
  		<link type="text/css" href="css/redmond/jquery-ui-1.8.13.custom.css" rel="stylesheet" />
        <link type="text/css" href="css/jquery-ui-numeric.css" rel="stylesheet" />
        <script type="text/javascript" src="js/sfunciones.js"></script>
        <script type="text/javascript" src="js/cierreenco.js"></script>        
		<script type="text/javascript" src="js/jquery-1.5.1.min.js"></script>
		<script type="text/javascript" src="js/jquery-ui-1.8.13.custom.min.js"></script>
        <script type="text/javascript" src="js/jquery.ui.datepicker-es.js"></script>
        <script type="text/javascript" src="js/jquery-ui-numeric.js"></script>
        <script type="text/javascript" src="js/jquery-ui-numeric-min.js"></script>
        <script type="text/javascript" src="js/jquery.uitablefilter.js"></script>
 		<script type="text/javascript">
//Inicia todos los objetos de la p'agina
            $(function(){
                $('#txtfechai').datepicker({
					inline: true
				});
                $('#txtfechai').datepicker('option', {dateFormat: 'dd/mm/yy'});
                $('#txtfechaf').datepicker({
					inline: true
				});
                $('#txtfechaf').datepicker('option', {dateFormat: 'dd/mm/yy'});
                ajusta();
                theTable = $("#tablafiltro");
                $("#txtfiltro").keyup(function() {
                  $.uiTableFilter(theTable, this.value);
                });

			});
            
		</script>
</head>
<body onLoad="MM_preloadImages('images/salir_2.jpg')">

<?php
   Encabezado();
   
?>
<div class="wrap">
<?php Menu(); ?>

<div id='cuerpo' class='content'  >

<form name='formcierre' id='formcierre' method='post' action='cierreenco.php' >
<div id='controles' style='float:left;'>
<div style='float:left;'>
<p>
<table class='tablatiq' >
 <tr>
   <td> n&uacute;mero de Cierre:</td>
    <td><div id='divnumero'>
      <input type='text' name='txtidcierre' id="txtidcierre" value='<?php echo $lidcierre; ?>' >
      <a href="#" onclick="$('#dialogfiltro').dialog().show();">Buscar</a>
    </div></td>
  </tr>
  <tr>
    <td> Estaci&oacute;n: </td>
    <td><select id='cbestacion' name='cbestacion' onchange='cambiaestacion(this.value);'>
    <?php
    $res=ListaActivos("Estacion",null);
    for($x=0;$x<count($res);$x++){
        echo "<option value='". $res[$x]["idestacion"]."'";
        if($res[$x]["idestacion"]==$lestacion)
            echo " selected ";
        echo">".$res[$x]["nombre"]."</option>";
        }
    ?>
  </select>
   </td>
   </tr>
   <tr>
    <td> Impresora : </td>
    <td><select id='cbimpresora' name='cbimpresora' onchange='cambiaimpresora(this.value);'>
        <?php
        $res=ListaActivos("Impresora",null);
        for($x=0;$x<count($res);$x++){
            echo "<option value='". $res[$x]["idimpresora"]."'";
            if($res[$x]["idimpresora"]==$limpresora)
                echo " selected ";
            echo">".$res[$x]["nombre"]."</option>";
            }
        ?>
    </select>
    </td>
    </tr>
    
</table>

</p>
<p>
Desde: <input type='text' name='txtfechai' id='txtfechai' class='fecha' value ='<?php echo $lfechai; ?>'>
Hasta: <input type='text' name='txtfechaf' id='txtfechaf' class='fecha' value ='<?php echo $lfechaf; ?>'>
</p>
</div>
 <div  id="diving">

<p>
Agregar Otros Ingresos<br/>
Detalle:<input type='text' name='txtotroi' id='txtotroi'> Monto:
<input type='number' name='txtmontoi' id='txtmontoi'>
<input type='button' value='Agregar' onclick='agregarotroi();' <?php if($lidcierre!=0) echo "DISABLED"; ?>>
</p>
<p>
Agregar Otros Egresos<br/>
Detalle:<input type='text' name='txtotroe' id='txtotroe'> Monto:
<input type='number' name='txtmontoe' id='txtmontoe'>
<input type='button' value='Agregar' onclick='agregarotroe();' <?php if($lidcierre!=0) echo "DISABLED"; ?>>
</p>

</div>
<p>

</p>

</div>
<div class='botones'>
    <input type='submit' value='Volver a cargar los datos' >
    <input type='submit' value='Guardar Cierre' onclick='guardarcierre();' <?php if($lidcierre!=0) echo "DISABLED"; ?> >
    <input type='submit' value='Limpiar Ingresos y egresos' onclick='limpiar();' <?php if($lidcierre!=0) echo "DISABLED"; ?> >
    <input type='submit' value='Imprimir' onclick='imprimir();'  >    
</div>
<div id='datoscierre' >
<div class="fechahora">
  Fecha y Hora de impresi&oacute;n: <span><?php echo Date('Y-m-d H:i:s'); ?></span>
</div>
<div class='divcolumna' >
<table class='tablacierre' >
<caption>Ingresos por ventas </caption>
<thead>
<tr>
   <th>Concepto</th><th>Cant</th><th>Monto</th>
</tr>
</thead>
<tbody>
<?php
   
    $x=0;
    $paginacion=1;
    while($x<count($resum)){
        $tipo = $resum[$x]["tipo"];
        echo "<tr><th colspan='3' class='cietitulo1'>" .$resum[$x] ["tipo"] . "</th></tr>";        
        while($x<count($resum) && $tipo==$resum[$x]["tipo"]){
			$desformapago = $resum[$x]["desformapago"];
			$totalfp = 0;
			echo "<tr><th colspan='3' class='cietitulo1'>" .$resum[$x] ["desformapago"] . "</th></tr>"; 
      $subsocio = -1;
      $primero = 1;
      $xsocio = "XX";
			while($x<count($resum) && $tipo==$resum[$x]["tipo"] && $desformapago==$resum[$x]["desformapago"]){
                if($paginacion>30){
                    echo "</table></div>
                               <div style='float:left;width:450;'>
                              <table class='tablacierre' border='1px;'> ";
                    $paginacion=1;
                }

                if ($resum[$x]["tipo"] == "Tiquetes"){
                  if ($xsocio != $resum[$x]["socio"]){
                    if ($primero == 0){
                      echo "<tr><td style='text-align:left;'><b>Total de " . $xsocio . "</b></td><td>&nbsp;</td><td><b>" . Number_format($subsocio,2) . "</b></td></tr><tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>";
                    }else{
                      $primero = 0;
                    }
                    echo "<tr><td style='text-align:left;'><b>" . $resum[$x]["socio"] . "</b></td><td>&nbsp;</td><td>&nbsp;</td></tr>";
                    $xsocio = $resum[$x]["socio"];
                    $subsocio = 0;
                  }
                }else{ $subsocio = 0;}


                echo "<tr";
                if(strpos(strtoupper($resum[$x]["nombre"]),"ADULTO")!==false)
                  echo " style='background-color:yellow;' ";
                echo "><td style='text-align:left;'>" .fechatoNormal($resum[$x] ["fecha"])." ".$resum[$x]["abreviatura"]." ".$resum[$x]["nombre"] ."</td><td>". 
                $resum[$x]["cantidad"] . "</td><td>" . Number_format($resum[$x]["monto"],2)."</td></tr>";
                $totalvta+=$resum[$x]["monto"];
                $totalvtapos+=$resum[$x]["montopos"];
                $totaliva+=$resum[$x]["iva"];
                $totalivapos+=$resum[$x]["ivapos"];
	              $totalfp+=$resum[$x]["monto"];
	             if ($resum[$x]["entracierre"] == 1){
 	               $totaldep+=$resum[$x]["monto"];
                 $subsocio+=$resum[$x]["monto"];
	             }
                $x++;
                $paginacion++;
            }

            if ($subsocio > 0 && $xsocio != "XX"){
              echo "<tr><td style='text-align:left;'><b>Total de " . $xsocio . "</b></td><td>&nbsp;</td><td><b>" . Number_format($subsocio,2) . "</b></td></tr><tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>";
            }
			echo "<tr><td colspan='2'>TOTAL " .$desformapago."</td><td> ". number_format($totalfp,2)."</td></tr>"; 
		}
}

?>

</tbody>
<tfoot>


<?php
      echo "<tr>";
      echo "<th colspan='2'>Total Ventas a reportar</th>";
      echo "<td> ". number_format(($totalvta-$totalvtapos)-($totaliva-$totalivapos),2)."</td>";
      echo "</tr>";
      echo "<tr>";
      echo "<th colspan='2'>IVA a reportar</th>";
      echo "<td> ". number_format($totaliva-$totalivapos,2)."</td>";
      echo "</tr>";
      echo "<tr>";
      echo "<th colspan='2'>Total Ventas Pendientes</th>";
      echo "<td> ". number_format($totalvtapos-$totalivapos,2)."</td>";
      echo "</tr>";
      echo "<tr>";
      echo "<th colspan='2'>IVA Pendiente</th>";
      echo "<td> ". number_format($totalivapos,2)."</td>";
      echo "</tr>";
      echo "<tr>";
      echo "<th colspan='2'>Total Final </th>";
      echo "<td> ". number_format($totalvta,2)."</td>";
      echo "</tr>";

?>
</tfoot>
</table>
</div>
<div class='divcolumna'>

<table class='tablacierre' >
<caption>Resumen por ventas </caption>
<thead>
<tr>
   <th>Concepto</th><th>Cant</th><th>Monto</th>
</tr>
</thead>
<tbody>
<?php
   
    $x=0;
    $paginacion=1;
    $res = Array();
   for($x=0;$x<count($resum);$x++){
       $lbencontro=false;
       for($y=0;$y<count($res) ;$y++)
          if($res[$y]["tipo"]==$resum[$x]["nombre"] && $res[$y]["desformapago"]==$resum[$x]["desformapago"] ){
              $lbencontro=true;
              $res[$y]["cantidad"]+= $resum[$x]["cantidad"];
              $res[$y]["total"]+=$resum[$x]["monto"];
		}
      if(!$lbencontro){
          $nuevo = Array("tipo"=>$resum[$x]["nombre"],
		  "desformapago"=>$resum[$x]["desformapago"],
          "cantidad"=>$resum[$x]["cantidad"],
          "total"=>$resum[$x]["monto"]);
          Array_push($res,$nuevo);
        }

    }
    for($x=0;$x<count($res);$x++)
     echo "<tr><td>".$res[$x]["tipo"]." ".$res[$x]["desformapago"]."</td><td>".$res[$x]["cantidad"]."</td><td>".Number_format($res[$x]["total"])."</td></tr>";
?>

</tbody>
    <tfoot>
      <tr><th>&nbsp;</th><th>&nbsp;</th><th></th></tr>

        </tfoot>
</table>
  


    <table class='tablacierre' >
    <caption>Otros Ingresos </caption>
        <thead>
            <tr>
                <th>Tipo de ingreso</th><th>Monto</th>
            </tr>
        </thead>
        <tbody>
        <?php 
        
          for($x=0;$x<count($_SESSION["matrizing"]);$x++)
          {
              echo "<tr><td>".$_SESSION["matrizing"][$x][0]."</td><td>".number_format($_SESSION["matrizing"][$x][1],2)."</td></tr>";
              $totaling+=str_replace(",","",$_SESSION["matrizing"][$x][1]);
              }
        ?>
        </tbody>
        <tfoot>
          <?php 
            echo "<tr><th>Total</th><th>".Number_format($totaling,2)."</th></tr>";
          ?>
        </tfoot>
        
    </table>
<p>
<table class='tablacierre' >
    <caption>Otros Egresos </caption>
        <thead>
            <tr>
                <th>Tipo de egreso</th><th>Monto</th>
            </tr>
        </thead>
<tbody>
        <?php 
        
          for($x=0;$x<count($_SESSION["matrizegr"]);$x++)
          {
              echo "<tr><td>".$_SESSION["matrizegr"][$x][0]."</td><td>".number_format($_SESSION["matrizegr"][$x][1],2)."</td></tr>";
              $totalegr+=str_replace(",","",$_SESSION["matrizegr"][$x][1]);
              }
        ?>
        </tbody>
        <tfoot>
          <?php 
            echo "<tr><th>Total</th><th>".Number_format($totalegr,2)."</th></tr>";
          ?>
        </tfoot>        
    </table>

    </p>    
    <div id='divtotal'>
       <p>Total a depositar : 
       <?php
         $totalgen = $totaldep+$totaling-$totalegr;
         echo number_format($totalgen,2);
       ?>
       </p>
       Depositos:
       <div id='divcuadro'>
  <div id='divtitulos'>
Cierre # 
<?php 
  if($lidcierre==0)
    echo "Preliminar";
 else
   echo $lidcierre;

echo " <br/>  ";
echo " Desde el ".$lfechai."<br/> hasta el " .$lfechaf; 
echo "<br/>";
echo "Usuario: ". $nomusu;
?>

</div>
     
       
       </div>
    </div>
    
</div>

</div>

<div id="dialogfiltro" title="Últimos cierres" style="display: none;">
  Buscar:<input type="text" id="txtfiltro"> </input>
  <div id="divgridcierres" style="max-height: 400px;overflow-y:scroll; ">
  <table id="tablafiltro">
    <thead>
      <tr>
        <th>#</th>
        <th>Fecha</th>
        <th>Usuario</th>
        <th>Impresora</th>
      </tr>
    </thead>
    <tbody>
    <?php
      $mat = DevCierre();
      for($x=0;$x<count($mat);$x++){
        echo "<tr>";
        echo "<td style='font-size:110%;padding-left:10px;padding-right:10px;'>
        <a href='#' onclick='$(\"#txtidcierre\").val(".$mat[$x]["idCierre"].");' >".$mat[$x]["idCierre"]."</a></td>";
        echo "<td>".substr($mat[$x]["Hasta"],0,10)."</td>";
        echo "<td>".$mat[$x]["Usuario"]."</td>";
        echo "<td>".$mat[$x]["Impresora"]."</td>";
        echo "</tr>";
      }
    ?>
    </tbody>
  </table>
  </div>
</div>

 
</div>
</div>
</form>
</div>
<?php
  Pie();
  if($limpiar){
       $_SESSION["matrizing"] = array();
   $_SESSION["matrizegr"] = array();
}
     
?>


</body>
</html>
