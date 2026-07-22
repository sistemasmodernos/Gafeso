<?php 
class Seguridad{
    var $bitacora;
    function ObjetoValido($objeto,$idperfil){
        $ar =$this->DevObjetos($idperfil);
        
        if(in_array($objeto,$ar)){
          return true;
          }
        else{
          return false;
          }
          
    }
    function DevObjetos($idperfil){
        $sql="select o.llave 
          from objeto o
            inner join  perxobj x
            on o.idobjeto=x.idobjeto
            inner join perfil p 
            on x.idperfil=p.idperfil
           where p.idperfil=" . $idperfil;
        $link = new tiqmysql();
        $res= $link->bdEjecutar($sql);
        
        $ar2 = array();
        while($lin=mysqli_fetch_array($res, MYSQLI_ASSOC)){
            array_push($ar2,$lin["llave"]);
        }
        return $ar2;
    }
    function TodosObjetos($pperfil){
        $sql=sprintf("select o1.idobjeto,o1.llave,o1.nombre,o1.idpadre,
        case when o1.idpadre is null then o1.llave  else concat(o2.llave,o1.llave) end as llave2,
        case when o1.idpadre is null then 1  else 2 end as nivel,
		case when o1.idpadre is null then concat(o1.llave,'0')  else concat(o2.llave,'1') end as llave3,
        x.idperxobj
        from objeto o1 left join objeto o2
        on o1.idpadre=o2.idobjeto
        left join ( select idperxobj, idobjeto from perxobj where idperfil=%d) x
        on o1.idobjeto=x.idobjeto
        order by 7,3",$pperfil);
    $link = new tiqmysql();
    return $link->bdEjecutar($sql);
    }

    function HeredaBitacora($pbit){
        $pbit->nivel++;		
		$this->bitacora=$pbit;
        $this->bitacora->obPadre=$this;
    }
    
    function MarcarObjeto($pidperfil,$pidobjeto,$pvalor){
       $link= new tiqmysql();
        $link->bdAbreTran();
        $sql=sprintf("select idperxobj from perxobj where idperfil= %d and idobjeto=%d",$pidperfil,$pidobjeto);
        $res=$link->PrimerValorD($sql);
      
        if($pvalor==1)
        {
           if($res==null)
             $sql=sprintf("insert into perxobj (idperfil,idobjeto) values (%d,%d)",$pidperfil,$pidobjeto);
         }
         else
           if($res!=null)
             $sql=sprintf("delete from perxobj where idperxobj=%d",$res);
       
        $link->bdEjecutar($sql);
        $this->bitacora->bitacorizar("Asigna permisos ".$pidperfil." ".$pidobjeto." ".$pvalor);
        $link->bdCierraTran();

        return true;
    }
    
    function ToString(){
        return "";
    }
}

?>