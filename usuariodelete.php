<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg12.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql12.php") ?>
<?php include_once "phpfn12.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn12.php" ?>
<?php

//
// Page class
//

$usuario_delete = NULL; // Initialize page object first

class cusuario_delete extends cusuario {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{00441056-EF9D-4233-BDD9-EE81681FA399}";

	// Table name
	var $TableName = 'usuario';

	// Page object name
	var $PageObjName = 'usuario_delete';

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

		// Table object (usuario)
		if (!isset($GLOBALS["usuario"]) || get_class($GLOBALS["usuario"]) == "cusuario") {
			$GLOBALS["usuario"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["usuario"];
		}

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'usuario', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("usuariolist.php"));
			else
				$this->Page_Terminate(ew_GetUrl("login.php"));
		}
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->idusuario->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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
		global $EW_EXPORT, $usuario;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($usuario);
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
			$this->Page_Terminate("usuariolist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in usuario class, usuarioinfo.php

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
				$rs = $conn->SelectLimit($sSql, $rowcnt, $offset, array("_hasOrderBy" => trim($this->getOrderBy()) || trim($this->getSessionOrderByList())));
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
		$this->idusuario->setDbValue($rs->fields('idusuario'));
		$this->user->setDbValue($rs->fields('user'));
		$this->password->setDbValue($rs->fields('password'));
		$this->nombre->setDbValue($rs->fields('nombre'));
		$this->userlevelid->setDbValue($rs->fields('userlevelid'));
		if (array_key_exists('EV__userlevelid', $rs->fields)) {
			$this->userlevelid->VirtualValue = $rs->fields('EV__userlevelid'); // Set up virtual field value
		} else {
			$this->userlevelid->VirtualValue = ""; // Clear value
		}
		$this->_email->setDbValue($rs->fields('email'));
		$this->activo->setDbValue($rs->fields('activo'));
		$this->idperfil->setDbValue($rs->fields('idperfil'));
		$this->autologinip->setDbValue($rs->fields('autologinip'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->idusuario->DbValue = $row['idusuario'];
		$this->user->DbValue = $row['user'];
		$this->password->DbValue = $row['password'];
		$this->nombre->DbValue = $row['nombre'];
		$this->userlevelid->DbValue = $row['userlevelid'];
		$this->_email->DbValue = $row['email'];
		$this->activo->DbValue = $row['activo'];
		$this->idperfil->DbValue = $row['idperfil'];
		$this->autologinip->DbValue = $row['autologinip'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// idusuario
		// user
		// password
		// nombre
		// userlevelid
		// email
		// activo
		// idperfil
		// autologinip

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// idusuario
		$this->idusuario->ViewValue = $this->idusuario->CurrentValue;
		$this->idusuario->ViewCustomAttributes = "";

		// user
		$this->user->ViewValue = $this->user->CurrentValue;
		$this->user->ViewCustomAttributes = ["style" => "text-transform: none;"];

		// password
		$this->password->ViewValue = $Language->Phrase("PasswordMask");
		$this->password->ViewCustomAttributes = ["style" => "text-transform: none;"];

		// nombre
		$this->nombre->ViewValue = $this->nombre->CurrentValue;
		$this->nombre->ViewCustomAttributes = "";

		// userlevelid
		if ($Security->CanAdmin()) { // System admin
		if ($this->userlevelid->VirtualValue <> "") {
			$this->userlevelid->ViewValue = $this->userlevelid->VirtualValue;
		} else {
		if (strval($this->userlevelid->CurrentValue) <> "") {
			$sFilterWrk = "\"userlevelid\"" . ew_SearchString("=", $this->userlevelid->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT \"userlevelid\", \"userlevelname\" AS \"DispFld\", '' AS \"Disp2Fld\", '' AS \"Disp3Fld\", '' AS \"Disp4Fld\" FROM \"registro_derecho\".\"userlevels\"";
		$sWhereWrk = "";
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->userlevelid, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY \"userlevelname\"";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->userlevelid->ViewValue = $this->userlevelid->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->userlevelid->ViewValue = $this->userlevelid->CurrentValue;
			}
		} else {
			$this->userlevelid->ViewValue = NULL;
		}
		}
		} else {
			$this->userlevelid->ViewValue = $Language->Phrase("PasswordMask");
		}
		$this->userlevelid->ViewCustomAttributes = "";

		// email
		$this->_email->ViewValue = $this->_email->CurrentValue;
		$this->_email->ViewCustomAttributes = "";

		// activo
		if (strval($this->activo->CurrentValue) <> "") {
			$this->activo->ViewValue = $this->activo->OptionCaption($this->activo->CurrentValue);
		} else {
			$this->activo->ViewValue = NULL;
		}
		$this->activo->ViewCustomAttributes = "";

		// idperfil
		if (strval($this->idperfil->CurrentValue) <> "") {
			$arwrk = explode(",", $this->idperfil->CurrentValue);
			$sFilterWrk = "";
			foreach ($arwrk as $wrk) {
				if ($sFilterWrk <> "") $sFilterWrk .= " OR ";
				$sFilterWrk .= "\"idperfil\"" . ew_SearchString("=", trim($wrk), EW_DATATYPE_NUMBER, "");
			}
		$sSqlWrk = "SELECT \"idperfil\", \"idperfil\" AS \"DispFld\", '' AS \"Disp2Fld\", '' AS \"Disp3Fld\", '' AS \"Disp4Fld\" FROM \"registro_derecho\".\"perfil\"";
		$sWhereWrk = "";
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->idperfil, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->idperfil->ViewValue = "";
				$ari = 0;
				while (!$rswrk->EOF) {
					$arwrk = array();
					$arwrk[1] = $rswrk->fields('DispFld');
					$this->idperfil->ViewValue .= $this->idperfil->DisplayValue($arwrk);
					$rswrk->MoveNext();
					if (!$rswrk->EOF) $this->idperfil->ViewValue .= ew_ViewOptionSeparator($ari); // Separate Options
					$ari++;
				}
				$rswrk->Close();
			} else {
				$this->idperfil->ViewValue = $this->idperfil->CurrentValue;
			}
		} else {
			$this->idperfil->ViewValue = NULL;
		}
		$this->idperfil->ViewCustomAttributes = "";

		// autologinip
		$this->autologinip->ViewValue = $this->autologinip->CurrentValue;
		$this->autologinip->ViewCustomAttributes = "";

			// idusuario
			$this->idusuario->LinkCustomAttributes = "";
			$this->idusuario->HrefValue = "";
			$this->idusuario->TooltipValue = "";

			// user
			$this->user->LinkCustomAttributes = "";
			if (!ew_Empty($this->idusuario->CurrentValue)) {
				$this->user->HrefValue = "usuarioedit.php?idusuario=" . $this->idusuario->CurrentValue; // Add prefix/suffix
				$this->user->LinkAttrs["target"] = ""; // Add target
				if ($this->Export <> "") $this->user->HrefValue = ew_ConvertFullUrl($this->user->HrefValue);
			} else {
				$this->user->HrefValue = "";
			}
			$this->user->TooltipValue = "";

			// password
			$this->password->LinkCustomAttributes = "";
			$this->password->HrefValue = "";
			$this->password->TooltipValue = "";

			// nombre
			$this->nombre->LinkCustomAttributes = "";
			$this->nombre->HrefValue = "";
			$this->nombre->TooltipValue = "";

			// userlevelid
			$this->userlevelid->LinkCustomAttributes = "";
			$this->userlevelid->HrefValue = "";
			$this->userlevelid->TooltipValue = "";

			// email
			$this->_email->LinkCustomAttributes = "";
			$this->_email->HrefValue = "";
			$this->_email->TooltipValue = "";

			// activo
			$this->activo->LinkCustomAttributes = "";
			$this->activo->HrefValue = "";
			$this->activo->TooltipValue = "";

			// idperfil
			$this->idperfil->LinkCustomAttributes = "";
			$this->idperfil->HrefValue = "";
			$this->idperfil->TooltipValue = "";

			// autologinip
			$this->autologinip->LinkCustomAttributes = "";
			$this->autologinip->HrefValue = "";
			$this->autologinip->TooltipValue = "";
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
				$sThisKey .= $row['idusuario'];
				$this->LoadDbValues($row);
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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("usuariolist.php"), "", $this->TableVar, TRUE);
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
if (!isset($usuario_delete)) $usuario_delete = new cusuario_delete();

// Page init
$usuario_delete->Page_Init();

// Page main
$usuario_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$usuario_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "delete";
var CurrentForm = fusuariodelete = new ew_Form("fusuariodelete", "delete");

// Form_CustomValidate event
fusuariodelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fusuariodelete.ValidateRequired = true;
<?php } else { ?>
fusuariodelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fusuariodelete.Lists["x_userlevelid"] = {"LinkField":"x_userlevelid","Ajax":true,"AutoFill":false,"DisplayFields":["x_userlevelname","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fusuariodelete.Lists["x_activo"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fusuariodelete.Lists["x_activo"].Options = <?php echo json_encode($usuario->activo->Options()) ?>;
fusuariodelete.Lists["x_idperfil[]"] = {"LinkField":"x_idperfil","Ajax":true,"AutoFill":false,"DisplayFields":["x_idperfil","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($usuario_delete->Recordset = $usuario_delete->LoadRecordset())
	$usuario_deleteTotalRecs = $usuario_delete->Recordset->RecordCount(); // Get record count
if ($usuario_deleteTotalRecs <= 0) { // No record found, exit
	if ($usuario_delete->Recordset)
		$usuario_delete->Recordset->Close();
	$usuario_delete->Page_Terminate("usuariolist.php"); // Return to list
}
?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $usuario_delete->ShowPageHeader(); ?>
<?php
$usuario_delete->ShowMessage();
?>
<form name="fusuariodelete" id="fusuariodelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($usuario_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $usuario_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="usuario">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($usuario_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table class="table ewTable">
<?php echo $usuario->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($usuario->idusuario->Visible) { // idusuario ?>
		<th><span id="elh_usuario_idusuario" class="usuario_idusuario"><?php echo $usuario->idusuario->FldCaption() ?></span></th>
<?php } ?>
<?php if ($usuario->user->Visible) { // user ?>
		<th><span id="elh_usuario_user" class="usuario_user"><?php echo $usuario->user->FldCaption() ?></span></th>
<?php } ?>
<?php if ($usuario->password->Visible) { // password ?>
		<th><span id="elh_usuario_password" class="usuario_password"><?php echo $usuario->password->FldCaption() ?></span></th>
<?php } ?>
<?php if ($usuario->nombre->Visible) { // nombre ?>
		<th><span id="elh_usuario_nombre" class="usuario_nombre"><?php echo $usuario->nombre->FldCaption() ?></span></th>
<?php } ?>
<?php if ($usuario->userlevelid->Visible) { // userlevelid ?>
		<th><span id="elh_usuario_userlevelid" class="usuario_userlevelid"><?php echo $usuario->userlevelid->FldCaption() ?></span></th>
<?php } ?>
<?php if ($usuario->_email->Visible) { // email ?>
		<th><span id="elh_usuario__email" class="usuario__email"><?php echo $usuario->_email->FldCaption() ?></span></th>
<?php } ?>
<?php if ($usuario->activo->Visible) { // activo ?>
		<th><span id="elh_usuario_activo" class="usuario_activo"><?php echo $usuario->activo->FldCaption() ?></span></th>
<?php } ?>
<?php if ($usuario->idperfil->Visible) { // idperfil ?>
		<th><span id="elh_usuario_idperfil" class="usuario_idperfil"><?php echo $usuario->idperfil->FldCaption() ?></span></th>
<?php } ?>
<?php if ($usuario->autologinip->Visible) { // autologinip ?>
		<th><span id="elh_usuario_autologinip" class="usuario_autologinip"><?php echo $usuario->autologinip->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$usuario_delete->RecCnt = 0;
$i = 0;
while (!$usuario_delete->Recordset->EOF) {
	$usuario_delete->RecCnt++;
	$usuario_delete->RowCnt++;

	// Set row properties
	$usuario->ResetAttrs();
	$usuario->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$usuario_delete->LoadRowValues($usuario_delete->Recordset);

	// Render row
	$usuario_delete->RenderRow();
?>
	<tr<?php echo $usuario->RowAttributes() ?>>
<?php if ($usuario->idusuario->Visible) { // idusuario ?>
		<td<?php echo $usuario->idusuario->CellAttributes() ?>>
<span id="el<?php echo $usuario_delete->RowCnt ?>_usuario_idusuario" class="usuario_idusuario">
<span<?php echo $usuario->idusuario->ViewAttributes() ?>>
<?php echo $usuario->idusuario->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($usuario->user->Visible) { // user ?>
		<td<?php echo $usuario->user->CellAttributes() ?>>
<span id="el<?php echo $usuario_delete->RowCnt ?>_usuario_user" class="usuario_user">
<span<?php echo $usuario->user->ViewAttributes() ?>>
<?php if ((!ew_EmptyStr($usuario->user->ListViewValue())) && $usuario->user->LinkAttributes() <> "") { ?>
<a<?php echo $usuario->user->LinkAttributes() ?>><?php echo $usuario->user->ListViewValue() ?></a>
<?php } else { ?>
<?php echo $usuario->user->ListViewValue() ?>
<?php } ?>
</span>
</span>
</td>
<?php } ?>
<?php if ($usuario->password->Visible) { // password ?>
		<td<?php echo $usuario->password->CellAttributes() ?>>
<span id="el<?php echo $usuario_delete->RowCnt ?>_usuario_password" class="usuario_password">
<span<?php echo $usuario->password->ViewAttributes() ?>>
<?php echo $usuario->password->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($usuario->nombre->Visible) { // nombre ?>
		<td<?php echo $usuario->nombre->CellAttributes() ?>>
<span id="el<?php echo $usuario_delete->RowCnt ?>_usuario_nombre" class="usuario_nombre">
<span<?php echo $usuario->nombre->ViewAttributes() ?>>
<?php echo $usuario->nombre->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($usuario->userlevelid->Visible) { // userlevelid ?>
		<td<?php echo $usuario->userlevelid->CellAttributes() ?>>
<span id="el<?php echo $usuario_delete->RowCnt ?>_usuario_userlevelid" class="usuario_userlevelid">
<span<?php echo $usuario->userlevelid->ViewAttributes() ?>>
<?php echo $usuario->userlevelid->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($usuario->_email->Visible) { // email ?>
		<td<?php echo $usuario->_email->CellAttributes() ?>>
<span id="el<?php echo $usuario_delete->RowCnt ?>_usuario__email" class="usuario__email">
<span<?php echo $usuario->_email->ViewAttributes() ?>>
<?php echo $usuario->_email->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($usuario->activo->Visible) { // activo ?>
		<td<?php echo $usuario->activo->CellAttributes() ?>>
<span id="el<?php echo $usuario_delete->RowCnt ?>_usuario_activo" class="usuario_activo">
<span<?php echo $usuario->activo->ViewAttributes() ?>>
<?php echo $usuario->activo->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($usuario->idperfil->Visible) { // idperfil ?>
		<td<?php echo $usuario->idperfil->CellAttributes() ?>>
<span id="el<?php echo $usuario_delete->RowCnt ?>_usuario_idperfil" class="usuario_idperfil">
<span<?php echo $usuario->idperfil->ViewAttributes() ?>>
<?php echo $usuario->idperfil->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($usuario->autologinip->Visible) { // autologinip ?>
		<td<?php echo $usuario->autologinip->CellAttributes() ?>>
<span id="el<?php echo $usuario_delete->RowCnt ?>_usuario_autologinip" class="usuario_autologinip">
<span<?php echo $usuario->autologinip->ViewAttributes() ?>>
<?php echo $usuario->autologinip->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$usuario_delete->Recordset->MoveNext();
}
$usuario_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</div>
<div>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $usuario_delete->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
</div>
</form>
<script type="text/javascript">
fusuariodelete.Init();
</script>
<?php
$usuario_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$usuario_delete->Page_Terminate();
?>
