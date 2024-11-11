<?php
	session_start();
    include('../config/conexion.php');
    $conexion=new conexion();
    $con=$conexion->getconection();
    $response=new stdClass();
    if (!$con) {
    	die("sin conex");
    }

	$sql="Select usuario_id,CONCAT(nombre,' ',apellido) nombre from usuarios where rol_id=1;";
	$result=mysqli_query($con,$sql);
	$alumnos=[];
	while ($row=mysqli_fetch_array($result)) {
		array_push($alumnos,$row);
	}
	$response->alumnos=$alumnos;

	header('Content-type: application/json');
	echo json_encode($response);