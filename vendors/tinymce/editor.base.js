tinymce.init({
schema: "html5",
menubar: false,
statusbar: false,
style_formats: [
{title: "Başlık", format: "h3"},
{title: "Koyu", format: "bold"},
{title: "Paragraf", format: "p"},
{title: "Alıntı", format: "blockquote"},
],
extended_valid_elements : "p[style],h4[style],img[style|src|alt|width|height|align]",
invalid_elements : "form, textarea, header, footer, section, span, h1, h2, h4, h5, h6, hr",
mode : "specific_textareas",
editor_selector : "mceEditorSimple",
theme: "modern",
language : 'tr_TR',
width: "700px",
height: 120,
browser_spellcheck : true,
gecko_spellcheck : true,
plugins: [ "advlist image link lists preview hr code searchreplace wordcount visualblocks visualchars media table paste textcolor fullscreen" ],
toolbar: "styleselect | link unlink | bold italic blockquote | alignleft aligncenter alignright alignjustify | cut copy paste searchreplace | undo redo | bullist numlist outdent indent | code preview fullscreen",
image_advtab: true,
entity_encoding : "raw",
external_filemanager_path:"vendors/tinymceFileManager/",
filemanager_title:"Resim ve Medya Yöneticisi" ,
external_plugins: { "filemanager" : "../tinymceFileManager/plugin.min.js"}
});
