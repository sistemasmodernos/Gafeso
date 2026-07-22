<?php

/* Funciona Giovanni, 10-05-2011 */

/* Usuarioes */
class Usuario{
  var $idUsuario;
  var $nombre;
  var $nombrelargo;
  var $email;
  var $impresora;
  var $ruta;
  var $estacion;
    var $activo;
  var $fechadi;
  var $bitacora;
  var $perfil;
  
  //Contructor
  function Usuario(){
     $this->Limpiar();
  }
  
  //Limpia las propiedades    
  function Limpiar(){
     $this->idUsuario = 0;
     $this->nombre = "";
       $this->activo = 0;
     $this->fechadi = date("Y-m-d H:i:s");
     $this->nombrelargo="";
     $this->email="";
     $this->impresora = new Impresora();
     $this->ruta = new Ruta();
     $this->estacion= new Estacion();
     $this->perfil= new Perfil();
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
       return $link->bdEjecutar($this->Seleccion()." from usuario " . $tira . " order by nombre " . $limite);
    }

   function Cantidad($filtro){
       $link=new tiqmysql();
       $tira = "";
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
       return $link->bdCantLineas($link->bdEjecutar("select idUsuario as cuenta from usuario " . $tira));
    }
  
  //Carga los datos del Usuario
  function Cargar(){
  	 $objMy = new tiqmysql();
    $consulta = sprintf($this->Seleccion()." from usuario where idUsuario = %s",sqlClearText($this->idUsuario));
    $resulquery = $objMy->bdEjecutar($consulta);
    if ($objMy->bdCantLineas($resulquery)==1){
       $linea = mysqli_fetch_array($resulquery);
       $this->Limpiar();
       $this->idUsuario = $linea["idUsuario"];
       $this->nombre = $linea["nombre"];
       $this->nombrelargo = $linea["nombrelargo"];
         $this->activo = $linea["activo"];
       $this->fechadi = $linea["fechadi"];
       $this->email = $linea["email"];
       $this->impresora->idImpresora =$linea["idimpresora"];
       $this->ruta->idRuta = $linea["idruta"];
       $this->estacion->idEstacion=$linea["idestacion"];
       $this->perfil->idperfil=$linea["idperfil"];
       return $this->idUsuario;
    }else{
      return 0;
    }
  }
    
  function CargarId($pIdUsuario){
      $this->idUsuario = $pIdUsuario;
      return $this->Cargar();
  }
  
   // Basado en un arreglo carga los datos para el mantenimiento
   // $parametros: arreglo que trae los datos
   // $opcion: tipo de operación que se realizará en el mantenimiento
   //          1- Agregar, 2- Modificar, 3- Borrar
   function CargarArreglo($parametros,$opcion){
       $retorno = null;
       switch($opcion) {
       	case 1:
                $xId = $this->idUsuario;
                $this->Limpiar();
                $this->idUsuario = $xId;
      	        $this->nombre = $parametros["nombre"];
      	        $this->nombrelargo = $parametros["nombrelargo"];
      	        $this->email = $parametros["email"];
 	            $this->activo = $parametros["activo"];
 	            $this->impresora->idImpresora = $parametros["idimpresora"];
 	            $this->ruta->idRuta = $parametros["idruta"];
 	            $this->estacion->idEstacion = $parametros["idestacion"];
                $this->perfil->idperfil=$parametros["idperfil"];
 	            $retorno = true;
       	        break;
       	case 2:
                $xId = $this->idUsuario;
                $this->Limpiar();
                $this->idUsuario = $xId;
       	        $this->idUsuario = $parametros["idUsuario"];
      	        $this->nombre = $parametros["nombre"];
      	        $this->nombrelargo = $parametros["nombrelargo"];
      	        $this->email = $parametros["email"];
 	              $this->activo = $parametros["activo"];
 	              $this->impresora->idImpresora = $parametros["idimpresora"];
 	              $this->ruta->idRuta = $parametros["idruta"];
 	              $this->estacion->idEstacion = $parametros["idestacion"];
                 $this->perfil->idperfil=$parametros["idperfil"];
 	              $retorno = true;
       	        break;
       	case 3:
       	        $this->idUsuario = $parametros["idUsuario"];
 	              $retorno = $this->Cargar();
       	        break;
       }
       return $retorno;
   }
  
  //Guarda los datos , recibe como paremetro la transacción si existe 
  function Guardar($link){
  	   $this->Validar();
  	   try {
  	     $nueva = ($this->idUsuario == 0);
        if ($this->idUsuario == 0){
          $consulta = sprintf("insert into usuario (nombre,nombrelargo,activo,fechadi,email,idimpresora,idruta,idestacion,idperfil)
             values ('%s','%s',%d,'%s','%s',%d,%d,%d,%d)",
	            sqlClearText($this->nombre),
                sqlClearText($this->nombrelargo),
	            $this->activo,
	            sqlClearText($this->fechadi),
                sqlClearText($this->email),
                $this->impresora->idImpresora,
                $this->ruta->idRuta,
                $this->estacion->idEstacion,
                $this->perfil->idperfil
                );
          $bit = "Agrega el Usuario ".$this->nombre;
        }else{
          $consulta = sprintf("update usuario set 
	            nombre = '%s',
                nombrelargo='%s',
	            activo = %s ,
                email = '%s',
                idimpresora=%d,
                idruta=%d,
                idestacion=%d,
                idperfil=%d
	            where idUsuario = %s",
	            sqlClearText($this->nombre),
                sqlClearText($this->nombrelargo),
	            $this->activo,
                sqlClearText($this->email),
                $this->impresora->idImpresora,
                $this->ruta->idRuta,
                $this->estacion->idEstacion,
                $this->perfil->idperfil,
	            sqlClearText($this->idUsuario)
                );
          $bit = "Modifica el Usuario ".$this->idUsuario." ".$this->nombre;
        }
        if($link==null){
           $link = new tiqmysql();
		     $cerrartran=true;
		  }
		  else 
		     $cerrartran=false;
		  $link->bdAbreTran();
 		  $res = $link->bdEjecutar($consulta);
		  if($nueva){
		      $this->idUsuario=$link->bdUltimoId();

      //        $this->CambiarClave("123456");
              }
		  $this->bitacora->bitacorizar($bit);
          if($cerrartran)
            $link->bdCierraTran();
          $this->CambiarClave("123456");  
		}
		catch (SimError $ex) {
			throw $ex;
	   }
	   return $this->idUsuario;
    }
    
    //Valida los campos  
    function Validar(){
      $errores="";
		if(strlen($this->nombre) <= 0)
		  $errores.="Detalle del Usuario en blanco";
		echo $errores;
		if($errores!="")		
			throw new SimError($errores);      
    }
    
    //Borra el Usuario
    function Borrar(){
        if ($this->idUsuario > 0){
        	  $link = new tiqmysql();
           $consulta = sprintf("delete from usuario where idUsuario = %s",sqlClearText($this->idUsuario));
           $resultado = $link->bdEjecutar($consulta);
           if ($resultado > 0){
              $this->bitacora->Bitacorizar("Elimina el Usuario ".$this->idUsuario." ".$this->nombre);
           }
           return $resultado;
        }else{
           return 0;
        }
    }
    
    //Pasa el objeto a un string 
    function ToString(){
      $tira = "Id = " . $this->idUsuario . "\n";
      $tira .= "Nombre = " . $this->nombre . "\n";
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
    function CifraContrasena($pass){
         return  md5($pass.'kovthe');
    }
    function VerificaUsuario($username,$pass){
        $link= new tiqmysql();
        //echo "<h1>Esta es el pass cifrado ".$this->CifraContrasena($pass)."</h1>";
        //echo $username."-".$pass;
        $sql=sprintf("select idusuario from usuario where clave='%s' and nombre='%s' and activo=1",
         $this->CifraContrasena($pass),
         $username);
        $res=$link->PrimerValorD($sql);
        //echo "primer valor ".$res;
        if($res==null)
          return 0;
        else
         return $res;
    }
    function AutorizarSesion($pusername,$ppass,$psesion,$pip){
        $link= new tiqmysql();
        $res=$this->VerificaUsuario($pusername,$ppass);
        if($res>0){
          $sql=sprintf("insert into sesiontiq (idsesion,idusuario,fecha,ip,aceptada) 
                values ('%s',%d,now(),'%s',1)",$psesion,$res,$pip);
        //Carga los parametros en un array para tenerlos en la variable de sesion
        $par = array();
          $us = new Usuario();
          $us->idUsuario = $res;
          $us->Cargar();
          $par["nombreusuario"]=$us->nombre;
          $par["estaciondefault"]=$us->estacion->idEstacion; 
          $par["impresoradefault"]=$us->impresora->idImpresora;
          $par["rutadefault"]=$us->ruta->idRuta;
          $par["nombrelargo"]=$us->nombrelargo;
          $par["productodefault"]=1;
          $_SESSION["parametros"]=$par;
        }
        else
          $sql=sprintf("insert into sesiontiq (idsesion,idusuario,fecha,ip,aceptada) 
                values (%d,0,'%s','%s',0)",$psesion,date('Y-m-d'),$pip);
       $link->bdEjecutar($sql);
       return $res;
    }
    function SesionActiva($psesion){
        $link=new tiqmysql();
        $sql=sprintf("select  idsesiontiq from sesiontiq where idusuario=%d and idsesion='%s' and aceptada=1",
             $this->idUsuario,$psesion);
        $res=$link->PrimerValorD($sql);
        if($res!=null and $res>0)
          return true;
        else
          return false;
    }
    function CambiarClave($nuevopass){
        $link = new tiqmysql();
        $sql=sprintf("update usuario set clave='%s' where idusuario=%d",$this->CifraContrasena($nuevopass),$this->idUsuario);
        $link->bdEjecutar($sql);
        $this->bitacora->bitacorizar("Cambio de contrasena");
        return true;
    }
  
  function Seleccion(){
    return "Select idUsuario,nombre,nombrelargo,activo,fechadi,email,idimpresora,idruta,idestacion,idperfil ";
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
       return $link->bdEjecutar("select idusuario,nombre from usuario where activo ".$tira." order by nombre ");
    }

  function PerfilesInicio(){
       $link=new tiqmysql();
       $resulquery = $link->bdEjecutar("select idperfilini from inicioxusuario where idusuario=".$this->idUsuario);
       $res="";

       while($linea = mysqli_fetch_array($resulquery)){
         $res.=$linea["idperfilini"].",";
       }
       if($res!="")
        $res=substr($res, 0,strlen($res)-1);

       return $res;
  }  

  function AgregaPerfilInicio($pperfil){
    $link=new tiqmysql();
    $link->bdEjecutar("insert into inicioxusuario (idusuario,idperfilini) values (".$this->idUsuario.",".$pperfil.")");
    return true;
  }

  function QuitaPerfilInicio($pperfil){
       $link=new tiqmysql();
    $link->bdEjecutar("delete from  inicioxusuario where idusuario=".$this->idUsuario." and idperfilini=".$pperfil);
    return true; 
  }
    
  
  function RutasxUsuario(){
       $link=new tiqmysql();
       $resulquery = $link->bdEjecutar("select idruta from rutaxusuario where idusuario=".$this->idUsuario);
       $res="";

       while($linea = mysqli_fetch_array($resulquery)){
         $res.=$linea["idruta"].",";
       }
       if($res!="")
        $res=substr($res, 0,strlen($res)-1);

       return $res;
  }  

  function AgregaRutaxUsuario($pruta){
    $link=new tiqmysql();
    $link->bdEjecutar("insert into rutaxusuario (idusuario,idruta) values (".$this->idUsuario.",".$pruta.")");
    return true;
  }

  function QuitaRutaxUsuario($pruta){
       $link=new tiqmysql();
    $link->bdEjecutar("delete from  rutaxusuario where idusuario=".$this->idUsuario." and idruta=".$pruta);
    return true; 
  }
    
  
  }
?>
