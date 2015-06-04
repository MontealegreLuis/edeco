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
Edeco.Pages.Property = {};
Edeco.Pages.Property.Detail = (function() {

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
        $('#property').validate({
            submitHandler: function(form) {
                $('.rte-zone').each(function(){
                    if ($(this).is('iframe')) {
                        var id = $(this).attr('id');
                        var textAreaId = 'textarea[id="' + id + '"]';
                        var html = $(this).contents().find('body').html(); 
                        var iframeContent = $('<div>' + html + '</div>');
                        $.rteTidy.tidy(iframeContent);
                        $(textAreaId).val(iframeContent.html());
                    }
                });
                //Check the field before sending, otherwise it won't be posted
                $('#showOnWeb').attr('checked', true); 
                form.submit();
            },
            errorElement : 'div',
            rules: {
                name: {
                    required: true,
                    minlength: 6,
                    maxlength: 45
                },
                description: {
                    rterequired: true,
                    rteminlength: 15,
                    rtemaxlength: 1500
                },
                price: {
                    rterequired: false,
                    rtemaxlength: 1500
                },
                category : {
                    required: true
                },
                totalSurface: {
                    required : true,
                    number: true,
                    min: 1
                },
                metersOffered: {
                    required : true,
                    number: true,
                    min: 1
                },
                metersFront: {
                    required : true,
                    number: true,
                    min: 1
                },
                landUse: {
                    required: true
                },
                contactName:{
                    required: false,
                    minlength: 6,
                    maxlength: 100
                },
                contactPhone:{
                    required: false,
                    minlength: 16,
                    maxlength: 16
                },
                contactCellphone:{
                     required: false,
                     minlength: 19,
                     maxlength: 19
                }
            },
            messages: {
                name: {
                    required: wrap(
                        'Por favor ingrese un nombre para el innmueble'
                    ),
                    minlength: $.format(
                        wrap(
                     'El nombre del inmueble debe tener al menos {0} caracteres'
                        )
                    ),
                    maxlength: $.format(
                        wrap(
                       'El nombre del inmueble debe tener máximo {0} caracteres'
                        )
                    )
                },
                description: {
                    rterequired: wrap('Por favor ingrese una descripción'),
                    rteminlength: $.format(
                        wrap(
                            'La descripción debe tener al menos {0} caracteres'
                        )
                    ),
                    rtemaxlength: $.format(
                        wrap('La descripción debe tener máximo {0} caracteres')
                    )
                },
                price: {
                    rterequired: wrap(
                        'Por favor ingrese el precio del inmueble'
                    ),
                    rtemaxlength: $.format(
                        wrap(
                            'El precio debe tener máximo {0} caracteres'
                        )
                    )
                },
                category: {
                    required: wrap(
                        'Por favor selecciona un tipo de propiedad'
                    )
                },
                totalSurface: {
                    required: wrap(
                        'Por favor ingrese el total de la superficie'
                    ),
                    number: wrap('Solo puede ingresar números'),
                    min: wrap(
                        'El total de la superficie debe ser mayor a 0'
                    )
                },
                metersOffered: {
                    required: wrap(
                        'Por favor ingrese la cantidad de metros ofrecidos'
                    ),
                    number: wrap('Solo puede ingresar números'),
                    min: wrap(
                        'La cantidad de metros ofrecidos debe ser mayor a 0'
                    )
                },
                metersFront: {
                    required: wrap(
                        'Por favor ingrese la cantidad de metros de frente'
                    ),
                    number: wrap('Solo puede ingresar números'),
                    min: wrap(
                        'La cantidad de metros de frente debe ser mayor a 0'
                    )
                },
                landUse: {
                    required: wrap(
                        'Por favor selecciona un tipo de uso de propiedad'
                    )
                },
                contactName: {
                    minlength: $.format(
                        wrap(
                     'El nombre del contacto debe tener al menos {0} caracteres'
                        )
                    ),
                    maxlength: $.format(
                        wrap(
                       'El nombre del contacto debe tener máximo {0} caracteres'
                        )
                    )
                },
                contactPhone: {
                    minlength: $.format(
                        wrap('Su número telefónico debe tener 10 dígitos')
                    ),
                    maxlength: $.format(
                        wrap('Su número telefónico debe tener 10 dígitos')
                    )
                },
                contactCellphone: {
                    minlength: $.format(
                            wrap('Su número celular debe tener 13 dígitos')
                        ),
                    maxlength: $.format(
                        wrap('Su número celular debe tener 13 dígitos')
                    )
                }
            }
        });
        $('#contactPhone').mask('(999) 9-99-99-99', {placeholder : ' '});
        $('#contactCellphone').mask('(99999) 99-99-99-99', {placeholder : ' '});
    };

    /**
     * @return void
     */
    var _constructor = function(){

        /**
         * @return void
         */
        this.init = function(cssUrl, imagesUrl) {
            $('.rte-zone').rte({cssUrl: cssUrl, mediaUrl: imagesUrl});

            /*
             * Move the textarea elements after the iframe, so that error
             * messages display correctly
             */
            $('textarea.rte-zone').each(function() {
                var dd = $(this).parent();
                var textArea = $(this).remove();
                textArea.appendTo(dd);
            });
            initValidator();
            if ($('.delete').length > 0) {
                var optionsConfirm = {
                    title: 'Confirmar eliminación',
                    message: '¿Desea eliminar este inmueble?',
                    ok: 'Sí',
                    cancel: 'No'
                };
                $('.delete').confirm(optionsConfirm);
            }
            fixCheckboxBehavior();
            $('#name').focus();
        };
    };
    
    var fixCheckboxBehavior = function() {
        $('input:hidden[name="showOnWeb"]').remove();
        $('#showOnWeb').click(function() {
            if ($(this).is(':checked')) {
                $(this).val('1');
            } else {
                $(this).val('0');
                $(this).attr('checked', false);
            }
        });
    };

    return _constructor;
})();