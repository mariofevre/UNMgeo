<?php
session_destroy();
session_start();

ini_set('display_errors', true);
chdir('..');
include('./encabezado/encabezado.php');

$Log=array();
function terminar($Log){
	$res=json_encode($Log);
	if($res==''){
		print_r($Log);
	}else{
		echo $res;
	}
	exit;
}

if(!isset($_POST['dni'])){
	$Log['tx'][]='error, no se registra dni.';
	$Log['res']='err';
	terminar($Log);	
}

if(!isset($_POST['password'])){
	$Log['tx'][]='error, no se registra constrasena.';
	$Log['res']='err';
	terminar($Log);	
}


$query="
	SELECT 
		usu_usuarios.*
	FROM
		unmgeo.usu_usuarios
	WHERE  dni='".$_POST['dni']."'";
/*$link=mysql_connect($server,$dbuser,$dbpass);
$result=mysql_db_query($database,$query,$link);*/

$ConsultaUsu = pg_query($ConPg, $query);

if(pg_errormessage($ConPg)!=''){
	$Log['tx'][]=utf8_encode('error: '.pg_errormessage($ConPg));
	$Log['tx'][]='query: '.$query;
	$Log['res']='err';
	terminar($Log);	
}	

if(pg_num_rows($ConsultaUsu)!=1){
	$Log['tx'][]=utf8_encode('error, no se registra usuario asociado al dni ingresado.');
	$Log['res']='err';
	terminar($Log);	
}

$fila=pg_fetch_assoc($ConsultaUsu);

if($fila['password']!=md5($_POST['password'])){
	$Log['tx'][]=utf8_encode('la contraseña no coincide con nuestro registro.');
	$Log['res']='err';
	terminar($Log);	
}
	
$_SESSION["unmgeo"]["usuario"]=$fila;
unset($_SESSION["unmgeo"]["usuario"]['password']);

foreach($fila as $k => $v){
	$filaD[$k]=utf8_encode($v);
}
		
$Log['tx'][]='se accedio correctamente.';
$Log['data']=$filaD;
$Log['res']='exito';
terminar($Log);	