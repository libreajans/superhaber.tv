
$(window).scroll(function()
{
	if ($(window).scrollTop() >= 300)
	{
		$('nav').addClass('fixed-nav');
	}
	else
	{
		$('nav').removeClass('fixed-nav');
	}
});

$(function()
{
	$(".navigation-menu ").on('click', '#open-drop', function ()
	{
		$(".navigation-menu li div").toggleClass('open');
		$(".drop-down").stop().animate({top:"59px",opacity:"1"});
		$(this).attr('id', 'close-drop'); //use this one
	});

	$(".navigation-menu ").on('click', '#close-drop', function ()
	{
		$(".navigation-menu li div").toggleClass('open');
		$(".drop-down").stop().animate({top:"0",opacity:"0"});
		$("#close-drop").attr("id", "open-drop");
	});

	var ink, d, x, y;
	$(".link-effect").click(function(e)
	{
		if($(this).find(".ink").length === 0)
		{
			$(this).prepend("<span class='ink'></span>");
		}

		ink = $(this).find(".ink");
		ink.removeClass("animate");

		if(!ink.height() && !ink.width())
		{
			d = Math.max($(this).outerWidth(), $(this).outerHeight());
			ink.css({height: d, width: d});
		}

		x = e.pageX - $(this).offset().left - ink.width()/2;
		y = e.pageY - $(this).offset().top - ink.height()/2;

		ink.css({top: y+'px', left: x+'px'}).addClass("animate");
	});
});

$(function () {

	/* Footer Animate */
	var i = false;
	$(".open-footer-icon").click(function(){
		if (i == false) {
			$(".footer-category").animate({height:"420px"});
			$(".footer-category-container").animate({height:"420px"});
			i = true;
			$(this).html('<i class="icon">&#xe82a;</i>');
		} else {
			$(this).html('<i class="icon">&#xe827;</i>');
			$(".footer-category").animate({height:"120px"});
			$(".footer-category-container").animate({height:"120px"});
			i = false
		}
	});


});

