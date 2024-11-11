<?php
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
	require 'PHPMailer/src/Exception.php';
	require 'PHPMailer/src/PHPMailer.php';
	require 'PHPMailer/src/SMTP.php';
	include('_parametros-mailing.php');

	function recovery_account($email,$token){
		$mail = new PHPMailer;
		try {
			$mail->isSMTP();
			$mail->SMTPOptions = array(
			    'ssl' => array(
			        'verify_peer' => false,
			        'verify_peer_name' => false,
			        'allow_self_signed' => true
			    )
			);
			$mail->SMTPDebug  = $GLOBALS['SMTPDebug'];
		    $mail->SMTPSecure = $GLOBALS['SMTPSecure'];
		    $mail->Host       = $GLOBALS['host'];
		    $mail->Port       = $GLOBALS['port'];
		    $mail->SMTPAuth   = true;
		    $mail->Username   = $GLOBALS['username'];
		    $mail->Password   = $GLOBALS['password'];

		    $mail->setFrom($GLOBALS['email'],$GLOBALS['nombre']);
		    $mail->addAddress($email);
		    if ($GLOBALS['sendCC']) {
		    	$mail->addCC($GLOBALS['emailCC']);
		    }
		    $mail->isHTML(true);
		    $mail->Subject = utf8_decode('Recuperación de correo | Arkxpres');
		    $mail->Body=utf8_decode('<h3>Bienvenido</h3>
		    <label>Click al siguiente enlace para reestablecer su contraseña: <a href="https://arkxpres.com/_reset-pass.php?t='.$token.'">Aquí</a></label>');
		    if($mail->send()){
			    return "1";
			}else{
				return "2";
			}
		} catch (Exception $e) {
		    return $e;
		}
	}