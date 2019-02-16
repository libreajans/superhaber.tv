$(function ()
{
	$('#open-side-menu').click(function(){
		$(this).toggleClass('open');
	});
	$("#go-up").click(function(){
		$("html, body").animate({ scrollTop: "0" }, 500);
	});
});

$(window).scroll(function()
{
	if ($(window).scrollTop() >= 300)
	{
		$('#menu').addClass('fixed-nav');
		$('.menu li:first-child').find("i").addClass("display-none");
		$('.menu li:first-child').find("span").removeClass("display-none");

	}
	else
	{
		$('#menu').removeClass('fixed-nav');
		$('.menu li:first-child').find("i").removeClass("display-none");
		$('.menu li:first-child').find("span").addClass("display-none");
	}

	if ($(this).scrollTop() > 50)
	{
		$("#go-up").fadeIn(400);
	}
	else
	{
		$("#go-up").fadeOut(400);
	}
});
