<?php
	$con=mysqli_connect("localhost","jyldigit_userw","jhvxQzU+i+h.","jyldigit_widget");
	if (!$con) {
		die("Sin conexion");
	}
	$parametros=[];

	$sql="select * from parametro";
	$result=mysqli_query($con,$sql);
	$obj=new stdClass();
	while ($row=mysqli_fetch_array($result)) {
		if ($row['par_id']==1) {
			$obj->tardanza=$row['value'];
		}
		if ($row['par_id']==2) {
			$obj->distancia_aula=$row['value'];
		}
	}
	array_push($parametros,$obj);

	header('Content-Type: application/json');
	echo json_encode($parametros);