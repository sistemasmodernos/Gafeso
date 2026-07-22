//carga los horarios de los viajes segun la fecha            
	    function traecalendario(){
                var lafecha;
                    var f = new Date();
                    lafecha= f.getDate() + "/" + (f.getMonth() +1) + "/" + f.getFullYear();

                //trae el calendario a partir de la nueva fecha
                traeajax("calendario","calenviajes.php?ruta="+
                  $("#cbruta").val()+
                "&estacion="+$("#cbestacion").val()+
                "&inicio="+fechatoUTC(lafecha)+
                "&dias=360"
               ,null);
		setTimeout('traecalendario()', 5000);
		}
//cambia formato de fecha 01/12/2011 a 2011-12-01

            function fechatoUTC(lcfecha){
                var cad = lcfecha.split('/');
                return cad[2]+"-"+cad[1]+"-"+cad[0];
            }
	
	function cambiaruta(){
	  $("#txtidviaje").val('0');
	  document.forms[0].submit();	
	}
	function marcaasientos(){
	   viaje =$("#txtidviaje").val();	
	    var par = {};
	    $.post("listaasientos.php?viaje="+viaje,par,function(data){
	    var res = data;
	    despues_marcaasientos(res);
	  });
	}
	function despues_marcaasientos(datos){
	   var lista = datos.split(',');
	   for(i=0;i<lista.length;i++){
		$("#asiento"+trim(lista[i])).removeClass('libre');
		$("#asiento"+trim(lista[i])).addClass('ocupado');

	  }
	  for(i=0;i<100;i++)
		if($("#asiento"+i)!=null)
		   $("#asiento"+i).click( function(ev) {
			agregaclickasi(ev);
			}
		    );


	}
function agregaclickasi(ev){
	$("#txtasiento").val(ev.currentTarget.id.substring(7,9));
}
function ltrim(s) {
   return s.replace(/^\s+/, "");
}
function rtrim(s) {
   return s.replace(/\s+$/, "");
}
function trim(s) {
   return rtrim(ltrim(s));
}
function cambiaviaje(){
  traeajax("formabus","bus50.php",null,marcaasientos);
}
function selectviaje(pfecha,pviaje){
 $("#txtidviaje").val(pviaje);
  cambiaviaje();
}
function llegadacustom(nombrediv,funciondespues){
	    if(funciondespues!=null)
                funciondespues();
            

}
function actualiza_consecutivo(){}
