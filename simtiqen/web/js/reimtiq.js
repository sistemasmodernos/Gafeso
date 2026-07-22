function preliminar(){
  var numeros= $("#txtnumero").val();
  var impre = $("#cbimpresora").val();
  var tiptiq=  $("input[name='tiqtip']:checked").val();
  enlace("imptiquete.php?rp=2&impre="+impre+"&fact="+numeros+"&tiptiq="+tiptiq,finpreliminar);

}
function finpreliminar(data){
  $("#textotiq").val(data);
}

function imprimir(){
  var numeros= $("#txtnumero").val();
  var impre = $("#cbimpresora").val();
  enlace("imptiquete.php?rp=1&impre="+impre+"&fact="+numeros,imprimetiqfin);
}




