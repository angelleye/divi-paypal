jQuery(function () {
    jQuery('[id^=angelleye_notification]').each(function (i) {
        jQuery('[id="' + this.id + '"]').slice(1).remove();
    });
    var el_notice = jQuery(".angelleye-notice");
    el_notice.fadeIn(750);
    jQuery(".angelleye-notice-dismiss").click(function (e) {
        e.preventDefault();
        jQuery(this).parent().parent(".angelleye-notice").fadeOut(600, function () {
            jQuery(this).parent().parent(".angelleye-notice").remove();
        });
        notify_wordpress(jQuery(this).data("msg"));
    });
    function notify_wordpress(message) {
        var param = {
            action: 'angelleye_dismiss_notice',
            data: message
        };
        jQuery.post(ajaxurl, param);
    }
    jQuery(document).off('click', '#angelleye-updater-notice .notice-dismiss').on('click', '#angelleye-updater-notice .notice-dismiss',function(event) {
        var r = confirm("If you do not install the Updater plugin you will not receive automated updates for Angell EYE products going forward!");
        if (r == true) {
            var data = {
                action : 'angelleye_updater_dismissible_admin_notice'
            };
            jQuery.post(ajaxurl, data, function (response) {
                var $el = jQuery( '#angelleye-updater-notice' );
                event.preventDefault();
                $el.fadeTo( 100, 0, function() {
                        $el.slideUp( 100, function() {
                                $el.remove();
                        });
                });
            });
        } 
    });
});
