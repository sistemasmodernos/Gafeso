	function EliminarDato(idestacion){
		var msg = confirm("¿ Desea eliminar esta estación ?")
		if ( msg ) {
			$.ajax({
				url: 'eliestacion.php',
				type: "GET",
				data: "idestacion="+idestacion,
				success: function(datos){
					alert(datos);
					$("#frmDatos").submit();
				}
			});
		}
		return false;
	}
	
	function Cancelar(){
		var pagina="manestacion.php"
         location.href=pagina;
		return false;
	}
	
	