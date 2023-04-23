<?php 
/**
* app0015
*
* registra ponencias, comunicaciones y demás expresiones académicas.
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

function terminar($Log){
	$res=json_encode($Log);
	if($res==''){$res=print_r($Log,true);
	}else{echo $res;}
	exit;
}

$Acceso='si';
if(!isset($_SESSION["unmgeo"])){
	$Acceso='no';
}else{
	if($_SESSION["unmgeo"]["usuario"]["id"]<1){
		$Acceso='no';
	}
}
//TODO verificar si tiene permisos en el Proyecto 26 (o del proyecto vinculado a la app 11) para permitir la edición de la app 0011.


if(!isset($_GET['idmarco'])){$_GET['idmarco']='1';}
if(!isset($_GET['modo'])){$_GET['modo']='';}         // opciones modo: orig / controlautorias / resultadoautorias / porautor
if(!isset($_GET['modover'])){$_GET['modover']='';}


?><!DOCTYPE html>
<head>
	<title>UNM - GEO - portal</title>
	<META http-equiv="Content-Type" content="text/html; charset=windows-1252">
	<meta http-equiv="Expires" content="0">
	<meta http-equiv="Last-Modified" content="0">
	<meta http-equiv="Cache-Control" content="no-cache, mustrevalidate">
	<meta http-equiv="Pragma" content="no-cache">
		
	<link rel="stylesheet" type="text/css" href="../../css/unmgeo.css">
	<link rel="stylesheet" type="text/css" href="./css/lista.css">

	<style type="text/css">
	.presentacion[filtrosmod="nover"]{
		display:none;	
	}
	.presentacion[filtrosmodev="nover"]{
		display:none;	
	}
	h1{
		margin-bottom:1px;
	}
	
	#stats p{
		margin: 0 1px;
	}

	
	#menusuperior {
		position: fixed;
		background-color: #fff;
		top: 0px;
		z-index: 5;
	}

	#menusuperior > div#cargando{
		display:none;
		
	}
	#menusuperior > div#cargando[estado='activo']{
		display:inline-block;
	}
	
	td{
		position: relative;
	}
	td>a{
		border:none;
		background-color:transparent;
	}
	td>a>img{
		display:block;
	}	
	td .tagusu{
		display:block;
		position:absolute;
		bottom:0px;
		right:0px;
		font-size:50%;
		coslor:#999;
	}		
	

	#formPresent > div.casicompleto{
		width: calc(100% - 5vh - 24px);
	}	

	#formPresent > div.mini{
		width: calc(5vh - 4px);
	}	
	#formAuter div#aviso{
		width: calc(20vh);
		color:red;
		display:inline-block;
	}	
	
	#formEval div#aviso{	width: calc(20vh);
		color:red;
		display:inline-block;
	}
	div#botonera{
		width:100%;
	}
	
	#formEval > div > div.formadjuntardoc > label {
		width:auto;}

	.autoriacontrol{
		font-size:2vw;
		background-color:#ffd;
	}
	
	[modo='controlautorias'] th{
			display:none;			
	}
	
	.archivoperdido{
		margin:5px;
	}
	td > a, td > img{
		vertical-align:middle;
		display:inline-block;
	}
	</style>
</head>



<body>
	
	<script type="text/javascript" src="./js/jquery/jquery-3.5.1.js"></script>
	
	<div id='menusuperior'>
		<div>
		<h1>Lista de envios</h1>
		<br>			
		<a onclick='crearPresentacion()'>+ presentación</a>


		
		
		</div>
		

		
		
		
		<form id='formfiltro' action='' method='post' onsubmit='event.preventDefault();'>
			
			
			<div id='conteo'>
				<div>
					<div class='tit'>ver</div>
					<span id='cantvisible'>0</span><br>
					<span id='porcvisible'>0</span>
				</div><!--
			---><div>
					<div class='tit'>ocu</div>
					<span id='cantfiltrado'>0</span><br>
					<span id='porcfiltrado'>0</span>
				</div><!--
			---><div>
					<div class='tit'>tot</div>
					<span id='canttotal'>0</span><br>
					<span id='porctotal'>0</span>
				</div>
			</div>
			
			
			
			<div class="f_smarc">
				<div class="grupo">						
				<label>
					<input type="radio" name="f_smarc" pref='COMfabiertas' value="todas" checked='checked'><span onclick='toogle(this);filtrarFilas();'>todo</span>
				</label>
				</div>							
				
				<div class="grupo">
					<label class="">
						<input type="radio" pref='COMfabiertas' name="f_smarc" value='Eje 1'><span class='f_eje' onclick='toogle(this);filtrarFilas();'>Eje 1</span>
					</label>
					<label class="">
						<input type="radio" pref='COMfabiertas' name="f_smarc" value='Eje 2'><span class='f_eje' onclick='toogle(this);filtrarFilas();'>Eje 2</span>
					</label>
					<label class="">
						<input type="radio" pref='COMfabiertas' name="f_smarc" value='Eje 3'><span class='f_eje' onclick='toogle(this);filtrarFilas();'>Eje 3</span>
					</label>
					<label class="">
						<input type="radio" pref='COMfabiertas' name="f_smarc" value='Eje 4'><span class='f_eje' onclick='toogle(this);filtrarFilas();'>Eje 4</span>
					</label>
					<br>
					<label class="">
						<input type="radio" pref='COMfabiertas' name="f_smarc" value='Eje 5'><span class='f_eje' onclick='toogle(this);filtrarFilas();'>Eje 5</span>
					</label>
					<label class="">
						<input type="radio" pref='COMfabiertas' name="f_smarc" value='Eje 6'><span class='f_eje' onclick='toogle(this);filtrarFilas();'>Eje 6</span>
					</label>
					<label class="">
						<input type="radio" pref='COMfabiertas' name="f_smarc" value='Eje 7'><span class='f_eje' onclick='toogle(this);filtrarFilas();'>Eje 7</span>
					</label>
					<label class="">
						<input type="radio" pref='COMfabiertas' name="f_smarc" value='Eje 8'><span class='f_eje' onclick='toogle(this);filtrarFilas();'>Eje 8</span>
					</label>
				</div>	
			</div>	
			
			
			
			<div class="f_modalidad">
				
				<div class="grupo">
					<label class="corto">
						<input type="radio" pref='COMfsentido' name="f_modalidad" value='todas' checked='checked'><span class="f_modalidad" onclick='toogle(this);filtrarFilas();'>todo</span>
					</label>
				</div>
				
				
				<div class="grupo">
					<label class="largo">
						<input type="radio" pref='COMfsentido' name="f_modalidad" value='poster'><span  class="f_modalidad" onclick='toogle(this);filtrarFilas();'>poster</span>
						</label>    
					<br>
					
					<label class="largo">
						<input type="radio" pref='COMfsentido' name="f_modalidad" value='presentacion'><span class="f_modalidad" onclick='toogle(this);filtrarFilas();'>ponencia</span>
					</label>
					<br>
					
					<label class="largo">
						<input type="radio" pref='COMfsentido' name="f_modalidad" value='no definida'><span class="f_modalidad" onclick='toogle(this);filtrarFilas();'>no define</span>
					</label>
					<br>
					
					<label class="largo">
						<input type="radio" pref='COMfsentido' name="f_modalidad" value=''><span class="f_modalidad" onclick='toogle(this);filtrarFilas();'>- vacio -</span>
					</label>
					
				</div>
				
				
			</div>	
			
			
			
			<div class="f_evaluado">
				
				<div class="grupo">
					<label class="corto">
						<input type="radio" pref='COMfsentido' name="f_evaluado" value='todas' checked='checked'><span class="f_evaluado" onclick='toogle(this);filtrarFilas();'>todo</span>
					</label>
				</div>
				
				
				<div class="grupo">
					<label class="largo">
						<input type="radio" pref='COMfsentido' name="f_evaluado" value='e_se'><span  class="f_evaluado" onclick='toogle(this);filtrarFilas();'>sin eval.</span>
						</label>    
					<br>
					
					<label class="largo">
						<input type="radio" pref='COMfsentido' name="f_evaluado" value='e_see'><span class="f_evaluado" onclick='toogle(this);filtrarFilas();'>sin e. ext.</span>
					</label>
					<br>
					
					<label class="largo">
						<input type="radio" pref='COMfsentido' name="f_evaluado" value='e_sep'><span class="f_evaluado" onclick='toogle(this);filtrarFilas();'>sin e. principl</span>
					</label>
					<br>
					
					<label class="largo">
						<input type="radio" pref='COMfsentido' name="f_evaluado" value='e_ec'><span class="f_evaluado" onclick='toogle(this);filtrarFilas();'>eval. completa</span>
					</label>
					
					<br>
					<label class="largo">
						<input type="radio" pref='COMfsentido' name="f_evaluado" value='e_ef'><span class="f_evaluado" onclick='toogle(this);filtrarFilas();'>eval. finalizada</span>
					</label>
				</div>
				
				
			</div>	
			

			<div class="f_resultado">
				
				<div class="grupo">
					<label class="corto">
						<input type="radio" pref='COMfsentido' name="f_resultado" value='todas' checked='checked'><span class="f_resultado" onclick='toogle(this);filtrarFilas();'>todo</span>
					</label>
				</div>
				
				
				<div class="grupo">
					<label class="largo">
						<input type="radio" pref='COMfsentido' name="f_resultado" value='Aceptado'><span  class="f_resultado" resultado="Aceptado" onclick='toogle(this);filtrarFilas();'>Aceptado</span>
						</label>    
					<br>
					
					<label class="largo">
						<input type="radio" pref='COMfsentido' name="f_resultado" value='devuelto menor'><span class="f_resultado" resultado="devuelto menor" onclick='toogle(this);filtrarFilas();'>Dev. menor</span>
					</label>
					<br>
					
					<label class="largo">
						<input type="radio" pref='COMfsentido' name="f_resultado" value='devuelto mayor'><span class="f_resultado" resultado="devuelto mayor" onclick='toogle(this);filtrarFilas();'>Dev. Mayor</span>
					</label>
					<br>
					
					<label class="largo">
						<input type="radio" pref='COMfsentido' name="f_resultado" value='rechazado'><span class="f_resultado" resultado="rechazado" onclick='toogle(this);filtrarFilas();'>Rechazado</span>
					</label>
					
				</div>
				
				
			</div>	
			
			
	
			<div class="f_revision">
				
				<div class="grupo">
					<label class="corto">
						<input type="radio" pref='COMfsentido' name="f_revision" value='todas' checked='checked'><span class="f_revision" onclick='toogle(this);filtrarFilas();'>todo</span>
					</label>
				</div>
				
				
				<div class="grupo">
					<label class="corto">
						<input type="radio" pref='COMfsentido' name="f_revision" value='orig'><span  class="f_revision" onclick='toogle(this);filtrarFilas();'>orig.</span>
						</label>    
					<br>
					
					<label class="corto">
						<input type="radio" pref='COMfsentido' name="f_revision" value='1'><span class="f_revision" onclick='toogle(this);filtrarFilas();'>1</span>
					</label>
					<br>
					
					<label class="corto">
						<input type="radio" pref='COMfsentido' name="f_revision" value='2'><span class="f_revision" onclick='toogle(this);filtrarFilas();'>2</span>
					</label>
					<br>
					
					<label class="corto">
						<input type="radio" pref='COMfsentido' name="f_revision" value='3'><span class="f_revision" onclick='toogle(this);filtrarFilas();'>3</span>
					</label>
					
				</div>
				
				
			</div>	
			
			
			
								
			<div class="f_modalidad_ev">				
				<div class="grupo">
					<label class="corto">
						<input type="radio" pref='COMfsentido' name="f_modalidad_ev" value='todas' checked='checked'><span class="f_modalidad_ev" onclick='toogle(this);filtrarFilas();'>todo</span>
					</label>
				</div>				
				
				<div class="grupo">
					<label class="largo">
						<input type="radio" pref='COMfsentido' name="f_modalidad_ev" value='ponencia'><span  class="f_modalidad_ev" onclick='toogle(this);filtrarFilas();'>ponencia</span>
						</label>    
					<br>
					
					<label class="largo">
						<input type="radio" pref='COMfsentido' name="f_modalidad_ev" value='comunicacion'><span class="f_modalidad_ev" onclick='toogle(this);filtrarFilas();'>comunicación</span>
					</label>
					<br>
					
					<label class="largo">
						<input type="radio" pref='COMfsentido' name="f_modalidad_ev" value=''><span class="f_modalidad_ev" onclick='toogle(this);filtrarFilas();'>- vacio -</span>
					</label>
					<br>
								
				</div>				
				
			</div>	
			
			
			
			<div id="busqueda">
					<span>buscar:</span>		
					<!---<span id='buscarmas' title='buscar dentro de contenidos.'>text:<input type='checkbox' checked='checked' name='busquedaprofunda'></span>
					</span>	-->
					<br>
					<input type='text' name='busqueda' onkeyup="tecleaBusqueda(this,event)">	
			</div>											
			  
			
		</form>
		
		<div id='cargando' estado='activo'><img src='./img/cargando.gif'></div>
		
    </div>
    <table id='lista'>
	<thead>
		<tr>
			<th>ID UNM</th>
			<th>ID UNAHUR</th>
			<th>ver</th>
			<th>Titulo</th>
			<th>Sub-marco</th>
			<th>Modalidad</th>    			
			<th>Autoria</th>
			<th>Evaluadora<br>interna (1)</th>
			<th>Ev. int.<br>Resultado</th>
			<th>Evaluadora<br>externa (2)</th>
			<th>Ev. Ext.<br>Resultado</th>
			<th>Modalidad evaluada</th>    					
			<th>Dictamen Depurado</th>  	    			
			<th>Dictamen Remitido</th>  	    			
		</tr>
	</thead>
    <tbody>
    </tbody>
</table>
   
<div id='formPresent' modoversion='0'>
	
	<input type='hidden' name='id'>
	<input type='hidden' name='idrev'>
	<div id='botonera'>
		<button onclick='guardarPres()'>Guardar Presentación</button>
		<button onclick='cancelarForm(this.parentNode.parentNode)'>Cancelar</button>
		<button onclick='eliminarPresent(this.parentNode.parentNode)'>Eliminar</button>
		
		
		<button onclick='crearRevision();'>Nueva versión</button>
		
		<div id='grupoversiones'>
			versiones disponibles:
			<button id='original' onclick='formularPresentacion(this.parentNode.parentNode.parentNode.querySelector("[name=\"id\"]").value,"orig");'> orig.</button>
			<span id='listavers'></span>
			<button onclick='borrarVersion();'> borrar v.</button>
		</div>
	</div>
	<div class='mini'><label>vers.</label><input name='num_version'></div>
	<div class='casicompleto'><label>Título</label><input name='titulo'></div>
	
	<div class='casicompleto'><label>Título en ingles</label><input name='titulo_en'></div>
	<div class='cuarto'><label>ID UNM</label><input name='identificador_local'></div>
	<div class='cuarto'><label>ID UNAHUR</label><input name='identificador_local_alt'></div>
	
	<div class='cuarto'><label>Modalidad Propuesta</label>
		<select name='modalidad'>
			<option value=''>- elegir -</option>
			<option value='no definida'>No definida</option>
			<option value='poster'>Poster</option>
			<option value='presentacion'>Ponencia</option>
		</select>
	</div>
	<div class='cuarto'>
		<label>Submarco</label>
		<select name='id_p_marc_submarcos'>
		</select>
	</div>
	<div class='cuarto'><label>Fecha presentado</label><input type='date' name='fecha_presentado'></div>
	<div><label>Resumen Castellano</label><textarea name='resumen_es'></textarea></div>
	<div><label>Resumen Inglés</label><textarea name='resumen_en'></textarea></div>
	<div><label>Palabras clave</label><textarea name='palabras_clave'></textarea></div>
	<div><label>Texto</label><textarea name='texto_plano'></textarea></div>
	<div><label>Referencias Bibliográficas</label><textarea name='referencia_bibliografica'></textarea></div>
	
	<div class='formadjuntardoc' class='paquete adjuntos' tipo='resumen'>
		<div id='contenedorlienzo' ondragover='resDrFile(event,this.parentNode.getAttribute("tipo"))' ondragleave='desDrFile(event)'>	
			<h2>Resumen:</h2>			
			<label>Arraste todos los archivos aquí.</label>
			<input 
				ondrop='event.stopPropagation()' 
				exo='si' multiple='' id='uploadinput' 
				tipo='resumen' type='file' name='upload' value='' onchange='cargarCmp(this,event,"pres_documentos");'
			></label>
			<div id="listadosubiendo"></div>            
			<div id='adjuntoslista'></div>
		</div>
	</div>
	
	
	<div>
		<h2>Autoría <a id='masautoria' onclick="crearAuter();">+ auter</a></h2>
		
		<table id='tablaauteres'>
			<thead>
				<tr><th>N°</th><th>nombre</th><th>apellido</th><th>mail</th></tr>
			</thead>
			<tbody>
				
			</tbody>
		</table>
	</div>
	
	<br>
	
	<div class='completo'>
		<h2>Evaluaciones <a onclick="crearEval();">+ evalución</a></h2>
		
		<table id='tablaevaluaciones'>
			<thead>
				<tr><th>tipo</th><th>evaluadora responsable</th><th>contacto</th><th>fecha</th><th>modo</th><th>estado</th><th>resultado</th><th>docs</th></tr>
			</thead>
			<tbody>
				
			</tbody>
		</table>
		
		
		
	</div>
	
	<div class='cuarto'><label>Fecha límite para nueva versión</label><input type='date' name='fecha_lim_nueva_ver'></div>
	
		<h2>Programación <img src='./img/calendario.png'></h2>	
		
		
		<div class='completo'>
			
			<label>Evendo definido en cronograma</label><select name='id_p_crono_eventos' onchange="cargarDatosEvento();"></select>
			<label>Número de orden en el evento</label><input type='number' name='crono_evento_orden'>
			<label>Horario estimado en el evento</label><input type='time' name='crono_evento_hora_min'>
			
		</div>
		
		
		
		<div class='cuarto'><label>Fecha</label><input type='date' disabled='disabled' name='crono_evento_fecha'></div>  
		<div class='cuarto'	>
			<label>Hora inicio</label><input type='time' disabled='disabled'  name='crono_evento_hora_min_desde'>  
			<label>Hora cierre</label><input type='time' disabled='disabled'  name='crono_evento_hora_min_hasta'>
		</div>  
		
		
		<div><label>Link acceso</label><input disabled='disabled'  name='crono_evento_url_acceso'></div>  	
		<div><label>Link consulta</label><input disabled='disabled'  name='crono_evento_url_consulta'></div>  	
		<div><label>Correo electrónico consulta</label><input disabled='disabled'  name='crono_evento_mail_consulta'></div>  	
		<div><label>Anfitrión/a</label><input disabled='disabled'  name='crono_evento_anfitrion'></div>  	
		<div><label>Anfitrion/a Suplente</label><input disabled='disabled'  name='crono_evento_anfitrion_suplente'></div>  	
		
	
	<h2>Publicación</h2>			
	<div><label>Link de acceso a publicación</label><input name='publicacion_url'></div> 
		
</div>


<div id='formAuter' linkeado='no'>

	<div id='botonera'>
		<button onclick='guardarAuter()'>Guardar Autoría</button>
		<button onclick='cancelarForm(this.parentNode.parentNode)'>Cancelar</button>
		<button onclick='desvincularAuter(this.parentNode.parentNode)'>Desvincular</button>
		<button onclick='eliminarAuter(this.parentNode.parentNode)'>Eliminar</button>
		<div id='aviso'></div>
	</div>
	
	<div id='datosauter'>
	<input type='hidden' name='id'>	
	<input type='hidden' name='id_p_pres'>
	<div><label>N° orden</label><input name='orden'></div>
	<div><label>Nombre</label><input onkeyup='avisoCambioAutor("formAuter","id");consultarAuterCandidato("formAuter")' name='nombre'></div>
	<div><label>Apellido</label><input onkeyup='avisoCambioAutor("formAuter","id");consultarAuterCandidato("formAuter")'  name='apellido'></div>   		
	<div><label>Mail</label><input onkeyup='avisoCambioAutor("formAuter","id");consultarAuterCandidato("formAuter")' name='mail'></div>
	
	<div><label>Teléfono</label><input name='telefono_pais' onkeyup='avisoCambioAutor("formAuter","id");'><input name='telefono_area' onkeyup='avisoCambioAutor("formAuter","id");'><input name='telefono' onkeyup='avisoCambioAutor("formAuter","id");'	></div>
	
	<div><input type='hidden' onkeyup='busquedaPersonas(this)' name='id_p_usu'></div>
	<div><label>Referencia</label><textarea name='referencia'></textarea></div>
	</div>
	
	<div>
		<h3>existentes</h3>
		<div id='listacoincidencias'>   				
			
		</div>
	</div>
	
</div>
   
<div id='formEval' linkeado='no'>
   		
   		<input type='hidden' name='id'>
   		<div id='botonera'>
	   		<button onclick='guardarEval()'>Guardar Evaluación</button>
	   		<button onclick='cancelarForm(this.parentNode.parentNode)'>Cancelar</button>
	   		<button onclick='eliminarEval(this.parentNode.parentNode)'>Eliminar</button>
	   		<div id='aviso'></div>
   		</div>
		<div class='cuarto'><label>Tipo de evaluación</label>
			<select name='tipo'>
				<option value=''>- elegir -</option>
				<option value='interna'>Interna</option>
				<option value='externa'>Exerna</option>
			</select>
		</div> 
		
		<div class='cuarto'>
			<label>Vigencia de esta evaluación</label>
			<select name='zz_superada'>
				<option value='0'>Vigente</option>
				<option value='1'>Superada u Obsoleta</option>
			</select>
		</div> 
		
		<div class='cuarto'><label>Fecha inicio evaluación</label><input type='date' name='fecha_pedido'></div>  
		<div class='cuarto'><label>Fecha fin evaluación</label><input type='date' name='fecha_evaluado'></div>  	
		<div class='cuarto'><label>Fecha límite evaluación</label><input type='date' name='fecha_lim_eval'></div>  	
		<div class='cuarto'><label>Resultado modo</label>
			<select name='eval_modo'>
				<option value=''>- elegir -</option>
				<option value='ponencia'>Ponencia</option>
				<option value='comunicacion'>Comunicación</option>
			</select>
		</div> 
		<div><label>Resultado aceptación</label>
			<select name='eval_aceptacion'>
				<option value=''>- elegir -</option>
				<option value='Aceptado'>Aceptado</option>
				<option value='devuelto menor'>Devuelto para correcciones menores</option>
				<option value='devuelto mayor'>Devuelto para correcciones mayores</option>
				<option value='rechazado'>Trabajo Rechazado</option>
			</select>
		</div> 
   
		
		<div id='contacto_eval' class='cuarto'>
			<label>Convocado:</label>
			<input for='evaluador_convocado' 	type='checkbox' onchange='toogleCheck(this)'>
			<input name='evaluador_convocado'	type='hidden'   onchange='toogleCheck(this)'>
			 
			<label> Confirmado:</label>
			<input for='evaluador_confirmado' 	type='checkbox' onchange='toogleCheck(this)'>
			<input name='evaluador_confirmado'	type='hidden'  	onchange='toogleCheck(this)'>
			
		</div>   
		
		
		
		<div class='cuarto'><label>Resultado valoración</label><input type='number' max='10' min='0' name='eval_puntaje'></div>  			
		<div><label>Resultado comentarios a autores</label><textarea name='eval_coment'></textarea></div>
		
		<div id='datosauter'>
		<h3>Evaluador Responsable</h3>
		<input type='hidden' name='id_p_aut'>
   		<div><label>Nombre</label><input onkeyup='avisoCambioAutor("formEval","id_p_aut");consultarAuterCandidato("formEval")' name='nombre'></div>
   		<div><label>Apellido</label><input onkeyup='avisoCambioAutor("formEval","id_p_aut");consultarAuterCandidato("formEval")'  name='apellido'></div>	
   		<div><label>Mail</label><input onkeyup='avisoCambioAutor("formEval","id_p_aut");consultarAuterCandidato("formEval")' name='mail'></div>  		
   		<div><label>Teléfono</label><input name='telefono_pais'  onkeyup='avisoCambioAutor("formEval","id_p_aut")'><input name='telefono_area' onkeyup='avisoCambioAutor("formEval","id_p_aut")'><input name='telefono' onkeyup='avisoCambioAutor("formEval","id_p_aut")'></div>
   		</div>
   		
   		
   		<div id='contacto_autor'>
			<div class='formadjuntardoc' class='paquete adjuntos' tipo='eval_orig'>
				<div id='contenedorlienzo' ondragover='resDrFile(event,this.parentNode.getAttribute("tipo"))' ondragleave='desDrFile(event)'>	
					<h2>Dictamen Original:</h2>			
					<label>Arraste todos los archivos aquí.</label>
					<input 
						ondrop='event.stopPropagation()' 
						exo='si' multiple='' id='uploadinput' 
						tipo='eval_orig' type='file' name='upload' value='' onchange='cargarCmp(this,event,"eval_documentos");'
					></label>
					<div id="listadosubiendo"></div>            
					<div id='adjuntoslista'></div>
				</div>
			</div>
				
					
			<div class='formadjuntardoc' class='paquete adjuntos' tipo='eval_depur'>
				<label>Dictamen procesado:</label>	
				<input for='evaluacion_depurada' 	type='checkbox' onchange='toogleCheck(this)'>
				<input name='evaluacion_depurada' 	type='hidden' 	onchange='toogleCheck(this)'>
				<div id='contenedorlienzo' ondragover='resDrFile(event,this.parentNode.getAttribute("tipo"))' ondragleave='desDrFile(event)'>	
					<h2>Dictamen procesado:</h2>			
					<label>Arraste todos los archivos aquí.</label>
					<input 
						ondrop='event.stopPropagation()' 
						exo='si' multiple='' id='uploadinput' 
						tipo='eval_depur' type='file' name='upload' value='' onchange='cargarCmp(this,event,"eval_documentos");'
					></label>
					<div id="listadosubiendo"></div>            
					<div id='adjuntoslista'></div>
				</div>
			</div>
			
			
			
			<label>Evalación Remitida:</label>
			<input for='evaluacion_remitida_autor' type='checkbox' onchange='toogleCheck(this)'><input type='hidden' name='evaluacion_remitida_autor' onchange='toogleCheck(this)'>
			<input name='evaluacion_remitida_autor_fecha' type='date' >
		
			  
			<label>Observaciones internas:</label>
			<textarea name='observaciones'></textarea>
				
		</div>   
		
		<div>
   			<h3>existentes</h3>
   			<div id='listacoincidencias'>   				
   				
   			</div>
   		</div>
		
   </div>  
       
  <script type="text/javascript">
	var _Modo ='<?php echo $_GET['modo'];?>';
	var _IdMarco ='<?php echo $_GET['idmarco'];?>';
	var _Modover ='<?php echo $_GET['modover'];?>';
		
	var _DataPres={};
	var _DataPresOrden={};
	var _DataSubMarcos={submarcos:{},orden:{}};
	
	var _DataAutores={};
	
	var _DataEvaluaciones={};
	
	var _DataUsuarios={};
	
	var _filtro={
        'busqueda' : '',
        'f_eval' : 'todas',
        'f_smarc' : 'todas'
    };
    
    var _FiltroVisibles={
		busqueda:Array(),
		busqueda_act:'no',
		busqueda_light_act:'no',
		busqueda_light_hatch:'',
		sentido:Array(),
		abiertas:Array(),
		grupoa:Array(),
		grupob:Array()
	};
	var _busquedaXHR=null;
	
	
	var _nFile=0;	
	var xhr=Array();
	var inter=Array();
    
    
    document.querySelector('#lista').setAttribute('modo',_Modo);
  </script>


  <script type="text/javascript" src='./lista/lista_consultas.js'></script>
   <script type="text/javascript" src='./lista/lista_mostrar.js'></script>
   <script type="text/javascript" src='./lista/lista_interac.js'></script>
   <script type="text/javascript" src='./lista/lista_adjuntar.js'></script>
    
  <script type="text/javascript">
  	consultarPresentaciones();
  	
  	
window.addEventListener("dragover",function(e){
  e = e || event;
  e.preventDefault();
},false);
window.addEventListener("drop",function(e){
  e = e || event;
  e.preventDefault();
},false);
  </script>
  
 
</body>

