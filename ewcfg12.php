<?php

/**
 * PHPMaker 12 configuration file
 */

// Relative path
if (!isset($EW_RELATIVE_PATH)) {
    $EW_RELATIVE_PATH = "";
}

// Show SQL for debug
define("EW_DEBUG_ENABLED", false); // TRUE to debug
if (EW_DEBUG_ENABLED) {
    @ini_set("display_errors", "1"); // Display errors
    error_reporting(E_ALL ^ E_NOTICE); // Report all errors except E_NOTICE
}

// General
define("EW_IS_WINDOWS", (strtolower(substr(PHP_OS, 0, 3)) === 'win')); // Is Windows OS
define("EW_IS_PHP5", (phpversion() >= "5.3.0")); // Is PHP 5.3 or later
if (!EW_IS_PHP5) {
    die("This script requires PHP 5.3 or later. You are running " . phpversion() . ".");
}

define("EW_PATH_DELIMITER", ((EW_IS_WINDOWS) ? "\\" : "/")); // Physical path delimiter
$EW_ROOT_RELATIVE_PATH = "."; // Relative path of app root
define("EW_DEFAULT_DATE_FORMAT", "dd/mm/yyyy"); // Default date format
define("EW_DEFAULT_DATE_FORMAT_ID", "11"); // Default date format
define("EW_DATE_SEPARATOR", "/"); // Date separator
define("EW_UNFORMAT_YEAR", 50); // Unformat year
define("EW_PROJECT_NAME", "abt"); // Project name
define("EW_CONFIG_FILE_FOLDER", EW_PROJECT_NAME . ""); // Config file name
define("EW_PROJECT_ID", "{00441056-EF9D-4233-BDD9-EE81681FA399}"); // Project ID (GUID)
$EW_RELATED_PROJECT_ID = "";
$EW_RELATED_LANGUAGE_FOLDER = "";
define("EW_RANDOM_KEY", 'p0tV598uz53QeXHg'); // Random key for encryption
define("EW_PROJECT_STYLESHEET_FILENAME", "phpcss/abt.css"); // Project stylesheet file name
define("EW_CHARSET", "utf-8"); // Project charset
define("EW_EMAIL_CHARSET", EW_CHARSET); // Email charset
define("EW_EMAIL_KEYWORD_SEPARATOR", ""); // Email keyword separator
$EW_COMPOSITE_KEY_SEPARATOR = ","; // Composite key separator
define("EW_HIGHLIGHT_COMPARE", true); // Highlight compare mode, TRUE(case-insensitive)|FALSE(case-sensitive)
if (!function_exists('xml_parser_create') && !class_exists("DOMDocument")) {
    die("This script requires PHP XML Parser or DOM.");
}

define('EW_USE_DOM_XML', ((!function_exists('xml_parser_create') && class_exists("DOMDocument")) || false));
if (!isset($ADODB_OUTP)) {
    $ADODB_OUTP = 'ew_SetDebugMsg';
}

define("EW_FONT_SIZE", 12);
define("EW_TMP_IMAGE_FONT", "DejaVuSans"); // Font for temp files

// Set up font path
$EW_FONT_PATH = realpath('./phpfont');

// Database connection info
if (!defined("EW_USE_ADODB")) {
    define("EW_USE_ADODB", true);
}
// Use ADOdb
if (!defined("EW_USE_MYSQLI")) {
    define('EW_USE_MYSQLI', extension_loaded("mysqli"));
}
// Use MySQLi
$EW_CONN["DB"] = array("conn" => null, "id" => "DB", "type" => "POSTGRESQL", "host" => "localhost", "port" => 5432, "user" => "admderechos", "pass" => "Geo2020*", "db" => "geosicob", "schema" => "registro_derecho", "qs" => "\"", "qe" => "\"");
$EW_CONN[0] = &$EW_CONN["DB"];
$EW_CONN["lime_hc"] = array("conn" => null, "id" => "lime_hc", "type" => "MYSQL", "host" => "localhost", "port" => 3306, "user" => "root", "pass" => "arma", "db" => "lime_hc", "qs" => "", "qe" => "", "new" => true);
$EW_CONN[1] = &$EW_CONN["lime_hc"];

// Set up database error function
$EW_ERROR_FN = 'ew_ErrorFn';

// ADODB (Access/SQL Server)
define("EW_CODEPAGE", 65001); // Code page

/**
 * Character encoding
 * Note: If you use non English languages, you need to set character encoding
 * for some features. Make sure either iconv functions or multibyte string
 * functions are enabled and your encoding is supported. See PHP manual for
 * details.
 */
define("EW_ENCODING", "UTF-8"); // Character encoding
define("EW_IS_DOUBLE_BYTE", in_array(EW_ENCODING, array("GBK", "BIG5", "SHIFT_JIS"))); // Double-byte character encoding
define("EW_FILE_SYSTEM_ENCODING", ""); // File system encoding

// Database
define("EW_IS_MSACCESS", false); // Access
define("EW_IS_MSSQL", false); // SQL Server
define("EW_IS_MYSQL", false); // MySQL
define("EW_IS_POSTGRESQL", true); // PostgreSQL
define("EW_IS_ORACLE", false); // Oracle
if (!EW_IS_WINDOWS && (EW_IS_MSACCESS || EW_IS_MSSQL)) {
    die("Microsoft Access or SQL Server is supported on Windows server only.");
}

define("EW_DB_QUOTE_START", "\"");
define("EW_DB_QUOTE_END", "\"");

/**
 * MySQL charset (for SET NAMES statement, not used by default)
 * Note: Read http://dev.mysql.com/doc/refman/5.0/en/charset-connection.html
 * before using this setting.
 */
define("EW_MYSQL_CHARSET", "utf8");

/**
 * Password (MD5 and case-sensitivity)
 * Note: If you enable MD5 password, make sure that the passwords in your
 * user table are stored as MD5 hash (32-character hexadecimal number) of the
 * clear text password. If you also use case-insensitive password, convert the
 * clear text passwords to lower case first before calculating MD5 hash.
 * Otherwise, existing users will not be able to login. MD5 hash is
 * irreversible, password will be reset during password recovery.
 */
define("EW_ENCRYPTED_PASSWORD", true); // Use encrypted password
define("EW_CASE_SENSITIVE_PASSWORD", true); // Case-sensitive password

/**
 * Remove XSS
 * Note: If you want to allow these keywords, remove them from the following EW_XSS_ARRAY at your own risks.
 */
define("EW_REMOVE_XSS", true);
$EW_XSS_ARRAY = array('javascript', 'vbscript', 'expression', '<applet', '<meta', '<xml', '<blink', '<link', '<style', '<script', '<embed', '<object', '<iframe', '<frame', '<frameset', '<ilayer', '<layer', '<bgsound', '<title', '<base',
    'onabort', 'onactivate', 'onafterprint', 'onafterupdate', 'onbeforeactivate', 'onbeforecopy', 'onbeforecut', 'onbeforedeactivate', 'onbeforeeditfocus', 'onbeforepaste', 'onbeforeprint', 'onbeforeunload', 'onbeforeupdate', 'onblur', 'onbounce', 'oncellchange', 'onchange', 'onclick', 'oncontextmenu', 'oncontrolselect', 'oncopy', 'oncut', 'ondataavailable', 'ondatasetchanged', 'ondatasetcomplete', 'ondblclick', 'ondeactivate', 'ondrag', 'ondragend', 'ondragenter', 'ondragleave', 'ondragover', 'ondragstart', 'ondrop', 'onerror', 'onerrorupdate', 'onfilterchange', 'onfinish', 'onfocus', 'onfocusin', 'onfocusout', 'onhelp', 'onkeydown', 'onkeypress', 'onkeyup', 'onlayoutcomplete', 'onload', 'onlosecapture', 'onmousedown', 'onmouseenter', 'onmouseleave', 'onmousemove', 'onmouseout', 'onmouseover', 'onmouseup', 'onmousewheel', 'onmove', 'onmoveend', 'onmovestart', 'onpaste', 'onpropertychange', 'onreadystatechange', 'onreset', 'onresize', 'onresizeend', 'onresizestart', 'onrowenter', 'onrowexit', 'onrowsdelete', 'onrowsinserted', 'onscroll', 'onselect', 'onselectionchange', 'onselectstart', 'onstart', 'onstop', 'onsubmit', 'onunload');

// Check Token
define("EW_CHECK_TOKEN", false); // Check post token

// Session timeout time
define("EW_SESSION_TIMEOUT", 30); // Session timeout time (minutes)

// Session keep alive interval
define("EW_SESSION_KEEP_ALIVE_INTERVAL", 0); // Session keep alive interval (seconds)
define("EW_SESSION_TIMEOUT_COUNTDOWN", 60); // Session timeout count down interval (seconds)

// Session names
define("EW_SESSION_STATUS", EW_PROJECT_NAME . "_status"); // Login status
define("EW_SESSION_USER_NAME", EW_SESSION_STATUS . "_UserName"); // User name
define("EW_SESSION_USER_LOGIN_TYPE", EW_SESSION_STATUS . "_UserLoginType"); // User login type
define("EW_SESSION_USER_ID", EW_SESSION_STATUS . "_UserID"); // User ID
define("EW_SESSION_USER_PROFILE", EW_SESSION_STATUS . "_UserProfile"); // User profile
define("EW_SESSION_USER_PROFILE_USER_NAME", EW_SESSION_USER_PROFILE . "_UserName");
define("EW_SESSION_USER_PROFILE_PASSWORD", EW_SESSION_USER_PROFILE . "_Password");
define("EW_SESSION_USER_PROFILE_LOGIN_TYPE", EW_SESSION_USER_PROFILE . "_LoginType");
define("EW_SESSION_USER_LEVEL_ID", EW_SESSION_STATUS . "_UserLevel"); // User Level ID
define("EW_SESSION_USER_LEVEL_LIST", EW_SESSION_STATUS . "_UserLevelList"); // User Level List
define("EW_SESSION_USER_LEVEL_LIST_LOADED", EW_SESSION_STATUS . "_UserLevelListLoaded"); // User Level List Loaded
@define("EW_SESSION_USER_LEVEL", EW_SESSION_STATUS . "_UserLevelValue"); // User Level
define("EW_SESSION_PARENT_USER_ID", EW_SESSION_STATUS . "_ParentUserID"); // Parent User ID
define("EW_SESSION_SYS_ADMIN", EW_PROJECT_NAME . "_SysAdmin"); // System admin
define("EW_SESSION_PROJECT_ID", EW_PROJECT_NAME . "_ProjectID"); // User Level project ID
define("EW_SESSION_AR_USER_LEVEL", EW_PROJECT_NAME . "_arUserLevel"); // User Level array
define("EW_SESSION_AR_USER_LEVEL_PRIV", EW_PROJECT_NAME . "_arUserLevelPriv"); // User Level privilege array
define("EW_SESSION_USER_LEVEL_MSG", EW_PROJECT_NAME . "_UserLevelMessage"); // User Level Message
define("EW_SESSION_MESSAGE", EW_PROJECT_NAME . "_Message"); // System message
define("EW_SESSION_FAILURE_MESSAGE", EW_PROJECT_NAME . "_Failure_Message"); // System error message
define("EW_SESSION_SUCCESS_MESSAGE", EW_PROJECT_NAME . "_Success_Message"); // System message
define("EW_SESSION_WARNING_MESSAGE", EW_PROJECT_NAME . "_Warning_Message"); // Warning message
define("EW_SESSION_INLINE_MODE", EW_PROJECT_NAME . "_InlineMode"); // Inline mode
define("EW_SESSION_BREADCRUMB", EW_PROJECT_NAME . "_Breadcrumb"); // Breadcrumb
define("EW_SESSION_TEMP_IMAGES", EW_PROJECT_NAME . "_TempImages"); // Temp images

// Language settings
define("EW_LANGUAGE_FOLDER", $EW_RELATIVE_PATH . "phplang/");
$EW_LANGUAGE_FILE = array();
$EW_LANGUAGE_FILE[] = array("es", "", "spanish.xml");
define("EW_LANGUAGE_DEFAULT_ID", "es");
define("EW_SESSION_LANGUAGE_ID", EW_PROJECT_NAME . "_LanguageId"); // Language ID

// Page Token
define("EW_TOKEN_NAME", "token"); // DO NOT CHANGE!
define("EW_SESSION_TOKEN", EW_PROJECT_NAME . "_Token");

// Data types
define("EW_DATATYPE_NUMBER", 1);
define("EW_DATATYPE_DATE", 2);
define("EW_DATATYPE_STRING", 3);
define("EW_DATATYPE_BOOLEAN", 4);
define("EW_DATATYPE_MEMO", 5);
define("EW_DATATYPE_BLOB", 6);
define("EW_DATATYPE_TIME", 7);
define("EW_DATATYPE_GUID", 8);
define("EW_DATATYPE_XML", 9);
define("EW_DATATYPE_OTHER", 10);

// Row types
define("EW_ROWTYPE_HEADER", 0); // Row type header
define("EW_ROWTYPE_VIEW", 1); // Row type view
define("EW_ROWTYPE_ADD", 2); // Row type add
define("EW_ROWTYPE_EDIT", 3); // Row type edit
define("EW_ROWTYPE_SEARCH", 4); // Row type search
define("EW_ROWTYPE_MASTER", 5); // Row type master record
define("EW_ROWTYPE_AGGREGATEINIT", 6); // Row type aggregate init
define("EW_ROWTYPE_AGGREGATE", 7); // Row type aggregate

// List actions
define("EW_ACTION_POSTBACK", "P"); // Post back
define("EW_ACTION_AJAX", "A"); // Ajax
define("EW_ACTION_MULTIPLE", "M"); // Multiple records
define("EW_ACTION_SINGLE", "S"); // Single record

// Table parameters
define("EW_TABLE_PREFIX", "||PHPReportMaker||");
define("EW_TABLE_REC_PER_PAGE", "recperpage"); // Records per page
define("EW_TABLE_START_REC", "start"); // Start record
define("EW_TABLE_PAGE_NO", "pageno"); // Page number
define("EW_TABLE_BASIC_SEARCH", "psearch"); // Basic search keyword
define("EW_TABLE_BASIC_SEARCH_TYPE", "psearchtype"); // Basic search type
define("EW_TABLE_ADVANCED_SEARCH", "advsrch"); // Advanced search
define("EW_TABLE_SEARCH_WHERE", "searchwhere"); // Search where clause
define("EW_TABLE_WHERE", "where"); // Table where
define("EW_TABLE_WHERE_LIST", "where_list"); // Table where (list page)
define("EW_TABLE_ORDER_BY", "orderby"); // Table order by
define("EW_TABLE_ORDER_BY_LIST", "orderby_list"); // Table order by (list page)
define("EW_TABLE_SORT", "sort"); // Table sort
define("EW_TABLE_KEY", "key"); // Table key
define("EW_TABLE_SHOW_MASTER", "showmaster"); // Table show master
define("EW_TABLE_SHOW_DETAIL", "showdetail"); // Table show detail
define("EW_TABLE_MASTER_TABLE", "mastertable"); // Master table
define("EW_TABLE_DETAIL_TABLE", "detailtable"); // Detail table
define("EW_TABLE_RETURN_URL", "return"); // Return URL
define("EW_TABLE_EXPORT_RETURN_URL", "exportreturn"); // Export return URL
define("EW_TABLE_GRID_ADD_ROW_COUNT", "gridaddcnt"); // Grid add row count

// Audit Trail
define("EW_AUDIT_TRAIL_TO_DATABASE", false); // Write audit trail to DB
define("EW_AUDIT_TRAIL_DBID", "DB"); // Audit trail DBID
define("EW_AUDIT_TRAIL_TABLE_NAME", ""); // Audit trail table name
define("EW_AUDIT_TRAIL_TABLE_VAR", ""); // Audit trail table var
define("EW_AUDIT_TRAIL_FIELD_NAME_DATETIME", ""); // Audit trail DateTime field name
define("EW_AUDIT_TRAIL_FIELD_NAME_SCRIPT", ""); // Audit trail Script field name
define("EW_AUDIT_TRAIL_FIELD_NAME_USER", ""); // Audit trail User field name
define("EW_AUDIT_TRAIL_FIELD_NAME_ACTION", ""); // Audit trail Action field name
define("EW_AUDIT_TRAIL_FIELD_NAME_TABLE", ""); // Audit trail Table field name
define("EW_AUDIT_TRAIL_FIELD_NAME_FIELD", ""); // Audit trail Field field name
define("EW_AUDIT_TRAIL_FIELD_NAME_KEYVALUE", ""); // Audit trail Key Value field name
define("EW_AUDIT_TRAIL_FIELD_NAME_OLDVALUE", ""); // Audit trail Old Value field name
define("EW_AUDIT_TRAIL_FIELD_NAME_NEWVALUE", ""); // Audit trail New Value field name

// Security
define("EW_ADMIN_USER_NAME", "erick"); // Administrator user name
define("EW_ADMIN_PASSWORD", "arma"); // Administrator password
define("EW_USE_CUSTOM_LOGIN", true); // Use custom login
define("EW_ALLOW_LOGIN_BY_URL", true); // Allow login by URL
define("EW_ALLOW_LOGIN_BY_SESSION", true); // Allow login by session variables
define("EW_PHPASS_ITERATION_COUNT_LOG2", "[10,8]"); // Note: Use JSON array syntax

// Dynamic User Level settings
// User level definition table/field names

@define("EW_USER_LEVEL_DBID", "DB");
@define("EW_USER_LEVEL_TABLE", "\"registro_derecho\".\"userlevels\"");
@define("EW_USER_LEVEL_ID_FIELD", "\"userlevelid\"");
@define("EW_USER_LEVEL_NAME_FIELD", "\"userlevelname\"");

// User Level privileges table/field names
@define("EW_USER_LEVEL_PRIV_DBID", "DB");
@define("EW_USER_LEVEL_PRIV_TABLE", "\"registro_derecho\".\"userlevelpermissions\"");
@define("EW_USER_LEVEL_PRIV_TABLE_NAME_FIELD", "\"tablename\"");
@define("EW_USER_LEVEL_PRIV_TABLE_NAME_FIELD_2", "tablename");
@define("EW_USER_LEVEL_PRIV_TABLE_NAME_FIELD_SIZE", 255);
@define("EW_USER_LEVEL_PRIV_USER_LEVEL_ID_FIELD", "\"userlevelid\"");
@define("EW_USER_LEVEL_PRIV_PRIV_FIELD", "\"permission\"");

// User level constants
define("EW_ALLOW_ADD", 1); // Add
define("EW_ALLOW_DELETE", 2); // Delete
define("EW_ALLOW_EDIT", 4); // Edit
@define("EW_ALLOW_LIST", 8); // List
if (defined("EW_USER_LEVEL_COMPAT")) {
    define("EW_ALLOW_VIEW", 8); // View
    define("EW_ALLOW_SEARCH", 8); // Search
} else {
    define("EW_ALLOW_VIEW", 32); // View
    define("EW_ALLOW_SEARCH", 64); // Search
}
@define("EW_ALLOW_REPORT", 8); // Report
@define("EW_ALLOW_ADMIN", 16); // Admin

// Hierarchical User ID
@define("EW_USER_ID_IS_HIERARCHICAL"); // Change to FALSE to show one level only

// Use subquery for master/detail
define("EW_USE_SUBQUERY_FOR_MASTER_USER_ID", false);
define("EW_USER_ID_ALLOW", 104);

// User table filters
define("EW_USER_TABLE_DBID", "DB");
define("EW_USER_TABLE", "\"registro_derecho\".\"usuario\"");
define("EW_USER_NAME_FILTER", "(\"user\" = '%u')");
define("EW_USER_ID_FILTER", "");
define("EW_USER_EMAIL_FILTER", "(\"email\" = '%e')");
define("EW_USER_ACTIVATE_FILTER", "");

// User Profile Constants
define("EW_USER_PROFILE_KEY_SEPARATOR", "");
define("EW_USER_PROFILE_FIELD_SEPARATOR", "");
define("EW_USER_PROFILE_SESSION_ID", "SessionID");
define("EW_USER_PROFILE_LAST_ACCESSED_DATE_TIME", "LastAccessedDateTime");
define("EW_USER_PROFILE_CONCURRENT_SESSION_COUNT", 1); // Maximum sessions allowed
define("EW_USER_PROFILE_SESSION_TIMEOUT", 20);
define("EW_USER_PROFILE_LOGIN_RETRY_COUNT", "LoginRetryCount");
define("EW_USER_PROFILE_LAST_BAD_LOGIN_DATE_TIME", "LastBadLoginDateTime");
define("EW_USER_PROFILE_MAX_RETRY", 3);
define("EW_USER_PROFILE_RETRY_LOCKOUT", 20);
define("EW_USER_PROFILE_LAST_PASSWORD_CHANGED_DATE", "LastPasswordChangedDate");
define("EW_USER_PROFILE_PASSWORD_EXPIRE", 90);
define("EW_USER_PROFILE_LANGUAGE_ID", "LanguageId");

// Email
define("EW_SMTP_SERVER", "localhost"); // SMTP server
define("EW_SMTP_SERVER_PORT", 25); // SMTP server port
define("EW_SMTP_SECURE_OPTION", "");
define("EW_SMTP_SERVER_USERNAME", ""); // SMTP server user name
define("EW_SMTP_SERVER_PASSWORD", ""); // SMTP server password
define("EW_SENDER_EMAIL", ""); // Sender email address
define("EW_RECIPIENT_EMAIL", ""); // Recipient email address
define("EW_MAX_EMAIL_RECIPIENT", 3);
define("EW_MAX_EMAIL_SENT_COUNT", 3);
define("EW_EXPORT_EMAIL_COUNTER", EW_SESSION_STATUS . "_EmailCounter");
define("EW_EMAIL_CHANGEPWD_TEMPLATE", "changepwd.html");
define("EW_EMAIL_FORGOTPWD_TEMPLATE", "forgotpwd.html");
define("EW_EMAIL_NOTIFY_TEMPLATE", "notify.html");
define("EW_EMAIL_REGISTER_TEMPLATE", "register.html");
define("EW_EMAIL_RESETPWD_TEMPLATE", "resetpwd.html");
define("EW_EMAIL_TEMPLATE_PATH", "phphtml"); // Template path

// File upload
define("EW_UPLOAD_TEMP_PATH", ""); // Upload temp path (absolute)
define("EW_UPLOAD_DEST_PATH", "uploads/"); // Upload destination path (relative to app root)
define("EW_UPLOAD_URL", "ewupload12.php"); // Upload URL
define("EW_UPLOAD_TEMP_FOLDER_PREFIX", "temp__"); // Upload temp folders prefix
define("EW_UPLOAD_TEMP_FOLDER_TIME_LIMIT", 1440); // Upload temp folder time limit (minutes)
define("EW_UPLOAD_THUMBNAIL_FOLDER", "thumbnail"); // Temporary thumbnail folder
define("EW_UPLOAD_THUMBNAIL_WIDTH", 200); // Temporary thumbnail max width
define("EW_UPLOAD_THUMBNAIL_HEIGHT", 0); // Temporary thumbnail max height
define("EW_UPLOAD_ALLOWED_FILE_EXT", "gif,jpg,jpeg,bmp,png,doc,xls,pdf,zip"); // Allowed file extensions
define("EW_IMAGE_ALLOWED_FILE_EXT", "gif,jpg,png,bmp"); // Allowed file extensions for images
define("EW_DOWNLOAD_ALLOWED_FILE_EXT", "pdf,xls,doc,xlsx,docx"); // Allowed file extensions for download (non-image)
define("EW_ENCRYPT_FILE_PATH", true); // Encrypt file path
define("EW_MAX_FILE_SIZE", 10000000); // Max file size
define("EW_MAX_FILE_COUNT", 0); // Max file count
define("EW_THUMBNAIL_DEFAULT_WIDTH", 0); // Thumbnail default width
define("EW_THUMBNAIL_DEFAULT_HEIGHT", 0); // Thumbnail default height
define("EW_THUMBNAIL_DEFAULT_QUALITY", 100); // Thumbnail default qualtity (JPEG)
define("EW_UPLOADED_FILE_MODE", 0666); // Uploaded file mode
define("EW_UPLOAD_TMP_PATH", ""); // User upload temp path (relative to app root) e.g. "tmp/"
define("EW_UPLOAD_CONVERT_ACCENTED_CHARS", false); // Convert accented chars in upload file name
define("EW_USE_COLORBOX", true); // Use Colorbox
define("EW_MULTIPLE_UPLOAD_SEPARATOR", ","); // Multiple upload separator

// Image resize
$EW_THUMBNAIL_CLASS = "cThumbnail";
define("EW_REDUCE_IMAGE_ONLY", true);
define("EW_KEEP_ASPECT_RATIO", true);
$EW_RESIZE_OPTIONS = array("keepAspectRatio" => EW_KEEP_ASPECT_RATIO, "resizeUp" => !EW_REDUCE_IMAGE_ONLY, "jpegQuality" => EW_THUMBNAIL_DEFAULT_QUALITY);

// Audit trail
define("EW_AUDIT_TRAIL_PATH", ""); // Audit trail path (relative to app root)

// Export records
define("EW_EXPORT_ALL", true); // Export all records
define("EW_EXPORT_ALL_TIME_LIMIT", 120); // Export all records time limit
define("EW_XML_ENCODING", "utf-8"); // Encoding for Export to XML
define("EW_EXPORT_ORIGINAL_VALUE", false);
define("EW_EXPORT_FIELD_CAPTION", true); // TRUE to export field caption
define("EW_EXPORT_CSS_STYLES", true); // TRUE to export CSS styles
define("EW_EXPORT_MASTER_RECORD", true); // TRUE to export master record
define("EW_EXPORT_MASTER_RECORD_FOR_CSV", true); // TRUE to export master record for CSV
define("EW_EXPORT_DETAIL_RECORDS", true); // TRUE to export detail records
define("EW_EXPORT_DETAIL_RECORDS_FOR_CSV", false); // TRUE to export detail records for CSV
$EW_EXPORT = array(
    "email" => "cExportEmail",
    "html" => "cExportHtml",
    "word" => "cExportWord",
    "excel" => "cExportExcel",
    "pdf" => "cExportPdf",
    "csv" => "cExportCsv",
    "xml" => "cExportXml",
);

// Export records for reports
$EW_EXPORT_REPORT = array(
    "print" => "ExportReportHtml",
    "html" => "ExportReportHtml",
    "word" => "ExportReportWord",
    "excel" => "ExportReportExcel",
);

// MIME types
$EW_MIME_TYPES = array(
    "pdf" => "application/pdf",
    "exe" => "application/octet-stream",
    "zip" => "application/zip",
    "doc" => "application/msword",
    "docx" => "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
    "xls" => "application/vnd.ms-excel",
    "xlsx" => "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
    "ppt" => "application/vnd.ms-powerpoint",
    "pptx" => "application/vnd.openxmlformats-officedocument.presentationml.presentation",
    "gif" => "image/gif",
    "png" => "image/png",
    "jpeg" => "image/jpeg",
    "jpg" => "image/jpeg",
    "mp3" => "audio/mpeg",
    "wav" => "audio/x-wav",
    "mpeg" => "video/mpeg",
    "mpg" => "video/mpeg",
    "mpe" => "video/mpeg",
    "mov" => "video/quicktime",
    "avi" => "video/x-msvideo",
    "3gp" => "video/3gpp",
    "css" => "text/css",
    "js" => "application/javascript",
    "htm" => "text/html",
    "html" => "text/html",
);

// Boolean html attributes
$EW_BOOLEAN_HTML_ATTRIBUTES = array("checked", "compact", "declare", "defer", "disabled", "ismap", "multiple", "nohref", "noresize", "noshade", "nowrap", "readonly", "selected");

// Use token in URL (reserved, not used, do NOT change!)
define("EW_USE_TOKEN_IN_URL", false);

// Use ILIKE for PostgreSql
define("EW_USE_ILIKE_FOR_POSTGRESQL", true);

// Use collation for MySQL
define("EW_LIKE_COLLATION_FOR_MYSQL", "");

// Use collation for MsSQL
define("EW_LIKE_COLLATION_FOR_MSSQL", "");

// Null / Not Null values
define("EW_NULL_VALUE", "##null##");
define("EW_NOT_NULL_VALUE", "##notnull##");

/**
 * Search multi value option
 * 1 - no multi value
 * 2 - AND all multi values
 * 3 - OR all multi values
 */
define("EW_SEARCH_MULTI_VALUE_OPTION", 3);

// Quick search
define("EW_BASIC_SEARCH_IGNORE_PATTERN", "/[\?,\.\^\*\(\)\[\]\\\"]/"); // Ignore special characters
define("EW_BASIC_SEARCH_ANY_FIELDS", true); // Search "All keywords" in any selected fields

// Validate option
define("EW_CLIENT_VALIDATE", true);
define("EW_SERVER_VALIDATE", false);

// Blob field byte count for hash value calculation
define("EW_BLOB_FIELD_BYTE_COUNT", 200);

// Auto suggest max entries
define("EW_AUTO_SUGGEST_MAX_ENTRIES", 10);

// Auto fill original value
define("EW_AUTO_FILL_ORIGINAL_VALUE", false);

// Checkbox and radio button groups
define("EW_ITEM_TEMPLATE_CLASSNAME", "ewTemplate");
define("EW_ITEM_TABLE_CLASSNAME", "ewItemTable");

// Use responsive layout
$EW_USE_RESPONSIVE_LAYOUT = false;

// Use css flip
define("EW_CSS_FLIP", false);

// Time zone
$DEFAULT_TIME_ZONE = "GMT";

/**
 * Numeric and monetary formatting options
 * Note: DO NOT CHANGE THE FOLLOWING $DEFAULT_* VARIABLES!
 * If you want to use custom settings, customize the language file,
 * set "use_system_locale" to "0" to override localeconv and customize the
 * phrases under the <locale> node for ew_FormatCurrency/Number/Percent functions
 * Also read http://www.php.net/localeconv for description of the constants
 */
$DEFAULT_LOCALE = json_decode('{"decimal_point":".","thousands_sep":"","int_curr_symbol":"$","currency_symbol":"$","mon_decimal_point":".","mon_thousands_sep":"","positive_sign":"","negative_sign":"-","int_frac_digits":2,"frac_digits":2,"p_cs_precedes":1,"p_sep_by_space":0,"n_cs_precedes":1,"n_sep_by_space":0,"p_sign_posn":1,"n_sign_posn":1}', true);
$DEFAULT_DECIMAL_POINT = &$DEFAULT_LOCALE["decimal_point"];
$DEFAULT_THOUSANDS_SEP = &$DEFAULT_LOCALE["thousands_sep"];
$DEFAULT_CURRENCY_SYMBOL = &$DEFAULT_LOCALE["currency_symbol"];
$DEFAULT_MON_DECIMAL_POINT = &$DEFAULT_LOCALE["mon_decimal_point"];
$DEFAULT_MON_THOUSANDS_SEP = &$DEFAULT_LOCALE["mon_thousands_sep"];
$DEFAULT_POSITIVE_SIGN = &$DEFAULT_LOCALE["positive_sign"];
$DEFAULT_NEGATIVE_SIGN = &$DEFAULT_LOCALE["negative_sign"];
$DEFAULT_FRAC_DIGITS = &$DEFAULT_LOCALE["frac_digits"];
$DEFAULT_P_CS_PRECEDES = &$DEFAULT_LOCALE["p_cs_precedes"];
$DEFAULT_P_SEP_BY_SPACE = &$DEFAULT_LOCALE["p_sep_by_space"];
$DEFAULT_N_CS_PRECEDES = &$DEFAULT_LOCALE["n_cs_precedes"];
$DEFAULT_N_SEP_BY_SPACE = &$DEFAULT_LOCALE["n_sep_by_space"];
$DEFAULT_P_SIGN_POSN = &$DEFAULT_LOCALE["p_sign_posn"];
$DEFAULT_N_SIGN_POSN = &$DEFAULT_LOCALE["n_sign_posn"];

// Cookies
define("EW_COOKIE_EXPIRY_TIME", time() + 365 * 24 * 60 * 60); // Change cookie expiry time here

// Client variables
$EW_CLIENT_VAR = array();

//
// Global variables
//

if (!isset($conn)) {

    // Common objects
    $conn = null; // Connection
    $Page = null; // Page
    $UserTable = null; // User table
    $UserTableConn = null; // User table connection
    $Table = null; // Main table
    $Grid = null; // Grid page object
    $Language = null; // Language
    $Security = null; // Security
    $UserProfile = null; // User profile
    $objForm = null; // Form

    // Current language
    $gsLanguage = "";

    // Token
    $gsToken = "";

    // Used by ValidateForm/ValidateSearch
    $gsFormError = ""; // Form error message
    $gsSearchError = ""; // Search form error message

    // Used by *master.php
    $gsMasterReturnUrl = "";

    // Used by header.php, export checking
    $gsExport = "";
    $gsExportFile = "";
    $gsCustomExport = "";

    // Used by header.php/footer.php, skip header/footer checking
    $gbSkipHeaderFooter = false;
    $gbOldSkipHeaderFooter = $gbSkipHeaderFooter;

    // Email error message
    $gsEmailErrDesc = "";

    // Debug message
    $gsDebugMsg = "";

    // Debug timer
    $gTimer = null;

    // Keep temp images name for PDF export for delete
    $gTmpImages = array();
}

// Mobile detect
$MobileDetect = null;

// Breadcrumb
$Breadcrumb = null;
?>
<?php

// Menu
define("EW_MENUBAR_ID", "RootMenu");
define("EW_MENUBAR_BRAND", "");
define("EW_MENUBAR_BRAND_HYPERLINK", "");
define("EW_MENUBAR_CLASSNAME", "");

//define("EW_MENU_CLASSNAME", "nav nav-list");
define("EW_MENU_CLASSNAME", "dropdown-menu");
define("EW_SUBMENU_CLASSNAME", "dropdown-menu");
define("EW_SUBMENU_DROPDOWN_IMAGE", "");
define("EW_SUBMENU_DROPDOWN_ICON_CLASSNAME", "");
define("EW_MENU_DIVIDER_CLASSNAME", "divider");
define("EW_MENU_ITEM_CLASSNAME", "dropdown-submenu");
define("EW_SUBMENU_ITEM_CLASSNAME", "dropdown-submenu");
define("EW_MENU_ACTIVE_ITEM_CLASS", "active");
define("EW_SUBMENU_ACTIVE_ITEM_CLASS", "active");
define("EW_MENU_ROOT_GROUP_TITLE_AS_SUBMENU", false);
define("EW_SHOW_RIGHT_MENU", false);
?>
<?php
define("EW_PDF_STYLESHEET_FILENAME", ""); // Export PDF CSS styles
