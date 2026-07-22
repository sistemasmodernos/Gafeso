//carga los horarios de los viajes segun la fecha            
function refrescaviaje(funciondespues){
  var lafecha;
  if($("#datepicker").val()==null){
    var f = new Date();
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
    "&tipo=1",null,funciondespues);

}
function traecalendario(){
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
    "&tipo=1",null,cambiahora);

}

//cambia formato de fecha 01/12/2011 a 2011-12-01

function fechatoUTC(lcfecha){
  if(lcfecha){
    var cad = lcfecha.split('/');
    return cad[2]+"-"+cad[1]+"-"+cad[0];
  }
  else
    return "";
}

function fechatoNormal(lcfecha){
  if(lcfecha){
    var cad = lcfecha.split('-');
    return cad[2]+"/"+cad[1]+"/"+cad[0];
  }
  else
   return "";
}
//Carga toda la divisi'on de horarios
function llegadacustom(nombrediv,funciondespues){
 switch (nombrediv){
   case 'divcrtviaje' : 
   $('#datepicker').datepicker({
    inline: true
  });
   $("#datepicker").datepicker($.datepicker.regional['es']);

   break;
   case 'divhora': 
   cambiahora();
   break;
 }
 if(funciondespues!=null)
  funciondespues();
}

/* se ejecuta cuando cambian el d'ia            */
function cambiafecha(){
  refrescahora();

}
function cambiahora(valor){
  refrescaAsiento();
  limpiaMarcas();

/* Esto es un parche para que cuando sea ruta cartago turriabla y es colectivo ponga parada cartago */
  if($("#cbruta").val()=="5")
   parchetranstusa();

  if($("#cbhora :selected").text().indexOf("EXPRESO")!=-1 ){
    $("#cbparadades").prop("disabled",true);
    $("#cbparadades").val($("#cbparadades").data("pardefa"));   //si es expreso pone la parada default 
  }
  else{
    $("#cbparadades").prop("disabled",false);
  }
  traeajax("formabus","mapabus.php?viaje="+$('#cbhora').val()+"&estacion="+$("#cbestacion").val() ,null,marcaasientos);
}

// actualiza el asiento siguiente 
function refrescaAsiento(){
  enlace("enlace.php?opcion=siguienteasiento&viaje="+$('#cbhora').val()+"&cantidad=1",finRefrescaAsiento);
}
function finRefrescaAsiento(dato){
  $("#txtasiento").val(dato);
}
function cambiaProducto(valor){
 if(valor==2 || valor==5)
  $("#divadultomayor").css("display","block");
else
  $("#divadultomayor").css("display","none");
if(valor==99)
 $("#divcortesia").css("display","block");
else
  $("#divcortesia").css("display","none");

actualizaPrecio();
}
function actualizaPrecio(){

  enlace("enlace.php?opcion=precioboleto"+
    "&ruta="+$("#cbruta").val()+
    "&parada1="+$("#cbparadasal").val()+
    "&parada2="+$("#cbparadades").val()+
    "&producto="+$("#cbproducto").val(),finActualizaPrecio);
}
function finActualizaPrecio(dat){
  $("#txtmonto").val(dat);
  /*aqui quito el desabiitado porque significa que se terminó de cargar los precios al cargar la pantalla*/
  $("#btvender").removeAttr("disabled");
}

function creaparametros(){
  var xx=$("#txtmonto").val();
  var ljunto="0";
  if($("#ckjuntos").is(':checked'))
   ljunto="1";
 var lafa ="";

 var param = {
  "fecha": fechatoUTC($("#datepicker").val()),
  "monto": $("#txtmonto").val(),
  "idEstacion": $("#cbestacion").val(),
  "idProducto": $("#cbproducto").val(),
  "idParada1": $("#cbparadasal").val(),
  "idParada2": $("#cbparadades").val(),
  "idCliente": $("#txtidcliente").val(),
  "idImpresora": $("#cbimpresora").val(),
  "cantasiento": $("#txtcantasiento").val(),
  "cantasientop": $("#txtcantasientop").val(),
  "asiento": $("#txtasiento").val(),
  "cedula": $("#txtcedula").val(),
  "nombre": $("#txtnombre").val()+$("#txtnombre2").val(),
  "comentario":$("#txtcomentario").val(),
  "fechanac":fechatoUTC($("#txtfechanac").val()),
  "idViaje": $("#cbhora").val(),
  "juntos": ljunto,
  "factura" : lafa,
  "marcados": $("#txtastomarcados").val()
};

return param;
}
function generaventa(){
  $("#btvender").attr("disabled",true);
  $("#btvender").val("Procesando..");
  var par = creaparametros();
  $.post("vendetiquete.php",par,function(data){
   var res = data;
   despuesventa(res);
 });
}
function despuesventa(data){

 $("#txttotalvta2").val($("#txttotalvta",data).val());

  if( (data.indexOf("error")!=-1) || ($("#cbproducto").val()=="99"))
    createPopup("Datos de la venta",data);
  else
  {
    setTimeout(function() {
      imprimetiq($("#txttiquetesvendidos",data).val());
    }, 1);
  }
  $("#cbruta").val($("#txtrutadefa").val());
  cambiaruta();
  traecalendario();
  $("#txtcantasiento").val("1");
  $("#txtcantasiento").val( "1");
  $("#txtcantasiento").focus();
  $("#txtcantasientop").val("0");
  $("#txtcantasientop").val("0");
  $("#txtnombre").val("");
  $("#txtnombre2").val("");
  $("#txtcomentario").val("");
  $("#txtcedula").val("");
  $("#txtfechanac").val("");
  refrescaAsiento();
  $("#btvender").removeAttr("disabled");
  $("#btvender").val("Vender");
  $("#ckjuntos").removeAttr("checked");
  cambiaProducto("1");
}

function selectviaje(fecha,viaje){
  $("#datepicker").val(fecha);
  refrescaviaje(despuesselectviaje);
  $('#calenhora').val(viaje);
}
function despuesselectviaje(){
  $('#cbhora').val($('#calenhora').val());
  cambiahora();
}
function cambiaruta(){
  var rut="enlace.php?opcion=datosdefault&ruta=" + $("#cbruta").val()+"&estacion="+$("#cbestacion").val();
  var elcolor = $("#cbruta option:selected").css("background-color");
  $("#cbruta").css("background-color",elcolor);
  enlace(rut,fin_cambiaruta)

}
function fin_cambiaruta(dat){
 var cad = dat.split(',');
 $("#datepicker").val(cad[0]);
 $("#calenhora").val(cad[1]);
 $("#cbparadasal").html(cad[6]);
 $("#cbparadasal").val(cad[2]);
 $("#cbparadades").html(cad[7]);
 $("#cbparadades").val(cad[3]);
 $("#cbparadades").data("pardefa",cad[3]);
 $("#cbproducto").val(cad[4]);
 $("#cbprecio").val(cad[5]);

  traecalendario();
 refrescaviaje(despuesselectviaje);
 actualizaPrecio();
}
function actualiza_consecutivo(){
  var rut="enlace.php?opcion=siguientefactura&impresora=" + $("#cbimpresora").val();
  enlace(rut,fin_actualiza_consecutivo)

}
function fin_actualiza_consecutivo(dato){
 $("#divnumero").html(dato);
 setTimeout('actualiza_consecutivo()',5000);
}
function marcaasientos(){
  viaje =$("#cbhora").val();	
  var par = creaparametros();
  $.post("listaasientos.php?viaje="+viaje,par,function(data2){
   var res = data2;
   despues_marcaasientos(res);
 });
}
function despues_marcaasientos(datos){
  var lista = datos.split(',');
  for(i=0;i<lista.length;i++){
    var lista2= lista[i].split('-');
    $("#asiento"+trim(lista2[0])).removeClass('libre');
    $("#asiento"+trim(lista2[0])).removeClass('libree');
    $("#asiento"+trim(lista2[0])).removeClass('adultomayor');
    $("#asiento"+trim(lista2[0])).removeClass('cortesia');
    if(lista2[2]=='APARTA')
      $("#asiento"+trim(lista2[0])).addClass('ocupadoA');
    else
      if(lista2[1]==4)
        $("#asiento"+trim(lista2[0])).addClass('ocupadoW');
      else
        $("#asiento"+trim(lista2[0])).addClass('ocupado');

    if(lista2[3]=="2")
      $("#asiento"+trim(lista2[0])).addClass('adultomayor');

 if(lista2[3]=="99")
      $("#asiento"+trim(lista2[0])).addClass('cortesia');

    }
    

    


    for(i=0;i<100;i++){
      lnodo = "#asiento"+i;
      if($(lnodo)!=null){
       $(lnodo).unbind('click');
       $(lnodo).click( function(ev) {
         agregaclickasi(ev);
       }
       );
     }
   }

   remarca();
 }

 function agregaclickasi(ev){
  if($(ev.currentTarget).hasClass("libre") || $(ev.currentTarget).hasClass("libree"))
    agregarAsientoMatriz(ev.currentTarget.id.substring(7,9));


  $("#txtasiento").val(ev.currentTarget.id.substring(7,9));
}

function agregarAsientoMatriz(pnumero){
  var asientomarcados = MarcasToMemoria();
  for(i=0;i<asientomarcados.length;i++)
  {
    if(asientomarcados[i]==pnumero)
    {
      asientomarcados[i]="";
      MarcasToTexto(asientomarcados);
      poneCantidad();
      remarca();
      return;
    }
    if(asientomarcados[i]=="")
    {
      asientomarcados[i]=pnumero;
      MarcasToTexto(asientomarcados);
      poneCantidad();
      remarca();
      return;
    }
  }



}
function poneCantidad(){
  numero = MarcasToMemoria().length-1;
  if(numero<=1)
    $("#txtcantasiento").val("1");
  else
    $("#txtcantasiento").val(numero);
}

function MarcasToMemoria(){
  var cadena = $("#txtastomarcados").val();
  var matrizmarca = cadena.split(",");
  return matrizmarca;

}
function MarcasToTexto(lamatriz){
  var cadtemp="";
  for(x=0;x<lamatriz.length;x++)
   if(lamatriz[x]!="")
     cadtemp+=lamatriz[x]+",";

   $("#txtastomarcados").val(cadtemp);
 }
 function limpiaMarcas(){
  $("#txtastomarcados").val("");
  remarca();
}

function remarca(){
  $(".marcado img").remove();
  $(".marcado").removeClass('marcado');
  var lista2 = MarcasToMemoria();
  for(x=0;x<lista2.length;x++){
    $("#asiento"+trim(lista2[x])).addClass('marcado');
    $("#asiento"+trim(lista2[x])).append("<img src='img/check.ico' height='10' width='10'>");
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

function imprimetiq(numeros){
  enlace("imptiquete.php?id="+numeros,imprimetiqfin);
}




function revisacedulades(){
 var rut="enlace.php?opcion=datoscliente&cedula=" + $("#txtcedula").val();
 enlace(rut,fin_revisacedulades);
 var rut="enlace.php?opcion=revisaaldultomayor&cedula=" + $("#txtcedula").val()
 +"&fecha="+fechatoUTC(fechahoy())+"&ruta="+$("#cbruta").val();
 traeajax("mensajeadulto",rut,null,null);
}


function fin_revisacedulades(dato){
 var dat= dato.split(',');
 if(dat.length>0){
  $("#txtnombre").val(dat[0]);
  $("#txtfechanac").val(fechatoNormal(dat[3].substring(0,10)));
}

}
function cambiamoneda(){
  if($("#cmbmoneda").val()=="CRC")
    $("#divtc").hide();
  else{
    $("#divtc").show();
    var url="http://indicadoreseconomicos.bccr.fi.cr/"+
    "indicadoreseconomicos/WebServices/wsIndicadoresEconomicos.asmx/"+
    "ObtenerIndicadoresEconomicosXML?"+
    "tcIndicador=318&"+
    "tcFechaInicio=28/08/2017&"+
    "tcFechaFinal=28/08/2017&"+
    "tcNombre=Transtusa&"+
    "tnSubNiveles=N";
    $.get( url, function( data ) {
      var ltc= $("NUM_VALOR",data).txt();
      $("#txttc").val(ltc);  
    });
  }
}

function calculavuelto(){
  var lmonto = $("#txtmontoori").val()
  var lpaga =0;
  if($("#cmbmoneda").val()=="CRC")
    lpaga= $("#txtvuelto").val();
  else
    lpaga = $("#txtvuelto").val()*$("#txttc").val()
  var lvuelto = lpaga-lmonto
  if(lvuelto>0)
  {
    $("#spanvuelto").html(lvuelto) ; 
  }  
}function cambiamoneda(){
  if($("#cmbmoneda").val()=="CRC")
    $("#divtc").hide();
  else{
    $("#divtc").show();
    var url="http://indicadoreseconomicos.bccr.fi.cr/"+
    "indicadoreseconomicos/WebServices/wsIndicadoresEconomicos.asmx/"+
    "ObtenerIndicadoresEconomicosXML?"+
    "tcIndicador=318&"+
    "tcFechaInicio=28/08/2017&"+
    "tcFechaFinal=28/08/2017&"+
    "tcNombre=Transtusa&"+
    "tnSubNiveles=N";
    $.get( url, function( data ) {
      var ltc= $("NUM_VALOR",data).txt();
      $("#txttc").val(ltc);  
    });
  }
}

function calculavuelto(){
  var lmonto = $("#txtmontoori").val()
  var lpaga =0;
  if($("#cmbmoneda").val()=="CRC")
    lpaga= $("#txtvuelto").val();
  else
    lpaga = $("#txtvuelto").val()*$("#txttc").val()
  var lvuelto = lpaga-lmonto
  if(lvuelto>0)
  {
    $("#spanvuelto").html(lvuelto) ; 
  }  
}function cambiamoneda(){
  if($("#cmbmoneda").val()=="CRC")
    $("#divtc").hide();
  else{
    $("#divtc").show();
    var url="http://indicadoreseconomicos.bccr.fi.cr/"+
    "indicadoreseconomicos/WebServices/wsIndicadoresEconomicos.asmx/"+
    "ObtenerIndicadoresEconomicosXML?"+
    "tcIndicador=318&"+
    "tcFechaInicio=28/08/2017&"+
    "tcFechaFinal=28/08/2017&"+
    "tcNombre=Transtusa&"+
    "tnSubNiveles=N";
    $.get( url, function( data ) {
      var ltc= $("NUM_VALOR",data).txt();
      $("#txttc").val(ltc);  
    });
  }
}

function calculavuelto(){
  var lmonto = $("#txtmontoori").val()
  var lpaga =0;
  if($("#cmbmoneda").val()=="CRC")
    lpaga= $("#txtvuelto").val();
  else
    lpaga = $("#txtvuelto").val()*$("#txttc").val()
  var lvuelto = lpaga-lmonto
  if(lvuelto>0)
  {
    $("#spanvuelto").html(lvuelto) ; 
  }  
}function cambiamoneda(){
  if($("#cmbmoneda").val()=="CRC")
    $("#divtc").hide();
  else{
    $("#divtc").show();
    var url="http://indicadoreseconomicos.bccr.fi.cr/"+
    "indicadoreseconomicos/WebServices/wsIndicadoresEconomicos.asmx/"+
    "ObtenerIndicadoresEconomicosXML?"+
    "tcIndicador=318&"+
    "tcFechaInicio=28/08/2017&"+
    "tcFechaFinal=28/08/2017&"+
    "tcNombre=Transtusa&"+
    "tnSubNiveles=N";
    $.get( url, function( data ) {
      var ltc= $("NUM_VALOR",data).txt();
      $("#txttc").val(ltc);  
    });
  }
}

function calculavuelto(){
  var lmonto = $("#txtmontoori").val()
  var lpaga =0;
  if($("#cmbmoneda").val()=="CRC")
    lpaga= $("#txtvuelto").val();
  else
    lpaga = $("#txtvuelto").val()*$("#txttc").val()
  var lvuelto = lpaga-lmonto
  if(lvuelto>0)
  {
    $("#spanvuelto").html(lvuelto) ; 
  }  
}function cambiamoneda(){
  if($("#cmbmoneda").val()=="CRC")
    $("#divtc").hide();
  else{
    $("#divtc").show();
    var url="http://indicadoreseconomicos.bccr.fi.cr/"+
    "indicadoreseconomicos/WebServices/wsIndicadoresEconomicos.asmx/"+
    "ObtenerIndicadoresEconomicosXML?"+
    "tcIndicador=318&"+
    "tcFechaInicio=28/08/2017&"+
    "tcFechaFinal=28/08/2017&"+
    "tcNombre=Transtusa&"+
    "tnSubNiveles=N";
    $.get( url, function( data ) {
      var ltc= $("NUM_VALOR",data).txt();
      $("#txttc").val(ltc);  
    });
  }
}

function calculavuelto(){
  var lmonto = $("#txtmontoori").val()
  var lpaga =0;
  if($("#cmbmoneda").val()=="CRC")
    lpaga= $("#txtvuelto").val();
  else
    lpaga = $("#txtvuelto").val()*$("#txttc").val()
  var lvuelto = lpaga-lmonto
  if(lvuelto>0)
  {
    $("#spanvuelto").html(lvuelto) ; 
  }  
}function cambiamoneda(){
  if($("#cmbmoneda").val()=="CRC")
    $("#divtc").hide();
  else{
    $("#divtc").show();
    var url="http://indicadoreseconomicos.bccr.fi.cr/"+
    "indicadoreseconomicos/WebServices/wsIndicadoresEconomicos.asmx/"+
    "ObtenerIndicadoresEconomicosXML?"+
    "tcIndicador=318&"+
    "tcFechaInicio=28/08/2017&"+
    "tcFechaFinal=28/08/2017&"+
    "tcNombre=Transtusa&"+
    "tnSubNiveles=N";
    $.get( url, function( data ) {
      var ltc= $("NUM_VALOR",data).txt();
      $("#txttc").val(ltc);  
    });
  }
}

function calculavuelto(){
  var lmonto = $("#txtmontoori").val()
  var lpaga =0;
  if($("#cmbmoneda").val()=="CRC")
    lpaga= $("#txtvuelto").val();
  else
    lpaga = $("#txtvuelto").val()*$("#txttc").val()
  var lvuelto = lpaga-lmonto
  if(lvuelto>0)
  {
    $("#spanvuelto").html(lvuelto) ; 
  }  
}function cambiamoneda(){
  if($("#cmbmoneda").val()=="CRC")
    $("#divtc").hide();
  else{
    $("#divtc").show();
    var url="http://indicadoreseconomicos.bccr.fi.cr/"+
    "indicadoreseconomicos/WebServices/wsIndicadoresEconomicos.asmx/"+
    "ObtenerIndicadoresEconomicosXML?"+
    "tcIndicador=318&"+
    "tcFechaInicio=28/08/2017&"+
    "tcFechaFinal=28/08/2017&"+
    "tcNombre=Transtusa&"+
    "tnSubNiveles=N";
    $.get( url, function( data ) {
      var ltc= $("NUM_VALOR",data).txt();
      $("#txttc").val(ltc);  
    });
  }
}

function calculavuelto(){
  var lmonto = $("#txtmontoori").val()
  var lpaga =0;
  if($("#cmbmoneda").val()=="CRC")
    lpaga= $("#txtvuelto").val();
  else
    lpaga = $("#txtvuelto").val()*$("#txttc").val()
  var lvuelto = lpaga-lmonto
  if(lvuelto>0)
  {
    $("#spanvuelto").html(lvuelto) ; 
  }  
}function cambiamoneda(){
  if($("#cmbmoneda").val()=="CRC")
    $("#divtc").hide();
  else{
    $("#divtc").show();
    var url="http://indicadoreseconomicos.bccr.fi.cr/"+
    "indicadoreseconomicos/WebServices/wsIndicadoresEconomicos.asmx/"+
    "ObtenerIndicadoresEconomicosXML?"+
    "tcIndicador=318&"+
    "tcFechaInicio=28/08/2017&"+
    "tcFechaFinal=28/08/2017&"+
    "tcNombre=Transtusa&"+
    "tnSubNiveles=N";
    $.get( url, function( data ) {
      var ltc= $("NUM_VALOR",data).txt();
      $("#txttc").val(ltc);  
    });
  }
}

function calculavuelto(){
  var lmonto = $("#txtmontoori").val()
  var lpaga =0;
  if($("#cmbmoneda").val()=="CRC")
    lpaga= $("#txtvuelto").val();
  else
    lpaga = $("#txtvuelto").val()*$("#txttc").val()
  var lvuelto = lpaga-lmonto
  if(lvuelto>0)
  {
    $("#spanvuelto").html(lvuelto) ; 
  }  
}function cambiamoneda(){
  if($("#cmbmoneda").val()=="CRC")
    $("#divtc").hide();
  else{
    $("#divtc").show();
    var url="http://indicadoreseconomicos.bccr.fi.cr/"+
    "indicadoreseconomicos/WebServices/wsIndicadoresEconomicos.asmx/"+
    "ObtenerIndicadoresEconomicosXML?"+
    "tcIndicador=318&"+
    "tcFechaInicio=28/08/2017&"+
    "tcFechaFinal=28/08/2017&"+
    "tcNombre=Transtusa&"+
    "tnSubNiveles=N";
    $.get( url, function( data ) {
      var ltc= $("NUM_VALOR",data).txt();
      $("#txttc").val(ltc);  
    });
  }
}

function calculavuelto(){
  var lmonto = $("#txtmontoori").val()
  var lpaga =0;
  if($("#cmbmoneda").val()=="CRC")
    lpaga= $("#txtvuelto").val();
  else
    lpaga = $("#txtvuelto").val()*$("#txttc").val()
  var lvuelto = lpaga-lmonto
  if(lvuelto>0)
  {
    $("#spanvuelto").html(lvuelto) ; 
  }  
}

function despuesimprimir(){
  var tmonto = $("#txttotalvta2").val();
  disablePopup();
  createPopup("Datos de la venta","<div id='divvuelto'></div>");
  var rut = 'vuelto.php?monto='+tmonto;
  traeajax("divvuelto",rut,null,ponecssvuelto);
}

function ponecssvuelto(){
  $("#divvuelto2").css("{background-color:yellow,display:block,margin:5px,padding:5px,text-align:center}");
  $("#txtvuelto").focus();
}

function parchetranstusa(){

/* Esto es un parche para que cuando sea ruta cartago turriabla y es colectivo ponga parada cartago 
  if($("#cbruta").val()=="5"){ 
    if($("#cbhora option:selected").text().indexOf("COLECTIVO")!=-1)
     $("#cbparadasal").val("3");
    else
      $("#cbparadasal").val("1");
   }
   actualizaPrecio();
*/


}


