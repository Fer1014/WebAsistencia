<div class="btn-close-cat"><a href="#"><i class="fa-solid fa-xmark"></i></a></div>
<h1><span class="row-cat-web">Categor&iacute;as</span></h1>
<div class="body-categoria">
<?php
	$ac=0;
	$asc=0;
	$sql="select * from categoria cat
	inner join ( 
		SELECT sc.codcat,sc.codsubcat,sc.nomsubcat,ssc.codsubsubcat,ssc.nomsubsubcat
		FROM subcat sc
		LEFT JOIN subsubcat ssc ON sc.codsubcat = ssc.codsubcat
		and sc.estado=1 and ssc.estado=1
		UNION ALL
		SELECT sc.codcat,sc.codsubcat,sc.nomsubcat,ssc.codsubsubcat,ssc.nomsubsubcat
		FROM subcat sc
		RIGHT JOIN subsubcat ssc ON sc.codsubcat = ssc.codsubcat and sc.estado=1 and ssc.estado=1
		WHERE ssc.codsubcat IS NULL
	) tbl on cat.codcat=tbl.codcat
	where cat.estado=1
	order by cat.codcat, tbl.codsubcat,tbl.codsubsubcat";
	$result=mysqli_query($con,$sql);
	while($row=mysqli_fetch_array($result)){
		if ($ac!=$row['codcat']) {
			if ($ac!=0) {
				if ($asc!=$row['codsubcat']) {
					echo 
		'</div>';//ssc
				}
				echo
	'</div>';//sc
			}
			echo
	'<div class="class-catpro" onclick="show_subcategoria('.$row['codcat'].')">
		<img src="assets/web/'.$row['imgcat'].'"/>
		<span>'.$row['nomcat'].'</span>
	</div>
	<div class="body-subcatpro" id="subcatpro-'.$row['codcat'].'" style="display:none;">';
			$ac=$row['codcat'];
			if ($asc!=$row['codsubcat']) {
				if ($asc!=0) {
					if ($ac!=$row['codcat']) {
						echo 
		'</div>';//ssc
					}
				}
				if ($row['codsubsubcat']!="") {
					echo
		'<div class="class-subcat" onclick="show_subsubcat('.$row['codsubsubcat'].')">'.$row['nomsubcat'].'&nbsp;<i class="fa-solid fa-caret-down"></i></div>
		<div class="body-ssc" id="ssc-'.$row['codsubsubcat'].'" style="display:none;">';
				}else{
					echo
		'<a href="busqueda.php?c='.$row['codcat'].'&sc='.$row['codsubcat'].'">
			<div class="class-subcat">'.$row['nomsubcat'].'</div>
		</a>
		<div>';
				}
				$asc=$row['codsubcat'];
			}
		}else{
			if ($asc!=$row['codsubcat']) {
				if ($asc!=0) {
						echo 
		'</div>';//ssc
				}
				if ($row['codsubsubcat']!="") {
					echo
		'<div class="class-subcat" onclick="show_subsubcat('.$row['codsubsubcat'].')">'.$row['nomsubcat'].'&nbsp;<i class="fa-solid fa-caret-down"></i></div>
		<div class="body-ssc" id="ssc-'.$row['codsubsubcat'].'" style="display:none;">';
				}else{
					echo
		'<a href="busqueda.php?c='.$row['codcat'].'&sc='.$row['codsubcat'].'">
			<div class="class-subcat">'.$row['nomsubcat'].'</div>
		</a>
		<div>';
				}
				$asc=$row['codsubcat'];
			}
		}
		if ($row['codsubsubcat']!="") {
			echo
			'<a href="busqueda.php?c='.$row['codcat'].'&sc='.$row['codsubcat'].'&ssc='.$row['codsubsubcat'].'">
				<div class="class-subsubcat">'.$row['nomsubsubcat'].'</div>
			</a>';
		}
	}
	echo
		'</div>
	</div>';
?>
		</div>
	</div>
</div>