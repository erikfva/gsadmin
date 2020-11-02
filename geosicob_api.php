<?php
if (!function_exists('get_full_url')) { 

	function get_full_url() {
			$https = !empty($_SERVER['HTTPS']) && strcasecmp($_SERVER['HTTPS'], 'on') === 0;
			return
				($https ? 'https://' : 'http://').
				(!empty($_SERVER['REMOTE_USER']) ? $_SERVER['REMOTE_USER'].'@' : '').
				(isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : ($_SERVER['SERVER_NAME'].
				($https && $_SERVER['SERVER_PORT'] === 443 ||
				$_SERVER['SERVER_PORT'] === 80 ? '' : ':'.$_SERVER['SERVER_PORT']))).
				substr($_SERVER['SCRIPT_NAME'],0, strrpos($_SERVER['SCRIPT_NAME'], '/'));
	}
}
define("GEOSICOB_URL", get_full_url()."/", TRUE); //!!!! IMPORTANTE COLOCAR EL IP DEL SERVIDOR !!!!

function GET_request($page,$data){
	$get_addr = GEOSICOB_URL.$page . '?' . http_build_query($data);
	return file_get_contents($get_addr);
}

function POST_request($page,$data){
	$url = GEOSICOB_URL.$page;
	$options = 	array("http" =>
								array("method" => "POST",
											"header" => "Content-Type: application/x-www-form-urlencoded, Accept: application/json",
											"content" =>  http_build_query($data) 
								)
							);
	$content = @file_get_contents($url, NULL, stream_context_create($options));
	if (strpos($http_response_header[0], "200")) { 
		return $content;
	} else { 
		$http_response_header["success"] = 0;
		$http_response_header["msg"] = $http_response_header[0];
		return json_encode($http_response_header);
	} 

	//return file_get_contents($url, NULL, stream_context_create($options));
}

/*
 * Funcion para obtener credencial de acceso.
 * LLAMADA:
 *  $session_key = get_session_key(["username"=>"admin","password"=>"xxxx"]);
 * RESPUESTA:
 *  {"success":"1","session_key":"ti14e03166mh8qrn5o373h6tb1"}
 *  devuelve una cadena vacia si ha sido rechazado.
 */

function get_session_key($data){
	$data["opciones"] = "login,webservice";
	return GET_request("login.php",$data);
}

/*
 * Funcion para dar de baja una credencial de acceso.
 * LLAMADA:
 *  release_session_key("ti14e03166mh8qrn5o373h6tb1");
 * RESPUESTA:
 *  ninguna
 */

function release_session_key($sSessionKey){
	$data = array(
		"opciones" => "webservice",
		"session_key" => $sSessionKey
	);
	return GET_request("logout.php",$data);
}
