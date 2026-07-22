<?php
    session_start();
include_once('lib/nucleo.php');
/*Este php sirve solo para mandar por medio de Ajax valores r'apidos sin html
*/
$opcion=$_GET["opcion"];
switch($opcion)
{
    case "siguienteasiento":
       $res = SiguienteAsiento($_GET["viaje"],$_GET["cantidad"]);
       echo $res;
       break;
    case "precioboleto":
        $res=precioProducto($_GET["ruta"],$_GET["parada1"],$_GET["parada2"],$_GET["producto"]);
        echo $res;
        break;
    case "datosdefault":
        $res =DatosDefault($_GET["ruta"],$_GET["estacion"]);
        echo date('d/m/Y',strtotime($res["fecha"]) ).",";
        echo $res["idviaje"].",";
        //parche especial para orotina
      //  if(($res["parada1"]==2) && ($_SESSION["parametros"]["paradaespecial"]==58))
      //     echo $_SESSION["parametros"]["paradaespecial"].",";
      //  else
           echo $res["parada1"].",";
       
        echo $res["parada2"].",";
        echo $res["producto"].",";
        echo $res["precio"].",";
        $res=ParadasxRuta($_GET["ruta"],1);
        for($x=0;$x<count($res);$x++){
            echo "<option value='".$res[$x]["idparada"]."'>".$res[$x]["parada"]."</option>";
        }
        echo ",";
        $res=ParadasxRuta($_GET["ruta"],2);
         for($x=0;$x<count($res);$x++){
            echo "<option value='".$res[$x]["idparada"]."'>".$res[$x]["parada"]."</option>";
        }
      break;
    case "siguientefactura":
          $res = SiguienteFactura($_GET["impresora"]);
          echo $res;
        break;
     case "siguientefacturae":
          $res = SiguienteFacturae($_GET["impresora"]);
          echo $res;
        break;
    case "datoscliente":
        $res = ClienteCedula($_GET["cedula"]);
        if($res)
        {
			echo strtr($res["nombre"],","," "). ",";
            echo strtr($res["telefono"],","," ").",";
            echo strtr($res["email"],",",";").",";
            echo $res["fechanac"].",";
            echo $res["provincia"].",";
            echo $res["canton"].",";
            echo $res["distrito"].",";
            echo $res["barrio"].",";
            echo $res["otrasSenas"].",";
			echo $res["poriva"].",";
            echo strtr($res["actividad"] ?? '', ",", " ");
        }
        break;
    case "datosenco":
        $res = ClienteSIC($_GET["cedula"]);
        if($res)
        {
			echo strtr($res["nombre"] ?? '',","," "). ",";
            echo strtr($res["telefono"] ?? '',","," ").",";
            echo strtr($res["email"] ?? '',",",";").",";
            echo $res["fechanac"].",";
            echo $res["provincia"].",";
            echo $res["canton"].",";
            echo $res["distrito"].",";
            echo $res["barrio"].",";
            echo strtr($res["otrasSenas"] ?? '', ",", " ").",";
			echo $res["poriva"].",";
            echo strtr($res["actividad"] ?? '', ",", " ");
        }
        break;
	case "marcaobjeto":
        
        $res= MarcarObjeto($_GET["perfil"],$_GET["objeto"],$_GET["valor"]);
        echo "correcto";
     break;
    case "datosjuri":
        $res = ClienteJurSIC($_GET["cedula"]);
        if($res)
        {
				echo strtr($res["nombre"],","," "). ",";
            echo strtr($res["telefono"],","," ").",";
            echo strtr($res["email"],",",";").",";
            echo $res["fechanac"].",";
            echo $res["provincia"].",";
            echo $res["canton"].",";
            echo $res["distrito"].",";
            echo $res["barrio"].",";
            echo $res["otrasSenas"].",";
				echo $res["poriva"];

        }
        break;
	case "marcaobjeto":
        $res= MarcarObjeto($_GET["perfil"],$_GET["objeto"],$_GET["valor"]);
        echo "correcto";
     break;
     case "revisaaldultomayor":
        echo "<div><div id='mensajeadulto'>".revisaAdultoMayor($_GET["cedula"],$_GET["fecha"],$_GET["ruta"])."</div></div>";
     break;
     
     case "devtiq":
         $tiq= devTiqueteF($_GET["impresora"],$_GET["factura"]);
         if(count($tiq)>0){
         echo "<div><div id='divtiq'><table border='1'>";
             $asiento=$tiq["asiento"];
             if($asiento==-1)
               $asiento='De Pie';
             echo "<tr>".
             "<td><input type='checkbox' name='ckmarca[]' value='".$tiq["idtiquete"]."'> </td>".
             "<td>".$tiq["ruta"]."</td>".
             "<td>".fechatoNatural(substr($tiq["fechaviaje"],0,10))." ".$tiq["hora"]."</td>".
             "<td>".$tiq["factura"]."</td>".
             "<td>".$asiento."</td>".
             "<td>".$tiq["monto"]."</td>".
             "</tr></table></div></div>";
     }
     else
         echo "<div><div id='divtiq'>Tiquete no existe</div></div>";
     break;
     
     case "escredito":
          $res = ListaActivos("Empresa",array("cedula='".$_GET["id"]."'"),100);
          if(count($res)==0)
            echo "2";
          else
            echo $res[0]["credito"];
     break;
     case "traechofer":
          $res = ListaActivos("Bus",array("idbus=".$_GET["idbus"]),100);
          echo $res[0]["idchofer"];
     break;
     case "marcaobjetopi":
        if($_GET["valor"]=="1")
            $res= AgregaPerfilInicio($_GET["usuario"],$_GET["objeto"]);
        else
            $res= QuitaPerfilInicio($_GET["usuario"],$_GET["objeto"]);
        echo "correcto";
        break;
     case "marcarutausu":
        if($_GET["valor"]=="1")
            $res= AgregaRutaxUsuario($_GET["usuario"],$_GET["objeto"]);
        else
            $res= QuitaRutaxUsuario($_GET["usuario"],$_GET["objeto"]);
        echo "correcto";
        break;
     case "consecutivo":
        $res = getConsecutivo($_GET["impresora"]);
        echo $res;
        break;
     case "setubicacion":
        setubicacion($_GET["idEncomienda"],$_GET["ubicacion"]);
        echo 'OK';
        break;
    case "validaactividad":
        $link = new tiqmysql();
        $codigo = sqlClearText($_GET["codigo"]);
        $sql = "SELECT codigo, descripcion FROM actividad WHERE codigo = '".$codigo."'";
        $res = $link->bdEjecutar($sql);
        
        if($link->bdCantLineas($res) > 0){
            $linea = mysqli_fetch_array($res);
            echo "1|".$linea["descripcion"];
        } else {
            echo "0|No existe";
        }
        break;
    }
?>
