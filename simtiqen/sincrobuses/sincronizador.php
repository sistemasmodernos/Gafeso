<?php
 include('mysql.php');
 $link=new mimysql();
 $link2=new mimysql2();
 $sql="select '001' as cobrador,b.placa as bus,
case v.idruta WHEN 1 THEN '001' else '004' end as estacion,
v.fecha,v.horaini as hora,'998' as estadi,v.extra
from viaje v inner join Bus b
on v.idbus=b.idbus
where v.viajo=0";
$res = $link->bdEjecutar($sql);
while($lin =mysql_fetch_array($res)){
    if($link2->PrimerValorD(sprintf("select hora from horario where estacion='%s' and fecha='%s' and hora='%' ",$lin["estacion"],$lin["fecha"],$["hora"]))==null)
    {
        $sql=sprintf("insert into horario
         (cobrador,bus,estacion,fecha,hora,estadi,extra)
         values ('%s','%s','%s','%s','%s','%s',%d)",
         $lin["cobradora"],
         $lin["bus"],
         $lin["estacion"],
         $lin["fecha"],
         $lin["hora"],
         $lin["estadi"],
         $lin["extra"],
         );
         $link2->bdEjecutar($sql);
    }
    
}
$sql="select '998' as impresora, t.factura as ide_fact,t.fecha,
t.monto,v.fecha as fechavi,v.horaini as hora,'998' as estacion,
'TIQ001' as ide_inve,'998' as estadi,0 as borrado,0 as cierre,
case when t.estado=0 then ' ', else '&' end as estado,
'040' as parada,
case when t.asiento=-1 then 99 else t.asiento end as asiento,'041' as destino
from tiquete t
inner join viaje v
on t.idviaje=v.idviaje
where t.viajo=0;"

$res=$link->bdEjecutar($sql);
while($lin =mysql_fetch_array($res)){
    if($link2->PrimerValorD(sprintf("select ide_fact from boletos where impresora='%s' and ide_fact='%s'  ",$lin["impresora"],$lin["ide_fact"]))==null)    
    {
        $sql=sprintf("insert into boletos (
             impresora,ide_fact,fecha,
            monto,fechavi,hora,estacion,
            ide_inve,estadi,borrado,
            cierre,estado,parada,
            asiento,destino,fechatem,cedula)
            values (
            '%s','%s','%s',
            %d,'%s','%s','%s',
            '%s','%s',%d,
            %d,'%s','%s',
            %d,'%s','','')",
            $lin["impresora"],$lin["ide_fact"],$lin["fecha"],
            $lin["monto"],$lin["fechavi"],$lin["hora"],$lin["estacion"],
            $lin["ide_inve"],$lin["estadi"],$lin["borrado"],
            $lin["cierre"],$lin["estado"],$lin["parada"],
            $lin["asiento"],$lin["destino"]
            
            );
            $link2->bdEjecutar($sql);
            $link2->bdEjecutar(sprintf("delete from boletosx where impresora ='%s' and ide_fact ='%s' ",$lin["impresora"],$lin["ide_fact"]));
    }
    else
    {
        $sql=sprintf("update boletos set fecha='%s', monto=%d,
        estado='%s', parada='%s', asiento=%d, destino='%s' 
        where impresora='%s' and ide_fact='%s'  ",
        $lin["fecha"],$lin["monto"],$lin["estado"],$lin["parada"],$lin["asiento"],
        $lin["destino"],
        $lin["impresora"],$lin["ide_fact"]);
        $link2->bdEjecutar($sql);
    }
    
}

?>