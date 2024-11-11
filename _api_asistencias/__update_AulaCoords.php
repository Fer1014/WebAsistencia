<?php
	$con=mysqli_connect("localhost","jyldigit_userw","jhvxQzU+i+h.","jyldigit_widget");
	if (!$con) {
		die("Sin conexion");
	}
	$id=$_POST['id'];
	$lat=$_POST['lat'];
	$lng=$_POST['lng'];
	$sql="update aulas set coord_latitud=$lat,coord_longitud=$lng where aula_id=$id";
	$result=mysqli_query($con,$sql);
	$response=new stdClass();
	if ($result) {
		$response->state=true;
	}else{
		$response->state=false;
	}
    
	echo json_encode($response);
	