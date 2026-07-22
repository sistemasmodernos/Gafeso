<?php
class Cierred{
    var $idCierre;
    var $impresora;
    var $estacion;
    var $fechai;
    var $fechaf;
    var $ingresos;
    var $egresos;
    var $usuario;
    var $bitacora;
 	function __construct(){
 	   $this->Limpiar();
 	}
    function Limpiar(){
        $this->idCierre=0;
        $this->impresora= new Impresora();
        $this->estacion= new Estacion();
        $this->fechai=date('Y-m-d',time());
        $this->fechaf=date('Y-m-d',time());
        $this->ingresos= array();
        $this->egresos=  array();
        $this->usuario = new Usuario();
    }

    function Resumen(){
        $link = new tiqmysql();
        if($this->idCierre==0){
            $sql="
            select max(v.idviaje) as idviaje,v.fecha,max(x.hora) as hora,p.nombre,count(*) as cantidad,
                sum(t.monto) as monto,0 as montopos,if(isnull(socio.nombre),'Sin Socio',socio.nombre) as socio,
                'Tiquetes' as tipo,'Efectivo' as desformapago
                ,ruta.abreviatura,1 as entracierre, 0 as saltran,0 as iva,0 as ivapos
                from tiquete t 
                inner join producto p on t.idproducto=p.idproducto
                inner join viaje v on t.idviaje=v.idviaje
                inner join viajexesta x on v.idviaje=x.idviaje
                left join Bus on v.idbus = Bus.idbus
                left join socio on Bus.idsocio = socio.idsocio
                left join ruta on v.idruta = ruta.idruta
                where t.idestacion=".$this->estacion->idEstacion."
                and t.idimpresora=".$this->impresora->idImpresora."
                and t.fecha between '".$this->fechai."' and '".$this->fechaf."'
                and x.idestacion=t.idestacion
                and t.estado=0
                and t.idcierre=0
                and t.idcierre=0
                group by socio.nombre,v.fecha,p.nombre,ruta.abreviatura
            union
            select v.idviaje,v.fecha,x.hora,p.nombre,count(*) as cantidad
                ,sum(t.monto) as monto
                ,sum(case 
                    when t.fechafe between  '".$this->fechai."' and '".$this->fechaf." 23:59' 
                    then 0 
                    else t.monto end) as montopos
                ,'Sin Socio' as socio,'Encomiendas' as tipo,fp.desformapago
                ,ruta.abreviatura,fp.entracierre, 0 as saltran
                ,sum(t.iva) as iva
                ,sum(case 
                    when t.fechafe between  '".$this->fechai."' and '".$this->fechaf." 23:59' 
                    then 0 
                    else t.iva end) as ivapos
                from encomienda t inner join producto p
                on t.idproducto=p.idproducto
                inner join viaje v
                on t.idviaje=v.idviaje
                inner join viajexesta x
                on v.idviaje=x.idviaje
                inner join formapago fp
                on t.idformapago=fp.idformapago
                left join ruta on v.idruta = ruta.idruta
                where t.idestacion=".$this->estacion->idEstacion."
                and t.idimpresora=".$this->impresora->idImpresora."
                and t.fecha between '".$this->fechai."' and '".$this->fechaf."'
                and x.idestacion=t.idestacion
                and t.estado=0
                and t.idcierre=0
                and p.tipo=2
                group by fp.entracierre,fp.desformapago,v.idviaje,v.fecha,x.hora,p.nombre
            union
            select v.idviaje,v.fecha,x.hora,'Comision Transf.  ' as nombre,count(*) as cantidad
                ,sum(t.monto) as monto
                ,sum(case 
                    when t.fechafe between  '".$this->fechai."' and '".$this->fechaf." 23:59' 
                    then 0 
                    else t.monto end) as montopos
                ,'Sin Socio' as socio,'Comision por Transferencias' as tipo,fp.desformapago
                ,ruta.abreviatura,fp.entracierre, 0 as saltran
                ,sum(t.iva) as iva
                ,sum(case 
                    when t.fechafe between  '".$this->fechai."' and '".$this->fechaf." 23:59' 
                    then 0 
                    else t.iva end) as ivapos
                from encomienda t inner join producto p
                on t.idproducto=p.idproducto
                inner join viaje v
                on t.idviaje=v.idviaje
                inner join viajexesta x
                on v.idviaje=x.idviaje
                inner join formapago fp
                on t.idformapago=fp.idformapago
                left join ruta on v.idruta = ruta.idruta
                where t.idestacion=".$this->estacion->idEstacion."
                and t.idimpresora=".$this->impresora->idImpresora."
                and t.fecha between '".$this->fechai."' and '".$this->fechaf."'
                and x.idestacion=t.idestacion
                and t.estado=0
                and t.idcierre=0
                and p.tipo=3
                group by fp.entracierre,fp.desformapago,v.idviaje,v.fecha,x.hora,p.nombre
            union
            select v.idviaje,v.fecha,x.hora,'Monto Transf.     ' as nombre,count(*) as cantidad,sum(t.declarado) as monto,0 as montopos
                ,'Sin Socio' as socio,'Monto de Transferencias' as tipo,'Efectivo' as desformapago
                ,ruta.abreviatura,fp.entracierre, 0 as saltran,0  as iva, 0 as ivapos
                from encomienda t inner join producto p
                on t.idproducto=p.idproducto
                inner join viaje v
                on t.idviaje=v.idviaje
                inner join viajexesta x
                on v.idviaje=x.idviaje
                inner join formapago fp
                on t.idformapago=fp.idformapago
                left join ruta on v.idruta = ruta.idruta
                where t.idestacion=".$this->estacion->idEstacion."
                and t.idimpresora=".$this->impresora->idImpresora."
                and t.fecha between '".$this->fechai."' and '".$this->fechaf."'
                and x.idestacion=t.idestacion
                and t.estado=0
                and t.idcierre=0
                and p.tipo=3
                group by fp.entracierre,fp.desformapago,v.idviaje,v.fecha,x.hora,p.nombre
            union
            select v.idviaje,v.fecha,x.hora,'Retiro Transferencia' as nombre,count(*) as cantidad,sum(-t.declarado) as monto,0 as montopos
                ,'Sin Socio' as socio,'Salida por Transferencia' as tipo,'' as desformapago
                ,ruta.abreviatura,1 as entracierre, 1 as saltran,0 as iva ,0 as ivapos
                from encomienda t 
                inner join producto p on t.idproducto=p.idproducto
                inner join viaje v on t.idviaje=v.idviaje
                inner join viajexesta x on v.idviaje=x.idviaje and x.idestacion=t.idestacion 
                inner join formapago fp on t.idformapago=fp.idformapago
                left join ruta on v.idruta = ruta.idruta
                where t.estaretiro=".$this->estacion->idEstacion."
                and t.entimpresora=".$this->impresora->idImpresora."
                and t.fecharetiro between '".$this->fechai."' and '".$this->fechaf." 23:59:59'
                and t.estado=0
                and t.entcierre=0
                and p.tipo=3
                group by fp.entracierre,fp.desformapago,v.idviaje,v.fecha,x.hora,p.nombre            
            union 
            select v.idviaje,v.fecha,x.hora,p.nombre,count(*) as cantidad
                ,sum(t.monto) as monto
                ,0 as montopos
                ,'Sin Socio' as socio
                ,'Encomiendas' as tipo
                ,concat(fp.desformapago,' Entregado') as desformapago
                ,ruta.abreviatura
                ,fp.sumaentcierre as entracierre
                ,0 as saltran
                ,sum(t.iva) as iva, 0 as ivapos
                from encomienda t inner join producto p
                on t.idproducto=p.idproducto
                inner join viaje v
                on t.idviaje=v.idviaje
                inner join viajexesta x
                on v.idviaje=x.idviaje
                inner join formapago fp
                on t.idformapago=fp.idformapago
                left join ruta on v.idruta = ruta.idruta
                where t.estaretiro=".$this->estacion->idEstacion."
                and t.entimpresora=".$this->impresora->idImpresora."
                and t.fecharetiro between '".$this->fechai."' and '".$this->fechaf." 23:59:59'
                and x.idestacion=t.estaretiro
                and fp.sumaentcierre = 1
                and t.estado=0
                and t.entcierre=0
                and p.tipo=2
                group by fp.sumaentcierre,fp.desformapago,v.idviaje,v.fecha,x.hora,p.nombre
                order by 10,9,8,2,3,4  
            ";
            
             
        }
        else
        {
            $this->Cargar();
            $this->fechai = substr($this->fechai,0,10);
            $this->fechaf = substr($this->fechaf,0,10);
        $sql=sprintf("
        select max(v.idviaje) as idviaje,v.fecha,max(x.hora) as hora,p.nombre,count(*) as cantidad
            ,sum(t.monto) as monto
            ,0 as montopos
            ,if(isnull(socio.nombre),'Sin Socio',socio.nombre) as socio,
            'Tiquetes' as tipo,'Efectivo' as desformapago
            ,ruta.abreviatura,1 as entracierre, 0 as saltran
            ,0 as iva
            ,0 as ivapos
            from tiquete t 
            inner join producto p on t.idproducto=p.idproducto
            inner join viaje v on t.idviaje=v.idviaje
            inner join viajexesta x on v.idviaje=x.idviaje
            left join Bus on v.idbus = Bus.idbus
            left join socio on Bus.idsocio = socio.idsocio
            left join ruta on v.idruta = ruta.idruta
            where t.idcierre=%d
            and x.idestacion=t.idestacion
            and t.estado=0
            group by socio.nombre,v.fecha,p.nombre,ruta.abreviatura
        union
        select v.idviaje,v.fecha,x.hora,p.nombre,count(*) as cantidad
            ,sum(t.monto) as monto
            ,sum(case 
                when t.fechafe between  '".$this->fechai."' and '".$this->fechaf." 23:59' 
                then 0 
                else t.monto end) as montopos
            ,'Sin Socio' as socio,'Encomiendas' as tipo,fp.desformapago
            ,ruta.abreviatura,fp.entracierre, 0 as saltran
            ,sum(t.iva) as iva
            ,sum(case 
                when t.fechafe between  '".$this->fechai."' and '".$this->fechaf." 23:59' 
                then 0 
                else t.iva end) as ivapos
            from encomienda t inner join producto p
            on t.idproducto=p.idproducto
            inner join viaje v
            on t.idviaje=v.idviaje
            inner join viajexesta x
            on v.idviaje=x.idviaje
            inner join formapago fp
            on t.idformapago=fp.idformapago
            left join ruta on v.idruta = ruta.idruta
            where  t.idcierre=%d
            and x.idestacion=t.idestacion
            and t.estado=0
            group by fp.entracierre,fp.desformapago,v.idviaje,v.fecha,x.hora,p.nombre
        union
        select v.idviaje,v.fecha,x.hora,p.nombre,count(*) as cantidad
            ,sum(t.monto) as monto
            ,sum(case 
                when t.fechafe between  '".$this->fechai."' and '".$this->fechaf." 23:59' 
                then 0 
                else t.monto end) as montopos
            ,'Sin Socio' as socio,'Ingreso por Transferencias' as tipo,fp.desformapago
            ,ruta.abreviatura,fp.entracierre, 0 as saltran
            ,sum(t.iva) as iva
            ,sum(case 
                when t.fechafe between  '".$this->fechai."' and '".$this->fechaf." 23:59' 
                then 0 
                else t.iva end) as ivapos
            from encomienda t inner join producto p
            on t.idproducto=p.idproducto
            inner join viaje v
            on t.idviaje=v.idviaje
            inner join viajexesta x
            on v.idviaje=x.idviaje
            inner join formapago fp
            on t.idformapago=fp.idformapago
            left join ruta on v.idruta = ruta.idruta
            where t.idcierre=%d
            and p.tipo=3
            and x.idestacion=t.idestacion
            and t.estado=0
            group by fp.entracierre,fp.desformapago,v.idviaje,v.fecha,x.hora,p.nombre
        union
        select v.idviaje,v.fecha,x.hora,p.nombre,count(*) as cantidad
            ,sum(t.declarado) as monto
            ,0 as montoiva
            ,'Sin Socio' as socio,'Monto de Transferencias' as tipo,fp.desformapago
            ,ruta.abreviatura,fp.entracierre, 0 as saltran
            ,0 as iva
            ,0 as ivapos
            from encomienda t inner join producto p
            on t.idproducto=p.idproducto
            inner join viaje v
            on t.idviaje=v.idviaje
            inner join viajexesta x
            on v.idviaje=x.idviaje
            inner join formapago fp
            on t.idformapago=fp.idformapago
            left join ruta on v.idruta = ruta.idruta
            where t.idcierre=%d
            and p.tipo=3
            and x.idestacion=t.idestacion
            and t.estado=0
            group by fp.entracierre,fp.desformapago,v.idviaje,v.fecha,x.hora,p.nombre
        union
        select v.idviaje,v.fecha,x.hora,'Retiro Transferencia' as nombre,count(*) as cantidad,sum(-t.declarado) as monto
            ,0 as montopos
            ,'Sin Socio' as socio,'Salida por Transferencia' as tipo,'' as desformapago
            ,ruta.abreviatura,fp.entracierre, 1 as saltran
            ,0 as iva
            ,0 as ivapos
            from encomienda t inner join producto p
            on t.idproducto=p.idproducto
            inner join viaje v
            on t.idviaje=v.idviaje
            inner join viajexesta x
            on v.idviaje=x.idviaje
            inner join formapago fp
            on t.idformapago=fp.idformapago
            left join ruta on v.idruta = ruta.idruta
            where t.entcierre=%d
            and p.tipo=3
            and x.idestacion=t.estaretiro
            and t.estado=0
            and p.tipo=3
            group by fp.entracierre,fp.desformapago,v.idviaje,v.fecha,x.hora,p.nombre  
        union
        select v.idviaje,v.fecha,x.hora,p.nombre,count(*) as cantidad
            ,sum(t.monto) as monto
            ,0 as montopos
            ,'Sin Socio' as socio
            ,'Encomiendas' as tipo
            ,concat(fp.desformapago,' Entregado') as desformapago
            ,ruta.abreviatura
            ,fp.sumaentcierre as entracierre
            , 0 as saltran
            ,sum(t.iva) as iva
            ,0 as ivapos
            from encomienda t inner join producto p
            on t.idproducto=p.idproducto
            inner join viaje v
            on t.idviaje=v.idviaje
            inner join viajexesta x
            on v.idviaje=x.idviaje
            inner join formapago fp
            on t.idformapago=fp.idformapago
            left join ruta on v.idruta = ruta.idruta
            where t.entcierre=%d
            and x.idestacion=t.estaretiro
            and t.estado=0
            group by fp.sumaentcierre,fp.desformapago,v.idviaje,v.fecha,x.hora,p.nombre
            order by 10,9,8,2,3,4  
        ",
        $this->idCierre,
        $this->idCierre,
        $this->idCierre,
        $this->idCierre,
        $this->idCierre,
        $this->idCierre
        );
        //echo $sql;

         }
        //echo "<br>" . $sql . "<br><br>";
        $res=$link->bdEjecutar($sql);
        return $res;
    }
    function Cargar(){
        $sql = sprintf("select *
            from cierre
            where idcierre=%d",$this->idCierre);
        $link = new tiqmysql();
        $res = $link->bdEjecutar($sql);
        if($link->bdCantLineas($res)>0){
             $linea = mysqli_fetch_array($res);    
             $this->fecha = $linea["fecha"];
             $this->fechai = $linea["desde"];
             $this->fechaf = $linea["hasta"];
             $this->estacion->idEstacion=$linea["idestacion"];
             $this->impresora->idImpresora=$linea["idimpresora"];
             $this->usuario->idUsuario=$linea["idusuario"];
             $sql=sprintf("select * from dcierre where idcierre=%d",$this->idCierre);
             $res2=$link->bdEjecutar($sql);
             $this->ingresos=array();
             $this->egresos=array();
             while($lin = mysqli_fetch_array($res2)){
                 if($lin["idingreso"]==1)
                   array_push($this->ingresos,array($lin["descripcion"],$lin["monto"]));
                 else
                   array_push($this->egresos,array($lin["descripcion"],$lin["monto"]));
              }
              return true;
        }
        else
          return false;
    }
    function Guardar(){
        $link = new tiqmysql();
        $link->bdAbreTran();
        $sql=sprintf("insert into cierre (idestacion,idimpresora,fecha,desde,hasta,idusuario)
          values (%d,%d,'%s','%s','%s',%d)",
          $this->estacion->idEstacion,
          $this->impresora->idImpresora,
          date("Y-m-d H:i:s",time()),
          $this->fechai,
          $this->fechaf,
          $this->usuario->idUsuario
          );
          $res = $link->bdEjecutar($sql);

		  $this->idCierre=$link->bdUltimoId();

        for($x=0;$x<count($this->ingresos);$x++){
              $sql=sprintf("insert into dcierre (idcierre,cantidad,descripcion,monto,idtipolin,idingreso)
                 values (%d,%d,'%s',%d,%d,%d)",
                 $this->idCierre,
                 1,
                 $this->ingresos[$x][0],
                 $this->ingresos[$x][1],
                 1,1
                 );
                 $link->bdEjecutar($sql);
        }
        for($x=0;$x<count($this->egresos);$x++){
              $sql=sprintf("insert into dcierre (idcierre,cantidad,descripcion,monto,idtipolin,idingreso)
                 values (%d,%d,'%s',%d,%d,%d)",
                 $this->idCierre,
                 1,
                 $this->egresos[$x][0],
                 $this->egresos[$x][1],
                 2,2
                 );
                 $link->bdEjecutar($sql);
        }
        $sql=sprintf("update encomienda set idcierre=%d 
                  where idestacion=%d
                  and idimpresora=%d
                  and fecha between '%s' and '%s' 
                 and idcierre=0",
                 $this->idCierre,
                 $this->estacion->idEstacion,
                 $this->impresora->idImpresora,
                 $this->fechai,
                 $this->fechaf);

        $link->bdEjecutar($sql);

$sql=sprintf("update encomienda set entcierre=%d 
                  where estaretiro=%d
                  and entimpresora=%d
                  and fecha between '%s' and '%s' 
                 and entcierre=0 
                 and idproducto in (
                    select p.idproducto 
                    from producto p
                    where p.tipo=3)",
                 $this->idCierre,
                 $this->estacion->idEstacion,
                 $this->impresora->idImpresora,
                 $this->fechai,
                 $this->fechaf);

        $link->bdEjecutar($sql);

        $sql=sprintf("update tiquete set idcierre=%d 
                  where idestacion=%d
                  and idimpresora=%d
                  and fecha between '%s' and '%s' 
                 and idcierre=0",
                 $this->idCierre,
                 $this->estacion->idEstacion,
                 $this->impresora->idImpresora,
                 $this->fechai,
                 $this->fechaf);

        $link->bdEjecutar($sql);
        


        $sql=sprintf("update encomienda set entcierre=%d 
                  where estaretiro=%d
                  and entimpresora=%d
                  and fecharetiro between '%s' and '%s 23:59:59' 
                  and idformapago in 
                      (select idformapago from formapago where sumaentcierre = 1)
                  and entcierre=0
                  and not idproducto in (
                    select p.idproducto 
                    from producto p
                    where p.tipo=3)",
                 $this->idCierre,
                 $this->estacion->idEstacion,
                 $this->impresora->idImpresora,
                 $this->fechai,
                 $this->fechaf);

        $link->bdEjecutar($sql);

		$this->bitacora->Bitacorizar("Crea cierre ".$this->idCierre);
        $link->bdCierraTran();
        return $this->idCierre;
    }
    //Hereda el objeto bitacacora    
	function HeredaBitacora($pbit){
		$pbit->nivel++;		
		$this->bitacora=$pbit;
        $this->bitacora->obPadre=$this;
	}
    function ToString(){
        return "";
    }

    function DevCierre(){
        $link = new tiqmysql();
        $sql = "select c.idCierre,c.Hasta,i.nombre as Impresora,u.nombre as Usuario
         from cierre c 
         inner join impresora i on i.idImpresora=c.idImpresora
         inner join usuario u on u.idusuario=c.idUsuario
         order by c.hasta desc limit 200 ";
       $res=$link->bdEjecutar($sql);
       return $res;

    }

    function TodosCierres($fechai,$fechaf){
        $link = new tiqmysql();
        $sql = "select b.*,usuario.nombrelargo,impresora.nombre as impresora,estacion.nombre as estacion
                    from (
                        select idimpresora,idestacion,fecha,idusuario,idcierre,sum(cantiq) as cantiq,
                        sum(montiq) as montiq,sum(canenc) as canenc,sum(monenc) as monenc
                        from (
                            select t.idimpresora,t.idestacion,t.fecha,t.idusuario,t.idcierre,
                            count(*) as cantiq,sum(t.monto) as montiq,0 as canenc,0 as monenc,1 as tipo
                            from tiquete t
                            where t.fecha between '".$fechai."' and '".$fechaf."'
                            and t.estado=0
                            group by t.idimpresora,t.idestacion,t.fecha,t.idusuario,t.idcierre
                        union
                            select t.idimpresora,t.idestacion,t.fecha,t.idusuario,t.idcierre,0 as cantiq,
                            0 as montiq,count(*) as canenc,sum(t.monto) as monenc,2 as tipo
                            from encomienda t inner join producto p
                            on t.idproducto=p.idproducto
                            where t.fecha between '".$fechai."' and '".$fechaf."'
                            and t.estado=0
                            and p.tipo=2
                            group by t.idimpresora,t.idestacion,t.fecha,t.idcierre,t.idusuario
                        union
                            select t.idimpresora,t.idestacion,t.fecha,t.idusuario,t.idcierre,0 as cantiq,0 as montiq,
                            count(*) as canenc,sum(t.monto) as monenc,3 as tipo
                            from encomienda t inner join producto p
                            on t.idproducto=p.idproducto
                            where t.fecha between '".$fechai."' and '".$fechaf."'
                            and t.estado=0
                            and p.tipo=3
                            group by t.idimpresora,t.idestacion,t.fecha,t.idcierre,t.idusuario
                        union
                            select t.idimpresora,t.idestacion,t.fecha,t.idusuario,t.idcierre,0 as cantiq,0 as montiq,
                            count(*) as canenc,sum(t.declarado) as monenc,4 as tipo
                            from encomienda t inner join producto p
                            on t.idproducto=p.idproducto
                            where t.fecha between '".$fechai."' and '".$fechaf."'
                            and t.estado=0
                            and p.tipo=3
                            group by t.idimpresora,t.idestacion,t.fecha,t.idcierre,t.idusuario
                        union
                            select t.entimpresora,t.estaretiro,
                            concat(substr(t.fecharetiro,1,10),' 00:00:00'),t.idusuario,t.idcierre,
                            0 as cantiq,0 as montiq,count(*) as canenc,sum(-t.declarado) as monenc,5 as tipo
                            from encomienda t inner join producto p
                            on t.idproducto=p.idproducto
                            where t.fecharetiro between '".$fechai."' and '".$fechaf." 23:59:59'
                            and t.estado=0
                            and p.tipo=3
                            group by t.entimpresora,t.estaretiro,3,t.entcierre,t.idusuario  
                        union
                            select t.entimpresora,t.estaretiro,
                            concat(substr(t.fecharetiro,1,10),' 00:00:00'),t.idusuario,t.entcierre,
                            0 as cantiq,0 as montiq,count(*) as canenc,sum(t.monto) as monenc,6 as tipo
                            from encomienda t inner join producto p
                            on t.idproducto=p.idproducto
                            inner join formapago fp
                            on t.idformapago=fp.idformapago
                            where t.fecharetiro between '".$fechai."' and '".$fechaf." 23:59:59'
                            and fp.sumaentcierre = 1
                            and t.estado=0
                            and p.tipo=2
                            group by t.entimpresora,t.estaretiro,3,t.entcierre,t.idusuario
                    ) a group by 1,2,3,4,5
                ) b
                inner join usuario on b.idusuario = usuario.idusuario
                inner join estacion on b.idestacion = estacion.idestacion
                inner join impresora on b.idimpresora = impresora.idimpresora
                where b.idimpresora != 5 order by fecha,impresora.nombre,estacion.nombre,idcierre desc;";
       //echo $sql;
       $res=$link->bdEjecutar($sql);
       return $res;
    }

}

?>
