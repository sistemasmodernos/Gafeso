<?php
   include_once('controles.php');
    inicio("sinpermiso");
?>
<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1"/> 
	<meta name="apple-mobile-web-app-capable" content="yes" />
	<link type="text/css" href="css/planilla.css" rel="stylesheet" />     
</head>
<body>
	<div>
		<?php
		    echo BoletaPlanilla($_GET["boleta"]);
		?>
	</div>
</body>
</html>