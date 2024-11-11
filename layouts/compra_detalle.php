<style type="text/css">
	button{
		background: var(--main-blue-lg);
		color: #fff;
		width: 100%;
	}
	.content-btn-payment{
		display: flex;
		flex-direction: column;
		gap: 15px;
		margin-bottom: 15px;
		justify-content: center;
		align-items: center;
	}
	.btn-efec{
		width: 400px;
		border-radius: 5px;
		background: #999;
		color: #fff;
		font-size: 18px;
	}
	.btn-deuna{
		background: #ffbd00;
		color: #020276;
	}
	.btn-deuna:hover{
		background: #cb9700;
	}
	.div-border img{
		width: 100%;
	}
	.sp-btn-close{
		background: none;
		width: 30px;
		float: right;
		color: #333;
		height: 30px;
		padding: 0;
	}
	.sp-btn-close:hover{
		background: #ccc;
	}
</style>
<div class="contenedor-carrito">
	<h3>Confirmar pedido</h3>
	<?php
	$sumtot=0;
	$envio=2;
	for ($i=0; $i < count($carrito); $i++) { 
		$sumtot+=intval($carrito[$i]->canpro)*floatval($carrito[$i]->prepro);
	}
	?>
	<div style="display:grid;grid-template-columns: 1fr 1fr;gap:20px;">
		<div class="div-border" style="padding:0;">
			<div style="font-weight:bold; font-size: 20px;text-align: right;padding:10px 10px 5px 10px;">Total: $<?php echo $sumtot+$envio; ?></div>
			<div class="line-divider"></div>
			<div style="padding:5px 10px 10px 10px;width: 100%;">
				<h4>Seleccionar método de pago</h4>
				<div class="content-btn-payment">
					<button class="btn-efec" onclick="show_payment(1)">Efectivo</button>
					<div class="div-border" style="margin:0 40px;display: none;transition: .3s;" id="content-efectivo">
						<button onclick="close_modal('content-efectivo');" class="sp-btn-close"><i class="fa-solid fa-xmark"></i></button>
						<h4>Recuerda que el pago lo realizarás al momento que recibas tu producto en tu dirección de entrega.</h4>
						<button class="button-primary" onclick="finalizar_compra('',3)">Finalizar</button>
					</div>
					<button class="btn-efec btn-deuna" onclick="show_payment(2)">Transferencia bancaria</button>
					<div class="div-border" style="margin:0 40px;padding: 15px;display: none;transition: .3s;" id="content-banco">
						<button onclick="close_modal('content-banco');" class="sp-btn-close"><i class="fa-solid fa-xmark"></i></button>
						<h4>Transferencia bancaria</h4>
						<div>Institución bancaria: Banco Pichincha</div>
						<div>Beneficiario: Franklin Choloquinga</div>
						<div>N° de cuenta: 203560</div>
						<div>Tipo de cuenta: Ahorro</div>
						<div class="line-divider"></div>
						<h4>Pagar con deuda!</h4>
						<img src="assets/web/qr-deuna.jpeg">
						<div class="line-divider"></div>
						<div><b>Nota:</b> Enviar el comprobante de pago y N° de pedido al <a target="_blank" href="https://wa.me/593983407450?text=Hola,%20vengo%20de%20la%20web%20Arkxpres">0983407450</a> o al correo <a target="_blank" href="">reception@arkxpres.com</a></div>
						<button class="button-primary" onclick="finalizar_compra('',4)">Finalizar</button>
					</div>
				</div>
				<div class="content-btns-paypal" style="margin: 0 auto;">
					<div id="paypal-button-container"></div>
				</div>
			</div>
		</div>
		<div>
			<h3>Tarjeta aceptadas</h3>
			<img src="assets/web/visa-detalle.png" style="width: 100%;">
			<center>
				<h2>Una forma mas segura de pagar</h2>
				<div>No importa donde compre, su información esta segura con Paypal ya que no compartimos sus datos con el vendedor</div>
			</center>
			<div style="padding:15px">
				<label style="color:var(--main-header-cl);font-weight: bold;font-size: 22px;">¡IMPORTANTE!</label>
				<p style="font-weight: bold;">Querido usuario estamos en etapa de prueba usa estos datos para realizar el pago de tu compra y posterior paga en efectivo en tu casa.</p>
				<label style="color:#0070ff;font-weight: bold;">Paypal con correo:</label>
				<p>Correo: sb-x7hnq25592306@personal.example.com</p>
				<p>contraseña: qv2ZNF.Q</p>
				<label style="color:#0070ff;font-weight: bold;margin-top: 10px;">Tarjeta de crédito o débito:</label>
				<p>N°: 4032035960493351</p>
				<p>Fecha de caducidad: 04/2028</p>
				<p>Código de seguridad: 123</p>
			</div>
		</div>
	</div>
    <script src="https://www.paypal.com/sdk/js?client-id=AblVuQNd8RjL9AAFNvlk5ZU0VFKu7yHqKJYw8cdJbXATsKjsuN9eo1tmqq0Ah465LmRzTTRBI7voFeT4&components=buttons"></script>
    <script type="text/javascript">
    	function close_modal(id){
    		document.getElementById(id).style.display="none";
    	}
    	function show_payment(type){
    		if (type==1) {
    			document.getElementById("content-efectivo").style.display="block";
    			document.getElementById("content-banco").style.display="none";
    		}else{
    			document.getElementById("content-banco").style.display="block";
    			document.getElementById("content-efectivo").style.display="none";
    		}
    	}
    	const nom="<?php echo $_SESSION['ae-nomusu']; ?>"
    	const ape="<?php echo $_SESSION['c-nomusu']; ?>";
        paypal.Buttons({
            style: {
                layout: 'vertical',
                color:  'blue',
                shape:  'rect',
                label:  'paypal',
                tagline :false
            },
            createOrder: function(data, actions) {
	            return actions.order.create({
                	purchase_units: [{
                  		amount: {
                    		value: '<?php echo $sumtot+$envio; ?>'
                  		}
                	}],
                	payer: {
                		name:{
                			given_name:"<?php echo $_SESSION['ae-nomusu']; ?>",
                			surname:ape.replace(nom,"").trim()
                		},
		                address: {
		                	address_line_2:"<?php echo $_SESSION['c-dirusu']; ?>",
		                	admin_area_2:"<?php echo $_SESSION['c-ciudad']; ?>",
		                    country_code: "EC"
		                },
		                email_address:"<?php echo $_SESSION['ae-corusu']; ?>",
			            phone:{
			            	phone_type:"MOBILE",
			            	phone_number:{
			            		national_number:"<?php echo $_SESSION['c-celusu']; ?>"
			            	}
			            }
		            }
             	});
            },
            onApprove: function(data, actions) {
                return actions.order.capture().then(function(details) {
                    finalizar_compra(details.id,1);
                });
            },
            onCancel: function (data) {
                alert("Se canceló la operación");
            },
            onError: function (err) {
                alert("Se produjo un error. "+err);
            }
        }).render('#paypal-button-container');
    </script>
</div>