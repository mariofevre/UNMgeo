<?php
/**
* con_proy_resp.php
*
* consulta responsables asignados a un proyecto
*  
* 
* @package    	unmGEO
* @subpackage 	difusion
* @author     	Universidad Nacional de Moreno
* @author     	<mario@trecc.com.ar>
* @author    	http://www.unm.edu.ar/
* @author		based on geoGEC Universidad de Buenos Aires. 
* @copyright	2016 Universidad Nacional de Moreno
* @copyright	esta aplicaci�n se desarrollo sobre una publicaci�n GNU (agpl) 2018 UBA
* @license    	https://www.gnu.org/licenses/agpl-3.0-standalone.html GNU AFFERO GENERAL PUBLIC LICENSE, version 3 (agpl-3.0)
* Este archivo es parte de TReCC(tm) paneldecontrol y de sus proyectos hermanos: baseobra(tm), TReCC(tm) intraTReCC  y TReCC(tm) Procesos Participativos Urbanos.
* Este archivo es software libre: tu puedes redistriburlo 
* y/o modificarlo bajo los t�rminos de la "GNU AFero General Public License version 3" 
* publicada por la Free Software Foundation
* 
* Este archivo es distribuido por si mismo y dentro de sus proyectos 
* con el objetivo de ser �til, eficiente, predecible y transparente
* pero SIN NIGUNA GARANT�A; sin siquiera la garant�a impl�cita de
* CAPACIDAD DE MERCANTILIZACI�N o utilidad para un prop�sito particular.
* Consulte la "GNU General Public License" para m�s detalles.
* 
* Si usted no cuenta con una copia de dicha licencia puede encontrarla aqu�: <http://www.gnu.org/licenses/>.
*/

session_start();
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

$e=explode(" ",print_r($ConPg,true));
if($e[0]!='Resource'){
	$Log['tx'][]='Error en la conexxi�n a la base de datos: '.print_r($ConPg,true);
	$Log['tx'][]=$e[0];
	terminar($Log);
}

if($_SESSION["unmgeo"]["usuario"]['sis_adm']!='1'){
	$Log['tx'][]='esta accion solo est� validad para administradores de sistema';
	$Log['res']='err';
	terminar($Log);		
}

if(!isset($_POST['idpro'])){
	$Log['tx'][]='no fue enviada la varaible idpro';
	$Log['res']='err';
	terminar($Log);	
}	

$query="
 
SELECT 
	id, 
	id_p_usu_usuarios, 
	id_p_pro_proyectos, 
	jerarquia, 
	cargo, 
	id_p_usu_usuarios_invitadopor, 
	fechasolicitud, 
	historial,
	zz_borrada
	FROM 
	unmgeo.usu_particiapacion
	WHERE
	id_p_pro_proyectos = '".$_POST['idpro']."'
";

$ConsultaProy = pg_query($ConPg, $query);
if(pg_errormessage($ConPg)!=''){
	$Log['tx'][]='error: '.pg_errormessage($ConPg);
	$Log['tx'][]='query: '.$query;
	$Log['res']='err';
	terminar($Log);
}	

while($fila=pg_fetch_assoc($ConsultaProy)){	
	//$Ord[$fila['id']]=$fila['orden'];	
	$Log['data']['resp'][$fila['id_p_usu_usuarios']]=$fila;
}		

$Log['res']='exito';
terminar($Log);


?>
