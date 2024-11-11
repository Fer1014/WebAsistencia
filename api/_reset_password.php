<?php
	session_start();
    include('../config/conexion.php');
    $conexion=new conexion();
    $con=$conexion->getconection();
    $response=new stdClass();

    $token=$_POST['token'];
    $pasusu=$_POST['pasusu'];
    $pasusuR=$_POST['pasusuR'];
    if ($pasusuR!=$pasusu) {
		$response->state=false;
		$response->detail="Las contraseñas deben coincidir";
    }else{
		$sql="SELECT * from usuario
		where estado=1 and token='$token'";
		$result=mysqli_query($con,$sql);
		if ($result) {
			$row=mysqli_fetch_array($result);
			if (mysqli_num_rows($result)==0) {
				$response->state=false;
				$response->detail="Vuelva al inicio e reintente el proceso";
			}else{
				$codusu=$row['codusu'];
				$sql="UPDATE usuario set pasusu='$pasusu'
				where codusu='$codusu'";
				$result=mysqli_query($con,$sql);
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
				$response->detail="Contraseña reestablecida";
				session_unset();
				$_SESSION['ae-codusu']=$row['codusu'];
				$_SESSION['ae-corusu']=$row['corusu'];
				$_SESSION['ae-nomusu']=$row['nomusu'];
			}
		}else{
			$response->state=false;
			$response->detail="Hubo un error, intente más tarde";
		}
	}

	header('Content-type: application/json');
	echo json_encode($response);