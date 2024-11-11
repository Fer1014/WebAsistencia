<?php
	session_start();
    include('../config/conexion.php');
    $conexion=new conexion();
    $con=$conexion->getconection();
    $response=new stdClass();
    if (!$con) {
    	die("sin conex");
    }

    $curso_id=$_POST['curso_id'];
    $fecha=$_POST['fecha'];
	$sql="SELECT u.usuario_id,concat(u.nombre, ' ' , u.apellido) usuario, a.hora_marca, if(not a.estado_presente,'AUSENTE', if(a.estado_tardanza,'TARDANZA','PRESENTE')) asistencia,a.asistencia_id
  	FROM asistencia a, horarios h, aulas l, cursos c, usuarios u 
 	WHERE a.usuario_id = u.usuario_id and a.horario_id = h.horario_id and h.curso_id = c.curso_id and h.aula_id = l.aula_id 
  	AND a.fecha = '$fecha' AND h.curso_id = $curso_id
 	ORDER BY a.fecha desc, a.hora_inicio desc";
	$result=mysqli_query($con,$sql);
	$alumnos=[];
	while ($row=mysqli_fetch_array($result)) {
		array_push($alumnos,$row);
	}
	$response->alumnos=$alumnos;

	header('Content-type: application/json');
	echo json_encode($response);