$(document).ready(function() {
    var sticked = false;
    $(window).scroll(function(e) {
        if (!sticked && $(window).scrollTop() > $('.maintabmenu').offset().top) {
            sticked = true;
            $('thead').css({
                'position':'fixed',
                'top': 35
            });
            $('.maintabmenu').css({
                'position':'fixed',
                'top': 0
            });

            $('tbody tr.even:first td').each(function(id, value) {
                var etalon = $(value).outerWidth();
                $('thead .selector th').slice(id).outerWidth(etalon);
            });

            $('thead').width($('tbody').width() + 1);
        }

        if (sticked && $(window).scrollTop() == 0) {
            sticked = false;
            $('thead').css({
                'position':'relative'
            });
            $('.maintabmenu').css({
                'position':'relative'
            });
        }
    })
});