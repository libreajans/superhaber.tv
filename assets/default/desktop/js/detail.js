//twitter widget
window.twttr = (function(d, s, id) {
var js, fjs = d.getElementsByTagName(s)[0],
t = window.twttr || {};
if (d.getElementById(id)) return t;
js = d.createElement(s);
js.id = id;
js.src = "https://platform.twitter.com/widgets.js";
fjs.parentNode.insertBefore(js, fjs);

t._e = [];
t.ready = function(f) {
t._e.push(f);
};

return t;
}(document, "script", "twitter-wjs"));

//yorum alanı aç
function yorum_ac(hid) {
	$(".comments-"+hid).show();
	$('.comment-list-'+hid).jScrollPane();
};

//yorum gönder
function yorum_gonder(hid)
{
	var yorum				= $('#yorum-'+hid).val();
	var isim				= $('#isim-'+hid).val();
	var comment_to_date		= $('#comment_to_date-'+hid).val();
	var comment_to_hash		= $('#comment_to_hash-'+hid).val();

	//eksik datamız yok ise
	if(yorum != '' && isim != '' && comment_to_date != '' && comment_to_hash != '')
	{
		$.ajax(
		{
			type		: 'POST',
			url			: 'index.php?page=ajax',
			dataType	: 'html',
			data		:
			{
				type			: 'submit_comment',
				comment_to		: hid,
				isim			: isim,
				yorum			: yorum,
				comment_to_date	: comment_to_date,
				comment_to_hash	: comment_to_hash
			},
			success		: function(html)
			{
				$(window).on('ajaxComplete', function()
				{
					$('#title-comment-'+hid).remove();
					$('#write-comment-'+hid).remove();
					$('#result-comment-'+hid).show();
				});
			}
		}).fail(function(xhr, ajaxOptions, thrownError)
		{
			//hata var mı?
			//alert(thrownError);
		});
	}
};
