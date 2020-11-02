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

$shapefiles_add = NULL; // Initialize page object first

class cshapefiles_add extends cshapefiles {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{00441056-EF9D-4233-BDD9-EE81681FA399}";

	// Table name
	var $TableName = 'shapefiles';

	// Page object name
	var $PageObjName = 'shapefiles_add';

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
			define("EW_PAGE_ID", 'add', TRUE);

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
		if (!$Security->CanAdd()) {
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
			if (@$_GET["idshapefile"] != "") {
				$this->idshapefile->setQueryStringValue($_GET["idshapefile"]);
				$this->setKey("idshapefile", $this->idshapefile->CurrentValue); // Set up key
			} else {
				$this->setKey("idshapefile", ""); // Clear key
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
					$this->Page_Terminate("shapefileslist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "shapefileslist.php")
						$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to list page with correct master key if necessary
					elseif (ew_GetPageName($sReturnUrl) == "shapefilesview.php")
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
		$this->narchivo->Upload->Index = $objForm->Index;
		$this->narchivo->Upload->UploadFile();
		$this->narchivo->CurrentValue = $this->narchivo->Upload->FileName;
		$this->tipo->CurrentValue = $this->narchivo->Upload->ContentType;
		$this->tamano->CurrentValue = $this->narchivo->Upload->FileSize;
	}

	// Load default values
	function LoadDefaultValues() {
		$this->narchivoorigen->CurrentValue = NULL;
		$this->narchivoorigen->OldValue = $this->narchivoorigen->CurrentValue;
		$this->narchivo->Upload->DbValue = NULL;
		$this->narchivo->OldValue = $this->narchivo->Upload->DbValue;
		$this->narchivo->CurrentValue = NULL; // Clear file related field
		$this->idaplicacion->CurrentValue = NULL;
		$this->idaplicacion->OldValue = $this->idaplicacion->CurrentValue;
		$this->token->CurrentValue = NULL;
		$this->token->OldValue = $this->token->CurrentValue;
		$this->tipo->CurrentValue = NULL;
		$this->tipo->OldValue = $this->tipo->CurrentValue;
		$this->tipo->CurrentValue = NULL; // Clear file related field
		$this->folder->CurrentValue = NULL;
		$this->folder->OldValue = $this->folder->CurrentValue;
		$this->tamano->CurrentValue = NULL;
		$this->tamano->OldValue = $this->tamano->CurrentValue;
		$this->tamano->CurrentValue = NULL; // Clear file related field
		$this->srid->CurrentValue = 32720;
		$this->tipogeom->CurrentValue = NULL;
		$this->tipogeom->OldValue = $this->tipogeom->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		$this->GetUploadFiles(); // Get upload files
		if (!$this->narchivoorigen->FldIsDetailKey) {
			$this->narchivoorigen->setFormValue($objForm->GetValue("x_narchivoorigen"));
		}
		if (!$this->idaplicacion->FldIsDetailKey) {
			$this->idaplicacion->setFormValue($objForm->GetValue("x_idaplicacion"));
		}
		if (!$this->token->FldIsDetailKey) {
			$this->token->setFormValue($objForm->GetValue("x_token"));
		}
		if (!$this->tipo->FldIsDetailKey) {
			$this->tipo->setFormValue($objForm->GetValue("x_tipo"));
		}
		if (!$this->folder->FldIsDetailKey) {
			$this->folder->setFormValue($objForm->GetValue("x_folder"));
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
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->narchivoorigen->CurrentValue = $this->narchivoorigen->FormValue;
		$this->idaplicacion->CurrentValue = $this->idaplicacion->FormValue;
		$this->token->CurrentValue = $this->token->FormValue;
		$this->folder->CurrentValue = $this->folder->FormValue;
		$this->srid->CurrentValue = $this->srid->FormValue;
		$this->tipogeom->CurrentValue = $this->tipogeom->FormValue;
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
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("idshapefile")) <> "")
			$this->idshapefile->CurrentValue = $this->getKey("idshapefile"); // idshapefile
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

			// tipo
			$this->tipo->LinkCustomAttributes = "";
			$this->tipo->HrefValue = "";
			$this->tipo->TooltipValue = "";

			// folder
			$this->folder->LinkCustomAttributes = "";
			$this->folder->HrefValue = "";
			$this->folder->TooltipValue = "";

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
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// narchivoorigen
			$this->narchivoorigen->EditAttrs["class"] = "form-control";
			$this->narchivoorigen->EditCustomAttributes = "";
			$this->narchivoorigen->EditValue = ew_HtmlEncode($this->narchivoorigen->CurrentValue);
			$this->narchivoorigen->PlaceHolder = ew_RemoveHtml($this->narchivoorigen->FldCaption());

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
			if (($this->CurrentAction == "I" || $this->CurrentAction == "C") && !$this->EventCancelled) ew_RenderUploadField($this->narchivo);

			// idaplicacion
			$this->idaplicacion->EditAttrs["class"] = "form-control";
			$this->idaplicacion->EditCustomAttributes = "";
			$this->idaplicacion->EditValue = ew_HtmlEncode($this->idaplicacion->CurrentValue);
			$this->idaplicacion->PlaceHolder = ew_RemoveHtml($this->idaplicacion->FldCaption());

			// token
			$this->token->EditAttrs["class"] = "form-control";
			$this->token->EditCustomAttributes = "";
			$this->token->EditValue = ew_HtmlEncode($this->token->CurrentValue);
			$this->token->PlaceHolder = ew_RemoveHtml($this->token->FldCaption());

			// tipo
			$this->tipo->EditAttrs["class"] = "form-control";
			$this->tipo->EditCustomAttributes = "";
			$this->tipo->EditValue = ew_HtmlEncode($this->tipo->CurrentValue);
			$this->tipo->PlaceHolder = ew_RemoveHtml($this->tipo->FldCaption());

			// folder
			$this->folder->EditAttrs["class"] = "form-control";
			$this->folder->EditCustomAttributes = "";
			$this->folder->EditValue = ew_HtmlEncode($this->folder->CurrentValue);
			$this->folder->PlaceHolder = ew_RemoveHtml($this->folder->FldCaption());

			// tamano
			$this->tamano->EditAttrs["class"] = "form-control";
			$this->tamano->EditCustomAttributes = "";
			$this->tamano->EditValue = ew_HtmlEncode($this->tamano->CurrentValue);
			$this->tamano->PlaceHolder = ew_RemoveHtml($this->tamano->FldCaption());

			// srid
			$this->srid->EditCustomAttributes = "";
			$this->srid->EditValue = $this->srid->Options(FALSE);

			// tipogeom
			$this->tipogeom->EditAttrs["class"] = "form-control";
			$this->tipogeom->EditCustomAttributes = "";
			$this->tipogeom->EditValue = ew_HtmlEncode($this->tipogeom->CurrentValue);
			$this->tipogeom->PlaceHolder = ew_RemoveHtml($this->tipogeom->FldCaption());

			// Add refer script
			// narchivoorigen

			$this->narchivoorigen->LinkCustomAttributes = "";
			$this->narchivoorigen->HrefValue = "";

			// narchivo
			$this->narchivo->LinkCustomAttributes = "";
			$this->narchivo->HrefValue = "";
			$this->narchivo->HrefValue2 = $this->narchivo->UploadPath . $this->narchivo->Upload->DbValue;

			// idaplicacion
			$this->idaplicacion->LinkCustomAttributes = "";
			$this->idaplicacion->HrefValue = "";

			// token
			$this->token->LinkCustomAttributes = "";
			$this->token->HrefValue = "";

			// tipo
			$this->tipo->LinkCustomAttributes = "";
			$this->tipo->HrefValue = "";

			// folder
			$this->folder->LinkCustomAttributes = "";
			$this->folder->HrefValue = "";

			// tamano
			$this->tamano->LinkCustomAttributes = "";
			$this->tamano->HrefValue = "";

			// srid
			$this->srid->LinkCustomAttributes = "";
			$this->srid->HrefValue = "";

			// tipogeom
			$this->tipogeom->LinkCustomAttributes = "";
			$this->tipogeom->HrefValue = "";
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
		if (!ew_CheckInteger($this->tamano->FormValue)) {
			ew_AddMessage($gsFormError, $this->tamano->FldErrMsg());
		}
		if (!ew_CheckInteger($this->tipogeom->FormValue)) {
			ew_AddMessage($gsFormError, $this->tipogeom->FldErrMsg());
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

		// narchivoorigen
		$this->narchivoorigen->SetDbValueDef($rsnew, $this->narchivoorigen->CurrentValue, NULL, FALSE);

		// narchivo
		if ($this->narchivo->Visible && !$this->narchivo->Upload->KeepFile) {
			$this->narchivo->Upload->DbValue = ""; // No need to delete old file
			if ($this->narchivo->Upload->FileName == "") {
				$rsnew['narchivo'] = NULL;
			} else {
				$rsnew['narchivo'] = $this->narchivo->Upload->FileName;
			}
			$this->tipo->SetDbValueDef($rsnew, trim($this->narchivo->Upload->ContentType), NULL, FALSE);
			$this->tamano->SetDbValueDef($rsnew, $this->narchivo->Upload->FileSize, NULL, FALSE);
		}

		// idaplicacion
		$this->idaplicacion->SetDbValueDef($rsnew, $this->idaplicacion->CurrentValue, NULL, FALSE);

		// token
		$this->token->SetDbValueDef($rsnew, $this->token->CurrentValue, NULL, FALSE);

		// tipo
		// folder

		$this->folder->SetDbValueDef($rsnew, $this->folder->CurrentValue, NULL, FALSE);

		// tamano
		// srid

		$this->srid->SetDbValueDef($rsnew, $this->srid->CurrentValue, NULL, FALSE);

		// tipogeom
		$this->tipogeom->SetDbValueDef($rsnew, $this->tipogeom->CurrentValue, NULL, FALSE);
		if ($this->narchivo->Visible && !$this->narchivo->Upload->KeepFile) {
			if (!ew_Empty($this->narchivo->Upload->Value)) {
				if ($this->narchivo->Upload->FileName == $this->narchivo->Upload->DbValue) { // Overwrite if same file name
					$this->narchivo->Upload->DbValue = ""; // No need to delete any more
				} else {
					$rsnew['narchivo'] = ew_UploadFileNameEx(ew_UploadPathEx(TRUE, $this->narchivo->UploadPath), $rsnew['narchivo']); // Get new file name
				}
			}
		}

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);
		if ($bInsertRow) {
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			$AddRow = $this->Insert($rsnew);
			$conn->raiseErrorFn = '';
			if ($AddRow) {

				// Get insert id if necessary
				$this->idshapefile->setDbValue($conn->GetOne("SELECT currval('shapefiles_idshapefile_seq'::regclass)"));
				$rsnew['idshapefile'] = $this->idshapefile->DbValue;
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
				$this->setFailureMessage($Language->Phrase("InsertCancelled"));
			}
			$AddRow = FALSE;
		}
		if ($AddRow) {

			// Call Row Inserted event
			$rs = ($rsold == NULL) ? NULL : $rsold->fields;
			$this->Row_Inserted($rs, $rsnew);
		}

		// narchivo
		ew_CleanUploadTempPath($this->narchivo, $this->narchivo->Upload->Index);
		return $AddRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("shapefileslist.php"), "", $this->TableVar, TRUE);
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
if (!isset($shapefiles_add)) $shapefiles_add = new cshapefiles_add();

// Page init
$shapefiles_add->Page_Init();

// Page main
$shapefiles_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$shapefiles_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "add";
var CurrentForm = fshapefilesadd = new ew_Form("fshapefilesadd", "add");

// Validate form
fshapefilesadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_tamano");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($shapefiles->tamano->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_tipogeom");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($shapefiles->tipogeom->FldErrMsg()) ?>");

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
fshapefilesadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fshapefilesadd.ValidateRequired = true;
<?php } else { ?>
fshapefilesadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fshapefilesadd.Lists["x_srid"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fshapefilesadd.Lists["x_srid"].Options = <?php echo json_encode($shapefiles->srid->Options()) ?>;

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
<?php $shapefiles_add->ShowPageHeader(); ?>
<?php
$shapefiles_add->ShowMessage();
?>
<form name="fshapefilesadd" id="fshapefilesadd" class="<?php echo $shapefiles_add->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($shapefiles_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $shapefiles_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="shapefiles">
<input type="hidden" name="a_add" id="a_add" value="A">
<div>
<?php if ($shapefiles->narchivoorigen->Visible) { // narchivoorigen ?>
	<div id="r_narchivoorigen" class="form-group">
		<label id="elh_shapefiles_narchivoorigen" for="x_narchivoorigen" class="col-sm-2 control-label ewLabel"><?php echo $shapefiles->narchivoorigen->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $shapefiles->narchivoorigen->CellAttributes() ?>>
<span id="el_shapefiles_narchivoorigen">
<input type="text" data-table="shapefiles" data-field="x_narchivoorigen" name="x_narchivoorigen" id="x_narchivoorigen" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($shapefiles->narchivoorigen->getPlaceHolder()) ?>" value="<?php echo $shapefiles->narchivoorigen->EditValue ?>"<?php echo $shapefiles->narchivoorigen->EditAttributes() ?>>
</span>
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
<input type="hidden" name="fa_x_narchivo" id= "fa_x_narchivo" value="0">
<input type="hidden" name="fs_x_narchivo" id= "fs_x_narchivo" value="255">
<input type="hidden" name="fx_x_narchivo" id= "fx_x_narchivo" value="<?php echo $shapefiles->narchivo->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_narchivo" id= "fm_x_narchivo" value="<?php echo $shapefiles->narchivo->UploadMaxFileSize ?>">
</div>
<table id="ft_x_narchivo" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php echo $shapefiles->narchivo->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($shapefiles->idaplicacion->Visible) { // idaplicacion ?>
	<div id="r_idaplicacion" class="form-group">
		<label id="elh_shapefiles_idaplicacion" for="x_idaplicacion" class="col-sm-2 control-label ewLabel"><?php echo $shapefiles->idaplicacion->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $shapefiles->idaplicacion->CellAttributes() ?>>
<span id="el_shapefiles_idaplicacion">
<input type="text" data-table="shapefiles" data-field="x_idaplicacion" name="x_idaplicacion" id="x_idaplicacion" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($shapefiles->idaplicacion->getPlaceHolder()) ?>" value="<?php echo $shapefiles->idaplicacion->EditValue ?>"<?php echo $shapefiles->idaplicacion->EditAttributes() ?>>
</span>
<?php echo $shapefiles->idaplicacion->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($shapefiles->token->Visible) { // token ?>
	<div id="r_token" class="form-group">
		<label id="elh_shapefiles_token" for="x_token" class="col-sm-2 control-label ewLabel"><?php echo $shapefiles->token->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $shapefiles->token->CellAttributes() ?>>
<span id="el_shapefiles_token">
<input type="text" data-table="shapefiles" data-field="x_token" name="x_token" id="x_token" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($shapefiles->token->getPlaceHolder()) ?>" value="<?php echo $shapefiles->token->EditValue ?>"<?php echo $shapefiles->token->EditAttributes() ?>>
</span>
<?php echo $shapefiles->token->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($shapefiles->tipo->Visible) { // tipo ?>
	<div id="r_tipo" class="form-group">
		<label id="elh_shapefiles_tipo" for="x_tipo" class="col-sm-2 control-label ewLabel"><?php echo $shapefiles->tipo->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $shapefiles->tipo->CellAttributes() ?>>
<span id="el_shapefiles_tipo">
<input type="text" data-table="shapefiles" data-field="x_tipo" name="x_tipo" id="x_tipo" size="30" maxlength="10" placeholder="<?php echo ew_HtmlEncode($shapefiles->tipo->getPlaceHolder()) ?>" value="<?php echo $shapefiles->tipo->EditValue ?>"<?php echo $shapefiles->tipo->EditAttributes() ?>>
</span>
<?php echo $shapefiles->tipo->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($shapefiles->folder->Visible) { // folder ?>
	<div id="r_folder" class="form-group">
		<label id="elh_shapefiles_folder" for="x_folder" class="col-sm-2 control-label ewLabel"><?php echo $shapefiles->folder->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $shapefiles->folder->CellAttributes() ?>>
<span id="el_shapefiles_folder">
<input type="text" data-table="shapefiles" data-field="x_folder" name="x_folder" id="x_folder" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($shapefiles->folder->getPlaceHolder()) ?>" value="<?php echo $shapefiles->folder->EditValue ?>"<?php echo $shapefiles->folder->EditAttributes() ?>>
</span>
<?php echo $shapefiles->folder->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($shapefiles->tamano->Visible) { // tamano ?>
	<div id="r_tamano" class="form-group">
		<label id="elh_shapefiles_tamano" for="x_tamano" class="col-sm-2 control-label ewLabel"><?php echo $shapefiles->tamano->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $shapefiles->tamano->CellAttributes() ?>>
<span id="el_shapefiles_tamano">
<input type="text" data-table="shapefiles" data-field="x_tamano" name="x_tamano" id="x_tamano" size="30" placeholder="<?php echo ew_HtmlEncode($shapefiles->tamano->getPlaceHolder()) ?>" value="<?php echo $shapefiles->tamano->EditValue ?>"<?php echo $shapefiles->tamano->EditAttributes() ?>>
</span>
<?php echo $shapefiles->tamano->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($shapefiles->srid->Visible) { // srid ?>
	<div id="r_srid" class="form-group">
		<label id="elh_shapefiles_srid" class="col-sm-2 control-label ewLabel"><?php echo $shapefiles->srid->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $shapefiles->srid->CellAttributes() ?>>
<span id="el_shapefiles_srid">
<div id="tp_x_srid" class="ewTemplate"><input type="radio" data-table="shapefiles" data-field="x_srid" data-value-separator="<?php echo ew_HtmlEncode(is_array($shapefiles->srid->DisplayValueSeparator) ? json_encode($shapefiles->srid->DisplayValueSeparator) : $shapefiles->srid->DisplayValueSeparator) ?>" name="x_srid" id="x_srid" value="{value}"<?php echo $shapefiles->srid->EditAttributes() ?>></div>
<div id="dsl_x_srid" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php
$arwrk = $shapefiles->srid->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($shapefiles->srid->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked" : "";
		if ($selwrk <> "")
			$emptywrk = FALSE;
?>
<label class="radio-inline"><input type="radio" data-table="shapefiles" data-field="x_srid" name="x_srid" id="x_srid_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $shapefiles->srid->EditAttributes() ?>><?php echo $shapefiles->srid->DisplayValue($arwrk[$rowcntwrk]) ?></label>
<?php
	}
	if ($emptywrk && strval($shapefiles->srid->CurrentValue) <> "") {
?>
<label class="radio-inline"><input type="radio" data-table="shapefiles" data-field="x_srid" name="x_srid" id="x_srid_<?php echo $rowswrk ?>" value="<?php echo ew_HtmlEncode($shapefiles->srid->CurrentValue) ?>" checked<?php echo $shapefiles->srid->EditAttributes() ?>><?php echo $shapefiles->srid->CurrentValue ?></label>
<?php
    }
}
?>
</div></div>
</span>
<?php echo $shapefiles->srid->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($shapefiles->tipogeom->Visible) { // tipogeom ?>
	<div id="r_tipogeom" class="form-group">
		<label id="elh_shapefiles_tipogeom" for="x_tipogeom" class="col-sm-2 control-label ewLabel"><?php echo $shapefiles->tipogeom->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $shapefiles->tipogeom->CellAttributes() ?>>
<span id="el_shapefiles_tipogeom">
<input type="text" data-table="shapefiles" data-field="x_tipogeom" name="x_tipogeom" id="x_tipogeom" size="30" placeholder="<?php echo ew_HtmlEncode($shapefiles->tipogeom->getPlaceHolder()) ?>" value="<?php echo $shapefiles->tipogeom->EditValue ?>"<?php echo $shapefiles->tipogeom->EditAttributes() ?>>
</span>
<?php echo $shapefiles->tipogeom->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $shapefiles_add->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
</form>
<script type="text/javascript">
fshapefilesadd.Init();
</script>
<?php
$shapefiles_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$shapefiles_add->Page_Terminate();
?>
