$(document).ready(function() {
    var storageName = window.location.href + '_pos';
    var posReader = localStorage[storageName];
    if (posReader) {
        $(window).scrollTop(posReader);
        localStorage.removeItem(storageName);
    }

    $('td a').click(function(e) {
        var storageName = window.location.href + '_pos';
        localStorage[storageName] = $(window).scrollTop();
        console.log(localStorage[storageName]);
    });

    $('body').on('mouseover',"input[name='Act[create_date]']",function() {
        datePickerDays ();
    });

    datePickerDays ();

    imagePreview();

    var offset = 220;
    var duration = 500;
    $(window).scroll(function() {
        if ($(this).scrollTop() > offset) {
            $('.back-to-top').fadeIn(duration);
        } else {
            $('.back-to-top').fadeOut(duration);
        }
    });

    $('.back-to-top').click(function(event) {
        event.preventDefault();
        $('html, body').animate({scrollTop: 0}, duration);
        return false;
    })

    $('body').on('change','#act-grid select', function(e) {
        var value = $(this).val();
        var name = $(this).attr('name');
        var url = window.location.href;
        if (url.indexOf("?") >= 0) {
            url = window.location.href + '&' + name + '=' + value;
        } else {
            url = window.location.href + '?' + name + '=' + value;
        }
        $.ajax({
            type: "GET",
            url: url,
            success: function(data) {
                $('#act-grid table tbody').html($(data).find('#act-grid tbody').html());
            }
        });
    })

    $('body').on('keypress focusout','#act-grid input', function(e) {
        var query = $(this).val();
        if(query != '' && (e.which == 13 || e.type == 'focusout')) {
            $(this).val('');
            pageSearch('#act-grid', query);
        }
    })

    $('body').on('click','.add_scope', function(e) {
        var oldScope = $(this).parents('.scope');
        var newScope = $('.scope.example').clone().removeClass('example');
        newScope.insertAfter(oldScope);
        oldScope.find('input[type=button]').hide();
        $('.scope_num').each(function(num, value) {
            $(this).text(num);
        });
    })

    $('body').on('click','.remove_scope', function(e) {
        if ($('.scope').not('.example').not('existed').length > 1) {
            var oldScope = $(this).parents('.scope');
            var newScope = oldScope.prev();
            oldScope.remove();
            newScope.find('input[type=button]').show();
            $('.scope_num').each(function(num, value) {
                $(this).text(num);
            });
        }
    })

    $('body').on('change','.select-period', function(e) {
        if ($(this).val() == 1) {
            $('.month-selector').fadeIn();
        } else {
            $('.month-selector').fadeOut();
            $('.date-send').click();
        }
    })
});

function searchHighlight(id, data) {
    var query = JSON.parse($(data).find("#query").val());
    var selector = '#' + id;
    pageSearch(selector, query);
}

function pageSearch(selector, query) {
    $('tr.highlight').removeClass('highlight');
    $('span.highlight').removeClass('highlight');
    $(selector).highlight(query);
    $(selector).find('span.highlight').parents('tr').addClass('highlight');
    if (!isScrolledIntoView("span.highlight")) {
        $('html, body').animate({
            scrollTop: $("span.highlight").offset().top - 400
        }, 2000);
    }
    setTimeout(function(){
        $('tr.highlight').removeClass('highlight');
        $('span.highlight').removeClass('highlight');
    },10000);
}

function isScrolledIntoView(elem) {
    var $elem = $(elem);
    var $window = $(window);

    var docViewTop = $window.scrollTop();
    var docViewBottom = docViewTop + $window.height();

    var elemTop = $elem.offset().top;
    var elemBottom = elemTop + $elem.height();

    return ((elemBottom <= docViewBottom) && (elemTop >= docViewTop));
}

function datePickerDays () {
    $( ".datepicker" ).datepicker({
        dateFormat: "yy-mm-dd"
    });
    $( ".date-select" ).datepicker({
        dateFormat: "yy-mm-dd"
    });

    $( "input[name='Act[create_date]']" ).datepicker({
        defaultDate: "+1w",
        dateFormat: "yy-mm-dd"
    });

    $( "#Act_month, #Car_month" ).datepicker({
        changeYear: true,
        changeMonth: true,
        showButtonPanel: true,
        navigationAsDateFormat: true,
        dateFormat: "yy-mm",
        hideIfNoPrevNext: true,
        onClose: function(dateText, inst) {
            var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
            var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
            $(this).datepicker('setDate', new Date(year, month, 1));
            $('.date-send').click();
        }
    });
}

/*
 * Image preview script
 * powered by jQuery (http://www.jquery.com)
 *
 * written by Alen Grakalic (http://cssglobe.com)
 *
 * for more info visit http://cssglobe.com/post/1695/easiest-tooltip-and-image-preview-using-jquery
 *
 */

this.imagePreview = function() {
    /* CONFIG */
    xOffset = 400;
    yOffset = 400;

    // these 2 variable determine popup's distance from the cursor
    // you might want to adjust to get the right result

    /* END CONFIG */
    $("a.preview").hover(function(e) {
            this.t = this.title;
            this.title = "";
            var c = (this.t != "") ? "<br/>" + this.t : "";
            $("body").append("<p id='preview'><img style='width: 200px' src='" + this.href + "' alt='Image preview' />" + c + "</p>");
            $("#preview")
                .css("top", "100px")
                .css("left", "10px")
                .css("position","fixed")
                .fadeIn("fast");
        },
        function() {
            this.title = this.t;
            $("#preview").remove();
        });
    $("a.preview").mousemove(function(e) {
        $("#preview")
            .css("top", "100px")
            .css("left", "10px");
    });
};