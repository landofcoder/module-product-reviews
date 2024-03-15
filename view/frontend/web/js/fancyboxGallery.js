/**
 * Hgati
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Hgati.com license that is
 * available through the world-wide-web at this URL:
 * https://hgati.com/terms
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category   Hgati
 * @package    Hgati_ProductReviews
 * @copyright  Copyright (c) 2021 Hgati (https://www.hgati.com/)
 * @license    https://hgati.com/terms
 */

define(['jquery', 'Hgati_All/lib/fancybox/jquery.fancybox.pack'], function() {
    jQuery(document).ready(function($) {
        jQuery(document).on("click", ".hgatireview-gallery-fancybox", function(){
            var config = {};
            config['autoSize']  = false;
            if(jQuery(this).data('fancybox-auto-size')){
                config['autoSize'] = jQuery(this).data('fancybox-auto-size');
                if(config['autoSize'] == 0){
                    config['autoSize'] = false;
                } else {
                    config['autoSize'] = true;
                }
            }
            if (!config['autoSize']) {
                if(jQuery(this).data('fancybox-height')){
                    config['minHeight'] = jQuery(this).data('fancybox-height');
                    config['height'] = jQuery(this).data('fancybox-height');
                }
                if(jQuery(this).data('fancybox-width')){
                    config['minWidth'] = jQuery(this).data('fancybox-width');
                    config['width'] = jQuery(this).data('fancybox-width');
                }
            }
            if(jQuery(this).data('fancybox-type')){
                config['type'] = jQuery(this).data('fancybox-type');
            }
            if(jQuery(this).data('fancybox-overlay-color')){
                config['overlayColor'] = jQuery(this).data('fancybox-overlay-color');
            }
            if(jQuery(this).data('fancybox-overlay-show')){
                config['overlayShow'] = jQuery(this).data('fancybox-overlay-show');
            }
            if(jQuery(this).data('fancybox-padding')){
                config['padding'] = jQuery(this).data('fancybox-padding');
            }
            if(jQuery(this).data('fancybox-margin')){
                config['margin'] = jQuery(this).data('fancybox-margin');
            }
            if(jQuery(this).data('fancybox-easing-in')){
                config['easingIn'] = jQuery(this).data('fancybox-easing-in');
            }
            if(jQuery(this).data('fancybox-easing-out')){
                config['easingOut'] = jQuery(this).data('fancybox-easing-out');
            }
            
            if(jQuery(this).attr('href')){
                config['href'] = jQuery(this).attr('href');
            }
            if(jQuery(this).data('fancybox-href')){
                config['href'] = jQuery(this).data('fancybox-href');
            }
            config['helpers'] = {
                overlay: {
                    locked: false
                }
            }
            if($(window).width()<=768){
                var pHref = jQuery(this).data('fancybox-href');
                pHref = pHref + 'is_redirect/true';
                window.location = pHref;
                return false;
            }
            jQuery.fancybox.open(config);
            return false;
        });
    });
});
