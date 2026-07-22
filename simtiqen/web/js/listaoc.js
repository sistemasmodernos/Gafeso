function anular(elid){
   if (confirm('Está seguro(a) que desea anular la orden '+elid+'?')) {
   $("#formloc").get(0).setAttribute('action', 'listaoc.php?do=anular&id='+elid);
   $("#formloc").submit();

   }
}
