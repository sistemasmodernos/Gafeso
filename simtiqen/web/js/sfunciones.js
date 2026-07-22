var datosimp="";
var lWSC=null;

function traeajax(nombrediv,ruta,parametros,funciondespues){
	if(parametros==null)
          parametros={};
	inicioEnvio();
	$.post(ruta,parametros,function(data){
	var res = data;
	llegadaDatos(res,nombrediv,funciondespues);
	});
}

function abrir(direccion, pantallacompleta, herramientas, direcciones, estado, barramenu, barrascroll, cambiatamano, ancho, alto, izquierda, arriba, sustituir){ 
    var opciones = "fullscreen=" + pantallacompleta + 
                 ",toolbar=" + herramientas + 
                 ",location=" + direcciones + 
                 ",status=" + estado + 
                 ",menubar=" + barramenu + 
                 ",scrollbars=" + barrascroll + 
                 ",resizable=" + cambiatamano + 
                 ",width=" + ancho + 
                 ",height=" + alto + 
                 ",left=" + izquierda + 
                 ",top=" + arriba; 
    var ventana = window.open(direccion,"venta",opciones,sustituir); 

}
function llegadaDatos(dat,nombrediv,funciondespues){
	var res = $(dat);
	var estadiv = $("#"+nombrediv);
	var revihtml = $("#"+nombrediv,res).html();
	estadiv.html(revihtml);
	llegadacustom(nombrediv,funciondespues);

}     
function inicioEnvio() 
{

}
function enlace(ruta,nombrefuncion){
        var nuruta= ruta+"&ran="+aleatorio();
	$.post(nuruta,{},function(data){
	var res = data;
	fin_enlace(res,nombrefuncion);
	});

}
function fin_enlace(datos,nombrefuncion){
  nombrefuncion(datos);
}









/***************************/
//@Author: Adrian "yEnS" Mato Gondelle
//@website: www.yensdesign.com
//@email: yensamg@gmail.com
//@license: Feel free to use it, but keep this credits please!					
/***************************/

//SETTING UP OUR POPUP
//0 means disabled; 1 means enabled;
var popupStatus = 0;

///esta es la parte m'ia  para insertar las divisiones dentro del document din'amicamente
// Tommy 24/06/2011
function createPopup(theTitle,theHtml){
 var xdiv=   document.createElement('div');
  xdiv.id='popupContact';
  document.body.appendChild(xdiv);

 var xclose = document.createElement('a');
 xclose.id='popupContactClose';
  xdiv.appendChild(xclose);
 $(xclose).html('X');

 var xtitle = document.createElement('h1');
 xdiv.appendChild(xtitle);
 $(xtitle).html(theTitle);

 var xbody = document.createElement('p');
 xbody.id='contactArea';
 xdiv.appendChild(xbody);
 $(xbody).html(theHtml);

 var xdivbk = document.createElement('div');
 xdivbk.id='backgroundPopup';
 document.body.appendChild(xdivbk);

 preparePopup();
//centering with css
 centerPopup();
//load popup
 loadPopup();
}


//loading popup with jQuery magic!
function loadPopup(){
	//loads popup only if it is disabled
	if(popupStatus==0){
		$("#backgroundPopup").css({
			"opacity": "0.7"
		});
		$("#backgroundPopup").fadeIn("slow");
		$("#popupContact").fadeIn("slow");
		popupStatus = 1;
	}
}

//disabling popup with jQuery magic!
function disablePopup(){
	//disables popup only if it is enabled
	if(popupStatus==1){
		$("#backgroundPopup").fadeOut("slow");
		$("#popupContact").fadeOut("slow");
		popupStatus = 0;
		document.body.removeChild(document.getElementById("backgroundPopup"));
		document.body.removeChild(document.getElementById("popupContact"));
	}
}

//centering popup
function centerPopup(){
	//request data for centering
	var windowWidth = (document.documentElement.clientWidth ? document.documentElement.clientWidth : document.body.clientWidth);
	var windowHeight = ( document.documentElement.clientHeight ? document.documentElement.clientHeight : document.body.clientHeight );
	var popupHeight = $("#popupContact").height();
	var popupWidth = $("#popupContact").width();
	//centering
	$("#popupContact").css({
		"position": "absolute",
		"top": windowHeight/2-popupHeight/2,
		"left": windowWidth/2-popupWidth/2
	});
	//only need force for IE6
	
	$("#backgroundPopup").css({
		"height": windowHeight
	});
	
}


//CONTROLLING EVENTS IN jQuery
function preparePopup(){
	
	//CLOSING POPUP
	//Click the x event!
	$("#popupContactClose").click(function(){
		disablePopup();
	});
	//Click out event!
	$("#backgroundPopup").click(function(){
		disablePopup();
	});
	//Press Escape event!
	$(document).keypress(function(e){
		if(e.keyCode==27 && popupStatus==1){
			disablePopup();
		}
	});

}




/**
 * This method is for Cross-site Origin Resource Sharing (CORS) POSTs
 *
 * @param string   url      the url to post to
 * @param mixed    data     additional data to send [optional]
 * @param function callback a function to call on success [optional]
 * @param string   type     the type of data to be returned [optional]
 */
function postCORS(url, data, callback, type)
{
    try {
        // Try using jQuery to POST
        jQuery.post(url, data, callback, type);
    } catch(e) {
        // jQuery POST failed
        var params = '';
        for (key in data) {
            params = params+'&'+key+'='+data[key];
        }
        // Try XDR, or use the proxy
        if (jQuery.browser.msie && window.XDomainRequest) {
            // Use XDR
            var xdr = new XDomainRequest();
            xdr.open("post", url);
            xdr.send(params);
            xdr.onload = function() {
                callback(xdr.responseText, 'success');
            };
        } else {
            try {
                // Use the proxy to post the data.
                request = new proxy_xmlhttp();
                request.open('POST', url, true);
                request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                request.send(params);
            } catch(e) {
                // could not post using the proxy
            }
        }
    }
}


function MM_swapImgRestore() { //v3.0
  var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}
function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}

function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_swapImage() { //v3.0
  var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
   if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}


function ajusta(){
 var ancho=window.innerWidth-265;
 var alto=window.innerheight;
 $("#cuerpo").css("width",ancho);
}

function fechahoy(){
   var f = new Date();
   var dia = ''+f.getDate();
   var mes = ''+(f.getMonth()+1);
   var ano = ''+f.getFullYear();
   if(dia.length==1)
     dia='0'+dia;
   if(mes.length==1)
     mes = '0'+mes; 
   lafecha= dia + "/" + mes + "/" + ano;
  return lafecha;
}


function imprimetiqfin(data,intentos){
	 datosimp=data;
	if ("WebSocket" in window) {
	    logon();
	    intentaimprimir(1);
	} else {
	  alert("El navegador no soporta WebSocket.");
	}


}
function logon(){
  lWSC = new WebSocket("ws://localhost:8887");
  lWSC.onopen = function() {
   }
  lWSC.onmessage = function(e) {
   }
  lWSC.onclose = function() {
  }
}
function intentaimprimir(intentos){
	if(lWSC.readyState==1){
		var xl;
		xl=datosimp;
                xl2=xl.split('_i_');
                for(kk=0;kk<xl2.length;kk++){
		  lWSC.send(xl2[kk]);
                  sleep(500);
                }
                lWSC.close();
		lWSC=null;
                despuesimprimir();

	    }
	    else
	    {
		if(intentos<10){
		intentos++;
		setTimeout('intentaimprimir('+intentos+')',300);
		}
		else
		 alert("Ocurrió un error al intentar imprimir");
	     }

}

function carganave(llave,objeto,filtro) { 
   createPopup("Buscador","<div id='divnavega'></div>");
   carganave2(llave,objeto,filtro);  
}

function despues_carganave(){
}

function carganave2(llave,objeto,filtro){
   traeajax("divnavega","navega.php?llave="+llave+"&objeto="+objeto              +"&filtro="+filtro,null,despues_carganave)   

}

//esta funcion es peligrosa porque carga el procesador no debería usarse por mas de 1 segundo

function sleep(milliseconds) {
  var start = new Date().getTime();
  for (var i = 0; i < 1e7; i++) {
    if ((new Date().getTime() - start) > milliseconds){
      break;
    }
  }
}


function aleatorio(){ 
   	numPosibilidades = 19
   	aleat = Math.random() * numPosibilidades 
   	aleat = Math.round(aleat) 
   	return 1 + aleat 
} 
