<?php
	session_start();
    include('../config/conexion.php');
    $conexion=new conexion();
    $con=$conexion->getconection();
    $response=new stdClass();
    if (!$con) {
    	die("sin conex");
    }

	$aulas=[];
    $aula_id=$_POST['aula_id'];
	$sql="select * from aulas";
	$result=mysqli_query($con,$sql);
	while ($row=mysqli_fetch_array($result)) {
		if ($row['aula_id']==$aula_id) {
			$response->aula=$row['nombre_aula'];
		}
		array_push($aulas,$row);
	}
	$response->aulas=$aulas;

	header('Content-type: application/json');
	echo json_encode($response);