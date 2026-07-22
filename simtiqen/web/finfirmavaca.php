<?php
   include_once('controles.php');
    inicio("sinpermiso");
    $lbguarda=0;
    if(isset($_POST["txtdatos"])){
        $ldatos= $_POST["txtdatos"];
        $idmovi = $_POST["txtidmovi"];
        GuardaFirmaVaca($idmovi,$ldatos);
        $lbguarda=1;
    }
?>

<html>
<head>
<link type="text/css" href="css/firma.css" rel="stylesheet" />
<link type="text/css" href="css/planilla.css" rel="stylesheet" />
<script type="text/javascript" src="js/jquery-current.min.js"></script>
</head>
<body>
<?php
	if($lbguarda==1){
		echo "<h1> Firma guardada </h1>";
		echo BoletaPlanilla($idmovi);
	}
?>
</body>
</html>
