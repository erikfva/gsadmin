<!-- Begin Main Menu -->
<?php $RootMenu = new cMenu(EW_MENUBAR_ID) ?>
<?php

// Generate all menu items
$RootMenu->IsRoot = TRUE;
$RootMenu->AddMenuItem(92, "mi_shapefiles", $Language->MenuPhrase("92", "MenuText"), "shapefileslist.php", -1, "", AllowListMenu('{00441056-EF9D-4233-BDD9-EE81681FA399}shapefiles'), FALSE);
$RootMenu->AddMenuItem(95, "mi_geoprocesamiento", $Language->MenuPhrase("95", "MenuText"), "geoprocesamientolist.php", -1, "", AllowListMenu('{00441056-EF9D-4233-BDD9-EE81681FA399}geoprocesamiento'), FALSE);
$RootMenu->AddMenuItem(52, "mci_Configuracif3n", $Language->MenuPhrase("52", "MenuText"), "", -1, "", IsLoggedIn(), FALSE, TRUE);
$RootMenu->AddMenuItem(43, "mi_usuario", $Language->MenuPhrase("43", "MenuText"), "usuariolist.php", 52, "", AllowListMenu('{00441056-EF9D-4233-BDD9-EE81681FA399}usuario'), FALSE);
$RootMenu->AddMenuItem(45, "mi_userlevels", $Language->MenuPhrase("45", "MenuText"), "userlevelslist.php", 52, "", (@$_SESSION[EW_SESSION_USER_LEVEL] & EW_ALLOW_ADMIN) == EW_ALLOW_ADMIN, FALSE);
$RootMenu->AddMenuItem(55, "mi_perfil", $Language->MenuPhrase("55", "MenuText"), "perfillist.php", 52, "", AllowListMenu('{00441056-EF9D-4233-BDD9-EE81681FA399}perfil'), FALSE);
$RootMenu->AddMenuItem(56, "mi_comportamiento", $Language->MenuPhrase("56", "MenuText"), "comportamientolist.php", 52, "", AllowListMenu('{00441056-EF9D-4233-BDD9-EE81681FA399}comportamiento'), FALSE);
$RootMenu->AddMenuItem(94, "mi_appacciones", $Language->MenuPhrase("94", "MenuText"), "appaccioneslist.php", 52, "", AllowListMenu('{00441056-EF9D-4233-BDD9-EE81681FA399}appacciones'), FALSE);
$RootMenu->AddMenuItem(-2, "mi_changepwd", $Language->Phrase("ChangePwd"), "changepwd.php", -1, "", IsLoggedIn() && !IsSysAdmin());
$RootMenu->AddMenuItem(-1, "mi_logout", $Language->Phrase("Logout"), "logout.php", -1, "", IsLoggedIn());
$RootMenu->AddMenuItem(-1, "mi_login", $Language->Phrase("Login"), "login.php", -1, "", !IsLoggedIn() && substr(@$_SERVER["URL"], -1 * strlen("login.php")) <> "login.php");
$RootMenu->Render();
?>
<!-- End Main Menu -->
