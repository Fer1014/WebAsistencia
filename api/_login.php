<?php
	session_start();
    include('../config/conexion.php');
    $conexion=new conexion();
    $con=$conexion->getconection();
    $response=new stdClass();
    if (!$con) {
    	die("sin conex");
    }

    $corusu=$_POST['corusu'];
    $pasusu=$_POST['pasusu'];
	$sql="SELECT * from usuarios
	where rol_id=3 and email='$corusu' and contrasena='$pasusu'";
	$result=mysqli_query($con,$sql);
	if ($result) {
		$row=mysqli_fetch_array($result);
		if (mysqli_num_rows($result)==0) {
			$response->state=false;
			$response->detail="Usuario no existe";
		}else{
			if ($row['contrasena']==$pasusu) {
				$response->state=true;
				session_unset();
				$_SESSION['wa-idusuario']=$row['usuario_id'];
			}else{
				$response->state=false;
				$response->detail="La contraseña es incorrecta";
			}
		}
	}else{
		$response->state=false;
		$response->detail="Hubo un error, intente más tarde";
	}

	header('Content-type: application/json');
	echo json_encode($response);