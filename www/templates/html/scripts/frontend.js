require(['jquery'], function($) {
	
	$('form#addTags').hide();
	
	$('#mediaTagsAdd a[href="#"]').click(function() {
		$(this).hide();
		$(this).siblings('form').show(function() {
			$('#new_tag').focus();
		});
		return false;
	});
});

function loadCSS(file) {
	var fileref = document.createElement('link');
	fileref.rel = 'stylesheet';
	fileref.type = 'text/css';
	fileref.href = file;
	document.getElementsByTagName('head')[0].appendChild(fileref);
}
