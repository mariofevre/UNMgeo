<?php 
/**
* pro_form.php
*
* formulario para la creació y edicon de proyectos en UNMgeo
*  
* 
* @package    	unmGEO
* @subpackage 	difusion
* @author     	Universidad Nacional de Moreno
* @author     	<mario@trecc.com.ar>
* @author    	http://www.unm.edu.ar/
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
<script>
	$('head').append('<link rel="stylesheet" type="text/css" href="./pro/pro_form.css">');
</script>	

<form action='' id='pro_form' onsubmit="enviarFormProy(event);">
	<a id='cerr' onclick='cerrar(this)'>cerrar</a>
	<a id='info' onclick='verayuda(this)'>ver ayuda</a>
	<input type='hidden' name='accion' value=''>
	<input type='hidden' name='id' value=''>
	<h3>Nombre del proyecto</h3>
	<input id='nombre' type='text' name='nombre' value=''>
	<h3>Este proyecto debe estar visible para el público en general</h3>
	<h3>Descripción del proyecto</h3>		
	<textarea id='descripcion' name='descripcion' value=''></textarea>
	<h3>Proyecto publicado:
	<input type='hidden' name='visible' value=''>
	<input type='checkbox' for='visible' value='' onclick='toogle(this);'>
	</h3>
	<br><input type='submit' value='crear'>
	<div id='ayuda'>
		<a id='cerr' onclick='cerrar(this);'>cerrar</a>
		<p>Un proyecto es una unidad de producción de conocimeinto.</p>
		<p>Idealmente reune más de un actor intervieniente.</p>
		<p>El proyecto es la instancia por la que se documenta toda generación de datos en UNMgeo.</p>
		<p>Así, crear o incorporarse a un proyecto es el primer paso obligado para ser parte activa de la plataforma UNMgeo.</p>
		<p>Los proyectos dan lugar a datos y a aplicaciones.</p>
		<p>Cada proyecto tiene responsables, participantes, reportte de avances, objetivos, etc.</p>
	</div>
	
</form>
<script type='text/javascript'>
	function toogle(_this){
		_for=_this.getAttribute('for');
		if(_this.checked==true){_val='1';}else{_val='0';}
		document.querySelector('input[name="'+_for+'"]').value=_val;
	}

	function enviarFormProy(_event){
		_event.preventDefault();
		if(document.querySelector('#pro_form input[name="accion"]').value=='crear'){
			enviarFormCrearProy(_event);
		}else if(document.querySelector('#pro_form input[name="accion"]').value=='cambiar'){
			enviarFormCambiarProy(_event);
		}else{
			alert('error en la definición de la acción del formulario');
		}
	}
	
	function enviarFormCambiarProy(_event){
		_param={
			"idpro":document.querySelector('#pro_form input[name="id"]').value,
			"nombre":document.querySelector('#pro_form input#nombre').value,
			"descripcion":document.querySelector('#pro_form textarea#descripcion').value,
			"visible":document.querySelector('#pro_form input[name="visible"]').value
		};
		
		$.ajax({
			data:  _param,
			url:   './pro/ed_proy_cambiar.php',
			type:  'post',
			success:  function (response){
				var _res = $.parseJSON(response);
				//console.log(_res);
				
				if(_res.res=='exito'){
					//cargarLocalizaciones(_res.data);
					//actualizarMedicionesRelevTodos();
					document.querySelector('#pro_form').style.display='none';
					consultarProyecto(_IdPro);
								
				}else{
					console.log('falló la actualizaicón de proyectos');
				}
			}
		});	
	}
	
		
	function enviarFormCrearProy(_event){
		_nom=document.querySelector('#pro_form input#nombre').value;
		_des=document.querySelector('#pro_form textarea#descripcion').value;
		_param={
			"nombre":_nom,
			"descripcion":_des
		};
		
		$.ajax({
		data:  _param,
		url:   './pro/ed_proy_crear.php',
		type:  'post',
		success:  function (response){
			var _res = $.parseJSON(response);
			//console.log(_res);
			
			if(_res.res=='exito'){
				//cargarLocalizaciones(_res.data);
				//actualizarMedicionesRelevTodos();
				
					var parametros = {
						'funcion':'cargarProyectos',
						'id':_res.data.nid
					};
					
					$.ajax({
						data:  parametros,
						url:   './dbpgcon/con_proy_list.php',
						type:  'post',
						success:  function (response){
							var _res = $.parseJSON(response);
							if(_res.res=='exito'){
								_dat=_res.data.proyecto;					
								anadirElementoProyecto(_dat);
								
								_pe=$('#proyectos').offset().top;
								_sc=document.querySelector('#proyectos').scrollTop;
								console.log(_pe+" "+_sc+" "+$('#proyectos [proid="'+_dat.id+'"]').offset().top);
								$('#marco1').animate({
								        scrollTop: ($('#proyectos [proid="'+_dat.id+'"]').offset().top+_sc-_pe)
								 }, 2000);
				    
				    
							}
						}
					});
							
			}else{
				console.log('falló la actualizaicón de proyectos');
			}
		}
	});	
	

	}
	
</script>
