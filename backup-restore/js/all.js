
 /*!
 * all.js v3.0.0
 * since 18/12/2020
 * Copyright 2020 Gilberto Villarreal Rodriguez
 * 				  < Gil_yeung@outlook.com >
 * Licensed open source
 */

/*=============================================
PETICION XMLHttpRequest
=============================================*/
//El navegador obtiene las especificaciones de getXmlHttpRequest
 function getXmlHttpRequestObject()
 {
	  if (window.XMLHttpRequest)
	  {
	    return new XMLHttpRequest();
	  }
	  else if (window.ActivateXObject)
	  {
	    return new ActiveXObject("Microsoft.XMLHTTP");
	  }
	  else
	  {
	    return swal("ADVERTENCIA", "No se puede crear el objeto XMLHttpRequest. Se recomienda actualizar su navegador", "warning");
	    
	  }
}
//function myAjax(ajax_url,datos=null){
function myAjax(ajax_url,datos){

	   // Creamos un nuevo objeto encargado de la comunicación
       var ajax_request = getXmlHttpRequestObject();
       ajax_request.open( "POST", ajax_url, false );//true = ASynchronous, false = Synchronous
       //ajax_request.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
       //Enviamos la solictud con los parámetros
       ajax_request.send(datos);
       //retornamos la respuesta
       return ajax_request.response;
}

//myAjax();

/*=============================================
AL PRECIONAR CONTINUE
=============================================*/
var form_in = $(".sign-in-htm");
$("#list_db").setAttribute("disabled", true);

function checkValidity(){/*checar si esta valid o invalid.*/
	//form_in[1].setCustomValidity("Introduce un host valido.");
	if (form_in[1].checkValidity() && form_in[2].checkValidity() &&form_in[3].checkValidity() &&form_in[4].checkValidity())
		return true;
	else
		return false;
}


 $("#button_continue").onclick=function(){
		
	 	if (!checkValidity()) {
	 		return;
	 	}
	 	show('#loader');
	 	window.setTimeout(btn_continue , 30);

 };

 function btn_continue(){

	var datos = new FormData();
	datos.append("show_db", 1);
	datos.append("host", $('#host').value );
	datos.append("port", $('#port').value );
	datos.append("user", $('#user').value );
	datos.append("pwd", $('#pwd').value );
	datos.append("name_db", "" );

	var res= myAjax('index.php', datos );
	//console.log(res);
	try	{
		if (res && JSON.parse(res)) {
			
			show("#select_database");
			show("#button_submit");
			hide("#button_continue");
			$("#list_db").removeAttribute("disabled");
		
			datosJson=JSON.parse(res);
			datosJson.forEach(addSelectDb);
			var res_db;
			
			function addSelectDb(item, index){
				res_db+='<option value="'+item+'">'+item+'</option>';
			}
			$("#list_db").innerHTML='<option hidden value="">Seleccione su base de datos</option><option  value="all">All Databases</option>'+res_db;
			form_in.removeAttribute("onsubmit");
		}
		else{

			hide("#select_database");
			hide("#button_submit");
			show("#button_continue");
			swal("ERROR", "Por favor introdusca datos validos.", "error");;
		}
	}catch(e){ swal("ERROR", res, "error");
	}
	hide('#loader');
	
 }


/*=============================================
 AL PRECIONAR RESTORE
=============================================*/
var form_up = $(".sign-up-htm");
function checkValidity2(){/*checar si esta valid o invalid.*/
	
	//form_up[0].setCustomValidity("Introduce un host valido.");
	if ( form_up[1].checkValidity() && form_up[2].checkValidity() &&form_up[3].checkValidity() &&
		form_up[4].checkValidity() && form_up[5].checkValidity() && form_up[6].checkValidity() )
		return true;
	else
		return false;
}
 $(".js-restore").onclick=function(){

 	if (!checkValidity2()) {
	 		return;
	}
 	show('#loader');
 };

hide("#select_database");
hide("#button_submit");

/*=============================================
HIDDEN SHOW FUNCTION
=============================================*/
function show(selector, display ="block") {
	document.querySelector(selector).style.display = display;
}
function hide(selector) {
	document.querySelector(selector).style.display = 'none';
}
/*=============================================
RETURN OBJECT FUNCTION
=============================================*/
function $(selector) {
		return  document.querySelector(selector);
 }

/*=============================================
 MSG
=============================================*/


if(res_msg == 'notHost' )
{
    swal("ERROR", "Por favor introdusca datos validos.", "error");
}   
else if(res_msg == 'notDb' )
{
     swal("ADVERTENCIA", "No se encontro la base de datos.", "warning");
}  
else if(res_msg == 'restored' )
{
 	swal("CORRECTO", "Base de datos restaurada exitosamente.", "success");
}
else if(res_msg == 'all_restored' )
{
 	swal("CORRECTO", "Los datos se restauraron exitosamente.", "success");
}
else if(res_msg == 'nocreatedb' )
{
 	swal("ERROR", "No se pudo crear la base de datos. \n verifica que tengas el permiso nesesario.", "error");
}
else if(res_msg == 'error_at_restore' )
{
 	swal("ERROR", "Ocurrio un error al restaurar. \n verifica que tengas el permiso nesesario.", "error");
}

else if(res_msg == '' )
{
 	//swal("INFORMACIÓN", "Ocurrio un error inesperado.", "info");
} 
else{
	swal("INFORMACIÓN", res_msg, "info");
}


if ($(".swal-button--confirm")){
	$(".swal-button--confirm").onclick=function(){ 
		history.pushState(null,"","?");
	}
}

