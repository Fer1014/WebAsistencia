<?php
    session_start();
    if (isset($_SESSION['wa-idusuario']) && $_SESSION['wa-idusuario']!="0") {
        header("location: main.php");
    }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/login.css">
    <link rel="stylesheet" href="font-awesome-4.7.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Mukta:wght@300&display=swap" rel="stylesheet">
    <title>Login | <?php include('layouts/nombre-web.php'); ?></title>
    <script src="https://kit.fontawesome.com/98f41f80ef.js" crossorigin="anonymous"></script>
</head>
<body>
    <?php include('layouts/_pantalla-carga.php') ?>
    <header>
        <h1>Web asistencias</h1>
    </header>
    <main class="contenedor">
        <section class="contenedor-registro">
            <div class="registro">
                <h3 class="titulo titulo-s2">Ingresa aquí</h3>
                <div class="box-text">
                    <input class="text-regis" autocomplete="off" type="email" id="correo" placeholder="Correo">
                    <input class="text-regis" autocomplete="off" type="password" id="password" placeholder="Contraseña">
                    <button type="submit" class="btn" onclick="try_login()">Ingresar</button>
                </div>
            </div>
        </section>
    </main>
    <script src="js/main-animations.js"></script>
    <script type="text/javascript">
        function try_login(){
            show_carga();
            var fd=new FormData();
            fd.append('corusu',document.getElementById("correo").value);
            fd.append('pasusu',document.getElementById("password").value);
            var request=new XMLHttpRequest();
            request.open('POST','api/_login.php',true);
            request.onload=function(){
                hide_carga();
                if (request.status==200 && request.readyState==4) {
                    let response=JSON.parse(request.responseText);
                    if (response.state) {
                        window.location.reload();
                    }else{
                        alert(response.detail);
                    }
                }
            }
            request.send(fd);
        }
    </script>
</body>
</html>