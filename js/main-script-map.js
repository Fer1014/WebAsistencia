function map_fullscreen(){
	document.getElementById("map").style.position="fixed";
	document.getElementById("map").style.height="100vh";
	document.getElementById("btn-close-map").style.display="flex";
}
function close_fullscreen(){
	document.getElementById("map").style.position="relative";
	document.getElementById("btn-close-map").style.display="none";
	document.getElementById("map").style.height="450px";
}
var lat=0;
var lng=0;
if (document.getElementById("idlatusu").value!="") {
	lat=parseFloat(document.getElementById("idlatusu").value);
	lng=parseFloat(document.getElementById("idlngusu").value);
}
var new_pos;
var map=null;
var marker=null;
function initMap() {
	if (lat==0) {
		set_autolocation();
	}
  	const position = { lat: lat, lng:lng };
	map = new google.maps.Map(document.getElementById("map"), {
	    zoom: 16,
	    center: position,
	});
	marker = new google.maps.Marker({
	    position: position,
	    map: map,
	});						
	map.addListener("click", (mapsMouseEvent) => {
		marker.setMap(null);
		lat=mapsMouseEvent.latLng.lat();
		lng=mapsMouseEvent.latLng.lng();
		document.getElementById("idlatusu").value=lat;
		document.getElementById("idlngusu").value=lng;
		marker = new google.maps.Marker({
		    position: mapsMouseEvent.latLng,
		    map: map,
		});
	});
	const input = document.getElementById("ubicacion");
	const options = {
	  fields: ["address_components", "geometry", "icon", "name"],
	  strictBounds: false,
	  types: ["geocode"],//establishment
	};
	const autocomplete = new google.maps.places.Autocomplete(input, options);
	google.maps.event.addListener(autocomplete, 'place_changed', function () {
        var place = autocomplete.getPlace();
        var components=place.address_components;
        for (var i = 0; i < components.length; i++) {
        	//console.log(components[i]);
        	if (components[i].types[0]=="postal_code") {
        		document.getElementById("codpostal").value=components[i].long_name;
        	}
        	if (components[i].types[0]=="locality") {
        		document.getElementById("ciudad").value=components[i].long_name;
        	}
        }
        lat=place.geometry.location.lat();
        lng=place.geometry.location.lng();
		document.getElementById("idlatusu").value=lat;
		document.getElementById("idlngusu").value=lng;
		marker.setMap(null);
		new_pos= { lat: lat, lng:lng };	
		map.setCenter(new google.maps.LatLng(
		  lat, lng
		));	
		marker = new google.maps.Marker({
		    position: new_pos,
		    map: map,
		});	
    });
}
window.initMap = initMap;
function set_autolocation(){		
	if (navigator.geolocation) {
	    navigator.geolocation.getCurrentPosition((position)=>{
			lat=position.coords.latitude;
			lng=position.coords.longitude;
			document.getElementById("idlatusu").value=lat;
			document.getElementById("idlngusu").value=lng;		
			marker.setMap(null);
			new_pos= { lat: lat, lng:lng };
			map.setCenter(new google.maps.LatLng(
			  lat, lng
			));
			marker = new google.maps.Marker({
	    		position: new_pos,
			    map: map,
			});
		});
	} else {
		alert("Geolocalización no está disponible en este navegador");
		lat=-1.6255950862951274;
		lng=-78.57182528628597;
		document.getElementById("idlatusu").value=lat;
		document.getElementById("idlngusu").value=lng;		
		marker.setMap(null);
		new_pos= { lat: lat, lng:lng };
		map.setCenter(new google.maps.LatLng(
		  lat, lng
		));
		marker = new google.maps.Marker({
	    	position: new_pos,
		    map: map,
		});
	}
}