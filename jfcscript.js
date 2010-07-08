/*
Javascript to create a JQuery based Featured Content Gallery
*/

/*
Useful resources
http://jonraasch.com/blog/a-simple-jquery-slideshow
http://www.prelovac.com/vladimir/best-practice-for-adding-javascript-code-to-wordpress-plugin
*/


function slideSwitch() {
    var $active = jQuery('#jfcg div.active');
    if ( $active.length == 0 ) $active = jQuery('#jfcg div:last');

    var $next =  $active.next().length ? $active.next()
        : jQuery('#jfcg div:first');

    $active.addClass('last-active');

            $active.hide();
    $active.css({opacity: 0.0})
            //$active.animate({opacity: 0.0});
    $next.css({opacity: 0.0})
        .addClass('active')
        .animate({opacity: 1.0}, 1000, function() {
            $active.removeClass('active last-active');
            $active.show();
            jQuery('#jfcg div.active h2').show('slow').animate({ marginBottom: 0 },7000).hide('slow');
        });

}


jQuery('#jfcg div.active h2').show('slow');
jQuery(function() {
    setInterval( "slideSwitch()", 7000 );
});
