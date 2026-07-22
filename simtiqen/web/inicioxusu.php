<?php
  include_once('controles.php');
  inicio("iniusu");
  include_once('lib/nucleo.php');
  include_once('lib/utiles.php');
 if(isset($_POST["cbusuario"]))
  $lusuario=$_POST["cbusuario"];
else
  $lusuario=1;
 $rr= PerfilesInicio($lusuario);
 $aprobados=explode(',', $rr);
 
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
        <script type="text/javascript" src="js/inicioxusu.js"></script>
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
<form name='forminixusu' method='post' action='inicioxusu.php'>
 <div id='controles'>
  Usuario: <select id='cbusuario' name='cbusuario' onchange='traer();'>
        <?php
        $res=ListaActivos("Usuario",null);
        
        for($x=0;$x<count($res);$x++){
            echo "<option value='". $res[$x]["idusuario"]."'";
             if($res[$x]["idusuario"]==$lusuario)
                echo " selected ";
            echo">".$res[$x]["nombre"]."</option>";
            }
        ?>
    </select>
 </div>
  <div id='detalle'>
<h1>Pefiles de inicio permitidos por usuario</h1>
    <table id='tablamarca'>
       <thead>
       <tr>
       <th></th>
       </tr>
       </thead>
       <tbody>

         <tr>
          <td> 
            <input type='checkbox' onclick="marca('1',this);" 
            <?php echo (in_array('1',$aprobados))? "checked":""; ?>
             > Vendedor de tiquetes en Buenos Aires 1</input>
          </td>
         </tr>       

         <tr>
          <td> 
            <input type='checkbox' onclick="marca('2',this);" 
            <?php echo (in_array('2',$aprobados))? "checked":""; ?>
             > Vendedor de tiquetes en Buenos Aires 2</input>
          </td>
         </tr>


         <tr>
          <td> 
            <input type='checkbox' onclick="marca('3',this);" 
            <?php echo (in_array('3',$aprobados))? "checked":""; ?>
             > Vendedor de encomiendas en Buenos Aires 3</input>
          </td>
         </tr>

         <tr>
          <td> 
            <input type='checkbox' onclick="marca('4',this);" 
            <?php echo (in_array('4',$aprobados))? "checked":""; ?>
             > Vendedor de tiquetes Perez Zeledón </input>
          </td>
         </tr>

         <tr>
          <td> 
            <input type='checkbox' onclick="marca('5',this);" 
            <?php echo (in_array('5',$aprobados))? "checked":""; ?>
             > Vendedor de encomiendas Perez Zeledón </input>
          </td>
         </tr>


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

