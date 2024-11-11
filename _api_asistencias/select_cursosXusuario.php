<?php
	$con=mysqli_connect("localhost","jyldigit_userw","jhvxQzU+i+h.","jyldigit_widget");
	if (!$con) {
		die("Sin conexion");
	}
	//$codusu=$_GET['codusu'];
	$sql="select * from usuario_cursos";
	$result=mysqli_query($con,$sql);
	$cursos=[];
	while ($row=mysqli_fetch_array($result)) {
		$cursos[] = $row;
	}
    
    header('Content-Type: application/json');
	echo json_encode($cursos);
	