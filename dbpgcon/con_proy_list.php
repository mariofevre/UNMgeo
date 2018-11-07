<?php
/**
* con_proy.php
*
* consultas b�sicas para la gestion de proyectos
*  
* 
* @package    	unmGEO
* @subpackage 	difusion
* @author     	Universidad Nacional de Moreno
* @author     	<mario@trecc.com.ar>
* @author    	http://www.unm.edu.ar/
* @author		based on TReCC SA Procesos Participativos Urbanos, development. www.trecc.com.ar/recursos
* @copyright	2016 Universidad Nacional de Moreno
* @copyright	esta aplicaci�n se desarrollo sobre una publicaci�n GNU (agpl) 2014 TReCC SA
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



function cargarProyectos(){
	global $ConPg;
$query="
SELECT id, nombre, descripcion, visible
  FROM unmgeo.pro_proyectos
  ORDER by visible desc, id desc
";

$ConsultaProy = pg_query($ConPg, $query);
if(pg_errormessage($ConPg)!=''){
	$Log['tx'][]='error: '.pg_errormessage($ConPg);
	$Log['tx'][]='query: '.$query;
	$Log['res']='err';
	terminar($Log);
}	

while($fila=pg_fetch_assoc($ConsultaProy)){
	$Log['data']['proyectos'][$fila["id"]]=$fila;
	$Log['data']['proyectos'][$fila["id"]]['responsables']=array();
	$Log['data']['proyectosOrden'][]=$fila["id"];
}


$query="
SELECT 
	usu_particiapacion.id, 
	usu_particiapacion.id_p_usu_usuarios, 
	usu_particiapacion.id_p_pro_proyectos, 
	usu_particiapacion.jerarquia, 
	usu_particiapacion.cargo,
	
	usu_usuarios.nombre, 
	usu_usuarios.apellido
	
	FROM 
		unmgeo.usu_particiapacion,
		unmgeo.usu_usuarios
	
	 WHERE
	 usu_usuarios.id = usu_particiapacion.id_p_usu_usuarios
	 AND
	 usu_particiapacion.zz_borrada='0'
";

$ConsultaProy = pg_query($ConPg, $query);
if(pg_errormessage($ConPg)!=''){
	$Log['tx'][]='error: '.pg_errormessage($ConPg);
	$Log['tx'][]='query: '.$query;
	$Log['res']='err';
}	
			
while($fila=pg_fetch_assoc($ConsultaProy)){		
	$Log['data']['proyectos'][$fila["id_p_pro_proyectos"]]['responsables'][$fila['id']]=$fila;
}

if(isset($_POST['id'])){// esto se puede hacer m�s eficiente.
	$Log['data']['proyecto']=$Log['data']['proyectos'][$_POST['id']];
	unset($Log['data']['proyectos']);
	unset($Log['data']['proyectosOrden']);
}

$Log['res']='exito';
	terminar($Log);
}



?>
