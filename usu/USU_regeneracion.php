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
		@font-face {
  font-family: 'Lato regular';
  font-style: normal;
  font-weight: 400;
  src: url('./css/Lato-Regular.woff') format('woff'), local('Lato regular'), local('Lato regular');
}

@font-face {
  font-family: 'Lato bold';
  font-style: normal;
  font-weight: 400;
  src: url('./css/Lato-Bold.woff') format('woff'), local('Lato regular'), local('Lato regular');
}


html, body {
    vertical-align:middle;
    font-family: 'Lato regular';
    font-size:1.2vw;
    background-image:none;
}

h2{
	margin-top:3vw;	
	margin-bottom:1vw;
}
h2 a{
	font-size:0.8vw;
	position:relative;
	left:2vw;
	opacity:0.5;
	border:1px solid #555;
	background-color:rgba(256,256,256,0.8);
	padding:0.1vw;
}
h2 a:hover{
	opacity:1;
}						
h3{
	margin-top:0.5vw;	
	margin-bottom:0.5vw;
}
h3 a{
	font-size:0.6vw;
	position:relative;
	left:2vw;
	opacity:0.5;
	border:1px solid #555;
	background-color:rgba(256,256,256,0.8);
	padding:0.1vw;
}
h3 a:hover{
	opacity:1;
}			
p{
	margin-top:0.5vw;	
	margin-bottom:0.5vw;
}

a{
	text-decoration:none;
	color:#000;
	cursor:pointer;
}
a:visited{
	text-decoration:none;
	color:#400;
}
#fondo{
	position:fixed;
	top:0px;
	left:0px;
	height:100vh;
	width:100vw;
}

#columna1{
	width:40vw;
	height:100vh;
	position:fixed;
	left:1vw;
	top:0;
	overflow:hidden;
}

#columna1 > div.marco{
	height:100vh;
	width:calc(40vw + 22px);
	overflow-y:scroll;
	overflow-x:hidden;
	position:absolute;
}
#columna1 .lista{
	margin:0;
}
#proyectos{
	position:absolute;
	top:70vh;
}		

#columna2{
	width:30vw;
	height:100vh;
	position:fixed;
	left:40vw;
	top:0;
	overflow:hidden;
}

.marco{
	height:100vh;
	width:calc(30vw + 22px);
	overflow-y:scroll;
	overflow-x:hidden;
	position:absolute;
}

.submarco{
    overflow: hidden;
    width: 27vw;
}

#columna2 >.marco> div{
	background-image: url("./img/lista1.png");
	width:30vw;
	background-size: 28vw auto;
	background-repeat: no-repeat;
	height:calc(30vw*2.4);
	position:absolute;
	left:0;
	top:20vw;
}

#columna3{
	height:100vh;
	width:30vw;
	position:fixed;
	left:70vw;
	top:0;
	overflow:hidden;
}



#columna3 >.marco{
	height:100vh;
	width:calc(30vw + 16px);
	overflow-y:scroll;
}

#columna3 >.marco> div{
	background-image: url("./img/lista2.png");
	background-size: 30vw auto;
	background-repeat: no-repeat;
	height:calc(30vw*2.3);
	width:30vw;
	position:absolute;
	left:0;
	top:20vw;
}

#applista {
	overflow:hidden;
	height:calc((30vw*2.3) - 200px);
	width:calc(27vw - 3.5vw + 18px);
}

#applista .estado{
	cursor: help;
	
}

#titulo{
	position:fixed;
	top:8vh;
	left:5vw;
	height:70vh;
			
}
#unmgeo{
	vertical-align:top;
	height:70vh;
	display:inline-block;				
}
#logbar{
	display:block;
	position:fixed;
	top:2vh;
	right:0px;
	width:50vw;
	height:auto;
	background-color:#ffd;
	border:1px solid #990;
	z-index:5;
}
#disclaimer{
	display:inline-block;
	font-size:1vw;
	width:30vw;
	vertical-align:middle;
	margin:2px;
	text-align:justify;
	background-color:#ffe;
	
	border:1px solid #cca;
}
#logbar div#usu{
	display:inline-block;
	width:calc(50vw - 30vw - 10px);
}
#logbar a{
	margin:2px;
	vertical-align:middle;
	display:inline-block;
	background-color:#ffd;
	border:1px solid #990;
	padding:0.2vw;
}
#logbar a:hover{
	background-color:#ff5;
}
#logbar p{
	margin:2px;
	display:inline-block;
	vertical-align:middle;
}
#logbar a#botonconfig{
	display:none;
}
a#botonsalir{
	display:none;
}
#bajada{
	margin-left:1vw;
	font-size:8vh;
	vertical-align:top;
	display:inline-block;
}		
.lista>a{
	border-radius:2vw;
	border:0.5vw solid transparent;
	margin-bottom:1vw;
	display: inline-block;
}

.lista>a:hover{
	background-color:rgba(200,100,200,0.5);
}

.lista>div{
	border-radius:2vw;
	border:0.5vw solid transparent;
	margin-bottom:1vw;
}

.lista>div:hover{
	background-color:rgba(200,100,200,0.5);
}

.lista{
	margin:1.5vw 3.5vw;
}
div.columna > div > div > h2{
	margin-left:3vw;
}

.lista .proitem{
	background-image: url("./img/p1.png");
	background-repeat: no-repeat;
	background-size: 39vw auto;
	height: 9vw;
	padding:5vh;
	margin-top:0;
}

.lista p{
	font-size:80%;
}
.lista p #cargo{
	font-size:70%;
	font-weight:bold;
}

.lista .appitem .noweb{
	font-size:70%;
	color:#333;
}

#configsis{
    z-index: 10;
    display:none;
    background-color: #fff;
    position: fixed;
    width: 90vw;
    height: 90vh;
    top: 5vh;
    left: 5vw;
}
#configsis a{
    margin: 2px;
    vertical-align: middle;
    display: inline-block;
    background-color: #ffd;
    border: 1px solid #990;
    padding: 0.2vw;
}

#configsis .fila div{
	display:inline-block;
	width:10vw;
}
#configsis .fila div.mail{
	width:20vw;
}
#resetPass{
	display: block;
	position: fixed;
	top: 20vh;
	right: 20vw;
	width: 60vw;
	height: 60vh;
	background-color: #ffd;
	border: 1px solid #990;
	z-index: 5;
}
#resetPass a{
    margin: 2px;
    vertical-align: middle;
    display: inline-block;
    background-color: #ffd;
    border: 1px solid #990;
    padding: 0.2vw;
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

	
	<form id='resetPass'>
		<h1>Usted está a punto de regenerar su contraseña.</h1>
		<a id='cerr' href='./index.php'>cancelar</a>
		<a id='guardar' onclick='enviarPass()'>enviar</a>
		<div>
			<h2>Usuarios</h2>
			<label>nueva contraseña</label><input type='password' name='pass1'><br>
			<label>nueva contraseña confirmación</label><input type='password' name='pass2'>
			<input type='hidden' name='cod'>
			<input type='hidden' name='usuid'>
		</div>
		
		
	</form>
	
<script type="text/javascript">

var _cod='<?php echo $_GET['cod'];?>';
var _usuid='<?php echo $_GET['usuid'];?>';
if(_usuid==''||_cod==''){
	alert('faltan variables de carga');
}

document.querySelector('#resetPass input[name="cod"]').value=_cod;
document.querySelector('#resetPass input[name="usuid"]').value=_usuid;
	
function enviarPass(){
	
	_parametros={
		usuid: document.querySelector('#resetPass input[name="usuid"]').value,
		cod: document.querySelector('#resetPass input[name="cod"]').value,
		pass1: document.querySelector('#resetPass input[name="pass1"]').value,
		pass2: document.querySelector('#resetPass input[name="pass2"]').value,
	}

	$.ajax({
		data:  _parametros,
		url:   './usu/usu_reset_pass.php',
		type:  'post',
		error: function (response){alert('error al contactar el servidor');},
		success:  function (response){
			
			var _res = $.parseJSON(response);
			//console.log(_res);
			
			if(_res.res=='exito'){
				alert('exito!. por favor volvé a loguearte en: http://170.210.177.36/unmgeo/index.php');
			}else{
				alert('falló la actualizaicón de proyectos');
			}
		}
	});	
	
}

</script>


	
</body>

