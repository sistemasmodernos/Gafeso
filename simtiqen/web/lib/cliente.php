<?php

/* Funciona Giovanni 10-05-2011 */

/* Clientes */
class Cliente{
  var $idCliente;
  var $nombre;
  var $cedula;
  var $activo;
  var $fechanac;
  var $email;
  var $telefono;
  var $fechadi;
  var $bitacora;
  var $provincia;
  var $canton;
  var $distrito;
  var $barrio;
  var $otrasSenas;
  var $actividad;
  
  //Contructor
  function __construct(){
     $this->Limpiar();
  }
  
  //Limpia las propiedades    
  function Limpiar(){
     $this->idCliente = 0;
     $this->nombre = "";
     $this->cedula = "";
     $this->email = "";
     $this->telefono = "";
     $this->activo = 1;
     $this->fechadi = date("Y-m-d H:i:s");
     $this->fechanac = date("Y-m-d H:i:s");
     $this->provincia ="1";
     $this->canton="01";
     $this->distrito="01";
     $this->barrio="01";
     $this->otrasSenas="";
     $this->actividad="";
  }
  
  //Carga los datos del Cliente
  function Cargar(){
  	 $objMy = new tiqmysql();
    $conexion = $objMy->bdAbrir();
    $consulta = sprintf($this->Seleccion()." from cliente where idCliente = %s",sqlClearText($this->idCliente));
    $resulquery = $objMy->bdEjecutar($consulta,$conexion);
    if ($objMy->bdCantLineas($resulquery)==1){
       $linea = mysqli_fetch_array($resulquery);
       $this->nombre = $linea["nombre"];
       $this->cedula = $linea["cedula"];
       $this->activo = $linea["activo"];
       $this->fechadi = $linea["fechadi"];
       $this->fechanac = $linea["fechanac"];
       $this->email = $linea["email"];
       $this->telefono = $linea["telefono"];
       $this->provincia = $linea["provincia"];
       $this->canton = $linea["canton"];
       $this->distrito= $linea["distrito"];
       $this->barrio= $linea["barrio"];
       $this->otrasSenas = $linea["otrasSenas"];
       $this->actividad = $linea["actividad"];
       return $this->idCliente;
    }else{
      return 0;
    }
  }
  
  function CargaCedula($pcedula){
      $link = new tiqmysql();
      $res=$link->PrimerValorD("select idcliente from cliente where cedula='".$pcedula."'");
      if($res!=null){
          $this->idCliente=$res;
          return $this->Cargar();
      }
      else
        return null;
    }
    
  function CargarId($pIdCliente){
      $this->idCliente = $pIdCliente;
      return $this->Cargar();
  }
  
  //Guarda los datos , recibe como paremetro la transacción si existe 
  function Guardar($link){
  	   $this->Validar();
  	   try {
  	     $nueva = ($this->idCliente == 0);
        if ($this->idCliente == 0){
          $consulta = sprintf("insert into cliente (nombre,cedula,activo,fechadi,fechanac,email,telefono
            ,provincia,canton,distrito,barrio,otrasSenas,actividad) 
              values ('%s','%s',%s,'%s','%s','%s','%s'
            ,'%s','%s','%s','%s','%s','%s')",
	            sqlClearText($this->nombre),
	            sqlClearText($this->cedula),
	            $this->activo,
	            sqlClearText($this->fechadi),
	            sqlClearText($this->fechanac),
	            sqlClearText($this->email),
	            sqlClearText($this->telefono),
              sqlClearText($this->provincia),
              sqlClearText($this->canton),
              sqlClearText($this->distrito),
              sqlClearText($this->barrio),
              sqlClearText($this->otrasSenas),
              sqlClearText($this->actividad)
            );
          $bit = "Agrega el Cliente ".$this->nombre;
        }else{
          $consulta = sprintf("update cliente set 
	            nombre = '%s',
	            cedula = '%s',
	            fechanac = '%s',
	            email = '%s',
	            telefono = '%s',
	            activo = %s ,
              provincia ='%s',
              canton='%s',
              distrito='%s',
              barrio='%s',
              otrasSenas='%s',
              actividad='%s' 
	            where idCliente = %s",
	            sqlClearText($this->nombre),
	            sqlClearText($this->cedula),
	            sqlClearText($this->fechanac),
	            sqlClearText($this->email),
	            sqlClearText($this->telefono),
	            $this->activo,
              sqlClearText($this->provincia),
              sqlClearText($this->canton),
              sqlClearText($this->distrito),
              sqlClearText($this->barrio),
              sqlClearText($this->otrasSenas),
              sqlClearText($this->actividad),
	            sqlClearText($this->idCliente));
          $bit = "Modifica el Cliente ".$this->idCliente." ".$this->nombre;
        }
        if($link==null){
           $link = new tiqmysql();
		     $cerrartran=true;
		  }
		  else 
		     $cerrartran=false;
		  $link->bdAbreTran();
 		  $res = $link->bdEjecutar($consulta,$link);
		  if($nueva)
		      $this->idCliente=$link->bdUltimoId();
		  $this->bitacora->bitacorizar($bit);
          if($cerrartran)
            $link->bdCierraTran();
		}
		catch (SimError $ex) {
			throw $ex;
	   }
	   return $this->idCliente;
    }
    
    //Valida los campos  
    function Validar(){
      $errores="";
		if(strlen($this->nombre) == 0)
		  $errores.="Detalle del Cliente en blanco. <br/>";
		/*if(strlen($this->cedula) == 0)
		  $errores.="Cédula del Cliente en blanco.<br/>";*/
		if($errores!="")		
			throw new SimError($errores); 

    }
    
    //Borra el Cliente
    function Borrar(){
        if ($this->idCliente > 0){
        	  $objMy = new tiqmysql();
           $consulta = sprintf("delete from cliente where idCliente = %s",sqlClearText($this->idCliente));
           $resultado = $objMy->bdEjecutar($consulta);
           if ($resultado > 0){
              $this->bitacora->Bitacorizar("Elimina el Cliente ".$this->idCliente." ".$this->nombre);
           }
           return $resultado;
        }else{
           return 0;
        }
    }
    
    //Pasa el objeto a un string 
    function ToString(){
      $tira = "Id = " . $this->idCliente . "\n";
      $tira .= "Nombre = " . $this->nombre . "\n";
      $tira .= "Cédula = " . $this->cedula . "\n";
      $tira .= "Correo Electrónico = " . $this->email . "\n";
      $tira .= "Teléfono = " . $this->telefono . "\n";
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
    return "Select  cedula,nombre,idCliente,activo,fechadi,fechanac,email,telefono,provincia,canton,distrito,barrio,otrasSenas,actividad ";
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
       $sql=$this->Seleccion()." from cliente  where activo=1 ".$tira." order by nombre limit 1000";
  //     echo $sql;
       return $link->bdEjecutar($sql);
    }
  
  }
?>
