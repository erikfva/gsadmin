<?php

// Global variable for table object
$shapefiles = NULL;

//
// Table class for shapefiles
//
class cshapefiles extends cTable {
	var $idshapefile;
	var $narchivoorigen;
	var $narchivo;
	var $idaplicacion;
	var $token;
	var $idusuario;
	var $tipo;
	var $folder;
	var $fechacreacion;
	var $tamano;
	var $srid;
	var $tipogeom;
	var $acciones;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'shapefiles';
		$this->TableName = 'shapefiles';
		$this->TableType = 'TABLE';

		// Update Table
		$this->UpdateTable = "\"registro_derecho\".\"shapefiles\"";
		$this->DBID = 'DB';
		$this->ExportAll = TRUE;
		$this->ExportPageBreakCount = 0; // Page break per every n record (PDF only)
		$this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)
		$this->ExportPageSize = "a4"; // Page size (PDF only)
		$this->ExportExcelPageOrientation = ""; // Page orientation (PHPExcel only)
		$this->ExportExcelPageSize = ""; // Page size (PHPExcel only)
		$this->DetailAdd = FALSE; // Allow detail add
		$this->DetailEdit = FALSE; // Allow detail edit
		$this->DetailView = FALSE; // Allow detail view
		$this->ShowMultipleDetails = FALSE; // Show multiple details
		$this->GridAddRowCount = 5;
		$this->AllowAddDeleteRow = ew_AllowAddDeleteRow(); // Allow add/delete row
		$this->UserIDAllowSecurity = 0; // User ID Allow
		$this->BasicSearch = new cBasicSearch($this->TableVar);

		// idshapefile
		$this->idshapefile = new cField('shapefiles', 'shapefiles', 'x_idshapefile', 'idshapefile', '"idshapefile"', 'CAST("idshapefile" AS varchar(255))', 3, -1, FALSE, '"idshapefile"', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'NO');
		$this->idshapefile->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['idshapefile'] = &$this->idshapefile;

		// narchivoorigen
		$this->narchivoorigen = new cField('shapefiles', 'shapefiles', 'x_narchivoorigen', 'narchivoorigen', '"narchivoorigen"', '"narchivoorigen"', 200, -1, FALSE, '"narchivoorigen"', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->narchivoorigen->UploadAllowedFileExt = "zip,arj";
		$this->fields['narchivoorigen'] = &$this->narchivoorigen;

		// narchivo
		$this->narchivo = new cField('shapefiles', 'shapefiles', 'x_narchivo', 'narchivo', '"narchivo"', '"narchivo"', 200, -1, TRUE, '"narchivo"', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'FILE');
		$this->narchivo->UploadAllowedFileExt = "zip,arj";
		$this->fields['narchivo'] = &$this->narchivo;

		// idaplicacion
		$this->idaplicacion = new cField('shapefiles', 'shapefiles', 'x_idaplicacion', 'idaplicacion', '"idaplicacion"', '"idaplicacion"', 200, -1, FALSE, '"idaplicacion"', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['idaplicacion'] = &$this->idaplicacion;

		// token
		$this->token = new cField('shapefiles', 'shapefiles', 'x_token', 'token', '"token"', '"token"', 200, -1, FALSE, '"token"', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['token'] = &$this->token;

		// idusuario
		$this->idusuario = new cField('shapefiles', 'shapefiles', 'x_idusuario', 'idusuario', '"idusuario"', 'CAST("idusuario" AS varchar(255))', 3, -1, FALSE, '"idusuario"', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->idusuario->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['idusuario'] = &$this->idusuario;

		// tipo
		$this->tipo = new cField('shapefiles', 'shapefiles', 'x_tipo', 'tipo', '"tipo"', '"tipo"', 200, -1, FALSE, '"tipo"', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['tipo'] = &$this->tipo;

		// folder
		$this->folder = new cField('shapefiles', 'shapefiles', 'x_folder', 'folder', '"folder"', '"folder"', 200, -1, FALSE, '"folder"', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['folder'] = &$this->folder;

		// fechacreacion
		$this->fechacreacion = new cField('shapefiles', 'shapefiles', 'x_fechacreacion', 'fechacreacion', '"fechacreacion"', 'CAST("fechacreacion" AS varchar(255))', 135, -1, FALSE, '"fechacreacion"', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['fechacreacion'] = &$this->fechacreacion;

		// tamano
		$this->tamano = new cField('shapefiles', 'shapefiles', 'x_tamano', 'tamano', '"tamano"', 'CAST("tamano" AS varchar(255))', 20, -1, FALSE, '"tamano"', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->tamano->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['tamano'] = &$this->tamano;

		// srid
		$this->srid = new cField('shapefiles', 'shapefiles', 'x_srid', 'srid', '"srid"', 'CAST("srid" AS varchar(255))', 3, -1, FALSE, '"srid"', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'RADIO');
		$this->srid->OptionCount = 4;
		$this->srid->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['srid'] = &$this->srid;

		// tipogeom
		$this->tipogeom = new cField('shapefiles', 'shapefiles', 'x_tipogeom', 'tipogeom', '"tipogeom"', 'CAST("tipogeom" AS varchar(255))', 3, -1, FALSE, '"tipogeom"', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->tipogeom->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['tipogeom'] = &$this->tipogeom;

		// acciones
		$this->acciones = new cField('shapefiles', 'shapefiles', 'x_acciones', 'acciones', '\'\'', '\'\'', 201, -1, FALSE, '\'\'', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'CHECKBOX');
		$this->acciones->FldIsCustom = TRUE; // Custom field
		$this->fields['acciones'] = &$this->acciones;
	}

	// Single column sort
	function UpdateSort(&$ofld) {
		if ($this->CurrentOrder == $ofld->FldName) {
			$sSortField = $ofld->FldExpression;
			$sLastSort = $ofld->getSort();
			if ($this->CurrentOrderType == "ASC" || $this->CurrentOrderType == "DESC") {
				$sThisSort = $this->CurrentOrderType;
			} else {
				$sThisSort = ($sLastSort == "ASC") ? "DESC" : "ASC";
			}
			$ofld->setSort($sThisSort);
			$this->setSessionOrderBy($sSortField . " " . $sThisSort); // Save to Session
		} else {
			$ofld->setSort("");
		}
	}

	// Table level SQL
	var $_SqlFrom = "";

	function getSqlFrom() { // From
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "\"registro_derecho\".\"shapefiles\"";
	}

	function SqlFrom() { // For backward compatibility
    	return $this->getSqlFrom();
	}

	function setSqlFrom($v) {
    	$this->_SqlFrom = $v;
	}
	var $_SqlSelect = "";

	function getSqlSelect() { // Select
		return ($this->_SqlSelect <> "") ? $this->_SqlSelect : "SELECT *, '' AS \"acciones\" FROM " . $this->getSqlFrom();
	}

	function SqlSelect() { // For backward compatibility
    	return $this->getSqlSelect();
	}

	function setSqlSelect($v) {
    	$this->_SqlSelect = $v;
	}
	var $_SqlWhere = "";

	function getSqlWhere() { // Where
		$sWhere = ($this->_SqlWhere <> "") ? $this->_SqlWhere : "";
		$this->TableFilter = "";
		ew_AddFilter($sWhere, $this->TableFilter);
		return $sWhere;
	}

	function SqlWhere() { // For backward compatibility
    	return $this->getSqlWhere();
	}

	function setSqlWhere($v) {
    	$this->_SqlWhere = $v;
	}
	var $_SqlGroupBy = "";

	function getSqlGroupBy() { // Group By
		return ($this->_SqlGroupBy <> "") ? $this->_SqlGroupBy : "";
	}

	function SqlGroupBy() { // For backward compatibility
    	return $this->getSqlGroupBy();
	}

	function setSqlGroupBy($v) {
    	$this->_SqlGroupBy = $v;
	}
	var $_SqlHaving = "";

	function getSqlHaving() { // Having
		return ($this->_SqlHaving <> "") ? $this->_SqlHaving : "";
	}

	function SqlHaving() { // For backward compatibility
    	return $this->getSqlHaving();
	}

	function setSqlHaving($v) {
    	$this->_SqlHaving = $v;
	}
	var $_SqlOrderBy = "";

	function getSqlOrderBy() { // Order By
		return ($this->_SqlOrderBy <> "") ? $this->_SqlOrderBy : "\"fechacreacion\" DESC";
	}

	function SqlOrderBy() { // For backward compatibility
    	return $this->getSqlOrderBy();
	}

	function setSqlOrderBy($v) {
    	$this->_SqlOrderBy = $v;
	}

	// Apply User ID filters
	function ApplyUserIDFilters($sFilter) {
		return $sFilter;
	}

	// Check if User ID security allows view all
	function UserIDAllow($id = "") {
		$allow = EW_USER_ID_ALLOW;
		switch ($id) {
			case "add":
			case "copy":
			case "gridadd":
			case "register":
			case "addopt":
				return (($allow & 1) == 1);
			case "edit":
			case "gridedit":
			case "update":
			case "changepwd":
			case "forgotpwd":
				return (($allow & 4) == 4);
			case "delete":
				return (($allow & 2) == 2);
			case "view":
				return (($allow & 32) == 32);
			case "search":
				return (($allow & 64) == 64);
			default:
				return (($allow & 8) == 8);
		}
	}

	// Get SQL
	function GetSQL($where, $orderby) {
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(),
			$this->getSqlGroupBy(), $this->getSqlHaving(), $this->getSqlOrderBy(),
			$where, $orderby);
	}

	// Table SQL
	function SQL() {
		$sFilter = $this->CurrentFilter;
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(),
			$this->getSqlGroupBy(), $this->getSqlHaving(), $this->getSqlOrderBy(),
			$sFilter, $sSort);
	}

	// Table SQL with List page filter
	function SelectSQL() {
		$sFilter = $this->getSessionWhere();
		ew_AddFilter($sFilter, $this->CurrentFilter);
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$this->Recordset_Selecting($sFilter);
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(), $this->getSqlGroupBy(),
			$this->getSqlHaving(), $this->getSqlOrderBy(), $sFilter, $sSort);
	}

	// Get ORDER BY clause
	function GetOrderBy() {
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql("", "", "", "", $this->getSqlOrderBy(), "", $sSort);
	}

	// Try to get record count
	function TryGetRecordCount($sSql) {
		$cnt = -1;
		if (($this->TableType == 'TABLE' || $this->TableType == 'VIEW' || $this->TableType == 'LINKTABLE') && preg_match("/^SELECT \* FROM/i", $sSql)) {
			$sSql = "SELECT COUNT(*) FROM" . preg_replace('/^SELECT\s([\s\S]+)?\*\sFROM/i', "", $sSql);
			$sOrderBy = $this->GetOrderBy();
			if (substr($sSql, strlen($sOrderBy) * -1) == $sOrderBy)
				$sSql = substr($sSql, 0, strlen($sSql) - strlen($sOrderBy)); // Remove ORDER BY clause
		} else {
			$sSql = "SELECT COUNT(*) FROM (" . $sSql . ") EW_COUNT_TABLE";
		}
		$conn = &$this->Connection();
		if ($rs = $conn->Execute($sSql)) {
			if (!$rs->EOF && $rs->FieldCount() > 0) {
				$cnt = $rs->fields[0];
				$rs->Close();
			}
		}
		return intval($cnt);
	}

	// Get record count based on filter (for detail record count in master table pages)
	function LoadRecordCount($sFilter) {
		$origFilter = $this->CurrentFilter;
		$this->CurrentFilter = $sFilter;
		$this->Recordset_Selecting($this->CurrentFilter);

		//$sSql = $this->SQL();
		$sSql = $this->GetSQL($this->CurrentFilter, "");
		$cnt = $this->TryGetRecordCount($sSql);
		if ($cnt == -1) {
			if ($rs = $this->LoadRs($this->CurrentFilter)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		$this->CurrentFilter = $origFilter;
		return intval($cnt);
	}

	// Get record count (for current List page)
	function SelectRecordCount() {
		$sSql = $this->SelectSQL();
		$cnt = $this->TryGetRecordCount($sSql);
		if ($cnt == -1) {
			$conn = &$this->Connection();
			if ($rs = $conn->Execute($sSql)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		return intval($cnt);
	}

	// INSERT statement
	function InsertSQL(&$rs) {
		$names = "";
		$values = "";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]) || $this->fields[$name]->FldIsCustom)
				continue;
			$names .= $this->fields[$name]->FldExpression . ",";
			$values .= ew_QuotedValue($value, $this->fields[$name]->FldDataType, $this->DBID) . ",";
		}
		while (substr($names, -1) == ",")
			$names = substr($names, 0, -1);
		while (substr($values, -1) == ",")
			$values = substr($values, 0, -1);
		return "INSERT INTO " . $this->UpdateTable . " ($names) VALUES ($values)";
	}

	// Insert
	function Insert(&$rs) {
		$conn = &$this->Connection();
		return $conn->Execute($this->InsertSQL($rs));
	}

	// UPDATE statement
	function UpdateSQL(&$rs, $where = "", $curfilter = TRUE) {
		$sql = "UPDATE " . $this->UpdateTable . " SET ";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]) || $this->fields[$name]->FldIsCustom)
				continue;
			$sql .= $this->fields[$name]->FldExpression . "=";
			$sql .= ew_QuotedValue($value, $this->fields[$name]->FldDataType, $this->DBID) . ",";
		}
		while (substr($sql, -1) == ",")
			$sql = substr($sql, 0, -1);
		$filter = ($curfilter) ? $this->CurrentFilter : "";
		if (is_array($where))
			$where = $this->ArrayToFilter($where);
		ew_AddFilter($filter, $where);
		if ($filter <> "")	$sql .= " WHERE " . $filter;
		return $sql;
	}

	// Update
	function Update(&$rs, $where = "", $rsold = NULL, $curfilter = TRUE) {
		$conn = &$this->Connection();
		return $conn->Execute($this->UpdateSQL($rs, $where, $curfilter));
	}

	// DELETE statement
	function DeleteSQL(&$rs, $where = "", $curfilter = TRUE) {
		$sql = "DELETE FROM " . $this->UpdateTable . " WHERE ";
		if (is_array($where))
			$where = $this->ArrayToFilter($where);
		if ($rs) {
			if (array_key_exists('idshapefile', $rs))
				ew_AddFilter($where, ew_QuotedName('idshapefile', $this->DBID) . '=' . ew_QuotedValue($rs['idshapefile'], $this->idshapefile->FldDataType, $this->DBID));
		}
		$filter = ($curfilter) ? $this->CurrentFilter : "";
		ew_AddFilter($filter, $where);
		if ($filter <> "")
			$sql .= $filter;
		else
			$sql .= "0=1"; // Avoid delete
		return $sql;
	}

	// Delete
	function Delete(&$rs, $where = "", $curfilter = TRUE) {
		$conn = &$this->Connection();
		return $conn->Execute($this->DeleteSQL($rs, $where, $curfilter));
	}

	// Key filter WHERE clause
	function SqlKeyFilter() {
		return "\"idshapefile\" = @idshapefile@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->idshapefile->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@idshapefile@", ew_AdjustSql($this->idshapefile->CurrentValue, $this->DBID), $sKeyFilter); // Replace key value
		return $sKeyFilter;
	}

	// Return page URL
	function getReturnUrl() {
		$name = EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL;

		// Get referer URL automatically
		if (ew_ServerVar("HTTP_REFERER") <> "" && ew_ReferPage() <> ew_CurrentPage() && ew_ReferPage() <> "login.php") // Referer not same page or login page
			$_SESSION[$name] = ew_ServerVar("HTTP_REFERER"); // Save to Session
		if (@$_SESSION[$name] <> "") {
			return $_SESSION[$name];
		} else {
			return "shapefileslist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "shapefileslist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("shapefilesview.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("shapefilesview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			$url = "shapefilesadd.php?" . $this->UrlParm($parm);
		else
			$url = "shapefilesadd.php";
		return $this->AddMasterUrl($url);
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		$url = $this->KeyUrl("shapefilesedit.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
		return $this->AddMasterUrl($url);
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		$url = $this->KeyUrl("shapefilesadd.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
		return $this->AddMasterUrl($url);
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("shapefilesdelete.php", $this->UrlParm());
	}

	// Add master url
	function AddMasterUrl($url) {
		return $url;
	}

	function KeyToJson() {
		$json = "";
		$json .= "idshapefile:" . ew_VarToJson($this->idshapefile->CurrentValue, "number", "'");
		return "{" . $json . "}";
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->idshapefile->CurrentValue)) {
			$sUrl .= "idshapefile=" . urlencode($this->idshapefile->CurrentValue);
		} else {
			return "javascript:ew_Alert(ewLanguage.Phrase('InvalidRecord'));";
		}
		return $sUrl;
	}

	// Sort URL
	function SortUrl(&$fld) {
		if ($this->CurrentAction <> "" || $this->Export <> "" ||
			in_array($fld->FldType, array(128, 204, 205))) { // Unsortable data type
				return "";
		} elseif ($fld->Sortable) {
			$sUrlParm = $this->UrlParm("order=" . urlencode($fld->FldName) . "&amp;ordertype=" . $fld->ReverseSort());
			return ew_CurrentPage() . "?" . $sUrlParm;
		} else {
			return "";
		}
	}

	// Get record keys from $_POST/$_GET/$_SESSION
	function GetRecordKeys() {
		global $EW_COMPOSITE_KEY_SEPARATOR;
		$arKeys = array();
		$arKey = array();
		if (isset($_POST["key_m"])) {
			$arKeys = ew_StripSlashes($_POST["key_m"]);
			$cnt = count($arKeys);
		} elseif (isset($_GET["key_m"])) {
			$arKeys = ew_StripSlashes($_GET["key_m"]);
			$cnt = count($arKeys);
		} elseif (!empty($_GET) || !empty($_POST)) {
			$isPost = ew_IsHttpPost();
			if ($isPost && isset($_POST["idshapefile"]))
				$arKeys[] = ew_StripSlashes($_POST["idshapefile"]);
			elseif (isset($_GET["idshapefile"]))
				$arKeys[] = ew_StripSlashes($_GET["idshapefile"]);
			else
				$arKeys = NULL; // Do not setup

			//return $arKeys; // Do not return yet, so the values will also be checked by the following code
		}

		// Check keys
		$ar = array();
		if (is_array($arKeys)) {
			foreach ($arKeys as $key) {
				if (!is_numeric($key))
					continue;
				$ar[] = $key;
			}
		}
		return $ar;
	}

	// Get key filter
	function GetKeyFilter() {
		$arKeys = $this->GetRecordKeys();
		$sKeyFilter = "";
		foreach ($arKeys as $key) {
			if ($sKeyFilter <> "") $sKeyFilter .= " OR ";
			$this->idshapefile->CurrentValue = $key;
			$sKeyFilter .= "(" . $this->KeyFilter() . ")";
		}
		return $sKeyFilter;
	}

	// Load rows based on filter
	function &LoadRs($sFilter) {

		// Set up filter (SQL WHERE clause) and get return SQL
		//$this->CurrentFilter = $sFilter;
		//$sSql = $this->SQL();

		$sSql = $this->GetSQL($sFilter, "");
		$conn = &$this->Connection();
		$rs = $conn->Execute($sSql);
		return $rs;
	}

	// Load row values from recordset
	function LoadListRowValues(&$rs) {
		$this->idshapefile->setDbValue($rs->fields('idshapefile'));
		$this->narchivoorigen->setDbValue($rs->fields('narchivoorigen'));
		$this->narchivo->Upload->DbValue = $rs->fields('narchivo');
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

	// Render list row values
	function RenderListRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
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

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Render edit row values
	function RenderEditRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

		// idshapefile
		$this->idshapefile->EditAttrs["class"] = "form-control";
		$this->idshapefile->EditCustomAttributes = "";
		$this->idshapefile->EditValue = $this->idshapefile->CurrentValue;
		$this->idshapefile->ViewCustomAttributes = "";

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

		// idaplicacion
		$this->idaplicacion->EditAttrs["class"] = "form-control";
		$this->idaplicacion->EditCustomAttributes = "";
		$this->idaplicacion->EditValue = $this->idaplicacion->CurrentValue;
		$this->idaplicacion->PlaceHolder = ew_RemoveHtml($this->idaplicacion->FldCaption());

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

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Aggregate list row values
	function AggregateListRowValues() {
	}

	// Aggregate list row (for rendering)
	function AggregateListRow() {

		// Call Row Rendered event
		$this->Row_Rendered();
	}
	var $ExportDoc;

	// Export data in HTML/CSV/Word/Excel/Email/PDF format
	function ExportDocument(&$Doc, &$Recordset, $StartRec, $StopRec, $ExportPageType = "") {
		if (!$Recordset || !$Doc)
			return;
		if (!$Doc->ExportCustom) {

			// Write header
			$Doc->ExportTableHeader();
			if ($Doc->Horizontal) { // Horizontal format, write header
				$Doc->BeginExportRow();
				if ($ExportPageType == "view") {
					if ($this->idshapefile->Exportable) $Doc->ExportCaption($this->idshapefile);
					if ($this->narchivoorigen->Exportable) $Doc->ExportCaption($this->narchivoorigen);
					if ($this->narchivo->Exportable) $Doc->ExportCaption($this->narchivo);
					if ($this->idaplicacion->Exportable) $Doc->ExportCaption($this->idaplicacion);
					if ($this->token->Exportable) $Doc->ExportCaption($this->token);
					if ($this->idusuario->Exportable) $Doc->ExportCaption($this->idusuario);
					if ($this->tipo->Exportable) $Doc->ExportCaption($this->tipo);
					if ($this->folder->Exportable) $Doc->ExportCaption($this->folder);
					if ($this->fechacreacion->Exportable) $Doc->ExportCaption($this->fechacreacion);
					if ($this->tamano->Exportable) $Doc->ExportCaption($this->tamano);
					if ($this->srid->Exportable) $Doc->ExportCaption($this->srid);
					if ($this->tipogeom->Exportable) $Doc->ExportCaption($this->tipogeom);
					if ($this->acciones->Exportable) $Doc->ExportCaption($this->acciones);
				} else {
					if ($this->idshapefile->Exportable) $Doc->ExportCaption($this->idshapefile);
					if ($this->narchivoorigen->Exportable) $Doc->ExportCaption($this->narchivoorigen);
					if ($this->narchivo->Exportable) $Doc->ExportCaption($this->narchivo);
					if ($this->idaplicacion->Exportable) $Doc->ExportCaption($this->idaplicacion);
					if ($this->token->Exportable) $Doc->ExportCaption($this->token);
					if ($this->idusuario->Exportable) $Doc->ExportCaption($this->idusuario);
					if ($this->tipo->Exportable) $Doc->ExportCaption($this->tipo);
					if ($this->folder->Exportable) $Doc->ExportCaption($this->folder);
					if ($this->fechacreacion->Exportable) $Doc->ExportCaption($this->fechacreacion);
					if ($this->tamano->Exportable) $Doc->ExportCaption($this->tamano);
					if ($this->srid->Exportable) $Doc->ExportCaption($this->srid);
					if ($this->tipogeom->Exportable) $Doc->ExportCaption($this->tipogeom);
				}
				$Doc->EndExportRow();
			}
		}

		// Move to first record
		$RecCnt = $StartRec - 1;
		if (!$Recordset->EOF) {
			$Recordset->MoveFirst();
			if ($StartRec > 1)
				$Recordset->Move($StartRec - 1);
		}
		while (!$Recordset->EOF && $RecCnt < $StopRec) {
			$RecCnt++;
			if (intval($RecCnt) >= intval($StartRec)) {
				$RowCnt = intval($RecCnt) - intval($StartRec) + 1;

				// Page break
				if ($this->ExportPageBreakCount > 0) {
					if ($RowCnt > 1 && ($RowCnt - 1) % $this->ExportPageBreakCount == 0)
						$Doc->ExportPageBreak();
				}
				$this->LoadListRowValues($Recordset);

				// Render row
				$this->RowType = EW_ROWTYPE_VIEW; // Render view
				$this->ResetAttrs();
				$this->RenderListRow();
				if (!$Doc->ExportCustom) {
					$Doc->BeginExportRow($RowCnt); // Allow CSS styles if enabled
					if ($ExportPageType == "view") {
						if ($this->idshapefile->Exportable) $Doc->ExportField($this->idshapefile);
						if ($this->narchivoorigen->Exportable) $Doc->ExportField($this->narchivoorigen);
						if ($this->narchivo->Exportable) $Doc->ExportField($this->narchivo);
						if ($this->idaplicacion->Exportable) $Doc->ExportField($this->idaplicacion);
						if ($this->token->Exportable) $Doc->ExportField($this->token);
						if ($this->idusuario->Exportable) $Doc->ExportField($this->idusuario);
						if ($this->tipo->Exportable) $Doc->ExportField($this->tipo);
						if ($this->folder->Exportable) $Doc->ExportField($this->folder);
						if ($this->fechacreacion->Exportable) $Doc->ExportField($this->fechacreacion);
						if ($this->tamano->Exportable) $Doc->ExportField($this->tamano);
						if ($this->srid->Exportable) $Doc->ExportField($this->srid);
						if ($this->tipogeom->Exportable) $Doc->ExportField($this->tipogeom);
						if ($this->acciones->Exportable) $Doc->ExportField($this->acciones);
					} else {
						if ($this->idshapefile->Exportable) $Doc->ExportField($this->idshapefile);
						if ($this->narchivoorigen->Exportable) $Doc->ExportField($this->narchivoorigen);
						if ($this->narchivo->Exportable) $Doc->ExportField($this->narchivo);
						if ($this->idaplicacion->Exportable) $Doc->ExportField($this->idaplicacion);
						if ($this->token->Exportable) $Doc->ExportField($this->token);
						if ($this->idusuario->Exportable) $Doc->ExportField($this->idusuario);
						if ($this->tipo->Exportable) $Doc->ExportField($this->tipo);
						if ($this->folder->Exportable) $Doc->ExportField($this->folder);
						if ($this->fechacreacion->Exportable) $Doc->ExportField($this->fechacreacion);
						if ($this->tamano->Exportable) $Doc->ExportField($this->tamano);
						if ($this->srid->Exportable) $Doc->ExportField($this->srid);
						if ($this->tipogeom->Exportable) $Doc->ExportField($this->tipogeom);
					}
					$Doc->EndExportRow();
				}
			}

			// Call Row Export server event
			if ($Doc->ExportCustom)
				$this->Row_Export($Recordset->fields);
			$Recordset->MoveNext();
		}
		if (!$Doc->ExportCustom) {
			$Doc->ExportTableFooter();
		}
	}

	// Get auto fill value
	function GetAutoFill($id, $val) {
		$rsarr = array();
		$rowcnt = 0;

		// Output
		if (is_array($rsarr) && $rowcnt > 0) {
			$fldcnt = count($rsarr[0]);
			for ($i = 0; $i < $rowcnt; $i++) {
				for ($j = 0; $j < $fldcnt; $j++) {
					$str = strval($rsarr[$i][$j]);
					$str = ew_ConvertToUtf8($str);
					if (isset($post["keepCRLF"])) {
						$str = str_replace(array("\r", "\n"), array("\\r", "\\n"), $str);
					} else {
						$str = str_replace(array("\r", "\n"), array(" ", " "), $str);
					}
					$rsarr[$i][$j] = $str;
				}
			}
			return ew_ArrayToJson($rsarr);
		} else {
			return FALSE;
		}
	}

	// Table level events
	// Recordset Selecting event
	function Recordset_Selecting(&$filter) {

		// Enter your code here	
	}

	// Recordset Selected event
	function Recordset_Selected(&$rs) {

		//echo "Recordset Selected";
	}

	// Recordset Search Validated event
	function Recordset_SearchValidated() {

		// Example:
		//$this->MyField1->AdvancedSearch->SearchValue = "your search criteria"; // Search value

	}

	// Recordset Searching event
	function Recordset_Searching(&$filter) {

		// Enter your code here	
	}

	// Row_Selecting event
	function Row_Selecting(&$filter) {

		// Enter your code here	
	}

	// Row Selected event
	function Row_Selected(&$rs) {

		//echo "Row Selected";
	}

	// Row Inserting event
	function Row_Inserting($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		if(chkopt("webservice") && !empty($_POST["x_filedata"])){ // datos pasados por API
			$this->narchivo->Upload->Value = $_POST["x_filedata"];
			try{
				$rsnew["narchivo"] = $_POST["x_filename"];
				$rsnew["tamano"] = $_POST["file"]["size"];
				$rsnew["tipo"] = ew_ContentType($_POST["x_filedata"],$_POST["x_filename"]);
			} catch (Exception $e) {
				setWSR("success","0");
				setWSR("msg",getMsg("101"));
				return FALSE;
			}
		}
		$rsnew["idusuario"] =  CurrentUserInfo("idusuario");
		$rsnew["fechacreacion"] =  ew_CurrentDateTime();
		$rsnew["narchivoorigen"] = $rsnew["narchivo"];
		$rsnew["folder"] = $this->narchivo->UploadPath;
		$bytes = openssl_random_pseudo_bytes(4, $cstrong);
		$hex   = str_shuffle("abcdefg").bin2hex($bytes);
		$rsnew["token"] = "f".date('Ymd').($hex); //el nombre de una tabla en postgresql debe comenzar con carater, la letra f se refiere a 'feature'
		$rsnew["narchivo"] = $rsnew["token"].".".pathinfo($rsnew["narchivoorigen"], PATHINFO_EXTENSION);

		//Creando la carpeta temporal para descomprimir el archivo
		$folder = ew_UploadTempPath($this->narchivo->FldVar, $this->narchivo->TblVar);
		ew_CleanUploadTempPaths(); // Clean all old temp folders
		ew_CleanPath($folder); // Clean the upload folder
		if (!file_exists($folder)) {
			if (!ew_CreateFolder($folder)){
				$msg = getMsg("102"). ": " . $folder;
				CurrentPage()->setFailureMessage($msg);
				setWSR("success","0");
				setWSR("msg",$msg);			
				return FALSE;
			}
		}

		//Descomprimiendo el archivo
		if ($this->narchivo->Visible && !$this->narchivo->Upload->KeepFile) {
			if (!ew_Empty($this->narchivo->Upload->Value)) {
				if ($this->narchivo->Upload->SaveToFile($this->narchivo->UploadPath, $rsnew['narchivo'], TRUE))
					$this->narchivo->Upload->KeepFile = FALSE;
			}
			if ($this->narchivo->Upload->DbValue <> "")
				@unlink(ew_UploadPathEx(TRUE, $this->narchivo->OldUploadPath) . $this->narchivo->Upload->DbValue);
		}
		$source_file = $rsnew["folder"] . $rsnew['narchivo'];
		$zip = new ZipArchive();

		// open the zip file to extract
		if ($zip->open($source_file) !== true) {
			$msg = getMsg("103") . ": " . $rsnew["narchivoorigen"];
			CurrentPage()->setFailureMessage($msg);
			setWSR("success","0");
			setWSR("msg",$msg);
			return FALSE;
		}

		// place in the temp folder
		if ($zip->extractTo($folder) !== true) {
			$zip->close();
			$msg = getMsg("104").": " . $source_file . " en la carpeta: " . $folder;
			CurrentPage()->setFailureMessage($msg);
			setWSR("success","0");
			setWSR("msg",$msg);
			return FALSE;
		}	
		$zip->close();

		//verificar si contiene los archivos basicos para leer shapefile: Shape (.shp),dBase (.dbf)
		$shapefile = glob(ew_IncludeTrailingDelimiter($folder, TRUE) . "*.shp");
		if(!$shapefile){
			$msg = getMsg("105");
			CurrentPage()->setFailureMessage($msg);
			setWSR("success","0");
			setWSR("msg",$msg);		
			return FALSE;
		}
		if(count($shapefile) > 1){ //verificando que el archivo empaquetado no contenga mas de un shape.
			$msg = getMsg("111"); //-->"El archivo empaquetado contiene mas de 1 shape."
			CurrentPage()->setFailureMessage($msg);
			setWSR("success","0");
			setWSR("msg",$msg);		
			return FALSE;
		}

		//verificando que exista el archivo .dbf
		$dbffile = substr($shapefile[0], 0, -3).'dbf';
		if (!(is_readable($dbffile) && is_file($dbffile))){
			$msg = getMsg("106");
			CurrentPage()->setFailureMessage($msg);
			setWSR("success","0");
			setWSR("msg",$msg);			
			return FALSE;
		}

		//leyendo el encabezado del archivo shapefile
		require_once("shpparser/shpparser.php");
		$shp = new shpParser();
		$shp->load($shapefile[0]);

		//verificando el sistema de proyeccion
		$bbox = $shp->headerInfo()["boundingBox"];
		$xc = $bbox["xmin"] + ($bbox["xmax"] - $bbox["xmin"]);
		$yc = $bbox["ymin"] + ($bbox["ymax"] - $bbox["ymin"]);
		$bboxProj = array();
		if($rsnew["srid"] == 4326){
			$bboxProj = array("xmin" => -180, "ymin" => -90, "xmax" => 180, "ymax" => 90);
		} else {
			$bboxProj = array("xmin" => 166021.4431, "ymin" => 1116915.0440, "xmax" => 833978.5569, "ymax" => 10000000.0000);
		}
		if($bboxProj["xmin"] > $xc || $xc > $bboxProj["xmax"] || $bboxProj["ymin"] > $yc || $yc > $bboxProj["ymax"]){
			$msg = getMsg("112"); //--> Las coordenadas no corresponden al sistema de proyección de referencia.
			CurrentPage()->setFailureMessage($msg);
			setWSR("success","0");
			setWSR("msg",$msg);			
			return FALSE;
		}
		$rsnew["tipogeom"] = $shp->headerInfo()["shapeType"]["id"];
		global $opciones;
		$opciones .= (!empty($opciones)?",":"").$rsnew["acciones"]; //agregando las opciones de subida a la variable global de opciones . 
	 	if( chkopt("allow_point") || chkopt("allow_line") || chkopt("allow_polygon")){ 

		//verificar que el tipo de geometria este permitido
			$allow_op = "";
			$msg = "";
			switch ($rsnew["tipogeom"]) {
			case 1:
				$allow_op = "allow_point";
				$msg = getMsg("107");
				break;
			case 3:
				$allow_op = "allow_line";
				$msg = getMsg("108");
				break;
			case 5:
				$allow_op = "allow_polygon";
				$msg = getMsg("109");
				break;
			}
			if(!chkopt($allow_op)){
				setWSR("success","0");
				setWSR("msg",$msg);	
				CurrentPage()->setFailureMessage($msg);
				return FALSE;
			}   
		}

		//creando el comando para generar el archivo SQL
		$command = "shp2pgsql -s ".$rsnew["srid"].":4326 -g the_geom -I -W \"UTF-8\" \"".$shapefile[0]."\" ".$_SESSION["uploads_schema"].".\"".$rsnew["token"]."\" > ".ew_IncludeTrailingDelimiter($folder, TRUE).$rsnew["token"].".sql";

		//Ejecutando el comando
		//Previamente se debe adicionar la ruta del archivo de comando shp2pgsql.exe a la variable "Path" del entorno del sistema.

		exec($command,$out,$error);

		//var_dump ($command,$out,$error);
		if($error){ //TODO: verificar que el problema sea la codificacion UTF8

			//echo "Primer Error utf-8!!";
	 		$command = "shp2pgsql -s ".$rsnew["srid"].":4326 -g the_geom -I -W \"LATIN1\" \"".$shapefile[0]."\" ".$_SESSION["uploads_schema"].".\"".$rsnew["token"]."\" > ".ew_IncludeTrailingDelimiter($folder, TRUE).$rsnew["token"].".sql";	
	  		$error = 0;	
				exec($command,$out,$error);

				//var_dump ($command,$out,$error);
				if($error){

					//echo "Segundo Error latin1!!";
					$msg = getMsg("110");
					setWSR("success","0");
					setWSR("msg",$msg);		
					CurrentPage()->setFailureMessage($msg);
					return FALSE;
				}
		}

		//Ejecutando en la Geodatabase el archivo SQL
		//Se debe tener activa la extension de PHP para conectarse a postgresql
		//$db = pg_connect("host=localhost port=5432 dbname=geodatabase user=postgres password=arma");  

		$infoDb = Db();
		$db = pg_connect("host=".$infoDb["host"]." port=".$infoDb["port"]." dbname=".$infoDb["db"]." user=".$infoDb["user"]." password=".$infoDb["pass"]);
		$filename = ew_IncludeTrailingDelimiter($folder, TRUE).$rsnew["token"].".sql";
		$handle = fopen($filename, "r");
		$query = fread($handle, filesize($filename)); 
		$result = pg_query($db,$query);
		if (!$result) {  
			$msg = getMsg("110");
			setWSR("success","0");
			setWSR("msg",$msg);		
			CurrentPage()->setFailureMessage($msg);
			return FALSE;
		}
		return TRUE;
	}

	// Row Inserted event
	function Row_Inserted($rsold, &$rsnew) {

		//echo "Row Inserted"
		$infoDb = Db();
		$db = pg_connect("host=".$infoDb["host"]." port=".$infoDb["port"]." dbname=".$infoDb["db"]." user=".$infoDb["user"]." password=".$infoDb["pass"]);
		$lyr_uploaded = $_SESSION["uploads_schema"].".".$rsnew["token"];
		setWSR("lyr_uploaded",$lyr_uploaded);

		//Si la geometria es de poligono, se realiza la correccion de interseccion con sigo mismo.	
		if( $rsnew["tipogeom"] == 5 &&  (  (strpos(strtoupper(@$rsnew["acciones"]),"FIX_SI") > -1) || chkopt("fix_si")) ){ // 'si' es abrev. de 'self intersection' => fix_si significa 'corregir interseccion con sigo mismo'				

			//var_dump($rsnew); exit;
			$result = pg_query($db, "SELECT * FROM sicob_fix_topo(json_build_object('lyr_in','".$lyr_uploaded."'))"); 
			if($result){
				$row = pg_fetch_row($result);
				$res = json_decode($row[0], true);

				//$layer = $row[0];
				if(@$res['lyr_fix'] != '' && $lyr_uploaded != @$res['lyr_fix']){
					$lyr_uploaded = @$res['lyr_fix'];
					setWSR("lyr_fix_si",$lyr_uploaded);
				}
			}
		}		

		//echo "Row Inserted"
		if(chkopt("webservice")){ //Si es webservice
			setWSR("success","1");
			setWSR("filesource",$rsnew["narchivoorigen"]);

			//obteniendo el listado de los campos del nuevo layer creado.
			$query = "SELECT * FROM replace(sicob_no_geo_column('".$lyr_uploaded."','{sicob_id,gid,sicob_sup}',''), '".$lyr_uploaded.".' ,'');";
			$result = pg_query($db,$query);
			while ($row = pg_fetch_row($result)) {
				setWSR("columns",$row[0]);
			}

			//$layer = $lyr_uploaded;
			//intersectando con predios titulados del inra

			if($rsnew["tipogeom"] == 5 && chkopt("intersect_tit")){
				$query = "select * FROM sicob_overlap('".$lyr_uploaded."','coberturas.predios_titulados','_tit','','',5)";
				$result = pg_exec($db,$query);
				if($result){
					$row = pg_fetch_row($result);
					setWSR("lyr_intersect_tit",$row[0]);			
				}
			}

			//global $opciones;
			//setWSR("opciones", @$_SESSION[CurrentPage()->PageObjName."_opciones"]);

	 			if(chkopt("geojson")){
				$query = "SELECT sicob_to_geojson('{\"lyr_in\":\"" . $lyr_uploaded . "\"}') AS geojson";
				$result = pg_exec($db,$query);
				if($result){
					$row = pg_fetch_row($result);
					setWSR($lyr_uploaded,$row[0]);			
				}
			} 
		} //Fin: Si es webservice
	}

	// Row Updating event
	function Row_Updating($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

	// Row Updated event
	function Row_Updated($rsold, &$rsnew) {

		//echo "Row Updated";
	}

	// Row Update Conflict event
	function Row_UpdateConflict($rsold, &$rsnew) {

		// Enter your code here
		// To ignore conflict, set return value to FALSE

		return TRUE;
	}

	// Grid Inserting event
	function Grid_Inserting() {

		// Enter your code here
		// To reject grid insert, set return value to FALSE

		return TRUE;
	}

	// Grid Inserted event
	function Grid_Inserted($rsnew) {

		//echo "Grid Inserted";
	}

	// Grid Updating event
	function Grid_Updating($rsold) {

		// Enter your code here
		// To reject grid update, set return value to FALSE

		return TRUE;
	}

	// Grid Updated event
	function Grid_Updated($rsold, $rsnew) {

		//echo "Grid Updated";
	}

	// Row Deleting event
	function Row_Deleting(&$rs) {

		// Enter your code here
		// To cancel, set return value to False

		return TRUE;
	}

	// Row Deleted event
	function Row_Deleted(&$rs) {

		//echo "Row Deleted";
	}

	// Email Sending event
	function Email_Sending(&$Email, &$Args) {

		//var_dump($Email); var_dump($Args); exit();
		return TRUE;
	}

	// Lookup Selecting event
	function Lookup_Selecting($fld, &$filter) {

		//var_dump($fld->FldName, $fld->LookupFilters, $filter); // Uncomment to view the filter
		// Enter your code here

	}

	// Row Rendering event
	function Row_Rendering() {

		// Enter your code here	
	}

	// Row Rendered event
	function Row_Rendered() {

		// To view properties of field class, use:
		//var_dump($this-><FieldName>);

		$this->narchivo->HrefValue = $this->folder->CurrentValue . $this->narchivo->CurrentValue;
	}

	// User ID Filtering event
	function UserID_Filtering(&$filter) {

		// Enter your code here
	}
}
?>
