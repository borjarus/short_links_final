var topNavSlide = {
    init : function() {
        try {
            this.slideIn();
        } catch (e) {}
        try {
            this.flexslider();
        } catch (e) {}
        try {
            this.tabs();
        } catch (e) {}
    },
    slideIn : function(){
        jQuery(document).on('mouseover', '.zaloguj-sie', function () {
            jQuery('#slide_top_navbar').animate(
                {'marginTop' : '0'},
                'fast'
            )
        });
    },
    flexslider : function(){
        jQuery('.flexslider').flexslider({
            manualControls: ".flex-control-nav li a",
            directionNav: false,
            slideshow: true,
            slideshowSpeed: 4000,
            animationSpeed: 500
        });
    },
    tabs : function(){
        jQuery('#menu_news').tabify();
        jQuery('#menu_trasy').tabify();
    }
}

jQuery(document).ready(function () {
    topNavSlide.init();
});
