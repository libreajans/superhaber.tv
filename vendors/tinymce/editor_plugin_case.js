/**
| Add Change Case buttons for TinyMCE 4.x
|
| * caseUpper
| * LowerCase
| * SentenceCase
| * TitleCase
|
| Copyright, Sabri Ünal/Yakusha <yakushabb@gmail.com>
| Released under Creative Commons Attribution-NonCommercial 3.0 Unported License.
|
| Contributing:
| Sources:
| * http://stackoverflow.com/questions/11933577/javascript-convert-unicode-string-to-title-case
| * http://stackoverflow.com/questions/1850232/turkish-case-conversion-in-javascript
| * https://github.com/wp-plugins/change-case-for-tinymce
*/

function toProperCase( str )
{
	/**
	 This function convert
	 text to Title Case Format
	*/

    var i,
        j,
        chars,
        arr;

    arr = str.replace(/I/g,"ı").toLocaleLowerCase( ).split("");

    chars = {
        " " : true,
        "-" : true,
        ":" : true,
        "=" : true,
        "/" : true
    };

    for( var i = 0, j = -1; i < arr.length; i += 1, j += 1 ) {
        // if previous char (j) exists in chars and current (i) does not;
        // replace with caseUpper equivalent.
        if ( ( arr[j] && chars[ arr[j] ] && !chars[ arr[i] ] ) || i === 0){
            arr[i] = arr[i].replace(/i/g,"İ").toLocaleUpperCase( );
        }
    }
    return arr.join("");
}

tinymce.PluginManager.add('caseSpecialTitle', function(ed, url)
{
	ed.addButton('caseSpecialTitle',
	{
		title : 'Özel Başlık',
		image : url+'/icon.png',
		onclick : function()
		{
			String.prototype.caseSpecialTitle = function()
			{
				return this.toLocaleUpperCase();
			}
			var sel = ed.dom.decode(ed.selection.getContent());
 			sel = sel.replace(/i/g,"İ").toLocaleUpperCase();
			ed.selection.setContent('<h3><strong>'+sel+'</strong></h3>');
			ed.save();
			ed.isNotDirty = true;
		}
	});
});

tinymce.PluginManager.add('caseUpper', function(ed, url)
{
	ed.addButton('caseUpper',
	{
		title : 'HEPSİ BÜYÜK HARF',
		image : url+'/uc.png',
		onclick : function()
		{
			String.prototype.caseUpper = function()
			{
				return this.toLocaleUpperCase();
			}
			var sel = ed.dom.decode(ed.selection.getContent());
 			sel = sel.replace(/i/g,"İ").toLocaleUpperCase();
			ed.selection.setContent(sel);
			ed.save();
			ed.isNotDirty = true;
		}
	});
});

tinymce.PluginManager.add('caseLower', function(ed, url)
{
	ed.addButton('caseLower',
	{
		title : 'hepsi küçük harf',
		image : url+'/lc.png',
		onclick : function()
		{
			String.prototype.caseLower = function()
			{
				return this.toLocaleLowerCase();
			}
			var sel = ed.dom.decode(ed.selection.getContent());
			sel = sel.replace(/I/g,"ı").toLocaleLowerCase();
			ed.selection.setContent(sel);
			ed.save();
			ed.isNotDirty = true;
		}
	});
});

tinymce.PluginManager.add('caseSentence', function(ed, url)
{
	ed.addButton('caseSentence',
	{
		title : 'Normal tümce düzeni',
		image : url+'/sc.png',
		onclick : function()
		{
			String.prototype.caseSentence = function()
			{
				return this.toLocaleLowerCase().replace(/(^\s*\w|[\.\!\?]\s*\w)/g, function(c)
				{
					return c.toLocaleUpperCase()
				});
			}
			var sel = ed.dom.decode(ed.selection.getContent());
			sel = sel.replace(/I/g,"ı").toLocaleLowerCase();
			sel = sel.caseSentence();
			ed.selection.setContent(sel);
			ed.save();
			ed.isNotDirty = true;
		}
	});
});

tinymce.PluginManager.add('caseTitle', function(ed, url)
{
	ed.addButton('caseTitle',
	{
		title : 'Başlık Düzeni',
		image : url+'/tc.png',
		onclick : function()
		{
			var sel = ed.dom.decode(ed.selection.getContent());
			sel = toProperCase(sel);
			ed.selection.setContent(sel);
			ed.save();
			ed.isNotDirty = true;
		}
	});
});
