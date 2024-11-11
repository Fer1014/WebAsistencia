<?php
	session_start();
    include('../config/conexion.php');
    include('../config/mailing/recovery_account.php');
    include('_generar_token.php');
    $conexion=new conexion();
    $con=$conexion->getconection();
    $response=new stdClass();

    $corusu=$_POST['corusu'];

	$sql="SELECT * from usuario where corusu='$corusu'";
	$result=mysqli_query($con,$sql);
	$row=mysqli_fetch_array($result);
	if (mysqli_num_rows($result)!=0) {
		$codusu=$row['codusu'];
		$token='';
		$validar=true;
		while ($validar) {
			$token=generar_token();
			$sql="select * from usuario where token='$token'";
			$result=mysqli_query($con,$sql);
			$row=mysqli_fetch_array($result);
			if (mysqli_num_rows($result)==0) {
				$validar=false;
			}
		}
		$val_con=recovery_account($corusu,$token);
		if ($val_con=="1") {
			$sql="UPDATE usuario SET token ='$token' where codusu=$codusu";
			$result=mysqli_query($con,$sql);
			if ($result) {
				$response->state=true;
				$response->detail="Se envió un mensaje a su correo";
			}else{
				$response->state=false;
				$response->detail="Hubo un error, intente más tarde";
			}
		}else{
			$response->state=false;
			$response->detail="No se pudo enviar el correo de confirmación";
		}
	}else{
		$response->state=false;
		$response->detail="No se existe el correo";
	}

	header('Content-type: application/json');
	echo json_encode($response);