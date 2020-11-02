<form name="form1" method="post" action="">
  <div align="center">
    <INPUT onClick="javascript:window.close()" TYPE="BUTTON" VALUE="Cerrar" TITLE="Haga clic aquí para cerrar la ventana" NAME="CloseWindow" >
  </div>
</form>
<?php


//$command = 'shp2pgsql  "uploads/elchore.shp" uploads.elchore -U admderechos -P Abt2016!';
//exec($command,$out,$ret);
//exit;

require "shpparser/shpparser.php";
$shp = new shpParser();
$shp->load('uploads/Capitalmunicipal_scz_utm21_satif_2007.shp');
print_r($shp->headerInfo()["shapeType"]["id"]);




//$command = 'shp2pgsql  "c:\\wamp\\www\\abt\\uploads\\shp\\p21.shp" temp.befcgaddbc07b93 > "c:\\wamp\\www\\abt\\uploads\\befcgaddbc07b93.sql"';

/*
$shell_output = fopen("shelloutput.bat","w+" );
fwrite($shell_output,$command);
$line2="\n"."exit";
fwrite($shell_output,$line2);
fclose($shell_output);
exec("start shelloutput.bat", $out,$ret);
*/

//$queries = shell_exec($command );
$command = 'shp2pgsql  "c:\\wamp64\\www\\abt\\uploads\\shp\\p21.shp" temp.befcgaddbc07b93 > "uploads/befcgaddbc07b93.sql"';


exec($command,$out,$ret);
var_dump($command,$out,$ret);

exit;
// Where the file is going to be placed 
$target_path = "uploads/";
$ok = 1;
/* Add the original filename to our target path.  
Result is "uploads/filename.extension" */
//$target_path = $target_path . basename( $_FILES['uploadedfile']['name']); 
//$_FILES['uploadedfile']['tmp_name'];  

$target_path = "uploads/";

$target_path = $target_path . basename( $_FILES['uploadedfile']['name']); 
if (file_exists($target_path)) {
    unlink($target_path);
}

$filename = "shp/".basename( $_FILES['uploadedfile']['name'],".zip")."shp";
if (file_exists($filename) ){
    unlink($filename);
}
$filename = "shp/".basename( $_FILES['uploadedfile']['name'],".zip")."shx";
if (file_exists($filename) ){
    unlink($filename);
}
$filename = "shp/".basename( $_FILES['uploadedfile']['name'],".zip")."dbf";
if (file_exists($filename)) {
    unlink($filename);
}
$filename = "uploads/quemas_inpe.sql";
if (file_exists($filename)) {
    unlink($filename);
}

if(move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $target_path)) {
    echo "El Archivo ".  basename( $_FILES['uploadedfile']['name']). 
    " ha sido cargado exitosamente";
/*
	require_once('pclzip.lib.php');
	$archive = new PclZip($target_path);
	if ($archive->extract("./uploads") == 0) {
	die("Error : ".$archive->errorInfo(true));
	$ok = 0;
	}
*/

        $zip = new ZipArchive();
        $x = $zip->open($target_path);  // open the zip file to extract
        if ($x === true) {
            $zip->extractTo("./uploads/shp"); // place in the directory with same name  
            $zip->close();
      
            unlink($target_path); //Deleting the Zipped file
            
$target_path = ".\\uploads\\shp\\";
//cargar la cobertura de poligonos.
$bytes = openssl_random_pseudo_bytes(4, $a);
$hex   = str_shuffle("abcdefg").bin2hex($bytes);
//$txt = "shp2pgsql -s 4326 -I -d ".$target_path.basename( $_FILES['uploadedfile']['name'],".zip")." temp.".$hex." > .\uploads\quemas_inpe.sql";
$command = "shp2pgsql -s ".$_POST["proj"]." -W \"latin1\" ".$target_path.basename( $_FILES['uploadedfile']['name'],".zip").".shp temp.".$hex." > .\uploads\\".$hex.".sql";
exec($command,$out,$ret);
var_dump($command,$out,$ret);

exit;
          
$db = pg_connect("host=localhost port=5432 dbname=geodatabase user=postgres password=arma");  
$filename = "uploads/".$hex.".sql";
$handle = fopen($filename, "r");
$query = fread($handle, filesize($filename)); 
$result = pg_query($db,$query); 
if (!$result)  
{  
echo "Update failed!!";  
}            
            
        }

} else{
    echo "Ocurrió un error al cargar el archivo, por favor intente nuevamente!";
	$ok = 0;
}
