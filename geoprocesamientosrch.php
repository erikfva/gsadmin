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

$geoprocesamiento_search = NULL; // Initialize page object first

class cgeoprocesamiento_search extends cgeoprocesamiento {

	// Page ID
	var $PageID = 'search';

	// Project ID
	var $ProjectID = "{00441056-EF9D-4233-BDD9-EE81681FA399}";

	// Table name
	var $TableName = 'geoprocesamiento';

	// Page object name
	var $PageObjName = 'geoprocesamiento_search';

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
			define("EW_PAGE_ID", 'search', TRUE);

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
		if (!$Security->CanSearch()) {
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
	var $FormClassName = "form-horizontal ewForm ewSearchForm";
	var $IsModal = FALSE;
	var $SearchLabelClass = "col-sm-3 control-label ewLabel";
	var $SearchRightColumnClass = "col-sm-9";

	//
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsSearchError;
		global $gbSkipHeaderFooter;

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Check modal
		$this->IsModal = (@$_GET["modal"] == "1" || @$_POST["modal"] == "1");
		if ($this->IsModal)
			$gbSkipHeaderFooter = TRUE;
		if ($this->IsPageRequest()) { // Validate request

			// Get action
			$this->CurrentAction = $objForm->GetValue("a_search");
			switch ($this->CurrentAction) {
				case "S": // Get search criteria

					// Build search string for advanced search, remove blank field
					$this->LoadSearchValues(); // Get search values
					if ($this->ValidateSearch()) {
						$sSrchStr = $this->BuildAdvancedSearch();
					} else {
						$sSrchStr = "";
						$this->setFailureMessage($gsSearchError);
					}
					if ($sSrchStr <> "") {
						$sSrchStr = $this->UrlParm($sSrchStr);
						$sSrchStr = "geoprocesamientolist.php" . "?" . $sSrchStr;
						if ($this->IsModal) {
							$row = array();
							$row["url"] = $sSrchStr;
							echo ew_ArrayToJson(array($row));
							$this->Page_Terminate();
							exit();
						} else {
							$this->Page_Terminate($sSrchStr); // Go to list page
						}
					}
			}
		}

		// Restore search settings from Session
		if ($gsSearchError == "")
			$this->LoadAdvancedSearch();

		// Render row for search
		$this->RowType = EW_ROWTYPE_SEARCH;
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Build advanced search
	function BuildAdvancedSearch() {
		$sSrchUrl = "";
		$this->BuildSearchUrl($sSrchUrl, $this->idgeoproceso); // idgeoproceso
		$this->BuildSearchUrl($sSrchUrl, $this->idusuario); // idusuario
		$this->BuildSearchUrl($sSrchUrl, $this->proceso); // proceso
		$this->BuildSearchUrl($sSrchUrl, $this->inicio); // inicio
		$this->BuildSearchUrl($sSrchUrl, $this->fin); // fin
		$this->BuildSearchUrl($sSrchUrl, $this->entradatxt); // entradatxt
		$this->BuildSearchUrl($sSrchUrl, $this->salidatxt); // salidatxt
		$this->BuildSearchUrl($sSrchUrl, $this->opcionestxt); // opcionestxt
		if ($sSrchUrl <> "") $sSrchUrl .= "&";
		$sSrchUrl .= "cmd=search";
		return $sSrchUrl;
	}

	// Build search URL
	function BuildSearchUrl(&$Url, &$Fld, $OprOnly=FALSE) {
		global $objForm;
		$sWrk = "";
		$FldParm = substr($Fld->FldVar, 2);
		$FldVal = $objForm->GetValue("x_$FldParm");
		$FldOpr = $objForm->GetValue("z_$FldParm");
		$FldCond = $objForm->GetValue("v_$FldParm");
		$FldVal2 = $objForm->GetValue("y_$FldParm");
		$FldOpr2 = $objForm->GetValue("w_$FldParm");
		$FldVal = ew_StripSlashes($FldVal);
		if (is_array($FldVal)) $FldVal = implode(",", $FldVal);
		$FldVal2 = ew_StripSlashes($FldVal2);
		if (is_array($FldVal2)) $FldVal2 = implode(",", $FldVal2);
		$FldOpr = strtoupper(trim($FldOpr));
		$lFldDataType = ($Fld->FldIsVirtual) ? EW_DATATYPE_STRING : $Fld->FldDataType;
		if ($FldOpr == "BETWEEN") {
			$IsValidValue = ($lFldDataType <> EW_DATATYPE_NUMBER) ||
				($lFldDataType == EW_DATATYPE_NUMBER && $this->SearchValueIsNumeric($Fld, $FldVal) && $this->SearchValueIsNumeric($Fld, $FldVal2));
			if ($FldVal <> "" && $FldVal2 <> "" && $IsValidValue) {
				$sWrk = "x_" . $FldParm . "=" . urlencode($FldVal) .
					"&y_" . $FldParm . "=" . urlencode($FldVal2) .
					"&z_" . $FldParm . "=" . urlencode($FldOpr);
			}
		} else {
			$IsValidValue = ($lFldDataType <> EW_DATATYPE_NUMBER) ||
				($lFldDataType == EW_DATATYPE_NUMBER && $this->SearchValueIsNumeric($Fld, $FldVal));
			if ($FldVal <> "" && $IsValidValue && ew_IsValidOpr($FldOpr, $lFldDataType)) {
				$sWrk = "x_" . $FldParm . "=" . urlencode($FldVal) .
					"&z_" . $FldParm . "=" . urlencode($FldOpr);
			} elseif ($FldOpr == "IS NULL" || $FldOpr == "IS NOT NULL" || ($FldOpr <> "" && $OprOnly && ew_IsValidOpr($FldOpr, $lFldDataType))) {
				$sWrk = "z_" . $FldParm . "=" . urlencode($FldOpr);
			}
			$IsValidValue = ($lFldDataType <> EW_DATATYPE_NUMBER) ||
				($lFldDataType == EW_DATATYPE_NUMBER && $this->SearchValueIsNumeric($Fld, $FldVal2));
			if ($FldVal2 <> "" && $IsValidValue && ew_IsValidOpr($FldOpr2, $lFldDataType)) {
				if ($sWrk <> "") $sWrk .= "&v_" . $FldParm . "=" . urlencode($FldCond) . "&";
				$sWrk .= "y_" . $FldParm . "=" . urlencode($FldVal2) .
					"&w_" . $FldParm . "=" . urlencode($FldOpr2);
			} elseif ($FldOpr2 == "IS NULL" || $FldOpr2 == "IS NOT NULL" || ($FldOpr2 <> "" && $OprOnly && ew_IsValidOpr($FldOpr2, $lFldDataType))) {
				if ($sWrk <> "") $sWrk .= "&v_" . $FldParm . "=" . urlencode($FldCond) . "&";
				$sWrk .= "w_" . $FldParm . "=" . urlencode($FldOpr2);
			}
		}
		if ($sWrk <> "") {
			if ($Url <> "") $Url .= "&";
			$Url .= $sWrk;
		}
	}

	function SearchValueIsNumeric($Fld, $Value) {
		if (ew_IsFloatFormat($Fld->FldType)) $Value = ew_StrToFloat($Value);
		return is_numeric($Value);
	}

	// Load search values for validation
	function LoadSearchValues() {
		global $objForm;

		// Load search values
		// idgeoproceso

		$this->idgeoproceso->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_idgeoproceso"));
		$this->idgeoproceso->AdvancedSearch->SearchOperator = $objForm->GetValue("z_idgeoproceso");

		// idusuario
		$this->idusuario->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_idusuario"));
		$this->idusuario->AdvancedSearch->SearchOperator = $objForm->GetValue("z_idusuario");

		// proceso
		$this->proceso->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_proceso"));
		$this->proceso->AdvancedSearch->SearchOperator = $objForm->GetValue("z_proceso");

		// inicio
		$this->inicio->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_inicio"));
		$this->inicio->AdvancedSearch->SearchOperator = $objForm->GetValue("z_inicio");

		// fin
		$this->fin->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_fin"));
		$this->fin->AdvancedSearch->SearchOperator = $objForm->GetValue("z_fin");

		// entradatxt
		$this->entradatxt->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_entradatxt"));
		$this->entradatxt->AdvancedSearch->SearchOperator = $objForm->GetValue("z_entradatxt");

		// salidatxt
		$this->salidatxt->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_salidatxt"));
		$this->salidatxt->AdvancedSearch->SearchOperator = $objForm->GetValue("z_salidatxt");

		// opcionestxt
		$this->opcionestxt->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_opcionestxt"));
		$this->opcionestxt->AdvancedSearch->SearchOperator = $objForm->GetValue("z_opcionestxt");
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
			if (strval($this->idusuario->AdvancedSearch->SearchValue) <> "") {
				$sFilterWrk = "\"idusuario\"" . ew_SearchString("=", $this->idusuario->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER, "");
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
					$this->idusuario->EditValue = ew_HtmlEncode($this->idusuario->AdvancedSearch->SearchValue);
				}
			} else {
				$this->idusuario->EditValue = NULL;
			}
			$this->idusuario->PlaceHolder = ew_RemoveHtml($this->idusuario->FldCaption());

			// proceso
			$this->proceso->EditAttrs["class"] = "form-control";
			$this->proceso->EditCustomAttributes = "";
			if (trim(strval($this->proceso->AdvancedSearch->SearchValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "\"idaccion\"" . ew_SearchString("=", $this->proceso->AdvancedSearch->SearchValue, EW_DATATYPE_STRING, "");
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

			// salidatxt
			$this->salidatxt->EditAttrs["class"] = "form-control";
			$this->salidatxt->EditCustomAttributes = "";
			$this->salidatxt->EditValue = ew_HtmlEncode($this->salidatxt->AdvancedSearch->SearchValue);
			$this->salidatxt->PlaceHolder = ew_RemoveHtml($this->salidatxt->FldCaption());

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
		if (!ew_CheckInteger($this->idusuario->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->idusuario->FldErrMsg());
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
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("geoprocesamientolist.php"), "", $this->TableVar, TRUE);
		$PageId = "search";
		$Breadcrumb->Add("search", $PageId, $url);
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
if (!isset($geoprocesamiento_search)) $geoprocesamiento_search = new cgeoprocesamiento_search();

// Page init
$geoprocesamiento_search->Page_Init();

// Page main
$geoprocesamiento_search->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$geoprocesamiento_search->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "search";
<?php if ($geoprocesamiento_search->IsModal) { ?>
var CurrentAdvancedSearchForm = fgeoprocesamientosearch = new ew_Form("fgeoprocesamientosearch", "search");
<?php } else { ?>
var CurrentForm = fgeoprocesamientosearch = new ew_Form("fgeoprocesamientosearch", "search");
<?php } ?>

// Form_CustomValidate event
fgeoprocesamientosearch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fgeoprocesamientosearch.ValidateRequired = true;
<?php } else { ?>
fgeoprocesamientosearch.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fgeoprocesamientosearch.Lists["x_idusuario"] = {"LinkField":"x_idusuario","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fgeoprocesamientosearch.Lists["x_proceso"] = {"LinkField":"x_idaccion","Ajax":true,"AutoFill":false,"DisplayFields":["x_idaccion","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};

// Form object for search
// Validate function for search

fgeoprocesamientosearch.Validate = function(fobj) {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	fobj = fobj || this.Form;
	var infix = "";
	elm = this.GetElements("x" + infix + "_idgeoproceso");
	if (elm && !ew_CheckInteger(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($geoprocesamiento->idgeoproceso->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_idusuario");
	if (elm && !ew_CheckInteger(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($geoprocesamiento->idusuario->FldErrMsg()) ?>");

	// Fire Form_CustomValidate event
	if (!this.Form_CustomValidate(fobj))
		return false;
	return true;
}
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php if (!$geoprocesamiento_search->IsModal) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $geoprocesamiento_search->ShowPageHeader(); ?>
<?php
$geoprocesamiento_search->ShowMessage();
?>
<form name="fgeoprocesamientosearch" id="fgeoprocesamientosearch" class="<?php echo $geoprocesamiento_search->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($geoprocesamiento_search->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $geoprocesamiento_search->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="geoprocesamiento">
<input type="hidden" name="a_search" id="a_search" value="S">
<?php if ($geoprocesamiento_search->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div>
<?php if ($geoprocesamiento->idgeoproceso->Visible) { // idgeoproceso ?>
	<div id="r_idgeoproceso" class="form-group">
		<label for="x_idgeoproceso" class="<?php echo $geoprocesamiento_search->SearchLabelClass ?>"><span id="elh_geoprocesamiento_idgeoproceso"><?php echo $geoprocesamiento->idgeoproceso->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_idgeoproceso" id="z_idgeoproceso" value="="></p>
		</label>
		<div class="<?php echo $geoprocesamiento_search->SearchRightColumnClass ?>"><div<?php echo $geoprocesamiento->idgeoproceso->CellAttributes() ?>>
			<span id="el_geoprocesamiento_idgeoproceso">
<input type="text" data-table="geoprocesamiento" data-field="x_idgeoproceso" name="x_idgeoproceso" id="x_idgeoproceso" placeholder="<?php echo ew_HtmlEncode($geoprocesamiento->idgeoproceso->getPlaceHolder()) ?>" value="<?php echo $geoprocesamiento->idgeoproceso->EditValue ?>"<?php echo $geoprocesamiento->idgeoproceso->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($geoprocesamiento->idusuario->Visible) { // idusuario ?>
	<div id="r_idusuario" class="form-group">
		<label class="<?php echo $geoprocesamiento_search->SearchLabelClass ?>"><span id="elh_geoprocesamiento_idusuario"><?php echo $geoprocesamiento->idusuario->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_idusuario" id="z_idusuario" value="="></p>
		</label>
		<div class="<?php echo $geoprocesamiento_search->SearchRightColumnClass ?>"><div<?php echo $geoprocesamiento->idusuario->CellAttributes() ?>>
			<span id="el_geoprocesamiento_idusuario">
<?php
$wrkonchange = trim(" " . @$geoprocesamiento->idusuario->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$geoprocesamiento->idusuario->EditAttrs["onchange"] = "";
?>
<span id="as_x_idusuario" style="white-space: nowrap; z-index: 8980">
	<input type="text" name="sv_x_idusuario" id="sv_x_idusuario" value="<?php echo $geoprocesamiento->idusuario->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($geoprocesamiento->idusuario->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($geoprocesamiento->idusuario->getPlaceHolder()) ?>"<?php echo $geoprocesamiento->idusuario->EditAttributes() ?>>
</span>
<input type="hidden" data-table="geoprocesamiento" data-field="x_idusuario" data-value-separator="<?php echo ew_HtmlEncode(is_array($geoprocesamiento->idusuario->DisplayValueSeparator) ? json_encode($geoprocesamiento->idusuario->DisplayValueSeparator) : $geoprocesamiento->idusuario->DisplayValueSeparator) ?>" name="x_idusuario" id="x_idusuario" value="<?php echo ew_HtmlEncode($geoprocesamiento->idusuario->AdvancedSearch->SearchValue) ?>"<?php echo $wrkonchange ?>>
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
fgeoprocesamientosearch.CreateAutoSuggest({"id":"x_idusuario","forceSelect":false});
</script>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($geoprocesamiento->proceso->Visible) { // proceso ?>
	<div id="r_proceso" class="form-group">
		<label for="x_proceso" class="<?php echo $geoprocesamiento_search->SearchLabelClass ?>"><span id="elh_geoprocesamiento_proceso"><?php echo $geoprocesamiento->proceso->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_proceso" id="z_proceso" value="LIKE"></p>
		</label>
		<div class="<?php echo $geoprocesamiento_search->SearchRightColumnClass ?>"><div<?php echo $geoprocesamiento->proceso->CellAttributes() ?>>
			<span id="el_geoprocesamiento_proceso">
<select data-table="geoprocesamiento" data-field="x_proceso" data-value-separator="<?php echo ew_HtmlEncode(is_array($geoprocesamiento->proceso->DisplayValueSeparator) ? json_encode($geoprocesamiento->proceso->DisplayValueSeparator) : $geoprocesamiento->proceso->DisplayValueSeparator) ?>" id="x_proceso" name="x_proceso"<?php echo $geoprocesamiento->proceso->EditAttributes() ?>>
<?php
if (is_array($geoprocesamiento->proceso->EditValue)) {
	$arwrk = $geoprocesamiento->proceso->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($geoprocesamiento->proceso->AdvancedSearch->SearchValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
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
		</div></div>
	</div>
<?php } ?>
<?php if ($geoprocesamiento->inicio->Visible) { // inicio ?>
	<div id="r_inicio" class="form-group">
		<label for="x_inicio" class="<?php echo $geoprocesamiento_search->SearchLabelClass ?>"><span id="elh_geoprocesamiento_inicio"><?php echo $geoprocesamiento->inicio->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_inicio" id="z_inicio" value="="></p>
		</label>
		<div class="<?php echo $geoprocesamiento_search->SearchRightColumnClass ?>"><div<?php echo $geoprocesamiento->inicio->CellAttributes() ?>>
			<span id="el_geoprocesamiento_inicio">
<input type="text" data-table="geoprocesamiento" data-field="x_inicio" name="x_inicio" id="x_inicio" placeholder="<?php echo ew_HtmlEncode($geoprocesamiento->inicio->getPlaceHolder()) ?>" value="<?php echo $geoprocesamiento->inicio->EditValue ?>"<?php echo $geoprocesamiento->inicio->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($geoprocesamiento->fin->Visible) { // fin ?>
	<div id="r_fin" class="form-group">
		<label for="x_fin" class="<?php echo $geoprocesamiento_search->SearchLabelClass ?>"><span id="elh_geoprocesamiento_fin"><?php echo $geoprocesamiento->fin->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_fin" id="z_fin" value="="></p>
		</label>
		<div class="<?php echo $geoprocesamiento_search->SearchRightColumnClass ?>"><div<?php echo $geoprocesamiento->fin->CellAttributes() ?>>
			<span id="el_geoprocesamiento_fin">
<input type="text" data-table="geoprocesamiento" data-field="x_fin" name="x_fin" id="x_fin" placeholder="<?php echo ew_HtmlEncode($geoprocesamiento->fin->getPlaceHolder()) ?>" value="<?php echo $geoprocesamiento->fin->EditValue ?>"<?php echo $geoprocesamiento->fin->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($geoprocesamiento->entradatxt->Visible) { // entradatxt ?>
	<div id="r_entradatxt" class="form-group">
		<label for="x_entradatxt" class="<?php echo $geoprocesamiento_search->SearchLabelClass ?>"><span id="elh_geoprocesamiento_entradatxt"><?php echo $geoprocesamiento->entradatxt->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_entradatxt" id="z_entradatxt" value="LIKE"></p>
		</label>
		<div class="<?php echo $geoprocesamiento_search->SearchRightColumnClass ?>"><div<?php echo $geoprocesamiento->entradatxt->CellAttributes() ?>>
			<span id="el_geoprocesamiento_entradatxt">
<input type="text" data-table="geoprocesamiento" data-field="x_entradatxt" name="x_entradatxt" id="x_entradatxt" size="35" placeholder="<?php echo ew_HtmlEncode($geoprocesamiento->entradatxt->getPlaceHolder()) ?>" value="<?php echo $geoprocesamiento->entradatxt->EditValue ?>"<?php echo $geoprocesamiento->entradatxt->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($geoprocesamiento->salidatxt->Visible) { // salidatxt ?>
	<div id="r_salidatxt" class="form-group">
		<label for="x_salidatxt" class="<?php echo $geoprocesamiento_search->SearchLabelClass ?>"><span id="elh_geoprocesamiento_salidatxt"><?php echo $geoprocesamiento->salidatxt->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_salidatxt" id="z_salidatxt" value="LIKE"></p>
		</label>
		<div class="<?php echo $geoprocesamiento_search->SearchRightColumnClass ?>"><div<?php echo $geoprocesamiento->salidatxt->CellAttributes() ?>>
			<span id="el_geoprocesamiento_salidatxt">
<input type="text" data-table="geoprocesamiento" data-field="x_salidatxt" name="x_salidatxt" id="x_salidatxt" size="35" placeholder="<?php echo ew_HtmlEncode($geoprocesamiento->salidatxt->getPlaceHolder()) ?>" value="<?php echo $geoprocesamiento->salidatxt->EditValue ?>"<?php echo $geoprocesamiento->salidatxt->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($geoprocesamiento->opcionestxt->Visible) { // opcionestxt ?>
	<div id="r_opcionestxt" class="form-group">
		<label for="x_opcionestxt" class="<?php echo $geoprocesamiento_search->SearchLabelClass ?>"><span id="elh_geoprocesamiento_opcionestxt"><?php echo $geoprocesamiento->opcionestxt->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_opcionestxt" id="z_opcionestxt" value="LIKE"></p>
		</label>
		<div class="<?php echo $geoprocesamiento_search->SearchRightColumnClass ?>"><div<?php echo $geoprocesamiento->opcionestxt->CellAttributes() ?>>
			<span id="el_geoprocesamiento_opcionestxt">
<input type="text" data-table="geoprocesamiento" data-field="x_opcionestxt" name="x_opcionestxt" id="x_opcionestxt" size="35" placeholder="<?php echo ew_HtmlEncode($geoprocesamiento->opcionestxt->getPlaceHolder()) ?>" value="<?php echo $geoprocesamiento->opcionestxt->EditValue ?>"<?php echo $geoprocesamiento->opcionestxt->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
</div>
<?php if (!$geoprocesamiento_search->IsModal) { ?>
<div class="form-group">
	<div class="col-sm-offset-3 col-sm-9">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("Search") ?></button>
<button class="btn btn-default ewButton" name="btnReset" id="btnReset" type="button" onclick="ew_ClearForm(this.form);"><?php echo $Language->Phrase("Reset") ?></button>
	</div>
</div>
<?php } ?>
</form>
<script type="text/javascript">
fgeoprocesamientosearch.Init();
</script>
<?php
$geoprocesamiento_search->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$geoprocesamiento_search->Page_Terminate();
?>
