/**
 * Class for pages add property and update property
 *
 * @author    LMV <luis.montealegre@mandragora-web.systems>
 * @copyright Mandrágora Web-Based Systems
 * @version   SVN: $Id$
 */
(function($) {
    
    $.googleMap = function (address, mapContainerId) {
        
        if (GBrowserIsCompatible()) {            
            var map = new GMap2(document.getElementById(mapContainerId));
            map.setUIToDefault();
            var baseIcon = new GIcon(G_DEFAULT_ICON);
            baseIcon.shadow = "http://www.google.com/mapfiles/shadow50.png";
            baseIcon.iconSize = new GSize(20, 34);
            baseIcon.shadowSize = new GSize(37, 34);
            baseIcon.iconAnchor = new GPoint(9, 34);
            baseIcon.infoWindowAnchor = new GPoint(9, 2);

            /**
             * @return GMarker
             */
            function createMarker(point, addressInfo) {
              var letter = String.fromCharCode("A".charCodeAt(0) + 0);
              var letteredIcon = new GIcon(baseIcon);
              letteredIcon.image = "http://www.google.com/mapfiles/marker" 
                  + letter + ".png";
              markerOptions = { icon:letteredIcon };
              var marker = new GMarker(point, markerOptions);
              GEvent.addListener(marker, "click", function() {
                marker.openInfoWindowHtml(
                    '<p>' + addressInfo.name  + '</p><p>' + addressInfo.Address 
                    + '</p>'
                );
              });
              return marker;
            };
            
            var latlng = new GLatLng(address.latitude, address.longitude);
            var bounds = new GLatLngBounds(
                new GLatLng(address.latitude, address.longitude), 
                new GLatLng(address.latitude, address.longitude)
            );
            map.setCenter(bounds.getCenter(), map.getBoundsZoomLevel(bounds));
            map.addOverlay(createMarker(latlng, address));
        }
    };
    
})(jQuery);