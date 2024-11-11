function show_product(codpro){
    window.location.href="producto.php?p="+codpro;
}
function show_carga(){
	document.getElementById("modal-carga").style.display="block";
}
function hide_carga(){
	document.getElementById("modal-carga").style.display="none";
}
function show_modal(id){
	document.getElementById(id).style.display="block";
}
function hide_modal(id){
	document.getElementById(id).style.display="none";
}