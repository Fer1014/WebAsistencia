<?php
	session_start();
	$usuario_id=$_SESSION['wa-idusuario'];
    include('../config/conexion.php');
    $conexion=new conexion();
    $con=$conexion->getconection();
    $response=new stdClass();
    if (!$con) {
    	die("sin conex");
    }

    $fecha=$_POST['fecha'];
    $horaInicio=$_POST['horaInicio'];
    $horaFin=$_POST['horaFin'];
    $aula_id=$_POST['aula_id'];
    $curso_id=$_POST['curso_id'];
    $fecha_ori=$_POST['fecha_ori'];
    $horaInicio_ori=$_POST['horaInicio_ori'];
    $horaFin_ori=$_POST['horaFin_ori'];
    $aula_id_ori=$_POST['aula_id_ori'];
    $horario_select=0;
	$sql="select horario_id from horarios
	where dia_semana = weekday('$fecha') + 1 and hora_inicio ='$horaInicio'
	and hora_fin = '$horaFin' and aula_id = $aula_id and curso_id = $curso_id";
	$result=mysqli_query($con,$sql);
	$row=mysqli_fetch_array($result);
	if (mysqli_num_rows($result)==0) {
		$sql="select max(horario_id)+1 as horario_id from horarios";
		$result=mysqli_query($con,$sql);
		$row=mysqli_fetch_array($result);
		$horario_select=$row['horario_id'];
		
		$sql="insert into horarios(horario_id,dia_semana,hora_inicio,hora_fin,aula_id,curso_id, tipo)
		values($horario_select, weekday('$fecha')+1, '$horaInicio', '$horaFin', $aula_id, $curso_id, 0)";
		$result=mysqli_query($con,$sql);
	}else{
		$horario_select=$row['horario_id'];
	}

	$sql="select asistencia_id from asistencia
	where fecha = '$fecha' and hora_inicio = '$horaInicio'
	and hora_fin = '$horaFin' and horario_id = $horario_select";
	$result=mysqli_query($con,$sql);
	$row=mysqli_fetch_array($result);
	if (mysqli_num_rows($result)==0) {
		$sql="select ifnull(max(codrepro_id),0)+1 codrepro_id from reprogramacion";
		$result=mysqli_query($con,$sql);
		$row=mysqli_fetch_array($result);
		$codrepro_id=$row['codrepro_id'];

		$sql="insert into reprogramacion
		(codrepro_id, curso_id, fecha_ori,hora_inicio_ori, hora_fin_ori,aula_id_ori,fecha_act,hora_inicio_act,hora_fin_act,aula_id_act,usuario_id,fecha_mod)
		values($codrepro_id, $curso_id, '$fecha_ori', '$horaInicio_ori', '$horaFin_ori', $aula_id_ori, '$fecha', '$horaInicio', '$horaFin', $aula_id,
		$usuario_id, now())";
		$result=mysqli_query($con,$sql);

		$sql="select horario_id from horarios
		where dia_semana = weekday('$fecha_ori') + 1 and hora_inicio = '$horaInicio_ori'
		and hora_fin = '$horaFin_ori' and aula_id = $aula_id_ori and curso_id = $curso_id";
		$result=mysqli_query($con,$sql);
		$row=mysqli_fetch_array($result);
		$horario_id_ori=$row['horario_id'];
		//$response->sql=$sql;

		$sql="update asistencia set fecha = '$fecha', hora_inicio =  '$horaInicio', hora_fin = '$horaFin',
		hora_marca = '0000', estado_presente = 0, estado_tardanza = 0, horario_id = $horario_select,
		codrepro_id = $codrepro_id
		where horario_id = $horario_id_ori and fecha = '$fecha_ori' and hora_inicio = '$horaInicio_ori' and hora_fin = '$horaFin_ori'";
		$result=mysqli_query($con,$sql);
		$response->state=true;
	}else{
		$response->state=false;
		$response->detail="No se puede reprogramar, ya esta reprogramado";
	}

	header('Content-type: application/json');
	echo json_encode($response);