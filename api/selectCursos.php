<?php
	session_start();
    include('../config/conexion.php');
    $conexion=new conexion();
    $con=$conexion->getconection();
    $response=new stdClass();
    if (!$con) {
    	die("sin conex");
    }

	$sql="Select curso_id,nombre_curso,descripcion from cursos order by descripcion";
	$result=mysqli_query($con,$sql);
	$cursos=[];
	while ($row=mysqli_fetch_array($result)) {
		array_push($cursos,$row);
	}
	$response->cursos=$cursos;

	header('Content-type: application/json');
	echo json_encode($response);