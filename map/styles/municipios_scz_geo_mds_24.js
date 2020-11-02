var textStyleCache_municipios_scz_geo_mds_24 = {}
var clusterStyleCache_municipios_scz_geo_mds_24 = {}
var selectedClusterStyleCache_municipios_scz_geo_mds_24 = {}
var style_municipios_scz_geo_mds_24 = function(feature, resolution) {

    if (feature.hide === true) {
        return null;
    }


    var value = ""
    var style = [new ol.style.Style({
        stroke: new ol.style.Stroke({
            color: "rgba(0,0,0,1.0)",
            lineDash: null,
            width: 0
        }),
        fill: new ol.style.Fill({
            color: "rgba(82,182,248,1.0)"
        })
    })];
    var selectionStyle = [new ol.style.Style({
        stroke: new ol.style.Stroke({
            color: "rgba(255, 204, 0, 1)",
            lineDash: null,
            width: 0
        }),
        fill: new ol.style.Fill({
            color: "rgba(255, 204, 0, 1)"
        })
    })];
    var labelText = "";
    var key = value + "_" + labelText

    if (!textStyleCache_municipios_scz_geo_mds_24[key]) {
        var text = new ol.style.Text({
            font: '1px Calibri,sans-serif',
            text: labelText,
            fill: new ol.style.Fill({
                color: "rgba(None, None, None, 255)"
            }),
        });
        textStyleCache_municipios_scz_geo_mds_24[key] = new ol.style.Style({
            "text": text
        });
    }
    var allStyles = [textStyleCache_municipios_scz_geo_mds_24[key]];
    var selected = lyr_municipios_scz_geo_mds_24.selectedFeatures;
    if (selected && selected.indexOf(feature) != -1) {
        allStyles.push.apply(allStyles, selectionStyle);
    } else {
        allStyles.push.apply(allStyles, style);
    }
    return allStyles;
};