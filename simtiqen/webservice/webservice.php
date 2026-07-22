<?php 
 require_once('nusoap.php');
 require_once('wsnucleo.php');


$server = new soap_server();
$ns="http://www.transportesjacoruta655.com/NuSOAP/webservice";
$server->configurewsdl('ApplicationServices',$ns);
$server->wsdl->schematargetnamespace=$ns;

$server->register('wsPrecios',array(
'pusuario' => 'xsd:string',
'pclave' => 'xsd:string',
'pdatos' => 'xsd:string'
),array('return' => 'xsd:string')
  ,$ns );

$server->register('wsDispo',array(
'pusuario' => 'xsd:string',
'pclave' => 'xsd:string',
'pviaje'=> 'xsd:string'
),array('return' => 'xsd:string')
  ,$ns );

$server->register('wsDispoDP',array(
'pusuario' => 'xsd:string',
'pclave' => 'xsd:string',
'pviaje'=> 'xsd:string'
),array('return' => 'xsd:string')
  ,$ns );


$server->register('wsApartar',array(
'pusuario' => 'xsd:string',
'pclave' => 'xsd:string',
'pdatos' => 'xsd:string',
'nombre'=>'xsd:string'
),array('return' => 'xsd:string')
  ,$ns );

$server->register('wsVender',array(
'pusuario' => 'xsd:string',
'pclave' => 'xsd:string',
'ptiquetes' => 'xsd:string'
),array('return' => 'xsd:string')
  ,$ns );

$server->register('wsRutas',array(
'pusuario' => 'xsd:string',
'pclave' => 'xsd:string'
),array('return' => 'xsd:string')
  ,$ns );

$server->register('wsParadas',array(
'pusuario' => 'xsd:string',
'pclave' => 'xsd:string'
),array('return' => 'xsd:string')
  ,$ns );
  
 $server->register('wsProductos',array(
'pusuario' => 'xsd:string',
'pclave' => 'xsd:string'
),array('return' => 'xsd:string')
  ,$ns ); 

$server->register('wsViajes',array(
'pusuario' => 'xsd:string',
'pclave' => 'xsd:string',
'pruta' => 'xsd:string',
'pdesde' => 'xsd:string',
'phasta' => 'xsd:string'
),array('return' => 'xsd:string')
  ,$ns );



$server->soap_defencoding = "UTF-8";
$server->decode_utf8 = false;

if (isset($HTTP_RAW_POST_DATA))
{
	$input = $HTTP_RAW_POST_DATA;
}
else
{
	$input = implode("\r\n", file('php://input'));
}
$server->service($input);
exit;
?>
