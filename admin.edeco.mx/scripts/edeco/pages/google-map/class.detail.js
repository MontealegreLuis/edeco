/**
 * Class for pages add Address and update Address
 *
 * @author     LNJ <lemuel.nonoal@mandragora-web.systems>
 * @author     LMV <luis.montealegre@mandragora-web.systems>
 * @author     MMA <misraim.mendoza@mandragora-web.systems>
 * @copyright  Mandrágora Web-Based Systems
 * @version    SVN: $Id$
 * @package    Edeco
 * @subpackage Pages
 */
var Edeco = {};
Edeco.Pages = {};
Edeco.Pages.GoogleMap = {};
Edeco.Pages.GoogleMap.Detail = (function() {

    var wrap =  function(message) {
        return $.format('<ul><li>{0}</li></ul>', message);
    };
    
    /**
     * @return void
     */
    var setUptCheckBoxes = function() {
        $(":checkbox").click(function() {
            $('#latitude').val($('#latitude-' + $(this).attr('id')).val());
            $('#longitude').val($('#longitude-' + $(this).attr('id')).val());
            $(':checkbox').attr('checked', false);
            $(this).attr('checked', true);
        });
    };

    var initValidator = function() {
        $("#google-map").validate({
            errorElement : 'div',
            rules: {
                latitude: {required: true},
                longitude: {required: true}
            },
            messages: {
                latitude: {required: wrap('Por favor ingrese la latitud')},
                longitude: {required: wrap('Por favor ingrese la longitud')}
            }
        });
    };
    
    /**
     * @param Object results
     * @return void
     */
    var _constructor = function() {

        /**
         * Initialize the page
         *
         * @return void
         */
        this.init = function(results) {
            for (var i = 0; i < results.length; i++) {
                $.googleMap(results[i], 'google-map-' + i);
            }
            $('body').unload(GUnload);
            setUptCheckBoxes();
            initValidator();
        };
    };

    return _constructor;
})();