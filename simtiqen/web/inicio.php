<?php
  include_once('controles.php');
  inicio("sinpermiso");
  if(isset($_GET["fe"]))
    $fechac= $_GET["fe"];
 else
    $fechac=date('d/m/Y',time());
 if(isset($_POST["cbperfil"]))
 {
     switch ($_POST["cbperfil"])
      {
          case '1':  //Buenos Aires 1
           $_SESSION["parametros"]["estaciondefault"]=1; 
          $_SESSION["parametros"]["impresoradefault"]=1;
          $_SESSION["parametros"]["rutadefault"]=1;
          $_SESSION["parametros"]["productodefault"]=1;
          $_SESSION["parametros"]["paradaespecial"]=1;
          $_SESSION["parametros"]["paradaenco"]=2;
          break;
          case '2': // Buenos Aires 2
           $_SESSION["parametros"]["estaciondefault"]=1; 
          $_SESSION["parametros"]["impresoradefault"]=2;
          $_SESSION["parametros"]["rutadefault"]=1;
          $_SESSION["parametros"]["productodefault"]=1;
          $_SESSION["parametros"]["paradaespecial"]=1;
          $_SESSION["parametros"]["paradaenco"]=2;
          break;
          case '3': //Encomiendas Buenos Aires
           $_SESSION["parametros"]["estaciondefault"]=1; 
          $_SESSION["parametros"]["impresoradefault"]=3;
          $_SESSION["parametros"]["rutadefault"]=1;
          $_SESSION["parametros"]["productodefault"]=1;
          $_SESSION["parametros"]["paradaespecial"]=1;
          $_SESSION["parametros"]["paradaenco"]=2;
          break;
          case '4':  //Tiquetes Perez Zeledon
           $_SESSION["parametros"]["estaciondefault"]=2; 
          $_SESSION["parametros"]["impresoradefault"]=4;
          $_SESSION["parametros"]["rutadefault"]=2;
          $_SESSION["parametros"]["productodefault"]=1;
          $_SESSION["parametros"]["paradaespecial"]=2;
          $_SESSION["parametros"]["paradaenco"]=1;
          break;
         case '5':  //Encomiendas Perez Zeledon
           $_SESSION["parametros"]["estaciondefault"]=2; 
          $_SESSION["parametros"]["impresoradefault"]=6;
          $_SESSION["parametros"]["rutadefault"]=2;
          $_SESSION["parametros"]["productodefault"]=1;
          $_SESSION["parametros"]["paradaespecial"]=2;
          $_SESSION["parametros"]["paradaenco"]=1;
          break;
           case '6':  //Tiquetes Buenos Aires
           $_SESSION["parametros"]["estaciondefault"]=3; 
          $_SESSION["parametros"]["impresoradefault"]=7;
          $_SESSION["parametros"]["rutadefault"]=2;
          $_SESSION["parametros"]["productodefault"]=1;
          $_SESSION["parametros"]["paradaespecial"]=3;
          $_SESSION["parametros"]["paradaenco"]=1;
          break;

          
    }
   $_SESSION["parametros"]["perfilinicio"]=$_POST["cbperfil"]; 
   echo "<script> location.href='index.php'</script>";
  }
  else{
   $lper =   $_SESSION["parametros"]["impresoradefault"];
  
   }

$rr= PerfilesInicio($_SESSION["idusuario"]);
 $aprobados=explode(',', $rr);




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
      <script type="text/javascript" src="js/jquery-1.5.1.min.js"></script>
      <script type="text/javascript" src="js/jquery.cookie.js"></script>
       <script type="text/javascript" src="js/sfunciones.js"></script>
   <script type="text/javascript">
        	$(document).ready(function(){
            ajusta();
            if($.cookie("perfilinicio")!=null)
              $("#cbperfil").val($.cookie("perfilinicio"));
          });
          function guardacookie(){
            $.cookie("perfilinicio",$("#cbperfil").val());

          }

</script> 
</head>
<body onLoad="MM_preloadImages('images/salir_2.jpg')">

<?php
   Encabezado();
   
?>
<div class="wrap">
<?php Menu(); ?>

<div id='cuerpo' class='content' >
    <form id='forminicio' method='post' onsubmit="guardacookie();">
           <div  style='margin:auto;width:350px;border-style:solid;border-width:1px;border-color: #EF4814;margin-top:200px;padding:0px' >
          <p style='background-color: #EF4814;color:white;margin:0px;padding:5px;font-size:200%;'> Por favor seleccione su perfil de hoy<p>
         <div style='padding:15px;'>

         <select  name='cbperfil' id='cbperfil'>

           <option value='1' <?php echo (in_array('1',$aprobados))? "":"disabled"; ?> > 
           Vendedor de tiquetes en Buenos Aires 1
           </option>.

           <option value='2' <?php echo (in_array('2',$aprobados))? "":"disabled"; ?> > 
           Vendedor de tiquetes en Buenos Aires 2
           </option>.

           <option value='3' <?php echo (in_array('3',$aprobados))? "":"disabled"; ?> > 
           Vendedor de encomiendas en Buenos Aires 3
           </option>

           <option value='4' <?php echo (in_array('4',$aprobados))? "":"disabled"; ?> > 
           Vendedor de tiquetes en Perez Zeledón
           </option>

           <option value='5' <?php echo (in_array('5',$aprobados))? "":"disabled"; ?> > 
           Vendedor de encomiendas en Perez Zeledón
           </option>


         </select> 
         <br>
         <br>
         <input type='submit' value= 'Aceptar' >
     </div>
     </div>
     </form> 
</div>
</div>
<?php
  Pie();
?>
</body>
</html>
