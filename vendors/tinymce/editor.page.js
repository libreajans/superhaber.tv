tinymce.init({
schema: "html5",
statusbar: true,
extended_valid_elements : "div[class|style|src|alt|title|hspace|vspace|width|height|align|onmouseover|onmouseout|name],img[style|class|src|border=0|alt|title|hspace|vspace|width|height|align|onmouseover|onmouseout|name]",
invalid_elements : "form, textarea, header, footer, section, span, h1, h2, h4, h5, h6, hr",
mode : "specific_textareas",
editor_selector : "mceEditor",
theme: "modern",
language : 'tr_TR',
width: "100%",
height: 250,
browser_spellcheck : true,
gecko_spellcheck : true,
plugins: [
"advlist autolink lists link image charmap print preview hr anchor pagebreak",
"searchreplace wordcount visualblocks visualchars code fullscreen media table",
"emoticons template paste textcolor colorpicker responsivefilemanager"
],
toolbar1: "styleselect | responsivefilemanager image media | link unlink | bold italic blockquote | alignleft aligncenter alignright alignjustify | undo redo | bullist numlist outdent indent  | forecolor backcolor emoticons | code preview fullscreen | cut copy paste pastetext searchreplace | removeformat visualchars visualblocks | underline strikethrough subscript superscript",
image_advtab: true,
entity_encoding : "raw",
external_filemanager_path:"vendors/tinymceFileManager/",
filemanager_title:"Resim ve Medya Yöneticisi" ,
external_plugins: { "filemanager" : "../tinymceFileManager/plugin.min.js"}
});
