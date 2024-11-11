<?php
	session_start();
    include('../config/conexion.php');
    include('../config/mailing/confirm_email.php');
    include('_generar_token.php');
    $conexion=new conexion();
    $con=$conexion->getconection();
    $response=new stdClass();

    $corusu=$_POST['corusu'];
    $pasusu=$_POST['pasusu'];
    $pasusur=$_POST['pasusuR'];

    if ($pasusur!=$pasusu) {
		$response->state=false;
		$response->detail="Las contraseñas deben ser iguales";
    }else{
		$sql="select * from usuario
		where estado=1 and corusu='$corusu'";
		$result=mysqli_query($con,$sql);
		if ($result) {
			$row=mysqli_fetch_array($result);
			if (mysqli_num_rows($result)!=0) {
				$response->state=false;
				$response->detail="El correo ya existe";
			}else{
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
				$val_con=send_confirm_email($corusu,$token);
				if ($val_con=="1") {
					$sql="INSERT INTO usuario (corusu,pasusu,codtipdoc,estado,token)
					VALUES ('$corusu','$pasusu',1,1,'$token')";
					$result=mysqli_query($con,$sql);
					if ($result) {
						$response->state=true;
					}else{
						$response->state=false;
						$response->detail="Hubo un error, intente más tarde";
					}
				}else{
					$response->state=false;
					$response->detail="No se pudo enviar el correo de confirmación";
				}
			}
		}else{
			$response->state=false;
			$response->detail="Hubo un error, intente más tarde";
		}
	}

	header('Content-type: application/json');
	echo json_encode($response);