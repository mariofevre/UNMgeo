<?php
/**
* con_capas.php
*
* consultas básicas para la gestion de capas
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

$func=$_POST['funcion'];
$func();


function terminar($Log){
	$res=json_encode($Log);
	if($res==''){
		echo print_r($Log,true);
	}
	echo $res;
	exit();
}

function cargarCapas(){
	global $ConPg;
$query='
SELECT 
	nombre, 
	descripcion, 
	ultimaversion, 
	id, 
	versiones, 
	id_b_unmgeo_pro_proyectos
  FROM unmgeodata."000_indice";
';

$Consulta = pg_query($ConPg, $query);
if(pg_errormessage($ConPg)!=''){
	$Log['tx'][]='error: '.pg_errormessage($ConPg);
	$Log['tx'][]='query: '.$query;
	$Log['res']='err';
	terminar($Log);
}	

while($fila=pg_fetch_assoc($Consulta)){
	$Capas['c'.$fila["id"]]=$fila;
}

$Log['data']=$Capas;
$Log['res']='exito';
	terminar($Log);
}


?>
