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

$shapefiles_list = NULL; // Initialize page object first

class cshapefiles_list extends cshapefiles {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{00441056-EF9D-4233-BDD9-EE81681FA399}";

	// Table name
	var $TableName = 'shapefiles';

	// Page object name
	var $PageObjName = 'shapefiles_list';

	// Grid form hidden field names
	var $FormName = 'fshapefileslist';
	var $FormActionName = 'k_action';
	var $FormKeyName = 'k_key';
	var $FormOldKeyName = 'k_oldkey';
	var $FormBlankRowName = 'k_blankrow';
	var $FormKeyCountName = 'key_count';

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

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "shapefilesadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "shapefilesdelete.php";
		$this->MultiUpdateUrl = "shapefilesupdate.php";

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

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

		// List options
		$this->ListOptions = new cListOptions();
		$this->ListOptions->TableVar = $this->TableVar;

		// Export options
		$this->ExportOptions = new cListOptions();
		$this->ExportOptions->Tag = "div";
		$this->ExportOptions->TagClassName = "ewExportOption";

		// Other options
		$this->OtherOptions['addedit'] = new cListOptions();
		$this->OtherOptions['addedit']->Tag = "div";
		$this->OtherOptions['addedit']->TagClassName = "ewAddEditOption";
		$this->OtherOptions['detail'] = new cListOptions();
		$this->OtherOptions['detail']->Tag = "div";
		$this->OtherOptions['detail']->TagClassName = "ewDetailOption";
		$this->OtherOptions['action'] = new cListOptions();
		$this->OtherOptions['action']->Tag = "div";
		$this->OtherOptions['action']->TagClassName = "ewActionOption";

		// Filter options
		$this->FilterOptions = new cListOptions();
		$this->FilterOptions->Tag = "div";
		$this->FilterOptions->TagClassName = "ewFilterOption fshapefileslistsrch";

		// List actions
		$this->ListActions = new cListActions();
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
		if (!$Security->CanList()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage($Language->Phrase("NoPermission")); // Set no permission
			$this->Page_Terminate(ew_GetUrl("index.php"));
		}

		// Get export parameters
		$custom = "";
		if (@$_GET["export"] <> "") {
			$this->Export = $_GET["export"];
			$custom = @$_GET["custom"];
		} elseif (@$_POST["export"] <> "") {
			$this->Export = $_POST["export"];
			$custom = @$_POST["custom"];
		} elseif (ew_IsHttpPost()) {
			if (@$_POST["exporttype"] <> "")
				$this->Export = $_POST["exporttype"];
			$custom = @$_POST["custom"];
		} else {
			$this->setExportReturnUrl(ew_CurrentUrl());
		}
		$gsExportFile = $this->TableVar; // Get export file, used in header

		// Get custom export parameters
		if ($this->Export <> "" && $custom <> "") {
			$this->CustomExport = $this->Export;
			$this->Export = "print";
		}
		$gsCustomExport = $this->CustomExport;
		$gsExport = $this->Export; // Get export parameter, used in header

		// Update Export URLs
		if (defined("EW_USE_PHPEXCEL"))
			$this->ExportExcelCustom = FALSE;
		if ($this->ExportExcelCustom)
			$this->ExportExcelUrl .= "&amp;custom=1";
		if (defined("EW_USE_PHPWORD"))
			$this->ExportWordCustom = FALSE;
		if ($this->ExportWordCustom)
			$this->ExportWordUrl .= "&amp;custom=1";
		if ($this->ExportPdfCustom)
			$this->ExportPdfUrl .= "&amp;custom=1";
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action

		// Get grid add count
		$gridaddcnt = @$_GET[EW_TABLE_GRID_ADD_ROW_COUNT];
		if (is_numeric($gridaddcnt) && $gridaddcnt > 0)
			$this->GridAddRowCount = $gridaddcnt;

		// Set up list options
		$this->SetupListOptions();

		// Setup export options
		$this->SetupExportOptions();
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

		// Setup other options
		$this->SetupOtherOptions();

		// Set up custom action (compatible with old version)
		foreach ($this->CustomActions as $name => $action)
			$this->ListActions->Add($name, $action);

		// Show checkbox column if multiple action
		foreach ($this->ListActions->Items as $listaction) {
			if ($listaction->Select == EW_ACTION_MULTIPLE && $listaction->Allow) {
				$this->ListOptions->Items["checkbox"]->Visible = TRUE;
				break;
			}
		}
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

	// Class variables
	var $ListOptions; // List options
	var $ExportOptions; // Export options
	var $SearchOptions; // Search options
	var $OtherOptions = array(); // Other options
	var $FilterOptions; // Filter options
	var $ListActions; // List actions
	var $SelectedCount = 0;
	var $SelectedIndex = 0;
	var $DisplayRecs = 50;
	var $StartRec;
	var $StopRec;
	var $TotalRecs = 0;
	var $RecRange = 10;
	var $Pager;
	var $DefaultSearchWhere = ""; // Default search WHERE clause
	var $SearchWhere = ""; // Search WHERE clause
	var $RecCnt = 0; // Record count
	var $EditRowCnt;
	var $StartRowCnt = 1;
	var $RowCnt = 0;
	var $Attrs = array(); // Row attributes and cell attributes
	var $RowIndex = 0; // Row index
	var $KeyCount = 0; // Key count
	var $RowAction = ""; // Row action
	var $RowOldKey = ""; // Row old key (for copy)
	var $RecPerRow = 0;
	var $MultiColumnClass;
	var $MultiColumnEditClass = "col-sm-12";
	var $MultiColumnCnt = 12;
	var $MultiColumnEditCnt = 12;
	var $GridCnt = 0;
	var $ColCnt = 0;
	var $DbMasterFilter = ""; // Master filter
	var $DbDetailFilter = ""; // Detail filter
	var $MasterRecordExists;	
	var $MultiSelectKey;
	var $Command;
	var $RestoreSearch = FALSE;
	var $DetailPages;
	var $Recordset;
	var $OldRecordset;

	//
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError, $gsSearchError, $Security;

		// Search filters
		$sSrchAdvanced = ""; // Advanced search filter
		$sSrchBasic = ""; // Basic search filter
		$sFilter = "";

		// Get command
		$this->Command = strtolower(@$_GET["cmd"]);
		if ($this->IsPageRequest()) { // Validate request

			// Process list action first
			if ($this->ProcessListAction()) // Ajax request
				$this->Page_Terminate();

			// Set up records per page
			$this->SetUpDisplayRecs();

			// Handle reset command
			$this->ResetCmd();

			// Set up Breadcrumb
			if ($this->Export == "")
				$this->SetupBreadcrumb();

			// Hide list options
			if ($this->Export <> "") {
				$this->ListOptions->HideAllOptions(array("sequence"));
				$this->ListOptions->UseDropDownButton = FALSE; // Disable drop down button
				$this->ListOptions->UseButtonGroup = FALSE; // Disable button group
			} elseif ($this->CurrentAction == "gridadd" || $this->CurrentAction == "gridedit") {
				$this->ListOptions->HideAllOptions();
				$this->ListOptions->UseDropDownButton = FALSE; // Disable drop down button
				$this->ListOptions->UseButtonGroup = FALSE; // Disable button group
			}

			// Hide options
			if ($this->Export <> "" || $this->CurrentAction <> "") {
				$this->ExportOptions->HideAllOptions();
				$this->FilterOptions->HideAllOptions();
			}

			// Hide other options
			if ($this->Export <> "") {
				foreach ($this->OtherOptions as &$option)
					$option->HideAllOptions();
			}

			// Get default search criteria
			ew_AddFilter($this->DefaultSearchWhere, $this->BasicSearchWhere(TRUE));

			// Get basic search values
			$this->LoadBasicSearchValues();

			// Restore filter list
			$this->RestoreFilterList();

			// Restore search parms from Session if not searching / reset / export
			if (($this->Export <> "" || $this->Command <> "search" && $this->Command <> "reset" && $this->Command <> "resetall") && $this->CheckSearchParms())
				$this->RestoreSearchParms();

			// Call Recordset SearchValidated event
			$this->Recordset_SearchValidated();

			// Set up sorting order
			$this->SetUpSortOrder();

			// Get basic search criteria
			if ($gsSearchError == "")
				$sSrchBasic = $this->BasicSearchWhere();
		}

		// Restore display records
		if ($this->getRecordsPerPage() <> "") {
			$this->DisplayRecs = $this->getRecordsPerPage(); // Restore from Session
		} else {
			$this->DisplayRecs = 50; // Load default
		}

		// Load Sorting Order
		$this->LoadSortOrder();

		// Load search default if no existing search criteria
		if (!$this->CheckSearchParms()) {

			// Load basic search from default
			$this->BasicSearch->LoadDefault();
			if ($this->BasicSearch->Keyword != "")
				$sSrchBasic = $this->BasicSearchWhere();
		}

		// Build search criteria
		ew_AddFilter($this->SearchWhere, $sSrchAdvanced);
		ew_AddFilter($this->SearchWhere, $sSrchBasic);

		// Call Recordset_Searching event
		$this->Recordset_Searching($this->SearchWhere);

		// Save search criteria
		if ($this->Command == "search" && !$this->RestoreSearch) {
			$this->setSearchWhere($this->SearchWhere); // Save to Session
			$this->StartRec = 1; // Reset start record counter
			$this->setStartRecordNumber($this->StartRec);
		} else {
			$this->SearchWhere = $this->getSearchWhere();
		}

		// Build filter
		$sFilter = "";
		if (!$Security->CanList())
			$sFilter = "(0=1)"; // Filter all records
		ew_AddFilter($sFilter, $this->DbDetailFilter);
		ew_AddFilter($sFilter, $this->SearchWhere);

		// Set up filter in session
		$this->setSessionWhere($sFilter);
		$this->CurrentFilter = "";

		// Export data only
		if ($this->CustomExport == "" && in_array($this->Export, array("html","word","excel","xml","csv","email","pdf"))) {
			$this->ExportData();
			$this->Page_Terminate(); // Terminate response
			exit();
		}

		// Load record count first
		if (!$this->IsAddOrEdit()) {
			$bSelectLimit = $this->UseSelectLimit;
			if ($bSelectLimit) {
				$this->TotalRecs = $this->SelectRecordCount();
			} else {
				if ($this->Recordset = $this->LoadRecordset())
					$this->TotalRecs = $this->Recordset->RecordCount();
			}
		}

		// Search options
		$this->SetupSearchOptions();
	}

	// Set up number of records displayed per page
	function SetUpDisplayRecs() {
		$sWrk = @$_GET[EW_TABLE_REC_PER_PAGE];
		if ($sWrk <> "") {
			if (is_numeric($sWrk)) {
				$this->DisplayRecs = intval($sWrk);
			} else {
				if (strtolower($sWrk) == "all") { // Display all records
					$this->DisplayRecs = -1;
				} else {
					$this->DisplayRecs = 50; // Non-numeric, load default
				}
			}
			$this->setRecordsPerPage($this->DisplayRecs); // Save to Session

			// Reset start position
			$this->StartRec = 1;
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Build filter for all keys
	function BuildKeyFilter() {
		global $objForm;
		$sWrkFilter = "";

		// Update row index and get row key
		$rowindex = 1;
		$objForm->Index = $rowindex;
		$sThisKey = strval($objForm->GetValue($this->FormKeyName));
		while ($sThisKey <> "") {
			if ($this->SetupKeyValues($sThisKey)) {
				$sFilter = $this->KeyFilter();
				if ($sWrkFilter <> "") $sWrkFilter .= " OR ";
				$sWrkFilter .= $sFilter;
			} else {
				$sWrkFilter = "0=1";
				break;
			}

			// Update row index and get row key
			$rowindex++; // Next row
			$objForm->Index = $rowindex;
			$sThisKey = strval($objForm->GetValue($this->FormKeyName));
		}
		return $sWrkFilter;
	}

	// Set up key values
	function SetupKeyValues($key) {
		$arrKeyFlds = explode($GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"], $key);
		if (count($arrKeyFlds) >= 1) {
			$this->idshapefile->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->idshapefile->FormValue))
				return FALSE;
		}
		return TRUE;
	}

	// Get list of filters
	function GetFilterList() {

		// Initialize
		$sFilterList = "";
		$sFilterList = ew_Concat($sFilterList, $this->idshapefile->AdvancedSearch->ToJSON(), ","); // Field idshapefile
		$sFilterList = ew_Concat($sFilterList, $this->narchivoorigen->AdvancedSearch->ToJSON(), ","); // Field narchivoorigen
		$sFilterList = ew_Concat($sFilterList, $this->narchivo->AdvancedSearch->ToJSON(), ","); // Field narchivo
		$sFilterList = ew_Concat($sFilterList, $this->idaplicacion->AdvancedSearch->ToJSON(), ","); // Field idaplicacion
		$sFilterList = ew_Concat($sFilterList, $this->token->AdvancedSearch->ToJSON(), ","); // Field token
		$sFilterList = ew_Concat($sFilterList, $this->idusuario->AdvancedSearch->ToJSON(), ","); // Field idusuario
		$sFilterList = ew_Concat($sFilterList, $this->tipo->AdvancedSearch->ToJSON(), ","); // Field tipo
		$sFilterList = ew_Concat($sFilterList, $this->folder->AdvancedSearch->ToJSON(), ","); // Field folder
		$sFilterList = ew_Concat($sFilterList, $this->fechacreacion->AdvancedSearch->ToJSON(), ","); // Field fechacreacion
		$sFilterList = ew_Concat($sFilterList, $this->tamano->AdvancedSearch->ToJSON(), ","); // Field tamano
		$sFilterList = ew_Concat($sFilterList, $this->srid->AdvancedSearch->ToJSON(), ","); // Field srid
		$sFilterList = ew_Concat($sFilterList, $this->tipogeom->AdvancedSearch->ToJSON(), ","); // Field tipogeom
		if ($this->BasicSearch->Keyword <> "") {
			$sWrk = "\"" . EW_TABLE_BASIC_SEARCH . "\":\"" . ew_JsEncode2($this->BasicSearch->Keyword) . "\",\"" . EW_TABLE_BASIC_SEARCH_TYPE . "\":\"" . ew_JsEncode2($this->BasicSearch->Type) . "\"";
			$sFilterList = ew_Concat($sFilterList, $sWrk, ",");
		}

		// Return filter list in json
		return ($sFilterList <> "") ? "{" . $sFilterList . "}" : "null";
	}

	// Restore list of filters
	function RestoreFilterList() {

		// Return if not reset filter
		if (@$_POST["cmd"] <> "resetfilter")
			return FALSE;
		$filter = json_decode(ew_StripSlashes(@$_POST["filter"]), TRUE);
		$this->Command = "search";

		// Field idshapefile
		$this->idshapefile->AdvancedSearch->SearchValue = @$filter["x_idshapefile"];
		$this->idshapefile->AdvancedSearch->SearchOperator = @$filter["z_idshapefile"];
		$this->idshapefile->AdvancedSearch->SearchCondition = @$filter["v_idshapefile"];
		$this->idshapefile->AdvancedSearch->SearchValue2 = @$filter["y_idshapefile"];
		$this->idshapefile->AdvancedSearch->SearchOperator2 = @$filter["w_idshapefile"];
		$this->idshapefile->AdvancedSearch->Save();

		// Field narchivoorigen
		$this->narchivoorigen->AdvancedSearch->SearchValue = @$filter["x_narchivoorigen"];
		$this->narchivoorigen->AdvancedSearch->SearchOperator = @$filter["z_narchivoorigen"];
		$this->narchivoorigen->AdvancedSearch->SearchCondition = @$filter["v_narchivoorigen"];
		$this->narchivoorigen->AdvancedSearch->SearchValue2 = @$filter["y_narchivoorigen"];
		$this->narchivoorigen->AdvancedSearch->SearchOperator2 = @$filter["w_narchivoorigen"];
		$this->narchivoorigen->AdvancedSearch->Save();

		// Field narchivo
		$this->narchivo->AdvancedSearch->SearchValue = @$filter["x_narchivo"];
		$this->narchivo->AdvancedSearch->SearchOperator = @$filter["z_narchivo"];
		$this->narchivo->AdvancedSearch->SearchCondition = @$filter["v_narchivo"];
		$this->narchivo->AdvancedSearch->SearchValue2 = @$filter["y_narchivo"];
		$this->narchivo->AdvancedSearch->SearchOperator2 = @$filter["w_narchivo"];
		$this->narchivo->AdvancedSearch->Save();

		// Field idaplicacion
		$this->idaplicacion->AdvancedSearch->SearchValue = @$filter["x_idaplicacion"];
		$this->idaplicacion->AdvancedSearch->SearchOperator = @$filter["z_idaplicacion"];
		$this->idaplicacion->AdvancedSearch->SearchCondition = @$filter["v_idaplicacion"];
		$this->idaplicacion->AdvancedSearch->SearchValue2 = @$filter["y_idaplicacion"];
		$this->idaplicacion->AdvancedSearch->SearchOperator2 = @$filter["w_idaplicacion"];
		$this->idaplicacion->AdvancedSearch->Save();

		// Field token
		$this->token->AdvancedSearch->SearchValue = @$filter["x_token"];
		$this->token->AdvancedSearch->SearchOperator = @$filter["z_token"];
		$this->token->AdvancedSearch->SearchCondition = @$filter["v_token"];
		$this->token->AdvancedSearch->SearchValue2 = @$filter["y_token"];
		$this->token->AdvancedSearch->SearchOperator2 = @$filter["w_token"];
		$this->token->AdvancedSearch->Save();

		// Field idusuario
		$this->idusuario->AdvancedSearch->SearchValue = @$filter["x_idusuario"];
		$this->idusuario->AdvancedSearch->SearchOperator = @$filter["z_idusuario"];
		$this->idusuario->AdvancedSearch->SearchCondition = @$filter["v_idusuario"];
		$this->idusuario->AdvancedSearch->SearchValue2 = @$filter["y_idusuario"];
		$this->idusuario->AdvancedSearch->SearchOperator2 = @$filter["w_idusuario"];
		$this->idusuario->AdvancedSearch->Save();

		// Field tipo
		$this->tipo->AdvancedSearch->SearchValue = @$filter["x_tipo"];
		$this->tipo->AdvancedSearch->SearchOperator = @$filter["z_tipo"];
		$this->tipo->AdvancedSearch->SearchCondition = @$filter["v_tipo"];
		$this->tipo->AdvancedSearch->SearchValue2 = @$filter["y_tipo"];
		$this->tipo->AdvancedSearch->SearchOperator2 = @$filter["w_tipo"];
		$this->tipo->AdvancedSearch->Save();

		// Field folder
		$this->folder->AdvancedSearch->SearchValue = @$filter["x_folder"];
		$this->folder->AdvancedSearch->SearchOperator = @$filter["z_folder"];
		$this->folder->AdvancedSearch->SearchCondition = @$filter["v_folder"];
		$this->folder->AdvancedSearch->SearchValue2 = @$filter["y_folder"];
		$this->folder->AdvancedSearch->SearchOperator2 = @$filter["w_folder"];
		$this->folder->AdvancedSearch->Save();

		// Field fechacreacion
		$this->fechacreacion->AdvancedSearch->SearchValue = @$filter["x_fechacreacion"];
		$this->fechacreacion->AdvancedSearch->SearchOperator = @$filter["z_fechacreacion"];
		$this->fechacreacion->AdvancedSearch->SearchCondition = @$filter["v_fechacreacion"];
		$this->fechacreacion->AdvancedSearch->SearchValue2 = @$filter["y_fechacreacion"];
		$this->fechacreacion->AdvancedSearch->SearchOperator2 = @$filter["w_fechacreacion"];
		$this->fechacreacion->AdvancedSearch->Save();

		// Field tamano
		$this->tamano->AdvancedSearch->SearchValue = @$filter["x_tamano"];
		$this->tamano->AdvancedSearch->SearchOperator = @$filter["z_tamano"];
		$this->tamano->AdvancedSearch->SearchCondition = @$filter["v_tamano"];
		$this->tamano->AdvancedSearch->SearchValue2 = @$filter["y_tamano"];
		$this->tamano->AdvancedSearch->SearchOperator2 = @$filter["w_tamano"];
		$this->tamano->AdvancedSearch->Save();

		// Field srid
		$this->srid->AdvancedSearch->SearchValue = @$filter["x_srid"];
		$this->srid->AdvancedSearch->SearchOperator = @$filter["z_srid"];
		$this->srid->AdvancedSearch->SearchCondition = @$filter["v_srid"];
		$this->srid->AdvancedSearch->SearchValue2 = @$filter["y_srid"];
		$this->srid->AdvancedSearch->SearchOperator2 = @$filter["w_srid"];
		$this->srid->AdvancedSearch->Save();

		// Field tipogeom
		$this->tipogeom->AdvancedSearch->SearchValue = @$filter["x_tipogeom"];
		$this->tipogeom->AdvancedSearch->SearchOperator = @$filter["z_tipogeom"];
		$this->tipogeom->AdvancedSearch->SearchCondition = @$filter["v_tipogeom"];
		$this->tipogeom->AdvancedSearch->SearchValue2 = @$filter["y_tipogeom"];
		$this->tipogeom->AdvancedSearch->SearchOperator2 = @$filter["w_tipogeom"];
		$this->tipogeom->AdvancedSearch->Save();
		$this->BasicSearch->setKeyword(@$filter[EW_TABLE_BASIC_SEARCH]);
		$this->BasicSearch->setType(@$filter[EW_TABLE_BASIC_SEARCH_TYPE]);
	}

	// Return basic search SQL
	function BasicSearchSQL($arKeywords, $type) {
		$sWhere = "";
		$this->BuildBasicSearchSQL($sWhere, $this->narchivoorigen, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->narchivo, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->idaplicacion, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->token, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->tipo, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->folder, $arKeywords, $type);
		return $sWhere;
	}

	// Build basic search SQL
	function BuildBasicSearchSql(&$Where, &$Fld, $arKeywords, $type) {
		$sDefCond = ($type == "OR") ? "OR" : "AND";
		$arSQL = array(); // Array for SQL parts
		$arCond = array(); // Array for search conditions
		$cnt = count($arKeywords);
		$j = 0; // Number of SQL parts
		for ($i = 0; $i < $cnt; $i++) {
			$Keyword = $arKeywords[$i];
			$Keyword = trim($Keyword);
			if (EW_BASIC_SEARCH_IGNORE_PATTERN <> "") {
				$Keyword = preg_replace(EW_BASIC_SEARCH_IGNORE_PATTERN, "\\", $Keyword);
				$ar = explode("\\", $Keyword);
			} else {
				$ar = array($Keyword);
			}
			foreach ($ar as $Keyword) {
				if ($Keyword <> "") {
					$sWrk = "";
					if ($Keyword == "OR" && $type == "") {
						if ($j > 0)
							$arCond[$j-1] = "OR";
					} elseif ($Keyword == EW_NULL_VALUE) {
						$sWrk = $Fld->FldExpression . " IS NULL";
					} elseif ($Keyword == EW_NOT_NULL_VALUE) {
						$sWrk = $Fld->FldExpression . " IS NOT NULL";
					} elseif ($Fld->FldIsVirtual && $Fld->FldVirtualSearch) {
						$sWrk = $Fld->FldVirtualExpression . ew_Like(ew_QuotedValue("%" . $Keyword . "%", EW_DATATYPE_STRING, $this->DBID), $this->DBID);
					} elseif ($Fld->FldDataType != EW_DATATYPE_NUMBER || is_numeric($Keyword)) {
						$sWrk = $Fld->FldBasicSearchExpression . ew_Like(ew_QuotedValue("%" . $Keyword . "%", EW_DATATYPE_STRING, $this->DBID), $this->DBID);
					}
					if ($sWrk <> "") {
						$arSQL[$j] = $sWrk;
						$arCond[$j] = $sDefCond;
						$j += 1;
					}
				}
			}
		}
		$cnt = count($arSQL);
		$bQuoted = FALSE;
		$sSql = "";
		if ($cnt > 0) {
			for ($i = 0; $i < $cnt-1; $i++) {
				if ($arCond[$i] == "OR") {
					if (!$bQuoted) $sSql .= "(";
					$bQuoted = TRUE;
				}
				$sSql .= $arSQL[$i];
				if ($bQuoted && $arCond[$i] <> "OR") {
					$sSql .= ")";
					$bQuoted = FALSE;
				}
				$sSql .= " " . $arCond[$i] . " ";
			}
			$sSql .= $arSQL[$cnt-1];
			if ($bQuoted)
				$sSql .= ")";
		}
		if ($sSql <> "") {
			if ($Where <> "") $Where .= " OR ";
			$Where .=  "(" . $sSql . ")";
		}
	}

	// Return basic search WHERE clause based on search keyword and type
	function BasicSearchWhere($Default = FALSE) {
		global $Security;
		$sSearchStr = "";
		if (!$Security->CanSearch()) return "";
		$sSearchKeyword = ($Default) ? $this->BasicSearch->KeywordDefault : $this->BasicSearch->Keyword;
		$sSearchType = ($Default) ? $this->BasicSearch->TypeDefault : $this->BasicSearch->Type;
		if ($sSearchKeyword <> "") {
			$sSearch = trim($sSearchKeyword);
			if ($sSearchType <> "=") {
				$ar = array();

				// Match quoted keywords (i.e.: "...")
				if (preg_match_all('/"([^"]*)"/i', $sSearch, $matches, PREG_SET_ORDER)) {
					foreach ($matches as $match) {
						$p = strpos($sSearch, $match[0]);
						$str = substr($sSearch, 0, $p);
						$sSearch = substr($sSearch, $p + strlen($match[0]));
						if (strlen(trim($str)) > 0)
							$ar = array_merge($ar, explode(" ", trim($str)));
						$ar[] = $match[1]; // Save quoted keyword
					}
				}

				// Match individual keywords
				if (strlen(trim($sSearch)) > 0)
					$ar = array_merge($ar, explode(" ", trim($sSearch)));

				// Search keyword in any fields
				if (($sSearchType == "OR" || $sSearchType == "AND") && $this->BasicSearch->BasicSearchAnyFields) {
					foreach ($ar as $sKeyword) {
						if ($sKeyword <> "") {
							if ($sSearchStr <> "") $sSearchStr .= " " . $sSearchType . " ";
							$sSearchStr .= "(" . $this->BasicSearchSQL(array($sKeyword), $sSearchType) . ")";
						}
					}
				} else {
					$sSearchStr = $this->BasicSearchSQL($ar, $sSearchType);
				}
			} else {
				$sSearchStr = $this->BasicSearchSQL(array($sSearch), $sSearchType);
			}
			if (!$Default) $this->Command = "search";
		}
		if (!$Default && $this->Command == "search") {
			$this->BasicSearch->setKeyword($sSearchKeyword);
			$this->BasicSearch->setType($sSearchType);
		}
		return $sSearchStr;
	}

	// Check if search parm exists
	function CheckSearchParms() {

		// Check basic search
		if ($this->BasicSearch->IssetSession())
			return TRUE;
		return FALSE;
	}

	// Clear all search parameters
	function ResetSearchParms() {

		// Clear search WHERE clause
		$this->SearchWhere = "";
		$this->setSearchWhere($this->SearchWhere);

		// Clear basic search parameters
		$this->ResetBasicSearchParms();
	}

	// Load advanced search default values
	function LoadAdvancedSearchDefault() {
		return FALSE;
	}

	// Clear all basic search parameters
	function ResetBasicSearchParms() {
		$this->BasicSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore basic search values
		$this->BasicSearch->Load();
	}

	// Set up sort parameters
	function SetUpSortOrder() {

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->idshapefile); // idshapefile
			$this->UpdateSort($this->narchivoorigen); // narchivoorigen
			$this->UpdateSort($this->narchivo); // narchivo
			$this->UpdateSort($this->idaplicacion); // idaplicacion
			$this->UpdateSort($this->token); // token
			$this->UpdateSort($this->idusuario); // idusuario
			$this->UpdateSort($this->tipo); // tipo
			$this->UpdateSort($this->folder); // folder
			$this->UpdateSort($this->fechacreacion); // fechacreacion
			$this->UpdateSort($this->tamano); // tamano
			$this->UpdateSort($this->srid); // srid
			$this->UpdateSort($this->tipogeom); // tipogeom
			$this->setStartRecordNumber(1); // Reset start position
		}
	}

	// Load sort order parameters
	function LoadSortOrder() {
		$sOrderBy = $this->getSessionOrderBy(); // Get ORDER BY from Session
		if ($sOrderBy == "") {
			if ($this->getSqlOrderBy() <> "") {
				$sOrderBy = $this->getSqlOrderBy();
				$this->setSessionOrderBy($sOrderBy);
			}
		}
	}

	// Reset command
	// - cmd=reset (Reset search parameters)
	// - cmd=resetall (Reset search and master/detail parameters)
	// - cmd=resetsort (Reset sort parameters)
	function ResetCmd() {

		// Check if reset command
		if (substr($this->Command,0,5) == "reset") {

			// Reset search criteria
			if ($this->Command == "reset" || $this->Command == "resetall")
				$this->ResetSearchParms();

			// Reset sorting order
			if ($this->Command == "resetsort") {
				$sOrderBy = "";
				$this->setSessionOrderBy($sOrderBy);
				$this->idshapefile->setSort("");
				$this->narchivoorigen->setSort("");
				$this->narchivo->setSort("");
				$this->idaplicacion->setSort("");
				$this->token->setSort("");
				$this->idusuario->setSort("");
				$this->tipo->setSort("");
				$this->folder->setSort("");
				$this->fechacreacion->setSort("");
				$this->tamano->setSort("");
				$this->srid->setSort("");
				$this->tipogeom->setSort("");
			}

			// Reset start position
			$this->StartRec = 1;
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Set up list options
	function SetupListOptions() {
		global $Security, $Language;

		// Add group option item
		$item = &$this->ListOptions->Add($this->ListOptions->GroupOptionName);
		$item->Body = "";
		$item->OnLeft = TRUE;
		$item->Visible = FALSE;

		// "view"
		$item = &$this->ListOptions->Add("view");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->CanView();
		$item->OnLeft = TRUE;

		// "edit"
		$item = &$this->ListOptions->Add("edit");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->CanEdit();
		$item->OnLeft = TRUE;

		// List actions
		$item = &$this->ListOptions->Add("listactions");
		$item->CssStyle = "white-space: nowrap;";
		$item->OnLeft = TRUE;
		$item->Visible = FALSE;
		$item->ShowInButtonGroup = FALSE;
		$item->ShowInDropDown = FALSE;

		// "checkbox"
		$item = &$this->ListOptions->Add("checkbox");
		$item->Visible = $Security->CanDelete();
		$item->OnLeft = TRUE;
		$item->Header = "<input type=\"checkbox\" name=\"key\" id=\"key\" onclick=\"ew_SelectAllKey(this);\">";
		$item->MoveTo(0);
		$item->ShowInDropDown = FALSE;
		$item->ShowInButtonGroup = FALSE;

		// Drop down button for ListOptions
		$this->ListOptions->UseImageAndText = TRUE;
		$this->ListOptions->UseDropDownButton = FALSE;
		$this->ListOptions->DropDownButtonPhrase = $Language->Phrase("ButtonListOptions");
		$this->ListOptions->UseButtonGroup = TRUE;
		if ($this->ListOptions->UseButtonGroup && ew_IsMobile())
			$this->ListOptions->UseDropDownButton = TRUE;
		$this->ListOptions->ButtonClass = "btn-sm"; // Class for button group

		// Call ListOptions_Load event
		$this->ListOptions_Load();
		$this->SetupListOptionsExt();
		$item = &$this->ListOptions->GetItem($this->ListOptions->GroupOptionName);
		$item->Visible = $this->ListOptions->GroupOptionVisible();
	}

	// Render list options
	function RenderListOptions() {
		global $Security, $Language, $objForm;
		$this->ListOptions->LoadDefault();

		// "view"
		$oListOpt = &$this->ListOptions->Items["view"];
		if ($Security->CanView())
			$oListOpt->Body = "<a class=\"ewRowLink ewView\" title=\"" . ew_HtmlTitle($Language->Phrase("ViewLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("ViewLink")) . "\" href=\"" . ew_HtmlEncode($this->ViewUrl) . "\">" . $Language->Phrase("ViewLink") . "</a>";
		else
			$oListOpt->Body = "";

		// "edit"
		$oListOpt = &$this->ListOptions->Items["edit"];
		if ($Security->CanEdit()) {
			$oListOpt->Body = "<a class=\"ewRowLink ewEdit\" title=\"" . ew_HtmlTitle($Language->Phrase("EditLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("EditLink")) . "\" href=\"" . ew_HtmlEncode($this->EditUrl) . "\">" . $Language->Phrase("EditLink") . "</a>";
		} else {
			$oListOpt->Body = "";
		}

		// Set up list action buttons
		$oListOpt = &$this->ListOptions->GetItem("listactions");
		if ($oListOpt) {
			$body = "";
			$links = array();
			foreach ($this->ListActions->Items as $listaction) {
				if ($listaction->Select == EW_ACTION_SINGLE && $listaction->Allow) {
					$action = $listaction->Action;
					$caption = $listaction->Caption;
					$icon = ($listaction->Icon <> "") ? "<span class=\"" . ew_HtmlEncode(str_replace(" ewIcon", "", $listaction->Icon)) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\"></span> " : "";
					$links[] = "<li><a class=\"ewAction ewListAction\" data-action=\"" . ew_HtmlEncode($action) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({key:" . $this->KeyToJson() . "}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . $listaction->Caption . "</a></li>";
					if (count($links) == 1) // Single button
						$body = "<a class=\"ewAction ewListAction\" data-action=\"" . ew_HtmlEncode($action) . "\" title=\"" . ew_HtmlTitle($caption) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({key:" . $this->KeyToJson() . "}," . $listaction->ToJson(TRUE) . "));return false;\">" . $Language->Phrase("ListActionButton") . "</a>";
				}
			}
			if (count($links) > 1) { // More than one buttons, use dropdown
				$body = "<button class=\"dropdown-toggle btn btn-default btn-sm ewActions\" title=\"" . ew_HtmlTitle($Language->Phrase("ListActionButton")) . "\" data-toggle=\"dropdown\">" . $Language->Phrase("ListActionButton") . "<b class=\"caret\"></b></button>";
				$content = "";
				foreach ($links as $link)
					$content .= "<li>" . $link . "</li>";
				$body .= "<ul class=\"dropdown-menu" . ($oListOpt->OnLeft ? "" : " dropdown-menu-right") . "\">". $content . "</ul>";
				$body = "<div class=\"btn-group\">" . $body . "</div>";
			}
			if (count($links) > 0) {
				$oListOpt->Body = $body;
				$oListOpt->Visible = TRUE;
			}
		}

		// "checkbox"
		$oListOpt = &$this->ListOptions->Items["checkbox"];
		$oListOpt->Body = "<input type=\"checkbox\" name=\"key_m[]\" value=\"" . ew_HtmlEncode($this->idshapefile->CurrentValue) . "\" onclick='ew_ClickMultiCheckbox(event);'>";
		$this->RenderListOptionsExt();

		// Call ListOptions_Rendered event
		$this->ListOptions_Rendered();
	}

	// Set up other options
	function SetupOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
		$option = $options["addedit"];

		// Add
		$item = &$option->Add("add");
		$item->Body = "<a class=\"ewAddEdit ewAdd\" title=\"" . ew_HtmlTitle($Language->Phrase("AddLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("AddLink")) . "\" href=\"" . ew_HtmlEncode($this->AddUrl) . "\">" . $Language->Phrase("AddLink") . "</a>";
		$item->Visible = ($this->AddUrl <> "" && $Security->CanAdd());
		$option = $options["action"];

		// Add multi delete
		$item = &$option->Add("multidelete");
		$item->Body = "<a class=\"ewAction ewMultiDelete\" title=\"" . ew_HtmlTitle($Language->Phrase("DeleteSelectedLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("DeleteSelectedLink")) . "\" href=\"\" onclick=\"ew_SubmitAction(event,{f:document.fshapefileslist,url:'" . $this->MultiDeleteUrl . "',msg:ewLanguage.Phrase('DeleteConfirmMsg')});return false;\">" . $Language->Phrase("DeleteSelectedLink") . "</a>";
		$item->Visible = ($Security->CanDelete());

		// Set up options default
		foreach ($options as &$option) {
			$option->UseImageAndText = TRUE;
			$option->UseDropDownButton = FALSE;
			$option->UseButtonGroup = TRUE;
			$option->ButtonClass = "btn-sm"; // Class for button group
			$item = &$option->Add($option->GroupOptionName);
			$item->Body = "";
			$item->Visible = FALSE;
		}
		$options["addedit"]->DropDownButtonPhrase = $Language->Phrase("ButtonAddEdit");
		$options["detail"]->DropDownButtonPhrase = $Language->Phrase("ButtonDetails");
		$options["action"]->DropDownButtonPhrase = $Language->Phrase("ButtonActions");

		// Filter button
		$item = &$this->FilterOptions->Add("savecurrentfilter");
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"fshapefileslistsrch\" href=\"#\">" . $Language->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = TRUE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"fshapefileslistsrch\" href=\"#\">" . $Language->Phrase("DeleteFilter") . "</a>";
		$item->Visible = TRUE;
		$this->FilterOptions->UseDropDownButton = TRUE;
		$this->FilterOptions->UseButtonGroup = !$this->FilterOptions->UseDropDownButton;
		$this->FilterOptions->DropDownButtonPhrase = $Language->Phrase("Filters");

		// Add group option item
		$item = &$this->FilterOptions->Add($this->FilterOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;
	}

	// Render other options
	function RenderOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
			$option = &$options["action"];

			// Set up list action buttons
			foreach ($this->ListActions->Items as $listaction) {
				if ($listaction->Select == EW_ACTION_MULTIPLE) {
					$item = &$option->Add("custom_" . $listaction->Action);
					$caption = $listaction->Caption;
					$icon = ($listaction->Icon <> "") ? "<span class=\"" . ew_HtmlEncode($listaction->Icon) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\"></span> " : $caption;
					$item->Body = "<a class=\"ewAction ewListAction\" title=\"" . ew_HtmlEncode($caption) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({f:document.fshapefileslist}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . "</a>";
					$item->Visible = $listaction->Allow;
				}
			}

			// Hide grid edit and other options
			if ($this->TotalRecs <= 0) {
				$option = &$options["addedit"];
				$item = &$option->GetItem("gridedit");
				if ($item) $item->Visible = FALSE;
				$option = &$options["action"];
				$option->HideAllOptions();
			}
	}

	// Process list action
	function ProcessListAction() {
		global $Language, $Security;
		$userlist = "";
		$user = "";
		$sFilter = $this->GetKeyFilter();
		$UserAction = @$_POST["useraction"];
		if ($sFilter <> "" && $UserAction <> "") {

			// Check permission first
			$ActionCaption = $UserAction;
			if (array_key_exists($UserAction, $this->ListActions->Items)) {
				$ActionCaption = $this->ListActions->Items[$UserAction]->Caption;
				if (!$this->ListActions->Items[$UserAction]->Allow) {
					$errmsg = str_replace('%s', $ActionCaption, $Language->Phrase("CustomActionNotAllowed"));
					if (@$_POST["ajax"] == $UserAction) // Ajax
						echo "<p class=\"text-danger\">" . $errmsg . "</p>";
					else
						$this->setFailureMessage($errmsg);
					return FALSE;
				}
			}
			$this->CurrentFilter = $sFilter;
			$sSql = $this->SQL();
			$conn = &$this->Connection();
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			$rs = $conn->Execute($sSql);
			$conn->raiseErrorFn = '';
			$this->CurrentAction = $UserAction;

			// Call row action event
			if ($rs && !$rs->EOF) {
				$conn->BeginTrans();
				$this->SelectedCount = $rs->RecordCount();
				$this->SelectedIndex = 0;
				while (!$rs->EOF) {
					$this->SelectedIndex++;
					$row = $rs->fields;
					$Processed = $this->Row_CustomAction($UserAction, $row);
					if (!$Processed) break;
					$rs->MoveNext();
				}
				if ($Processed) {
					$conn->CommitTrans(); // Commit the changes
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage(str_replace('%s', $ActionCaption, $Language->Phrase("CustomActionCompleted"))); // Set up success message
				} else {
					$conn->RollbackTrans(); // Rollback changes

					// Set up error message
					if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

						// Use the message, do nothing
					} elseif ($this->CancelMessage <> "") {
						$this->setFailureMessage($this->CancelMessage);
						$this->CancelMessage = "";
					} else {
						$this->setFailureMessage(str_replace('%s', $ActionCaption, $Language->Phrase("CustomActionFailed")));
					}
				}
			}
			if ($rs)
				$rs->Close();
			$this->CurrentAction = ""; // Clear action
			if (@$_POST["ajax"] == $UserAction) { // Ajax
				if ($this->getSuccessMessage() <> "") {
					echo "<p class=\"text-success\">" . $this->getSuccessMessage() . "</p>";
					$this->ClearSuccessMessage(); // Clear message
				}
				if ($this->getFailureMessage() <> "") {
					echo "<p class=\"text-danger\">" . $this->getFailureMessage() . "</p>";
					$this->ClearFailureMessage(); // Clear message
				}
				return TRUE;
			}
		}
		return FALSE; // Not ajax request
	}

	// Set up search options
	function SetupSearchOptions() {
		global $Language;
		$this->SearchOptions = new cListOptions();
		$this->SearchOptions->Tag = "div";
		$this->SearchOptions->TagClassName = "ewSearchOption";

		// Search button
		$item = &$this->SearchOptions->Add("searchtoggle");
		$SearchToggleClass = ($this->SearchWhere <> "") ? " active" : "";
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"fshapefileslistsrch\">" . $Language->Phrase("SearchBtn") . "</button>";
		$item->Visible = TRUE;

		// Show all button
		$item = &$this->SearchOptions->Add("showall");
		$item->Body = "<a class=\"btn btn-default ewShowAll\" title=\"" . $Language->Phrase("ShowAll") . "\" data-caption=\"" . $Language->Phrase("ShowAll") . "\" href=\"" . $this->PageUrl() . "cmd=reset\">" . $Language->Phrase("ShowAllBtn") . "</a>";
		$item->Visible = ($this->SearchWhere <> $this->DefaultSearchWhere && $this->SearchWhere <> "0=101");

		// Button group for search
		$this->SearchOptions->UseDropDownButton = FALSE;
		$this->SearchOptions->UseImageAndText = TRUE;
		$this->SearchOptions->UseButtonGroup = TRUE;
		$this->SearchOptions->DropDownButtonPhrase = $Language->Phrase("ButtonSearch");

		// Add group option item
		$item = &$this->SearchOptions->Add($this->SearchOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;

		// Hide search options
		if ($this->Export <> "" || $this->CurrentAction <> "")
			$this->SearchOptions->HideAllOptions();
		global $Security;
		if (!$Security->CanSearch()) {
			$this->SearchOptions->HideAllOptions();
			$this->FilterOptions->HideAllOptions();
		}
	}

	function SetupListOptionsExt() {
		global $Security, $Language;
	}

	function RenderListOptionsExt() {
		global $Security, $Language;
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

	// Load basic search values
	function LoadBasicSearchValues() {
		$this->BasicSearch->Keyword = @$_GET[EW_TABLE_BASIC_SEARCH];
		if ($this->BasicSearch->Keyword <> "") $this->Command = "search";
		$this->BasicSearch->Type = @$_GET[EW_TABLE_BASIC_SEARCH_TYPE];
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
		$this->ViewUrl = $this->GetViewUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->InlineEditUrl = $this->GetInlineEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->InlineCopyUrl = $this->GetInlineCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();

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
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Set up export options
	function SetupExportOptions() {
		global $Language;

		// Printer friendly
		$item = &$this->ExportOptions->Add("print");
		$item->Body = "<a href=\"" . $this->ExportPrintUrl . "\" class=\"ewExportLink ewPrint\" title=\"" . ew_HtmlEncode($Language->Phrase("PrinterFriendlyText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("PrinterFriendlyText")) . "\">" . $Language->Phrase("PrinterFriendly") . "</a>";
		$item->Visible = TRUE;

		// Export to Excel
		$item = &$this->ExportOptions->Add("excel");
		$item->Body = "<a href=\"" . $this->ExportExcelUrl . "\" class=\"ewExportLink ewExcel\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToExcelText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToExcelText")) . "\">" . $Language->Phrase("ExportToExcel") . "</a>";
		$item->Visible = TRUE;

		// Export to Word
		$item = &$this->ExportOptions->Add("word");
		$item->Body = "<a href=\"" . $this->ExportWordUrl . "\" class=\"ewExportLink ewWord\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToWordText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToWordText")) . "\">" . $Language->Phrase("ExportToWord") . "</a>";
		$item->Visible = TRUE;

		// Export to Html
		$item = &$this->ExportOptions->Add("html");
		$item->Body = "<a href=\"" . $this->ExportHtmlUrl . "\" class=\"ewExportLink ewHtml\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToHtmlText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToHtmlText")) . "\">" . $Language->Phrase("ExportToHtml") . "</a>";
		$item->Visible = FALSE;

		// Export to Xml
		$item = &$this->ExportOptions->Add("xml");
		$item->Body = "<a href=\"" . $this->ExportXmlUrl . "\" class=\"ewExportLink ewXml\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToXmlText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToXmlText")) . "\">" . $Language->Phrase("ExportToXml") . "</a>";
		$item->Visible = FALSE;

		// Export to Csv
		$item = &$this->ExportOptions->Add("csv");
		$item->Body = "<a href=\"" . $this->ExportCsvUrl . "\" class=\"ewExportLink ewCsv\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToCsvText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToCsvText")) . "\">" . $Language->Phrase("ExportToCsv") . "</a>";
		$item->Visible = FALSE;

		// Export to Pdf
		$item = &$this->ExportOptions->Add("pdf");
		$item->Body = "<a href=\"" . $this->ExportPdfUrl . "\" class=\"ewExportLink ewPdf\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToPDFText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToPDFText")) . "\">" . $Language->Phrase("ExportToPDF") . "</a>";
		$item->Visible = FALSE;

		// Export to Email
		$item = &$this->ExportOptions->Add("email");
		$url = "";
		$item->Body = "<button id=\"emf_shapefiles\" class=\"ewExportLink ewEmail\" title=\"" . $Language->Phrase("ExportToEmailText") . "\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_shapefiles',hdr:ewLanguage.Phrase('ExportToEmailText'),f:document.fshapefileslist,sel:false" . $url . "});\">" . $Language->Phrase("ExportToEmail") . "</button>";
		$item->Visible = FALSE;

		// Drop down button for export
		$this->ExportOptions->UseButtonGroup = TRUE;
		$this->ExportOptions->UseImageAndText = TRUE;
		$this->ExportOptions->UseDropDownButton = FALSE;
		if ($this->ExportOptions->UseButtonGroup && ew_IsMobile())
			$this->ExportOptions->UseDropDownButton = TRUE;
		$this->ExportOptions->DropDownButtonPhrase = $Language->Phrase("ButtonExport");

		// Add group option item
		$item = &$this->ExportOptions->Add($this->ExportOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;
	}

	// Export data in HTML/CSV/Word/Excel/XML/Email/PDF format
	function ExportData() {
		$utf8 = (strtolower(EW_CHARSET) == "utf-8");
		$bSelectLimit = $this->UseSelectLimit;

		// Load recordset
		if ($bSelectLimit) {
			$this->TotalRecs = $this->SelectRecordCount();
		} else {
			if (!$this->Recordset)
				$this->Recordset = $this->LoadRecordset();
			$rs = &$this->Recordset;
			if ($rs)
				$this->TotalRecs = $rs->RecordCount();
		}
		$this->StartRec = 1;

		// Export all
		if ($this->ExportAll) {
			set_time_limit(EW_EXPORT_ALL_TIME_LIMIT);
			$this->DisplayRecs = $this->TotalRecs;
			$this->StopRec = $this->TotalRecs;
		} else { // Export one page only
			$this->SetUpStartRec(); // Set up start record position

			// Set the last record to display
			if ($this->DisplayRecs <= 0) {
				$this->StopRec = $this->TotalRecs;
			} else {
				$this->StopRec = $this->StartRec + $this->DisplayRecs - 1;
			}
		}
		if ($bSelectLimit)
			$rs = $this->LoadRecordset($this->StartRec-1, $this->DisplayRecs <= 0 ? $this->TotalRecs : $this->DisplayRecs);
		if (!$rs) {
			header("Content-Type:"); // Remove header
			header("Content-Disposition:");
			$this->ShowMessage();
			return;
		}
		$this->ExportDoc = ew_ExportDocument($this, "h");
		$Doc = &$this->ExportDoc;
		if ($bSelectLimit) {
			$this->StartRec = 1;
			$this->StopRec = $this->DisplayRecs <= 0 ? $this->TotalRecs : $this->DisplayRecs;
		} else {

			//$this->StartRec = $this->StartRec;
			//$this->StopRec = $this->StopRec;

		}

		// Call Page Exporting server event
		$this->ExportDoc->ExportCustom = !$this->Page_Exporting();
		$ParentTable = "";
		$sHeader = $this->PageHeader;
		$this->Page_DataRendering($sHeader);
		$Doc->Text .= $sHeader;
		$this->ExportDocument($Doc, $rs, $this->StartRec, $this->StopRec, "");
		$sFooter = $this->PageFooter;
		$this->Page_DataRendered($sFooter);
		$Doc->Text .= $sFooter;

		// Close recordset
		$rs->Close();

		// Call Page Exported server event
		$this->Page_Exported();

		// Export header and footer
		$Doc->ExportHeaderAndFooter();

		// Clean output buffer
		if (!EW_DEBUG_ENABLED && ob_get_length())
			ob_end_clean();

		// Write debug message if enabled
		if (EW_DEBUG_ENABLED && $this->Export <> "pdf")
			echo ew_DebugMsg();

		// Output data
		$Doc->Export();
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$url = preg_replace('/\?cmd=reset(all){0,1}$/i', '', $url); // Remove cmd=reset / cmd=resetall
		$Breadcrumb->Add("list", $this->TableVar, $url, "", $this->TableVar, TRUE);
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

	// ListOptions Load event
	function ListOptions_Load() {

		// Example:
		//$opt = &$this->ListOptions->Add("new");
		//$opt->Header = "xxx";
		//$opt->OnLeft = TRUE; // Link on left
		//$opt->MoveTo(0); // Move to first column

	}

	// ListOptions Rendered event
	function ListOptions_Rendered() {

		// Example: 
		//$this->ListOptions->Items["new"]->Body = "xxx";

	}

	// Row Custom Action event
	function Row_CustomAction($action, $row) {

		// Return FALSE to abort
		return TRUE;
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
if (!isset($shapefiles_list)) $shapefiles_list = new cshapefiles_list();

// Page init
$shapefiles_list->Page_Init();

// Page main
$shapefiles_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$shapefiles_list->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($shapefiles->Export == "") { ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "list";
var CurrentForm = fshapefileslist = new ew_Form("fshapefileslist", "list");
fshapefileslist.FormKeyCountName = '<?php echo $shapefiles_list->FormKeyCountName ?>';

// Form_CustomValidate event
fshapefileslist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fshapefileslist.ValidateRequired = true;
<?php } else { ?>
fshapefileslist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fshapefileslist.Lists["x_srid"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fshapefileslist.Lists["x_srid"].Options = <?php echo json_encode($shapefiles->srid->Options()) ?>;

// Form object for search
var CurrentSearchForm = fshapefileslistsrch = new ew_Form("fshapefileslistsrch");

// Init search panel as collapsed
if (fshapefileslistsrch) fshapefileslistsrch.InitSearchPanel = true;
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($shapefiles->Export == "") { ?>
<div class="ewToolbar">
<?php if ($shapefiles->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if ($shapefiles_list->TotalRecs > 0 && $shapefiles_list->ExportOptions->Visible()) { ?>
<?php $shapefiles_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($shapefiles_list->SearchOptions->Visible()) { ?>
<?php $shapefiles_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php if ($shapefiles_list->FilterOptions->Visible()) { ?>
<?php $shapefiles_list->FilterOptions->Render("body") ?>
<?php } ?>
<?php if ($shapefiles->Export == "") { ?>
<?php echo $Language->SelectionForm(); ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php
	$bSelectLimit = $shapefiles_list->UseSelectLimit;
	if ($bSelectLimit) {
		if ($shapefiles_list->TotalRecs <= 0)
			$shapefiles_list->TotalRecs = $shapefiles->SelectRecordCount();
	} else {
		if (!$shapefiles_list->Recordset && ($shapefiles_list->Recordset = $shapefiles_list->LoadRecordset()))
			$shapefiles_list->TotalRecs = $shapefiles_list->Recordset->RecordCount();
	}
	$shapefiles_list->StartRec = 1;
	if ($shapefiles_list->DisplayRecs <= 0 || ($shapefiles->Export <> "" && $shapefiles->ExportAll)) // Display all records
		$shapefiles_list->DisplayRecs = $shapefiles_list->TotalRecs;
	if (!($shapefiles->Export <> "" && $shapefiles->ExportAll))
		$shapefiles_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$shapefiles_list->Recordset = $shapefiles_list->LoadRecordset($shapefiles_list->StartRec-1, $shapefiles_list->DisplayRecs);

	// Set no record found message
	if ($shapefiles->CurrentAction == "" && $shapefiles_list->TotalRecs == 0) {
		if (!$Security->CanList())
			$shapefiles_list->setWarningMessage($Language->Phrase("NoPermission"));
		if ($shapefiles_list->SearchWhere == "0=101")
			$shapefiles_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$shapefiles_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
$shapefiles_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($shapefiles->Export == "" && $shapefiles->CurrentAction == "") { ?>
<form name="fshapefileslistsrch" id="fshapefileslistsrch" class="form-inline ewForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($shapefiles_list->SearchWhere <> "") ? " in" : ""; ?>
<div id="fshapefileslistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="shapefiles">
	<div class="ewBasicSearch">
<div id="xsr_1" class="ewRow">
	<div class="ewQuickSearch input-group">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="form-control" value="<?php echo ew_HtmlEncode($shapefiles_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo ew_HtmlEncode($Language->Phrase("Search")) ?>">
	<input type="hidden" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" id="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="<?php echo ew_HtmlEncode($shapefiles_list->BasicSearch->getType()) ?>">
	<div class="input-group-btn">
		<button type="button" data-toggle="dropdown" class="btn btn-default"><span id="searchtype"><?php echo $shapefiles_list->BasicSearch->getTypeNameShort() ?></span><span class="caret"></span></button>
		<ul class="dropdown-menu pull-right" role="menu">
			<li<?php if ($shapefiles_list->BasicSearch->getType() == "") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this)"><?php echo $Language->Phrase("QuickSearchAuto") ?></a></li>
			<li<?php if ($shapefiles_list->BasicSearch->getType() == "=") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'=')"><?php echo $Language->Phrase("QuickSearchExact") ?></a></li>
			<li<?php if ($shapefiles_list->BasicSearch->getType() == "AND") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'AND')"><?php echo $Language->Phrase("QuickSearchAll") ?></a></li>
			<li<?php if ($shapefiles_list->BasicSearch->getType() == "OR") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'OR')"><?php echo $Language->Phrase("QuickSearchAny") ?></a></li>
		</ul>
	<button class="btn btn-primary ewButton" name="btnsubmit" id="btnsubmit" type="submit"><?php echo $Language->Phrase("QuickSearchBtn") ?></button>
	</div>
	</div>
</div>
	</div>
</div>
</form>
<?php } ?>
<?php } ?>
<?php $shapefiles_list->ShowPageHeader(); ?>
<?php
$shapefiles_list->ShowMessage();
?>
<?php if ($shapefiles_list->TotalRecs > 0 || $shapefiles->CurrentAction <> "") { ?>
<div class="panel panel-default ewGrid">
<?php if ($shapefiles->Export == "") { ?>
<div class="panel-heading ewGridUpperPanel">
<?php if ($shapefiles->CurrentAction <> "gridadd" && $shapefiles->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="form-inline ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($shapefiles_list->Pager)) $shapefiles_list->Pager = new cPrevNextPager($shapefiles_list->StartRec, $shapefiles_list->DisplayRecs, $shapefiles_list->TotalRecs) ?>
<?php if ($shapefiles_list->Pager->RecordCount > 0) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($shapefiles_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $shapefiles_list->PageUrl() ?>start=<?php echo $shapefiles_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($shapefiles_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $shapefiles_list->PageUrl() ?>start=<?php echo $shapefiles_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $shapefiles_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($shapefiles_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $shapefiles_list->PageUrl() ?>start=<?php echo $shapefiles_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($shapefiles_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $shapefiles_list->PageUrl() ?>start=<?php echo $shapefiles_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $shapefiles_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $shapefiles_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $shapefiles_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $shapefiles_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
<?php if ($shapefiles_list->TotalRecs > 0) { ?>
<div class="ewPager">
<input type="hidden" name="t" value="shapefiles">
<select name="<?php echo EW_TABLE_REC_PER_PAGE ?>" class="form-control input-sm" onchange="this.form.submit();">
<option value="10"<?php if ($shapefiles_list->DisplayRecs == 10) { ?> selected<?php } ?>>10</option>
<option value="20"<?php if ($shapefiles_list->DisplayRecs == 20) { ?> selected<?php } ?>>20</option>
<option value="50"<?php if ($shapefiles_list->DisplayRecs == 50) { ?> selected<?php } ?>>50</option>
<option value="ALL"<?php if ($shapefiles->getRecordsPerPage() == -1) { ?> selected<?php } ?>><?php echo $Language->Phrase("AllRecords") ?></option>
</select>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($shapefiles_list->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
<form name="fshapefileslist" id="fshapefileslist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($shapefiles_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $shapefiles_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="shapefiles">
<div id="gmp_shapefiles" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<?php if ($shapefiles_list->TotalRecs > 0) { ?>
<table id="tbl_shapefileslist" class="table ewTable">
<?php echo $shapefiles->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$shapefiles_list->RowType = EW_ROWTYPE_HEADER;

// Render list options
$shapefiles_list->RenderListOptions();

// Render list options (header, left)
$shapefiles_list->ListOptions->Render("header", "left");
?>
<?php if ($shapefiles->idshapefile->Visible) { // idshapefile ?>
	<?php if ($shapefiles->SortUrl($shapefiles->idshapefile) == "") { ?>
		<th data-name="idshapefile"><div id="elh_shapefiles_idshapefile" class="shapefiles_idshapefile"><div class="ewTableHeaderCaption"><?php echo $shapefiles->idshapefile->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="idshapefile"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $shapefiles->SortUrl($shapefiles->idshapefile) ?>',1);"><div id="elh_shapefiles_idshapefile" class="shapefiles_idshapefile">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $shapefiles->idshapefile->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($shapefiles->idshapefile->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($shapefiles->idshapefile->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($shapefiles->narchivoorigen->Visible) { // narchivoorigen ?>
	<?php if ($shapefiles->SortUrl($shapefiles->narchivoorigen) == "") { ?>
		<th data-name="narchivoorigen"><div id="elh_shapefiles_narchivoorigen" class="shapefiles_narchivoorigen"><div class="ewTableHeaderCaption"><?php echo $shapefiles->narchivoorigen->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="narchivoorigen"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $shapefiles->SortUrl($shapefiles->narchivoorigen) ?>',1);"><div id="elh_shapefiles_narchivoorigen" class="shapefiles_narchivoorigen">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $shapefiles->narchivoorigen->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($shapefiles->narchivoorigen->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($shapefiles->narchivoorigen->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($shapefiles->narchivo->Visible) { // narchivo ?>
	<?php if ($shapefiles->SortUrl($shapefiles->narchivo) == "") { ?>
		<th data-name="narchivo"><div id="elh_shapefiles_narchivo" class="shapefiles_narchivo"><div class="ewTableHeaderCaption"><?php echo $shapefiles->narchivo->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="narchivo"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $shapefiles->SortUrl($shapefiles->narchivo) ?>',1);"><div id="elh_shapefiles_narchivo" class="shapefiles_narchivo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $shapefiles->narchivo->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($shapefiles->narchivo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($shapefiles->narchivo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($shapefiles->idaplicacion->Visible) { // idaplicacion ?>
	<?php if ($shapefiles->SortUrl($shapefiles->idaplicacion) == "") { ?>
		<th data-name="idaplicacion"><div id="elh_shapefiles_idaplicacion" class="shapefiles_idaplicacion"><div class="ewTableHeaderCaption"><?php echo $shapefiles->idaplicacion->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="idaplicacion"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $shapefiles->SortUrl($shapefiles->idaplicacion) ?>',1);"><div id="elh_shapefiles_idaplicacion" class="shapefiles_idaplicacion">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $shapefiles->idaplicacion->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($shapefiles->idaplicacion->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($shapefiles->idaplicacion->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($shapefiles->token->Visible) { // token ?>
	<?php if ($shapefiles->SortUrl($shapefiles->token) == "") { ?>
		<th data-name="token"><div id="elh_shapefiles_token" class="shapefiles_token"><div class="ewTableHeaderCaption"><?php echo $shapefiles->token->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="token"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $shapefiles->SortUrl($shapefiles->token) ?>',1);"><div id="elh_shapefiles_token" class="shapefiles_token">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $shapefiles->token->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($shapefiles->token->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($shapefiles->token->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($shapefiles->idusuario->Visible) { // idusuario ?>
	<?php if ($shapefiles->SortUrl($shapefiles->idusuario) == "") { ?>
		<th data-name="idusuario"><div id="elh_shapefiles_idusuario" class="shapefiles_idusuario"><div class="ewTableHeaderCaption"><?php echo $shapefiles->idusuario->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="idusuario"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $shapefiles->SortUrl($shapefiles->idusuario) ?>',1);"><div id="elh_shapefiles_idusuario" class="shapefiles_idusuario">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $shapefiles->idusuario->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($shapefiles->idusuario->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($shapefiles->idusuario->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($shapefiles->tipo->Visible) { // tipo ?>
	<?php if ($shapefiles->SortUrl($shapefiles->tipo) == "") { ?>
		<th data-name="tipo"><div id="elh_shapefiles_tipo" class="shapefiles_tipo"><div class="ewTableHeaderCaption"><?php echo $shapefiles->tipo->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="tipo"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $shapefiles->SortUrl($shapefiles->tipo) ?>',1);"><div id="elh_shapefiles_tipo" class="shapefiles_tipo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $shapefiles->tipo->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($shapefiles->tipo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($shapefiles->tipo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($shapefiles->folder->Visible) { // folder ?>
	<?php if ($shapefiles->SortUrl($shapefiles->folder) == "") { ?>
		<th data-name="folder"><div id="elh_shapefiles_folder" class="shapefiles_folder"><div class="ewTableHeaderCaption"><?php echo $shapefiles->folder->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="folder"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $shapefiles->SortUrl($shapefiles->folder) ?>',1);"><div id="elh_shapefiles_folder" class="shapefiles_folder">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $shapefiles->folder->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($shapefiles->folder->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($shapefiles->folder->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($shapefiles->fechacreacion->Visible) { // fechacreacion ?>
	<?php if ($shapefiles->SortUrl($shapefiles->fechacreacion) == "") { ?>
		<th data-name="fechacreacion"><div id="elh_shapefiles_fechacreacion" class="shapefiles_fechacreacion"><div class="ewTableHeaderCaption"><?php echo $shapefiles->fechacreacion->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="fechacreacion"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $shapefiles->SortUrl($shapefiles->fechacreacion) ?>',1);"><div id="elh_shapefiles_fechacreacion" class="shapefiles_fechacreacion">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $shapefiles->fechacreacion->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($shapefiles->fechacreacion->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($shapefiles->fechacreacion->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($shapefiles->tamano->Visible) { // tamano ?>
	<?php if ($shapefiles->SortUrl($shapefiles->tamano) == "") { ?>
		<th data-name="tamano"><div id="elh_shapefiles_tamano" class="shapefiles_tamano"><div class="ewTableHeaderCaption"><?php echo $shapefiles->tamano->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="tamano"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $shapefiles->SortUrl($shapefiles->tamano) ?>',1);"><div id="elh_shapefiles_tamano" class="shapefiles_tamano">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $shapefiles->tamano->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($shapefiles->tamano->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($shapefiles->tamano->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($shapefiles->srid->Visible) { // srid ?>
	<?php if ($shapefiles->SortUrl($shapefiles->srid) == "") { ?>
		<th data-name="srid"><div id="elh_shapefiles_srid" class="shapefiles_srid"><div class="ewTableHeaderCaption"><?php echo $shapefiles->srid->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="srid"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $shapefiles->SortUrl($shapefiles->srid) ?>',1);"><div id="elh_shapefiles_srid" class="shapefiles_srid">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $shapefiles->srid->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($shapefiles->srid->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($shapefiles->srid->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($shapefiles->tipogeom->Visible) { // tipogeom ?>
	<?php if ($shapefiles->SortUrl($shapefiles->tipogeom) == "") { ?>
		<th data-name="tipogeom"><div id="elh_shapefiles_tipogeom" class="shapefiles_tipogeom"><div class="ewTableHeaderCaption"><?php echo $shapefiles->tipogeom->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="tipogeom"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $shapefiles->SortUrl($shapefiles->tipogeom) ?>',1);"><div id="elh_shapefiles_tipogeom" class="shapefiles_tipogeom">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $shapefiles->tipogeom->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($shapefiles->tipogeom->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($shapefiles->tipogeom->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$shapefiles_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($shapefiles->ExportAll && $shapefiles->Export <> "") {
	$shapefiles_list->StopRec = $shapefiles_list->TotalRecs;
} else {

	// Set the last record to display
	if ($shapefiles_list->TotalRecs > $shapefiles_list->StartRec + $shapefiles_list->DisplayRecs - 1)
		$shapefiles_list->StopRec = $shapefiles_list->StartRec + $shapefiles_list->DisplayRecs - 1;
	else
		$shapefiles_list->StopRec = $shapefiles_list->TotalRecs;
}
$shapefiles_list->RecCnt = $shapefiles_list->StartRec - 1;
if ($shapefiles_list->Recordset && !$shapefiles_list->Recordset->EOF) {
	$shapefiles_list->Recordset->MoveFirst();
	$bSelectLimit = $shapefiles_list->UseSelectLimit;
	if (!$bSelectLimit && $shapefiles_list->StartRec > 1)
		$shapefiles_list->Recordset->Move($shapefiles_list->StartRec - 1);
} elseif (!$shapefiles->AllowAddDeleteRow && $shapefiles_list->StopRec == 0) {
	$shapefiles_list->StopRec = $shapefiles->GridAddRowCount;
}

// Initialize aggregate
$shapefiles->RowType = EW_ROWTYPE_AGGREGATEINIT;
$shapefiles->ResetAttrs();
$shapefiles_list->RenderRow();
while ($shapefiles_list->RecCnt < $shapefiles_list->StopRec) {
	$shapefiles_list->RecCnt++;
	if (intval($shapefiles_list->RecCnt) >= intval($shapefiles_list->StartRec)) {
		$shapefiles_list->RowCnt++;

		// Set up key count
		$shapefiles_list->KeyCount = $shapefiles_list->RowIndex;

		// Init row class and style
		$shapefiles->ResetAttrs();
		$shapefiles->CssClass = "";
		if ($shapefiles->CurrentAction == "gridadd") {
		} else {
			$shapefiles_list->LoadRowValues($shapefiles_list->Recordset); // Load row values
		}
		$shapefiles->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$shapefiles->RowAttrs = array_merge($shapefiles->RowAttrs, array('data-rowindex'=>$shapefiles_list->RowCnt, 'id'=>'r' . $shapefiles_list->RowCnt . '_shapefiles', 'data-rowtype'=>$shapefiles->RowType));

		// Render row
		$shapefiles_list->RenderRow();

		// Render list options
		$shapefiles_list->RenderListOptions();
?>
	<tr<?php echo $shapefiles->RowAttributes() ?>>
<?php

// Render list options (body, left)
$shapefiles_list->ListOptions->Render("body", "left", $shapefiles_list->RowCnt);
?>
	<?php if ($shapefiles->idshapefile->Visible) { // idshapefile ?>
		<td data-name="idshapefile"<?php echo $shapefiles->idshapefile->CellAttributes() ?>>
<span id="el<?php echo $shapefiles_list->RowCnt ?>_shapefiles_idshapefile" class="shapefiles_idshapefile">
<span<?php echo $shapefiles->idshapefile->ViewAttributes() ?>>
<?php echo $shapefiles->idshapefile->ListViewValue() ?></span>
</span>
<a id="<?php echo $shapefiles_list->PageObjName . "_row_" . $shapefiles_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($shapefiles->narchivoorigen->Visible) { // narchivoorigen ?>
		<td data-name="narchivoorigen"<?php echo $shapefiles->narchivoorigen->CellAttributes() ?>>
<span id="el<?php echo $shapefiles_list->RowCnt ?>_shapefiles_narchivoorigen" class="shapefiles_narchivoorigen">
<span<?php echo $shapefiles->narchivoorigen->ViewAttributes() ?>>
<?php echo $shapefiles->narchivoorigen->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($shapefiles->narchivo->Visible) { // narchivo ?>
		<td data-name="narchivo"<?php echo $shapefiles->narchivo->CellAttributes() ?>>
<span id="el<?php echo $shapefiles_list->RowCnt ?>_shapefiles_narchivo" class="shapefiles_narchivo">
<span<?php echo $shapefiles->narchivo->ViewAttributes() ?>>
<?php echo ew_GetFileViewTag($shapefiles->narchivo, $shapefiles->narchivo->ListViewValue()) ?>
</span>
</span>
</td>
	<?php } ?>
	<?php if ($shapefiles->idaplicacion->Visible) { // idaplicacion ?>
		<td data-name="idaplicacion"<?php echo $shapefiles->idaplicacion->CellAttributes() ?>>
<span id="el<?php echo $shapefiles_list->RowCnt ?>_shapefiles_idaplicacion" class="shapefiles_idaplicacion">
<span<?php echo $shapefiles->idaplicacion->ViewAttributes() ?>>
<?php echo $shapefiles->idaplicacion->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($shapefiles->token->Visible) { // token ?>
		<td data-name="token"<?php echo $shapefiles->token->CellAttributes() ?>>
<span id="el<?php echo $shapefiles_list->RowCnt ?>_shapefiles_token" class="shapefiles_token">
<span<?php echo $shapefiles->token->ViewAttributes() ?>>
<?php echo $shapefiles->token->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($shapefiles->idusuario->Visible) { // idusuario ?>
		<td data-name="idusuario"<?php echo $shapefiles->idusuario->CellAttributes() ?>>
<span id="el<?php echo $shapefiles_list->RowCnt ?>_shapefiles_idusuario" class="shapefiles_idusuario">
<span<?php echo $shapefiles->idusuario->ViewAttributes() ?>>
<?php echo $shapefiles->idusuario->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($shapefiles->tipo->Visible) { // tipo ?>
		<td data-name="tipo"<?php echo $shapefiles->tipo->CellAttributes() ?>>
<span id="el<?php echo $shapefiles_list->RowCnt ?>_shapefiles_tipo" class="shapefiles_tipo">
<span<?php echo $shapefiles->tipo->ViewAttributes() ?>>
<?php echo $shapefiles->tipo->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($shapefiles->folder->Visible) { // folder ?>
		<td data-name="folder"<?php echo $shapefiles->folder->CellAttributes() ?>>
<span id="el<?php echo $shapefiles_list->RowCnt ?>_shapefiles_folder" class="shapefiles_folder">
<span<?php echo $shapefiles->folder->ViewAttributes() ?>>
<?php echo $shapefiles->folder->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($shapefiles->fechacreacion->Visible) { // fechacreacion ?>
		<td data-name="fechacreacion"<?php echo $shapefiles->fechacreacion->CellAttributes() ?>>
<span id="el<?php echo $shapefiles_list->RowCnt ?>_shapefiles_fechacreacion" class="shapefiles_fechacreacion">
<span<?php echo $shapefiles->fechacreacion->ViewAttributes() ?>>
<?php echo $shapefiles->fechacreacion->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($shapefiles->tamano->Visible) { // tamano ?>
		<td data-name="tamano"<?php echo $shapefiles->tamano->CellAttributes() ?>>
<span id="el<?php echo $shapefiles_list->RowCnt ?>_shapefiles_tamano" class="shapefiles_tamano">
<span<?php echo $shapefiles->tamano->ViewAttributes() ?>>
<?php echo $shapefiles->tamano->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($shapefiles->srid->Visible) { // srid ?>
		<td data-name="srid"<?php echo $shapefiles->srid->CellAttributes() ?>>
<span id="el<?php echo $shapefiles_list->RowCnt ?>_shapefiles_srid" class="shapefiles_srid">
<span<?php echo $shapefiles->srid->ViewAttributes() ?>>
<?php echo $shapefiles->srid->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($shapefiles->tipogeom->Visible) { // tipogeom ?>
		<td data-name="tipogeom"<?php echo $shapefiles->tipogeom->CellAttributes() ?>>
<span id="el<?php echo $shapefiles_list->RowCnt ?>_shapefiles_tipogeom" class="shapefiles_tipogeom">
<span<?php echo $shapefiles->tipogeom->ViewAttributes() ?>>
<?php echo $shapefiles->tipogeom->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$shapefiles_list->ListOptions->Render("body", "right", $shapefiles_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($shapefiles->CurrentAction <> "gridadd")
		$shapefiles_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($shapefiles->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($shapefiles_list->Recordset)
	$shapefiles_list->Recordset->Close();
?>
</div>
<?php } ?>
<?php if ($shapefiles_list->TotalRecs == 0 && $shapefiles->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($shapefiles_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($shapefiles->Export == "") { ?>
<script type="text/javascript">
fshapefileslistsrch.Init();
fshapefileslistsrch.FilterList = <?php echo $shapefiles_list->GetFilterList() ?>;
fshapefileslist.Init();
</script>
<?php } ?>
<?php
$shapefiles_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($shapefiles->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$shapefiles_list->Page_Terminate();
?>
