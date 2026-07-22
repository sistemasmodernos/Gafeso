<?php 
include('wsnucleo.php');
$elxml='<tabla>
   <reg>
      <idlincompra>1</idlincompra>
      <idruta>1</idruta>
      <idviaje>5645</idviaje>
      <idparada1>57</idparada1>
      <idparada2>57</idparada2>
      <idproducto>1</idproducto>
      <cantidad>4</cantidad>
      <precio>2361</precio>
   </reg>
   <reg>
     <idlincompra>2</idlincompra>
     <idruta>1</idruta><idviaje>5645</idviaje><idparada1>52</idparada1><idparada2>52</idparada2><idproducto>1</idproducto><cantidad>8</cantidad><precio>2361</precio></reg><reg><idlincompra>3</idlincompra><idruta>1</idruta><idviaje>5645</idviaje><idparada1>52</idparada1><idparada2>52</idparada2><idproducto>1</idproducto><cantidad>8</cantidad><precio>2361</precio></reg><reg><idlincompra>4</idlincompra><idruta>1</idruta><idviaje>5645</idviaje><idparada1>52</idparada1><idparada2>52</idparada2><idproducto>1</idproducto><cantidad>8</cantidad><precio>2361</precio></reg></tabla>'; 

//$res = wsApartar('vtaonline','123456',$elxml,"");
//echo "Resultado".$res; 
//$res=wsDispo('vtaonline','123456',5813);
//$res=wsDispoDP('vtaonline','123456',6038);
//$res = wsRutas('vtaonline','123456');
$res=wsParadas('vtaonline','123456');

//$res =  wsVender('vtaonline','4321',"91309,91310,91311");
//$res=wsViajes('vtaonline','4321',1,'2012-04-20','2012-04-20');
//$res =  wsProductos('vtaonline','4321');
echo var_dump($res);
?>
