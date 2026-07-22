<?php
/* Bitacora */
class Bitacora{
  var $nivel;
  var $usuario;
  var $idPadre;
  var $obPadre;
  function Bitacorizar($pTexto){
      $link=new tiqmysql();
    //  echo var_dump($this);
      $sql=sprintf("insert into bitacora (idusuario,detalle,detobjeto,idpadre,fechadi)
      values (%d,'%s','%s',%d,now())",$this->usuario->idUsuario,$pTexto,$this->obPadre->ToString(),$this->idPadre);
      $link->bdEjecutar($sql);
      $this->idPadre=$link->bdUltimoId();
    return true;
  }
}
?>
