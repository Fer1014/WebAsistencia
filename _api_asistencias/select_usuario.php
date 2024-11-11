<?php
	$con=mysqli_connect("localhost","jyldigit_userw","jhvxQzU+i+h.","jyldigit_widget");
	if (!$con) {
		die("Sin conexion");
	}
	$usuarios=[];

	$usuario_id=$_GET['codusu'];
	$sql="select * from usuarios where usuario_id=$usuario_id";
	$result=mysqli_query($con,$sql);
	while ($row=mysqli_fetch_array($result)) {
		$usuarios[] = $row;
	}

	header('Content-Type: application/json');
	echo json_encode($usuarios);