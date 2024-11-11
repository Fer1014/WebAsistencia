<?php
	$con=mysqli_connect("localhost","jyldigit_userw","jhvxQzU+i+h.","jyldigit_widget");
	if (!$con) {
		die("Sin conexion");
	}
    $dias = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sábado");
	date_default_timezone_set('America/Lima');
	$hoy = date("Y-m-d");
	$dia_semana = date("w");
	$codusu=$_GET['codusu'];

	$sql="SELECT c.descripcion curso, l.nombre_aula aula, a.hora_inicio, a.hora_fin
  		FROM asistencia a, horarios h, aulas l, cursos c, usuarios u 
 		WHERE a.usuario_id = u.usuario_id and a.horario_id = h.horario_id and h.curso_id = c.curso_id and h.aula_id = l.aula_id 
  		AND a.fecha = '$hoy' AND a.usuario_id = $codusu and a.estado <> 'C'
 		ORDER BY a.hora_inicio";
	$result=mysqli_query($con,$sql);
	$json=[];
	$obj=new stdClass();
	$hoy_f = date("d-m-Y");
	$obj->fecha=$dias[$dia_semana]." ".$hoy_f;
	array_push($json,$obj);
	$datos=[];
	while ($row=mysqli_fetch_array($result)) {
		array_push($datos,$row);
	}
	array_push($json,$datos);

	header('Content-Type: application/json');
	echo json_encode($json);