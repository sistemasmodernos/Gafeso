<?php
function inicio($pantalla){
    date_default_timezone_set('America/Costa_Rica');
    
    session_cache_expire(60*3);
    session_start();
    if(!SesionActiva()){
	    echo "<script> location.href='login.php?type=nologin'; </script> ";
	    header("HTTP/1.1 401 Unauthorized");
            exit;
    }
    if(!isset($pantalla) || ($pantalla!="sinpermiso" && !ObjetoValido($pantalla))){
	    echo "<script> location.href='sinpermiso.php'; </script> ";
	    exit;
    }
    if(!isset($_SESSION["parametros"]["perfilinicio"]) && $pantalla!="sinpermiso")
       echo "<script> location.href='inicio.php'; </script> "; 
 //   error_reporting(E_ALL);
error_reporting(E_ERROR);     
//   ini_set('error_reporting', E_ALL);
   ini_set('display_errors','On');
    $_SESSION["parametros"]["mensaje_error"]=""  ;
}
include_once('lib/nucleo.php');

function Encabezado(){
echo "        <script type='text/javascript'>
$.datepicker.regional['es'] = {
 closeText: 'Cerrar',
 prevText: '<Ant',
 nextText: 'Sig>',
 currentText: 'Hoy',
 monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
 monthNamesShort: ['Ene','Feb','Mar','Abr', 'May','Jun','Jul','Ago','Sep', 'Oct','Nov','Dic'],
 dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
 dayNamesShort: ['Dom','Lun','Mar','Mié','Juv','Vie','Sáb'],
 dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sá'],
 weekHeader: 'Sm',
 dateFormat: 'dd/mm/yy',
 firstDay: 1,
 isRTL: false,
 showMonthAfterYear: false,
 yearSuffix: ''
 };
 $.datepicker.setDefaults($.datepicker.regional['es']);
</script>";
 echo "<div class='header_detail' ></div>";
  echo "<div id='encabezado' class='header'>
              <div class='titulo'> Sistema Venta de Tiquetes <span class='titulo_2'>" . DevParametro('nombrecia',3) . "</span></div>
              <div class='user'>
               <div class='name'>Nombre de usuario registrado:</div> 
               <div class='usuario'> " . $_SESSION["parametros"]["nombreusuario"] ."</div>
            </div></div>";
}
function Pie(){
     
    echo "<div id='pie' class='footer'>
<a href='www.simosa.com'><img src='images/logo.jpg' width='199' height='46'>
</a></div>";
}
function Menu(){
    echo "<div id='gmenu' class='gmenu'>

	<div id='menu' class='menu'>
    <div>
        <h1 style='margin-bottom:0px;'><a href='index.php'>Inicio</a></h1>
    </div>
    <div style='overflow-y:scroll;height:450px;'>
      <ul>
        <li><div class='mantenimiento'><a href='#'>MANTENIMIENTO</a></div>
            <ul class='mantenimiento2'>
                <li><a href='manbus.php'>Buses</a></li>
                <li><a href='manchofer.php'>Choferes</a></li>
                <li><a href='mancobrador.php'>Cobradores</a></li>
                <li><a href='manempresa.php'>Empresa</a></li>
                <li><a href='manestacion.php'>Estaciones</a></li>
                <li><a href='manparada.php'>Paradas</a></li>
                <li><a href='manperfil.php'>Perfiles</a></li>
                <li><a href='manproducto.php'>Productos</a></li>
                <li><a href='manruta.php'>Rutas</a></li>
                <li><a href='mansocio.php'>Socios</a></li>
                <li><a href='mantipoviaje.php'>Tipos de Viaje</a></li>
                <li><a href='manusuario.php'>Usuarios</a></li>
                <li><a href='parametros.php'>Parametros</a></li>
                <li><a href='precios.php'>Precios y asignacion de paradas</a></li>
                <li><a href='preciosfuturos.php'>Precios futuros</a></li>
                <li><a href='manprodenco.php'>Cat&aacute;logo encomiendas</a></li>
            </ul>
        </li>

        <li> <div class='procesos'><a href='#'>PROCESOS</a></div>
            <ul class='procesos2'>
                <li><a href='tiquetes.php'>Tiquetes</a></li>
                <li><a href='cambiaparada.php'>Cambiar de parada de tiquete</a></li>        
                <li><a href='cambiahora.php'>Cambiar horario de tiquete</a></li>
                <li><a href='encomiendas.php'>Encomiendas</a></li>
                <li><a href='horarios.php'>Horarios</a></li>
                <li><a href='anulatiq.php'>Anular tiquetes</a></li>
                <li><a href='anulaenco.php'>Anular encomiendas</a></li>
                <li><a href='cierreenco.php'>Cierre diario</a></li>
                <li><a href='reimtiq.php'>Reimpresi&oacute;n de tiquetes</a></li>
                <li><a href='vistahora.php'>Consulta de horarios</a></li>        
                <li><a href='retiraenco.php'>Retirar Encomiendas</a></li>        
		        <li><a href='impcortesia.php'>Imprimir cortes&iacute;as</a></li>
		        <li><a href='marcareimpre.php'>Marcar para Reimprimir</a></li>       
                <li><a href='cierraviaje.php'>Cerrar viaje</a></li>       
                <li><a href='cambiaconse.php'>Cambiar Consecutivo</a></li>
            </ul>
        </li>
        <li><div class= 'reporte'><a href='#'>REPORTE</a></div>
            <ul class='reporte2'>
                <li><a href='remision2.php'>Reporte de encomiendas</a></li>
                <li><a href='buscarenco.php'>Búsqueda de encomiendas</a></li>
                <li><a href='lasientos.php'>Lista de asientos</a></li>
                <li><a href='lconsetiq.php'>Consecutivo de tiquetes</a></li>
                <li><a href='lconseenc.php'>Consecutivo de encomiendas</a></li>
                <li><a href='rescierre.php'>Resumen de Cierres</a></li>
                <li><a href='encomicredito.php'>Encomiendas a cr&eacute;dito</a></li>
                <li><a href='repd151.php'>Reporte para D151</a></li>
                <li><a href='repviajes.php'>Ventas Aresep</a></li>
                <li><a href='repmayor.php'>Reporte Adulto Mayor</a></li>
                <li><a href='liquiviaje.php'>Liquidación de un viaje</a></li>
                <li><a href='liqxperiodo.php'>Liquidaciones por periodo</a></li>
                <li><a href='consabajo.php'>Estad&iacute;sticas</a></li>
                <li><a href='repchofer.php'>Choferes Comisi&oacute;n por Viaje</a></li>
                <li><a href='repviajeoc.php'>Ocupaci&oacute;n de los Buses</a></li>
                <li><a href='ventageneral.php'>Venta General</a></li>
                <li><a href='ventagralcajero.php'>Venta General por Cajero</a></li>
                <li><a href='lretiraenco.php'>Encomiendas entregadas</a></li>
                <li><a href='encoanuladas.php'>Encomiendas anuladas</a></li>
                <li><a href='ventaxtiquete.php'>Venta por Tiquete</a></li>
            </ul>
        </li>
        <div class='reporte' <a href='#'>OTROS</a></div>
        <ul class='reporte2'>
        <li> <a href='cambiacla.php'>Cambio de contrase&ntilde;a</a></li>
        <li> <a href='segu.php'>Asignaci&oacute;n de permisos</a></li>
        <li><a href='inicio.php'>Cambio de perfil de inicio</a></li>
        <li><a href='inicioxusu.php'>Inicios por Usuario</a></li>
        <li><a href='rutasxusu.php'>Rutas por Usuario</a></li>";
        if(ObjetoValido("lreimpre"))
         echo "<li><a href='lreimpre.php'>Listado de reimpresiones </a></li>";
        if(ObjetoValido("lcortesia"))
         echo "<li><a href='lcortesia.php'>Listado de cortesias </a></li>";
       
       echo "</ul>
       </div>
       </ul> 
        <div style='margin-top:5px;'>
        <ul>
           <li><a href='login.php?type=salir' 
           onMouseOut='MM_swapImgRestore()' 
           onMouseOver=\"MM_swapImage('Salir','','images/salir_2.jpg',1)\">
           <img src='images/salir_1.jpg' name= 'Salir' width='118' height='33' border='0' ></a></li>
        </ul>
        </div>
    </div>
	<div id='finmenu' class='finmenu'></div>
	</div>";
}
function PasaHtml($cadena){
    $cadena=str_replace("\n","<br/>",$cadena);
    $cadena=str_replace("á","&aacute;",$cadena);
    $cadena=str_replace("é","&eacute;",$cadena);
    $cadena=str_replace("í","&iacute;",$cadena);
    $cadena=str_replace("ó","&oacute;",$cadena);
    $cadena=str_replace("ú","&uacute;",$cadena);
    $cadena=str_replace("ñ","&ntilde;",$cadena);
    return $cadena;
}
?>
