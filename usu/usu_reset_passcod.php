<?php
ini_set('display_errors', true);
chdir('..'); 
include('./encabezado/encabezado.php');
include('./comunes/cadenas.php');

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

if($_SESSION["unmgeo"]["usuario"]['sis_adm']!='1'){
	$Log['tx'][]='esta accion solo está validad para administradores de sistema';
	$Log['res']='err';
	terminar($Log);		
}

if(!isset($_POST['idusu'])){
	$Log['tx'][]='no fue enviada la varaible idusu';
	$Log['res']='err';
	terminar($Log);	
}	

	
$query="
SELECT 
	dni, id, nombre, apellido, password, mail, sis_adm, zz_reset_cod, zz_reset_time, zz_reset_autor
	FROM 
		unmgeo.usu_usuarios
	WHERE
		id='".$_POST['idusu']."'
";
$Consulta = pg_query($ConPg, $query);
if(pg_errormessage($ConPg)!=''){
	$Log['tx'][]=utf8_encode('error: '.pg_errormessage($ConPg));
	$Log['tx'][]='query: '.$query;
	$Log['res']='err';
	terminar($Log);	
}	
$fila=pg_fetch_assoc($Consulta);

if(pg_num_rows($Consulta)<1){
	$Log['tx'][]=utf8_encode('error, en la identificación del usuario solicitado');
	$Log['res']='err';
	terminar($Log);		
}


$Log['data']['cod']=cadenaArchivo(8);
$Log['data']['mail']=$fila['mail'];
$Log['data']['idusu']=$_POST['idusu'];
$query="
	UPDATE unmgeo.usu_usuarios
	SET 
	zz_reset_cod='".$Log['data']['cod']."', zz_reset_time='".time()."', zz_reset_autor='".$dataU["id"]."'
	WHERE id='".$_POST['idusu']."';
";
$Consulta = pg_query($ConPg, $query);

if(pg_errormessage($ConPg)!=''){
	$Log['tx'][]='error: '.pg_errormessage($ConPg);
	$Log['tx'][]='query: '.$query;
	$Log['res']='err';
	terminar($Log);	
}	

		
$Log['tx'][]='se creo correctamente.';
$Log['res']='exito';
terminar($Log);	