/**
 * plugin.js
 *
 * Copyright, Sabri Ünal
 * Released under Creative Commons Attribution-NonCommercial 3.0 Unported License.
 *
 * Contributing:
 */

tinymce.PluginManager.add('videoEkle', function(editor, url) {
    //
	editor.addButton('videoEkle', {
		icon: 'media',
		tooltip: 'Video Çerçevesi Ekle',
		shortcut: 'Ctrl+H',
		onclick: function() {
			// Open window
			editor.windowManager.open({
				title: 'Video Çerçevesi Ekle',
				body: [ {type: 'textbox', name: 'title', label: 'Video Id'} ],
				onsubmit: function(e)
				{
					// Insert content when the window form is submitted
					editor.insertContent('[video=' + e.data.title + ']');
				}
			});
		}
	});

// 	kısayol ekle
// 	editor.addShortcut('Ctrl+H', '', openmanager);

});
