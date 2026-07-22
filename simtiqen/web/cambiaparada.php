<?php
  include_once('controles.php');
  include_once('lib/nucleo.php');
  include_once('lib/utiles.php');
  inicio("cambiaparada");
  $mensaje="";
  if(isset($_POST["cbparada1"])){
      $idparada1=$_POST["cbparada1"];
      $idparada2=$_POST["cbparada2"];
      $idtiquete=str_pad($_POST["txtidtiquete"],6,"0",STR_PAD_LEFT);
      $idimpresora=$_POST["cbimpresora"];
  }
    else{
  $idparada1=0;
  $idparada2=0;
  $idtiquete="";
  $idimpresora=$_SESSION["parametros"]["impresoradefault"];
  }
   if(isset($_GET["do"]) && $_GET["do"]=="cargar")
  {
      $elfiltro = array();
	   array_push($elfiltro,"t.factura = ".$_POST["txtidtiquete"]); 
       array_push($elfiltro,"t.idimpresora = ".$_POST["cbimpresora"]); 
      $consulta = ListaActivos("Tiquete",$elfiltro);
      if (count($consulta)> 0){
      	$idparada1 = $consulta[0]["idparada1"];
        $idparada2 = $consulta[0]["idparada2"];
        $mensaje="Datos cargados";
      }
      else
        $mensaje="Ese número de tiquete no existe en esa impresora";
   }
  if(isset($_GET["do"]) && $_GET["do"]=="cambiar")
  {
      echo "el tiquete ".$idtiquete; 
      $res = cambiaparada($idimpresora,$idtiquete,$idparada1,$idparada2);
      if($res=0)
        $mensaje="Ocurri&oacute;  un error al realizar el cambio"; 
      else
        $mensaje="Los datos fueron cambiados";
      
   }
    
 ?>
<html>
<head>
   <meta http-equiv="content-type" content="text/html; charset=utf-8" /> 
  <title>Sistemas de ventas de tiquetes</title> 
        <link rel="stylesheet" href="css/general.css" type="text/css" />
        <link rel="stylesheet" href="style.css" type="text/css" />        
        <link type="text/css" href="css/cambiaparada.css" rel="stylesheet" />       
  		<link type="text/css" href="css/redmond/jquery-ui-1.8.13.custom.css" rel="stylesheet" />        
        <script type="text/javascript" src="js/sfunciones.js"></script>
		<script type="text/javascript" src="js/jquery-1.5.1.min.js"></script>
		<script type="text/javascript" src="js/jquery-ui-1.8.13.custom.min.js"></script>
        <script type="text/javascript" src="js/jquery.ui.datepicker-es.js"></script>
        <script type="text/javascript" src="js/cambiaparada.js"></script>
         <script type="text/javascript" src="js/jquery-ui-numeric.js"></script>
        <script type="text/javascript" src="js/jquery-ui-numeric-min.js"></script>        
 		<script type="text/javascript">
//Inicia todos los objetos de la p'agina
          $(document).ready(function(){
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

<form id='fcambiaparada' name='fcambiaparada' action='cambiaparada.php' method='post'>
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
          <td><input type='number' name='txtidtiquete' id='txtidtiquete' value='<?php echo $idtiquete; ?>'></td>
        </tr>
        <tr>
          <td colspan='2'> <input type='button' value='Cargar' onclick='cargacambiaparada();'> </td>
        </tr>
        <tr>
           <td>Salida</td>
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
           <td>Destino</td>
           <td><select id='cbparada2' name='cbparada2'> 
           <?php
                    $res=ListaActivos("Parada",null);
                    for($x=0;$x<count($res);$x++){
                    echo "<option value='". $res[$x]["idparada"]."'";
                    if($res[$x]["idparada"]==$idparada2)
                     echo " selected ";
                    echo">".$res[$x]["nombre"]."</option>";
                    }
                    ?>
                </select>
           </td> 
        </tr>
        <tr>
        <td colspan='2' ><input type='button' value='Cambiar' onclick='cambiaparada();'></td>
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
