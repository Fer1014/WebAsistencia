<?php
	session_start();
	$response=new stdClass();

    include('../config/conexion.php');

    $conexion=new conexion();
    $con=$conexion->getconection();

	$codpro=$_POST['codpro'];
	$agregado=$_POST['agregado'];
	if (!isset($_SESSION['ae-codusu'])) {
		$_SESSION['ae-codusu']="0";
	}
	$sql="select * from producto
	where estado=1 and codpro=$codpro";
	$result=mysqli_query($con,$sql);
	if ($result) {
		$producto=mysqli_fetch_array($result);
		if ($producto['stopro']>0) {
			if ($_SESSION['ae-codusu']=="0") {
				$carrito=[];
				if (isset($_SESSION['carrito'])) {
					$carrito=$_SESSION['carrito'];
				}
				$carrito_aux=[];
				$encontrado=false;
				$blo_enc=false;
				for ($i=0; $i < count($carrito); $i++) { 
					if ($carrito[$i]->codpro==$codpro) {
						$encontrado=true;
						$canact=intval($carrito[$i]->canpro);
						$canagr=intval($carrito[$i]->canpro)+intval($agregado);
						if ($canagr>0) {
							$obj=new stdClass();
							$obj->codpro=$carrito[$i]->codpro;
							$obj->nompro=$carrito[$i]->nompro;
								$obj->prepro=$carrito[$i]->prepro;
								$obj->imapro=$carrito[$i]->imapro;
							if ($canagr>intval($producto['stopro'])) {
								$blo_enc=true;
								$response->state=false;
								$response->detail="Cantidad insuficiente en stock";
								$obj->canpro=$canact;
							}else{
								$obj->canpro=$canagr;
							}
							array_push($carrito_aux,$obj);
						}
					}else{
						$obj=new stdClass();
						$obj->codpro=$carrito[$i]->codpro;
						$obj->nompro=$carrito[$i]->nompro;
						$obj->canpro=$carrito[$i]->canpro;
						$obj->prepro=$carrito[$i]->prepro;
						$obj->imapro=$carrito[$i]->imapro;
						array_push($carrito_aux,$obj);
					}
				}
				if (!$blo_enc) {
					if (!$encontrado) {
						$obj=new stdClass();
						$obj->codcar=count($carrito)+1;
						$obj->codpro=$producto['codpro'];
						$obj->nompro=utf8_encode($producto['nompro']);
						$obj->canpro=1;
						$obj->prepro=$producto['prepro'];
						$obj->imapro=$producto['imapro'];
						array_push($carrito_aux,$obj);
						$_SESSION['carrito']=$carrito_aux;
						$response->state=true;
						$response->detail="Producto agregado";
					}else{
						$_SESSION['carrito']=$carrito_aux;
						$response->state=true;
						$response->detail="Producto agregado";
					}
				}
			}else{
				$codusu=$_SESSION['ae-codusu'];
				$sql="select * from carrito where codpro=$codpro and codusu=$codusu";
				$result=mysqli_query($con,$sql);
				$row=mysqli_fetch_array($result);
				if (mysqli_num_rows($result)>0) {
					$canpro=intval($row['canpro'])+$agregado;
					if ($canpro<=0) {
						$sql="delete from carrito where codpro=$codpro and codusu=$codusu";
						$result=mysqli_query($con,$sql);
						$response->state=true;
					}else{
						if ($canpro>intval($producto['stopro'])) {
							$response->state=false;
							$response->detail="Cantidad insuficiente en stock";
						}else{
							$sql="update carrito set canpro=$canpro where codpro=$codpro and codusu=$codusu";
							$result=mysqli_query($con,$sql);
							$response->state=true;
						}
					}
				}else{
					$prepro=$producto['prepro'];
					$sql="insert into carrito(codusu,codpro,canpro,prepro) values ($codusu,$codpro,$agregado,$prepro)";
					$result=mysqli_query($con,$sql);
					$response->state=true;
				}
			}
		}else{
			$response->state=false;
			$response->detail="Cantidad insuficiente en stock";
		}
	}else{
		$response->state=false;
		$response->detail="No se pudo agregar el producto";
	}

	mysqli_close($con);
	echo json_encode($response);