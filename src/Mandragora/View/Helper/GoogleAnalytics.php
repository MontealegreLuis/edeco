<?php
/**
 * PHP version 5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Mandragora\View\Helper;

/**
 * Google Analytics helper
 */
class GoogleAnalytics
{
    /**
     * @param string $trackerId
     * @return string
     */
    protected function getHtml($trackerId)
    {
        return "<script type='text/javascript'>
  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', '$trackerId']);
  _gaq.push(['_trackPageview']);
  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
</script>";
    }

    /**
     * @param string $trackerId
     * @return string
     */
    public function googleAnalytics($trackerId)
    {
        if (APPLICATION_ENV == 'production') {
            return $this->getHtml($trackerId);
        }
        return '';
    }
}
