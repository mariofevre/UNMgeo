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


?><!DOCTYPE html>
<head>
	<title>UNM - GEO - portal</title>
	<META http-equiv="Content-Type" content="text/html; charset=windows-1252">
		
	<link rel="stylesheet" type="text/css" href="../../css/unmgeo.css">
	<link rel="stylesheet" type="text/css" href="./css/lista_mesas.css">

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

	</style>
</head>



<body>
	
	<script type="text/javascript" src="./js/jquery/jquery-3.5.1.js"></script>
	
	<div id='menusuperior'>
		<div>
		<h1>Lista de mesas</h1>
		
		<a onclick='crearMesa()'>+ mesa</a>
		<br>	
		<div id="stats">
			<p><label>mesas cargadas:</label> <span id='stat_res'></span></p>
			<p><label>mesas confirmadas:</label> <span id='stat_eval_int'></span></p>
			<p><label>mesas prgramadas:</label> <span id='stat_eval_ext'></span></p>
		</div>	
		
		
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
					<span id='buscarmas' title='buscar dentro de contenidos.'>text:<input type='checkbox' checked='checked' name='busquedaprofunda'></span>
					</span>
					<br>
					<input type='text' name='busqueda' onkeyup="tecleaBusqueda(this,event)">		
			</div>											
			  
			
		</form>
		
		<div id='cargando' estado='activo'><img src='./img/cargando.gif'></div>
		
    </div>
    <table id='lista'>
	<thead>
		<tr>
			<th>Nombre</th>
			<th>Canal</th>
			<th>Titulo</th>
			<th>Fecha</th>
			<th>Hora</th>
			<th>Hasta</th>
			<th>Participantes</th>
			<th>Observciones</th>
			<th>Link</th>
		</tr>
	</thead>
    <tbody>
    </tbody>
</table>
   
<div id='formMesa'>
	
	<input type='hidden' name='id'>
	<div id='botonera'>
		<button onclick='guardarMesa()'>Guardar Mesa</button>
		<button onclick='cancelarForm(this.parentNode.parentNode)'>Cancelar</button>
		<button onclick='eliminarMesa(this.parentNode.parentNode)'>Eliminar</button>
	</div>
	
		<div class='cuarto'><label>Nombre</label><input name='nombre'></div>
	<div class='cuarto'><label>Fecha</label><input type='date' name='fecha'></div>
	<div class='cuarto'><label>Hora inicio</label><input type='time' name='hora'></div>
	<div class='cuarto'><label>Hora fin</label><input type='time' name='hora_hasta'></div>
	
	
	<div class='cuarto'><label>Canal</label>
		<select name='id_p_canales'>
		</select>
	</div>
	<div class='completo'><label>Título</label><input name='titulo'></div>
	<div class='completo'><label>Título (en)</label><input name='titulo_en'></div>
	<div class='completo'><label>Link</label><input name='link'></div>
	
	<div><label>Tema</label><textarea name='tema'></textarea></textarea></div>
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
		<h2>Autoría <a onclick="crearAuter();">+ auter</a></h2>
		
		<table id='tablaauteres'>
			<thead>
				<tr><th>N°</th><th>titulo</th><th>nombre</th><th>apellido</th><th>mail</th><th>conf</th><th>tema</th><th>filiación</th></tr>
			</thead>
			<tbody>
				
			</tbody>
		</table>
	</div>
		<div class='completo'><label>Observaciones</label><textarea name='observaciones'></textarea></div>
		
</div>


<div id='formAuter' linkeado='no'>

	<div id='botonera'>
		<button onclick='guardarAuter()'>Guardar Autoría</button>
		<button onclick='cancelarForm(this.parentNode.parentNode)'>Cancelar</button>
		<button onclick='desvincularAuter(this.parentNode.parentNode)'>Desvincular</button>
		<div id='aviso'></div>
	</div>
	
	<div id='datosauter'>
	<input type='hidden' name='id'>
	<div><label>N° orden</label><input name='orden'></div>
	<div><label>titulo</label><input name='titulo'></div>
	<div><label>Nombre</label><input onkeyup='avisoCambioAutor("formAuter","id");consultarAuterCandidato("formAuter")' name='nombre'></div>
	<div><label>Apellido</label><input onkeyup='avisoCambioAutor("formAuter","id");consultarAuterCandidato("formAuter")'  name='apellido'></div>   		
	<div><label>Mail</label><input onkeyup='avisoCambioAutor("formAuter","id");consultarAuterCandidato("formAuter")' name='mail'></div>
	
	<div><label>Teléfono</label><input name='telefono_pais' onkeyup='avisoCambioAutor("formAuter","id");'><input name='telefono_area' onkeyup='avisoCambioAutor("formAuter","id");'><input name='telefono' onkeyup='avisoCambioAutor("formAuter","id");'	></div>
	
	<div><input type='hidden' onkeyup='busquedaPersonas(this)' name='id_p_usu'></div>
	<div><label>Rol</label><input name='rol'></div>
	<div><label>Filiación</label><input name='filiacion'></div>
	<div><label>Tema</label><input name='tema'></div>
	<div><label>Confirmado</label>
		<input for='confirmado' type='checkbox' onchange='toogleCheck(this)'>
		<input type='hidden' name='confirmado' type='checkbox' onchange='toogleCheck(this)'>
	</div>
	</div>
	
	
	<div class="completo"><label>Título</label><input name="tema_titulo"></div>
	<div class="completo"><label>Título (en)</label><input name="tema_titulo_en"></div>
	<div class="completo"><label>Texto</label><textarea name="tema_titulo_texto"></textarea></div>
	<div class="completo"><label>Palabras clave</label><textarea name="tema_titulo_palabras_clave"></textarea></div>

	
	
	<div>
		<h3>existentes</h3>
		<div id='listacoincidencias'>   						
		</div>
	</div>
	
</div>
   
       
<script type="text/javascript">
	var _IdMarco='1';
	var _DataPres={};
	var _DataPresOrden={};
	var _DataCanales={'canales':{},'orden':{}};
	
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
    
  </script>


  <script type="text/javascript" src='./lista_mesas/lista_mesas_consultas.js'></script>
  <script type="text/javascript" src='./lista_mesas/lista_mesas_consultas.js'></script>
   <script type="text/javascript" src='./lista_mesas/lista_mesas_mostrar.js'></script>
   <script type="text/javascript" src='./lista_mesas/lista_mesas_interac.js'></script>
   <script type="text/javascript" src='./lista_mesas/lista_mesas_adjuntar.js'></script>
    
  <script type="text/javascript">
  	consultarMesas();
  	
  	
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

