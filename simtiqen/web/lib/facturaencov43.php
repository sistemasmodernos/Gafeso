<?php
require_once 'vendor/autoload.php';
use Spipu\Html2Pdf\Html2Pdf;
/**
* Genera la factura electrónica
*/
class FacturaEnco 
{

	var $html2pdf ;
	function __construct(){
		$this->html2pdf = new Html2Pdf();
	}
	function generafactura($pid){
		$enco = new Encomienda();
		$enco->idEncomienda=$pid;
		$enco->Cargar();
		$lqr="";
		if(substr($enco->facturafe, 8,2)=='01')
			$xml= $this->generaxmlFacturaV43($enco);
	    else
	    	$xml= $this->generaxmlTiqueteV43($enco);
	    echo $xml;
		$pdf = $this->generapdf($enco);
		$rr= $this->enviafactura($xml,$pdf,$lqr);
		return $rr;

	}

	function generanotacredito($pid){
		$enco = new Encomienda();
		$enco->idEncomienda=$pid;
		$enco->Cargar();
		$lqr="";
		$xml= $this->generaxmlNCV43($enco);
		echo $xml;
		$pdf = $this->generapdfNC($enco);
		$rr= $this->enviafactura($xml,$pdf,$lqr);
		return $rr;

	}

	function generapdf($penco){
		$comprobante = ImprimeEncomienda($penco->idEncomienda);
		echo var_dump($comprobante);
		$comprobante = str_replace("\r\n", "<br/>", $comprobante);
		$this->html2pdf->writeHTML('<page>'.$comprobante.'</page>');
		$base64 = $this->html2pdf->output("factura.pdf","E");
		$temp = explode("\n", $base64);
		
		$base64="";
		
		for($x=6;$x<count($temp);$x++){
			$base64= $base64.$temp[$x]."\n";
		}
		
		return $base64;
	}
	function generapdfNC($penco){
		$comprobante = ImprimeEncomiendaNC($penco->idEncomienda);
		$comprobante = str_replace("\r\n", "<br/>", $comprobante);
		$this->html2pdf->writeHTML('<page>'.$comprobante.'</page>');
		$base64 = $this->html2pdf->output("notacredito.pdf","E");
		$temp = explode("\n", $base64);
		
		$base64="";
		
		for($x=6;$x<count($temp);$x++){
			$base64= $base64.$temp[$x]."\n";
		}
		
		return $base64;
	}

	function enviafactura($pxml,$ppdf,$pqr){

    $par=new Parametro();
    $contentType = 'Content-type: application/x-www-form-urlencoded';
    $accept ='Accept: application/json';
    $service_url = $par->Retorna("rutaserverfe",3);


    // curl init

    $ch = curl_init($service_url);
    $dat = array(
      "serial" => $par->Retorna("hashfe",3),
      "xml" => $pxml,
      "pdf"=>$ppdf,
      "qr"=>$pqr
    );

    echo $service_url;
		echo var_dump($dat);


		curl_setopt_array($ch, [
			CURLOPT_RETURNTRANSFER => true,
			CURLINFO_HEADER_OUT    => true,
			CURLOPT_HTTPHEADER     => [
				$contentType,
				$accept
			],
			CURLOPT_POST=>1,
			CURLOPT_POSTFIELDS => http_build_query($dat)

		]);

/*

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_VERBOSE, 1);
		curl_setopt($ch, CURLOPT_HEADER, 1);
*/
		// Send the request & save response to $resp
		$resp = curl_exec($ch);

		if(curl_errno($ch)){
			echo 'Curl error: ' . curl_error($ch);
			return "";
		}


		// Close request to clear up some resources
		curl_close($ch);
	  echo $resp;	
		
		return $resp;

	}

	function generaxml($pventa){
		
		$par=new Parametro(); 
		$nomcia = $par->Retorna("nombreciae",3);

		$numfa = $pventa->facturafe;
		$clave = $pventa->clavefe;
		$ced=str_replace("-", "", $par->Retorna("cedulae",3));
		$lprovincia=$par->Retorna("provincia",3);
		$lcanton= $par->Retorna("canton",3);
		$ldistrito=$par->Retorna("distrito",3);
		$lbarrio = $par->Retorna("barrio",3);
		$lotras = $par->Retorna("OtrasSenas",3);
		$lcorreo = $par->Retorna("correo",3);
		$fecha = str_replace(" ","T",$pventa->fechadi).".999";
		$monto = $pventa->monto;

		$xml='<?xml version="1.0" encoding="utf-8" standalone="no"?>
		<FacturaElectronica xmlns="https://tribunet.hacienda.go.cr/docs/esquemas/2017/v4.2/facturaElectronica"
		 xmlns:xsd="http://www.w3.org/2001/XMLSchema" 
		xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
		<Clave>'.$clave.'</Clave>
		<NumeroConsecutivo>'.$numfa.'</NumeroConsecutivo>
		<FechaEmision>'.$fecha.'</FechaEmision>
		<Emisor>
		<Nombre>'.$nomcia.'</Nombre>
		<Identificacion>
		<Tipo>02</Tipo>
		<Numero>'.$ced.'</Numero>
		</Identificacion>
		<NombreComercial/>
		<Ubicacion>
		<Provincia>'.$lprovincia.'</Provincia>
		<Canton>'.$lcanton.'</Canton>
		<Distrito>'.$ldistrito.'</Distrito>
		<Barrio>'.$lbarrio.'</Barrio>
		<OtrasSenas>'.$lotras.'</OtrasSenas>
		</Ubicacion>
		<CorreoElectronico>'.$lcorreo.'</CorreoElectronico>
		</Emisor>
		';


		
		if($pventa->tipocedula!='00'){
			if ($pventa->emailremi == ''){
				$xycorreo = '';
			}else
			{
				$xycorreo = '<CorreoElectronico>'.$pventa->emailremi.'</CorreoElectronico>';
			}
			$xml.='<Receptor>
         <Nombre>'.$pventa->remitente.'</Nombre>
         <Identificacion>
         <Tipo>'.$pventa->tipocedula.'</Tipo>
         <Numero>'.$pventa->cedremi.'</Numero>
			</Identificacion>
			<NombreComercial/>
			<Ubicacion>
			<Provincia>'.$pventa->provincia.'</Provincia>
			<Canton>'.str_pad($pventa->canton,2,"0",STR_PAD_LEFT).'</Canton>
			<Distrito>'.str_pad($pventa->distrito,2,"0",STR_PAD_LEFT).'</Distrito>
			<Barrio>'.str_pad($pventa->barrio,2,"0",STR_PAD_LEFT).'</Barrio>
			<OtrasSenas>'.$pventa->otrassenas.'</OtrasSenas>
			</Ubicacion>
			'.$xycorreo.'
			</Receptor>';
		}

    if (strlen($pventa->nota) >= 200){ $pventa->nota = substr($pventa->nota, 0, 180); }

		$xml.='<CondicionVenta>01</CondicionVenta>
		<PlazoCredito/>
		<MedioPago>02</MedioPago>
		<DetalleServicio>
		<LineaDetalle>
		<NumeroLinea>1</NumeroLinea>
		<Codigo>
		<Tipo>04</Tipo>
		<Codigo>001</Codigo>
		</Codigo>
		<Cantidad>1</Cantidad>
		<UnidadMedida>Sp</UnidadMedida>
		<UnidadMedidaComercial/>
		<Detalle>Encomienda '.$pventa->nota.'</Detalle>
		<PrecioUnitario>'.$monto.'</PrecioUnitario>
		<MontoTotal>'.$monto.'</MontoTotal>
		<NaturalezaDescuento/>
		<SubTotal>'.$monto.'</SubTotal>
		<MontoTotalLinea>'.$monto.'</MontoTotalLinea>
		</LineaDetalle>
		</DetalleServicio>
		<ResumenFactura>
		<TotalServExentos>'.$monto.'</TotalServExentos>
		<TotalExento>'.$monto.'</TotalExento>
		<TotalVenta>'.$monto.'</TotalVenta>
		<TotalVentaNeta>'.$monto.'</TotalVentaNeta>
		<TotalComprobante>'.$monto.'</TotalComprobante>
		</ResumenFactura>
		<InformacionReferencia>
		<TipoDoc>01</TipoDoc>
		<Numero>0006</Numero>
		<FechaEmision>2018-03-04T00:00:00</FechaEmision>
		<Codigo>99</Codigo>
		<Razon>Encomienda</Razon>
		</InformacionReferencia>
		<Normativa>
		<NumeroResolucion>DGT-R-48-2016</NumeroResolucion>
		<FechaResolucion>07-10-2016 08:00:00</FechaResolucion>
		</Normativa>
		<Otros/>
		</FacturaElectronica>';

		return $xml;

	}


	function generaxmlTiqueteV43($pventa){
		
		$par=new Parametro(); 
		$nomcia = $par->Retorna("nombreciae",3);
        $codact = $par->Retorna("CodigoActividad",3);
		$numfa = $pventa->facturafe;
		$clave = $pventa->clavefe;
		$ced=str_replace("-", "", $par->Retorna("cedulae",3));
		$lprovincia=$par->Retorna("provincia",3);
		$lcanton= $par->Retorna("canton",3);
		$ldistrito=$par->Retorna("distrito",3);
		$lbarrio = $par->Retorna("barrio",3);
		$lotras = $par->Retorna("OtrasSenas",3);
		$lcorreo = $par->Retorna("correo",3);
		$fecha = str_replace(" ","T",$pventa->fechadi).".999";
		$monto = $pventa->monto-$pventa->iva;
		$iva = $pventa->iva;
		$total = $pventa->monto;
				
		$xml='<?xml version="1.0" encoding="utf-8" standalone="no"?>
		<TiqueteElectronico xmlns="https://cdn.comprobanteselectronicos.go.cr/xml-schemas/v4.3/tiqueteElectronico" 
		xmlns:xsd="http://www.w3.org/2001/XMLSchema" 
		xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
		<Clave>'.$clave.'</Clave>
		<CodigoActividad>'.$codact.'</CodigoActividad>
		<NumeroConsecutivo>'.$numfa.'</NumeroConsecutivo>
		<FechaEmision>'.$fecha.'</FechaEmision>
		<Emisor>
		  <Nombre>'.$nomcia.'</Nombre>
		  <Identificacion>
		    <Tipo>02</Tipo>
		    <Numero>'.$ced.'</Numero>
		  </Identificacion>
		  <Ubicacion>
		   <Provincia>'.$lprovincia.'</Provincia>
		   <Canton>'.$lcanton.'</Canton>
		   <Distrito>'.$ldistrito.'</Distrito>
		   <Barrio>'.$lbarrio.'</Barrio>
		   <OtrasSenas>'.$lotras.'</OtrasSenas>
		   	</Ubicacion>
		   <CorreoElectronico>'.$lcorreo.'</CorreoElectronico>
		</Emisor>
		';


		
		/*if($pventa->tipocedula!='00'){
			if ($pventa->emailremi == ''){
				$xycorreo = '';
			}else
			{
				$xycorreo = '<CorreoElectronico>'.$pventa->emailremi.'</CorreoElectronico>';
			}
			$xml.='<Receptor>
			<Nombre>'.$pventa->remitente.'</Nombre>
         <Identificacion>
         <Tipo>'.$pventa->tipocedula.'</Tipo>
         <Numero>'.$pventa->cedremi.'</Numero>
			<Nombre>'.$pventa->nombrefa.'</Nombre>
			</Identificacion>
			<NombreComercial/>
			<Ubicacion>
			<Provincia>'.$pventa->provincia.'</Provincia>
			<Canton>'.str_pad($pventa->canton,2,"0",STR_PAD_LEFT).'</Canton>
			<Distrito>'.str_pad($pventa->distrito,2,"0",STR_PAD_LEFT).'</Distrito>
			<Barrio>'.str_pad($pventa->barrio,2,"0",STR_PAD_LEFT).'</Barrio>
			<OtrasSenas>'.$pventa->otrassenas.'</OtrasSenas>
			</Ubicacion>
			'.$xycorreo.'
			</Receptor>';
		}*/

    if (strlen($pventa->nota) >= 200){ $pventa->nota = substr($pventa->nota, 0, 180); }

		$xml.='
		<CondicionVenta>01</CondicionVenta>
		<MedioPago>02</MedioPago>
		<DetalleServicio>
		<LineaDetalle>
		     <NumeroLinea>1</NumeroLinea>    
           <Codigo>6511600000000</Codigo> 
		     <Cantidad>1</Cantidad>
			 <UnidadMedida>Os</UnidadMedida>
		     <Detalle>Encomienda '.$pventa->nota.'</Detalle>
		     <PrecioUnitario>'.$monto.'</PrecioUnitario>
		     <MontoTotal>'.$monto.'</MontoTotal>
			 <SubTotal>'.$monto.'</SubTotal>
			 <Impuesto>
				<Codigo>01</Codigo>
				<CodigoTarifa>08</CodigoTarifa>
				<Tarifa>13</Tarifa>
				<Monto>'.$iva.'</Monto>
			 </Impuesto>
			 <MontoTotalLinea>'.$total.'</MontoTotalLinea>
		   </LineaDetalle>
		</DetalleServicio>
		<ResumenFactura>
			<TotalServGravados>'.$monto.'</TotalServGravados>
			<TotalGravado>'.$monto.'</TotalGravado>
			<TotalVenta>'.$monto.'</TotalVenta>
			<TotalVentaNeta>'.$monto.'</TotalVentaNeta>
			<TotalImpuesto>'.$iva.'</TotalImpuesto>
			<TotalComprobante>'.$total.'</TotalComprobante>
		</ResumenFactura>
		</TiqueteElectronico>';

		return $xml;

	}
	function generaxmlFacturaV43($pventa){
		
		$par=new Parametro(); 
		$newexo = $par->Retorna("newcalexo",1);
		$nomcia = $par->Retorna("nombreciae",3);
                $codact = $par->Retorna("CodigoActividad",3);
		$numfa = $pventa->facturafe;
		$clave = $pventa->clavefe;
		$ced=str_replace("-", "", $par->Retorna("cedulae",3));
		$lprovincia=$par->Retorna("provincia",3);
		$lcanton= $par->Retorna("canton",3);
		$ldistrito=$par->Retorna("distrito",3);
		$lbarrio = $par->Retorna("barrio",3);
		$lotras = $par->Retorna("OtrasSenas",3);
		$lcorreo = $par->Retorna("correo",3);
		$fecha = str_replace(" ","T",$pventa->fechadi).".999";
		$monto = $pventa->monto-$pventa->iva;
		$iva = $pventa->iva;
		$total = $pventa->monto;
		$strimpu="";
		$strResumen="";
		$empre = new Empresa();
		if($pventa->tipocedfa=='00'){
			$strimpu='
			<Impuesto>
				<Codigo>01</Codigo>
				<CodigoTarifa>08</CodigoTarifa>
				<Tarifa>13</Tarifa>
				<Monto>'.$iva.'</Monto>
			</Impuesto>';
			
			$strResumen='
			<ResumenFactura>
				<TotalServGravados>'.$monto.'</TotalServGravados>
				<TotalServExentos>0</TotalServExentos>
				<TotalServExonerado>0</TotalServExonerado>
				<TotalMercanciasGravadas>0</TotalMercanciasGravadas>
				<TotalMercanciasExentas>0</TotalMercanciasExentas>
				<TotalMercExonerada>0</TotalMercExonerada>
				<TotalGravado>'.$monto.'</TotalGravado>
				<TotalExento>0</TotalExento>
				<TotalExonerado>0</TotalExonerado>
				<TotalVenta>'.$monto.'</TotalVenta>
				<TotalVentaNeta>'.$monto.'</TotalVentaNeta>
				<TotalImpuesto>'.$iva.'</TotalImpuesto>
				<TotalComprobante>'.$total.'</TotalComprobante>
			</ResumenFactura>';
		}
		else
		{
			if($empre->CargarCedula($pventa->cedulafa)>0)
			{
	        	$poriva = $empre->getPorIva();
	        	if($poriva==0){
	        		$strimpu='';
	        		$strResumen='
						<ResumenFactura>
			                                <TotalServGravados>0</TotalServGravados>
			                                <TotalServExentos>'.$monto.'</TotalServExentos>
			                                <TotalServExonerado>0</TotalServExonerado>
                        			        <TotalMercanciasGravadas>0</TotalMercanciasGravadas>
			                                <TotalMercanciasExentas>0</TotalMercanciasExentas>
			                                <TotalMercExonerada>0</TotalMercExonerada>
			                                <TotalGravado>0</TotalGravado>
			                                <TotalExento>'.$monto.'</TotalExento>
			                                <TotalExonerado>0</TotalExonerado>
							<TotalVenta>'.$monto.'</TotalVenta>
							<TotalVentaNeta>'.$monto.'</TotalVentaNeta>
							<TotalImpuesto>0</TotalImpuesto>
							<TotalComprobante>'.$monto.'</TotalComprobante>
						</ResumenFactura>';
	        	}
	        	else
	        	{
		        	if($empre->exodoc!=''){
						if($newexo ==1){
 							
							$impexo=round($monto*$empre->exoporce/100,2);
							//$brutoexo = round($impexo/($poriva/100),2);
							$brutoexo=round($empre->exoporce/$poriva,5)*$monto;
							
						}
						else{
							$brutoexo = round($monto*$empre->exoporce/100,2);
		        			$impexo=round($brutoexo*$poriva/100,2);
						}
		        		
			        	$strimpu='<Impuesto>
							<Codigo>01</Codigo>
							<CodigoTarifa>'.$empre->tarifaimp.'</CodigoTarifa>
							<Tarifa>'.$poriva.'</Tarifa>
							<Monto>'.($impexo+$iva).'</Monto>
							<Exoneracion>
								<TipoDocumento>03</TipoDocumento>
								<NumeroDocumento>'.$empre->exodoc.'</NumeroDocumento>
								<NombreInstitucion>'.$empre->exoentidad.'</NombreInstitucion>
								<FechaEmision>'.$empre->exofecha.'T00:00:00-06:00</FechaEmision>
								<PorcentajeExoneracion>'.$empre->exoporce.'</PorcentajeExoneracion>
								<MontoExoneracion>'.$impexo.'</MontoExoneracion>
							</Exoneracion>	
						 	</Impuesto>
						 	<ImpuestoNeto>'.$iva.'</ImpuestoNeto>';

						$strResumen='
							<ResumenFactura>
				                                <TotalServGravados>'.($monto-$brutoexo).'</TotalServGravados>
				                                <TotalServExentos>0</TotalServExentos>
				                                <TotalServExonerado>'.$brutoexo.'</TotalServExonerado>
				                                <TotalMercanciasGravadas>0</TotalMercanciasGravadas>
				                                <TotalMercanciasExentas>0</TotalMercanciasExentas>
				                                <TotalMercExonerada>0</TotalMercExonerada>
				                                <TotalGravado>'.($monto-$brutoexo).'</TotalGravado>
				                                <TotalExento>0</TotalExento>
				                                <TotalExonerado>'.$brutoexo.'</TotalExonerado>
								<TotalVenta>'.$monto.'</TotalVenta>
								<TotalVentaNeta>'.$monto.'</TotalVentaNeta>
								<TotalImpuesto>'.$iva.'</TotalImpuesto>
								<TotalComprobante>'.$total.'</TotalComprobante>
							</ResumenFactura>';
		        	}
		        	else
		        	{
						$strimpu='
				       		<Impuesto>
								<Codigo>01</Codigo>
								<CodigoTarifa>'.$empre->tarifaimp.'</CodigoTarifa>
								<Tarifa>'.$poriva.'</Tarifa>
								<Monto>'.$iva.'</Monto>
						 	</Impuesto>';
						$strResumen='
							<ResumenFactura>
				                                <TotalServGravados>'.$monto.'</TotalServGravados>
				                                <TotalServExentos>0</TotalServExentos>
				                                <TotalServExonerado>0</TotalServExonerado>
				                                <TotalMercanciasGravadas>0</TotalMercanciasGravadas>
				                                <TotalMercanciasExentas>0</TotalMercanciasExentas>
				                                <TotalMercExonerada>0</TotalMercExonerada>
				                                <TotalGravado>'.$monto.'</TotalGravado>
				                                <TotalExento>0</TotalExento>
				                                <TotalExonerado>0</TotalExonerado>
								<TotalVenta>'.$monto.'</TotalVenta>
								<TotalVentaNeta>'.$monto.'</TotalVentaNeta>
								<TotalImpuesto>'.$iva.'</TotalImpuesto>
								<TotalComprobante>'.$total.'</TotalComprobante>
							</ResumenFactura>';						 	
		        	}
	        	}
	    	}
	    	else{
	    		$strimpu='<Impuesto>
						<Codigo>01</Codigo>
						<CodigoTarifa>08</CodigoTarifa>
						<Tarifa>13</Tarifa>
						<Monto>'.$iva.'</Monto>
			 		 </Impuesto>';
			 	$strResumen='
					<ResumenFactura>
                   <TotalServGravados>'.$monto.'</TotalServGravados>
	                <TotalServExentos>0</TotalServExentos>
                   <TotalServExonerado>0</TotalServExonerado>
                   <TotalMercanciasGravadas>0</TotalMercanciasGravadas>
                   <TotalMercanciasExentas>0</TotalMercanciasExentas>
                   <TotalMercExonerada>0</TotalMercExonerada>
                   <TotalGravado>'.$monto.'</TotalGravado>
                   <TotalExento>0</TotalExento>
                   <TotalExonerado>0</TotalExonerado>
						<TotalVenta>'.$monto.'</TotalVenta>
						<TotalVentaNeta>'.$monto.'</TotalVentaNeta>
						<TotalImpuesto>'.$iva.'</TotalImpuesto>
						<TotalComprobante>'.$total.'</TotalComprobante>
					</ResumenFactura>';
	    	}
    	}


		$xml='<?xml version="1.0" encoding="utf-8" standalone="no"?>
			<FacturaElectronica
			xmlns="https://cdn.comprobanteselectronicos.go.cr/xml-schemas/v4.3/facturaElectronica">
		<Clave>'.$clave.'</Clave>
		<CodigoActividad>'.$codact.'</CodigoActividad>
		<NumeroConsecutivo>'.$numfa.'</NumeroConsecutivo>
		<FechaEmision>'.$fecha.'</FechaEmision>
		<Emisor>
		  <Nombre>'.$nomcia.'</Nombre>
		  <Identificacion>
		    <Tipo>02</Tipo>
		    <Numero>'.$ced.'</Numero>
		  </Identificacion>
		  <Ubicacion>
		   <Provincia>'.$lprovincia.'</Provincia>
		   <Canton>'.$lcanton.'</Canton>
		   <Distrito>'.$ldistrito.'</Distrito>
		   <Barrio>'.$lbarrio.'</Barrio>
		   <OtrasSenas>'.$lotras.'</OtrasSenas>
		   </Ubicacion>
		   <CorreoElectronico>'.$lcorreo.'</CorreoElectronico>
		</Emisor>
		';


      if($pventa->tipocedfa!='00'){
         if ($pventa->correofa == ''){
            $xycorreo = '';
         }else
         {
            $xycorreo = '<CorreoElectronico>'.$pventa->correofa.'</CorreoElectronico>';
         }
         $xml.='<Receptor>
         <Nombre>'.$pventa->remitente.'</Nombre>
         <Identificacion>
         <Tipo>'.$pventa->tipocedfa.'</Tipo>
         <Numero>'.$pventa->cedulafa.'</Numero>

			  </Identificacion>
			  <NombreComercial/>
			  <Ubicacion>
			    <Provincia>'.$pventa->provincia.'</Provincia>
			    <Canton>'.str_pad($pventa->canton,2,"0",STR_PAD_LEFT).'</Canton>
			    <Distrito>'.str_pad($pventa->distrito,2,"0",STR_PAD_LEFT).'</Distrito>
			    <Barrio>'.str_pad($pventa->barrio,2,"0",STR_PAD_LEFT).'</Barrio>
			    <OtrasSenas>'.$pventa->otrassenas.'</OtrasSenas>
			  </Ubicacion>
			  '.$xycorreo.'
			</Receptor>';
		}

    if (strlen($pventa->nota) >= 200){ $pventa->nota = substr($pventa->nota, 0, 180); }

		$xml.='
		<CondicionVenta>01</CondicionVenta>
		<MedioPago>02</MedioPago>
		<DetalleServicio>
		   <LineaDetalle>
		     <NumeroLinea>1</NumeroLinea>
           <Codigo>6511600000000</Codigo> 
		     <Cantidad>1</Cantidad>
			 <UnidadMedida>Os</UnidadMedida>
		     <Detalle>Encomienda '.$pventa->nota.'</Detalle>
		     <PrecioUnitario>'.$monto.'</PrecioUnitario>
		     <MontoTotal>'.$monto.'</MontoTotal>
			 <SubTotal>'.$monto.'</SubTotal>
			 '.$strimpu.'
			 <MontoTotalLinea>'.$total.'</MontoTotalLinea>
		   </LineaDetalle>
		</DetalleServicio>
		'.$strResumen.'
		</FacturaElectronica>';

		return $xml;

	}

	function generaxmlNC($pventa){
		
		$par=new Parametro(); 
		$nomcia = $par->Retorna("nombrecia",3);

		$numfa = $pventa->facturancfe;
		$clave = $pventa->clavencfe;
		$ced=str_replace("-", "", $par->Retorna("cedula",3));
		$lprovincia=$par->Retorna("provincia",3);
		$lcanton=$par->Retorna("canton",3);
		$ldistrito=$par->Retorna("distrito",3);
		$lbarrio = $par->Retorna("barrio",3);
		$lotras = $par->Retorna("OtrasSenas",3);
		$lcorreo = $par->Retorna("correo",3);
		$fecha = $pventa->fechanc;
		$monto = $pventa->monto;
		$fechao = str_replace(" ", "T",$fecha).".999" ;

		$xml='<?xml version="1.0" encoding="utf-8" standalone="no"?><NotaCreditoElectronica xmlns="https://tribunet.hacienda.go.cr/docs/esquemas/2017/v4.2/notaCreditoElectronica" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
		<Clave>'.$clave.'</Clave>
		<NumeroConsecutivo>'.$numfa.'</NumeroConsecutivo>
		<FechaEmision>'.$fechao.'</FechaEmision>
		<Emisor>
		<Nombre>'.$nomcia.'</Nombre>
		<Identificacion>
		<Tipo>02</Tipo>
		<Numero>'.$ced.'</Numero>
		</Identificacion>
		<NombreComercial/>
		<Ubicacion>
		<Provincia>'.$lprovincia.'</Provincia>
		<Canton>'.$lcanton.'</Canton>
		<Distrito>'.$ldistrito.'</Distrito>
		<Barrio>'.$lbarrio.'</Barrio>
		<OtrasSenas>'.$lotras.'</OtrasSenas>
		</Ubicacion>
		<Telefono xsi:nil="true"/>
		<Fax xsi:nil="true"/>
		<CorreoElectronico>'.$lcorreo.'</CorreoElectronico>
		</Emisor>
		';


		$empre = new Empresa();
		$empre->CargarCedula($pventa->cedulafa);
		if($empre->idEmpresa!=0){
			$xml.='<Receptor>
			<Nombre>'.$empre->nombre.'</Nombre>
			<Identificacion>
			<Tipo>02</Tipo>
			<Numero>'.$empre->cedula.'</Numero>
			</Identificacion>
			<NombreComercial/>
			<Ubicacion>
			<Provincia>'.$empre->provincia.'</Provincia>
			<Canton>'.$empre->canton.'</Canton>
			<Distrito>'.$empre->distrito.'</Distrito>
			<Barrio>'.$empre->barrio.'</Barrio>
			<OtrasSenas>'.$empre->direccion.'</OtrasSenas>
			</Ubicacion>
			<Telefono xsi:nil="true"/>
			<Fax xsi:nil="true"/>
			<CorreoElectronico>'.$empre->correo.'</CorreoElectronico>
			</Receptor>';
		}

    if (strlen($pventa->nota) >= 200){ $pventa->nota = substr($pventa->nota, 0, 180); }

		$xml.='<CondicionVenta>01</CondicionVenta>
		<PlazoCredito/>
		<MedioPago>02</MedioPago>
		<DetalleServicio>
		<LineaDetalle>
		<NumeroLinea>1</NumeroLinea>
		<Codigo>
		<Tipo>04</Tipo>
		<Codigo>001</Codigo>
		</Codigo>
		<Cantidad>1</Cantidad>
		<UnidadMedida>Sp</UnidadMedida>
		<UnidadMedidaComercial/>
		<Detalle>Encomienda '.$pventa->nota.'</Detalle>
		<PrecioUnitario>'.$monto.'</PrecioUnitario>
		<MontoTotal>'.$monto.'</MontoTotal>
		<NaturalezaDescuento/>
		<SubTotal>'.$monto.'</SubTotal>
		<MontoTotalLinea>'.$monto.'</MontoTotalLinea>
		</LineaDetalle>
		</DetalleServicio>
		<ResumenFactura>
		<TotalServExentos>'.$monto.'</TotalServExentos>
		<TotalExento>'.$monto.'</TotalExento>
		<TotalVenta>'.$monto.'</TotalVenta>
		<TotalVentaNeta>'.$monto.'</TotalVentaNeta>
		<TotalComprobante>'.$monto.'</TotalComprobante>
		</ResumenFactura>
		<InformacionReferencia>
		<TipoDoc>01</TipoDoc>
		<Numero>'.$pventa->facturafe.'</Numero>
		<FechaEmision>2'.$fechao.'</FechaEmision>
		<Codigo>01</Codigo>
		<Razon>Anula</Razon>
		</InformacionReferencia>
		<Normativa>
		<NumeroResolucion>DGT-R-48-2016</NumeroResolucion>
		<FechaResolucion>07-10-2016 08:00:00</FechaResolucion>
		</Normativa>
		<Otros/>
		</NotaCreditoElectronica>';

		return $xml;

	}

	function generaxmlNCV43($pventa){
		
		$par=new Parametro(); 
		$nomcia = $par->Retorna("nombreciae",3);
                $codact = $par->Retorna("CodigoActividad",3);
		$numfa = $pventa->facturancfe;
		$clave = $pventa->clavencfe;
		$ced=str_replace("-", "", $par->Retorna("cedulae",3));
		$lprovincia=$par->Retorna("provincia",3);
		$lcanton=$par->Retorna("canton",3);
		$ldistrito=$par->Retorna("distrito",3);
		$lbarrio = $par->Retorna("barrio",3);
		$lotras = $par->Retorna("OtrasSenas",3);
		$lcorreo = $par->Retorna("correo",3);
		$fecha = $pventa->fechanc;
		$monto = $pventa->monto-$pventa->iva;
		$iva = $pventa->iva;
		$total = $pventa->monto;
		$fechao = str_replace(" ", "T",$fecha).".999" ;
		$tipref = substr($pventa->facturafe, 8,2);

		$xml='<?xml version="1.0" encoding="utf-8" standalone="no"?>
		<NotaCreditoElectronica xmlns="https://cdn.comprobanteselectronicos.go.cr/xml-schemas/v4.3/notaCreditoElectronica" 
		xmlns:xsd="http://www.w3.org/2001/XMLSchema" 
		xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
		<Clave>'.$clave.'</Clave>
		<CodigoActividad>'.$codact.'</CodigoActividad>
		<NumeroConsecutivo>'.$numfa.'</NumeroConsecutivo>
		<FechaEmision>'.$fechao.'</FechaEmision>
		<Emisor>
			<Nombre>'.$nomcia.'</Nombre>
			<Identificacion>
				<Tipo>02</Tipo>
				<Numero>'.$ced.'</Numero>
			</Identificacion>
			<NombreComercial/>
			<Ubicacion>
				<Provincia>'.$lprovincia.'</Provincia>
				<Canton>'.$lcanton.'</Canton>
				<Distrito>'.$ldistrito.'</Distrito>
				<Barrio>'.$lbarrio.'</Barrio>
				<OtrasSenas>'.$lotras.'</OtrasSenas>
			</Ubicacion>
			<CorreoElectronico>'.$lcorreo.'</CorreoElectronico>
		</Emisor>
		';


	

		$empre = new Empresa();
		$empre->CargarCedula($pventa->cedulafa);
      if($pventa->tipocedula!='00'){
         if ($pventa->emailremi == ''){
            $xycorreo = '';
         }else
         {
            $xycorreo = '<CorreoElectronico>'.$pventa->emailremi.'</CorreoElectronico>';
         }
         $xml.='<Receptor>
         <Nombre>'.$pventa->remitente.'</Nombre>
         <Identificacion>
         <Tipo>'.$pventa->tipocedula.'</Tipo>
         <Numero>'.$pventa->cedremi.'</Numero>
        </Identificacion>
        <NombreComercial/>
        <Ubicacion>
          <Provincia>'.$pventa->provincia.'</Provincia>
          <Canton>'.str_pad($pventa->canton,2,"0",STR_PAD_LEFT).'</Canton>
          <Distrito>'.str_pad($pventa->distrito,2,"0",STR_PAD_LEFT).'</Distrito>
          <Barrio>'.str_pad($pventa->barrio,2,"0",STR_PAD_LEFT).'</Barrio>
          <OtrasSenas>'.$pventa->otrassenas.'</OtrasSenas>
        </Ubicacion>
        '.$xycorreo.'
      </Receptor>';
    }


    if (strlen($pventa->nota) >= 200){ $pventa->nota = substr($pventa->nota, 0, 180); }

		$xml.='
<CondicionVenta>01</CondicionVenta>
                <MedioPago>02</MedioPago>
                <DetalleServicio>
                   <LineaDetalle>
                     <NumeroLinea>1</NumeroLinea>
                     <Codigo>6511600000000</Codigo> 
                     <Cantidad>1</Cantidad>
                         <UnidadMedida>Os</UnidadMedida>
                     <Detalle>Encomienda '.$pventa->nota.'</Detalle>
                     <PrecioUnitario>'.$monto.'</PrecioUnitario>
                     <MontoTotal>'.$monto.'</MontoTotal>
                         <SubTotal>'.$monto.'</SubTotal>
                         <Impuesto>';
                 if($iva!=0)
                     $xml.='    <Codigo>01</Codigo>
                                <CodigoTarifa>08</CodigoTarifa>
                                <Tarifa>13</Tarifa>
                                <Monto>'.$iva.'</Monto>
                       ';
                 else
                      $xml.='
                               <Codigo>01</Codigo>
                               <CodigoTarifa>01</CodigoTarifa>
                               <Tarifa>0</Tarifa>
                               <Monto>0</Monto>
                      ';

                $xml.='
                         </Impuesto>
                         <MontoTotalLinea>'.$total.'</MontoTotalLinea>
                   </LineaDetalle>
                </DetalleServicio>
                <ResumenFactura>
                        <TotalServGravados>'.$monto.'</TotalServGravados>
                        <TotalGravado>'.$monto.'</TotalGravado>
                        <TotalVenta>'.$monto.'</TotalVenta>
                        <TotalVentaNeta>'.$monto.'</TotalVentaNeta>
                        <TotalImpuesto>'.$iva.'</TotalImpuesto>
                        <TotalComprobante>'.$total.'</TotalComprobante>
                </ResumenFactura>

		<InformacionReferencia>
			<TipoDoc>'.$tipref.'</TipoDoc>
			<Numero>'.$pventa->facturafe.'</Numero>
			<FechaEmision>'.$fechao.'</FechaEmision>
			<Codigo>01</Codigo>
			<Razon>Anula</Razon>
		</InformacionReferencia>
		</NotaCreditoElectronica>';

		return $xml;

	}

}

?>
