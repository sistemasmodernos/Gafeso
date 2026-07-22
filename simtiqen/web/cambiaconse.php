<?php
  include_once('controles.php');
  include_once('lib/nucleo.php');
  include_once('lib/utiles.php');
  inicio("cambiaconse");
  $mensaje="";
  if(isset($_POST["cbimpresora"])){
      $idtiquete=$_POST["txtidtiquete"];
      $idimpresora=$_POST["cbimpresora"];
  }
    else{
  $idtiquete="";
  $idimpresora=$_SESSION["parametros"]["impresoradefault"];
  }
   if(isset($_GET["do"]) && $_GET["do"]=="save")
  {
    $res = setConsecutivo($_POST["cbimpresora"],$_POST["txtidtiquete"]);

      if ($res > 0){
        $mensaje="El consecutivo se actualiz&oacute; correctamente";
      }
      else
        $mensaje="Hubo un problema al actualizar el consecutivo";
   }
    
 ?>
<html>
<head>
   <meta http-equiv="content-type" content="text/html; charset=utf-8" /> 
  <title>Sistemas de ventas de tiquetes</title> 
        <link rel="stylesheet" href="css/general.css" type="text/css" />
        <link rel="stylesheet" href="style.css" type="text/css" />        
  		<link type="text/css" href="css/redmond/jquery-ui-1.8.13.custom.css" rel="stylesheet" />        
        <script type="text/javascript" src="js/sfunciones.js"></script>
		<script type="text/javascript" src="js/jquery-1.5.1.min.js"></script>
		<script type="text/javascript" src="js/jquery-ui-1.8.13.custom.min.js"></script>
        <script type="text/javascript" src="js/jquery.ui.datepicker-es.js"></script>
        <script type="text/javascript" src="js/cambiaconse.js"></script>
         <script type="text/javascript" src="js/jquery-ui-numeric.js"></script>
        <script type="text/javascript" src="js/jquery-ui-numeric-min.js"></script>        
 		<script type="text/javascript">
//Inicia todos los objetos de la p'agina
          $(document).ready(function(){
                ajusta();
                actuconse();
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

<form id='fcambiaconse' name='fcambiaconse' action='cambiaconse.php' method='post'>
    <h1>Cambio de consecutivo</h1>
    <table>
     <tr>
    <td> Impresora: </td>
    <td><select id='cbimpresora' name='cbimpresora' onchange="actuconse();" >
        <?php
        $res=ListaActivos("Impresora",null);
        for($x=0;$x<count($res);$x++){
            echo "<option value='". $res[$x]["idimpresora"]."'";
            if($res[$x]["idimpresora"]==$idimpresora)
                echo " selected ";
            echo">".$res[$x]["nombre"]."</option>";
            }
        ?>
    </select>
    </td>
    </tr>
       <tr>
          <td> N&uacute;mero de consecutivo </td> 
          <td><input type='number' name='txtidtiquete' id='txtidtiquete' value='<?php echo $idtiquete; ?>'></td>
        </tr>
        <tr>
          <td colspan='2'> <input type='button' value='Cambiar' onclick='cambiaconsecutivo();'> </td>
        </tr>
    </table>
    <br>
    <br>
    <h1><?php  echo $mensaje; ?> </h1>
</form>
</div>
</div>
<?php
  Pie();
?>
</body>
</html>
