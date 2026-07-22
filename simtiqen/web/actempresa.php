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
    <script type="text/javascript" src="js/sfunciones.js"></script>  
	<script src="js/manempresa.js" type="text/javascript"></script>
	<script type="text/javascript" src="js/encomiendas.js"></script>  
	<script type="text/javascript">
		$(function(){ ajusta();})
		
		$(function(){ 
    		ajusta();
    		loadDirecciones();
  		})

		function escoge(llave,res){
			xlaced = res;
			if (xlaced.length==10){
				xlaced =  res.substring(0,1) + "-" + res.substring(1,4) + "-"+ res.substring(4,10);
			}
			res = xlaced;
            $("#"+llave).val(res);
               disablePopup();
               revisacedulajur();
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
<!--Inicio /!-->
<?php
if(isset($_POST['submit'])){
	$idempresa = htmlspecialchars(trim($_POST['idempresa']));
	$nomempresa = htmlspecialchars(trim($_POST['nomempresa']));
	$cedula = htmlspecialchars(trim($_POST['cedula']));
	$tele1 = htmlspecialchars(trim($_POST['tele1']));
	$tele2 = htmlspecialchars(trim($_POST['tele2']));
	$encargado = htmlspecialchars(trim($_POST['encargado']));
	$direccion = htmlspecialchars(trim($_POST['direccion']));
	$tarifaimp = $_POST['tarifaimp'];
  	$exodoc = $_POST["exodoc"];
  	$exofecha = $_POST["exofecha"];
  	$exoentidad = $_POST["exoentidad"];
  	$exoporce = $_POST["exoporce"];
  	$exotipo = $_POST["exotipo"];
	if (isset($_POST['credito'])){
    	$credito = 1;
	}else{
		$credito = 0;
	}
	if (isset($_POST['activo'])){
    	$activo = 1;
	}else{
		$activo = 0;
	}

	?>
</p>
<div id="contenedor">
	<div id="formulario" style="display:none;">
    </div>
    <div id="tabla">
	<br />
	<p align="center">
<?php	
	if (Mantenimiento("empresa",array("idempresa"=>$idempresa
		,"nombre"=>$nomempresa
		,"cedula"=>$cedula
		,"tele1"=>$tele1
		,"tele2"=>$tele2
		,"encargado"=>$encargado
		,"direccion"=>$direccion
		,"activo"=>$activo
		,"credito"=>$credito
		,"tarifaimp"=>$tarifaimp
        ,"exodoc" => $exodoc
        ,"exofecha"=> $exofecha
        ,"exoentidad" => $exoentidad
        ,"exoporce" => $exoporce
        ,"exotipo" => $exotipo
	),2) == true){
		echo 'Datos Guardados<br>';
	}else{
		echo 'Se produjo un error. Intente nuevamente<br>';
	} 
?>
    <br />
    <br />
	<a href="manempresa.php">Regresar al mantenimiento</a>
	</p>
    </div>
</div>
<?php
}else{
	if(isset($_GET['idempresa'])){
		$idempresa = $_GET['idempresa'];
		$elfiltro = array();
		array_push($elfiltro,"idempresa = ".$idempresa); 
		$consulta = ListaMantenimiento("empresa",$elfiltro,null);
		
		if (count($consulta)> 0){
			$nombre = $consulta[0]["nombre"];
			$activo = $consulta[0]["activo"];
			$credito = $consulta[0]["credito"];
			$cedula = $consulta[0]["cedula"];
			$xlaced =  substr($cedula,0,1) . "-" . substr($cedula,1,3) . "-" . substr($cedula,4,6);
			$cedula = $xlaced;
			$tele1 = $consulta[0]['tele1'];
			$tele2 = $consulta[0]['tele2'];
			$encargado = $consulta[0]['encargado'];
			$direccion = $consulta[0]['direccion'];
			$idempresa = $consulta[0]["idempresa"];
			$tarifaimp = $consulta[0]["tarifaimp"];
			$exodoc 	= $consulta[0]["exodoc"];
			$exofecha 	= substr($consulta[0]["exofecha"], 0,10);
			$exoentidad	= $consulta[0]["exoentidad"];
			$exoporce	= $consulta[0]["exoporce"];
			$exotipo 	= $consulta[0]["exotipo"];

    
	?>
<div id="contenedor">
    <div id="formulario" style="display:none;">
    </div>
    <div id="tabla">
	<form id="frmActualizar" name="frmActualizar" method="post" action="actempresa.php" enctype="multipart/form-data" onSubmit="ActualizarDatos(); return false">
    	<input type="hidden" name="idempresa" id="idempresa" value="<?php echo $idempresa ?>" />
        <p>
	  <label>C&eacute;dula<br />
      <input class="text" type="text" name="cedula" id="cedula" value="<?php echo $cedula ?>" onchange='revisacedulajur();' />
	  <a id='acedremi' onclick='carganave("cedula","CedulaJurSIC","" ); ' href='#'> Buscar</a>
	  </label>
	  </p>
      <br />
        <p>
	  <label>Nombre de la Empresa<br />
	  <input class="text" type="text" name="nomempresa" id="nomempresa" value="<?php echo $nombre ?>" />
	  </label>
	  </p>
      <br />
    <p>
    <label>Tel&eacute;fono<br />
    <input class="text" type="text" name="tele1" id="tele1" value="<?php echo $tele1 ?>" />
    </label>
	</p>
 	<br>
    <p>
    <label>Tel&eacute;fono<br />
    <input class="text" type="text" name="tele2" id="tele2" value="<?php echo $tele2 ?>" />
    </label>
	</p>
 	<br>
    <p>
    <label>Encargado<br />
    <input class="text" type="text" name="encargado" id="encargado" value="<?php echo $encargado ?>" />
    </label>
	</p>

     <p>
     <?php
	   if ($credito == 1){
	   	  echo '<input type="checkbox" name="credito" id="credito" value="1" checked> La empresa tiene crédito';
	   }else{
	   	  echo '<input type="checkbox" name="credito" id="credito" value="1" > La empresa tiene crédito';
	   }
	  ?>
	  <br>
	  </p>
    <br />
     <p>
     <?php
	   if ($activo == 1){
	   	  echo '<input type="checkbox" name="activo" id="activo" value="1" checked> Activo';
	   }else{
	   	  echo '<input type="checkbox" name="activo" id="activo" value="1" > Activo';
	   }
	  ?>
	  <br>
	  </p>
     <br />
     
      <p>

    <label>Tarifa de Impuesto2</label>
   
    <select name="tarifaimp">
      <option value="08" <?php echo ($tarifaimp=='08')?'selected':''; ?> >
        Impuesto Valor Agregado 13%
      </option>
      <option value="01" <?php echo ($tarifaimp=='01')?'selected':''; ?> >
        Tarifa 0% (Exento)
      </option>
      <option value="02" <?php echo ($tarifaimp=='02')?'selected':''; ?> >
        Tarifa reducida 1%
      </option>
      <option value="03" <?php echo ($tarifaimp=='03')?'selected':''; ?> >
        Tarifa reducida 2%
      </option>
      <option value="04" <?php echo ($tarifaimp=='04')?'selected':''; ?> >
        Tarifa reducida 4%
      </option>
      <option value="05" <?php echo ($tarifaimp=='05')?'selected':''; ?> >
        Transitorio 0%
      </option>
      <option value="06" <?php echo ($tarifaimp=='06')?'selected':''; ?> >
        Transitorio 4%
      </option>
      <option value="07" <?php echo ($tarifaimp=='07')?'selected':''; ?> >
        Transitorio 8%
      </option>
    </select>
  </p>
  <p>
    <label>Tipo de documento de exoneración</label>
    <select name="exotipo">
      <option value="01" <?php echo ($exotipo=='01')?'selected':''; ?> >
        Compras autorizadas
      </option>
      <option value="02" <?php echo ($exotipo=='02')?'selected':''; ?> >
        Ventas exentas a diplomáticos
      </option>
      <option value="03" <?php echo ($exotipo=='03')?'selected':''; ?> >
        Autorizado por Ley especial
      </option>
      <option value="04" <?php echo ($exotipo=='04')?'selected':''; ?> >
        Exenciones Dirección General de Hacienda
      </option>
      <option value="05" <?php echo ($exotipo=='05')?'selected':''; ?> >
        Transitorio V
      </option>
      <option value="06" <?php echo ($exotipo=='06')?'selected':''; ?> >
        Transitorio IX
      </option>
      <option value="07" <?php echo ($exotipo=='06')?'selected':''; ?> >
        Transitorio XVII
      </option>
      <option value="99" <?php echo ($exotipo=='07')?'selected':''; ?> >
        Otros
      </option>
    </select>
  </p>
  <p>
    <label>Documento de exoneraci&oacute;n</label>
    <input type="text" name="exodoc" value="<?php echo $exodoc; ?>">
  </p>
  <p>
    <label>Fecha de exoneraci&oacute;n</label>
    <input type="date" name="exofecha" value="<?php echo $exofecha; ?>">
  </p>
  <p>
    <label>Entidad que exonera</label>
    <input type="text" name="exoentidad" value="<?php echo $exoentidad; ?>">
  </p>
  <p>
    <label>Porcentaje de exoneraci&oacute;n</label>
    <input type="text" name="exoporce" value="<?php echo $exoporce; ?>">
  </p>
   <br>
    <p>
    <label>Provincia<br />
    <select name="provincia" id="provincia" onchange="fillCantones();"></select>
    </label>
  </p>
  <p>
      <label>Canton<br/>
        <select name="canton" id="canton" onchange="fillDistritos();" ></select>
      </label>
  </p>
  <p>
      <label>Distrito<br/>
        <select name="distrito" id="distrito"></select>
      </label>
  </p>
 	<br>
    <p>
    <label>Direcci&oacute;n<br />
	<textarea name="direccion" id="direccion" rows="5" cols="50"><?php echo $direccion ?></textarea>
    </label>
	</p>
 	<br>
	  <p>
		<input type="submit" name="submit" id="button" value="Enviar" />
		<label></label>
		<input type="button" name="cancelar" id="cancelar" value="Cancelar" onClick="Cancelar()" />
	  </p>
	  <br />
</form>
    </div>
</div>
	<?php
      }else{
         	echo "<div id='contenedor'>";
         	echo "<div id='tabla' align='center'>";
         	echo "<br>El registro buscado no existe en la base de datos<br><br>";
         	echo "<a href='manempresa.php'>Regresar al mantenimiento</a>";
         	echo "</div>";
         	echo "</div>";
      	}
	}
}
?>
<!--Final /!-->
</div>
</div>
<?php
  Pie();
?>
</body>
</html>
