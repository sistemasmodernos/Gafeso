function imprimecor(){
 var res= confirm ("Esta seguro(a) que desea imprimir los tiquetes seleccionados?");
 if(!res)
   return;

  var ids='';
  $("input[type=checkbox]:checked").each(function(){
	//cada elemento seleccionado
	ids=ids+$(this).val()+",";
  });
  if(ids.length==0)
    alert("No ha marcado ningun tiquete");
  else {
  	ids=ids.substring(0,ids.length-1);
  	enlace("imptiquete.php?id="+ids,imprimetiqfin);
  }
// $("#fimpcortesia").submit();
	
}
