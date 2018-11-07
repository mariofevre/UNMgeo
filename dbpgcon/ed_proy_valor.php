<?php
/**
*
* actualiza un valor para un registro dentro de la tabla de proyectos
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

ini_set('display_errors', true);
chdir('..');
include('./encabezado/encabezado.php');


function terminar($Log){
	$res=json_encode($Log);
	if($res==''){
		echo print_r($Log,true);
	}
	echo $res;
	exit();
}

session_start();
foreach($_SESSION["unmgeo"]["usuario"] as $k => $v){
	$dataU[$k]=utf8_encode($v);
}

if($dataU["sis_adm"]!=1){
	$Log['tx'][]='error: en la validación del usuario';
	$Log['tx'][]=$dataU;
	$Log['res']='err';
	terminar($Log);
}


if(!isset($_POST["id"])){
	$Log['tx'][]='error: al enviar variable id';
	$Log['res']='err';
	terminar($Log);
}
if(!isset($_POST["campo"])){
	$Log['tx'][]='error: al enviar variable campo';
	$Log['res']='err';
	terminar($Log);
}
if(!isset($_POST["valor"])){
	$Log['tx'][]='error: al enviar variable valor';
	$Log['res']='err';
	terminar($Log);
}


global $ConPg;
$query="
UPDATE 
  unmgeo.pro_proyectos
  SET
  \"".$_POST["campo"]."\" = '".$_POST["valor"]."'
  WHERE id = '".$_POST['id']."'
";

$ConsultaProy = pg_query($ConPg, $query);
if(pg_errormessage($ConPg)!=''){
	$Log['tx'][]='error: '.pg_errormessage($ConPg);
	$Log['tx'][]='query: '.$query;
	$Log['res']='err';
	terminar($Log);
}	

$Log['res']='exito';
terminar($Log);




?>
