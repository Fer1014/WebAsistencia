<?php
	session_start();
	$response=new stdClass();

    include('../config/conexion.php');

    $conexion=new conexion();
    $con=$conexion->getconection();

    $codusu=$_SESSION['ae-codusu'];
    $direccion=$_POST['direccion'];
    $ciudad=$_POST['ciudad'];
    $celular=$_POST['celular'];
    $ncasa=$_POST['ncasa'];
    $npiso=$_POST['npiso'];
    $codpostal=$_POST['codpostal'];
    if ($ncasa=="") {
        $ncasa=0;
    }
    if ($npiso=="") {
        $npiso=0;
    }
    if ($codpostal=="") {
        $codpostal=0;
    }
    $referencia=$_POST['referencia'];
    $lat=$_POST['lat'];
    $lng=$_POST['lng'];
    $seldef="0";
    if (isset($_POST['codusudir'])) {
        $cod=$_POST['codusudir'];
        $sql="update usudir set latusu=$lat,lngusu=$lng,celusu='$celular',dirusu='$direccion',ciuusu='$ciudad',refusu='$referencia',
        seldef=$seldef,ncasausu=$ncasa,npisousu=$npiso,codposusu=$codpostal where codusu=$codusu and codusudir=$cod";
        $result=mysqli_query($con,$sql);
        if ($result) {
            $response->state=true;
        }else{
            $response->state=false;
            $response->detail="No se pudo guardar la nueva dirección";
        }
    }else{
        $sql="update usudir set seldef=0 where codusu=$codusu";
        $result=mysqli_query($con,$sql);

        $sql="insert into usudir (codusu,latusu,lngusu,celusu,dirusu,ciuusu,refusu,seldef,ncasausu,npisousu,codposusu)
        values ($codusu,$lat,$lng,'$celular','$direccion','$ciudad','$referencia',1,$ncasa,$npiso,$codpostal)";
        $result=mysqli_query($con,$sql);
        if ($result) {
        	$response->state=true;
        	$html='';
    		$sql="select * from usudir where codusu=$codusu";
    		$result=mysqli_query($con,$sql);
    		while($row=mysqli_fetch_array($result)){
    			$html.=
    			'<div class="save-dirs" onclick="select_dir('.$row['codusudir'].')">
                    <div class="icon-dir"><img src="assets/web/ubicacion.png" style="width:100%;"/></div>
                    <div class="content-dir">
                        <h4>'.$row['dirusu'].'</h4>
                        <p>'.$row['codposusu'].' - '.$row['ciuusu'].'</p>
                    </div>
                    <div class="select-dir" id="dir-'.$row['codusudir'].'">
                        <div class="animate-select-dir"><i class="fa-solid fa-check"></i></div>
                    </div>
                </div>';
    		}
    		$response->html=$html;
        }else{
        	$response->state=false;
        	$response->detail="No se pudo guardar la nueva dirección";
        }
    }
	mysqli_close($con);
	echo json_encode($response);