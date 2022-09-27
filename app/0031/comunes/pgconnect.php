<?php
/*
 * Genera la conexion a la base de datos para cada consulta
 */

include('./00settings/dbsettings.php');

$query="host=".$dbsettings['host']." dbname=".$dbsettings['usuario']." user=".$dbsettings['usuario']." password=".$dbsettings['pass']." port=5432";
$ConPg = pg_connect($query)or die('connection failed');
echo pg_errormessage($ConPg);

 

