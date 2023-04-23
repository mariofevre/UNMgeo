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
if(!isset($_GET['modo'])){$_GET['modo']='';}
if(!isset($_GET['modover'])){$_GET['modover']='';}


?><!DOCTYPE html>
<head>
	<title>UNM - GEO - portal</title>
	<META http-equiv="Content-Type" content="text/html; charset=windows-1252">
	<meta http-equiv="Expires" content="0">
	<meta http-equiv="Last-Modified" content="0">
	<meta http-equiv="Cache-Control" content="no-cache, mustrevalidate">
	<meta http-equiv="Pragma" content="no-cache">
		


	<style type="text/css">

		body {
			font-family: 'Raleway', Arial, Tahoma;
			font-size: 0.95em;
			border: 0;
			color: #111;
			background-color: #e5e5e5;
		}
		
		:root {
			--wp-admin-theme-color: #007cba;
			--wp-admin-theme-color-darker-10: #006ba1;
			--wp-admin-theme-color-darker-20: #005a87;
		}
		
		
		#wrapper {
			max-width: 1340px;
			width: 92%;
			margin: 2em auto;
			
			box-sizing: border-box;
			background: #fff;
		}
		#header-wrap {
			border-top: 4px solid #004488;
		}
		header{
			padding: 2.5em;
			background: #fff;
		}
		#logo {
			max-width: 100%;
			overflow: hidden;
		}
		#logo img{
			margin: 0 1.5em 0 0;
			padding: 0;
			border: none;
			vertical-align: top;
			max-width: 100%;
			height: auto;
		}
		
		h1 {
			background: #004488;
			position: relative;
			margin:0px 10px;
			color:#fff;
			
		}
		
		#navi-wrap {
			border-top: 1px solid #004488;
			border-bottom: 1px solid #004488;
			background: #fff;
			padding-top: 1px;
			padding-bottom: 1px;
		}
		

		#mainnav {
			background: #004488;
			position: relative;
		}
		
		.page-title, .entry-title {
			font-weight: bold;
			font-family: 'Bitter', Georgia, Arial, Tahoma;
		
			line-height: 1.4em;
			-ms-word-wrap: break-word;
			word-wrap: break-word;
		}
		.texto{
			margin:10px;
			}
			
	div#cargando{
		display:none;
		position:fixed;
		left:48vw;
		top:48vh;
		
	}
	div#cargando[estado='activo']{
		display:inline-block;
	}
	table, td, tr, th{
	border:1px solid #000;
  border-collapse: collapse;

		}
		
		a{
			
			    font-weight: bold;
    font-family: 'Bitter', Georgia, Arial, Tahoma;
    line-height: 1.4em;
    background: #004488;
    position: relative;
    margin: 10px;
    color: #fff;
    cursor:pointer;
    border:1px solid  #004488;
    padding:5px;
    }

			a:hover{
			 color: #004488;
			  background: #fff;
			 
			}
			
				</style>
</head>



<body>
	
	<script type="text/javascript" src="./js/jquery/jquery-3.5.1.js"></script>
	
	<div id='wrapper'>
	<div id='header-wrap'>
	<header>
		<div id='logo'>
			<img src='./img/encabezado.jpg'>
		</div>
		
	</header>
	</div>
	
	
	<div id='navi-wrap'>
	<div id="mainnav">
		<h1 class='page-title'>Página de confirmación</h1>		
	</div>
	</div>
	
	<div class='texto'>
	La siguiente es la información cargada para un presentación a su nombre. Se solicita que verifique los contenidos y en caso de ser correctos presione el botón de confirmación.
	</div>
	
	
		<div id='cargando' estado='activo'><img src='./img/cargando.gif'></div>
	<div id='datosconfirma'>
	<table>
	
		<tr>
			<th>Titulo</th>
			<td id='cont-titulo'></td>
		</tr>
		<tr>
			<th>Titulo en inglés</th>
			<td id='cont-tituloen'></td>
		</tr>
		<tr>
			<th>Autoría</th>
			<td id='cont-autoria'><table></table></td>
		</tr>
		<tr>
			<th>Última versión presentada</th>
			<td id='cont-version'></td>
		</tr>
		<tr>
			<th>Resultado de evaluación</th>
			<td id='cont-resultado'></td>
		</tr>
		
		<tr>
			<th>Palabras clave</th>
			<td id='cont-palabras'></td>
		</tr>
		<tr>
			<th>Resumen</th>
			<td id='cont-resumen'></td>
		</tr>
		<tr>
			<th>Resumen en inglés</th>
			<td id='cont-resumenen'></td>
		</tr>
		
	</table>
	</div>
	
	<div class='texto'>
	<a onclick='confirmar()'>Confirmo que los contenidos visibles son correctos para la publicación de las jornadas y la generación de certificados.</a>
	</div>
	<div id='navi-wrap'>
	</div>
    </div>   
  <script type="text/javascript">
	var _idpres ='<?php echo $_GET['idpres'];?>';
	var _codigo ='<?php echo $_GET['codigo'];?>';
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
    
    
    
    
    
    function consultarConfirmación(){
		_parametros = {
			'idpres':_idpres,
			'codigo':_codigo
		};
		
		cartelDeCarga('activo');
		
		$.ajax({
			data:  _parametros,
			url:   './confirmacion/confirmacion_consulta.php',
			type:  'post',
			success:  function (response){
				var _res = $.parseJSON(response);
				console.log(_res);
				
				cartelDeCarga('inactivo');
				
				for(_mgn in _res.mg){
					alert(_res.mg[_mgn]);
				}

					
				if(_res.res!='exito'){
					alert('error al generar resutlado');
					return;
				}
				
				_DataPres=_res.data.presentacion;
				mostrarContenidos();
			}
		})	
			
	}
    

    function mostrarContenidos(){
		document.querySelector('#cont-titulo').innerHTML=_DataPres.titulo;
		document.querySelector('#cont-tituloen').innerHTML=_DataPres.titulo_en;
		document.querySelector('#cont-palabras').innerHTML=_DataPres.palabras_clave;
		document.querySelector('#cont-resumen').innerHTML=_DataPres.resumen_es;
		document.querySelector('#cont-resumenen').innerHTML=_DataPres.resumen_en;
		
		document.querySelector('#cont-resultado').innerHTML=_DataPres.eval_ultima.externa.eval_aceptacion + 'paraser prsentado como: ' +_DataPres.eval_ultima.externa.eval_modo+'.';
		
		
		
		document.querySelector('#cont-version').innerHTML='<a>descargar archivo</a>';
		
		_dest=document.querySelector('#cont-autoria table');
		
		for(_na in _DataPres.autoria){
			
			_dataut=_DataPres.autoria[_na];
			//console.log(_idaut);
			
			_tr=document.createElement('tr');
				
			
			_td=document.createElement('td');
			_td.innerHTML=_dataut.nombre;
			_tr.appendChild(_td);
			
			_td=document.createElement('td');
			_td.innerHTML=_dataut.apellido;
			_tr.appendChild(_td);
			
			
			_td=document.createElement('td');
			_td.innerHTML=_dataut.referencia;
			_tr.appendChild(_td);
			
			_td=document.createElement('td');
			_td.innerHTML=_dataut.mail;
			_tr.appendChild(_td);
			
			_dest.appendChild(_tr);
		}
		
		document.querySelector('#cont-titulo').innerHTML=_DataPres.titulo;
		
	}
    
    function confirmar(){
		
		_dest=document.querySelector('#datosconfirma');
		
		_parametros = {
			'idpres':_idpres,
			'codigo':_codigo,
			'html':_dest.innerHTML
		};
		cartelDeCarga('activo');
		
		$.ajax({
			data:  _parametros,
			url:   './confirmacion/confirmacion_ed_confirma.php',
			type:  'post',
			success:  function (response){
				var _res = $.parseJSON(response);
				console.log(_res);
				
				cartelDeCarga('inactivo');
				
				for(_mgn in _res.mg){
					alert(_res.mg[_mgn]);
				}
					
				if(_res.res!='exito'){
					alert('error al generar resutlado');
					return;
				}
				alert('Gracias, los contenidos han sido confirmados para su publicación en el marco de las jornadas.');
			}
		});
			
	}
  </script>


  <script type="text/javascript" src='./lista/lista_consultas.js'></script>
   <script type="text/javascript" src='./lista/lista_mostrar.js'></script>
   <script type="text/javascript" src='./lista/lista_interac.js'></script>
   <script type="text/javascript" src='./lista/lista_adjuntar.js'></script>
    
  <script type="text/javascript">

    consultarConfirmación();
  	
  	
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

