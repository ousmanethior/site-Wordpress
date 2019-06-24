<?php
/**
 * Template Name: Testing
 * Created by PhpStorm.
 * User: waqasriaz
 * Date: 16/12/15
 * Time: 3:27 PM
 */
get_header();
?>
<style type="text/css">
	#map_canvas {
	    border: 1px solid #dedede;
	    height: 418px;
	    width: 100%;
	}
</style>
<div class="row">
    <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12 list-grid-area container-contentbar">
        <div id="content-area">
			<div id="map_canvas"></div>
		</div>
	</div>
</div>

<script>
    jQuery( function( $ ) {
        'use strict';

        var tileLayer = L.tileLayer( 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution : '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        } );

        /*var tileLayer = L.tileLayer( 'https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token=pk.eyJ1Ijoid2FxYXNyaWF6IiwiYSI6ImNqdXIyaDFtNTA1dzQ0NHA1MXJ3Z205emkifQ.LofmdwTRdw3JSChLFR3AxQ', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
            maxZoom: 18,
            id: 'mapbox.streets',
            accessToken: 'your.mapbox.access.token'
            } 
        );*/

        var mapCenter = L.latLng( 31.582045, 74.329376 );
        var mapZoom = 16;

        var mapOptions = {
            center : mapCenter, zoom : mapZoom
        };

        var contactMap = L.map( 'map_canvas', mapOptions );
        contactMap.scrollWheelZoom.disable();
        contactMap.addLayer( tileLayer );

        var myIcon = L.icon({
		    iconUrl: 'http://localhost:8888/houzezwp/wp-content/uploads/2019/04/pin-villa.png',
		    iconSize: [38, 95],
		    iconAnchor: [22, 94],
		    popupAnchor: [-3, -76],
		    shadowUrl: 'my-icon-shadow.png',
		    shadowSize: [68, 95],
		    shadowAnchor: [22, 94]
		});
		L.marker(mapCenter, {icon: myIcon}).addTo(contactMap);

    } );
</script>

<?php
get_footer(); ?>



