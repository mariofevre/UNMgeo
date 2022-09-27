<?php 
/**
*
* consulta AI y su intersecci´no con otros contenidos a partir de una geometría y una distancia
 * 
 *  
* @package    	geoGEC
* @subpackage 	app_docs. Aplicacion para la gestión de documento
* @author     	GEC - Gestión de Espacios Costeros, Facultad de Arquitectura, Diseño y Urbanismo, Universidad de Buenos Aires.
* @author     	<mario@trecc.com.ar>
* @author    	http://www.municipioscosteros.org
* @copyright	2018 Universidad de Buenos Aires
* @license    	http://www.gnu.org/licenses/gpl.html GNU AFFERO GENERAL PUBLIC LICENSE, version 3 (GPL-3.0)
* Este archivo es software libre: tu puedes redistriburlo 
* y/o modificarlo bajo los términos de la "GNU AFFERO GENERAL PUBLIC LICENSE" 
* publicada por la Free Software Foundation, version 3
* 
* Este archivo es distribuido por si mismo y dentro de sus proyectos 
* con el objetivo de ser útil, eficiente, predecible y transparente
* pero SIN NIGUNA GARANTÍA; sin siquiera la garantía implícita de
* CAPACIDAD DE MERCANTILIZACIÓN o utilidad para un propósito particular.
* Consulte la "GNU General Public License" para más detalles.
* 
* Si usted no cuenta con una copia de dicha licencia puede encontrarla aquí: <http://www.gnu.org/licenses/>.
* 
*
*/

//if($_SERVER[SERVER_ADDR]=='192.168.0.252')ini_set('display_errors', '1');ini_set('display_startup_errors', '1');ini_set('suhosin.disable.display_errors','0'); error_reporting(-1);


ini_set('display_errors', true);
chdir('..');
session_start();

include('./comunes/encabezado.php');

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

$oblig=array(
'modo','coordenadas','distancia','tabla'
);

foreach($oblig as $k => $v){
	if(!isset($_POST[$v])){
		$Log['tx'][]='Falta variable: '.$v;
		$Log['res']='err';
		terminar($Log);
	}
}


 
 $query="
SELECT 
	 *,
	 ST_AsText(
		ST_Intersection(
			ST_Transform(
				ST_Buffer(
					ST_Transform(
						ST_GeomFromText('".$_POST['coordenadas']."',3857),
						22175
					), 
					".$_POST['distancia'].", 
					'endcap=round join=round' 
				), 
				3857
			), 
			ST_Transform( 
				capa.geom,
				3857 
			) 
		)
	)as geotx_intersec,
	ST_AsText( 
		ST_Transform( 
			capa.geom,
			3857 
		
		)
	)as geotx,
	ST_Area( 
		ST_Transform( 
			capa.geom,
			22175 
		)
	) area_orig,
	
	ST_Area( 
		ST_Intersection(
			ST_Transform(
				ST_Buffer(
					ST_Transform(
						ST_GeomFromText('".$_POST['coordenadas']."',3857),
						22175
					), 
					".$_POST['distancia'].", 
					'endcap=round join=round' 
				), 
				3857
			), 
			ST_Transform( 
				capa.geom,
				3857 
			) 
		)
	) as area_intersec
	FROM 
		unmgeodata.\"".$_POST['tabla']."\"
	AS 
		capa
	WHERE

		ST_Intersects(
			ST_Buffer( 
				ST_Transform( 		
					ST_GeomFromText('".$_POST['coordenadas']."',3857),
					22175 
				), 
				1000, 
			 'endcap=round join=round'
			),
			ST_Transform( 
				capa.geom,
				22175 
			) 
		)

	
";


		
$Consulta = pg_query($ConPg, $query);
if(pg_errormessage($ConPg)!=''){
	$Log['tx'][]='error: '.pg_errormessage($ConPg);
	$Log['tx'][]='query: '.preg_replace( "/\r|\t|\n/", " ", $query);
	$Log['res']='err';
	terminar($Log);
}


while($fila=pg_fetch_assoc($Consulta)){	
	unset($fila['geom']);
	$Log['data']['interseccion'][]=$fila;
}


$Log['res']='exito';
terminar($Log);		
?>
