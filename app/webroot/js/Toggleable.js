$(document).ready(function() {	  		
	$(".hidden-content-toggler").click(function()
	{	
		var hiddenContent = $(this).nextElementInDom(".hidden-content, .hidden-content-defer-hide");
				
		if ($(hiddenContent).is(':visible')) {
			if ($(this).is(':checked')) {
				$(this).prop('checked', false);	//will not be visible shortly
			}					
		} else {
			if (!$(this).is(':checked')) {
				$(this).prop('checked', true);	//will be visible shortly
			}
		}
		
		hiddenContent.slideToggle(500);				
	});
	
	//set timeout so that content can fully render before it's hidden.
	setTimeout(function() {$(".hidden-content").hide();}, 100);	
	//$(".hidden-content").hide();	//only hide hidden content that does not have the -defer-hide class.
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

 