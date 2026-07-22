var gcantbul=0;
var datosimp2="";
var datosimp3="";
var lWSC2=null;
var lWSC3=null;

//carga los horarios de los viajes segun la fecha            
function refrescaviaje(funciondespues){
	var lafecha;
	if($("#datepicker").val()==null){
		lafecha= fechahoy();
	}
	else
	{
		lafecha=$("#datepicker").val();
	}

	traeajax("divcrtviaje","crtviaje.php?estacion="+
		$("#cbestacion").val()+
		"&ruta="+$("#cbruta").val()+
		"&fecha="+fechatoUTC(lafecha)+
		"&parada="+$("#cbparadades").val()+
		"&tipo=2",null,funciondespues);
}

function traecalendario(){
		//se quita el calendario de la pantalla de encomiendas
		return 
		var lafecha;
		if($("#datepicker").val()==null){
			var f = new Date();
			lafecha= f.getDate() + "/" + (f.getMonth() +1) + "/" + f.getFullYear();
		}
		else
		{
			lafecha=$("#datepicker").val();
		}
                //trae el calendario a partir de la nueva fecha
                traeajax("calendario","calenviajes.php?ruta="+
                	$("#cbruta").val()+
                	"&estacion="+$("#cbestacion").val()+
                	"&inicio="+fechatoUTC(lafecha)+
                	"&dias=1"
                	,null);
                setTimeout('traecalendario()', 5000);
            }
//carga los horarios de los viajes segun la fecha                        
function refrescahora(){
	var lafecha;
	if($("#datepicker").val()==null){
		var f = new Date();
		lafecha= f.getDate() + "/" + (f.getMonth() +1) + "/" + f.getFullYear();
	}
	else
	{
		lafecha=$("#datepicker").val();
	}


	traeajax("divhora","crtviaje.php?estacion="+
		$("#cbestacion").val()+
		"&ruta="+$("#cbruta").val()+
		"&fecha="+fechatoUTC(lafecha)+
		"&parada="+$("#cbparadades").val()+
		"&tipo=2",null,cambiahora);

}

//cambia formato de fecha 01/12/2011 a 2011-12-01
function fechatoUTC(lcfecha){
			var cad = lcfecha.split('/');
			var res=cad[2];
			
			if (cad[1].length==1)
				res=res+'-0'+cad[1];
			else
				res=res+'-'+cad[1] ;

			if (cad[0].length==1)
				res=res+'-0'+cad[0];	
			else
				res=res+'-'+cad[0];
			
			return res;
}

//Carga toda la divisi'on de horarios
function llegadacustom(nombrediv,funciondespues){
	switch (nombrediv){
		case 'divcrtviaje' : 
            $('#datepicker').datepicker({inline: true });
            $("#datepicker").datepicker($.datepicker.regional['es']);
        break;
        case 'divhora': 
            cambiahora();
        break;
     }
     if(funciondespues!=null)
      	funciondespues();
 }

// se ejecuta cuando cambian el d'ia            
function cambiafecha(){
	refrescahora();

}
function cambiahora(valor){

}

function creaparametros(){
	var lafa="";
	var lesmanual=0;
	if($("#ckmanual").is(":checked")){
		lesmanual=1;
		lafa=$("#txtfactura").val();
		for(x=lafa.length;x<6;x++)
			lafa="0"+lafa;
	}
	var param = {
		"fecha": fechatoUTC($("#txtfechae").val()),
		"monto": $("#txtmonto").val(),
                "iva": $("#txtiva").val(),
		"idViaje": $("#cbhora").val(),
		"idEstacion": $("#cbestacion").val(),
		"idProducto": $("#cbproducto").val(),
		"idParada": $("#cbparadades").val(),
		"idCliente": $("#txtidcliente").val(),
		"idImpresora": $("#cbimpresora").val(),
		"peso": $("#txtpeso").val(),
		"remitente": $("#txtnomremi").val(),
		"destinatario": $("#txtnomdesti").val(),
		"tcedremi": $("#tcedremi").val(),
		"cedremi": $("#txtcedremi").val().replace(/\-/g,""),
		"tceddesti": $("#tceddesti").val(),
		"ceddesti": $("#txtceddesti").val().replace(/\-/g,""),
		"telremi": $("#txttelremi").val(),
		"teldesti": $("#txtteldesti").val(),
		"teldesti2": $("#txtteldesti2").val(),
		"nota": $("#txtnotas").val(),
		"cantbul": $("#txtcantbul").val(),
		"declarado": $("#txtdeclarado").val(),
		"emailremi": $("#txtemailremi").val(),
		"idformapago": $("#cbformapago").val(),
		"detprod":$("#txtdetprod").val(),
		"esmanual": lesmanual,
		"factura": lafa,
		"provincia":$("#provincia").val(),
		"canton":$("#canton").val(),
		"distrito":$("#distrito").val(),
		"otrassenas":$("#txtotrassenas").val(),
		"tipocedfa":$("#tipocedfa").val(),
		"cedulafa":$("#txtcedulafa").val().replace(/\-/g,""),
		"nombrefa":$("#txtnombrefa").val(),
		"correofa":$("#txtcorreofa").val()
	};
	return param;
}
function generaventa(){

	if($("#tcedremi").val()!='00'){
		 	var mes = validaDatosFe();
		 	if(mes!=""){
		 		alert(mes);
		 		return;
		 	}
		} 

	
	if($("#txtcantbul").val()==0){
		alert("No puede dejar la cantidad de bultos en cero")
		return false;
	}
	gcantbul=$("#txtcantbul").val();

	if($("#txtmonto").val()<0 && $("#cbproducto").val()!="99"){
		alert("No puede dejar el monto en cero")
		return false;
	}

	if($("#txtmonto").val()>150000)
		if(!confirm("Está encomienda tiene un precio mayor a 150,000 es correcto"))
			return false;
		if($("#txtmonto").val()<0){
			alert("La encomienda no puede tener un monto negativo")
			return false;
		}

		$("#btvender").attr("disabled",true);
		$("#btvender").val("Procesando..");
		var par = creaparametros();
		$.post("vendeencomienda.php",par,function(data){
			var res = data;
			despuesventa(res);
		});
}
function despuesventa(data){
	if(data.includes("error")){
		createPopup("Datos de la venta",data);
	}
	else
	{
		setTimeout(function() {
			imprimetiq($("#txtencomiendavendida",data).val());
			}, 1);
	}
	if(!data.includes("error")){
		cambiaruta();
		traecalendario();
		$("#txtnomremi").val("");
		$("#txtnomdesti").val("");
		$("#txtcedremi").val("");
		$("#txtceddesti").val("");
		$("#txttelremi").val("");
		$("#txtteldesti").val("");
		$("#txtteldesti2").val("");
		$("#txtemailremi").val("");
		$("#txtpeso").val("0");
		$("#txtdeclarado").val("0");
		$("#txtcantbul").val("1");
		$("#txtmonto").val("0");
        $("#txtiva").val("0");
        $("#txtsubtotal").val("0");
		$("#txtnotas").val("");
		$("#cbprodenco").val("0");
		$(".prod").val("");
		$(".canprod").val(0);
		$(".precioprod").val(0);
		$("#otrassenas").val("");
		$("#tcedremi").val("00");
		$("#tcedremi").val("00");
		$("#tipocedfa").val("00");
		$("#txtcedulafa").val("");
		$("#txtnombrefa").val("");
		$("#txtcorreofa").val("");
		cambiatipocedula();
	}
	$("#btvender").removeAttr("disabled");
	$("#btvender").val("Vender");


}
	
function selectviaje(fecha,viaje){
	$("#datepicker").val(fecha);
	refrescaviaje(despuesselectviaje);
	$('#calenhora').val(viaje);
}

function despuesselectviaje(){
	cambiahora();
}
function cambiaruta(){
	var rut="enlace.php?opcion=datosdefault&ruta=" + $("#cbruta").val()+"&estacion="+$("#cbestacion").val();
	enlace(rut,fin_cambiaruta)

}
function fin_cambiaruta(dat){
	var cad = dat.split(',');
	$("#datepicker").val(cad[0]);
	$("#calenhora").val(cad[1]);
	$("#cbparadasal").val(cad[2]);
	$("#cbparadades").val(cad[3]);
	$("#cbproducto").val(cad[4]);
	cambiaProducto(cad[4]);
	traecalendario();
	refrescaviaje(despuesselectviaje);
}
function actualiza_consecutivo(){
	var rut="enlace.php?opcion=siguientefacturae&impresora=" + $("#cbimpresora").val();
	enlace(rut,fin_actualiza_consecutivo)
	
}
function fin_actualiza_consecutivo(dato){
	$("#divnumero").html(dato);
	setTimeout('actualiza_consecutivo()',10000);
}
function revisacedulare(){
			var xlaced = $("#txtcedremi").val();
		
		if(xlaced.substr(0,1)=="0")
		 $("#txtcedremi").val(xlaced.substr(1,15));
		
		xlaced = $("#txtcedremi").val();

		if ($("#tcedremi").val()=="01"){
                	if(xlaced.indexOf('-')<0)
			  $("#txtcedremi").val(xlaced.substring(0,1)+"-"+xlaced.substr(1,4)+"-"+xlaced.substr(5,4))

			if (! $("#txtcedremi").val().match(/^[0-9]{1}\-[0-9]{4}\-[0-9]{4}/)) {
				alert("Cedula no válida, formato 9-9999-9999");
			}
		}
		if ($("#tcedremi").val()=="02"){
	                if(xlaced.indexOf('-')<0)
			  $("#txtcedremi").val(xlaced.substr(0,1)+"-"+xlaced.substr(1,3)+"-"+xlaced.substr(4,6))

			if (! $("#txtcedremi").val().match(/^[0-9]\-[0-9]{3}\-[0-9]{6}/)) {
				alert("Cédula no válida, formato 9-999-999999");
			}
		}
		
		var res = $("#txtcedremi").val().replace(/\-/g,"");
		
		if(xlaced!="1111111111"){
			var rut="enlace.php?opcion=datosenco&cedula=" + res;
			enlace(rut,fin_revisacedulare);
		}

	}
	
	function fin_revisacedulare(dato){
		var dat= dato.split(',');
		if(dat.length>1){
			$("#txtnomremi").val(dat[0]);
			$("#txttelremi").val(dat[1]);
			$("#txtemailremi").val(dat[2]);
			$("#provincia").val(dat[4]);
			fillCantones();
			$("#canton").val(dat[5]);
			fillDistritos();
			$("#distrito").val(dat[6]);
			$("#txtotrassenas").val(dat[8]);
			if($("#tcedremi").val()=='00')
      	$("#txtporiva").val(13);
    	else
      	$("#txtporiva").val(dat[9]);
		}
		else
		{
			$("#txtnomremi").val("");
			$("#txttelremi").val("");
			$("#txtemailremi").val("");
  	  $("#txtporiva").val("13");
		}
	}
	
	function revisacedulades(){
		 var xlaced = $("#txtceddesti").val();
		
		if(xlaced.substr(0,1)=="0")
		 $("#txtceddesti").val(xlaced.substr(1,15));
		
		xlaced = $("#txtceddesti").val();

		if ($("#tceddesti").val()=="01"){
                	if(xlaced.indexOf('-')<0)
			  $("#txtceddesti").val(xlaced.substring(0,1)+"-"+xlaced.substr(1,4)+"-"+xlaced.substr(5,4))

			if (! $("#txtceddesti").val().match(/^[0-9]{1}\-[0-9]{4}\-[0-9]{4}/)) {
				alert("Cedula no válida, formato 9-9999-9999");
			}
		}
		if ($("#tceddesti").val()=="02"){
	                if(xlaced.indexOf('-')<0)
			  $("#txtceddesti").val(xlaced.substr(0,1)+"-"+xlaced.substr(1,3)+"-"+xlaced.substr(4,6))

			if (! $("#txtceddesti").val().match(/^[0-9]\-[0-9]{3}\-[0-9]{6}/)) {
				alert("Cédula no válida, formato 9-999-999999");
			}
		}
		
		var res = $("#txtceddesti").val().replace(/\-/g,"");
		
		
		if(xlaced!="1111111111"){
		  var rut="enlace.php?opcion=datosenco&cedula=" + res;
		  enlace(rut,fin_revisacedulades);
	    }
	}
	
	function fin_revisacedulades(dato){
	  var dat= dato.split(',');
	  if(dat.length>0){
		$("#txtnomdesti").val(dat[0]);
		$("#txtteldesti").val(dat[1]);
	  }
	}


function ltrim(s) {
	return s.replace(/^\s+/, "");
}
function rtrim(s) {
	return s.replace(/\s+$/, "");
}
function trim(s) {
	return rtrim(ltrim(s));
}
function consemanual(){
	if($("#ckmanual").is(":checked"))
	{
		$("#divnumero").css("display","none")
		$("#divmanual").css("display","block")
	}
	else
	{
		$("#divnumero").css("display","block")
		$("#divmanual").css("display","none")
	}

}
function actuhora(){
	refrescahora();
	setTimeout('actuhora()',60000);
}

function imprimetiq(numeros){
	enlace("impenco.php?id="+numeros,imprimeEncofin);
	var lpro = $("#cbproducto").val();
	var lpos = $.inArray(lpro, ltrans)
	if(lpos==-1)
		enlace("impsticker.php?id="+numeros,imprimestickerfin);
}


function ponedeclarado(){
	var monto=($("#txtdeclarado").val()).replace(",", "") * 0.15;
	var nval = $("#cbproducto").val();
	var pos = $.inArray(nval, ltrans)
	if(pos==-1){
		if(monto<1000)
			$("#txtmonto").val(1000);
		else	
			$("#txtmonto").val(monto);
	}
	else
	{
		monto=($("#txtdeclarado").val()).replace(",", "") * 0.07;
		$("#txtmonto").val(Math.round(monto));	
	}
       caltotal();

}
function despuesimprimir(){
	disablePopup();
}

function decidenave(campo,combo){
    if ($(combo).val()=="01"){
		carganave(campo,"CedulaSIC","" );
	}
    if ($(combo).val()=="02"){
		carganave(campo,"CedulaJurSIC","" );
	}
    if ($(combo).val()=="03"){
		carganave(campo,"cliente","" );
	}
	if ($(combo).val()=="00"){
		carganave(campo,"cliente","" );
	}
}

function validacedula(campo,tipo){
	var valido = true;
	if ($("#"+tipo).val()=="1"){
		valido = $("#"+campo).attr("value").match(/^[0-9]{2}\-[0-9]{4}\-[0-9]{4}/);
	}
	if ($("#"+tipo).val()=="2"){
		valido = $("#"+campo).attr("value").match(/^[0-9]\-[0-9]{3}\-[0-9]{6}/);
	}
	return valido;
}

function revisaEmpresa(){
	if($("#cbformapago").val()==2){
		var xlaced = $("#txtcedremi").val();
		var res = $("#txtcedremi").val();
		if ($("#tcedremi").val()=="01"){
			res="0"+res;
			xlaced =  res.substring(0,2) + res.substring(3,7) + res.substring(8,12);
		}
		if ($("#tcedremi").val()=="2"){
			xlaced = res.substring(0,1) + res.substring(2,5) + res.substring(6,12);
		}
		var rut="enlace.php?opcion=escredito&id=" + xlaced;
		enlace(rut,fin_revisaEmpresa);

	}
	else 
		generaventa();
}

function fin_revisaEmpresa(datos){
	if(datos=='2')
		alert('La cédula que digitó no pertenece a ninguna empresa registrada');
	else
		generaventa();
}


function imprimestickerfin(data,intentos){
	datosimp3=data;
	if ("WebSocket" in window) {
		logonsticker();
		intentaimprimirsticker(1);
	} else {
		alert("El navegador no soporta WebSocket.");
	}
}
function logonsticker(){
	lWSC3 = new WebSocket("ws://localhost:8889");
	lWSC3.onopen = function() {
	}
	lWSC3.onmessage = function(e) {
	}
	lWSC3.onclose = function() {
	}
}
function intentaimprimirsticker(intentos){
	if(lWSC3.readyState==1){
		var xl;
		xl=datosimp3;
		xl2=xl.split('_i_');
		for(jj=0;jj<1;jj++){
			for(kk=0;kk<xl2.length;kk++){
				lWSC3.send(xl2[kk]);
				sleep(500);
			}
		}
		lWSC3.close();
		lWSC3=null;
	}
	else
	{
		if(intentos<10){
			intentos++;
			setTimeout('intentaimprimirsticker('+intentos+')',300);
		}
		else
			alert("Ocurrió un error al intentar imprimir el sticker");
	}
}

function cambiaProducto(nval){
	var pos = $.inArray(nval, ltrans)
	if(pos==-1){
		$("#trpeso").show();
		$("#trcantidad").show();
		$("#tddeclarado").html("Monto Declarado");
	}
	else
	{
		$("#trpeso").hide();
		$("#trcantidad").hide();
		$("#txtcantbul").val("1");
		$("#tddeclarado").html("Monto de la transferencia");

	}
}

function cambiaProdenco(nval){
	var lprecio = $('#cbprodenco option[value="'+nval+'"]').data('precio');
	$("#txtmonto").val(lprecio);
	if(nval!='0')
		$("#txtnotas").val($('#cbprodenco option[value="'+nval+'"]').text());
         caltotal();
}

function calcula(){
	var prods = $(".prod");
	var cants = $(".canprod");
	var precios = $(".precioprod");
	var det="";
	var notes="";
	var tcan=0;
	var tmonto=0;

	for(var x=0;x<prods.length;x++){
		if($(prods[x]).val()!=''){
			if (parseFloat($(cants[x]).val()) < 0) {
                $(cants[x]).val(0);
			}
			if (parseFloat($(precios[x]).val()) < 0) {
                $(precios[x]).val(0);
			}
			det = det+$(prods[x]).val()+"|"+$(cants[x]).val()+"|"+$(precios[x]).val()+"\n";
			notes = notes+$(cants[x]).val()+" "+$(prods[x]).val()+" "+$(precios[x]).val()+"\n";
			tcan = tcan + parseInt($(cants[x]).val());
			tmonto = tmonto+ Math.round( parseFloat($(precios[x]).val())*parseFloat($(cants[x]).val()),2);
	    }
	}
	$("#txtcantbul").val(tcan);
	$("#txtmonto").val(tmonto);
	$("#txtnotas").val(notes);
	$("#txtdetprod").val(det);
        caltotal();

}


function imprimeEncofin(data,intentos){
	datosimp2=data;
	if ("WebSocket" in window) {
		logonEnco();
		intentaimprimirEnco(1);
	} else {
		alert("El navegador no soporta WebSocket.");
	}
}
function logonEnco(){
	lWSC2 = new WebSocket("ws://localhost:8887");
	lWSC2.onopen = function() {
	}
	lWSC2.onmessage = function(e) {
	}
	lWSC2.onclose = function() {
	}
}
function intentaimprimirEnco(intentos){
	if(lWSC2.readyState==1){
		var xl;
		xl=datosimp2;
		xl2=xl.split('_i_');
		//for(jj=0;jj<gcantbul;jj++)
		for(jj=0;jj<1;jj++){
			for(kk=0;kk<xl2.length;kk++){
				lWSC2.send(xl2[kk]);
				sleep(500);
			}
		}
		lWSC2.close();
		lWSC2=null;
	}
	else
	{
		if(intentos<10){
			intentos++;
			setTimeout('intentaimprimirEnco('+intentos+')',300);
		}
		else
			alert("Ocurrió un error al intentar imprimir la Encomienda");
	}
}

function loadDirecciones(){
      $.get("direcciones.json").done(function(datx){
        dir = datx;
        fillProvincias();
      });
  }
	function fillProvincias(){
    $("#provincia").html("");
    var selected="selected";
    for(var x=0;x<dir.provincias.length;x++){
      $("#provincia").append("<option value='"+dir.provincias[x].id+"' "+selected+" >"+dir.provincias[x].nombre+"</option>");
      selected="";
    }
    fillCantones();
  }
  function fillCantones(){
    var lpro = $("#provincia").val();
    var pro ;
    for(var x = 0 ; x< dir.provincias.length;x++)
      if(dir.provincias[x].id==lpro)
        pro=dir.provincias[x];
    $("#canton").html("");
     var selected="selected";
    for(var x=0; x<pro.cantones.length;x++){
       $("#canton").append("<option value='"+pro.cantones[x].id+"' "+selected+" >"+pro.cantones[x].nombre+"</option>");
      selected="";
    }
    fillDistritos();

  }

  function cambiatipocedula(){
  	var tip = $("#tcedremi").val();
	if(tip=='00')
	  $("#datosparafe").hide();
	else{
	  $("#datosparafe").show();
	  loadDirecciones();
	}
  }

  function validaDatosFe(){
  	var res="";
  	if ($("#tcedremi").val()=="01"){
		if (!$("#txtcedremi").attr("value").match(/^[0-9]{1}\-[0-9]{4}\-[0-9]{4}/)) {
			res= "Cedula no válida, formato 9-9999-9999 \n";
		}
	}
	if ($("#tcedremi").val()=="02"){
		if (! $("#txtcedremi").attr("value").match(/^[0-9]\-[0-9]{3}\-[0-9]{6}/)) {
			res += "Cédula no válida, formato 9-999-999999 \n";
		}
	}
	if (! $("#txtemailremi").attr("value").match(/^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/)) {
		res += "Correo no valido \n";
	}
	return res;
  }

  function fillDistritos(){
	var lprov =$("#provincia").val();     
    var lcan = $("#canton").val();
    var can ;
    for(var x = 0 ; x< dir.provincias.length;x++){
    	if(dir.provincias[x].id==lprov){
      		for( var y=0 ; y <dir.provincias[x].cantones.length;y++)
        		if(dir.provincias[x].cantones[y].id==lcan)
          			can=dir.provincias[x].cantones[y];
  		}
  	}
    $("#distrito").html("");
     var selected="selected";
    for(var x=0; x<can.distritos.length;x++){
       $("#distrito").append("<option value='"+can.distritos[x].id+"' "+selected+" >"+can.distritos[x].nombre+"</option>");
      selected="";
    }
  }
function caltotal(){
	var mon= parseFloat($("#txtmonto").val());
	var lporiva1 = parseFloat($("#txtporiva").val())/100;
	var lporiva2= 1+lporiva1;
	var imp= Math.round( mon/lporiva2*lporiva1*100)/100;
	$("#txtiva").val(imp);
	$("#txtsubtotal").val(Math.round((mon-imp)*100)/100);
	copiafaDO();
}
function copiafa(){
	setTimeout(copiafaDO,1000);
}
function copiafaDO(){
	var ptipo=$("input[name='ckafa']:checked").val();

  if(ptipo=='remitente'){
  	$("#tipocedfa").val($("#tcedremi").val());
  	$("#txtnombrefa").val($("#txtnomremi").val());
  	$("#txtcedulafa").val($("#txtcedremi").val());
  	$("#txtcorreofa").val($("#txtemailremi").val());
  }
  if(ptipo=='destinatario'){
  	$("#tipocedfa").val($("#tceddesti").val());
  	$("#txtnombrefa").val($("#txtnomdesti").val());
  	$("#txtcedulafa").val($("#txtceddesti").val());
  	//$("#txtcorreofa").val($("#txtemailremi").val());
  }
}

function revisacedulafa(){
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
			enlace(rut,fin_revisacedulafa);
		}

	}
	
	function fin_revisacedulafa(dato){
		var dat= dato.split(',');
		if(dat.length>1){
			$("#txtnombrefa").val(dat[0]);
			$("#txtcorreofa").val(dat[2]);
			if($("#tipocedfa").val()=='00')
		      	$("#txtporiva").val(13);
    		else
      			$("#txtporiva").val(dat[9]);
			}
		else
		{
			$("#txtnombrefa").val("");
			$("#txtcorreofa").val("");
  	  		$("#txtporiva").val("13");
		}
	}
