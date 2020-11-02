<?php include_once "wms.php" ?>
<!doctype html>
<html>
  <head>
    <title>Hello OpenStreetMap</title>
    <link rel="stylesheet" href="ol3/css/ol.css" type="text/css">
    <script src="ol3/build/ol-debug.js"></script>
  </head>
  <body>
    <div id="map" class="map"></div>
    <script>
        // Declare a Tile layer with an OSM source
        var osmLayer = new ol.layer.Tile({
          source: new ol.source.OSM()
        });
        // Create latitude and longitude and convert them to default projection
        var santacruz = ol.proj.transform([-61.3, -16.80], 'EPSG:4326', 'EPSG:3857');
        // Create a View, set it center and zoom level
        var view = new ol.View({
          center: santacruz,
          zoom: 6
        });
        // Instanciate a Map, set the object target to the map DOM id
        var map = new ol.Map({
          target: 'map'
        });
        // Add the created layer to the Map
        map.addLayer(osmLayer);
        
var projection = new ol.proj.Projection({
  code: 'EPSG:32720',
  extent: [441867.78, 1116915.04, 833978.56, 10000000.00],
  units: 'm'
});
/*
var lyr_poligono = new ol.layer.Tile({
    opacity: 1.0,
    timeInfo: null,

    source: new ol.source.TileWMS(({
        url: "http://localhost/cgi-bin/mapserv.exe?map%3D/wamp64/www/abt/tpl_poligono.map",
        params: {
            "LAYERS": "poligono",
            "TILED": "true",
            "STYLES": "",
            "table": "fbaedgc7a2934ff"
        },
        serverType: 'mapserver'
    })),
    title: "poligono"
});
*/
/*
var lyr_poligono = new ol.layer.Image({
    opacity: 1.0,
    timeInfo: null,

    source: new ol.source.ImageWMS({
        url: "http://localhost/msrv?map%3D/wamp64/ms_maps/tpl_poligono.map",
        params: <?php echo json_encode($params);?>,
        serverType: 'mapserver'
    }),
    title: "poligono"
});
*/
var lyr_poligono = new ol.layer.Tile({
    opacity: 1.0,
    timeInfo: null,

    source: new ol.source.TileWMS(({
        url: "http://localhost/msrv?map%3D/wamp64/ms_maps/tpl_poligono.map",
        params: <?php echo json_encode($params);?>,
        serverType: 'mapserver'
    })),
    title: "poligono"
});



lyr_poligono.setVisible(true);

  map.addLayer(lyr_poligono)
          
        // Set the view for the map
        map.setView(view);            
        var extent = [<?php echo $zoomExt;?>];        
        console.log(extent);
 				map.getView().fit(extent, map.getSize());

    </script>
  </body>
</html>