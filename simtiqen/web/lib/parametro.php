<?php


/* Clase Parametro */

  class Parametro{
      var $bitacora;
  	function Retorna($nombre,$ptipo) {
         $link=new tiqmysql();
         switch($ptipo){
            case 1: //entero
              $sql="select vint as valor from parametro where identifica='".$nombre."'";
              break;
            case 2: //caracter
              $sql="select vcaracter as valor from parametro where identifica='".$nombre."'";
              break;
            case 3: //string
              $sql="select vtexto as valor from parametro where identifica='".$nombre."'";
              break;
            case 4: //fecha
              $sql="select vfecha as valor from parametro where identifica='".$nombre."'";
              break;
            case 1: //monto
              $sql="select vmonto as valor from parametro where identifica='".$nombre."'";
              break;
              
              
              
        }
        $res= $link->PrimerValorD($sql);
  	 	return $res;
  	}
    function Establece($pnombre,$ptipo,$pvalor){
         $link=new tiqmysql();
         switch($ptipo){
            case 1: //entero
              $sql=sprintf("update parametro set vint=%d  where identifica='%s' ",$pvalor,$pnombre);
              break;
            case 2: //caracter
              $sql=sprintf("update parametro set vcaracter='%s'  where identifica='%s' ",$pvalor,$pnombre);
              break;
            case 3: //string
              $sql=sprintf("update parametro set vtexto='%s'  where identifica='%s' ",$pvalor,$pnombre);
              break;
            case 4: //fecha
              $sql=sprintf("update parametro set vfecha='%s'  where identifica='%s' ",$pvalor,$pnombre);
              break;
            case 1: //monto
              $sql=sprintf("update parametro set vmonto=%d  where identifica='%s' ",$pvalor,$pnombre);
              break;
              
              
              
        }
        
        $res= $link->bdEjecutar($sql);
        $this->bitacora->bitacorizar("Actualiza par&aacute;metro ".$pnombre." -> ".$pvalor );
  	 	return true;
        
    }
    //Hereda el objeto bitácora    
	function HeredaBitacora($pbit){
		$pbit->nivel++;		
		$this->bitacora=$pbit;
		$this->bitacora->obPadre=$this;
	}
    function ToString(){
        return "";
    }
  }
  
?>