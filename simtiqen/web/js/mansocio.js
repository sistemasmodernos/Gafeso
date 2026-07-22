	function EliminarDato(idsocio){
		var msg = confirm("¿ Desea eliminar este socio ?");
		if ( msg ) {
			$.ajax({
				url: 'elisocio.php',
				type: "GET",
				data: "idsocio="+idsocio,
				success: function(datos){
					alert(datos);
					$("#frmDatos").submit();
				}
			});
		}
		return false;
	}
	
	function Cancelar(){
		var pagina="mansocio.php"
         location.href=pagina;
		return false;
	}
	
	