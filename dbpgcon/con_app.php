<?php
ini_set('display_errors', true);
chdir('..');
include('./encabezado/encabezado.php');

$func=$_POST['funcion'];
$func();

function cargarAplicaciones(){
	global $ConPg;
$query="
SELECT 
  app_aplicacion.nombre, 
  app_aplicacion.enserver, 
  app_aplicacion.enexterno, 
  app_aplicacion.encliente, 
  app_aplicacion.descripcion, 
  app_aplicacion.id_p_pro_proyectos, 
  app_aplicacion.zz_borrada, 
  app_aplicacion.url, 
  app_aplicacion.id_p_app_estados, 
  app_aplicacion.id, 
  app_aplicacion.responsables_tx,
  app_estados.nombre e_nombre, 
  app_estados.descripcion e_descripcion
FROM 
  unmgeo.app_aplicacion, 
  unmgeo.app_estados
  
 WHERE 
 app_aplicacion.id_p_app_estados = app_estados.id
 AND
 app_aplicacion.zz_borrada=0
ORDER BY
  app_aplicacion.id_p_app_estados DESC;



";

$ConsultaProy = pg_query($ConPg, $query);
if(pg_errormessage($ConPg)!=''){
	$Log['tx'][]='error: '.pg_errormessage($ConPg);
	$Log['tx'][]='query: '.$query;
	echo pg_errormessage($ConPg);
	echo $query;
	$Log['res']='err';
}	
			
while($fila=pg_fetch_assoc($ConsultaProy)){
	$App['a'.$fila["id"]]=$fila;
}

$res['data']=$App;
$res['res']='exito';
	echo json_encode($res);
}


?>
