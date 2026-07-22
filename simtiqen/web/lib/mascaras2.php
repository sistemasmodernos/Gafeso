<?php
  /*include_once($rutapri.'lib/nucleo.php');
  include_once($rutapri.'lib/parametro.php');*/
  include_once('lib/nucleo.php');
//  include_once('lib/parametro.php');

  function ImprimeTiqueteF($pimpresora,$pfactura){
    $tiq = new Tiquete();
    $idtiq = $tiq->idxFactura($pimpresora,$pfactura);
    if($idtiq!=null)
       return ImprimeTiquete($idtiq);
    $enco = new Encomienda();
    $idtiq = $enco->idxFactura($pimpresora,$pfactura);
    if($idtiq!=null)
       return ImprimeEncomienda($idtiq);
    else
      return "Factura ".$pfactura." no existe en esta impresora";
    
  }
    function ImprimeTiquete($idtiquete){
        $tiq = new Tiquete();
    //    echo " El id es ". $idtiquete;
        $tiq->idTiquete=$idtiquete;
        if(!$tiq->Cargar()){
           echo "No cargo";
         }
        $cad="";
        $letrai = chr(27).chr(33).chr(32);
		    $letraf = chr(27).chr(33).chr(1);
		    $letran = chr(27).chr(33).chr(8);
		    $letrac = chr(27).chr(33).chr(0);
        $letrai = '';
        $letraf = '';
        $letran = '';
        $letrac = '';
        $espa=' ';
        $par=new Parametro();
        $tiq->viaje->Cargar();
        $tiq->viaje->ruta->Cargar();
        $tiq->viaje->ruta->empresa->Cargar();
        $tiq->viaje->tipoviaje->Cargar();
        $tiq->estacion->Cargar();
        $inicio2 = 100;

        // Linea 1
        //$cad.= str_repeat(" ", 1) . "\r\n";
        
        // Linea 2
        $cad.= str_repeat(" ", 1) . "\r\n";
        // Linea 3
        $dato = str_repeat(" ", 22) . fechatoDia(substr($tiq->viaje->fecha,0,10)).
                str_repeat(" ", 2).fechatoMes(substr($tiq->viaje->fecha,0,10)).
                str_repeat(" ", 2).fechatoAnno(substr($tiq->viaje->fecha,0,10));        
        $dato2 = fechatoDia(substr($tiq->viaje->fecha,0,10)).
                 str_repeat(" ", 1).fechatoMes(substr($tiq->viaje->fecha,0,10)).
                 str_repeat(" ", 1).fechatoAnno(substr($tiq->viaje->fecha,0,10));;
        //$espacio = $inicio2 - strlen($dato);
        $cad.= $dato . str_repeat(" ", 10) . $dato2 . "\r\n";
	
	$cad.= str_repeat(" ", 1) . "\r\n";
        
 	$hora="";
        $hor="";
        for($i=0;$i<count($tiq->viaje->estaciones);$i++)
            if($tiq->viaje->estaciones[$i]->idEstacion==$tiq->estacion->idEstacion)
                $hora=$tiq->viaje->estaciones[$i]->hora;
        if(substr($hora,0,2)>"11"){
            if(substr($hora,0,2)=="12"){
                $hor = substr($hora,0,2);
            }else{
                $hor = substr($hora,0,2)-12;
            }
            $hor = $hor . substr($hora,2,3) . " PM";
        }else
            if($hora=="12:00")
                $hor.=" " . $hora . " PM";
            else
                $hor.=" " . $hora ." AM";



        // Linea 4
        if($tiq->asiento==-1){
            $dato	= "DE PIE              ".$hor;
            $dato2	="DE PIE              ".$hor;
        }else{
            $dato	=" " . $tiq->asiento."                 ".$hor;
            $dato2	=      $tiq->asiento."       ".$hor;
        }
        $espacio = $inicio2 - strlen($dato);
        if ($espacio < 1){ $espacio = 1; }
       	 $cad.= str_repeat(" ", 5) . $dato . str_repeat(" ", 10) . $dato2 . "\r\n";

        // Linea 5
        $dato= "Valido para el ";
        $dato2= "Valido ". $letrai . fechatoNormal(substr($tiq->viaje->fecha,0,10));
        $espacio = 34 - strlen($dato);
        if ($espacio < 1){ $espacio = 1; }
	     $cad.= str_repeat(" ", 3) . $dato . str_repeat(" ", $espacio) . $dato2 . "\r\n";
        // Linea 6
        $dato= fechatoNormal(substr($tiq->viaje->fecha,0,10));
        $dato2="";

        $espacio = 34 - strlen($dato);
        if ($espacio < 1){ $espacio = 1; }
        	$cad.= str_repeat(" ", 4) . $dato . str_repeat(" ", $espacio) . $dato2 . "\r\n";
        // Linea 7
        $tiq->parada1->Cargar();
        $tiq->parada2->Cargar();
        $dato = "Ruta 607    " . $tiq->viaje->ruta->abreviatura;
        $dato2 = $tiq->viaje->ruta->abreviatura;
        $espacio = 33 - strlen($dato);
        if ($espacio < 1){ $espacio = 1; }
        $cad.= str_repeat(" ", 2) . $dato . str_repeat(" ", $espacio) . $dato2 . "\r\n";
        // Linea 8
        //$dato= "Bus " . $tiq->viaje->bus->placa;
        //$dato2= "Bus " . $tiq->viaje->bus->placa;
        //$espacio = $inicio2 - strlen($dato);
        $dato = $tiq->parada1->nombre." - " . $tiq->parada2->nombre;
        $dato2 = $tiq->parada1->nombre." - " . $tiq->parada2->nombre;
        $espacio = 33 - strlen($dato);
        if ($espacio < 1){ $espacio = 1; }
        $cad.= str_repeat(" ", 2) . $dato . str_repeat(" ", $espacio) . $dato2 . "\r\n";
        // Linea 9
        $dato = " ".number_format( $tiq->monto,2);
        $dato2 = number_format( $tiq->monto,2);
        $espacio = 32 - strlen($dato);
        if ($espacio < 1){ $espacio = 1; }
        $cad.= str_repeat(" ", 8) . $dato . str_repeat(" ", $espacio) . $dato2 . "\r\n";
        // Linea 10
        //$cad.= str_repeat(" ", 1) . "\r\n";
        // Linea 11
        $dato = "No ".$tiq->factura;
        $dato2 = "No ".$tiq->factura;        
        $espacio = 22 - strlen($dato);
        if ($espacio < 1){ $espacio = 1; }
        $cad.= str_repeat(" ", 20) . $dato . str_repeat(" ", $espacio) . $dato2 . "\r\n";

        $cad.= str_repeat(" ", 1) . "\r\n";

	$tiq->usuario->Cargar();
        $dato = $tiq->usuario->nombre;
        $dato2 = $tiq->usuario->nombre;
        $espacio = 22 - strlen($dato);
        if ($espacio < 1){ $espacio = 1; }
        $cad.= str_repeat(" ", 20) . $dato . str_repeat(" ", $espacio) . $dato2 . "\r\n";

        return $cad;
    }

    function ImprimeEncomienda($idencomienda){
        
        $en = new Encomienda();
        $en->idEncomienda=$idencomienda;
        if(!$en->Cargar())
          echo "No cargo";

         $en->producto->cargar();
         $en->formapago->Cargar();
         $cad="";
        //         $cad.=chr(27)+chr(64)   ;       // ESC @   - Inicializar impresora
        //        $cad.=chr(27)+chr(61)+chr(1)  ; // ESC = n - Selecciona o cancela un periferico
        //       $cad.=chr(27)+chr(116)+chr(2) ; // ESC t n - Selecciona la tabla de caracteres  spanish
        //$cad.=chr(27)+chr(97)+chr(1) ; // ESC a n - Aliniacion izquierda(0),centrada(1),derecha(2)
        //$cad.=chr(27)+chr(33)+chr(17);  // ESC ! n - Tamano de Letra 17cpi
        $letrai = chr(27).chr(33).chr(32);
        $letraf = chr(27).chr(33).chr(1);
        $letran = chr(27).chr(33).chr(8);
        $letrac = chr(27).chr(33).chr(0); 
        $letrai = '';
        $letraf = '';
        $letran = '';
        $letrac = ''; 
        $espa='';
        $par=new Parametro(); 
        //$cad.="            ".$par->Retorna("nombrecia",3)."\r\n";
        //$cad.="          Ced. ".$par->Retorna("cedula",3)."\r\n";
        //$cad.="         ".$par->Retorna("web",3)."\r\n";
        //$cad.=" Telefono: ".$par->Retorna("teleenco",3)."\r\n";
        $inicio2 = 100;

        // Linea 1
        $cad.= str_repeat(" ", 1) . "\r\n";
        // Linea 2
        $dato = str_repeat(" ", 26) . fechatoDia(substr($en->fecha,0,10)).
                str_repeat(" ", 3).fechatoMes(substr($en->fecha,0,10)).
                str_repeat(" ", 3).fechatoAnno(substr($en->fecha,0,10));    
        $dato2 = fechatoDia(substr($en->fecha,0,10)).
                str_repeat(" ", 2).fechatoMes(substr($en->fecha,0,10)).
                str_repeat(" ", 2).fechatoAnno(substr($en->fecha,0,10)); 
        $cad.= $dato . str_repeat(" ", 28) . $dato2 . "\r\n";
        // Linea 3
        if($en->producto->tipo!=3){
            $dato = "**** ENCOMIENDA ****" . str_repeat(" ", 5) . "Hora ".date("H:i");
            $dato2 = "**** ENCOMIENDA ****" . str_repeat(" ", 2) . "Hora ".date("H:i");
         }
         else
         {
            $dato = "*** TRANSFERENCIA **" . str_repeat(" ", 5) . "Hora ".date("H:i");
            $dato2 = "*** TRANSFERENCIA **" . str_repeat(" ", 2) . "Hora ".date("H:i");  
         }
        $cad.= str_repeat(" ", 5) . $dato . str_repeat(" ", 4) . $dato2 . "\r\n";
        // Linea 4
        $dato = $en->remitente;
        $dato2 = $en->remitente;
        $espacio = 41 - strlen($dato);
        if ($espacio < 1){ $espacio = 1; }
        $cad.=  str_repeat(" ", 7) . $dato . str_repeat(" ", $espacio) . $dato2 . "\r\n";
        // Linea 5
        $dato = $en->destinatario;
        $dato2 = $en->destinatario;
        $espacio = 41 - strlen($dato);
        if ($espacio < 1){ $espacio = 1; }
        $cad.=  str_repeat(" ", 7) . $dato . str_repeat(" ", $espacio) . $dato2 . "\r\n";
        // Linea 6
        $cad.= str_repeat(" ", 1) . "\r\n";
        // Linea 7
        $cad.= str_repeat(" ", 1) . "\r\n";
        // Linea 8
        if (strlen($en->nota) > 23){
            $texto = substr($en->nota, 0, 23);
            $espacio = 1;
        }else{
            $texto = $en->nota;
            $espacio = 26 - strlen($en->nota);
        }
        if ($espacio>2){
            $espa2 = $espacio - 2;
        }else{
            $espa2 = 1;
        }
        if ($espacio < 1){ $espacio = 1; }
        $dato = number_format($en->cantbul,0) . " " . $texto . str_repeat(" ", $espacio) . number_format($en->monto,2);
        $dato2 = number_format($en->cantbul,0) . " " . $texto . str_repeat(" ", $espa2) .  number_format($en->monto,2);
        $espacio = 39 - strlen($dato);
        if ($espacio < 1){ $espacio = 1; }
        $cad.=  str_repeat(" ", 4) . $dato . str_repeat(" ", $espacio) . $dato2 . "\r\n";
        // Linea 8
        $texto = "";
        if (strlen($en->nota) > 23){
            if (strlen($en->nota) > 52){
                $texto = substr($en->nota, 23, 30);
            }else{
                $texto = substr($en->nota, 23);
            }
        }    
        $espacio = 39 - strlen($texto);
        if ($espacio < 1){ $espacio = 1; }
        $cad.= str_repeat(" ", 4) . $texto . str_repeat(" ", $espacio) . $texto . "\r\n";
        // Linea 9
        $texto = "";
        if (strlen($en->nota) > 52){
            if (strlen($en->nota) > 80){
                $texto = substr($en->nota, 53, 30);
            }else{
                $texto = substr($en->nota, 53);
            }
        }
        $espacio = 39 - strlen($texto);
        if ($espacio < 1){ $espacio = 1; }
        $cad.= str_repeat(" ", 4) . $texto . str_repeat(" ", $espacio) . $texto . "\r\n";
        // Linea 10
        if($en->formapago->idFormaPago==3){
            $dato = "**** COBRAR ****";
            $dato2 = "**** COBRAR ****";
         }
         else
         {
            $dato = "";
            $dato2 = "";
         }
        $cad.= str_repeat(" ", 14) . $dato . str_repeat(" ", 12) . $dato2 . "\r\n";
        // Linea 11
        $en->usuario->Cargar();  
        $dato = "Hecho por " . $en->usuario->nombre . "    Recibo " . $en->factura;
        $dato2 = "Hecho por " . $en->usuario->nombre . "    Recibo " . $en->factura;
        $espacio = 37 - strlen($dato);
        if ($espacio < 1){ $espacio = 1; }
        $cad.=  str_repeat(" ", 7) . $dato . str_repeat(" ", $espacio) . $dato2 . "\r\n";
        // Linea 12
        $cad.= str_repeat(" ", 1) . "\r\n";
        // Linea 13
        if($en->declarado == 0){
            $dato = "No Declaró Valor";
            $dato2 = "No Declaró Valor";
        }
        else{
            $dato = number_format($en->declarado,2);
            $dato2 = number_format($en->declarado,2);
        }
        $espacio = 41 - strlen($dato);
        if ($espacio < 1){ $espacio = 1; }
        $cad.= str_repeat(" ", 16) . $dato . str_repeat(" ", $espacio) . $dato2 . "\r\n";
        // Linea 14
        $cad.= str_repeat(" ", 1) . "\r\n";
        // Linea 15
        $cad.= str_repeat(" ", 1) . "\r\n";
        // Linea 16
        $cad.= str_repeat(" ", 1) . "\r\n";
        // Linea 17
        $cad.= str_repeat(" ", 1) . "\r\n";
        // Linea 18
        $cad.= str_repeat(" ", 1) . "\r\n";
        // Linea 19
        $cad.= str_repeat(" ", 1) . "\r\n";
        // Linea 20
        if($en->producto->tipo!=3){
            $dato = "**** ENCOMIENDA ****";
            $dato2 = "**** ENCOMIENDA ****";
         }
         else
         {
            $dato = "*** TRANSFERENCIA **";
            $dato2 = "*** TRANSFERENCIA **";
         }
        $cad.= str_repeat(" ",17) . $dato . str_repeat(" ", 14) . $dato2 . "\r\n";
        // Linea 19
        $dato = $en->remitente;
        $dato2 = ""; 
        $espacio = 10 - strlen($dato);
        if ($espacio < 1){ $espacio = 1; }
        $cad.=  str_repeat(" ", 20) . $dato . str_repeat(" ", $espacio) . $dato2 . "\r\n";
        // Linea 20
        $dato = $en->destinatario;
        $espacio = 46 - strlen($dato);
        if($en->formapago->idFormaPago==3){
            $dato2 = "*COBRAR*      ";
            $espacio = $espacio - 12;
         }
         else
         {
            $dato2 = "";
         }
        if ($espacio < 1){ $espacio = 1; }
        $dato2 .= fechatoDia(substr($en->fecha,0,10)).
                str_repeat(" ", 1).fechatoMes(substr($en->fecha,0,10)).
                str_repeat(" ", 1).fechatoAnno(substr($en->fecha,0,10)); 
        $cad.=  str_repeat(" ", 20) . $dato . str_repeat(" ", $espacio) . $dato2 . "\r\n";
        // Linea 21
        $dato = "";
        $dato2 = "Hora ".date("H:i");
        $espacio = 62 - strlen($dato);
        if ($espacio < 1){ $espacio = 1; }
        $cad.=  $dato . str_repeat(" ", $espacio) . $dato2 . "\r\n";
        // Linea 22
        $dato = "";
        $dato2 = "Recibo " . $en->factura;
        $espacio = 48 - strlen($dato);
        if ($espacio < 1){ $espacio = 1; }
        $cad.=  str_repeat(" ", 5) . $dato . str_repeat(" ", $espacio) . $dato2 . "\r\n";
        // Linea 23
        if (strlen($en->nota) > 23){
            $texto = substr($en->nota, 0, 22);
            $espacio = 1;
        }else{
            $texto = $en->nota;
            $espacio = 22 - strlen($en->nota);
        }
        if ($espacio < 1){ $espacio = 1; }
        $dato = number_format($en->cantbul,0) . " " . $texto . str_repeat(" ", $espacio) . number_format($en->monto,2);
        if($en->declarado == 0){
            $dato2 = "No Declaró Valor";
        }
        else{
            $dato2 = number_format($en->declarado,2);
        }
        $espacio = 58 - strlen($dato);
        if ($espacio < 1){ $espacio = 1; }
        $cad.=  str_repeat(" ", 5) . $dato . str_repeat(" ", $espacio) . $dato2 . "\r\n";
        // Linea 24
        $cad.= str_repeat(" ", 1) . "\r\n";
        // Linea 25
        $cad.= str_repeat(" ", 1) . "\r\n";
        // Linea 26
        if($en->declarado == 0){
            $dato = "No Declaró Valor";
        }
        else{
            $dato = number_format($en->declarado,2);
        }
        $dato.= "  Hecho por " . $en->usuario->nombre;
        $cad.=  str_repeat(" ", 11) . $dato . "\r\n";
        return $cad;
    }

    function ImprimeSticker($idencomienda){
        $enc = new Encomienda();
        $enc->idEncomienda = $idencomienda;
        if(!$enc->Cargar())
            echo "No cargo " . $idencomienda;
        $cad="";
        $letrai = chr(27).chr(33).chr(32);
        $letraf = chr(27).chr(33).chr(1);
        $letran = chr(27).chr(33).chr(8);
        $letrac = chr(27).chr(33).chr(0); 
        $letrai = '';
        $letraf = '';
        $letran = '';
        $letrac = ''; 
        $espa='';
        $cad = " " . $enc->factura . "\r\n  " . "\r\n";
        return $cad;
    }


    function ImprimeTiqueteStandard($idtiquete){
        $tiq = new Tiquete();
        $tiq->idTiquete=$idtiquete;
        if(!$tiq->Cargar()){
           echo "No cargo";
         }
        $tiq->viaje->Cargar();
        $tiq->viaje->ruta->Cargar();
        $tiq->estacion->Cargar();
        $tiq->usuario->Cargar();
        $tiq->impresora->Cargar();
        $tiq->parada1->Cargar();
        $tiq->parada2->Cargar();
        $tiq->producto->Cargar();
        $cad="";
        $espa='   ';
        $par=new Parametro();
        $cad.=$espa."".$par->Retorna("nombrecia",3)."\r\n";
        $cad.=$espa."Cédula ".$par->Retorna("cedula",3)."\r\n";
        $cad.=$espa."Telefono ".$par->Retorna("telefono",3)."\r\n";
        $cad.="\r\n";
        $cad.=$espa."Ruta: ".$tiq->viaje->ruta->nombre ."\r\n";
        $cad.=$espa."Boletero: ".$tiq->usuario->nombre ."\r\n";
        $cad.=$espa."Terminal: ".$tiq->impresora->nombre ."\r\n";
        
        $cad.="\r\n";

        $hora=$espa;
        for($i=0;$i<count($tiq->viaje->estaciones);$i++)
           if($tiq->viaje->estaciones[$i]->idEstacion==$tiq->estacion->idEstacion)
               $hora=$tiq->viaje->estaciones[$i]->hora;
        if(substr($hora,0,2)>"12"){
            $hor = substr($hora,0,2)-12;
           $cad .= $espa."Hora de Salida : ".$hor. substr($hora,2,3)." PM \r\n";
           }
        else
            if($hora>="12:00")
             $cad.=$espa."Hora de Salida : ".$hora." PM \r\n";
            else
              $cad.=$espa."Hora de Salida : ".$hora." AM \r\n";


        $cad.=$espa."Fecha de Salida : ".fechatoNormal(substr($tiq->viaje->fecha,0,10))."\r\n";
        $cad.="\r\n";
        if(strlen(trim($tiq->parada2->nombre))<=20)
           $cad.= $espa."Destino: ".$tiq->parada2->nombre.".\r\n";  // negrita With .T.
        else{
            $cad.= $espa."Destino: ".substr($tiq->parada2->nombre,0,19)."\r\n";
            $cad.= $espa." ".substr($tiq->parada2->nombre,19,40).".\r\n";
        }
        $cad.=$espa."Tiquete: ".$tiq->producto->nombre."\r\n";        
        if($tiq->asiento==-1)
            $cad.=$espa."Asiento: DE PIE \r\n";
        else
            $cad.=$espa."Asiento: ".$tiq->asiento.".\r\n";
        $cad.=$espa."Folio: ".$tiq->factura."\r\n";        
        $cad.= $espa."Tarifa: ".number_format($tiq->monto,2)."\r\n";
        if (strlen($tiq->anombre) > 0){
            $cad.="\r\n";
            $cad.= $espa."Pasajero: ".$tiq->anombre."\r\n";
        }
        $cad.="\r\n";
        $cad.="\r\n";
        $cad.=$espa."========================\r\n";        
        if(substr($hora,0,2)>"12"){
            $hor = substr($hora,0,2)-12;
           $cad .= $espa."Hora de Salida : ".$hor. substr($hora,2,3)." PM \r\n";
           }
        else
            If($hora>="12:00")
             $cad.=$espa."Hora de Salida : ".$hora." PM \r\n";
            else
              $cad.=$espa."Hora de Salida : ".$hora." AM \r\n";

        $cad.=$espa."Fecha de Salida : ".fechatoNormal(substr($tiq->viaje->fecha,0,10))."\r\n";
        if(strlen(trim($tiq->parada2->nombre))<=20)
           $cad.= $espa."Destino: ".$tiq->parada2->nombre.".\r\n";  // negrita With .T.
        else{
            $cad.= $espa."Destino: ".substr($tiq->parada2->nombre,0,19)."\r\n";
            $cad.= $espa." ".substr($tiq->parada2->nombre,19,40).".\r\n";
        }
        $cad.=$espa."Tiquete: ".$tiq->producto->nombre."\r\n";        
        if($tiq->asiento==-1)
            $cad.=$espa."Asiento: DE PIE \r\n";
        else
            $cad.=$espa."Asiento: ".$tiq->asiento.".\r\n";

        $cad.=$espa."Folio: ".$tiq->factura."\r\n";        
        $cad.= $espa."Tarifa: ".number_format($tiq->monto,2)."\r\n";
        if (strlen($tiq->anombre) > 0){
            $cad.= $espa."Pasajero: ".$tiq->anombre."\r\n";
        }

        return $cad;
  }




?>
