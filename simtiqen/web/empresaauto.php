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
		
		function escoge(llave,res){
			xlaced = res;
			if (xlaced.length==10){
				xlaced = res.substring(0,2) + "-" + res.substring(2,6) + "-"+ res.substring(6,10);
			}
			res = xlaced;
				$("#"+llave).val(res);
					disablePopup();
					revisacedula();
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
		<?php
		    if(isset($_GET["idempresa"])){ 
				$idempresa = $_GET['idempresa'];
			}
		    if(isset($_POST["idempresa"])){ 
				$idempresa = $_POST['idempresa'];
			}
			$LaEmpresa = new Empresa();
			$nombre = "Sin Empresa";
			if ($LaEmpresa->CargarId($idempresa)> 0){
				$nombre = $LaEmpresa->nombre;
			}
		?>
		<h2 style='margin:auto;width:400px;'>Autorizados de <?php echo $nombre ?></h2>
		<?php
			if(isset($_POST["agregar"])){ 
				if ($LaEmpresa->GuardarAutorizado($_POST["cedula"],$_POST["nomauto"])==true){
					echo "El autorizado se guardó correctamente";
				}else{
					echo "El autorizado no se pudo guardar";
				}
			}
			if ($LaEmpresa->CargarId($idempresa)> 0){
				$consulta = $LaEmpresa->autorizados();
			}
		?>
		<div id="contenedor">
		<form id="frmDatos" action='empresaauto.php?idempresa=<?php echo $idempresa ?>'>
			<input type="hidden" name="idempresa" id="idempresa" value="<?php echo $idempresa ?>" />
			<table>
				<th>Nombre</th>
				<th>C&eacute;dula</th>
				<th></th>
				<?php
				if($consulta) {
					for ($i=0;$i<count($consulta);$i++){
						$linea = $consulta[$i];
				?>
					<tr id="fila-<?php echo $linea['idautoriza'] ?>">
						<td><?php echo $linea['nombre'] ?></td>
						<td><?php echo $linea['cedula'] ?></td>
						<td><span class="dele"><a onClick="EliminarAutoriza(<?php echo $linea['idautoriza'] ?>); return false" href="eliautoriza.php?idautoriza=<?php echo $linea['idautoriza'] ?>"><img src="img/delete.png" title="Elimina el registro seleccionado" alt="Eliminar" /></a></span></td>
					</tr>
				<?php
					}
				}
				?>
			</table>
		</form>
		<form id="frmNuevo" name="frmNuevo" method="post" action="empresaauto.php" enctype="multipart/form-data" onSubmit="GrabarDatos(); return false">
			<input type="hidden" name="idempresa" id="idempresa" value="<?php echo $idempresa ?>" />
			<table>
			<tr>
				<th>C&eacute;dula</th>
				<th>Nombre del Autorizado</th>
				<th></th>
			</tr>
				<td>
					<input type="text" name="cedula" id="cedula" onchange='revisacedula();' size='12' />
					<a id='acedremi' onclick='carganave("cedula","CedulaSIC","" ); ' href='#'>Buscar</a>
				</td>
				<td>
					<input type="text" name="nomauto" id="nomauto" size='60' />
				</td>
				<td>
					<input type="submit" name="agregar" id="agregar" value="Agregar" />
				</td>
			</tr>
			</table>
		</form>
		<a href="manempresa.php">Regresar al mantenimiento</a>
		</div>
	</div>
</div>
<?php
  Pie();
?>
</body>
</html>
