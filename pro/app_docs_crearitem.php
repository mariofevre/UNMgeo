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
		id_p_pro_pseudocarpetas, 
		orden
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


while($fila=pg_fetch_assoc($ConsultaProy)){	
	$ultimoorden=$fila['orden'];	
}		
$ultimoorden++;

$query="
	INSERT INTO 
		unmgeo.pro_pseudocarpetas(
            id_p_pro_proyectos,
            orden
		)
	    VALUES (
	    	'".$_POST['idpro']."',
	    	'".$ultimoorden."'
	    )    
	    RETURNING id
";
$ConsultaVer = pg_query($ConPg, $query);
if(pg_errormessage($ConPg)!=''){
	$Log['tx'][]='error: '.pg_errormessage($ConPg);
	$Log['tx'][]='query: '.$query;
	$Log['mg'][]='error interno';
	$Log['res']='err';
	terminar($Log);	
}

while($fila=pg_fetch_assoc($ConsultaVer)){
	$Nid=$fila['id'];
	$Log['tx'][]='item creado, id:'.$Nid;
	$Log['data']['nid']=$Nid;
}


$Log['res']='exito';
terminar($Log);
?>