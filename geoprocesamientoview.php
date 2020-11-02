<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg12.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql12.php") ?>
<?php include_once "phpfn12.php" ?>
<?php include_once "geoprocesamientoinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn12.php" ?>
<?php

//
// Page class
//

$geoprocesamiento_view = NULL; // Initialize page object first

class cgeoprocesamiento_view extends cgeoprocesamiento {

	// Page ID
	var $PageID = 'view';

	// Project ID
	var $ProjectID = "{00441056-EF9D-4233-BDD9-EE81681FA399}";

	// Table name
	var $TableName = 'geoprocesamiento';

	// Page object name
	var $PageObjName = 'geoprocesamiento_view';

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

		// Table object (geoprocesamiento)
		if (!isset($GLOBALS["geoprocesamiento"]) || get_class($GLOBALS["geoprocesamiento"]) == "cgeoprocesamiento") {
			$GLOBALS["geoprocesamiento"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["geoprocesamiento"];
		}
		$KeyUrl = "";
		if (@$_GET["idgeoproceso"] <> "") {
			$this->RecKey["idgeoproceso"] = $_GET["idgeoproceso"];
			$KeyUrl .= "&amp;idgeoproceso=" . urlencode($this->RecKey["idgeoproceso"]);
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
			define("EW_TABLE_NAME", 'geoprocesamiento', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("geoprocesamientolist.php"));
			else
				$this->Page_Terminate(ew_GetUrl("login.php"));
		}
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->idgeoproceso->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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
		global $EW_EXPORT, $geoprocesamiento;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($geoprocesamiento);
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
			if (@$_GET["idgeoproceso"] <> "") {
				$this->idgeoproceso->setQueryStringValue($_GET["idgeoproceso"]);
				$this->RecKey["idgeoproceso"] = $this->idgeoproceso->QueryStringValue;
			} elseif (@$_POST["idgeoproceso"] <> "") {
				$this->idgeoproceso->setFormValue($_POST["idgeoproceso"]);
				$this->RecKey["idgeoproceso"] = $this->idgeoproceso->FormValue;
			} else {
				$sReturnUrl = "geoprocesamientolist.php"; // Return to list
			}

			// Get action
			$this->CurrentAction = "I"; // Display form
			switch ($this->CurrentAction) {
				case "I": // Get a record to display
					if (!$this->LoadRow()) { // Load record based on key
						if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
							$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
						$sReturnUrl = "geoprocesamientolist.php"; // No matching record, return to list
					}
			}
		} else {
			$sReturnUrl = "geoprocesamientolist.php"; // Not page request, return to list
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

		// Copy
		$item = &$option->Add("copy");
		$item->Body = "<a class=\"ewAction ewCopy\" title=\"" . ew_HtmlTitle($Language->Phrase("ViewPageCopyLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("ViewPageCopyLink")) . "\" href=\"" . ew_HtmlEncode($this->CopyUrl) . "\">" . $Language->Phrase("ViewPageCopyLink") . "</a>";
		$item->Visible = ($this->CopyUrl <> "" && $Security->CanAdd());

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
		$this->idgeoproceso->setDbValue($rs->fields('idgeoproceso'));
		$this->idusuario->setDbValue($rs->fields('idusuario'));
		$this->proceso->setDbValue($rs->fields('proceso'));
		$this->inicio->setDbValue($rs->fields('inicio'));
		$this->fin->setDbValue($rs->fields('fin'));
		$this->entradatxt->setDbValue($rs->fields('entradatxt'));
		$this->salidatxt->setDbValue($rs->fields('salidatxt'));
		$this->opcionestxt->setDbValue($rs->fields('opcionestxt'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->idgeoproceso->DbValue = $row['idgeoproceso'];
		$this->idusuario->DbValue = $row['idusuario'];
		$this->proceso->DbValue = $row['proceso'];
		$this->inicio->DbValue = $row['inicio'];
		$this->fin->DbValue = $row['fin'];
		$this->entradatxt->DbValue = $row['entradatxt'];
		$this->salidatxt->DbValue = $row['salidatxt'];
		$this->opcionestxt->DbValue = $row['opcionestxt'];
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
		// idgeoproceso
		// idusuario
		// proceso
		// inicio
		// fin
		// entradatxt
		// salidatxt
		// opcionestxt

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// idgeoproceso
		$this->idgeoproceso->ViewValue = $this->idgeoproceso->CurrentValue;
		$this->idgeoproceso->ViewCustomAttributes = ["style" => "text-transform: none;"];

		// idusuario
		$this->idusuario->ViewValue = $this->idusuario->CurrentValue;
		if (strval($this->idusuario->CurrentValue) <> "") {
			$sFilterWrk = "\"idusuario\"" . ew_SearchString("=", $this->idusuario->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT \"idusuario\", \"nombre\" AS \"DispFld\", '' AS \"Disp2Fld\", '' AS \"Disp3Fld\", '' AS \"Disp4Fld\" FROM \"registro_derecho\".\"usuario\"";
		$sWhereWrk = "";
		$lookuptblfilter = "\"idusuario\" = ".CurrentUserInfo("idusuario");
		ew_AddFilter($sWhereWrk, $lookuptblfilter);
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->idusuario, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
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

		// proceso
		if (strval($this->proceso->CurrentValue) <> "") {
			$sFilterWrk = "\"idaccion\"" . ew_SearchString("=", $this->proceso->CurrentValue, EW_DATATYPE_STRING, "");
		$sSqlWrk = "SELECT \"idaccion\", \"idaccion\" AS \"DispFld\", '' AS \"Disp2Fld\", '' AS \"Disp3Fld\", '' AS \"Disp4Fld\" FROM \"registro_derecho\".\"appacciones\"";
		$sWhereWrk = "";
		$lookuptblfilter = "contexto = 'geoprocesamiento'";
		ew_AddFilter($sWhereWrk, $lookuptblfilter);
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->proceso, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->proceso->ViewValue = $this->proceso->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->proceso->ViewValue = $this->proceso->CurrentValue;
			}
		} else {
			$this->proceso->ViewValue = NULL;
		}
		$this->proceso->ViewCustomAttributes = ["style" => "text-transform: none;"];

		// inicio
		$this->inicio->ViewValue = $this->inicio->CurrentValue;
		$this->inicio->ViewCustomAttributes = "";

		// fin
		$this->fin->ViewValue = $this->fin->CurrentValue;
		$this->fin->ViewCustomAttributes = "";

		// entradatxt
		$this->entradatxt->ViewValue = $this->entradatxt->CurrentValue;
		$this->entradatxt->ViewCustomAttributes = ["style" => "text-transform: none;"];

		// salidatxt
		$this->salidatxt->ViewValue = $this->salidatxt->CurrentValue;
		$this->salidatxt->ViewCustomAttributes = ["style" => "text-transform: none;"];

		// opcionestxt
		$this->opcionestxt->ViewValue = $this->opcionestxt->CurrentValue;
		$this->opcionestxt->ViewCustomAttributes = ["style" => "text-transform: none;"];

			// idgeoproceso
			$this->idgeoproceso->LinkCustomAttributes = "";
			$this->idgeoproceso->HrefValue = "";
			$this->idgeoproceso->TooltipValue = "";

			// idusuario
			$this->idusuario->LinkCustomAttributes = "";
			$this->idusuario->HrefValue = "";
			$this->idusuario->TooltipValue = "";

			// proceso
			$this->proceso->LinkCustomAttributes = "";
			$this->proceso->HrefValue = "";
			$this->proceso->TooltipValue = "";

			// inicio
			$this->inicio->LinkCustomAttributes = "";
			$this->inicio->HrefValue = "";
			$this->inicio->TooltipValue = "";

			// fin
			$this->fin->LinkCustomAttributes = "";
			$this->fin->HrefValue = "";
			$this->fin->TooltipValue = "";

			// entradatxt
			$this->entradatxt->LinkCustomAttributes = "";
			$this->entradatxt->HrefValue = "";
			$this->entradatxt->TooltipValue = "";

			// salidatxt
			$this->salidatxt->LinkCustomAttributes = "";
			$this->salidatxt->HrefValue = "";
			$this->salidatxt->TooltipValue = "";

			// opcionestxt
			$this->opcionestxt->LinkCustomAttributes = "";
			$this->opcionestxt->HrefValue = "";
			$this->opcionestxt->TooltipValue = "";
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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("geoprocesamientolist.php"), "", $this->TableVar, TRUE);
		$PageId = "view";
		$Breadcrumb->Add("view", $PageId, $url);
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
if (!isset($geoprocesamiento_view)) $geoprocesamiento_view = new cgeoprocesamiento_view();

// Page init
$geoprocesamiento_view->Page_Init();

// Page main
$geoprocesamiento_view->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$geoprocesamiento_view->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "view";
var CurrentForm = fgeoprocesamientoview = new ew_Form("fgeoprocesamientoview", "view");

// Form_CustomValidate event
fgeoprocesamientoview.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fgeoprocesamientoview.ValidateRequired = true;
<?php } else { ?>
fgeoprocesamientoview.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fgeoprocesamientoview.Lists["x_idusuario"] = {"LinkField":"x_idusuario","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fgeoprocesamientoview.Lists["x_proceso"] = {"LinkField":"x_idaccion","Ajax":true,"AutoFill":false,"DisplayFields":["x_idaccion","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php $geoprocesamiento_view->ExportOptions->Render("body") ?>
<?php
	foreach ($geoprocesamiento_view->OtherOptions as &$option)
		$option->Render("body");
?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $geoprocesamiento_view->ShowPageHeader(); ?>
<?php
$geoprocesamiento_view->ShowMessage();
?>
<form name="fgeoprocesamientoview" id="fgeoprocesamientoview" class="form-inline ewForm ewViewForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($geoprocesamiento_view->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $geoprocesamiento_view->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="geoprocesamiento">
<table class="table table-bordered table-striped ewViewTable">
<?php if ($geoprocesamiento->idgeoproceso->Visible) { // idgeoproceso ?>
	<tr id="r_idgeoproceso">
		<td><span id="elh_geoprocesamiento_idgeoproceso"><?php echo $geoprocesamiento->idgeoproceso->FldCaption() ?></span></td>
		<td data-name="idgeoproceso"<?php echo $geoprocesamiento->idgeoproceso->CellAttributes() ?>>
<span id="el_geoprocesamiento_idgeoproceso">
<span<?php echo $geoprocesamiento->idgeoproceso->ViewAttributes() ?>>
<?php echo $geoprocesamiento->idgeoproceso->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($geoprocesamiento->idusuario->Visible) { // idusuario ?>
	<tr id="r_idusuario">
		<td><span id="elh_geoprocesamiento_idusuario"><?php echo $geoprocesamiento->idusuario->FldCaption() ?></span></td>
		<td data-name="idusuario"<?php echo $geoprocesamiento->idusuario->CellAttributes() ?>>
<span id="el_geoprocesamiento_idusuario">
<span<?php echo $geoprocesamiento->idusuario->ViewAttributes() ?>>
<?php echo $geoprocesamiento->idusuario->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($geoprocesamiento->proceso->Visible) { // proceso ?>
	<tr id="r_proceso">
		<td><span id="elh_geoprocesamiento_proceso"><?php echo $geoprocesamiento->proceso->FldCaption() ?></span></td>
		<td data-name="proceso"<?php echo $geoprocesamiento->proceso->CellAttributes() ?>>
<span id="el_geoprocesamiento_proceso">
<span<?php echo $geoprocesamiento->proceso->ViewAttributes() ?>>
<?php echo $geoprocesamiento->proceso->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($geoprocesamiento->inicio->Visible) { // inicio ?>
	<tr id="r_inicio">
		<td><span id="elh_geoprocesamiento_inicio"><?php echo $geoprocesamiento->inicio->FldCaption() ?></span></td>
		<td data-name="inicio"<?php echo $geoprocesamiento->inicio->CellAttributes() ?>>
<span id="el_geoprocesamiento_inicio">
<span<?php echo $geoprocesamiento->inicio->ViewAttributes() ?>>
<?php echo $geoprocesamiento->inicio->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($geoprocesamiento->fin->Visible) { // fin ?>
	<tr id="r_fin">
		<td><span id="elh_geoprocesamiento_fin"><?php echo $geoprocesamiento->fin->FldCaption() ?></span></td>
		<td data-name="fin"<?php echo $geoprocesamiento->fin->CellAttributes() ?>>
<span id="el_geoprocesamiento_fin">
<span<?php echo $geoprocesamiento->fin->ViewAttributes() ?>>
<?php echo $geoprocesamiento->fin->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($geoprocesamiento->entradatxt->Visible) { // entradatxt ?>
	<tr id="r_entradatxt">
		<td><span id="elh_geoprocesamiento_entradatxt"><?php echo $geoprocesamiento->entradatxt->FldCaption() ?></span></td>
		<td data-name="entradatxt"<?php echo $geoprocesamiento->entradatxt->CellAttributes() ?>>
<span id="el_geoprocesamiento_entradatxt">
<span<?php echo $geoprocesamiento->entradatxt->ViewAttributes() ?>>
<?php echo $geoprocesamiento->entradatxt->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($geoprocesamiento->salidatxt->Visible) { // salidatxt ?>
	<tr id="r_salidatxt">
		<td><span id="elh_geoprocesamiento_salidatxt"><?php echo $geoprocesamiento->salidatxt->FldCaption() ?></span></td>
		<td data-name="salidatxt"<?php echo $geoprocesamiento->salidatxt->CellAttributes() ?>>
<span id="el_geoprocesamiento_salidatxt">
<span<?php echo $geoprocesamiento->salidatxt->ViewAttributes() ?>>
<?php echo $geoprocesamiento->salidatxt->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($geoprocesamiento->opcionestxt->Visible) { // opcionestxt ?>
	<tr id="r_opcionestxt">
		<td><span id="elh_geoprocesamiento_opcionestxt"><?php echo $geoprocesamiento->opcionestxt->FldCaption() ?></span></td>
		<td data-name="opcionestxt"<?php echo $geoprocesamiento->opcionestxt->CellAttributes() ?>>
<span id="el_geoprocesamiento_opcionestxt">
<span<?php echo $geoprocesamiento->opcionestxt->ViewAttributes() ?>>
<?php echo $geoprocesamiento->opcionestxt->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
</form>
<script type="text/javascript">
fgeoprocesamientoview.Init();
</script>
<?php
$geoprocesamiento_view->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$geoprocesamiento_view->Page_Terminate();
?>
