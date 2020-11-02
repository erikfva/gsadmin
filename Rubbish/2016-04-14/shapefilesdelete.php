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

$shapefiles_delete = NULL; // Initialize page object first

class cshapefiles_delete extends cshapefiles {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{00441056-EF9D-4233-BDD9-EE81681FA399}";

	// Table name
	var $TableName = 'shapefiles';

	// Page object name
	var $PageObjName = 'shapefiles_delete';

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
			define("EW_PAGE_ID", 'delete', TRUE);

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
		if (!$Security->CanDelete()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage($Language->Phrase("NoPermission")); // Set no permission
			if ($Security->CanList())
				$this->Page_Terminate(ew_GetUrl("shapefileslist.php"));
			else
				$this->Page_Terminate(ew_GetUrl("login.php"));
		}
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->idshapefile->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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
	var $DbMasterFilter = "";
	var $DbDetailFilter = "";
	var $StartRec;
	var $TotalRecs = 0;
	var $RecCnt;
	var $RecKeys = array();
	var $Recordset;
	var $StartRowCnt = 1;
	var $RowCnt = 0;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Load key parameters
		$this->RecKeys = $this->GetRecordKeys(); // Load record keys
		$sFilter = $this->GetKeyFilter();
		if ($sFilter == "")
			$this->Page_Terminate("shapefileslist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in shapefiles class, shapefilesinfo.php

		$this->CurrentFilter = $sFilter;

		// Get action
		if (@$_POST["a_delete"] <> "") {
			$this->CurrentAction = $_POST["a_delete"];
		} else {
			$this->CurrentAction = "D"; // Delete record directly
		}
		switch ($this->CurrentAction) {
			case "D": // Delete
				$this->SendEmail = TRUE; // Send email on delete success
				if ($this->DeleteRows()) { // Delete rows
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("DeleteSuccess")); // Set up success message
					$this->Page_Terminate($this->getReturnUrl()); // Return to caller
				}
		}
	}

	// Load recordset
	function LoadRecordset($offset = -1, $rowcnt = -1) {

		// Load List page SQL
		$sSql = $this->SelectSQL();
		$conn = &$this->Connection();

		// Load recordset
		$dbtype = ew_GetConnectionType($this->DBID);
		if ($this->UseSelectLimit) {
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			if ($dbtype == "MSSQL") {
				$rs = $conn->SelectLimit($sSql, $rowcnt, $offset, array("_hasOrderBy" => trim($this->getOrderBy()) || trim($this->getSessionOrderBy())));
			} else {
				$rs = $conn->SelectLimit($sSql, $rowcnt, $offset);
			}
			$conn->raiseErrorFn = '';
		} else {
			$rs = ew_LoadRecordset($sSql, $conn);
		}

		// Call Recordset Selected event
		$this->Recordset_Selected($rs);
		return $rs;
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
		$this->idaplicacion->setDbValue($rs->fields('idaplicacion'));
		$this->token->setDbValue($rs->fields('token'));
		$this->idusuario->setDbValue($rs->fields('idusuario'));
		$this->tipo->setDbValue($rs->fields('tipo'));
		$this->folder->setDbValue($rs->fields('folder'));
		$this->narchivo->Upload->DbValue = $rs->fields('narchivo');
		$this->narchivo->CurrentValue = $this->narchivo->Upload->DbValue;
		$this->narchivoorigen->setDbValue($rs->fields('narchivoorigen'));
		$this->fechacreacion->setDbValue($rs->fields('fechacreacion'));
		$this->tamano->setDbValue($rs->fields('tamano'));
		$this->srid->setDbValue($rs->fields('srid'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->idshapefile->DbValue = $row['idshapefile'];
		$this->idaplicacion->DbValue = $row['idaplicacion'];
		$this->token->DbValue = $row['token'];
		$this->idusuario->DbValue = $row['idusuario'];
		$this->tipo->DbValue = $row['tipo'];
		$this->folder->DbValue = $row['folder'];
		$this->narchivo->Upload->DbValue = $row['narchivo'];
		$this->narchivoorigen->DbValue = $row['narchivoorigen'];
		$this->fechacreacion->DbValue = $row['fechacreacion'];
		$this->tamano->DbValue = $row['tamano'];
		$this->srid->DbValue = $row['srid'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// idshapefile
		// idaplicacion
		// token
		// idusuario
		// tipo
		// folder
		// narchivo
		// narchivoorigen
		// fechacreacion
		// tamano
		// srid

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// idshapefile
		$this->idshapefile->ViewValue = $this->idshapefile->CurrentValue;
		$this->idshapefile->ViewCustomAttributes = "";

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

		// narchivo
		if (!ew_Empty($this->narchivo->Upload->DbValue)) {
			$this->narchivo->ViewValue = $this->narchivo->Upload->DbValue;
		} else {
			$this->narchivo->ViewValue = "";
		}
		$this->narchivo->ViewCustomAttributes = ["style" => "text-transform: none;"];

		// narchivoorigen
		$this->narchivoorigen->ViewValue = $this->narchivoorigen->CurrentValue;
		$this->narchivoorigen->ViewCustomAttributes = ["style" => "text-transform: none;"];

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

			// idshapefile
			$this->idshapefile->LinkCustomAttributes = "";
			$this->idshapefile->HrefValue = "";
			$this->idshapefile->TooltipValue = "";

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

			// narchivo
			$this->narchivo->LinkCustomAttributes = "";
			$this->narchivo->HrefValue = "";
			$this->narchivo->HrefValue2 = $this->narchivo->UploadPath . $this->narchivo->Upload->DbValue;
			$this->narchivo->TooltipValue = "";

			// narchivoorigen
			$this->narchivoorigen->LinkCustomAttributes = "";
			$this->narchivoorigen->HrefValue = "";
			$this->narchivoorigen->TooltipValue = "";

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
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	//
	// Delete records based on current filter
	//
	function DeleteRows() {
		global $Language, $Security;
		if (!$Security->CanDelete()) {
			$this->setFailureMessage($Language->Phrase("NoDeletePermission")); // No delete permission
			return FALSE;
		}
		$DeleteRows = TRUE;
		$sSql = $this->SQL();
		$conn = &$this->Connection();
		$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE) {
			return FALSE;
		} elseif ($rs->EOF) {
			$this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
			$rs->Close();
			return FALSE;

		//} else {
		//	$this->LoadRowValues($rs); // Load row values

		}
		$rows = ($rs) ? $rs->GetRows() : array();
		$conn->BeginTrans();

		// Clone old rows
		$rsold = $rows;
		if ($rs)
			$rs->Close();

		// Call row deleting event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$DeleteRows = $this->Row_Deleting($row);
				if (!$DeleteRows) break;
			}
		}
		if ($DeleteRows) {
			$sKey = "";
			foreach ($rsold as $row) {
				$sThisKey = "";
				if ($sThisKey <> "") $sThisKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
				$sThisKey .= $row['idshapefile'];
				$this->LoadDbValues($row);
				@unlink(ew_UploadPathEx(TRUE, $this->narchivo->OldUploadPath) . $row['narchivo']);
				$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
				$DeleteRows = $this->Delete($row); // Delete
				$conn->raiseErrorFn = '';
				if ($DeleteRows === FALSE)
					break;
				if ($sKey <> "") $sKey .= ", ";
				$sKey .= $sThisKey;
			}
		} else {

			// Set up error message
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("DeleteCancelled"));
			}
		}
		if ($DeleteRows) {
			$conn->CommitTrans(); // Commit the changes
		} else {
			$conn->RollbackTrans(); // Rollback changes
		}

		// Call Row Deleted event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$this->Row_Deleted($row);
			}
		}
		return $DeleteRows;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("shapefileslist.php"), "", $this->TableVar, TRUE);
		$PageId = "delete";
		$Breadcrumb->Add("delete", $PageId, $url);
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
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($shapefiles_delete)) $shapefiles_delete = new cshapefiles_delete();

// Page init
$shapefiles_delete->Page_Init();

// Page main
$shapefiles_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$shapefiles_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "delete";
var CurrentForm = fshapefilesdelete = new ew_Form("fshapefilesdelete", "delete");

// Form_CustomValidate event
fshapefilesdelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fshapefilesdelete.ValidateRequired = true;
<?php } else { ?>
fshapefilesdelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fshapefilesdelete.Lists["x_srid"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fshapefilesdelete.Lists["x_srid"].Options = <?php echo json_encode($shapefiles->srid->Options()) ?>;

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($shapefiles_delete->Recordset = $shapefiles_delete->LoadRecordset())
	$shapefiles_deleteTotalRecs = $shapefiles_delete->Recordset->RecordCount(); // Get record count
if ($shapefiles_deleteTotalRecs <= 0) { // No record found, exit
	if ($shapefiles_delete->Recordset)
		$shapefiles_delete->Recordset->Close();
	$shapefiles_delete->Page_Terminate("shapefileslist.php"); // Return to list
}
?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $shapefiles_delete->ShowPageHeader(); ?>
<?php
$shapefiles_delete->ShowMessage();
?>
<form name="fshapefilesdelete" id="fshapefilesdelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($shapefiles_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $shapefiles_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="shapefiles">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($shapefiles_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table class="table ewTable">
<?php echo $shapefiles->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($shapefiles->idshapefile->Visible) { // idshapefile ?>
		<th><span id="elh_shapefiles_idshapefile" class="shapefiles_idshapefile"><?php echo $shapefiles->idshapefile->FldCaption() ?></span></th>
<?php } ?>
<?php if ($shapefiles->idaplicacion->Visible) { // idaplicacion ?>
		<th><span id="elh_shapefiles_idaplicacion" class="shapefiles_idaplicacion"><?php echo $shapefiles->idaplicacion->FldCaption() ?></span></th>
<?php } ?>
<?php if ($shapefiles->token->Visible) { // token ?>
		<th><span id="elh_shapefiles_token" class="shapefiles_token"><?php echo $shapefiles->token->FldCaption() ?></span></th>
<?php } ?>
<?php if ($shapefiles->idusuario->Visible) { // idusuario ?>
		<th><span id="elh_shapefiles_idusuario" class="shapefiles_idusuario"><?php echo $shapefiles->idusuario->FldCaption() ?></span></th>
<?php } ?>
<?php if ($shapefiles->tipo->Visible) { // tipo ?>
		<th><span id="elh_shapefiles_tipo" class="shapefiles_tipo"><?php echo $shapefiles->tipo->FldCaption() ?></span></th>
<?php } ?>
<?php if ($shapefiles->folder->Visible) { // folder ?>
		<th><span id="elh_shapefiles_folder" class="shapefiles_folder"><?php echo $shapefiles->folder->FldCaption() ?></span></th>
<?php } ?>
<?php if ($shapefiles->narchivo->Visible) { // narchivo ?>
		<th><span id="elh_shapefiles_narchivo" class="shapefiles_narchivo"><?php echo $shapefiles->narchivo->FldCaption() ?></span></th>
<?php } ?>
<?php if ($shapefiles->narchivoorigen->Visible) { // narchivoorigen ?>
		<th><span id="elh_shapefiles_narchivoorigen" class="shapefiles_narchivoorigen"><?php echo $shapefiles->narchivoorigen->FldCaption() ?></span></th>
<?php } ?>
<?php if ($shapefiles->fechacreacion->Visible) { // fechacreacion ?>
		<th><span id="elh_shapefiles_fechacreacion" class="shapefiles_fechacreacion"><?php echo $shapefiles->fechacreacion->FldCaption() ?></span></th>
<?php } ?>
<?php if ($shapefiles->tamano->Visible) { // tamano ?>
		<th><span id="elh_shapefiles_tamano" class="shapefiles_tamano"><?php echo $shapefiles->tamano->FldCaption() ?></span></th>
<?php } ?>
<?php if ($shapefiles->srid->Visible) { // srid ?>
		<th><span id="elh_shapefiles_srid" class="shapefiles_srid"><?php echo $shapefiles->srid->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$shapefiles_delete->RecCnt = 0;
$i = 0;
while (!$shapefiles_delete->Recordset->EOF) {
	$shapefiles_delete->RecCnt++;
	$shapefiles_delete->RowCnt++;

	// Set row properties
	$shapefiles->ResetAttrs();
	$shapefiles->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$shapefiles_delete->LoadRowValues($shapefiles_delete->Recordset);

	// Render row
	$shapefiles_delete->RenderRow();
?>
	<tr<?php echo $shapefiles->RowAttributes() ?>>
<?php if ($shapefiles->idshapefile->Visible) { // idshapefile ?>
		<td<?php echo $shapefiles->idshapefile->CellAttributes() ?>>
<span id="el<?php echo $shapefiles_delete->RowCnt ?>_shapefiles_idshapefile" class="shapefiles_idshapefile">
<span<?php echo $shapefiles->idshapefile->ViewAttributes() ?>>
<?php echo $shapefiles->idshapefile->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($shapefiles->idaplicacion->Visible) { // idaplicacion ?>
		<td<?php echo $shapefiles->idaplicacion->CellAttributes() ?>>
<span id="el<?php echo $shapefiles_delete->RowCnt ?>_shapefiles_idaplicacion" class="shapefiles_idaplicacion">
<span<?php echo $shapefiles->idaplicacion->ViewAttributes() ?>>
<?php echo $shapefiles->idaplicacion->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($shapefiles->token->Visible) { // token ?>
		<td<?php echo $shapefiles->token->CellAttributes() ?>>
<span id="el<?php echo $shapefiles_delete->RowCnt ?>_shapefiles_token" class="shapefiles_token">
<span<?php echo $shapefiles->token->ViewAttributes() ?>>
<?php echo $shapefiles->token->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($shapefiles->idusuario->Visible) { // idusuario ?>
		<td<?php echo $shapefiles->idusuario->CellAttributes() ?>>
<span id="el<?php echo $shapefiles_delete->RowCnt ?>_shapefiles_idusuario" class="shapefiles_idusuario">
<span<?php echo $shapefiles->idusuario->ViewAttributes() ?>>
<?php echo $shapefiles->idusuario->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($shapefiles->tipo->Visible) { // tipo ?>
		<td<?php echo $shapefiles->tipo->CellAttributes() ?>>
<span id="el<?php echo $shapefiles_delete->RowCnt ?>_shapefiles_tipo" class="shapefiles_tipo">
<span<?php echo $shapefiles->tipo->ViewAttributes() ?>>
<?php echo $shapefiles->tipo->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($shapefiles->folder->Visible) { // folder ?>
		<td<?php echo $shapefiles->folder->CellAttributes() ?>>
<span id="el<?php echo $shapefiles_delete->RowCnt ?>_shapefiles_folder" class="shapefiles_folder">
<span<?php echo $shapefiles->folder->ViewAttributes() ?>>
<?php echo $shapefiles->folder->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($shapefiles->narchivo->Visible) { // narchivo ?>
		<td<?php echo $shapefiles->narchivo->CellAttributes() ?>>
<span id="el<?php echo $shapefiles_delete->RowCnt ?>_shapefiles_narchivo" class="shapefiles_narchivo">
<span<?php echo $shapefiles->narchivo->ViewAttributes() ?>>
<?php echo ew_GetFileViewTag($shapefiles->narchivo, $shapefiles->narchivo->ListViewValue()) ?>
</span>
</span>
</td>
<?php } ?>
<?php if ($shapefiles->narchivoorigen->Visible) { // narchivoorigen ?>
		<td<?php echo $shapefiles->narchivoorigen->CellAttributes() ?>>
<span id="el<?php echo $shapefiles_delete->RowCnt ?>_shapefiles_narchivoorigen" class="shapefiles_narchivoorigen">
<span<?php echo $shapefiles->narchivoorigen->ViewAttributes() ?>>
<?php echo $shapefiles->narchivoorigen->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($shapefiles->fechacreacion->Visible) { // fechacreacion ?>
		<td<?php echo $shapefiles->fechacreacion->CellAttributes() ?>>
<span id="el<?php echo $shapefiles_delete->RowCnt ?>_shapefiles_fechacreacion" class="shapefiles_fechacreacion">
<span<?php echo $shapefiles->fechacreacion->ViewAttributes() ?>>
<?php echo $shapefiles->fechacreacion->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($shapefiles->tamano->Visible) { // tamano ?>
		<td<?php echo $shapefiles->tamano->CellAttributes() ?>>
<span id="el<?php echo $shapefiles_delete->RowCnt ?>_shapefiles_tamano" class="shapefiles_tamano">
<span<?php echo $shapefiles->tamano->ViewAttributes() ?>>
<?php echo $shapefiles->tamano->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($shapefiles->srid->Visible) { // srid ?>
		<td<?php echo $shapefiles->srid->CellAttributes() ?>>
<span id="el<?php echo $shapefiles_delete->RowCnt ?>_shapefiles_srid" class="shapefiles_srid">
<span<?php echo $shapefiles->srid->ViewAttributes() ?>>
<?php echo $shapefiles->srid->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$shapefiles_delete->Recordset->MoveNext();
}
$shapefiles_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</div>
<div>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $shapefiles_delete->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
</div>
</form>
<script type="text/javascript">
fshapefilesdelete.Init();
</script>
<?php
$shapefiles_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$shapefiles_delete->Page_Terminate();
?>
