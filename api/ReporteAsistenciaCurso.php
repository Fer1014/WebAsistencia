<?php
	session_start();
    include('../config/conexion.php');
    $conexion=new conexion();
    $con=$conexion->getconection();
    $response=new stdClass();
    if (!$con) {
    	die("sin conex");
    }

	$consulta1=[];
    $curso_id=$_POST['curso_id'];
	$sql="SELECT b.usuario_id, b.usuario, b.total, b.presente, if(b.total = 0, 0, round(100*b.presente/b.total,2)) por_presente
    , b.total - b.presente ausente, if(b.total = 0, 0, round(100*(b.total - b.presente)/b.total,2)) por_ausente
    , b.tardanza, if(b.total = 0, 0, round(100*b.tardanza/b.total,2)) por_tardanza
  	FROM (
		SELECT a.usuario_id, concat(u.nombre, ' ' , u.apellido) usuario, sum(a.estado_presente) presente, sum(a.estado_tardanza) tardanza, count(*) total
  		FROM asistencia a, horarios h, aulas l, cursos c, usuarios u 
 		WHERE a.usuario_id = u.usuario_id and a.horario_id = h.horario_id and h.curso_id = c.curso_id and h.aula_id = l.aula_id 
   		AND h.curso_id = $curso_id AND a.estado <> 'C' AND  (a.fecha < DATE_FORMAT(NOW(), '%Y-%m-%d') or (a.fecha = DATE_FORMAT(NOW(), '%Y-%m-%d')
   		AND a.hora_inicio <= DATE_FORMAT(NOW(), '%H%i')))
 		GROUP BY a.usuario_id,concat(u.nombre, ' ' , u.apellido)
    ) b
 	ORDER BY b.usuario";
	$result=mysqli_query($con,$sql);
	while ($row=mysqli_fetch_array($result)) {
		array_push($consulta1,$row);
	}
	$response->consulta1=$consulta1;


	$consulta2=[];
	$sql="SELECT t.usuario, t.fecha, t.hora_inicio, m.asistencia
  	FROM
	(
		SELECT a.usuario_id, a.usuario, d.fecha, d.hora_inicio
  		FROM
		(
			SELECT distinct a.fecha, a.hora_inicio
  			FROM asistencia a, horarios h, aulas l, cursos c, usuarios u 
 			WHERE a.usuario_id = u.usuario_id and a.horario_id = h.horario_id and h.curso_id = c.curso_id and h.aula_id = l.aula_id 
			AND h.curso_id = $curso_id AND a.estado <> 'C' AND  (
				a.fecha < DATE_FORMAT(NOW(), '%Y-%m-%d') or (a.fecha = DATE_FORMAT(NOW(), '%Y-%m-%d') AND a.hora_inicio <= DATE_FORMAT(NOW(), '%H%i'))
			)
		) d
		INNER JOIN
		(
			SELECT distinct a.usuario_id, concat(u.nombre, ' ' , u.apellido) usuario
  			FROM asistencia a, horarios h, aulas l, cursos c, usuarios u 
 			WHERE a.usuario_id = u.usuario_id and a.horario_id = h.horario_id and h.curso_id = c.curso_id and h.aula_id = l.aula_id 
			AND h.curso_id = $curso_id AND a.estado <> 'C' AND  (a.fecha < DATE_FORMAT(NOW(), '%Y-%m-%d') or (a.fecha = DATE_FORMAT(NOW(), '%Y-%m-%d') AND a.hora_inicio <= DATE_FORMAT(NOW(), '%H%i')))
		) a
	) t
	LEFT JOIN
	(
		SELECT a.usuario_id, a.fecha, a.hora_inicio, if(not a.estado_presente,'AUS', if(a.estado_tardanza,'TAR','PRE')) asistencia
  		FROM asistencia a, horarios h, aulas l, cursos c, usuarios u 
 		WHERE a.usuario_id = u.usuario_id and a.horario_id = h.horario_id and h.curso_id = c.curso_id and h.aula_id = l.aula_id 
		AND h.curso_id = $curso_id AND a.estado <> 'C' AND  (a.fecha < DATE_FORMAT(NOW(), '%Y-%m-%d') or (a.fecha = DATE_FORMAT(NOW(), '%Y-%m-%d') AND a.hora_inicio <= DATE_FORMAT(NOW(), '%H%i')))
	) m 
	ON t.usuario_id = m.usuario_id and t.fecha = m.fecha and t.hora_inicio = m.hora_inicio
	ORDER BY t.usuario, t.fecha, t.hora_inicio";
	$result=mysqli_query($con,$sql);
	while ($row=mysqli_fetch_array($result)) {
		array_push($consulta2,$row);
	}
	$response->consulta2=$consulta2;

	header('Content-type: application/json');
	echo json_encode($response);