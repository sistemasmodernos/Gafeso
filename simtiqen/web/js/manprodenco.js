function actualizar(ptr,ptipo){
	var ltr = $(ptr).closest( "tr" );
	var lid = $(ltr).data("id");
	var lnombre = $("#txtnombre",ltr).val();
	var lactivo=1;
	var lprecio = $("#txtprecio",ltr).val();

	var par = { idProdenco:lid , nombre:lnombre, activo:lactivo, precio:lprecio, tipoactu:ptipo} ;
	traeajax("datos","manprodenco.php?do=save",par,fin_actualizar);
}

function quitar(ptr){
	var ltr = $(ptr).closest( "tr" );
	var lid = $(ltr).data("id");
	var lnombre = $("#txtnombre",ltr).val();
	var lactivo=1;
	var lprecio = $("#txtprecio",ltr).val();

	var par = { idProdenco:lid , nombre:lnombre, activo:lactivo, precio:lprecio, tipoactu:3} ;
	traeajax("datos","manprodenco.php?do=save",par,fin_actualizar);
}

function fin_actualizar(pdata){
 	
}

function llegadacustom(){

}