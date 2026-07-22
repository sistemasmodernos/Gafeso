		$(document).ready(function() {
			$("#excel").change(excelcambia);
		});

		function excelcambia(){
		    if ($("#excel").is(':checked')){
				$("#formlasiento").attr('action', 'lconseencexc.php');
				$("#Enviar").attr('value', 'Descargar Excel');
			}else{
				$("#formlasiento").attr('action', 'lconseenc.php');
				$("#Enviar").attr('value', 'Volver a cargar los datos');
			}
		}