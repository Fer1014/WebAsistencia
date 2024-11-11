<?php
	$sql="select * from producto where estado=1";
	$result=mysqli_query($con,$sql);
	while($row=mysqli_fetch_array($result)){
?>
		<div class="producto">
            <div class="btn-fav" title="Añadir a favorito" id="product-fav-<?php echo $row['codpro']; ?>" onclick="add_fav(<?php echo $row['codpro']; ?>)"><i class="fa-regular fa-heart"></i></div>
            <div class="cuerpo-producto" onclick="show_product(<?php echo $row['codpro']; ?>)">
                <div class="section-img">
                    <img src="assets/img/<?php echo $row['imapro']; ?>">
                </div>
                <div class="descripcion">
                    <p class="nombre-producto"><?php echo $row['nompro']; ?></p>
                    <p class="stock" style="font-size:14px;"><b>Cant.:&nbsp;<?php echo $row['stopro']; ?></b></p>
                    <p class="precio">$&nbsp;<?php echo $row['prepro']; ?></p>
                </div>
            </div>
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
<?php
	}
?>