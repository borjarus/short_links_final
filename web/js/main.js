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
    jQuery('#top_navbar a.modal').leanModal({
        width: 350
    });
    //validate register fields
    jQuery(document).on('click', '#register_submit', function (e) {
        e.preventDefault();

        var data = {
            login:  jQuery('#username').val(),
            password:  jQuery('#password').val(),
            retype_password:  jQuery('#retype_password').val(),
            email:  jQuery('#email').val()
        }
        // find empty fields and set them as true
        var empty_error_Arr = new Array();
        jQuery.each(jQuery('form input'), function (){
            if ( jQuery(this).attr('id') !== undefined && jQuery(this).attr('id') != 'register_submit'){

                if ( jQuery(this).val() == '' ){
                    empty_error_Arr[jQuery(this).attr('id')] = true;
                } else {
                    empty_error_Arr[jQuery(this).attr('id')] = false;
                }
            }
        });
        // vaidate empty fields
        if ( empty_error_Arr['password'] && empty_error_Arr['retype_password'] ){
            jQuery('form .error').html('Pole hasło i powtórz hasło nie może być puste');
        } else {
            jQuery('form .error').html('');
            var password_different = data.password != data.retype_password;
            if ( password_different ){
                jQuery('form .error').html('Hasła różnią się od siebie');
            } else {
                jQuery('form .error').html('');
                var add_user = jQuery.ajax({
                    url: jQuery('#register_form').attr('action'),
                    type: "POST",
                    data: data,
                    dataType: "html"
                });
                add_user.done(function(){
                    jQuery("#lean_overlay").css({
                        "display": "block",
                        opacity: 0
                    });
                    jQuery('.modal_box').remove();
                })
            }
        }
    });
    // login user
    jQuery(document).on('click', '#login_form input[type=submit]', function (e) {
        e.preventDefault();
        console.log('click');
        var data = {
            login:  jQuery('#username').val(),
            password:  jQuery('#password').val()
        }

        var login_user = jQuery.ajax({
            url: jQuery('#login_form').attr('action'),
            type: "POST",
            data: data,
            dataType: "html"
        });
        login_user.done(function(){
            jQuery('#log_in').html( 'Zalogownano: '+data.login );
            jQuery("#lean_overlay").css({
                "display": "block",
                opacity: 0
            });
            jQuery('.modal_box').remove();
        })

    });
});