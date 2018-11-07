<?php

echo utf8_encode("
<div id='formacceso'>
	<h1>Bienvenido</h1>
	<a id='cerr' onclick='cerrarAcc(this)'>cerrar</a>
	<a id='info' onclick='verayuda(this)'>ver política de acceso a usuarios</a>
	<div id='dataacceso'>
		<p>DNI</p><input name='dni'>
		<p>Contraseña</p><input type='password' name='password'>
		<a id='acceder' onclick='acceder(this);'>acceder</a>
	</div>
	<div id='dataregistro'>		
		<p>Nombre</p><input name='nombre'>
		<p>Confirmar Contraseña</p><input type='password' name='password2'>
		<p>Apellido</p><input name='apellido'>
		<p>Mail de referencia</p><input name='mail'>
		
		<a id='registrarse' onclick='registrar(this);'>registrarse</a>
	</div>
	<a id='ampliar' onclick='ampliarUsu(this);'>registrarse como nuevo usuario</a>
	
	
	<div id='ayuda'>
		<a id='cerr' onclick='cerrar(this);'>cerrar</a>
		<p>El registro de usuarios tiene por finalidad brindar a los mismos, herramientas para generar información, seguir novedades, y formar equipos de trabajo.</p>
		<p>Actualmente el proyecto no prevé la restriccion de acceso a la información final de cada proyecto.</p>
		<p>De este modo un usuario anónimo tiene acceso a todos los resultados finales de todas la investigaciones.</p>
		<p>De este modo un usuario registrado tiene acceso a la información preliminar de aquellos proyectos específicos de los que forma parte.</p>
		<p>La información suministrada por los usuarios tiene fines meramente operativos internos de esta plataforma y no tiene por finalidad su utilización con otros fines. 
		Dado que esta plataforma se encuentra en desarrollo, las politicas sobre la utilización de esta infromación es suceptible de camibo, pero en ningún caso será utilizada con fines de difusión general ni actividades no académicas</p>
	</div>
</div>
");
?>