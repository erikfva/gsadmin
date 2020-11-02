baseLayers = [new ol.layer.Tile({
    type: 'base',
    title: 'OSM Mapnik',
    source: new ol.source.OSM()
})];
var baseLayersGroup = new ol.layer.Group({
    'type': 'base',
    'title': 'Base maps',
    layers: baseLayers
});
var lyr_21633facgebd15ec7824 = new ol.layer.Vector({
    opacity: 1.0,
    source: new ol.source.Vector({
        features: new ol.format.GeoJSON().readFeatures(geojson_21633facgebd15ec7824)
    }),

    style: style_21633facgebd15ec7824,
    title: "20160330facgebd15ec7824",
    filters: [],
    timeInfo: null,
    isSelectable: true
});

lyr_21633facgebd15ec7824.setVisible(true);
var layersList = [lyr_21633facgebd15ec7824];
layersList.unshift(baseLayersGroup);