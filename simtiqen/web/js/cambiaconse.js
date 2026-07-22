function cambiaconsecutivo(){
  if($("txtidtiquete").val()==""){
    alert("Debe  el consecutivo primero");
    return false;
  }
   var res= confirm ("Esta seguro(a) que desea cambiar estos datos?");
 if(res){
   $("#fcambiaconse").attr("action","cambiaconse.php?do=save");
   $("#fcambiaconse").submit();
 }

}

function actuconse(){
	var rut="enlace.php?opcion=consecutivo&impresora=" + $("#cbimpresora").val();
	enlace(rut,fin_actuconse);

}

function fin_actuconse(data){
	$("#txtidtiquete").val(data);
}