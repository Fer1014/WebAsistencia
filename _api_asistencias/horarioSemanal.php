<?php
	$con=mysqli_connect("localhost","jyldigit_userw","jhvxQzU+i+h.","jyldigit_widget");
	if (!$con) {
		die("Sin conexion");
	}

	date_default_timezone_set('America/Lima');
	$hoy = date("Y-m-d");
	$codusu=$_GET['codusu'];
	$hora_minuto = date("Hi");

	$sql="SELECT h.dia_semana, h.hora_inicio, h.hora_fin, c.descripcion, l.nombre_aula
  	FROM horarios h, cursos c, aulas l, usuario_cursos uc
 	WHERE h.curso_id = c.curso_id and h.aula_id = l.aula_id and c.curso_id = uc.curso_id
   	AND uc.usuario_id = $codusu 
 	ORDER BY h.dia_semana, h.hora_inicio, c.descripcion";
	$result=mysqli_query($con,$sql);
	$datos=[];
	while ($row=mysqli_fetch_array($result)) {
		array_push($datos,$row);
	}

	header('Content-Type: application/json');
	echo json_encode($datos);