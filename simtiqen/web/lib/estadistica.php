<?php
 class Estadistica{
 function viajes( $pruta,$pestacion,$pinicio,$pfin){
       $sql= sprintf("select v.idviaje,v.fecha,x.hora,b.cantpas,
       b.placa,ifnull(t.vendidos,0) as vendidos,ifnull(t.depie,0) as depie,b.color,v.extra,v.noweb,v.idtipoviaje
        from viaje v 
        inner join viajexesta x
        on v.idviaje=x.idviaje
        inner join Bus b
        on v.idbus = b.idbus
        left join (
          select t2.idviaje,sum(case when t2.asiento>0 then 1 else 0 end) as vendidos 
          ,sum(case when t2.asiento<0 then 1 else 0 end) as depie 
          from tiquete t2 inner join viaje v2 on v2.idviaje=t2.idviaje
          where v2.fecha between '%s' and '%s' and t2.estado=0
          and  t2.factura!='APARTA'
          group by t2.idviaje
        ) t
        on v.idviaje=t.idviaje
        where v.idruta=%d
        and x.idestacion=%d
        and v.fecha between '%s' and '%s'
        and v.activo=1
        and x.activo=1
        group by v.idviaje,v.fecha,x.hora,b.cantpas,b.color,v.extra
        order by v.fecha,x.hora,v.idviaje",
        $pinicio,$pfin,$pruta,$pestacion,$pinicio,$pfin);
      $link = new tiqmysql();
      return $link->bdEjecutar($sql);
    }

    function repoD151($pfechai,$pfechaf){
        $sql=sprintf("select t.cedremi as cedula,t.remitente as nombre,count(*) as cantidad,sum(t.monto) as monto
        from encomienda t
        where t.fecha between '%s' and '%s'  and t.estado=0
        group by t.cedremi,t.remitente
        order by 2",$pfechai,$pfechaf);
        $link = new tiqmysql();
        return $link->bdEjecutar($sql);
    }
    function AdultoMayor($pfechai,$pfechaf){
        $sql=sprintf("select c.cedula,c.nombre,c.fechanac,datediff(now(),c.fechanac) as edad,sum(t.monto) as monto,count(*) as cantidad
from cliente c inner join tiquete t
on c.idcliente=t.idcliente
where t.fecha between '%s' and '%s'
and t.estado=' ' and t.idproducto=2
group by c.cedula,c.nombre,c.fechanac",$pfechai,$pfechaf);
      $link= new tiqmysql();
      return $link->bdEjecutar($sql);
}
   function revisaAdultoMayor($pcedula,$pfecha,$pruta){
       $sql= sprintf("select datediff(now(),c.fechanac) as edad,count(*) as veces 
          from cliente c inner join tiquete t
         on c.idcliente=t.idcliente
         inner join viaje v on v.idviaje=t.idviaje 
         where c.cedula='%s' and t.fecha = '%s'
         and t.estado=' ' and t.idproducto=2 
         and v.idruta=%d
         group by c.fechanac ",$pcedula,$pfecha,$pruta);
       $link= new tiqmysql();
      $res = $link->bdEjecutar($sql); 
      if($link->bdCantLineas($res)){
          $linea = mysqli_fetch_array($res);
          if($linea["veces"]>=1)
             return "Este adulto mayor ya tiene un tiquete para la misma ruta en el mismo día";
        else
           return "";
      }

   }
   
   function listaReimpresion($ldfechai,$ldfechaf,$potros){
       $sql= "select u.nombre,r.fecha as fechareimpre,t.idtiquete,t.factura,t.fecha as fechatiq,v.fecha as fechaviaje,v.horaini,t.asiento
            from reimpresion r 
            inner join tiquete t on t.idtiquete=r.idtiquete
            inner join viaje v on v.idviaje=t.idviaje
            inner join usuario u on u.idusuario=r.idusuario 
            where r.fecha between '".$ldfechai."' and '".$ldfechaf."' ".$potros ;

            $link= new tiqmysql();
      return $link->bdEjecutar($sql);

    
    }
function listaCortesia($ldfechai,$ldfechaf,$potros){
       $sql= "select u.nombre,t.anombre,t.comentario,t.idtiquete,t.factura,t.fecha as fechatiq,v.fecha as fechaviaje,v.horaini,t.asiento
            from  tiquete t 
            inner join viaje v on v.idviaje=t.idviaje
            inner join usuario u on u.idusuario=t.idusuario 
            where t.fecha between '".$ldfechai."' and '".$ldfechaf."' and t.monto=0 ".$potros ;

            $link= new tiqmysql();
      return $link->bdEjecutar($sql);
}

function listaNulo($ldfechai,$ldfechaf,$potros){
       $sql= "select u.nombre,t.anombre,t.comentario,t.idtiquete,t.factura,t.fecha as fechatiq,v.fecha as fechaviaje,v.horaini,t.asiento
            from  tiquete t 
            inner join viaje v on v.idviaje=t.idviaje
            inner join usuario u on u.idusuario=t.idusuario 
            where t.fecha between '".$ldfechai."' and '".$ldfechaf."'  and t.estado = 1 ".$potros ;

            $link= new tiqmysql();
      return $link->bdEjecutar($sql);
}


function ListaCarreras($ldfechai,$ldfechaf){
  $sql = "select fecha,placa
  ,sum(vendidos) as vendidos
  ,sum(adulto) as adulto
  ,sum(normal) as normal
  ,sum(monto) as monto
  ,round(count(*)/2,2) as carreras
  ,round(sum(monto)/max(tarifamax),0) as equivalente
  from vista_ocupacion2 
  where fecha between '".$ldfechai."' and '".$ldfechaf."' group by fecha,placa
  order by fecha,placa ";

  $link = new tiqmysql();
  return $link->bdEjecutar($sql);
}

function RepChoferes($pfechai,$pfechaf,$pmonto,$lunchofe,$lchofer)
   {
    if($lunchofe ==0)
      $elsql = "";
    else
      $elsql = "idchofer = '".$lchofer."' and ";

    $sql="select 1 as orden, vi.idchofer,vi.idruta,vi.nviajes,
        ch.Nombre as chofer,
        substring(ch.Nombre,1,LOCATE(' ', ch.Nombre)-1) as cnombre,
        ru.nombre as ruta,
        vi.nviajes * ".$pmonto." as apagar
        from (select idchofer,idruta,count(idviaje) as nviajes
        from viaje
        where ".$elsql." fecha between '".$pfechai."' and '".$pfechaf."'
        group by idchofer,idruta) as vi
        left join chofer as ch on vi.idchofer = ch.idchofer
        left join ruta as ru on vi.idruta = ru.idruta
        union
        select 2 as orden, 
             vi.idchofer,
               vi.idruta,
               sum(vi.nviajes) as nviajes,
               ch.Nombre as chofer,
               substring(ch.Nombre,1,LOCATE(' ', ch.Nombre)-1) as cnombre,
               ru.nombre as ruta, 
               sum(vi.nviajes * ".$pmonto.") as apagar
        from (select idchofer,idruta,count(idviaje) as nviajes
            from viaje
            where ".$elsql." fecha between '".$pfechai."' and '".$pfechaf."'
            group by idchofer,idruta) as vi
        left join chofer as ch on vi.idchofer = ch.idchofer
        left join ruta as ru on vi.idruta = ru.idruta
        group by vi.idchofer
        order by 2,1 ";

   $link= new tiqmysql();
   return $link->bdEjecutar($sql);
   #return var_dump($sql);
  }

function RepEsta1($pfechai,$pfechaf)
   {
    $sql="select n.idruta, n.nombre ,
           sum(n.viajes)   as viajes,
           sum(n.espacios) as espacios, 
           sum(n.personas) as personas,
           sum(n.personas/n.espacios)*100 as ocupacion,
           sum(e.viajes)   as e_viajes,
           sum(e.espacios) as e_espacios, 
           sum(e.personas) as e_personas,
           sum(e.personas/e.espacios)*100 as e_ocupacion,
           sum(n.viajes+e.viajes)  as t_viajes,
           sum(n.espacios + e.espacios) as t_espacios, 
           sum(n.personas + e.personas) as t_personas,
           sum( (n.personas+e.personas) / (n.espacios+e.espacios))*100 as t_ocupacion
      from (select vi.idruta, ru.nombre ,
           count(vi.idviaje)  as viajes,
           sum(bu.cantpas)    as espacios, 
           sum(ti.personas)   as personas
           from viaje as vi inner join Bus  as bu on vi.idbus  = bu.idbus
                 inner join ruta as ru on vi.idruta = ru.idruta
                 left join (
                    select t2.idviaje,
                       sum(case when t2.asiento>0 then 1 else 0 end) as personas,
                       sum(case when t2.asiento<0 then 1 else 0 end) as depie
                     from tiquete t2 inner join viaje v2 on v2.idviaje=t2.idviaje
                    where v2.fecha between '".$pfechai."' and '".$pfechaf."'
                      and t2.estado=0
                      and t2.factura!='APARTA'
                    group by t2.idviaje,v2.extra  ) as ti  on vi.idviaje=ti.idviaje 
            where vi.fecha between  '".$pfechai."' and '".$pfechaf."'
              and vi.idtipoviaje=1
              and vi.activo=1
            group by vi.idruta) as n left join 
       (select vi.idruta, ru.nombre ,
          count(vi.idviaje)  as viajes,
          sum(bu.cantpas)    as espacios, 
          sum(ti.personas)   as personas
         from viaje as vi inner join Bus  as bu on vi.idbus  = bu.idbus
                 inner join ruta as ru on vi.idruta = ru.idruta
                 left join (
                    select t2.idviaje,
                       sum(case when t2.asiento>0 then 1 else 0 end) as personas,
                       sum(case when t2.asiento<0 then 1 else 0 end) as depie
                     from tiquete t2 inner join viaje v2 on v2.idviaje=t2.idviaje
                    where v2.fecha between '".$pfechai."' and '".$pfechaf."'
                      and t2.estado=0
                      and t2.factura!='APARTA'
                    group by t2.idviaje,v2.extra  ) as ti  on vi.idviaje=ti.idviaje 
         where vi.fecha between '".$pfechai."' and '".$pfechaf."'
           and vi.idtipoviaje!=1
           and vi.activo=1
         group by vi.idruta) as e
    on e.idruta = n.idruta
    group by n.idruta" ;
    $link= new tiqmysql();
    return $link->bdEjecutar($sql);
  }

function VentasGenerales($pfechai,$pfechaf,$pfiltros){
  $elsql= "select t.fechadi,if(t.estado=1,0,t.monto) as monto,u.nombre as usuario,
  pro.nombre as producto
  ,par.nombre as parada, tip.destipoviaje as tipoviaje, t.asiento, t.estado
  ,v.fecha as fechaviaje,v.horaini as hora,soc.nombre as socio
  from tiquete t
  inner join usuario u on u.idusuario=t.idusuario
  inner join producto pro on pro.idproducto=t.idproducto
  inner join parada par on par.idparada=t.idparada2
  inner join viaje v on v.idviaje=t.idviaje
  inner join tipoviaje tip on tip.idtipoviaje= v.idtipoviaje
  inner join Bus b on b.idBus=v.idbus
  inner join socio soc on soc.idsocio = b.idsocio
  where t.fecha between '".$pfechai."' and '".$pfechaf."'
   ".$pfiltros. " order by fechadi ";
  
  $link= new tiqmysql();
  return $link->bdEjecutar($elsql);
}

function VentaGralCajero($pfechai,$pfechaf,$pfiltros){
  $elsql= "select t.fechadi,if(t.estado=1,0,t.monto) as monto,u.nombre as usuario,
  pro.nombre as producto
  ,par.nombre as parada, tip.destipoviaje as tipoviaje, t.asiento, t.estado
  ,v.fecha as fechaviaje,v.horaini as hora,soc.nombre as socio
  from tiquete t
  inner join usuario u on u.idusuario=t.idusuario
  inner join producto pro on pro.idproducto=t.idproducto
  inner join parada par on par.idparada=t.idparada2
  inner join viaje v on v.idviaje=t.idviaje
  inner join tipoviaje tip on tip.idtipoviaje= v.idtipoviaje
  inner join Bus b on b.idBus=v.idbus
  inner join socio soc on soc.idsocio = b.idsocio
  where t.fecha between '".$pfechai."' and '".$pfechaf."'
   ".$pfiltros. " order by u.nombre,t.factura ";
  
  $link= new tiqmysql();
  return $link->bdEjecutar($elsql);
}

function LiquidacionxPeriodo($pfechai,$pfechaf,$pfiltros){
  $elsql="
 select v.idviaje
  ,v.fecha
  ,v.horaini
  ,b.placa
  ,b.idbus
  ,s.Nombre as socio
  ,s.idsocio 
  ,r.abreviatura as estacion
  ,r.idruta
  ,ifnull(sum(case when t.estado = 1 then 0 else t.monto end),0) as monto
  ,sum(case when isnull(t.asiento) then 0 else (case when t.estado = 1 then 0 else 1 end) end) as cantidad,r.montosocio as rebajo
  from  viaje v 
  left join tiquete t on v.idviaje=t.idviaje
  left join producto p on t.idproducto = p.idproducto
  left join impresora i on t.idimpresora = i.idimpresora
  inner join Bus b on b.idbus = v.idbus
  inner join socio s on s.idsocio=b.idsocio
  inner join ruta r on r.idruta=v.idruta
  where v.liquidado=1 and v.fecha between '".$pfechai."' and '".$pfechaf."' ".$pfiltros."
  group by v.idviaje,b.placa
  ,b.idbus
  ,s.Nombre
  ,s.idsocio 
  ,r.montosocio
  ,r.abreviatura
  ,r.idruta
  ";
 
  $link= new tiqmysql();
  return $link->bdEjecutar($elsql);

}


}
?>
