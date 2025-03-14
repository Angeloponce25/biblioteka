function iniciarSesion(){
	$("#mensaje_error").hide();
	var usuario=$("#usuario").val();
	var clave=$("#clave").val();
	if(usuario==''){
		alert("Ingrese el usuario");		
		$("#usuario").focus();
		return false;
	}
	if(clave==''){
		alert("Ingrese la contrase\u00F1a");		
		$("#clave").focus();
		return false;		
	}	 
	document.getElementById('resultado').innerHTML='Cargando';
	var parametros = {iniciarSesion:1,
			usuario:usuario,
			clave:clave			
    };
	   $.ajax({
	       url: "Controlador/co_sesion.php",
		   type: "POST",
		   data: parametros,
     	   dataType: "JSON",
		   success: function (datos)
		   			{
	     				 if(datos!='error'){
					 		document.getElementById('resultado').innerHTML='Acceso correcto';							 
								 var id_usuario;
							 		for(var i=0;i<(datos.length);i++)
									{
							              id_usuario=datos[i].id_usuario;
							  		}
								if( (id_usuario=='' || id_usuario==null) || datos==null ){
										document.getElementById('resultado').innerHTML='No se pudo acceder - problemas con la conexi\xf3n al servidor';
								}else{									
									    var url='Vista/modulos/verificarSesion.php';    									
										window.open(url+"?id_usuario="+id_usuario, '_self');								
								}
						 }else{
							 document.getElementById('resultado').innerHTML='No se pudo acceder - usuario o password incorrectos';							 
						 }
                         
			   		}
		   });   
}
function cerrarSesion(){
	if(confirm("Desea realmente salir del Sistema?")){
		var url='salir.php';
		window.open(url+"?err=0", '_self');
	}	
}