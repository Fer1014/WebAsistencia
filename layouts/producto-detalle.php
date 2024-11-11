<?php
	function replace_char($text){
		return str_replace(";","<br>", $text);
	}
	$codpro=$_GET['p'];
	$sql="select * from producto
	where estado=1
	and codpro=$codpro";
	$result=mysqli_query($con,$sql);
	while($row=mysqli_fetch_array($result)){
?>
<style type="text/css">
	.img-zoom{
  		overflow: hidden;
  		background-position: 50% 50%;
		border: 5px solid #fff;
		width: 100%;
		height: 500px;
	}
	.img-zoom img{
	}
	.img-zoom img:hover{
		opacity: 0;
	}
</style>
		<div class="show-producto">
			<div class="part-producto">
				<div style="border:1px solid #ccc;">
					<figure class="img-zoom" onmousemove="zoom(event)" id="img-select" style="background-image: url(assets/img/<?php echo $row['imapro']; ?>)">
						<img src="assets/img/<?php echo $row['imapro']; ?>" id="img-select2">
					</figure>
				</div>
				<div class="content-fotos">
					<div class="fotos-detail" onclick="change_img('<?php echo $row['imapro']; ?>')">
						<img src="assets/img/<?php echo $row['imapro']; ?>">
					</div>
					<?php
					$sql2="select * from imgpro where codpro=$codpro and estado=1";
					$result2=mysqli_query($con,$sql2);
					while($row2=mysqli_fetch_array($result2)){
						echo
					'<div class="fotos-detail" onclick="change_img(\''.$row2['imapro'].'\')">
						<img src="assets/img/'.$row2['imapro'].'">
					</div>';
					}
					?>
				</div>
			</div>
			<div class="part-producto">
	            <div class="btn-fav" title="Añadir a favorito" id="product-fav-<?php echo $row['codpro']; ?>" onclick="add_fav(<?php echo $row['codpro']; ?>)"><i class="fa-regular fa-heart"></i></div>
				<h1><?php echo $row['nompro']; ?></h1>
				<h2 style="font-size:30px;">$&nbsp;<?php echo $row['prepro']; ?></h2>
				<p style="font-size:15px;">Cant.:&nbsp;<?php echo $row['stopro']; ?></p>
				<div class="line-divider"></div>
				<h4>Descripci&oacute;n</h4>
				<p><?php echo replace_char($row['despro']); ?></p>
				<div class="line-divider"></div>
				<div style="max-width:300px;width: 100%;">
					<h4>En carro</h4>
		            <div class="options-buy">
		                <div id="content-product-<?php echo $row['codpro']; ?>" style="transition: .3s;">
		                    <div class="div-btn" onclick="add_car(<?php echo $row['codpro']; ?>)">Añadir al carrito</div>
		                    <div class="select-quantity">
		                        <div class="quantity"><span id="quantity-product-<?php echo $row['codpro']; ?>">0</span>&nbsp;uds.</div>
		                        <div class="btns-mas-menos">
		                            <div class="btn-menos" onclick="add_quantiy(-1,<?php echo $row['codpro']; ?>)"><i class="fa-solid fa-minus"></i></div>
		                            <div class="btn-mas" onclick="add_quantiy(1,<?php echo $row['codpro']; ?>)"><i class="fa-solid fa-plus"></i></div>
		                        </div>
		                    </div>
		                </div>
		            </div>
	            </div>
	            <style type="text/css">
	            	.btn-compartir{
	            		cursor: pointer;
	            		color: #00a373;
	            		padding: 10px 15px;
	            		font-size: 22px;
	            	}
	            	.btn-compartir:hover{
	            		text-decoration: underline;
	            	}
	            </style>
				<div class="btn-compartir" onclick="copy_clipboard(<?php echo $row['codpro']; ?>)"><i class="fa-solid fa-share"></i> Compartir</div>
			</div>
		</div>
<?php
	}
?>
<script type="text/javascript">
	function zoom(e) {
		/*
		console.log(x,y);
		x=x;
		y=y;
	    document.getElementById("img-select").style.transformOrigin=x+"px "+y+"px";*/
	    var zoomer = e.currentTarget;
	  	e.offsetX ? offsetX = e.offsetX : offsetX = e.touches[0].pageX
	  	e.offsetY ? offsetY = e.offsetY : offsetX = e.touches[0].pageX
	  	x = offsetX/zoomer.offsetWidth*100;
	  	y = offsetY/zoomer.offsetHeight*100;
	  	zoomer.style.backgroundPosition = x + "% " + y + "%";
	}
	function change_img(path){
		document.getElementById("img-select").style.backgroundImage="url(assets/img/"+path+")";
		document.getElementById("img-select2").src="assets/img/"+path;
	}
</script>