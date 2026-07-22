function cambiaparada(){
  if($("txtidtiquete").val()==""){
    alert("Debe digitar el número de tiquete primero");
    return false;
  }
   var res= confirm ("Esta seguro(a) que desea cambiar estos datos?");
 if(res){
   $("#fcambiaparada").attr("action","cambiaparada.php?do=cambiar");
   $("#fcambiaparada").submit();
 }

}
function cargar(){
 $("#fcambiaparada").submit();
}
function cargacambiaparada(){
  if($("txtidtiquete").val()==""){
    alert("Debe digitar el número de tiquete primero");
    return false;
  }
  $("#fcambiaparada").attr("action","cambiaparada.php?do=cargar");
  $("#fcambiaparada").submit();


}
