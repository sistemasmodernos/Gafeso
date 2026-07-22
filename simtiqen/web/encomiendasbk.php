<?php
error_reporting(E_ALL ^ E_DEPRECATED);
include_once('controles.php');
inicio("proenco");
$lproductos=ListaActivos("Producto",array("tipo>=2"));
$lprodencos=ListaActivos("Prodenco",array());
?>
<html>
<head>
 <meta http-equiv="content-type" content="text/html; charset=utf-8" /> 
 <title>Sistemas de ventas de tiquetes</title> 
 <link rel="icon" type="image/png" sizes="32x32" href="favicon-32x32.png">
 <link rel="stylesheet" href="css/general.css" type="text/css" />
 <link rel="stylesheet" href="style.css" type="text/css" />
 <link type="text/css" href="css/redmond/jquery-ui-1.8.13.custom.css" rel="stylesheet" />
 <link type="text/css" href="css/jquery-ui-numeric.css" rel="stylesheet" />
 <link type="text/css" href="css/encomiendas.css" rel="stylesheet" />               
 <link type="text/css" href="css/buses.css" rel="stylesheet" />                
 <script type="text/javascript" src="js/sfunciones.js"></script>
 <script type="text/javascript" src="js/jquery-1.5.1.min.js"></script>
 <script type="text/javascript" src="js/jquery-ui-1.8.13.custom.min.js"></script>
 <script type="text/javascript" src="js/jquery.ui.datepicker-es.js"></script>
 <script type="text/javascript" src="js/encomiendas.js"></script>        
 <script type="text/javascript">
   var ltrans=['0' <?php
   for($x=0;$x<count($lproductos);$x++)
    if($lproductos[$x]["tipo"]==3)
      echo ",'".$lproductos[$x]["idProducto"]."'";
    ?> ];
		//Inicia todos los objetos de la p'agina
    $(document).ready(function(){
      refrescaviaje(cambiaruta);
      $('#datepicker').datepicker({
       inline: true
     });
      $('#datepicker').datepicker('option', {dateFormat: 'dd/mm/yy'});
      $('#txtfechae').datepicker({
       inline: true
     });
      ajusta();                
      actualiza_consecutivo();
      actuhora();
      loadDirecciones();


    });
    function escoge(llave,res){
      xlaced = res;
      if(llave=="txtcedremi")
       combo = "#tcedremi";
     else
       combo = "#tceddesti";
     if ($(combo).val()=="01"){
      if (xlaced.length==10){
        xlaced = res.substring(1,2) + "-" + res.substring(2,6) + "-"+ res.substring(6,10);
      }
    }
    if ($(combo).val()=="02"){
      if (xlaced.length==10){
        xlaced =  res.substring(0,1) + "-" + res.substring(1,4) + "-"+ res.substring(4,10);
      }
    }
    res = xlaced;
    $("#"+llave).val(res);
    disablePopup();
    if(llave=="txtcedremi")
     revisacedulare();
   else
     revisacedulades();
 }
</script>
</head>
<body onLoad="MM_preloadImages('images/salir_2.jpg')">

  <?php
  Encabezado();
  
  ?>
  <div class="wrap">
    <?php Menu(); ?>

    <div id='cuerpo' class='content'>


      <table class='tablatiq'>
       <tr>
         <td> Siguiente n&uacute;mero de tiquete:</td>
         <td><div id='divnumero'>11111111</div>
          <div> <input type="checkbox" id="ckmanual" onClick="consemanual();">Meter manualmente
           <div id='divmanual' style='display:none;'>
             <input type='text' id='txtfactura'>
             <br/>Fecha de la encomienda<br/>
             <input id='txtfechae' type='text' name ='txtfechae' class='fecha' value='<?php echo date('d/m/Y') ?>'>
           </div>
         </div>
       </td>
     </tr>
     <tr>
      <td> Estaci&oacute;n: </td>
      <td><select id='cbestacion' onchange='cambiaestacion(this.value);' disabled>
        <?php
        $res=ListaActivos("Estacion",null);
        for($x=0;$x<count($res);$x++){
          echo "<option value='". $res[$x]["idestacion"]."'";
          if($res[$x]["idestacion"]==$_SESSION["parametros"]["estaciondefault"])
            echo " selected ";
          echo">".$res[$x]["nombre"]."</option>";
        }
        ?>
      </select>
    </td>
  </tr>
  <tr>
    <td> Impresora: </td>
    <td><select id='cbimpresora' onchange='cambiaimpresora(this.value);' disabled>
      <?php
      $res=ListaActivos("Impresora",null);
      for($x=0;$x<count($res);$x++){
        echo "<option value='". $res[$x]["idimpresora"]."'";
        if($res[$x]["idimpresora"]==$_SESSION["parametros"]["impresoradefault"])
          echo " selected ";
        echo">".$res[$x]["nombre"]."</option>";
      }
      ?>
    </select>
  </td>
</tr>
<tr>
  
  <td> Ruta: </td>
  <td><select id='cbruta' onchange='cambiaruta();'>
    <?php
    $res=ListaActivos("Ruta",null);
    for($x=0;$x<count($res);$x++){
      echo "<option value='". $res[$x]["idruta"]."'";
      if($res[$x]["idruta"]==$_SESSION["parametros"]["rutadefault"])
        echo " selected ";
      echo">".$res[$x]["nombre"]."</option>";
    }
    ?>
  </select>
</td>
</tr>
</table>
<div id='divcrtviaje'>
</div>
<div>
  <div id='remitente'>
    <table>
      <thead>
        <tr>
         <th colspan='2'>Remitente</th>
       </tr>
     </thead>
     <tbody>
      <tr>
        <td>Tipo de Identificaci&oacute;n</td>
        <td>  
          <select id="tcedremi" name="tcedremi" onchange="cambiatipocedula();">
            <option selected value="00">Impersonal</option>
            <option value="01">Persona F&iacute;sica</option>
            <option value="02">Persona Jur&iacute;dica</option>
            <option value="03">DIMEX</option>
            <option value="03">NITE</option>
          </select>
        </a>
      </td>
    </tr>
    <tr>
      <td>C&eacute;dula</td>
      <td><input type='text' id='txtcedremi' name='txtcedremi' onchange='revisacedulare();' >
       <a id='acedremi' onclick='decidenave("txtcedremi","#tcedremi"); ' href='#' > Buscar</a> 
     </td>
   </tr>
   <tr>
    <td>Nombre</td>
    <td><input type='text' id='txtnomremi' name='txtnomremi'></td>
  </tr>
  <tr>
    <td>Email</td>
    <td><input type='text' id='txtemailremi' name='txtemailremi' ></td>
  </tr>
  <tr>
    <td>Tel&eacute;fono</td>
    <td><input type='text' id='txttelremi' name='txttelremi' ></td>
  </tr>
  
</tbody>
</table>
<div id="datosparafe" style="display: none;">
  <h3> Datos para factura electr&oacute;nica </h3>

  <p>
    <label>Provincia<br />
      <select name="provincia" id="provincia" onchange="fillCantones();"></select>
    </label>
  </p>
  
  <p>
    <label>Cant&oacute;n<br/>
      <select name="canton" id="canton" onchange="fillDistritos();" ></select>
    </label>
  </p>
  <p>
    <label>Distrito<br/>
      <select name="distrito" id="distrito"></select>
    </label>
  </p>
  <p>
    <label>
      Direcci&oacute;n<br/>
      <input type="text" name="txtotrassenas" id="txtotrassenas">
    </label>
  </p>
</div>
</div>
<div id='destinatario'>
  <table>
    <thead>
      <tr>
       <th colspan='2'>Destinatario</th>
     </tr>
   </thead>
   <tbody>
    <tr>
      <td>Tipo de Identificaci&oacute;n</td>
      <td>  
        <select id="tceddesti" name="tceddesti">
          <option selected value="00">Impersonal</option>
          <option value="01">Persona F&iacute;sica</option>
          <option value="02">Persona Jur&iacute;dica</option>
          <option value="03">DIMEX</option>
          <option value="03">NITE</option>
        </select>
      </a>
    </td>
  </tr>
  <tr>
    <td>C&eacute;dula</td>
    <td><input type='text' id='txtceddesti' name='txtceddesti'   onchange='revisacedulades();' disabled="disabled">
     <a onclick='decidenave("txtceddesti","#tceddesti"); ' href='#' > Buscar </a> 
   </td>
 </tr>
 <tr>
  <td>Nombre</td>
  <td><input type='text' id='txtnomdesti' name='txtnomdesti'></td>
</tr>
<tr>
  <td>Tel&eacute;fono</td>
  <td><input type='text' id='txtteldesti' name='txtteldesti' disabled="disabled"></td>
</tr>
<tr>
  <td>Tel&eacute;fono2</td>
  <td><input type='text' id='txtteldesti2' name='txtteldesti2' disabled="disabled"></td>
</tr>    
</tbody>
</table>
</div>
</div>
<div id="detalleproductos">
 <table>
   <thead>
     <tr>
       <th>Detalle</th>
       <th>Cantidad</th>
       <th>Precio</th>
     </tr>
   </thead>
   <tbody>
     <tr>
       <td>
         <input style="width: 350px;" 
         type="text" 
         name="txtprod" 
         id="txtprod" 
         class="prod" 
         onchange="calcula();">
       </td>
       <td>
         <input type="number" 
         name="txtcanprod" 
         id="txtcanprod"
         class="canprod" 
         min="0" 
         value="0" 
         onchange="calcula();">
       </td>
       <td>
         <input type="number" 
         name="txtprecioprod" 
         id= "txtprecioprod" 
         min="0" 
         step="0.01"  
         class="precioprod"
         value="0" 
         onchange="calcula();">
       </td>
     </tr>
     <tr>
       <td>
         <input style="width: 350px;" 
         type="text" 
         name="txtprod" 
         id="txtprod" 
         class="prod" 
         onchange="calcula();">
       </td>
       <td>
         <input type="number" 
         name="txtcanprod" 
         id="txtcanprod"
         class="canprod" 
         min="0" 
         value="0"
         onchange="calcula();">
       </td>
       <td>
         <input type="number" 
         name="txtprecioprod" 
         id= "txtprecioprod" 
         min="0" 
         step="0.01"  
         class="precioprod"
         value="0" 
         onchange="calcula();">
       </td>
     </tr>

     <tr>
       <td>
         <input style="width: 350px;" 
         type="text" 
         name="txtprod" 
         id="txtprod" 
         class="prod" 
         onchange="calcula();">
       </td>
       <td>
         <input type="number" 
         name="txtcanprod" 
         id="txtcanprod"
         class="canprod" 
         min="0" 
         value="0"
         onchange="calcula();">
       </td>
       <td>
         <input type="number" 
         name="txtprecioprod" 
         id= "txtprecioprod" 
         min="0" 
         step="0.01"  
         class="precioprod"
         value="0" 
         onchange="calcula();">
       </td>
     </tr>
   </tbody>
 </table>
</div>
<div id='otros' >
  <table class='tablatiq'>
    <tr>
      <td> Tipo Producto: </td>
      <td>
        <select id='cbproducto' name='cbproducto' onchange='cambiaProducto(this.value);'>
          <?php
          
          for($x=0;$x<count($lproductos);$x++){
            echo "<option value='". $lproductos[$x]["idProducto"] . "'";
            echo">".$lproductos[$x]["nombre"]."</option>";
          }  
          ?>
        </select>
      </td>
    </tr>
    <tr>
      <td> Tipo Encomienda: </td>
      <td>
        <select id='cbprodenco' name='cbprodenco' onchange='cambiaProdenco(this.value);'>
          <option value='' data-precio='0'>Encomienda no definida</option>
          <?php
          
          for($x=0;$x<count($lprodencos);$x++){
            echo "<option value='". $lprodencos[$x]["idProdenco"] . "'";
            echo "data-precio='".$lprodencos[$x]["precio"]."' ";
            echo">".$lprodencos[$x]["nombre"]."</option>";
          }  
          ?>
        </select>
      </td>
    </tr>
    <tr>
      <td> Parada destino:</td>
      <td>
        <select id='cbparadades' name='cbparadades' onchange="actualizaPrecio();">
          <?php
          $res=ListaActivos("Parada",null);
          for($x=0;$x<count($res);$x++){
            echo "<option value='". $res[$x]["idparada"]."'";
            echo">".$res[$x]["nombre"]."</option>";
          }
          ?>
        </select>
      </td>
    </tr>
    <tr id='trpeso'>
      <td>Peso</td>
      <td><input type='number' id='txtpeso' name='txtpeso'></td>
    </tr>
    <tr id='trcantidad'>
      <td>Cant. Bultos</td>
      <td><input type='number' id='txtcantbul' name='txtcantbul' disabled="true"></td>
    </tr>
    <tr id='trdeclarado'>
      <td id='tddeclarado'>Monto Declarado</td>
      <td>
        <input 
        type='number' 
        id='txtdeclarado' 
        name='txtdeclarado' 
        onchange='ponedeclarado();'>
      </td>
    </tr>
   <tr>
      <td>Monto </td>
      <td style="text-align:right;font-weight: bold;font-size: 120%;">
     <input type='number' 
                 id='txtsubtotal' 
                 name='txtsbutotal' 
                 style="width: 100%;text-align: right;" 
                 disabled="true" >
      </td>
    </tr>
    <tr>
      <td>IVA 
        <input type="number" name="txtporiva" id="txtporiva" style="width: 50px;margin-left:7px;" value="13">
        %
      </td>
      <td style="text-align:right;font-weight: bold;font-size: 120%;" >
      <input type='number' 
                 id='txtiva' 
                 name='txtiva' 
                 style="width: 100%;text-align: right;" 
                 disabled="true" >
      </td>
    </tr>
    <tr>
      <td>Total </td>
      <td style="text-align:right;font-weight: bold;font-size: 120%;">
         <input type='number' 
                 id='txtmonto' 
                 name='txtmonto' 
                 style="width: 100%;text-align: right;" 
                 disabled >
      </td>
      
    </tr>

    <tr>
      <td>Forma de Pago:</td>
      <td>
        <select id='cbformapago' name='cbformapago'>
          <?php
          $res=ListaActivos("FormaPago",null);
          for($x=0;$x<count($res);$x++){
            echo "<option value='". $res[$x]["idformapago"]."'";
            echo">".$res[$x]["desformapago"]."</option>";
          }
          ?>
        </select>
      </td>
    </tr>

  </table>
</div>
<div >
  Notas: <textarea id='txtnotas' name='txtnotas'></textarea>
</div>
<input type='hidden' id='txtidcliente' value='1'>
<div>
  <input type='button' id='btvender' value="Vender" onclick='generaventa();'>
</div>
</div>
</div>
<?php
Pie();
?>
<input type='hidden' id='calenhora'>
<input type='hidden' id='txtdetprod'>
</body>
</html>
