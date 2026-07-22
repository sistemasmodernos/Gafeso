<?php
//parche para buses

function parchearhoras($pid){
            $link = new tiqmysql();

            $sql="select * from (
                select x.*,h.hora
                from (select v.idviaje,e.idestacion,v.horaini
                    from viaje v, estacion e   where v.fecha>=DATE_SUB(CURDATE(), INTERVAL 1 DAY) ) x
                    left join viajexesta h
                    on x.idviaje=h.idviaje and h.idestacion=x.idestacion ) as kk
                 where isnull(hora) 
                order by idviaje,hora desc;";

            $res=$link->bdEjecutar($sql);
            $ares=ResultArray($res);
            $x=0;
            while($x<count($ares)){
                $hora=$ares[$x]["horaini"];
                $viaje=$ares[$x]["idviaje"];
                while($x<count($ares) && $viaje=$ares[$x]["idviaje"]){
     //               echo "viaje ". $ares[$x]["idviaje"] ." - ". $ares[$x]["idestacion"]." - " . $ares[$x]["hora"]."<br/>";                    
                    if($ares[$x]["hora"]==null && $hora!=null){
                        $sql=sprintf("insert into viajexesta (idviaje,idestacion,hora,activo,fechadi)
                        values (%d,%d,'%s',%d,'%s')",
                        $ares[$x]["idviaje"],
                        $ares[$x]["idestacion"],
                        $hora,
                        1,
                        date('Y-m-d'));
                        $link->bdEjecutar($sql);
                        }
                    $x++;
                    }
            }
            if($pid!=0){
                 $xhor=$link->PrimerValorD("select horaini from viaje where idviaje=".$pid);
                 $link->bdEjecutar("update viajexesta set hora='".$xhor."' where idviaje=".$pid);
            }

    
}

function parchearlista(){
            $link = new tiqmysql();
            
            $sql="select x.*,h.precio
from (select v.idproducto,e.idruta,p1.idparada as idparada1,p2.idparada as idparada2,v.precio as preciop
            from producto v,parada p1, parada p2, ruta e ) x
left join lista h
on x.idproducto=h.idproducto and h.idparada1=x.idparada1 and h.idparada2=x.idparada2 and h.idruta=x.idruta
order by x.idproducto,precio desc";
            $res=$link->bdEjecutar($sql);
            $ares=ResultArray($res);
            $x=0;
            while($x<count($ares)){
                $precio=$ares[$x]["preciop"];
                $viaje=$ares[$x]["idproducto"];
                if($ares[$x]["precio"]==null && $precio!=null){
                    $sql=sprintf("insert into lista (idproducto,idparada1,idparada2,idruta,precio,activo,fechadi)
                    values (%d,%d,%d,%d,%d,%d,'%s')",
                    $ares[$x]["idproducto"],
                    $ares[$x]["idparada1"],
                    $ares[$x]["idparada2"],
                    $ares[$x]["idruta"],
                    $precio,1,date('Y-m-d'));
                    $link->bdEjecutar($sql);
                }
                $x++;   
            }
            


    
}
?>
