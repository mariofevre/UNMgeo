<?php 
/**
* CAP_muestra.php
*
* muestra y permite gestionar una capa de información
*  
* 
* @package    	unmGEO
* @subpackage 	difusion
* @author     	Universidad Nacional de Moreno
* @author     	<mario@trecc.com.ar>
* @author    	http://www.unm.edu.ar/
* @author		based on TReCC SA Procesos Participativos Urbanos, development. www.trecc.com.ar/recursos
* @copyright	2016 Universidad Nacional de Moreno
* @copyright	esta aplicación se desarrollo sobre una publicación GNU (agpl) 2014 TReCC SA
* @license    	https://www.gnu.org/licenses/agpl-3.0-standalone.html GNU AFFERO GENERAL PUBLIC LICENSE, version 3 (agpl-3.0)
* Este archivo es parte de TReCC(tm) paneldecontrol y de sus proyectos hermanos: baseobra(tm), TReCC(tm) intraTReCC  y TReCC(tm) Procesos Participativos Urbanos.
* Este archivo es software libre: tu puedes redistriburlo 
* y/o modificarlo bajo los términos de la "GNU AFero General Public License version 3" 
* publicada por la Free Software Foundation
* 
* Este archivo es distribuido por si mismo y dentro de sus proyectos 
* con el objetivo de ser útil, eficiente, predecible y transparente
* pero SIN NIGUNA GARANTÍA; sin siquiera la garantía implícita de
* CAPACIDAD DE MERCANTILIZACIÓN o utilidad para un propósito particular.
* Consulte la "GNU General Public License" para más detalles.
* 
* Si usted no cuenta con una copia de dicha licencia puede encontrarla aquí: <http://www.gnu.org/licenses/>.
*/

//if($_SERVER[SERVER_ADDR]=='192.168.0.252')ini_set('display_errors', '1');ini_set('display_startup_errors', '1');ini_set('suhosin.disable.display_errors','0'); error_reporting(-1);

// verificación de seguridad 
//include('./includes/conexion.php');
//include('./includes/conexionusuario.php');

// funciones frecuentes
//include("./includes/fechas.php");
//include("./includes/cadenas.php");
session_start();

?>
<!DOCTYPE html>
<head>
	<title>UNM - GEO - portal</title>
	<META http-equiv="Content-Type" content="text/html; charset=windows-1252">
	<link rel="stylesheet" type="text/css" href="./css/unmgeo.css">
	
	<link rel="stylesheet" type="text/css" href="./css/CAP.css?v=9">
	
	<link rel="stylesheet" type="text/css" href="./cap/capas.css?v=9">
	<link rel="stylesheet" type="text/css" href="./cap/cap_form.css?v=9">

		<style type="text/css">

		#mce_redacc_ifr{
			height:22vh !important;
			width:74vw !important;
		}
		.mce-tinymce.mce-container.mce-panel{
			width:74vw !important;
		}
		</style>
</head>

<body>
	
	<script type="text/javascript" src="./js/jquery/jquery-1.12.0.min.js"></script>
	<script type="text/javascript" src="./js/ol4.2/ol.js"></script>	 
		
	<img id="fondo" src="./img/fondo.png">		

	<div id='logbar'>
		<div id='disclaimer'>
			<p>
				Esta es una web experimental, la información aquí disponible es solo una previsualización de las capacidades de la plataforma informática en desarrollo
				y no debe ser utilizada como información válida para el desarrollo de documentos científicos.
			</p>
		</div>		
		<div id='usu'>
		<p id='hola'>hola: ...</p><br>
		<a id='botonacceder' onclick='formUsuario()'>acceder</a>
		<a id='botonsalir' onclick='salir()'>salir</a>
		</div>
	</div>
		
	<div id='velo'></div>
	
				<div id='lacapa'>
					<a id='volver' href='./index.php'>vover al inicio</a>
		
					<h2 id='tituloCapa'>
                        <span id='nom'>cargando...</span>
                        <div id='acciones'>
                            <a id='gcapa' onclick='cargarFormularioCapa()'>modificar datos de la capa</a>
                        	<a id='iralproyecto'>ver proyecto de origen</a>
                            <a id='cargarcapa' onclick='formversion()'>+ cargar SHP</a>
                            <a id='crearwms' onclick='crearWMS(this)'>+ crear WMS</a>
                        </div>
                    </h2>
					<p id='descripcion'>cargando...</p>
					<img id='aver' src='./img/mapear.png' alt='mapa' title='ver esta capa en un mapa'>
					<img id='awms' src='./img/urlWMS.png' alt='wms' title='ver conexion wms para esta capa'>
					<img id='ashp' src='./img/descargaSHP.png' alt='shp' title='descargar en formato shp'>
					<a style='display:none;'>cargar una nueva versión</a>
					<h3>Documentos disponibles (<span id='doccant'></span>)</h3>
					<div id='documentos'></div>
					<h3>Metadatos</h3>
					<div id='metadatos'></div>
					
				</div>
				
			</div>			
		</div>
	</div></div>
	
<div class='formcentral' id='formcargaverest' idver=''>
	<div id='avanceproceso'></div>
	<a class='cerrar' onclick='this.parentNode.style.display="none";'>x- cerrar</a>
	<h1>formulario para la carga de una nueva versión para una capa</h1>
	<a id='botonformversion' onclick='formversion()'>cargar una nueva versión</a>
	<div id='carga'>
		<h2> usted está cargando una nueva versión con el id <span id='idnv'></span></h2>
		<p id='nomver'></p>

		<div class='componentecarga'>
			<h1>archivos cargando</h1>
			<div id='archivosacargar'>
				<form id='shp' enctype='multipart/form-data' method='post' action='./ed_ai_adjunto.php'>			
					<label style='position:relative;' class='upload'>							
					<span id='upload' style='position:absolute;top:0px;left:0px;'>arrastre o busque aquí un archivo</span>
					<input id='uploadinput' style='opacity:0;' type='file' multiple name='upload' value='' onchange='enviarSHP(event,this);'></label>
					<select id='crs' onchange='ValidarProcesarBoton()'>
						<option value=''>- elegir -</option>
						<option value='4326'>4326</option>
						<option value='3857'>3857</option>
						<option value='22171'>22171</option>
						<option value='22172'>22172</option>
						<option value='22173'>22173</option>
						<option value='22174'>22174</option>
						<option value='22175'>22175</option>
						<option value='22176'>22176</option>
						<option value='22177'>22177</option>
					</select>
					
					<div id='cargando'></div>
				</form>
			</div>
		</div>
		
		<div class='componentecarga'>
			<h1>archivos cargados</h1>
			<p id='txningunarchivo'>- ninguno -</p>
			<div id='archivoscargados'></div>
		</div>
		
		<div class='componentecargalargo'>
			<h1>campos identificados</h1>
			<p id='verproccampo'></p>
			<div id='camposident'></div>			
		</div>
		
		<div class='componentecarga'>
			<h1>Acciones</h1>
			<a onclick='eliminarCandidatoVersion(this.parentNode);'>eliminar esta versión candidata</a>
			<a onclick='guardarVer(this.parentNode);'>guardar esta versión preliminarmente</a>
			<a id='ProcesarBoton' onclick='procesarVersion(this.parentNode)'>procesar la carga de esta versión</a>
		</div>
	</div>
	
</div>

<script type="text/javascript">



function cargarFormularioCapavacio(){
        
    $.ajax({
        url:   './cap/cap_form.php',
        type:  'post',
        success:  function (response){
        
            document.body.innerHTML +=response;
        
            tinymce.init({ 
                selector:'textarea.mceEditable',
                plugins: "code",
                toolbar: "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | code",
                menubar: false,
                width : "615px",
                height : "280px",
                skin : "unmapa",
                forced_root_block: "p",
                remove_trailing_nbsp : true,
                remove_trailing_brs: true,
                editor_deselector : "mceNoEditor",
                invalid_elements : "br"
            });
        }
    });
    
}
cargarFormularioCapavacio();

function cargarFormularioCapa(){
    
        _form=document.querySelector('#cap_form');
        _form.style.display='block';
        _form.querySelector('input[name="id"]').value=_DatCapa.capa.id;
        _form.querySelector('input[name="accion"]').value='cambiar';
        _form.querySelector('input[type="submit"]').value='cambiar';
        _form.querySelector('input[name="nombre"]').value=_DatCapa.capa.nombre;
        _form.querySelector('input[name="codigo"]').value=_DatCapa.capa.codigo;
        _form.querySelector('textarea[name="descripcion"]').value=_DatCapa.capa.descripcion;
        
        var editor = tinymce.get('mce_redacc'); // use your own editor id here - equals the id of your textarea
        editor.setContent(_DatCapa.texto);	
        
}
</script>
<script type="text/javascript">

	<?php
	foreach($_SESSION["unmgeo"]["usuario"] as $k => $v){
		$dataU[$k]=utf8_encode($v);
	}
	?>	
	
	
	var _UsuarioA= $.parseJSON('<?php echo json_encode($dataU);?>');
	if(_UsuarioA==null){
		_UsuarioA={'nombre':'Anónimo','apellido':''}
	}
	cargarusuario();
	
	var _NomCapa='';
	function cargarusuario(){
		if(_UsuarioA==null){
			_UsuarioA={'nombre':'Anónimo','apellido':''}
		}
	
		document.querySelector('#hola').innerHTML="Hola: "+_UsuarioA.nombre+" "+_UsuarioA.apellido;
		document.querySelector('#botonacceder').style.display='none';
		document.querySelector('#botonsalir').style.display='inline-block';
		
		if(_UsuarioA.dni==undefined){
			document.querySelector('#botonacceder').style.display='inline-block';
			document.querySelector('#botonsalir').style.display='none';
		}
		
	}

	var _IdCapa= '<?php echo $_GET['id'];?>';
	var _IdPro= '';
	var _DatCapa={};
	function cargarCapa(){		
		var parametros = {
			'funcion':'cargarCapa',
			'id': _IdCapa
		};
		
		$.ajax({
			data:  parametros,
			url:   './dbpgcon/con_capa.php',
			type:  'post',
			success:  function (response){
				var _res = $.parseJSON(response);
				console.log(_res);
				
				if(_res.res=='exito'){
                    _DatCapa=_res.data;
					//cargarLocalizaciones(_res.data);
					//actualizarMedicionesRelevTodos();
					_cont=document.getElementById('capalista');
					
					_dat=_res.data;				
					_IdPro=_dat.capa.id_p_pro_proyectos;
					
					_app= document.querySelector('#lacapa #tituloCapa #acciones #iralproyecto');
					_app.setAttribute('href','./PRO_muestra.php?id='+_dat.capa.id_p_pro_proyectos);
					
					
					
					if(_UsuarioA.sis_adm==1||_dat.usuarioresponsable=='si'){
						document.querySelector('#lacapa #tituloCapa #acciones #cargarcapa').style.display='inline-block';
						document.querySelector('#lacapa #tituloCapa #acciones #gcapa').style.display='inline-block';
						document.querySelector('#lacapa #tituloCapa #acciones #gcapa').style.display='inline-block';						
					}
					_dit=document.querySelector('#lacapa #tituloCapa #nom');							
					_dit.innerHTML=_dat.capa.nombre;
					
					_div=document.querySelector('#lacapa #descripcion');		
					_div.innerHTML=_dat.capa.descripcion;
											 
					_pad = "000"
					_strid = _pad.substring(0, _pad.length - _dat.capa.id.length) + _dat.capa.id;
					_strver = _pad.substring(0, _pad.length -  _dat.capa.geodata.ultimaversion.length) + _dat.capa.geodata.ultimaversion;
					_NomCapa=_strid+"_v"+_strver;
					
					_ssp=document.createElement('span');
					_ssp.innerHTML=_NomCapa;
					_ssp.setAttribute('id','codigo');
					
					_dit.appendChild(_ssp);
					
					//_dit.appendChild(_clacc);
					
					_aa=document.querySelector('#lacapa #aver');		
					
					
					if(_dat.capa.ultimaversion==undefined){
					
                        document.querySelector('#lacapa #aver').style.display='none';
                        document.querySelector('#lacapa #awms').style.display='none';        
                        document.querySelector('#lacapa #ashp').style.display='none';        
                        
                    }else{
                        
                        if(_dat.capa.ultimaversion.disponible_wms=='0'){
                        
                            if(_UsuarioA.sis_adm==1||_dat.usuarioresponsable=='si'){
                                document.querySelector('#crearwms').style.display='inline-block';    
                            }
                            
                            document.querySelector('#lacapa #aver').style.opacity='0.2';        
                            document.querySelector('#lacapa #awms').style.opacity='0.2';        
                            document.querySelector('#lacapa #ashp').style.opacity='0.2';        
                            
                        }else{
                        
                            document.querySelector('#crearwms').style.display='none';
                            
                        }
                        
                    }
										
					_aa.setAttribute('onclick','navegarCapa(event,this);desaltarIMG(this)');
					_aa.innerHTML="ver";
					_aa.setAttribute('onmouseover','resaltarIMG(this)');
					_aa.setAttribute('onmouseout','desaltarIMG(this)');		
					
					_aa=document.querySelector('#lacapa #awms');	
					 if(_dat.capa.geodata.ultimaversion=='0'){_aa.style.display='none';}
					
					_alert='alert("esta capa se encuentra disponible bajo el servicio wms unmgeo \\n conexion: http://170.210.177.36:8080/geoserver/ows \\n bajo el nombre: '+_NomCapa+'")';			
					_aa.setAttribute("onclick",_alert);	
					_aa.setAttribute('onmouseover','resaltarIMG(this)');
					_aa.setAttribute('onmouseout','desaltarIMG(this)');	
		
					
					_aa=document.querySelector('#lacapa #ashp');
					if(_dat.capa.geodata.ultimaversion=='0'){_aa.style.display='none';}
					_aa.setAttribute('onclick','descargarSHP(this,event)');		
					_aa.setAttribute('onmouseover','resaltarIMG(this)');
					_aa.setAttribute('onmouseout','desaltarIMG(this)');		
					_link=Array();
					
					_link.host="http://170.210.177.36:8080/geoserver/UNMgeo/";			
					
					_link.standarSHP="ows?service=WFS&version=1.0.0&request=GetFeature&maxFeatures=1000000&outputFormat=SHAPE-ZIP";
					_link.capaSHP="&typeName=UNMgeo:"+_NomCapa;
					_aa.setAttribute('link',_link.host+_link.standarSHP+_link.capaSHP+_link.extension);
					_aa.setAttribute('link',_link.host+_link.standarSHP+_link.capaSHP);//retiramos el recorte para la descarga					
					
									
					if(_dat.capa.geodata.ultimaversion=='0'){
						_ddd=document.createElement('div');
						_ddd.innerHTML='Esta Capa no tiene ninguna versión disponible en este momento.';
						document.querySelector('#lacapa #descripcion').appendChild(_ddd);		
					}
					
					_div=document.querySelector('#lacapa #doccant');
					_div.innerHTML=Object.keys(_dat.docs).length;
					
					_div=document.querySelector('#lacapa #documentos');
					for(_nn in _dat.docs){
						
							_dd=document.createElement('a');
							_dd.innerHTML=_dat.docs[_nn].archivo
							_dd.setAttribute('href','./documentos/'+_dat.docs[_nn].ruta);
							_dd.setAttribute('download',_dat.docs[_nn].archivo);
							_div.appendChild(_dd);
						
					}
					
					_div=document.querySelector('#lacapa #metadatos');		
					_div.innerHTML=_dat.texto;		
					
				}else{
					console.log('falló la actualizaicón de proyectos');
				}
			}
		});	
	}
cargarCapa();

function resaltarIMG(_this){
	if(_this.getAttribute('ocupado')=='si'){return;}
	_src=_this.getAttribute('src');
	_arr=_src.split('.');
	_ext=_arr[(_arr.length-1)];
	_src=_src.substring(0,(_src.length-4));
	_this.setAttribute('src',_src+'h.'+_ext);
}
function desaltarIMG(_this){
	if(_this.getAttribute('ocupado')=='si'){return;}
	_src=_this.getAttribute('src');
	_arr=_src.split('.');
	_ext=_arr[_arr.length-1];
	_src=_src.substring(0,(_src.length-5));
	_this.setAttribute('src',_src+'.'+_ext);
}	
function descargarSHP(_this,_ev){	
	_ev.stopPropagation();
	_this.setAttribute('ocupado','si');
	desaltarIMG(_this);
	
	var _srcorig=_this.getAttribute('src');
	
	_this.src='./img/cargando.gif';
	
	_if=document.createElement('iframe');
	_if.style.display='none';
	_if.src=_this.getAttribute('link');
	_this.appendChild(_if);
	_if.onload = function() { 
		alert('¡capa lista para descargar!'); 
		_this.src=_srcorig;
		_this.removeAttribute('ocupado');
	};
	
	//window.open(_this.getAttribute('link')); 
	
	
}


function navegarCapa(_ev,_this){
	_ev.stopPropagation();
	var _this=_this;
	//window.location.assign('www.trecc.com.ar');
	$('head').append('<link rel="stylesheet" type="text/css" href="./cap/capas.css?v=4">');
	
	var parametros = {};
	
	_nav=document.getElementById('cap_nav');
	if(_nav!=undefined){_nav.partenNode.removeChild(_nav);}	
			
	$.ajax({
		data:  parametros,
		url:   './cap/cap_nav.php',
		type:  'post',
		success:  function (response){
			document.body.innerHTML+=response;
			_nav=document.getElementById('cap_nav');
			if(_nav==undefined){alert('error inesperado #dfgfg');return;}
			_tit=_nav.querySelector('#titulo');
			_tit.innerHTML=_this.parentNode.querySelector('#tituloCapa').innerHTML;
			
			my_awesome_script = document.createElement('script');
			my_awesome_script.setAttribute('src','./cap/cap_nav_mapa.js');
			document.head.appendChild(my_awesome_script);
			//_iframe=_nav.querySelector('iframe');
			//_iframe.setAttribute('src',_this.getAttribute('isrc'));			
		}
	});
	
}


	//funciones del formulario de usuarios
	function formUsuario(){		
		$('head').append('<link rel="stylesheet" type="text/css" href="./usu/usuarios.css?v=2">');
		
		var parametros = {};
		$.ajax({
			data:  parametros,
			url:   './usu/usu_temp_acces.php',
			type:  'post',
			success:  function (response){
				document.body.innerHTML+=response;	
			}
		});
	
	}
	
	function cerrar(_this){
		_this.parentNode.parentNode.removeChild(_this.parentNode);
	}
	
	function ampliarUsu(_this){
		_this.parentNode.querySelector('#dataregistro').style.display='block';
		_this.parentNode.querySelector('#acceder').style.display='none';
		_this.style.display='none';
	}
	
	function verayuda(_this){
		_this.parentNode.querySelector('#ayuda').style.display='block';
	}
	
	function acceder(_this){		
		
		var parametros = {
			'dni': _this.parentNode.parentNode.querySelector('input[name="dni"]').value,
			'password': _this.parentNode.parentNode.querySelector('input[name="password"]').value
		};
		
		$.ajax({
			data:  parametros,
			url:   './usu/usu_acceso_ajax.php',
			type:  'post',
			success:  function (response){
				var _res = $.parseJSON(response);
				console.log(_res);
				if(_res.res=='exito'){	
					_UsuarioA= _res.data;
					cargarusuario();
					document.querySelector('#formacceso').parentNode.removeChild(document.querySelector('#formacceso'));
				}else{
					alert('error')
				}
			}
		});
	
	}

	function salir(){		
		
		var parametros = {
		};
		
		$.ajax({
			data:  parametros,
			url:   './usu/usu_salir_ajax.php',
			type:  'post',
			success:  function (response){
				var _res = $.parseJSON(response);
				console.log(_res);	
				_UsuarioA= Array();
				_UsuarioA.nombre= "Anónimo";
				_UsuarioA.apellido= "";
				cargarusuario();
			}
		});
	
	}
	
	
		
	function registrar(_this){		
		
		_stop='no';
		_form=_this.parentNode.parentNode;
		if(_form.querySelector('input[name="password"]').value !=_form.querySelector('input[name="password2"]').value){
			_form.querySelector('input[name="password2"]').backgroundColor='#fda';
			alert('no coinciden las contraseñas');
			_stop='si';
		}
		
		if(_form.querySelector('input[name="password"]').value.lenght < 4){
			_form.querySelector('input[name="password"]').backgroundColor='#fda';
			alert('la contraseña requiere al menos 4 caracteres');
			_stop='si';
		}
		
		if(_form.querySelector('input[name="nombre"]').value.lenght < 1){
			_form.querySelector('input[name="nombre"]').backgroundColor='#fda';
			alert('falta ingresar su nombre');
			_stop='si';
		}
		
		if(_form.querySelector('input[name="apellido"]').value.lenght < 1){
			_form.querySelector('input[name="apellido"]').backgroundColor='#fda';
			alert('falta ingresar su nombre');
			_stop='si';
		}
		
		if(_form.querySelector('input[name="mail"]').value.lenght < 6){
			_form.querySelector('input[name="mail"]').backgroundColor='#fda';
			alert('falta ingresar su mail');
			_stop='si';
		}
		
		if(_stop=='si'){
			return;
		}
		
		var parametros = {
			'dni': _form.querySelector('input[name="dni"]').value,
			'password': _form.querySelector('input[name="password"]').value,
			'password2': _form.querySelector('input[name="password2"]').value,
			'nombre': _form.querySelector('input[name="nombre"]').value,
			'apellido': _form.querySelector('input[name="apellido"]').value,
			'mail': _form.querySelector('input[name="mail"]').value
		};
		
		$.ajax({
			data:  parametros,
			url:   './usu/usu_registro_ajax.php',
			type:  'post',
			success:  function (response){
				var _res = $.parseJSON(response);
				console.log(_res);
				if(_res.res=='exito'){
					_UsuarioA= _res.data;
					acceder(_this);					
					document.querySelector('#formacceso').parentNode.removeChild(document.querySelector('#formacceso'));					
				}else{
					alert('error')
				}
				
			}
		});
	
	}	
</script>

<script>


//operación del formulario central par ala carga de SHP

function limpiarfomularioversion(){
	document.querySelector('#formcargaverest select#crs').options[1].selected;
	document.querySelector('#formcargaverest').setAttribute('idver','');
	document.querySelector('#formcargaverest #txningunarchivo').style.display='block';
	document.querySelector('#formcargaverest #archivoscargados').innerHTML='';
	document.querySelector('#formcargaverest #camposident').innerHTML='';
	document.querySelector('#formcargaverest #carga').style.display='none';
	document.querySelector('#formcargaverest #carga').style.display='none';
	document.querySelector('#formcargaverest #botonformversion').style.display='block';
}


function allowDrop(ev) {
    ev.preventDefault();
    
}

function drag(ev) {
    ev.dataTransfer.setData("text", ev.target.id);
}

function drop(ev,_gg) {
	
    ev.preventDefault();
    
    var data = ev.dataTransfer.getData("text");
	
    _dest=ev.target;
    if(_dest.getAttribute('id')!='espacioshp'){
    	return;
    }
    
    if(_dest.querySelector('.enshp') != null){
    	
    	return;
    }

    
    _obj=document.getElementById(data);
    _parent=_obj.parentNode.parentNode;
    
    if(_dest.parentNode.getAttribute('origen')=='aux'){
 		_clon=_dest.parentNode.cloneNode(true);
 		_parent.parentNode.appendChild(_clon);
 		
 		_dest.parentNode.setAttribute('origen','shp');
 	}
 	    
    _dest.appendChild(_obj);    
    
    if(_parent.getAttribute('origen')=='shp'){
 		_parent.parentNode.removeChild(_parent);
 	}
 	
 	actualizarCadenaCampos();
	    	 
}

var _Procesarcampos;
function actualizarCadenaCampos(){
	ValidarProcesarBoton();
	_Procesarcampos={};
	_filas=document.querySelectorAll('#formcargaverest #carga #camposident .enshp');
	for(_nf in _filas){
		if(typeof _filas[_nf] !='object'){continue;}
		_parent=_filas[_nf].parentNode.parentNode;
		if(_parent.getAttribute('origen')=='aux'){continue;}
		_nom=_filas[_nf].getAttribute('nom');
		if(_parent.querySelector('#entabla').getAttribute('nom')==''){
			if(_parent.querySelector('#crear').checked){
				_Procesarcampos[_nom]={};
				_Procesarcampos[_nom]['acc']='crear';
				
				if(_parent.querySelector('#rename').value!=''){
					_Procesarcampos[_nom]['nom']=_parent.querySelector('#rename').value;
				}else{
					_Procesarcampos[_nom]['nom']=_nom;
				}
			}
		}else{
			_Procesarcampos[_nom]={};
			_Procesarcampos[_nom]['acc']='asignar';
			_Procesarcampos[_nom]['nom']=_parent.querySelector('#entabla').getAttribute('nom');
		}
		
	}
	
	document.querySelector('#verproccampo').innerHTML=JSON.stringify(_Procesarcampos);
	
}

var _checkList={
	'prj':{'s':'no','mg':'sin prj definido'},
	'shp':{'s':'no','mg':'sin shapefile definido'},
	'dbf':{'s':'no','mg':'completamiento indefinido para algunas columnas d ela base'}
};


function procesarVersion(_this){
	_stop='no';
	for(_comp in _checkList){
		if(_checkList[_comp].s=='no'){
			alert(_checkList[_comp].mg);
			_stop='si';	
		}		
	}
	if(_stop=='si'){return;}
	
	guardarVer(_this,'si');
	
}

function procesarVersion2(_this,_avance){
	var _this =_this;
	var _parametros = {
		'idcapa': _IdCapa,
		'accion': 'procesar versión',
		'id': document.querySelector('#formcargaverest #carga #idnv').innerHTML,
		'avance':_avance,
		'idpro':_IdPro
	};
	
	$.ajax({
		url:   './cap/ed_version_procesa.php',
		type:  'post',
		data: _parametros,
		success:  function (response){
			var _res = $.parseJSON(response);
			console.log(_res);
			for(_nm in _res.mg){alert(_res.mg[_nm]);}
			if(_res.res=='exito'){
				if(_res.data.avance!='final'){
					procesarVersion2(_this,_res.data.avance);
					document.querySelector('#avanceproceso').style.display='block';
					document.querySelector('#avanceproceso').innerHTML=_res.data.avanceP+"%";
					document.querySelector('#avanceproceso').setAttribute('avance',_res.data.avanceP);
				}else{
					document.querySelector('#avanceproceso').style.display='none';
					impiarfomularioversion();
					
				}
			}
		}
	});
}



function ValidarProcesarBoton(){
	actualizarestadoCampos();
	_stop='no';
	_bot=document.querySelector('#ProcesarBoton');
	_bot.title=''
	_bot.removeAttribute('estado');
	for(_comp in _checkList){
		if(_checkList[_comp].s=='no'){
			_bot.setAttribute('estado','inviable');
			_bot.title+=_checkList[_comp].mg;
			_stop='si';	
		}		
	}
	if(_stop=='no'){
		_bot.setAttribute('estado','viable');
		_bot.title+='listo para procesar versión';
	}
}


function actualizarestadoCampos(){
	console.log('actualizarestadoCampos()');	
	//actualiza en el checklist el estado de los campos asignados
	_divs=document.querySelectorAll('#camposident > div[origen="tabla"]');
	
	_checkList.dbf.s='si';
	_checkList.dbf.mg='ok';
	for(_nd in _divs){
		if(typeof _divs[_nd]!='object'){continue;}
		/*
		if(_divs[_nd].querySelector('#espacioshp > .enshp') == null){
			_checkList.dbf.s='no';
			_checkList.dbf.mg='al menos un campo de la tabla carece de un campo asociado del shapefile.';
		}*/
	}	
}

function eliminarCandidatoVersion(_this){
	console.log('eliminarCandidatoVersion()');
	
	if(confirm("¿Confirma que quiere eliminar este candidato a versión? \n Si lo hace se eliminarán los archivos que haya subido y se guardará registro en la papelera de los datos cargados en el formulario.")){
		console.log('o');
		
		var _parametros = {
            'idpro':_IdPro,
			'idcapa':_IdCapa,
			'accion': 'borrar candidato',
			'id':_this.parentNode.querySelector('#idnv').innerHTML
		}
			
		$.ajax({
		url:   './cap/ed_version_borra.php',
		type:  'post',
		data: _parametros,
		success:  function (response){
			var _res = $.parseJSON(response);
				console.log(_res);				
				for(_nm in _res.mg){alert(_res.mg[_nm]);}
				if(_res.res=='exito'){
					document.querySelector('#formcargaverest').style.display='none';
				}
			}
		});
				
	}
}

function formversion(){
	console.log('formversion()');
	
	limpiarfomularioversion();
	document.querySelector('#formcargaverest').style.display="block";
	//intenta generar una nueva versión candidata para este usuario u esta capa
	var _parametros = {
		'idcapa': _IdCapa,
		'accion': 'crear nueva versión',
		'idpro': _IdPro
	};
	
	$.ajax({
		url:   './cap/ed_version_crea.php',
		type:  'post',
		data: _parametros,
		error:  function (response){alert('error al conectar el servidor');},
		success:  function (response){
			var _res = $.parseJSON(response);
				console.log(_res);				
				for(_nm in _res.mg){alert(_res.mg[_nm]);}
				
				if(_res.res=='exito'){
					
					document.querySelector('#formcargaverest #carga').style.display='block';
					
					//_this.style.display='none';
					
					
					if(_res.data.nid!=undefined){
						document.querySelector('#formcargaverest #carga #idnv').innerHTML=_res.data.nid;
						document.querySelector('#formcargaverest').setAttribute('idver',_res.data.nid);
					}else{
						document.querySelector('#formcargaverest #carga #idnv').innerHTML=_res.data.version.id;
						document.querySelector('#formcargaverest').setAttribute('idver',_res.data.version.id);
						document.querySelector('#formcargaverest #carga #nomver').innerHTML='nombre: '+_res.data.version.nombre;
					}
					
					
					for(_na in _res.data.archivos){
						document.querySelector('#formcargaverest #txningunarchivo').style.display='none';
						_fil=document.createElement('p');
						_fil.innerHTML=_res.data.archivos[_na].nom;
						_fil.setAttribute('fileExt',_res.data.archivos[_na].ext);				
						document.querySelector('#formcargaverest #carga #archivoscargados').appendChild(_fil);						
					}
					
					
					if(_res.data.prj.stat=='viable'){
						_checkList.prj.s='si';
						_checkList.prj.ms='ok';
						_sel=document.querySelector('#crs');
						for(_no in _sel.options){
							if(_sel.options[_no].value==_res.data.prj.def){
								_sel.options[_no].selected=true;
								
								_ppp=document.querySelectorAll('#archivoscargados [fileext="prj"], #archivoscargados [fileext="qpj"]');
								for(_np in _ppp){
									if(typeof _ppp[_np] == 'object'){
										_ppp[_np].setAttribute('estado','viable');
										_ppp[_np].title=_res.data.prj.mg;
									}
								}
							}
						}		
						
						
					}else if(_res.data.prj.stat=='viableobs'){
						_checkList.prj.s='si';
						_checkList.prj.ms='se adoptará el prj del formulario que difiere del explicitado en el archivo subido';
						_sel=document.querySelector('#crs');
						for(_no in _sel.options){
							if(_sel.options[_no].value==_res.data.prj.def){
								_sel.options[_no].selected=true;
								
								_ppp=document.querySelectorAll('#archivoscargados [fileext="prj"], #archivoscargados [fileext="qpj"]');
								for(_np in _ppp){
									if(typeof _ppp[_np] == 'object'){
										_ppp[_np].setAttribute('estado','viableobs');
										_ppp[_np].title=_res.data.prj.mg;
									}
								}
							}
						}
					}else{
						_checkList.prj.s='no';
						_checkList.prj.ms=_res.data.prj.mg;
						_ppp=document.querySelectorAll('#archivoscargados [fileext="prj"], #archivoscargados [fileext="qpj"]');
						for(_np in _ppp){
							if(typeof _ppp[_np] == 'object'){
								_ppp[_np].setAttribute('estado','inviable');
								_ppp[_np].title=_res.data.prj.mg;
							}
						}
						
						//alert("crs: ",_res.data.prj.mg);
					}
					
					
					if(_res.data.shp.stat=='viable'){
						_checkList.shp.s='si';
						_checkList.shp.ms='ok';
						_ppp=document.querySelectorAll('#archivoscargados [fileext="shp"], #archivoscargados [fileext="shx"], #archivoscargados [fileext="dbf"]');
						for(_np in _ppp){
							if(typeof _ppp[_np] == 'object'){
								_ppp[_np].setAttribute('estado','viable');
								_ppp[_np].title=_res.data.shp.mg;
							}
						}
					}else if(_res.data.prj.stat=='inviable'){
						_checkList.shp.s='no';
						_checkList.shp.ms=_res.data.shp.mg;
						_ppp=document.querySelectorAll('#archivoscargados [fileext="shp"], #archivoscargados [fileext="shx"], #archivoscargados [fileext="dbf"]');
						for(_np in _ppp){
							if(typeof _ppp[_np] == 'object'){
								_ppp[_np].setAttribute('estado','inviable');
								_ppp[_np].title=_res.data.shp.mg;
							}
						}
						alert(_res.data.shp.mg);
					}
					
					
					for(_col in _res.data.columnas){
						
						if(_col=='id'){continue;}
						if(_col=='geom'){continue;}
						if(_col=='id_sis_versiones'){continue;}
						if(_col=='zz_obsoleto'){continue;}
						
						_fil=document.createElement('div');
						_fil.setAttribute('origen','tabla');
						
						_nom=document.createElement('div');
						_nom.setAttribute('id','entabla');
						_nom.setAttribute('nom',_col);
						_nom.innerHTML=_col;						
						_fil.appendChild(_nom);
						
						_nom=document.createElement('div');
						_nom.setAttribute('id','espacioshp');	
						_nom.setAttribute('ondrop',"drop(event)");
						_nom.setAttribute('ondragover',"allowDrop(event)");
								
						_fil.appendChild(_nom);
						/*
						_nom=document.createElement('input');
						_nom.setAttribute('id','rename');	
						_fil.appendChild(_nom);
						*/
						/*
						_nom=document.createElement('input');
						_nom.setAttribute('type','checkbox');
						_nom.setAttribute('id','crear');	
						_fil.appendChild(_nom);
						*/
						
						document.querySelector('#formcargaverest #carga #camposident').appendChild(_fil);
						
					}
					
					_c=0;
					
					_Icampos={};
					if(_res.data.version.instrucciones!=''&&_res.data.version.instrucciones!=null){
						_Icampos = $.parseJSON(_res.data.version.instrucciones);
					}
					
					console.log(_Icampos);
					for(_col in _res.data.dbf.campos){
						
						_dat=_res.data.dbf.campos[_col];
						_norig=_dat.name;
						_nombre=_dat.name;
						
						if(_norig=='id'){continue;}
						if(_norig=='geo'){continue;}
						if(_norig=='id_sis_versiones'){continue;}
						if(_col=='zz_obsoleto'){continue;}
						
						
						_crear=false;
						if(_Icampos[_norig]!=null){
							_nombre=_Icampos[_norig].nom;
							if(_Icampos[_norig].acc=='crear'){_crear=true;}
						}
						console.log(_nombre+' '+_norig);
						_ref=document.querySelector('#formcargaverest #carga #camposident #entabla[nom="'+_nombre+'"]');
						
						
						if(_ref==null){
							
							_fil=document.createElement('div');
							_fil.setAttribute('id','entabla')
							_fil.setAttribute('origen','shp');
							
							
							_nom=document.createElement('div');
							_nom.setAttribute('id','entabla');
							_nom.setAttribute('nom','');
							_fil.appendChild(_nom);
							
							_nome=document.createElement('div');
							_nome.setAttribute('id','espacioshp');	
							_nome.setAttribute('ondrop',"drop(event)");
							_nome.setAttribute('ondragover',"allowDrop(event)");
							_fil.appendChild(_nome);
							
							_nom=document.createElement('div');
							_c++;
							_nom.setAttribute('id',_c);
							_nom.setAttribute('class','enshp');
							_nom.setAttribute('nom',_norig);
							_nom.setAttribute("draggable","true");
							_nom.setAttribute("ondragstart","drag(event)");							
							_nom.innerHTML=_norig;			
							_nome.appendChild(_nom);
							
							_nom=document.createElement('input');
							_nom.setAttribute('id','rename');
							_nom.setAttribute('onkeyup','actualizarCadenaCampos()');
							if(_norig!=_nombre){_nom.value=_nombre;}	
							_fil.appendChild(_nom);
							
							_nom=document.createElement('input');
							_nom.setAttribute('type','checkbox');
							_nom.checked=_crear;
							_nom.setAttribute('id','crear');	
							_nom.setAttribute('onchange','actualizarCadenaCampos()');
							_fil.appendChild(_nom);
	
							
							document.querySelector('#formcargaverest #carga #camposident').appendChild(_fil);
							
						}else{
							
							_nome=_ref.parentNode.querySelector('#espacioshp');
							
							_nom=document.createElement('div');
							_c++;
							_nom.setAttribute('id',_c);
							_nom.setAttribute('class','enshp');
							_nom.setAttribute('nom',_norig);
							_nom.setAttribute("draggable","true");
							_nom.setAttribute("ondragstart","drag(event)");							
							_nom.innerHTML=_norig;			
							_nome.appendChild(_nom);
											
							
						}
					}
					
					_fil=document.createElement('div');	
					_fil.setAttribute('origen','aux');
					
					_nom=document.createElement('div');
					_nom.setAttribute('id','entabla');
					_nom.setAttribute('nom','');					
					_fil.appendChild(_nom);
					
					_nom=document.createElement('div');
					_nom.setAttribute('id','espacioshp');	
					_nom.setAttribute('ondrop',"drop(event)");
					_nom.setAttribute('ondragover',"allowDrop(event)");
							
					_fil.appendChild(_nom);
					
					_nom=document.createElement('input');
					_nom.setAttribute('id','rename');	
					_nom.setAttribute('onkeyup','actualizarCadenaCampos()');
					_fil.appendChild(_nom);
					
					_nom=document.createElement('input');
					_nom.setAttribute('type','checkbox');
					_nom.setAttribute('onchange','actualizarCadenaCampos()');
					_nom.setAttribute('id','crear');	
					_fil.appendChild(_nom);
					
					
					document.querySelector('#formcargaverest #carga #camposident').appendChild(_fil);
				
				
					actualizarCadenaCampos();		
					
				}else{
					alert('error al consultar la base de datos');
				}
				
				
				
				/*
				_Tablas=_res.data.tablas;
				
				_cont=document.querySelector('#menutablas #lista');
				for(_nn in _Tablas['est']){					
					_aaa=document.createElement('a');
					_aaa.innerHTML=_Tablas['est'][_nn];
					_aaa.setAttribute('tabla',_Tablas['est'][_nn]);
					_aaa.setAttribute('onclick','cargarAtabla(this)');
					_cont.appendChild(_aaa);
				}*/
			document.querySelector('#formcargaverest #carga').style.display='block';
			document.querySelector('#formcargaverest #botonformversion').style.display='none';
		}
	});


	
}

function crearWMS(_this){
    var _parametros = {
		'capa_ver': _this.parentNode.parentNode.querySelector('#nom #codigo').innerHTML,
		'idpro': _IdPro
	};
	$.ajax({
		url:   './cap/ed_capa_crearwms.php',
		type:  'post',
		data: _parametros,
		success:  function (response){
			
			var _res = $.parseJSON(response);
			for(_nm in _res.mg){alert(_res.mg[_nm]);}
			console.log(_res);

		}
	});
	
}

function enviarCapa(_event,_this){
    _event.preventDefault();
    var _parametros = {
		'idcapa': _IdCapa,
		'idpro': _IdPro,
		'accion': 'guardar',
		'nombre':_this.querySelector('input[name="nombre"]').value,
		'codigo':_this.querySelector('input[name="codigo"]').value,
		'descripcion':_this.querySelector('textarea[name="descripcion"]').value,		
		'documentacion':tinymce.get('mce_redacc').getContent({format: 'html'})
	};
	$.ajax({
		url:   './cap/ed_capa_cambia.php',
		type:  'post',
		data: _parametros,
		success:  function (response){
			
			var _res = $.parseJSON(response);
			for(_nm in _res.mg){alert(_res.mg[_nm]);}
			console.log(_res);
			
			if(_res.res=='exito'){
                document.querySelector('#cap_form').style.display='none';
			}else{
                alert('se produjo un error al consultar la base de datos');
			}
			
			
		}
	});   
    

}

function guardarVer(_this,_procesar){

	//intenta generar una nueva versión candidata para este usuario u esta capa
	var _this=_this;
	var _procesar=_procesar;
	var _parametros = {
		'idcapa': _IdCapa,
		'accion': 'guardar version',
		'instrucciones':_this.parentNode.querySelector('#verproccampo').innerHTML,
		'fi_prj':_this.parentNode.querySelector('select#crs').value,
		'id':_this.parentNode.querySelector('#idnv').innerHTML,
		'idpro': _IdPro
	};
	$.ajax({
		url:   './cap/ed_version_cambia.php',
		type:  'post',
		data: _parametros,
		success:  function (response){
			
			var _res = $.parseJSON(response);
			for(_nm in _res.mg){alert(_res.mg[_nm]);}
			console.log(_res);
			
			if(_procesar=='si'){procesarVersion2(_this.parentNode,0);return;}
			formversion(_this.parentNode);
			
		}
	});
}



//funciones para la gestión de archivos uploads
var _contUp=0;
var _Cargas={};

function enviarSHP(_event,_this){	
	ValidarProcesarBoton();
	var files = _this.files;		
	for (i = 0; i < files.length; i++) {
		
    	_contUp++;
    	_Cargas[_contUp]='subiendo';
		var parametros = new FormData();
		parametros.append("upload",files[i]);
		parametros.append("idver",document.querySelector('#formcargaverest').getAttribute('idver'));
		parametros.append("crs",_this.parentNode.parentNode.querySelector('#crs').value);
		parametros.append("cont",_contUp);
		
		cargando(files[i].name,_contUp);
		
		//Llamamos a los puntos de la actividad
		$.ajax({
				data:  parametros,
				url:   './cap/proc_shp_upload.php',
				type:  'post',
				processData: false, 
				contentType: false,
				success:  function (response) {
					var _res = $.parseJSON(response);
					for(_nm in _res.mg){alert(_res.mg[_nm]);}
					if(_res.res=='exito'){
						archivoSubido(_res);
					}else{
						archivoFallido(_res)
					}
					
					_Cargas[_res.data.ncont]='terminado';
					
					_pendientes=0;
					for(_nn in _Cargas){
						if(_Cargas[_nn]=='subiendo'){_pendientes++;}
					}
					if(_pendientes==0){
						alert(document.querySelector('#botonformversion').innerHTML);
						formversion(document.querySelector('#botonformversion'));
						alert('ok');
					}
					
				}
		});
	}
}

/*
function intentarProcesarSHP(){
	_datos={};
	_datos["idcont"]=_idcont;
	_datos["crs"]=document.querySelector('#shp #crs').value;
	
	$.ajax({
		data: _datos,
		url:   './cap/proc_shp_ddbb.php',
		type:  'post',
		success:  function (response){
			
			cargaContrato();
			var _res = $.parseJSON(response);
			
			//console.log(_res);
			for(_nm in _res.mg){alert(_res.mg[_nm]);}
			if(_res.res=='error'){
				_this.parentNode.innerHTML='ERROR AL ACTUALIZAR LOS DATOS';
			}else{
				cargaContrato();
			}
		}
	})		
}
*/

function cargando(_nombre,_con){
	
	_ppp=document.createElement('p');
	_ppp.innerHTML=_nombre;
	_ppp.setAttribute('ncont',_con);
	_ppp.setAttribute('class','carga');
	
	document.querySelector('#shp #cargando').appendChild(_ppp);
	
}	
	
function archivoSubido(_res){
	
	document.querySelector('#shp #cargando p[ncont="'+_res.data.ncont+'"]').innerHTML+=' ...subido';
	document.querySelector('#shp #cargando p[ncont="'+_res.data.ncont+'"]').setAttribute('estado','subido');
	
}	

function archivoFallido(_res){
		
	document.querySelector('#shp #cargando p[ncont="'+_res.data.ncont+'"]').innerHTML+=' ...fallido';
	document.querySelector('#shp #cargando p[ncont="'+_res.data.ncont+'"]').setAttribute('estado','fallido');
	
}	
			
</script>

<script type="text/javascript" src="./js/tinymce43/tinymce.min.js"></script>
<script type="text/javascript">

</script>
</body>

