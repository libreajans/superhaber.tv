tinymce.init({
schema: "html5",
style_formats: [
{title: "Başlık", format: "h3"},
{title: "Koyu", format: "bold"},
{title: "Paragraf", format: "p"},
{title: "Alıntı", format: "blockquote"},
],
menubar: false,
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
convert_urls: true,
relative_urls: true,
plugins: ["haberEkle videoEkle caseSpecialTitle caseUpper caseLower caseSentence caseTitle advlist autolink image link lists preview code searchreplace visualblocks visualchars media paste fullscreen responsivefilemanager"],
toolbar: "styleselect | link unlink | bold italic blockquote | haberEkle videoEkle | responsivefilemanager image media | alignleft aligncenter alignright alignjustify | undo redo | bullist numlist outdent indent | code preview fullscreen | cut copy paste pastetext searchreplace | removeformat visualchars visualblocks | caseUpper caseLower caseSentence caseTitle caseSpecialTitle",
image_advtab: true,
entity_encoding : "raw",
external_filemanager_path:"vendors/tinymceFileManager/",
filemanager_title:"Resim ve Medya Yöneticisi" ,
external_plugins:
{
	"filemanager"	: "../tinymceFileManager/plugin.min.js",
}
});
