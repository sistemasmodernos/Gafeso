//Inicia todos los objetos de la p'agina
$(document).ready(function() {
	var date = new Date();
	var d = date.getDate();
	var m = date.getMonth();
	var y = date.getFullYear();
	$("#excel").change(excelcambia);
	$('.fecha').datepicker({inline: true});
	$('.fecha').datepicker('option', {dateFormat: 'dd/mm/yy'});                
	$(".fecha").datepicker($.datepicker.regional['es']);
});

function validacion(){
	var tira = "";
	var vuelta = true;
	var todovacio = true;
	if (!esVacio("#txtfactura")){
		todovacio = false ;
	}
	if (!esVacio("#txtremi")){
		todovacio = false ;
	}
	if (!esVacio("#txtcedr")){
		todovacio = false ;
	}
	if (!esVacio("#txtdesti")){
		todovacio = false ;
	}
	if (!esVacio("#txtcedd")){
		todovacio = false ;
	}
	var sifecha1 = false;
	if (!esVacio("#txtfecha")){
		todovacio = false ;
		sifecha1 = true;
		if (!esFecha($("#txtfecha").val())){
			vuelta = false ;
			tira += "la fecha inicial no tiene un formato válido de fecha\n";
		}
	}
	if (!esVacio("#txtfechaf")){
		todovacio = false ;
		if (!esFecha($("#txtfechaf").val())){
			vuelta = false ;
			tira += "la fecha final no tiene un formato válido de fecha\n";
		}
		if (!sifecha1){
			vuelta = false ;
			tira += "Si digita la fecha final, debe también digitar la fecha inicial\n";
		}
	}
	if (todovacio){
		vuelta = false;
		tira += "Debe digitar al menos un dato para buscar\n";
	}
	if (!vuelta){
		alert(tira);
	}
	return vuelta;
}
		
function esVacio(campo){
    var retorno = false;
	var eldato = $(campo).val();
	if(eldato == ""){
		retorno = true;
		//tira = tira + "Debe digitar " + campo.placeholder + " ";
	};
	return retorno;
}

function esFecha(string) 
{ //string estará en formato dd/mm/yyyy (dí­as < 32 y meses < 13)
	var ExpReg = /^([0][1-9]|[12][0-9]|3[01])(\/|-)([0][1-9]|[1][0-2])\2(\d{4})$/ ;
	return (ExpReg.test(string));
}

function excelcambia(){
	if ($("#excel").is(':checked')){
		$("#formconsulta").attr('action', 'excenco.php');
		$("#formconsulta").attr("target", "_blank");
		$("#Consulta").attr('value', 'Descargar Excel');
	}else{
		$("#formconsulta").attr('action', 'buscarenco.php');
		$("#formconsulta").attr("target", "_self");
		$("#Consulta").attr('value', 'Buscar Encomiendas');
	}
}
