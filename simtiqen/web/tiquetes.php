<?php
  include_once('controles.php');
  inicio("protiq");
  
?>
<html>
<head>
   <meta http-equiv="content-type" content="text/html; charset=utf-8" /> 
  <title>Sistemas de ventas de tiquetes</title> 
  <link rel="icon" type="image/png" sizes="32x32" href="favicon-32x32.png">
  <link rel="stylesheet" href="css/general.css" type="text/css" />
  		<link type="text/css" href="css/redmond/jquery-ui-1.8.13.custom.css" rel="stylesheet" />
        <link type="text/css" href="css/jquery-ui-numeric.css" rel="stylesheet" />
        <link type="text/css" href="css/tiquetes.css" rel="stylesheet" />       
        <link type="text/css" href="css/buses.css" rel="stylesheet" />     
<link href="style.css" rel="stylesheet" type="text/css">          
        <script type="text/javascript" src="js/sfunciones.js"></script>
		<script type="text/javascript" src="js/jquery-current.min.js"></script>
		<script type="text/javascript" src="js/jquery-ui-current.custom.min.js"></script>
        <script type="text/javascript" src="js/jquery.ui.datepicker-es.js"></script>
        <script type="text/javascript" src="js/jquery-ui-numeric.js"></script>
        <script type="text/javascript" src="js/jquery-ui-numeric-min.js"></script>
        <script type="text/javascript" src="js/tiquetes.js"></script>        
        <script type="text/javascript" src="js/jWebSocket.js"></script>        
 		<script type="text/javascript">
//Inicia todos los objetos de la p'agina
            $(function(){
				refrescaviaje(cambiaruta);
				$('#datepicker').datepicker({
					inline: true
				});
                $('#datepicker').datepicker('option', {dateFormat: 'dd/mm/yy'});
                $("#datepicker").datepicker($.datepicker.regional['es']);                
                $('#txtfechanac').datepicker({
					inline: true
				});
       //         $('#txtcantasiento').numeric({minValue:0,increment:1,emptyValue: 0});
        //        $('#txtcantasientop').numeric({minValue:0,increment:1,emptyValue: 0});
        //        $('#txtasiento').numeric({minValue:0,increment:1,emptyValue: 0});
		//	    $('#txtmonto').numeric({minValue:0,increment:1, format:"#,000.00" ,emptyValue: 0});
                traecalendario();
                setTimeout('traecalendario()', 5000);
                setTimeout('actualiza_consecutivo()',3000);
                ajusta();
                cicloasientos();
			});

function cicloasientos(){
  setTimeout('cicloasientos()',5000);
  marcaasientos();
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
<div id='formabus'>
</div>
<div id='calendario'>
</div>
 <input type='hidden' id="txtastomarcados">
<table class='tablatiq'>
 <tr>
   <td> Siguiente n&uacute;mero de tiquete:</td>
    <td><div id='divnumero'>11111111</div></td>
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
        <td> Ruta:</td>
        <td><input type='hidden' id='txtrutadefa' value='<?php echo $_SESSION["parametros"]["rutadefault"]; ?>'>
         <select id='cbruta' onchange='cambiaruta();'>
        <?php
        $res=ListaActivos("Ruta",null);
        for($x=0;$x<count($res);$x++){
            echo "<option value='". $res[$x]["idruta"]."'";
            echo "style='background-color:".$res[$x]["color"]."' " ;
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
<div id='divasiento'>
    <table class='tablatiq'>
        <tr>
            <td>N&uacute;mero de asiento siguiente:</td>
            <td><input id='txtasiento'  type='number' min='0'  name='txtasiento' value='1' class='spinner' > </td>
        </tr>
       </table> 
        
        
         Cantidad de asientos a vender:

                <table class='asientos'>
                    <thead>
                    <tr>
                        <th>Juntos</th><th>Sentados</th><th>De pie</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td><input type="checkbox" name="ckjuntos" id='ckjuntos' value='1'  
                           onclick='$("#txtcantasiento").val("2");'></td>
                        <td><input  type='number' min='0'  id='txtcantasiento' name='txtcantasiento' class='spinner' value='1'></td>
                        <td><input type='number' min='0'  id='txtcantasientop' name='txtcantasientop' class='spinner' value='0'></td>
                    </tr>
                    </body>
                </table>
</div>
<div id='otros'>
    <table class='tablatiq'>
        <tr>
            <td> Tipo Tiquete: </td>
            <td>
                <select id='cbproducto' name='cbproducto' onchange='cambiaProducto(this.value);'>
                <?php
                    $res=ListaActivos("Producto",array("tipo=1"));
                    for($x=0;$x<count($res);$x++){
                        if($res[$x]["idProducto"]!=99 || ObjetoValido('cortesia')){
                            echo "<option value='". $res[$x]["idProducto"] . "'";
                            echo">".$res[$x]["nombre"]."</option>";
                        }
                    }  
                ?>
                </select>
            </td>
        </tr>
        <tr>
            <td>Monto del tiquete:</td>
            <td><input type='text' id='txtmonto' name='txtmonto' disabled="disabled"></td>
        </tr>
        <tr>
            <td> Parada Salida:</td>
            <td>
                <select id='cbparadasal' name='cbparadasal' onchange="actualizaPrecio();">
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
        <tr>
            <td> Parada destino:</td>
            <td>
                <select id='cbparadades' name='cbparadades' onchange="actualizaPrecio();">
                <?php
                    $res=ListaActivos("Parada",array("tipo=1"));
                    for($x=0;$x<count($res);$x++){
                    echo "<option value='". $res[$x]["idparada"]."'";
                    echo">".$res[$x]["nombre"]."</option>";
                    }
                ?>
                </select>
            </td>
        </tr>
    </table>
</div>
<div id='divadultomayor' style='display:none;' class='tablatiq'>
  <table>
   <tbody>
      <tr>
         <td>C&eacute;dula</td>
         <td><input type='text' id='txtcedula' name='txtcedula' onchange='revisacedulades();'>
             <div id='mensajeadulto'> </div>
         </td>
    </tr>
      <tr>
         <td>Nombre</td>
         <td><input type='text' id='txtnombre' name='txtnombre'></td>
    </tr>
      <tr>
         <td>Fecha nacimiento</td>
         <td><input type='text' id='txtfechanac' name='txtfechanac'></td>
    </tr>
   </tbody>
  </table>
  <input type='hidden' id='txtidcliente' value='1'>
</div>

<div id='divcortesia' style='display:none;' class='tablatiq'>
  <table>
   <tbody>
      <tr>
         <td>Nombre</td>
         <td><input type='text' id='txtnombre2' name='txtnombre2'></td>
    </tr>
    <tr>
         <td>Comentario</td>
         <td><input type='text' id='txtcomentario' name='txtcomentario'></td>
    </tr>

   </tbody>
  </table>
</div>


<div>
  <input type='button'  id='btvender' value="Vender" onclick='generaventa();' disabled >
  <input type='hidden' id="txttotalvta2" value="0">
</div>
</div>
</div>
<?php
  Pie();
?>

<input type='hidden' id='calenhora'>
</body>
</html>
