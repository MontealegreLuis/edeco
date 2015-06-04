/**
 * Class for pages add Address and update Address
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
Edeco.Pages.Picture = {};
Edeco.Pages.Picture.Detail = (function() {
	
    /**
     * @return void
     */
	var wrap =  function(message) {
        return $.format(
            '<ul class="error-list"><li class="error-message">{0}</li></ul>', 
            message
        );
    };
	
	/**
	 * @return void
	 */
	var _initValidator = function() {
	    var isImageFileRequired = $('#image').length == 0;
        var validator = $("#picture").validate({
            errorElement : 'div',
            rules: {
            	shortDescription: {
                    required: true,
                    minlength: 6,
                    maxlength: 80
                },
                filename: {
                	required: isImageFileRequired,
                	accept: '.jpg'
                }
            },
            messages: {
            	shortDescription: {
                    required: wrap(
                    'Por favor ingrese una breve descripción de la fotografía'
                    ),
                    minlength: $.format(
                    		wrap(
                             'La descripción debe tener al menos {0} caracteres'
                            )
                        ),
                    maxlength: $.format(
                    		wrap(
                            'La descripción debe tener máximo {0} caracteres'
                        )
                    )
                },
                filename: {
                    required: wrap(
                        'No seleccionó ninguna fotografía para el inmueble'
                    ),
                    accept: $.format(
                    		wrap(
                            'El archivo seleccionado no es una imagen .jpg'
                        )
                    )
                }
            }
        });
        $('#filename').file();
        $('#shortDescription').focus();
	};
	
	/**
	 * @retun void
	 */
	var _constructor = function(){
		
	    /**
	     * @return void
	     */
		this.init = function() {
		    _initValidator();
		};
	};
	
	return _constructor;
})();