function traer(){
  var par={"cbperfil":$("#cbperfil").val()};
  traeajax("detalle","segu.php",par,despuestraer);
}

function despuestraer(){
}

function marca(xobjeto,xck){
   if(xck.checked)
     xvalor=1;
   else
     xvalor=0;

   enlace("enlace.php?opcion=marcaobjeto&perfil="+$('#cbperfil').val()+"&objeto="+xobjeto+"&valor="+xvalor,finmarca);
}
function finmarca(dat){
   traer();
}
function llegadacustom(){
}
