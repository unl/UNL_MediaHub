require(['jquery'], function($){
	$('.embed').colorbox({inline: true, href:'#sharing', width:'75%', height:'75%'});
	
	$('form#addTags').hide();
	
	$('#mediaTagsAdd a[href="#"]').click(function(){
		$(this).hide();
		$(this).siblings('form').show(function(){
			$('#new_tag').focus();
		});
		return false;
	});
});
