<?php
	session_start();
	$response=new stdClass();
    include('../config/conexion.php');
    $conexion=new conexion();
    $con=$conexion->getconection();

	if (!isset($_SESSION['ae-codusu'])) {
		$_SESSION['ae-codusu']="0";
	}
	if ($_SESSION['ae-codusu']=="0") {
		$carrito=$_SESSION['carrito'];
		if (count($carrito)==0) {
			$response->state=false;
			$response->detail="Su carrito esta vacío";
		}else{
			$_SESSION['carrito']=[];
			$response->state=true;
			$response->detail="Carrito vaciado";
		}
	}else{
		$codusu=$_SESSION['ae-codusu'];
		$sql="delete from carrito where codusu=$codusu";
		$result=mysqli_query($con,$sql);
		if ($result) {
			$response->state=true;
			$response->detail="Carrito vaciado";
		}else{
			$response->state=false;
			$response->detail="No se pudo eliminar el carrito";
		}
	}

	mysqli_close($con);
	echo json_encode($response);