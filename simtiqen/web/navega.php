<?php
  include_once('controles.php');
  inicio("sinpermiso");
  $objeto=$_GET["objeto"];
  $llave=$_GET["llave"];
  if(strlen($_GET["filtro"])>0)
    $filtro = array("nombre like '%".trim($_GET["filtro"])."%'");
  else
   $filtro=null;
   
   $res=ListaActivos($objeto,$filtro)
   
   
   
  ?>
  
  <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
   <meta http-equiv="content-type" content="text/html; charset=utf-8" /> 
  <title>Sistemas de ventas de tiquetes</title> 
  <link rel="stylesheet" href="css/general.css" type="text/css" />
<link href="style.css" rel="stylesheet" type="text/css">  
  		<link type="text/css" href="css/redmond/jquery-ui-1.8.13.custom.css" rel="stylesheet" />
       <script type="text/javascript" src="js/sfunciones.js"></script>
   <script type="text/javascript">
        	$(document).ready(function() {
              //ajusta();
	});
   

</script> 
</head>
<body>
<div>
  <div id='divnavega'>
     Filtrar datos que contengan: <input type='text' name='txtfiltro' id='txtfiltro'>
     <input type='button' onclick='carganave2("<?php echo $llave; ?>","<?php echo $objeto; ?>",$("#txtfiltro").val());' value='Filtrar'  ><br>
     <div class='divnavega2'>
     <table border='1px' class='tablenavega' >
         <tr><th>C&oacute;digo</th><th>Nombre</th></tr>
         <?php
            for($x=0;$x<count($res);$x++){
                echo "<tr><td><a href='#' onclick=\"escoge('".$llave."','".$res[$x][0]."');\">".$res[$x][0]."</a></td>";
                echo "<td>".$res[$x]["nombre"]."</td></tr> ";
                if($x==30)
                  $x=count($res);
            }
         ?>
     </table>
     </div>
  </div>
</div>

</body>
</html>