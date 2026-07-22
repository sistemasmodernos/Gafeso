	function EliminarDato(idProducto){
		var msg = confirm('¿ Desea eliminar este producto ?'  )
		if ( msg ) {
			$.ajax({
				url: 'eliproducto.php',
				type: "GET",
				data: "idProducto="+idProducto,
				success: function(datos){
					alert(datos);
					$("#frmDatos").submit();
				}
			});
		}
		return false;
	}
	
	function Cancelar(){
		var pagina="manproducto.php"
         location.href=pagina;
		return false;
	}
	
	