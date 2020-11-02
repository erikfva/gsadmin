<!-- Begin Main Menu -->
<?php

// Generate all menu items
$RootMenu->IsRoot = TRUE;
$RootMenu->AddMenuItem(92, "mmi_shapefiles", $Language->MenuPhrase("92", "MenuText"), "shapefileslist.php", -1, "", AllowListMenu('{00441056-EF9D-4233-BDD9-EE81681FA399}shapefiles'), FALSE);
$RootMenu->AddMenuItem(95, "mmi_geoprocesamiento", $Language->MenuPhrase("95", "MenuText"), "geoprocesamientolist.php", -1, "", AllowListMenu('{00441056-EF9D-4233-BDD9-EE81681FA399}geoprocesamiento'), FALSE);
$RootMenu->AddMenuItem(52, "mmci_Configuracif3n", $Language->MenuPhrase("52", "MenuText"), "", -1, "", IsLoggedIn(), FALSE, TRUE);
$RootMenu->AddMenuItem(43, "mmi_usuario", $Language->MenuPhrase("43", "MenuText"), "usuariolist.php", 52, "", AllowListMenu('{00441056-EF9D-4233-BDD9-EE81681FA399}usuario'), FALSE);
$RootMenu->AddMenuItem(45, "mmi_userlevels", $Language->MenuPhrase("45", "MenuText"), "userlevelslist.php", 52, "", (@$_SESSION[EW_SESSION_USER_LEVEL] & EW_ALLOW_ADMIN) == EW_ALLOW_ADMIN, FALSE);
$RootMenu->AddMenuItem(55, "mmi_perfil", $Language->MenuPhrase("55", "MenuText"), "perfillist.php", 52, "", AllowListMenu('{00441056-EF9D-4233-BDD9-EE81681FA399}perfil'), FALSE);
$RootMenu->AddMenuItem(56, "mmi_comportamiento", $Language->MenuPhrase("56", "MenuText"), "comportamientolist.php", 52, "", AllowListMenu('{00441056-EF9D-4233-BDD9-EE81681FA399}comportamiento'), FALSE);
$RootMenu->AddMenuItem(94, "mmi_appacciones", $Language->MenuPhrase("94", "MenuText"), "appaccioneslist.php", 52, "", AllowListMenu('{00441056-EF9D-4233-BDD9-EE81681FA399}appacciones'), FALSE);
$RootMenu->AddMenuItem(-2, "mmi_changepwd", $Language->Phrase("ChangePwd"), "changepwd.php", -1, "", IsLoggedIn() && !IsSysAdmin());
$RootMenu->AddMenuItem(-1, "mmi_logout", $Language->Phrase("Logout"), "logout.php", -1, "", IsLoggedIn());
$RootMenu->AddMenuItem(-1, "mmi_login", $Language->Phrase("Login"), "login.php", -1, "", !IsLoggedIn() && substr(@$_SERVER["URL"], -1 * strlen("login.php")) <> "login.php");
$RootMenu->Render();
?>
<!-- End Main Menu -->
