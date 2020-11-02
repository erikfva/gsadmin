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
} 

// Page Rendering event
function Page_Rendering() {

	//echo "Page Rendering";
}

// Page Unloaded event
function Page_Unloaded() {

	//echo "Page Unloaded";
}
if (IsLoggedIn()){ 
	global $conn;
	if(empty($conn))
		$conn = ew_Connect();
	global $perfil_sys;
			$perfil_sys = ew_ExecuteRow("SELECT * FROM perfil WHERE idperfil='" . CurrentUserInfo("perfil") . "'");
			$_SESSION["comportamiento"] =  explode(",", $perfil_sys["comportamiento"]); 
			if($perfil_sys["interfaz"] == ""){
				$perfil_sys = ew_ExecuteRow("SELECT * FROM perfil WHERE idperfil='general'");
			} 
			$_SESSION["perfil_sys"] = $perfil_sys; 
}            

//Definiendo carpeta para los archivos de imagenes
 if (!defined('UPLOADS_DIR')) define("UPLOADS_DIR", dirname( realpath( __FILE__ ) ) . DIRECTORY_SEPARATOR."jupload".DIRECTORY_SEPARATOR."server".DIRECTORY_SEPARATOR."php".DIRECTORY_SEPARATOR."files".DIRECTORY_SEPARATOR, TRUE);
 if (!defined('DATA_URL')) define("DATA_URL", get_full_url().(strpos(get_full_url(),"jupload")>-1? '' : '/jupload')."/server/php/", TRUE);    

	//procesando el parámetro de opciones adicionales
	global $opciones,$_GET,$_POST;
	$opciones = isset($_GET["opciones"])?$_GET["opciones"]:( isset($_POST["opciones"])? $_POST["opciones"]:'');
	if((strpos($opciones,"json")>-1 || strpos($opciones,"addjsn")>-1 )){
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		if(strpos($opciones,"language")>-1){
				echo json_encode($Language);
				exit();
		}        
		if(!(strpos($opciones,"login")>-1) && !IsLoggedIn()){
				$strJSON = "{\"login\":\"0\",\"msg\":\"".$Language->Phrase("NoPermission").". ".$Language->Phrase("enteruid")." ".$Language->Phrase("and")." ".$Language->Phrase("password")."\"}";            
				echo strpos($opciones,"addjsn")>-1?'<pre style="display:none" name="json">'.$strJSON.'</pre>':$strJSON;
				exit();
		}            
	}      

function addJSLib($libreria,$posicion="header"){
	global $EW_RELATIVE_PATH;
	switch ( strtoupper($libreria) ) {
		case "OPENLAYERS":
			if($posicion=="footer"){
				echo "
<script type=\"text/javascript\"> 

	//******************************************//
	//**  Openlayers **//
	//******************************************//
	function loadOpenlayersScript() {
		var script = document.createElement('script');
		script.type = 'text/javascript';
		script.src = 'ol3/build/ol-debug.js';
		if(script.addEventListener) 
			script.addEventListener('load',onloadOL,false); 
		else 
			script.onreadystatechange=function(){ 
				if(script.readyState=='complete' || script.readyState=='loaded') onloadOL(); 
			} 			  
		document.body.appendChild(script);
	}
	window.onload = loadOpenlayersScript;

	function onloadOL() {
		if(typeof afterLoadOL != 'undefined') afterLoadOL();
	}
</script>				
				";
			}else{
				ew_AddStylesheet("ol3/css/ol.css");
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
	  $bSelectLimit = EW_SELECT_LIMIT;

	  // Load recordset
	  if ($bSelectLimit) {
		  $page->TotalRecs = $page->SelectRecordCount();
	  } else {
		  if ($rs = $page->LoadRecordset())
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
