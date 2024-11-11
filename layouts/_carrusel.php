<div class="slider-body">
	<div class="slider-container" id="ctrl-carrusel"> 
	<?php
	$sql="select * from banner where codban in (1,2,3)";
	$result=mysqli_query($con,$sql);
	while($row=mysqli_fetch_array($result)){
		echo
		'<img class="slider-item" src="assets/banner/'.$row['nomban'].'"/>';
	}
	?>
	</div>
	<div class="ctrl-buttons">
	  	<div class="btn-carrusel" id="car-1" onclick="move_carrusel(1)"><span class="car-notarget car-target"></span></div>
	  	<div class="btn-carrusel" id="car-2" onclick="move_carrusel(2)"><span class="car-notarget"></span></div>
	  	<div class="btn-carrusel" id="car-3" onclick="move_carrusel(3)"><span class="car-notarget"></span></div>
  	</div>
</div>
<div class="buttons-adi">
	<div class="content-buttons-adi" id="content-buttons-adi">
		<div class="aux-content-buttons-adi">
		<?php
		$sql="select * from botpri where estado=1";
		$result=mysqli_query($con,$sql);
		while($row=mysqli_fetch_array($result)){
			echo
			'<div class="button-adi" onclick="show_type_products('.$row['codbotpri'].')">
				<img src="assets/web/'.$row['imgbotpri'].'">
				<div class="text-button-adi">'.$row['nombotpri'].'</div>
				<div class="text-min">'.$row['desbotpri'].'</div>
			</div>';
		}
		?>
		</div>
	</div>
	<div class="btn-ctrl izq-ctrl" onclick="move_button_adi(1)"><i class="fa-solid fa-angle-left"></i></div>
	<div class="btn-ctrl der-ctrl" onclick="move_button_adi(2)"><i class="fa-solid fa-angle-right"></i></div>
</div>
<script type="text/javascript">
	function show_type_products(code){
		window.location.href="busqueda.php?t="+code;
	}
	var btns_adi=document.getElementsByClassName("button-adi");
	var can_btns_adi=btns_adi.length;
	var tam_btns_adi=btns_adi[0].offsetWidth;
	var tam_content_btns=document.getElementsByClassName("buttons-adi")[0].offsetWidth;
	var translado_content=0;
	var dom_btns=document.getElementById("content-buttons-adi");
	function move_button_adi(type){
		if (type==2) {
			translado_content-=tam_btns_adi;
		}else{
			translado_content+=tam_btns_adi;
		}
		if (translado_content>0) {
			translado_content=0;
		}
		if (translado_content<-1*(tam_btns_adi*can_btns_adi-tam_content_btns+80)) {
			translado_content=-1*(tam_btns_adi*can_btns_adi-tam_content_btns+80);
		}
		console.log(translado_content);
		dom_btns.style.transform="translateX("+translado_content+"px)";
	}
	var carrusel=document.getElementById("ctrl-carrusel");
	var tam_width_carrusel=document.getElementById("ctrl-carrusel").offsetWidth;
	var tim_sli=6;
	var num_ban_sli=1;
	var timer_carrusel=setInterval(function(){
		if (num_ban_sli>2) {
			carrusel.scrollLeft=0;
			num_ban_sli=0;
		}else{
			carrusel.scrollLeft+=tam_width_carrusel;
		}
		num_ban_sli++;
		var ar=document.getElementsByClassName("btn-carrusel");
		for (var i = 0; i < ar.length; i++) {
			ar[i].getElementsByTagName("span")[0].classList.remove("car-target");
		}
		document.getElementById("car-"+num_ban_sli).getElementsByTagName("span")[0].classList.add("car-target");
	},tim_sli*1000);
	function move_carrusel(id){
		num_ban_sli=id;
		carrusel.scrollLeft=(num_ban_sli-1)*tam_width_carrusel;
		var ar=document.getElementsByClassName("btn-carrusel");
		for (var i = 0; i < ar.length; i++) {
			ar[i].getElementsByTagName("span")[0].classList.remove("car-target");
		}
		document.getElementById("car-"+num_ban_sli).getElementsByTagName("span")[0].classList.add("car-target");
		clearInterval(timer_carrusel);
		timer_carrusel=setInterval(function(){
			if (num_ban_sli>2) {
				carrusel.scrollLeft=0;
				num_ban_sli=0;
			}else{
				carrusel.scrollLeft+=tam_width_carrusel;
			}
			num_ban_sli++;
			var ar=document.getElementsByClassName("btn-carrusel");
			for (var i = 0; i < ar.length; i++) {
				ar[i].getElementsByTagName("span")[0].classList.remove("car-target");
			}
			document.getElementById("car-"+num_ban_sli).getElementsByTagName("span")[0].classList.add("car-target");
		},tim_sli*1000);
	}
</script>