<?php
  /*include_once($rutapri.'lib/nucleo.php');
  include_once($rutapri.'lib/parametro.php');*/
  include_once('lib/nucleo.php');
//  include_once('lib/parametro.php');

  function ImprimeTiqueteF($pimpresora,$pfactura,$tiptiq){
    if(!isset($tiptiq))
      $tipptiq="T";

    if($tiptiq=="T"){
    	$tiq = new Tiquete();
    	$idtiq = $tiq->idxFactura($pimpresora,$pfactura);
      if($idtiq!=null)
       return ImprimeTiquete($idtiq);
      else
       return "Factura ".$pfactura." no existe en esta impresora";
    }
    else{
      $enco = new Encomienda();
      $idtiq = $enco->idxFactura($pimpresora,$pfactura);
      if($idtiq!=null)
         return ImprimeEncomienda($idtiq);
      else
        return "Factura ".$pfactura." no existe en esta impresora";
    }
  }

    function ImprimeTiquete($idtiquete){
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
        //$cad.=$espa." Fecha impresión: ".date("Y-m-d H:i:s")."\r\n";      
        //$cad.=$espa." Fecha facturado: ".fechatoNormal(substr($tiq->fecha,0,10))."\r\n";
        //$cad.=$espa." ------------------------------------\r\n";        

        $hora=$espa;
        for($i=0;$i<count($tiq->viaje->estaciones);$i++)
           if($tiq->viaje->estaciones[$i]->idEstacion==$tiq->estacion->idEstacion)
               $hora=$tiq->viaje->estaciones[$i]->hora;
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
        $cad.= $espa.$tiq->viaje->fruta." ".$tiq->viaje->color." ".$tiq->serial;
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
        

        /*
        $tiq->viaje->bus->Cargar();
        if($tiq->viaje->extra==1)
            $cad.= $espa."    EXTRA \r\n";
        $cad.= $espa." ".$tiq->viaje->ruta->nombre."\r\n";
        $tiq->parada1->Cargar();
        $tiq->parada2->Cargar();
        if(strlen(trim($tiq->parada1->nombre))<=20)
           $cad.= $espa." Recoger en  : ".$tiq->parada1->nombre.".\r\n";  // negrita With .T.
        else{
            $cad.= $espa." Recoger en  : ".substr($tiq->parada1->nombre,0,19)."\r\n";  // negrita With .T.
            $cad.= $espa." ".substr($tiq->parada1->nombre,19,40).".\r\n";  // negrita With .T.
        }
        $cad.= $espa." Precio      : ".number_format( $tiq->monto,2)." colones\r\n";
        if ($tiq->producto->idProducto==99)
         $cad.=" <<< TIQUETE CORTESIA >>>\r\n";
        if($tiq->asiento==-1)
            $cad.=$espa." Asiento     : DE PIE \r\n"; // , negrita With .T.
        else
            $cad.=$espa." Asiento     : ".$tiq->asiento."    . \r\n";  //negrita With .T. , grande With .T.
        $cad.= $espa." ------------------------------------\r\n";
        $cad.=$espa." La validez de este tiquete \r\n"; // , negrita With .T.
        $cad.=$espa." está sujeta a la fecha y hora \r\n"; //, negrita With .T.
        $cad.=$espa." Indicada \r\n"; //, negrita With .T.
        $cad.="\r\n";
        $cad.=$espa." Favor Presentarse 15 minutos \r\n"; //, negrita With .T.
        $cad.=$espa." antes de la salida\r\n"; //, negrita With .T.
        $cad.=$espa." Resolución 11-97 DGT\r\n";
        $cad.= "\r\n";
        $cad.= " ".$tiq->viaje->fruta." ".$tiq->viaje->color." ".$tiq->serial;
        $cad.= "\r\n";
        */
        return $cad;
}


function ImprimeEncomienda($idencomienda){
      $en = new Encomienda();
      $en->idEncomienda=$idencomienda;
      if(!$en->Cargar())
          echo "No cargo";
      $cad="";
      // $cad.=chr(27)+chr(64)   ;       // ESC @   - Inicializar impresora
      // $cad.=chr(27)+chr(61)+chr(1)  ; // ESC = n - Selecciona o cancela un periferico
      // $cad.=chr(27)+chr(116)+chr(2) ; // ESC t n - Selecciona la tabla de caracteres  spanish
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
      $en->viaje->Cargar();
      $en->viaje->ruta->Cargar();
      $en->viaje->ruta->empresa->Cargar();
      $cad.="    ".wordwrap($letraf .$en->viaje->ruta->empresa->nombre,25,"\r\n  ") . "\r\n";
      $cad.="    Ced Jur. ".$en->viaje->ruta->empresa->cedula."\r\n";
        //$cad.=$espa."  ".$par->Retorna("web",3)."\r\n";
      $cad.= $letraf. "\r\n";
      $cad.= $letran. wordwrap("    Telefono: ".$en->viaje->ruta->empresa->tele1." / ".$en->viaje->ruta->empresa->tele2, 28, "\r\n  ") .$letraf. "\r\n";
      $cad.="    Fecha Emi.: ".date("d-m-Y H:i:s")."\r\n";              
      //$cad.=" Fecha " . date('d-m-Y',time()) . "\r\n";
      $en->estacion->Cargar();
      $cad.="    Lugar Emision: ".$en->estacion->nombre ."\r\n";
      $cad.="\r\n";
      $cad.="    --------------------------------\r\n";
      $cad.="    Guia No: ***" .$letran.$en->factura.$letraf."*** \r\n";
      if($en->tipocedfa=="00"){
           $cad.=" Tiquete Electronica v4.3 : \r\n";
        }
        else{
           $cad.=" Factura Electronica v4.3 : \r\n";
        }
      $cad.="   " .$en->facturafe."\r\n";
      $cad.="    Clave: \r\n ";
      $cad.="    ".substr($en->clavefe,0,25)."\r\n";
      $cad.="    ".substr($en->clavefe,25,25)."\r\n";
      if($en->formapago->idFormaPago==3){
        $cad.="    Tipo de Venta: Contado \r\n";
        $cad.="    Medio de Pago: Efectivo\r\n";
      }
      else
      {
        if($en->formapago->idFormaPago==2){
          $cad.="    Tipo de Venta: Credito\r\n";
          $cad.="    Medio de Pago: Efectivo\r\n";
        }
        else
        {
          $cad.="    Tipo de Venta: Contado\r\n";
          $cad.="    Medio de Pago: Efectivo\r\n";
        }
      }
       if($en->retirada==1)
          $cad.="    Estado: Retirada\r\n";
      else
          $cad.="    Estado: Pendiente de retiro\r\n";


        if($en->tipocedula=="00"){
          $cad.="    Cedula: No especificada \r\n";
        }
        else{
          $cad.="    Cedula: ".$en->cedremi."\r\n";
          $cad.="    Correo: ".$en->emailremi."\r\n";
        }


      $cad.="    ------------------------------------\r\n";
     

//$cad.="    ------------------------------------\r\n";
      $en->parada->Cargar();
      $cad.= $letran. wordwrap("    Retira: " .$en->destinatario, 28, "\r\n  ") .$letraf. "\r\n";
      if($en->retirada==1)
         $cad.= $letran. wordwrap("     Retirada por : " .$en->autocedula." ".$en->autonombre, 30, "\r\n    ") .$letraf. "\r\n";
      //$cad.="    Destinatario :\r\n";
      //$cad.="    ".$en->ceddesti."\r\n";
      //$cad.="    ".$en->destinatario."\r\n\r\n\r\n";
      $cad.="    Destino: ".$en->parada->nombre."\r\n";
      $cad.="    Bultos: ".number_format($en->cantbul,0)."\r\n";
      $cad.="    Precio: ".number_format($en->monto-$en->iva,2)."\r\n";
      $cad.="    IVA: ".number_format($en->iva,2)."\r\n";
      $cad.="    Total: ".number_format($en->monto,2)."\r\n";
      $cad.="    Monto en Colones (CRC)"."\r\n";
      if($en->declarado == 0){
          $cad.="    No Declaró Valor\r\n";
      }else{
          $cad.="    Valor Declara: ".number_format($en->declarado,2)."\r\n";
      }
      $en->viaje->Cargar();
      $cad.="    Fecha Viaje: ".fechatoNormal(substr($en->viaje->fecha,0,10))."\r\n";
      $hora="";
      for($i=0;$i<count($en->viaje->estaciones);$i++)
          if($en->viaje->estaciones[$i]->idEstacion==$en->estacion->idEstacion)
              $hora=$en->viaje->estaciones[$i]->hora;
      if(substr($hora,0,2)>"12"){
          $hor = substr($hora,0,2)-12;
          $cad .= $espa."    Hora        : ".$hor. substr($hora,2,3)." PM \r\n"; //,negrita With .T. , grande With .T.
      }else
          if($hora=="12:00")
              $cad.=$espa."    Hora        : ".$hora." PM \r\n"; // , negrita With .T. , grande With .T.
          else
              $cad.=$espa."    Hora        : ".$hora." AM \r\n"; // , negrita With .T. , grande With .T.
      //$cad.= " Hora: ".$hora."\r\n"; // , negrita With .T. , grande With .T.


     
      $cad.="\r\n";
      if($en->formapago->idFormaPago==3){
            $cad.= "     ***COBRAR***     \r\n ";
      }
      else
      {
          $cad .= "     ***CANCELADA*** \r\n ";
       }
      $cad.="    -------------------------------\r\n";
      $cad.= wordwrap("    Detalle: ".$en->nota, 28, "\r\n  ") . "\r\n";
      $cad.= wordwrap("    Envia: " . $en->remitente, 28, "\r\n  ") . "\r\n";
      //$cad.="    ".$en->cedremi."\r\n";
      //$cad.="    ".$en->remitente."\r\n\r\n";
      //$cad.="    Teléfono 1   :".$en->teldesti1."\r\n";
      //$cad.="    Teléfono 2   :".$en->teldesti2."\r\n";
      //$cad.="    Peso         :".$en->peso."\r\n";
      //$cad.="    FIRMA :  _________________________\r\n\r\n\r\n";
      $cad.="    -------------------------------\r\n";
      $cad.="  Factura Amparada Res: DGT-R-48-2016\r\n";
      $cad.="  Además tiene un límite 30 día\n";
      $cad.="  naturales para retirar su encomienda\r\n";
      $cad.="  o se declara en abandono\r\n";
      $cad.="  No aplica para encomiendas\r\n con valor declarado ni dineros\r\n";
      $cad.="  Perecederos solamente 3 días\r\n para retirar o se desecha\r\n";
      $cad.="  Fecha Impre.: ".date("d-m-Y H:i:s")."\r\n";  

      $cad.= "\r\n";
      $cad.= "\r\n\r\n".chr(27).chr(105).chr(27).'i';
      // $cad.= "\r\n\r\n";
      //echo $cad;
      return $cad;
    }



    function ImprimeEncomiendaMusoc($idencomienda){
        
        $en = new Encomienda();
        $en->idEncomienda=$idencomienda;
        if(!$en->Cargar())
          echo "No cargo";

         $en->producto->cargar();
         $en->formapago->Cargar();
         $mcad="";
        //         $mcad.=chr(27)+chr(64)   ;       // ESC @   - Inicializar impresora
        //        $mcad.=chr(27)+chr(61)+chr(1)  ; // ESC = n - Selecciona o cancela un periferico
        //       $mcad.=chr(27)+chr(116)+chr(2) ; // ESC t n - Selecciona la tabla de caracteres  spanish
        //$mcad.=chr(27)+chr(97)+chr(1) ; // ESC a n - Aliniacion izquierda(0),centrada(1),derecha(2)
        //$mcad.=chr(27)+chr(33)+chr(17);  // ESC ! n - Tamano de Letra 17cpi
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
        //$mcad.="            ".$par->Retorna("nombrecia",3)."\r\n";
        //$mcad.="          Ced. ".$par->Retorna("cedula",3)."\r\n";
        //$mcad.="         ".$par->Retorna("web",3)."\r\n";
        //$mcad.=" Telefono: ".$par->Retorna("teleenco",3)."\r\n";
        $inicio2 = 100;

        // Linea 1
        $mcad.= str_repeat(" ", 1) . "\r\n";
        // Linea 2
        $dato = str_repeat(" ", 26) . $en->fecha;
        //$dato = str_repeat(" ", 26) . fechatoDia(substr($en->fecha,0,10)).
        //        str_repeat(" ", 3).fechatoMes(substr($en->fecha,0,10)).
        //        str_repeat(" ", 3).fechatoAnno(substr($en->fecha,0,10));    
        //$dato2 = fechatoDia(substr($en->fecha,0,10)).
        //        str_repeat(" ", 2).fechatoMes(substr($en->fecha,0,10)).
        //        str_repeat(" ", 2).fechatoAnno(substr($en->fecha,0,10)); 
        $mcad.= $dato . str_repeat(" ", 28) . $dato2 . "\r\n";
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
        $mcad.= str_repeat(" ", 5) . $dato . str_repeat(" ", 4) . $dato2 . "\r\n";
        // Linea 4
        $dato = $en->remitente;
        $dato2 = $en->remitente;
        $espacio = 41 - strlen($dato);
        $mcad.=  str_repeat(" ", 7) . $dato . str_repeat(" ", $espacio) . $dato2 . "\r\n";
        // Linea 5
        $dato = $en->destinatario;
        $dato2 = $en->destinatario;
        $espacio = 41 - strlen($dato);
        $mcad.=  str_repeat(" ", 7) . $dato . str_repeat(" ", $espacio) . $dato2 . "\r\n";
        // Linea 6
        $mcad.= str_repeat(" ", 1) . "\r\n";
        // Linea 7
        $mcad.= str_repeat(" ", 1) . "\r\n";
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
        $dato = number_format($en->cantbul,0) . " " . $texto . str_repeat(" ", $espacio) . number_format($en->monto,2);
        $dato2 = number_format($en->cantbul,0) . " " . $texto . str_repeat(" ", $espa2) .  number_format($en->monto,2);
        $espacio = 39 - strlen($dato);
        $mcad.=  str_repeat(" ", 4) . $dato . str_repeat(" ", $espacio) . $dato2 . "\r\n";
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
        $mcad.= str_repeat(" ", 4) . $texto . str_repeat(" ", $espacio) . $texto . "\r\n";
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
        $mcad.= str_repeat(" ", 4) . $texto . str_repeat(" ", $espacio) . $texto . "\r\n";
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
        $mcad.= str_repeat(" ", 14) . $dato . str_repeat(" ", 12) . $dato2 . "\r\n";
        // Linea 11
        $en->usuario->Cargar();  
        $dato = "Hecho por " . $en->usuario->nombre . "    Recibo " . $en->factura;
        $dato2 = "Hecho por " . $en->usuario->nombre . "    Recibo " . $en->factura;
        $espacio = 37 - strlen($dato);
        $mcad.=  str_repeat(" ", 7) . $dato . str_repeat(" ", $espacio) . $dato2 . "\r\n";
        // Linea 12
        $mcad.= str_repeat(" ", 1) . "\r\n";
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
        $mcad.= str_repeat(" ", 16) . $dato . str_repeat(" ", $espacio) . $dato2 . "\r\n";
        // Linea 14
        $mcad.= str_repeat(" ", 1) . "\r\n";
        // Linea 15
        $mcad.= str_repeat(" ", 1) . "\r\n";
        // Linea 16
        $mcad.= str_repeat(" ", 1) . "\r\n";
        // Linea 17
        $mcad.= str_repeat(" ", 1) . "\r\n";
        // Linea 18
        $mcad.= str_repeat(" ", 1) . "\r\n";
        // Linea 19
        $mcad.= str_repeat(" ", 1) . "\r\n";
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
        $mcad.= str_repeat(" ",17) . $dato . str_repeat(" ", 14) . $dato2 . "\r\n";
        // Linea 19
        $dato = $en->remitente;
        $dato2 = ""; 
        $espacio = 10 - strlen($dato);
        $mcad.=  str_repeat(" ", 20) . $dato . str_repeat(" ", $espacio) . $dato2 . "\r\n";
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
         $dato2 .= $en->fecha;
        //$dato2 .= fechatoDia(substr($en->fecha,0,10)).
        //        str_repeat(" ", 1).fechatoMes(substr($en->fecha,0,10)).
        //        str_repeat(" ", 1).fechatoAnno(substr($en->fecha,0,10)); 
        $mcad.=  str_repeat(" ", 20) . $dato . str_repeat(" ", $espacio) . $dato2 . "\r\n";
        // Linea 21
        $dato = "";
        $dato2 = "Hora ".date("H:i");
        $espacio = 62 - strlen($dato);
        $mcad.=  $dato . str_repeat(" ", $espacio) . $dato2 . "\r\n";
        // Linea 22
        $dato = "";
        $dato2 = "Recibo " . $en->factura;
        $espacio = 48 - strlen($dato);
        $mcad.=  str_repeat(" ", 5) . $dato . str_repeat(" ", $espacio) . $dato2 . "\r\n";
        // Linea 23
        if (strlen($en->nota) > 23){
            $texto = substr($en->nota, 0, 22);
            $espacio = 1;
        }else{
            $texto = $en->nota;
            $espacio = 22 - strlen($en->nota);
        }
        $dato = number_format($en->cantbul,0) . " " . $texto . str_repeat(" ", $espacio) . number_format($en->monto,2);
        if($en->declarado == 0){
            $dato2 = "No Declaró Valor";
        }
        else{
            $dato2 = number_format($en->declarado,2);
        }
        $espacio = 58 - strlen($dato);
        $mcad.=  str_repeat(" ", 5) . $dato . str_repeat(" ", $espacio) . $dato2 . "\r\n";
        // Linea 24
        $mcad.= str_repeat(" ", 1) . "\r\n";
        // Linea 25
        $mcad.= str_repeat(" ", 1) . "\r\n";
        // Linea 26
        if($en->declarado == 0){
            $dato = "No Declaró Valor";
        }
        else{
            $dato = number_format($en->declarado,2);
        }
        $dato.= "  Hecho por " . $en->usuario->nombre;
        $mcad.=  str_repeat(" ", 11) . $dato . "\r\n";
        return $mcad;
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

function ImprimeEncomiendaNC($idencomienda){
        
        $en = new Encomienda();
        $en->idEncomienda=$idencomienda;
        if(!$en->Cargar())
          echo "No cargo";
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
        $espa='';
        $par=new Parametro(); 
        $cad.="            ".$par->Retorna("nombreciae",3)."\r\n";
        $cad.="          Ced. ".$par->Retorna("cedulae",3)."\r\n";
        $cad.="         ".$par->Retorna("web",3)."\r\n";
        $cad.=" Telefono: ".$par->Retorna("teleenco",3)."\r\n";
    
        $cad.=" Fecha Emi.: ".date("d-m-Y H:i:s")."\r\n";              
        //$cad.=" Fecha " . date('d-m-Y',time()) . "\r\n";
    $en->estacion->Cargar();
        $cad.=" Lugar Emision: ".$en->estacion->nombre ."\r\n";
        $cad.="\r\n";
        $cad.=" --------------------------------\r\n";
        $cad.=" Guia No: " .$letrai.$en->factura.$letraf.".\r\n";
        $cad.=" Nota de Crédito: " .$letrai.$en->facturancfe.".\r\n";
        $cad.=" Clave: " .$letrai.$en->clavencfe.".\r\n";
        $cad.=" Basado en factura: ".$letrai.$en->facturafe.".\r\n";
        //$cad.="    ------------------------------------\r\n";
        $en->parada->Cargar();

        $cad.=" Retira: " .$en->destinatario . "\r\n";

        //$cad.="    Destinatario :\r\n";
        //$cad.="    ".$en->ceddesti."\r\n";
        //$cad.="    ".$en->destinatario."\r\n\r\n\r\n";

        $cad.=" Destino: ".$en->parada->nombre."\r\n";
        $cad.=" Bultos: ".number_format($en->cantbul,0)."\r\n";
        $cad.=" Precio: ".number_format($en->monto,2)."\r\n";
        if($en->declarado == 0){
            $cad.=" No Declaró Valor\r\n";
    }
        else{
            $cad.=" Valor Declara: ".number_format($en->declarado,2)."\r\n";
    }

        $en->viaje->Cargar();
        $cad.=" Fecha Viaje: ".fechatoNormal(substr($en->viaje->fecha,0,10))."\r\n";
        $hora="";
        for($i=0;$i<count($en->viaje->estaciones);$i++)
           if($en->viaje->estaciones[$i]->idEstacion==$en->estacion->idEstacion)
               $hora=$en->viaje->estaciones[$i]->hora;
               
      if(substr($hora,0,2)>"12"){
           $hor = substr($hora,0,2)-12;
       $cad .= $espa." Hora        : ".$hor. substr($hora,2,3)." PM \r\n"; //,negrita With .T. , grande With .T.
           }
        else
            if($hora=="12:00")
              $cad.=$espa." Hora        : ".$hora." PM \r\n"; // , negrita With .T. , grande With .T.
           else
              $cad.=$espa." Hora        : ".$hora." AM \r\n"; // , negrita With .T. , grande With .T.


        //$cad.= " Hora: ".$hora."\r\n"; // , negrita With .T. , grande With .T.
        $cad.="\r\n";
  
        $cad.=" -------------------------------\r\n";

    
      $cad.= wordwrap(" Detalle: ".$en->nota, 36, "\r\n ") . "\r\n";

        $cad.=" Envia: " . $en->remitente . "\r\n";
        //$cad.="    ".$en->cedremi."\r\n";
        //$cad.="    ".$en->remitente."\r\n\r\n";

    //$cad.="    Teléfono 1   :".$en->teldesti1."\r\n";
        //$cad.="    Teléfono 2   :".$en->teldesti2."\r\n";
        //$cad.="    Peso         :".$en->peso."\r\n";
        //$cad.="    FIRMA :  _________________________\r\n\r\n\r\n";

        $cad.=" -------------------------------\r\n";
        $cad.=" Factura Amparada Res:  DGT-R-48-2016\r\n";
        $cad.= "\r\n";

//   $cad.= "\r\n\r\n".chr(27).chr(105).chr(27).'i';
   //      $cad.= "\r\n\r\n";
         return $cad;
        
        
    }


?>
