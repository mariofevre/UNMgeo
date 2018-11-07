<?php
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

$e=explode(" ",print_r($ConPg,true));
if($e[0]!='Resource'){
	$Log['tx'][]='Error en la conexxión a la base de datos: '.print_r($ConPg,true);
	$Log['tx'][]=$e[0];
	terminar($Log);
}

if(!isset($_POST['usuid'])){
	$Log['tx'][]='no fue enviada la varaible idpro';
	$Log['res']='err';
	terminar($Log);	
}	

	
$query="
SELECT 
	dni, id, nombre, apellido, password, mail, sis_adm, zz_reset_cod, zz_reset_time, zz_reset_autor
	FROM 
		unmgeo.usu_usuarios
	WHERE
		id='".$_POST['usuid']."'
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

if($fila['zz_reset_time']==''){
	$Log['tx'][]=utf8_encode('error, no hay una solicitud de activación vigente');
	$Log['res']='err';
	terminar($Log);		
}


$antig=time()-$fila['zz_reset_time'];

if($antig>259200){
	
	$Log['tx'][]=utf8_encode('el período válido para la activación a expirado');	
	
	$query="
			UPDATE unmgeo.usu_usuarios
			zz_reset_cod='', zz_reset_time=0, zz_reset_autor=0
			WHERE id = '".$fila['usuid']."';
	";				
	$ConsultaUsu = pg_query($ConPg, $query);			
	if(pg_errormessage($ConPg)!=''){
		$Log['tx'][]=utf8_encode('error: '.pg_errormessage($ConPg));
		$Log['tx'][]='query: '.$query;
		$Log['res']='err';
		terminar($Log);	
	}	
	
	$Log['res']='err';
	terminar($Log);		
}


if($_POST['pass1']!=$_POST['pass2']){
	$Log['tx'][]=utf8_encode('error: no coinciden las dos contraseñas enviadas');
	$Log['tx'][]=$_POST['pass1'].' vs '.$_POST['pass2'];
	$Log['res']='err';
	terminar($Log);		
}

$hatch=md5($_POST['pass1']);
$query="
	UPDATE 
		unmgeo.usu_usuarios
	SET 
		password='".$hatch."', 
		zz_reset_cod='', 
		zz_reset_time=0, 
		zz_reset_autor=0
	WHERE 
		id='".$_POST['usuid']."';
";
$Consulta = pg_query($ConPg, $query);

if(pg_errormessage($ConPg)!=''){
	$Log['tx'][]='error: '.pg_errormessage($ConPg);
	$Log['tx'][]='query: '.$query;
	$Log['res']='err';
	terminar($Log);	
}	

		
$Log['tx'][]='se actualizó correctametne.';
$Log['res']='exito';
terminar($Log);	