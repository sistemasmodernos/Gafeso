	function EliminarDato(idPerfil){
		var msg = confirm('¿ Desea eliminar este perfil ?'  )
		if ( msg ) {
			$.ajax({
				url: 'eliperfil.php',
				type: "GET",
				data: "idperfil="+idPerfil,
				success: function(datos){
					alert(datos);
					$("#frmDatos").submit();
				}
			});
		}
		return false;
	}
	
	function Cancelar(){
		var pagina="manperfil.php"
         location.href=pagina;
		return false;
	}
	
	