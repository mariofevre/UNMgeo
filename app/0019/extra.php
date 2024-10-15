<?php 
/**
* app/0031/*.php
*
* Generador rápido de área de influencia
*  
* 
* @package    	unmGEO
* @subpackage 	difusion
* @author     	Universidad Nacional de Moreno
* @author     	<mario@trecc.com.ar>
* @author    	http://www.unm.edu.ar/
* @author		based on TReCC SA Procesos Participativos Urbanos, development. www.trecc.com.ar/recursos
* @copyright	2022 Universidad Nacional de Moreno
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


session_start();

$Acceso='si';
if(!isset($_SESSION["unmgeo"])){
	$Acceso='no';
}else{
	if($_SESSION["unmgeo"]["usuario"]["id"]<1){
		$Acceso='no';
	}
}


?>
<!DOCTYPE html>
<head>
	<title>UNM - GEO - portal
	
	</title>
	
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	<META http-equiv="Content-Type" content="text/html; charset=windows-1252">
	<link rel="stylesheet" type="text/css" href="./css/unmgeo.css">
	
	<link rel="icon" href="./img/unmicon.ico">
	
	<link rel="stylesheet" type="text/css" href="./css/CAP.css?v=9">
	<link rel="stylesheet" type="text/css" href="./css/index.css?c=<?php echo time();?>">	
	<link rel="stylesheet" type="text/css" href="./css/catalogo_autore_mapa.css?c=<?php echo time();?>">	
	<link rel="stylesheet" type="text/css" href="./css/catalogo_autore_formulario.css?c=<?php echo time();?>">	
	<link rel="stylesheet" type="text/css" href="./css/catalogo_autore_tabla.css?c=<?php echo time();?>">	

		<style type="text/css">
			
			body{
				font-size:10px;	
			}
			
			#tablasec{
				display:none;
			}
			
			#tablasec td[campo='locs'] span{
				display:inline-block;
				margin:2px;
				border-radius:5px;
				border:1px solid #000;
			}
			
			#tablasec{
				width: calc(78vw - 20vw - 1.3vw);
			}
			
			#cap_nav #tabla table#tablasec {
			
			    width: calc(100% - 4px);
			    border-collapse: collapse;
			    border: 1px solid rgba(255,155,155,1);
			
			}

			#listaSecIncomp{
				display:none;
			}
			#cap_nav #tabla{
				width: calc(83.3vw - 20vw - 1.0vw - 12px);
				display: inline-block;
				margin-left: 1vw;
				height:100%;
			}
			#tabla[estado='cargando']{
				background-image:url('./img/cargando.gif');
				background-repeat: no-repeat;
			}
			
			#cap_nav #tabla tbody{
				overflow-y:scroll;
			}
			
			#cap_nav #tabla table#tablasec [tipo='1']{
				background-color: rgba(255,205,205,0.2);
			}
			#cap_nav #tabla table#tablasec [tipo='-1']{
				background-color: rgba(255,205,205,0.4);
			}
			#cap_nav #tabla table#tablasec th{
				background-color: rgba(255,205,205,1);
				position:relative;
			}
			
			#cap_nav #tabla table#tablasec td{
				font-size:1.6vh;
				margin:2px;	
				cursor:pointer;
				padding: 4px;
				border: 1px solid rgba(255,155,155,1);
			}
			
			
			#cap_nav #tabla table#tablasec tr:hover td, #cap_nav #tabla table tr[resaltado='si'] td{
				background-color: rgba(255,205,205,0.94);
			}
			
			#cap_nav #tabla table#tablasec tr[filtrado='si']{
				display:none;
			}
			#cap_nav #tabla table#tablasec tr[filtradoP='si']{
				display:none;
			}
			#cap_nav #tabla table#tablasec tr[seleccionado='si']{
				border: 3px solid rgba(155 ,55,55,1);
				background-color: rgba(255,205,205,0.94);
			}

			#menufiltroclase{
				display:none;
				position:absolute;
				right:-170px;		
				text-align:left;	
				z-index:20;	
			}
			
			#menufiltroparcela{
				display:none;
				position:absolute;
				right:70px;		
				text-align:left;
				width:30vh;
			}	
			
			#acciones a{
				text-align:center;
			}
			#acciones a > img{
				margin:2px;
				
				vertical-align:middle;
			}
			
			#comando{
				border:none;
			}
			
			#iconcarga{
			display:none;
			}
			
			
			
			#velo{
				position:fixed;	
				width:100vw;
				height:100vh;
				display:none;
				background-color:rgba(0,0,0,0.2);
				opacity:0;
				z-index:10000;
				text-align:center;
			}
			#velo[estado='cargando']{
				display:block;
				opacity:100;
			}
			
			#velo #cartel{
				position:absolute;
				left:calc(50% - 15vw);
				top:calc(50% - 15vh);
				width:30vw;
				height:10vh;	
				background-color:#fff;
				border:3px solid #000;
				box-shadow:10px 10px 10px rgba(0,0,0,0.8);
				padding:5vw
			}
			
			.titulo{
					margin-top:10px;
					font-weight:bold;
			}
			
			.fila{	
				font-size:30px;
				background-color:rgba(255,102,0,0.5);
				border: 2px solid #000;
				margin:2px;
			}
			#portatabla{
					text-align:center;
			}
			
			.punto input{
				width: calc(50% - 20px);
			}
			
			.mapa{				
				width:1000px;
				height:1000px;
				border:2px solid #000;
				margin:40px;
				display:inline-block;
			}
			
			.fila {
			  font-size: 30px;
			  background-color: #fff;
			  border: 2px solid #000;
			  margin: 2px;
			}

			.portamapa .mapa{
				width: 1000px;
				display:inline-block;
				vertical-align:top;
			}
			
			.portamapa .campos{
				
				width: 1000px;
				display:inline-block;
				vertical-align:top;
			}
			
						
			.multiply{
			   mix-blend-mode: multiply;
			  
			}
			
			.RMBA{
				display:none;
			}

			@media print {
				img#fondo{
					display:none;
				}
				
				#menucapas{
				display:none;
				}
				
				#portatabla{
					display:none;
				}
			
			}
			
			@media screen and (max-aspect-ratio: 4/3) {
				.portamapa .mapa{
					width: calc(100vw - 10px);
					display:block;
				}
				
				.portamapa .campos{
					width: calc(100vw - 10px);
					display:block;
				}
				
				#mapa {
					
				  width: calc(100vw - 40px);
				  height: calc(75vh - 60px);
				  position: absolute;
				  top: 20px;
				  left: 20px;
				  display:block;
				}
				
				#portatabla {
				  width: calc(100vw - 40px);
				  height: 25vh;
				  position: absolute;
				  left: 20px;
				  bottom: 20px;
				  top:unset;
				}
				
				.fila{
					border: 1px solid #000;
					margin: 3px;
					padding: 5px 10px;
					display:inline-block;
					font-size:20px;
					
				}
				.fila .titulo{
					font-size:15px;
					margin:0px;
				}
				
				
				
			}
		</style>
</head>

<body>
	
	<script charset='utf-8' type="text/javascript" src="./externo_js/jquery/jquery-1.12.0.min.js"></script>
	<script charset='utf-8' type="text/javascript" src="./externo_js/ol7.1/ol.js"></script>
		
	<img id="fondo" src="./img/fondo.png">		

	

	<div id='ayuda'>
		<a onclick='this.parentNode.style.display="none"'>cerrar</a>
				Esta ventana se encuentra en desarrollo.
				<br>
				Tal vez quieras volver más tarde.
	</div>	
	

	<div id='velo' estado='inactivo'>
	
		<div id='cartel'>
			<img id='cargando' src='./img/cargando.gif'>
			<h2>calculando</h2>
		</div>
	
	</div>
	
	
			
	<h1 id='titulo'></h1>
	<div id='portamapas'>
		
	</div>
	
	<div id='portatabla' estado='vacia'>
		<H1>Hacé click en el mapa</H1>
		<div id='campos'>
			<div>
			<p>
				Este sitio calcula algunas características básicas de población y vivienda.<br>
				Estima el total a una distancia de 1000m del punto que elijas.<br>
				Genera el cálculo con solo hacer click en el mapa para cualquier lugar de la Argentina.<br>
				Los datos consultados provienen del censo 2010 (INDEC).<br>
			</p>
			</div>
		</div>
		<div id='parametros'>
			<div>
				<label for='distancia'>Dístancia de influencia</label><input name='distancia' value='1000'>
			</div>
			<div>
				<label for='planilla' >pegá el listado de puntos</label><textarea onchange='procesaPlanilla()' name='planilla'></textarea>
			</div>
			<div id='puntos'>
			</div>
		</div>	
	</div>
	

	
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
		
			
			
		var _IdAutore=1;  // Claudio Caveri';
		
		var _Acceso='<?php echo $Acceso;?>';
		
		var _Filtro={};
		
		var _Mapas={}; //acá pondremos los componentes de los mapas
		var _MapasN=0; //acá pondremos el último numero de mapa generado
		
		var _Capa_consulta='034_v002'
		var _Datos = {};		
		
		var _Modo='extra'; //simple: para un punto a distancia fija / extra: para muchos puntos
		
		//rangos validos para argentina
		var y_min=-55.2;
		var y_max=-21.6;
		var x_min=-72.6;
		var x_max=-53.3;
		
		//variables para la gestión de adjuntos
		var _nFile=0;
		var xhr=Array();
		var inter=Array();
		
		
		var _PuntoN=0;
	</script>
	
	
	
	<script type="text/javascript" src="./funciones_js/catalogo_autore_consultas.js?<?php echo time();?>"></script>
	<script type="text/javascript" src="./funciones_js/catalogo_autore_muestra.js?<?php echo time();?>"></script>
	<script type="text/javascript" src="./funciones_js/catalogo_autore_mapa.js?<?php echo time();?>"></script>
	<script type="text/javascript" src="./funciones_js/catalogo_autore_interaccion.js?<?php echo time();?>"></script>
	<script type="text/javascript" src="./funciones_js/catalogo_autore_adjuntar.js?<?php echo time();?>"></script>
		
	
	<script type="text/javascript">	
	
		//consultaObras();
		//cargaMapa();	
		generarBotonCapas();
		//consultaAutores();
		//consultaReferencias();
		//cargarusuario();	
	</script>


	<script type="text/javascript">
	
		
		
	function procesaPlanilla(){	
		
		_txa=document.querySelector('#parametros textarea[name="planilla"]');
		_tx=_txa.value.replaceAll(',','.');
		//console.log(_tx);
		_s=_tx.split('\n');
		//console.log(_s);
		_eliminarFilas={};
		
		for(_reng in _s){
			_v=_s[_reng].split('\t');
			if(_s[_reng]==''){_eliminarFilas[_reng]='si';continue;}
			if(_v.length<2){continue;}
			
			for(_n in _v ){
				_vn=parseFloat(_v[_n]);
				if(_vn>0){ _vn=_vn*-1; }
				//console.log(_vn + ' vs ' + x_min + ' y ' + x_min);
				if( _vn > x_min && _vn < x_max ){// viable x
					//console.log(_vn+ '(x compatible)');
					for(_n2 in _v){
						_vn2=parseFloat(_v[_n2]);
						if(_vn2>0){ _vn2= _vn2*-1; }
						if(_n == _n2){continue;}
						if( _vn2 > y_min && _vn2 < y_max ){//viable y				
							//console.log(_vn2+' (y compatible)');
							//console.log('x: '+_n+ ' ' +_vn);							
							//console.log('y: '+_n2+ ' ' +_vn2);							
							_eliminarFilas[_reng]='si';
							_PuntoN++;
							
							_div=document.createElement('div');
							_div.setAttribute('class','punto');
							document.querySelector('#parametros #puntos').appendChild(_div);
							
							_div.setAttribute('puntoN',_PuntoN);
							
							_in=document.createElement('input');
							_in.setAttribute('name','x');
							_in.value=_vn;
							_div.appendChild(_in);
							
							_in=document.createElement('input');
							_in.setAttribute('name','y');
							_in.value=_vn2;
							_div.appendChild(_in);
						}
					}
				}
			}
		}
		
		_salidaTx='';
		for(_reng in _s){
			if(_eliminarFilas[_reng]==undefined){
				_salidaTx+=_s[_reng]+'\n';
			}
		}
		document.querySelector('#parametros textarea[name="planilla"]').value=_salidaTx;
		procesaPuntos();
	}

	function procesaPuntos(){			
		_divsP=document.querySelectorAll('#parametros #puntos .punto');
		
		for(_nd in _divsP){
			if(typeof _divsP[_nd] != 'object'){continue;}
			_np=_divsP[_nd].getAttribute('punton');
			console.log(_np);		
			cargaMapa(_np);
			
			_x=_divsP[_nd].querySelector('[name="x"]').value;
			_y=_divsP[_nd].querySelector('[name="y"]').value;
			
			consultaCoord(_x,_y,_np);
		}
	}
		
	function actualizarAcceso(){
		_sels=document.querySelectorAll('[restringidoVista="si"]');
		for(_el in _sels){
			if(typeof _sels[_el]!='object'){continue;}
			if(_Acceso=='no'){
				_sels[_el].style.display='none';
			}else{
				_sels[_el].style.display='block';
			}
		}
	}
	actualizarAcceso();


	function activarMenuFiltroClase(){
		if(document.querySelector('#tablasec #menufiltroclase').style.display=='block'){
			document.querySelector('#tablasec #menufiltroclase').style.display='none';
			return;
		}
		document.querySelector('#tablasec #menufiltroclase').style.display='block';
		document.querySelector('#tablasec #menufiltroclase').innerHTML='';
		
		_opciones={};
		for(_idsec in _Datos.sec){
			_dat=_Datos.sec[_idsec];
			if(_opciones[_dat.a_clase]==undefined){
				_opciones[_dat.a_clase]='caso';
			}
		}
		for(_opcion in _opciones){
			_aa=document.createElement('div');
			_aa.setAttribute('class','opcion');
			document.querySelector('#tablasec #menufiltroclase').appendChild(_aa);
			
			_oo=document.createElement('input');
			_oo.setAttribute('type','checkbox');
			_oo.setAttribute('value',_opcion);
			_oo.setAttribute('onclick','toggleFiltro(this)');
			
			
			_aa.appendChild(_oo);
			if(_Filtro[_opcion]==undefined){
				_oo.checked=true;	
			}
			
			_oo=document.createElement('a');
			_oo.setAttribute('onclick','simpleFiltro(this)');
			_oo.innerHTML=_opcion;
			_aa.appendChild(_oo);
		}
	}


	function toggleFiltro(_this){
		_op=_this.value;
		if(_this.checked==false){
			_Filtro[_op]='si';
		}else if(_Filtro[_op]!=undefined){
			delete _Filtro[_op];
		}
		
		_FiltradosSecs={};
		for(_idsec in _Datos.sec){
			
			_op=_Datos.sec[_idsec].a_clase;
			if(_Filtro[_op]!=undefined){
				_FiltradosSecs[_idsec]='si';
				document.querySelector('#tablasec tr[idsec="'+_idsec+'"]').setAttribute('filtrado','si');
			}else{
				document.querySelector('#tablasec tr[idsec="'+_idsec+'"]').setAttribute('filtrado','no');
			}
			
		}
		filtrarSecEnMapa();	
	}


	function teclearForm(_event){	
		_event.preventDefault();
		if(_event.keyCode==13){
			if(confirm('¿Enviamos estos datos como una nueva versión?')){
				enviarFormSec(_event,document.querySelector("#formularioSec #botonenvia").parentNode.parentNode);	
			}				
		}	
	}
	
		
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
		
	function verayuda(_this){
		_this.parentNode.querySelector('#ayuda').style.display='block';
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
	
		
	</script>




	<script type="text/javascript">

		//funciones del formulario de usuarios
		function formUsuario(){		
			$('head').append('<link id="usucssform" rel="stylesheet" type="text/css" href="../../usu/usuarios.css?v=2">');
			
			var parametros = {};
			$.ajax({
				data:  parametros,
				url:   '../../usu/usu_temp_acces.php',
				type:  'post',
				success:  function (response){
					document.body.innerHTML+=response;	
				}
			});
		
		}
		
		function ocultar(_this){
			_this.parentNode.style.display='none';
		}
		
		/*function cerrar(_this){
			_this.parentNode.parentNode.removeChild(_this.parentNode);
		}*/
		
		function cerrarAcc(_this){
			$("#usucssform").remove();
		}
			
		function ampliarUsu(_this){
			_this.parentNode.querySelector('#dataregistro').style.display='block';
			_this.parentNode.querySelector('#acceder').style.display='none';
			_this.style.display='none';
		}
		
		function verayuda(_this){
			document.querySelector('#ayuda').style.display='block';
		}
		
		function acceder(_this){		
			
			var parametros = {
				'dni': _this.parentNode.parentNode.querySelector('input[name="dni"]').value,
				'password': _this.parentNode.parentNode.querySelector('input[name="password"]').value
			};
			
			$.ajax({
				data:  parametros,
				url:   '../../usu/usu_acceso_ajax.php',
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
				url:   '../../usu/usu_salir_ajax.php',
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
	
				
		function formatear(_num){
			
				var _num = _num.replace(/\./g,'');
				
				if(!isNaN(_num)){
						_num = _num.toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g,'$1.');
						_num = _num.split('').reverse().join('').replace(/^[\.]/,'');
						return _num;
					}else{ 
						alert('Solo se permiten numeros');
						retunr = _num.replace(/[^\d\.]*/g,'');
				}	
		}
		
		
				
		function formatear(_num){
			
				var _num = _num.replace(/\./g,'');
				
				if(!isNaN(_num)){
						_num = _num.toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g,'$1.');
						_num = _num.split('').reverse().join('').replace(/^[\.]/,'');
						return _num;
					}else{ 
						alert('Solo se permiten numeros');
						retunr = _num.replace(/[^\d\.]*/g,'');
				}	
		}
				
		
		
	</script>
</body>

