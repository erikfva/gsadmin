
<form name="form1" method="post" action="">
  <div align="center">
    <INPUT onClick="javascript:window.close()" TYPE="BUTTON" VALUE="Cerrar" TITLE="Haga clic aquí para cerrar la ventana" NAME="CloseWindow" >
  </div>
</form>
<?php

//$url = 'http://localhost/abt/shapefilesadd.php';
define("GEOSICOB_URL", "http://localhost/abt/", TRUE);

function GET_request($page,$data){
	$data["opciones"] = (!empty($data["opciones"])?$data["opciones"]."," : "") . "webservice";
	$get_addr = GEOSICOB_URL.$page . '?' . http_build_query($data);
	return file_get_contents($get_addr);
}

function POST_request($page,$data){
	$data["opciones"] = (!empty($data["opciones"])?$data["opciones"]."," : "") . "webservice";
	$url = GEOSICOB_URL.$page;
	$options = ["http" =>
["method" => "POST",
"header" => "Content-Type: application/x-www-form-urlencoded",
"content" => http_build_query($data)
]
];
	return file_get_contents($url, NULL, stream_context_create($options));
}

function get_session_key($data){
	$data["opciones"] = "login,webservice"; 
	return GET_request("login.php",$data);
}

function upload_shapefile($data){
	$data["a_add"] = "A"; 
	return POST_request("shapefilesadd.php",$data);
}


/*
$session_key = get_session_key(["username"=>"admin","password"=>"12345"]);
var_dump($session_key);
exit;
*/
//$session_key = "c49u6d5r03hlrtfmdtc49gok32";
$filedata = file_get_contents($_FILES["uploadedfile"]["tmp_name"]);
$filename = basename($_FILES['uploadedfile']['name']);
//$data = ["file" => $_FILES["uploadedfile"], "x_filedata" => $filedata, "x_srid" => $_POST["proj"], "session_key" => $session_key, "opciones" => ""];
$data = ["file" => $_FILES["uploadedfile"], "x_filedata" => $filedata, "x_srid" => $_POST["proj"], "session_key" => $session_key, "opciones" => ""];
echo upload_shapefile($data);


/*
$options = ["http" =>
["method" => "POST",
"header" => "Content-Type: application/x-www-form-urlencoded",
"content" => http_build_query($data)
]
];
$page = file_get_contents($url, NULL, stream_context_create($options));
echo $page;
*/


?>