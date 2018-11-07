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

$_POST['dni']=str_replace('.','',$_POST['dni']);
$_POST['dni']=str_replace(',','',$_POST['dni']);
$_POST['dni']=str_replace('-','',$_POST['dni']);
$_POST['dni']=str_replace(' ','',$_POST['dni']);

if(strlen($_POST['dni'])!=8){
	$Log['tx'][]=utf8_encode('error, en el dni ingresado.'.$_POST['dni']);
	$Log['res']='err';
	terminar($Log);	
}

if(!isset($_POST['password'])){
	$Log['tx'][]='error, no se registra constrasena.';
	$Log['res']='err';
	terminar($Log);	
}

if(strlen($_POST['password'])<4){
	$Log['tx'][]='la contrasena no alcanza el mínimmo de 4 caracteres.';
	$Log['res']='err';
	terminar($Log);	
}

if($_POST['password2']!==$_POST['password']){
	$Log['tx'][]='error, no coinciden las dos conrtasenas suministradas.';
	$Log['res']='err';
	terminar($Log);	
}

if(!isset($_POST['nombre'])){
	$Log['tx'][]='error, no se registra nombre.';
	$Log['res']='err';
	terminar($Log);	
}

if(!isset($_POST['apellido'])){
	$Log['tx'][]='error, no se registra apellido.';
	$Log['res']='err';
	terminar($Log);	
}

if(!isset($_POST['mail'])){
	$Log['tx'][]='error, no se registra direccion de correo electronico.';
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
	$Log['tx'][]='error: '.pg_errormessage($ConPg);
	$Log['tx'][]='query: '.$query;
	$Log['res']='err';
	terminar($Log);	
}	

if(pg_num_rows($ConsultaUsu)>0){
	$Log['tx'][]='error, ya se encuentra registrado el dni solicitado.';
	$Log['res']='err';
	terminar($Log);	
}



$query="
	INSERT 
		INTO unmgeo.usu_usuarios (
            dni, nombre, apellido, password, mail
    	)
    	
    	VALUES (
    	'".$_POST['dni']."', 
    	'".$_POST['nombre']."', 
    	'".$_POST['apellido']."', 
    	'".md5($_POST['password'])."', 
    	'".$_POST['mail']."' 
    	)
";
		
		
$ConsultaUsu = pg_query($ConPg, $query);

if(pg_errormessage($ConPg)!=''){
	$Log['tx'][]='error: '.pg_errormessage($ConPg);
	$Log['tx'][]='query: '.utf8_encode($query);
	$Log['res']='err';
	terminar($Log);	
}	

$fila=pg_fetch_assoc($ConsultaUsu);

		
$Log['tx'][]='se creo correctamente.';
$Log['res']='exito';
terminar($Log);	