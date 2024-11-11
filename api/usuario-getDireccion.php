<?php
	session_start();
	$response=new stdClass();
    include('../config/conexion.php');
    $conexion=new conexion();
    $con=$conexion->getconection();

    $codusu=$_SESSION['ae-codusu'];
	$codusudir=$_POST['codusudir'];
	$sql="select * from usudir
	where codusu=$codusu and codusudir=$codusudir";
	$result=mysqli_query($con,$sql);
	if ($result) {
		$response->state=true;
		$row=mysqli_fetch_array($result);
		$response->latusu=$row['latusu'];
		$response->lngusu=$row['lngusu'];
		$response->codpos=$row['codposusu'];
		$response->ciudad=$row['ciuusu'];
		$response->ncasa=$row['ncasausu'];
		$response->npiso=$row['npisousu'];
		$response->direccion=$row['dirusu'];
		$response->celular=$row['celusu'];
		$response->referencia=$row['refusu'];
	}else{
		$response->state=false;
		$response->detail="Hubo un error, inténtelo más tarde";
	}

	mysqli_close($con);
	echo json_encode($response);