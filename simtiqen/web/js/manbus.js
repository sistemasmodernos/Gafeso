	function EliminarDato(idbus){
		var msg = confirm("¿ Desea eliminar este bus ?");
		if ( msg ) {
			$.ajax({
				url: 'elibus.php',
				type: "GET",
				data: "idBus="+idbus,
				success: function(datos){
					alert(datos);
					$("#frmDatos").submit();
				}
			});
		}
		return false;
	}
	
	function Cancelar(){
		var pagina="manbus.php"
         location.href=pagina;
		return false;
	}
	
	