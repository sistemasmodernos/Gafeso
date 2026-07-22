	function EliminarDato(idempresa){
		var msg = confirm("¿ Desea eliminar este empresa ?");
		if ( msg ) {
			$.ajax({
				url: 'eliempresa.php',
				type: "GET",
				data: "idempresa="+idempresa,
				success: function(datos){
					alert(datos);
					$("#frmDatos").submit();
				}
			});
		}
		return false;
	}

	function EliminarAutoriza(idautoriza){
		var msg = confirm("¿ Desea eliminar este autorizado ?");
		if ( msg ) {
			$.ajax({
				url: 'eliautoriza.php',
				type: "GET",
				data: "idautoriza="+idautoriza,
				success: function(datos){
					alert(datos);
					$("#frmDatos").submit();
				}
			});
		}
		return false;
	}

	
	function Cancelar(){
		var pagina="manempresa.php"
         location.href=pagina;
		return false;
	}
	
	function revisacedulajur(){
		if (! $("#cedula").attr("value").match(/^[0-9]\-[0-9]{3}\-[0-9]{6}/)) {
			alert("Cédula no válida, formato 9-999-999999");
		}
		var xlaced = $("#cedula").val();
		var res = $("#cedula").val();
		xlaced = res.substring(0,1) + res.substring(2,5) + res.substring(6,12);
		var rut="enlace.php?opcion=datosjuri&cedula=" + xlaced;
		enlace(rut,fin_revisacedulajur);
	}

	function fin_revisacedulajur(dato){
		var dat= dato.split(',');
		if(dat.length>0){
			if(dat[0].length>0){
				$("#nomempresa").val(dat[0]);
			}
		}
	}

	function revisacedula(){
		if (! $("#cedula").attr("value").match(/^[0-9]{2}\-[0-9]{4}\-[0-9]{4}/)) {
			alert("Cedula no válida, formato 99-9999-9999");
		}
		var xlaced = $("#cedula").val();
		var res = $("#cedula").val();
		xlaced =  res.substring(0,2) + res.substring(3,7) + res.substring(8,12);
		var rut="enlace.php?opcion=datosenco&cedula=" + xlaced;
		enlace(rut,fin_revisacedula);
	}

	function fin_revisacedula(dato){
		var dat= dato.split(',');
		if(dat.length>0){
			$("#nomauto").val(dat[0]);
		}
	}
		
	function llegadacustom(){
		return true;
	}
	function loadDirecciones(){
      $.get("direcciones.json").done(function(datx){
        dir = datx;
        fillProvincias();
      });
  }
	function fillProvincias(){
    $("#provincia").html("");
    var selected="selected";
    for(var x=0;x<dir.provincias.length;x++){
      $("#provincia").append("<option value='"+dir.provincias[x].id+"' "+selected+" >"+dir.provincias[x].nombre+"</option>");
      selected="";
    }
    fillCantones();
  }
  function fillCantones(){
    var lpro = $("#provincia").val();
    var pro ;
    for(var x = 0 ; x< dir.provincias.length;x++)
      if(dir.provincias[x].id==lpro)
        pro=dir.provincias[x];
    $("#canton").html("");
     var selected="selected";
    for(var x=0; x<pro.cantones.length;x++){
       $("#canton").append("<option value='"+pro.cantones[x].id+"' "+selected+" >"+pro.cantones[x].nombre+"</option>");
      selected="";
    }
    fillDistritos();

  }
  function fillDistritos(){
    var lcan = $("#canton").val();
    var can ;
    for(var x = 0 ; x< dir.provincias.length;x++)
      for( var y=0 ; y <dir.provincias[x].cantones.length;y++)
        if(dir.provincias[x].cantones[y].id==lcan)
          can=dir.provincias[x].cantones[y];
    $("#distrito").html("");
     var selected="selected";
    for(var x=0; x<can.distritos.length;x++){
       $("#distrito").append("<option value='"+can.distritos[x].id+"' "+selected+" >"+can.distritos[x].nombre+"</option>");
      selected="";
    }
  }