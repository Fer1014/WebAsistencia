<?php
	$con=mysqli_connect("localhost","jyldigit_userw","jhvxQzU+i+h.","jyldigit_widget");
	if (!$con) {
		die("Sin conexión");
	}

	$usuario=$_GET['usuario'];
	$password=$_GET['password'];
	$sql="select * from usuarios where email='$usuario' and contrasena='$password'";
	$result=mysqli_query($con,$sql);
	$count=mysqli_num_rows($result);
	if ($count>0) {
		$row=mysqli_fetch_array($result);
		header('Content-Type: application/json');
		echo json_encode($row);
	}else{
		echo 0;//error
	}