	function EliminarDato(idchofer){
		var msg = confirm("¿ Desea eliminar este chofer ?");
		if ( msg ) {
			$.ajax({
				url: 'elichofer.php',
				type: "GET",
				data: "idchofer="+idchofer,
				success: function(datos){
					alert(datos);
					$("#frmDatos").submit();
				}
			});
		}
		return false;
	}
	
	function Cancelar(){
		var pagina="manchofer.php"
         location.href=pagina;
		return false;
	}
	
	