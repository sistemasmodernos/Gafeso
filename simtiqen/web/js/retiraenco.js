function revisacedula(){
	if (! $("#cedula").attr("value").match(/^[0-9]{1}\-[0-9]{4}\-[0-9]{4}/)) {
		alert("Cedula no válida, formato 99-9999-9999");
	}
	var xlaced = $("#cedula").val();
	var res = $("#cedula").val();
	 xlaced =  res.replace(/\-/g,"");
	var rut="enlace.php?opcion=datosenco&cedula=" + xlaced;
	enlace(rut,fin_revisacedula);
}
function fin_revisacedula(dato){
	var dat= dato.split(',');
	if(dat.length>0){
		$("#nomauto").val(dat[0]);
	}
}
	
function llegadacustom(){
	return true;
}

function guardaretira(){
	alert($("#autoriza").val());
    if ($("#autoriza").val() == "0"){
		if (!validacedula("cedula")){
			alert("Cédula de la persona que retira no tiene el formato correcto");
			return false;
		}
		if (esVacio("#nomauto")){
			alert("No puede dejar el nombre de la persona que retira vacio");
			return false;
		}
	}else{
		return false;
	}
}

function validacedula(campo,tipo){
    var valido = true;
	valido = $("#"+campo).attr("value").match(/^[0-9]{1}\-[0-9]{4}\-[0-9]{4}/);
	return valido;
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

function marcaenco(pimpresora,pfactura){
	$("#impresora").val(pimpresora);
	$("#numenco").val(pfactura);
	$("#buscar").click();
}

function buscacheck(){
	var res ="";
	$("input:checkbox:checked").each(   
    function() {
        res+=$(this).val() +",";
    });
    if(res!="")
    	res=res.substring(0,res.length-1);
    $("#txtidencomiendas").val(res);

}


function imprimeEncore(numeros){
	enlace("impenco.php?id="+numeros,imprimeEncofin);
	
}

function filtra(){
	var $valor = $("#txtfiltro").val().toUpperCase().trim();
	$("#tablaenbody tr").each(function( index ) {
  		if($valor=='' || $(this).html().toUpperCase().includes($valor) )
  			$(this).show();
  		else
  			$(this).hide();
	});

}


function revisacedulafa2(){
	var xlaced = $("#txtcedulafa").val();
		
	if(xlaced.substr(0,1)=="0")
	 $("#txtcedulafa").val(xlaced.substr(1,15));
		
	xlaced = $("#txtcedulafa").val();

	if ($("#tipocedfa").val()=="01"){
       	if(xlaced.indexOf('-')<0)
		  $("#txtcedulafa").val(xlaced.substring(0,1)+"-"+xlaced.substr(1,4)+"-"+xlaced.substr(5,4))

		if (! $("#txtcedulafa").val().match(/^[0-9]{1}\-[0-9]{4}\-[0-9]{4}/)) {
				alert("Cedula no válida, formato 9-9999-9999");
			}
	}
	if ($("#tipocedfa").val()=="02"){
        if(xlaced.indexOf('-')<0)
		  $("#txtcedulafa").val(xlaced.substr(0,1)+"-"+xlaced.substr(1,3)+"-"+xlaced.substr(4,6))

		if (! $("#txtcedulafa").val().match(/^[0-9]\-[0-9]{3}\-[0-9]{6}/)) {
				alert("Cédula no válida, formato 9-999-999999");
		}
	}
		
	var res = $("#txtcedulafa").val().replace(/\-/g,"");
		
	if(xlaced!="1111111111"){
		var rut="enlace.php?opcion=datosenco&cedula=" + res;
		enlace(rut,fin_revisacedulafa2);
	}

}

function fin_revisacedulafa2(dato){
	var dat= dato.split(',');
	if(dat.length>1){
		$("#txtnombrefa").val(dat[0]);
		$("#txtcorreofa").val(dat[2]);
	}
	else
	{
		$("#txtnombrefa").val("");
		$("#txtcorreofa").val("");

	}
}