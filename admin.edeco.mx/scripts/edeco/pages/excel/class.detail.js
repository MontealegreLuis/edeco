/**
 * Class for pages add property and update property
 *
 * @author     LMV <luis.montealegre@mandragora-web.systems>
 * @author     MMA <misraim.mendoza@mandragora-web.systems>
 * @copyright  Mandrágora Web-Based Systems
 * @version    SVN: $Id$
 * @package    Edeco
 * @subpackage Pages
 */
var Edeco = {};
Edeco.Pages = {};
Edeco.Pages.Excel = {};
Edeco.Pages.Excel.Detail = (function() {
    
    /**
     * @return void
     */
    var _initValidator = function() {
        var validator = $("#excel").validate({
            errorElement : 'div',
            rules: {
                groups: {
                    dateRange: 'startDate stopDate'
                }
            }
        });
    };
    
    /**
     * @return void
     */
    var _setupCalendars = function() {
        $('.date').datepicker({ 
            dayNamesMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'],
            maxDate: '+0d',
            monthNames: [
                'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
                'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre',
                'Diciembre'
            ],
            dateFormat: 'yy/mm/dd'
        });
    };
    
    
    /**
     * @return void
     */
    var _constructor = function(){
        
        /**
         * @return void
         */ 
        this.init = function() {
            _initValidator();
            _setupCalendars();
        };
    };
    return _constructor;
})();