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
	$sql="SELECT distinct a.fecha, a.hora_inicio, a.hora_fin, h.aula_id
	FROM asistencia a, horarios h 
	WHERE a.horario_id = h.horario_id and h.curso_id = $curso_id
	AND (
		a.fecha < now() or (a.fecha = now() and a.hora_inicio < DATE_FORMAT(NOW(), '%H%i'))
	) and a.estado <> 'X'
	ORDER BY a.fecha, a.hora_inicio";
	$result=mysqli_query($con,$sql);
	$fechaHora=[];
	while ($row=mysqli_fetch_array($result)) {
		array_push($fechaHora,$row);
	}
	$response->fechaHora=$fechaHora;

	header('Content-type: application/json');
	echo json_encode($response);