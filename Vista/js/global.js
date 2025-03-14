/////MENU PRINCIPAL
function menu_principal(nurl,modulo,opcion){
	if(nurl!="#")
    {    
    var parametros = {modulo:modulo,opcion:opcion};
	$("#home").empty();
	$("#home").append("<center><br><br><span class='red'>Cargando...</span></center>");    
	$.ajax({
		url:nurl,
		data:parametros,
		dataType:'html',
		type:'POST',
		success: function(datos){
					 		
							$("#home").empty();
							$("#home").append(datos);
							/*$("#sms_valido").hide();							
                            $("#sms_error").hide();*/
		   		}
		   });
	}
}