<?php
if (session_id() == "") {
    session_start();
}
// Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg12.php"?>
<?php $EW_ROOT_RELATIVE_PATH = "";?>
<?php include_once EW_USE_ADODB ? "adodb5/adodb.inc.php" : "ewmysql12.php"?>
<?php include_once "phpfn12.php"?>
<?php include_once "usuarioinfo.php"?>
<?php include_once "userfn12.php"?>
<?php

//
// Page class
//

$main_php = null; // Initialize page object first

class cmain_php
{

    // Page ID
    public $PageID = 'custom';

    // Project ID
    public $ProjectID = "{00441056-EF9D-4233-BDD9-EE81681FA399}";

    // Table name
    public $TableName = 'main.php';

    // Page object name
    public $PageObjName = 'main_php';

    // Page name
    public function PageName()
    {
        return ew_CurrentPage();
    }

    // Page URL
    public function PageUrl()
    {
        $PageUrl = ew_CurrentPage() . "?";
        return $PageUrl;
    }

    // Message
    public function getMessage()
    {
        return @$_SESSION[EW_SESSION_MESSAGE];
    }

    public function setMessage($v)
    {
        ew_AddMessage($_SESSION[EW_SESSION_MESSAGE], $v);
    }

    public function getFailureMessage()
    {
        return @$_SESSION[EW_SESSION_FAILURE_MESSAGE];
    }

    public function setFailureMessage($v)
    {
        ew_AddMessage($_SESSION[EW_SESSION_FAILURE_MESSAGE], $v);
    }

    public function getSuccessMessage()
    {
        return @$_SESSION[EW_SESSION_SUCCESS_MESSAGE];
    }

    public function setSuccessMessage($v)
    {
        ew_AddMessage($_SESSION[EW_SESSION_SUCCESS_MESSAGE], $v);
    }

    public function getWarningMessage()
    {
        return @$_SESSION[EW_SESSION_WARNING_MESSAGE];
    }

    public function setWarningMessage($v)
    {
        ew_AddMessage($_SESSION[EW_SESSION_WARNING_MESSAGE], $v);
    }

    // Methods to clear message
    public function ClearMessage()
    {
        $_SESSION[EW_SESSION_MESSAGE] = "";
    }

    public function ClearFailureMessage()
    {
        $_SESSION[EW_SESSION_FAILURE_MESSAGE] = "";
    }

    public function ClearSuccessMessage()
    {
        $_SESSION[EW_SESSION_SUCCESS_MESSAGE] = "";
    }

    public function ClearWarningMessage()
    {
        $_SESSION[EW_SESSION_WARNING_MESSAGE] = "";
    }

    public function ClearMessages()
    {
        $_SESSION[EW_SESSION_MESSAGE] = "";
        $_SESSION[EW_SESSION_FAILURE_MESSAGE] = "";
        $_SESSION[EW_SESSION_SUCCESS_MESSAGE] = "";
        $_SESSION[EW_SESSION_WARNING_MESSAGE] = "";
    }

    // Show message
    public function ShowMessage()
    {
        $hidden = false;
        $html = "";

        // Message
        $sMessage = $this->getMessage();
        $this->Message_Showing($sMessage, "");
        if ($sMessage != "") { // Message in Session, display
            if (!$hidden) {
                $sMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sMessage;
            }

            $html .= "<div class=\"alert alert-info ewInfo\">" . $sMessage . "</div>";
            $_SESSION[EW_SESSION_MESSAGE] = ""; // Clear message in Session
        }

        // Warning message
        $sWarningMessage = $this->getWarningMessage();
        $this->Message_Showing($sWarningMessage, "warning");
        if ($sWarningMessage != "") { // Message in Session, display
            if (!$hidden) {
                $sWarningMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sWarningMessage;
            }

            $html .= "<div class=\"alert alert-warning ewWarning\">" . $sWarningMessage . "</div>";
            $_SESSION[EW_SESSION_WARNING_MESSAGE] = ""; // Clear message in Session
        }

        // Success message
        $sSuccessMessage = $this->getSuccessMessage();
        $this->Message_Showing($sSuccessMessage, "success");
        if ($sSuccessMessage != "") { // Message in Session, display
            if (!$hidden) {
                $sSuccessMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sSuccessMessage;
            }

            $html .= "<div class=\"alert alert-success ewSuccess\">" . $sSuccessMessage . "</div>";
            $_SESSION[EW_SESSION_SUCCESS_MESSAGE] = ""; // Clear message in Session
        }

        // Failure message
        $sErrorMessage = $this->getFailureMessage();
        $this->Message_Showing($sErrorMessage, "failure");
        if ($sErrorMessage != "") { // Message in Session, display
            if (!$hidden) {
                $sErrorMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sErrorMessage;
            }

            $html .= "<div class=\"alert alert-danger ewError\">" . $sErrorMessage . "</div>";
            $_SESSION[EW_SESSION_FAILURE_MESSAGE] = ""; // Clear message in Session
        }
        echo "<div class=\"ewMessageDialog\"" . (($hidden) ? " style=\"display: none;\"" : "") . ">" . $html . "</div>";
    }
    public $Token = "";
    public $TokenTimeout = 0;
    public $CheckToken = EW_CHECK_TOKEN;
    public $CheckTokenFn = "ew_CheckToken";
    public $CreateTokenFn = "ew_CreateToken";

    // Valid Post
    public function ValidPost()
    {
        if (!$this->CheckToken || !ew_IsHttpPost()) {
            return true;
        }

        if (!isset($_POST[EW_TOKEN_NAME])) {
            return false;
        }

        $fn = $this->CheckTokenFn;
        if (is_callable($fn)) {
            return $fn($_POST[EW_TOKEN_NAME], $this->TokenTimeout);
        }

        return false;
    }

    // Create Token
    public function CreateToken()
    {
        global $gsToken;
        if ($this->CheckToken) {
            $fn = $this->CreateTokenFn;
            if ($this->Token == "" && is_callable($fn)) // Create token
            {
                $this->Token = $fn();
            }

            $gsToken = $this->Token; // Save to global variable
        }
    }

    //
    // Page class constructor
    //
    public function __construct()
    {
        global $conn, $Language;
        global $UserTable, $UserTableConn;
        $GLOBALS["Page"] = &$this;
        $this->TokenTimeout = ew_SessionTimeoutTime();

        // Language object
        if (!isset($Language)) {
            $Language = new cLanguage();
        }

        // Page ID
        if (!defined("EW_PAGE_ID")) {
            define("EW_PAGE_ID", 'custom');
        }

        // Table name (for backward compatibility)
        if (!defined("EW_TABLE_NAME")) {
            define("EW_TABLE_NAME", 'main.php');
        }

        // Start timer
        if (!isset($GLOBALS["gTimer"])) {
            $GLOBALS["gTimer"] = new cTimer();
        }

        // Open connection
        if (!isset($conn)) {
            $conn = ew_Connect();
        }

        // User table object (usuario)
        if (!isset($UserTable)) {
            $UserTable = new cusuario();
            $UserTableConn = Conn($UserTable->DBID);
        }
    }

    //
    //  Page_Init
    //
    public function Page_Init()
    {
        global $gsExport, $gsCustomExport, $gsExportFile, $UserProfile, $Language, $Security, $objForm;

        // Security
        $Security = new cAdvancedSecurity();
        if (!$Security->IsLoggedIn()) {
            $Security->AutoLogin();
        }

        if ($Security->IsLoggedIn()) {
            $Security->TablePermission_Loading();
        }

        $Security->LoadCurrentUserLevel($this->ProjectID . $this->TableName);
        if ($Security->IsLoggedIn()) {
            $Security->TablePermission_Loaded();
        }

        if (!$Security->CanReport()) {
            $Security->SaveLastUrl();
            $this->setFailureMessage($Language->Phrase("NoPermission")); // Set no permission
            $this->Page_Terminate(ew_GetUrl("index.php"));
        }

        // Global Page Loading event (in userfn*.php)
        Page_Loading();

        // Check token
        if (!$this->ValidPost()) {
            echo $Language->Phrase("InvalidPostRequest");
            $this->Page_Terminate();
            exit();
        }

        // Create Token
        $this->CreateToken();
    }

    //
    // Page_Terminate
    //
    public function Page_Terminate($url = "")
    {
        global $gsExportFile, $gTmpImages;

        // Global Page Unloaded event (in userfn*.php)
        Page_Unloaded();

        // Export
        // Close connection

        ew_CloseConn();

        // Go to URL if specified
        if ($url != "") {
            if (!EW_DEBUG_ENABLED && ob_get_length()) {
                ob_end_clean();
            }

            header("Location: " . $url);
        }
        exit();
    }

    //
    // Page main
    //
    public function Page_Main()
    {

        // Set up Breadcrumb
        $this->SetupBreadcrumb();
    }

    // Set up Breadcrumb
    public function SetupBreadcrumb()
    {
        global $Breadcrumb;
        $Breadcrumb = new cBreadcrumb();
        $url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/") + 1);
        $Breadcrumb->Add("custom", "main_php", $url, "", "main_php", true);
    }
}
?>
<?php ew_Header(false)?>
<?php

// Create page object
if (!isset($main_php)) {
    $main_php = new cmain_php();
}

// Page init
$main_php->Page_Init();

// Page main
$main_php->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();
?>
<?php include_once "header.php"?>
<?php if (!@$gbSkipHeaderFooter) {?>
<div class="ewToolbar">
<?php $Breadcrumb->Render();?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php }?>
<script type="text/javascript">
var EW_PAGE_ID = 'main';
</script>
<!--
**************************************
	 Iniciando variables generales
**************************************
!-->
<?php
$perfil_sys = $_SESSION["perfil_sys"];
?>
<!--
**************************************
	 Motor de busqueda estilo Google
**************************************
!-->
<style type="text/css">
	@media only screen and (min-width: 0px) {
	  #formulario-buscar { position:inherit}
	}
	@media only screen and (min-width: 700px) {
	  #formulario-buscar { position:absolute}
	}
</style>
<script type="text/javascript" src="busqueda/main.js"></script>
 <div id="formulario-buscar" class="open" align="center" style="top:10px;height:50px;right:0">
	<form id="form-busqueda" name="f">
			<div class="pull-right input-group input-group-lg" style="z-index:2; margin-right:70px">
		  <input id="texto-busqueda" type="text" class="form-control" name="q" size="20" placeholder="Buscar..." title="Busqueda">
		  <span class="input-group-btn">
		  	<button class="btn btn-default" type="button" onclick="$('#texto-busqueda').val('').focus();">&times;</button>
			<button id="btnBuscar" name="btnBuscar" class="btn btn-primary goobutton" type="button"><span class="glyphicon glyphicon-search"></span></button>
		  </span>
	  </div>
   </form>
<span class="responsive dropdown-menu dropdown-submenu" id="mensaje-buscando" style="color:#4CC3EC; text-align:center; display:none">Buscando...<img src="busqueda/img/loading.gif"></span>
</div>
<!--
**************************************
	Barra de munú izquierda
**************************************
!-->
<style type="text/css">
#leftmenu .ui-state-hover{ background-color:black; background-image:none}
#leftmenu{
	min-height:500px;
	min-width: 90px;
	z-index:2;
	text-align:center;
	position:fixed;
	left:0;top:0px;height:100%;background-color: #646464;
	border-radius:1px;
}
	@media only screen and (min-height: 0px) {
	  #leftmenu { position:absolute}
	}
	@media only screen and (min-height: 500px) {
	  #leftmenu { position:fixed}
	}
</style>
<?php
$btnLeftMenu_Click = "
	if($(this).is('.busy')) return false;
	$('.glyphicon',this).toggleClass('glyphicon-chevron-right glyphicon-chevron-left');
	$('.btnleftmenu').toggleClass('open').addClass('busy');
	$('#leftmenu').animate({left: $('.btnleftmenu').hasClass('open')?'0px':'-' + $('#leftmenu').width()  },'fast',function(){
		$('#mainbody').animate({'margin-left':($('.btnleftmenu').hasClass('open')?'90px':'0px')}, 200);
		if($('.btnleftmenu').hasClass('open')){ $('html, body').animate({scrollTop:0,scrollLeft:0}, 'fast'); };
		if( $('.btnleftmenu').hasClass('open') && $(window).width()<700 && $('.btnTopMenu').hasClass('open') )
			$('.btnTopMenu').trigger('click');
		$('.btnleftmenu').removeClass('busy');
	});
	return false;
	";

/* $('#leftmenu').toggle('slide','fast',function(){$('.btnleftmenu').toggleClass('open');  if($(window).width()<700 && $('#formulario-buscar').is(':visible')) $('.btnTopMenu').trigger('click'); });$('#mainbody').animate({'margin-left':($('.btnleftmenu').hasClass('open')?'90px':'0px')}, 200); if($('.btnleftmenu').hasClass('open')){ $('html, body').animate({scrollTop:0,scrollLeft:0}, 'fast'); } return false;    */
?>
<span style="z-index:3;bottom:85px;left:-10px;padding-right:5px" class="btn  btnleftmenu open close affix ui-corner-right " onclick="<?php echo $btnLeftMenu_Click; ?>">
	<span class="glyphicon glyphicon-chevron-left"></span>
</span>
<div id="leftmenu" class="modal-content">
	<div style="width: 100%; text-align: center;background-color: #009933">
		<a href="">
			<img src="images/arbol.png">
		</a>
	</div>
	<br>
	<center id="slidx_button" class=" glyphicon glyphicon-list" style="color:white;font-size:24px;font-weight: lighter;cursor:pointer;width:32px;margin:5px 5px"></center>
<?php if (comportamiento("101")) {?>
<!--COMPORTAMIENTO: Registro de pacientes desde la pantalla principal
<br>
<a href="#" onclick="pacienteadd(); return false;/*pacienteadd()*/" data-toggle="modal" data-target="#FrmMenuAddPaciente" style="display:block">
<span class="outlookbutton" style=" padding:0; width:90px; color:white">
	<img src="images/contactosadd.png" style="width:48px" height="48" border="0">
	Registrar paciente
</span>
</a>-->
<?php }?>
<?php if (comportamiento("112")) {?>
<!--COMPORTAMIENTO: Adicion de citas médicas para paciente nuevo en la barra lateral
<br>
<a href="javascript:citaadd()" style="display:block">
<span class="outlookbutton" style=" padding:0; width:90px; color:white">
	<img src="images/citapaciente.png" style="width:60px;height:55px" align="top" border="0">
  <span style="display:block">Cita de paciente nuevo</span>
</span>
</a>
-->
<?php }?>
<?php if (comportamiento("113") && comportamiento("114")) {?>
<!-- Boton de aniversarios
<br>
<a class="fade" id="btnAniversario" herf="#" onclick="$('div.metro-pivot').data('metro-pivot').goToItemByName('aniversario'); return false;">
<span class="outlookbutton" style=" padding:0; width:90px; color:white">
	<img src="images/birthday.png" style="width:48px" height="48" border="0">
  <span class="badge hashtag">5</span>
</span>
</a>
-->
<?php }?>
	<div align="center" style="position:absolute; bottom:0px; width:100%">
		<div style="display:inline-block;margin-bottom:13px">
			<a href="logout.php" class="close  glyphicon glyphicon-off" style="color:#FFF;font-size: 30px;"></a>
		</div>
	</div>
</div>
<!--
**************************************
MENU ESTILO IPOD
**************************************
!-->
<nav id="slidx_menu">
<div id="user_info" style="display:none" class="well-sm media">
  <div class="media-left media-middle">
	  <img class="img-circle media-object" src="images/foto.jpg" alt="..." style="width:64px">
  </div>
  <div class="media-middle media-body">
	<span class="h4 media-heading"><?php echo CurrentUserInfo("nombre") ? CurrentUserInfo("nombre") : (IsSysAdmin() ? "Administrador" : "???"); ?></span>
  </div>
</div>
</nav>
<link href="drilldownmenu/linkes_drilldown.css" rel="stylesheet" type="text/css">
<script src="drilldownmenu/linkes_drilldown.min.js" type="text/javascript"></script>
	<style>
		ul {
			padding: 0px;
			margin: 0px;
		}
		li {
			list-style: none;
		}
		#slidx_menu {
			background-color: #F5F5F5;
		}
		#slidx_menu a {
			padding: 15px 0px 15px 15px;

			//display: block;
			background:#459e00;
			color:#FFF;
		}
		#slidx_menu a:hover {
			background-color: #67b021;
			cursor: pointer;
			color:#fff;
			border-top:#E3F2FD solid 1px;
		}
		#slidx_button:hover {
			cursor: pointer;
		}
		.l_drillDown li,.l_drillDown li:last-child {

			//border-bottom: 2px solid #777
		}
		.goHome,.l_ddbc{background:#000 !important; padding:5px !important; color:white; font-size:18px;}

		/*.l_drillDownWrapper, #slidx_menu a.goHome{color:#31708f;background:none; border:none}*/
		.l_caret{background:none; color:#fff; padding-right:20px}
		.l_drillDown li.hasSubs:hover .l_caret{background:#009933}
		.l_drillDownWrapper{height:100% !important}
	</style>
		<script type="text/javascript">
			$(function(){
				var htmlmenu = $('#RootMenu').clone(false);
				htmlmenu.removeAttr("id").removeAttr("class").find(".dropdown-menu, .dropdown-submenu, li, ul").removeAttr("id").removeAttr("class");
				htmlmenu.appendTo('#slidx_menu').linkesDrillDown({lbHome:'<i class="glyphicon glyphicon-home goHome ewIcon"></i>',cssCaret:'glyphicon glyphicon-play-circle'});
				$('#user_info').prependTo('#slidx_menu ul:first').show();
			});
		</script>
<script type="text/javascript" src="drilldownmenu/slidx.js"></script>
<!--
**************************************
	Cuerpo principal
**************************************
!-->
<?php
ew_AddStylesheet("metro/jquery.metro.css");
ew_AddClientScript("metro/jquery.metro.js");
$btnTopMenu_Click = "
	if($(this).is('.busy')) return false;
	$('.glyphicon',this).toggleClass('glyphicon-chevron-up glyphicon-chevron-down');
	$('.btnTopMenu').toggleClass('open').addClass('busy');
	var TopMenu = $('#formulario-buscar,.metro-pivot>.headers').css('display','block');
	TopMenu.animate({top: $('.btnTopMenu').hasClass('open')?'0px':'-100px' },'fast',function(){
		if( $('.btnTopMenu').hasClass('open') && $(window).width()<700 && $('.btnleftmenu').hasClass('open'))
			$('.btnleftmenu').trigger('click');
		TopMenu.css('display',$('.btnTopMenu').hasClass('open')?'block':'none');
		$('.btnTopMenu').removeClass('busy');
	});
	return false;
	";
?>
<span style="z-index:1001;right:20px;top:0px;padding-bottom:5px" class="btnTopMenu close open affix ui-corner-bottom outlookbutton" onclick="<?php echo $btnTopMenu_Click; ?>">
	<span class="glyphicon glyphicon-chevron-up"></span>
</span>
<style type="text/css">
	@media only screen and (max-width: 700px) {
	 .modal { left:initial;margin-left:4%; max-width:90%; top:40px;height:80%;overflow:auto;min-height:505px}
	}
</style>
<div id="mainbody" style="margin-top:5px;">
	<div class="metro-pivot">
	  <div class="pivot-item">
				<h3> <img src="images/home.png" align="absmiddle" style="width:48px" border="0"><span style="display:none">Inicio</span></h3>
<?php
echo $perfil_sys["interfaz"];
?>
			</div>
		  <div class="pivot-item" id="resultado-busqueda">
		  	<h3> <img src="images/search.png" align="absmiddle" border="0" style="width:48px"/><span style="display:none">Resultado</span></h3>
			<div align="right" style="position:absolute; top:0px; left:0" class="ui-button ui-state-default">
			</div>
			<iframe id="marco-resultado-busqueda" style="width:98%" class="autosize fixedwidth empty" scrolling="no" src="" frameborder="0" marginheight="0" marginwidth="0"  ></iframe>
		  </div>
			<script type="text/javascript">
			jQuery(document).ready(function($){
				$('#marco-resultado-busqueda').on('load',function(){
					if($('div.metro-pivot').data('metro-pivot')) $('div.metro-pivot').data('metro-pivot').goToItemByName('Resultado');
				});
			});
			</script>
		<!-- Pagina links del menu principal-->
		<div class="pivot-item">
			<h3>contenido</h3>
			<iframe id="marco-contenido" data-url="" class="autosize empty" scrolling="no" src="" frameborder="0" marginheight="0" marginwidth="0"  ></iframe>
			<script type="text/javascript">
				ewLanguage.obj.label_contenido = 'contenido';
			</script>
		</div>
  </div>
</div>
<script type="text/javascript">
			var defaults = {
				animationDuration: 250,
				headerOpacity: 0.25,
				fixedHeaders: false,
				headerSelector: function (item) { return item.children("h3").first(); },
				itemSelector: function (item) { return item.children(".pivot-item"); },
				headerItemTemplate: function () { return $("<span class='header'>"); },
				pivotItemTemplate: function () { return $("<div class='pivotItem'>"); },
				itemsTemplate: function () { return $("<div class='items'>"); },
				headersTemplate: function () { return $("<div style='position:relative' class='headers'>"); },
				controlInitialized: function(){
					this.data('metro-pivot',this);

					//this.headers.children(":contains(Inicio)").hide();
				},
				beforeItemChanged: function(index){
					var iframe = this.find('.pivot-item:eq(' + index + ')').find('iframe');
					if(iframe.length && iframe.attr('id')=='marco-contenido'){
						iframe.attr('src', iframe.data('url') );
					}
				},
				selectedItemChanged: function(index){

					//this.headers.children(":contains(Inicio)").hide();
					if(this.items != undefined){
						var iframe = this.find('.pivot-item:eq(' + index + ')').find('iframe');
						if(iframe.length){
							if (iframe.is('.empty')){
  								iframe.one('load',function(){ $(this).removeClass('empty'); $('.pageload-overlay').hide(); }).attr('src', iframe.data('url') );
							}else{

								//iframe[0].contentWindow.resizeIFRM();
							}
  						}
					}
				}
			};
			$(function () {
				$('#mainbody').css('margin-left', $('#leftmenu').width() + 'px' );
				$("div.metro-pivot").metroPivot(defaults);
				$('.headers .header:last').addClass('hidden');

				/*
				var hammertime = $("div.metro-pivot .items").hammer();
				hammertime.on("touch", function() {
					alert('you swiped left!');
				});
				*/
			});
resizeTimer = 0;
ToggleBarTimer = 0;

function doToggleBar() {

	/*
					if($(window).width()<1024 && $('.btnleftmenu').hasClass('open')) $('.btnleftmenu').trigger('click');
					if($(window).width()<700 && $('.btnTopMenu').hasClass('open') ) $('.btnTopMenu').trigger('click');
	*/
};

/*
$('.btnleftmenu,.btnTopMenu').on('mouseup touchend',function(e) {
		$(this).trigger('click');
		});
*/
$(window).bind('resize', function () {
				clearTimeout(ToggleBarTimer);
				ToggleBarTimer = top.setTimeout(doToggleBar, 1000);
});
var isScrolling = false;
(function( $ ) {
	$(function() {
		var $output = $( "#output" ),
			scrolling = "<span id='scrolling'>Scrolling</span>",
			stopped = "<span id='stopped'>Stopped</span>";
			$( window ).scroll(function() {
				isScrolling = true;
				clearTimeout( $.data( this, "scrollCheck" ) );
				$.data( this, "scrollCheck", setTimeout(function() {
					isScrolling = false;
				}, 250) );
			});
	});
})( jQuery );
</script>
<?php if (EW_DEBUG_ENABLED) {
    echo ew_DebugMsg();
}
?>
<?php include_once "footer.php"?>
<?php
$main_php->Page_Terminate();
?>
