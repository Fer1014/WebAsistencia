<?php
	session_start();
    include('../config/conexion.php');
    $conexion=new conexion();
    $con=$conexion->getconection();
    $response=new stdClass();
    if (!$con) {
    	die("sin conex");
    }

    $fecha=$_POST['fecha'];
    $horaInicio=intval($_POST['horaInicio']);
    $array=json_decode($_POST['array']);

    $sql="select * from parametro where par_id=1";
    $result=mysqli_query($con,$sql);
    $row=mysqli_fetch_array($result);
    $tolerancia=intval($row['value']);
    for ($i=0; $i < count($array); $i++) { 
    	$tardanza=0;
    	$horaMarca=$array[$i][1];
        $asistencia_id=$array[$i][0];
    	if ($horaInicio+$tolerancia<=intval($horaMarca)) {
    		$tardanza=1;
    	}
    	$sql="update asistencia set estado_tardanza=$tardanza,hora_marca='$horaMarca',estado='A',estado_presente=1
    	where asistencia_id=$asistencia_id";
        $result=mysqli_query($con,$sql);
    }
    $response->state=true;

	header('Content-type: application/json');
	echo json_encode($response);