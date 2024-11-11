<?php
	$con=mysqli_connect("localhost","jyldigit_userw","jhvxQzU+i+h.","jyldigit_widget");
	if (!$con) {
		die("Sin conexion");
	}
	$id=$_POST['id'];
	$sql="select * from aulas where aula_id=$id";
	$result=mysqli_query($con,$sql);
	$obj=new stdClass();
	while ($row=mysqli_fetch_array($result)) {
		$obj->lat=$row['coord_latitud'];
		$obj->lng=$row['coord_longitud'];
	}
    
	echo json_encode($obj);
	