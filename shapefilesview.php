<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg12.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql12.php") ?>
<?php include_once "phpfn12.php" ?>
<?php include_once "shapefilesinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn12.php" ?>
<?php

//
// Page class
//

$shapefiles_view = NULL; // Initialize page object first

class cshapefiles_view extends cshapefiles {

	// Page ID
	var $PageID = 'view';

	// Project ID
	var $ProjectID = "{00441056-EF9D-4233-BDD9-EE81681FA399}";

	// Table name
	var $TableName = 'shapefiles';

	// Page object name
	var $PageObjName = 'shapefiles_view';

	// Page name
	function PageName() {
		return ew_CurrentPage();
	}

	// Page URL
	function PageUrl() {
		$PageUrl = ew_CurrentPage() . "?";
		if ($this->UseTokenInUrl) $PageUrl .= "t=" . $this->TableVar . "&"; // Add page token
		return $PageUrl;
	}

	// Page URLs
	var $AddUrl;
	var $EditUrl;
	var $CopyUrl;
	var $DeleteUrl;
	var $ViewUrl;
	var $ListUrl;

	// Export URLs
	var $ExportPrintUrl;
	var $ExportHtmlUrl;
	var $ExportExcelUrl;
	var $ExportWordUrl;
	var $ExportXmlUrl;
	var $ExportCsvUrl;
	var $ExportPdfUrl;

	// Custom export
	var $ExportExcelCustom = FALSE;
	var $ExportWordCustom = FALSE;
	var $ExportPdfCustom = FALSE;
	var $ExportEmailCustom = FALSE;

	// Update URLs
	var $InlineAddUrl;
	var $InlineCopyUrl;
	var $InlineEditUrl;
	var $GridAddUrl;
	var $GridEditUrl;
	var $MultiDeleteUrl;
	var $MultiUpdateUrl;

	// Message
	function getMessage() {
		return @$_SESSION[EW_SESSION_MESSAGE];
	}

	function setMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_MESSAGE], $v);
	}

	function getFailureMessage() {
		return @$_SESSION[EW_SESSION_FAILURE_MESSAGE];
	}

	function setFailureMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_FAILURE_MESSAGE], $v);
	}

	function getSuccessMessage() {
		return @$_SESSION[EW_SESSION_SUCCESS_MESSAGE];
	}

	function setSuccessMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_SUCCESS_MESSAGE], $v);
	}

	function getWarningMessage() {
		return @$_SESSION[EW_SESSION_WARNING_MESSAGE];
	}

	function setWarningMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_WARNING_MESSAGE], $v);
	}

	// Methods to clear message
	function ClearMessage() {
		$_SESSION[EW_SESSION_MESSAGE] = "";
	}

	function ClearFailureMessage() {
		$_SESSION[EW_SESSION_FAILURE_MESSAGE] = "";
	}

	function ClearSuccessMessage() {
		$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = "";
	}

	function ClearWarningMessage() {
		$_SESSION[EW_SESSION_WARNING_MESSAGE] = "";
	}

	function ClearMessages() {
		$_SESSION[EW_SESSION_MESSAGE] = "";
		$_SESSION[EW_SESSION_FAILURE_MESSAGE] = "";
		$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = "";
		$_SESSION[EW_SESSION_WARNING_MESSAGE] = "";
	}

	// Show message
	function ShowMessage() {
		$hidden = FALSE;
		$html = "";

		// Message
		$sMessage = $this->getMessage();
		$this->Message_Showing($sMessage, "");
		if ($sMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sMessage;
			$html .= "<div class=\"alert alert-info ewInfo\">" . $sMessage . "</div>";
			$_SESSION[EW_SESSION_MESSAGE] = ""; // Clear message in Session
		}

		// Warning message
		$sWarningMessage = $this->getWarningMessage();
		$this->Message_Showing($sWarningMessage, "warning");
		if ($sWarningMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sWarningMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sWarningMessage;
			$html .= "<div class=\"alert alert-warning ewWarning\">" . $sWarningMessage . "</div>";
			$_SESSION[EW_SESSION_WARNING_MESSAGE] = ""; // Clear message in Session
		}

		// Success message
		$sSuccessMessage = $this->getSuccessMessage();
		$this->Message_Showing($sSuccessMessage, "success");
		if ($sSuccessMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sSuccessMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sSuccessMessage;
			$html .= "<div class=\"alert alert-success ewSuccess\">" . $sSuccessMessage . "</div>";
			$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = ""; // Clear message in Session
		}

		// Failure message
		$sErrorMessage = $this->getFailureMessage();
		$this->Message_Showing($sErrorMessage, "failure");
		if ($sErrorMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sErrorMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sErrorMessage;
			$html .= "<div class=\"alert alert-danger ewError\">" . $sErrorMessage . "</div>";
			$_SESSION[EW_SESSION_FAILURE_MESSAGE] = ""; // Clear message in Session
		}
		echo "<div class=\"ewMessageDialog\"" . (($hidden) ? " style=\"display: none;\"" : "") . ">" . $html . "</div>";
	}
	var $PageHeader;
	var $PageFooter;

	// Show Page Header
	function ShowPageHeader() {
		$sHeader = $this->PageHeader;
		$this->Page_DataRendering($sHeader);
		if ($sHeader <> "") { // Header exists, display
			echo "<p>" . $sHeader . "</p>";
		}
	}

	// Show Page Footer
	function ShowPageFooter() {
		$sFooter = $this->PageFooter;
		$this->Page_DataRendered($sFooter);
		if ($sFooter <> "") { // Footer exists, display
			echo "<p>" . $sFooter . "</p>";
		}
	}

	// Validate page request
	function IsPageRequest() {
		global $objForm;
		if ($this->UseTokenInUrl) {
			if ($objForm)
				return ($this->TableVar == $objForm->GetValue("t"));
			if (@$_GET["t"] <> "")
				return ($this->TableVar == $_GET["t"]);
		} else {
			return TRUE;
		}
	}
	var $Token = "";
	var $TokenTimeout = 0;
	var $CheckToken = EW_CHECK_TOKEN;
	var $CheckTokenFn = "ew_CheckToken";
	var $CreateTokenFn = "ew_CreateToken";

	// Valid Post
	function ValidPost() {
		if (!$this->CheckToken || !ew_IsHttpPost())
			return TRUE;
		if (!isset($_POST[EW_TOKEN_NAME]))
			return FALSE;
		$fn = $this->CheckTokenFn;
		if (is_callable($fn))
			return $fn($_POST[EW_TOKEN_NAME], $this->TokenTimeout);
		return FALSE;
	}

	// Create Token
	function CreateToken() {
		global $gsToken;
		if ($this->CheckToken) {
			$fn = $this->CreateTokenFn;
			if ($this->Token == "" && is_callable($fn)) // Create token
				$this->Token = $fn();
			$gsToken = $this->Token; // Save to global variable
		}
	}

	//
	// Page class constructor
	//
	function __construct() {
		global $conn, $Language;
		global $UserTable, $UserTableConn;
		$GLOBALS["Page"] = &$this;
		$this->TokenTimeout = ew_SessionTimeoutTime();

		// Language object
		if (!isset($Language)) $Language = new cLanguage();

		// Parent constuctor
		parent::__construct();

		// Table object (shapefiles)
		if (!isset($GLOBALS["shapefiles"]) || get_class($GLOBALS["shapefiles"]) == "cshapefiles") {
			$GLOBALS["shapefiles"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["shapefiles"];
		}
		$KeyUrl = "";
		if (@$_GET["idshapefile"] <> "") {
			$this->RecKey["idshapefile"] = $_GET["idshapefile"];
			$KeyUrl .= "&amp;idshapefile=" . urlencode($this->RecKey["idshapefile"]);
		}
		$this->ExportPrintUrl = $this->PageUrl() . "export=print" . $KeyUrl;
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html" . $KeyUrl;
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel" . $KeyUrl;
		$this->ExportWordUrl = $this->PageUrl() . "export=word" . $KeyUrl;
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml" . $KeyUrl;
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv" . $KeyUrl;
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf" . $KeyUrl;

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'view', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'shapefiles', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect($this->DBID);

		// User table object (usuario)
		if (!isset($UserTable)) {
			$UserTable = new cusuario();
			$UserTableConn = Conn($UserTable->DBID);
		}

		// Export options
		$this->ExportOptions = new cListOptions();
		$this->ExportOptions->Tag = "div";
		$this->ExportOptions->TagClassName = "ewExportOption";

		// Other options
		$this->OtherOptions['action'] = new cListOptions();
		$this->OtherOptions['action']->Tag = "div";
		$this->OtherOptions['action']->TagClassName = "ewActionOption";
		$this->OtherOptions['detail'] = new cListOptions();
		$this->OtherOptions['detail']->Tag = "div";
		$this->OtherOptions['detail']->TagClassName = "ewDetailOption";
	}

	// 
	//  Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsCustomExport, $gsExportFile, $UserProfile, $Language, $Security, $objForm;

		// Security
		$Security = new cAdvancedSecurity();
		if (!$Security->IsLoggedIn()) $Security->AutoLogin();
		if ($Security->IsLoggedIn()) $Security->TablePermission_Loading();
		$Security->LoadCurrentUserLevel($this->ProjectID . $this->TableName);
		if ($Security->IsLoggedIn()) $Security->TablePermission_Loaded();
		if (!$Security->CanView()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage($Language->Phrase("NoPermission")); // Set no permission
			if ($Security->CanList())
				$this->Page_Terminate(ew_GetUrl("shapefileslist.php"));
			else
				$this->Page_Terminate(ew_GetUrl("login.php"));
		}
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->idshapefile->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

		// Set up multi page object
		$this->SetupMultiPages();

		// Global Page Loading event (in userfn*.php)
		Page_Loading();

		// Page Load event
		$this->Page_Load();

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
	function Page_Terminate($url = "") {
		global $gsExportFile, $gTmpImages;

		// Page Unload event
		$this->Page_Unload();

		// Global Page Unloaded event (in userfn*.php)
		Page_Unloaded();

		// Export
		global $EW_EXPORT, $shapefiles;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($shapefiles);
				$doc->Text = $sContent;
				if ($this->Export == "email")
					echo $this->ExportEmail($doc->Text);
				else
					$doc->Export();
				ew_DeleteTmpImages(); // Delete temp images
				exit();
			}
		}
		$this->Page_Redirecting($url);

		 // Close connection
		ew_CloseConn();

		// Go to URL if specified
		if ($url <> "") {
			if (!EW_DEBUG_ENABLED && ob_get_length())
				ob_end_clean();
			header("Location: " . $url);
		}
		exit();
	}
	var $ExportOptions; // Export options
	var $OtherOptions = array(); // Other options
	var $DisplayRecs = 1;
	var $DbMasterFilter;
	var $DbDetailFilter;
	var $StartRec;
	var $StopRec;
	var $TotalRecs = 0;
	var $RecRange = 10;
	var $RecCnt;
	var $RecKey = array();
	var $Recordset;
	var $MultiPages; // Multi pages object

	//
	// Page main
	//
	function Page_Main() {
		global $Language;

		// Load current record
		$bLoadCurrentRecord = FALSE;
		$sReturnUrl = "";
		$bMatchRecord = FALSE;

		// Set up Breadcrumb
		if ($this->Export == "")
			$this->SetupBreadcrumb();
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET["idshapefile"] <> "") {
				$this->idshapefile->setQueryStringValue($_GET["idshapefile"]);
				$this->RecKey["idshapefile"] = $this->idshapefile->QueryStringValue;
			} elseif (@$_POST["idshapefile"] <> "") {
				$this->idshapefile->setFormValue($_POST["idshapefile"]);
				$this->RecKey["idshapefile"] = $this->idshapefile->FormValue;
			} else {
				$sReturnUrl = "shapefileslist.php"; // Return to list
			}

			// Get action
			$this->CurrentAction = "I"; // Display form
			switch ($this->CurrentAction) {
				case "I": // Get a record to display
					if (!$this->LoadRow()) { // Load record based on key
						if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
							$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
						$sReturnUrl = "shapefileslist.php"; // No matching record, return to list
					}
			}
		} else {
			$sReturnUrl = "shapefileslist.php"; // Not page request, return to list
		}
		if ($sReturnUrl <> "")
			$this->Page_Terminate($sReturnUrl);

		// Render row
		$this->RowType = EW_ROWTYPE_VIEW;
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Set up other options
	function SetupOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
		$option = &$options["action"];

		// Add
		$item = &$option->Add("add");
		$item->Body = "<a class=\"ewAction ewAdd\" title=\"" . ew_HtmlTitle($Language->Phrase("ViewPageAddLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("ViewPageAddLink")) . "\" href=\"" . ew_HtmlEncode($this->AddUrl) . "\">" . $Language->Phrase("ViewPageAddLink") . "</a>";
		$item->Visible = ($this->AddUrl <> "" && $Security->CanAdd());

		// Edit
		$item = &$option->Add("edit");
		$item->Body = "<a class=\"ewAction ewEdit\" title=\"" . ew_HtmlTitle($Language->Phrase("ViewPageEditLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("ViewPageEditLink")) . "\" href=\"" . ew_HtmlEncode($this->EditUrl) . "\">" . $Language->Phrase("ViewPageEditLink") . "</a>";
		$item->Visible = ($this->EditUrl <> "" && $Security->CanEdit());

		// Delete
		$item = &$option->Add("delete");
		$item->Body = "<a onclick=\"return ew_ConfirmDelete(this);\" class=\"ewAction ewDelete\" title=\"" . ew_HtmlTitle($Language->Phrase("ViewPageDeleteLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("ViewPageDeleteLink")) . "\" href=\"" . ew_HtmlEncode($this->DeleteUrl) . "\">" . $Language->Phrase("ViewPageDeleteLink") . "</a>";
		$item->Visible = ($this->DeleteUrl <> "" && $Security->CanDelete());

		// Set up action default
		$option = &$options["action"];
		$option->DropDownButtonPhrase = $Language->Phrase("ButtonActions");
		$option->UseImageAndText = TRUE;
		$option->UseDropDownButton = FALSE;
		$option->UseButtonGroup = TRUE;
		$item = &$option->Add($option->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;
	}

	// Set up starting record parameters
	function SetUpStartRec() {
		if ($this->DisplayRecs == 0)
			return;
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET[EW_TABLE_START_REC] <> "") { // Check for "start" parameter
				$this->StartRec = $_GET[EW_TABLE_START_REC];
				$this->setStartRecordNumber($this->StartRec);
			} elseif (@$_GET[EW_TABLE_PAGE_NO] <> "") {
				$PageNo = $_GET[EW_TABLE_PAGE_NO];
				if (is_numeric($PageNo)) {
					$this->StartRec = ($PageNo-1)*$this->DisplayRecs+1;
					if ($this->StartRec <= 0) {
						$this->StartRec = 1;
					} elseif ($this->StartRec >= intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1) {
						$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1;
					}
					$this->setStartRecordNumber($this->StartRec);
				}
			}
		}
		$this->StartRec = $this->getStartRecordNumber();

		// Check if correct start record counter
		if (!is_numeric($this->StartRec) || $this->StartRec == "") { // Avoid invalid start record counter
			$this->StartRec = 1; // Reset start record counter
			$this->setStartRecordNumber($this->StartRec);
		} elseif (intval($this->StartRec) > intval($this->TotalRecs)) { // Avoid starting record > total records
			$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to last page first record
			$this->setStartRecordNumber($this->StartRec);
		} elseif (($this->StartRec-1) % $this->DisplayRecs <> 0) {
			$this->StartRec = intval(($this->StartRec-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to page boundary
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Load row based on key values
	function LoadRow() {
		global $Security, $Language;
		$sFilter = $this->KeyFilter();

		// Call Row Selecting event
		$this->Row_Selecting($sFilter);

		// Load SQL based on filter
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$conn = &$this->Connection();
		$res = FALSE;
		$rs = ew_LoadRecordset($sSql, $conn);
		if ($rs && !$rs->EOF) {
			$res = TRUE;
			$this->LoadRowValues($rs); // Load row values
			$rs->Close();
		}
		return $res;
	}

	// Load row values from recordset
	function LoadRowValues(&$rs) {
		if (!$rs || $rs->EOF) return;

		// Call Row Selected event
		$row = &$rs->fields;
		$this->Row_Selected($row);
		$this->idshapefile->setDbValue($rs->fields('idshapefile'));
		$this->narchivoorigen->setDbValue($rs->fields('narchivoorigen'));
		$this->narchivo->Upload->DbValue = $rs->fields('narchivo');
		$this->narchivo->CurrentValue = $this->narchivo->Upload->DbValue;
		$this->idaplicacion->setDbValue($rs->fields('idaplicacion'));
		$this->token->setDbValue($rs->fields('token'));
		$this->idusuario->setDbValue($rs->fields('idusuario'));
		$this->tipo->setDbValue($rs->fields('tipo'));
		$this->folder->setDbValue($rs->fields('folder'));
		$this->fechacreacion->setDbValue($rs->fields('fechacreacion'));
		$this->tamano->setDbValue($rs->fields('tamano'));
		$this->srid->setDbValue($rs->fields('srid'));
		$this->tipogeom->setDbValue($rs->fields('tipogeom'));
		$this->acciones->setDbValue($rs->fields('acciones'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->idshapefile->DbValue = $row['idshapefile'];
		$this->narchivoorigen->DbValue = $row['narchivoorigen'];
		$this->narchivo->Upload->DbValue = $row['narchivo'];
		$this->idaplicacion->DbValue = $row['idaplicacion'];
		$this->token->DbValue = $row['token'];
		$this->idusuario->DbValue = $row['idusuario'];
		$this->tipo->DbValue = $row['tipo'];
		$this->folder->DbValue = $row['folder'];
		$this->fechacreacion->DbValue = $row['fechacreacion'];
		$this->tamano->DbValue = $row['tamano'];
		$this->srid->DbValue = $row['srid'];
		$this->tipogeom->DbValue = $row['tipogeom'];
		$this->acciones->DbValue = $row['acciones'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		$this->AddUrl = $this->GetAddUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();
		$this->ListUrl = $this->GetListUrl();
		$this->SetupOtherOptions();

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// idshapefile
		// narchivoorigen
		// narchivo
		// idaplicacion
		// token
		// idusuario
		// tipo
		// folder
		// fechacreacion
		// tamano
		// srid
		// tipogeom
		// acciones

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// idshapefile
		$this->idshapefile->ViewValue = $this->idshapefile->CurrentValue;
		$this->idshapefile->ViewCustomAttributes = "";

		// narchivoorigen
		$this->narchivoorigen->ViewValue = $this->narchivoorigen->CurrentValue;
		$this->narchivoorigen->ViewCustomAttributes = ["style" => "text-transform: none;"];

		// narchivo
		if (!ew_Empty($this->narchivo->Upload->DbValue)) {
			$this->narchivo->ViewValue = $this->narchivo->Upload->DbValue;
		} else {
			$this->narchivo->ViewValue = "";
		}
		$this->narchivo->ViewCustomAttributes = ["style" => "text-transform: none;"];

		// idaplicacion
		$this->idaplicacion->ViewValue = $this->idaplicacion->CurrentValue;
		$this->idaplicacion->ViewCustomAttributes = "";

		// token
		$this->token->ViewValue = $this->token->CurrentValue;
		$this->token->ViewCustomAttributes = ["style" => "text-transform: none;"];

		// idusuario
		$this->idusuario->ViewValue = $this->idusuario->CurrentValue;
		if (strval($this->idusuario->CurrentValue) <> "") {
			$sFilterWrk = "\"idusuario\"" . ew_SearchString("=", $this->idusuario->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT \"idusuario\", \"nombre\" AS \"DispFld\", '' AS \"Disp2Fld\", '' AS \"Disp3Fld\", '' AS \"Disp4Fld\" FROM \"registro_derecho\".\"usuario\"";
		$sWhereWrk = "";
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->idusuario, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY \"nombre\" ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->idusuario->ViewValue = $this->idusuario->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->idusuario->ViewValue = $this->idusuario->CurrentValue;
			}
		} else {
			$this->idusuario->ViewValue = NULL;
		}
		$this->idusuario->ViewCustomAttributes = ["style" => "text-transform: none;"];

		// tipo
		$this->tipo->ViewValue = $this->tipo->CurrentValue;
		$this->tipo->ViewCustomAttributes = "";

		// folder
		$this->folder->ViewValue = $this->folder->CurrentValue;
		$this->folder->ViewCustomAttributes = "";

		// fechacreacion
		$this->fechacreacion->ViewValue = $this->fechacreacion->CurrentValue;
		$this->fechacreacion->ViewCustomAttributes = "";

		// tamano
		$this->tamano->ViewValue = $this->tamano->CurrentValue;
		$this->tamano->ViewCustomAttributes = "";

		// srid
		if (strval($this->srid->CurrentValue) <> "") {
			$this->srid->ViewValue = $this->srid->OptionCaption($this->srid->CurrentValue);
		} else {
			$this->srid->ViewValue = NULL;
		}
		$this->srid->ViewCustomAttributes = "";

		// tipogeom
		$this->tipogeom->ViewValue = $this->tipogeom->CurrentValue;
		$this->tipogeom->ViewCustomAttributes = "";

		// acciones
		if (strval($this->acciones->CurrentValue) <> "") {
			$arwrk = explode(",", $this->acciones->CurrentValue);
			$sFilterWrk = "";
			foreach ($arwrk as $wrk) {
				if ($sFilterWrk <> "") $sFilterWrk .= " OR ";
				$sFilterWrk .= "\"idaccion\"" . ew_SearchString("=", trim($wrk), EW_DATATYPE_STRING, "");
			}
		$sSqlWrk = "SELECT \"idaccion\", \"accion\" AS \"DispFld\", '' AS \"Disp2Fld\", '' AS \"Disp3Fld\", '' AS \"Disp4Fld\" FROM \"registro_derecho\".\"appacciones\"";
		$sWhereWrk = "";
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->acciones, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->acciones->ViewValue = "";
				$ari = 0;
				while (!$rswrk->EOF) {
					$arwrk = array();
					$arwrk[1] = $rswrk->fields('DispFld');
					$this->acciones->ViewValue .= $this->acciones->DisplayValue($arwrk);
					$rswrk->MoveNext();
					if (!$rswrk->EOF) $this->acciones->ViewValue .= ew_ViewOptionSeparator($ari); // Separate Options
					$ari++;
				}
				$rswrk->Close();
			} else {
				$this->acciones->ViewValue = $this->acciones->CurrentValue;
			}
		} else {
			$this->acciones->ViewValue = NULL;
		}
		$this->acciones->ViewCustomAttributes = "";

			// idshapefile
			$this->idshapefile->LinkCustomAttributes = "";
			$this->idshapefile->HrefValue = "";
			$this->idshapefile->TooltipValue = "";

			// narchivoorigen
			$this->narchivoorigen->LinkCustomAttributes = "";
			$this->narchivoorigen->HrefValue = "";
			$this->narchivoorigen->TooltipValue = "";

			// narchivo
			$this->narchivo->LinkCustomAttributes = "";
			$this->narchivo->HrefValue = "";
			$this->narchivo->HrefValue2 = $this->narchivo->UploadPath . $this->narchivo->Upload->DbValue;
			$this->narchivo->TooltipValue = "";

			// idaplicacion
			$this->idaplicacion->LinkCustomAttributes = "";
			$this->idaplicacion->HrefValue = "";
			$this->idaplicacion->TooltipValue = "";

			// token
			$this->token->LinkCustomAttributes = "";
			$this->token->HrefValue = "";
			$this->token->TooltipValue = "";

			// idusuario
			$this->idusuario->LinkCustomAttributes = "";
			$this->idusuario->HrefValue = "";
			$this->idusuario->TooltipValue = "";

			// tipo
			$this->tipo->LinkCustomAttributes = "";
			$this->tipo->HrefValue = "";
			$this->tipo->TooltipValue = "";

			// folder
			$this->folder->LinkCustomAttributes = "";
			$this->folder->HrefValue = "";
			$this->folder->TooltipValue = "";

			// fechacreacion
			$this->fechacreacion->LinkCustomAttributes = "";
			$this->fechacreacion->HrefValue = "";
			$this->fechacreacion->TooltipValue = "";

			// tamano
			$this->tamano->LinkCustomAttributes = "";
			$this->tamano->HrefValue = "";
			$this->tamano->TooltipValue = "";

			// srid
			$this->srid->LinkCustomAttributes = "";
			$this->srid->HrefValue = "";
			$this->srid->TooltipValue = "";

			// tipogeom
			$this->tipogeom->LinkCustomAttributes = "";
			$this->tipogeom->HrefValue = "";
			$this->tipogeom->TooltipValue = "";

			// acciones
			$this->acciones->LinkCustomAttributes = "";
			$this->acciones->HrefValue = "";
			$this->acciones->TooltipValue = "";
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("shapefileslist.php"), "", $this->TableVar, TRUE);
		$PageId = "view";
		$Breadcrumb->Add("view", $PageId, $url);
	}

	// Set up multi pages
	function SetupMultiPages() {
		$pages = new cSubPages();
		$pages->Style = "tabs";
		$pages->Add(0);
		$pages->Add(1);
		$pages->Add(2);
		$this->MultiPages = $pages;
	}

	// Page Load event
	function Page_Load() {

		//echo "Page Load";
	}

	// Page Unload event
	function Page_Unload() {

		//echo "Page Unload";
	}

	// Page Redirecting event
	function Page_Redirecting(&$url) {

		// Example:
		//$url = "your URL";

	}

	// Message Showing event
	// $type = ''|'success'|'failure'|'warning'
	function Message_Showing(&$msg, $type) {
		if ($type == 'success') {

			//$msg = "your success message";
		} elseif ($type == 'failure') {

			//$msg = "your failure message";
		} elseif ($type == 'warning') {

			//$msg = "your warning message";
		} else {

			//$msg = "your message";
		}
	}

	// Page Render event
	function Page_Render() {

		//echo "Page Render";
		global $JSLibs;
		$JSLibs[]="OPENLAYERS";
	}

	// Page Data Rendering event
	function Page_DataRendering(&$header) {

		// Example:
		//$header = "your header";

	}

	// Page Data Rendered event
	function Page_DataRendered(&$footer) {

		// Example:
		//$footer = "your footer";

	}

	// Page Exporting event
	// $this->ExportDoc = export document object
	function Page_Exporting() {

		//$this->ExportDoc->Text = "my header"; // Export header
		//return FALSE; // Return FALSE to skip default export and use Row_Export event

		return TRUE; // Return TRUE to use default export and skip Row_Export event
	}

	// Row Export event
	// $this->ExportDoc = export document object
	function Row_Export($rs) {

	    //$this->ExportDoc->Text .= "my content"; // Build HTML with field value: $rs["MyField"] or $this->MyField->ViewValue
	}

	// Page Exported event
	// $this->ExportDoc = export document object
	function Page_Exported() {

		//$this->ExportDoc->Text .= "my footer"; // Export footer
		//echo $this->ExportDoc->Text;

	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($shapefiles_view)) $shapefiles_view = new cshapefiles_view();

// Page init
$shapefiles_view->Page_Init();

// Page main
$shapefiles_view->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$shapefiles_view->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "view";
var CurrentForm = fshapefilesview = new ew_Form("fshapefilesview", "view");

// Form_CustomValidate event
fshapefilesview.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fshapefilesview.ValidateRequired = true;
<?php } else { ?>
fshapefilesview.ValidateRequired = false; 
<?php } ?>

// Multi-Page
fshapefilesview.MultiPage = new ew_MultiPage("fshapefilesview");

// Dynamic selection lists
fshapefilesview.Lists["x_idusuario"] = {"LinkField":"x_idusuario","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fshapefilesview.Lists["x_srid"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fshapefilesview.Lists["x_srid"].Options = <?php echo json_encode($shapefiles->srid->Options()) ?>;
fshapefilesview.Lists["x_acciones[]"] = {"LinkField":"x_idaccion","Ajax":true,"AutoFill":false,"DisplayFields":["x_accion","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php $shapefiles_view->ExportOptions->Render("body") ?>
<?php
	foreach ($shapefiles_view->OtherOptions as &$option)
		$option->Render("body");
?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $shapefiles_view->ShowPageHeader(); ?>
<?php
$shapefiles_view->ShowMessage();
?>
<form name="fshapefilesview" id="fshapefilesview" class="form-inline ewForm ewViewForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($shapefiles_view->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $shapefiles_view->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="shapefiles">
<?php if ($shapefiles->Export == "") { ?>
<div class="ewMultiPage">
<div class="tabbable" id="shapefiles_view">
	<ul class="nav<?php echo $shapefiles_view->MultiPages->NavStyle() ?>">
		<li<?php echo $shapefiles_view->MultiPages->TabStyle("1") ?>><a href="#tab_shapefiles1" data-toggle="tab"><?php echo $shapefiles->PageCaption(1) ?></a></li>
		<li<?php echo $shapefiles_view->MultiPages->TabStyle("2") ?>><a href="#tab_shapefiles2" data-toggle="tab"><?php echo $shapefiles->PageCaption(2) ?></a></li>
	</ul>
	<div class="tab-content">
<?php } ?>
<?php if ($shapefiles->Export == "") { ?>
		<div class="tab-pane<?php echo $shapefiles_view->MultiPages->PageStyle("1") ?>" id="tab_shapefiles1">
<?php } ?>
<table class="table table-bordered table-striped ewViewTable">
<?php if ($shapefiles->idshapefile->Visible) { // idshapefile ?>
	<tr id="r_idshapefile">
		<td><span id="elh_shapefiles_idshapefile"><?php echo $shapefiles->idshapefile->FldCaption() ?></span></td>
		<td data-name="idshapefile"<?php echo $shapefiles->idshapefile->CellAttributes() ?>>
<span id="el_shapefiles_idshapefile" data-page="1">
<span<?php echo $shapefiles->idshapefile->ViewAttributes() ?>>
<?php echo $shapefiles->idshapefile->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($shapefiles->narchivoorigen->Visible) { // narchivoorigen ?>
	<tr id="r_narchivoorigen">
		<td><span id="elh_shapefiles_narchivoorigen"><?php echo $shapefiles->narchivoorigen->FldCaption() ?></span></td>
		<td data-name="narchivoorigen"<?php echo $shapefiles->narchivoorigen->CellAttributes() ?>>
<span id="el_shapefiles_narchivoorigen" data-page="1">
<span<?php echo $shapefiles->narchivoorigen->ViewAttributes() ?>>
<?php echo $shapefiles->narchivoorigen->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($shapefiles->narchivo->Visible) { // narchivo ?>
	<tr id="r_narchivo">
		<td><span id="elh_shapefiles_narchivo"><?php echo $shapefiles->narchivo->FldCaption() ?></span></td>
		<td data-name="narchivo"<?php echo $shapefiles->narchivo->CellAttributes() ?>>
<span id="el_shapefiles_narchivo" data-page="1">
<span<?php echo $shapefiles->narchivo->ViewAttributes() ?>>
<?php echo ew_GetFileViewTag($shapefiles->narchivo, $shapefiles->narchivo->ViewValue) ?>
</span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($shapefiles->idaplicacion->Visible) { // idaplicacion ?>
	<tr id="r_idaplicacion">
		<td><span id="elh_shapefiles_idaplicacion"><?php echo $shapefiles->idaplicacion->FldCaption() ?></span></td>
		<td data-name="idaplicacion"<?php echo $shapefiles->idaplicacion->CellAttributes() ?>>
<span id="el_shapefiles_idaplicacion" data-page="1">
<span<?php echo $shapefiles->idaplicacion->ViewAttributes() ?>>
<?php echo $shapefiles->idaplicacion->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($shapefiles->token->Visible) { // token ?>
	<tr id="r_token">
		<td><span id="elh_shapefiles_token"><?php echo $shapefiles->token->FldCaption() ?></span></td>
		<td data-name="token"<?php echo $shapefiles->token->CellAttributes() ?>>
<span id="el_shapefiles_token" data-page="1">
<span<?php echo $shapefiles->token->ViewAttributes() ?>>
<?php echo $shapefiles->token->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($shapefiles->idusuario->Visible) { // idusuario ?>
	<tr id="r_idusuario">
		<td><span id="elh_shapefiles_idusuario"><?php echo $shapefiles->idusuario->FldCaption() ?></span></td>
		<td data-name="idusuario"<?php echo $shapefiles->idusuario->CellAttributes() ?>>
<span id="el_shapefiles_idusuario" data-page="1">
<span<?php echo $shapefiles->idusuario->ViewAttributes() ?>>
<?php echo $shapefiles->idusuario->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($shapefiles->tipo->Visible) { // tipo ?>
	<tr id="r_tipo">
		<td><span id="elh_shapefiles_tipo"><?php echo $shapefiles->tipo->FldCaption() ?></span></td>
		<td data-name="tipo"<?php echo $shapefiles->tipo->CellAttributes() ?>>
<span id="el_shapefiles_tipo" data-page="1">
<span<?php echo $shapefiles->tipo->ViewAttributes() ?>>
<?php echo $shapefiles->tipo->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($shapefiles->folder->Visible) { // folder ?>
	<tr id="r_folder">
		<td><span id="elh_shapefiles_folder"><?php echo $shapefiles->folder->FldCaption() ?></span></td>
		<td data-name="folder"<?php echo $shapefiles->folder->CellAttributes() ?>>
<span id="el_shapefiles_folder" data-page="1">
<span<?php echo $shapefiles->folder->ViewAttributes() ?>>
<?php echo $shapefiles->folder->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($shapefiles->fechacreacion->Visible) { // fechacreacion ?>
	<tr id="r_fechacreacion">
		<td><span id="elh_shapefiles_fechacreacion"><?php echo $shapefiles->fechacreacion->FldCaption() ?></span></td>
		<td data-name="fechacreacion"<?php echo $shapefiles->fechacreacion->CellAttributes() ?>>
<span id="el_shapefiles_fechacreacion" data-page="1">
<span<?php echo $shapefiles->fechacreacion->ViewAttributes() ?>>
<?php echo $shapefiles->fechacreacion->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($shapefiles->tamano->Visible) { // tamano ?>
	<tr id="r_tamano">
		<td><span id="elh_shapefiles_tamano"><?php echo $shapefiles->tamano->FldCaption() ?></span></td>
		<td data-name="tamano"<?php echo $shapefiles->tamano->CellAttributes() ?>>
<span id="el_shapefiles_tamano" data-page="1">
<span<?php echo $shapefiles->tamano->ViewAttributes() ?>>
<?php echo $shapefiles->tamano->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($shapefiles->srid->Visible) { // srid ?>
	<tr id="r_srid">
		<td><span id="elh_shapefiles_srid"><?php echo $shapefiles->srid->FldCaption() ?></span></td>
		<td data-name="srid"<?php echo $shapefiles->srid->CellAttributes() ?>>
<span id="el_shapefiles_srid" data-page="1">
<span<?php echo $shapefiles->srid->ViewAttributes() ?>>
<?php echo $shapefiles->srid->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($shapefiles->acciones->Visible) { // acciones ?>
	<tr id="r_acciones">
		<td><span id="elh_shapefiles_acciones"><?php echo $shapefiles->acciones->FldCaption() ?></span></td>
		<td data-name="acciones"<?php echo $shapefiles->acciones->CellAttributes() ?>>
<span id="el_shapefiles_acciones" data-page="1">
<span<?php echo $shapefiles->acciones->ViewAttributes() ?>>
<?php echo $shapefiles->acciones->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
<?php if ($shapefiles->Export == "") { ?>
		</div>
<?php } ?>
<?php if ($shapefiles->Export == "") { ?>
		<div class="tab-pane<?php echo $shapefiles_view->MultiPages->PageStyle("2") ?>" id="tab_shapefiles2">
<?php } ?>
<table class="table table-bordered table-striped ewViewTable">
<?php if ($shapefiles->tipogeom->Visible) { // tipogeom ?>
	<tr id="r_tipogeom">
		<td><span id="elh_shapefiles_tipogeom"><?php echo $shapefiles->tipogeom->FldCaption() ?></span></td>
		<td data-name="tipogeom"<?php echo $shapefiles->tipogeom->CellAttributes() ?>>
<span id="el_shapefiles_tipogeom" data-page="2">
<span<?php echo $shapefiles->tipogeom->ViewAttributes() ?>>
<?php echo $shapefiles->tipogeom->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
<?php if ($shapefiles->Export == "") { ?>
		</div>
<?php } ?>
<?php if ($shapefiles->Export == "") { ?>
	</div>
</div>
</div>
<?php } ?>
</form>
<script type="text/javascript">
fshapefilesview.Init();
</script>
<?php
$shapefiles_view->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

var cloudURL = 'https://erikfva.github.io/geosicobView/';
window.onload = loadOpenlayersScript(function(){
	if(typeof ol === 'undefined') return; //no se ha cargado openlayers
	$.getScript( cloudURL + "src/geoutils.js" )
	.done(function( script, textStatus ) {
		InitMap();
	})
	.fail(function( jqxhr, settings, exception ) {
		console.log('No se pudo cargar la libreria geoSICOB de utilidades Openlayers (geoutils.js).');
	});	
});

function InitMap(){
	if(!$('#map').is(':empty')){//ya se ha inicializado openlayers
		if($('#map').css('visibility') == 'hidden'){ 
			$('#map').prependTo('.tab-pane:last').css('visibility','visible');
		}
		 return; 
	}
	var centerMap = ol.proj.transform([-61.3, -16.80], 'EPSG:4326', 'EPSG:3857'); //+/- en SCZ
	window.map = new ol.Map({ //<= Importante declarar "map" como variable global!!!
  	layers: [], //baseMaps,
  	target: 'map',
  	loadTilesWhileInteracting: true,
  	view: new ol.View({
		center: centerMap,
		zoom: 6
  	})
	});
	$('<div id="base"></div>')
	.appendTo('#tab_shapefiles2');
	$.when(
		$.getScript(cloudURL + 'src/mapas_base/mapas_base.js')
	).then(function () {
  		renderMapasBase('base');
  		resizeIFRM();
  	});
<?php

	/* Configurando conexion a geodatabase */
	$infoDb = Db();
	$db = pg_connect(
		"host=".$infoDb["host"].
		" port=".$infoDb["port"].
		" dbname=".$infoDb["db"].
		" user=".$infoDb["user"].
		" password=".$infoDb["pass"]
	);

	/*Preparando parametros para MapServer */
	unset($params);
	$params["token"] = CurrentPage()->token->ViewValue;
	$params["table"] = "uploads.".$params["token"];
	$params["map_projection"] = "init=epsg:3857";
	$params["LAYERS"] =  "poligono";
	$params["TILED"] =  "true";
	$params["STYLES"] =  "";
	$query = "select count(sicob_id) as rcount FROM ".$params["table"];
	$result = pg_query($db,$query); 
	if(pg_fetch_result($result, 0, 'rcount') > 50){	// <- Utilizar MapServer 

  	/* Obteniendo las cordenadas del cuadrado que enmarca al poligono para realizar el "zoomTo" */
  	$query = "SELECT replace(btrim(ST_Extent(the_geom_webmercator)::text, 'BOX()'),' ',',')
  			as extent from ".$params["table"] ; 
  	$result = pg_query($db,$query); 
  	$zoomExt = pg_fetch_result($result, 0, 'extent');
  	$addlayerJS = "

  /* Adicionando la capa del poligono utilizando MapServer */ 
  	var lyr_poligono = new ol.layer.Tile({
  		opacity: 1.0,
  		timeInfo: null,
  		source: new ol.source.TileWMS({
  			url: '".MSRV_URL."/gspoly',
  			params: ".json_encode($params).",
  			serverType: 'mapserver'
  		}),
  		title: 'poligonoWMS'
  	});
  	lyr_poligono.setVisible(true);
  	map.addLayer(lyr_poligono); 
  	var extent = [$zoomExt];        
   	map.getView().fit(extent, map.getSize());   
   	";
  }	else {	// <- Utilizar GeoJSON 

  	/* Obteniendo el geojson del poligono */ 	
  	$query = "SELECT 
	sicob_to_geojson('{\"lyr_in\":\"".$params["table"]."\"}') AS geojson"; 
  	$result = pg_query($db,$query);  
  	$geojson = pg_fetch_result($result, 0, 'geojson');  
  	$addlayerJS = "

  /* Adicionando la capa del poligono utilizando geoJSON */ 
  	map.addGeojsonLayer({
  						id: '".$params['table']."', 
  						geojson : '$geojson', 
  						title : 'Poligono geoSICOB', 
  						type : 'geosicob', 
  						geosicobStyle : {text:{color:'green',size:12},border:{color:'green'},fill:{color:'green'}}
  	});	
  	";
  }
  echo $addlayerJS;
?>
}
$('body').append('<div style="visibility: hidden;" id="map" class="map"></div>');
$('a[data-toggle="tab"]:last').on('shown.bs.tab', function (e) {
  InitMap();
  resizeIFRM();
  $('#map canvas').height($('#map canvas').height()); //FIX openlayer grow 5px
})
</script>
<?php include_once "footer.php" ?>
<?php
$shapefiles_view->Page_Terminate();
?>
