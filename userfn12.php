<?php

// Global user functions
// Page Loading event
function Page_Loading() {

	//echo "Page Loading"; 
	global $JSLibs;
	$JSLibs = isset($JSLibs)?$JSLibs:array();
	global $opciones;
	$_SESSION[CurrentPage()->PageObjName."_opciones"] = !empty($opciones)? $opciones:
		(!empty($_SESSION[CurrentPage()->PageObjName."_opciones"])?$_SESSION[CurrentPage()->PageObjName."_opciones"]:"") ;

	//Configurando opciones regionales para cada usuario
	global $DEFAULT_TIME_ZONE;
	$DEFAULT_TIME_ZONE = "America/La_Paz";
	if (function_exists("date_default_timezone_set"))
		date_default_timezone_set($DEFAULT_TIME_ZONE);

	//Reemplazando el valor de la variable opciones en la variable de session cuando se incluye "reset"
	if(strpos($opciones,"webservice") !== false && strpos($opciones,"reset") !== false){
		$_SESSION[CurrentPage()->PageObjName."_opciones"] = $opciones;
	}	

	//Eliminando la opcion webservice en la variable de session
	if(strpos($opciones,"webservice") === false){
		$_SESSION[CurrentPage()->PageObjName."_opciones"] = str_replace("webservice","",$_SESSION[CurrentPage()->PageObjName."_opciones"]);
	}

	//Iniciando buffer Web Service Response WSR
	if(!empty($_SESSION[CurrentPage()->PageObjName."_WSR"])){
		unset($_SESSION[CurrentPage()->PageObjName."_WSR"]); // = [];
	} else {
		$_SESSION[CurrentPage()->PageObjName."_WSR"] = [];
	}

	//Completando parametros especiales de PHPMaker en llamadas por webservice. 
	if(chkopt("webservice")){ //Si se ha llamado como servicio.
		global $_POST;
		switch(CurrentPageID()){
			case "add" :
				$_POST["t"] = CurrentPage()->TableName;
				$_POST["a_add"] = "A";
				break;
		}

		//setWSR($res ? $res : ('{"success":"0","msg":"'.getMsg(113).'"}'));
	}
} 

// Page Rendering event
function Page_Rendering() {

	//echo "Page Rendering";
 	if(chkopt("webservice")){ //Si se ha llamado como servicio.
		$err = CurrentPage()->getFailureMessage();
		if ($err != "") {
 			setWSR('"success":"0","error":"'.$err.'"');
 		}
 		if(CurrentPageID() == "list"){
 			$strJSON = toJSON(CurrentPage());
 			$_SESSION[CurrentPage()->PageObjName."_WSR"] = json_decode($strJSON,TRUE);
 			CurrentPage()->Page_Terminate();
 		}
 	}
}

// Page Unloaded event
function Page_Unloaded() {

	//echo "Page Unloaded";
	if (@$GLOBALS["_SERVER"]["REQUEST_METHOD"] == "OPTIONS"){
	 	header('Access-Control-Allow-Origin: *'); //Permitir cross-domain
	 	header("Content-Type: application/json");
		exit();
	}
	if(chkopt("webservice")){ 

	//verificando webservice.	
	 	if (ob_get_length()) ob_end_clean();// Clean output buffer
	 	header('Access-Control-Allow-Origin: *'); //Permitir cross-domain
	 	header("Content-Type: application/json");
	 	global $opciones;
	 	setWSR("opciones",$opciones);
		$err = CurrentPage()->getFailureMessage();
		if ($err != "") {
			CurrentPage()->ClearFailureMessage();
 			echo json_encode(json_decode('{"success":"0","error":"'.$err.'"}'));
 		}
	 	if(@$_SESSION[CurrentPage()->PageObjName."_WSR"])
	 		echo json_encode(@$_SESSION[CurrentPage()->PageObjName."_WSR"]); //$strJSON;
	 	exit();        
	}
}
error_reporting(E_ALL & ~E_NOTICE);
include_once "codmsg.php";

//-----------------------------------//
//***FIX: limpiando 'amp;' en los indices de $_POST 
// cuando se llama el script desde file_get_content
//-----------------------------------//

if(!empty($_POST))
while ( list($key, $value) = each($_POST) ){

	//var_dump($key, $value);
	$newkey = str_replace('amp;', '', $key);
	if ($newkey != $key) {
		global $_POST;
		$_POST[$newkey] = $_POST[$key];
		unset($_POST[$key]);
	}
}

//-----------------------------------//
//***Registrando variables globales***
//-----------------------------------//

	global $_SESSION;
	if(!isset($_SESSION["uploads_schema"])){               
		$_SESSION["uploads_schema"]  = "uploads";                     
	} 

	//Definiendo carpeta para los archivos de imagenes
	if (!defined('UPLOADS_DIR')) define("UPLOADS_DIR", dirname( realpath( __FILE__ ) ) . DIRECTORY_SEPARATOR."jupload".DIRECTORY_SEPARATOR."server".DIRECTORY_SEPARATOR."php".DIRECTORY_SEPARATOR."files".DIRECTORY_SEPARATOR);
	if (!defined('DATA_URL')) define("DATA_URL", get_full_url().(strpos(get_full_url(),"jupload")>-1? '' : '/jupload')."/server/php/");    

	//Definiendo la url del mapserver
	if (!defined("MSRV_URL")) define("MSRV_URL", ew_DomainUrl() );

//-----------------------------------//
//*** Realizando autologin por IP ***
//-----------------------------------//	

if(!IsLoggedIn()){
	global $conn;
	if(empty($conn))
		$conn = ew_Connect();
	$ip = ew_CurrentUserIP() == '::1'?'127.0.0.1':ew_CurrentUserIP();
	$row = ew_ExecuteRow("SELECT * from usuario WHERE autologinip = '".$ip."'");
	if($row){
		global $Security,$UserTable;
		$Security = new cAdvancedSecurity();
		if (!isset($UserTable)) {
			$UserTable = new cusuario();
			$UserTableConn = Conn($UserTable->DBID);
		}
		if($Security->ValidateUser($row["user"], $row["password"], FALSE, TRUE))
			$Security->setSessionUserID($row["idusuario"]);
	}
}

//--------------------------------------------------//
//***procesando el parámetro de opciones adicionales
//--------------------------------------------------//

	global $opciones,$_GET,$_POST;
	$opciones = isset($_GET["opciones"])?$_GET["opciones"]:( isset($_POST["opciones"])? $_POST["opciones"]:'');

	//$opciones .= !empty(@$_POST["x_acciones"])?(!empty($opciones)?',':'').implode(",", $_POST["x_acciones"]) :'';
//----------------------------------------------------------------------//
//***procesando respuestas especiales por webservice o solicitudes json
//----------------------------------------------------------------------//

if(strpos($opciones,"webservice")>-1 || strpos($opciones,"json")>-1 || strpos($opciones,"addjsn")>-1 ){
	global $Language;

	// Language object
	if (!isset($Language)) $Language = new cLanguage();
	if(strpos($opciones,"language")>-1){
			echo json_encode($Language);
			exit();
	}  
	if(!(strpos($opciones,"login")>-1) && !IsLoggedIn() && (@$_SESSION[EW_PROJECT_NAME . "_Username"] == "")){ //validando opciones de autologin

			//autologin con parámetro 'session_key'
		$sessionid = @$_POST["session_key"] .  @$_GET["session_key"];
		if($sessionid){
			if (session_id() != "") @session_destroy();
			session_id($sessionid);
			session_start();			
		}

		/*
		if( !IsLoggedIn()){  			
			$strJSON = "{\"success\":\"0\",\"login\":\"0\",\"msg\":\"".getMsg("100")."\",\"errorcod\":\"100\"}";            
			if(!chkopt("addjsn")){
				header('Access-Control-Allow-Origin: *'); //Permitir cross-domain
				header("Content-Type: application/json");
				echo $strJSON;
			}else{
				echo '<pre style="display:none" name="json">'.$strJSON.'</pre>';
			}
			if (session_id() != "") @session_destroy();
			exit();
		}
		*/
	}            
}

//-------------------------//
//***Funciones de geoprocesamiento
//-------------------------//
function rungeoprocess($id){
	$url = "http://localhost:3000/do/".$id;
	$res = ew_ClientUrl($url);
	if(!$res){ //No se ha encontrado activo el servicio de geoprocesamiento.
		$infoDb = Db();
		$db = pg_connect("host=".$infoDb["host"]." port=".$infoDb["port"]." dbname=".$infoDb["db"]." user=".$infoDb["user"]." password=".$infoDb["pass"]);
		$sql = "SELECT sicob_log_geoproceso('{\"idgeoproceso\":\"" . $id . "\", \"exec_point\":\"fin\",\"msg\":{\"error\":\"".getMsg(113)."\"}}'::json)";
		$r = pg_query($db,$sql);			
	}
	return $res;
}		

//-------------------------//
//***Funciones de soporte
//-------------------------//	
function chkopt($op){
	global $opciones;
	return strpos(strtoupper(@$_SESSION[CurrentPage()->PageObjName."_opciones"]),strtoupper($op)) > -1 || 
	strpos(strtoupper($opciones),strtoupper($op)) > -1;
}

function setWSR($key,$value=null){ //Web Service Response
	global $_SESSION;
	if (func_num_args() >= 2){
		$_SESSION[CurrentPage()->PageObjName."_WSR"][$key] = $value;
	} else {
		if(!is_null($obj = json_decode($key,TRUE))){
			foreach($obj as $okey=>$oval){
				 setWSR($okey,$oval);
			}
		}
	}
}		

function CargarPerfil(){
	global $conn;
	global $_SESSION;
	global $perfil_sys;
	if(!CurrentUserInfo("idperfil")) return;
	if(empty($conn))
		$conn = ew_Connect();
	$perfil_sys = ew_ExecuteRow("SELECT * FROM perfil WHERE idperfil=" . CurrentUserInfo("idperfil") );
	$_SESSION["comportamiento"] =  explode(",", $perfil_sys["comportamiento"]); 
	if($perfil_sys["interfaz"] == ""){
		$perfil_sys = ew_ExecuteRow("SELECT * FROM perfil WHERE idperfil=1");
	} 
	$_SESSION["perfil_sys"] = $perfil_sys; 
}

function addJSLib($libreria,$posicion="header"){
	global $EW_RELATIVE_PATH;
	switch ( strtoupper($libreria) ) {
		case "OPENLAYERS":
			if($posicion=="header"){
				ew_AddStylesheet("ol3/css/ol.css");
				echo "
<script type=\"text/javascript\"> 

	//******************************************//
	//**  Openlayers **//
	//******************************************//
	function loadOpenlayersScript(f) {
		var script = document.createElement('script');
		script.type = 'text/javascript';
		script.src = 'ol3/build/ol-debug.js';
		if (typeof f != 'undefined'){
			if(script.addEventListener) 
				script.addEventListener('load',f,false); 
			else 
				script.onreadystatechange=function(){ 
					if(script.readyState=='complete' || script.readyState=='loaded'){
						f();
					}
				} 
		}				  
		document.body.appendChild(script);
	}
</script>				
				";
			}
			break;    			
		case "AUTOSIZETEXTAREA":
			if($posicion=="footer"){
				echo "
				<script type=\"text/javascript\">
				jQuery(document).ready(function(){
					autosize($('textarea.autosize'));
				});
				</script>
				";
			}else{ 
				ew_AddClientScript("autosize/dist/autosize.min.js");  
			}
			break;
		case "SHEDULER":
			if($posicion=="header"){ 
				ew_AddStylesheet("metro/jquery.metro.css");
				ew_AddClientScript("metro/jquery.metro.js"); 
				echo "<script type=\"text/javascript\"> ";
				include "sheduler.php";
				echo "</script>";
			}
			break;    
		case "BOOTSTRAPCALENDAR":
			if($posicion=="footer"){ 
				ew_AddClientScript("bootstrapcalendar/underscore-min.js");  
				ew_AddClientScript("bootstrapcalendar/language/es-MX.js"); 
				ew_AddClientScript("bootstrapcalendar/jstz.min.js");  
				ew_AddClientScript("bootstrapcalendar/bootstrapcalendar.min.js");
			}else{ 
				ew_AddStylesheet("bootstrapcalendar/css/calendar.min.css");
			}
			break; 
		case "UIDATETIME":
			if($posicion=="footer"){ 
				ew_AddClientScript("uidatetime/ui-datetime.js"); 
			}else{ 
				global $customstyle;
				$customstyle .= ".btn-cell-selected,.btn-cell:hover{background-color:#0072C6;color:#fff;opacity:1}";
			}
			break;  
		case "JQUERYUI":       
			if($posicion=="header"){ ew_AddStylesheet("ui/themes/cupertino/jquery-ui.min.css");}
			else{ew_AddClientScript("ui/jquery-ui-1.10.0.custom.min.js",array("requiere" => $EW_RELATIVE_PATH."jquery/jquery-1.11.3.min.js"));}                            
			break;
		case "UIDIALOG":
			if($posicion=="header"){ew_AddStylesheet("uidialog/jquery.dialogr.css");}
			else{ew_AddClientScript("uidialog/ui.dialogr.min.js",array("requiere" => "jquery-ui-1.10.0.custom.min.js"));} 
			break;
		case "LIMESURVEY": 
			if($posicion=="footer")
				ew_AddClientScript("limesurvey/scripts/survey_runtime.js");
			break; 
		case "UIFORM": 
			if($posicion=="footer")
				ew_AddClientScript("ui/ui.main.js");
			break; 
		case "RESIZEPANEL": 
			if($posicion=="footer")
				ew_AddClientScript("resizepanel/resizepanel.jQuery.js");
			break;            
		case "UICOMBOBOX":  
			if($posicion=="footer"){
			ew_AddClientScript("ui/ui.combobox.js"); 
			echo "
<script type=\"text/javascript\"> 
	 jQuery(document).ready(function(){ 
		$.ui.autocomplete.prototype.options.autoSelect = true;
		$( '.ui-autocomplete-input' ).live( 'blur', function( event ) {
			var autocomplete = $( this ).data( 'ui-autocomplete' );
			if ( !autocomplete.options.autoSelect || autocomplete.selectedItem ) { return; }
			var matcher = new RegExp( $.ui.autocomplete.escapeRegex( $(this).val() ) , 'i' );
			autocomplete.widget().children( '.ui-menu-item' ).each(function() {
				var item = $( this ).data( 'ui-autocomplete-item' );
				if ( matcher.test( item.label || item.value || item ) ) {
					autocomplete.selectedItem = item;
					return false;
				}
			});
			if ( autocomplete.selectedItem ) {
				autocomplete._trigger( 'select', event, { item: autocomplete.selectedItem } );
				$(this).val(autocomplete.selectedItem.value);
			}
		});
		$('select:not([readonly=\"readonly\"]):not(.unstyled)').combobox().each(function(){ $(this).next().css('display','inline-block')});
	 });
</script>
		";  
			}
			break;  
		case "TIMEPICKER":             
			if($posicion=="header"){ew_AddStylesheet("timepicker/jquery-ui-timepicker-addon.css");}
			else{
				ew_AddClientScript("timepicker/jquery-ui-timepicker-addon.min.js");
				ew_AddClientScript("timepicker/jquery-ui-sliderAccess.js");
				ew_AddClientScript("timepicker/localization/jquery-ui-timepicker-es.js");             
				echo "
	   <script type=\"text/javascript\">
	   jQuery(document).ready(function(){
		$.datepicker.regional['es'] = {
			closeText: 'Cerrar',
			prevText: '&#x3C;Ant',
			nextText: 'Sig&#x3E;',
			currentText: 'Hoy',
			monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio',
			'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
			monthNamesShort: ['Ene','Feb','Mar','Abr','May','Jun',         
			'Jul','Ago','Sep','Oct','Nov','Dic'],
			dayNames: ['Domingo','Lunes','Martes','Mi\u00e9rcoles','Jueves','Viernes','S\u00e1bado'],
			dayNamesShort: ['Dom','Lun','Mar','Mi\u00e9','Juv','Vie','S\u00e1b'],
			dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','S\u00e1'],
			weekHeader: 'Sm',
			dateFormat: 'dd/mm/yy',
			firstDay: 1,
			isRTL: false,
			showMonthAfterYear: false,                                                    
			yearSuffix: ''};
		$.datepicker.setDefaults($.datepicker.regional['es']);
		   $('.datetimecontrol').datetimepicker({timeFormat: 'HH:mm:ss',
												 AddSliderAccess:true,
												 sliderAccessArgs:{touchonly:false}});
		   $('.datecontrol').datepicker();
		   $('.timecontrol').timepicker({stepMinute: 5});
	   });
	   </script> 
			";
			}
			break; 
		case "DOSIFICADOR": 
			if($posicion=="footer")
				ew_AddClientScript("dosificador/dosificador.jquery.js");  
			break;  
		case "KEYPAD":
			if($posicion=="header"){
				ew_AddStylesheet("keypad/jquery.keypad.css");
			} else {
				ew_AddClientScript("keypad/jquery.plugin.min.js"); 
				ew_AddClientScript("keypad/jquery.keypad.min.js");
				ew_AddClientScript("keypad/jquery.keypad-es.js"); 
				echo "
	<script type=\"text/javascript\">
		jQuery(document).ready(function(){
			$.keypad.setDefaults({
				keypadClass : 'dropdown-menu',
				beforeShow : function(div, inst){
					div
					.find('.keypad-key,.keypad-space').css({'width':'50px','display':'inline-block'}).end()
					.find('.keypad-key').addClass('btn btn-large').end()
					.find('.keypad-special:not(.keypad-close)').addClass('btn btn-info').end()
					.find('.keypad-close').addClass('btn btn-warning');
				}
			})
		});
	</script> 
				";
			}
			break; 
		case "JUPLOAD":       
			if($posicion=="footer"){    
			include_once "jupload/fileupload.php";
			ew_AddClientScript("jupload/js/tmpl.min.js");
			ew_AddClientScript("jupload/js/load-image.min.js");  
			ew_AddClientScript("jupload/js/jquery.iframe-transport.js");
			ew_AddClientScript("jupload/js/jquery.fileupload.js");
			ew_AddClientScript("jupload/js/jquery.fileupload-process.js");
			ew_AddClientScript("jupload/js/jquery.fileupload-image.js"); 
			ew_AddClientScript("jupload/js/jquery.fileupload-audio.js");
			ew_AddClientScript("jupload/js/jquery.fileupload-video.js");
			ew_AddClientScript("jupload/js/jquery.fileupload-validate.js");
			ew_AddClientScript("jupload/js/jquery.fileupload-ui.js");  
			ew_AddClientScript("blockUI/jquery.blockUI.js");
			echo "
	<script type=\"text/javascript\">             
	jQuery(document).ready(function(){ 
		$.getScript( 'jupload/js/jquery.fileupload-jquery-ui.js',function(){ 
			$.getScript( 'jupload/js/main.js', function(){ 
				$('.uploadcontrol').each(function(){
					fileupload_inicializar({'editor':$(this),emptyimage:'".ew_DomainUrl().(strrpos(ew_CurrentUrl(),"/")?substr(ew_CurrentUrl(), 0, strrpos(ew_CurrentUrl(),"/")) :"")."/jupload/img/pictures.png'});
				});    
				resizeIFRM();            
			}); 
		});    
	});            
	</script>    
			";            
			}                     
			break;                        
	}                             
}

function addCSSLib($libreria){
	switch ( strtoupper($libreria) ) { 
		case "JUPLOAD":
			ew_AddStylesheet("jupload/css/demo.css");
			echo "<!--[if lte IE 8]>";
			ew_AddStylesheet("jupload/css/demo-ie8.css");
			echo "<![endif]-->"; 
			ew_AddStylesheet("jupload/css/jquery.fileupload.css");
			ew_AddStylesheet("jupload/css/jquery.fileupload-ui.css");
			echo "<noscript>";  
			ew_AddStylesheet("jupload/css/jquery.fileupload-noscript.css");
			echo "</noscript>";  
			echo "<noscript>";  
			ew_AddStylesheet("jupload/css/jquery.fileupload-ui-noscript.css");
			echo "</noscript>";
			break;
	}
}

function comportamiento($id){
	global $_SESSION;
	return (isset($_SESSION["comportamiento"]) && in_array($id,$_SESSION["comportamiento"]));
}

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

function toJSON($page){
	global $Security;
	$utf8 = (strtolower(EW_CHARSET) == "utf-8");
  if($page->PageID == 'list'){
		$bSelectLimit = $page->UseSelectLimit;

		// Load recordset
		if ($bSelectLimit) {
			$page->TotalRecs = $page->SelectRecordCount();
		} else {
			if (!$page->Recordset)
				$page->Recordset = $page->LoadRecordset();
			$rs = &$page->Recordset;
			if ($rs)
				$page->TotalRecs = $rs->RecordCount();
		}
		$page->StartRec = 1;
	  { // Export one $page only
		  $page->SetUpStartRec(); // Set up start record position

		  // Set the last record to display
		  if ($page->DisplayRecs <= 0) {
			  $page->StopRec = $this->TotalRecs;
		  } else {
			  $page->StopRec = $page->StartRec + $page->DisplayRecs - 1;
		  }
	  }
	  if ($bSelectLimit){
		  $sSql = !empty($page->customSQL)? $page->customSQL : $page->SelectSQL();
		  $offset = $page->StartRec-1;
		  $rowcnt = $page->DisplayRecs <= 0 ? $page->TotalRecs : $page->DisplayRecs;
		  $sSql .= " LIMIT $rowcnt OFFSET $offset";
	global $ADODB_FETCH_MODE;
	$auxADODB_FETCH_MODE = $ADODB_FETCH_MODE;
	$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
	error_reporting(~E_STRICT);
		  $rs = ew_LoadRecordset($sSql);
	$ADODB_FETCH_MODE = $auxADODB_FETCH_MODE;	 		  
	  }    
	  if (!$rs) {
		  header("Content-Type:"); // Remove header
		  header("Content-Disposition:");
		  $page->ShowMessage();
		  return;
	  }            
	  $Pager = new cPrevNextPager($page->StartRec, $page->DisplayRecs, $page->TotalRecs); 
	  $res = $rs->GetRows();	  
	  $rs->Close();
	  $Allowed = '{"CanView":"'.$Security->CanView().'","CanEdit":"'.$Security->CanEdit().'","CanDelete":"'.$Security->CanDelete().'","CanAdd":"'.$Security->CanAdd().'","CanList":"'.$Security->CanList().'","CanAdmin":"'.$Security->CanAdmin().'","CanSearch":"'.$Security->CanSearch().'","CanReport":"'.$Security->CanReport().'"}';
	  return "{\"TableVar\":\"".$page->TableVar."\",\"Security\":".$Allowed.",\"PageUrl\":\"".$page->PageUrl()."\",\"pager\":".json_encode($Pager).", \"rows\": ".json_encode($res)."}" ;
  }
  if($page->PageID == 'edit'){
		$sFilter = $page->KeyFilter();

		// Call Row Selecting event
		$page->Row_Selecting($sFilter);

		// Load SQL based on filter
		$page->CurrentFilter = $sFilter;
		$sSql = $page->SQL();
		$res = FALSE;
	global $ADODB_FETCH_MODE;
	$auxADODB_FETCH_MODE = $ADODB_FETCH_MODE;
	$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
	error_reporting(~E_STRICT);		
		$rs = ew_LoadRecordset($sSql);
	$ADODB_FETCH_MODE = $auxADODB_FETCH_MODE;	 		
		if ($rs && !$rs->EOF) {
			$res = array($rs->fields);
			$rs->Close();
			return json_encode($res[0]);
		}     
  }
}

function SqlSelectList($page){
		$sFilter = $page->getSessionWhere();
		ew_AddFilter($sFilter, $page->CurrentFilter);
		$sFilter = $page->ApplyUserIDFilters($sFilter);
		$sSort = $page->getSessionOrderByList();
		return ew_BuildSelectSql($page->SqlSelectList(), $page->SqlWhere(), $page->SqlGroupBy(), 
			$page->SqlHaving(), $page->SqlOrderBy(), $sFilter, $sSort);
}

function CalendarView($date="",$type="cal_display_i"){
	if(empty($shortmonths)){
		global $shortmonths,$shortdays,$Language;                                
		$shortmonths = explode(",",$Language->Phrase("shortmonths")); 
		$shortdays = explode(",",$Language->Phrase("shortdays"));
	}
	return "<div class=\"".$type."\" >   
			<span class=\"cal_m\">".$shortmonths[(int) strftime( "%m", strtotime($date) ) - 1]."</span>
			<span class=\"cal_y\">".strftime( "%Y", strtotime($date) )."</span>
			<span class=\"cal_d\">".strftime( "%d", strtotime($date) )."</span>
			<span class=\"cal_w\">".$shortdays[((int) strftime( "%w", strtotime($date))>0?(int) strftime( "%w", strtotime($date) ):7) - 1]."</span>
			<span class=\"cal_t\">".strftime( "%H:%M:%S", strtotime($date) )."</span>
			</div>";	
}
?>
