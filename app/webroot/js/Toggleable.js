$(document).ready(function() {	  	
	var isMobile = $(window).width() < 650;	
	$(".hidden-content-toggler").click(function()
	{	
		
		if (isMobile) {
			var hiddenContent = $(this).nextElementInDom(".mobile-hidden-content, .hidden-content, .hidden-content-defer-hide");
		} else {
			var hiddenContent = $(this).nextElementInDom(".hidden-content, .hidden-content-defer-hide");
		}		
		
		hiddenContent.css('overflow','');
		
	
				
		if ($(hiddenContent).is(':visible')) {
			if ($(this).is(':checked')) {
				$(this).prop('checked', false);	//will not be visible shortly
			}					
		} else {
			if (!$(this).is(':checked')) {
				$(this).prop('checked', true);	//will be visible shortly
				
			}	
			hiddenContent.css('overflow','');
		}
		//console.log(hiddenContent.css('overflow'));
		
		hiddenContent.slideToggle(500, function() {
			$(this).css('overflow','');		
		});				
	});
	
	//set timeout so that content can fully render before it's hidden.
	$(".hidden-content").hide();
	if (isMobile) {
		$(".mobile-hidden-content").hide();
	}	
});

(function( $ ) {
$.fn.close = function() {
	if ($(this).is(':visible')) {
		$(this).slideToggle(500);    
	}  
};

$.fn.open = function() {
	if (!$(this).is(':visible')) {
		$(this).slideToggle(500);  		
	}
};

$.fn.getHiddenContent = function() {
	$(this).nextElementInDom(".hidden-content, .hidden-content-defer-hide");
}

}) ( jQuery );

 