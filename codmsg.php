<?php

function getMsg($cod){
	$msg = array(
	"100" => "No tiene permisos para ver esta página. Ingrese su usuario y Contraseña.",
	"101" => "Error en los parametros de llamada del servicio.",
	"102" => "No se pudo crear la carpeta para descomprimir el archivo.",
	"103" => "No se pudo leer el archivo",
	"104" => "No se pudo extraer el archivo",
	"105" => "No se pudo encontrar el archivo .shp",
	"106" => "No se pudo encontrar el archivo .dbf",
	"107" => "El tipo de geometria de 'PUNTO' no esta permitido.",	
	"108" => "El tipo de geometria de 'LINEA' no esta permitido.",	
	"109" => "El tipo de geometria de 'POLIGONO' no esta permitido.",		
	"110" => "No se pudo cargar la cobertura en la geodatabase.",
	"111" => "El archivo empaquetado contiene mas de 1 shape.",
	"112" => "Las coordenadas no corresponden al sistema de proyección de referencia.",
	"113" => "El servicio de geoprocesamiento no está activo."
	);
	return @$msg[$cod];
}
?>
