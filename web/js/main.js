/**
 * This is main Javascript file. Is used for basic scripts.
 * @author Mirosław Puczyński
 */
jQuery(document).ready(function () {
    //clean data from input form on click
    jQuery(document).on('click', 'input[type=text]', function () {
        jQuery(this).val('');
    });
    // add modals to nav menu
    jQuery('#top_navbar a').leanModal({
        width: 350
    });
});