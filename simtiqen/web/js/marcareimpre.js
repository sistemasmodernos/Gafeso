function marcar(){
  if($("txtanula").val()==""){
    alert("Debe digitar la raz&oacute;n de la reimpresi&oacute;n");
    return false;
  }
   var res= confirm ("Esta seguro(a) que desea marcar para reimprimir los tiquetes seleccionados?");
 if(res){
   $("#fmarcareimpre").attr("action","marcareimpre.php?do=marcar");
   $("#fmarcareimpre").submit();
 }

}
function cargar(){
 $("#fmarcareimpre").submit();
}


function traetiq(){
  var rut="enlace.php?opcion=devtiq&impresora="+$("#cbimpresora").val()+"&factura="+$("#txteltiq").val();
  traeajax("divtiq",rut,null,despues_traetiq);

}
function despues_traetiq(){
}
