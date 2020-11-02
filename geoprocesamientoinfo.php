<?php

// Global variable for table object
$geoprocesamiento = NULL;

//
// Table class for geoprocesamiento
//
class cgeoprocesamiento extends cTable {
	var $idgeoproceso;
	var $idusuario;
	var $proceso;
	var $inicio;
	var $fin;
	var $entradatxt;
	var $salidatxt;
	var $salidatrunc;
	var $opcionestxt;
	var $geojson;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'geoprocesamiento';
		$this->TableName = 'geoprocesamiento';
		$this->TableType = 'TABLE';

		// Update Table
		$this->UpdateTable = "\"registro_derecho\".\"geoprocesamiento\"";
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

		// idgeoproceso
		$this->idgeoproceso = new cField('geoprocesamiento', 'geoprocesamiento', 'x_idgeoproceso', 'idgeoproceso', '"idgeoproceso"', 'CAST("idgeoproceso" AS varchar(255))', 3, -1, FALSE, '"idgeoproceso"', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'NO');
		$this->idgeoproceso->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['idgeoproceso'] = &$this->idgeoproceso;

		// idusuario
		$this->idusuario = new cField('geoprocesamiento', 'geoprocesamiento', 'x_idusuario', 'idusuario', '"idusuario"', 'CAST("idusuario" AS varchar(255))', 3, -1, FALSE, '"idusuario"', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->idusuario->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['idusuario'] = &$this->idusuario;

		// proceso
		$this->proceso = new cField('geoprocesamiento', 'geoprocesamiento', 'x_proceso', 'proceso', '"proceso"', '"proceso"', 200, -1, FALSE, '"proceso"', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->fields['proceso'] = &$this->proceso;

		// inicio
		$this->inicio = new cField('geoprocesamiento', 'geoprocesamiento', 'x_inicio', 'inicio', '"inicio"', 'CAST("inicio" AS varchar(255))', 135, -1, FALSE, '"inicio"', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['inicio'] = &$this->inicio;

		// fin
		$this->fin = new cField('geoprocesamiento', 'geoprocesamiento', 'x_fin', 'fin', '"fin"', 'CAST("fin" AS varchar(255))', 135, -1, FALSE, '"fin"', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['fin'] = &$this->fin;

		// entradatxt
		$this->entradatxt = new cField('geoprocesamiento', 'geoprocesamiento', 'x_entradatxt', 'entradatxt', 'entrada', 'entrada', 201, -1, FALSE, 'entrada', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->entradatxt->FldIsCustom = TRUE; // Custom field
		$this->fields['entradatxt'] = &$this->entradatxt;

		// salidatxt
		$this->salidatxt = new cField('geoprocesamiento', 'geoprocesamiento', 'x_salidatxt', 'salidatxt', 'salida', 'salida', 201, -1, FALSE, 'salida', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->salidatxt->FldIsCustom = TRUE; // Custom field
		$this->fields['salidatxt'] = &$this->salidatxt;

		// salidatrunc
		$this->salidatrunc = new cField('geoprocesamiento', 'geoprocesamiento', 'x_salidatrunc', 'salidatrunc', 'LEFT(salida::text,1000) || CASE WHEN length(salida::text)>1000 THEN \'...\' ELSE \'\' END', 'LEFT(salida::text,1000) || CASE WHEN length(salida::text)>1000 THEN \'...\' ELSE \'\' END', 201, -1, FALSE, 'LEFT(salida::text,1000) || CASE WHEN length(salida::text)>1000 THEN \'...\' ELSE \'\' END', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->salidatrunc->FldIsCustom = TRUE; // Custom field
		$this->fields['salidatrunc'] = &$this->salidatrunc;

		// opcionestxt
		$this->opcionestxt = new cField('geoprocesamiento', 'geoprocesamiento', 'x_opcionestxt', 'opcionestxt', 'opciones', 'opciones', 201, -1, FALSE, 'opciones', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->opcionestxt->FldIsCustom = TRUE; // Custom field
		$this->fields['opcionestxt'] = &$this->opcionestxt;

		// geojson
		$this->geojson = new cField('geoprocesamiento', 'geoprocesamiento', 'x_geojson', 'geojson', '"geojson"', '"geojson"', 201, -1, FALSE, '"geojson"', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->fields['geojson'] = &$this->geojson;
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
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "\"registro_derecho\".\"geoprocesamiento\"";
	}

	function SqlFrom() { // For backward compatibility
    	return $this->getSqlFrom();
	}

	function setSqlFrom($v) {
    	$this->_SqlFrom = $v;
	}
	var $_SqlSelect = "";

	function getSqlSelect() { // Select
		return ($this->_SqlSelect <> "") ? $this->_SqlSelect : "SELECT *, entrada AS \"entradatxt\", salida AS \"salidatxt\", LEFT(salida::text,1000) || CASE WHEN length(salida::text)>1000 THEN '...' ELSE '' END AS \"salidatrunc\", opciones AS \"opcionestxt\" FROM " . $this->getSqlFrom();
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
		return ($this->_SqlOrderBy <> "") ? $this->_SqlOrderBy : "\"idgeoproceso\" DESC";
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
			if (array_key_exists('idgeoproceso', $rs))
				ew_AddFilter($where, ew_QuotedName('idgeoproceso', $this->DBID) . '=' . ew_QuotedValue($rs['idgeoproceso'], $this->idgeoproceso->FldDataType, $this->DBID));
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
		return "\"idgeoproceso\" = @idgeoproceso@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->idgeoproceso->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@idgeoproceso@", ew_AdjustSql($this->idgeoproceso->CurrentValue, $this->DBID), $sKeyFilter); // Replace key value
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
			return "geoprocesamientolist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "geoprocesamientolist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("geoprocesamientoview.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("geoprocesamientoview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			$url = "geoprocesamientoadd.php?" . $this->UrlParm($parm);
		else
			$url = "geoprocesamientoadd.php";
		return $this->AddMasterUrl($url);
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		$url = $this->KeyUrl("geoprocesamientoedit.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
		return $this->AddMasterUrl($url);
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		$url = $this->KeyUrl("geoprocesamientoadd.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
		return $this->AddMasterUrl($url);
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("geoprocesamientodelete.php", $this->UrlParm());
	}

	// Add master url
	function AddMasterUrl($url) {
		return $url;
	}

	function KeyToJson() {
		$json = "";
		$json .= "idgeoproceso:" . ew_VarToJson($this->idgeoproceso->CurrentValue, "number", "'");
		return "{" . $json . "}";
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->idgeoproceso->CurrentValue)) {
			$sUrl .= "idgeoproceso=" . urlencode($this->idgeoproceso->CurrentValue);
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
			if ($isPost && isset($_POST["idgeoproceso"]))
				$arKeys[] = ew_StripSlashes($_POST["idgeoproceso"]);
			elseif (isset($_GET["idgeoproceso"]))
				$arKeys[] = ew_StripSlashes($_GET["idgeoproceso"]);
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
			$this->idgeoproceso->CurrentValue = $key;
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

	// Render list row values
	function RenderListRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
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

		// geojson
		$this->geojson->ViewValue = $this->geojson->CurrentValue;
		$this->geojson->ViewCustomAttributes = "";

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

		// salidatrunc
		$this->salidatrunc->LinkCustomAttributes = "";
		$this->salidatrunc->HrefValue = "";
		$this->salidatrunc->TooltipValue = "";

		// opcionestxt
		$this->opcionestxt->LinkCustomAttributes = "";
		$this->opcionestxt->HrefValue = "";
		$this->opcionestxt->TooltipValue = "";

		// geojson
		$this->geojson->LinkCustomAttributes = "";
		$this->geojson->HrefValue = "";
		$this->geojson->TooltipValue = "";

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Render edit row values
	function RenderEditRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

		// idgeoproceso
		$this->idgeoproceso->EditAttrs["class"] = "form-control";
		$this->idgeoproceso->EditCustomAttributes = "";
		$this->idgeoproceso->EditValue = $this->idgeoproceso->CurrentValue;
		$this->idgeoproceso->ViewCustomAttributes = ["style" => "text-transform: none;"];

		// idusuario
		$this->idusuario->EditAttrs["class"] = "form-control";
		$this->idusuario->EditCustomAttributes = "";
		$this->idusuario->EditValue = $this->idusuario->CurrentValue;
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
				$this->idusuario->EditValue = $this->idusuario->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->idusuario->EditValue = $this->idusuario->CurrentValue;
			}
		} else {
			$this->idusuario->EditValue = NULL;
		}
		$this->idusuario->ViewCustomAttributes = ["style" => "text-transform: none;"];

		// proceso
		$this->proceso->EditAttrs["class"] = "form-control";
		$this->proceso->EditCustomAttributes = "";

		// inicio
		$this->inicio->EditAttrs["class"] = "form-control";
		$this->inicio->EditCustomAttributes = "";
		$this->inicio->EditValue = $this->inicio->CurrentValue;
		$this->inicio->ViewCustomAttributes = "";

		// fin
		$this->fin->EditAttrs["class"] = "form-control";
		$this->fin->EditCustomAttributes = "";
		$this->fin->EditValue = $this->fin->CurrentValue;
		$this->fin->ViewCustomAttributes = "";

		// entradatxt
		$this->entradatxt->EditAttrs["class"] = "form-control";
		$this->entradatxt->EditCustomAttributes = "";
		$this->entradatxt->EditValue = $this->entradatxt->CurrentValue;
		$this->entradatxt->PlaceHolder = ew_RemoveHtml($this->entradatxt->FldCaption());

		// salidatxt
		$this->salidatxt->EditAttrs["class"] = "form-control";
		$this->salidatxt->EditCustomAttributes = "";
		$this->salidatxt->EditValue = $this->salidatxt->CurrentValue;
		$this->salidatxt->ViewCustomAttributes = ["style" => "text-transform: none;"];

		// salidatrunc
		$this->salidatrunc->EditAttrs["class"] = "form-control";
		$this->salidatrunc->EditCustomAttributes = "";
		$this->salidatrunc->EditValue = $this->salidatrunc->CurrentValue;
		$this->salidatrunc->PlaceHolder = ew_RemoveHtml($this->salidatrunc->FldCaption());

		// opcionestxt
		$this->opcionestxt->EditAttrs["class"] = "form-control";
		$this->opcionestxt->EditCustomAttributes = "";
		$this->opcionestxt->EditValue = $this->opcionestxt->CurrentValue;
		$this->opcionestxt->PlaceHolder = ew_RemoveHtml($this->opcionestxt->FldCaption());

		// geojson
		$this->geojson->EditAttrs["class"] = "form-control";
		$this->geojson->EditCustomAttributes = "";
		$this->geojson->EditValue = $this->geojson->CurrentValue;
		$this->geojson->ViewCustomAttributes = "";

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
					if ($this->idgeoproceso->Exportable) $Doc->ExportCaption($this->idgeoproceso);
					if ($this->idusuario->Exportable) $Doc->ExportCaption($this->idusuario);
					if ($this->proceso->Exportable) $Doc->ExportCaption($this->proceso);
					if ($this->inicio->Exportable) $Doc->ExportCaption($this->inicio);
					if ($this->fin->Exportable) $Doc->ExportCaption($this->fin);
					if ($this->entradatxt->Exportable) $Doc->ExportCaption($this->entradatxt);
					if ($this->salidatxt->Exportable) $Doc->ExportCaption($this->salidatxt);
					if ($this->opcionestxt->Exportable) $Doc->ExportCaption($this->opcionestxt);
					if ($this->geojson->Exportable) $Doc->ExportCaption($this->geojson);
				} else {
					if ($this->idgeoproceso->Exportable) $Doc->ExportCaption($this->idgeoproceso);
					if ($this->idusuario->Exportable) $Doc->ExportCaption($this->idusuario);
					if ($this->proceso->Exportable) $Doc->ExportCaption($this->proceso);
					if ($this->inicio->Exportable) $Doc->ExportCaption($this->inicio);
					if ($this->fin->Exportable) $Doc->ExportCaption($this->fin);
					if ($this->entradatxt->Exportable) $Doc->ExportCaption($this->entradatxt);
					if ($this->salidatxt->Exportable) $Doc->ExportCaption($this->salidatxt);
					if ($this->opcionestxt->Exportable) $Doc->ExportCaption($this->opcionestxt);
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
						if ($this->idgeoproceso->Exportable) $Doc->ExportField($this->idgeoproceso);
						if ($this->idusuario->Exportable) $Doc->ExportField($this->idusuario);
						if ($this->proceso->Exportable) $Doc->ExportField($this->proceso);
						if ($this->inicio->Exportable) $Doc->ExportField($this->inicio);
						if ($this->fin->Exportable) $Doc->ExportField($this->fin);
						if ($this->entradatxt->Exportable) $Doc->ExportField($this->entradatxt);
						if ($this->salidatxt->Exportable) $Doc->ExportField($this->salidatxt);
						if ($this->opcionestxt->Exportable) $Doc->ExportField($this->opcionestxt);
						if ($this->geojson->Exportable) $Doc->ExportField($this->geojson);
					} else {
						if ($this->idgeoproceso->Exportable) $Doc->ExportField($this->idgeoproceso);
						if ($this->idusuario->Exportable) $Doc->ExportField($this->idusuario);
						if ($this->proceso->Exportable) $Doc->ExportField($this->proceso);
						if ($this->inicio->Exportable) $Doc->ExportField($this->inicio);
						if ($this->fin->Exportable) $Doc->ExportField($this->fin);
						if ($this->entradatxt->Exportable) $Doc->ExportField($this->entradatxt);
						if ($this->salidatxt->Exportable) $Doc->ExportField($this->salidatxt);
						if ($this->opcionestxt->Exportable) $Doc->ExportField($this->opcionestxt);
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

		$this->entradatxt->FldIsCustom = FALSE;
		$this->salidatxt->FldIsCustom = FALSE;
		$this->opcionestxt->FldIsCustom = FALSE;
		$this->inicio->FldDataType = EW_DATATYPE_OTHER;
		$rsnew["inicio"] = "clock_timestamp()";
		$rsnew["idusuario"] =  CurrentUserInfo('idusuario');
		return TRUE;
	}

	// Row Inserted event
	function Row_Inserted($rsold, &$rsnew) {

		//echo "Row Inserted"
		$res = rungeoprocess($this->idgeoproceso->CurrentValue);
		if(chkopt("webservice")){ //Si se ha llamado como servicio.
			setWSR($res ? $res : ('{"success":"0","msg":"'.getMsg(113).'"}'));
		}
	}

	// Row Updating event
	function Row_Updating($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		$this->entradatxt->FldIsCustom = FALSE;
		$this->salidatxt->FldIsCustom = FALSE;
		$this->opcionestxt->FldIsCustom = FALSE;
		if($rsold["entradatxt"] != $rsnew["entradatxt"]){
			$rsnew["salidatxt"] = NULL;
			$rsnew["fin"] = NULL;
			$this->inicio->FldDataType = EW_DATATYPE_OTHER;
			$rsnew["inicio"] = "clock_timestamp()";
		}
		return TRUE;
	}

	// Row Updated event
	function Row_Updated($rsold, &$rsnew) {

		//echo "Row Updated";
		if($rsold["entradatxt"] != $rsnew["entradatxt"]){	//si cambio la entrada -> ejecutar geoproceso
			$res = rungeoprocess($this->idgeoproceso->CurrentValue);
			if(chkopt("webservice")){ //Si se ha llamado como servicio.
				setWSR($res ? $res : ('{"success":"0","msg":"'.getMsg(113).'"}'));
			}
		}
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

	}

	// User ID Filtering event
	function UserID_Filtering(&$filter) {

		// Enter your code here
	}
}
?>
