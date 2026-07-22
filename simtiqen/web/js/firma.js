function guardarfirma(){
  var sigdiv = $("#signature");
  var datos  = $(sigdiv).jSignature("getData","svg");
  $("#txtdatos").val(datos);
  $("#formfirma").submit();
}
