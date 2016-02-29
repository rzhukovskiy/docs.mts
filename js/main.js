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
        switch ($(this).val()) {
            case '1':
                $('#year').fadeIn();
                $('#month').fadeIn();
                $('#half').fadeOut();
                $('#quarter').fadeOut();
                break;
            case '2':
                $('#year').fadeIn();
                $('#quarter').fadeIn();
                $('#month').fadeOut();
                $('#half').fadeOut();
                break;
            case '3':
                $('#year').fadeIn();
                $('#half').fadeIn();
                $('#month').fadeOut();
                $('#quarter').fadeOut();
                break;
            case '4':
                $('#year').fadeIn();
                $('#month').fadeOut();
                $('#quarter').fadeOut();
                $('#half').fadeOut();
                break;
            default:
                $('.autoinput').not('.select-period').fadeOut();
        }
    })

    $('body').on('click','.date-send', function(e) {
        var startDate = new Date();
        var endDate = new Date();
        switch ($('.select-period').val()) {
            case '1':
                startDate = new Date($('#year option:selected').text(), $('#month').val(), 1);
                if ($('#month').val() == 11) {
                    endDate = new Date(parseInt($('#year option:selected').text()) + 1, 0, 1);
                } else {
                    endDate = new Date($('#year option:selected').text(), parseInt($('#month').val()) + 1, 1);
                }
                break;
            case '2':
                startDate = new Date($('#year option:selected').text(), $('#quarter').val() * 3, 1);
                if ($('#quarter').val() == 3) {
                    endDate = new Date(parseInt($('#year option:selected').text()) + 1, 0, 1);
                } else {
                    endDate = new Date($('#year option:selected').text(), parseInt($('#quarter').val()) * 3 + 3, 1);
                }
                break;
            case '3':
                startDate = new Date($('#year option:selected').text(), $('#half').val() * 6, 1);
                if ($('#half').val() == 1) {
                    endDate = new Date(parseInt($('#year option:selected').text()) + 1, 0, 1);
                } else {
                    endDate = new Date($('#year option:selected').text(), parseInt($('#half').val()) * 6 + 6, 1);
                }
                break;
            case '4':
                startDate = new Date($('#year option:selected').text(), 0, 1);
                endDate = new Date(parseInt($('#year option:selected').text()) + 1, 0, 1);
                break;
            default:
                $('.from_date').remove();
                $('.to_date').remove();
                return true;
        }
        $('.from_date').datepicker({
            dateFormat: "yy-mm-dd"
        });
        $('.from_date').datepicker('setDate', startDate);
        $('.to_date').datepicker({
            dateFormat: "yy-mm-dd"
        });
        $('.to_date').datepicker('setDate', endDate);
        $('.autoinput').remove();
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
    if ($( "span.highlight" ).length && !isScrolledIntoView("span.highlight")) {
        $('html, body').animate({
            scrollTop: $("span.highlight").offset().top - 600
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

this.addHeaders = function(options) {
    var tableSelector = options.tableSelector;
    var headers = options.headers;
    var footers = options.footers;
    var defaultClass = 'extra-row';


    footers.forEach(function(trigger, i) {
        var previousValue = 'empty';
        var total = 0;
        var len = $(tableSelector).find('tbody tr')
            .not('.' + defaultClass).length;

        $(tableSelector).find('tbody tr')
            .not('.' + defaultClass)
            .each(function(id, row) {

                var currentValue = $(row).find(trigger.className).text();
                var pos = $(row).find('td:visible').index($(row).find('.sum'));

                if (previousValue != 'empty' && previousValue != currentValue) {
                    if (previousValue != '') {
                        var tr = $('<tr>').addClass(trigger.rowClass).addClass(defaultClass);
                        for (var i = 0; i < $(row).find('td:visible').length; i++) {
                            if (i == pos) {
                                var td = $('<td>').text(numeral(total).format()).css('text-align', 'center');
                                tr.append(td);
                            } else if (i == 0) {
                                var td = $('<td>').text(trigger.title).css('text-align', 'center');
                                tr.append(td);
                            } else {
                                var td = $('<td>');
                                tr.append(td);
                            }
                        }
                        $(row).before(tr);
                    }

                    total = 0;
                }

                total += parseInt($(row).find('.sum').text().replace(" ", ""));
                previousValue = currentValue;

                if (id == len - 1 && currentValue != '') {
                    var tr = $('<tr>').addClass(trigger.rowClass).addClass(defaultClass);
                    for (var i = 0; i < $(row).find('td:visible').length; i++) {
                        if (i == pos) {
                            var td = $('<td>').text(numeral(total).format()).css('text-align', 'center');
                            tr.append(td);
                        } else if (i == 0) {
                            var td = $('<td>').text(trigger.title).css('text-align', 'center');
                            tr.append(td);
                        } else {
                            var td = $('<td>');
                            tr.append(td);
                        }
                    }
                    $(row).after(tr);
                    total = 0;
                }
            });
    });

    headers.forEach(function(trigger, i) {
        var previousValue = '';
        $(tableSelector).find('tbody tr')
            .not('.' + defaultClass)
            .each(function(id, row) {

                var currentValue = $(row).find(trigger.className).text();
                if($(row).find(trigger.className).attr('data-header')) {
                    currentValue = $(row).find(trigger.className).attr('data-header');
                }

                if (previousValue != currentValue) {
                    var td = $('<td>').text(currentValue).attr("colspan", $(row).find('td').length);
                    var tr = $('<tr>').addClass(trigger.rowClass).addClass(defaultClass).append(td);
                    $(row).before(tr);
                }
                previousValue = currentValue;
            });
    });
};