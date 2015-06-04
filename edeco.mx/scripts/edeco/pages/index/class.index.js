/**
 * Class for pages add property and update property
 *
 * @author     LMV <luis.montealegre@mandragora-web.systems>
 * @copyright  Mandrágora Web-Based Systems
 * @version    SVN: $Id$
 * @package    Edeco
 * @subpackage Pages
 */
var Edeco = {};
Edeco.Pages = {};
Edeco.Pages.Index = {};
Edeco.Pages.Index.Index = (function() {
    var galleryTemplate = 
        '<ul id="gallery-images" class="jcarousel-skin-edeco">{$pictureList}</ul>';
    var galleryItem = 
        '<li><a href="#" class="gallery-thumb" id="property-{$propertyId}">'
      + '<img alt="{$pictureAlt}" title="{$pictureTitle}" src="{$pictureThumbnailUrl}" width="{$pictureWidth}" height="{$pictureHeight}" />'
      + '</a></li>';
    
    var generateCarousel = function(properties, galleryImagesUrl) {
        var items = '';
        for (var i = 0; i < properties.length; i++) {
            var picture = properties[i].Picture;
            items += galleryItem.replace('{$propertyId}', properties[i].id)
                                .replace('{$pictureAlt}', picture.shortDescription)
                                .replace('{$pictureTitle}', picture.shortDescription)
                                .replace('{$pictureThumbnailUrl}', galleryImagesUrl + picture.filename)
                                .replace('{$pictureWidth}', picture.thumbWidth)
                                .replace('{$pictureHeight}', picture.thumbHeight);
        }
        return galleryTemplate.replace('{$pictureList}', items);
    };
    
    /**
     * @return Object
     */
    var _constructor = function() {
        var page = this;

        this.initCarouselCallback = function(carousel) {
            carousel.buttonNext.bind('click', function() {
                carousel.startAuto(0);
            });
            carousel.buttonPrev.bind('click', function() {
                carousel.startAuto(0);
            });
            // Pause autoscrolling if the user moves the cursor over the clip.
            carousel.clip.hover(function() {
                carousel.stopAuto();
            }, function() {
                carousel.startAuto();
            });
        };


        /**
         * Initialize the page
         *
         * @return void
         */
        this.init = function(properties, dialogImagesUrl, galleryImagesUrl, propertyDetailUrl) {
            $('.slideShow').slideShow({slideSize : {width: 450,height: 300}});
            if (properties.length > 0) {
                var gallery = generateCarousel(properties, galleryImagesUrl);
                $('.gallery-navigation:first').append(gallery);
                $('#gallery-images').jcarousel({
                    wrap: 'circular',
                    scroll: 1,
                    size: 5,
                    auto: 2,
                    initCallback: page.initCarouselCallback
                });
                $.gallery(
                    properties, 
                    {'galleryDirectory': galleryImagesUrl, 
                     'imagesDirectory': dialogImagesUrl, 
                     'propertyDetailUrl': propertyDetailUrl}
                );
            }
            $('#dialog').fancybox({
                'titlePosition': 'inside', 
                'onStart': $.fancybox.showActivity, 
                'onComplete': $.fancybox.hideActivity
            });
        };
    };

    return _constructor;
})();