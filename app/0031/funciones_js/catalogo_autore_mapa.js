/**
*
* @package    	geoGEC
* @author     	GEC - Gestión de Espacios Costeros, Facultad de Arquitectura, Diseño y Urbanismo, Universidad de Buenos Aires.
* @author     	<mario@trecc.com.ar>
* @author    	http://www.municipioscosteros.org
* @copyright	2018 Universidad de Buenos Aires
* @copyright	esta aplicación se desarrollo sobre una publicación GNU 2017 TReCC SA
* @license    	http://www.gnu.org/licenses/gpl.html GNU AFFERO GENERAL PUBLIC LICENSE, version 3 (GPL-3.0)
* Este archivo es software libre: tu puedes redistriburlo 
* y/o modificarlo bajo los términos de la "GNU AFFERO GENERAL PUBLIC LICENSE" 
* publicada por la Free Software Foundation, version 3
* 
* Este archivo es distribuido por si mismo y dentro de sus proyectos 
* con el objetivo de ser útil, eficiente, predecible y transparente
* pero SIN NIGUNA GARANTÍA; sin siquiera la garantía implícita de
* CAPACIDAD DE MERCANTILIZACIÓN o utilidad para un propósito particular.
* Consulte la "GNU General Public License" para más detalles.
* 
* Si usted no cuenta con una copia de dicha licencia puede encontrarla aquí: <http://www.gnu.org/licenses/>.
*/



//funciones para el control del mapa

function cargaMapa(){

	_Mapa['estado']='';
	_Mapa['cargado']='no';
	_Mapa['dibujando']='no';
	
	_Mapa['objeto']={};

	_Mapa['M_Obr_pto']={};	
	
	_Mapa['M_ObrForm_pto']={};	

	
	
	_Mapa.M_Obr_pto['st'] = new ol.style.Style({
		   image: new ol.style.Circle({
			   fill: new ol.style.Fill({color: 'rgba(255,102,0,0.5)'}),
			   stroke: new ol.style.Stroke({color: 'rgba(255,102,0,0.8)',width: 0.8}),
			   radius: 6
			})
		});
	_Mapa.M_Obr_pto['src'] = new ol.source.Vector();
	_Mapa.M_Obr_pto['ly'] = new ol.layer.Vector({
		name:'obr_obras pto',
		source: _Mapa.M_Obr_pto.src,
		style:_Mapa.M_Obr_pto.st,
		declutter: true  
	});   
	
	
	_Mapa.M_ObrForm_pto['st'] = new ol.style.Style({
		   image: new ol.style.Circle({
			   fill: new ol.style.Fill({color: 'rgba(0,10,200,0.1)'}),
			   stroke: new ol.style.Stroke({color: 'rgba(0,10,200,1)',width: 0.8}),
			   radius: 8
			})
		});
	_Mapa.M_ObrForm_pto['src'] = new ol.source.Vector();
	_Mapa.M_ObrForm_pto['ly'] = new ol.layer.Vector({
		name:'obr_obra_formulada pto',
		source: _Mapa.M_ObrForm_pto.src,
		style:_Mapa.M_ObrForm_pto.st,
		declutter: true  
	});   	
	

	_Mapa['Interseccion_AI']={};
	_Mapa.Interseccion_AI['st'] = new ol.style.Style({
		fill: new ol.style.Fill({color: 'rgba(255,102,0,0.5)'}),
		stroke: new ol.style.Stroke({color: 'rgba(255,102,0,0.8)',width: 2}),
		zIndex:1
	});
	_Mapa.Interseccion_AI['src'] = new ol.source.Vector();
	_Mapa.Interseccion_AI['ly'] = new ol.layer.Vector({
		name:'Interseccion_AI',
		source: _Mapa.Interseccion_AI.src,
		style:_Mapa.Interseccion_AI.st
	});   


	_Mapa['referencia_gral']={};
	_Mapa.referencia_gral['src'] = new ol.source.TileWMS({
		  url: 'http://170.210.177.36:8080/geoserver/UNMgeo/wms',
		  params: {'LAYERS': 'UNMgeo:006_v001', 'TILED': true},
		  serverType: 'geoserver',
		  transition: 0,
    }),
	_Mapa.referencia_gral['ly'] = new ol.layer.Tile({
		name:'referencia general',
		source: _Mapa.referencia_gral.src
	});   

	_Mapa['Base']={};
	_Mapa.Base['src_ign']= new ol.source.TileWMS({
        url: 'https://wms.ign.gob.ar/geoserver/mapabase_gris/gwc/service/wms',
        crossOrigin: 'Anonymous',
        params: {
	        'VERSION': '1.1.1',
	        tiled: true,
	        LAYERS: 'mapabase_gris',
	        STYLES: '',
        }
	});
	_Mapa.Base['src_bing']= new ol.source.BingMaps({
	 	key: 'CygH7Xqd2Fb2cPwxzhLe~qz3D2bzJlCViv4DxHJd7Iw~Am0HV9t9vbSPjMRR6ywsDPaGshDwwUSCno3tVELuob__1mx49l2QJRPbUBPfS8qN',
	 	imagerySet:  'Aerial'
	});
	_Mapa.Base['src_osm']= new ol.source.Stamen({
		layer: 'toner'
	});
	_Mapa.Base['ly']= new ol.layer.Tile({
		source:_Mapa.Base.src_ign
	});
	
	_Mapa['View'] =	new ol.View({
      projection: 'EPSG:3857',
      center: [-6550000,-4113000],
      zoom: 11,
      minZoom:2,
      maxZoom:19	      
	});


	_Mapa['Dibuja_pto']={};
	_Mapa.Dibuja_pto['st']=new ol.style.Style({	 
	   image: new ol.style.Circle({
		   fill: new ol.style.Fill({color: 'rgba(255,102,0,0.5)'}),
		   stroke: new ol.style.Stroke({color: 'rgba(255,102,0,0.8)',width: 0.8}),
		   radius: 6
		}),
		fill: new ol.style.Fill({color: 'rgba(255,102,0,0.5)'}),
		stroke: new ol.style.Stroke({color: 'rgba(255,102,0,0.8)',width: 0.8}),
		zIndex:101
    });	

	_Mapa.Dibuja_pto['src'] = new ol.source.Vector({
		projection: 'EPSG:3857',
		wrapX: false // sin este set no funciona el draw ¨\:i/¨
	});
	_Mapa.Dibuja_pto['ly'] = new ol.layer.Vector({
		name:'obr_obras_pto',
		source: _Mapa.Dibuja_pto.src,
		style:_Mapa.Dibuja_pto.st
	});  
	
	_Mapa.Dibuja_pto.src.on('change', function(e){	
		_Mapa.objeto.removeInteraction(_Mapa.Dibuja_pto.accion);
		_features=_Mapa.Dibuja_pto.src.getFeatures();	    
		_format = new ol.format.WKT();
		if(_features[0] != undefined){
			_geometriatx=_format.writeGeometry(_features[0].getGeometry());
			document.querySelector('#formularioObra [name="geotx_loc"]').value=_geometriatx;
		}
		_Mapa.estado='cargado';
	});
		

	_Mapa.Dibuja_pto['accion'] = new ol.interaction.Draw({
		source: _Mapa.Dibuja_pto.src,
		type: 'Point'
	});
	
	
	document.getElementById('mapa').innerHTML='';
    document.getElementById('mapa').setAttribute('estado','activo');	
    
    
    _Mapa['objeto'] = new ol.Map({
	    layers: [
			_Mapa.Base.ly,
			_Mapa.referencia_gral.ly,
			_Mapa.Interseccion_AI.ly,
			
			_Mapa.M_Obr_pto.ly,
			_Mapa.M_ObrForm_pto.ly,
			_Mapa.Dibuja_pto['ly']
			
			
	    ],
	    target: 'mapa',
	    view: _Mapa.View
	});
	
	
	if(document.querySelector('.ol-zoom.ol-unselectable.ol-control .ol-zoom-out')!=null){
		//corrije el encode del zoom out
    	document.querySelector('.ol-zoom.ol-unselectable.ol-control .ol-zoom-out').innerHTML='-';
    }
    
    _Mapa.objeto.on('click', function(evt){   
		if(_Mapa.estado=='dibujando'){
			return;			
		} 	
		consultaPunto(evt.pixel,evt);       
	});
	
	

}




function generarBotonCapas(){
	
	_div=document.createElement('div');
	_div.setAttribute('id','menucapas');
	_div.setAttribute('abierto','-1');
	_div.setAttribute('modo','IGN');
	document.querySelector('#portamapa').appendChild(_div);
	
	
	_bc=document.createElement('a');
	_bc.setAttribute('id','botoncapas');
	
	_bc.innerHTML='<img src="./img/capas.png"></a>'
	_bc.setAttribute('onclick','this.parentNode.setAttribute("abierto",(parseInt(this.parentNode.getAttribute("abierto"))*-1))');
	_div.appendChild(_bc);
	
	_bc=document.createElement('div');
	_bc.setAttribute('id','opciones');
	_div.appendChild(_bc);
	
	_op=document.createElement('a');
	_op.setAttribute('onclick','baseMapaaIGN()');
	_op.setAttribute('modo','IGN');
	_op.innerHTML="callejero nacional: IGN"; 
	_bc.appendChild(_op);
	
	_op=document.createElement('a');
	_op.setAttribute('onclick','baseMapaaOSM()');
	_op.setAttribute('modo','OSM');
	_op.innerHTML="callejero gobal: OSM-Stamen."; 
	_bc.appendChild(_op);
	
	_op=document.createElement('a');
	_op.setAttribute('onclick','baseMapaaBing()');
	_op.setAttribute('modo','Bing');
	_op.innerHTML="satelital: Bing"; 
	_bc.appendChild(_op);
	
}


function baseMapaaIGN(){
	
	_Mapa.Base.ly.setSource(_Mapa.Base.src_ign);	
	document.querySelector('#portamapa #menucapas').setAttribute('modo','IGN');
	
}

function baseMapaaOSM(){
	_Mapa.Base.ly.setSource(_Mapa.Base.src_osm);	
	document.querySelector('#portamapa #menucapas').setAttribute('modo','OSM');
}



function baseMapaaBing(){
	_Mapa.Base.ly.setSource(_Mapa.Base.src_bing);	
	document.querySelector('#portamapa #menucapas').setAttribute('modo','Bing');
}



function consultaPunto(_pixel,_event) { 
	
	console.log(_pixel);
	console.log(_event.coordinate);
	console.log(_event);
	
	_wkt='POINT('+_event.coordinate[0]+' '+_event.coordinate[1]+')';
	consultaAI(_wkt);
		
		
	if(_Mapa.estado=='dibujando'){return;}
	if(_Mapa.estado=='nuevaGeom'){return;}  
	if(_Mapa.estado=='terminado'){return;}
	//if(_Dibujando=='si'){return;}	
	
	
}


function dibujarAI(_data){
	
	_Mapa.Interseccion_AI.src.clear();
	
	
	for(_i in _data.interseccion){
		_d=_data.interseccion[_i];
		_format = new ol.format.WKT();
		_feature = _format.readFeature(_d.geotx_intersec, {
		  dataProjection: 'EPSG:3857',
		  featureProjection: 'EPSG:3857',
		});
		_Mapa.Interseccion_AI.src.addFeature(_feature);
	}
	
}







function relocalizar(_tipo){	
	_Mapa.Dibuja_pto.src.clear();
	alert('Indique el punto de la obra en el mapa');
	_Mapa.objeto.addInteraction(_Mapa.Dibuja_pto.accion);
	_Mapa.estado='dibujando';
	
	 
}

