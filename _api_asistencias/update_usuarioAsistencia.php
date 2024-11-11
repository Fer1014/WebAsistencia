<?php
	$con=mysqli_connect("localhost","jyldigit_userw","jhvxQzU+i+h.","jyldigit_widget");
	if (!$con) {
		die("Sin conexion");
	}
	date_default_timezone_set('America/Lima');
	$asistencia_id=$_GET['asistencia_id'];
	$hora_marca = date("Hi");

	$sql="select * from asistencia where asistencia_id=$asistencia_id";
	$result=mysqli_query($con,$sql);
	$row=mysqli_fetch_array($result);
	$hora_inicio=$row['hora_inicio'];
	$hora_fin=$row['hora_fin'];
	
	$sql="select * from parametro where par_id=1";
	$result=mysqli_query($con,$sql);
	$row=mysqli_fetch_array($result);
	$tie_tar=$row['value'];

	$tardanza=0;
	if (intval($hora_marca)>intval($hora_inicio)+intval($tie_tar)) {
		$tardanza=1;
	}

	$sql="update asistencia set hora_marca ='$hora_marca' , estado_presente = 1,
	estado_tardanza = $tardanza, estado = 'A'
	where asistencia_id = $asistencia_id";
	$result=mysqli_query($con,$sql);
	if ($result) {
		echo 1;
	}else{
		echo 0;//error
	}