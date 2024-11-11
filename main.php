<?php
    session_start();
    if (!isset($_SESSION['wa-idusuario']) && $_SESSION['wa-idusuario']=="0") {
        header("location: index.php");
    }
    include('config/conexion.php');
    $conexion=new conexion();
    $con=$conexion->getconection();
    global $nombre_modulo;
    $nombre_modulo = "Bienvenido";
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
                <h1>Reporte de Asistencia por curso</h1>
                <section class="main">
                    <div class="samelineDiv">
                        <label>Curso</label>
                        <input type="text" id="curso" style="width:400px;max-width:100%;">
                    </div>
                    <div class="contentList" id="content-cursos">
                        <div class="itemList">Curso 1 / asd</div>
                    </div>
                    <div id="step-2" style="margin-top: 10px;display: none;">
                        <div class="contentListTbl2">
                            <table>
                                <thead>
                                    <tr id="headerTable">
                                        <th>Persona</th>
                                        <th>Clases</th>
                                        <th>% Pre.</th>
                                        <th>% Aus.</th>
                                        <th>% Tar.</th>
                                    </tr>
                                </thead>
                                <tbody id="bodyTable">
                                    
                                </tbody>
                            </table>
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
            curso_id_g=curso_id;
            document.getElementById("curso").value=nombre;
            var fd=new FormData();
            fd.append('curso_id',curso_id);
            var request=new XMLHttpRequest();
            request.open('POST','api/ReporteAsistenciaCurso.php',true);
            request.onload=function(){
                if (request.status==200 && request.readyState==4) {
                    let response=JSON.parse(request.responseText);
                    var ar_consulta2=response.consulta2;
                    var html_header='<th>Persona</th>'+
                                        '<th>Clases</th>'+
                                        '<th>% Pre.</th>'+
                                        '<th>% Aus.</th>'+
                                        '<th>% Tar.</th>';
                    var ar_body=[];
                    var html_body='';
                    var name1=ar_consulta2[0]['usuario'];
                    var name_header=ar_consulta2[0]['usuario'];
                    for (var i = 0; i < ar_consulta2.length; i++) {
                        var asistencia='-';
                        var color='#333';
                        if (ar_consulta2[i]['asistencia']!=null) {
                            asistencia=ar_consulta2[i]['asistencia'];
                        }
                        if (asistencia=="AUS") {
                            color='#a70404';
                        }
                        if (asistencia=="TAR") {
                            color='#cdb800';
                        }
                        if (asistencia=="PRE") {
                            color='#0432a7';
                        }
                        if (name_header==ar_consulta2[i]['usuario']) {
                            html_header+=
                            '<th>'+format_fecha(ar_consulta2[i]['fecha'])+'</th>';
                        }
                        if (name1==ar_consulta2[i]['usuario']) {
                            html_body+='<td class="tdFecha '+ar_consulta2[i]['fecha']+'" style="color:'+color+';">'+asistencia+'</td>';
                        }else{
                            name1=ar_consulta2[i]['usuario'];
                            ar_body.push(html_body);
                            html_body='<td class="tdFecha '+ar_consulta2[i]['fecha']+'" style="color:'+color+';">'+asistencia+'</td>';
                        }
                    }
                    ar_body.push(html_body);
                    document.getElementById("headerTable").innerHTML=html_header;
                    console.log(ar_consulta2);

                    var html='';
                    var ar_consulta1=response.consulta1;
                    for (var i = 0; i < ar_consulta1.length; i++) {
                        html+=
                        '<tr>'+
                            '<td>'+ar_consulta1[i]['usuario']+'</td>'+
                            '<td>'+ar_consulta1[i]['total']+'</td>'+
                            '<td>'+ar_consulta1[i]['por_presente']+'%</td>'+
                            '<td>'+ar_consulta1[i]['por_ausente']+'%</td>'+
                            '<td>'+ar_consulta1[i]['por_tardanza']+'%</td>'+
                            ar_body[i]+
                        '</tr>';
                    }
                    document.getElementById("bodyTable").innerHTML=html;
                }
            }
            request.send(fd);
        }
        function format_fecha(fecha){
            var fecha_for='';
            var ar_fecha=fecha.split("-");
            fecha_for=ar_fecha[2]+"/"+ar_fecha[1];
            return fecha_for;
        }
    </script>
</body>
</html>