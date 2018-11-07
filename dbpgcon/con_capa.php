<?php
/**
* con_capas.php
*
* consultas básicas para la gestion de capas
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
session_start();
ini_set('display_errors', true);
chdir('..');

include('./encabezado/encabezado.php');



$Log['tx']=array();
$Log['mg']=array();
$Log['res']='';
$Log['data']=array();

function terminar($Log){
	$res=json_encode($Log);
	if($res==''){
		echo print_r($Log,true);
	}
	echo $res;
	exit();
}

if(!isset($_POST['id'])){
	$Log['tx'][]="no se ha recibido la variable id";
	$Log['mg'][]=utf8_encode("la información de esta capa no se encuentra disponible. #556456rf");
	$Log['res']='err';
	terminar($Log);
}


	
	$query='
	SELECT 
		id, 
		nombre, 
		descripcion, 
		codigo, 
		id_p_pro_proyectos, 
		zz_borrada, 
		zz_auto_insert_usu, 
		zz_auto_insert_fechau, 
		zz_auto_borra_usu, 
		zz_auto_borra_fechau
	  FROM 
	  	unmgeo.dat_capas
	  WHERE
		id = '.$_POST['id'].'
	';
	
	$Consulta = pg_query($ConPg, $query);
	if(pg_errormessage($ConPg)!=''){
		$Log['tx'][]='error: '.pg_errormessage($ConPg);
		$Log['tx'][]='query: '.$query;
		$Log['res']='err';
		terminar($Log);
	}	
	if(pg_num_rows($Consulta)<1){
		$Log['tx'][]=$query;
		$Log['tx'][]="no se ha identificado el id en la base recibido la variable id: ".$_POST['id'];
		$Log['mg'][]=utf8_encode("la información de esta capa no se encuentra disponible. #556456rf");
		$Log['res']='err';
		terminar($Log);	
	}
	
	$fila=pg_fetch_assoc($Consulta);
	foreach($fila as $k => $v){
		$Log['data']['capa'][$k]=$v;
	}
	
	$Log['data']['texto']='';
	$Log['data']['docs']=array();
	$Log['data']['metadatos']=array();
	$Log['data']['usuarioresponsable']='no';
	
	
	$_POST['idpro']=$fila['id_p_pro_proyectos'];
	
	include('./dbpgcon/con_interna_proy_resp.php');
	
	if(isset($_SESSION["unmgeo"]["usuario"]["id"])){
        if(isset($Log['data']['resp'][$_SESSION["unmgeo"]["usuario"]["id"]])){
            $Log['data']['usuarioresponsable']='si';
        }
	}
	
	$query='
	SELECT 
		ultimaversion, 
		id, 
		versiones, 
		id_b_unmgeo_pro_proyectos
	  FROM 
	  	unmgeodata."000_indice"
	   WHERE
		id = '.$_POST['id'].'
		';
	
	$Consulta = pg_query($ConPg, $query);
	if(pg_errormessage($ConPg)!=''){
		$Log['tx'][]='error: '.pg_errormessage($ConPg);
		$Log['tx'][]='query: '.$query;
		$Log['res']='err';
		terminar($Log);
	}	
	if(pg_num_rows($Consulta)<1){
		$Log['tx'][]=$query;	
		$Log['tx'][]="no se ha identificado el id en la base recibido la variable id: ".$_POST['id'];
		$Log['mg'][]=utf8_encode("la información de esta capa no se encuentra disponible. #5gg456rf");
		$Log['res']='err';
		terminar($Log);	
	}
	$fila=pg_fetch_assoc($Consulta);
	foreach($fila as $k => $v){
		$Log['data']['capa']['geodata'][$k]=utf8_encode($v);
	}
	
	
	
	$query="
	SELECT 
	id, usu_autor, fechau, zz_borrada, zz_publicada, instrucciones, fi_prj, zz_obsoleto, id_p_capa, nombre, disponible_wms
	FROM 
        unmgeo.dat_versiones
	  WHERE
	   id_p_capa = '".$_POST['id']."'
	   AND
	   nombre = '".$Log['data']['capa']['geodata']['ultimaversion']."'
	 ";
	
	$Consulta = pg_query($ConPg, $query);
	if(pg_errormessage($ConPg)!=''){
		$Log['tx'][]='error: '.pg_errormessage($ConPg);
		$Log['tx'][]='query: '.$query;
		$Log['res']='err';
		terminar($Log);
	}
	if(pg_num_rows($Consulta)>0){
        $fila=pg_fetch_assoc($Consulta);
        foreach($fila as $k => $v){
            $Log['data']['capa']['ultimaversion'][$k]=utf8_encode($v);
        }    
	}
	//print_r($Capas);
		
	if(isset($Log['data']['capa']['ultimaversion'])){
        $query="
            SELECT 
                id, id_p_capa, id_p_proyecto, id_p_version, 
                a1_titulo, a2_fechadereferencia, \"a2.1_tipodefecharef\", a3_edicion, a4_resumen, a5_estado, 
                a6_contactodato, a7_contactometadato, a8_frecuencia, a9_tema, a10_palabrasclave, 
                a11_restricciones, a12_tipo, a13_escala, a14_idioma, a15_encode, 
                a16_extensiontemporal, a17_extensiongeografica, a18_descripcion, 
                b1_proyeccion, 
                c1_enlace, c2_protocolo, c3_nombreenlace, c4_descripcionenlace, d1_linaje, 
                e1_idnumerico, e2_identificadormetadatos, e3_versionmetadatos, 
                e4_idiomametadatos, e5_encodematadatos, e6_fechametadato
        FROM 
            unmgeo.dat_metadatos_idera2
        WHERE
            id_p_capa='".$_POST['id']."'
            AND
            id_p_proyecto='".$Log['data']['capa']['id_p_pro_proyectos']."'
            AND
            id_p_version='".$Log['data']['capa']['ultimaversion']['id']."'
        ";
        $Consulta = pg_query($ConPg, $query);
        if(pg_errormessage($ConPg)!=''){
            $Log['tx'][]='error: '.pg_errormessage($ConPg);
            $Log['tx'][]='query: '.$query;
            $Log['res']='err';
            terminar($Log);
        }
        while($fila=pg_fetch_assoc($Consulta)){
            foreach($fila as $k => $v){
                $Log['data']['metadatos'][$fila['id']][$k]=utf8_encode($v);
            }    
        }	
	}
	
	
	$query='
	SELECT 
		id, 
		texto, 
		zz_borrada, 
		zz_auto_insert_usu, 
		zz_auto_insert_fechau, 
	   zz_auto_borra_usu, 
	   zz_auto_borra_fechau, 
	   id_p_dat_capas
	  FROM 
	  		unmgeo.dat_documentacion
	  WHERE
	   	id_p_dat_capas = '.$_POST['id'].'
	   AND
	  	 zz_borrada=0
	 ';
	
	$Consulta = pg_query($ConPg, $query);
	if(pg_errormessage($ConPg)!=''){
		$Log['tx'][]='error: '.pg_errormessage($ConPg);
		$Log['tx'][]='query: '.$query;
		$Log['res']='err';
		terminar($Log);
	}	
		
	if(pg_num_rows($Consulta)>0){
		$fila=pg_fetch_assoc($Consulta);
		$Log['data']['texto']=$fila['texto'];
	}
	
	
	
	$query='
		SELECT 
			id, 
			id_dat_capas, 
			ruta, 
			zz_auto_insert_usu, 
			zz_auto_insert_fechau, 
		       zz_borrada, 
		       archivo, 
		       zz_auto_borra_usu, 
		       zz_auto_borra_fechau
		  FROM 
		  	unmgeo.dat_docs
		
		   WHERE
			id_dat_capas = '.$_POST['id'].'
			AND
		 	zz_borrada=0
	';
	
	$Consulta = pg_query($ConPg, $query);
	if(pg_errormessage($ConPg)!=''){
		$Log['tx'][]='error: '.pg_errormessage($ConPg);
		$Log['tx'][]='query: '.$query;
		$Log['res']='err';
		terminar($Log);
	}	
	
	while($fila=pg_fetch_assoc($Consulta)){
		foreach($fila as $k => $v){
			$Log['data']['docs'][$fila["id"]][$k]=utf8_encode($v);
		}
	}
	
	
	
	

	$Log['res']='exito';
	terminar($Log);



?>
