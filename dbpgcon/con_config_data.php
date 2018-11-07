<?php
/**
* con_usuario.php
*
* consultas básicas para obtener usuarios
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

$Log['data']=array();
$Log['tx']=array();
$Log['mg']=array();
$Log['res']='';
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

$e=explode(" ",print_r($ConPg,true));
if($e[0]!='Resource'){
	$Log['tx'][]='Error en la conexxión a la base de datos: '.print_r($ConPg,true);
	$Log['tx'][]=$e[0];
	terminar($Log);
}

	
$query="
SELECT 
	dni, id, nombre, apellido, password, mail, sis_adm, zz_reset_cod, zz_reset_time, zz_reset_autor
	FROM unmgeo.usu_usuarios
	order by apellido asc, nombre asc
";
	
$ConsultaUsu = pg_query($ConPg, $query);

if(pg_errormessage($ConPg)!=''){
	$Log['tx'][]=utf8_encode('error: '.pg_errormessage($ConPg));
	$Log['tx'][]='query: '.$query;
	$Log['res']='err';
	terminar($Log);	
}	


while($fila=pg_fetch_assoc($ConsultaUsu)){
	$Log['data']['usuarios'][$fila['id']]=$fila;
	$Log['data']['usuariosOrden'][]=$fila['id'];
	
	if($fila['zz_reset_time']!='0'){
		$antig=time()-$fila['zz_reset_time'];
		
		if($antig>259200){
			$query="
					UPDATE 
						unmgeo.usu_usuarios
					SET
						zz_reset_cod='', 
						zz_reset_time=0, 
						zz_reset_autor=0
					WHERE 
						id = '".$fila['id']."';
			";				
			$ConsultaUsu = pg_query($ConPg, $query);			
			if(pg_errormessage($ConPg)!=''){
				$Log['tx'][]=utf8_encode('error: '.pg_errormessage($ConPg));
				$Log['tx'][]='query: '.$query;
				$Log['res']='err';
				terminar($Log);	
			}	
			
		}
	}
}

$Log['res']='exito';
terminar($Log);	
?>
