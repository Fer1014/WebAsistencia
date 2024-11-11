<?php
	session_start();
    include('../config/conexion.php');
    $conexion=new conexion();
    $con=$conexion->getconection();
    $response=new stdClass();

    $corusu=$_POST['corusu'];
    $pasusu=$_POST['pasusu'];
    $nomusu=$_POST['nomusu']." ".$_POST['apeusu'];
	$sql="SELECT * from usuario
	where estado=1 and corusu='$corusu' and pasusu='$pasusu'";
	$result=mysqli_query($con,$sql);
	if ($result) {
		$row=mysqli_fetch_array($result);
		$codusu=0;
		if (mysqli_num_rows($result)==0) {
			$sql="INSERT INTO usuario (corusu,pasusu,codtipdoc,estado,token,nomusu)
			VALUES ('$corusu','$pasusu',1,1,'','$nomusu')";
			$result=mysqli_query($con,$sql);
			if (!$result) {
				$response->state=false;
				$response->detail="No se pudo conectar con la cuenta de google";
				exit();
			}else{
				$response->state=true;
				$codusu=mysqli_insert_id($con);
			}
		}else{
			$codusu=$row['codusu'];
		}
		if (isset($_SESSION['carrito'])) {
			$carrito=[];
			$carrito=$_SESSION['carrito'];
			for ($i=0; $i < count($carrito); $i++) { 
				$codpro=$carrito[$i]->codpro;
				$canpro=$carrito[$i]->canpro;
				$prepro=$carrito[$i]->prepro;
				$sql="INSERT INTO carrito
				(codusu,codpro,canpro,prepro)
				values
				($codusu,$codpro,$canpro,$prepro)";
				$result=mysqli_query($con,$sql);
			}
		}
		$response->state=true;
		session_unset();
		$_SESSION['ae-codusu']=$codusu;
		$_SESSION['ae-corusu']=$_POST['corusu'];
		$_SESSION['ae-nomusu']=$_POST['nomusu'];
	}else{
		$response->state=false;
		$response->detail="Hubo un error, intente más tarde";
	}

	header('Content-type: application/json');
	echo json_encode($response);