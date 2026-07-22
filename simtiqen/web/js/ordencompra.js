function agregar(){
   $("#formoc").get(0).setAttribute('action', 'ordencompra.php?do=agregar');
   $("#formoc").submit();
}
function eliminar(elid){
   $("#formoc").get(0).setAttribute('action', 'ordencompra.php?do=borrar&linea='+elid);
   $("#formoc").submit();

}
function guardar(){
if (confirm('Todos los datos están correctos?')) {
   $("#formoc").get(0).setAttribute('action', 'ordencompra.php?do=guardar');
   $("#formoc").submit();

   }
}

function impuesto(){
  $("#txtcantidad").val(parseFloat($("#txtcantidad").val()));
  $("#txtprecio").val(parseFloat($("#txtprecio").val()));
  $("#txtdescuento").val(parseFloat($("#txtdescuento").val()));
  if($("#ckexento").is(':checked'))
    $("#txtimpuesto").val(0);
  else
    $("#txtimpuesto").val((parseFloat($("#txtcantidad").val())*parseFloat($("#txtprecio").val()).toFixed(2)-parseFloat($("#txtdescuento").val()))*13/100);


}

function enviarcorreo(){
  if($("#txtidcompra").val()=="0"){
    alert("Debe guardar la orden de compra para poder enviarla por correo");
     return;
   }
   parametros={};
   $.post("imporden.php?id="+$("#txtidcompra").val(),parametros,fin_enviarcorreo);
}
function fin_enviarcorreo(datores){
  $("#datoscorreo").val(datores);
   $("#formoc").get(0).setAttribute('action', 'ordencompra.php?do=enviar');
   $("#formoc").submit();
}

function cambiacia(){
  var texto = $( "#txtidremitente option:selected" ).text();
  $("#txtremitente").val(texto);
}

function caldescuento(){
  var bruto = (parseFloat($("#txtcantidad").val())*parseFloat($("#txtprecio").val())).toFixed(2);
  var descuento = (bruto*$("#txtpordesc").val()/100).toFixed(2);
  $("#txtdescuento").val(descuento);
  impuesto();
}
