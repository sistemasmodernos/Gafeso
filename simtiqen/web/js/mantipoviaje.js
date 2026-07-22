	function EliminarDato(idchofer){
		var msg = confirm("¿ Desea eliminar este tipo de viaje ?");
		if ( msg ) {
			$.ajax({
				url: 'elitipoviaje.php',
				type: "GET",
				data: "idtipoviaje="+idchofer,
				success: function(datos){
					alert(datos);
					$("#frmDatos").submit();
				}
			});
		}
		return false;
	}
	
	function Cancelar(){
		var pagina="mantipoviaje.php"
         location.href=pagina;
		return false;
	}
	
	