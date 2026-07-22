function agregarotroi(){
   $("#formcierre").attr("action","cierreenco.php?do=agring");
   $("#formcierre").submit();
}
function agregarotroe(){
   $("#formcierre").attr("action","cierreenco.php?do=agregr");
   $("#formcierre").submit();
}
function limpiar(){
   $("#formcierre").attr("action","cierreenco.php?do=limpiar");
   $("#formcierre").submit();
}
function guardarcierre(){
var res= confirm ("Esta seguro(a) que desea realizar el cierre?");
 if(res){
   $("#formcierre").attr("action","cierreenco.php?do=cerrar");
   $("#formcierre").submit();
}
}
function imprimir(){
  window.print();
}
