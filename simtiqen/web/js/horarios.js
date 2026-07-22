function guarda_horario(){
  var estacion= $("#cbestacion").val();
  var ruta =$("#cbruta").val();

}
function eliminar(){
 var res= confirm ("Esta seguro(a) que desea eliminar este horario?");
 if(res){
   $("#formhora").attr("action","horarios.php?do=del");
   $("#formhora").submit();
 }

}
function cambiaestacion(xesta){
  document.forms["formhora"].submit();
}
function guardar(){
  var tira = "";
  var vuelta = true;
  if (esVacio("#txtfecha")){
    vuelta = false ;
    tira += "debe digitar la fecha\n";
  }else{
    if (!esFecha($("#txtfecha").val())){
      vuelta = false ;
      tira += "la fecha final está mal digitada\n";
    }
  }
  if (vuelta){
    $("#formhora").attr("action","horarios.php?do=save");
    $("#formhora").submit();
  }else{
    alert(tira);
  }
}
   
function traechofer(){
	var elbus= $("#cbbus").val();
	enlace("enlace.php?opcion=traechofer&idbus="+elbus,fin_traechofer);
}
function fin_traechofer(datos){
	$("#cbchofer").val(datos);
}

function esVacio(campo){
  var retorno = false;
  var eldato = $(campo).val();
  if(eldato == ""){
    retorno = true;
    //tira = tira + "Debe digitar " + campo.placeholder + " ";
  };
  return retorno;
}
      
function esFecha(string) 
{ //string estará en formato dd/mm/yyyy (dí­as < 32 y meses < 13)
  var ExpReg = /^([0][1-9]|[12][0-9]|3[01])(\/|-)([0][1-9]|[1][0-2])\2(\d{4})$/ ;
  return (ExpReg.test(string));
}
