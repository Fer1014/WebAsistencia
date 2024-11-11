<?php
	session_start();
	$response=new stdClass();

    include('../config/conexion.php');

    $conexion=new conexion();
    $con=$conexion->getconection();

	$codcar=$_POST['codcar'];
	if ($_SESSION['ae-codusu']=="0") {
		$carrito=[];
		$carrito_aux=[];
		if (isset($_SESSION['carrito'])) {
			$carrito=$_SESSION['carrito'];
		}
		for ($i=0; $i < count($carrito); $i++) { 
			if ($codcar!=$carrito[$i]->codcar) {
				$obj=new stdClass();
				$obj->codcar=$i+1;
				$obj->codpro=$carrito[$i]->codpro;
				$obj->nompro=$carrito[$i]->nompro;
				$obj->canpro=$carrito[$i]->canpro;
				$obj->prepro=$carrito[$i]->prepro;
				$obj->imapro=$carrito[$i]->imapro;
				array_push($carrito_aux, $obj);
			}
		}
		$_SESSION['carrito']=$carrito_aux;
		$response->state=true;
		$response->detail="Producto eliminado";
	}else{
		$sql="DELETE FROM carrito
		WHERE codcar=$codcar";
		$result=mysqli_query($con,$sql);
		if ($result) {
			$response->state=true;
			$response->detail="Producto eliminado";
		}else{
			$response->state=false;
			$response->detail="No se pudo eliminar el producto";
		}
	}

	mysqli_close($con);
	echo json_encode($response);