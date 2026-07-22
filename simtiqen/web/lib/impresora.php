<?php
/* Funciona Giovanni, 10-05-2011 */

/* Impresoraes */
class Impresora{
  var $idImpresora;
  var $nombre;
  var $consecutivo;
  var $consecutivoe;
  var $activo;
  var $fechadi;
  var $bitacora;
  var $sucursal;
  var $terminal;
  
  //Contructor
  function Impresora(){
     $this->Limpiar();
  }
  
  //Limpia las propiedades    
  function Limpiar(){
     $this->idImpresora = 0;
     $this->nombre = "";
     $this->consecutivo = 0;
     $this->consecutivoe = 0;
     $this->activo = 0;
     $this->fechadi = date("Y-m-d H:i:s");
     $this->sucursal='001';
     $this->terminal='00001';
  }
  
  //Carga los datos del Impresora
  function Cargar(){
     $objMy = new tiqmysql();
    $conexion = $objMy->bdAbrir();
    $consulta = sprintf($this->Seleccion()." from impresora where idimpresora = %s",sqlClearText($this->idImpresora));
    $resulquery = $objMy->bdEjecutar($consulta,$conexion);
    if ($objMy->bdCantLineas($resulquery)==1){
       $linea = mysqli_fetch_array($resulquery);
       $this->nombre = $linea["nombre"];
       $this->consecutivo = $linea["consecutivo"];
       $this->consecutivoe = $linea["conseenco"];
       $this->activo = $linea["activo"];
       $this->fechadi = $linea["fechadi"];
        $this->sucursal = $linea["sucursal"];
       $this->terminal = $linea["terminal"];
       return $this->idImpresora;
    }else{
      return 0;
    }
  }
    
  function CargarId($pIdImpresora){
      $this->idImpresora = $pIdImpresora;
      return $this->Cargar();
  }
  
  //Guarda los datos , recibe como paremetro la transacción si existe 
  function Guardar($link){
       $this->Validar();
       try {
         $nueva = ($this->idImpresora == 0);
        if ($this->idImpresora == 0){
          $consulta = sprintf("insert into impresora (nombre,consecutivo,activo,fechadi) values ('%s',%s,%s,'%s')",
              sqlClearText($this->nombre),
              sqlClearText($this->cedula),
              $this->activo,
              sqlClearText($this->fechadi));
          $bit = "Agrega el Impresora ".$this->nombre;
        }else{
          $consulta = sprintf("update impresora set 
              nombre = '%s',
              consecutivo = %s,
              activo = %s 
              where idimpresora = %s",
              sqlClearText($this->nombre),
              $this->consecutivo,
              $this->activo,
              sqlClearText($this->idImpresora));
          $bit = "Modifica el Impresora ".$this->idImpresora." ".$this->nombre;
        }
        if($link==null){
           $link = new tiqmysql();
         $cerrartran=true;
      }
      else 
         $cerrartran=false;
      $link->bdAbreTran();
      $res = $link->bdEjecutar($consulta);
      if($nueva)
          $this->idImpresora=$link->bdUltimoId();
      $this->bitacora->bitacorizar($bit);
        if($cerrartran)
            $link->bdCierraTran();
    }
    catch (SimError $ex) {
      throw $ex;
     }
     return $this->idImpresora;
    }
    
    //devuelve el siguiente consecutivo dentro de una transacci'on
    function SiguienteConsecutivo($link){
        if($link==null)
          $link = new tiqmysql();
        $sql = " update impresora set consecutivo=consecutivo+1 where idimpresora=" . $this->idImpresora;
        $link->bdEjecutar($sql);
        $sql= " select consecutivo from impresora where idimpresora=" . $this->idImpresora;
        return $link->PrimerValorD($sql);
    }
     //devuelve el siguiente consecutivo dentro de una transacci'on
    function SiguienteConsecutivoe($link){
        if($link==null)
          $link = new tiqmysql();
        $sql = " update impresora set conseenco=conseenco+1 where idimpresora=" . $this->idImpresora;
        $link->bdEjecutar($sql);
        $sql= " select conseenco as consecutivo from impresora where idimpresora=" . $this->idImpresora;
        return $link->PrimerValorD($sql);
    }
    //devuelve el siguiente consecutivo dentro de una transacci'on
    function SiguienteConsecutivoNC($link){
        if($link==null)
          $link = new tiqmysql();
        $sql = " update impresora set consecutivonc=consecutivonc+1 where idimpresora=" . $this->idImpresora;
        $link->bdEjecutar($sql);
        $sql= " select consecutivonc from impresora where idimpresora=" . $this->idImpresora;
        return $link->PrimerValorD($sql);
    }



    //Valida los campos  
    function Validar(){
      $errores="";
    if(strlen($this->nombre) <= 0)
      $errores.="Detalle del Impresora en blanco";
    if($errores!="")    
      throw new SimError($errores);      
    }
    
    //Borra el Impresora
    function Borrar(){
        if ($this->idImpresora > 0){
            $link = new tiqmysql();
           $consulta = sprintf("delete from impresora where idImpresora = %s",sqlClearText($this->idImpresora));
           $resultado = $link->bdEjecutar($consulta);
           if ($resultado > 0){
              $this->bitacora->Bitacorizar("Elimina el Impresora ".$this->idImpresora." ".$this->nombre);
           }
           return $resultado;
        }else{
           return 0;
        }
    }
    
    
    function Listar($filtro,$paginacion){
       $link=new tiqmysql();
       $tira = "";
       $limite = "";
       if ($paginacion != null){
          if ($paginacion["pagina"] > 0){
              $limite = "limit " . $paginacion["registros"] * ($paginacion["pagina"] - 1);
          }else{
              $limite = "limit 0";
            }
          $limite .= "," . $paginacion["registros"];
       }
       if ($filtro == null){
        $tira = "";
       }else{
         $primero = true;
          for($i = 0;$i<count($filtro);$i++){ 
              $valor = $filtro[$i];
              if ($primero){
                 $tira .= " where " . $valor;
                 $primero = false;
              }else{
                 $tira .= " and " . $valor;
              }
          }
       }
       return $link->bdEjecutar("select idimpresora,nombre from impresora ".$tira." order by nombre " .$limite);
    }  

   function ListarActivos($filtro){
       $link=new tiqmysql();
       $tira = "";
       if ($filtro == null){
        $tira = "";
       }else{
          for($i = 0;$i<count($filtro);$i++){ 
              $valor = $filtro[$i];
              $tira .= " and " . $valor;
          }
       }
       return $link->bdEjecutar("select idimpresora,nombre from impresora where activo ".$tira." order by nombre ");
    }  
    
    
    
    
    //Pasa el objeto a un string 
    function ToString(){
      $tira = "Id = " . $this->idImpresora . "\n";
      $tira .= "Nombre = " . $this->nombre . "\n";
      $tira .= "Consectuvio = " . $this->consecutivo . "\n";
      if ($this->activo==1){
         $tira .= "Estado = Activo\n";
      }else{
         $tira .= "Estado = Inactivo\n";
      }
      return $tira;
    }
    
    //Hereda el objeto bitácora    
  function HeredaBitacora($pbit){
    $pbit->nivel++;   
    $this->bitacora=$pbit;
    $this->bitacora->obPadre=$this;
  }
  
  function Seleccion(){
    return "Select idImpresora,nombre,consecutivo,activo,fechadi,sucursal,terminal,conseenco,consecutivonc ";
  }
      function SiguienteConsecutivoFA($link){
        if($link==null)
          $link = new tiqmysql();
        $sql = " update impresora set consefa=consefa+1 where idimpresora=" . $this->idImpresora;
        $link->bdEjecutar($sql);
        $sql= " select consefa from impresora where idimpresora=" . $this->idImpresora;
        return $link->PrimerValorD($sql);
    }
     function SiguienteConsecutivoTI($link){
        if($link==null)
          $link = new tiqmysql();
        $sql = " update impresora set conseti=conseti+1 where idimpresora=" . $this->idImpresora;
        $link->bdEjecutar($sql);
        $sql= " select conseti from impresora where idimpresora=" . $this->idImpresora;
        return $link->PrimerValorD($sql);
    } 
  }
?>
