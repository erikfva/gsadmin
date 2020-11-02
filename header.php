<?php

// Compatibility with PHP Report Maker
if (!isset($Language)) {
	include_once "ewcfg12.php";
	include_once "ewshared12.php";
	$Language = new cLanguage();
}

// Responsive layout
if (ew_IsResponsiveLayout()) {
	$gsHeaderRowClass = " ewHeaderRow";
	$gsMenuColumnClass = " ewMenuColumn";
	$gsSiteTitleClass = " ewSiteTitle";
} else {
	$gsHeaderRowClass = "ewHeaderRow";
	$gsMenuColumnClass = "ewMenuColumn";
	$gsSiteTitleClass = "ewSiteTitle";
}
?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo $Language->ProjectPhrase("BodyTitle") ?></title>
<meta charset="utf-8">
<?php if (@$gsExport == "" || @$gsExport == "print") { ?>
<link rel="stylesheet" type="text/css" href="<?php echo $EW_RELATIVE_PATH ?>bootstrap3/css/<?php echo ew_CssFile("bootstrap.css") ?>">
<!-- Optional theme -->
<link rel="stylesheet" type="text/css" href="<?php echo $EW_RELATIVE_PATH ?>bootstrap3/css/<?php echo ew_CssFile("bootstrap-theme.css") ?>">
<?php } ?>
<?php if (@$gsExport == "" || @$gsExport == "print") { ?>
<link rel="stylesheet" type="text/css" href="<?php echo $EW_RELATIVE_PATH ?>phpcss/jquery.fileupload.css">
<link rel="stylesheet" type="text/css" href="<?php echo $EW_RELATIVE_PATH ?>phpcss/jquery.fileupload-ui.css">
<link rel="stylesheet" type="text/css" href="<?php echo $EW_RELATIVE_PATH ?>colorbox/colorbox.css">
<?php } ?>
<?php if (@$gsExport == "" || @$gsExport == "print") { ?>
<?php if (ew_IsResponsiveLayout()) { ?>
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php } ?>
<link rel="stylesheet" type="text/css" href="<?php echo $EW_RELATIVE_PATH ?><?php echo ew_CssFile(EW_PROJECT_STYLESHEET_FILENAME) ?>">
<?php if (@$gsCustomExport == "pdf" && EW_PDF_STYLESHEET_FILENAME <> "") { ?>
<link rel="stylesheet" type="text/css" href="<?php echo $EW_RELATIVE_PATH ?><?php echo EW_PDF_STYLESHEET_FILENAME ?>">
<?php } ?>
<script type="text/javascript" src="<?php echo $EW_RELATIVE_PATH ?>jquery/jquery-1.11.3.min.js"></script>
<script type="text/javascript" src="<?php echo $EW_RELATIVE_PATH ?>jquery/jquery.storageapi.min.js"></script>
<script type="text/javascript" src="<?php echo $EW_RELATIVE_PATH ?>jquery/pStrength.jquery.js"></script>
<script type="text/javascript" src="<?php echo $EW_RELATIVE_PATH ?>jquery/pGenerator.jquery.js"></script>
<?php } ?>
<?php if (@$gsExport == "" || @$gsExport == "print") { ?>
<script type="text/javascript" src="<?php echo $EW_RELATIVE_PATH ?>bootstrap3/js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo $EW_RELATIVE_PATH ?>phpjs/typeahead.bundle.min.js"></script>
<script type="text/javascript" src="<?php echo $EW_RELATIVE_PATH ?>jqueryfileupload/jquery.ui.widget.js"></script>
<script type="text/javascript" src="<?php echo $EW_RELATIVE_PATH ?>jqueryfileupload/load-image.all.min.js"></script>
<script type="text/javascript" src="<?php echo $EW_RELATIVE_PATH ?>jqueryfileupload/jqueryfileupload.min.js"></script>
<script type="text/javascript" src="<?php echo $EW_RELATIVE_PATH ?>colorbox/jquery.colorbox-min.js"></script>
<script type="text/javascript" src="<?php echo $EW_RELATIVE_PATH ?>phpjs/mobile-detect.min.js"></script>
<script type="text/javascript" src="<?php echo $EW_RELATIVE_PATH ?>phpjs/moment.min.js"></script>
<script type="text/javascript" src="<?php echo $EW_RELATIVE_PATH ?>ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="<?php echo $EW_RELATIVE_PATH ?>phpjs/eweditor.js"></script>
<script type="text/javascript">
var EW_LANGUAGE_ID = "<?php echo $gsLanguage ?>";
var EW_DATE_SEPARATOR = "/"; // Default date separator
var EW_DEFAULT_DATE_FORMAT = "<?php echo EW_DEFAULT_DATE_FORMAT ?>"; // Default date format
var EW_DECIMAL_POINT = "<?php echo $DEFAULT_DECIMAL_POINT ?>";
var EW_THOUSANDS_SEP = "<?php echo $DEFAULT_THOUSANDS_SEP ?>";
var EW_MIN_PASSWORD_STRENGTH = 15;
var EW_GENERATE_PASSWORD_LENGTH = 16;
var EW_GENERATE_PASSWORD_UPPERCASE = true;
var EW_GENERATE_PASSWORD_LOWERCASE = true;
var EW_GENERATE_PASSWORD_NUMBER = true;
var EW_GENERATE_PASSWORD_SPECIALCHARS = false;
var EW_SESSION_TIMEOUT = <?php echo (EW_SESSION_TIMEOUT > 0) ? ew_SessionTimeoutTime() : 0 ?>; // Session timeout time (seconds)
var EW_SESSION_TIMEOUT_COUNTDOWN = <?php echo EW_SESSION_TIMEOUT_COUNTDOWN ?>; // Count down time to session timeout (seconds)
var EW_SESSION_KEEP_ALIVE_INTERVAL = <?php echo EW_SESSION_KEEP_ALIVE_INTERVAL ?>; // Keep alive interval (seconds)
var EW_RELATIVE_PATH = "<?php echo $EW_RELATIVE_PATH ?>"; // Relative path
var EW_SESSION_URL = EW_RELATIVE_PATH + "ewsession12.php"; // Session URL
var EW_IS_LOGGEDIN = <?php echo IsLoggedIn() ? "true" : "false" ?>; // Is logged in
var EW_IS_AUTOLOGIN = <?php echo IsAutoLogin() ? "true" : "false" ?>; // Is logged in with option "Auto login until I logout explicitly"
var EW_LOGOUT_URL = EW_RELATIVE_PATH + "logout.php"; // Logout URL
var EW_LOOKUP_FILE_NAME = "ewlookup12.php"; // Lookup file name
var EW_AUTO_SUGGEST_MAX_ENTRIES = <?php echo EW_AUTO_SUGGEST_MAX_ENTRIES ?>; // Auto-Suggest max entries
var EW_DISABLE_BUTTON_ON_SUBMIT = true;
var EW_IMAGE_FOLDER = "phpimages/"; // Image folder
var EW_UPLOAD_URL = "<?php echo EW_UPLOAD_URL ?>"; // Upload URL
var EW_UPLOAD_THUMBNAIL_WIDTH = <?php echo EW_UPLOAD_THUMBNAIL_WIDTH ?>; // Upload thumbnail width
var EW_UPLOAD_THUMBNAIL_HEIGHT = <?php echo EW_UPLOAD_THUMBNAIL_HEIGHT ?>; // Upload thumbnail height
var EW_MULTIPLE_UPLOAD_SEPARATOR = "<?php echo EW_MULTIPLE_UPLOAD_SEPARATOR ?>"; // Upload multiple separator
var EW_USE_COLORBOX = <?php echo (EW_USE_COLORBOX) ? "true" : "false" ?>;
var EW_USE_JAVASCRIPT_MESSAGE = false;
var EW_MOBILE_DETECT = new MobileDetect(window.navigator.userAgent);
var EW_IS_MOBILE = EW_MOBILE_DETECT.mobile() ? true : false;
var EW_PROJECT_STYLESHEET_FILENAME = "<?php echo EW_PROJECT_STYLESHEET_FILENAME ?>"; // Project style sheet
var EW_PDF_STYLESHEET_FILENAME = "<?php echo EW_PDF_STYLESHEET_FILENAME ?>"; // Pdf style sheet
var EW_TOKEN = "<?php echo @$gsToken ?>";
var EW_CSS_FLIP = <?php echo (EW_CSS_FLIP) ? "true" : "false" ?>;
var EW_CONFIRM_CANCEL = true;
</script>
<?php } ?>
<?php if (@$gsExport == "" || @$gsExport == "print") { ?>
<script type="text/javascript" src="<?php echo $EW_RELATIVE_PATH ?>phpjs/jsrender.min.js"></script>
<script type="text/javascript" src="<?php echo $EW_RELATIVE_PATH ?>phpjs/ewp12.js"></script>
<?php } ?>
<?php if (@$gsExport == "" || @$gsExport == "print") { ?>
<script type="text/javascript">
var ewVar = <?php echo json_encode($EW_CLIENT_VAR); ?>;
<?php echo $Language->ToJSON() ?>
</script>
<?php

//Page Head event 

/*
<!-- Don't scale the viewport in either portrait or landscape mode.
	 Note that this means apps will be reflowed when rotated (like iPad).
	 If we wanted to maintain position we could remove 'maximum-scale' so
	 that we'd zoom out in portrait mode, but then there would be a bunch
	 of unusable space at the bottom.
-->
*/
echo "<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1, user-scalable=no' name='viewport'>";

//Procesando el parametro de opciones 
$opciones = $_SESSION[CurrentPage()->PageObjName."_opciones"]; 

//forzando recarga de userfnxx.js
ew_AddClientScript("phpjs/userfn12.js?ver=".rand(1,10000));
global $Breadcrumb,$Language;
if(isset($Breadcrumb->Links)){

	//Quitando el link de "inicio" del path 
	if($Breadcrumb->Links[0][0]=="home"){array_splice($Breadcrumb->Links, 0, 1);}

	//Quitando links que no corresponden a la tabla maestra
	$masterTbl = isset(CurrentPage()->TableVar) && isset($_SESSION[EW_PROJECT_NAME . "_" . CurrentPage()->TableVar . "_" . EW_TABLE_MASTER_TABLE])?$_SESSION[EW_PROJECT_NAME . "_" . CurrentPage()->TableVar . "_" . EW_TABLE_MASTER_TABLE]:"";
	if(!empty($masterTbl)){
		if( isset($_SESSION[CurrentPage()->TableVar."_list_opciones"]) && (strpos($_SESSION[CurrentPage()->TableVar."_list_opciones"],"hidebkmainpage")>-1))
			$masterTbl = CurrentPage()->TableVar;
		while(count($Breadcrumb->Links)>0 && $Breadcrumb->Links[0][1]!=$masterTbl){
			array_splice($Breadcrumb->Links, 0, 1);
		}

		//PHPMaker asume el ultimo elemento del array Links como el de la pagina actual y no coloca el url y lo pone como 'active'
		//Restaurando el link de la pagina actual

		if(count($Breadcrumb->Links)==0){
			array_push( $Breadcrumb->Links, array(CurrentPage()->PageID,CurrentPage()->TableVar,"","",CurrentPage()->TableVar,false ) );
		}
	}

	//En algunos casos es necesario adicionar el link para volver atras
	if( !strpos($opciones,"hidebkmainpage") && isset(CurrentPage()->TableVar) && !empty($_SESSION[EW_PROJECT_NAME . "_" . CurrentPage()->TableVar . "_" . EW_TABLE_MASTER_TABLE]) && count($Breadcrumb->Links) == 1 ) {                   
		$masterTbl = $_SESSION[EW_PROJECT_NAME . "_" . CurrentPage()->TableVar . "_" . EW_TABLE_MASTER_TABLE];    
		$PageLnk = $_SESSION[EW_PROJECT_NAME ."_".$_SESSION[EW_PROJECT_NAME . "_" . CurrentPage()->TableVar . "_" . EW_TABLE_MASTER_TABLE]."_exportreturn"];
		array_splice( $Breadcrumb->Links, count($Breadcrumb->Links)-1, 0, array(array(
		$masterTbl,
		$masterTbl , 
		ew_DomainUrl().$PageLnk,
		"", 
		$masterTbl,
		false) ) );
	}

	//Agregando los botones de accion en la misma fila del path 
	if(isset(CurrentPage()->PageID) && (CurrentPage()->PageID == "edit" || CurrentPage()->PageID == "add" ) ){
		global $customstyle;     
		$PageCaption = $Language->Phrase("EditBtn");
		array_splice( $Breadcrumb->Links, count($Breadcrumb->Links)-1, 0, array(array("editbtn","SaveBtn" , "javascript:$('#btnAction').trigger('click');\" class=\"btn btn-lg btn-primary", "", CurrentPage()->TableVar, false) ) );
		$customstyle.= "#btnAction,#btnCancel{display:none !important}";
	}                
}
if (IsLoggedIn()){ 
	global $JSLibs;
	$JSLibs = array_unique($JSLibs); 
	foreach ($JSLibs as $JSLibname) {
	   addJSLib($JSLibname);
	}
	if (empty(CurrentPage()->Export) && !ew_IsMobile()) {

		//Mensaje deslizable cargando...
		ew_AddStylesheet("loading/css/loading.css");           
		echo '
			<div class="pageload-overlay">
				<!-- the component -->
				<ul class="bokeh">
					<li></li>
					<li></li>
					<li></li>
					<li></li>
				</ul>
			</div>
		';
	}
	global $customstyle;
	if(strpos($opciones,"hidetitle")>-1){
		$customstyle .= ' .ewMasterTableTitle, .ewTableTitle {display:none !important}';
	}
	if(strpos($opciones,"hidemaster")>-1){
		$customstyle .= ' table[id*="master"] {display:none !important}';
	}
	if(strpos($opciones,"hidebkmainpage")>-1){
		$customstyle .= ' a[href="' . $Page->getCurrentMasterTable() . 'list.php"] {display:none !important}';
	} 
	if(strpos($opciones,"hidebreadcrumb")>-1){
		$customstyle .= ' .breadcrumb{display:none !important}';
	}
}                         
global $customstyle; 

//Para mostrar solo la tabla de datos sin encabezado y pie de pagina
global $gbSkipHeaderFooter;
$gbSkipHeaderFooter = TRUE;

//Para ocultar el control de filtros en el listado
if(isset(CurrentPage()->PageID) && CurrentPage()->PageID == "list") CurrentPage()->FilterOptions->HideAllOptions();
if($Page && $Page->PageObjName == "main_php"){
	$gbSkipHeaderFooter = FALSE;

	//ocultando el encabezado y columna de menu
	$customstyle .=' #ewHeaderRow,#ewMenuColumn,.ewToolbar{display:none}';
	$customstyle .=' .ewContentColumn{padding:0px}';
}
if($customstyle){                                
		echo "
		<style type=\"text/css\">
		".$customstyle."
		</style>
		";
}
?>
<script type="text/javascript" src="<?php echo $EW_RELATIVE_PATH ?>phpjs/userfn12.js"></script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
//Configurando el CKEditor

if (typeof CKEDITOR != "undefined"){

	//CKEDITOR.config.extraPlugins = 'richcombo,font,widget,filetools,lineutils,notificationaggregator,notification,toolbar,uploadwidget,uploadimage';
	//CKEDITOR.config.uploadUrl = '/uploader/upload.php';

	CKEDITOR.config.extraPlugins = 'richcombo,font,justify';
	CKEDITOR.config.allowedContent = true;
}
<?php    
	global $_SESSION;
	$PageName = basename(ew_CurrentPage(), ".php");
	if(isset($_SESSION[$PageName."_run_script"]) && $_SESSION[$PageName."_run_script"]!=""){
		echo $_SESSION[$PageName."_run_script"];
		$_SESSION[$PageName."_run_script"] = "";
	} 
?>                                                                             
  if(!top.jQuery.fn.block){      
	var po = top.document.createElement('script'); po.type = 'text/javascript'; po.async = true;
	po.src = 'blockUI/jquery.blockUI.js';
	var s = top.document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
  }                                                                       
</script>
<?php } ?>
<meta name="generator" content="PHPMaker v12.0.5">
</head>
<body>
<?php if (@!$gbSkipHeaderFooter) { ?>
<?php if (@$gsExport == "") { ?>
<div class="ewLayout">
	<!-- header (begin) --><!-- ** Note: Only licensed users are allowed to change the logo ** -->
	<div id="ewHeaderRow" class="<?php echo $gsHeaderRowClass ?>"><img src="<?php echo $EW_RELATIVE_PATH ?>phpimages/phpmkrlogo12.png" alt=""></div>
<?php if (ew_IsResponsiveLayout()) { ?>
<nav id="ewMobileMenu" role="navigation" class="navbar navbar-default  hidden-print">
	<div class="container-fluid"><!-- Brand and toggle get grouped for better mobile display -->
		<div class="navbar-header">
			<button data-target="#ewMenu" data-toggle="collapse" class="navbar-toggle" type="button">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="<?php echo (EW_MENUBAR_BRAND_HYPERLINK <> "") ? EW_MENUBAR_BRAND_HYPERLINK : "#" ?>"><?php echo (EW_MENUBAR_BRAND <> "") ? EW_MENUBAR_BRAND : $Language->ProjectPhrase("BodyTitle") ?></a>
		</div>
		<div id="ewMenu" class="collapse navbar-collapse" style="height: auto;"><!-- Begin Main Menu -->
<?php
	$RootMenu = new cMenu("MobileMenu");
	$RootMenu->MenuBarClassName = "";
	$RootMenu->MenuClassName = "nav navbar-nav";
	$RootMenu->SubMenuClassName = "dropdown-menu";
	$RootMenu->SubMenuDropdownImage = "";
	$RootMenu->SubMenuDropdownIconClassName = "icon-arrow-down";
	$RootMenu->MenuDividerClassName = "divider";
	$RootMenu->MenuItemClassName = "dropdown";
	$RootMenu->SubMenuItemClassName = "dropdown";
	$RootMenu->MenuActiveItemClassName = "active";
	$RootMenu->SubMenuActiveItemClassName = "active";
	$RootMenu->MenuRootGroupTitleAsSubMenu = TRUE;
	$RootMenu->MenuLinkDropdownClass = "ewDropdown";
	$RootMenu->MenuLinkClassName = "icon-arrow-right";
?>
<?php include_once "ewmobilemenu.php" ?>
		</div><!-- /.navbar-collapse -->
	</div><!-- /.container-fluid -->
</nav>
<?php } ?>
	<!-- header (end) -->
	<!-- content (begin) -->
	<div id="ewContentTable" class="ewContentTable">
		<div id="ewContentRow">
			<div id="ewMenuColumn" class="<?php echo $gsMenuColumnClass ?>">
				<!-- left column (begin) -->
				<div class="ewMenu">
<?php include_once "ewmenu.php" ?>
				</div>
				<!-- left column (end) -->
			</div>
			<div id="ewContentColumn" class="ewContentColumn">
				<!-- right column (begin) -->
				<h4 class="<?php echo $gsSiteTitleClass ?>"><?php echo $Language->ProjectPhrase("BodyTitle") ?></h4>
<?php } ?>
<?php } ?>
