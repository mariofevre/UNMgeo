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
		
	<link rel="stylesheet" type="text/css" href="../../css/unmgeo.css">
	<link rel="stylesheet" type="text/css" href="./css/lista.css">
	
	<link rel="stylesheet" type="text/css" href="./js/ol_6.5.0/ol.css">

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
	
	td input{
		width:800px;
	}
	td div{
		width:880px;
	}
	
	div.vinculado{
		background-color:rgba(100,255,100,0.5);
		border:1px solid rgba(50,100,50,1);
		width:auto;
	    border-radius: 7px;
		display: inline-block;
		padding: 0px 5px;
	}
	div.candidato{
		background-color:rgba(100,100,255,0.5);
		border:1px solid rgba(50,50,100,1);
		width:auto;
		border-radius: 7px;
		display: inline-block;
		padding: 0px 5px;
	}
	#mapa1{
		position:fixed;
		display:none;
		width:450px;
		height:500px;
		rop:100px;
		right:50px;
		z-index:20;
		border:2px solid #000;
		box-shadow:10px 10px 20px rgba(0,0,0,0.8);
		background-color:#fff;
	}
	#mapa1[estado='activo']{
		display:block;
	}
	
	table{
		width:calc(100vw - 500px);
		min-width:400px;
	}
	</style>
</head>



<body>
	
	<script type="text/javascript" src="./js/jquery/jquery-3.5.1.js"></script>
	
	
	<script  type="text/javascript" src="./js/ol_6.5.0/ol.js"></script>		
	



    <table id='lista'>
	<thead>
		<tr>
			<th>Id inst</th>
			<th>Nombre inst</th>
			<th>Localizaciones</th>
		</tr>
	</thead>
    <tbody>
    </tbody>
</table>
       
  <script type="text/javascript">

  </script>

    
  <script type="text/javascript">
  
  	
function consultaPalabras(){
	
	_parametros = {
	};
	
	
	$.ajax({
		data:  _parametros,
		url:   './consultapublica/palabras_clave.php',
		type:  'post',
		success:  function (response){
			var _res = $.parseJSON(response);
			console.log(_res);
			
		
			
			for(_mgn in _res.mg){
				alert(_res.mg[_mgn]);
			}
		
			if(_res.acc=='log'){
				console.log('i');
				abrirLogRemoto();
			}
			
			if(_res.res!='exito'){
				alert('error al generar resutlado');
				return;
			}
			
			for(_lo in _res.data.palabras){
					
					_tr=document.createElement('tr');
					document.querySelector('tbody').appendChild(_tr);
					
					_td=document.createElement('td');
					_tr.appendChild(_td);
					_td.innerHTML=_lo;
					
					_td=document.createElement('td');
					_tr.appendChild(_td);
					_td.innerHTML=_res.data.palabras[_lo].cant;
					
					
			}
			
		}
	})
}
consultaPalabras();
  	
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

