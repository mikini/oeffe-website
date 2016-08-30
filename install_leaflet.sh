#! /bin/bash

# Get and extract leaflet distribtion
if [ ! -d leaflet ]; then
    mkdir leaflet
    cd leaflet
    wget http://cdn.leafletjs.com/leaflet/v0.7.7/leaflet.zip
    unzip leaflet.zip
    rm leaflet.zip
else
    echo leaflet seems to be present.
fi

#mkdir -p images
#for img in marker-{shadow,icon}.png; do
#  wget -nc http://cdn.leafletjs.com/leaflet/v0.7.7/images/$img -O images/$img
#done
#wget -nc http://cdn.leafletjs.com/leaflet/v0.7.7/leaflet.css
#wget -nc http://cdn.leafletjs.com/leaflet/v0.7.7/leaflet.js
