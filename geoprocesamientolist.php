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

$geoprocesamiento_list = NULL; // Initialize page object first

class cgeoprocesamiento_list extends cgeoprocesamiento {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{00441056-EF9D-4233-BDD9-EE81681FA399}";

	// Table name
	var $TableName = 'geoprocesamiento';

	// Page object name
	var $PageObjName = 'geoprocesamiento_list';

	// Grid form hidden field names
	var $FormName = 'fgeoprocesamientolist';
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

		// Table object (geoprocesamiento)
		if (!isset($GLOBALS["geoprocesamiento"]) || get_class($GLOBALS["geoprocesamiento"]) == "cgeoprocesamiento") {
			$GLOBALS["geoprocesamiento"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["geoprocesamiento"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "geoprocesamientoadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "geoprocesamientodelete.php";
		$this->MultiUpdateUrl = "geoprocesamientoupdate.php";

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

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
		$this->FilterOptions->TagClassName = "ewFilterOption fgeoprocesamientolistsrch";

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
			ew_AddFilter($this->DefaultSearchWhere, $this->AdvancedSearchWhere(TRUE));

			// Get basic search values
			$this->LoadBasicSearchValues();

			// Get and validate search values for advanced search
			$this->LoadSearchValues(); // Get search values

			// Restore filter list
			$this->RestoreFilterList();
			if (!$this->ValidateSearch())
				$this->setFailureMessage($gsSearchError);

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

			// Get search criteria for advanced search
			if ($gsSearchError == "")
				$sSrchAdvanced = $this->AdvancedSearchWhere();
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

			// Load advanced search from default
			if ($this->LoadAdvancedSearchDefault()) {
				$sSrchAdvanced = $this->AdvancedSearchWhere();
			}
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
			$this->idgeoproceso->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->idgeoproceso->FormValue))
				return FALSE;
		}
		return TRUE;
	}

	// Get list of filters
	function GetFilterList() {

		// Initialize
		$sFilterList = "";
		$sFilterList = ew_Concat($sFilterList, $this->idgeoproceso->AdvancedSearch->ToJSON(), ","); // Field idgeoproceso
		$sFilterList = ew_Concat($sFilterList, $this->idusuario->AdvancedSearch->ToJSON(), ","); // Field idusuario
		$sFilterList = ew_Concat($sFilterList, $this->proceso->AdvancedSearch->ToJSON(), ","); // Field proceso
		$sFilterList = ew_Concat($sFilterList, $this->inicio->AdvancedSearch->ToJSON(), ","); // Field inicio
		$sFilterList = ew_Concat($sFilterList, $this->fin->AdvancedSearch->ToJSON(), ","); // Field fin
		$sFilterList = ew_Concat($sFilterList, $this->entradatxt->AdvancedSearch->ToJSON(), ","); // Field entradatxt
		$sFilterList = ew_Concat($sFilterList, $this->salidatxt->AdvancedSearch->ToJSON(), ","); // Field salidatxt
		$sFilterList = ew_Concat($sFilterList, $this->opcionestxt->AdvancedSearch->ToJSON(), ","); // Field opcionestxt
		$sFilterList = ew_Concat($sFilterList, $this->geojson->AdvancedSearch->ToJSON(), ","); // Field geojson
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

		// Field idgeoproceso
		$this->idgeoproceso->AdvancedSearch->SearchValue = @$filter["x_idgeoproceso"];
		$this->idgeoproceso->AdvancedSearch->SearchOperator = @$filter["z_idgeoproceso"];
		$this->idgeoproceso->AdvancedSearch->SearchCondition = @$filter["v_idgeoproceso"];
		$this->idgeoproceso->AdvancedSearch->SearchValue2 = @$filter["y_idgeoproceso"];
		$this->idgeoproceso->AdvancedSearch->SearchOperator2 = @$filter["w_idgeoproceso"];
		$this->idgeoproceso->AdvancedSearch->Save();

		// Field idusuario
		$this->idusuario->AdvancedSearch->SearchValue = @$filter["x_idusuario"];
		$this->idusuario->AdvancedSearch->SearchOperator = @$filter["z_idusuario"];
		$this->idusuario->AdvancedSearch->SearchCondition = @$filter["v_idusuario"];
		$this->idusuario->AdvancedSearch->SearchValue2 = @$filter["y_idusuario"];
		$this->idusuario->AdvancedSearch->SearchOperator2 = @$filter["w_idusuario"];
		$this->idusuario->AdvancedSearch->Save();

		// Field proceso
		$this->proceso->AdvancedSearch->SearchValue = @$filter["x_proceso"];
		$this->proceso->AdvancedSearch->SearchOperator = @$filter["z_proceso"];
		$this->proceso->AdvancedSearch->SearchCondition = @$filter["v_proceso"];
		$this->proceso->AdvancedSearch->SearchValue2 = @$filter["y_proceso"];
		$this->proceso->AdvancedSearch->SearchOperator2 = @$filter["w_proceso"];
		$this->proceso->AdvancedSearch->Save();

		// Field inicio
		$this->inicio->AdvancedSearch->SearchValue = @$filter["x_inicio"];
		$this->inicio->AdvancedSearch->SearchOperator = @$filter["z_inicio"];
		$this->inicio->AdvancedSearch->SearchCondition = @$filter["v_inicio"];
		$this->inicio->AdvancedSearch->SearchValue2 = @$filter["y_inicio"];
		$this->inicio->AdvancedSearch->SearchOperator2 = @$filter["w_inicio"];
		$this->inicio->AdvancedSearch->Save();

		// Field fin
		$this->fin->AdvancedSearch->SearchValue = @$filter["x_fin"];
		$this->fin->AdvancedSearch->SearchOperator = @$filter["z_fin"];
		$this->fin->AdvancedSearch->SearchCondition = @$filter["v_fin"];
		$this->fin->AdvancedSearch->SearchValue2 = @$filter["y_fin"];
		$this->fin->AdvancedSearch->SearchOperator2 = @$filter["w_fin"];
		$this->fin->AdvancedSearch->Save();

		// Field entradatxt
		$this->entradatxt->AdvancedSearch->SearchValue = @$filter["x_entradatxt"];
		$this->entradatxt->AdvancedSearch->SearchOperator = @$filter["z_entradatxt"];
		$this->entradatxt->AdvancedSearch->SearchCondition = @$filter["v_entradatxt"];
		$this->entradatxt->AdvancedSearch->SearchValue2 = @$filter["y_entradatxt"];
		$this->entradatxt->AdvancedSearch->SearchOperator2 = @$filter["w_entradatxt"];
		$this->entradatxt->AdvancedSearch->Save();

		// Field salidatxt
		$this->salidatxt->AdvancedSearch->SearchValue = @$filter["x_salidatxt"];
		$this->salidatxt->AdvancedSearch->SearchOperator = @$filter["z_salidatxt"];
		$this->salidatxt->AdvancedSearch->SearchCondition = @$filter["v_salidatxt"];
		$this->salidatxt->AdvancedSearch->SearchValue2 = @$filter["y_salidatxt"];
		$this->salidatxt->AdvancedSearch->SearchOperator2 = @$filter["w_salidatxt"];
		$this->salidatxt->AdvancedSearch->Save();

		// Field opcionestxt
		$this->opcionestxt->AdvancedSearch->SearchValue = @$filter["x_opcionestxt"];
		$this->opcionestxt->AdvancedSearch->SearchOperator = @$filter["z_opcionestxt"];
		$this->opcionestxt->AdvancedSearch->SearchCondition = @$filter["v_opcionestxt"];
		$this->opcionestxt->AdvancedSearch->SearchValue2 = @$filter["y_opcionestxt"];
		$this->opcionestxt->AdvancedSearch->SearchOperator2 = @$filter["w_opcionestxt"];
		$this->opcionestxt->AdvancedSearch->Save();

		// Field geojson
		$this->geojson->AdvancedSearch->SearchValue = @$filter["x_geojson"];
		$this->geojson->AdvancedSearch->SearchOperator = @$filter["z_geojson"];
		$this->geojson->AdvancedSearch->SearchCondition = @$filter["v_geojson"];
		$this->geojson->AdvancedSearch->SearchValue2 = @$filter["y_geojson"];
		$this->geojson->AdvancedSearch->SearchOperator2 = @$filter["w_geojson"];
		$this->geojson->AdvancedSearch->Save();
		$this->BasicSearch->setKeyword(@$filter[EW_TABLE_BASIC_SEARCH]);
		$this->BasicSearch->setType(@$filter[EW_TABLE_BASIC_SEARCH_TYPE]);
	}

	// Advanced search WHERE clause based on QueryString
	function AdvancedSearchWhere($Default = FALSE) {
		global $Security;
		$sWhere = "";
		if (!$Security->CanSearch()) return "";
		$this->BuildSearchSql($sWhere, $this->idgeoproceso, $Default, FALSE); // idgeoproceso
		$this->BuildSearchSql($sWhere, $this->idusuario, $Default, FALSE); // idusuario
		$this->BuildSearchSql($sWhere, $this->proceso, $Default, FALSE); // proceso
		$this->BuildSearchSql($sWhere, $this->inicio, $Default, FALSE); // inicio
		$this->BuildSearchSql($sWhere, $this->fin, $Default, FALSE); // fin
		$this->BuildSearchSql($sWhere, $this->entradatxt, $Default, FALSE); // entradatxt
		$this->BuildSearchSql($sWhere, $this->salidatxt, $Default, FALSE); // salidatxt
		$this->BuildSearchSql($sWhere, $this->opcionestxt, $Default, FALSE); // opcionestxt
		$this->BuildSearchSql($sWhere, $this->geojson, $Default, FALSE); // geojson

		// Set up search parm
		if (!$Default && $sWhere <> "") {
			$this->Command = "search";
		}
		if (!$Default && $this->Command == "search") {
			$this->idgeoproceso->AdvancedSearch->Save(); // idgeoproceso
			$this->idusuario->AdvancedSearch->Save(); // idusuario
			$this->proceso->AdvancedSearch->Save(); // proceso
			$this->inicio->AdvancedSearch->Save(); // inicio
			$this->fin->AdvancedSearch->Save(); // fin
			$this->entradatxt->AdvancedSearch->Save(); // entradatxt
			$this->salidatxt->AdvancedSearch->Save(); // salidatxt
			$this->opcionestxt->AdvancedSearch->Save(); // opcionestxt
			$this->geojson->AdvancedSearch->Save(); // geojson
		}
		return $sWhere;
	}

	// Build search SQL
	function BuildSearchSql(&$Where, &$Fld, $Default, $MultiValue) {
		$FldParm = substr($Fld->FldVar, 2);
		$FldVal = ($Default) ? $Fld->AdvancedSearch->SearchValueDefault : $Fld->AdvancedSearch->SearchValue; // @$_GET["x_$FldParm"]
		$FldOpr = ($Default) ? $Fld->AdvancedSearch->SearchOperatorDefault : $Fld->AdvancedSearch->SearchOperator; // @$_GET["z_$FldParm"]
		$FldCond = ($Default) ? $Fld->AdvancedSearch->SearchConditionDefault : $Fld->AdvancedSearch->SearchCondition; // @$_GET["v_$FldParm"]
		$FldVal2 = ($Default) ? $Fld->AdvancedSearch->SearchValue2Default : $Fld->AdvancedSearch->SearchValue2; // @$_GET["y_$FldParm"]
		$FldOpr2 = ($Default) ? $Fld->AdvancedSearch->SearchOperator2Default : $Fld->AdvancedSearch->SearchOperator2; // @$_GET["w_$FldParm"]
		$sWrk = "";

		//$FldVal = ew_StripSlashes($FldVal);
		if (is_array($FldVal)) $FldVal = implode(",", $FldVal);

		//$FldVal2 = ew_StripSlashes($FldVal2);
		if (is_array($FldVal2)) $FldVal2 = implode(",", $FldVal2);
		$FldOpr = strtoupper(trim($FldOpr));
		if ($FldOpr == "") $FldOpr = "=";
		$FldOpr2 = strtoupper(trim($FldOpr2));
		if ($FldOpr2 == "") $FldOpr2 = "=";
		if (EW_SEARCH_MULTI_VALUE_OPTION == 1 || $FldOpr <> "LIKE" ||
			($FldOpr2 <> "LIKE" && $FldVal2 <> ""))
			$MultiValue = FALSE;
		if ($MultiValue) {
			$sWrk1 = ($FldVal <> "") ? ew_GetMultiSearchSql($Fld, $FldOpr, $FldVal, $this->DBID) : ""; // Field value 1
			$sWrk2 = ($FldVal2 <> "") ? ew_GetMultiSearchSql($Fld, $FldOpr2, $FldVal2, $this->DBID) : ""; // Field value 2
			$sWrk = $sWrk1; // Build final SQL
			if ($sWrk2 <> "")
				$sWrk = ($sWrk <> "") ? "($sWrk) $FldCond ($sWrk2)" : $sWrk2;
		} else {
			$FldVal = $this->ConvertSearchValue($Fld, $FldVal);
			$FldVal2 = $this->ConvertSearchValue($Fld, $FldVal2);
			$sWrk = ew_GetSearchSql($Fld, $FldVal, $FldOpr, $FldCond, $FldVal2, $FldOpr2, $this->DBID);
		}
		ew_AddFilter($Where, $sWrk);
	}

	// Convert search value
	function ConvertSearchValue(&$Fld, $FldVal) {
		if ($FldVal == EW_NULL_VALUE || $FldVal == EW_NOT_NULL_VALUE)
			return $FldVal;
		$Value = $FldVal;
		if ($Fld->FldDataType == EW_DATATYPE_BOOLEAN) {
			if ($FldVal <> "") $Value = ($FldVal == "1" || strtolower(strval($FldVal)) == "y" || strtolower(strval($FldVal)) == "t") ? $Fld->TrueValue : $Fld->FalseValue;
		} elseif ($Fld->FldDataType == EW_DATATYPE_DATE) {
			if ($FldVal <> "") $Value = ew_UnFormatDateTime($FldVal, $Fld->FldDateTimeFormat);
		}
		return $Value;
	}

	// Return basic search SQL
	function BasicSearchSQL($arKeywords, $type) {
		$sWhere = "";
		$this->BuildBasicSearchSQL($sWhere, $this->proceso, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->entradatxt, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->salidatxt, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->opcionestxt, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->geojson, $arKeywords, $type);
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
		if ($this->idgeoproceso->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->idusuario->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->proceso->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->inicio->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->fin->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->entradatxt->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->salidatxt->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->opcionestxt->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->geojson->AdvancedSearch->IssetSession())
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

		// Clear advanced search parameters
		$this->ResetAdvancedSearchParms();
	}

	// Load advanced search default values
	function LoadAdvancedSearchDefault() {
		return FALSE;
	}

	// Clear all basic search parameters
	function ResetBasicSearchParms() {
		$this->BasicSearch->UnsetSession();
	}

	// Clear all advanced search parameters
	function ResetAdvancedSearchParms() {
		$this->idgeoproceso->AdvancedSearch->UnsetSession();
		$this->idusuario->AdvancedSearch->UnsetSession();
		$this->proceso->AdvancedSearch->UnsetSession();
		$this->inicio->AdvancedSearch->UnsetSession();
		$this->fin->AdvancedSearch->UnsetSession();
		$this->entradatxt->AdvancedSearch->UnsetSession();
		$this->salidatxt->AdvancedSearch->UnsetSession();
		$this->opcionestxt->AdvancedSearch->UnsetSession();
		$this->geojson->AdvancedSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore basic search values
		$this->BasicSearch->Load();

		// Restore advanced search values
		$this->idgeoproceso->AdvancedSearch->Load();
		$this->idusuario->AdvancedSearch->Load();
		$this->proceso->AdvancedSearch->Load();
		$this->inicio->AdvancedSearch->Load();
		$this->fin->AdvancedSearch->Load();
		$this->entradatxt->AdvancedSearch->Load();
		$this->salidatxt->AdvancedSearch->Load();
		$this->opcionestxt->AdvancedSearch->Load();
		$this->geojson->AdvancedSearch->Load();
	}

	// Set up sort parameters
	function SetUpSortOrder() {

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->idgeoproceso); // idgeoproceso
			$this->UpdateSort($this->idusuario); // idusuario
			$this->UpdateSort($this->proceso); // proceso
			$this->UpdateSort($this->inicio); // inicio
			$this->UpdateSort($this->fin); // fin
			$this->UpdateSort($this->entradatxt); // entradatxt
			$this->UpdateSort($this->salidatrunc); // salidatrunc
			$this->UpdateSort($this->opcionestxt); // opcionestxt
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
				$this->idgeoproceso->setSort("DESC");
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
				$this->idgeoproceso->setSort("");
				$this->idusuario->setSort("");
				$this->proceso->setSort("");
				$this->inicio->setSort("");
				$this->fin->setSort("");
				$this->entradatxt->setSort("");
				$this->salidatrunc->setSort("");
				$this->opcionestxt->setSort("");
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
		$oListOpt->Body = "<input type=\"checkbox\" name=\"key_m[]\" value=\"" . ew_HtmlEncode($this->idgeoproceso->CurrentValue) . "\" onclick='ew_ClickMultiCheckbox(event);'>";
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
		$item->Body = "<a class=\"ewAction ewMultiDelete\" title=\"" . ew_HtmlTitle($Language->Phrase("DeleteSelectedLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("DeleteSelectedLink")) . "\" href=\"\" onclick=\"ew_SubmitAction(event,{f:document.fgeoprocesamientolist,url:'" . $this->MultiDeleteUrl . "',msg:ewLanguage.Phrase('DeleteConfirmMsg')});return false;\">" . $Language->Phrase("DeleteSelectedLink") . "</a>";
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
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"fgeoprocesamientolistsrch\" href=\"#\">" . $Language->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = TRUE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"fgeoprocesamientolistsrch\" href=\"#\">" . $Language->Phrase("DeleteFilter") . "</a>";
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
					$item->Body = "<a class=\"ewAction ewListAction\" title=\"" . ew_HtmlEncode($caption) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({f:document.fgeoprocesamientolist}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . "</a>";
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
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"fgeoprocesamientolistsrch\">" . $Language->Phrase("SearchBtn") . "</button>";
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

	// Load search values for validation
	function LoadSearchValues() {
		global $objForm;

		// Load search values
		// idgeoproceso

		$this->idgeoproceso->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_idgeoproceso"]);
		if ($this->idgeoproceso->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->idgeoproceso->AdvancedSearch->SearchOperator = @$_GET["z_idgeoproceso"];

		// idusuario
		$this->idusuario->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_idusuario"]);
		if ($this->idusuario->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->idusuario->AdvancedSearch->SearchOperator = @$_GET["z_idusuario"];

		// proceso
		$this->proceso->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_proceso"]);
		if ($this->proceso->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->proceso->AdvancedSearch->SearchOperator = @$_GET["z_proceso"];

		// inicio
		$this->inicio->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_inicio"]);
		if ($this->inicio->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->inicio->AdvancedSearch->SearchOperator = @$_GET["z_inicio"];

		// fin
		$this->fin->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_fin"]);
		if ($this->fin->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->fin->AdvancedSearch->SearchOperator = @$_GET["z_fin"];

		// entradatxt
		$this->entradatxt->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_entradatxt"]);
		if ($this->entradatxt->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->entradatxt->AdvancedSearch->SearchOperator = @$_GET["z_entradatxt"];

		// salidatxt
		$this->salidatxt->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_salidatxt"]);
		if ($this->salidatxt->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->salidatxt->AdvancedSearch->SearchOperator = @$_GET["z_salidatxt"];

		// opcionestxt
		$this->opcionestxt->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_opcionestxt"]);
		if ($this->opcionestxt->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->opcionestxt->AdvancedSearch->SearchOperator = @$_GET["z_opcionestxt"];

		// geojson
		$this->geojson->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_geojson"]);
		if ($this->geojson->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->geojson->AdvancedSearch->SearchOperator = @$_GET["z_geojson"];
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
		$this->ViewUrl = $this->GetViewUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->InlineEditUrl = $this->GetInlineEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->InlineCopyUrl = $this->GetInlineCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();

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

		// salidatrunc
		$this->salidatrunc->ViewValue = $this->salidatrunc->CurrentValue;
		$this->salidatrunc->ViewCustomAttributes = "";

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

			// salidatrunc
			$this->salidatrunc->LinkCustomAttributes = "";
			$this->salidatrunc->HrefValue = "";
			$this->salidatrunc->TooltipValue = "";

			// opcionestxt
			$this->opcionestxt->LinkCustomAttributes = "";
			$this->opcionestxt->HrefValue = "";
			$this->opcionestxt->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_SEARCH) { // Search row

			// idgeoproceso
			$this->idgeoproceso->EditAttrs["class"] = "form-control";
			$this->idgeoproceso->EditCustomAttributes = "";
			$this->idgeoproceso->EditValue = ew_HtmlEncode($this->idgeoproceso->AdvancedSearch->SearchValue);
			$this->idgeoproceso->PlaceHolder = ew_RemoveHtml($this->idgeoproceso->FldCaption());

			// idusuario
			$this->idusuario->EditAttrs["class"] = "form-control";
			$this->idusuario->EditCustomAttributes = "";
			$this->idusuario->EditValue = ew_HtmlEncode($this->idusuario->AdvancedSearch->SearchValue);
			$this->idusuario->PlaceHolder = ew_RemoveHtml($this->idusuario->FldCaption());

			// proceso
			$this->proceso->EditAttrs["class"] = "form-control";
			$this->proceso->EditCustomAttributes = "";

			// inicio
			$this->inicio->EditAttrs["class"] = "form-control";
			$this->inicio->EditCustomAttributes = "";
			$this->inicio->EditValue = ew_HtmlEncode(ew_UnFormatDateTime($this->inicio->AdvancedSearch->SearchValue, 0));
			$this->inicio->PlaceHolder = ew_RemoveHtml($this->inicio->FldCaption());

			// fin
			$this->fin->EditAttrs["class"] = "form-control";
			$this->fin->EditCustomAttributes = "";
			$this->fin->EditValue = ew_HtmlEncode(ew_UnFormatDateTime($this->fin->AdvancedSearch->SearchValue, 0));
			$this->fin->PlaceHolder = ew_RemoveHtml($this->fin->FldCaption());

			// entradatxt
			$this->entradatxt->EditAttrs["class"] = "form-control";
			$this->entradatxt->EditCustomAttributes = "";
			$this->entradatxt->EditValue = ew_HtmlEncode($this->entradatxt->AdvancedSearch->SearchValue);
			$this->entradatxt->PlaceHolder = ew_RemoveHtml($this->entradatxt->FldCaption());

			// salidatrunc
			$this->salidatrunc->EditAttrs["class"] = "form-control";
			$this->salidatrunc->EditCustomAttributes = "";
			$this->salidatrunc->EditValue = ew_HtmlEncode($this->salidatrunc->AdvancedSearch->SearchValue);
			$this->salidatrunc->PlaceHolder = ew_RemoveHtml($this->salidatrunc->FldCaption());

			// opcionestxt
			$this->opcionestxt->EditAttrs["class"] = "form-control";
			$this->opcionestxt->EditCustomAttributes = "";
			$this->opcionestxt->EditValue = ew_HtmlEncode($this->opcionestxt->AdvancedSearch->SearchValue);
			$this->opcionestxt->PlaceHolder = ew_RemoveHtml($this->opcionestxt->FldCaption());
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

	// Validate search
	function ValidateSearch() {
		global $gsSearchError;

		// Initialize
		$gsSearchError = "";

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return TRUE;
		if (!ew_CheckInteger($this->idgeoproceso->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->idgeoproceso->FldErrMsg());
		}

		// Return validate result
		$ValidateSearch = ($gsSearchError == "");

		// Call Form_CustomValidate event
		$sFormCustomError = "";
		$ValidateSearch = $ValidateSearch && $this->Form_CustomValidate($sFormCustomError);
		if ($sFormCustomError <> "") {
			ew_AddMessage($gsSearchError, $sFormCustomError);
		}
		return $ValidateSearch;
	}

	// Load advanced search
	function LoadAdvancedSearch() {
		$this->idgeoproceso->AdvancedSearch->Load();
		$this->idusuario->AdvancedSearch->Load();
		$this->proceso->AdvancedSearch->Load();
		$this->inicio->AdvancedSearch->Load();
		$this->fin->AdvancedSearch->Load();
		$this->entradatxt->AdvancedSearch->Load();
		$this->salidatxt->AdvancedSearch->Load();
		$this->opcionestxt->AdvancedSearch->Load();
		$this->geojson->AdvancedSearch->Load();
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
		$item->Body = "<button id=\"emf_geoprocesamiento\" class=\"ewExportLink ewEmail\" title=\"" . $Language->Phrase("ExportToEmailText") . "\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_geoprocesamiento',hdr:ewLanguage.Phrase('ExportToEmailText'),f:document.fgeoprocesamientolist,sel:false" . $url . "});\">" . $Language->Phrase("ExportToEmail") . "</button>";
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
if (!isset($geoprocesamiento_list)) $geoprocesamiento_list = new cgeoprocesamiento_list();

// Page init
$geoprocesamiento_list->Page_Init();

// Page main
$geoprocesamiento_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$geoprocesamiento_list->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($geoprocesamiento->Export == "") { ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "list";
var CurrentForm = fgeoprocesamientolist = new ew_Form("fgeoprocesamientolist", "list");
fgeoprocesamientolist.FormKeyCountName = '<?php echo $geoprocesamiento_list->FormKeyCountName ?>';

// Form_CustomValidate event
fgeoprocesamientolist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fgeoprocesamientolist.ValidateRequired = true;
<?php } else { ?>
fgeoprocesamientolist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fgeoprocesamientolist.Lists["x_idusuario"] = {"LinkField":"x_idusuario","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fgeoprocesamientolist.Lists["x_proceso"] = {"LinkField":"x_idaccion","Ajax":true,"AutoFill":false,"DisplayFields":["x_idaccion","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};

// Form object for search
var CurrentSearchForm = fgeoprocesamientolistsrch = new ew_Form("fgeoprocesamientolistsrch");

// Validate function for search
fgeoprocesamientolistsrch.Validate = function(fobj) {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	fobj = fobj || this.Form;
	var infix = "";
	elm = this.GetElements("x" + infix + "_idgeoproceso");
	if (elm && !ew_CheckInteger(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($geoprocesamiento->idgeoproceso->FldErrMsg()) ?>");

	// Fire Form_CustomValidate event
	if (!this.Form_CustomValidate(fobj))
		return false;
	return true;
}

// Form_CustomValidate event
fgeoprocesamientolistsrch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fgeoprocesamientolistsrch.ValidateRequired = true; // Use JavaScript validation
<?php } else { ?>
fgeoprocesamientolistsrch.ValidateRequired = false; // No JavaScript validation
<?php } ?>

// Dynamic selection lists
// Init search panel as collapsed

if (fgeoprocesamientolistsrch) fgeoprocesamientolistsrch.InitSearchPanel = true;
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($geoprocesamiento->Export == "") { ?>
<div class="ewToolbar">
<?php if ($geoprocesamiento->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if ($geoprocesamiento_list->TotalRecs > 0 && $geoprocesamiento_list->ExportOptions->Visible()) { ?>
<?php $geoprocesamiento_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($geoprocesamiento_list->SearchOptions->Visible()) { ?>
<?php $geoprocesamiento_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php if ($geoprocesamiento_list->FilterOptions->Visible()) { ?>
<?php $geoprocesamiento_list->FilterOptions->Render("body") ?>
<?php } ?>
<?php if ($geoprocesamiento->Export == "") { ?>
<?php echo $Language->SelectionForm(); ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php
	$bSelectLimit = $geoprocesamiento_list->UseSelectLimit;
	if ($bSelectLimit) {
		if ($geoprocesamiento_list->TotalRecs <= 0)
			$geoprocesamiento_list->TotalRecs = $geoprocesamiento->SelectRecordCount();
	} else {
		if (!$geoprocesamiento_list->Recordset && ($geoprocesamiento_list->Recordset = $geoprocesamiento_list->LoadRecordset()))
			$geoprocesamiento_list->TotalRecs = $geoprocesamiento_list->Recordset->RecordCount();
	}
	$geoprocesamiento_list->StartRec = 1;
	if ($geoprocesamiento_list->DisplayRecs <= 0 || ($geoprocesamiento->Export <> "" && $geoprocesamiento->ExportAll)) // Display all records
		$geoprocesamiento_list->DisplayRecs = $geoprocesamiento_list->TotalRecs;
	if (!($geoprocesamiento->Export <> "" && $geoprocesamiento->ExportAll))
		$geoprocesamiento_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$geoprocesamiento_list->Recordset = $geoprocesamiento_list->LoadRecordset($geoprocesamiento_list->StartRec-1, $geoprocesamiento_list->DisplayRecs);

	// Set no record found message
	if ($geoprocesamiento->CurrentAction == "" && $geoprocesamiento_list->TotalRecs == 0) {
		if (!$Security->CanList())
			$geoprocesamiento_list->setWarningMessage($Language->Phrase("NoPermission"));
		if ($geoprocesamiento_list->SearchWhere == "0=101")
			$geoprocesamiento_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$geoprocesamiento_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
$geoprocesamiento_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($geoprocesamiento->Export == "" && $geoprocesamiento->CurrentAction == "") { ?>
<form name="fgeoprocesamientolistsrch" id="fgeoprocesamientolistsrch" class="form-inline ewForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($geoprocesamiento_list->SearchWhere <> "") ? " in" : ""; ?>
<div id="fgeoprocesamientolistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="geoprocesamiento">
	<div class="ewBasicSearch">
<?php
if ($gsSearchError == "")
	$geoprocesamiento_list->LoadAdvancedSearch(); // Load advanced search

// Render for search
$geoprocesamiento->RowType = EW_ROWTYPE_SEARCH;

// Render row
$geoprocesamiento->ResetAttrs();
$geoprocesamiento_list->RenderRow();
?>
<div id="xsr_1" class="ewRow">
<?php if ($geoprocesamiento->idgeoproceso->Visible) { // idgeoproceso ?>
	<div id="xsc_idgeoproceso" class="ewCell form-group">
		<label for="x_idgeoproceso" class="ewSearchCaption ewLabel"><?php echo $geoprocesamiento->idgeoproceso->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_idgeoproceso" id="z_idgeoproceso" value="="></span>
		<span class="ewSearchField">
<input type="text" data-table="geoprocesamiento" data-field="x_idgeoproceso" name="x_idgeoproceso" id="x_idgeoproceso" placeholder="<?php echo ew_HtmlEncode($geoprocesamiento->idgeoproceso->getPlaceHolder()) ?>" value="<?php echo $geoprocesamiento->idgeoproceso->EditValue ?>"<?php echo $geoprocesamiento->idgeoproceso->EditAttributes() ?>>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_2" class="ewRow">
	<div class="ewQuickSearch input-group">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="form-control" value="<?php echo ew_HtmlEncode($geoprocesamiento_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo ew_HtmlEncode($Language->Phrase("Search")) ?>">
	<input type="hidden" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" id="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="<?php echo ew_HtmlEncode($geoprocesamiento_list->BasicSearch->getType()) ?>">
	<div class="input-group-btn">
		<button type="button" data-toggle="dropdown" class="btn btn-default"><span id="searchtype"><?php echo $geoprocesamiento_list->BasicSearch->getTypeNameShort() ?></span><span class="caret"></span></button>
		<ul class="dropdown-menu pull-right" role="menu">
			<li<?php if ($geoprocesamiento_list->BasicSearch->getType() == "") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this)"><?php echo $Language->Phrase("QuickSearchAuto") ?></a></li>
			<li<?php if ($geoprocesamiento_list->BasicSearch->getType() == "=") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'=')"><?php echo $Language->Phrase("QuickSearchExact") ?></a></li>
			<li<?php if ($geoprocesamiento_list->BasicSearch->getType() == "AND") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'AND')"><?php echo $Language->Phrase("QuickSearchAll") ?></a></li>
			<li<?php if ($geoprocesamiento_list->BasicSearch->getType() == "OR") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'OR')"><?php echo $Language->Phrase("QuickSearchAny") ?></a></li>
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
<?php $geoprocesamiento_list->ShowPageHeader(); ?>
<?php
$geoprocesamiento_list->ShowMessage();
?>
<?php if ($geoprocesamiento_list->TotalRecs > 0 || $geoprocesamiento->CurrentAction <> "") { ?>
<div class="panel panel-default ewGrid">
<?php if ($geoprocesamiento->Export == "") { ?>
<div class="panel-heading ewGridUpperPanel">
<?php if ($geoprocesamiento->CurrentAction <> "gridadd" && $geoprocesamiento->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="form-inline ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($geoprocesamiento_list->Pager)) $geoprocesamiento_list->Pager = new cPrevNextPager($geoprocesamiento_list->StartRec, $geoprocesamiento_list->DisplayRecs, $geoprocesamiento_list->TotalRecs) ?>
<?php if ($geoprocesamiento_list->Pager->RecordCount > 0) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($geoprocesamiento_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $geoprocesamiento_list->PageUrl() ?>start=<?php echo $geoprocesamiento_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($geoprocesamiento_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $geoprocesamiento_list->PageUrl() ?>start=<?php echo $geoprocesamiento_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $geoprocesamiento_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($geoprocesamiento_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $geoprocesamiento_list->PageUrl() ?>start=<?php echo $geoprocesamiento_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($geoprocesamiento_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $geoprocesamiento_list->PageUrl() ?>start=<?php echo $geoprocesamiento_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $geoprocesamiento_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $geoprocesamiento_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $geoprocesamiento_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $geoprocesamiento_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
<?php if ($geoprocesamiento_list->TotalRecs > 0) { ?>
<div class="ewPager">
<input type="hidden" name="t" value="geoprocesamiento">
<select name="<?php echo EW_TABLE_REC_PER_PAGE ?>" class="form-control input-sm" onchange="this.form.submit();">
<option value="10"<?php if ($geoprocesamiento_list->DisplayRecs == 10) { ?> selected<?php } ?>>10</option>
<option value="20"<?php if ($geoprocesamiento_list->DisplayRecs == 20) { ?> selected<?php } ?>>20</option>
<option value="50"<?php if ($geoprocesamiento_list->DisplayRecs == 50) { ?> selected<?php } ?>>50</option>
<option value="ALL"<?php if ($geoprocesamiento->getRecordsPerPage() == -1) { ?> selected<?php } ?>><?php echo $Language->Phrase("AllRecords") ?></option>
</select>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($geoprocesamiento_list->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
<form name="fgeoprocesamientolist" id="fgeoprocesamientolist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($geoprocesamiento_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $geoprocesamiento_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="geoprocesamiento">
<div id="gmp_geoprocesamiento" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<?php if ($geoprocesamiento_list->TotalRecs > 0) { ?>
<table id="tbl_geoprocesamientolist" class="table ewTable">
<?php echo $geoprocesamiento->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$geoprocesamiento_list->RowType = EW_ROWTYPE_HEADER;

// Render list options
$geoprocesamiento_list->RenderListOptions();

// Render list options (header, left)
$geoprocesamiento_list->ListOptions->Render("header", "left");
?>
<?php if ($geoprocesamiento->idgeoproceso->Visible) { // idgeoproceso ?>
	<?php if ($geoprocesamiento->SortUrl($geoprocesamiento->idgeoproceso) == "") { ?>
		<th data-name="idgeoproceso"><div id="elh_geoprocesamiento_idgeoproceso" class="geoprocesamiento_idgeoproceso"><div class="ewTableHeaderCaption"><?php echo $geoprocesamiento->idgeoproceso->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="idgeoproceso"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $geoprocesamiento->SortUrl($geoprocesamiento->idgeoproceso) ?>',1);"><div id="elh_geoprocesamiento_idgeoproceso" class="geoprocesamiento_idgeoproceso">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $geoprocesamiento->idgeoproceso->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($geoprocesamiento->idgeoproceso->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($geoprocesamiento->idgeoproceso->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($geoprocesamiento->idusuario->Visible) { // idusuario ?>
	<?php if ($geoprocesamiento->SortUrl($geoprocesamiento->idusuario) == "") { ?>
		<th data-name="idusuario"><div id="elh_geoprocesamiento_idusuario" class="geoprocesamiento_idusuario"><div class="ewTableHeaderCaption"><?php echo $geoprocesamiento->idusuario->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="idusuario"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $geoprocesamiento->SortUrl($geoprocesamiento->idusuario) ?>',1);"><div id="elh_geoprocesamiento_idusuario" class="geoprocesamiento_idusuario">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $geoprocesamiento->idusuario->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($geoprocesamiento->idusuario->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($geoprocesamiento->idusuario->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($geoprocesamiento->proceso->Visible) { // proceso ?>
	<?php if ($geoprocesamiento->SortUrl($geoprocesamiento->proceso) == "") { ?>
		<th data-name="proceso"><div id="elh_geoprocesamiento_proceso" class="geoprocesamiento_proceso"><div class="ewTableHeaderCaption"><?php echo $geoprocesamiento->proceso->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="proceso"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $geoprocesamiento->SortUrl($geoprocesamiento->proceso) ?>',1);"><div id="elh_geoprocesamiento_proceso" class="geoprocesamiento_proceso">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $geoprocesamiento->proceso->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($geoprocesamiento->proceso->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($geoprocesamiento->proceso->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($geoprocesamiento->inicio->Visible) { // inicio ?>
	<?php if ($geoprocesamiento->SortUrl($geoprocesamiento->inicio) == "") { ?>
		<th data-name="inicio"><div id="elh_geoprocesamiento_inicio" class="geoprocesamiento_inicio"><div class="ewTableHeaderCaption"><?php echo $geoprocesamiento->inicio->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="inicio"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $geoprocesamiento->SortUrl($geoprocesamiento->inicio) ?>',1);"><div id="elh_geoprocesamiento_inicio" class="geoprocesamiento_inicio">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $geoprocesamiento->inicio->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($geoprocesamiento->inicio->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($geoprocesamiento->inicio->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($geoprocesamiento->fin->Visible) { // fin ?>
	<?php if ($geoprocesamiento->SortUrl($geoprocesamiento->fin) == "") { ?>
		<th data-name="fin"><div id="elh_geoprocesamiento_fin" class="geoprocesamiento_fin"><div class="ewTableHeaderCaption"><?php echo $geoprocesamiento->fin->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="fin"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $geoprocesamiento->SortUrl($geoprocesamiento->fin) ?>',1);"><div id="elh_geoprocesamiento_fin" class="geoprocesamiento_fin">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $geoprocesamiento->fin->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($geoprocesamiento->fin->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($geoprocesamiento->fin->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($geoprocesamiento->entradatxt->Visible) { // entradatxt ?>
	<?php if ($geoprocesamiento->SortUrl($geoprocesamiento->entradatxt) == "") { ?>
		<th data-name="entradatxt"><div id="elh_geoprocesamiento_entradatxt" class="geoprocesamiento_entradatxt"><div class="ewTableHeaderCaption"><?php echo $geoprocesamiento->entradatxt->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="entradatxt"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $geoprocesamiento->SortUrl($geoprocesamiento->entradatxt) ?>',1);"><div id="elh_geoprocesamiento_entradatxt" class="geoprocesamiento_entradatxt">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $geoprocesamiento->entradatxt->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($geoprocesamiento->entradatxt->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($geoprocesamiento->entradatxt->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($geoprocesamiento->salidatrunc->Visible) { // salidatrunc ?>
	<?php if ($geoprocesamiento->SortUrl($geoprocesamiento->salidatrunc) == "") { ?>
		<th data-name="salidatrunc"><div id="elh_geoprocesamiento_salidatrunc" class="geoprocesamiento_salidatrunc"><div class="ewTableHeaderCaption"><?php echo $geoprocesamiento->salidatrunc->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="salidatrunc"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $geoprocesamiento->SortUrl($geoprocesamiento->salidatrunc) ?>',1);"><div id="elh_geoprocesamiento_salidatrunc" class="geoprocesamiento_salidatrunc">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $geoprocesamiento->salidatrunc->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($geoprocesamiento->salidatrunc->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($geoprocesamiento->salidatrunc->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($geoprocesamiento->opcionestxt->Visible) { // opcionestxt ?>
	<?php if ($geoprocesamiento->SortUrl($geoprocesamiento->opcionestxt) == "") { ?>
		<th data-name="opcionestxt"><div id="elh_geoprocesamiento_opcionestxt" class="geoprocesamiento_opcionestxt"><div class="ewTableHeaderCaption"><?php echo $geoprocesamiento->opcionestxt->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="opcionestxt"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $geoprocesamiento->SortUrl($geoprocesamiento->opcionestxt) ?>',1);"><div id="elh_geoprocesamiento_opcionestxt" class="geoprocesamiento_opcionestxt">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $geoprocesamiento->opcionestxt->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($geoprocesamiento->opcionestxt->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($geoprocesamiento->opcionestxt->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$geoprocesamiento_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($geoprocesamiento->ExportAll && $geoprocesamiento->Export <> "") {
	$geoprocesamiento_list->StopRec = $geoprocesamiento_list->TotalRecs;
} else {

	// Set the last record to display
	if ($geoprocesamiento_list->TotalRecs > $geoprocesamiento_list->StartRec + $geoprocesamiento_list->DisplayRecs - 1)
		$geoprocesamiento_list->StopRec = $geoprocesamiento_list->StartRec + $geoprocesamiento_list->DisplayRecs - 1;
	else
		$geoprocesamiento_list->StopRec = $geoprocesamiento_list->TotalRecs;
}
$geoprocesamiento_list->RecCnt = $geoprocesamiento_list->StartRec - 1;
if ($geoprocesamiento_list->Recordset && !$geoprocesamiento_list->Recordset->EOF) {
	$geoprocesamiento_list->Recordset->MoveFirst();
	$bSelectLimit = $geoprocesamiento_list->UseSelectLimit;
	if (!$bSelectLimit && $geoprocesamiento_list->StartRec > 1)
		$geoprocesamiento_list->Recordset->Move($geoprocesamiento_list->StartRec - 1);
} elseif (!$geoprocesamiento->AllowAddDeleteRow && $geoprocesamiento_list->StopRec == 0) {
	$geoprocesamiento_list->StopRec = $geoprocesamiento->GridAddRowCount;
}

// Initialize aggregate
$geoprocesamiento->RowType = EW_ROWTYPE_AGGREGATEINIT;
$geoprocesamiento->ResetAttrs();
$geoprocesamiento_list->RenderRow();
while ($geoprocesamiento_list->RecCnt < $geoprocesamiento_list->StopRec) {
	$geoprocesamiento_list->RecCnt++;
	if (intval($geoprocesamiento_list->RecCnt) >= intval($geoprocesamiento_list->StartRec)) {
		$geoprocesamiento_list->RowCnt++;

		// Set up key count
		$geoprocesamiento_list->KeyCount = $geoprocesamiento_list->RowIndex;

		// Init row class and style
		$geoprocesamiento->ResetAttrs();
		$geoprocesamiento->CssClass = "";
		if ($geoprocesamiento->CurrentAction == "gridadd") {
		} else {
			$geoprocesamiento_list->LoadRowValues($geoprocesamiento_list->Recordset); // Load row values
		}
		$geoprocesamiento->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$geoprocesamiento->RowAttrs = array_merge($geoprocesamiento->RowAttrs, array('data-rowindex'=>$geoprocesamiento_list->RowCnt, 'id'=>'r' . $geoprocesamiento_list->RowCnt . '_geoprocesamiento', 'data-rowtype'=>$geoprocesamiento->RowType));

		// Render row
		$geoprocesamiento_list->RenderRow();

		// Render list options
		$geoprocesamiento_list->RenderListOptions();
?>
	<tr<?php echo $geoprocesamiento->RowAttributes() ?>>
<?php

// Render list options (body, left)
$geoprocesamiento_list->ListOptions->Render("body", "left", $geoprocesamiento_list->RowCnt);
?>
	<?php if ($geoprocesamiento->idgeoproceso->Visible) { // idgeoproceso ?>
		<td data-name="idgeoproceso"<?php echo $geoprocesamiento->idgeoproceso->CellAttributes() ?>>
<span id="el<?php echo $geoprocesamiento_list->RowCnt ?>_geoprocesamiento_idgeoproceso" class="geoprocesamiento_idgeoproceso">
<span<?php echo $geoprocesamiento->idgeoproceso->ViewAttributes() ?>>
<?php echo $geoprocesamiento->idgeoproceso->ListViewValue() ?></span>
</span>
<a id="<?php echo $geoprocesamiento_list->PageObjName . "_row_" . $geoprocesamiento_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($geoprocesamiento->idusuario->Visible) { // idusuario ?>
		<td data-name="idusuario"<?php echo $geoprocesamiento->idusuario->CellAttributes() ?>>
<span id="el<?php echo $geoprocesamiento_list->RowCnt ?>_geoprocesamiento_idusuario" class="geoprocesamiento_idusuario">
<span<?php echo $geoprocesamiento->idusuario->ViewAttributes() ?>>
<?php echo $geoprocesamiento->idusuario->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($geoprocesamiento->proceso->Visible) { // proceso ?>
		<td data-name="proceso"<?php echo $geoprocesamiento->proceso->CellAttributes() ?>>
<span id="el<?php echo $geoprocesamiento_list->RowCnt ?>_geoprocesamiento_proceso" class="geoprocesamiento_proceso">
<span<?php echo $geoprocesamiento->proceso->ViewAttributes() ?>>
<?php echo $geoprocesamiento->proceso->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($geoprocesamiento->inicio->Visible) { // inicio ?>
		<td data-name="inicio"<?php echo $geoprocesamiento->inicio->CellAttributes() ?>>
<span id="el<?php echo $geoprocesamiento_list->RowCnt ?>_geoprocesamiento_inicio" class="geoprocesamiento_inicio">
<span<?php echo $geoprocesamiento->inicio->ViewAttributes() ?>>
<?php echo $geoprocesamiento->inicio->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($geoprocesamiento->fin->Visible) { // fin ?>
		<td data-name="fin"<?php echo $geoprocesamiento->fin->CellAttributes() ?>>
<span id="el<?php echo $geoprocesamiento_list->RowCnt ?>_geoprocesamiento_fin" class="geoprocesamiento_fin">
<span<?php echo $geoprocesamiento->fin->ViewAttributes() ?>>
<?php echo $geoprocesamiento->fin->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($geoprocesamiento->entradatxt->Visible) { // entradatxt ?>
		<td data-name="entradatxt"<?php echo $geoprocesamiento->entradatxt->CellAttributes() ?>>
<span id="el<?php echo $geoprocesamiento_list->RowCnt ?>_geoprocesamiento_entradatxt" class="geoprocesamiento_entradatxt">
<span<?php echo $geoprocesamiento->entradatxt->ViewAttributes() ?>>
<?php echo $geoprocesamiento->entradatxt->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($geoprocesamiento->salidatrunc->Visible) { // salidatrunc ?>
		<td data-name="salidatrunc"<?php echo $geoprocesamiento->salidatrunc->CellAttributes() ?>>
<span id="el<?php echo $geoprocesamiento_list->RowCnt ?>_geoprocesamiento_salidatrunc" class="geoprocesamiento_salidatrunc">
<span<?php echo $geoprocesamiento->salidatrunc->ViewAttributes() ?>>
<?php echo $geoprocesamiento->salidatrunc->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($geoprocesamiento->opcionestxt->Visible) { // opcionestxt ?>
		<td data-name="opcionestxt"<?php echo $geoprocesamiento->opcionestxt->CellAttributes() ?>>
<span id="el<?php echo $geoprocesamiento_list->RowCnt ?>_geoprocesamiento_opcionestxt" class="geoprocesamiento_opcionestxt">
<span<?php echo $geoprocesamiento->opcionestxt->ViewAttributes() ?>>
<?php echo $geoprocesamiento->opcionestxt->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$geoprocesamiento_list->ListOptions->Render("body", "right", $geoprocesamiento_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($geoprocesamiento->CurrentAction <> "gridadd")
		$geoprocesamiento_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($geoprocesamiento->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($geoprocesamiento_list->Recordset)
	$geoprocesamiento_list->Recordset->Close();
?>
</div>
<?php } ?>
<?php if ($geoprocesamiento_list->TotalRecs == 0 && $geoprocesamiento->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($geoprocesamiento_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($geoprocesamiento->Export == "") { ?>
<script type="text/javascript">
fgeoprocesamientolistsrch.Init();
fgeoprocesamientolistsrch.FilterList = <?php echo $geoprocesamiento_list->GetFilterList() ?>;
fgeoprocesamientolist.Init();
</script>
<?php } ?>
<?php
$geoprocesamiento_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($geoprocesamiento->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$geoprocesamiento_list->Page_Terminate();
?>
