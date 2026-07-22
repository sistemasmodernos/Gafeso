<?php
include_once('controles.php');
inicio("proenco");
if ($_POST["factura"] == "")
	$ldfecha = date('Y-m-d', time());
else
	$ldfecha = $_POST["fecha"];


$xlaced = $_POST["cedulafa"];
$res = $_POST["cedulafa"];
$ylaced = $_POST["ceddesti"];
$sifrenocierre = DevParametro("frenocierre", 1);
if ($sifrenocierre == 1 && DocumentosSinCierre($_POST["idImpresora"], date('Y-m-d', time())) > 0) {
	echo "Ocurri&oacute; un error al guardar la Encomienda: No puede facturar porque hay Encomiendas o Tiquetes pendientes de cierre en esta impresora ";
	return;
}
$horacorte = DevParametro("horacorte", 3);

if ($horacorte != "" & $horacorte < date('H:m')) {
	echo "Ocurri&oacute; un error al guardar la Encomienda: No se permiten ventas después de las " . $horacorte;
	return;
}

if (!isset($_POST["tipocedfa"]) || $_POST["tipocedfa"] == '') {
	echo "Ocurri&oacute; un error al guardar la Encomienda:  Necesita actualizar la versi�n, por favor presione Ctrl+F5";
	return;
}

if ($_POST["tipocedfa"] == '01' && !preg_match("/^[0-9]{9}$/", $xlaced)) {
	echo "Ocurri&oacute; un error al guardar la Encomienda: la cédula facturar no tiene el formato correcto " . $xlaced . "<br/>";
	return;
}
$v = "/[a-zA-z0-9.-]+\@[a-zA-z0-9.-]+.[a-zA-Z]+/";
if ($_POST["tipocedfa"] != '00' && !preg_match($v, $_POST["correofa"])) {
	echo "Ocurri&oacute; un error al guardar la Encomienda: el correo de la factura no tiene el formato correcto " . $_POST["correofa"] . "<br/>";
	return;
}
if ($_POST["tipocedfa"] == '02' &&  !preg_match('/^[0-9]{10}$/', $xlaced)) {
	echo "Ocurri&oacute; un error al guardar la Encomienda:
								la cédulaa facturar no tiene el formato correcto de cédula juridica " . $xlced . " <br/>";
	echo " resultado " . preg_last_error();
	return;
}

if ($_POST["tipocedfa"] == '03'  &&  !preg_match('/^[0-9]{11,12}$/', $xlaced)) {
	echo "Ocurri&oacute; un error al guardar la Encomienda: la cédulaa facturar no tiene el formato correcto <br/>";
	return;
}

if ($_POST["tipocedfa"] == '04'  &&  !preg_match('/^[0-9]{10}$/', $xlaced)) {
   echo "Ocurri&oacute; un error al guardar la Encomienda: la cédulaa facturar no tiene el formato correcto <br/>";
   return;
}




if ($_POST["tipocedfa"] != '00' &&  !preg_match('/^[0-9]{1}$/', trim($_POST["provincia"]))) {
	echo "Ocurri&oacute; un error al guardar la Encomienda:
								la provincia no tiene el formato correcto " . $_POST["provincia"] . " <br/>";
	return;
}



if ($_POST["tipocedfa"] != '00' &&   !preg_match('/^[0-9]{2}$/', trim($_POST["canton"]))) {
	echo "Ocurri&oacute; un error al guardar la Encomienda: el cantón no tiene el formato correcto <br/>";
	return;
}
if ($_POST["tipocedfa"] != '00' &&  !preg_match('/^[0-9]{2}$/', trim($_POST["distrito"]))) {
	echo "Ocurri&oacute; un error al guardar la Encomienda: el distrito no tiene el formato correcto <br/>";
	return;
}

$_POST["nombrefa"] = replace_specials_characters($_POST["nombrefa"]);

//Revisa que el monto del impuesto sea el correcto

$liva = str_replace(",", "", $_POST["iva"]);
$ldatemp = ClienteSIC($_POST["cedulafa"]);
$lmonto= str_replace(",", "", $_POST["monto"]) - $liva;
$elpor = $ldatemp["poriva"];
$livacorrecto= round($lmonto*$elpor/100,2);

if ($livacorrecto>=$liva){
	if($livacorrecto-$liva > 0.50){
  		echo "Ocurri&oacute; un error al guardar la Encomienda: el monto del impuesto (".$liva.") no es el correcto (".$livacorrecto.")<br/>";
  		return;
	}
}else{
	if($liva-$livacorrecto > 0.50){
  		echo "Ocurri&oacute; un error al guardar la Encomienda: el monto del impuestoi (".$liva.") no es el correcto (".$livacorrecto.")<br/>";
  		return;
	}
}

$datos =  array(
	"fecha" => $ldfecha,
	"monto" => str_replace(",", "", $_POST["monto"]),
	"iva" => str_replace(",", "", $_POST["iva"]),
	"idestacion" => $_POST["idEstacion"],
	"idproducto" => $_POST["idProducto"],
	"idparada" => $_POST["idParada"],
	"idcliente" => $_POST["idCliente"],
	"idimpresora" => $_POST["idImpresora"],
	"peso" => str_replace(",", "", $_POST["peso"]),
	"remitente" => sqlClearText($_POST["remitente"]),
	"destinatario" => sqlClearText($_POST["destinatario"]),
	"tcedremi" => sqlClearText($_POST["tcedremi"]),
	"cedremi" => sqlClearText($xlaced),
	"ceddesti" => sqlClearText($ylaced),
	"telremi" => sqlClearText($_POST["telremi"]),
	"teldesti1" => sqlClearText($_POST["teldesti"]),
	"teldesti2" => sqlClearText($_POST["teldesti2"]),
	"nota" => sqlClearText($_POST["nota"]),
	"cantbul" => str_replace(",", "", $_POST["cantbul"]),
	"declarado" => str_replace(",", "", $_POST["declarado"]),
	"emailremi" => sqlClearText($_POST["emailremi"]),
	"idformapago" => sqlClearText($_POST["idformapago"]),
	"factura" => sqlClearText($_POST["factura"]),
	"detprod" => sqlClearText($_POST["detprod"]),
	"idviaje" => $_POST["idViaje"],
	"esmanual" => $_POST["esmanual"],
	"tipocedula" => $_POST["tcedremi"],
	"provincia" => $_POST["provincia"],
	"canton" => str_pad($_POST["canton"], 2, "0", STR_PAD_LEFT),
	"distrito" => str_pad($_POST["distrito"], 2, "0", STR_PAD_LEFT),
	"barrio" => "01",
	"otrassenas" => sqlClearText($_POST["otrassenas"]),
	"tipocedfa" => $_POST["tipocedfa"],
	"cedulafa" => $_POST["cedulafa"],
	"nombrefa" => $_POST["nombrefa"],
	"correofa" => $_POST["correofa"],
	"actividad"=>$_POST["actividad"]
);
$res = VenderEncomienda($datos);
if ($res == null)
	echo "Ocurri&oacute; un error al guardar la Encomienda: <br/>" . pasaHtml($_SESSION["parametros"]["mensaje_error"]);
else
	echo "<div>La encomienda se guard&oacute; correctamente </br>" .
		"<input type='hidden' id='txtencomiendavendida'  value=" . $res . ">" .
		"Desea imprimir el comprobante ? 
				<input type='button' value='Imprimir' onclick='imprimetiq(" . $res . ");'></div> ";


function replace_specials_characters($s)
{
	$s = preg_replace("/�~A|�~@|�~B|�~C/", "A", $s);
	$s = preg_replace("/é|è|ê/", "e", $s);
	$s = preg_replace("/�~I|�~H|�~J/", "E", $s);
	$s = preg_replace("/í|ì|î/", "i", $s);
	$s = preg_replace("/�~M|�~L|�~N/", "I", $s);
	$s = preg_replace("/ó|ò|ô|õ|º/", "o", $s);
	$s = preg_replace("/�~S|�~R|�~T|�~U/", "O", $s);
	$s = preg_replace("/ú|ù|û/", "u", $s);
	$s = str_replace("ñ", "n", $s);
	$s = str_replace("�~Q", "N", $s);
	$s = preg_replace('/[^a-zA-Z0-9\s_.-]/', ' ', $s);
	return $s;
}
