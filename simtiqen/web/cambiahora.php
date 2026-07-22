<?php
  include_once('controles.php');
  include_once('lib/nucleo.php');
  include_once('lib/utiles.php');
  inicio("cambiahora");
  $mensaje="";
  $idviaje=0;
  $idparada1=1;
  $idparada2=1;
  $idtiquete="";
  $idruta=1;
  $idestacion=1;
  $fecha=date('Y-m-d');
  $hora="";
  if(isset($_POST["cbparada1"])){
      $idparada1=$_POST["cbparada1"];
            $idtiquete=str_pad($_POST["txtidtiquete"],6,"0",STR_PAD_LEFT);
      $idimpresora=$_POST["cbimpresora"];
      $idviaje = $_POST["cbviaje"];
  }
    else{
  $idparada1=1;
  $idparada2=1;
  $idtiquete="";
  $idruta=1;
  $idestacion=1;
  $fecha=date('Y-m-d');
  $hora="";
  $idimpresora=$_SESSION["parametros"]["impresoradefault"];
  
  }
   if(isset($_GET["do"]) && $_GET["do"]=="cargar")
  {
  
      $elfiltro = array();
	   array_push($elfiltro,"t.factura = '".$_POST["txtidtiquete"]."'"); 
       array_push($elfiltro,"t.idimpresora = ".$_POST["cbimpresora"]); 
      $consulta = ListaActivos("Tiquete",$elfiltro);
      if (count($consulta)> 0){
  //     echo var_dump($consulta);
      	$idparada1 = $consulta[0]["idparada1"];
        $idestacion = $consulta[0]["idestacion"];
        $idruta = $consulta[0]["idruta"];
        $fecha = substr($consulta[0]["fechaviaje"],0,10);
        $hora= $consulta[0]["hora"];
        $mensaje="Datos cargados";
      }
      else
        $mensaje="Ese número de tiquete no existe en esa impresora";
   }
  if(isset($_GET["do"]) && $_GET["do"]=="cambiar")
  {
  //    echo "tiquete ".$idtiquete; 
      $res = cambiahora($idimpresora,$idtiquete,$idviaje,$idparada1);
      if($res=0)
        $mensaje="Ocurri&oacute;  un error al realizar el cambio"; 
      else
        $mensaje="Los datos fueron cambiados";
       $elfiltro = array();
	   array_push($elfiltro,"t.factura = '".$_POST["txtidtiquete"]."'"); 
       array_push($elfiltro,"t.idimpresora = ".$_POST["cbimpresora"]); 
      $consulta = ListaActivos("Tiquete",$elfiltro);
      if (count($consulta)> 0){
  //      echo var_dump($consulta);
      	$idparada1 = $consulta[0]["idparada1"];
        $idestacion = $consulta[0]["idestacion"];
        $idruta = $consulta[0]["idruta"];
        $fecha = substr($consulta[0]["fechaviaje"],0,10);
        $hora= $consulta[0]["hora"];
      }

   }
    
 ?>
<html>
<head>
   <meta http-equiv="content-type" content="text/html; charset=utf-8" /> 
  <title>Sistemas de ventas de tiquetes</title> 
        <link rel="stylesheet" href="css/general.css" type="text/css" />
        <link rel="stylesheet" href="style.css" type="text/css" />        
        <link type="text/css" href="css/cambiahora.css" rel="stylesheet" />       
  		<link type="text/css" href="css/redmond/jquery-ui-1.8.13.custom.css" rel="stylesheet" />        
        <script type="text/javascript" src="js/sfunciones.js"></script>
		<script type="text/javascript" src="js/jquery-1.5.1.min.js"></script>
		<script type="text/javascript" src="js/jquery-ui-1.8.13.custom.min.js"></script>
        <script type="text/javascript" src="js/jquery.ui.datepicker-es.js"></script>
        <script type="text/javascript" src="js/cambiahora.js"></script>
         <script type="text/javascript" src="js/jquery-ui-numeric.js"></script>
        <script type="text/javascript" src="js/jquery-ui-numeric-min.js"></script>        
 		<script type="text/javascript">
//Inicia todos los objetos de la p'agina
           <?php 
           $cadena ="crtviaje.php?estacion=".$idestacion."&fecha=".$fecha."&parada=".$idparada2."&tipo=1"."&ruta=".$idruta;
           ?>
            $(function(){
                ajusta();
                  traeajax("divcrtviaje","<?php echo $cadena; ?>",null,null);
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

<form id='fcambiahora' name='fcambiahora' action='cambiahora.php' method='post'>
    <table>
     <tr>
    <td> Impresora: </td>
    <td><select id='cbimpresora' name='cbimpresora' >
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
        
          <td> N&uacute;mero de tiquete </td> 
          <td><input type='text' name='txtidtiquete' id='txtidtiquete' value='<?php echo $idtiquete; ?>'></td>
        </tr>
        <tr>
          <td colspan='2'> <input type='button' value='Cargar' onclick='cargacambiahora();'> </td>
        </tr>
    </table>
    <table border="1" class='tablacambio'>
        <tr>
          <th></th><th>Anterior</th><th>Nuevo</th>
         </tr>
        <tr>
           <td>Ruta</td>
           <td> <select id='cbrutav' name='cbrutav' disabled > 
                    <?php
                    $res=ListaActivos("Ruta",null);
                    for($x=0;$x<count($res);$x++){
                    echo "<option value='". $res[$x]["idruta"]."'";
                    if($res[$x]["idruta"]==$idruta)
                     echo " selected ";
                    echo ">".$res[$x]["nombre"]. "</option>";
                    }
                    ?>
                </select>
           </td>
            <td> <select id='cbruta' name='cbruta' onchange='cambiafecha();'> 
                    <?php
                    $res=ListaActivos("Ruta",null);
                    for($x=0;$x<count($res);$x++){
                    echo "<option value='". $res[$x]["idruta"]."'";
                    if($res[$x]["idruta"]==$idruta)
                     echo " selected ";
                    echo ">".$res[$x]["nombre"]. "</option>";
                    }
                    ?>
                </select>
           </td>           
        </tr>         
        <tr>
           <td>Salida</td>
           <td> <select id='cbparadav' name='cbparadav' disabled > 
                    <?php
                    $res=ListaActivos("Parada",null);
                    for($x=0;$x<count($res);$x++){
                    echo "<option value='". $res[$x]["idparada"]."'";
                    if($res[$x]["idparada"]==$idparada1)
                     echo " selected ";
                    echo ">".$res[$x]["nombre"]. "</option>";
                    }
                    ?>
                </select>
           </td>
            <td> <select id='cbparada1' name='cbparada1'> 
                    <?php
                    $res=ListaActivos("Parada",null);
                    for($x=0;$x<count($res);$x++){
                    echo "<option value='". $res[$x]["idparada"]."'";
                    if($res[$x]["idparada"]==$idparada1)
                     echo " selected ";
                    echo ">".$res[$x]["nombre"]. "</option>";
                    }
                    ?>
                </select>
           </td>           
        </tr>
        <tr>
           <td>Viaje</td>
           <td>
               <table>
                   <tr>
                       <td>Fecha:</td><td><?php echo fechatoNatural($fecha); ?> </td>
                   </tr>
                   <tr>
                       <td>Hora :</td><td><?php echo $hora; ?> </td>
                   </tr>
                   
               </table>
           </td> 
           <td>
              <div id='divcrtviaje'>
              </div>
           </td>
        </tr>
        <tr>
        <td colspan='3' ><input type='button' value='Cambiar' onclick='cambiahorario();'></td>
        </tr>
    </table>
    <br>
    <br>
    <h1><?php  echo $mensaje; ?> </h1>

<input type='hidden' id='cbestacion' name ='cbestacion' value='<?php echo $idestacion; ?>'>
<input type='hidden' id='cbviaje' name='cbviaje' value='<?php echo $idviaje; ?>' >
</form>
</div>
</div>
<?php
  Pie();
?>


</body>
</html>
