<?php
	session_start();
	$response=new stdClass();

    include('../config/conexion.php');

    $conexion=new conexion();
    $con=$conexion->getconection();

	$codpro=$_POST['codpro'];
	if (!isset($_SESSION['ae-codusu'])) {
		$_SESSION['ae-codusu']="0";
	}
	if ($_SESSION['ae-codusu']=="0") {
		$response->state=false;
		$response->detail="Debe iniciar sesión primero";
	}else{
		$codpro=$_POST['codpro'];
		$codusu=$_SESSION['ae-codusu'];
		$sql="select * from favorito
		where codpro=$codpro and codusu=$codusu";
		$result=mysqli_query($con,$sql);
		if ($result) {
			$response->state=true;
			$row=mysqli_fetch_array($result);
			$count=mysqli_num_rows($result);
			if($count>0){
				$sql="delete from favorito where codpro=$codpro and codusu=$codusu";
				$result=mysqli_query($con,$sql);
			}else{
				$sql="insert into favorito values ($codpro,$codusu)";
				$result=mysqli_query($con,$sql);
			}
		}else{
			$response->state=false;
			$response->detail="Hubo un error, inténtelo más tarde";
		}
	}

	mysqli_close($con);
	echo json_encode($response);