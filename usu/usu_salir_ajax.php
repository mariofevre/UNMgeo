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

unset($_SESSION["unmgeo"]["usuario"]);
		
$Log['tx'][]='se salio correctamente.';
$Log['res']='exito';
terminar($Log);	