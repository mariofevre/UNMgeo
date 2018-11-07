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
if(!isset($_POST['idpro'])){
	$Log['tx'][]='no fue enviada la varaible idpro';
	$Log['res']='err';
	terminar($Log);	
}	


/*
if($Usu['acc']['ref'][$_POST['idMarco']]<2&&$Usu['acc']['ref'][$_POST['codMarco']]<2){
	$Log['tx'][]='query: '.$query;
	$Log['mg'][]=utf8_encode('no cuenta con permisos para consultar la caja de documentnos de este marco académico');
	$Log['res']='err';
	terminar($Log);	
}
*/

$query="
	SELECT 
		id, 
		id_p_pro_pseudocarpetas, 
		id_p_pro_proyectos,
		orden,
		nombre, 
		descripcion
	FROM 
  		unmgeo.pro_pseudocarpetas
	WHERE
		id_p_pro_proyectos='".$_POST['idpro']."' 			
	AND
		zz_borrada='0'
	ORDER BY 
		orden
";

$ConsultaProy = pg_query($ConPg, $query);
if(pg_errormessage($ConPg)!=''){
	$Log['tx'][]='error: '.pg_errormessage($ConPg);
	$Log['tx'][]='query: '.$query;
	$Log['res']='err';
	terminar($Log);
}
if(pg_num_rows($ConsultaProy)<1){
	$Log['tx'][]='no se encontraron pseudocarpetas para este marco academico';
}

$Log['data']['psdir']=array();
$Log['data']['psdir'][0]['archivos']=array();//el id de la caja 0 refiere a archivos localizados en ninguna caja
$Log['data']['orden']['psdir']=array();

while($fila=pg_fetch_assoc($ConsultaProy)){	
	//$Ord[$fila['id']]=$fila['orden'];	
	$Log['data']['psdir'][$fila['id']]=$fila;
	$Log['data']['psdir'][$fila['id']]['archivos']=Array();	
	$Log['data']['orden']['psdir'][]=$fila['id'];	
}		


$query="
	SELECT 
		id, 
		id_p_pro_proyectos, 
		zz_borrada, 
		nombre, 
		descripcion,
		archivo, 
		id_p_pro_pseudocarpetas, 
		orden
  	FROM 
  		unmgeo.pro_documentos
	WHERE
		id_p_pro_proyectos='".$_POST['idpro']."'	
	AND
		zz_borrada='0'
	ORDER BY 
		orden, nombre asc
";
$ConsultaProy = pg_query($ConPg, $query);
if(pg_errormessage($ConPg)!=''){
	$Log['tx'][]='error: '.pg_errormessage($ConPg);
	$Log['tx'][]='query: '.$query;
	$Log['res']='err';
	terminar($Log);
}
$c=0;
while ($fila=pg_fetch_assoc($ConsultaProy)){
		
	$Log['data']['psdir'][$fila['id_p_pro_pseudocarpetas']]['archivos'][$fila['id']]=$fila;
	$Log['data']['psdir'][$fila['id_p_pro_pseudocarpetas']]['ordenarchivos'][]=$fila['id'];
	$c++;
}
$Log['data']['totalArchivos']=$c;
$Log['res']='exito';
terminar($Log);
?>