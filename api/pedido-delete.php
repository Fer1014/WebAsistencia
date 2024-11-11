<?php
	session_start();
	$response=new stdClass();

    include('../config/conexion.php');

    $conexion=new conexion();
    $con=$conexion->getconection();

	$codped=$_POST['codped'];

	$sql="update pedido set estado=4
	WHERE codped=$codped";
	$result=mysqli_query($con,$sql);
	if ($result) {
		$response->state=true;
		$response->detail="Pedido eliminado";
	}else{
		$response->state=false;
		$response->detail="No se pudo eliminar el pedido";
	}

	mysqli_close($con);
	echo json_encode($response);