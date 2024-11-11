<?php
	function generar_token(){
        $longitud = 20;
    	return bin2hex(openssl_random_pseudo_bytes(($longitud - ($longitud % 2)) / 2));
	}