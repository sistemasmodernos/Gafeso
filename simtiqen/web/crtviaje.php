<?php
include_once('lib/nucleo.php');
?>
<html>
<head>
</head>
<body>
<div>
  <div id='divcrtviaje'>
     <form id='formviaje' name='formviaje' method='post'>
     <table class='tablatiq'>
     <tr>
        <td>Date: <td>
        <td><input type="text" id="datepicker" class='fecha'
            value='<?php if(isset($_GET["fecha"])) 
               echo FechatoNatural($_GET["fecha"]); ?>' 
            onchange='cambiafecha();'></p>
        </td>
    </tr>
    </table>
    
        <div id='divhora'>
       
        <table class='tablatiq'>
            <tr>
                <td>Hora:</td>
                <td>
                    <select name='cbhora' id='cbhora' onchange='cambiahora(this.value);'>
                    <?php
                        if(isset($_GET["ruta"]))
                        {
                            $idviaje= SiguienteViaje($_GET["estacion"],$_GET["parada"],$_GET["fecha"],$_GET["ruta"],$_GET["tipo"]);
                            $res=ViajesxEstacion($_GET["estacion"],$_GET["ruta"],$_GET["fecha"], $_GET["tipo"]);
                            for($x=0;$x<count($res);$x++){
                                $linea = $res[$x];
                                echo "<option value='". $linea["idviaje"] ."'";
                                if($linea["idviaje"]==$idviaje)
                                    echo " selected ";
                                echo ">" .$linea["hora"] ." ".$linea["tipo"];
                                if($linea["extra"]==1)
                                  echo " Extra ";
                                echo "</option>";
                            }
                        }
                    ?>
                    </select>
                    <span id='spanplacabus'></span>
                </td>
            </tr>
        </table>
        </div>
     <p><input name='hidruta' type='hidden'/ value='<?php if(isset($_GET["ruta"])) echo $_GET["ruta"]; ?>'></p>
     <p><input name='hestacion' type='hidden' value='<?php if(isset($_GET["estacion"])) echo $_GET["estacion"]; ?>' ></p>
        
     </p>
     </form>
     
  </div>
  </div>
</body>
</html>