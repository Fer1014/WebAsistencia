<?php
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
	require 'PHPMailer/src/Exception.php';
	require 'PHPMailer/src/PHPMailer.php';
	require 'PHPMailer/src/SMTP.php';
	include('_parametros-mailing.php');

	function notificar_stock($email,$products){
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
		    $mail->Subject = utf8_decode('Notificacion de stock | '.$GLOBALS['nombre']);
		    $mail->Body=utf8_decode('<h3>Aviso importante</h3>
		    <label>Los productos siguientes están debajo del stock minimo requerido: '.$products.'</label>');
		    if($mail->send()){
			    return "1";
			}else{
				return "2";
			}
		} catch (Exception $e) {
		    return $e;
		}
	}