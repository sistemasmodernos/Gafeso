<?php
include_once('mysql.php');
class TablAbajo {

	function PorcentajeVenta(){
    $link= new tiqmysql();
    $sql="
		select 
		count(*) as total,
		sum( case when date_format(fecha, '%m-%Y') = date_format(now(), '%m-%Y') then 1 else 0 end ) as mes,
		sum( case when date_format(fecha, '%Y') = date_format(now(), '%Y') then 1 else 0 end ) as anno,
		sum(case when idimpresora = 5 then 1 else 0 end) as wtotal,
		sum( case when date_format(fecha, '%m-%Y') = date_format(now(), '%m-%Y') then case when idimpresora = 5 then 1 else 0 end else 0 end ) as wmes,
		sum( case when date_format(fecha, '%Y') = date_format(now(), '%Y') then case when idimpresora = 5 then 1 else 0 end else 0 end ) as wanno,
		sum(case when MONTH(Fecha) = MONTH(DATE_SUB(CURDATE(), INTERVAL DAY(CURDATE()) DAY)) AND
				YEAR(Fecha) = YEAR(DATE_SUB(CURDATE(), INTERVAL DAY(CURDATE()) DAY)) then 1 else 0 end) as mesant,
		sum(case when MONTH(Fecha) = MONTH(DATE_SUB(CURDATE(), INTERVAL DAY(CURDATE()) DAY)) AND
				YEAR(Fecha) = YEAR(DATE_SUB(CURDATE(), INTERVAL DAY(CURDATE()) DAY)) then case when idimpresora = 5 then 1 else 0 end else 0 end) as wmesant
		from 
		(    
			select idparada1,idparada2,idimpresora,fecha from musoccr.tiquete where fecha >= '2014-05-19' and factura != 'APARTA' and estado = 0
		) a		";
    $res=$link->bdEjecutar($sql);
	$xtotal = 0;
	$xanno = 0;
	$xmes = 0;
	$tabla = "<h3 class='alineado'>Porcentaje de la compra total</h3>";
	if($link->bdCantLineas($res)>0){
		$tabla .= "<table border='1' class='centrado' style='border-collapse:collapse'> ";
		$tabla .= "<tr> ";
		$tabla .= "<th>Periodo</th> ";
		$tabla .= "<th>Venta Total</th> ";
		$tabla .= "<th>Venta Web</th> ";
		$tabla .= "<th>Porcentaje</th> ";
		$tabla .= "</tr> ";
        while($linea =  mysqli_fetch_array($res)){
			$tabla .= "<tr> ";
			$tabla .= "<td>Este Mes</td> ";
			$tabla .= "<td class='dere'>" . number_format($linea["mes"], 0, '.', ',') . "</td> ";
			$tabla .= "<td class='dere'>" . number_format($linea["wmes"], 0, '.', ',') . "</td> ";
			$tabla .= "<td class='dere'>" . number_format($linea["wmes"] / $linea["mes"] * 100, 2, '.', ',') . "%</td> ";
			$tabla .= "</tr> ";
			$tabla .= "<tr> ";
			$tabla .= "<td>Mes Anterior</td> ";
			$tabla .= "<td class='dere'>" . number_format($linea["mesant"], 0, '.', ',') . "</td> ";
			$tabla .= "<td class='dere'>" . number_format($linea["wmesant"], 0, '.', ',') . "</td> ";
			$tabla .= "<td class='dere'>" . number_format($linea["wmesant"] / $linea["mesant"] * 100, 2, '.', ',') . "%</td> ";
			$tabla .= "</tr> ";
			$tabla .= "<tr> ";
			$tabla .= "<td>Este A&ntilde;o</td> ";
			$tabla .= "<td class='dere'>" . number_format($linea["anno"], 0, '.', ',') . "</td> ";
			$tabla .= "<td class='dere'>" . number_format($linea["wanno"], 0, '.', ',') . "</td> ";
			$tabla .= "<td class='dere'>" . number_format($linea["wanno"] / $linea["anno"] * 100, 2, '.', ',') . "%</td> ";
			$tabla .= "</tr> ";
			$tabla .= "<td>Total</td> ";
			$tabla .= "<td class='dere'>" . number_format($linea["total"], 0, '.', ',') . "</td> ";
			$tabla .= "<td class='dere'>" . number_format($linea["wtotal"], 0, '.', ',') . "</td> ";
			$tabla .= "<td class='dere'>" . number_format($linea["wtotal"] / $linea["total"] * 100, 2, '.', ',') . "%</td> ";
			$tabla .= "</tr> ";
		}
		$tabla .= "</table>";
	}
    return $tabla;
	}

	function Ocupacion(){
    $link= new tiqmysql();
    $sql="
		select  sum(cantpas) as tcapa,sum(cuenta) as tocup,
		sum( case when date_format(x.fecha, '%m-%Y') = date_format(now(), '%m-%Y') then cantpas else 0 end ) as mescapa,
		sum( case when date_format(x.fecha, '%m-%Y') = date_format(now(), '%m-%Y') then cuenta else 0 end ) as mesocup,
		sum( case when date_format(x.fecha, '%Y') = date_format(now(), '%Y') then cantpas else 0 end ) as annocapa,
		sum( case when date_format(x.fecha, '%Y') = date_format(now(), '%Y') then cuenta else 0 end ) as annoocup,
		sum(case when MONTH(x.Fecha) = MONTH(DATE_SUB(CURDATE(), INTERVAL DAY(CURDATE()) DAY)) AND
				YEAR(x.Fecha) = YEAR(DATE_SUB(CURDATE(), INTERVAL DAY(CURDATE()) DAY)) then cantpas else 0 end) as mesantcapa,
		sum(case when MONTH(x.Fecha) = MONTH(DATE_SUB(CURDATE(), INTERVAL DAY(CURDATE()) DAY)) AND
				YEAR(x.Fecha) = YEAR(DATE_SUB(CURDATE(), INTERVAL DAY(CURDATE()) DAY)) then cuenta else 0 end) as mesantocup
		from (
			select viaje.idviaje,viaje.fecha,Bus.cantpas,a.cuenta
			from musoccr.viaje
			inner join musoccr.Bus on viaje.idbus = Bus.idbus
			inner join (
			select viaje.idviaje,count(*) as cuenta
			from musoccr.tiquete
			inner join musoccr.viaje on tiquete.idviaje = viaje.idviaje
			where factura != 'APARTA' and estado = 0 and viaje.fecha < curdate()
			group by viaje.idviaje
		) a on viaje.idviaje = a.idviaje
		) x";
    $res=$link->bdEjecutar($sql);
	$xtotal = 0;
	$xanno = 0;
	$xmes = 0;
	$tabla = "<h3 class='alineado'>Ocupaci&oacute;n Promedio</h3>";
	if($link->bdCantLineas($res)>0){
		$tabla .= "<table border='1' class='centrado' style='border-collapse:collapse'> ";
		$tabla .= "<tr> ";
		$tabla .= "<th>Periodo</th> ";
		$tabla .= "<th>Asientos Disponibles</th> ";
		$tabla .= "<th>Asientos Ocupados</th> ";
		$tabla .= "<th>Porcentaje</th> ";
		$tabla .= "</tr> ";
        while($linea =  mysqli_fetch_array($res)){
			$tabla .= "<tr> ";
			$tabla .= "<td>Este Mes *</td> ";
			$tabla .= "<td class='dere'>" . number_format($linea["mescapa"], 0, '.', ',') . "</td> ";
			$tabla .= "<td class='dere'>" . number_format($linea["mesocup"], 0, '.', ',') . "</td> ";
			if ($linea["mescapa"] != 0){
				$tabla .= "<td class='dere'>" . number_format($linea["mesocup"] / $linea["mescapa"] * 100, 2, '.', ',') . "%</td> ";
			}else{
				$tabla .= "<td class='dere'>&nbsp;</td> ";
			}
			$tabla .= "</tr> ";
			$tabla .= "<tr> ";
			$tabla .= "<td>Mes Anterior</td> ";
			$tabla .= "<td class='dere'>" . number_format($linea["mesantcapa"], 0, '.', ',') . "</td> ";
			$tabla .= "<td class='dere'>" . number_format($linea["mesantocup"], 0, '.', ',') . "</td> ";
			if ($linea["mesantcapa"] != 0){
				$tabla .= "<td class='dere'>" . number_format($linea["mesantocup"] / $linea["mesantcapa"] * 100, 2, '.', ',') . "%</td> ";
			}else{
				$tabla .= "<td class='dere'>&nbsp;</td> ";
			}
			$tabla .= "</tr> ";
			$tabla .= "<tr> ";
			$tabla .= "<td>Este A&ntilde;o</td> ";
			$tabla .= "<td class='dere'>" . number_format($linea["annocapa"], 0, '.', ',') . "</td> ";
			$tabla .= "<td class='dere'>" . number_format($linea["annoocup"], 0, '.', ',') . "</td> ";
			if ($linea["annocapa"] != 0){
				$tabla .= "<td class='dere'>" . number_format($linea["annoocup"] / $linea["annocapa"] * 100, 2, '.', ',') . "%</td> ";
			}else{
				$tabla .= "<td class='dere'>&nbsp;</td> ";
			}
			$tabla .= "</tr> ";
			$tabla .= "<td>Total</td> ";
			$tabla .= "<td class='dere'>" . number_format($linea["tcapa"], 0, '.', ',') . "</td> ";
			$tabla .= "<td class='dere'>" . number_format($linea["tocup"], 0, '.', ',') . "</td> ";
			if ( $linea["tcapa"] != 0){
				$tabla .= "<td class='dere'>" . number_format($linea["tocup"] / $linea["tcapa"] * 100, 2, '.', ',') . "%</td> ";
			}else{
				$tabla .= "<td class='dere'>&nbsp;</td> ";
			}
			$tabla .= "</tr> ";
		}
		$tabla .= "</table>";
		$tabla .= "<p class='alineado' style='margin-top: 0;'>* Se incluyen viajes de ayer hacia atr&aacute;s</p>";
	}
    return $tabla;
	}
	
	function PorDiaDeLaSemana(){
    $link= new tiqmysql();
    $sql="
		select diasem,count(*) as cantidad,sum(cuenta) as total,
		sum( case when date_format(fecha, '%m-%Y') = date_format(now(), '%m-%Y') then cuenta else 0 end ) as mes,
		sum( case when date_format(fecha, '%Y') = date_format(now(), '%Y') then cuenta else 0 end ) as anno,
		sum( case when date_format(fecha, '%m-%Y') = date_format(now(), '%m-%Y') then 1 else 0 end ) as mescant,
		sum( case when date_format(fecha, '%Y') = date_format(now(), '%Y') then 1 else 0 end ) as annocant,
		sum(case when MONTH(Fecha) = MONTH(DATE_SUB(CURDATE(), INTERVAL DAY(CURDATE()) DAY)) AND
				YEAR(Fecha) = YEAR(DATE_SUB(CURDATE(), INTERVAL DAY(CURDATE()) DAY)) then cuenta else 0 end) as mesant,
		sum(case when MONTH(Fecha) = MONTH(DATE_SUB(CURDATE(), INTERVAL DAY(CURDATE()) DAY)) AND
				YEAR(Fecha) = YEAR(DATE_SUB(CURDATE(), INTERVAL DAY(CURDATE()) DAY)) then 1 else 0 end) as cmesant
		from 
		(
			select fecha,CONCAT(ELT(WEEKDAY(b.fecha) + 1, '1- Lunes', '2- Martes', '3- Miercoles', '4- Jueves', '5- Viernes', '6- Sabado', '7- Domingo')) AS diasem,
			cuenta 
			from (
				select fecha,count(*) as cuenta
				from musoccr.tiquete 
				inner join musoccr.parada on tiquete.idparada1 = parada.idparada 
				where factura != 'APARTA' and estado = 0
				group by fecha
			) b
		) a
		group by diasem";
    $res=$link->bdEjecutar($sql);
	$xtotal = 0;
	$xanno = 0;
	$xmes = 0;
	$xmesant = 0;
	$ctotal = 0;
	$canno = 0;
	$cmes = 0;
	$cmesant = 0;
	$tabla = "<h3 class='alineado'>Por Día de Compra</h3>";
	if($link->bdCantLineas($res)>0){
		$tabla .= "<table border='1' class='centrado' style='border-collapse:collapse'> ";
		$tabla .= "<tr> ";
		$tabla .= "<th>&nbsp;</th> ";
		$tabla .= "<th colspan='3'>Este Mes</th> ";
		$tabla .= "<th colspan='3'>Mes Anterior</th> ";
		$tabla .= "<th colspan='3'>Este a&ntilde;o</th> ";
		$tabla .= "<th colspan='3'>Total</th> ";
		$tabla .= "</tr> ";
		$tabla .= "<tr> ";
		$tabla .= "<th>D&iacute;a</th> ";
		$tabla .= "<th>Boletos</th> ";
		$tabla .= "<th>Días</th> ";
		$tabla .= "<th>Promedio</th> ";
		$tabla .= "<th>Boletos</th> ";
		$tabla .= "<th>Días</th> ";
		$tabla .= "<th>Promedio</th> ";
		$tabla .= "<th>Boletos</th> ";
		$tabla .= "<th>Días</th> ";
		$tabla .= "<th>Promedio</th> ";
		$tabla .= "<th>Boletos</th> ";
		$tabla .= "<th>Días</th> ";
		$tabla .= "<th>Promedio</th> ";
		$tabla .= "</tr> ";
        while($linea =  mysqli_fetch_array($res)){
			$tabla .= "<tr> ";
			$tabla .= "<td>" . $linea["diasem"] . "</td> ";
			$tabla .= "<td class='dere'>" . number_format($linea["mes"], 0, '.', ',') . "</td> ";
			$tabla .= "<td class='dere'>" . number_format($linea["mescant"], 0, '.', ',') . "</td> ";
			if ($linea["mescant"] == 0){
				$tabla .= "<td class='dere'>&nbsp;</td> ";
			}else{
				$tabla .= "<td class='dere'>" . number_format($linea["mes"]/$linea["mescant"] , 2, '.', ',') . "</td> ";
			}
			$tabla .= "<td class='dere'>" . number_format($linea["mesant"], 0, '.', ',') . "</td> ";
			$tabla .= "<td class='dere'>" . number_format($linea["cmesant"], 0, '.', ',') . "</td> ";
			if ($linea["cmesant"] == 0){
				$tabla .= "<td class='dere'>&nbsp;</td> ";
			}else{
				$tabla .= "<td class='dere'>" . number_format($linea["mesant"]/$linea["cmesant"] , 2, '.', ',') . "</td> ";
			}
			$tabla .= "<td class='dere'>" . number_format($linea["anno"], 0, '.', ',') . "</td> ";
			$tabla .= "<td class='dere'>" . number_format($linea["annocant"], 0, '.', ',') . "</td> ";
			if ($linea["annocant"] == 0){
				$tabla .= "<td class='dere'>&nbsp;</td> ";
			}else{
				$tabla .= "<td class='dere'>" . number_format($linea["anno"]/$linea["annocant"] , 2, '.', ',') . "</td> ";
			}
			$tabla .= "<td class='dere'>" . number_format($linea["total"], 0, '.', ',') . "</td> ";
			$tabla .= "<td class='dere'>" . number_format($linea["cantidad"], 0, '.', ',') . "</td> ";
			if ($linea["cantidad"] == 0){
				$tabla .= "<td class='dere'>&nbsp;</td> ";
			}else{
				$tabla .= "<td class='dere'>" . number_format($linea["total"]/$linea["cantidad"] , 2, '.', ',') . "</td> ";
			}
			$tabla .= "</tr> ";
			$xtotal += $linea["total"];
			$xanno += $linea["anno"];
			$xmes += $linea["mes"];
			$xmesant += $linea["mesant"];
			$ctotal += $linea["cantidad"];
			$canno += $linea["annocant"];
			$cmes += $linea["mescant"];
			$cmesant += $linea["cmesant"];
		}
		$tabla .= "<tr> ";
		$tabla .= "<th>Total</td> ";
		$tabla .= "<th class='dere'>" . number_format($xmes, 0, '.', ',') . "</th> ";
		$tabla .= "<th class='dere'>" . number_format($cmes, 0, '.', ',') . "</th> ";
		$tabla .= "<th class='dere'>" . number_format($xmes/$cmes , 2, '.', ',') . "</th> ";
		$tabla .= "<th class='dere'>" . number_format($xmesant, 0, '.', ',') . "</th> ";
		$tabla .= "<th class='dere'>" . number_format($cmesant, 0, '.', ',') . "</th> ";
		$tabla .= "<th class='dere'>" . number_format($xmesant/$cmesant, 2, '.', ',') . "</th> ";
		$tabla .= "<th class='dere'>" . number_format($xanno, 0, '.', ',') . "</th> ";
		$tabla .= "<th class='dere'>" . number_format($canno, 0, '.', ',') . "</th> ";
		$tabla .= "<th class='dere'>" . number_format($xanno/$canno , 2, '.', ',') . "</th> ";
		$tabla .= "<th class='dere'>" . number_format($xtotal, 0, '.', ',') . "</th> ";
		$tabla .= "<th class='dere'>" . number_format($ctotal, 0, '.', ',') . "</th> ";
		$tabla .= "<th class='dere'>" . number_format($xtotal/$ctotal, 2, '.', ',') . "</th> ";
		$tabla .= "</tr> ";
			$tabla .= "</table>";
	}
    return $tabla;
	}

	function PorParada(){
    $link= new tiqmysql();
    $sql="
		select nombre,
		count(*) as total,
		sum( case when date_format(fecha, '%m-%Y') = date_format(now(), '%m-%Y') then 1 else 0 end ) as mes,
		sum( case when date_format(fecha, '%Y') = date_format(now(), '%Y') then 1 else 0 end ) as anno,
		sum(monto) as mtotal,
		sum( case when date_format(fecha, '%m-%Y') = date_format(now(), '%m-%Y') then monto else 0 end ) as mmes,
		sum( case when date_format(fecha, '%Y') = date_format(now(), '%Y') then monto else 0 end ) as manno,
		sum(case when MONTH(Fecha) = MONTH(DATE_SUB(CURDATE(), INTERVAL DAY(CURDATE()) DAY)) AND
				YEAR(Fecha) = YEAR(DATE_SUB(CURDATE(), INTERVAL DAY(CURDATE()) DAY)) then 1 else 0 end) as mesant,
		sum(case when MONTH(Fecha) = MONTH(DATE_SUB(CURDATE(), INTERVAL DAY(CURDATE()) DAY)) AND
				YEAR(Fecha) = YEAR(DATE_SUB(CURDATE(), INTERVAL DAY(CURDATE()) DAY)) then monto else 0 end) as mmesant
		from 
		(    
			select idimpresora,parada.nombre,fecha,monto 
			from musoccr.tiquete 
			inner join musoccr.parada on tiquete.idparada1 = parada.idparada 
			where factura != 'APARTA' and estado = 0
		) a
		group by nombre	";
    $res=$link->bdEjecutar($sql);
	$xtotal = 0;
	$xanno = 0;
	$xmes = 0;
	$ctotal = 0;
	$canno = 0;
	$cmes = 0;
	$cmesant = 0;
	$xmesant = 0;
	$tabla = "<h3 class='alineado'>Por Parada</h3>";
	if($link->bdCantLineas($res)>0){
		$tabla .= "<table border='1' class='centrado' style='border-collapse:collapse'> ";
		$tabla .= "<tr> ";
		$tabla .= "<th>&nbsp;</th> ";
		$tabla .= "<th colspan='2'>Este Mes</th> ";
		$tabla .= "<th colspan='2'>Mes Anterior</th> ";
		$tabla .= "<th colspan='2'>Este a&ntilde;o</th> ";
		$tabla .= "<th colspan='2'>Total</th> ";
		$tabla .= "</tr> ";
		$tabla .= "<tr> ";
		$tabla .= "<th>Parada</th> ";
		$tabla .= "<th>Tiquetes</th> ";
		$tabla .= "<th>Monto</th> ";
		$tabla .= "<th>Tiquetes</th> ";
		$tabla .= "<th>Monto</th> ";
		$tabla .= "<th>Tiquetes</th> ";
		$tabla .= "<th>Monto</th> ";
		$tabla .= "<th>Tiquetes</th> ";
		$tabla .= "<th>Monto</th> ";
		$tabla .= "</tr> ";
        while($linea =  mysqli_fetch_array($res)){
			$tabla .= "<tr> ";
			$tabla .= "<td>" . $linea["nombre"] . "</td> ";
			$tabla .= "<td class='dere'>" . number_format($linea["mes"], 0, '.', ',') . "</td> ";
			$tabla .= "<td class='dere'>" . number_format($linea["mmes"], 2, '.', ',') . "</td> ";
			$tabla .= "<td class='dere'>" . number_format($linea["mesant"], 0, '.', ',') . "</td> ";
			$tabla .= "<td class='dere'>" . number_format($linea["mmesant"], 2, '.', ',') . "</td> ";
			$tabla .= "<td class='dere'>" . number_format($linea["anno"], 0, '.', ',') . "</td> ";
			$tabla .= "<td class='dere'>" . number_format($linea["manno"], 2, '.', ',') . "</td> ";
			$tabla .= "<td class='dere'>" . number_format($linea["total"], 0, '.', ',') . "</td> ";
			$tabla .= "<td class='dere'>" . number_format($linea["mtotal"], 2, '.', ',') . "</td> ";
			$tabla .= "</tr> ";
			$xtotal += $linea["total"];
			$xanno += $linea["anno"];
			$xmes += $linea["mes"];
			$ctotal += $linea["mtotal"];
			$canno += $linea["manno"];
			$cmes += $linea["mmes"];
			$xmesant += $linea["mesant"];
			$cmesant += $linea["mmesant"];
		}
		$tabla .= "<tr> ";
		$tabla .= "<th>Total</td> ";
		$tabla .= "<th class='dere'>" . number_format($xmes, 0, '.', ',') . "</th> ";
		$tabla .= "<th class='dere'>" . number_format($cmes, 2, '.', ',') . "</th> ";
		$tabla .= "<th class='dere'>" . number_format($xmesant, 0, '.', ',') . "</th> ";
		$tabla .= "<th class='dere'>" . number_format($cmesant, 2, '.', ',') . "</th> ";
		$tabla .= "<th class='dere'>" . number_format($xanno, 0, '.', ',') . "</th> ";
		$tabla .= "<th class='dere'>" . number_format($canno, 2, '.', ',') . "</th> ";
		$tabla .= "<th class='dere'>" . number_format($xtotal, 0, '.', ',') . "</th> ";
		$tabla .= "<th class='dere'>" . number_format($ctotal, 2, '.', ',') . "</th> ";
		$tabla .= "</tr> ";
		$tabla .= "</table>";
	}
    return $tabla;
	}
	
	function PorRuta(){
    $link= new tiqmysql();
    $sql="
		select ruta.nombre as ruta,count(*) as cuenta,sum(monto) as cuentot,
		sum( case when date_format(tiquete.fecha, '%m-%Y') = date_format(now(), '%m-%Y') then 1 else 0 end ) as mes,
		sum( case when date_format(tiquete.fecha, '%m-%Y') = date_format(now(), '%m-%Y') then monto else 0 end ) as tmes,
		sum( case when date_format(tiquete.fecha, '%Y') = date_format(now(), '%Y') then 1 else 0 end ) as anno,
		sum( case when date_format(tiquete.fecha, '%Y') = date_format(now(), '%Y') then monto else 0 end ) as tanno,
		sum(case when MONTH(tiquete.Fecha) = MONTH(DATE_SUB(CURDATE(), INTERVAL DAY(CURDATE()) DAY)) AND
				YEAR(tiquete.Fecha) = YEAR(DATE_SUB(CURDATE(), INTERVAL DAY(CURDATE()) DAY)) then 1 else 0 end) as mesant,
		sum(case when MONTH(tiquete.Fecha) = MONTH(DATE_SUB(CURDATE(), INTERVAL DAY(CURDATE()) DAY)) AND
				YEAR(tiquete.Fecha) = YEAR(DATE_SUB(CURDATE(), INTERVAL DAY(CURDATE()) DAY)) then monto else 0 end) as tmesant
		from musoccr.tiquete
		inner join musoccr.viaje on tiquete.idviaje = viaje.idviaje
		inner join musoccr.ruta on viaje.idruta = ruta.idruta
		where factura != 'APARTA' and estado = 0
		group by ruta.nombre";
    $res=$link->bdEjecutar($sql);
	$xtotal = 0;
	$xanno = 0;
	$xmes = 0;
	$xmesant = 0;
	$ytotal = 0;
	$yanno = 0;
	$ymes = 0;
	$ymesant = 0;
	$tabla = "<h3 class='alineado'>Por Ruta</h3>";
	if($link->bdCantLineas($res)>0){
		$tabla .= "<table border='1' class='centrado' style='border-collapse:collapse'> ";
		$tabla .= "<tr> ";
		$tabla .= "<th>&nbsp;</th> ";
		$tabla .= "<th colspan='2'>Este Mes</th> ";
		$tabla .= "<th colspan='2'>Mes Anterior</th> ";
		$tabla .= "<th colspan='2'>Este a&ntilde;o</th> ";
		$tabla .= "<th colspan='2'>Total</th> ";
		$tabla .= "</tr> ";
		$tabla .= "<tr> ";
		$tabla .= "<th>Ruta</th> ";
		$tabla .= "<th>Boletos</th> ";
		$tabla .= "<th>Monto</th> ";
		$tabla .= "<th>Boletos</th> ";
		$tabla .= "<th>Monto</th> ";
		$tabla .= "<th>Boletos</th> ";
		$tabla .= "<th>Monto</th> ";
		$tabla .= "<th>Boletos</th> ";
		$tabla .= "<th>Monto</th> ";
		$tabla .= "</tr> ";
        while($linea =  mysqli_fetch_array($res)){
			$tabla .= "<tr> ";
			$tabla .= "<td>" . $linea["ruta"] . "</td> ";
			$tabla .= "<td class='dere'>" . number_format($linea["mes"], 0, '.', ',') . "</td> ";
			$tabla .= "<td class='dere'>" . number_format($linea["tmes"], 2, '.', ',') . "</td> ";
			$tabla .= "<td class='dere'>" . number_format($linea["mesant"], 0, '.', ',') . "</td> ";
			$tabla .= "<td class='dere'>" . number_format($linea["tmesant"], 2, '.', ',') . "</td> ";
			$tabla .= "<td class='dere'>" . number_format($linea["anno"], 0, '.', ',') . "</td> ";
			$tabla .= "<td class='dere'>" . number_format($linea["tanno"], 2, '.', ',') . "</td> ";
			$tabla .= "<td class='dere'>" . number_format($linea["cuenta"], 0, '.', ',') . "</td> ";
			$tabla .= "<td class='dere'>" . number_format($linea["cuentot"], 2, '.', ',') . "</td> ";
			$tabla .= "</tr> ";
			$xtotal += $linea["cuenta"];
			$xanno += $linea["anno"];
			$xmes += $linea["mes"];
			$xmesant += $linea["mesant"];
			$ytotal += $linea["cuentot"];
			$yanno += $linea["tanno"];
			$ymes += $linea["tmes"];
			$ymesant += $linea["tmesant"];
		}
		$tabla .= "<tr> ";
		$tabla .= "<th>Total</th> ";
		$tabla .= "<th class='dere'>" . number_format($xmes, 0, '.', ',') . "</th> ";
		$tabla .= "<th class='dere'>" . number_format($ymes, 2, '.', ',') . "</th> ";
		$tabla .= "<th class='dere'>" . number_format($xmesant, 0, '.', ',') . "</th> ";
		$tabla .= "<th class='dere'>" . number_format($ymesant, 2, '.', ',') . "</th> ";
		$tabla .= "<th class='dere'>" . number_format($xanno, 0, '.', ',') . "</th> ";
		$tabla .= "<th class='dere'>" . number_format($yanno, 2, '.', ',') . "</th> ";
		$tabla .= "<th class='dere'>" . number_format($xtotal, 0, '.', ',') . "</th> ";
		$tabla .= "<th class='dere'>" . number_format($ytotal, 2, '.', ',') . "</th> ";
		$tabla .= "</tr> ";
		$tabla .= "</table>";
	}
    return $tabla;
	}

	function PorProducto(){
    $link= new tiqmysql();
    $sql="
		select producto.nombre as producto,count(*) as cuenta,sum(monto) as cuentot,
		sum( case when date_format(tiquete.fecha, '%m-%Y') = date_format(now(), '%m-%Y') then 1 else 0 end ) as mes,
		sum( case when date_format(tiquete.fecha, '%m-%Y') = date_format(now(), '%m-%Y') then monto else 0 end ) as tmes,
		sum( case when date_format(tiquete.fecha, '%Y') = date_format(now(), '%Y') then 1 else 0 end ) as anno,
		sum( case when date_format(tiquete.fecha, '%Y') = date_format(now(), '%Y') then monto else 0 end ) as tanno,
		sum(case when MONTH(tiquete.Fecha) = MONTH(DATE_SUB(CURDATE(), INTERVAL DAY(CURDATE()) DAY)) AND
				YEAR(tiquete.Fecha) = YEAR(DATE_SUB(CURDATE(), INTERVAL DAY(CURDATE()) DAY)) then 1 else 0 end) as mesant,
		sum(case when MONTH(tiquete.Fecha) = MONTH(DATE_SUB(CURDATE(), INTERVAL DAY(CURDATE()) DAY)) AND
				YEAR(tiquete.Fecha) = YEAR(DATE_SUB(CURDATE(), INTERVAL DAY(CURDATE()) DAY)) then monto else 0 end) as tmesant
		from musoccr.tiquete
		inner join musoccr.viaje on tiquete.idviaje = viaje.idviaje
		inner join musoccr.ruta on viaje.idruta = ruta.idruta
		inner join musoccr.producto on tiquete.idproducto = producto.idproducto
		where factura != 'APARTA' and estado = 0
		group by producto.nombre";
    $res=$link->bdEjecutar($sql);
	$xtotal = 0;
	$xanno = 0;
	$xmes = 0;
	$xmesant = 0;
	$ytotal = 0;
	$yanno = 0;
	$ymes = 0;
	$ymesant = 0;
	$tabla = "<h3 class='alineado'>Por Producto</h3>";
	if($link->bdCantLineas($res)>0){
		$tabla .= "<table border='1' class='centrado' style='border-collapse:collapse'> ";
		$tabla .= "<tr> ";
		$tabla .= "<th>&nbsp;</th> ";
		$tabla .= "<th colspan='2'>Este Mes</th> ";
		$tabla .= "<th colspan='2'>Mes Anterior</th> ";
		$tabla .= "<th colspan='2'>Este a&ntilde;o</th> ";
		$tabla .= "<th colspan='2'>Total</th> ";
		$tabla .= "</tr> ";
		$tabla .= "<tr> ";
		$tabla .= "<th>Producto</th> ";
		$tabla .= "<th>Boletos</th> ";
		$tabla .= "<th>Monto</th> ";
		$tabla .= "<th>Boletos</th> ";
		$tabla .= "<th>Monto</th> ";
		$tabla .= "<th>Boletos</th> ";
		$tabla .= "<th>Monto</th> ";
		$tabla .= "<th>Boletos</th> ";
		$tabla .= "<th>Monto</th> ";
		$tabla .= "</tr> ";
        while($linea =  mysqli_fetch_array($res)){
			$tabla .= "<tr> ";
			$tabla .= "<td>" . $linea["producto"] . "</td> ";
			$tabla .= "<td class='dere'>" . number_format($linea["mes"], 0, '.', ',') . "</td> ";
			$tabla .= "<td class='dere'>" . number_format($linea["tmes"], 2, '.', ',') . "</td> ";
			$tabla .= "<td class='dere'>" . number_format($linea["mesant"], 0, '.', ',') . "</td> ";
			$tabla .= "<td class='dere'>" . number_format($linea["tmesant"], 2, '.', ',') . "</td> ";
			$tabla .= "<td class='dere'>" . number_format($linea["anno"], 0, '.', ',') . "</td> ";
			$tabla .= "<td class='dere'>" . number_format($linea["tanno"], 2, '.', ',') . "</td> ";
			$tabla .= "<td class='dere'>" . number_format($linea["cuenta"], 0, '.', ',') . "</td> ";
			$tabla .= "<td class='dere'>" . number_format($linea["cuentot"], 2, '.', ',') . "</td> ";
			$tabla .= "</tr> ";
			$xtotal += $linea["cuenta"];
			$xanno += $linea["anno"];
			$xmes += $linea["mes"];
			$xmesant += $linea["mesant"];
			$ytotal += $linea["cuentot"];
			$yanno += $linea["tanno"];
			$ymes += $linea["tmes"];
			$ymesant += $linea["tmesant"];
		}
		$tabla .= "<tr> ";
		$tabla .= "<th>Total</th> ";
		$tabla .= "<th class='dere'>" . number_format($xmes, 0, '.', ',') . "</th> ";
		$tabla .= "<th class='dere'>" . number_format($ymes, 2, '.', ',') . "</th> ";
		$tabla .= "<th class='dere'>" . number_format($xmesant, 0, '.', ',') . "</th> ";
		$tabla .= "<th class='dere'>" . number_format($ymesant, 2, '.', ',') . "</th> ";
		$tabla .= "<th class='dere'>" . number_format($xanno, 0, '.', ',') . "</th> ";
		$tabla .= "<th class='dere'>" . number_format($yanno, 2, '.', ',') . "</th> ";
		$tabla .= "<th class='dere'>" . number_format($xtotal, 0, '.', ',') . "</th> ";
		$tabla .= "<th class='dere'>" . number_format($ytotal, 2, '.', ',') . "</th> ";
		$tabla .= "</tr> ";
		$tabla .= "</table>";
	}
    return $tabla;
	}

	function PorEstacion(){
    $link= new tiqmysql();
    $sql="
		select estacion.nombre as estacion,count(*) as cuenta,sum(monto) as cuentot,
		sum( case when date_format(tiquete.fecha, '%m-%Y') = date_format(now(), '%m-%Y') then 1 else 0 end ) as mes,
		sum( case when date_format(tiquete.fecha, '%m-%Y') = date_format(now(), '%m-%Y') then monto else 0 end ) as tmes,
		sum( case when date_format(tiquete.fecha, '%Y') = date_format(now(), '%Y') then 1 else 0 end ) as anno,
		sum( case when date_format(tiquete.fecha, '%Y') = date_format(now(), '%Y') then monto else 0 end ) as tanno,
		sum(case when MONTH(tiquete.Fecha) = MONTH(DATE_SUB(CURDATE(), INTERVAL DAY(CURDATE()) DAY)) AND
				YEAR(tiquete.Fecha) = YEAR(DATE_SUB(CURDATE(), INTERVAL DAY(CURDATE()) DAY)) then 1 else 0 end) as mesant,
		sum(case when MONTH(tiquete.Fecha) = MONTH(DATE_SUB(CURDATE(), INTERVAL DAY(CURDATE()) DAY)) AND
				YEAR(tiquete.Fecha) = YEAR(DATE_SUB(CURDATE(), INTERVAL DAY(CURDATE()) DAY)) then monto else 0 end) as tmesant
		from musoccr.tiquete
		inner join musoccr.estacion on tiquete.idestacion = estacion.idestacion
		where factura != 'APARTA' and estado = 0
		group by estacion.nombre";
    $res=$link->bdEjecutar($sql);
	$xtotal = 0;
	$xanno = 0;
	$xmes = 0;
	$xmesant = 0;
	$ytotal = 0;
	$yanno = 0;
	$ymes = 0;
	$ymesant = 0;
	$tabla = "<h3 class='alineado'>Por Estación</h3>";
	if($link->bdCantLineas($res)>0){
		$tabla .= "<table border='1' class='centrado' style='border-collapse:collapse'> ";
		$tabla .= "<tr> ";
		$tabla .= "<th>&nbsp;</th> ";
		$tabla .= "<th colspan='2'>Este Mes</th> ";
		$tabla .= "<th colspan='2'>Mes Anterior</th> ";
		$tabla .= "<th colspan='2'>Este a&ntilde;o</th> ";
		$tabla .= "<th colspan='2'>Total</th> ";
		$tabla .= "</tr> ";
		$tabla .= "<tr> ";
		$tabla .= "<th>Estaci&oacute;n</th> ";
		$tabla .= "<th>Boletos</th> ";
		$tabla .= "<th>Monto</th> ";
		$tabla .= "<th>Boletos</th> ";
		$tabla .= "<th>Monto</th> ";
		$tabla .= "<th>Boletos</th> ";
		$tabla .= "<th>Monto</th> ";
		$tabla .= "<th>Boletos</th> ";
		$tabla .= "<th>Monto</th> ";
		$tabla .= "</tr> ";
        while($linea =  mysqli_fetch_array($res)){
			$tabla .= "<tr> ";
			$tabla .= "<td>" . $linea["estacion"] . "</td> ";
			$tabla .= "<td class='dere'>" . number_format($linea["mes"], 0, '.', ',') . "</td> ";
			$tabla .= "<td class='dere'>" . number_format($linea["tmes"], 2, '.', ',') . "</td> ";
			$tabla .= "<td class='dere'>" . number_format($linea["mesant"], 0, '.', ',') . "</td> ";
			$tabla .= "<td class='dere'>" . number_format($linea["tmesant"], 2, '.', ',') . "</td> ";
			$tabla .= "<td class='dere'>" . number_format($linea["anno"], 0, '.', ',') . "</td> ";
			$tabla .= "<td class='dere'>" . number_format($linea["tanno"], 2, '.', ',') . "</td> ";
			$tabla .= "<td class='dere'>" . number_format($linea["cuenta"], 0, '.', ',') . "</td> ";
			$tabla .= "<td class='dere'>" . number_format($linea["cuentot"], 2, '.', ',') . "</td> ";
			$tabla .= "</tr> ";
			$xtotal += $linea["cuenta"];
			$xanno += $linea["anno"];
			$xmes += $linea["mes"];
			$xmesant += $linea["mesant"];
			$ytotal += $linea["cuentot"];
			$yanno += $linea["tanno"];
			$ymes += $linea["tmes"];
			$ymesant += $linea["tmesant"];
		}
		$tabla .= "<tr> ";
		$tabla .= "<th>Total</th> ";
		$tabla .= "<th class='dere'>" . number_format($xmes, 0, '.', ',') . "</th> ";
		$tabla .= "<th class='dere'>" . number_format($ymes, 2, '.', ',') . "</th> ";
		$tabla .= "<th class='dere'>" . number_format($xmesant, 0, '.', ',') . "</th> ";
		$tabla .= "<th class='dere'>" . number_format($ymesant, 2, '.', ',') . "</th> ";
		$tabla .= "<th class='dere'>" . number_format($xanno, 0, '.', ',') . "</th> ";
		$tabla .= "<th class='dere'>" . number_format($yanno, 2, '.', ',') . "</th> ";
		$tabla .= "<th class='dere'>" . number_format($xtotal, 0, '.', ',') . "</th> ";
		$tabla .= "<th class='dere'>" . number_format($ytotal, 2, '.', ',') . "</th> ";
		$tabla .= "</tr> ";
		$tabla .= "</table>";
	}
    return $tabla;
	}

	function PorProductoRuta(){
    $link= new tiqmysql();
    $sql="
		select producto.nombre as producto,ruta.nombre as ruta,count(*) as cuenta,sum(monto) as cuentot,
		sum( case when date_format(tiquete.fecha, '%m-%Y') = date_format(now(), '%m-%Y') then 1 else 0 end ) as mes,
		sum( case when date_format(tiquete.fecha, '%m-%Y') = date_format(now(), '%m-%Y') then monto else 0 end ) as tmes,
		sum( case when date_format(tiquete.fecha, '%Y') = date_format(now(), '%Y') then 1 else 0 end ) as anno,
		sum( case when date_format(tiquete.fecha, '%Y') = date_format(now(), '%Y') then monto else 0 end ) as tanno,
		sum(case when MONTH(tiquete.Fecha) = MONTH(DATE_SUB(CURDATE(), INTERVAL DAY(CURDATE()) DAY)) AND
				YEAR(tiquete.Fecha) = YEAR(DATE_SUB(CURDATE(), INTERVAL DAY(CURDATE()) DAY)) then 1 else 0 end) as mesant,
		sum(case when MONTH(tiquete.Fecha) = MONTH(DATE_SUB(CURDATE(), INTERVAL DAY(CURDATE()) DAY)) AND
				YEAR(tiquete.Fecha) = YEAR(DATE_SUB(CURDATE(), INTERVAL DAY(CURDATE()) DAY)) then monto else 0 end) as tmesant
		from musoccr.tiquete
		inner join musoccr.viaje on tiquete.idviaje = viaje.idviaje
		inner join musoccr.ruta on viaje.idruta = ruta.idruta
		inner join musoccr.producto on tiquete.idproducto = producto.idproducto
		where factura != 'APARTA' and estado = 0
		group by producto.nombre,ruta.nombre";
    $res=$link->bdEjecutar($sql);
	$xtotal = 0;
	$xanno = 0;
	$xmes = 0;
	$xmesant = 0;
	$ytotal = 0;
	$yanno = 0;
	$ymes = 0;
	$ymesant = 0;
	$tabla = "<h3 class='alineado'>Por Producto y Ruta</h3>";
	if($link->bdCantLineas($res)>0){
		$tabla .= "<table border='1' class='centrado' style='border-collapse:collapse'> ";
		$tabla .= "<tr> ";
		$tabla .= "<th colspan='2'>&nbsp;</th> ";
		$tabla .= "<th colspan='2'>Este Mes</th> ";
		$tabla .= "<th colspan='2'>Mes Anterior</th> ";
		$tabla .= "<th colspan='2'>Este a&ntilde;o</th> ";
		$tabla .= "<th colspan='2'>Total</th> ";
		$tabla .= "</tr> ";
		$tabla .= "<tr> ";
		$tabla .= "<th>Producto</th> ";
		$tabla .= "<th>Ruta</th> ";
		$tabla .= "<th>Boletos</th> ";
		$tabla .= "<th>Monto</th> ";
		$tabla .= "<th>Boletos</th> ";
		$tabla .= "<th>Monto</th> ";
		$tabla .= "<th>Boletos</th> ";
		$tabla .= "<th>Monto</th> ";
		$tabla .= "<th>Boletos</th> ";
		$tabla .= "<th>Monto</th> ";
		$tabla .= "</tr> ";
        while($linea =  mysqli_fetch_array($res)){
			$tabla .= "<tr> ";
			$tabla .= "<td>" . $linea["producto"] . "</td> ";
			$tabla .= "<td>" . $linea["ruta"] . "</td> ";
			$tabla .= "<td class='dere'>" . number_format($linea["mes"], 0, '.', ',') . "</td> ";
			$tabla .= "<td class='dere'>" . number_format($linea["tmes"], 2, '.', ',') . "</td> ";
			$tabla .= "<td class='dere'>" . number_format($linea["mesant"], 0, '.', ',') . "</td> ";
			$tabla .= "<td class='dere'>" . number_format($linea["tmesant"], 2, '.', ',') . "</td> ";
			$tabla .= "<td class='dere'>" . number_format($linea["anno"], 0, '.', ',') . "</td> ";
			$tabla .= "<td class='dere'>" . number_format($linea["tanno"], 2, '.', ',') . "</td> ";
			$tabla .= "<td class='dere'>" . number_format($linea["cuenta"], 0, '.', ',') . "</td> ";
			$tabla .= "<td class='dere'>" . number_format($linea["cuentot"], 2, '.', ',') . "</td> ";
			$tabla .= "</tr> ";
			$xtotal += $linea["cuenta"];
			$xanno += $linea["anno"];
			$xmes += $linea["mes"];
			$xmesant += $linea["mesant"];
			$ytotal += $linea["cuentot"];
			$yanno += $linea["tanno"];
			$ymes += $linea["tmes"];
			$ymesant += $linea["tmesant"];
		}
		$tabla .= "<tr> ";
		$tabla .= "<th colspan='2'>Total</th> ";
		$tabla .= "<th class='dere'>" . number_format($xmes, 0, '.', ',') . "</th> ";
		$tabla .= "<th class='dere'>" . number_format($ymes, 2, '.', ',') . "</th> ";
		$tabla .= "<th class='dere'>" . number_format($xmesant, 0, '.', ',') . "</th> ";
		$tabla .= "<th class='dere'>" . number_format($ymesant, 2, '.', ',') . "</th> ";
		$tabla .= "<th class='dere'>" . number_format($xanno, 0, '.', ',') . "</th> ";
		$tabla .= "<th class='dere'>" . number_format($yanno, 2, '.', ',') . "</th> ";
		$tabla .= "<th class='dere'>" . number_format($xtotal, 0, '.', ',') . "</th> ";
		$tabla .= "<th class='dere'>" . number_format($ytotal, 2, '.', ',') . "</th> ";
		$tabla .= "</tr> ";
		$tabla .= "</table>";
	}
    return $tabla;
	}

	function PorRutaProducto(){
    $link= new tiqmysql();
    $sql="
		select producto.nombre as producto,ruta.nombre as ruta,count(*) as cuenta,sum(monto) as cuentot,
		sum( case when date_format(tiquete.fecha, '%m-%Y') = date_format(now(), '%m-%Y') then 1 else 0 end ) as mes,
		sum( case when date_format(tiquete.fecha, '%m-%Y') = date_format(now(), '%m-%Y') then monto else 0 end ) as tmes,
		sum( case when date_format(tiquete.fecha, '%Y') = date_format(now(), '%Y') then 1 else 0 end ) as anno,
		sum( case when date_format(tiquete.fecha, '%Y') = date_format(now(), '%Y') then monto else 0 end ) as tanno,
		sum(case when MONTH(tiquete.Fecha) = MONTH(DATE_SUB(CURDATE(), INTERVAL DAY(CURDATE()) DAY)) AND
				YEAR(tiquete.Fecha) = YEAR(DATE_SUB(CURDATE(), INTERVAL DAY(CURDATE()) DAY)) then 1 else 0 end) as mesant,
		sum(case when MONTH(tiquete.Fecha) = MONTH(DATE_SUB(CURDATE(), INTERVAL DAY(CURDATE()) DAY)) AND
				YEAR(tiquete.Fecha) = YEAR(DATE_SUB(CURDATE(), INTERVAL DAY(CURDATE()) DAY)) then monto else 0 end) as tmesant
		from musoccr.tiquete
		inner join musoccr.viaje on tiquete.idviaje = viaje.idviaje
		inner join musoccr.ruta on viaje.idruta = ruta.idruta
		inner join musoccr.producto on tiquete.idproducto = producto.idproducto
		where factura != 'APARTA' and estado = 0
		group by ruta.nombre,producto.nombre";
    $res=$link->bdEjecutar($sql);
	$xtotal = 0;
	$xanno = 0;
	$xmes = 0;
	$xmesant = 0;
	$ytotal = 0;
	$yanno = 0;
	$ymes = 0;
	$ymesant = 0;
	$tabla = "<h3 class='alineado'>Por Ruta y Producto</h3>";
	if($link->bdCantLineas($res)>0){
		$tabla .= "<table border='1' class='centrado' style='border-collapse:collapse'> ";
		$tabla .= "<tr> ";
		$tabla .= "<th colspan='2'>&nbsp;</th> ";
		$tabla .= "<th colspan='2'>Este Mes</th> ";
		$tabla .= "<th colspan='2'>Mes Anterior</th> ";
		$tabla .= "<th colspan='2'>Este a&ntilde;o</th> ";
		$tabla .= "<th colspan='2'>Total</th> ";
		$tabla .= "</tr> ";
		$tabla .= "<tr> ";
		$tabla .= "<th>Ruta</th> ";
		$tabla .= "<th>Producto</th> ";
		$tabla .= "<th>Boletos</th> ";
		$tabla .= "<th>Monto</th> ";
		$tabla .= "<th>Boletos</th> ";
		$tabla .= "<th>Monto</th> ";
		$tabla .= "<th>Boletos</th> ";
		$tabla .= "<th>Monto</th> ";
		$tabla .= "<th>Boletos</th> ";
		$tabla .= "<th>Monto</th> ";
		$tabla .= "</tr> ";
        while($linea =  mysqli_fetch_array($res)){
			$tabla .= "<tr> ";
			$tabla .= "<td>" . $linea["ruta"] . "</td> ";
			$tabla .= "<td>" . $linea["producto"] . "</td> ";
			$tabla .= "<td class='dere'>" . number_format($linea["mes"], 0, '.', ',') . "</td> ";
			$tabla .= "<td class='dere'>" . number_format($linea["tmes"], 2, '.', ',') . "</td> ";
			$tabla .= "<td class='dere'>" . number_format($linea["mesant"], 0, '.', ',') . "</td> ";
			$tabla .= "<td class='dere'>" . number_format($linea["tmesant"], 2, '.', ',') . "</td> ";
			$tabla .= "<td class='dere'>" . number_format($linea["anno"], 0, '.', ',') . "</td> ";
			$tabla .= "<td class='dere'>" . number_format($linea["tanno"], 2, '.', ',') . "</td> ";
			$tabla .= "<td class='dere'>" . number_format($linea["cuenta"], 0, '.', ',') . "</td> ";
			$tabla .= "<td class='dere'>" . number_format($linea["cuentot"], 2, '.', ',') . "</td> ";
			$tabla .= "</tr> ";
			$xtotal += $linea["cuenta"];
			$xanno += $linea["anno"];
			$xmes += $linea["mes"];
			$xmesant += $linea["mesant"];
			$ytotal += $linea["cuentot"];
			$yanno += $linea["tanno"];
			$ymes += $linea["tmes"];
			$ymesant += $linea["tmesant"];
		}
		$tabla .= "<tr> ";
		$tabla .= "<th colspan='2'>Total</th> ";
		$tabla .= "<th class='dere'>" . number_format($xmes, 0, '.', ',') . "</th> ";
		$tabla .= "<th class='dere'>" . number_format($ymes, 2, '.', ',') . "</th> ";
		$tabla .= "<th class='dere'>" . number_format($xmesant, 0, '.', ',') . "</th> ";
		$tabla .= "<th class='dere'>" . number_format($ymesant, 2, '.', ',') . "</th> ";
		$tabla .= "<th class='dere'>" . number_format($xanno, 0, '.', ',') . "</th> ";
		$tabla .= "<th class='dere'>" . number_format($yanno, 2, '.', ',') . "</th> ";
		$tabla .= "<th class='dere'>" . number_format($xtotal, 0, '.', ',') . "</th> ";
		$tabla .= "<th class='dere'>" . number_format($ytotal, 2, '.', ',') . "</th> ";
		$tabla .= "</tr> ";
		$tabla .= "</table>";
	}
    return $tabla;
	}
}
?>
