$(window).scroll(function()
{
	if ($(window).scrollTop() >= 300)
	{
		$('nav').addClass('fixed-nav');
		$('nav .navigation-menu li:first-child').find("span").addClass("display-none");
		$('nav .navigation-menu li:first-child').find("img").removeClass("display-none");
		$('nav .navigation-menu li.hide-menu ').removeClass("display-none");
	}
	else
	{
		$('nav').removeClass('fixed-nav');
		$('nav .navigation-menu li:first-child').find("span").removeClass("display-none");
		$('nav .navigation-menu li:first-child').find("img").addClass("display-none");
		$('nav .navigation-menu li.hide-menu ').addClass("display-none");
	}
});

$(function()
{
	$(".navigation-menu ").on('click', '#open-drop', function ()
	{
		$(".navigation-menu li div").toggleClass('open');
		$(".drop-down").stop().animate({top:"49px",opacity:"1"});
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


