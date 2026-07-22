<?php   
session_start(); 
if(isset($_GET["type"]))
 if($_GET["type"]=="salir")
   {
       session_destroy(); 
        $parametros_cookies = session_get_cookie_params();  
        setcookie(session_name(),0,1,$parametros_cookies["path"]); 
       }
?>
<html>
<head>
   <meta http-equiv="content-type" content="text/html; charset=utf-8" /> 
  <title>Sistemas de ventas de tiquetes</title> 
  <link rel="icon" type="image/png" sizes="32x32" href="favicon-32x32.png">
  <link rel="stylesheet" href="css/general.css" type="text/css" />
  <?php
  if(isset($_POST["txtusuario"]))
  {
      include_once('lib/nucleo.php');
      $res=AutorizarSesion($_POST["txtusuario"],$_POST["txtpass"]);

      if($res>0){
           
        echo "<script> location.href='inicio.php'</script>";
        }
      else{
 //       echo "<script> location.href= 'login.php?type=Usuario o Contraseña incorrecta'</script>";
        }
}
?>
</head>
<body>
    <form name='formlogin' id='formlogin' method="post" action='#' >
      <div  style='margin:auto;width:300px;border-style:solid;border-width:1px;border-color: #EF4814;margin-top:200px;padding:0px' >
          <p style='background-color: #EF4814;color:white;margin:0px;padding:5px;font-size:200%;'>Sistema de venta de tiquetes<p>
         <div style='padding:15px;'>
         <table id='tablalogin'>
              <tr><td>Usuario</td><td><input type='text' name='txtusuario'/></td></tr>
              <tr><td>Contraseña</td><td> <input type='password' name='txtpass'/></td></tr>
              <tr><td colspan='2'><input type='submit' value='Ingresar'/></td></tr>
         </table>
         <p>
          <?php 
            if(isset($_GET["type"]))
              echo $_GET["type"];
            
          ?>
          </p>
          </div>
          </div>
    </form>
</body>
</html>
