<?php
    if (!isset($_SESSION['ae-corusu']) || $_SESSION['ae-corusu']=="") {
?>
<div class="modal" style="display: none;" id="modal-login" onclick="hide_modal('modal-login')">
    <div class="modal-main modal-login" id="modal-body">
        <div class="modal-body">
            <button class="btn-close" onclick="hide_modal('modal-login')" style="display:flex;align-items: center;justify-content: center;"><i class="fa fa-times" aria-hidden="true"></i></button>
            <section class="contenedor-login">
                <h3 class="titulo titulo-s2">Iniciar sesión</h3>
                    <script src="https://accounts.google.com/gsi/client" async defer></script>
                    <script>
                        function decodeJwtResponse(token){
                            var base64Url = token.split('.')[1];
                            var base64 = base64Url.replace(/-/g, '+').replace(/_/g, '/');
                            var jsonPayload = decodeURIComponent(window.atob(base64).split('').map(function(c) {
                                return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2);
                            }).join(''));

                            return JSON.parse(jsonPayload);
                        }
                        function handleCredentialResponse(response) {
                            const responsePayload = decodeJwtResponse(response.credential);
                            console.log("ID: " + responsePayload.sub);
                            console.log('Full Name: ' + responsePayload.name);
                            console.log('Given Name: ' + responsePayload.given_name);
                            console.log('Family Name: ' + responsePayload.family_name);
                            console.log("Image URL: " + responsePayload.picture);
                            console.log("Email: " + responsePayload.email);
                            var fd=new FormData();
                            fd.append('corusu',responsePayload.email);
                            fd.append('pasusu','google');
                            fd.append('nomusu',responsePayload.given_name);
                            fd.append('apeusu',responsePayload.family_name);
                            var request=new XMLHttpRequest();
                            request.open('POST','api/usuario-login-google.php',true);
                            request.onload=function(){
                                console.log(request);
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
                        window.onload = function () {
                            google.accounts.id.initialize({
                                client_id: "864984012969-s2fpna18lnkbc2v5jffacrbksask93ee.apps.googleusercontent.com",
                                callback: handleCredentialResponse
                            });
                            google.accounts.id.renderButton(
                                document.getElementById("buttonDiv"),
                                { theme: "outline", size: "large" }  // customization attributes
                            );
                            google.accounts.id.prompt(); // also display the One Tap dialog
                        }
                    </script>
                    <div id="buttonDiv" style="margin-bottom: 5px;"></div> 
                    <h4 class="titulo titulo-s2" style="margin: 15px 0 5px 0;">O usa tu correo electrónico</h4>
                    <div class="text-label-login">Correo electrónico</div>
                    <input class="text-regis" autocomplete="off" type="email" id="correo" placeholder="Correo">
                    <div class="text-label-login">Contraseña</div>
                    <div style="display:flex;">
                        <input class="text-regis" autocomplete="off" type="password" id="password" placeholder="Contrase&ntilde;a">
                        <button id="btn-pass" data-state="1" onclick="ctrl_pass()" style="background:#ddd;color:#999;width: 30px;height: 47px;"><i class="fa-solid fa-eye"></i></button>
                    </div>
                    <button type="submit" class="btn" onclick="try_login()">Ingresar</button>
                    <center>
                        <div style="margin:10px 0; color: #666;">No tienes cuenta? <a href="registro.php">Regístrate gratis!</a></div>
                        <a href="_recuperar-cuenta.php">Olvidé mi contraseña</a>
                    </center>
                </div>
            </section>
        </div>
    </div>
</div>
<script type="text/javascript">
    var modal=document.getElementById("modal-body");
    modal.addEventListener("click",function(evt){
        evt.stopPropagation();
    });
</script>
<?php
    }
?>