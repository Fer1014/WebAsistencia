<?php
	$con=mysqli_connect("localhost","jyldigit_userw","jhvxQzU+i+h.","jyldigit_widget");
	if (!$con) {
		die("Sin conexion");
	}

	date_default_timezone_set('America/Lima');
	$hoy = date("Y-m-d");
	$codusu=$_GET['codusu'];
	$hora_minuto = date("Hi");

	$sql="SELECT b.curso, count(*) clases, sum(b.presente) + sum(b.tardanza) presente, sum(b.ausente) ausente, sum(b.tardanza) tardanza
  	FROM (
		SELECT a.curso, if(a.asistencia='P',1,0) presente, if(a.asistencia='A',1,0) ausente, if(a.asistencia='T',1,0) tardanza
  		FROM (
			SELECT c.descripcion curso, if(not a.estado_presente,'A', if(a.estado_tardanza,'T','P')) asistencia 
  			FROM asistencia a, horarios h, aulas l, cursos c, usuarios u 
 			WHERE a.usuario_id = u.usuario_id and a.horario_id = h.horario_id and h.curso_id = c.curso_id and h.aula_id = l.aula_id 
  			AND a.usuario_id = $codusu and a.estado <> 'C'
  			AND (a.fecha < '$hoy'
  			OR (
  				a.fecha = '$hoy' and CONVERT(a.hora_inicio, SIGNED INTEGER) <= CONVERT('$hora_minuto', SIGNED INTEGER))
  			)
  		) a
    ) b
 	GROUP BY b.curso";
	$result=mysqli_query($con,$sql);
	$hoy_f = date("d-m-Y");
	$response=[];
	$obj=new stdClass();
	$obj->fecha=$hoy_f;
	array_push($response,$obj);
	$datos=[];
	while ($row=mysqli_fetch_array($result)) {
		array_push($datos,$row);
	}
	array_push($response,$datos);

	header('Content-Type: application/json');
	echo json_encode($response);