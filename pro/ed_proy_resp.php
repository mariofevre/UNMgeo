<?php
/**
* ed_proy_resp.php
*
* modifica las responsabilidades de un usuario en un proyecto
*  
* 
* @package    	unmGEO
* @subpackage 	difusion
* @author     	Universidad Nacional de Moreno
* @author     	<mario@trecc.com.ar>
* @author    	http://www.unm.edu.ar/
* @author		based on geoGEC Universidad de Buenos Aires. 
* @copyright	2016 Universidad Nacional de Moreno
* @copyright	esta aplicación se desarrollo sobre una publicación GNU (agpl) 2018 UBA
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

if(!isset($_POST['idusu'])){
	$Log['tx'][]='no fue enviada la varaible idusu que indica el usuario signado';
	$Log['res']='err';
	terminar($Log);	
}	
if(!isset($_POST['nuevoestado'])){
	$Log['tx'][]='no fue enviada la varaible nuevoestado (incluido/excluido)';
	$Log['res']='err';
	terminar($Log);	
}	
if(!isset($_POST['responsabilidad'])){
	$Log['tx'][]='no fue enviada la varaible responsabilidad';
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
		id_p_pro_proyectos='".$_POST['idpro']."'
	AND
		id_p_usu_usuarios='".$_POST['idusu']."'
";
$ConsultaProy = pg_query($ConPg, $query);
if(pg_errormessage($ConPg)!=''){
	$Log['tx'][]='error: '.pg_errormessage($ConPg);
	$Log['tx'][]='query: '.$query;
	$Log['res']='err';
	terminar($Log);
}

if($_POST['nuevoestado']=='excluido'){
	$_POST['zz_borrada']="1";
}elseif($_POST['nuevoestado']=='incluido'){
	$_POST['zz_borrada']="0";
}else{
	$Log['tx'][]='error: valor inesperado para la variable nuevoestado';
	$Log['res']='err';
	terminar($Log);	
}



if(pg_num_rows($ConsultaProy)==0){
	$query="
		INSERT INTO 
			unmgeo.usu_particiapacion(
				id_p_usu_usuarios, 
				id_p_pro_proyectos, 
				cargo, 
				id_p_usu_usuarios_invitadopor,
				fechasolicitud, 
				historial, 
				zz_borrada
				)
		VALUES (
			'".$_POST['idusu']."', 
			'".$_POST['idpro']."', 
			'".$_POST['responsabilidad']."', 
			'".$_SESSION["unmgeo"]["usuario"]['id']."',
			'".$HOY."',
			'".time().'_creado_por_'.$_SESSION["unmgeo"]["usuario"]['id'].'/'."',
			'".$_POST['zz_borrada']."'
			)
	";	
}else{
	while($row=pg_fetch_assoc($ConsultaProy)){
		$_POST['historial']=$row['historial'].time()."_actualizado_por_".$_SESSION["unmgeo"]["usuario"]['id'].":cargo->".$_POST['responsabilidad']."_zz_borrada->".$_POST['zz_borrada'].'/';
	}
	$query="
		UPDATE
			unmgeo.usu_particiapacion
		SET 
			cargo='".$_POST['responsabilidad']."', 
			zz_borrada='".$_POST['zz_borrada']."',
			historial='".$_POST['historial']."'
		WHERE 
			id_p_usu_usuarios='".$_POST['idusu']."'
		AND 
			id_p_pro_proyectos='".$_POST['idpro']."'
	";		
}
	$Log['tx'][]='query: '.$query;
$ConsultaProy = pg_query($ConPg, $query);
if(pg_errormessage($ConPg)!=''){
	$Log['tx'][]='error: '.pg_errormessage($ConPg);
	$Log['tx'][]='query: '.$query;
	$Log['res']='err';
	terminar($Log);
}

$Log['data']=$_POST;



$Log['res']='exito';
terminar($Log);
?>