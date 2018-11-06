<?php 
/**
* index.php
*
* index para la gastión de UNMgeo
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
	<style type='text/css'>
		
		#velo{
			width:100vw;
			height:100vh;
			position:absolute;
			background-color:#fffecf;
			opacity:0;
			transition: opacity 0.2s ease-in-out;
		}
		
	</style>

</head>

<body>
	
	<script type="text/javascript" src="./js/jquery/jquery-1.12.0.min.js"></script>
	 
		
	<img id="fondo" src="./img/fondo.png">		
	<div id='titulo'>
		<img id="unmgeo" alt='UNMGEO' src='./img/unmgeo.png'>
		<h1 id='bajada'>
			Plataforma <br>Geomática de la<br>Universidad<br>Nacional<br>de<br>Moreno
		</h1>
	</div>

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
		<div id='redes'>
			<a href="https://twitter.com/UNMgeo" tittle='enterate de nuestras publicaciones por twitter'><img src='./img/bo_twitter.png' alt='twitter'></a>
		</div>
		</div>
		<a id='botonconfig' onclick='configurar()'>Configurar</a>
	</div>
		
	<div id='velo'></div>

	<div class='columna'  id='columna1'><div class='marco' id='marco1'>
		<div id='proyectos'>
			<h2>Proyectos</h2>
			<div class='lista' id='prolista'>				
			</div>
		</div>
	</div></div>	
	<div class='columna'  id='columna2'><div class='marco' id='marco2'>
		<div id='capas'>
			<h2>Capas Disponibles</h2>
			<div class='lista' id='capalista'>
				
			</div>			
		</div>
	</div></div>
	<div class='columna'  id='columna3'><div class='marco' id='marco3'>
		<div id='aplicaciones'>
			<h2>Aplicaciones</h2>
			<div class='submarco' id='submarco3'>
			<div class='lista' id='applista'>
			</div>
			</div>
		</div>
	</div></div>
	
	<?php
	include("./pro/pro_form.php");
	?>
	
	<form id='configsis'>
		<a id='cerr' onclick="ocultar(this)">cerrar</a>
		<div>
			<h2>Usuarios</h2>
			<div id='listausuarios'></div>
		</div>		
	</form>
	
<script type="text/javascript">

var _Usuarios={};
function configurar(){
	document.getElementById('listausuarios').innerHTML='';
	if(_UsuarioA.sis_adm!='1'){
		alert('sin permisos de configuración de UNMgeo');
		return;			
	}	
	
	document.getElementById('configsis').style.display='block';
	
	_parametros={};
	$.ajax({
		data:  _parametros,
		url:   './dbpgcon/con_config_data.php',
		type:  'post',
		error: function (response){alert('error al contactar el servidor');},
		success:  function (response){
			var _res = $.parseJSON(response);
			//console.log(_res);
			
			if(_res.res=='exito'){
				_Usuarios=_res.data.usuarios;
				//cargarLocalizaciones(_res.data);
				//actualizarMedicionesRelevTodos();
				_cont=document.getElementById('listausuarios');
				
				for(_nn in _res.data.usuariosOrden){
					
					_usuid=_res.data.usuariosOrden[_nn];
					_usuDat=_res.data.usuarios[_usuid];
					
					_fila=document.createElement('div');
					_fila.setAttribute('class','fila');
					_fila.setAttribute('admin',_usuDat.sis_admin);
					_fila.setAttribute('usuid',_usuid);
					_cont.appendChild(_fila);
					
					_cel=document.createElement('div');
					_cel.innerHTML=_usuDat.nombre+" "+_usuDat.apellido;
					_fila.appendChild(_cel);
					
					_cel=document.createElement('div');
					_cel.innerHTML=_usuDat.dni;
					_fila.appendChild(_cel);
					
					_cel=document.createElement('div');
					_cel.setAttribute('class','mail');
					_cel.innerHTML=_usuDat.mail;
					_fila.appendChild(_cel);

					_acc=document.createElement('div');
					_acc.setAttribute('class','acciones');
					_fila.appendChild(_acc);
					
					_aaa=document.createElement('a');
					_aaa.innerHTML='resetear';
					_aaa.setAttribute('onclick','resetPass(this.parentNode.parentNode)');
					_aaa.title='Regenerar el password de este usuario.'
					_acc.appendChild(_aaa);		
					
					_cel=document.createElement('div');
					_cel.setAttribute('class','mensaje');
					_fila.appendChild(_cel);
					
					if(_usuDat.zz_reset_time>0){
						_cel.innerHTML='código de reinicio pendiente solicitado por '+_Usuarios[_usuDat.zz_reset_autor].nombre+' '+_Usuarios[_usuDat.zz_reset_autor].apellido; 
					}
				}
				
			}else{
				alert('error durante la consulta en el servidor');
			}
		}
	});	
	
	
}

function resetPass(_fila){
	_usuid=_fila.getAttribute('usuid');
	console.log(_usuid);
	if(_Usuarios[_usuid]=='undefined'){
		alert('erorr en la consulta del suario solicitado. id: '+_usuid);
		return;
	}
	console.log('o');
	if(confirm('Confirmás que querés resetera el password del usuario: '+_Usuarios[_usuid].nombre+" "+_Usuarios[_usuid].apellido+" ?")){
		
		parametros={
			idusu:_usuid
		}
		$.ajax({
			data:  parametros,
			url:   './usu/usu_reset_passcod.php',
			type:  'post',
			error: function (response){alert('error al contactar el servidor');},
			success:  function (response){
				var _res = $.parseJSON(response);
				//console.log(_res);
				
				if(_res.res=='exito'){
					
					_msg=document.querySelector('#configsis #listausuarios .fila[usuid="'+_res.data.idusu+'"] .mensaje');
					_msg.innerHTML='Generado el código. El usuario deberá generar su nueva contraseña con este link: <br>';
					_msg.innerHTML+='http://170.210.177.36/unmgeo/USU_regeneracion.php?usuid='+_res.data.idusu+'&cod='+_res.data.cod;				
					
				}else{
					alert('error durante la consulta en el servidor');
				}
			}
		});
	}
	
}

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

	if(_UsuarioA.sis_adm==1){
		document.querySelector('#proyectos h2').innerHTML +="<a onclick='crearproyecto()'>+ proyecto</a>";
		document.querySelector('#logbar a#botonconfig').style.display='inline-block';
	}

}

</script>


<script type="text/javascript">

	_yyy=document.getElementById('marco3');
	//_yyy.onscroll=alert('hh');
	//_yyy.addEventListener("scroll", alert('hh'));

	
	$('#marco3').scroll(function(){
		if(($('#marco3').height()+$('#marco3').scrollTop())==$('#marco3')[0].scrollHeight){
			document.getElementById('applista').style.overflowY='scroll';
			
			//alert('0');
		}else{
			document.getElementById('applista').style.overflowY='hidden';
		}
   //console.log($('#marco3').scrollTop()+' '+($('#columna3').height()+$('#applista').height()));
   //console.log(($('#marco3').scrollTop()));
	   if($('#marco1').scrollTop()>150||$('#marco2').scrollTop()>100||$('#marco3').scrollTop()>250){
	   		document.querySelector('#velo').style.opacity=0.9;
	   		document.querySelector('#logbar').style.zIndex=0;
	   }else{
	   		document.querySelector('#velo').style.opacity=0;
	   		document.querySelector('#logbar').style.zIndex=5;
	   }
	   
	});

	$('#marco2').scroll(function(){
		if(($('#marco2').height()+$('#marco2').scrollTop())==$('#marco2')[0].scrollHeight){
			document.getElementById('applista').style.overflowY='scroll';
			

		}else{
			document.getElementById('applista').style.overflowY='hidden';
		}

	   if($('#marco1').scrollTop()>150||$('#marco2').scrollTop()>100||$('#marco3').scrollTop()>250){
	   		document.querySelector('#velo').style.opacity=0.9;
	   		document.querySelector('#logbar').style.zIndex=0;
	   }else{
	   		document.querySelector('#velo').style.opacity=0;
	   		document.querySelector('#logbar').style.zIndex=5;
	   }
	   
	});

	$('#marco1').scroll(function(){
		if(($('#marco1').height()+$('#marco1').scrollTop())==$('#marco1')[0].scrollHeight){
			document.getElementById('applista').style.overflowY='scroll';
			
		}else{
			document.getElementById('applista').style.overflowY='hidden';
		}

	   if($('#marco1').scrollTop()>150||$('#marco2').scrollTop()>100||$('#marco3').scrollTop()>250){
	   		document.querySelector('#velo').style.opacity=0.9;
	   }else{
	   		document.querySelector('#velo').style.opacity=0;
	   }
	   
	});
/*
	function scrolle(){
		alert('gg');
	}
	function ggg(){
		//_yyy=document.getElementById('marco3');
		console.log('ii');
	}*/
</script>

	
<script type="text/javascript">
var _Proyectos={};
//funciones de carga inicial
function cargarProyectos(){		
	var parametros = {
		'funcion':'cargarProyectos'
	};
	
	$.ajax({
		data:  parametros,
		url:   './dbpgcon/con_proy_list.php',
		type:  'post',
		success:  function (response){
			var _res = $.parseJSON(response);
			//console.log(_res);
			
			if(_res.res=='exito'){
				//cargarLocalizaciones(_res.data);
				//actualizarMedicionesRelevTodos();
				_cont=document.getElementById('prolista');
				_cont.innerHTML='';
				_Proyectos=_res.data.proyectos;
				for(_nn in _res.data.proyectosOrden){
					
					_pid=_res.data.proyectosOrden[_nn];
					_dat=_res.data.proyectos[_pid];
					
					_ver='no';
					
					
					if(_dat.visible!='1'){						
						_ver='si';			
					}
					
					
					if(_UsuarioA.sis_adm==1){
						_ver='si';								
					}
							
								
					for(_nr in _dat.responsables){
						if(_dat.responsables[_nr].id_p_usu_usuarios==_UsuarioA.id){
							_ver='si';
						}
					}
					
					if(_ver=='si'){
						anadirElementoProyecto(_dat);
						continue;
					}					
					
				}
				cargarCapas();
			}else{
				console.log('falló la actualizaicón de proyectos');
			}
		}
	});	
	
}
cargarProyectos();

function anadirElementoProyecto(_dat){
	console.log(_dat);
	_cont=document.getElementById('prolista');
	_div=document.createElement('div');
	_rim=Math.round(Math.random()*5)+1;
	//console.log(_rim);
	_div.setAttribute('style',"background-image: url('./img/p"+_rim+".png');");
	_div.setAttribute('class',"proitem");
	_div.setAttribute('proid',_dat.id);
	_div.setAttribute('onclick','navegarProyecto(this,event)');
	
	_h3=document.createElement('h3');
	_h3.innerHTML=_dat.nombre;
	
	
	
	if(_UsuarioA.sis_adm==1){
		//_h3.innerHTML+="<a onclick='activarFormularioProyecto(this,event)'>editar</a>";						
	}
	
	_div.appendChild(_h3);

	
	_pp=document.createElement('p');
	_pp.innerHTML=_dat.descripcion;
	_div.appendChild(_pp);
	_pp=document.createElement('p');
	_pp.innerHTML="Responsables: S/D";
	_div.appendChild(_pp);	
	_cont.appendChild(_div);
	
	if(Object.keys(_dat.responsables).length>0){
		_pp.innerHTML="";
	}

	for(_idr in _dat.responsables){
		_datRes=_dat.responsables[_idr];
		
		_clon=document.createElement('div');
		_clon.setAttribute('class','res');
		_clon.setAttribute('idres','nn');
		
		_sp=document.createElement('span');
		_sp.setAttribute('id','cargo');
		_clon.appendChild(_sp);

		_sp=document.createElement('span');
		_sp.setAttribute('id','nombre');
		_clon.appendChild(_sp);
		
		_clon.querySelector('#cargo').innerHTML=_datRes.cargo+':';
		_clon.querySelector('#nombre').innerHTML=_datRes.nombre+' '+_datRes.apellido;
		_pp.appendChild(_clon);
	}		
}


function cargarCapas(){		
	var parametros = {
		'funcion':'cargarCapas'
	};
	
	$.ajax({
		data:  parametros,
		url:   './dbpgcon/con_capas.php',
		type:  'post',
		success:  function (response){
			var _res = $.parseJSON(response);
			//console.log(_res);
			
			if(_res.res=='exito'){
				//cargarLocalizaciones(_res.data);
				//actualizarMedicionesRelevTodos();
				_cont=document.getElementById('capalista');
				
				for(_nn in _res.data){				
				
					_div=document.createElement('div');					
					_div.setAttribute('class',"capaitem");
					_div.setAttribute('capaid',_res.data[_nn].id);
					
					
					_div.setAttribute('onclick','editarCapa(event,this)');
					
					_h3=document.createElement('h3');
					_h3.innerHTML=_res.data[_nn].nombre;					
					_div.appendChild(_h3);
					
					_pp=document.createElement('p');
					_pp.innerHTML=_res.data[_nn].descripcion;
					_div.appendChild(_pp);
					
					_pp=document.createElement('p');
					_pp.setAttribute('idpro_ref','...');
					_pp.innerHTML="Proyecto: "+_Proyectos[_res.data[_nn].id_b_unmgeo_pro_proyectos].nombre;
					_div.appendChild(_pp);
					
					_link=Array();
					_link.host="http://170.210.177.36:8080/geoserver/UNMgeo/";
						
					
					_ww=Math.round(($(window).width()*0.8)-40);
					_hh=Math.round(($(window).height()*0.8)-200);
					
					_link.standarWMS="wms?service=WMS&version=1.1.0&request=GetMap&width="+_ww+"&height="+_hh+"&srs=EPSG:22175&format=application/openlayers&styles=";
					//alert(_link.standarWMS);
					_link.extension="&bbox=5600000,6159000,5619000,6182000";
					_link.extensio2="&bbox=5580000,6150000,5640000,6190000";
					_link.capa="&layers=UNMgeo:"+encodeURI(_res.data[_nn].nombre);
					 
					_pad = "000"
					_strid = _pad.substring(0, _pad.length - _res.data[_nn].id.length) + _res.data[_nn].id;
					_strver = _pad.substring(0, _pad.length - _res.data[_nn].ultimaversion.length) + _res.data[_nn].ultimaversion;
					_link.capa="&layers=UNMgeo:"+_strid+"_v"+_strver;
					
					/*
					_aa=document.createElement('a');
					_aa.setAttribute('isrc',_link.host+_link.standarWMS+_link.capa+_link.extension);
					_aa.setAttribute('target','_blank');					
					_aa.setAttribute('onclick','navegarCapa(event,this)');
					_aa.innerHTML="ver";
					_div.appendChild(_aa);
					
					_div.innerHTML+=' - ';
					
					_link.standarSHP="ows?service=WFS&version=1.0.0&request=GetFeature&maxFeatures=1000000&outputFormat=SHAPE-ZIP";
					_link.capaSHP="&typeName=UNMgeo:"+_strid+"_v"+_strver;
					
					
					
					_aa=document.createElement('a');
					_aa.setAttribute('onclick','descargarSHP(this,event)');
					
					_aa.setAttribute('link',_link.host+_link.standarSHP+_link.capaSHP+_link.extension);
					_aa.setAttribute('link',_link.host+_link.standarSHP+_link.capaSHP);//retiramos el recorte para la descarga
					
					_aa.innerHTML="shp";
					_div.appendChild(_aa);
					
					*/
						
					_cont.appendChild(_div);
					
					
				}
			}else{
				console.log('falló la actualizaicón de proyectos');
			}
		}
	});	
}

function descargarSHP(_this,_ev){	
	_ev.stopPropagation();
	_if=document.createElement('iframe');
	_this.appendChild(_if);
	
	_if.style.display='none';
	_if.onload = function() { alert('myframe is loaded'); }; 
	
	_im=document.createElement('img');
	//_this.appendChild(_im);
	_im.src='./img/cargando.gif';
	
	_if.src=_this.getAttribute('link');
}




function cargarAplicaciones(){		
	var parametros = {
		'funcion':'cargarAplicaciones'
	};
	
	$.ajax({
		data:  parametros,
		url:   './dbpgcon/con_app.php',
		type:  'post',
		success:  function (response){
			var _res = $.parseJSON(response);
			console.log(_res);
			
			if(_res.res=='exito'){
				//cargarLocalizaciones(_res.data);
				//actualizarMedicionesRelevTodos();
				_cont=document.getElementById('applista');
				
				for(_nn in _res.data){				
				
					_div=document.createElement('a');
					_div.setAttribute('class',"appitem");
					
					
					_h3=document.createElement('h3');
					_h3.innerHTML=_res.data[_nn].nombre;
					_div.appendChild(_h3);
					
					if(_res.data[_nn].url!=null){
						_div.setAttribute("href",_res.data[_nn].url);
					}else{
						_pp=document.createElement('p');
						_pp.setAttribute('class','noweb');
						_pp.innerHTML="sin web disponible por el momento";
						_div.appendChild(_pp);		
					}
					
					_pp=document.createElement('p');
					_pp.innerHTML=_res.data[_nn].descripcion;
					_div.appendChild(_pp);
					
					_pp=document.createElement('p');
					if(_res.data.responsables_tx==''){_res.data[_nn].responsables_tx=' S/D';}
					_pp.innerHTML="Responsables: "+_res.data[_nn].responsables_tx ;
					_div.appendChild(_pp);
					
					_pp=document.createElement('p');
					_pp.setAttribute('class',"estado");
					_pp.innerHTML="Estado: "+_res.data[_nn].e_nombre;
					_pp.title=_res.data[_nn].e_descripcion ;
					_div.appendChild(_pp);
						
					
					
					
					_cont.appendChild(_div);
					
				}
			}else{
				console.log('falló la actualizaicón de estados');
			}
			
			
		}
	});	
}
cargarAplicaciones();


</script>

<script>
//fincuines de capas
function navegarCapa(_ev,_this){
	_ev.stopPropagation();
	var _this=_this;
	//window.location.assign('www.trecc.com.ar');
	$('head').append('<link rel="stylesheet" type="text/css" href="./cap/capas.css?v=2">');
	
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
			_tit.innerHTML=_this.parentNode.querySelector('h3').innerHTML;
			my_awesome_script = document.createElement('script');
			my_awesome_script.setAttribute('src','./cap/cap_nav_mapa.js');
			document.head.appendChild(my_awesome_script);
			
			//_iframe=_nav.querySelector('iframe');
			//_iframe.setAttribute('src',_this.getAttribute('isrc'));			
		}
	});
	
}




function editarCapa(_event,_this){
	window.location.assign('./CAP_muestra.php?id='+_this.getAttribute('capaid'));	
}
</script>

<script>

	//funciones del formulario de usuarios
	function formUsuario(){		
		$('head').append('<link id="usucssform" rel="stylesheet" type="text/css" href="./usu/usuarios.css?v=2">');
		
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
	
	function ocultar(_this){
		_this.parentNode.style.display='none';
	}
	
	function cerrar(_this){
		_this.parentNode.parentNode.removeChild(_this.parentNode);
	}
	
	function cerrarAcc(_this){
		$("#usucssform").remove();
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
					window.location.reload();
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
				window.location.reload();
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

function crearproyecto(){		
	document.querySelector('#pro_form').style.display='block';	
}

function activarFormularioProyecto(_this,_event){
	_event.stopPropagation();
	_event.preventDefault();
	var _idprocons=_this.parentNode.parentNode.getAttribute('proid');		
		
	$('head').append('<link rel="stylesheet" type="text/css" href="./pro/proyectos.css?v=2">');
	
	var parametros = {}
	$.ajax({
		data:  parametros,
		url:   './pro/pro_form.php',
		type:  'post',
		success:  function (response){
			document.body.innerHTML+=response;		
			consultarProyecto(_idprocons);
		}
	});
}	



function cargarFormularioProyecto(_proy){
	_form=document.querySelector('#pro_form');
	_form.querySelector('input[name="id"]').value=_proy.id;
	_form.querySelector('input[name="accion"]').value='cambiar';	
	_form.querySelector('input[name="nombre"]').value=_proy.nombre;
	_form.querySelector('textarea[name="descripcion"]').value=_proy.descripcion;
	if(_proy.visible=='si'){
		_form.querySelector('input[for="visible"]').checked=true;
		_form.querySelector('input[name="visible"]').value='1';
	}else{
		_form.querySelector('input[for="visible"]').checked=false;
		_form.querySelector('input[name="visible"]').value='0';
	}
}

				
function navegarProyecto(_this,_event){
		_event.stopPropagation();
		
		window.location.assign('./PRO_muestra.php?id='+_this.getAttribute('proid'));
	
	}	
		
		
function checkTogle(_this){
	_name=_this.getAttribute('for');
	if(_this.checked){
		_this.parentNode.querySelector('input[name="'+_name+'"]').value=_this.getAttribute('von');
	}else{
		_this.parentNode.querySelector('input[name="'+_name+'"]').value=_this.getAttribute('voff');
	}
	enviarFormPro(_this.parentNode.querySelector('input[name="'+_name+'"]'));
}

function enviarFormPro(_this){
	var parametros = {
		"id":_this.parentNode.querySelector("input[name='id']").value,
		"campo":_this.getAttribute('name'),
		"valor":_this.value
	};
	$.ajax({
		data:  parametros,
		url:   './dbpgcon/ed_proy_valor.php',
		type:  'post',
		success:  function (response){
			var _res = $.parseJSON(response);
			console.log(_res);
			if(_res.res=='exito'){
							
			}else{
				alert('error')
			}
		}
	});	

}
</script>


	
</body>

