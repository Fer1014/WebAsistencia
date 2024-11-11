<?php
    session_start();
    if (!isset($_SESSION['wa-idusuario']) && $_SESSION['wa-idusuario']=="0") {
        header("location: index.php");
    }
    include('config/conexion.php');
    $conexion=new conexion();
    $con=$conexion->getconection();
    global $nombre_modulo;
    $nombre_modulo = "Marcación fuera de linea";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <link rel="stylesheet" href="css/login.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php include('layouts/nombre-web.php'); ?></title>
    <meta name="description" content="Todo lo que buscas en un solo lugar">
    <link href="https://fonts.googleapis.com/css2?family=Mukta:wght@300&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="font-awesome-4.7.0/css/font-awesome.min.css">
    <script src="https://kit.fontawesome.com/98f41f80ef.js" crossorigin="anonymous"></script>
</head>
<body>
    <?php include('layouts/_pantalla-carga.php') ?>
    <?php include('layouts/_main-header.php') ?>
    <div class="body-all">
        <div class="categories" id="categorias">
            <?php include('layouts/menu.php'); ?>
        </div>
        <div class="content">
            <div class="main-content">
                <section class="main">
                    <div class="samelineDiv">
                        <label>Curso</label>
                        <input type="text" id="curso" style="width:400px;max-width:100%;">
                    </div>
                    <div class="contentList" id="content-cursos">
                        <div class="itemList">Curso 1 / asd</div>
                    </div>
                    <div id="step-2" style="margin-top: 10px;display: none;">
                        <div class="samelineDiv">
                            <label>Fecha/Hora</label>
                        </div>
                        <div class="contentList" id="content-fechaHora">
                            <div class="itemList">Curso 1 / asd</div>
                        </div>
                        <div id="step-3" style="margin-top: 10px;display: none;">
                            <div class="samelineDiv">
                                <h3>Lista de alumnos <span id="fechaSelect"></span></h3>
                            </div>
                            <div class="contentListComplete">
                                <table>
                                    <thead>
                                        <tr>
                                            <th>Alumno</th>
                                            <th>Marcaci&oacute;n</th>
                                            <th>Editar</th>
                                            <th>Asistencia</th>
                                        </tr>
                                    </thead>
                                    <tbody id="bodyTable">
                                        
                                    </tbody>
                                </table>
                            </div>
                            <button style="margin-top:5px;" onclick="actualizar();">Actualizar asistencia</button>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
    <script type="text/javascript" src="js/main-animations.js"></script>
    <script type="text/javascript">
        var ar_cursos=[];
        var curso_id_g='';
        document.addEventListener("DOMContentLoaded", function(e) {
            var request=new XMLHttpRequest();
            request.open('POST','api/selectCursos.php',true);
            request.onload=function(){
                hide_carga();
                if (request.status==200 && request.readyState==4) {
                    let response=JSON.parse(request.responseText);
                    console.log(response);
                    var html='';
                    ar_cursos=response.cursos;
                    for (var i = 0; i < ar_cursos.length; i++) {
                        var text=ar_cursos[i]['nombre_curso']+' '+ar_cursos[i]['descripcion'];
                        html+=
                        '<div class="itemList" onclick="select_curso('+ar_cursos[i]['curso_id']+',\''+text+'\')">'+ar_cursos[i]['nombre_curso']+' '+ar_cursos[i]['descripcion']+'</div>';
                    }
                    document.getElementById("content-cursos").innerHTML=html;
                }
            }
            request.send(null);
        });
        document.getElementById("curso").addEventListener("keyup",function(evt){
            var html='';
            for (var i = 0; i < ar_cursos.length; i++) {
                var text=ar_cursos[i]['nombre_curso']+' '+ar_cursos[i]['descripcion'];
                if (text.toUpperCase().indexOf(document.getElementById("curso").value.toUpperCase())>=0) {
                    html+=
                    '<div class="itemList" onclick="select_curso('+ar_cursos[i]['curso_id']+',\''+text+'\')">'+ar_cursos[i]['nombre_curso']+' '+ar_cursos[i]['descripcion']+'</div>';
                }
            }
            document.getElementById("content-cursos").innerHTML=html;
        });
        function select_curso(curso_id,nombre){
            document.getElementById("step-2").style.display="block";
            document.getElementById("step-3").style.display="none";
            curso_id_g=curso_id;
            document.getElementById("curso").value=nombre;
            var fd=new FormData();
            fd.append('curso_id',curso_id);
            var request=new XMLHttpRequest();
            request.open('POST','api/selectFechaHora.php',true);
            request.onload=function(){
                if (request.status==200 && request.readyState==4) {
                    let response=JSON.parse(request.responseText);
                    var html='';
                    var ar_fechaHora=response.fechaHora;
                    for (var i = 0; i < ar_fechaHora.length; i++) {
                        html+=
                        '<div class="itemList" onclick="select_fechaHora(\''+ar_fechaHora[i]['fecha']+'\',\''+ar_fechaHora[i]['hora_inicio']+'\',\''+ar_fechaHora[i]['hora_fin']+'\')">'+ar_fechaHora[i]['fecha']+' | '+ar_fechaHora[i]['hora_inicio']+' a '+ar_fechaHora[i]['hora_fin']+'</div>';
                    }
                    document.getElementById("content-fechaHora").innerHTML=html;
                }
            }
            request.send(fd);
        }
        var horaInicio_g='';
        var horaFin_g='';
        var fecha_g='';
        function select_fechaHora(fecha,horaInicio,horaFin){
            document.getElementById("step-3").style.display="block";
            fecha_g=fecha
            horaInicio_g=parseInt(horaInicio);
            horaFin_g=parseInt(horaFin);
            document.getElementById("fechaSelect").innerHTML=fecha+" | "+horaInicio+" a "+horaFin;
            var fd=new FormData();
            fd.append('curso_id',curso_id_g);
            fd.append('fecha',fecha);
            var request=new XMLHttpRequest();
            request.open('POST','api/selectAlumnosFechaCurso.php',true);
            request.onload=function(){
                if (request.status==200 && request.readyState==4) {
                    let response=JSON.parse(request.responseText);
                    var html='';
                    var ar_alumnos=response.alumnos;
                    for (var i = 0; i < ar_alumnos.length; i++) {
                        html+=
                        '<tr>'+
                            '<td>'+ar_alumnos[i]['usuario']+'</td>'+
                            '<td>'+ar_alumnos[i]['hora_marca']+'</td>'+
                            '<td><input type="number" class="usuAsi" id="usu-'+ar_alumnos[i]['asistencia_id']+'"/></td>'+
                            '<td>'+ar_alumnos[i]['asistencia']+'</td>'+
                        '</tr>';
                    }
                    document.getElementById("bodyTable").innerHTML=html;
                }
            }
            request.send(fd);
        }
        function actualizar(){
            var ar_asistencias=document.getElementsByClassName("usuAsi");
            var conError=0;
            var ar_send=[];
            for (var i = 0; i < ar_asistencias.length; i++) {
                var horaEditada=parseInt(ar_asistencias[i].value);
                if (ar_asistencias[i].value!="") {
                    if(horaInicio_g>horaEditada ||
                        horaFin_g<horaEditada){
                        ar_asistencias[i].style.color="red";
                        conError++;
                    }else{
                        var ar_aux=[];
                        ar_aux.push(ar_asistencias[i].id.replace("usu-", ""));
                        ar_aux.push(ar_asistencias[i].value);
                        ar_send.push(ar_aux);
                    }
                }else{
                    ar_asistencias[i].style.color="#333";
                }
            }
            if (conError>0) {
                alert("Revise los formatos");
                return;
            }
            var fd=new FormData();
            fd.append('fecha',fecha_g);
            fd.append('horaInicio',horaInicio_g);
            fd.append('array',JSON.stringify(ar_send));
            var request=new XMLHttpRequest();
            request.open('POST','api/updateAsistenciasFL.php',true);
            request.onload=function(){
                if (request.status==200 && request.readyState==4) {
                    console.log(request.responseText);
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