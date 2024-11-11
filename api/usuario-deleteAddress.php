<?php
	session_start();
	$response=new stdClass();

    include('../config/conexion.php');

    $conexion=new conexion();
    $con=$conexion->getconection();

    $cod=$_POST['codusudir'];
    $codusu=$_SESSION['ae-codusu'];
    $sql="delete from usudir where codusu=$codusu and codusudir=$cod";
    $result=mysqli_query($con,$sql);
    if ($result) {
        $response->state=true;
    }else{
        $response->state=false;
        $response->detail="No se pudo eliminar la dirección";
    }

	mysqli_close($con);
	echo json_encode($response);