function cambiahorario(){
  if($("txtidtiquete").val()==""){
    alert("Debe digitar el número de tiquete primero");
    return false;
  }
   var res= confirm ("Esta seguro(a) que desea cambiar estos datos?");
 if(res){
   $("#fcambiahora").attr("action","cambiahora.php?do=cambiar");
   $("#fcambiahora").submit();
 }

}
function cargar(){
 $("#fcambiahora").submit();
}
function cargacambiahora(){
  if($("txtidtiquete").val()==""){
    alert("Debe digitar el número de tiquete primero");
    return false;
  }
  $("#fcambiahora").attr("action","cambiahora.php?do=cargar");
  $("#fcambiahora").submit();


}


function llegadacustom(){
}

            function cambiafecha(){
                refrescahora();
                
            }


    function refrescahora(){
                var lafecha;
                if($("#datepicker").val()==null){
                    var f = new Date();
                    lafecha= f.getDate() + "/" + (f.getMonth() +1) + "/" + f.getFullYear();
                }
                else
                {
                    lafecha=$("#datepicker").val();
                 }

                traeajax("divhora","crtviaje.php?estacion="+
                  $("#cbestacion").val()+
                "&ruta="+$("#cbruta").val()+
                "&fecha="+fechatoUTC(lafecha)+
                "&parada="+$("#cbparada1").val()+
		"&tipo=1",null,cambiahora);

            }

//cambia formato de fecha 01/12/2011 a 2011-12-01

            function fechatoUTC(lcfecha){
                if(lcfecha){
                var cad = lcfecha.split('/');
                return cad[2]+"-"+cad[1]+"-"+cad[0];
                }
                else
                  return "";
            }

            function fechatoNormal(lcfecha){
                if(lcfecha){
                var cad = lcfecha.split('-');
                return cad[2]+"/"+cad[1]+"/"+cad[0];
               }
               else
                 return "";
            }
//Carga toda la divisi'on de horarios
            function llegadacustom(nombrediv,funciondespues){
               switch (nombrediv){
                   case 'divcrtviaje' : 
                        // Datepicker
                        $('#datepicker').datepicker({
                            inline: true
                        });
                        $("#datepicker").datepicker($.datepicker.regional['es']);
/*                        $.datepicker._selectDateOverload = $.datepicker._selectDate; 
                        $.datepicker._selectDate = function(id, dateStr) { 
                        var target = $(id); 
                        var inst = this._getInst(target[0]); 
                        inst.inline = true; 
                        $.datepicker._selectDateOverload(id, dateStr); 
                        inst.inline = false; 
                        this._updateDatepicker(inst); 
                        }
*/
                    break;
                   case 'divhora': 
                     // cambiahora();
                      break;
                }
	    if(funciondespues!=null)
                funciondespues();
            }

function cambiahora(valor){
 $("#cbviaje").val($("#cbhora").val());
}

