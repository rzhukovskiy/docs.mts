$(document).ready(function(){
    <!-- меню -->
    $('#menu li a').click(function(){
        $('#menu li a').removeClass("active");
        $(this).addClass("active");
    });
	
	<!-- пагинатор -->
    $('.pagination li a').click(function(){
        $('.pagination li a').removeClass("active");
        $(this).addClass("active");
    });
	
	<!-- tabs -->
    $('.tab').click(function(){
        $('.tab').removeClass("active");
        $(this).addClass("active");
    });
		
	<!-- modal -->
    $('.download_programm').click(function(){
        $('.modal').show();
		$('.opacity_bg').show();
    });
	$('.opacity_bg').click(function(){
        $('.modal').hide();
		$(this).hide();
    });
	$('.close').click(function(){
        $('.modal').hide();
		$('.opacity_bg').hide();
    });
	
	// tolika
	$('button[data-toggle="newcol"]').click(function(e){
		var $target = $('.newcol[data-target_block="' + $(this).data("target") + '"]');
		$(".newcol").fadeOut("fast");
		$target.fadeIn("fast");
		$(this).addClass("active");
		e.stopPropagation();
		return false;
	})
	//end
	
	$('button[data-toggle="newcolvideo"]').click(function(e){
		var $target = $('.newcolvideo[data-target_block="' + $(this).data("target") + '"]');
		$(".newcolvideo").fadeOut("fast");
		$target.fadeIn("fast");
		$(this).addClass("active");
		e.stopPropagation();
		return false;
	})
});