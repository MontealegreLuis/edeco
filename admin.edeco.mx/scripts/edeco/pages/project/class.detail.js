/**
 * Class for pages add project and update project
 */
var Edeco = {};
Edeco.Pages = {};
Edeco.Pages.Project = {};
Edeco.Pages.Project.Detail = (function() {

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
	var initValidator = function() {
	    var isFileRequired = !$('#project').hasClass('update');

        $('#project').validate({
            errorElement : 'div',
            rules: {
                name: {
                    required: true,
                    minlength: 6,
                    maxlength: 40
                },
                attachment: {
                	required: isFileRequired,
                	accept: '.pps|.ppsx'
                }
            },
            messages: {
                name: {
                    required: wrap(
                        'Por favor ingrese un nombre para el proyecto'
                    ),
                    minlength: $.format(
                    		wrap(
                     'El nombre del proyecto debe tener al menos {0} caracteres'
                        )
                    ),
                    maxlength: $.format(
                    		wrap(
                       'El nombre del proyecto debe tener máximo {0} caracteres'
                        )
                    )
                },
                attachment: {
                	required: wrap(
                        'No seleccionó ninguna presentación para el proyecto'
                    ),
                    accept: $.format(
                    		wrap(
                            'El archivo seleccionado no es un PowerPoint válido'
                        )
                    )
                }
            }
        });
        $('#name').focus();
	};

	/**
	 * @return void
	 */
	var _constructor = function(){

	    /**
	     * @return void
	     */
		this.init = function() {
		    initValidator();
		    if ($('.delete').length > 0) {
                var optionsConfirm = {
                    title: 'Confirmar eliminación',
                    message: '¿Desea eliminar este proyecto?',
                    ok: 'Sí',
                    cancel: 'No'
                };
                $('.delete').confirm(optionsConfirm);
            }
		    $('#attachment').file();
		};
	};

	return _constructor;
})();
