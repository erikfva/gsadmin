<?php

error_reporting(E_ALL);

//$filename = 'cache/'.md5(implode('&', $_GET));
$wmsurl = "";

global $_GET;
//if(!file_exists($filename)){
	$params = $_GET;

	$params["token"] = !empty($params["token"])? $params["token"]: "";
	$params["table"] = $params["token"];

$db = pg_connect("host=localhost port=5432 dbname=geodatabase user=postgres password=arma");  

/*
$query = "SELECT concat_ws(' ', st_xmin(st_extent(geom)) , st_ymin(st_extent(geom)) ,
st_xmax(st_extent(geom)) , st_ymax(st_extent(geom))) as extent from uploads.".$params["table"] ; 

$result = pg_query($db,$query); 
$value = pg_fetch_result($result, 0, 'extent');
	$params["MAPEXT"] = $value;
*/

/*	
	$query = "SELECT ST_SRID(geom) as srid FROM uploads.".$params["table"]." LIMIT 1;"; 

	$result = pg_query($db,$query); 
	$value = pg_fetch_result($result, 0, 'srid');
	$params["srid"] = $value;	
*/
	
//	$params["map_projection"] = "init=epsg:".$value;

  $params["map_projection"] = "init=epsg:3857";
  $params["LAYERS"] =  "poligono";
  $params["TILED"] =  "true";
  $params["STYLES"] =  "";
  
/*$query = "SELECT replace(btrim(Box2D(ST_Transform(ST_SetSRID(ST_Extent(geom),".$params["srid"]."), 3857))::text, 'BOX()'),' ',',')
 as extent from uploads.".$params["table"] ; 
*/

$query = "SELECT replace(btrim(ST_Extent(the_geom_webmercator)::text, 'BOX()'),' ',',')
 as extent from uploads.".$params["table"] ; 

$result = pg_query($db,$query); 
$zoomExt = pg_fetch_result($result, 0, 'extent');

  
/*          	
var_dump(json_encode($params));

exit;


	$params['FORMAT'] = urlencode('image/png');
	$params['LAYERS'] = 'poligono';
	$params['CACHE'] = 'true';
	
	foreach($params as $key => $value)
	{
		$qs[] = $key.'='.$value;
	}
	$wmsurl = 'http://localhost/cgi-bin/mapserv.exe?'.implode('&', $qs);
	//file_put_contents($filename, file_get_contents($wmsurl));
//}


//header('Content-type: image/png');
//header('Cache-Control: max-age=604800');
header("Location: " . $wmsurl);
error_reporting(0);
//fpassthru(fopen($filename, 'r'));

*/
?>
