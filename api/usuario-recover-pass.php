<?php
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
	require 'PHPMailer/src/Exception.php';
	require 'PHPMailer/src/PHPMailer.php';
	require 'PHPMailer/src/SMTP.php';
	include('../config/conexion.php');
    $conexion=new conexion();
    $con=$conexion->getconection();
    $response=new stdClass();

    $corusu=$_POST['corusu'];

	$sql="select * from usuario
	where estado=1 and corusu='$corusu'";
	$result=mysqli_query($con,$sql);
	if ($result) {
		$row=mysqli_fetch_array($result);
		if (mysqli_num_rows($result)!=0) {
			$pasusu=$row['pasusu'];

			$mail = new PHPMailer(true);
			try {
			    $mail->SMTPDebug = 0;
			    $mail->isSMTP();
			    $mail->SMTPDebug = 0;
				$mail->Host 	  = 'smtp.gmail.com';
			    $mail->Port       = 587;
			    $mail->SMTPSecure = 'tls';
			    $mail->SMTPAuth   = true;
			    $mail->Username   = 'aderym.app@gmail.com';
			    $mail->Password   = 'yosoyedy1998';

			    $mail->setFrom('aderym.app@gmail.com', 'ADERYM');
			    $mail->addAddress($corusu);

			    $mail->isHTML(true);
			    $mail->Subject = 'ADERYM | Envio de contraseña';
			    $mail->Body    =
			    '<h2>Se ha solicitado recuperar su contraseña</h2>'.
			    '<p>La contraseña para el correo <b>'.$corusu.'</b> es <b>'.$pasusu.'</b></p>';
			    if($mail->send()){
					$response->state=true;
					$response->detail="Correo enviado, revise su bandeja (en SPAM también)";
				}else{
					$response->state=false;
					$response->detail="No se pudo enviar el correo";
				}
			} catch (Exception $e) {
				$response->state=false;
				$response->detail="Hubo un error al enviar el correo";
			}

		}else{
			$response->state=false;
			$response->detail="El correo no existe";
		}
	}else{
		$response->state=false;
		$response->detail="Hubo un error, intente más tarde";
	}

	header('Content-type: application/json');
	echo json_encode($response);