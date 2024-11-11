<style type="text/css">
	input{
		border:1px solid #ddd;
	}
	button{
		background: var(--main-blue-lg);
		color: #fff;
		width: 100%;
	}
	.div-line{
		display: grid;
		grid-template-columns: 3fr 1fr;
		font-size: 13px;
	}
	.div-line div:nth-child(2){
		text-align: right;
	}
	.link-a{
		color: #0093ff;
		text-decoration: underline;
		cursor: pointer;
	}
	.link-a:hover{
		color: #0361a7;
	}
	.btn-float{
		position: absolute;
		top: 18px;
		right: 24px;
	}
	.content-direcciones{
		display: flex;
		flex-direction: column;
	}
	.div-direccion{
		display: grid;
		grid-template-columns: 2fr 1fr;
		padding: 5px;
		font-size: 14px;
	}
	.div-direccion .text-dir{
		height: 24px;
		overflow: hidden;
	}
	.div-nota{
		background: #dfffdf;
		padding: 5px;
		margin-top: 10px;
		color: #022053;
	}
	.save-dirs{
		cursor: pointer;
		width: 100%;
		display: grid;
		grid-template-columns: 30px 1fr 30px;
		gap: 10px;
		border-bottom: 1px solid #ccc;
	}
	.save-dirs .icon-dir{
		padding: 5px;
	}
	.save-dirs .select-dir{
		margin-top: 10px;
		width: 25px;
		height: 25px;
		border-radius: 50%;
		border:1px solid #ccc;
		display: flex;
		justify-content: center;
		align-items: center;
		color: #fff;
	}
	.add-dir{
		padding: 5px;
		color: var(--main-green-lg);
		cursor: pointer;
		font-size: 18px;
	}
</style>
<?php
$sumtot=0;
$envio=2;
for ($i=0; $i < count($carrito); $i++) { 
	$sumtot+=intval($carrito[$i]->canpro)*floatval($carrito[$i]->prepro);
}
$codusu=$_SESSION['ae-codusu'];
$sql="select * from usudir where codusu=$codusu";
$result_dirs=mysqli_query($con,$sql);
$num_dirs=mysqli_num_rows($result_dirs);
$display1='style="display:none;"';
$display2='style="display:none;"';
if ($num_dirs>0) {
	$display1='style="display:block;"';
}else{
	$display2='style="display:block;"';
}
?>	
<div class="contenedor-carrito">
	<div style="display:grid;grid-template-columns: 1fr 250px; gap: 20px; margin-top: 20px;">
		<div class="div-border" id="block-datos" style="display:none;">			
			<!--<h3>¿Qué desea?</h3>
			<div style="display:flex;">
				<input type="radio" id="factura" name="tipodoc">
				<label for="factura" style="margin-left:5px;">Factura</label>
				<input type="radio" id="nofactura" name="tipodoc" style="margin-left:20px;">
				<label for="nofactura" style="margin-left:5px;">Consumidor final</label>
			</div>-->
			<?php
			$sql="SELECT * from usuario where codusu=$codusu";
			$result=mysqli_query($con,$sql);
			$usuario=mysqli_fetch_array($result);
			?>
			<div class="form-buy">
				<h3>Datos de facturaci&oacute;n</h3>
				<div class="content-carrito-detalle">
					<div>
						<label>Nombres Completos / Raz&oacute;n Social</label><br>
						<input type="text" id="idnombres" placeholder="Nombres Completos / Raz&oacute;n Social" value="<?php echo $usuario['nomusu']; ?>">
					</div>
					<div>
						<label>Tipo de identificaci&oacute;n</label>
						<br>
						<select id="idcodtipide">
							<option value="1">C&eacute;dula</option>
							<option value="2">RUC</option>
							<option value="3">Pasaporte</option>
							<option value="4">Identificación del exterior</option>
							<option value="5">Consumidor final</option>
						</select>
					</div>
					<div>
						<label>Identificaci&oacute;n</label><br>
						<input type="number" id="ididentificacion" placeholder="Identificaci&oacute;n" value="<?php echo $usuario['numdocusu']; ?>">
					</div>
					<div>
						<label>Tel&eacute;fono / Celular</label><br>
						<input type="number" id="idcelular" placeholder="Tel&eacute;fono / Celular" value="<?php echo $usuario['celusu']; ?>">
					</div>
					<div>
						<label>Correo (para envío de comprobante)</label><br>
						<input type="text" id="idcorreo" placeholder="Correo" value="<?php echo $usuario['corusu']; ?>">
					</div>
				</div>
				<div style="text-align: right;">
					<button class="button-primary mt10 button-inverse" style="width: 150px;" onclick="show_formAllDirs()">Atrás</button>
				</div>
			</div>
		</div>
		<script type="text/javascript">
			var codusudir_g="0";
			function restart_dir(){
				var ar_dom=document.getElementsByClassName("select-dir");
				for (var i = 0; i < ar_dom.length; i++) {
					ar_dom[i].style.background="#fff";
				}
			}
			function select_dir(codusudir){
				codusudir_g=codusudir;
				restart_dir();
				document.getElementById("dir-"+codusudir).style.background="var(--main-green-lg)";
			}
			function show_formDir(){
				codusudir_g="0";
				restart_dir();
				document.getElementById("block-createDir").style.display="block";
				document.getElementById("block-dirs").style.display="none";
			}
			function show_formAllDirs(){
				document.getElementById("btn-comprar").style.display="none";
				document.getElementById("block-datos").style.display="none";
				document.getElementById("block-createDir").style.display="none";
				document.getElementById("block-dirs").style.display="block";
			}
			function show_formDatos(){
				if (codusudir_g=="0") {
					alert("Debe seleccionar una dirección");
					return;
				}
				document.getElementById("btn-comprar").style.display="block";
				document.getElementById("block-datos").style.display="block";
				document.getElementById("block-dirs").style.display="none";
			}
		</script>
		<div class="div-border" id="block-dirs" <?php echo $display1; ?>>
			<h3>Elige tu dirección de entrega</h3>
			<div id="all-dirs">
			<?php
			while($row2=mysqli_fetch_array($result_dirs)){
				echo
				'<div class="save-dirs" onclick="select_dir('.$row2['codusudir'].')">
					<div class="icon-dir"><img src="assets/web/ubicacion.png" style="width:100%;"/></div>
					<div class="content-dir">
						<h4>'.$row2['dirusu'].'</h4>
						<p>'.$row2['codposusu'].' - '.$row2['ciuusu'].'</p>
					</div>
					<div class="select-dir" id="dir-'.$row2['codusudir'].'">
						<div class="animate-select-dir"><i class="fa-solid fa-check"></i></div>
					</div>
				</div>';
			}
			?>
			</div>
			<div>
				<div class="add-dir" onclick="show_formDir()"><i class="fa-solid fa-plus"></i>&nbsp;Añadir dirección</div>
			</div>
			<div style="text-align: right;">
				<button class="button-primary mt10" style="width:150px" onclick="show_formDatos()">Siguiente</button>
			</div>
		</div>
		<div class="div-border" id="block-createDir" <?php echo $display2; ?>>
			<h3>Ingresa tu dirección de entrega</h3>
			<div style="display:grid;grid-template-columns: 250px 1fr; gap: 20px;">
				<div>
					<label>Escribe tu dirección</label>
					<input type="text" id="ubicacion" placeholder="Dirección para el mapa" style="margin-bottom:5px; padding: 5px 10px;width: 100%;">
					<div class="link-a" onclick="set_autolocation()">Ubicacion actual</div>
					<div style="display:grid;grid-template-columns: 1fr 1fr; gap: 5px;">
						<input type="number" id="codpostal" placeholder="Código postal" style="margin-bottom:5px; padding: 5px 10px;width: 100%;">
						<input type="text" id="ciudad" placeholder="Ciudad" style="margin-bottom:5px; padding: 5px 10px;width: 100%;">
					</div>
					<div style="display:grid;grid-template-columns: 1fr 1fr; gap: 5px;">
						<input type="number" id="ncasa" placeholder="N° casa" style="margin-bottom:5px; padding: 5px 10px;width: 100%;">
						<input type="number" id="npiso" placeholder="N° piso/N° puerta" style="margin-bottom:5px; padding: 5px 10px;width: 100%;">
					</div>	
					<input type="text" id="direccion" placeholder="Dirección" style="margin-bottom:5px; padding: 5px 10px;width: 100%;">
					<input type="number" id="celular" placeholder="Celular del que recibe" style="margin-bottom:5px; padding: 5px 10px;width: 100%;">
					<input type="text" id="referencia" placeholder="Referencia (opcional)" style="margin-bottom:5px; padding: 5px 10px;width: 100%;">
					<button class="button-primary mt10" onclick="save_direccion()">Guardar dirección</button>
					<button class="button-primary mt10 button-inverse" onclick="show_formAllDirs()">Cancelar</button>
					<!--
					<input type="text" id="provincia" placeholder="Provincia" style="margin-bottom:5px; padding: 5px 10px;width: 100%;">-->
				</div>
				<div>
					<div class="link-a btn-float" onclick="map_fullscreen()">Expandir mapa</div>
					<label style="margin:10px 5px 0 0;">Elige tu ubicaci&oacute;n</label>
					<style type="text/css">
						#map {
						  	height: 450px;
						  	width: 100%;
						}
						.btn-close-map{
							z-index: 5;
							position: fixed;
							top: 0;
							left: 0;
							background: #fff;
							width: 30px;
							height: 30px;
							cursor: pointer;
							font-size: 25px;
							display: flex;
							justify-content: center;
							align-items: center;
						}
					</style>
					<div id="map" style="position:relative;top: 0;left: 0;z-index: 2;"></div>
					<div class="btn-close-map" id="btn-close-map" style="display:none;" onclick="close_fullscreen()"><i class="fa-solid fa-xmark"></i></div>
					<input type="number" id="idlatusu" style="display: none;" value="<?php echo $usuario['latusu']; ?>">
					<input type="number" id="idlngusu" style="display: none;" value="<?php echo $usuario['lngusu']; ?>">
				</div>
			</div>
		</div>
		<div class="div-border">
			<h3>Resumen</h3>
			<div class="div-line">
				<div class="text">Importe de productos</div>
				<div class="monto">$<?php echo $sumtot; ?></div>
			</div>
			<div class="div-line">
				<div class="text">Envío</div>
				<div class="monto">$<?php echo $envio; ?></div>
			</div>
			<div style="font-weight: bold;font-size: 18px;" class="div-line">
				<div class="text">Total</div>
				<div class="monto">$<?php echo $sumtot+$envio; ?></div>
			</div>
			<div class="div-line">
				<div class="text">IVA incluido</div>
			</div>
			<button class="button-primary mt10" onclick="procesar_compra()" id="btn-comprar" style="display:none;">Procesar compra</button>
			<button class="button-primary mt10 button-inverse" onclick="window.location.href='index.php';">Continuar comprando</button>
		</div>
	</div>
</div>
<div class="div-nota"><b>NOTA:</b> Una vez finalizada la compra, no se podrá cambiar la entrega</div>
</div>
<script async
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCR6vf2l6YXoeqKU4R9gb9R2MT08vcP_4k&callback=initMap&libraries=places">
</script>
<script src="js/main-script-map.js"></script>
<script type="text/javascript">
	function save_direccion(){
		if (document.getElementById("direccion").value=="") {
			alert("Complete una dirección");
			return 0;
		}
		if (document.getElementById("ciudad").value=="") {
			alert("Complete una ciudad");
			return 0;
		}
		if (document.getElementById("celular").value=="") {
			alert("Complete un celular");
			return 0;
		}
		var fd=new FormData();
        fd.append('direccion',document.getElementById("direccion").value);
        fd.append('ciudad',document.getElementById("ciudad").value);
        fd.append('celular',document.getElementById("celular").value);
        fd.append('referencia',document.getElementById("referencia").value);
        fd.append('ncasa',document.getElementById("ncasa").value);
        fd.append('npiso',document.getElementById("npiso").value);
        fd.append('codpostal',document.getElementById("codpostal").value);
        fd.append('lat',document.getElementById("idlatusu").value);
        fd.append('lng',document.getElementById("idlngusu").value);
        var request=new XMLHttpRequest();
        request.open('POST','api/usuario-save-newAddress.php',true);
        request.onload=function(){
            if (request.status==200 && request.readyState==4) {
                hide_carga();
                let data=JSON.parse(request.responseText);
                if (!data.state) {
                	alert(data.detail);
                }else{
                	document.getElementById("all-dirs").innerHTML=data.html;
                	document.getElementById("direccion").value="";
                	document.getElementById("ciudad").value="";
                	document.getElementById("celular").value="";
                	document.getElementById("referencia").value="";
                	document.getElementById("ncasa").value="";
                	document.getElementById("npiso").value="";
                	document.getElementById("codpostal").value="";
                	show_formAllDirs();
                }
            }
        }
        request.send(fd);
	}
</script>