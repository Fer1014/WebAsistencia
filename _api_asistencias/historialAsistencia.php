<?php
	$con=mysqli_connect("localhost","jyldigit_userw","jhvxQzU+i+h.","jyldigit_widget");
	if (!$con) {
		die("Sin conexion");
	}

	date_default_timezone_set('America/Lima');
	$hoy = date("Y-m-d");
	$usuario_id=$_GET['codusu'];

	$sql="SELECT c.nombre_curso codcurso, c.descripcion curso, l.nombre_aula aula, concat(u.nombre, ' ' , u.apellido) usuario,
		a.fecha, a.hora_inicio, a.hora_fin, a.hora_marca, if(a.estado_presente,'PRESENTE','AUSENTE') presente,
		if(a.estado_tardanza,'TARDANZA','A TIEMPO') tardanza, a.estado
  		FROM asistencia a, horarios h, aulas l, cursos c, usuarios u 
 		WHERE a.usuario_id = u.usuario_id and a.horario_id = h.horario_id and h.curso_id = c.curso_id and h.aula_id = l.aula_id 
  		AND a.fecha<= '$hoy' and a.usuario_id=$usuario_id
  		order by a.fecha desc, a.hora_inicio desc";

	$result=mysqli_query($con,$sql);
	$datos=[];
	while ($row=mysqli_fetch_array($result)) {
		array_push($datos,$row);
	}

	header('Content-Type: application/json');
	echo json_encode($datos);