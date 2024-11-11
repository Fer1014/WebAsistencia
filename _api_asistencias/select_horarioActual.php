<?php
	$con=mysqli_connect("localhost","jyldigit_userw","jhvxQzU+i+h.","jyldigit_widget");
	if (!$con) {
		die("Sin conexion");
	}
	date_default_timezone_set('America/Lima');
	$hoy = date("Y-m-d H:i:s");
	$dia_hoy = date("Y-m-d");
	$hora_minuto = date("Hi");
	$dia_semana = date("w");//0:domingo y 6:sabado
	$codusu=$_GET['codusu'];

	$cursos="";
	$sql="select * from usuario_cursos where usuario_id=$codusu";
	$result=mysqli_query($con,$sql);	
	while ($row=mysqli_fetch_array($result)) {
		if ($cursos!="") {
			$cursos.=",";
		}
		$cursos.=$row['curso_id'];
	}

	$sql="select * from horarios hor
	inner join aulas aul
	on hor.aula_id=aul.aula_id
	inner join cursos cur
	on hor.curso_id=cur.curso_id
	inner join asistencia asi
	on hor.horario_id=asi.horario_id
	where hor.curso_id in ($cursos)
	and asi.usuario_id=$codusu
	and CONVERT(asi.hora_inicio, SIGNED INTEGER) <= CONVERT('$hora_minuto', SIGNED INTEGER)
	and CONVERT('$hora_minuto', SIGNED INTEGER) <= CONVERT(asi.hora_fin, SIGNED INTEGER)
	and hor.dia_semana=$dia_semana
	and asi.fecha='$dia_hoy'";
	//echo $sql;
	$result=mysqli_query($con,$sql);
	$ar_cursos=[];
	while ($row=mysqli_fetch_array($result)) {
		$ar_cursos[] = $row;
	}

	header('Content-Type: application/json');
	echo json_encode($ar_cursos);