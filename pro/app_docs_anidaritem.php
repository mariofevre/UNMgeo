<?php
/**
* ESP_consulta_esp.php
*
 * ejecuta funciones dentro de una aplicaci{on php devolviendo el resultado en formtao json
* @package    	TReCC(tm) paneldecontrol.
* @subpackage 	
* @author     	TReCC SA
* @author     	<mario@trecc.com.ar> <trecc@trecc.com.ar>
* @author    	www.trecc.com.ar  
* @copyright	2013-2015 TReCC SA
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
ini_set('display_errors', true);
chdir('..');
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

$e=explode(" ",print_r($ConPg,true));
if($e[0]!='Resource'){
	$Log['tx'][]='Error en la conexxión a la base de datos: '.print_r($ConPg,true);
	$Log['tx'][]=$e[0];
	terminar($Log);
}

if($_SESSION["unmgeo"]["usuario"]['sis_adm']!='1'){
	$Log['tx'][]='esta accion solo está validad para administradores de sistema';
	$Log['res']='err';
	terminar($Log);		
}

if(!isset($_POST['idpro'])){
	$Log['tx'][]='no fue enviada la varaible idpro';
	$Log['res']='err';
	terminar($Log);	
}	

if(!isset($_POST['id'])){
	$Log['tx'][]='error, falta titulo';
	$Log['res']='err';
	terminar($Log);	
}
if(!isset($_POST['id_anidado'])){
	$Log['tx'][]='error, id_p_ESPitems_anidado';
	$Log['res']='err';
	terminar($Log);	
}
if(!isset($_POST['viejoAnidado'])){
	$Log['tx'][]='error, faltaviejoAnidado';
	$Log['res']='err';
	terminar($Log);	
}
if(!isset($_POST['viejoAserie'])){
	$Log['tx'][]='error, falta viejoAserie';
	$Log['res']='err';
	terminar($Log);	
}
if(!isset($_POST['nuevoAnidado'])){
	$Log['tx'][]='error, falta nuevoAnidado';
	$Log['res']='err';
	terminar($Log);	
}
if(!isset($_POST['nuevoAserie'])){
	$Log['tx'][]='error, falta nuevoAserie';
	$Log['res']='err';
	terminar($Log);	
}


$query="
	UPDATE
		unmgeo.pro_pseudocarpetas
	SET 
		id_p_pro_pseudocarpetas='".$_POST['id_anidado']."'
	WHERE
		id='".$_POST['id']."'
	AND
		id_p_pro_proyectos='".$_POST['idpro']."'
";
$ConsultaProy = pg_query($ConPg, $query);
if(pg_errormessage($ConPg)!=''){
	$Log['tx'][]='error: '.pg_errormessage($ConPg);
	$Log['tx'][]='query: '.$query;
	$Log['res']='err';
	terminar($Log);
}


$e=explode(',',$_POST['viejoAserie']);
$c=0;
foreach($e as $v){
	if(intval($v)>0){
		$c++;
		
		$query="
			UPDATE
				unmgeo.pro_pseudocarpetas
			SET 
				orden='".$c."'
			WHERE
				id='".$v."'
			AND
				id_p_pro_proyectos='".$_POST['idpro']."'
		";
		$Log['tx'][]="$v -> $c";
		$ConsultaProy = pg_query($ConPg, $query);
		if(pg_errormessage($ConPg)!=''){
			$Log['tx'][]='error: '.pg_errormessage($ConPg);
			$Log['tx'][]='query: '.$query;
			$Log['res']='err';
			terminar($Log);
		}
		
	}	
}

$e=explode(',',$_POST['nuevoAserie']);
$c=0;
foreach($e as $v){
	if(intval($v)>0){
		$c++;
		
		$query="
			UPDATE
				unmgeo.pro_pseudocarpetas
			SET 
				orden='".$c."'
			WHERE
				id='".$v."'
			AND
				id_p_pro_proyectos='".$_POST['idpro']."'
		";
		$Log['tx'][]="$v -> $c";
		$ConsultaProy = pg_query($ConPg, $query);
		if(pg_errormessage($ConPg)!=''){
			$Log['tx'][]='error: '.pg_errormessage($ConPg);
			$Log['tx'][]='query: '.$query;
			$Log['res']='err';
			terminar($Log);
		}
		
	}	
}




$Log['res']='exito';
terminar($Log);
?>