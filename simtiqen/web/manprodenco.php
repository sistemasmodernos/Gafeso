<?php
include_once('controles.php');
include_once('lib/nucleo.php');
include_once('lib/utiles.php');
inicio("manprodenco");

if(isset($_GET["do"]) && $_GET["do"]=="save")
{
  $idProdenco = $_POST["idProdenco"];
  $nombre = $_POST["nombre"];
  $activo = $_POST["activo"];
  $precio = $_POST["precio"];
  Mantenimiento("prodenco",array("idProdenco"=>$idProdenco
    ,"nombre"=>$nombre
    ,"activo"=>$activo
    ,"precio"=>$precio),$_POST["tipoactu"]);
}

?>
<html>
<head>
 <meta http-equiv="content-type" content="text/html; charset=utf-8" /> 
 <title>Sistemas de ventas de tiquetes</title> 
 <link rel="stylesheet" href="css/general.css" type="text/css" />
 <link rel="stylesheet" href="css/manprodenco.css" type="text/css" />
 <link rel="stylesheet" href="style.css" type="text/css" />        
 <link type="text/css" href="css/redmond/jquery-ui-1.8.13.custom.css" rel="stylesheet" />        
 <script type="text/javascript" src="js/sfunciones.js"></script>
 <script type="text/javascript" src="js/jquery-1.5.1.min.js"></script>
 <script type="text/javascript" src="js/jquery-ui-1.8.13.custom.min.js"></script>
 <script type="text/javascript" src="js/jquery.ui.datepicker-es.js"></script>
 <script type="text/javascript" src="js/manprodenco.js"></script>
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
      <h1>Cat&aacute;logo de encomiendas</h1>
      <div id='datos'>
        <table>
         <thead>
           <tr>
             <th>Nombre</th>
             <th>Activo</th>
             <th>Precio</th>
             <th>Agregar</th>
           </tr>
         </thead>
         <tbody>
          <tr data-id="0">
            <td>
             <input type="text" name="txtnombre" id="txtnombre">
           </td>
           <td>
             <input type="checkbox"  id="ckactivo" checked="true">
           </td>
           <td>
            <input type="number" name="txtprecio" id="txtprecio">
          </td>
          <td>
            <input type="button" 
                value="+" 
                id="btagregar" 
                title="Agregar nuevo" 
                onclick="actualizar(this,1);">
          </td>
        </tr>
        <?php
        $res = ListaActivos("Prodenco",null);

        for($i=0;$i<count($res);$i++){

          if($res[$i]["activo"]=="1")
            $checked = "checked";
          else
            $checked = "";

          echo "<tr data-id='".$res[$i]["idProdenco"]."'>";
          echo "
          <td>
            <input type='text' 
            class='sinbordes'
            name='txtnombre' 
            id='txtnombre' 
            onchange='actualizar(this,2);'
            value='".$res[$i]["nombre"]."'>
          </td>
          <td>
            <input type='checkbox' 
            name='ckactivo' 
            class='sinbordes'
            onchange='actualizar(this,2);'
            ".$checked."
            id='ckactivo'>
          </td>
          <td>
            <input type='number' 
            name='txtprecio' 
            class='sinbordes classmonto'
            id='txtprecio'
            onchange='actualizar(this,2);'
            value='".$res[$i]["precio"]."'>
          </td>
          <td>
            <input type='button'
                   value='-'
                   tittle='Eliminar este prodructo'
                   id='btquitar'
                   onclick='quitar(this);'
                   >
          </td>
          ";
          echo "</tr>";
        }
        ?>
      </tbody>
    </table>
  </div>
</div>
</div>
<?php
Pie();
?>
</body>
</html>
