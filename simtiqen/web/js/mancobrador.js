	function EliminarDato(idcobrador){
		var msg = confirm("¿ Desea eliminar este cobrador ?");
		if ( msg ) {
			$.ajax({
				url: 'elicobrador.php',
				type: "GET",
				data: "idcobrador="+idcobrador,
				success: function(datos){
					alert(datos);
					$("#frmDatos").submit();
				}
			});
		}
		return false;
	}
	
	function Cancelar(){
		var pagina="mancobrador.php"
         location.href=pagina;
		return false;
	}
	
	