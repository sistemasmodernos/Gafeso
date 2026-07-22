function anulartiq(){
  if($("txtanula").val()==""){
    alert("Debe digitar la raz&oacute;n de la anulaci&oacute;n");
    return false;
  }
   var res= confirm ("Esta seguro(a) que desea anular las encomiendas seleccionados?");
 if(res){
   $("#fanulaenco").attr("action","anulaenco.php?do=anular");
   $("#fanulaenco").submit();
 }

}
function cargar(){
 $("#fanulaenco").submit();
}
