<?php
  include_once('controles.php');
  inicio("manempresa");
  $provincia = "1";
  $canton = "";
  $distrito = "";
  $barrio = "";
  $correo = "";
  $tarifaimp = "08";
  $exodoc = "";
  $exofecha = "1900-01-01";
  $exoentidad = "";
  $exoporce = 100;
  $exotipo='03';
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
        <script type="text/javascript" src="js/encomiendas.js"></script>  
 <script type="text/javascript">
	$(function(){ 
    ajusta();
    loadDirecciones();
  })
 	var dir;
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

<?php
$lmensaje="";
$lpasa=true;
if(isset($_POST['submit'])){
	if($_POST["nomempresa"]==""){
	  $lmensaje.= "No puede dejar el nombre de la compañia en blanco ";
	}
  if($_POST["exodoc"]!="" && $_POST["exoporce"]=="0")
     $lmensaje.=" Si digita algo en el campo de exoneración o puede dejar en cero el porcentaje de exoneraci&oacute;n";
} 

if(isset($_POST['submit']) && $lmensaje==""){
	$nomempresa = htmlspecialchars(trim($_POST['nomempresa']));
	$cedula = htmlspecialchars(trim($_POST['cedula']));
	$tele1 = htmlspecialchars(trim($_POST['tele1']));
	$tele2 = htmlspecialchars(trim($_POST['tele2']));
	$encargado = htmlspecialchars(trim($_POST['encargado']));
	$direccion = htmlspecialchars(trim($_POST['direccion']));
  $provincia = $_POST['provincia'];
  $canton = $_POST['canton'];
  $distrito = $_POST['distrito'];
  $correo = $_POST['correo'];
  $tarifaimp = $_POST['tarifaimp'];
  $exodoc = $_POST["exodoc"];
  $exofecha = $_POST["exofecha"];
  $exoentidad = $_POST["exoentidad"];
  $exoporce = $_POST["exoporce"];
  $exotipo = $_POST["exotipo"];

	if (isset($_POST['activo'])){
    	$activo = 1;
	}else{
		$activo = 0;
	}
	if (isset($_POST['credito'])){
    	$credito = 1;
	}else{
		$credito = 0;
	}
?>
	<div id="contenedor">
    <div id="formulario" style="display:none;">
    </div>
    <div id="tabla">
	<br />
	<p align="center">
<?php	
    if (Mantenimiento("empresa",array("nombre"=>$nomempresa
      ,"cedula"=>$cedula
      ,"tele1"=>$tele1
      ,"tele2"=>$tele2
      ,"encargado"=>$encargado
      ,"direccion"=>$direccion
      ,"activo"=>$activo
      ,"credito"=>$credito
      ,"provincia"=>$provincia
      ,"canton"=>$canton
      ,"distrito"=>$distrito
      ,"barrio"=>""
      ,"correo"=>$correo
      ,"tarifaimp"=>$tarifaimp
      ,"exodoc" => $exodoc
      ,"exofecha"=> $exofecha
      ,"exoentidad" => $exoentidad
      ,"exoporce" => $exoporce
      ,"exotipo" => $exotipo
    ),1) == true){
		echo 'Datos guardados<br>';
	}else{
		echo 'Se produjo un error. Intente nuevamente<br>';
	}
?>
	<a href="manempresa.php">Regresar al mantenimiento</a>
	</p>
    </div>
   </div>
<?php
}else{
?>
    <div id="contenedor">
    <div id="formulario" style="display:none;">
    </div>
    <div class="mensaje">
      <?php echo $lmensaje; ?>
   </div>

    <div id="tabla">
	<br />
    <form id="frmNuevo" name="frmNuevo" method="post" action="newempresa.php" enctype="multipart/form-data" onSubmit="GrabarDatos(); return false">
    <p>
    <label>C&eacute;dula<br />
    <input class="text" type="text" name="cedula" id="cedula"  />
	<a id='acedremi' onclick='carganave("cedula","CedulaJurSIC","" ); ' href='#'> Buscar</a>
    </label>
	</p>
 	<br>
    <p>
    <label>Nombre de la Empresa<br />
    <input class="text" type="text" name="nomempresa" id="nomempresa" />
    </label>
	</p>
 	<br>
    <p>
    <label>Tel&eacute;fono<br />
    <input class="text" type="text" name="tele1" id="tele1" />
    </label>
	</p>
 	<br>
    <p>
    <label>Tel&eacute;fono 2<br />
    <input class="text" type="text" name="tele2" id="tele2" />
    </label>
	</p>
 	<br>
    <p>
    <label>Encargado<br />
    <input class="text" type="text" name="encargado" id="encargado" />
    </label>
	</p>
    <br>
    <p>
    <label>Correo<br />
    <input class="text" type="email" name="correo" id="correo" />
    </label>
  </p>
 
  <p>
    <label>Tarifa de Impuesto</label>
    <select name="tarifaimp">
      <option value="08" <?php ($tarifaimp=='08')?'selected':''; ?> >
        Impuesto Valor Agregado 13%
      </option>
      <option value="01" <?php ($tarifaimp=='01')?'selected':''; ?> >
        Tarifa 0% (Exento)
      </option>
      <option value="02" <?php ($tarifaimp=='02')?'selected':''; ?> >
        Tarifa reducida 1%
      </option>
      <option value="03" <?php ($tarifaimp=='03')?'selected':''; ?> >
        Tarifa reducida 2%
      </option>
      <option value="04" <?php ($tarifaimp=='04')?'selected':''; ?> >
        Tarifa reducida 4%
      </option>
      <option value="05" <?php ($tarifaimp=='05')?'selected':''; ?> >
        Transitorio 0%
      </option>
      <option value="06" <?php ($tarifaimp=='06')?'selected':''; ?> >
        Transitorio 4%
      </option>
      <option value="07" <?php ($tarifaimp=='07')?'selected':''; ?> >
        Transitorio 8%
      </option>
    </select>
  </p>
  <p>
    <label>Tipo de documento de exoneración</label>
    <select name="exotipo">
      <option value="01" <?php ($exotipo=='01')?'selected':''; ?> >
        Compras autorizadas
      </option>
      <option value="02" <?php ($exotipo=='02')?'selected':''; ?> >
        Ventas exentas a diplomáticos
      </option>
      <option value="03" <?php ($exotipo=='03')?'selected':''; ?> >
        Autorizado por Ley especial
      </option>
      <option value="04" <?php ($exotipo=='04')?'selected':''; ?> >
        Exenciones Dirección General de Hacienda
      </option>
      <option value="05" <?php ($exotipo=='05')?'selected':''; ?> >
        Transitorio V
      </option>
      <option value="06" <?php ($exotipo=='06')?'selected':''; ?> >
        Transitorio IX
      </option>
      <option value="07" <?php ($exotipo=='06')?'selected':''; ?> >
        Transitorio XVII
      </option>
      <option value="99" <?php ($exotipo=='07')?'selected':''; ?> >
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
	<textarea name="direccion" id="direccion" rows="5" cols="50"></textarea>
    </label>
	</p>
 	<br>
	<p>
	  <input type="checkbox" name="credito" id="credito" value="1" checked> La empresa tiene cr&eacute;dito<br>
	  </p>
 	<br>
	<p>
	  <input type="checkbox" name="activo" id="activo" value="1" checked> Activo<br>
	  </p>
  <p>
    <br />
    <input type="submit" name="submit" id="button" value="Enviar" />
    <label></label>
    <input type="button" class="cancelar" name="cancelar" id="cancelar" value="Cancelar" onClick="Cancelar()" />
  </p>
</form>
    </div>
</div>
<?php
}
?>
</div>
</div>
<?php
  Pie();
?>
</body>
</html>
