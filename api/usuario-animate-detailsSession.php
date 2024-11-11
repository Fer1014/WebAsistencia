<?php	
    include('../config/conexion.php');
	session_start();
	$response=new stdClass();

	$response->state=true;

	if ($_SESSION['ae-codusu']!="0") {
	    $conexion=new conexion();
	    $con=$conexion->getconection();

		$codusu=$_SESSION['ae-codusu'];
		$sql="select * from favorito where codusu=$codusu";
		$result=mysqli_query($con,$sql);
		$array=[];
		while($row=mysqli_fetch_array($result)) {
			$obj=new stdClass();
			$obj->codpro=$row['codpro'];
			array_push($array,$obj);
		}
		$response->favs=$array;

		$carrito=[];
        $sql="select * from carrito car
        inner join producto pro
        on car.codpro=pro.codpro
        where car.codusu=$codusu";
        $result=mysqli_query($con,$sql);
        while ($row=mysqli_fetch_array($result)) {
            $obj=new stdClass();
            $obj->codcar=$row['codcar'];
            $obj->codpro=$row['codpro'];
            $obj->nompro=utf8_encode($row['nompro']);
            $obj->canpro=$row['canpro'];
            $obj->prepro=$row['prepro'];
            $obj->imapro=$row['imapro'];
            array_push($carrito, $obj);
        }
		$response->carrito=$carrito;
	}else{
		$response->carrito=$_SESSION['carrito'];
	}

	echo json_encode($response);