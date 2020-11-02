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

$shapefiles_edit = NULL; // Initialize page object first

class cshapefiles_edit extends cshapefiles {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{00441056-EF9D-4233-BDD9-EE81681FA399}";

	// Table name
	var $TableName = 'shapefiles';

	// Page object name
	var $PageObjName = 'shapefiles_edit';

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

		// Table object (shapefiles)
		if (!isset($GLOBALS["shapefiles"]) || get_class($GLOBALS["shapefiles"]) == "cshapefiles") {
			$GLOBALS["shapefiles"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["shapefiles"];
		}

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

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
		if (!$Security->CanEdit()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage($Language->Phrase("NoPermission")); // Set no permission
			if ($Security->CanList())
				$this->Page_Terminate(ew_GetUrl("shapefileslist.php"));
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
	var $FormClassName = "form-horizontal ewForm ewEditForm";
	var $DbMasterFilter;
	var $DbDetailFilter;

	// 
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;

		// Load key from QueryString
		if (@$_GET["idshapefile"] <> "") {
			$this->idshapefile->setQueryStringValue($_GET["idshapefile"]);
		}

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Process form if post back
		if (@$_POST["a_edit"] <> "") {
			$this->CurrentAction = $_POST["a_edit"]; // Get action code
			$this->LoadFormValues(); // Get form values
		} else {
			$this->CurrentAction = "I"; // Default action is display
		}

		// Check if valid key
		if ($this->idshapefile->CurrentValue == "")
			$this->Page_Terminate("shapefileslist.php"); // Invalid key, return to list

		// Validate form if post back
		if (@$_POST["a_edit"] <> "") {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = ""; // Form error, reset action
				$this->setFailureMessage($gsFormError);
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues();
			}
		}
		switch ($this->CurrentAction) {
			case "I": // Get a record to display
				if (!$this->LoadRow()) { // Load record based on key
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("shapefileslist.php"); // No matching record, return to list
				}
				break;
			Case "U": // Update
				$sReturnUrl = $this->getReturnUrl();
				if (ew_GetPageName($sReturnUrl) == "shapefileslist.php")
					$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to list page with correct master key if necessary
				$this->SendEmail = TRUE; // Send email on update success
				if ($this->EditRow()) { // Update record based on key
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Update success
					$this->Page_Terminate($sReturnUrl); // Return to caller
				} elseif ($this->getFailureMessage() == $Language->Phrase("NoRecord")) {
					$this->Page_Terminate($sReturnUrl); // Return to caller
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Restore form values if update failed
				}
		}

		// Render the record
		$this->RowType = EW_ROWTYPE_EDIT; // Render as Edit
		$this->ResetAttrs();
		$this->RenderRow();
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

	// Get upload files
	function GetUploadFiles() {
		global $objForm, $Language;

		// Get upload data
		$this->narchivo->Upload->Index = $objForm->Index;
		$this->narchivo->Upload->UploadFile();
		$this->narchivo->CurrentValue = $this->narchivo->Upload->FileName;
		$this->tipo->CurrentValue = $this->narchivo->Upload->ContentType;
		$this->tamano->CurrentValue = $this->narchivo->Upload->FileSize;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		$this->GetUploadFiles(); // Get upload files
		if (!$this->narchivoorigen->FldIsDetailKey) {
			$this->narchivoorigen->setFormValue($objForm->GetValue("x_narchivoorigen"));
		}
		if (!$this->token->FldIsDetailKey) {
			$this->token->setFormValue($objForm->GetValue("x_token"));
		}
		if (!$this->idusuario->FldIsDetailKey) {
			$this->idusuario->setFormValue($objForm->GetValue("x_idusuario"));
		}
		if (!$this->tipo->FldIsDetailKey) {
			$this->tipo->setFormValue($objForm->GetValue("x_tipo"));
		}
		if (!$this->folder->FldIsDetailKey) {
			$this->folder->setFormValue($objForm->GetValue("x_folder"));
		}
		if (!$this->fechacreacion->FldIsDetailKey) {
			$this->fechacreacion->setFormValue($objForm->GetValue("x_fechacreacion"));
			$this->fechacreacion->CurrentValue = ew_UnFormatDateTime($this->fechacreacion->CurrentValue, 0);
		}
		if (!$this->tamano->FldIsDetailKey) {
			$this->tamano->setFormValue($objForm->GetValue("x_tamano"));
		}
		if (!$this->srid->FldIsDetailKey) {
			$this->srid->setFormValue($objForm->GetValue("x_srid"));
		}
		if (!$this->tipogeom->FldIsDetailKey) {
			$this->tipogeom->setFormValue($objForm->GetValue("x_tipogeom"));
		}
		if (!$this->acciones->FldIsDetailKey) {
			$this->acciones->setFormValue($objForm->GetValue("x_acciones"));
		}
		if (!$this->idshapefile->FldIsDetailKey)
			$this->idshapefile->setFormValue($objForm->GetValue("x_idshapefile"));
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->idshapefile->CurrentValue = $this->idshapefile->FormValue;
		$this->narchivoorigen->CurrentValue = $this->narchivoorigen->FormValue;
		$this->token->CurrentValue = $this->token->FormValue;
		$this->idusuario->CurrentValue = $this->idusuario->FormValue;
		$this->folder->CurrentValue = $this->folder->FormValue;
		$this->fechacreacion->CurrentValue = $this->fechacreacion->FormValue;
		$this->fechacreacion->CurrentValue = ew_UnFormatDateTime($this->fechacreacion->CurrentValue, 0);
		$this->srid->CurrentValue = $this->srid->FormValue;
		$this->tipogeom->CurrentValue = $this->tipogeom->FormValue;
		$this->acciones->CurrentValue = $this->acciones->FormValue;
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

			// narchivoorigen
			$this->narchivoorigen->LinkCustomAttributes = "";
			$this->narchivoorigen->HrefValue = "";
			$this->narchivoorigen->TooltipValue = "";

			// narchivo
			$this->narchivo->LinkCustomAttributes = "";
			$this->narchivo->HrefValue = "";
			$this->narchivo->HrefValue2 = $this->narchivo->UploadPath . $this->narchivo->Upload->DbValue;
			$this->narchivo->TooltipValue = "";

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
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// narchivoorigen
			$this->narchivoorigen->EditAttrs["class"] = "form-control";
			$this->narchivoorigen->EditCustomAttributes = "";
			$this->narchivoorigen->EditValue = $this->narchivoorigen->CurrentValue;
			$this->narchivoorigen->ViewCustomAttributes = ["style" => "text-transform: none;"];

			// narchivo
			$this->narchivo->EditAttrs["class"] = "form-control";
			$this->narchivo->EditCustomAttributes = "";
			if (!ew_Empty($this->narchivo->Upload->DbValue)) {
				$this->narchivo->EditValue = $this->narchivo->Upload->DbValue;
			} else {
				$this->narchivo->EditValue = "";
			}
			if (!ew_Empty($this->narchivo->CurrentValue))
				$this->narchivo->Upload->FileName = $this->narchivo->CurrentValue;
			if ($this->CurrentAction == "I" && !$this->EventCancelled) ew_RenderUploadField($this->narchivo);

			// token
			$this->token->EditAttrs["class"] = "form-control";
			$this->token->EditCustomAttributes = "";
			$this->token->EditValue = $this->token->CurrentValue;
			$this->token->ViewCustomAttributes = ["style" => "text-transform: none;"];

			// idusuario
			$this->idusuario->EditAttrs["class"] = "form-control";
			$this->idusuario->EditCustomAttributes = "";
			$this->idusuario->EditValue = $this->idusuario->CurrentValue;
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
					$this->idusuario->EditValue = $this->idusuario->DisplayValue($arwrk);
					$rswrk->Close();
				} else {
					$this->idusuario->EditValue = $this->idusuario->CurrentValue;
				}
			} else {
				$this->idusuario->EditValue = NULL;
			}
			$this->idusuario->ViewCustomAttributes = ["style" => "text-transform: none;"];

			// tipo
			$this->tipo->EditAttrs["class"] = "form-control";
			$this->tipo->EditCustomAttributes = "";
			$this->tipo->EditValue = $this->tipo->CurrentValue;
			$this->tipo->ViewCustomAttributes = "";

			// folder
			$this->folder->EditAttrs["class"] = "form-control";
			$this->folder->EditCustomAttributes = "";
			$this->folder->EditValue = $this->folder->CurrentValue;
			$this->folder->ViewCustomAttributes = "";

			// fechacreacion
			$this->fechacreacion->EditAttrs["class"] = "form-control";
			$this->fechacreacion->EditCustomAttributes = "";
			$this->fechacreacion->EditValue = $this->fechacreacion->CurrentValue;
			$this->fechacreacion->ViewCustomAttributes = "";

			// tamano
			$this->tamano->EditAttrs["class"] = "form-control";
			$this->tamano->EditCustomAttributes = "";
			$this->tamano->EditValue = $this->tamano->CurrentValue;
			$this->tamano->ViewCustomAttributes = "";

			// srid
			$this->srid->EditAttrs["class"] = "form-control";
			$this->srid->EditCustomAttributes = "";
			if (strval($this->srid->CurrentValue) <> "") {
				$this->srid->EditValue = $this->srid->OptionCaption($this->srid->CurrentValue);
			} else {
				$this->srid->EditValue = NULL;
			}
			$this->srid->ViewCustomAttributes = "";

			// tipogeom
			$this->tipogeom->EditAttrs["class"] = "form-control";
			$this->tipogeom->EditCustomAttributes = "";
			$this->tipogeom->EditValue = $this->tipogeom->CurrentValue;
			$this->tipogeom->ViewCustomAttributes = "";

			// acciones
			$this->acciones->EditCustomAttributes = "";
			if (trim(strval($this->acciones->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$arwrk = explode(",", $this->acciones->CurrentValue);
				$sFilterWrk = "";
				foreach ($arwrk as $wrk) {
					if ($sFilterWrk <> "") $sFilterWrk .= " OR ";
					$sFilterWrk .= "\"idaccion\"" . ew_SearchString("=", trim($wrk), EW_DATATYPE_STRING, "");
				}
			}
			$sSqlWrk = "SELECT \"idaccion\", \"accion\" AS \"DispFld\", '' AS \"Disp2Fld\", '' AS \"Disp3Fld\", '' AS \"Disp4Fld\", '' AS \"SelectFilterFld\", '' AS \"SelectFilterFld2\", '' AS \"SelectFilterFld3\", '' AS \"SelectFilterFld4\" FROM \"registro_derecho\".\"appacciones\"";
			$sWhereWrk = "";
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->acciones, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->acciones->EditValue = $arwrk;

			// Edit refer script
			// narchivoorigen

			$this->narchivoorigen->LinkCustomAttributes = "";
			$this->narchivoorigen->HrefValue = "";
			$this->narchivoorigen->TooltipValue = "";

			// narchivo
			$this->narchivo->LinkCustomAttributes = "";
			$this->narchivo->HrefValue = "";
			$this->narchivo->HrefValue2 = $this->narchivo->UploadPath . $this->narchivo->Upload->DbValue;

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

	// Update record based on key values
	function EditRow() {
		global $Security, $Language;
		$sFilter = $this->KeyFilter();
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$conn = &$this->Connection();
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE)
			return FALSE;
		if ($rs->EOF) {
			$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
			$EditRow = FALSE; // Update Failed
		} else {

			// Save old values
			$rsold = &$rs->fields;
			$this->LoadDbValues($rsold);
			$rsnew = array();

			// narchivo
			if ($this->narchivo->Visible && !$this->narchivo->ReadOnly && !$this->narchivo->Upload->KeepFile) {
				$this->narchivo->Upload->DbValue = $rsold['narchivo']; // Get original value
				if ($this->narchivo->Upload->FileName == "") {
					$rsnew['narchivo'] = NULL;
				} else {
					$rsnew['narchivo'] = $this->narchivo->Upload->FileName;
				}
				$this->tipo->SetDbValueDef($rsnew, trim($this->narchivo->Upload->ContentType), NULL, FALSE);
				$this->tamano->SetDbValueDef($rsnew, $this->narchivo->Upload->FileSize, NULL, FALSE);
			}

			// acciones
			$this->acciones->SetDbValueDef($rsnew, $this->acciones->CurrentValue, NULL, $this->acciones->ReadOnly);
			if ($this->narchivo->Visible && !$this->narchivo->Upload->KeepFile) {
				if (!ew_Empty($this->narchivo->Upload->Value)) {
					if ($this->narchivo->Upload->FileName == $this->narchivo->Upload->DbValue) { // Overwrite if same file name
						$this->narchivo->Upload->DbValue = ""; // No need to delete any more
					} else {
						$rsnew['narchivo'] = ew_UploadFileNameEx(ew_UploadPathEx(TRUE, $this->narchivo->UploadPath), $rsnew['narchivo']); // Get new file name
					}
				}
			}

			// Call Row Updating event
			$bUpdateRow = $this->Row_Updating($rsold, $rsnew);
			if ($bUpdateRow) {
				$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
				if (count($rsnew) > 0)
					$EditRow = $this->Update($rsnew, "", $rsold);
				else
					$EditRow = TRUE; // No field to update
				$conn->raiseErrorFn = '';
				if ($EditRow) {
					if ($this->narchivo->Visible && !$this->narchivo->Upload->KeepFile) {
						if (!ew_Empty($this->narchivo->Upload->Value)) {
							$this->narchivo->Upload->SaveToFile($this->narchivo->UploadPath, $rsnew['narchivo'], TRUE);
						}
						if ($this->narchivo->Upload->DbValue <> "")
							@unlink(ew_UploadPathEx(TRUE, $this->narchivo->OldUploadPath) . $this->narchivo->Upload->DbValue);
					}
				}
			} else {
				if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

					// Use the message, do nothing
				} elseif ($this->CancelMessage <> "") {
					$this->setFailureMessage($this->CancelMessage);
					$this->CancelMessage = "";
				} else {
					$this->setFailureMessage($Language->Phrase("UpdateCancelled"));
				}
				$EditRow = FALSE;
			}
		}

		// Call Row_Updated event
		if ($EditRow)
			$this->Row_Updated($rsold, $rsnew);
		$rs->Close();

		// narchivo
		ew_CleanUploadTempPath($this->narchivo, $this->narchivo->Upload->Index);
		return $EditRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("shapefileslist.php"), "", $this->TableVar, TRUE);
		$PageId = "edit";
		$Breadcrumb->Add("edit", $PageId, $url);
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
if (!isset($shapefiles_edit)) $shapefiles_edit = new cshapefiles_edit();

// Page init
$shapefiles_edit->Page_Init();

// Page main
$shapefiles_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$shapefiles_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = fshapefilesedit = new ew_Form("fshapefilesedit", "edit");

// Validate form
fshapefilesedit.Validate = function() {
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
fshapefilesedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fshapefilesedit.ValidateRequired = true;
<?php } else { ?>
fshapefilesedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fshapefilesedit.Lists["x_idusuario"] = {"LinkField":"x_idusuario","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fshapefilesedit.Lists["x_srid"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fshapefilesedit.Lists["x_srid"].Options = <?php echo json_encode($shapefiles->srid->Options()) ?>;
fshapefilesedit.Lists["x_acciones[]"] = {"LinkField":"x_idaccion","Ajax":true,"AutoFill":false,"DisplayFields":["x_accion","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};

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
<?php $shapefiles_edit->ShowPageHeader(); ?>
<?php
$shapefiles_edit->ShowMessage();
?>
<form name="fshapefilesedit" id="fshapefilesedit" class="<?php echo $shapefiles_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($shapefiles_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $shapefiles_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="shapefiles">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<div>
<?php if ($shapefiles->narchivoorigen->Visible) { // narchivoorigen ?>
	<div id="r_narchivoorigen" class="form-group">
		<label id="elh_shapefiles_narchivoorigen" for="x_narchivoorigen" class="col-sm-2 control-label ewLabel"><?php echo $shapefiles->narchivoorigen->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $shapefiles->narchivoorigen->CellAttributes() ?>>
<span id="el_shapefiles_narchivoorigen">
<span<?php echo $shapefiles->narchivoorigen->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $shapefiles->narchivoorigen->EditValue ?></p></span>
</span>
<input type="hidden" data-table="shapefiles" data-field="x_narchivoorigen" name="x_narchivoorigen" id="x_narchivoorigen" value="<?php echo ew_HtmlEncode($shapefiles->narchivoorigen->CurrentValue) ?>">
<?php echo $shapefiles->narchivoorigen->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($shapefiles->narchivo->Visible) { // narchivo ?>
	<div id="r_narchivo" class="form-group">
		<label id="elh_shapefiles_narchivo" class="col-sm-2 control-label ewLabel"><?php echo $shapefiles->narchivo->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $shapefiles->narchivo->CellAttributes() ?>>
<span id="el_shapefiles_narchivo">
<div id="fd_x_narchivo">
<span title="<?php echo $shapefiles->narchivo->FldTitle() ? $shapefiles->narchivo->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($shapefiles->narchivo->ReadOnly || $shapefiles->narchivo->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="shapefiles" data-field="x_narchivo" name="x_narchivo" id="x_narchivo"<?php echo $shapefiles->narchivo->EditAttributes() ?>>
</span>
<input type="hidden" name="fn_x_narchivo" id= "fn_x_narchivo" value="<?php echo $shapefiles->narchivo->Upload->FileName ?>">
<?php if (@$_POST["fa_x_narchivo"] == "0") { ?>
<input type="hidden" name="fa_x_narchivo" id= "fa_x_narchivo" value="0">
<?php } else { ?>
<input type="hidden" name="fa_x_narchivo" id= "fa_x_narchivo" value="1">
<?php } ?>
<input type="hidden" name="fs_x_narchivo" id= "fs_x_narchivo" value="255">
<input type="hidden" name="fx_x_narchivo" id= "fx_x_narchivo" value="<?php echo $shapefiles->narchivo->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_narchivo" id= "fm_x_narchivo" value="<?php echo $shapefiles->narchivo->UploadMaxFileSize ?>">
</div>
<table id="ft_x_narchivo" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php echo $shapefiles->narchivo->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($shapefiles->token->Visible) { // token ?>
	<div id="r_token" class="form-group">
		<label id="elh_shapefiles_token" for="x_token" class="col-sm-2 control-label ewLabel"><?php echo $shapefiles->token->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $shapefiles->token->CellAttributes() ?>>
<span id="el_shapefiles_token">
<span<?php echo $shapefiles->token->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $shapefiles->token->EditValue ?></p></span>
</span>
<input type="hidden" data-table="shapefiles" data-field="x_token" name="x_token" id="x_token" value="<?php echo ew_HtmlEncode($shapefiles->token->CurrentValue) ?>">
<?php echo $shapefiles->token->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($shapefiles->idusuario->Visible) { // idusuario ?>
	<div id="r_idusuario" class="form-group">
		<label id="elh_shapefiles_idusuario" class="col-sm-2 control-label ewLabel"><?php echo $shapefiles->idusuario->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $shapefiles->idusuario->CellAttributes() ?>>
<span id="el_shapefiles_idusuario">
<span<?php echo $shapefiles->idusuario->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $shapefiles->idusuario->EditValue ?></p></span>
</span>
<input type="hidden" data-table="shapefiles" data-field="x_idusuario" name="x_idusuario" id="x_idusuario" value="<?php echo ew_HtmlEncode($shapefiles->idusuario->CurrentValue) ?>">
<?php echo $shapefiles->idusuario->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($shapefiles->tipo->Visible) { // tipo ?>
	<div id="r_tipo" class="form-group">
		<label id="elh_shapefiles_tipo" for="x_tipo" class="col-sm-2 control-label ewLabel"><?php echo $shapefiles->tipo->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $shapefiles->tipo->CellAttributes() ?>>
<span id="el_shapefiles_tipo">
<span<?php echo $shapefiles->tipo->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $shapefiles->tipo->EditValue ?></p></span>
</span>
<input type="hidden" data-table="shapefiles" data-field="x_tipo" name="x_tipo" id="x_tipo" value="<?php echo ew_HtmlEncode($shapefiles->tipo->CurrentValue) ?>">
<?php echo $shapefiles->tipo->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($shapefiles->folder->Visible) { // folder ?>
	<div id="r_folder" class="form-group">
		<label id="elh_shapefiles_folder" for="x_folder" class="col-sm-2 control-label ewLabel"><?php echo $shapefiles->folder->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $shapefiles->folder->CellAttributes() ?>>
<span id="el_shapefiles_folder">
<span<?php echo $shapefiles->folder->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $shapefiles->folder->EditValue ?></p></span>
</span>
<input type="hidden" data-table="shapefiles" data-field="x_folder" name="x_folder" id="x_folder" value="<?php echo ew_HtmlEncode($shapefiles->folder->CurrentValue) ?>">
<?php echo $shapefiles->folder->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($shapefiles->fechacreacion->Visible) { // fechacreacion ?>
	<div id="r_fechacreacion" class="form-group">
		<label id="elh_shapefiles_fechacreacion" for="x_fechacreacion" class="col-sm-2 control-label ewLabel"><?php echo $shapefiles->fechacreacion->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $shapefiles->fechacreacion->CellAttributes() ?>>
<span id="el_shapefiles_fechacreacion">
<span<?php echo $shapefiles->fechacreacion->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $shapefiles->fechacreacion->EditValue ?></p></span>
</span>
<input type="hidden" data-table="shapefiles" data-field="x_fechacreacion" name="x_fechacreacion" id="x_fechacreacion" value="<?php echo ew_HtmlEncode($shapefiles->fechacreacion->CurrentValue) ?>">
<?php echo $shapefiles->fechacreacion->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($shapefiles->tamano->Visible) { // tamano ?>
	<div id="r_tamano" class="form-group">
		<label id="elh_shapefiles_tamano" for="x_tamano" class="col-sm-2 control-label ewLabel"><?php echo $shapefiles->tamano->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $shapefiles->tamano->CellAttributes() ?>>
<span id="el_shapefiles_tamano">
<span<?php echo $shapefiles->tamano->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $shapefiles->tamano->EditValue ?></p></span>
</span>
<input type="hidden" data-table="shapefiles" data-field="x_tamano" name="x_tamano" id="x_tamano" value="<?php echo ew_HtmlEncode($shapefiles->tamano->CurrentValue) ?>">
<?php echo $shapefiles->tamano->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($shapefiles->srid->Visible) { // srid ?>
	<div id="r_srid" class="form-group">
		<label id="elh_shapefiles_srid" class="col-sm-2 control-label ewLabel"><?php echo $shapefiles->srid->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $shapefiles->srid->CellAttributes() ?>>
<span id="el_shapefiles_srid">
<span<?php echo $shapefiles->srid->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $shapefiles->srid->EditValue ?></p></span>
</span>
<input type="hidden" data-table="shapefiles" data-field="x_srid" name="x_srid" id="x_srid" value="<?php echo ew_HtmlEncode($shapefiles->srid->CurrentValue) ?>">
<?php echo $shapefiles->srid->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($shapefiles->tipogeom->Visible) { // tipogeom ?>
	<div id="r_tipogeom" class="form-group">
		<label id="elh_shapefiles_tipogeom" for="x_tipogeom" class="col-sm-2 control-label ewLabel"><?php echo $shapefiles->tipogeom->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $shapefiles->tipogeom->CellAttributes() ?>>
<span id="el_shapefiles_tipogeom">
<span<?php echo $shapefiles->tipogeom->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $shapefiles->tipogeom->EditValue ?></p></span>
</span>
<input type="hidden" data-table="shapefiles" data-field="x_tipogeom" name="x_tipogeom" id="x_tipogeom" value="<?php echo ew_HtmlEncode($shapefiles->tipogeom->CurrentValue) ?>">
<?php echo $shapefiles->tipogeom->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($shapefiles->acciones->Visible) { // acciones ?>
	<div id="r_acciones" class="form-group">
		<label id="elh_shapefiles_acciones" class="col-sm-2 control-label ewLabel"><?php echo $shapefiles->acciones->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $shapefiles->acciones->CellAttributes() ?>>
<span id="el_shapefiles_acciones">
<div id="tp_x_acciones" class="ewTemplate"><input type="checkbox" data-table="shapefiles" data-field="x_acciones" data-value-separator="<?php echo ew_HtmlEncode(is_array($shapefiles->acciones->DisplayValueSeparator) ? json_encode($shapefiles->acciones->DisplayValueSeparator) : $shapefiles->acciones->DisplayValueSeparator) ?>" name="x_acciones[]" id="x_acciones[]" value="{value}"<?php echo $shapefiles->acciones->EditAttributes() ?>></div>
<div id="dsl_x_acciones" data-repeatcolumn="2" class="ewItemList" style="display: none;"><div>
<?php
$arwrk = $shapefiles->acciones->EditValue;
if (is_array($arwrk)) {
	$armultiwrk = (strval($shapefiles->acciones->CurrentValue) <> "") ? explode(",", strval($shapefiles->acciones->CurrentValue)) : array();
	$cnt = count($armultiwrk);
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = "";
		for ($ari = 0; $ari < $cnt; $ari++) {
			if (ew_SameStr($arwrk[$rowcntwrk][0], $armultiwrk[$ari]) && !is_null($armultiwrk[$ari])) {
				$armultiwrk[$ari] = NULL; // Marked for removal
				$selwrk = " checked";
				if ($selwrk <> "") $emptywrk = FALSE;
				break;
			}
		}
		if ($selwrk <> "") {
?>
<label class="checkbox-inline"><input type="checkbox" data-table="shapefiles" data-field="x_acciones" name="x_acciones[]" id="x_acciones_<?php echo $rowcntwrk ?>[]" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $shapefiles->acciones->EditAttributes() ?>><?php echo $shapefiles->acciones->DisplayValue($arwrk[$rowcntwrk]) ?></label>
<?php
		}
	}
	for ($ari = 0; $ari < $cnt; $ari++) {
		if (!is_null($armultiwrk[$ari])) {
?>
<label class="checkbox-inline"><input type="checkbox" data-table="shapefiles" data-field="x_acciones" name="x_acciones[]" value="<?php echo ew_HtmlEncode($armultiwrk[$ari]) ?>" checked<?php echo $shapefiles->acciones->EditAttributes() ?>><?php echo $armultiwrk[$ari] ?></label>
<?php
		}
	}
}
?>
</div></div>
<?php
$sSqlWrk = "SELECT \"idaccion\", \"accion\" AS \"DispFld\", '' AS \"Disp2Fld\", '' AS \"Disp3Fld\", '' AS \"Disp4Fld\" FROM \"registro_derecho\".\"appacciones\"";
$sWhereWrk = "";
$shapefiles->acciones->LookupFilters = array("s" => $sSqlWrk, "d" => "");
$shapefiles->acciones->LookupFilters += array("f0" => "\"idaccion\" = {filter_value}", "t0" => "200", "fn0" => "");
$sSqlWrk = "";
$shapefiles->Lookup_Selecting($shapefiles->acciones, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
if ($sSqlWrk <> "") $shapefiles->acciones->LookupFilters["s"] .= $sSqlWrk;
?>
<input type="hidden" name="s_x_acciones" id="s_x_acciones" value="<?php echo $shapefiles->acciones->LookupFilterQuery() ?>">
</span>
<?php echo $shapefiles->acciones->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<input type="hidden" data-table="shapefiles" data-field="x_idshapefile" name="x_idshapefile" id="x_idshapefile" value="<?php echo ew_HtmlEncode($shapefiles->idshapefile->CurrentValue) ?>">
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $shapefiles_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
</form>
<script type="text/javascript">
fshapefilesedit.Init();
</script>
<?php
$shapefiles_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$shapefiles_edit->Page_Terminate();
?>
