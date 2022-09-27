



function formularObra(_id_p_obr_obras){
	
	_dat=_Datos.Obras[_id_p_obr_obras];
	
	_form=document.querySelector('#formularioObra');
	
	_form.querySelector('[name="id_p_obr_obra"]').value=_id_p_obr_obras;
	_form.setAttribute('estado','activo');
	
	_form.querySelector('#nombres').innerHTML='';
	_form.querySelector('#listanombre').innerHTML='';
	
	_in=document.createElement('input');
	_in.value=_dat.nombre;
	_in.setAttribute('name','nombre');
	_form.querySelector('#nombres').innerHTML=_dat.nombre+' ,';
	_form.querySelector('#listanombre').appendChild(_in);
	

	_form.querySelector('[name="ano_construccion"]').value=_dat.ano_construccion;
	_form.querySelector('[name="geotx_loc"]').value=_dat.geotx_loc;
	
	_form.querySelector('[name="ano_proyecto"]').value=_dat.ano_proyecto;
	
	_form.querySelector('#listadirecciones').innerHTML='';
	_in=document.createElement('input');
	_in.value=_dat.direccion;
	_in.setAttribute('name','direccion');
	_form.querySelector('#listadirecciones').appendChild(_in);
	
	formularDocumentosAdjuntos(_id_p_obr_obras);
		
	formularObraColaboraciones(_id_p_obr_obras);
	
	formularObraLinkReferencias(_id_p_obr_obras);
	
	_c=_dat.geotx_loc.replace('POINT(','');
	_c=_c.replace(')','');
	_cc=_c.split(' ');
	
	_arr=[parseInt(_cc[0]),parseInt(_cc[1])];
	

	_Mapa.M_ObrForm_pto.src.clear();
	if(_dat.geotx_loc!=null&&_dat.geotx_loc!=''){
		console.log(_dat.geotx_loc);
		
		_format = new ol.format.WKT();
		_ft = _format.readFeature(_dat.geotx_loc, {
			dataProjection: 'EPSG:3857',
			featureProjection: 'EPSG:3857'
		});	    
		_Mapa.M_ObrForm_pto.src.addFeature(_ft);
	}
	
	
	
	_Mapa.View.setCenter(_arr);
	
	_Mapa.View.setZoom(17);
}

function formularDocumentosAdjuntos(_id_p_obr_obras){
	_dat=_Datos.Obras[_id_p_obr_obras];
	document.querySelector('#adjuntoslista').innerHTML='';
	for(_dn in _dat.documentacion){
		_daj=_dat.documentacion[_dn];
			
		_div=document.createElement('a');
		document.querySelector('#adjuntoslista').appendChild(_div);
		_div.setAttribute('class','adjunto');
		//_div.setAttribute('ruta','./documentos/'+_daj.tipo+'/'+_daj.fi_documento);
		
		_div.setAttribute('onclick','formularDocumento("'+_id_p_obr_obras+'","'+_daj.id+'")');
		
		//_div.setAttribute('href','./documentos/obr_obras/'+_id_p_obr_obras.padStart(8, "0")+'/originales/'+_daj.fi_documento);
		//_div.setAttribute('download',_daj.fi_original);
		_div.setAttribute('idadj',_daj.id);
		//_div.setAttribute('onclick','mostrarAdjunto(this)');
		
		_img2=document.createElement('img');
		if(_daj.fi_muestra==null){
			if(_daj.fi_tipo==''){_daj.fi_tipo='desconocido';}
			_img2.setAttribute('src','./img/file_'+_daj.fi_tipo+'.png');
			_img2.setAttribute('style','image-rendering: pixelated;');
		}else{
			_img2.setAttribute('src','./documentos/obr_obras/'+_id_p_obr_obras.padStart(8, "0")+'/muestras/'+_daj.fi_muestra);
		}
		_div.appendChild(_img2);
		_epi=document.createElement('div');
		_epi.setAttribute('class','epigrafe');
		_epi.innerHTML=_daj.fi_original;
		_div.appendChild(_epi);
		
		_borr=document.createElement('a');
		_borr.setAttribute('class','elimina');
		_borr.setAttribute('onclick','event.stopPropagation();eliminaAdjunto("'+_id_p_obr_obras+'","'+_daj.id+'")');
		_borr.innerHTML='x';
		_borr.title='Eliminar este adjunto';
		_div.appendChild(_borr);	
	}
}


function formularDocumento(_id_p_obr_obras,_id_p_doc){
	_form=document.querySelector('#formularioDocumento');
	_form.setAttribute('estado','activo');
	_dat=_Datos.Obras[_id_p_obr_obras].documentacion[_id_p_doc];
	
	_form.querySelector('[name="id_p_obr_obras"]').value=_id_p_obr_obras;
	_form.querySelector('[name="iddoc"]').value=_id_p_doc;
	_form.querySelector('#descarga').setAttribute('href','./documentos/obr_obras/'+_id_p_obr_obras.padStart(8, "0")+'/originales/'+_dat.fi_documento);
	_form.querySelector('#descarga').setAttribute('download',_dat.fi_original);
	
	if(_dat.fi_muestra==null){
		if(_dat.fi_tipo==''){_dat.fi_tipo='desconocido';}
		_form.querySelector('#muestra').setAttribute('src','./img/file_'+_dat.fi_tipo+'.png');
	}else{
		_form.querySelector('#muestra').setAttribute('src','./documentos/obr_obras/'+_id_p_obr_obras.padStart(8, "0")+'/muestras/'+_dat.fi_muestra);
	}
	
	_form.querySelector('#tipo').innerHTML=_dat.fi_extension;
	_form.querySelector('[name="nombre"]').value=_dat.nombre;
	_form.querySelector('[name="fuente"]').value=_dat.fuente;
	_form.querySelector('[name="derechos"]').value=_dat.derechos;	
}




function formularObraColaboraciones(_id_p_obr_obras){
	_form=document.querySelector('#formularioObra');
	_form.querySelector('#listacolaboraciones').innerHTML='';
	_dat=_Datos.Obras[_id_p_obr_obras];
	for(_nc in _dat.colaboraciones){
		_datc=_dat.colaboraciones[_nc];
		_a=document.createElement('a');
		_a.innerHTML=_datc.apellido+', '+_datc.nombre+' ('+_datc.rol+')';
		_a.setAttribute('onclick','formulaColaboracion("'+_id_p_obr_obras+'","'+_datc.id_p_aut_autores+'")');
		_form.querySelector('#listacolaboraciones').appendChild(_a);
	}
}

function formularObraLinkReferencias(_id_p_obr_obras){
	_form=document.querySelector('#formularioObra');
	_form.querySelector('#listareferencias').innerHTML='';
	_dat=_Datos.Obras[_id_p_obr_obras];
	for(_nc in _dat.referencias){
		_datc=_dat.referencias[_nc];
		_a=document.createElement('a');
		_a.innerHTML=_datc.nombre+' ('+_datc.autores+')';
		_a.setAttribute('onclick','formulaLinkReferencia("'+_id_p_obr_obras+'","'+_datc.id_p_ref_referencias+'")');
		_form.querySelector('#listareferencias').appendChild(_a);
	}
}



function formulaColaboracion(_id_p_obr_obras,_id_p_aut_autores){
	
	_datcol=_Datos.Obras[_id_p_obr_obras].colaboraciones[_id_p_aut_autores];
	_form=document.querySelector('#formularioColaboracion');
	_form.querySelector('[name="id_p_obr_obra"]').value=_id_p_obr_obras;
	_form.querySelector('[name="rol"]').value=_datcol.rol;
	_form.querySelector('[name="id_p_aut_autores_orig"]').value=_id_p_aut_autores;
	_form.setAttribute('estado','activo');

	_sel=_form.querySelector('[name="id_p_aut_autores"]');
	
	for(_an in _Datos.Autores){
		_data=_Datos.Autores[_an];
		_op=document.createElement('option');
		_op.value=_data.id_p_aut_autores;
		_op.innerHTML=_data.apellido+', '+_data.nombre;
		_sel.appendChild(_op);
	}
	
	_sel.value=_datcol.id_p_aut_autores;
}



function formulaLinkReferencia(_id_p_obr_obras,_id_p_ref_referencias){
	
	_datref=_Datos.Obras[_id_p_obr_obras].referencias[_id_p_ref_referencias];
	_form=document.querySelector('#formularioLinkReferencia');
	_form.querySelector('[name="id_p_obr_obra"]').value=_id_p_obr_obras;
	_form.querySelector('[name="resumen"]').value=_datref.resumen;
	_form.querySelector('[name="localizaciones_en_texto"]').value=_datref.localizaciones_en_texto;
	_form.querySelector('[name="id_p_ref_referencias_orig"]').value=_id_p_ref_referencias;
	_form.setAttribute('estado','activo');

	_sel=_form.querySelector('[name="id_p_ref_referencias"]');
	_sel.innerHTML='';
	for(_an in _Datos.Referencias){
		_data=_Datos.Referencias[_an];
		_op=document.createElement('option');
		_op.value=_data.id_p_ref_referencias;
		_op.innerHTML=_data.nombre+' ('+_datc.autores+')';
		_sel.appendChild(_op);
	}
	
	_sel.value=_datref.id_p_ref_referencias;
}



function cerrarformularioColaboracion(){
	document.querySelector('#formularioColaboracion').removeAttribute('estado');
}

function cerrarFormularioObra(){
	document.querySelector('#formularioObra').removeAttribute('estado');
	muestraObras();
}
function cerrarFormularioLinkReferencia(){
	document.querySelector('#formularioLinkReferencia').removeAttribute('estado');
	muestraObras();
}


function cerrarFormularioDocumento(){
	document.querySelector('#formularioDocumento').removeAttribute('estado');
}
