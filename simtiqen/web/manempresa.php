<?php
  include_once('controles.php');
  inicio("manempresa");
  
?>
<html>
<head>
   <meta http-equiv="content-type" content="text/html; charset=utf-8" /> 
  <title>Sistemas de ventas de tiquetes</title> 
  <link rel="stylesheet" href="css/general.css" type="text/css" />
  <link rel="stylesheet" href="style.css" type="text/css" />
  <link rel="stylesheet" href="css/mantenimiento.css" type="text/css" />
  <script src="js/jquery-1.5.1.min.js" type="text/javascript"></script>
  <script src="js/manempresa.js" type="text/javascript"></script>
        <script type="text/javascript" src="js/sfunciones.js"></script>  
 <script type="text/javascript">
           $(function(){ ajusta();})
  </script>  

</head>
<body onLoad="MM_preloadImages('images/salir_2.jpg')">

<?php
   Encabezado();
   
?>
<div class="wrap">
<?php Menu(); ?>

<div id='cuerpo' class='content'>

<h2 style='margin:auto;width:400px;'>Mantenimiento de Empresas<h2>

<div id="contenedor">
    <div id="formulario" style="display:none;">
    </div>
    <div id="tabla">
<?php
$pagina = 1;
$limite = 10;
if(isset($_GET['pagina'])){
	   $pagina = $_GET['pagina'];
}
if(isset($_POST['submit'])){
	$elfiltro = array();
	array_push($elfiltro,"nombre like '%".$_POST['buscar']."%' or cedula like '%".$_POST['buscar']."%'"); 
   $consulta = ListaMantenimiento("empresa",$elfiltro,array("pagina"=>$pagina,"registros"=>$limite));
  	$registros = Paginas("empresa",$elfiltro);
   $xbus = $_POST['buscar'];
}else{
   $consulta = ListaMantenimiento("empresa",null,array("pagina"=>$pagina,"registros"=>$limite));
  	$registros = Paginas("empresa",null);
   $xbus = '';
}
?>
<p align="center"><a href="index.php">volver al Menu Principal</a></p>
<br>
<div align="center">
<form id="frmConsulta" name="frmConsulta" method="post" action="" onSubmit="GrabarDatos(); return false">
      <label>Buscar <input class="text2" type="text" name="buscar" value="<?php echo $xbus?>" id="buscar" />
      </label>
    <input type="submit" name="submit" id="button" value="Ir" />
    <br>
    <br>
    <label>Incluir un nuevo registro</label>
          <span id="nuevo"><a href="newempresa.php"><img src="img/add.png" title="Agrega un nuevo registro" alt="Agregar dato" /></a></span>
</form>
</div>
<form id="frmDatos">
	<table>
   		<tr>
   			<th>Nombre de la Empresa</th>
            <th>C&eacute;dula</th>
            <th>Tel&eacute;fono</th>
            <th>Encargado</th>
            <th></th>
            <th></th>
            <th></th>
        </tr>
<?php
if($consulta) {
	for ($i=0;$i<count($consulta);$i++){
	$linea = $consulta[$i];
	?>
		  <tr id="fila-<?php echo $linea['idempresa'] ?>">
			  <td><?php echo $linea['nombre'] ?></td>
			  <td><?php echo $linea['cedula'] ?></td>
			  <td><?php echo $linea['tele1'] ?>, <?php echo $linea['tele2'] ?></td>
			  <td><?php echo $linea['encargado'] ?></td>
			  <td><span class="modi"><a href="actempresa.php?idempresa=<?php echo $linea['idempresa'] ?>"><img src="img/database_edit.png" title="Editar el registro" alt="Editar" /></a></span></td>
			  <td><span class="dele"><a onClick="EliminarDato(<?php echo $linea['idempresa'] ?>); return false" href="eliempresa.php?idempresa=<?php echo $linea['idempresa'] ?>"><img src="img/delete.png" title="Elimina el registro seleccionado" alt="Eliminar" /></a></span></td>
			  <td><span class="autoriza"><a href="empresaauto.php?idempresa=<?php echo $linea['idempresa'] ?>"><img src="img/usuarios.png" title="Mantenimiento de Autorizados" alt="Autorizados" /></a></span></td>
		  </tr>
	<?php
	}
}
?>
    </table>
    <p align="center">
<?php
$total_paginas = ceil($registros / $limite);
$cantpagver=10;
if ($total_paginas<=$cantpagver){
$paginicial = 1;
}else
{
	$paginicial = $pagina - ceil($cantpagver / 2);
	if ($paginicial < 1){
		$paginicial = 1;
		}
}
if ($total_paginas>1) 
{
	echo "<a title='Start' href='?pagina=1'><< Inicio</a> - ";
if ($pagina>1) echo "</a><a title='Previous' href='?pagina=".($pagina-1)."'> << Anterior </a> - ";
 for ($i = $paginicial; $i <= $total_paginas && $i<=($pagina+$cantpagver); $i++) {
      if ($i == $pagina) echo "<strong>$i - </strong>";
      else echo "</a><a title='Pagina $i' href='?pagina=$i'>$i</a> - ";
}
 if (($pagina+$cantpagver)< $total_paginas) echo "..."; 
if ($pagina<$total_paginas) echo "<a title='Siguiente' href='?pagina=".($pagina+1)."'> Siguiente >>  ";
echo "<a title='Final' href='?pagina=$total_paginas'>Final >></a>";
}	
?></p>
</form>
</div>
</div>
</div>
</div>
<?php
  Pie();
?>
</body>
</html>
