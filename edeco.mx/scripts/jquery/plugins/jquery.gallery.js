/**
 * Edeco's gallery
 *
 * @author     LMV <luis.montealegre@mandragora-web.systems>
 * @copyright  Mandrágora Web-Based Systems
 * @version    SVN: $Id$
 * @package    Edeco
 */
(function($) {
    $.gallery = function(properties, options) {
        var defaults = {
            galleryDirectory: '/images/gallery/',
            imagesDirectory: '/images/properties/',
            propertyDetailUrl: '/'
        };
        options = $.extend(defaults, options);

        /**
         * @return void
         */
        var _assignEventHandlers = function() {
            $('.gallery-thumb').click(function() {
                _replaceInformation($(this).attr('id').split('-')[1]);
                return false;
            });
        };
    
        /**
         * @return void
         */
        var _replaceInformation = function(id) {
            for ( var i = 0; i < properties.length; i++) {
                var property = properties[i];
                if (property.id == id) {
                    $('#gallery-current').fadeOut('slow');
                    _replaceTitle(property.name);
                    _replaceImage(property.Picture);
                    _replaceDescription(property);
                    $('#gallery-current').fadeIn('slow');
                    break;
                }
            }
        };
    
        /**
         * @return void
         */
        var _replaceTitle = function(propertyName) {
            var title = $('<h2></h2>').append(propertyName)
                                      .attr('id', 'gallery-property-name');
            $('#gallery-property-name').replaceWith(title);
        };

        /**
         * @return void
         */
        var _replaceImage = function(picture) {
            var newImage = $('<img />');
            newImage.attr('src', options.galleryDirectory + picture.filename)
                    .attr('id', 'gallery-main-image');
            $('#gallery-main-image').replaceWith(newImage);
            $('#dialog').attr('href', options.imagesDirectory + picture.filename);
        };
    
        /**
         * @return void
         */
        var _replaceDescription = function(property) {
            var paragraphDescription =
                $('<p></p>').append('<strong>Descripción</strong>');
            var href = options.propertyDetailUrl;
            href = href.replace('propertyUrl', property.url)
                       .replace('category', property.Category.url)
                       .replace('state', property.Address.City.State.url)
                       .replace('availability', property.availabilityFor);
            var link = $('<a></a>').addClass('more').attr('href', href)
                                   .append('Leer más');
            var paragraphLink = $('<p></p>').append(link);
            $('.gallery-description').empty();
            $('.gallery-description').append(paragraphDescription)
                                     .append(property.description)
                                     .append(paragraphLink);
        };

        _assignEventHandlers();
    };
})(jQuery);