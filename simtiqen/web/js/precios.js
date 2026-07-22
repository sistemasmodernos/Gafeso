function traer(){
  var par={"cbruta":$("#cbruta").val(),"cbproducto":$("#cbproducto").val()};
  traeajax("detalle","precios.php",par,despuestraer);
}

function despuestraer(){
}


function llegadacustom(){
}

function GuardarDatos(){
	var registros = $("#tablamarca tbody tr");
	var cadena="";
	$(registros).each(function(){
       lidparada1 = $("#txtparada1",this).val();
       lidparada2 = $("#txtparada2",this).val();
       lproducto = $("#cbproducto").val();
       lprecio = $("#txtprecio",this).val();
       if(lidparada1!='0' && lidparada2!='0' )
       	cadena+=lidparada1+","+lidparada2+","+lproducto+","+lprecio+"!";
	});
	var par={"cbruta":$("#cbruta").val(),"cbproducto":$("#cbproducto").val(),"lacadena":cadena};
    traeajax("detalle","precios.php?do=guardar",par,despuestraer);
}

