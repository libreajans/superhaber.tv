/**
 * plugin.js
 *
 * Copyright, Sabri Ünal
 * Released under Creative Commons Attribution-NonCommercial 3.0 Unported License.
 *
 * Contributing:
 */

tinymce.PluginManager.add('haberEkle', function(editor, url)
{
    //
	editor.addButton('haberEkle', {
		icon: 'anchor',
		tooltip: 'Haber Çerçevesi Ekle',
		shortcut: 'Ctrl+H',
		onclick: function() {
			// Open window
			editor.windowManager.open({
				title: 'Haber Çerçevesi Ekle',
				body: [ {type: 'textbox', name: 'title', label: 'Haber Id'} ],
				onsubmit: function(e)
				{
					// Insert content when the window form is submitted
					editor.insertContent('[haber=' + e.data.title + ']');
				}
			});
		}
	});

// 	kısayol ekle
// 	editor.addShortcut('Ctrl+H', '', openmanager);
});
