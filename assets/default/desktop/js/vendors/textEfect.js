function textEfect()
{
var ch = 0;
var item = 0;
var items = $('.last-minute-list li').length;
var time = 2000;
var delay = 28;
var wait = 3000;
var tagOpen = false;

$('.last-minute-list-effect').css('width', ($('.last-minute-list').width()));

function tickInterval() 
{
	if(item >= items)
	{
		ch = 0;
		item = 0;
	}
	var text = $('.last-minute-list li:eq('+item+')').html();
	type(text);
	var tick = setTimeout(tickInterval, time);
}


function type(text)
{
	time = delay;
	ch++;
	if(text.substr((ch - 1), 1) == '<')
	{
		if(text.substr(ch, 1) == '/') 
		{
			tagOpen = false;
		}
		var tag = '';
		while(text.substr((ch - 1), 1) != '>') 
		{
			tag += text.substr((ch - 1), 1);
			ch++;
		}
		ch++;
		tag += '>';
		var html = /\<[a-z]+/i.exec(tag);
		if(html !== null)
		{
			html = html[0].replace('<', '</') + '>';
			tagOpen = html;
		}
	}
	if(tagOpen !== false)
	{
		var t = text.substr(0, ch) + tagOpen;
	}
	else
	{
		var t = text.substr(0, ch);
	}

	$('.last-minute-list-effect').html(t);
	if(ch > text.length)
	{
		item++;
		ch = 0;
		time = wait;
	}
}

var tick = setTimeout(tickInterval, time);
}