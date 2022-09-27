
function preprocesarRespuestaAjax(_data, _textStatus, _jqXHR){
	
    try {
        JSON.parse(_data);
    } catch (e) {
		alert('Error. La respuesta del servidor no tiene un forma Json válido');
        return false;
    }
    
    _res = JSON.parse(_data);
    
    if(_res.res != 'exito'){
		console.log(_res);
		alert('Ocurrio un error durante el procesamiento y no se obtuvo el resultado esperado.');
		
		return false;
	}
	
	for(_nm in _res.mg){
		alert(_res.mg[_nm]);
	}
	
    return _res;
}


function consultaAI(_coordenadas){
	
	_modo='punto';
	document.querySelector('#velo').setAttribute('estado','cargando');
	var parametros = {
		'modo':_modo,
		'coordenadas':_coordenadas,
		'distancia':_Distancia,
		'tabla':_Capa_consulta
	};
	
	
	$.ajax({
		data:  parametros,
		url:   './consultas_php/consultar_AI.php',
		type:  'post'
	}).done(function (_data, _textStatus, _jqXHR){
		_res = preprocesarRespuestaAjax(_data, _textStatus, _jqXHR);
		document.querySelector('#velo').setAttribute('estado','inactivo');
		if(_res===false){return;}
		
		dibujarAI(_res.data);
		mostrarValoresAI(_res.data);
		
	})	    
}
		
		
		
		
	


/*
* De acá en mas deprecar
* */

function consultaObras(){
	
	var parametros = {
		idautore:_IdAutore
	};
	
	document.querySelector('#tabla').setAttribute('estado','cargando');
	document.querySelector('#mapa').setAttribute('estado','cargando');
	$.ajax({
		data:  parametros,
		url:   './consultas_php/catalogo_autore_consulta_general.php',
		type:  'post',
		error:  function (response){alert('error al contctar el servidor');console.log(response);},
		success:  function (response){
			var _res = $.parseJSON(response);
			
			//alert('i');
			for(_nm in _res.mg){alert(_res.mg[_nm]);}
			
			if(_res.res!='exito'){
				alert('error al consultar la base de datos');
			}
				
			_Datos['Autore']=_res.data.Autore;
			_Datos['Obras']=_res.data.Obras;
			
			document.querySelector('#tabla').setAttribute('estado','cargado');
			document.querySelector('#mapa').setAttribute('estado','cargado');
				
			muestraObras();
			
		}
	});	
}

function consultaAutores(){
	
	var parametros = {
	};
	

	$.ajax({
		data:  parametros,
		url:   './consultas_php/catalogo_autore_consulta_aut_autores.php',
		type:  'post',
		error:  function (response){alert('error al contctar el servidor');console.log(response);},
		success:  function (response){
			var _res = $.parseJSON(response);
			
			//alert('i');
			for(_nm in _res.mg){alert(_res.mg[_nm]);}
			
			if(_res.res!='exito'){
				alert('error al consultar la base de datos');
			}
				
			_Datos['Autores']=_res.data.Autores;			
			
		}
	});	
}


function consultaReferencias(){
	
	var parametros = {
	};
	

	$.ajax({
		data:  parametros,
		url:   './consultas_php/catalogo_autore_consulta_ref_referencias.php',
		type:  'post',
		error:  function (response){alert('error al contctar el servidor');console.log(response);},
		success:  function (response){
			var _res = $.parseJSON(response);
			
			//alert('i');
			for(_nm in _res.mg){alert(_res.mg[_nm]);}
			
			if(_res.res!='exito'){
				alert('error al consultar la base de datos');
			}
				
			_Datos['Referencias']=_res.data.Referencias;			
			
		}
	});	
}


function nuevaObra(){
	var parametros = {
		'idautore':_IdAutore
	};
		
	$.ajax({
		data:  parametros,
		url:   './consultas_php/catalogo_autore_ed_obras_nueva.php',
		type:  'post',
		error:  function (response){alert('error al contctar el servidor');console.log(response);},
		success:  function (response){
			var _res = $.parseJSON(response);
			
			//alert('i');
			for(_nm in _res.mg){alert(_res.mg[_nm]);}
			
			if(_res.res!='exito'){
				alert('error al consultar la base de datos');
			}
				
			consultaObras();
			
		}
	});	
}


function enviarFormObra(){
	_form=document.querySelector('#formularioObra');		
		
	var parametros = {
		'idautore':_IdAutore,
		'nombre':_form.querySelector('[name="nombre"]').value,
		'id_p_obr_obras':_form.querySelector('[name="id_p_obr_obra"]').value,
		'geom_loc':_form.querySelector('[name="geotx_loc"]').value,
		//'geom_pol':_form.querySelector('[name="geom_pol"]').value,
		'ano_construccion':_form.querySelector('[name="ano_construccion"]').value,
		'ano_proyecto':_form.querySelector('[name="ano_proyecto"]').value,
		'direccion':_form.querySelector('[name="direccion"]').value,
		'observaciones':_form.querySelector('[name="observaciones"]').value
	};
		
	$.ajax({
		data:  parametros,
		url:   './consultas_php/catalogo_autore_ed_obras_guarda.php',
		type:  'post',
		error:  function (response){alert('error al contctar el servidor');console.log(response);},
		success:  function (response){
			var _res = $.parseJSON(response);
			
			//alert('i');
			for(_nm in _res.mg){alert(_res.mg[_nm]);}
			
			if(_res.res!='exito'){
				alert('error al consultar la base de datos');
			}
			
			cerrarFormularioObra()
		}
	});	
}




function nuevaColaboracion(){
	_id_p_obr_obras=document.querySelector('#formularioObra [name="id_p_obr_obra"]').value;
	_form=document.querySelector('#formularioColaboracion');
	_form.querySelector('[name="id_p_obr_obra"]').value=_id_p_obr_obras;
	_form.querySelector('[name="rol"]').value='';
	_form.querySelector('[name="id_p_aut_autores"]').value='0';
	_form.querySelector('[name="id_p_aut_autores_orig"]').value='';
	var parametros = {
		'idautore':_IdAutore,
		'id_p_obr_obras':_id_p_obr_obras
	};
		
	$.ajax({
		data:  parametros,
		url:   './consultas_php/catalogo_autore_ed_colaboraciones_nueva.php',
		type:  'post',
		error:  function (response){alert('error al contctar el servidor');console.log(response);},
		success:  function (response){
			var _res = $.parseJSON(response);
			
			//alert('i');
			for(_nm in _res.mg){alert(_res.mg[_nm]);}
			
			if(_res.res!='exito'){
				alert('error al consultar la base de datos');
			}
				
			_Datos.Obras[_res.data.id_p_obr_obra]['colaboraciones'][0]=_res.data.datacolaboracion;
			
			formulaColaboracion(_res.data.id_p_obr_obra,0);
			
		}
	});	
}



function enviarFormColabora(){
	_form=document.querySelector('#formularioColaboracion');		
		
	var parametros = {
		'idautore':_IdAutore,
		'id_p_obr_obras':_form.querySelector('[name="id_p_obr_obra"]').value,
		'id_p_aut_autores':_form.querySelector('[name="id_p_aut_autores"]').value,
		'id_p_aut_autores_orig':_form.querySelector('[name="id_p_aut_autores_orig"]').value,
		'rol':_form.querySelector('[name="rol"]').value
	};
		
	$.ajax({
		data:  parametros,
		url:   './consultas_php/catalogo_autore_ed_colaboraciones_guarda.php',
		type:  'post',
		error:  function (response){alert('error al contctar el servidor');console.log(response);},
		success:  function (response){
			var _res = $.parseJSON(response);
			
			//alert('i');
			for(_nm in _res.mg){alert(_res.mg[_nm]);}
			
			if(_res.res!='exito'){
				alert('error al consultar la base de datos');
			}
			
			_Datos.Obras[_res.data.id_p_obr_obras].colaboraciones=_res.data.Obras[_res.data.id_p_obr_obras].colaboraciones;
			formularObraColaboraciones(_res.data.id_p_obr_obras);
			cerrarformularioColaboracion();
		}
	});	
}


function eliminaColaboracion(){
	if(!confirm('¿Eliminamos esta colaboración?... Segure?')){return;}
	
	_form=document.querySelector('#formularioColaboracion');		
		
	var parametros = {
		'idautore':_IdAutore,
		'id_p_obr_obras':_form.querySelector('[name="id_p_obr_obra"]').value,
		'id_p_aut_autores_orig':_form.querySelector('[name="id_p_aut_autores_orig"]').value
	};
		
	$.ajax({
		data:  parametros,
		url:   './consultas_php/catalogo_autore_ed_colaboraciones_elimina.php',
		type:  'post',
		error:  function (response){alert('error al contctar el servidor');console.log(response);},
		success:  function (response){
			var _res = $.parseJSON(response);
			
			//alert('i');
			for(_nm in _res.mg){alert(_res.mg[_nm]);}
			
			if(_res.res!='exito'){
				alert('error al consultar la base de datos');
			}
			
			_Datos.Obras[_res.data.id_p_obr_obras].colaboraciones=_res.data.Obras[_res.data.id_p_obr_obras].colaboraciones;
			formularObraColaboraciones(_res.data.id_p_obr_obras);
			cerrarformularioColaboracion();
		}
	});	
}


function nuevoAutore(){
	alert('en desarrollo');
}



function nuevoLinkReferencia(){
	_id_p_obr_obras=document.querySelector('#formularioObra [name="id_p_obr_obra"]').value;
	_form=document.querySelector('#formularioLinkReferencia');
	_form.querySelector('[name="id_p_obr_obra"]').value=_id_p_obr_obras;
	_form.querySelector('[name="resumen"]').value='';
	_form.querySelector('[name="localizaciones_en_texto"]').value='';
	_form.querySelector('[name="id_p_ref_referencias"]').value='0';
	_form.querySelector('[name="id_p_ref_referencias_orig"]').value='';
	var parametros = {
		'idautore':_IdAutore,
		'id_p_obr_obras':_id_p_obr_obras
	};
		
	$.ajax({
		data:  parametros,
		url:   './consultas_php/catalogo_autore_ed_link_referencia_nueva.php',
		type:  'post',
		error:  function (response){alert('error al contctar el servidor');console.log(response);},
		success:  function (response){
			var _res = $.parseJSON(response);
			
			//alert('i');
			for(_nm in _res.mg){alert(_res.mg[_nm]);}
			
			if(_res.res!='exito'){
				alert('error al consultar la base de datos');
			}
				
			_Datos.Obras[_res.data.id_p_obr_obra]['referencias'][0]=_res.data.datareferencia;
			
			formulaLinkReferencia(_res.data.id_p_obr_obra,0);
			
		}
	});	
}



function enviarFormLinkReferencia(){
	_form=document.querySelector('#formularioLinkReferencia');		
		
	var parametros = {
		'idautore':_IdAutore,
		'id_p_obr_obras':_form.querySelector('[name="id_p_obr_obra"]').value,
		'id_p_ref_referencias':_form.querySelector('[name="id_p_ref_referencias"]').value,
		'id_p_ref_referencias_orig':_form.querySelector('[name="id_p_ref_referencias_orig"]').value,
		'resumen':_form.querySelector('[name="resumen"]').value,
		'localizaciones_en_texto':_form.querySelector('[name="localizaciones_en_texto"]').value
	};
		
	$.ajax({
		data:  parametros,
		url:   './consultas_php/catalogo_autore_ed_link_referencia_guarda.php',
		type:  'post',
		error:  function (response){alert('error al contctar el servidor');console.log(response);},
		success:  function (response){
			var _res = $.parseJSON(response);
			
			//alert('i');
			for(_nm in _res.mg){alert(_res.mg[_nm]);}
			
			if(_res.res!='exito'){
				alert('error al consultar la base de datos');
			}
			
			_Datos.Obras[_res.data.id_p_obr_obras].referencias=_res.data.Obras[_res.data.id_p_obr_obras].referencias;
			formularObraLinkReferencias(_res.data.id_p_obr_obras);
			cerrarFormularioLinkReferencia();
		}
	});	
}


function eliminaLinkReferencia(){
	if(!confirm('¿Eliminamos esta referencia?... Segure?')){return;}
	
	_form=document.querySelector('#formularioLinkReferencia');		
		
	var parametros = {
		'idautore':_IdAutore,
		'id_p_obr_obras':_form.querySelector('[name="id_p_obr_obra"]').value,
		'id_p_ref_referencias_orig':_form.querySelector('[name="id_p_ref_referencias_orig"]').value
	};
		
	$.ajax({
		data:  parametros,
		url:   './consultas_php/catalogo_autore_ed_link_referencia_elimina.php',
		type:  'post',
		error:  function (response){alert('error al contctar el servidor');console.log(response);},
		success:  function (response){
			var _res = $.parseJSON(response);
			
			//alert('i');
			for(_nm in _res.mg){alert(_res.mg[_nm]);}
			
			if(_res.res!='exito'){
				alert('error al consultar la base de datos');
			}
			
			_Datos.Obras[_res.data.id_p_obr_obras].referencias=_res.data.Obras[_res.data.id_p_obr_obras].referencias;
			formularObraLinkReferencias(_res.data.id_p_obr_obras);
			cerrarFormularioLinkReferencia();
		}
	});	
}


function eliminaObra(){
	if(!confirm('¿Eliminamos esta obra?... Segure?')){return;}
	
	_form=document.querySelector('#formularioObra');		
		
	var parametros = {
		'idautore':_IdAutore,
		'id_p_obr_obras':_form.querySelector('[name="id_p_obr_obra"]').value
	};
		
	$.ajax({
		data:  parametros,
		url:   './consultas_php/catalogo_autore_ed_obras_elimina.php',
		type:  'post',
		error:  function (response){alert('error al contctar el servidor');console.log(response);},
		success:  function (response){
			var _res = $.parseJSON(response);
			
			//alert('i');
			for(_nm in _res.mg){alert(_res.mg[_nm]);}
			
			if(_res.res!='exito'){
				alert('error al consultar la base de datos');
			}
			
			
			consultaObras();
			cerrarFormularioObra();
			
			
			
		}
	});	
}



function enviarFormDocumento(){
	_form=document.querySelector('#formularioDocumento');		
		
	var parametros = {
		'iddoc':_form.querySelector('[name="iddoc"]').value,
		'id_p_obr_obras':_form.querySelector('[name="id_p_obr_obras"]').value,
		'nombre':_form.querySelector('[name="nombre"]').value,
		'fuente':_form.querySelector('[name="fuente"]').value,
		'derechos':_form.querySelector('[name="derechos"]').value
	};
	
		
	$.ajax({
		data:  parametros,
		url:   './consultas_php/catalogo_autore_ed_documentacion_guarda.php',
		type:  'post',
		error:  function (response){alert('error al contctar el servidor');console.log(response);},
		success:  function (response){
			var _res = $.parseJSON(response);
			
			//alert('i');
			for(_nm in _res.mg){alert(_res.mg[_nm]);}
			
			if(_res.res!='exito'){
				alert('error al consultar la base de datos');
			}
			
			_Datos.Obras[_res.data.id_p_obr_obras].documentacion[_res.data.datadoc.id]=_res.data.datadoc;
			formularDocumentosAdjuntos(_res.data.id_p_obr_obras);
			cerrarFormularioDocumento();
			
		}
	});	
}
