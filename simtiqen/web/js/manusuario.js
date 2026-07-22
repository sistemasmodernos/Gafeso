	function EliminarDato(idUsuario){
		var msg = confirm("¿ Desea eliminar este usuario ?")
		if ( msg ) {
			$.ajax({
				url: 'eliusuario.php',
				type: "GET",
				data: "idUsuario="+idUsuario,
				success: function(datos){
					alert(datos);
					$("#frmDatos").submit();
				}
			});
		}
		return false;
	}
	
	function Cancelar(){
		var pagina="manusuario.php"
         location.href=pagina;
		return false;
	}
	
	