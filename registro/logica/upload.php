<?php

$ruta = '../imagenes/';

foreach ($_FILES as $archivo) {
	$imagenOriginal = $archivo["name"];
	$extension = pathinfo($imagenOriginal, PATHINFO_EXTENSION);
	$imagenServidor = date("Y").date("m").date("d")."_".date("h").date("i").date("s").".".$extension;
    move_uploaded_file($archivo["tmp_name"], $ruta.$imagenServidor);
}

echo "{\"resultado\": true, \"imagenServidor\": \"$imagenServidor\", \"imagenOriginal\": \"$imagenOriginal\"}";