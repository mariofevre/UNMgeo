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
include('./comunes/cadenas.php');


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
if($_SESSION["unmgeo"]["usuario"]['sis_adm']!='1'){
	$Log['tx'][]='esta accion solo está validad para administradores de sistema';
	$Log['res']='err';
	terminar($Log);		
}

if(!isset($_POST['idpro'])){
	$Log['tx'][]='no fue enviada la varaible idpro';
	$Log['tx'][]=print_r($_POST,true);
	$Log['res']='err';
	terminar($Log);	
}	


if(!isset($_POST['nfile'])){
	$Log['tx'][]='no fue definido el tipo de contenido';
	$Log['res']='err';
	terminar($Log);
}


if(!isset($_FILES['upload'])){
	$Log['tx'][]='no fue enviada la imagen en la variable FILES[upload]';
	$Log['res']='err';
	terminar($Log);
}
if(!file_exists($_FILES['upload']['tmp_name'])){
	$Log['tx'][]='el archivo enviado no pudo ser guardado en el servidor';
	$Log['res']='err';
	terminar($Log);
}

	$Log['tx'][]= "archivo enviado";
	
	$ArchivoOrig = $_FILES['upload']['name'];	
	$Log['tx'][]= "cargando: ".$ArchivoOrig;
	$Log['tx'][]= print_r($_FILES['upload'],true);
	
	$b = explode(".",$ArchivoOrig);
	$ext = strtolower($b[(count($b)-1)]);	
	
	
	$idmarcpad=str_pad($_POST['idpro'],8,"0",STR_PAD_LEFT);
	$PathBase="./documentos/pro/".$idmarcpad."/docs/";
	
	$path=$PathBase;
	$carpetas= explode("/",$path);	
	$rutaacumulada="";			
	foreach($carpetas as $valor){		
	$Log['tx'][]= "instancia de ruta: $valor ";
	$rutaacumulada.=$valor."/";
		if (!file_exists($rutaacumulada)&&$valor!=''){
			$Log['tx'][]="creando: $rutaacumulada ";
		    mkdir($rutaacumulada, 0777, true);
		    chmod($rutaacumulada, 0777);
		}
	}		
	// FIN verificar y crear directorio				
										
	$nombretipo = "pro_doc_[NID]_";
	$nombre=$nombretipo;
	$nombreprliminar='si';//indica que el documento debe ser renombrado luego de creado el registro.			
	
	$c=explode('.',$nombre);

	$cod = cadenaArchivo(10); // define un código que evita la predictivilidad de los documentos ante búsquedas maliciosas
	$nombre=$path.$c[0].$cod.".".$ext;
	
	/*
	$extVal['jpg']='1';
	$extVal['png']='1';
	$extVal['tif']='1';
	$extVal['bmp']='1';
	$extVal['gif']='1';
	//$extVal['pdf']='1';
	//$extVal['zip']='1';
	*/
	
	//if(isset($extVal[strtolower($ext)])){
		$Log['tx'][]= "guardado en: ".$nombre."<br>";
		
		if (!copy($_FILES['upload']['tmp_name'], $nombre)) {
		   	$Log['tx'][]= "Error al copiar ".$_FILES['upload']['tmp_name']." en ".$nombre."...\n";
			$Log['res']='err';
			terminar($Log);
		}else{
			chmod($nombre, 0777);
			$Log['tx'][]= "archivo guardado";
		}
	/*}else{
		$ms="solo se aceptan los formatos:";
		foreach($extVal as $k => $v){$ms.=" $k,";}
		$Log['mg'][]= $ms;
		$ArchivoOrig='';
		$Log['res']='err';
		terminar($Log);
	}*/	
		
	$query="
	INSERT INTO 
		unmgeo.pro_documentos(
			archivo,
			nombre,
			id_p_pro_proyectos
		)
		VALUES(
			'".$nombre."',
			'".$ArchivoOrig."',
			'".$_POST['idpro']."'	
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
		$NID=$fila['id'];
		$Log['tx'][]='item creado, id:'.$NID;
		$Log['data']['nid']=$NID;
	}
	
	
	$nuevonombre=str_replace("[NID]", $NID, $nombre);
	$Log['data']['ruta']=$nuevonombre;
	
	if(!rename($nombre,$nuevonombre)){		
	 	$Log['tx'][]=" error al renombrar el documento ".$origen['nombre']." con el nuevo id => $nuevonombre";
		$Log['res']='err';
		terminar($Log);	
	}else{
	 	$query="
	 		UPDATE 
	 			unmgeo.pro_documentos
	 		SET 
	 			archivo = '".$nuevonombre."'
	 		WHERE
	 			id='".$NID."'
	 			AND
	 			id_p_pro_proyectos = '".$_POST['idpro']."'
	 	";
	 	
		 pg_query($ConPg, $query);
	
		if(pg_errormessage($ConPg)!=''){
			$Log['tx'][]='error: '.pg_errormessage($ConPg);
			$Log['tx'][]='query: '.$query;
			$Log['mg'][]='error interno';
			$Log['res']='err';
			terminar($Log);	
		}	
	}

//echo $query;
$Log['data']['nid']=$NID;
$Log['data']['nf']=$_POST['nfile'];
$Log['data']['ruta']=$nuevonombre;
$Log['tx'][]='completado';
$Log['res']='exito';
terminar($Log);
?>