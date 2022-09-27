<?php

/**
* guarda_adjunto.php
*
* recibe y guarda archivos y genera un registro, si puede genera una vista en miniatura.
* 
* @package    	UNMGEO app 15 / catalogo .
* @author    	UNM UNMGEO
* @author     	<mario@trecc.com.ar> <trecc@trecc.com.ar>
* @copyright	2018 - 2022 UNM
* @copyright	derivado de 2013 - 2019 TReCC SA
* @license    	http://www.gnu.org/licenses/agpl.html GNU Affero General Public License, version 3 (AGPL-3.0)
* Este archivo es parte de TReCC(tm) paneldecontrol y de sus proyectos hermanos: baseobra(tm) y TReCC(tm) intraTReCC.
* Este archivo es software libre: tu puedes redistriburlo 
* y/o modificarlo bajo los términos de la "GNU Affero General Public License" 
* publicada por la Free Software Foundation, version 3
* 
* Este archivo es distribuido por si mismo y dentro de sus proyectos 
* con el objetivo de ser útil, eficiente, predecible y transparente
* pero SIN NIGUNA GARANTÍA; sin siquiera la garantía implícita de
* CAPACIDAD DE MERCANTILIZACIÓN o utilidad para un propósito particular.
* Consulte la "GNU Affero General Public License" para más detalles.
* 
* Si usted no cuenta con una copia de dicha licencia puede encontrarla aquí: <http://www.gnu.org/licenses/>.
*/

ini_set('display_errors', true);
chdir('..');
session_start();

include('./encabezado/encabezado.php');
include_once('./comunes/cadenas.php');



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




if(!isset($_SESSION["unmgeo"])){
	$Log['mg'][]="error al identificar su usuario. Por favor Logueese en UNMgeo";
	$Log['tx'][]="error al identificar su usuario";
	$Log['res']='err';
	terminar($Log);
}
if($_SESSION["unmgeo"]["usuario"]["id"]<1){
	$Log['mg'][]="error al identificar su usuario. Por favor Logueese en UNMgeo";
	$Log['tx'][]="error al identificar su usuario";
	$Log['res']='err';
	terminar($Log);
}
//TODO verificar si tiene permisos en el Proyecto 26 (o del proyecto vinculado a la app 11) para permitir la edición de la app 0011.




$varsOblig=Array(
	'idref',
	'tabla'
);

foreach($varsOblig as $v){
	if(!isset($_POST[$v])){
		$Log['mg'][]="error, falta variable $v ";
		$Log['tx'][]="error, falta variable $v ";
		$Log['res']='err';
		terminar($Log);
	}	
}


$tablasOk=Array(
	'obr_documentacion' => 'obr_obras'
);

if(!isset($tablasOk[$_POST['tabla']])){
	$Log['mg'][]="error, tabla invalida ".$_POST['tabla'];
	$Log['tx'][]="error, tabla invalida ".$_POST['tabla'];
	$Log['res']='err';
	terminar($Log);
}
$Tabla=$_POST['tabla'];



$HOY=date("Y-m-d");



if(!isset($_POST['nfile'])){
	$Log['tx'][]='no fue definido el tipo de contenido';
	$Log['res']='err';
	terminar($Log);
}
$Log['data']['nf']= $_POST['nfile'];

if(!isset($_FILES['upload'])){
	$Log['tx'][]='no fue enviada el documento en la variable FILES[upload]';
	$Log['res']='err';
	terminar($Log);
}



$Log['tx'][]= "archivo enviado";

$ArchivoOrig = $_FILES['upload']['name'];	
$Log['tx'][]= "cargando: ".$ArchivoOrig;

$b = explode(".",$ArchivoOrig);
$ext = strtolower($b[(count($b)-1)]);	



$PathBase="./documentos/".$tablasOk[$_POST['tabla']].'/'.str_pad($_POST['idref'], 8, "0", STR_PAD_LEFT).'/';
$path=$PathBase.'originales/';
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
								
$nombreprliminar='si';//indica que el documento debe ser renombrado luego de creado el registro.			

$cod = cadenaArchivo(10); // define un código que evita la predictivilidad de los documentos ante búsquedas maliciosas
$nombre='[NID]'.$cod.".".$ext;
$ruta=$path.$nombre;

$extVal['jpg']='1';
$extVal['jpeg']='1';
$extVal['png']='1';
$extVal['tif']='1';
$extVal['tiff']='1';
$extVal['bmp']='1';
$extVal['gif']='1';

$Tipo='';
if(isset($extVal[strtolower($ext)])){
	$Log['tx'][]= "tipo Imagen";	
	$Tipo="img";	
}	

if(strtolower($ext)=='pdf'){
	$Log['tx'][]= "tipo pdf";	
	$Tipo="pdf";	
}	

unset($extVal);
$extVal['xls']='1';
$extVal['xlsx']='1';
$extVal['ods']='1';
if(isset($extVal[strtolower($ext)])){
	$Log['tx'][]= "tipo hoja de calculo";	
	$Tipo="calc";	
}	

unset($extVal);	
$extVal['doc']='1';
$extVal['docx']='1';
$extVal['odt']='1';
if(isset($extVal[strtolower($ext)])){
	$Log['tx'][]= "tipo texto";	
	$Tipo="tx";	
}	
	
unset($extVal);
$extVal['dwg']='1';
$extVal['dxf']='1';
if(isset($extVal[strtolower($ext)])){
	$Log['tx'][]= "tipo cad";	
	$Tipo="cad";	
}	

unset($extVal);
$extVal['ppt']='1';
$extVal['pptx']='1';
$extVal['odp']='1';
if(isset($extVal[strtolower($ext)])){
	$Log['tx'][]= "tipo presentacion";	
	$Tipo="pres";	
}		
			
			
if (!copy($_FILES['upload']['tmp_name'], $ruta)) {	
	$Log['tx'][]="Error al copiar $ruta";
	$Log['res']="err";
	terminar($Log);		
}
	
	//$extVal['pdf']='1';
//$extVal['zip']='1';

/*}else{
	$ms="solo se aceptan los formatos:";
	foreach($extVal as $k => $v){$ms.=" $k,";}
	$Log['mg'][]= $ms;
	$ArchivoOrig='';
	$Log['res']='err';
	terminar($Log);
}*/	



$Log['data']['adjunto']['idref']=$_POST['idref'];
$Log['data']['adjunto']['fi_documento']=$nombre;
$Log['data']['adjunto']['fi_tipo']=$Tipo;
$Log['data']['adjunto']['fi_extension']=$ext;
$Log['data']['adjunto']['fi_original']=$ArchivoOrig;
$Log['data']['adjunto']['zz_auto_fechau_creacion']=$HOY;
//$Log['data']['adjunto']['zz_auto_usu_creacion']=$_SESSION["unmgeo"]["usuario"]["id"];

$camporef='id_p_'.$tablasOk[$_POST['tabla']];

$query="
INSERT INTO 
		unmgeo_app0030.$Tabla(
			".$camporef.",
			fi_documento,
			fi_tipo,
			fi_extension,
			fi_original,
			zz_auto_fechau_creacion,
			zz_auto_usu_creacion			
		
		)VALUES(			 
			'".$_POST['idref']."',
			'".$nombre."',
			'".$Tipo."',
			'".$ext."',
			'".$ArchivoOrig."',
			'".time()."',
			'".$_SESSION["unmgeo"]["usuario"]["id"]."'	 
		 ) 		
		 
	RETURNING id
";
$Consulta = pg_query($ConPg, $query);
if(pg_errormessage($ConPg)!=''){
	$Log['tx'][]='error: '.pg_errormessage($ConPg);
	$Log['tx'][]='query: '.$query;
	$Log['res']='err';
	terminar($Log);
}

$fila=pg_fetch_assoc($Consulta);
$NID = $fila['id'];
if($NID<1){
		$Log['tx'][]='error: '.pg_errormessage($ConPg);
		$Log['tx'][]='query: '.$query;
		$Log['res']='err';
		terminar($Log);
}

$nuevonombre=str_replace("[NID]", $NID, $nombre);
$path=$PathBase.'originales/';
$nuevaruta=$path.$nuevonombre;

$Log['data']['adjunto']['id']=$NID;
$Log['data']['adjunto']['fi_documento']=$nuevonombre;
$Log['data']['adjunto']['fi_muestra']='./img/file_'.$Tipo.'.png';

$Log['data']['ruta']=$nuevonombre;

if(!rename($ruta,$nuevaruta)){		
	$Log['tx'][]=" error al renombrar el documento ".$origen['nombre']." con el nuevo id => $nuevonombre";
	$Log['res']='err';
	terminar($Log);	
}else{
	$query="
		UPDATE 
			unmgeo_app0030.$Tabla 
		SET 
			fi_documento = '$nuevonombre' 
		WHERE
			id='$NID'
	";
	$Consulta = pg_query($ConPg, $query);
	if(pg_errormessage($ConPg)!=''){
		$Log['tx'][]='error: '.pg_errormessage($ConPg);
		$Log['tx'][]='query: '.$query;
		$Log['res']='err';
		terminar($Log);
	}
}


$path=$PathBase.'muestras/';
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


if($Tipo == 'img'){
	
	$ladomayor=50;					
		//echo "<br>procesando: ".iconv('IBM850','ISO-8859-2', $file).filesize ($carp.$file)." -> ";
	$i = getimagesize($nuevaruta);	
	
		
	if($i[0]>$ladomayor*1.3&&$i[1]>$ladomayor*1.3){
		
		$filterType=imagick::FILTER_BOX;
		$blur=0;
		$bestFit=0;
		$cropZoom=0;
		
		$imagick = new \Imagick(realpath($nuevaruta));
		
		$ancho = $imagick->getImageWidth();
		$alto = $imagick->getImageHeight();	
		if($ancho>($ladomayor*1.3)&&$alto>($ladomayor*1.3)){
			if($ancho<$alto){
				$nancho=$ladomayor;
				$nalto=$alto*$nancho/$ancho;			
			}else{
				$nalto=$ladomayor;
				$nancho=$ancho*$nalto/$alto;
			}		
			$imagick->resizeImage($nancho, $nalto, $filterType, $blur, $bestFit);						    
		   // echo "<br>destino: ".$destino.$file. ": $carpetaTemporalA,$carpetaLocal,$carp";
			$imagick->writeImage ($path.$nuevonombre);
			chmod($path.$nuevonombre,0777);
		}
	}
	//$Log['tx'][]= filesize($PathMuestra.$nuevonombre);
	
	$Log['tx'][]='generada miniatura de imagen';
	
	
	$query="
		UPDATE 
			unmgeo_app0030.$Tabla 
		SET 
			fi_muestra = '".$nuevonombre."' 
		WHERE
			id='$NID'
	";
	$Consulta = pg_query($ConPg, $query);
	if(pg_errormessage($ConPg)!=''){
		$Log['tx'][]='error: '.pg_errormessage($ConPg);
		$Log['tx'][]='query: '.$query;
		$Log['res']='err';
		terminar($Log);
	}	
	$Log['data']['adjunto']['fi_muestra']=$nuevonombre;
}elseif($Tipo=='pdf'){
		
	$img = new Imagick();

	if(!isset($_POST['res'])){$_POST['res']='100';}
	if(!isset($_POST['qty'])){$_POST['qty']='80';}
	
	if(!is_numeric($_POST['res'])){
		$Log['mg'][]='la resolución enviada no es numérica.'.$_POST['res'];
		$Log['res']='err';
		terminar($Log);
	}
	
	if(!is_numeric($_POST['qty'])){
		$Log['mg'][]='la resolución enviada no es numérica.'.$_POST['qty'];
		$Log['res']='err';
		terminar($Log);
	}

	$img->setResolution($_POST['res'], $_POST['res']);
	$img->setCompressionQuality($_POST['qty']); 	
	
	$img->readImage("{$nuevaruta}[0]");
	
	$nombrepdfmuestra=str_replace($ext, 'jpg', $nuevonombre);
	
	$img->writeImage($path.$nombrepdfmuestra);	
			
	$Log['tx'][]='generada miniatura de pdf';
	
	$query="
		UPDATE 
			unmgeo_app0030.$Tabla 
		SET 
			fi_muestra = '".$nombrepdfmuestra."' 
		WHERE
			id='$NID'
	";
	$Consulta = pg_query($ConPg, $query);
	if(pg_errormessage($ConPg)!=''){
		$Log['tx'][]='error: '.pg_errormessage($ConPg);
		$Log['tx'][]='query: '.$query;
		$Log['res']='err';
		terminar($Log);
	}
		
	$Log['data']['adjunto']['fi_muestra']=$nombrepdfmuestra;
	
}elseif($ext=='odt'||$ext=='ods'){
	
	
	$TEMP=sys_get_temp_dir();			
	$comando='unzip '.$nuevaruta.' -d '.$TEMP;
	exec($comando,$exec_res);
	$Log['tx'][]=$comando;
	$nombreODmuestra=str_replace($ext, 'jpg', $nuevonombre);
	
	$scan=scandir($TEMP);
	
	
	if(copy($TEMP.'/Thumbnails/thumbnail.png', $path.$nombreODmuestra)){	
		
		$query="
			UPDATE 
				unmgeo_app0030.$Tabla 
			SET 
				fi_muestra = '".$nombreODmuestra."' 
			WHERE
				id='$NID'
		";
		
		$Consulta = pg_query($ConPg, $query);
		if(pg_errormessage($ConPg)!=''){
			$Log['tx'][]='error: '.pg_errormessage($ConPg);
			$Log['tx'][]='query: '.$query;
			$Log['res']='err';
			terminar($Log);
		}
				
		$Log['data']['adjunto']['fi_muestra']=$nombreODmuestra;	
			
	}else{
		$Log['tx'][]="Error al copiar $path.$nombreODmuestra";
	}
}



$query="
	SELECT 
		*	
	FROM 
		unmgeo_app0030.".$_POST['tabla']."
	WHERE
			id='".$NID."'
		
	";
	
$Consulta = pg_query($ConPg, $query);
if(pg_errormessage($ConPg)!=''){
	$Log['tx'][]='error: '.pg_errormessage($ConPg);
	$Log['tx'][]='query: '.$query;
	$Log['res']='err';
	terminar($Log);
}
$Log['data']['datadoc']=pg_fetch_assoc($Consulta);


$Log['data']['nid']=$NID;
$Log['data']['nf']=$_POST['nfile'];
$Log['data']['ruta']=$nuevonombre;
$Log['tx'][]='completado';
$Log['res']='exito';

terminar($Log);
?>
