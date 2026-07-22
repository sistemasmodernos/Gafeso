<?php
  include_once('controles.php');
  inicio("segu");
  include_once('lib/nucleo.php');
  include_once('lib/utiles.php');
 if(isset($_POST["cbperfil"]))
  $lperfil=$_POST["cbperfil"];
else
  $lperfil=1;
?>
<html>
<head>
   <meta http-equiv="content-type" content="text/html; charset=utf-8" /> 
  <title>Sistemas de ventas de tiquetes</title> 
  <link rel="stylesheet" href="css/general.css" type="text/css" />
  <link rel="stylesheet" href="style.css" type="text/css" />
  <link rel="stylesheet" href="css/segu.css" type="text/css" />
    		<link type="text/css" href="css/redmond/jquery-ui-1.8.13.custom.css" rel="stylesheet" />
        <link type="text/css" href="css/jquery-ui-numeric.css" rel="stylesheet" />
        <script type="text/javascript" src="js/sfunciones.js"></script>
        <script type="text/javascript" src="js/segu.js"></script>
          		<script type="text/javascript" src="js/jquery-1.5.1.min.js"></script>
		<script type="text/javascript" src="js/jquery-ui-1.8.13.custom.min.js"></script>
 		<script type="text/javascript">
//Inicia todos los objetos de la p'agina
        	$(document).ready(function() {
         ajusta();
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
<form name='formsegu' method='post' action='segu.php'>
 <div id='controles'>
  Perfil: <select id='cbperfil' name='cbperfil' onchange='traer();'>
        <?php
        $res=ListaActivos("Perfil",null);
        echo var_dump($res);
        for($x=0;$x<count($res);$x++){
            echo "<option value='". $res[$x]["idperfil"]."'";
             if($res[$x]["idperfil"]==$lperfil)
                echo " selected ";
            echo">".$res[$x]["nombre"]."</option>";
            }
        ?>
    </select>
 </div>
  <div id='detalle'>
    <table id='tablamarca'>
       <thead>
       <tr>
       <th>Permiso</th><th>Detalle</th>
       </tr>
       </thead>
       <tbody>
       <?php
        $dat=TodosObjetos($lperfil);
        for($x=0;$x<count($dat);$x++){
            if($dat[$x]["idperxobj"]==null)
              $ck="";
            else
              $ck="checked";
              
            echo "<tr>
            <td style='padding-left:".($dat[$x]['nivel']*25)."px;'>
            <input type='checkbox' ".$ck." onclick='marca(".$dat[$x]["idobjeto"].",this)'>".
            $dat[$x]["nombre"]. "</td></tr>";
            
        }
       ?>
       </tbody>
    </table>
  </div>
</form>
</div>
</div>
<?php
  Pie();
   
?>


</body>
</html>

