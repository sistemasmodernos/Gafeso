<?php
require "vendor/autoload.php";
Predis\Autoloader::register();
class rediscore{
 function open(){	
   $redis = new Predis\Client(array(
    "scheme" => "tcp",
    "host" => "localhost",
    "port" => 6379,
    ));
   return $redis;
 }
 function set($pkey,$pmessage,$ptime){
	 $redis=$this->open();
	 $redis->setex($pkey,$ptime,$pmessage);
 }
 function get($pkey){
	 $redis = $this->open();
	 $val="";
	 $val=$redis->get($pkey);
	 if($val==null)
		 $val="";
	 return $val;
 }
}
?>
