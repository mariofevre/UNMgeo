<?php
/**
* con_proy.php
*
* consultas básicas para la gestion de proyectos
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


global $ConPg;
$query="
SELECT *
  FROM unmgeo.pro_proyectos
  WHERE id = '".$_POST['id']."'
";

$ConsultaProy = pg_query($ConPg, $query);
if(pg_errormessage($ConPg)!=''){
	$Log['tx'][]='error: '.pg_errormessage($ConPg);
	$Log['tx'][]='query: '.$query;
	$Log['res']='err';
	terminar($Log);
}	

if(pg_num_rows($ConsultaProy)==0){
	$Log['tx'][]='no se encontró el proyecto slicitado con el id '.$_POST['id'];
	$Log['res']='err';
	terminar($Log);
}

while($fila=pg_fetch_assoc($ConsultaProy)){
	$Log['data']['proyecto']=$fila;		
}

$Log['data']['aplicaciones']=array();
$query="
SELECT 
  app_aplicacion.nombre, 
  app_aplicacion.enserver, 
  app_aplicacion.enexterno, 
  app_aplicacion.encliente, 
  app_aplicacion.descripcion, 
  app_aplicacion.id_p_pro_proyectos, 
  app_aplicacion.zz_borrada, 
  app_aplicacion.url, 
  app_aplicacion.id_p_app_estados, 
  app_aplicacion.id, 
  app_aplicacion.responsables_tx,
  app_estados.nombre e_nombre, 
  app_estados.descripcion e_descripcion
FROM 
  unmgeo.app_aplicacion, 
  unmgeo.app_estados

 WHERE
 id_p_pro_proyectos = '".$_POST['id']."'
 AND
 app_aplicacion.id_p_app_estados = app_estados.id
 AND
 app_aplicacion.zz_borrada=0
ORDER BY
  app_aplicacion.id_p_app_estados DESC;
";

$ConsultaProy = pg_query($ConPg, $query);
if(pg_errormessage($ConPg)!=''){
	$Log['tx'][]='error: '.pg_errormessage($ConPg);
	$Log['tx'][]='query: '.$query;
	$Log['res']='err';
}	
			
while($fila=pg_fetch_assoc($ConsultaProy)){
	$Log['data']['aplicaciones'][$fila['id']]=$fila;		
}


$Log['data']['capas']=array();
$query="
SELECT 
	nombre, 
	descripcion, 
	ultimaversion, 
	id, 
	versiones, 
	id_b_unmgeo_pro_proyectos
  FROM unmgeodata.\"000_indice\"
    WHERE
  id_b_unmgeo_pro_proyectos = '".$_POST['id']."'
";

$Consulta = pg_query($ConPg, $query);
if(pg_errormessage($ConPg)!=''){
	$Log['tx'][]='error: '.pg_errormessage($ConPg);
	$Log['tx'][]='query: '.$query;
	$Log['res']='err';
	terminar($Log);
}	

while($fila=pg_fetch_assoc($Consulta)){
	$Log['data']['capas'][$fila["id"]]=$fila;
}





$query="
SELECT 
	usu_particiapacion.id, 
	usu_particiapacion.id_p_usu_usuarios, 
	usu_particiapacion.id_p_pro_proyectos, 
	usu_particiapacion.jerarquia, 
	usu_particiapacion.cargo, 
	usu_particiapacion.id_p_usu_usuarios_invitadopor, 
	usu_particiapacion.fechasolicitud, 
	usu_particiapacion.historial,
	
	usu_usuarios.nombre, 
	usu_usuarios.apellido
	
	FROM 
		unmgeo.usu_particiapacion,
		unmgeo.usu_usuarios
	
	 WHERE
	 usu_usuarios.id = usu_particiapacion.id_p_usu_usuarios
	 AND
	 usu_particiapacion.id_p_pro_proyectos = '".$_POST['id']."'
	 AND
	 usu_particiapacion.zz_borrada=0	
";

$ConsultaProy = pg_query($ConPg, $query);
if(pg_errormessage($ConPg)!=''){
	$Log['tx'][]='error: '.pg_errormessage($ConPg);
	$Log['tx'][]='query: '.$query;
	$Log['res']='err';
}	

$Log['data']['responsables']=array();	
while($fila=pg_fetch_assoc($ConsultaProy)){
	$Log['data']['responsables'][$fila['id']]=$fila;		
}

$Log['res']='exito';
terminar($Log);




?>
