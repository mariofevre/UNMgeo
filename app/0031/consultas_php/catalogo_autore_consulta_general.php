<?php 
/**
*
* consulta de centroides de los elementos contenidos en la versión actual de una capa. 
 * Se utiliza para generar una capa interactiva liviana en el mapa online. 
 * 
 *  
* @package    	geoGEC
* @subpackage 	app_docs. Aplicacion para la gestión de documento
* @author     	GEC - Gestión de Espacios Costeros, Facultad de Arquitectura, Diseño y Urbanismo, Universidad de Buenos Aires.
* @author     	<mario@trecc.com.ar>
* @author    	http://www.municipioscosteros.org
* @copyright	2018 Universidad de Buenos Aires
* @license    	http://www.gnu.org/licenses/gpl.html GNU AFFERO GENERAL PUBLIC LICENSE, version 3 (GPL-3.0)
* Este archivo es software libre: tu puedes redistriburlo 
* y/o modificarlo bajo los términos de la "GNU AFFERO GENERAL PUBLIC LICENSE" 
* publicada por la Free Software Foundation, version 3
* 
* Este archivo es distribuido por si mismo y dentro de sus proyectos 
* con el objetivo de ser útil, eficiente, predecible y transparente
* pero SIN NIGUNA GARANTÍA; sin siquiera la garantía implícita de
* CAPACIDAD DE MERCANTILIZACIÓN o utilidad para un propósito particular.
* Consulte la "GNU General Public License" para más detalles.
* 
* Si usted no cuenta con una copia de dicha licencia puede encontrarla aquí: <http://www.gnu.org/licenses/>.
* 
*
*/

//if($_SERVER[SERVER_ADDR]=='192.168.0.252')ini_set('display_errors', '1');ini_set('display_startup_errors', '1');ini_set('suhosin.disable.display_errors','0'); error_reporting(-1);


ini_set('display_errors', true);
chdir('..');
session_start();

include('./encabezado/encabezado.php');

$Hoy_a = date("Y");$Hoy_m = date("m");$Hoy_d = date("d");
$HOY = $Hoy_a."-".$Hoy_m."-".$Hoy_d;	

$Log['data']=array();
$Log['tx']=array();
$Log['mg']=array();
$Log['res']='';
function terminar($Log){
	$res=json_encode($Log);
	if($res==''){$res=print_r($Log,true);}
	echo $res;
	exit;	
}

$mco=microtime(true);

$_POST['idautore'];


$query="
	SELECT id, nombre, apellido
	FROM unmgeo_app0030.aut_autores
	
	WHERE 
		id='".$_POST['idautore']."'
    
";
	
$Consulta = pg_query($ConPg, $query);
if(pg_errormessage($ConPg)!=''){
	$Log['tx'][]='error: '.pg_errormessage($ConPg);
	$Log['tx'][]='query: '.$query;
	$Log['res']='err';
	terminar($Log);
}	
$Log['data']['Autore']=pg_fetch_assoc($Consulta);



$query="
	SELECT 
		link_aut_obr.id_p_obr_obras, 
		link_aut_obr.id_p_aut_autores, 
		link_aut_obr.rol,
		obr_obras.nombre, 
		obr_obras.observaciones, 
		obr_obras.direccion, 
		ST_AsText(obr_obras.geom_loc) as geotx_loc, 
		ST_AsText(obr_obras.geom_pol) as geotx_pol, 
		obr_obras.ano_construccion, 
		obr_obras.ano_proyecto
		
	FROM 
		unmgeo_app0030.link_aut_obr
	LEFT JOIN
		unmgeo_app0030.obr_obras ON obr_obras.id = link_aut_obr.id_p_obr_obras
	WHERE
		id_p_aut_autores='".$_POST['idautore']."'
		AND
		obr_obras.zz_borrada='0'
	
	";
	
$Consulta = pg_query($ConPg, $query);
if(pg_errormessage($ConPg)!=''){
	$Log['tx'][]='error: '.pg_errormessage($ConPg);
	$Log['tx'][]='query: '.$query;
	$Log['res']='err';
	terminar($Log);
}


while($fila=pg_fetch_assoc($Consulta)){	
	$Log['data']['Obras'][$fila['id_p_obr_obras']]=$fila;	
	$Log['data']['Obras'][$fila['id_p_obr_obras']]['documentacion']=array();
	$Log['data']['Obras'][$fila['id_p_obr_obras']]['referencias']=array();
}


$query="
	SELECT 
		link_aut_obr.id_p_obr_obras, 
		obr_documentacion.id, 
		obr_documentacion.fi_documento, 
		obr_documentacion.nombre, 
		obr_documentacion.fuente, 
		obr_documentacion.derechos,
		
		obr_documentacion.fi_original, 
		obr_documentacion.fi_tipo, 
		obr_documentacion.fi_extension, 
		obr_documentacion.zz_borrada, 
		obr_documentacion.zz_auto_fechau_creacion, 
		obr_documentacion.fi_muestra, 
		obr_documentacion.zz_auto_usu_creacion
		
			
	FROM 
		unmgeo_app0030.link_aut_obr
	LEFT JOIN
		unmgeo_app0030.obr_obras ON obr_obras.id = link_aut_obr.id_p_obr_obras
	LEFT JOIN
		unmgeo_app0030.obr_documentacion ON obr_documentacion.id_p_obr_obras = obr_obras.id
		
	WHERE
			id_p_aut_autores='".$_POST['idautore']."'
		AND 
			obr_documentacion.id is not null
		AND
			obr_documentacion.zz_borrada='0'
	";
	
$Consulta = pg_query($ConPg, $query);
if(pg_errormessage($ConPg)!=''){
	$Log['tx'][]='error: '.pg_errormessage($ConPg);
	$Log['tx'][]='query: '.$query;
	$Log['res']='err';
	terminar($Log);
}


while($fila=pg_fetch_assoc($Consulta)){	
	if(!isset($Log['data']['Obras'][$fila['id_p_obr_obras']])){continue;}
	$Log['data']['Obras'][$fila['id_p_obr_obras']]['documentacion'][$fila['id']]=$fila;	
}


		

$query="
	SELECT 
		link_ref_obr.id_p_obr_obras,
		link_ref_obr.resumen, 
		link_ref_obr.localizaciones_en_texto,
		link_ref_obr.id_p_ref_referencias, 
		ref_referencias.nombre, 
		ref_referencias.ano_publicacion, 
		ref_referencias.autores, 
		ref_referencias.editorial, 
		ref_referencias.formato, 
		ref_referencias.zz_borrada
			
	FROM 
		unmgeo_app0030.link_aut_obr
	LEFT JOIN
		unmgeo_app0030.obr_obras 
			ON obr_obras.id = link_aut_obr.id_p_obr_obras
	LEFT JOIN
		unmgeo_app0030.link_ref_obr
			ON link_ref_obr.id_p_obr_obras = obr_obras.id
	LEFT JOIN
		unmgeo_app0030.ref_referencias
			ON link_ref_obr.id_p_ref_referencias = ref_referencias.id	
		
	WHERE
		id_p_aut_autores='".$_POST['idautore']."'
		AND
		link_ref_obr.zz_borrada='0'
	
	";
	
$Consulta = pg_query($ConPg, $query);
if(pg_errormessage($ConPg)!=''){
	$Log['tx'][]='error: '.pg_errormessage($ConPg);
	$Log['tx'][]='query: '.$query;
	$Log['res']='err';
	terminar($Log);
}


while($fila=pg_fetch_assoc($Consulta)){	
	if(!isset($Log['data']['Obras'][$fila['id_p_obr_obras']])){continue;}
	$Log['data']['Obras'][$fila['id_p_obr_obras']]['referencias'][$fila['id_p_ref_referencias']]=$fila;	
	
}

	

$query="
	SELECT 
		aut_autores.*,
		
		link2.rol,
		link2.id_p_obr_obras,
		link2.id_p_aut_autores
			
	FROM 
		unmgeo_app0030.link_aut_obr
	LEFT JOIN
		unmgeo_app0030.obr_obras 
			ON obr_obras.id = link_aut_obr.id_p_obr_obras
	LEFT JOIN
		unmgeo_app0030.link_aut_obr as link2
			ON link2.id_p_obr_obras = obr_obras.id
	LEFT JOIN
		unmgeo_app0030.aut_autores
			ON aut_autores.id = link2.id_p_aut_autores	
		
	WHERE
		link_aut_obr.id_p_aut_autores='".$_POST['idautore']."'
		AND
		link2.zz_borrada='0'
	";
	
$Consulta = pg_query($ConPg, $query);
if(pg_errormessage($ConPg)!=''){
	$Log['tx'][]='error: '.pg_errormessage($ConPg);
	$Log['tx'][]='query: '.$query;
	$Log['res']='err';
	terminar($Log);
}


while($fila=pg_fetch_assoc($Consulta)){	
	if(!isset($Log['data']['Obras'][$fila['id_p_obr_obras']])){continue;}
	$Log['data']['Obras'][$fila['id_p_obr_obras']]['colaboraciones'][$fila['id_p_aut_autores']]=$fila;
	
}




$Log['res']='exito';
terminar($Log);		
?>


