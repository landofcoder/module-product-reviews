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

define(['jquery','Hgati_All/lib/owl.carousel/owl.carousel.min'], function(){
    if(jQuery(".gallery-play").length > 0 ) {
        var owlItems = [];
        jQuery(".gallery-play").each( function(){
            var owlCarousel = jQuery(this).find(".owl-carousel");
            if(!owlItems[jQuery(owlCarousel).attr("id")]){
                var config = [];
                owlItems[jQuery(owlCarousel).attr("id")] = true;
                if(typeof(jQuery(owlCarousel).data('nav'))!='underfined'){
                    config['nav'] = jQuery(owlCarousel).data('nav');
                    if(config['nav'] == 0){
                        config['nav'] = false;
                    } else {
                        config['nav'] = true;
                    }
                }
                if(typeof(jQuery(owlCarousel).data('dot'))!='underfined'){
                    config['dots'] = jQuery(owlCarousel).data('dot');
                    if(config['dots'] == 0){
                        config['dots'] = false;
                    } else {
                        config['dots'] = true;
                    }
                }
                if(typeof(jQuery(owlCarousel).data('autoplay'))!='underfined'){
                    config['autoplay'] = jQuery(owlCarousel).data('autoplay');
                    if(config['autoplay'] == 0){
                        config['autoplay'] = false;
                    } else {
                        config['autoplay'] = true;
                    }
                }
                if(jQuery(owlCarousel).data('autoplay-timeout')){
                    config['autoplayTimeout'] = jQuery(owlCarousel).data('autoplay-timeout');
                }
                if(typeof(jQuery(owlCarousel).data('autoplay-pauonhover'))!='underfined'){
                    config['autoplayHoverPause'] = jQuery(owlCarousel).data('autoplay-pauonhover');
                    if(config['autoplayHoverPause'] == 0){
                        config['autoplayHoverPause'] = false;
                    } else {
                        config['autoplayHoverPause'] = true;
                    }
                }
                if(typeof(jQuery(owlCarousel).data('rtl'))!='underfined'){
                    config['rtl'] = jQuery(owlCarousel).data('rtl');
                    if(config['rtl'] == 0){
                        config['rtl'] = false;
                    } else {
                        config['rtl'] = true;
                    }
                }
                if(typeof(jQuery(owlCarousel).data('loop'))!='underfined'){
                    config['loop'] = jQuery(owlCarousel).data('loop');
                    if(config['loop'] == 0){
                        config['loop'] = false;
                    } else {
                        config['loop'] = true;
                    }
                }
                config['navText'] = [ 'prev', 'next' ];
                if(jQuery(owlCarousel).data("nav-text-owlpre")){
                    config['navText'] = [ jQuery(owlCarousel).data("nav-text-owlpre"), 'next' ];
                }
                if(jQuery(owlCarousel).data("nav-text-owlnext")){
                    config['navText'] = [ 'pre', jQuery(owlCarousel).data("nav-text-owlnext") ];
                }
                if(jQuery(owlCarousel).data("nav-text-owlpre") && jQuery(owlCarousel).data("nav-text-owlnext")){
                    config['navText'] = [ jQuery(owlCarousel).data("nav-text-owlpre"), jQuery(owlCarousel).data("nav-text-owlnext") ];
                }
                var mobile_items = 1;
                if(jQuery(owlCarousel).data('mobile-items')){
                    mobile_items = jQuery(owlCarousel).data('mobile-items');
                }
                var tablet_small_items = 3;
                if(jQuery(owlCarousel).data('tablet-small-items')){
                    tablet_small_items = jQuery(owlCarousel).data('tablet-small-items');
                }
                var tablet_items = 3;
                if(jQuery(owlCarousel).data('tablet-items')){
                    tablet_items = jQuery(owlCarousel).data('tablet-items');
                }
                var portrait_items = 4;
                if(jQuery(owlCarousel).data('portrait-items')){
                    portrait_items = jQuery(owlCarousel).data('portrait-items');
                }
                var large_items = 5;
                if(jQuery(owlCarousel).data('large-items')){
                    large_items = jQuery(owlCarousel).data('large-items');
                }
                var large_max_items = 6;
                if(jQuery(owlCarousel).data('large-max-items')){
                    large_max_items = jQuery(owlCarousel).data('large-max-items');
                }
                config['responsive'] = {
                    0 : {items: mobile_items},
                    480 : {items: tablet_small_items},
                    640 : {items: tablet_items},
                    768 : {items: portrait_items},
                    980 : {items: large_items},
                    1200 : {items: large_max_items}
                };
                jQuery(owlCarousel).owlCarousel(config);
                jQuery('.owl-left',jQuery(owlCarousel).parent()).click(function(){
                    owlCarousel.trigger('prev.owl.carousel');
                    return false;
                });
                jQuery('.owl-right',jQuery(owlCarousel).parent()).click(function(){
                    owlCarousel.trigger('next.owl.carousel');
                    return false;
                });
                var id = jQuery(owlCarousel).attr("id");
                if(jQuery("#" + id + " .gallery-item").height){
                    var height = 0;
                    jQuery("#" + id + " .gallery-item").each(function(){
                        if(jQuery(this).height()>height){
                            height = jQuery(this).height();
                        }
                    });
                    if(height>0){
                        jQuery("#" + id + " .gallery-item").css({"height":height+"px"});
                    }
                };
            };
        });
    }
});
