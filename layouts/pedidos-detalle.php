<div class="contenedor-carrito">
	<h2 class="mt10">Mis pedidos</h2>
	<div class="body-carrito">
	<?php
	function process_color($estado){
		switch ($estado) {
			case 1:
				return '#bf2a2a';
				break;
			case 2:
				return '#daa203';
				break;
			case 3:
				return '#45a709';
				break;
			default:
				return '#333';
				break;
		}
	}
	$codusu=$_SESSION['ae-codusu'];
	$sql="SELECT ped.*,sum(dp.mondetped) cantidad,
	CASE WHEN ped.estado=1 THEN
	  	'POR PAGAR'
	ELSE
	  	CASE WHEN ped.estado=2 THEN
		  	'POR ENTREGAR'
		ELSE
			CASE WHEN ped.estado=3 THEN
				'EN CAMINO'
			ELSE
				'CANCELADO'
			END
		END
	END estadotexto
	from pedido ped
	inner join detped dp
	on ped.codped=dp.codped
	where codusu=$codusu and ped.estado!=4
	group by ped.codped,ped.codusu";
	$result=mysqli_query($con,$sql);
	while ($row=mysqli_fetch_array($result)) { 
	?>
		<div class="item-pedido">
			<h3>Cod. Pedido:&nbsp;<?php echo $row['codped']; ?></h3>
			<p>Fecha:&nbsp;<?php echo $row['fecpedcre']; ?></p>
			<h4>Monto total:&nbsp;$&nbsp;<?php echo $row['cantidad']+$row['envped']; ?></h4>
			<p>Estado:&nbsp;<?php echo '<span style="color:'.process_color($row['estado']).';font-weight:bold;">'.$row['estadotexto'].'</span>'; ?></p>
			<div class="link-pedido" onclick="show_detalle(<?php echo $row['codped']; ?>)">Mostrar detalle</div>
			<div class="body-carrito" id="detalle-<?php echo $row['codped']; ?>" style="display: none;">
	<?php
		$codped=$row['codped'];
		$sql="SELECT * from detped dp
		inner join producto pro
		on dp.codpro=pro.codpro
		where dp.codped=$codped";
		$result2=mysqli_query($con,$sql);
		while ($row2=mysqli_fetch_array($result2)) { 
	?>
				<div class="item-carrito">
					<div class="part">
						<img src="assets/img/<?php echo $row2['imapro'];?>">
					</div>
					<div class="part">
						<h3><a href="producto.php?p=<?php echo $row2['codpro']; ?>"><?php echo $row2['nompro']; ?></a></h3>
						<h5>Cantidad:&nbsp;<?php echo $row2['candetped']; ?></h5>
						<h4>Total:&nbsp;$&nbsp;<?php echo $row2['mondetped']; ?></h4>
					</div>
				</div>
	<?php
		}
	?>
			</div>
		</div>
	<?php
	}
	?>
	</div>
	<?php
	if (mysqli_num_rows($result)==0) {
	?>
	<h4>Sin pedidos</h4>
	<?php
	}
	?>
</div>