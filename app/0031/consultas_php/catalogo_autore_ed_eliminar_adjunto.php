<?php 
/**
* SEG_ed_borrar_adjunto.php
*
* modifica los atributos del registro de un archivo adjunto para que sea considerado eliminado (no es devuelto por las consultas habituales)
* 
* @package    	TReCC(tm) paneldecontrol.
* @subpackage 	Lista de seguimiento / tracking / segumiento
* @author     	TReCC SA
* @author     	<mario@trecc.com.ar> <trecc@trecc.com.ar>
* @author    	www.trecc.com.ar  
* @copyright	2013 - 2019 TReCC SA
* @license    	http://www.gnu.org/licenses/agpl.html GNU Affero General Public License, version 3 (AGPL-3.0)
* Este archivo es parte de TReCC(tm) paneldecontrol y de sus proyectos hermanos: baseobra(tm) y TReCC(tm) intraTReCC.
* Este archivo es software libre: tu puedes redistriburlo 
* y/o modificarlo bajo los términos de la "GNU Affero General Public License" 
* publicada por la Free Software Foundation, version 3
* 
* Este archivo es distribuido por si mismo y dentro de sus proyectos 
* con el objetivo de ser útil, eficiente, predecible y transparente
* pero SIN NIGUNA GARANTÍA; sin siquiera la garantía implícita de
* CAPACIDAD DE MERCANTILIZACIÓN o utilidad para un propósito particular.
* Consulte la "GNU Affero General Public License" para más detalles.
* 
* Si usted no cuenta con una copia de dicha licencia puede encontrarla aquí: <http://www.gnu.org/licenses/>.
*/

ini_set('display_errors', true);
session_start();
chdir('..');
include('./encabezado/encabezado.php');
include_once('./comunes/cadenas.php');



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

/*

if(!isset($_SESSION["unmgeo"])){
	$Log['mg'][]="error al identificar su usuario. Por favor Logueese en UNMgeo";
	$Log['tx'][]="error al identificar su usuario";
	$Log['res']='err';
	terminar($Log);
}
if($_SESSION["unmgeo"]["usuario"]["id"]<1){
	$Log['mg'][]="error al identificar su usuario. Por favor Logueese en UNMgeo";
	$Log['tx'][]="error al identificar su usuario";
	$Log['res']='err';
	terminar($Log);
}*/
//TODO verificar si tiene permisos en el Proyecto 26 (o del proyecto vinculado a la app 11) para permitir la edición de la app 0011.




$varsOblig=Array(
	'idadj',
	'tabla'
);

foreach($varsOblig as $v){
	if(!isset($_POST[$v])){
		$Log['mg'][]="error, falta variable $v ";
		$Log['tx'][]="error, falta variable $v ";
		$Log['res']='err';
		terminar($Log);
	}	
}


$tablasOk=Array(
	'obr_documentacion' => 'obr_obras'
);
if(!isset($tablasOk[$_POST['tabla']])){
	$Log['mg'][]="error, tabla invalida ".$_POST['tabla'];
	$Log['tx'][]="error, tabla invalida ".$_POST['tabla'];
	$Log['res']='err';
	terminar($Log);
}
$Tabla=$_POST['tabla'];


$Log['data']['idadj']=$_POST['idadj'];
$Log['data']['tabla']=$_POST['tabla'];


$camporef='id_p_'.$tablasOk[$_POST['tabla']];


$Hoy = date("Y-m-d");

	
	
$query="
   UPDATE
		unmgeo_app0030.$Tabla
   	SET
        zz_borrada='1'
    WHERE
		id = '".$_POST['idadj']."'
";
$Consulta = pg_query($ConPg, $query);
if(pg_errormessage($ConPg)!=''){
	$Log['tx'][]='error: '.pg_errormessage($ConPg);
	$Log['tx'][]='query: '.$query;
	$Log['res']='err';
	terminar($Log);
}




$Log['res']='exito';
terminar($Log);
?>
