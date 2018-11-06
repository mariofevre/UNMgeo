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
if($_SESSION["unmgeo"]["usuario"]['sis_adm']!=1){
	header('location: ./index.php');
}
	
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
			background-color:#e9ddaf;
			opacity:0;
			transition: opacity 0.2s ease-in-out;
		}
		
		#lacapa{
		    display: block;
		    position: fixed;
		    height: 80vh;
		    width: 90vw;
		    background-color: #dfd;
		    border: 2px solid #aca;
		    top: 8vh;
		    left: 8vw;
		    z-index: 5;
		}
		#lacapa #metadatos{
			font-size:2vh;
		}
		#lacapa #codigo{
		    margin:1vh;
		    font-size:2vh;
		}
		
		#lacapa a{
		    border: 1px solid #aca;
		    border-radius:3px;
		    box-shadow:2px 2px 5px rgba(0,0,0,0.5);
		    margin:1vh;
		}
		
		#lacapa a:hover{
		    background-color:#aca;
		}
		
		#lacapa a:visited{
		    color:#000;
		}
		#volver{
			position:absolute;
			top:1vh;
			right:1vw;
		}
		
		#cap_nav{
			
			height:60vh!important;
			
		}
		
		#elproyecto{
			z-index:10;
			position:fixed;
			width:90vw;
			height:80vh;
			top:5vh;
			left:5vw;
			border: 3px solid rgba(200,55,55,1);
			background-color:rgba(220,120,120,0.9);
			box-shadow:1vw 1vw 3vw rgba(50,50,50,0.5); 
			padding:1vw;
		}
		
		p{
			margin:0 10px;
		}
		
		h1{
			margin: 0 10px;
			display: inline-block;
			width: 40%;
			text-align: justify;
			font-size: 150%;
		}
		#descripcion{
			display: inline-block;
			width: calc( (60% - 1vw) - 50px);
			text-align: justify;
		}
		h2 {
		    font-size: 18px;
		    margin: 0 0 0 10px;
		}		
		
		form{
			margin:0;
		}
		form.respuestar > .COMcomunicacion{
			font-size:10px;padding:0;
		}
				
		pre {
		    font-family: inherit;
		    white-space: pre-wrap;
		}
		
		input.fecha{
		float:left;
		width:40px;		
		}		
	
		input.chico{
		width:560px;
		}		
		
		input.mini{
		float:left;
		width:25px;
		}		
		
		select.chico{
		width:100px;
		}			
			
		th{
		font-size:12px;
		text-align:right;
		background-color:#DBF7FE;
		width:100px;
		}
	
		td{
			text-align:center;
			border:solid 1px gray;
		}
		
		#page > div#cuadrovalores{
			display:inline-block;
			width:40%;
		}
		#page  > div#menutablas{
			display:inline-block;
			width:calc(60% - 5px);
		}
		
		
		.salva{
		width:90px;
		margin-bottom:5px;
		float:left;
		}
	
		.reporte{
		width:200px;
		}
	
		.marco{
		border-bottom: 1px solid #EEEEEE;
		float: none;
		width: 100%;		
		}
		
		.referencia{
		font-size: 10px;
		}
	
		.alerta{
		background-color:red;
		}
	
		.similth{
		background-color:none;
		width:800px;
		text-align:left;
		}	
	
		.fila{
			display:block;
			position:relative;
			width:784px;
			height:auto;
			margin-bottom: -1px;
			font-size:11px;
			background-color:#fff;
		}
		
		.fila.busqueda{
			border-top:#CDF0F9 1px solid;
			height:auto;
			overflow-y: scroll;
			max-height: 200px;
			background-color:#EBF5F9;
		}
		
		.fila.titulo, .fila.filtro{
			height:30px;
		}
				
	
		.fila > div, .fila > a{
			display:inline-block;
			overflow:hidden;
			text-align:center;
			position:relative;		
			vertical-align:top;
		}
		
		.fila > a{
			line-height: 15px;
		}
		
		.contenido{
			font-size:12px;
			height:30px;
			border-top:1px solid #000;
			border-bottom:1px solid #000;
			z-index:1;	
		}	
		
		.cerrada .contenido{
			z-index:0;
			border-color:silver;
		}		
		.cerrada .contenido.respuesta{
			border-left-color:silver;
		}			
	
	
		.fila.titulo{
			background-color:#DBF7FE;
		}			
		.fila.titulo > div{
			height: 28px;
			font-size:11px;	
			margin-right: 0px;	
		}
					
		.seleccion{
			width:30px;
			font-size:10px;	
		}		
		
		.nombre{
			width:170px;
			font-size:14px;	
			line-height:14px;
		}
		
		.nombre.eliminada{
			background-color:#f00;
		}
		.accion{
			width:45px;
			line-height:12px:
		}		
		
		.aclaracionsubmit{
			width:120px;
			height:24px;
			font-size:12px;
			font-weight:normal;
			display:inline-block;
		}
		
		.tipo{
			width:40px;
		}
		
		.adjunto{
			width:30px;
		}
		.contenido.adjunto > a{
			font-size:9px;
			width:auto;
			display:inline-block;
		}
		.contenido.adjunto > a.alerta{
			border:1px solid red;
			background-color:transparent;
		}		
		
				
		div.emision, div.recepcion, div.cerradodesde{
			width:41px;
			line-height:9px;
		}		
			
		.emision>span, .recepcion>span, .cerradodesde>span{
			font-size:10px;
		}		
		
		.ids{
			width:88px;
			display:block;
		}
		.id1{
			width:86px;
			display:block;		
		}
	
			
		.id2{
			width:86px;
			display:block;
		}
					
		.responder{
			width:20px;
		}					
		
		.contenido.alta{
			font-size:10px;
		}
		.contenido.baja{
			font-size:10px;
		}		
		
	
	
		a.contenido.nombre{
			color:#000;
		}
		
		.cerrada{
			color: #777;
			background-color:#E4F4EB;
		}		
		
		a.contenido.nombre{
			color: #000;
		}		
		.cerrada > a.contenido.nombre{
			color: #777;
		}
		.cerrada > a.contenido.nombre:hover{
			color: #000;
		}				
				
		.id_p_B_usuarios_usuarios_id_nombre_autor{
			width:45px;
		}	
		.id_p_B_usuarios_usuarios_id_nombre_responsable{
			width:45px;
		}	
		
		.accionseleccion{
			display:auto;
		}
		
					
		button{
			min-width:160px;
			padding:0px;
		}
		
		#contenidoextenso{
			overflow:hidden;
			margin-top:20px;
			max-height: calc(100% - 190px);
			overflow-y: scroll;
		}	
		
		#titulo{
			position: relative;
			top: 0;
			left: 0;
			height: auto;
		}
		
		#archivos > #botonanadir{
			background-color: rgba(8,175,217,0.3);
			background-color: #fff;
			color: #08afd9;
			height: 80px;
			vertical-align: middle;
			font-size: 30px;
			text-align: center;
			width: 50px;
			border: 2px solid #08afd9;
			border-radius: 2px;
			box-shadow: 1px 1px 3px 1px rgba(0,0,0,0.2);
			margin: 0px;
			display: inline-block;
			line-height: 10px;
		}
		#archivos > a#botonanadir:hover{
			background-color: #08afd9;
			color:#fff;
		}
		
		#archivos > #botonanadir > span{
			font-size:11px;	
		}
		

		@media print {
			h1{
				font-size:15px;
			}
			#encabezado{
			visibility: hidden;
			position:absolute;
			}
			body{
				background-image:none;
			}
			
			div.fila, form.fila{
				width:718px;
			}
			
			#page{
				width:722px;
				margin:0px;				
				border:1px solid #000;
				background-image:none;
			}			
			div.recuadros.ventanadesarrollo{
				display:none;
			}
			#pageborde{
				background-color:transparent;
			} 
			
			#recuadro{
				display:none;
			}
			#recuadro2{
				display:none;
			}
			#recuadro3{
				display:none;
			}			
			
			a{
				color:#000;
			}
			
			a.linkppal{
				display:none;				
			}			
			
			a.version{
				display:none;		
			}
			
			.fila{
				font-size:5px;
				position:relative;
			}
			div.version{
				width:5px;
				border-width:1px;
			}
			div#contenidoextenso{
				margin:0px;
			}
			div.cargafecha{
				display:none;
			}
			div.versionesventana {
			    width: 100px;
			}
			select{
				display:none;
			}
			input{
				display:none;
			}
			.fila.filtro{
				display:none;
			}			
			.recuadro.ventanadesarrollo{
				display:none;
			}
			div.#comandoA{
				display:none;
			}
			a.botonmenu{
				display:none;
			}
			a.cargarespuesta{
				display:none;
			}
			a.eliminar.respuesta{
				display:none;
			}					
			.respuesta{
				width: 140px;
				display:inline-block;
			}
			#comandoAborde, #encabezado{
				display:none;
			}
			
			#pageborde{
				margin:0;
			}
			
			#cuadroresumen{
			   page-break-before: always;		
			}
			
			.respuesta > div > a.cargarespuesta{
				display:none;
			}		
			
			.fila > div.descrip{
				display:none;
			}
		}
		
		#page>div{
			font-size:11px;
				display:block;
		}
		form{
			display:inline-block;
			border-radius:4px;
			
		    border:1px solid #D0D0D0;
		    
		    margin:2px;
		}
		form > div{
		    background-color:#EFEFEF;    
		    overflow: hidden;
		    display:inline-block;
		   
		}
		
		
		div > span{
		    text-align:center;
		    padding:0px;
		    display:inline-block;
		    cursor: pointer;
		}
		
		select{
		    display:inline-block;	
		    width:60px;
		}
		
		select, option, input[type='button']{
		    text-align:center;
		    padding:0px;
		    cursor: pointer;
		    height:16px;  
		    vertical-align:middle;
		    margin:1px 2px;
		    border-radius:4px;
		    border:1px solid #D0D0D0;
		    overflow:auto;
		    line-height:10px;
		    font-size:inherit;
		    font-family:inherit;    
		    color:inherit;
		}
		input[type='button']{
			width:85px;
		}
		input[type='button']:hover{
			background-color: #C8E9D7;
		}
		
		label span.saliente{
			background-color:#EAFBCE;
		}
		
		label span.entrante{
			background-color:#839EF8;
		}
		
		label input {
		    position:absolute;
		    top:-20px;
		}
		
		input[type='radio'] {
		    display:none;
		}
		
		input[checked='checked'] + span{
		    background-color:#404040;
		    color:#F7F7F7;
		}
		
		#uploader{
		
		}
		#contenedorlienzo{
			vertical-align:top;
			display:block;
			position:relative;
			box-shadow:2px 2px 5px 2px rgba(0,0,0,0.5) inset;
		}
		
		
		#editorArchivos #contenedorlienzo{
			height:60px;
		}
		
		.botoncerrar{
			position:absolute;
			right:1vw;
			top:1vh;
		}
		
		#contenedorlienzo #lienzo{
			vertical-align:top;
			display:inline-block;
			position:relative;
			border:2px solid #aaa;
		}
		#contenedorlienzo #upload{		
			position:absolute;
			display:inline-block;
			border:0px solid #aaa;
			position:relative;
			height:80px;
			width:200px;
		}
		
		#contenedorlienzo #upload label{
			height:calc(100% - 40px);
			width:calc(100% - 50px);
			font-size:15px;
			color:#999;
			margin:20px;
			text-align:center;
			line-height:20px;
			display: inline-block;
		}
			
		#contenedorlienzo #uploadinput{
			opacity:0;
			height:100%;
			width:100%;
			position:absolute;
			top:0;
			left:0;
			display:inline-block;
		}
		form{
			border:2px solid #aaa;	
		}
		
		#archivos > div, #archivos > form{
			vertical-align:middle;
			display:inline-block;
			height:80px;
			width:200px;
		}
		#listadosubiendo{
			background-color:#fff;
			border:2px solid #aaa;
			border-radius:2px;
			position:relative;
		}
		
		
		
		#listadosubiendo > p{
			display:block;
			border:1px solid #999;
			margin:1px;
			color:#444;
			background-color:#ccc;
		}
		
		#listadoaordenar{
			background-color:#fff;
			border:2px solid #aaa;
			border-radius:2px;
			position:relative;
		}
		#listadosubiendo label, #listadoaordenar label{
			position:absolute;
			top:2px;
			left:2px;
			color:silver;
			font-weight:normal;
		}
		#listadosubiendo .archivo, #listadoaordenar .archivo{
			margin:1px;
			width:calc(100% - 6px);
		}
		.archivo{
			display:inline-block;
			border:1px solid red;
			margin:1px;
			margin-left:10px;
			padding-left:2px;
			color:red;
			background-color:#fbb;
			cursor:move;
			width:calc(100% - 15px);
			max-width:221px;
		}
		
		
		a.archivo:hover{
			background-color:#f00;
			color:#0;
		}
		
		#archivos > #eliminar{
			background-color:#fbb;
			color:#f00;
			font-size:20px;
			text-align:center;
			width:50px;
			border:2px solid #f00;
			border-radius:2px;
			box-shadow:2px 2px 5px 2px rgba(0,0,0,0.5) inset;
		}
		#archivos > #eliminar > span{
			color:#f00;
			font-size:11px;
		}
		
			
		#contenidoextenso{
			margin-top:10px;
		}
			
			
		
		div#modelos{
			display:none;
		}
		#botonanadir{
			display:block;
			margin:20px;
		}
		.medio{
			display: inline-block;
			height: 91px;
			width: 10px;
			border: 1px solid silver;
			position: relative;
			vertical-align: middle;
			transition: width 0.5s;
			background-color: rgba(50,50,50,0.2);
			margin-top:2px;
			margin-left:1px;
	
		}
		.medio > div{
			height: 10px;
			width: calc((( 90vw - 2vw - 14px - 11px ) / 2 ) - 15px);
			top:-1px;
			left:10px;
			border: 1px solid silver;
			border-left: 1px solid #dadada;
			background-color:inherit;		
			position: absolute;	
			height: 10px;
		}
		.item[nivel="1"] .submedio{
			width:213px;
		}
		.item[nivel="2"] .submedio{
			display:none;
		}
		.item[nivel="2"] .medio{
			width:calc(100% - 4px);
			height:10px;
		}
			
		.item{
			display:inline-block;
			width:calc(50% - 20px);
			min-width:100px;
			min-height:60px;
			border:1px solid #08afd9;
			box-shadow:6px 6px 8px rgba(30, 30, 30, 0.5);
			margin: 15px 1px 4px 1px;
			vertical-align: top;
		}
	
		.item[nivel="0"]{
			width:471px;
		}
		
		.item[nivel="1"]{
			width:calc(50% - 20px);
		}
		.item[nivel="2"] .item{
			width:calc(100% - 4px);
			margin-top:2px;
		}	
		.hijos{
			display: inline-block;
			width: calc(100% - 14px);
			min-width: 80px;
			min-height: 43px;
			border: 1px dashed #ebb;
			margin: 1px 1px 1px 10px;
			background-color: #fff;
		}	
	
		.hijos[nivel="0"]{
			background-color: rgba(255,255,255,0.5);
		}	
	
		
		
		.item h3{
			display:block;
			margin: 0 0 0 0px ;
			cursor:move;
		}
		.item p{
			cursor:move;
		}
	
	
		#editoritem{
			display:none;
			position:fixed;
			top:20vh;
			height:60vh;
			min-height:calc(10vh + 125px);		
			left:20vw;
			width:60vw;
			background-color:#fff;
			border:1px solid #08afd9;
			z-index:20;
			box-shadow:10px 10px 40px rgba(0,0,0,0.8);
		}
		#editoritem label, #editoritem input, #editoritem textarea{
			display:block;
			float:none;
			width:40vw;
			margin-left:10vw;
		}
		#editoritem label{
			margin-top:5vh;
		}
		#editoritem #botoncierra{
			position:absolute;
			width:auto;
			text-align:right;
			right:1vw;
			top:1vh;
		}
		#editoritem input[type='submit']{
			position:absolute;
			width:auto;
			text-align:right;
			right:1vw;
			top:4vh;
			background:transparent;
			border: none;
			margin:0;
			padding:0;
		}
		#editoritem input[type='submit']:hover{
			background-color:#08afd9;
			color:#000;	
		}
		
		#editoritem #botonelimina{
			color:red;
			position:absolute;
			width:auto;
			text-align:right;
			right:1vw;
			top:calc(7vh + 7px);
		}
		#editoritem a#botonelimina:hover{
			background-color:red;
			color:#000;	
		}
		#editoritem textarea{
			font-family:'Ropa Sans-local', 'Ropa Sans', sans-serif;
			font-size:12px;
			height:calc(60vh - 10vh - 70px);
			min-height:60px;
		}
		
		.item{
			background-color:#fff;
		}
		.item[resaltado="si"]{
			background-color:#49c;
		}
		div[destino='si']{		
			background-color:#047;
		}
		
		div.item[destinof='si']{		
			background-color:red;
		}
		
		
		#archivos > div#eliminar[destinof='si']{		
			background-color:#900;
		}

	</style>

</head>

<body>
	
	<script type="text/javascript" src="./js/jquery/jquery-1.12.0.min.js"></script>
	 
		
	<img id="fondo" src="./img/fondo.png">		
	<div id='tituloCapa'>
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
		</div>
	</div>
		
	<div id='velo'></div>
	
	<div id='elproyecto'>
		
		<a id='volver' href='./index.php'>vover al inicio</a>		
		<div id='menutablas'>
			<h1 id='tituloProyecto'>- nombre de proyecto -</h1>
			<p id='descripcion'>- descripcion de proyecto -</p>
		</div>	
		
		<div id='portamapa'>
			<div id='titulomapa'><p id='tnombre'></p><h1 id='tnombre_humano'></h1><p id='tdescripcion'></p><b><p id='tseleccion'></p></b></div>
		</div>
		
		<div id='modelos'>
			
			<div
				class='item'
				idit='nn'
				draggable="true"
				ondragstart="dragcaja(event);bloquearhijos(event,this);"
				ondragleave="limpiarAllowFile()"
				ondragover="allowDropFile(event,this)"
				ondrop='dropFile(event,this)'
			>
				<h3 onmouseout='desaltar(this)' onmouseover='resaltar(this)' onclick='editarI(this)'>nombre de la caja</h3>
				<p onmouseout='desaltar(this)' onmouseover='resaltar(this)' onclick='editarI(this)'>descipcion del contenido de la caja</p>
				<div class='documentos'>
				</div>
				<div 
					class='hijos'
					ondrop="drop(event,this)"
					ondragover="allowDrop(event,this)"
					ondragleave="limpiarAllow()" 
				></div>
			</div>
		</div>
		
		<div id="archivos">
			
			<form action='' enctype='multipart/form-data' method='post' id='uploader' ondragover='resDrFile(event)' ondragleave='desDrFile(event)'>
				<div id='contenedorlienzo'>									
					<div id='upload'>
						<label>Arrastre todos los archivos aquí.</label>
						<input multiple='' id='uploadinput' type='file' name='upload' value='' onchange='cargarCmp(this);'></label>
					</div>
				</div>
			</form>
			
			<div id="listadosubiendo">
				<label>archivos subiendo...</label>
			</div>
			
			<div id="listadoaordenar">
				<label>archivos subidos.</label>
			</div>
			
			<div id="eliminar"
				ondragover="allowDropFile(event,this)"
				ondragleave="limpiarAllowFile()"
				ondrop='dropTacho(event,this)'
			>
				<br>
				X
				<span>tacho de basura</span>
				
			</div>
			 <a id='botonanadir' onclick='anadirItem("0")'> <br>+ <br><span>nueva <br> caja</span></a>		
		</div>	
							
		<div id="contenidoextenso" idit='0'>
			
			<div 
				class='hijos'
				nivel="0"
				ondrop="drop(event,this)" 
				ondragover="allowDrop(event,this);resaltaHijos(event,this)" 
				ondragleave="desaltaHijos(this)" 
			></div>
		</div>
	</div>

	<form id="editoritem" onsubmit="guardarI(event,this)">
		<label>Nombre de la caja</label>
		<input name='nombre'>
		<input name='id' type='hidden'>
		<label>Descripcion del contenido de la caja</label>
		<textarea name='descripcion'></textarea>
		<a id='botoncierra' onclick='cerrar(this)'>cerrar</a>
		<input type='submit' value='guardar'>
		<a id='botonelimina' onclick='eliminarI(event,this)'>eliminar</a>
	</form>
	
	
<script type="text/javascript">

	<?php
	foreach($_SESSION["unmgeo"]["usuario"] as $k => $v){
		$dataU[$k]=utf8_encode($v);
	}
	?>	
	var _IdPro= '<?php echo $_GET['id'];?>';
	
	
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
		
	}

	function consultarProyecto(_idprocons){
		
		var parametros = {
			"id":_idprocons
		};
		$.ajax({
			data:  parametros,
			url:   './dbpgcon/con_proy.php',
			type:  'post',
			success:  function (response){
				var _res = $.parseJSON(response);
				console.log(_res);
				if(_res.res=='exito'){					
					document.querySelector('#tituloProyecto').innerHTML=_res.data.proyecto.nombre;
					document.querySelector('#descripcion').innerHTML=_res.data.proyecto.descripcion;
				}else{
					alert('error')
				}
			}
		});	
	
	}
	consultarProyecto(_IdPro);
	
</script>
<script type='text/javascript'>

	///funciones para cargar información base
		var _Items=Array();
		var _Orden=Array();
		
		
		function cargaBase(){	
			document.querySelector('#contenidoextenso > .hijos').innerHTML='';			
			document.querySelector('#listadosubiendo').innerHTML='<label>archivos subiendo...</label>';
			document.querySelector('#listadoaordenar').innerHTML='<label>archivos subidos.</label>';

			_parametros = {
				'idpro': _IdPro
			};
			
			$.ajax({
				data: _parametros,
				url:   './pro/app_docs_consulta.php',
				type:  'post',
				success:  function (response){
					var _res = $.parseJSON(response);
					console.log(_res);
					for(_nm in _res.mg){
						alert(_res.mg[_nm]);
					}
					if(_res.res=='exito'){
						_Items=_res.data.psdir;
						_Orden=_res.data.orden;								
						generarItemsHTML();		
						generarArchivosHTML();
					}else{
						alert('error dsfg');
					}
				}
			})	
		}
		cargaBase();
		
		
		function generarArchivosHTML(){
			if(_Items[0]!=undefined){
			if(Object.keys(_Items[0].archivos).length>0){
				for(_na in _Items[0].archivos){
					_dat=_Items[0].archivos[_na];
					console.log(_dat);
					_aaa=document.createElement('a');
					_aaa.innerHTML=_dat.nombre;
					_aaa.setAttribute('href',_dat.archivo);
					_aaa.setAttribute('download',_dat.nombre);
					_aaa.setAttribute('draggable',"true");
					_aaa.setAttribute('ondragstart',"dragFile(event)");
					_aaa.setAttribute('idfi',_dat.id);
					_aaa.setAttribute('class','archivo');					
					document.getElementById('listadoaordenar').appendChild(_aaa);
				}			
			}
			}
		}
		
		function generarItemsHTML(){
			//genera un elemento html por cada instancia en el array _Items
			for(_nO in _Orden.psdir){
				
				_ni=_Orden.psdir[_nO];
				
				_dat=_Items[_ni];
				_clon=document.querySelector('#modelos .item').cloneNode(true);
				
				_clon.setAttribute('idit',_dat.id);
				
				if(_dat.nombre==null){_dat.nombre='- caja sin nombre -';}
				
				_clon.querySelector('h3').innerHTML=_dat.nombre;
				if(_dat.descripcion==null){_dat.descripcion='- caja sin descripción -';}
				_clon.querySelector('p').innerHTML=_dat.descripcion;
				_clon.setAttribute('nivel',"1");
				
				for(_na in _dat['archivos']){
					_dar=_dat['archivos'][_na];
					_aa=document.createElement('a');
					
					_aa.innerHTML=_dar.nombre;
					_aa.setAttribute('href',_dar.archivo);
					_aa.setAttribute('download',_dar.nombre);
					_aa.setAttribute('draggable',"true");
					_aa.setAttribute('ondragstart',"dragFile(event)");
					_aa.setAttribute('idfi',_dar.id);
					_aa.setAttribute('class','archivo');
					_clon.querySelector('.documentos').appendChild(_aa);
				}
				
				
				document.querySelector('#contenidoextenso > .hijos').appendChild(_clon);
			}
			  
			//anida los itmes genreados unos dentro de otros
			for(_nO in _Orden.psdir){
				_ni=_Orden.psdir[_nO];
				_el=document.querySelector('#contenidoextenso > .hijos > .item[idit="'+_Items[_ni].id+'"]');
				
				if(_Items[_ni].id_p_pro_pseudocarpetas!='0'){
					_dest=document.querySelector('#contenidoextenso > .hijos .item[idit="'+_Items[_ni].id_p_pro_pseudocarpetas+'"] > .hijos');
					_niv=_dest.parentNode.getAttribute('nivel');
					_niv++;
					_dest.appendChild(_el);
				}
			}
				
			_itemscargados=document.querySelectorAll('#contenidoextenso > .hijos .item');
			
			for(_nni in _itemscargados){
				if(typeof _itemscargados[_nni]=='object'){
					_esp=document.createElement('div');				
					_esp.setAttribute('class','medio');
					_esp.innerHTML='<div class="submedio"></div>';
					_esp.setAttribute('ondragover',"allowDrop(event,this);resaltaHijos(event,this)");
					_esp.setAttribute('ondragleave',"desaltaHijos(this)");
					_esp.setAttribute('ondrop',"drop(event,this)");  
					
					_itemscargados[_nni].parentNode.insertBefore(_esp, _itemscargados[_nni]);
					
					
				}
			}
		}
		
	
	</script>
	
	<script type='text/javascript'>
	///funciones para editar y crear items
	
		function resaltar(_this){
			//realta el div del item al que pertenese un título o una descripcion
			
			_dests=document.querySelectorAll('[resaltado="si"]');
			for(_nn in _dests){
				if(typeof _dests[_nn]=='object'){
					_dests[_nn].removeAttribute('resaltado');
				}
			}
			_this.parentNode.setAttribute('resaltado','si');
			
		}
		function desaltar(_this){
			//realta el div del item al que pertenese un título o una descripcion
			_dests=document.querySelectorAll('[resaltado="si"]');
			for(_nn in _dests){
				if(typeof _dests[_nn]=='object'){
					_dests[_nn].removeAttribute('resaltado');
				}
			}
			
		}
		function editarI(_this){				
			//abre el formulario para edittar item
			_idit=_this.parentNode.getAttribute('idit');
			_form=document.querySelector('#editoritem');
			_form.style.display='block';
			_form.querySelector('input[name="nombre"]').value=_Items[_idit].nombre;
			_form.querySelector('input[name="id"]').value=_Items[_idit].id;
			_form.querySelector('[name="descripcion"]').value=_Items[_idit].descripcion;
		}
		
		
		function cerrar(_this){
			//cierra el formulario que lo contiene
			_this.parentNode.style.display='none';
		}
		
		function eliminarI(_event,_this){
			if (confirm("¿Eliminar item y sus archivos asociados? \n (los ítems anidados quedarán en la raiz)")==true){
				
				_event.preventDefault();
				
				var _this=_this;
				
				_parametros = {
					"id": _this.parentNode.querySelector('input[name="id"]').value,
					"accion": "borrar",
					"tipo": "item",
					"idpro": _IdPro
				};
				$.ajax({
					url:   './pro/app_docs_borraritem.php',
					type:  'post',
					data: _parametros,
					success:  function (response){
						var _res = $.parseJSON(response);
							console.log(_res);
						if(_res.res=='exito'){	
							cerrar(_this);
							cargaBase();
						}else{
							alert('error asfffgh');
						}
					}
				});
				//envía los datos para editar el ítem
				
			}
		}
		
		
		
		function guardarI(_event,_this){// ajustado geogec
			_event.preventDefault();
			console.log(_this);
			var _this=_this;
			_parametros = {
				"idpro": _IdPro,
				"id": _this.querySelector('input[name="id"]').value,
				"nombre": _this.querySelector('input[name="nombre"]').value,
				"descripcion": _this.querySelector('[name="descripcion"]').value
			};
			$.ajax({
				url:   './pro/app_docs_cambiaritem.php',
				type:  'post',
				data: _parametros,
				success:  function (response){
					var _res = $.parseJSON(response);
						console.log(_res);
					if(_res.res=='exito'){	
						cerrar(_this.querySelector('#botoncierra'));
						cargaBase();
					}else{
						alert('error asdfdasf');
					}
				}
			});
			//envía los datos para editar el ítem
			
		}
		
		function anadirItem(){//ajustado a geogec
			_parametros = {
				"idpro": _IdPro
			};
			
			$.ajax({
				url:   './pro/app_docs_crearitem.php',
				type:  'post',
				data: _parametros,
				success:  function (response){
					var _res = $.parseJSON(response);
					console.log(_res);
					for(_nm in _res.mg){
						alert(_res.mg[_nm]);
					}
					if(_res.res=='exito'){	
						cargaBase();
					}
				}
			})	
		}
		
		
	</script>	
	
	
	
	<script type='text/javascript'>
		///funciones para gestionar drop en el tacho
		function dropTacho(_event,_this){//ajustado geogec
			
			_event.stopPropagation();
    		_event.preventDefault();    		
    		
    		limpiarAllowFile();
    		
    		if(JSON.parse(_event.dataTransfer.getData("text")).tipo=='archivo'){
    		
    			if(confirm('¿Confirma que quiere eliminar el archivo del panel?')==true){
    				
    				_parametros={
				    	"idfi":JSON.parse(_event.dataTransfer.getData("text")).id,
				    	"tipo":JSON.parse(_event.dataTransfer.getData("text")).tipo,
				    	"idpro": _IdPro,
				    	"accion":'borrar'
				    };
				    
			 		$.ajax({
						url:   './pro/app_docs_borrararchivo.php',
						type:  'post',
						data: _parametros,
						success:  function (response){
							var _res = $.parseJSON(response);
								console.log(_res);
							if(_res.res=='exito'){	
								cargaBase();
							}else{
								alert('error asffsvrrfgh');
							}
						}
					});
    				
    			}
    			return;
    			
    		}else if(JSON.parse(_event.dataTransfer.getData("text")).tipo=='item'){
    			
    			if(confirm('¿Confirma que quiere eliminar el Item y todo su contenido?')==true){
    				
    				
    				
    			}
    			return;
    			
    		}
    		
		    var _DragData = JSON.parse(_event.dataTransfer.getData("text")).id;
		    console.log(_DragData);
		    _el=document.querySelector('.archivo[idfi="'+_DragData+'"]');
		    
		    _ViejoIdIt=_el.parentNode.parentNode.getAttribute('idfi');
		    _em=_el.nextSibling;
		    _idit=_this.getAttribute('idit');
		    _ref=document.querySelector('.item[idit="'+_idit+'"] .documentos');
		    _ref.appendChild(_el);

		 }
		
		
	</script>
		
	<script type='text/javascript'>
		///funciones para gestionar drag y drop de archivos
			
		function dragFile(_event){
			//alert(_event.target.getAttribute('idit'));
			_event.stopPropagation();
    		_arr=Array();
			_arr={
				'id':_event.target.getAttribute('idfi'),
				'tipo':'archivo'
			};
			_arb = JSON.stringify(_arr);
    		_event.dataTransfer.setData("text", _arb);
		}
		
		function allowDropFile(_event,_this){
			_event.stopPropagation();
			//console.log(_this.parentNode.getAttribute('idit'));
			//console.log(_event.dataTransfer);
			if(_event.dataTransfer.items[0].kind=='file'){return;}
			if(JSON.parse(_event.dataTransfer.getData("text")).tipo!='archivo'){
				return;
			}
			
			limpiarAllowFile();
			_event.stopPropagation();
			_this.setAttribute('destinof','si');
			_event.preventDefault();
		}
		
		function limpiarAllowFile(){
			_dests=document.querySelectorAll('[destinof="si"]');
			for(_nn in _dests){
				if(typeof _dests[_nn]=='object'){
					_dests[_nn].removeAttribute('destinof');
				}
			}
		}
		function dropFile(_event,_this){// ajustado a geogec
			_event.stopPropagation();
    		_event.preventDefault();
    		    		
    		if(JSON.parse(_event.dataTransfer.getData("text")).tipo!='archivo'){
				return;
			}
    		
		    var _DragData = JSON.parse(_event.dataTransfer.getData("text")).id;
		    
		    _el=document.querySelector('.archivo[idfi="'+_DragData+'"]');
		    
		    //console.log(_DragData);
		   
		    if(_event.target.getAttribute('class')=='hijos'){	
		    	_tar=_event.target;
		    	 _idit=_this.parentNode.getAttribute('idit');
		    	_dest=_tar.parentNode.querySelector('.item[idit="'+_idit+'"] .documentos'); 
		    }else{
		    	 _idit=_this.getAttribute('idit');
		    	 _ViejoIdIt=_el.parentNode.parentNode.getAttribute('idfi');
		    	_dest=document.querySelector('.item[idit="'+_idit+'"] .documentos');
		    }
		    
		    _dest.appendChild(_el);
		    		    			    
		    _parametros={
		    	"idpro": _IdPro,
		    	"id":_DragData,
		    	"id_anidadoen":_idit
		    };
		    
	 		$.ajax({
				url:   './pro/app_docs_localizararchivo.php',
				type:  'post',
				data: _parametros,
				success:  function (response){
					var _res = $.parseJSON(response);
						console.log(_res);
					if(_res.res=='exito'){	
						cargaBase();
					}else{
						alert('error asdfdsf');
					}
				}
			});
		    
		  }
	</script>
		
	<script type='text/javascript'>
		///funciones para gestjionar drag y drop de items
		
		function allowDrop(_event,_this){
			//console.log(_this.parentNode.getAttribute('idit'));
			
			console.log(_event.dataTransfer);
			
			if(JSON.parse(_event.dataTransfer.getData("text")).tipo!='item'){
				return;
			}
			
			limpiarAllow();
			
			_event.stopPropagation();
			_this.setAttribute('destino','si');
			_event.preventDefault();
			
		}
		
		function limpiarAllow(){
			_dests=document.querySelectorAll('[destino="si"]');
			for(_nn in _dests){
				if(typeof _dests[_nn]=='object'){
					_dests[_nn].removeAttribute('destino');
				}
			}
		}
		
		function resaltaHijos(_event,_this){
			//realta el div del item al que pertenese un título o una descripcion
			//_this.style.backgroundColor='lightblue';
			_dests=document.querySelectorAll('[destino="si"]');
			for(_nn in _dests){
				if(typeof _dests[_nn]=='object'){
					_dests[_nn].removeAttribute('destino');
				}
			}
			_this.setAttribute('destino','si');
			_event.stopPropagation();
			
		}
		function desaltaHijos(_this){
			//realta el div del item al que pertenese un título o una descripcion
			//_this.style.backgroundColor='#fff';
			_this.removeAttribute('destino');
			_this.parentNode.removeAttribute('destino');
		}
		
		
		function dragcaja(_event){			
			//alert(_event.target.getAttribute('idit'));
			_arr=Array();
			_arr={
				'id':_event.target.getAttribute('idit'),
				'tipo':'item'
			};		
			_arb = JSON.stringify(_arr);

    		_event.dataTransfer.setData("text", _arb);
		}
		
		function bloquearhijos(_event,_this){			
			_idit=JSON.parse(_event.dataTransfer.getData("text")).id;
    		_negados = _this.querySelectorAll('.item[idit="'+_idit+'"] .hijos, .item[idit="'+_idit+'"] .medio');   
    		 		
    		for(_nn in _negados){
    			if(typeof _negados[_nn] == 'object'){
    				_negados[_nn].setAttribute('destino','negado');
    			}
    		}
		}
		
		function desbloquearhijos(_this){
    		_negados=document.querySelectorAll('[destino="negado"]');
    		for(_nn in _negados){
    			if(typeof _negados[_nn] == 'object'){
    				_negados[_nn].removeAttribute('destino');
    			}
    		}
		}	
		
			
		function drop(_event,_this){//ajustado a geogec
			
			_event.stopPropagation();
    		_this.removeAttribute('style');
    		_this.removeAttribute('destino');
    		
    		_event.preventDefault();
    		
    		if(JSON.parse(_event.dataTransfer.getData("text")).tipo=='archivo'){
    			dropFile(_event,_this);
				return;
			}
    		
		    var _DragData = JSON.parse(_event.dataTransfer.getData("text")).id;
		    console.log('u');
		    console.log(_event.dataTransfer.getData("text"));
		    
		    _el=document.querySelector('.item[idit="'+_DragData+'"]');
		    _ViejoIdIt=_el.parentNode.parentNode.getAttribute('idit');
		    _em=_el.previousSibling;
		        
		    _evitar='no';//evita destinos erronos por jerarquia.

		    
		    if(_event.target.getAttribute('class')=='medio'||_event.target.getAttribute('class')=='submedio'){
		    	
		    	if(_event.target.getAttribute('class')=='submedio'){
		    		_tar=_event.target.parentNode;
		    	}else{
		    		_tar=_event.target;
		    	}
		    	
		    	_dest=_tar.parentNode; 
			    _dest.insertBefore(_el,_tar);			    
			    _dest.insertBefore(_em,_el);
			    
		    }else if(_event.target.getAttribute('class')=='hijos'){
		    	
		    	_dest=_event.target;

			    _dest.appendChild(_el);
			    _dest.insertBefore(_em,_el);
		    	
		    	
		    }else{
		    	alert('destino inesperado');
		    	
		    	return;
		    	
		    }
		    
		    _niv=_dest.parentNode.getAttribute('nivel');
		    _niv++;
		    _el.setAttribute('nivel',_niv.toString());
		    		    
		    _NuevoIdIt=_dest.parentNode.getAttribute('idit');
		    
		    _enviejo=document.querySelectorAll('[idit="'+_ViejoIdIt+'"] > .hijos > .item');
		    _serieviejo='';
		    for(_ni in _enviejo){
		    	if(typeof _enviejo[_ni]=='object'){
		    		_serieviejo+=_enviejo[_ni].getAttribute('idit')+',';
		    	}
		    }
		    
		    console.log(_NuevoIdIt);
		    _ennuevo=document.querySelectorAll('[idit="'+_NuevoIdIt+'"] > .hijos > .item');
		    _serienuevo='';
		    for(_ni in _ennuevo){
		    	console.log(_ennuevo[_ni]);
		    	if(typeof _ennuevo[_ni]=='object'){
		    		_serienuevo+=_ennuevo[_ni].getAttribute('idit')+',';
		    	}
		    }
		   
		    _parametros={
		    	"idpro": _IdPro,
		    	"id":_DragData,
		    	"id_anidado":_NuevoIdIt,
		    	"viejoAnidado":_ViejoIdIt,
		    	"viejoAserie":_serieviejo,
		    	"nuevoAnidado":_NuevoIdIt,
		    	"nuevoAserie":_serienuevo
		    };
		    
	 		$.ajax({
				url:   './pro/app_docs_anidaritem.php',
				type:  'post',
				data: _parametros,
				success:  function (response){
					var _res = $.parseJSON(response);
						console.log(_res);
					if(_res.res=='exito'){	
						cargaBase();
					}else{
						alert('error asfffgh');
					}
				}
			});
			//envía los datos para editar el ítem
		}
		
	
		
	</script>
	
	<script type='text/javascript'>
	///funciones para guardar archivos
	
		function resDrFile(_event){
			//console.log(_event);
			document.querySelector('#archivos #contenedorlienzo').style.backgroundColor='lightblue';
		}	
		
		function desDrFile(_event){
			//console.log(_event);
			document.querySelector('#archivos #contenedorlienzo').removeAttribute('style');
		}
		
		var _nFile=0;
		
		var xhr=Array();
		var inter=Array();
		function cargarCmp(_this){
			
			var files = _this.files;
					
			for (i = 0; i < files.length; i++) {
		    	_nFile++;
		    	console.log(files[i]);
				var parametros = new FormData();
				parametros.append('nfile',_nFile);
				parametros.append('idpro',_IdPro);
				parametros.append('upload',files[i]);
				
				
				var _nombre=files[i].name;
				_upF=document.createElement('a');
				_upF.setAttribute('nf',_nFile);
				_upF.setAttribute('class',"archivo");
				_upF.setAttribute('size',Math.round(files[i].size/1000));
				_upF.innerHTML=files[i].name;
				document.querySelector('#listadosubiendo').appendChild(_upF);
				
				_nn=_nFile;
				xhr[_nn] = new XMLHttpRequest();
				xhr[_nn].open('POST', './pro/app_docs_guardaarchivo.php', true);
				xhr[_nn].upload.li=_upF;
				xhr[_nn].upload.addEventListener("progress", updateProgress, false);
				
				
				xhr[_nn].onreadystatechange = function(evt){
					//console.log(evt);
					
					if(evt.explicitOriginalTarget.readyState==4){
						var _res = $.parseJSON(evt.explicitOriginalTarget.response);
						//console.log(_res);
						
						alert('terminó '+_res.data.nf);
						
						if(_res.res=='exito'){							
							_file=document.querySelector('#listadosubiendo > a[nf="'+_res.data.nf+'"]');								
							document.querySelector('#listadoaordenar').appendChild(_file);
							_file.setAttribute('href',_res.data.ruta);
							_file.setAttribute('download',_file.innerHTML);
							_file.setAttribute('draggable',"true");
							_file.setAttribute('ondragstart',"dragFile(event)");
							_file.setAttribute('idfi',_res.data.nid);
							
						}else{
							_file=document.querySelector('#listadosubiendo > a[nf="'+_res.data.nf+'"]');
							_file.innerHTML+=' ERROR';
							_file.style.color='red';
						}
						//cargaTodo();
						//limpiarcargando(_nombre);
					}
				}
				xhr[_nn].send(parametros);
			}			
		}

		function updateProgress(evt) {
			if (evt.lengthComputable) {
				var percentComplete = 100 * evt.loaded / evt.total;		   
				this.li.style.width="calc("+Math.round(percentComplete)+"% - ("+Math.round(percentComplete)/100+" * 6px))";
			} else {
				// Unable to compute progress information since the total size is unknown
			} 
		}

	</script>
	

</body>

