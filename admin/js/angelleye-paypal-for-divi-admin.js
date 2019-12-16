(function ($) {

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
    window.localStorage.clear();
})(jQuery);
