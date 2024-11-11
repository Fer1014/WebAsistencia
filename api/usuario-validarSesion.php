<?php
	session_start();
	$response=new stdClass();
	if ($_SESSION['ae-codusu']=="0") {
		$response->sesion=false;
	}else{
		$response->sesion=true;
	}
	echo json_encode($response);