<?php
   include_once('controles.php');
    inicio("sinpermiso");
    $laestacion = $_GET["esta"];
    $res = EncomiendasSinFirma($laestacion);
    $lbguarda=0;
    if(isset($_POST["txtdatos"])){
        $ldatos= $_POST["txtdatos"];
        $lencomienda = $_POST["txtidencomienda"];
        GuardaFirma($lencomienda,$ldatos);
        $lbguarda=1;
    }
   
     
?>

<html>
<head>
<link type="text/css" href="css/firma.css" rel="stylesheet" />
<script type="text/javascript" src="js/firma.js"></script>
<script type="text/javascript" src="js/jquery-current.min.js"></script>
<link rel="icon" type="image/png" sizes="32x32" href="favicon-32x32.png">
<script>
 <?php
   if((count($res)>0) && ($lbguarda==0))
      echo "setTimeout(function(){ location.href='firma.php?esta=".$laestacion."'; }, 3000);";
  else
     echo "setTimeout(function(){ location.href='finfirma.php?esta=".$laestacion."'; }, 3000);";
 ?>
</script>
</head>
<body>
<?php
  if($lbguarda==1)
     echo "<h1> Firma guardada </h1>";
?>
<p>
   Esperando firmas...
</p>
</body>
</html>
