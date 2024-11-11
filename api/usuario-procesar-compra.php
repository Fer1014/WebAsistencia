<?php
	session_start();
	$response=new stdClass();

    include('../config/conexion.php');

    $conexion=new conexion();
    $con=$conexion->getconection();

	$codusu=$_SESSION['ae-codusu'];
	$sql="SELECT * from carrito where codusu=$codusu";
	$result=mysqli_query($con,$sql);
	$row=mysqli_fetch_array($result);
	if (mysqli_num_rows($result)==0) {
		$response->state=false;
		$response->detail="Su carrito esta vacio";
	}else{
		if ($_POST['nomusu']=="" ||
			$_POST['numdocusu']=="" ||
			$_POST['tipdocusu']=="") {
			$response->state=false;
			$response->detail="Debe completar los datos obligatorios";
		}else{
			if ($_POST['codusudir']=="0") {
				$response->state=false;
				$response->detail="Debe seleccionar una ubicación de entrega";
			}else{
				$codusudir=$_POST['codusudir'];
				$_SESSION['c-nomusu']=$_POST['nomusu'];
				$_SESSION['c-numdocusu']=$_POST['numdocusu'];
				$_SESSION['c-codusudir']=$_POST['codusudir'];
				$_SESSION['c-tipdocusu']=$_POST['tipdocusu'];
				$_SESSION['c-factura']=$_POST['factura'];
				$_SESSION['c-corusu']=$_POST['corusu'];
				$sql="select * from usudir where codusudir=$codusudir";
				$result=mysqli_query($con,$sql);
				$row=mysqli_fetch_array($result);
				$_SESSION['c-dirusu']=$row['dirusu'];
				$_SESSION['c-latusu']=$row['latusu'];
				$_SESSION['c-lngusu']=$row['lngusu'];
				$_SESSION['c-ciudad']=$row['ciuusu'];
				$_SESSION['c-celusu']=$row['celusu'];
				$_SESSION['c-refusu']=$row['refusu'];
				$_SESSION['proceso_compra']=true;
				$response->state=true;
			}
		}
	}

	mysqli_close($con);
	echo json_encode($response);