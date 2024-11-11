<?php
    session_start();
    if (!isset($_SESSION['wa-idusuario']) && $_SESSION['wa-idusuario']=="0") {
        header("location: index.php");
    }
    include('config/conexion.php');
    $conexion=new conexion();
    $con=$conexion->getconection();
    global $nombre_modulo;
    $nombre_modulo = "Reprogramaci&oacute;n de clases";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php include('layouts/nombre-web.php'); ?></title>
    <link rel="stylesheet" href="css/login.css">
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
                        <div id="step-3" style="margin-top: 10px;display: block;">
                            <div class="contentListTbl2">
                                <table>
                                    <thead>
                                        <tr>
                                            <th class="minTdTbl1"></th>
                                            <th class="minTdTbl">Aula</th>
                                            <th class="minTdTbl">Fecha</th>
                                            <th class="minTdTbl">Inicio</th>
                                            <th class="minTdTbl">Fin</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="minTdTbl1">Original</td>
                                            <td class="minTdTbl" id="aula"></td>
                                            <td class="minTdTbl" id="fecha"></td>
                                            <td class="minTdTbl" id="horaInicio"></td>
                                            <td class="minTdTbl" id="horaFin"></td>
                                        </tr>
                                        <tr>
                                            <td class="minTdTbl1">Propuesto</td>
                                            <td class="minTdTbl"><select id="aula_id"></select></td>
                                            <td class="minTdTbl"><input type="date" id="fec_act"></td>
                                            <td class="minTdTbl"><input type="number" id="horaInicio_act"></td>
                                            <td class="minTdTbl"><input type="numer" id="horaFin_act"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <button style="margin-top:5px;" onclick="reprogramar();">Reprogramar</button>
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
            request.open('POST','api/RCselectFechaHora.php',true);
            request.onload=function(){
                if (request.status==200 && request.readyState==4) {
                    let response=JSON.parse(request.responseText);
                    var html='';
                    var ar_fechaHora=response.fechaHora;
                    for (var i = 0; i < ar_fechaHora.length; i++) {
                        html+=
                        '<div class="itemList" onclick="select_fechaCurso(\''+ar_fechaHora[i]['fecha']+'\',\''+ar_fechaHora[i]['hora_inicio']+'\',\''+ar_fechaHora[i]['hora_fin']+'\','+ar_fechaHora[i]['aula_id']+')">'+ar_fechaHora[i]['fecha']+' | '+ar_fechaHora[i]['hora_inicio']+' a '+ar_fechaHora[i]['hora_fin']+'</div>';
                    }
                    document.getElementById("content-fechaHora").innerHTML=html;
                }
            }
            request.send(fd);
        }
        var horaInicio_g='';
        var horaFin_g='';
        var horaInicio_gt='';
        var horaFin_gt='';
        var fecha_g='';
        var aula_id_g='';
        function select_fechaCurso(fecha,horaInicio,horaFin,aula_id){
            document.getElementById("step-3").style.display="block";
            fecha_g=fecha;
            aula_id_g=aula_id;
            horaInicio_g=parseInt(horaInicio);
            horaFin_g=parseInt(horaFin);
            horaInicio_gt=horaInicio;
            horaFin_gt=horaFin;
            document.getElementById("fecha").innerHTML=fecha;
            document.getElementById("horaInicio").innerHTML=horaInicio;
            document.getElementById("horaFin").innerHTML=horaFin;
            document.getElementById("horaInicio_act").value=horaInicio;
            document.getElementById("horaFin_act").value=horaFin;
            document.getElementById("fec_act").value=fecha;
            var fd=new FormData();
            fd.append('curso_id',curso_id_g);
            fd.append('aula_id',aula_id);
            var request=new XMLHttpRequest();
            request.open('POST','api/RCselectInfoClase.php',true);
            request.onload=function(){
                if (request.status==200 && request.readyState==4) {
                    let response=JSON.parse(request.responseText);
                    document.getElementById("aula").innerHTML=response.aula;
                    var html='';
                    var ar_aulas=response.aulas;
                    for (var i = 0; i < ar_aulas.length; i++) {
                        html+=
                        '<option value="'+ar_aulas[i]['aula_id']+'">'+ar_aulas[i]['nombre_aula']+'</tr>';
                    }
                    document.getElementById("aula_id").innerHTML=html;
                    document.getElementById("aula_id").value=aula_id;
                }
            }
            request.send(fd);
        }
        function reprogramar(){
            var aula_id=document.getElementById("aula_id").value;
            var fecha=document.getElementById("fec_act").value;
            var horaInicio=document.getElementById("horaInicio_act").value;
            var horaFin=document.getElementById("horaFin_act").value;
            if (aula_id==aula_id_g &&
                fecha==fecha_g &&
                horaInicio==horaInicio_g &&
                horaFin==horaFin_g) {
                alert("Debe cambiar almenos un dato para reprogramar la clase");
                return;
            }
            var min_act=parseInt(horaFin.substring(0,2))*60+parseInt(horaFin.substring(2,4))-
                            parseInt(horaInicio.substring(0,2))*60-parseInt(horaInicio.substring(2,4));
            var min_g=parseInt(horaFin_gt.substring(0,2))*60+parseInt(horaFin_gt.substring(2,4))-
                            parseInt(horaInicio_gt.substring(0,2))*60-parseInt(horaInicio_gt.substring(2,4));
            console.log(min_act,min_g);
            if (min_act != min_g) {
                alert("Las clase reprogramada debe durar las mismas horas que la clase original");
                return;
            }
            var ar_fechaAct=fecha.split("-");
            var fechaAct_num=ar_fechaAct[0]+ar_fechaAct[1]+ar_fechaAct[2];
            var ar_fecha=fecha_g.split("-");
            var fecha_num=ar_fecha[0]+ar_fecha[1]+ar_fecha[2];
            if (fechaAct_num<fecha_num) {
                alert("La fecha no puede ser anterior a la actual");
                return;
            }
            var fd=new FormData();
            fd.append('fecha',fecha);
            fd.append('horaInicio',horaInicio);
            fd.append('horaFin',horaFin);
            fd.append('aula_id',aula_id);
            fd.append('curso_id',curso_id_g);
            fd.append('fecha_ori',document.getElementById("fecha").innerHTML);
            fd.append('horaInicio_ori',document.getElementById("horaInicio").innerHTML);
            fd.append('horaFin_ori',document.getElementById("horaFin").innerHTML);
            fd.append('aula_id_ori',aula_id_g);
            var request=new XMLHttpRequest();
            request.open('POST','api/RCupdate.php',true);
            request.onload=function(){
                if (request.status==200 && request.readyState==4) {
                    console.log(request.responseText);
                    let response=JSON.parse(request.responseText);
                    if (response.state) {
                        alert("Curso reprogramado");
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