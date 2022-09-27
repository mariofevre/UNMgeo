function mostrarValoresAI(_data){
	_acc={};
	for(_i in _data.interseccion){
		_d=_data.interseccion[_i];
		
		
		for(_campo in _d){
			_v=_d[_campo]*_d.area_intersec/_d.area_orig;
			
			if(_campo=='id'){continue;}
			if(_campo=='gid'){continue;}
			if(_campo=='LINK'){continue;}
			if(_campo=='link'){continue;}
			if(_campo=='densidad_pob'){continue;}
			if(_campo=='db_id'){continue;}
			if(_campo=='area_orig'){continue;}
			if(_campo=='area_intersec'){continue;}
			
			if(Number.isNaN(Number(_v))){continue;}
			console.log(_campo+':'+_acc[_campo]);
			if(_acc[_campo]==undefined){
				_acc[_campo]=0;
			}
			//console.log(_campo+':'+_acc[_campo]);
			_acc[_campo]+=Number(_v);
			//console.log(_campo+':'+_acc[_campo]);
		}
	}
	
	document.querySelector('#portatabla #campos').innerHTML='Resultados obtenidos (a una distancia de 1000m del punto indicado):<br>';
	
	
	for(_campo in _acc){
		_v=_acc[_campo];
		
		_fila=document.createElement('div');
		_fila.setAttribute('class','fila');
		document.querySelector('#portatabla #campos').appendChild(_fila);
		
		_tit=document.createElement('div');
		_tit.setAttribute('class','titulo');
		_fila.appendChild(_tit);
		_tit.innerHTML=_campo;
		
		_val=document.createElement('div');
		_val.setAttribute('class','resultado');
		
		_fila.appendChild(_val);
		_val.innerHTML=formatear(Math.round(_v).toString());
		
	}
}


function muestraObras(){
	document.querySelector('#tabla tbody').innerHTML='';
	for(_no in _Datos.Obras){
		_dat=_Datos.Obras[_no];
		
		_tr=document.createElement('tr');
		_tr.setAttribute('id_p_obr_obras',_dat.id_p_obr_obras);
		_tr.setAttribute('onclick','formularObra(this.getAttribute("id_p_obr_obras"))');
		document.querySelector('#tabla tbody').appendChild(_tr);
		
		_td=document.createElement('td');
		_td.innerHTML=_dat.id_p_obr_obras;
		_tr.appendChild(_td);
		
		_td=document.createElement('td');
		_td.innerHTML=_dat.nombre;
		_tr.appendChild(_td);
		
		_td=document.createElement('td');
		_td.innerHTML=_dat.ano_construccion +' <br>('+_dat.ano_proyecto+')';
		_tr.appendChild(_td);
		
		_td=document.createElement('td');
		_tr.appendChild(_td);
		
		_div=document.createElement('div');
		_div.setAttribute('id','colaboraciones');
		_td.appendChild(_div);
		
		for(_nc in _dat.colaboraciones){
			_datc=_dat.colaboraciones[_nc];
			_a=document.createElement('a');
			_a.innerHTML=_datc.apellido+', '+_datc.nombre+' ('+_datc.rol+')';
			_div.appendChild(_a);
		}
		
		_td=document.createElement('td');
		_td.innerHTML=_dat.direccion;
		_tr.appendChild(_td);
		
		_td=document.createElement('td');
		_td.setAttribute('class', 'observaciones');
		_td.innerHTML=_dat.observaciones;
		_tr.appendChild(_td);

		_td=document.createElement('td');
		
		_tr.appendChild(_td);

		_div=document.createElement('div');
		_div.setAttribute('id','referencias');
		_td.appendChild(_div);
		
		for(_nc in _dat.referencias){
			_datc=_dat.referencias[_nc];
			_a=document.createElement('a');
			_a.innerHTML='<img src="./img/reporte.png">';
			_a.title=_datc.nombre+' ('+_datc.autores+')';
			_div.appendChild(_a);
		}
		
				
		_td=document.createElement('td');
		_td.innerHTML='<div id="documentacion">'+Object.keys(_dat.documentacion).length + ' Docs'+'</div>';
		_tr.appendChild(_td);
		
		_td=document.createElement('td');
		_tr.appendChild(_td);
		
		
		if(_dat.geotx_loc!=null&&_dat.geotx_loc!=''){
			console.log(_dat.geotx_loc);
			
			_format = new ol.format.WKT();
			_ft = _format.readFeature(_dat.geotx_loc, {
				dataProjection: 'EPSG:3857',
				featureProjection: 'EPSG:3857'
			});	    
			_Mapa.M_Obr_pto.src.addFeature(_ft);
		}
		
		if(_dat.geotx_pol!=null&&_dat.geotx_pol!=''){
			_format = new ol.format.WKT();
			_ft = _format.readFeature(_dat.geotx_pol, {
				dataProjection: 'EPSG:3857',
				featureProjection: 'EPSG:3857'
			});	    
			_Mapa.M_Obr_pol.src.addFeature(_ft);	
		}
					
	}
	
	
	document.querySelector('#mapa').setAttribute('estado','cargado');
	
}





