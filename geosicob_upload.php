<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php 
	header("Content-Type: application/json");
	header('Access-Control-Allow-Origin: *');
?>	
<?php include_once "ewcfg12.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql12.php") ?>
<?php include_once "phpfn12.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn12.php" ?>
<?php

		// Open connection
		if (!isset($conn)) $conn = ew_Connect();

		// User table object (usuario)
		if (!isset($UserTable)) {
			$UserTable = new cusuario();
			$UserTableConn = Conn($UserTable->DBID);
		}
		global $UserProfile, $Language, $Security;

		// Security
		$Security = new cAdvancedSecurity();
		if (!$Security->IsLoggedIn()) $Security->AutoLogin();
		if ($Security->IsLoggedIn()) $Security->TablePermission_Loading();
		$TableName = 'shapefiles';
		$ProjectID = CurrentProjectID();
		$Security->LoadCurrentUserLevel($ProjectID . $TableName);
		if ($Security->IsLoggedIn()) $Security->TablePermission_Loaded();
		if (!$Security->CanAdd()) {
			$Security->SaveLastUrl();
			echo '{"success":"0","msg":"'.$Language->Phrase("NoPermission").'","status":"error","IP":"'.ew_CurrentUserIP().'"}';
			exit();
		}
include_once('geosicob_api.php');

//var_dump(@$_POST);	
if(isset($_FILES['uploadedfile']) && $_FILES['uploadedfile']['error'] == 0 && isset($_POST["session_key"]) ){

//-----------------------------------//
//***Variables requeridas enviadas por POST***
//-----------------------------------//

	$session_key = $_POST["session_key"];
  $filedata = file_get_contents($_FILES["uploadedfile"]["tmp_name"]); //leyendo el contenido del archivo desde almac. temporal
  $data = array(
  	"x_filename" => $_FILES["uploadedfile"]["name"], //nombre del archivo en el cliente.
  	"file" => $_FILES["uploadedfile"], //Informacion referencial del archivo cargado.
  	"x_filedata" => $filedata, //Stream del contenido del archivo.
  	"x_srid" => $_POST["proj"], //Codigo de sistema de proyeccion del archivo shape 
  	"opciones" => "webservice,allow_polygon,fix_si,geojson", //opciones para el script del servidor:

	  	//webservice -> inica que es una solicitud por servicios WEB.
	  	//allow_polygon -> solo permitir geometrias de tipo poligono.
	  	//fix_si -> correguir intersecciones con sigo mismo.
	  	//intersect_tit -> intersectar con cobertura de predios titulados.

  	"a_add" => "A", //Variable que le indica al servidor que se realizará una 'Adición'
	  "session_key" => $session_key //Credencial de acceso, no se requiere si el cliente tiene permiso por IP.
  );
	unset($respuestajson);
 	$respuestajson = json_decode(POST_request('shapefilesadd.php',$data)); //llamada del servicio y captura de respuesta.
	$respuestajson->status = $respuestajson->success <> '1'? 'error' : 'success'; //ejemplo de como complementar datos a la respuesta.
  echo json_encode($respuestajson);

	//release_session_key($session_key);
} else {
	echo '{"success":"0","msg":"Archivo y/o clave de acceso invalidos.","status":"error"}';
}
