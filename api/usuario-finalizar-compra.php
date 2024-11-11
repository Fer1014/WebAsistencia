<?php
	session_start();
    include('../config/mailing/mails_admin.php');
    include('../config/conexion.php');
	$response=new stdClass();
    $conexion=new conexion();
    $con=$conexion->getconection();

    $codusu=$_SESSION['ae-codusu'];
	$dirusu=$_SESSION['c-dirusu'];
	$celusu=$_SESSION['c-celusu'];
	$latusu=$_SESSION['c-latusu'];
	$lngusu=$_SESSION['c-lngusu'];
	$nomusu=$_SESSION['c-nomusu'];
	//$provincia=$_SESSION['c-provincia'];
	$numdocusu=$_SESSION['c-numdocusu'];
	$ciudad=$_SESSION['c-ciudad'];
	$tipdocusu=$_SESSION['c-tipdocusu'];
	$factura=$_SESSION['c-factura'];
	$refusu=$_SESSION['c-refusu'];

	$stock_normal=true;
	$lista_productos="";
	$sql="select pro.nompro,car.canpro,pro.stopro from carrito car
	inner join producto pro
	on car.codpro=pro.codpro";
	$result=mysqli_query($con,$sql);
	while($datos=mysqli_fetch_array($result)){
		if (intval($datos['canpro'])>intval($datos['stopro'])) {
			$stock_normal=false;
			if ($lista_productos!="") {
				$lista_productos.=",";
			}
			$lista_productos.="'".$datos['nompro']."'";
		}
	}

	if ($stock_normal) {
		$_SESSION['proceso_compra']=false;
		$id=$_POST['id'];
		$type=$_POST['type'];
		$envped=2.00;
		$sql="UPDATE usuario set dirusu='$dirusu',celusu='$celusu',latusu=$latusu,lngusu=$lngusu,numdocusu='$numdocusu',nomusu='$nomusu',
		codtipdoc=$tipdocusu,ciuusu='$ciudad',factura=$factura
		where codusu=$codusu";
		$result=mysqli_query($con,$sql);

		$sql="";
		if ($type<3) {
			$sql="INSERT INTO pedido (codusu,fecpedcre,fecpedpag,celped,dirped,ciuped,latped,lngped,estado,idpay,typpay,factura,refped,envped)
			VALUES ($codusu,now(),now(),'$celusu','$dirusu','$ciudad',$latusu,$lngusu,2,'$id',$type,$factura,'$refusu',$envped)";
		}else{
			$sql="INSERT INTO pedido (codusu,fecpedcre,celped,dirped,ciuped,latped,lngped,estado,idpay,typpay,factura,refped,envped)
			VALUES ($codusu,now(),'$celusu','$dirusu','$ciudad',$latusu,$lngusu,2,'$id',$type,$factura,'$refusu',$envped)";
		}
		$result=mysqli_query($con,$sql);
		if ($result) {
			$codped=mysqli_insert_id($con);

			$sql="SELECT * from carrito where codusu=$codusu";
			$result=mysqli_query($con,$sql);
			while($row=mysqli_fetch_array($result)){
				$codpro=$row['codpro'];
				$candetped=$row['canpro'];
				$mondetped=floatval($row['prepro'])*intval($row['canpro']);
				$sql="INSERT INTO detped (codped,codpro,candetped,mondetped,estado)
				VALUES ($codped,$codpro,$candetped,$mondetped,1)";
				$result2=mysqli_query($con,$sql);
				$sql="update producto set stopro=stopro-$candetped where codpro=$codpro";
				$result2=mysqli_query($con,$sql);
			}
			$sql="DELETE FROM carrito where codusu=$codusu";
			$result=mysqli_query($con,$sql);

			$response->state=true;
			$response->detail="Compra procesada";
			/*
			$sql="select * from producto where stopro<minstopro";
			$result=mysqli_query($con,$sql);
			$lista_productos='';
			while($row=mysqli_fetch_array($result)){
				if ($lista_productos!="") {
					$lista_productos.="<br>";
				}
				$lista_productos.="Código: ".$row['codpro']." | Producto: ".$row['nompro']." | Stock: ".$row['stopro'];
			}
			if ($lista_productos!="") {
				$sql="select * from administrador adm
				inner join accadm aa
				on adm.codadm=aa.codadm
				where aa.codacc=1";
				$result=mysqli_query($con,$sql);
				while($row=mysqli_fetch_array($result)){				
					notificar_stock($row['coradm'],$lista_productos);
				}
			}*/
		}else{
			$response->state=false;
			$response->detail="No se pude procesar la compra, intente en unos minutos";
		}
	}else{
		$response->state=false;
		$response->confirm=true;
		$response->detail="No hay stock suficiente en los productos: ".$lista_productos.". Desea comprar la cantidad máxima posible?";
	}

	mysqli_close($con);
	echo json_encode($response);