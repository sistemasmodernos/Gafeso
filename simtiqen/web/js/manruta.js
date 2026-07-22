	function EliminarDato(idruta){
		var msg = confirm("¿ Desea eliminar esta ruta ?");
		if ( msg ) {
			$.ajax({
				url: 'eliruta.php',
				type: "GET",
				data: "idruta="+idruta,
				success: function(datos){
					alert(datos);
					$("#frmDatos").submit();
				}
			});
		}
		return false;
	}
	
	function Cancelar(){
		var pagina="manruta.php"
         location.href=pagina;
		return false;
	}
	
	