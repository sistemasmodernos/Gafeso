function anulartiq(){
  if($("txtanula").val()==""){
    alert("Debe digitar la raz&oacute;n de la anulaci&oacute;n");
    return false;
  }
   var res= confirm ("Esta seguro(a) que desea anular los tiquetes seleccionados?");
 if(res){
   $("#fanulatiq").attr("action","anulatiq.php?do=anular");
   $("#fanulatiq").submit();
 }

}
function cargar(){
 $("#fanulatiq").submit();
}

function traetiq(){
  var rut="enlace.php?opcion=devtiq&impresora="+$("#cbimpresora").val()+"&factura="+$("#txteltiq").val();
  traeajax("divtiq",rut,null,despues_traetiq);

}
function despues_traetiq(){
}
