function traer(){
  var par={"cbusuario":$("#cbusuario").val()};
  traeajax("detalle","rutasxusu.php",par,despuestraer);
}

function despuestraer(){
}

function marca(xobjeto,xck){
   if(xck.checked)
     xvalor=1;
   else
     xvalor=0;

   enlace("enlace.php?opcion=marcarutausu&usuario="+$('#cbusuario').val()+"&objeto="+xobjeto+"&valor="+xvalor,finmarca);
}
function finmarca(dat){
   traer();
}
function llegadacustom(){
}
