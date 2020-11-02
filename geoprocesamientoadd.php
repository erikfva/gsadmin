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

$geoprocesamiento_add = NULL; // Initialize page object first

class cgeoprocesamiento_add extends cgeoprocesamiento {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{00441056-EF9D-4233-BDD9-EE81681FA399}";

	// Table name
	var $TableName = 'geoprocesamiento';

	// Page object name
	var $PageObjName = 'geoprocesamiento_add';

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

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

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
		if (!$Security->CanAdd()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage($Language->Phrase("NoPermission")); // Set no permission
			if ($Security->CanList())
				$this->Page_Terminate(ew_GetUrl("geoprocesamientolist.php"));
			else
				$this->Page_Terminate(ew_GetUrl("login.php"));
		}

		// Create form object
		$objForm = new cFormObj();
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action

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

		// Process auto fill
		if (@$_POST["ajax"] == "autofill") {
			$results = $this->GetAutoFill(@$_POST["name"], @$_POST["q"]);
			if ($results) {

				// Clean output buffer
				if (!EW_DEBUG_ENABLED && ob_get_length())
					ob_end_clean();
				echo $results;
				$this->Page_Terminate();
				exit();
			}
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
	var $FormClassName = "form-horizontal ewForm ewAddForm";
	var $DbMasterFilter = "";
	var $DbDetailFilter = "";
	var $StartRec;
	var $Priv = 0;
	var $OldRecordset;
	var $CopyRecord;

	// 
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;

		// Process form if post back
		if (@$_POST["a_add"] <> "") {
			$this->CurrentAction = $_POST["a_add"]; // Get form action
			$this->CopyRecord = $this->LoadOldRecord(); // Load old recordset
			$this->LoadFormValues(); // Load form values
		} else { // Not post back

			// Load key values from QueryString
			$this->CopyRecord = TRUE;
			if (@$_GET["idgeoproceso"] != "") {
				$this->idgeoproceso->setQueryStringValue($_GET["idgeoproceso"]);
				$this->setKey("idgeoproceso", $this->idgeoproceso->CurrentValue); // Set up key
			} else {
				$this->setKey("idgeoproceso", ""); // Clear key
				$this->CopyRecord = FALSE;
			}
			if ($this->CopyRecord) {
				$this->CurrentAction = "C"; // Copy record
			} else {
				$this->CurrentAction = "I"; // Display blank record
			}
		}

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Validate form if post back
		if (@$_POST["a_add"] <> "") {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = "I"; // Form error, reset action
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues(); // Restore form values
				$this->setFailureMessage($gsFormError);
			}
		} else {
			if ($this->CurrentAction == "I") // Load default values for blank record
				$this->LoadDefaultValues();
		}

		// Perform action based on action code
		switch ($this->CurrentAction) {
			case "I": // Blank record, no action required
				break;
			case "C": // Copy an existing record
				if (!$this->LoadRow()) { // Load record based on key
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("geoprocesamientolist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "geoprocesamientolist.php")
						$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to list page with correct master key if necessary
					elseif (ew_GetPageName($sReturnUrl) == "geoprocesamientoview.php")
						$sReturnUrl = $this->GetViewUrl(); // View page, return to view page with keyurl directly
					$this->Page_Terminate($sReturnUrl); // Clean up and return
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Add failed, restore form values
				}
		}

		// Render row based on row type
		$this->RowType = EW_ROWTYPE_ADD; // Render add type

		// Render row
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Get upload files
	function GetUploadFiles() {
		global $objForm, $Language;

		// Get upload data
	}

	// Load default values
	function LoadDefaultValues() {
		$this->idusuario->CurrentValue = CurrentUserInfo('idusuario');
		$this->proceso->CurrentValue = NULL;
		$this->proceso->OldValue = $this->proceso->CurrentValue;
		$this->entradatxt->CurrentValue = NULL;
		$this->entradatxt->OldValue = $this->entradatxt->CurrentValue;
		$this->opcionestxt->CurrentValue = NULL;
		$this->opcionestxt->OldValue = $this->opcionestxt->CurrentValue;
		$this->geojson->CurrentValue = NULL;
		$this->geojson->OldValue = $this->geojson->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->idusuario->FldIsDetailKey) {
			$this->idusuario->setFormValue($objForm->GetValue("x_idusuario"));
		}
		if (!$this->proceso->FldIsDetailKey) {
			$this->proceso->setFormValue($objForm->GetValue("x_proceso"));
		}
		if (!$this->entradatxt->FldIsDetailKey) {
			$this->entradatxt->setFormValue($objForm->GetValue("x_entradatxt"));
		}
		if (!$this->opcionestxt->FldIsDetailKey) {
			$this->opcionestxt->setFormValue($objForm->GetValue("x_opcionestxt"));
		}
		if (!$this->geojson->FldIsDetailKey) {
			$this->geojson->setFormValue($objForm->GetValue("x_geojson"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->idusuario->CurrentValue = $this->idusuario->FormValue;
		$this->proceso->CurrentValue = $this->proceso->FormValue;
		$this->entradatxt->CurrentValue = $this->entradatxt->FormValue;
		$this->opcionestxt->CurrentValue = $this->opcionestxt->FormValue;
		$this->geojson->CurrentValue = $this->geojson->FormValue;
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
		$this->salidatrunc->setDbValue($rs->fields('salidatrunc'));
		$this->opcionestxt->setDbValue($rs->fields('opcionestxt'));
		$this->geojson->setDbValue($rs->fields('geojson'));
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
		$this->salidatrunc->DbValue = $row['salidatrunc'];
		$this->opcionestxt->DbValue = $row['opcionestxt'];
		$this->geojson->DbValue = $row['geojson'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("idgeoproceso")) <> "")
			$this->idgeoproceso->CurrentValue = $this->getKey("idgeoproceso"); // idgeoproceso
		else
			$bValidKey = FALSE;

		// Load old recordset
		if ($bValidKey) {
			$this->CurrentFilter = $this->KeyFilter();
			$sSql = $this->SQL();
			$conn = &$this->Connection();
			$this->OldRecordset = ew_LoadRecordset($sSql, $conn);
			$this->LoadRowValues($this->OldRecordset); // Load row values
		} else {
			$this->OldRecordset = NULL;
		}
		return $bValidKey;
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
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
		// salidatrunc
		// opcionestxt
		// geojson

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

		// geojson
		$this->geojson->ViewValue = $this->geojson->CurrentValue;
		$this->geojson->ViewCustomAttributes = "";

			// idusuario
			$this->idusuario->LinkCustomAttributes = "";
			$this->idusuario->HrefValue = "";
			$this->idusuario->TooltipValue = "";

			// proceso
			$this->proceso->LinkCustomAttributes = "";
			$this->proceso->HrefValue = "";
			$this->proceso->TooltipValue = "";

			// entradatxt
			$this->entradatxt->LinkCustomAttributes = "";
			$this->entradatxt->HrefValue = "";
			$this->entradatxt->TooltipValue = "";

			// opcionestxt
			$this->opcionestxt->LinkCustomAttributes = "";
			$this->opcionestxt->HrefValue = "";
			$this->opcionestxt->TooltipValue = "";

			// geojson
			$this->geojson->LinkCustomAttributes = "";
			$this->geojson->HrefValue = "";
			$this->geojson->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// idusuario
			$this->idusuario->EditAttrs["class"] = "form-control";
			$this->idusuario->EditCustomAttributes = "";
			$this->idusuario->EditValue = ew_HtmlEncode($this->idusuario->CurrentValue);
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
					$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
					$this->idusuario->EditValue = $this->idusuario->DisplayValue($arwrk);
					$rswrk->Close();
				} else {
					$this->idusuario->EditValue = ew_HtmlEncode($this->idusuario->CurrentValue);
				}
			} else {
				$this->idusuario->EditValue = NULL;
			}
			$this->idusuario->PlaceHolder = ew_RemoveHtml($this->idusuario->FldCaption());

			// proceso
			$this->proceso->EditAttrs["class"] = "form-control";
			$this->proceso->EditCustomAttributes = "";
			if (trim(strval($this->proceso->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "\"idaccion\"" . ew_SearchString("=", $this->proceso->CurrentValue, EW_DATATYPE_STRING, "");
			}
			$sSqlWrk = "SELECT \"idaccion\", \"idaccion\" AS \"DispFld\", '' AS \"Disp2Fld\", '' AS \"Disp3Fld\", '' AS \"Disp4Fld\", '' AS \"SelectFilterFld\", '' AS \"SelectFilterFld2\", '' AS \"SelectFilterFld3\", '' AS \"SelectFilterFld4\" FROM \"registro_derecho\".\"appacciones\"";
			$sWhereWrk = "";
			$lookuptblfilter = "contexto = 'geoprocesamiento'";
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->proceso, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->proceso->EditValue = $arwrk;

			// entradatxt
			$this->entradatxt->EditAttrs["class"] = "form-control";
			$this->entradatxt->EditCustomAttributes = "";
			$this->entradatxt->EditValue = ew_HtmlEncode($this->entradatxt->CurrentValue);
			$this->entradatxt->PlaceHolder = ew_RemoveHtml($this->entradatxt->FldCaption());

			// opcionestxt
			$this->opcionestxt->EditAttrs["class"] = "form-control";
			$this->opcionestxt->EditCustomAttributes = "";
			$this->opcionestxt->EditValue = ew_HtmlEncode($this->opcionestxt->CurrentValue);
			$this->opcionestxt->PlaceHolder = ew_RemoveHtml($this->opcionestxt->FldCaption());

			// geojson
			$this->geojson->EditAttrs["class"] = "form-control";
			$this->geojson->EditCustomAttributes = "";
			$this->geojson->EditValue = ew_HtmlEncode($this->geojson->CurrentValue);
			$this->geojson->PlaceHolder = ew_RemoveHtml($this->geojson->FldCaption());

			// Add refer script
			// idusuario

			$this->idusuario->LinkCustomAttributes = "";
			$this->idusuario->HrefValue = "";

			// proceso
			$this->proceso->LinkCustomAttributes = "";
			$this->proceso->HrefValue = "";

			// entradatxt
			$this->entradatxt->LinkCustomAttributes = "";
			$this->entradatxt->HrefValue = "";

			// opcionestxt
			$this->opcionestxt->LinkCustomAttributes = "";
			$this->opcionestxt->HrefValue = "";

			// geojson
			$this->geojson->LinkCustomAttributes = "";
			$this->geojson->HrefValue = "";
		}
		if ($this->RowType == EW_ROWTYPE_ADD ||
			$this->RowType == EW_ROWTYPE_EDIT ||
			$this->RowType == EW_ROWTYPE_SEARCH) { // Add / Edit / Search row
			$this->SetupFieldTitles();
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Validate form
	function ValidateForm() {
		global $Language, $gsFormError;

		// Initialize form error message
		$gsFormError = "";

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return ($gsFormError == "");
		if (!$this->idusuario->FldIsDetailKey && !is_null($this->idusuario->FormValue) && $this->idusuario->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->idusuario->FldCaption(), $this->idusuario->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->idusuario->FormValue)) {
			ew_AddMessage($gsFormError, $this->idusuario->FldErrMsg());
		}
		if (!$this->proceso->FldIsDetailKey && !is_null($this->proceso->FormValue) && $this->proceso->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->proceso->FldCaption(), $this->proceso->ReqErrMsg));
		}

		// Return validate result
		$ValidateForm = ($gsFormError == "");

		// Call Form_CustomValidate event
		$sFormCustomError = "";
		$ValidateForm = $ValidateForm && $this->Form_CustomValidate($sFormCustomError);
		if ($sFormCustomError <> "") {
			ew_AddMessage($gsFormError, $sFormCustomError);
		}
		return $ValidateForm;
	}

	// Add record
	function AddRow($rsold = NULL) {
		global $Language, $Security;
		$conn = &$this->Connection();

		// Load db values from rsold
		if ($rsold) {
			$this->LoadDbValues($rsold);
		}
		$rsnew = array();

		// idusuario
		$this->idusuario->SetDbValueDef($rsnew, $this->idusuario->CurrentValue, 0, FALSE);

		// proceso
		$this->proceso->SetDbValueDef($rsnew, $this->proceso->CurrentValue, "", FALSE);

		// entradatxt
		$this->entradatxt->SetDbValueDef($rsnew, $this->entradatxt->CurrentValue, NULL, FALSE);

		// opcionestxt
		$this->opcionestxt->SetDbValueDef($rsnew, $this->opcionestxt->CurrentValue, NULL, FALSE);

		// geojson
		$this->geojson->SetDbValueDef($rsnew, $this->geojson->CurrentValue, NULL, FALSE);

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);
		if ($bInsertRow) {
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			$AddRow = $this->Insert($rsnew);
			$conn->raiseErrorFn = '';
			if ($AddRow) {

				// Get insert id if necessary
				$this->idgeoproceso->setDbValue($conn->GetOne("SELECT currval('geoprocesamiento_idgeoproceso_seq'::regclass)"));
				$rsnew['idgeoproceso'] = $this->idgeoproceso->DbValue;
			}
		} else {
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("InsertCancelled"));
			}
			$AddRow = FALSE;
		}
		if ($AddRow) {

			// Call Row Inserted event
			$rs = ($rsold == NULL) ? NULL : $rsold->fields;
			$this->Row_Inserted($rs, $rsnew);
		}
		return $AddRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("geoprocesamientolist.php"), "", $this->TableVar, TRUE);
		$PageId = ($this->CurrentAction == "C") ? "Copy" : "Add";
		$Breadcrumb->Add("add", $PageId, $url);
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
		 $this->idusuario->Disabled = TRUE;
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

	// Form Custom Validate event
	function Form_CustomValidate(&$CustomError) {

		// Return error message in CustomError
		return TRUE;
	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($geoprocesamiento_add)) $geoprocesamiento_add = new cgeoprocesamiento_add();

// Page init
$geoprocesamiento_add->Page_Init();

// Page main
$geoprocesamiento_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$geoprocesamiento_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "add";
var CurrentForm = fgeoprocesamientoadd = new ew_Form("fgeoprocesamientoadd", "add");

// Validate form
fgeoprocesamientoadd.Validate = function() {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	var $ = jQuery, fobj = this.GetForm(), $fobj = $(fobj);
	if ($fobj.find("#a_confirm").val() == "F")
		return true;
	var elm, felm, uelm, addcnt = 0;
	var $k = $fobj.find("#" + this.FormKeyCountName); // Get key_count
	var rowcnt = ($k[0]) ? parseInt($k.val(), 10) : 1;
	var startcnt = (rowcnt == 0) ? 0 : 1; // Check rowcnt == 0 => Inline-Add
	var gridinsert = $fobj.find("#a_list").val() == "gridinsert";
	for (var i = startcnt; i <= rowcnt; i++) {
		var infix = ($k[0]) ? String(i) : "";
		$fobj.data("rowindex", infix);
			elm = this.GetElements("x" + infix + "_idusuario");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $geoprocesamiento->idusuario->FldCaption(), $geoprocesamiento->idusuario->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_idusuario");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($geoprocesamiento->idusuario->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_proceso");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $geoprocesamiento->proceso->FldCaption(), $geoprocesamiento->proceso->ReqErrMsg)) ?>");

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
	}

	// Process detail forms
	var dfs = $fobj.find("input[name='detailpage']").get();
	for (var i = 0; i < dfs.length; i++) {
		var df = dfs[i], val = df.value;
		if (val && ewForms[val])
			if (!ewForms[val].Validate())
				return false;
	}
	return true;
}

// Form_CustomValidate event
fgeoprocesamientoadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fgeoprocesamientoadd.ValidateRequired = true;
<?php } else { ?>
fgeoprocesamientoadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fgeoprocesamientoadd.Lists["x_idusuario"] = {"LinkField":"x_idusuario","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fgeoprocesamientoadd.Lists["x_proceso"] = {"LinkField":"x_idaccion","Ajax":true,"AutoFill":false,"DisplayFields":["x_idaccion","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $geoprocesamiento_add->ShowPageHeader(); ?>
<?php
$geoprocesamiento_add->ShowMessage();
?>
<form name="fgeoprocesamientoadd" id="fgeoprocesamientoadd" class="<?php echo $geoprocesamiento_add->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($geoprocesamiento_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $geoprocesamiento_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="geoprocesamiento">
<input type="hidden" name="a_add" id="a_add" value="A">
<div>
<?php if ($geoprocesamiento->idusuario->Visible) { // idusuario ?>
	<div id="r_idusuario" class="form-group">
		<label id="elh_geoprocesamiento_idusuario" class="col-sm-2 control-label ewLabel"><?php echo $geoprocesamiento->idusuario->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $geoprocesamiento->idusuario->CellAttributes() ?>>
<span id="el_geoprocesamiento_idusuario">
<?php
$wrkonchange = trim(" " . @$geoprocesamiento->idusuario->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$geoprocesamiento->idusuario->EditAttrs["onchange"] = "";
?>
<span id="as_x_idusuario" style="white-space: nowrap; z-index: 8980">
	<input type="text" name="sv_x_idusuario" id="sv_x_idusuario" value="<?php echo $geoprocesamiento->idusuario->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($geoprocesamiento->idusuario->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($geoprocesamiento->idusuario->getPlaceHolder()) ?>"<?php echo $geoprocesamiento->idusuario->EditAttributes() ?>>
</span>
<input type="hidden" data-table="geoprocesamiento" data-field="x_idusuario" data-value-separator="<?php echo ew_HtmlEncode(is_array($geoprocesamiento->idusuario->DisplayValueSeparator) ? json_encode($geoprocesamiento->idusuario->DisplayValueSeparator) : $geoprocesamiento->idusuario->DisplayValueSeparator) ?>" name="x_idusuario" id="x_idusuario" value="<?php echo ew_HtmlEncode($geoprocesamiento->idusuario->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<?php
$sSqlWrk = "SELECT \"idusuario\", \"nombre\" AS \"DispFld\" FROM \"registro_derecho\".\"usuario\"";
$sWhereWrk = "\"nombre\" LIKE '%{query_value}%'";
$lookuptblfilter = "\"idusuario\" = ".CurrentUserInfo("idusuario");
ew_AddFilter($sWhereWrk, $lookuptblfilter);
$geoprocesamiento->Lookup_Selecting($geoprocesamiento->idusuario, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " LIMIT " . EW_AUTO_SUGGEST_MAX_ENTRIES;
?>
<input type="hidden" name="q_x_idusuario" id="q_x_idusuario" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&d=">
<script type="text/javascript">
fgeoprocesamientoadd.CreateAutoSuggest({"id":"x_idusuario","forceSelect":false});
</script>
</span>
<?php echo $geoprocesamiento->idusuario->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($geoprocesamiento->proceso->Visible) { // proceso ?>
	<div id="r_proceso" class="form-group">
		<label id="elh_geoprocesamiento_proceso" for="x_proceso" class="col-sm-2 control-label ewLabel"><?php echo $geoprocesamiento->proceso->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $geoprocesamiento->proceso->CellAttributes() ?>>
<span id="el_geoprocesamiento_proceso">
<select data-table="geoprocesamiento" data-field="x_proceso" data-value-separator="<?php echo ew_HtmlEncode(is_array($geoprocesamiento->proceso->DisplayValueSeparator) ? json_encode($geoprocesamiento->proceso->DisplayValueSeparator) : $geoprocesamiento->proceso->DisplayValueSeparator) ?>" id="x_proceso" name="x_proceso"<?php echo $geoprocesamiento->proceso->EditAttributes() ?>>
<?php
if (is_array($geoprocesamiento->proceso->EditValue)) {
	$arwrk = $geoprocesamiento->proceso->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($geoprocesamiento->proceso->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $geoprocesamiento->proceso->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($geoprocesamiento->proceso->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($geoprocesamiento->proceso->CurrentValue) ?>" selected><?php echo $geoprocesamiento->proceso->CurrentValue ?></option>
<?php
    }
}
?>
</select>
<?php
$sSqlWrk = "SELECT \"idaccion\", \"idaccion\" AS \"DispFld\", '' AS \"Disp2Fld\", '' AS \"Disp3Fld\", '' AS \"Disp4Fld\" FROM \"registro_derecho\".\"appacciones\"";
$sWhereWrk = "";
$lookuptblfilter = "contexto = 'geoprocesamiento'";
ew_AddFilter($sWhereWrk, $lookuptblfilter);
$geoprocesamiento->proceso->LookupFilters = array("s" => $sSqlWrk, "d" => "");
$geoprocesamiento->proceso->LookupFilters += array("f0" => "\"idaccion\" = {filter_value}", "t0" => "200", "fn0" => "");
$sSqlWrk = "";
$geoprocesamiento->Lookup_Selecting($geoprocesamiento->proceso, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
if ($sSqlWrk <> "") $geoprocesamiento->proceso->LookupFilters["s"] .= $sSqlWrk;
?>
<input type="hidden" name="s_x_proceso" id="s_x_proceso" value="<?php echo $geoprocesamiento->proceso->LookupFilterQuery() ?>">
</span>
<?php echo $geoprocesamiento->proceso->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($geoprocesamiento->entradatxt->Visible) { // entradatxt ?>
	<div id="r_entradatxt" class="form-group">
		<label id="elh_geoprocesamiento_entradatxt" for="x_entradatxt" class="col-sm-2 control-label ewLabel"><?php echo $geoprocesamiento->entradatxt->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $geoprocesamiento->entradatxt->CellAttributes() ?>>
<span id="el_geoprocesamiento_entradatxt">
<textarea data-table="geoprocesamiento" data-field="x_entradatxt" name="x_entradatxt" id="x_entradatxt" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($geoprocesamiento->entradatxt->getPlaceHolder()) ?>"<?php echo $geoprocesamiento->entradatxt->EditAttributes() ?>><?php echo $geoprocesamiento->entradatxt->EditValue ?></textarea>
</span>
<?php echo $geoprocesamiento->entradatxt->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($geoprocesamiento->opcionestxt->Visible) { // opcionestxt ?>
	<div id="r_opcionestxt" class="form-group">
		<label id="elh_geoprocesamiento_opcionestxt" for="x_opcionestxt" class="col-sm-2 control-label ewLabel"><?php echo $geoprocesamiento->opcionestxt->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $geoprocesamiento->opcionestxt->CellAttributes() ?>>
<span id="el_geoprocesamiento_opcionestxt">
<textarea data-table="geoprocesamiento" data-field="x_opcionestxt" name="x_opcionestxt" id="x_opcionestxt" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($geoprocesamiento->opcionestxt->getPlaceHolder()) ?>"<?php echo $geoprocesamiento->opcionestxt->EditAttributes() ?>><?php echo $geoprocesamiento->opcionestxt->EditValue ?></textarea>
</span>
<?php echo $geoprocesamiento->opcionestxt->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($geoprocesamiento->geojson->Visible) { // geojson ?>
	<div id="r_geojson" class="form-group">
		<label id="elh_geoprocesamiento_geojson" for="x_geojson" class="col-sm-2 control-label ewLabel"><?php echo $geoprocesamiento->geojson->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $geoprocesamiento->geojson->CellAttributes() ?>>
<span id="el_geoprocesamiento_geojson">
<textarea data-table="geoprocesamiento" data-field="x_geojson" name="x_geojson" id="x_geojson" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($geoprocesamiento->geojson->getPlaceHolder()) ?>"<?php echo $geoprocesamiento->geojson->EditAttributes() ?>><?php echo $geoprocesamiento->geojson->EditValue ?></textarea>
</span>
<?php echo $geoprocesamiento->geojson->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $geoprocesamiento_add->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
</form>
<script type="text/javascript">
fgeoprocesamientoadd.Init();
</script>
<?php
$geoprocesamiento_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$geoprocesamiento_add->Page_Terminate();
?>
