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

// Load WDN notice javascript when available
window.addEventListener('inlineJSReady', function() {
	require(['notice']);
}, false);