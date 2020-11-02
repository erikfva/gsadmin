var textStyleCache_216222defbcage71c23 = {}
var clusterStyleCache_216222defbcage71c23 = {}
var selectedClusterStyleCache_216222defbcage71c23 = {}
var style_216222defbcage71c23 = function(feature, resolution) {

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
            color: "rgba(214,205,104,1.0)"
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

    if (!textStyleCache_216222defbcage71c23[key]) {
        var text = new ol.style.Text({
            font: '1px Calibri,sans-serif',
            text: labelText,
            fill: new ol.style.Fill({
                color: "rgba(None, None, None, 255)"
            }),
        });
        textStyleCache_216222defbcage71c23[key] = new ol.style.Style({
            "text": text
        });
    }
    var allStyles = [textStyleCache_216222defbcage71c23[key]];
    var selected = lyr_216222defbcage71c23.selectedFeatures;
    if (selected && selected.indexOf(feature) != -1) {
        allStyles.push.apply(allStyles, selectionStyle);
    } else {
        allStyles.push.apply(allStyles, style);
    }
    return allStyles;
};