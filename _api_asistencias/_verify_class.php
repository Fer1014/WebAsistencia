<?php
	$con=mysqli_connect("localhost","jyldigit_userw","jhvxQzU+i+h.","jyldigit_widget");
	if (!$con) {
		die("Sin conexion");
	}
	$usuarios=[];

	$usuario_id=$_GET['codusu'];
	$sql="select *,DATE_FORMAT(NOW(), '%H%i') hora_act from asistencia
	where usuario_id=$usuario_id
	and hora_inicio>DATE_FORMAT(NOW(), '%H%i')
	and fecha=DATE_FORMAT(NOW(), '%Y-%m-%d')
	and estado='G'
	order by hora_inicio";
	$result=mysqli_query($con,$sql);
	while ($row=mysqli_fetch_array($result)) {
		$usuarios[] = $row;
	}

	header('Content-Type: application/json');
	echo json_encode($usuarios);