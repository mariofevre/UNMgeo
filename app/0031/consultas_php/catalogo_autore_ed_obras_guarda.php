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


if($_POST['geom_loc']!=''){
	$set_loc="geom_loc=ST_GeomFromText('".$_POST['geom_loc']."','3857'),";	
}else{
	$set_loc="geom_loc= NULL, ";	
}

if(!isset($_POST['geom_pol'])){$_POST['geom_pol']='';}
if($_POST['geom_pol']!=''){
	$set_pol="geom_pol=ST_GeomFromText('".$_POST['geom_pol']."','3857'),";	
}else{
	$set_pol="geom_pol= NULL, ";	
}


if(!is_numeric($_POST['ano_construccion'])){
	$set_ac=" NULL ";
}else{
	$set_ac=" '".$_POST['ano_construccion']."'";
}

if(!is_numeric($_POST['ano_proyecto'])){
	$set_ap=" NULL ";
}else{
	$set_ap=" '".$_POST['ano_proyecto']."'";
}

$query="
	UPDATE
	
			unmgeo_app0030.obr_obras
			
		SET 
		
			nombre='".$_POST['nombre']."',
			direccion='".$_POST['direccion']."',
			$set_loc
			$set_pol
			ano_construccion=$set_ac,
			ano_proyecto=$set_ap,
			observaciones='".$_POST['observaciones']."'
		WHERE
			id='".$_POST['id_p_obr_obras']."'
	
";
		
$Consulta = pg_query($ConPg, $query);
if(pg_errormessage($ConPg)!=''){
	$Log['tx'][]='error: '.pg_errormessage($ConPg);
	$Log['tx'][]='query: '.$query;
	$Log['res']='err';
	terminar($Log);
}
$fila=pg_fetch_assoc($Consulta);

		

$Log['res']='exito';
terminar($Log);		
?>


