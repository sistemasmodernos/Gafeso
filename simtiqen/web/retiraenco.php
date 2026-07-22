<?php
include_once('controles.php');
inicio("retiraenco");
if(!isset($_POST["impresora"]))
	$limpresora = $_SESSION["parametros"]["impresoradefault"];
else
	$limpresora = $_POST["impresora"];

$lidencomiendas = "";
if(isset($_POST["txtidencomiendas"]))
	$lidencomiendas=$_POST["txtidencomiendas"];

if(isset($_POST["numenco"])){ 
	$txtenco = $_POST["numenco"];
}else{
	$txtenco = "";
}

?>

<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" /> 
	<title>Sistemas de ventas de tiquetes</title> 
	<link rel="stylesheet" href="css/general.css" type="text/css" />
	<link rel="stylesheet" href="style.css" type="text/css" />
	<script src="js/jquery-1.5.1.min.js" type="text/javascript"></script>
	<script type="text/javascript" src="js/sfunciones.js"></script>  
	<script src="js/encomiendas01.js" type="text/javascript"></script>
	<script src="js/retiraenco.js" type="text/javascript"></script>
	<script type="text/javascript">
		
		$(document).ready(function(){
			ajusta();
			$('#frmInicio').submit(function(e){
				e.preventDefault();
				buscacheck();
				this.submit();
			});
		});
		
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
			<div id="contenedor">
				<h2 style='margin:auto;width:400px;'>Entregar Encomienda</h2>
				<?php
				$existe = false;
				$losid = array();
				if(isset($_POST["numenco"])){ 
					$laconsulta = new Encomienda();
					if($lidencomiendas==""){
						$factura = $_POST["numenco"];
						$impresora = $_POST["impresora"];
						$numencomienda = $laconsulta->idxFactura($impresora,$factura);
						array_push($losid, $numencomienda);
					}
					else
					{
						$losid = explode(',',$lidencomiendas);
					}
					
					for($k=0;$k<count($losid);$k++){
						echo "<div class='divenco'>";
						echo "<p> Numero de encomienda encontrado ".$losid[$k]."</p>";
						$laconsulta->idEncomienda = $losid[$k];
						if ($laconsulta->Cargar()){
							echo $laconsulta->HTMLCorto($losid[$k]);
							if ($laconsulta->retirada == 0){
								$existe = true;
							}else{
								echo "<br>La Encomienda " . $factura . " fue ya retirada por " . $laconsulta->autocedula . " " . $laconsulta->autonombre .  " <br><br>";
							}
						}else{
							echo "<br>La Encomienda " . $factura . " NO existe, por favor revise <br><br>";
						}
						echo "</div>";
					}
				}
				if ($existe){
					?>
					<div id="divretira">

						<?php 
						if(isset($_POST["buscar"])){ 
							echo "Seleccione o ";
						}
						?>
						digite la persona que retira la encomienda
						<form id="frmretira" name="frmretira" method="post" action="retiraenco.php" enctype="multipart/form-data" >
							<input type="hidden" name="idencomienda" id="idencomienda" value="<?php echo $numencomienda ?>" />
							<input type="hidden" name="txtidencomiendas2" id="txtidencomiendas2" value="<?php echo $lidencomiendas ?>" />
							
							<table>
								<tr>
									<td>
										<select id='autoriza' name='autoriza'>
											<option value='0' selected>Digitado Manualmente</option>
											<?php
											$laempresa = new Empresa();
											if ($laempresa->CargarCedula($laconsulta->ceddesti)){
												$res=$laempresa->autorizados();
												for($x=0;$x<count($res);$x++){
													echo "<option value='". $res[$x]["idautoriza"]."'";
													echo">".$res[$x]["nombre"]. " (" . $res[$x]["cedula"] . ")</option>";
												}
											}
											?>
										</select>
									</td>
								</tr>
							</table>
							<table>
								<tr>
									<th>C&eacute;dula</th>
									<th>Nombre del Autorizado</th>
								</tr>
								<td>
									<input type="text" name="cedula" id="cedula" onchange='revisacedula();' size='12' />
									<a id='acedremi' onclick='carganave("cedula","CedulaSIC","" ); ' href='#'>Buscar</a>
								</td>
								<td>
									<input type="text" name="nomauto" id="nomauto" size='60' />
								</td>
							</tr>

							<?php 
							echo var_dump($laconsulta->facturafe);
							  
							  if($laconsulta->facturafe==''){
							  
							  	echo "<tr><td>";
							  	echo "<div id='datosfactura'><h4>Datos para la factura</h4>";
							  	echo "<table>
							  			<tr>
							  				<td>
							  					Tipo ID
							  				</td>
							  				<td>
							  					<select id='tipocedfa' name='tipocedfa'>";
								echo "							  					
            										<option value='00' ". (($laconsulta->tipocedfa=='00')?'selected':'').">
            											Impersonal
            										</option>";
            					echo "				<option value='01' ". (($laconsulta->tipocedfa=='01')?'selected':'').">
            											Persona F&iacute;sica
            										</option>
            										<option value='02' ". (($laconsulta->tipocedfa=='02')?'selected':'').">
            											Persona Jur&iacute;dica
            										</option>
            										<option value='03' ". (($laconsulta->tipocedfa=='03')?'selected':'').">
            											DIMEX
            										</option>
            										<option value='03' ". (($laconsulta->tipocedfa=='03')?'selected':'').">
            											NITE
            										</option>
          										</select>
							  				</td>
							  			</tr>
							  			<tr>
							  				<td>
							  					c&eacute;dula
							  				</td>
							  				<td>
							  				<input type='text' 
							  					id='txtcedulafa' 
							  					name='txtcedulafa'
							  					onchange='revisacedulafa2();' 
							  					value='".$laconsulta->cedulafa."'>
							  				</td>
							  			</tr>
							  			<tr>
							  				<td>
							  					Nombre
							  				</td>
							  				<td>
							  					<input type='text' 
							  						id='txtnombrefa' 
							  						name='txtnombrefa'
							  						value='".$laconsulta->nombrefa."'>
							  				</td>
							  			</tr>
							  			<tr>
							  				<td>
							  					Correo
							  				</td>
							  				<td>
							  					<input type='text' 
							  						id='txtcorreofa' 
							  						name='txtcorreofa' 
							  						value='".$laconsulta->correofa."'>
							  				</td>
							  			</tr>
							  		  </table>";
							  	echo "</div>";
							  	echo "</td></tr>";
							  }
							  else{
							  	echo "<input type='hidden' 
							  			id='tipocedfa' 
							  			name='tipocedfa' 
							  			value='00'>
							  	<input type='hidden' 
							  			id='txtcedulafa'
							  			name='txtcedulafa'
							  			value=''>
							  	<input type='hidden'
							  			id='txtnombrefa'
							  			name='txtnombrefa'
							  			value=''>
							  	<input type='hidden'
							  			id='txtcorreofa'
							  			name='txtcorreofa'
							  			value=''>

							  	";
							  }

							?>

							<tr><td><input type='checkbox' name='ckfirma' id='ckfirma'  value="Pedir Firma" checked>Pedir Firma</td></tr>
						</table>
						<div>
						<?php
						    $rd = debeImprimir($numencomienda);
						    
							if($rd>0){
								$checked="";
								if($rd==1)
									$checked="checked";
								echo "<input type='checkbox' id='ckimprimeen' name='ckimprimeen' ".$checked." /> Deseo imprimir el comprobante";


							}

						?>
						</div>
						<input type="submit" name="retirar" id="retirar" value="Entregar la Encomienda" onsubmit='guardaretira();' />
						
					</form>
				</div>
				<?php
			}else{
				?>
				<div id="divInicio">
					<?php
					$men="";
					if(isset($_POST["retirar"])){
						$tipocedfa = $_POST["tipocedfa"];
						$cedulafa = str_replace("-", "", trim($_POST["txtcedulafa"]));
						$nombrefa = $_POST["txtnombrefa"];
						$correofa = $_POST["txtcorreofa"];
						if($tipocedfa!='00'){
							if($tipocedfa=='01' && strlen($cedulafa)!=9)
								$men="La c&eacute;dula no tiene el tamaño establecido (9)";
							if($tipocedfa=='02' && strlen($cedulafa)!=10)
								$men="La c&eacute;dula no tiene el tamaño establecido (10)";

						}
						if($men!="")
							echo "<h2 style='color:black;background-color:yellow'>**Atenci&oacute;n** ".$men."</h2>";
					}


					if(isset($_POST["retirar"]) && $men==""){ 
						$cedula = $_POST["cedula"];
						$nombre = $_POST["nomauto"];
						

						if(isset($_POST["autoriza"])) { $combo = $_POST["autoriza"]; }else{ $combo = "0"; }

						if ($combo != "0" || ($cedula != "" && $nombre != "&&")){

							if($_POST["txtidencomiendas2"]=="")
								$lasid = $_POST["idencomienda"];
							else
								$lasid = $_POST["txtidencomiendas2"];

							 $enco = new Encomienda();

							 $elres = RetiraEncomienda($lasid,$combo,$cedula,$nombre,$tipocedfa,$cedulafa,$nombrefa,$correofa);
							if ($elres!="No Existe"){
								if ($elres=="Correcto"){
									echo "<br>Los datos se guardaron correctamente<br>";
									if(isset($_POST["ckimprimeen"]))
										echo "<script>
									           setTimeout(imprimeEncore(".$lasid."),1000);
									         </script>";

									$existe = true;
									if(isset($_POST["ckfirma"]))
										PonerColaFirma($lasid);
									else 
										echo "<h1> no ckfirma </h1>";
								}
							}else{
								echo "<h1>La Encomienda " . $factura . " NO existe, por favor revise</h1> <br><br>";
							}
						}else{
							echo "<h1>Los datos ingresados no son válidos</h1><br><br>";
						}
					}
					?>
					<form id="frmInicio" name="frmInicio" method="post" 
					action="retiraenco.php" enctype="multipart/form-data" >
					<input type ='hidden' id='txtidencomiendas' name='txtidencomiendas' />
					<table>
						<tr>
							<th>Impresora</th>
							<td>
								<select id='impresora' name='impresora'>
									<?php
									$res=ListaActivos("Impresora",null);
									for($x=0;$x<count($res);$x++){
										echo "<option value='". $res[$x]["idimpresora"]."'";
										if($limpresora==$res[$x]["idimpresora"])
											echo " selected ";
										echo">".$res[$x]["nombre"]. "</option>";
									}
									?>
								</select>
							</td>
						</tr>
						<tr>
							<th>Número de Encomienda a Entregar</th>
							<td><input type="text" name="numenco" id="numenco" size='20' value='<?php echo $txtenco; ?>'/></td>

						</tr>
						<tr>
							<th>&nbsp;</th>
							<td><input type="submit" name="buscar" id="buscar" value="Buscar" /></td>
						</tr>
					</table>
					<div>
						<h2>Encomiendas abiertas de los &uacute;ltimos 2 meses</h2>
						<div>
							<input type="text" 
							  name="txtfiltro" 
							  id="txtfiltro"
							  class="filtro" 
							  placeholder="Digite aquí su filtro" 
							  onkeyup="filtra();"
							>
						</div>
						<table>
							<thead>
								<tr>
									<th>Retira</th>
									
									<th>Impresora</th>
									<th>ID</th>
									<th>Ubica</th>
									<th>Factura</th>
									<th>Remitente</th>
									<th>Destinatario</th>
									<th>Detalle</th>
									<th>Destino</th>
									<th>Monto Transferencia</th>
								</tr>
							</thead>
							<tbody id="tablaenbody">
								<?php

								$res2 = EncomiendasAbiertas(Date('Y-m-d',strtotime ( '-60 day' , strtotime (Date('Y-m-d'))) ),Date('Y-m-d'));



								for($x=0;$x<count($res2);$x++){
									if($res2[$x]["tipo"]!="3")
									  echo "<tr>";
								    else
								      echo "<tr style='background-color:yellow'>";
								    echo "<td><input type='checkbox' value='".$res2[$x]["idencomienda"]."' /></td>
									<td>".$res2[$x]["impresora"]."</td>
									<th><a href='#' onclick='marcaenco(\"".$res2[$x]["idimpresora"]."\",\"".$res2[$x]["factura"]."\");'>".$res2[$x]["factura"]."</a></th>
									<td>".$res2[$x]["ubicacion"]."</td>
									<td>".$res2[$x]["factura"]."</td>
									<td>".$res2[$x]["remitente"]."</td>
									<td>".$res2[$x]["destinatario"]."</td>
									<td>".$res2[$x]["nota"]."</td>
									<td>".$res2[$x]["destino"]."</td>";
									if($res2[$x]["tipo"]=="3")
										echo "<td style='text-align:right;'>".
											number_format($res2[$x]["declarado"]) .
										"</td>";
									else
										echo "<td>&nbsp;</td>";
								echo "</tr>";
							}

							?>


						</tbody>
					</table>



				</div>
			</form>
		</div>
		<?php
	}
	?>

</div>
</div>
</div>
<?php
Pie();
?>
</body>
</html>
