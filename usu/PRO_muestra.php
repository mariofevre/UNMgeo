<?php 
/**
* PRO_muestra.php
*
* muestra y permite gestionar un proyeto de dearrollo de la plataforma
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
		body{
			overflow: hidden;
		}
		#velo{
			width:100vw;
			height:100vh;
			position:absolute;
			background-color:#e9ddaf;
			opacity:0;
			transition: opacity 0.2s ease-in-out;
		}
		#volver{
			position: absolute;
			top: 1vh;
			right: 1vw;
			 display: inline-block;
		    border: 1px solid rgba(200,55,55,1);
		    background-color: rgba(220,120,120,0.9);
		    border-radius: 3px;
		    box-shadow: 2px 2px 5px rgba(0,0,0,0.5);
		    margin: 1vh;
		    z-index: 2;
		
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
		
		#gResp, #gArch{
			display:none;
		}

	
		h2{
			margin-top:2vh;			
			margin-bottom:1vh;
		}			
		h3{
			margin-top:1.5vh;			
			margin-bottom:0px;
		}
					
		h4{
			margin-top:0.5vh;			
			margin-bottom:0px;
		}

		#responsables{
			margin-left:2vw;
		}		
		#responsables p{
			margin:0;
			margin-left:2vw;
		}
		
		#responsables > div.res{
			width: auto;
			display: inline-block;
			margin-top:0.5vw;
			margin-right:0.5vw;
			border:1px solid rgba(200,55,55,0.3);
			padding:0.2vw;
		}
		#responsables > div.res #cargo{
			font-weight:bold;
			font-size:70%;
		}
		
		#aplicacionestitulo{
			display:none;
		}		
		#aplicaciones{
			margin-left:2vw;
			display:none;
		}				
		#aplicaciones p{
			margin:0;
		}		
		#aplicaciones > a.app:hover{
			background-color:rgba(255,102,0,1);
		}		
		#aplicaciones > .app{
			width: 45%;
			display: inline-block;
			margin-top:1vw;
			margin-right:2vw;
			border:1px solid rgba(255,102,0,1);
			padding:0.5vw;
			background-color:rgba(255,102,0,0.4);
		}

		
		#capastitulo{
			display:none;
		}		
		#capas{
			margin-left:2vw;
			display:none;
		}	
		#capas p{
			margin:0;
		}
		#capas > a.cap:hover{
			background-color:rgba(80,221,204,1);
		}		
		#capas > .cap{
			width: 45%;
			display: inline-block;
			margin-top:1vw;
			margin-right:2vw;
			border:1px solid rgba(80,221,204,1);
			padding:0.5vw;
			background-color:rgba(80,221,204,0.4);
		}

		
		#documentos{
			margin-left:2vw;
		}		
		#documentos p{
				margin:0;
		}
		
		#documentos > .hijos > .item{
			width: 45%;
			display: inline-block;
			margin-top:1vw;
			margin-right:2vw;
			border:1px solid rgba(200,55,55,0.3);
			padding:0.5vw;
		}
		
		#documentos .documentos .archivo{
			font-size:12px;
			border: 1px solid rgba(200,55,55,0.3);
			margin:2px;
			padding-left:2px;
			padding-right:2px;
			display:inline-block;
		}
		
		#documentos .documentos a.archivo:hover{
			background-color:rgba(200,55,55,0.8);
		}
			
		#elproyecto{
		    overflow-y: auto;
		}	
		

	#gCapa{
		display:none;
	}
	
	
	#editorresp {
	    display: none;
	    position: fixed;
	    top: 20vh;
	    height: 60vh;
	    min-height: calc(10vh + 125px);
	    left: 20vw;
	    width: 650px;
	    background-color: #fff;
	    border: 1px solid #08afd9;
	    z-index: 20;
	    box-shadow: 10px 10px 40px rgba(0,0,0,0.8);
	}
	#editorresp #botoncierra {
	    position: absolute;
	    width: auto;
	    text-align: right;
	    right: 1vw;
	    top: 1vh;
	}	
	#editorresp > div{
		vertical-align:top;
		display:inline-block;
		width:200px;
		border: 1px dashed #08afd9;
		margin:5px;
		margin-bottom:1px;
		margin-top:1px;	
		background-color:#fff;
		font-size: 15px;
		text-align:center;
	}
	#editorresp > div#incluidos{
		width:410px;
	}
	#editorresp > div#excluidos, #editorresp > div#incluidos{
		min-height:100px;
	}
	#editorresp > div#tituloincluidos{
		margin-right:0;
	}
	#editorresp > div#tituloresponsabilidad{
		margin-left:0;
	}
	#editorresp > div#excluidos div{
		color:#666;
		cursor:pointer;
	}
	#editorresp > div#excluidos div:hover{
		color:#000;
		background-color:#08afd9;
	}
	#editorresp > div#excluidos input{
		display:none;
	}
	#editorresp > div#incluidos div{
		cursor:pointer;
	}
	#editorresp > div#incluidos div:hover{
		color:#000;
		background-color:#08afd9;
	}	
	#editorresp > div#incluidos div span{
		display:inline-block;
		width:200px;
	}		
	#editorresp > div#incluidos div input{
		margin:0px;
		width:200px;
	}	
	#editorresp > div#incluidos div input[estado='guardando']{
		color:#d90;
		background-color:#fc9;
	}
	#editorresp > div#incluidos div input[estado='editando']{
		color:#d00;
		background-color:#f99;
	}				
	#editorresp > div#incluidos div[estado="excluido"]{
		display:none;
	}
	#editorresp > div#excluidos div[estado="incluido"]{
		display:none;
	}
	
	
	
	#crearcapa {
	    display: none;
	    position: fixed;
	    top: 20vh;
	    height: 60vh;
	    min-height: calc(10vh + 125px);
	    left: 20vw;
	    width: 650px;
	    background-color: #fff;
	    border: 1px solid #08afd9;
	    z-index: 20;
	    box-shadow: 10px 10px 40px rgba(0,0,0,0.8);
	}
	#crearcapa #botoncierra {
	    position: absolute;
	    width: auto;
	    text-align: right;
	    right: 1vw;
	    top: 1vh;
	}	
	#crearcapa > div{
		vertical-align:top;
		display:inline-block;
		width:200px;
		border: 1px dashed #08afd9;
		margin:5px;
		margin-bottom:1px;
		margin-top:1px;	
		background-color:#fff;
		font-size: 15px;
		text-align:center;
	}

	#crearcapa > div#tituloincluidos{
		margin-right:0;
	}
	#crearcapa > div#tituloresponsabilidad{
		margin-left:0;
	}
	#crearcapa > input{
		width:90%;
		margin-left:5%
		font-size: 15px;
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
		<h2 id='tituloProyecto'><span id='texto'>Cargando...</span><a id='gpro' onclick='cargarFormularioProyecto()'>modificar datos del proyecto</a></h2>
						
		<p id='descripcion'></p>
		
		
		<h3 id='capastitulo'>Capas producidas <a id='gCapa' onclick='sumarCapa(this)'>añadir nuevas capas</a></h3>
		<div id='capas'>-sin datos disponibles-</div>		
		
		<h3 id='aplicacionestitulo'>Aplicaciones producidas</h3>
		<div id='aplicaciones'>-sin datos disponibles-</div>
		
		<h3 id='responsablestitulo'>Responsables de este proyecto (<span id='respcant'>?</span>) <a id='gResp' onclick='sumarResp(this)'>Gestionar responsables</a></h3>
		<div id='responsables'>-sin datos disponibles-</div>
		
		<h3>Documentos disponibles (<span id='doccant'>?</span>) <a id='gArch' href='./PRO_documentos.php'>Gestionar archivos</a></h3>
		<div id='documentos'>-sin datos disponibles-</div>
		
		<div id='resultados'></div>
	</div>
				

	<div id='modelos'>
		<div class='item' idit='nn'>
			<h4>nombre de la caja</h4>
			<p >descipcion del contenido de la caja</p>
			<div class='documentos'>-sin documentos por el momento-</div>
			<div class='hijos'></div>
		</div>
		<div class='res' idres='nn' target='blank'>
			<span id='cargo'></span>: <span id='nombre'></span>
		</div>		
		<a class='app' idapp='nn' target='blank'>
			<h4 id='nombre'>nombre de la aplicación</h4>
			<p id='descripcion'>descripcion del contenido de la caja</p>
			<p>estado: <span id='estado'>-sin documentos por el momento-</span></p>
		</a>
		<a class='cap' idcap='nn' target='blank'>
			<h4 id='nombre'>nombre de la capa</h4>
			<p id='descripcion'>descripcion de la capa</p>
		</a>		
	</div>
	
	<form id="editorresp">
		<a id='botoncierra' onclick='cerrar(this)'>cerrar</a>
		<h1>Nombre de la Unidad de Planificación</h1>
		<div id="tituloexcluidos">usuarios sin responsabilidades asignadas.</div>
		<div id="tituloincluidos">usuarios con responsabilidades asignadas.</div>
		<div id="tituloresponsabilidad">responsabilidad <br> asignada</div>
		<div id="excluidos"></div>
		<div id="incluidos"></div>
		<input name='idit' type='hidden'>
	</form>
	
	<form id="crearcapa" onsubmit="crearCapa(event,this)">
		<a id='botoncierra' onclick='cerrar(this)'>cerrar</a>		
		<h2>Nombre de la nueva capa</h2>
		<input name='nombre'>
		<h2>Codigo (abreviatura del nombre)</h2>
		<input name='codigo'>
		<h2>Descripción</h2>
		<input name='descripcion'>
		<input name='idit' type='hidden'>
		<input type='submit' value='crear'>
	</form>
	</div>	
	<?php
	include("./pro/pro_form.php");
	?>	
<script type="text/javascript">

	<?php
	foreach($_SESSION["unmgeo"]["usuario"] as $k => $v){
		$dataU[$k]=utf8_encode($v);
	}
	?>	
	var _IdPro= '<?php echo $_GET['id'];?>';
	var _Proyecto={};
	
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
					_Proyecto=_res.data.proyecto;					
					document.querySelector('#tituloProyecto span').innerHTML=_res.data.proyecto.nombre;
					document.querySelector('#descripcion').innerHTML=_res.data.proyecto.descripcion;
					
					
					_resp='no';
					if(_UsuarioA.sis_adm==1){
						_resp='si';
					}
					for(_nr in _res.data.responsables){
						if(_res.data.responsables[_nr].id_p_usu_usuarios==_UsuarioA.id){
							_resp='si';
							continue;
						}
					}

					if(_resp=='si'){
						document.querySelector('#gCapa').style.display='inline-block';	
						document.querySelector('#gResp').style.display='inline-block';						
						document.querySelector('#gArch').style.display='inline-block';
						document.querySelector('#gArch').setAttribute('href','./PRO_documentos.php?id='+_idprocons);
						document.querySelector('#capas').style.display='block';
						document.querySelector('#capastitulo').style.display='block';
					}

					//carga responsables
					if(Object.keys(_res.data.responsables).length>0){
						document.querySelector('#respcant').innerHTML=Object.keys(_res.data.responsables).length;
						document.querySelector('#responsables').innerHTML='';
						document.querySelector('#responsablestitulo').style.display='block';	
					}

					for(_idr in _res.data.responsables){
						_datRes=_res.data.responsables[_idr];
						_clon=document.querySelector('#modelos > .res').cloneNode(true);
						_clon.querySelector('#cargo').innerHTML=_datRes.cargo;
						_clon.querySelector('#nombre').innerHTML=_datRes.nombre+' '+_datRes.apellido;
						document.querySelector('#responsables').appendChild(_clon);
					}

					//carga capas del proyecto
					if(Object.keys(_res.data.capas).length){
						document.querySelector('#capas').style.display='block';
						document.querySelector('#capas').innerHTML='';						
						document.querySelector('#capastitulo').style.display='block';
								
					}
					for(_idc in _res.data.capas){
						_datCap=_res.data.capas[_idc];
						_clon=document.querySelector('#modelos > .cap').cloneNode(true);
						_clon.setAttribute('href','./CAP_muestra.php?id='+_datCap.id);
						_clon.querySelector('#nombre').innerHTML=_datCap.nombre;
						_clon.querySelector('#descripcion').innerHTML=_datCap.descripcion;
						document.querySelector('#capas').appendChild(_clon);
					}
					
					//carga aplicaciones del proyecto
					if(Object.keys(_res.data.aplicaciones).length>0){
						document.querySelector('#aplicaciones').innerHTML='';
						document.querySelector('#aplicaciones').style.display='block';	
						document.querySelector('#aplicacionestitulo').style.display='block';	
					}
					for(_ida in _res.data.aplicaciones){
						_datApp=_res.data.aplicaciones[_ida];
						_clon=document.querySelector('#modelos > .app').cloneNode(true);
						_clon.setAttribute('href',_datApp.url);
						_clon.querySelector('#nombre').innerHTML=_datApp.nombre;
						_clon.querySelector('#descripcion').innerHTML=_datApp.descripcion;
						_clon.querySelector('#estado').innerHTML=_datApp.e_nombre;
						document.querySelector('#aplicaciones').appendChild(_clon);
					}

				}else{
					alert('error')
				}
			}
		});	
	
	}
	consultarProyecto(_IdPro);
	
	function cargarFormularioProyecto(){
	
		_form=document.querySelector('#pro_form');
		_form.style.display='block';
		_form.querySelector('input[name="id"]').value=_Proyecto.id;
		_form.querySelector('input[name="accion"]').value='cambiar';
		_form.querySelector('input[type="submit"]').value='cambiar';
		_form.querySelector('input[name="nombre"]').value=_Proyecto.nombre;
		_form.querySelector('textarea[name="descripcion"]').value=_Proyecto.descripcion;
		if(_Proyecto.visible=='1'){
			_form.querySelector('input[for="visible"]').checked=true;
			_form.querySelector('input[name="visible"]').value='1';
		}else{
			_form.querySelector('input[for="visible"]').checked=false;
			_form.querySelector('input[name="visible"]').value='0';
		}
	}
</script>
<script type="text/javascript">
// carga documentos y carpetas
		function cargaBase(){
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
						generarArchivosHTML(_res);
						generarItemsHTML(_res);		
						
					}else{
						alert('error dsfg');
					}
				}
			})	
		}
		cargaBase();

		
		function generarArchivosHTML(_res){
			document.getElementById('doccant').innerHTML=_res.data.totalArchivos;
			_Items=_res.data.psdir;
			_Orden=_res.data.orden;
			
			document.getElementById('documentos').innerHTML='<div class="hijos" nivel="0"></div>';
			
			if(_Items[0]!=undefined){
				if(Object.keys(_Items[0].archivos).length>0){
					for(_na in _Items[0].archivos){
						_dat=_Items[0].archivos[_na];
						console.log(_dat);
						_aaa=document.createElement('a');
						_aaa.innerHTML=_dat.nombre;
						_aaa.setAttribute('href',_dat.archivo);
						_aaa.setAttribute('download',_dat.nombre);
						_aaa.setAttribute('idfi',_dat.id);
						_aaa.setAttribute('class','archivo');					
						document.getElementById('documentos').appendChild(_aaa);
					}			
				}
			}
		}
		
		function generarItemsHTML(_res){
			_Items=_res.data.psdir;
			_Orden=_res.data.orden;			
			//genera un elemento html por cada instancia en el array _Items
			for(_nO in _Orden.psdir){
				_ni=_Orden.psdir[_nO];				
				_dat=_Items[_ni];
				_clon=document.querySelector('#modelos .item').cloneNode(true);
				
				_clon.setAttribute('idit',_dat.id);
				
				if(_dat.nombre==null){_dat.nombre='- caja sin nombre -';}
				
				_clon.querySelector('h4').innerHTML=_dat.nombre;
				if(_dat.descripcion==null){_dat.descripcion='- caja sin descripción -';}
				_clon.querySelector('p').innerHTML=_dat.descripcion;
				_clon.setAttribute('nivel',"1");
				
				
				if(Object.keys(_dat['archivos']).length>0){
					_clon.querySelector('.documentos').innerHTML='';
				}
				
				for(_na in _dat['ordenarchivos']){
					_idar=_dat['ordenarchivos'][_na]
					_dar=_dat['archivos'][_idar];
					_aa=document.createElement('a');					
					_aa.innerHTML=_dar.nombre;
					_aa.setAttribute('href',_dar.archivo);
					_aa.setAttribute('download',_dar.nombre);
					_aa.setAttribute('idfi',_dar.id);
					_aa.setAttribute('class','archivo');
					_clon.querySelector('.documentos').appendChild(_aa);
				}		
				
				document.querySelector('#documentos > .hijos').appendChild(_clon);
			}
			  /*
			//anida los itmes genreados unos dentro de otros
			for(_nO in _Orden.psdir){
				_ni=_Orden.psdir[_nO];
				_el=document.querySelector('#documentos > .hijos > .item[idit="'+_Items[_ni].id+'"]');
				
				if(_Items[_ni].id_p_pro_pseudocarpetas!='0'){
					//alert(_Items[_ni].id_p_ESPitems_anidado);
					_dest=document.querySelector('#documentos > .hijos .item[idit="'+_Items[_ni].id_p_pro_pseudocarpetas+'"] > .hijos');
					_niv=_dest.parentNode.getAttribute('nivel');
					_niv++;
					_dest.appendChild(_el);
				}
			}*/

		}
</script>


	<script type='text/javascript'>
	///funciones para asignar responsables	
	
	function cerrar(_this){
		_this.parentNode.style.display='none';
	}
	function sumarResp(_this){

		var _this=_this;
		
		_parametros = {
			"idpro": _IdPro
		};
		$.ajax({
			url:   './dbpgcon/con_usuarios.php',
			type:  'post',
			data: _parametros,
			success:  function (response){
				var _res = $.parseJSON(response);
					console.log(_res);
				if(_res.res=='exito'){
					formResponsables(_res);
				}else{
					alert('error asfffgh');
				}
			}
		});	

	}
			 
	function formResponsables(_usuarios){
			//abre el formulario para edittar item

			_nom=document.querySelector('#tituloProyecto').innerHTML;
			_des=document.querySelector('#descripcion').innerHTML;
			
			_form=document.querySelector('#editorresp');
			_form.querySelector('#excluidos').innerHTML='';
			_form.querySelector('#incluidos').innerHTML='';			
			_form.style.display='block';
			_form.querySelector('input[name="idit"]').value=_IdPro;			
			_form.querySelector('h1').innerHTML=_nom;	
			for(_nu in _usuarios.data){
				_div=document.createElement('div');
				_div.setAttribute('idusu',_usuarios.data[_nu].id);
				_div.setAttribute('estado','excluido');
				_span=document.createElement('span');
				_span.setAttribute('onclick','togleResp(this.parentNode)');
				_span.innerHTML=_usuarios.data[_nu].apellido+', '+_usuarios.data[_nu].nombre;
				_div.appendChild(_span);
				
				_input=document.createElement('input');
				_input.setAttribute('id','responsabilidad');
				_input.setAttribute('estado','ok');
				_input.setAttribute('onkeypress','cargaResponsabilidad(event,this)');
				_div.appendChild(_input);	
						
				_form.querySelector('#excluidos').appendChild(_div);
				
				_clon=_div.cloneNode(true);
				_form.querySelector('#incluidos').appendChild(_clon);
			}	
			
			cargarResponsablesenForm(_IdPro);
	}
	
	function cargarResponsablesenForm(_idit){
		_parametros={
			"idpro":_IdPro,
		};
		console.log(_parametros);
		$.ajax({
				data: _parametros,
				url:   './dbpgcon/con_proy_resp.php',
				type:  'post',
				success:  function (response){
					var _res = $.parseJSON(response);
					console.log(_res);
					
					for(_nn in _res.mg){
						alert(_res.mg[_nn]);
					}
					if(_res.res='exito'){		
						for(_idusu in _res.data.resp){
							_dat=_res.data.resp[_idusu];
							
							if(_dat.zz_borrada=='0'){
								document.querySelector('#incluidos div[idusu="'+_idusu+'"]').setAttribute('estado','incluido');
								document.querySelector('#excluidos div[idusu="'+_idusu+'"]').setAttribute('estado','incluido');
							}else{
								document.querySelector('#incluidos div[idusu="'+_idusu+'"]').setAttribute('estado','excluido');
								document.querySelector('#excluidos div[idusu="'+_idusu+'"]').setAttribute('estado','excluido');
							}
							
							document.querySelector('#incluidos div[idusu="'+_idusu+'"] input').value=_dat.cargo;
							document.querySelector('#excluidos div[idusu="'+_idusu+'"] input').value=_dat.cargo;
							
						}
					}			
				}
			});		
	}	
		
	
	
	function togleResp(_this,_usuarios){
		
		if(_this.parentNode.getAttribute('id')=='incluidos'){
			_nuevoestado='excluido';
		}else{
			_nuevoestado='incluido';
		}
		_this.parentNode.parentNode.querySelector('#excluidos div[idusu="'+_this.getAttribute('idusu')+'"]').setAttribute('estado',_nuevoestado);
		_this.parentNode.parentNode.querySelector('#incluidos div[idusu="'+_this.getAttribute('idusu')+'"]').setAttribute('estado',_nuevoestado);
		
		_idit=_this.parentNode.parentNode.querySelector('input[name="idit"]').value;
		_responsabilidad=_this.parentNode.parentNode.querySelector('#incluidos div[idusu="'+_this.getAttribute('idusu')+'"] input').value;
		
		guardarCambiosResp(_this.getAttribute('idusu'),_nuevoestado,_responsabilidad);
	}
	
	function guardarCambiosResp(_idusu,_nuevoestado,_responsabilidad){
		var _idusu=_idusu;
		_parametros={
			"idpro":_IdPro,
			"idusu":_idusu,
			"nuevoestado":_nuevoestado,
			"responsabilidad":_responsabilidad
		};
		$.ajax({
				data: _parametros,
				url:   './pro/ed_proy_resp.php',
				type:  'post',
				success:  function (response){
					var _res = $.parseJSON(response);
					console.log(_res);
					
					for(_nn in _res.mg){
						alert(_res.mg[_nn]);
					}
					if(_res.res='exito'){						
						document.querySelector('#incluidos div[idusu="'+_res.data.idusu+'"] input').setAttribute('estado','ok');
						//_Items[_res.data.idit]['responsables'][_res.data.idusu]=_res.data;
						//cargaBase('actualizaresponsables');
						consultarProyecto(_IdPro);
					}
				}
			});			
	}
	
	function cargaResponsabilidad(_event,_this){
		console.log(_event.keyCode);
		if(_event.keyCode==9){return;}//tab
		if(_event.keyCode>=33&&_event.keyCode<=40){return;}//direccionales
		_this.setAttribute('estado','editando');
		if(_event.keyCode==13){
			_this.setAttribute('estado','guardando');
			_idusu=_this.parentNode.getAttribute('idusu');
			_idit=_this.parentNode.parentNode.parentNode.querySelector('input[name="idit"]').value;
			_nuevoestado='incluido';
			_responsabilidad=_this.value;
			guardarCambiosResp(_idusu,_nuevoestado,_responsabilidad);
	
		}
	}
</script>





<script type='text/javascript'>
	///funciones para crear capas
	
	function cerrar(_this){
		_this.parentNode.style.display='none';
	}
	function sumarCapa(_this){

		_form=document.querySelector('#crearcapa');
		_form.querySelector('[name="nombre"]').value='';
		_form.querySelector('[name="descripcion"]').value='';
		_form.querySelector('[name="codigo"]').value='';	
		_form.style.display='block';
		_form.querySelector('input[name="idit"]').value=_IdPro;		

	}
	
	function crearCapa(_event,_this){
		_event.preventDefault();
		
		_parametros={
			"idpro":_IdPro,
			"nombre":_this.querySelector('[name="nombre"]').value,
			"codigo":_this.querySelector('[name="codigo"]').value,
			"descripcion":_this.querySelector('[name="descripcion"]').value
		};
		$.ajax({
				data: _parametros,
				url:   './cap/ed_capa_crear.php',
				type:  'post',
				arror:  function (response){alert('se produjo un erro al consultar el servidor');},
				success:  function (response){
					var _res = $.parseJSON(response);
					console.log(_res);
					
					for(_nn in _res.mg){
						alert(_res.mg[_nn]);
					}
					if(_res.res='exito'){		
						document.querySelector('#crearcapa').style.display='none';		
						consultarProyecto(_IdPro);	
						//document.querySelector('#incluidos div[idusu="'+_res.data.idusu+'"] input').setAttribute('estado','ok');
						//_Items[_res.data.idit]['responsables'][_res.data.idusu]=_res.data;
						//cargaBase('actualizaresponsables');
						//consultarProyecto(_IdPro);
					}else{
						alert('se produjo un error al consultar la base de datos');
					}
				}
			});				
	}
	
	</script>	
</body>

