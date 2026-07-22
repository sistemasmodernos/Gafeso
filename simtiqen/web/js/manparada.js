	function EliminarDato(idparada){
		var msg = confirm("¿ Desea eliminar esta parada ?");
		if ( msg ) {
			$.ajax({
				url: 'eliparada.php',
				type: "GET",
				data: "idparada="+idparada,
				success: function(datos){
					alert(datos);
					$("#frmDatos").submit();
				}
			});
		}
		return false;
	}
	
	function Cancelar(){
		var pagina="manparada.php"
         location.href=pagina;
		return false;
	}
	
	