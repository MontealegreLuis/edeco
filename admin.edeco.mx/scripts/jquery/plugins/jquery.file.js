/**
 * Plugin for styled file input elements
 *
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 * @copyright  Mandrágora Web-Based Systems 2010
 * @version    SVN: $Id$
 */
(function($) {
    $.fn.file = function(options) {
        var defaults = {
            fileImage: '/images/jquery/plugins/file/button.gif'
        };
        var options = $.extend(defaults, options);
        var fakeFileUpload = $('<div></div>');
        var input = $('<input />');
        var image = $('<img />');

        input.attr('readonly', true);
        fakeFileUpload.addClass('fakefile').append(input);
        image.attr('src', options.fileImage);
        fakeFileUpload.append(image);
        return this.each(function() {
            if ($(this).parent().hasClass('fileinputs')) {
                $(this).addClass('file');
                $(this).addClass('hidden');
                $(this).parent().append(fakeFileUpload);
                $(this).change(function(){
                    input.val($(this).val());
                });
                $(this).mouseout(function(){
                    input.val($(this).val());
                });
            }
        });    
    };
})(jQuery);