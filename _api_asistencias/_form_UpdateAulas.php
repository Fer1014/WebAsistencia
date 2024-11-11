<?php
	$con=mysqli_connect("localhost","jyldigit_userw","jhvxQzU+i+h.","jyldigit_widget");
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title></title>
</head>
<body>
	<h1>Localizacion de aulas</h1>
	<label>Aula</label>
	<select id="aula" onchange="update_coords()">
		<option>Seleccione</option>
		<?php
		$sql="select * from aulas";
		$result=mysqli_query($con,$sql);
		while ($row=mysqli_fetch_array($result)) {
			echo '<option value="'.$row['aula_id'].'">'.$row['aula_id'].' - '.$row['nombre_aula'].'</option>';
		}
		?>
	</select>
	<br><br>
	<label>Latitud</label>
	<input type="text" id="lat">
	<br><br>
	<label>Longitud</label>
	<input type="text" id="lng">
	<br><br>
	<button onclick="save_coords()">Guardar</button>
	<script type="text/javascript">
		function update_coords(){
			var fd=new FormData();
            fd.append('id',document.getElementById("aula").value);
            var request=new XMLHttpRequest();
            request.open('POST','__get_AulaCoords.php',true);
            request.onload=function(){
                console.log(request);
                if (request.status==200 && request.readyState==4) {
                    let response=JSON.parse(request.responseText);
                    document.getElementById("lat").value=response.lat;
                    document.getElementById("lng").value=response.lng;
                }
            }
            request.send(fd);
		}
		function save_coords(){
			var fd=new FormData();
            fd.append('id',document.getElementById("aula").value);
            fd.append('lat',document.getElementById("lat").value);
            fd.append('lng',document.getElementById("lng").value);
            var request=new XMLHttpRequest();
            request.open('POST','__update_AulaCoords.php',true);
            request.onload=function(){
                console.log(request);
                if (request.status==200 && request.readyState==4) {
                    let response=JSON.parse(request.responseText);
                    if (response.state) {
                    	window.location.reload();
                    }else{
                    	alert("Hubo un error");
                    }
                }
            }
            request.send(fd);
		}
	</script>
</body>
</html>