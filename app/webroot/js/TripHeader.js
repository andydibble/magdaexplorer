$(document).ready(function() {
	$('.hug-button').click(function(event) {		
		$.ajax({
	        type: 'GET',
	        url: APPROOT+'locations/hug',
	        data: { id: $(event.target).attr('record-id') },
	        dataType: 'json',
	        success: function(data){				
	            if (data.success) {
	            	var statSelector = '#'+data.model.toLowerCase() + '-hugs-stat';			            		            									
					$(statSelector).text(parseInt($(statSelector).text())+1).css("background","yellow");	
						
		       	}		       	     
	        }
		});
		return false;
	});
});