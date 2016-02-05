$(document).ready(function() {
    var sticked = false;
    $(window).scroll(function(e) {
        if (!sticked && $(window).scrollTop() > $('.maintabmenu').offset().top) {
            sticked = true;
            stick();
        }

        if (sticked && $(window).scrollTop() == 0) {
            sticked = false;

            $('.selector, .grid thead, .maintabmenu').css({
                'position':'relative',
                'top': 0
            });
        }
    })
});

function stick() {
    $('.selector').css({
        'position':'fixed',
        'top': 35
    });
    $('.grid thead').css({
        'position':'fixed',
        'top': 121
    });
    $('.maintabmenu').css({
        'position':'fixed',
        'top': 0
    });

    $('tbody tr.even:last td').each(function(id, value) {
        var etalon = $(value).width();
        $('.grid thead th').slice(id).width(etalon);
    });

    $('.selector, .grid thead').width($('tbody').width() + 1);
}