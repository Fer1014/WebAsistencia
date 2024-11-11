<?php
	session_start();
	$response=new stdClass();
    include('../config/conexion.php');
    $conexion=new conexion();
    $con=$conexion->getconection();

	$codusu=$_SESSION['ae-codusu'];
	$nombre=$_POST['nombre'];
	$tipdoc=$_POST['tipdoc'];
	$numdoc=$_POST['numdoc'];
	$celular=$_POST['celular'];
	$direccion=$_POST['direccion'];
	$password=$_POST['password'];
	$sql="update usuario set nomusu='$nombre',codtipdoc=$tipdoc,numdocusu='$numdoc',celusu='$celular',dirusu='$direccion',pasusu='$password'
	where codusu=$codusu";
	$result=mysqli_query($con,$sql);
	if ($result) {
		$response->state=true;
		$response->detail="Datos actualizados";
	}else{
		$response->state=false;
		$response->detail="Hubo un error, inténtelo más tarde";
	}

	mysqli_close($con);
	echo json_encode($response);