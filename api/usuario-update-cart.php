<?php
    include('../config/conexion.php');
	session_start();
	$response=new stdClass();

	$sumtot=0;
	$html='';
	$carrito=[];
    if ($_SESSION['ae-codusu']=="0") {
        $carrito=$_SESSION['carrito'];
    }else{
        $conexion=new conexion();
        $con=$conexion->getconection();
        $codusu=$_SESSION['ae-codusu'];
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
    }
    for ($i=0; $i < count($carrito); $i++) { 
        $html.=
    '<div class="item-carrito-c" id="body-car-'.$carrito[$i]->codpro.'">
        <div class="img-carrito">
            <img src="assets/img/'.$carrito[$i]->imapro.'">
        </div>
        <div class="detail-carrito">
            <h5>'.$carrito[$i]->nompro.'</h5>
            <h3>$'.$carrito[$i]->prepro.'</h3>
            <div class="options-buy">
                <div id="content-product-'.$carrito[$i]->codpro.'-c">
                    <div class="select-quantity">
                        <div class="quantity"><span id="quantity-product-'.$carrito[$i]->codpro.'-c">'.$carrito[$i]->canpro.'</span>&nbsp;uds.</div>
                        <div class="btns-mas-menos">
                            <div class="btn-menos" onclick="add_quantiy(-1,'.$carrito[$i]->codpro.')"><i class="fa-solid fa-minus"></i></div>
                            <div class="btn-mas" onclick="add_quantiy(1,'.$carrito[$i]->codpro.')"><i class="fa-solid fa-plus"></i></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>';
        $sumtot+=floatval($carrito[$i]->prepro)*intval($carrito[$i]->canpro);
    }
    $response->state=true;
    $response->carrito=$html;
    $response->sumtot=$sumtot;

	echo json_encode($response);
?>