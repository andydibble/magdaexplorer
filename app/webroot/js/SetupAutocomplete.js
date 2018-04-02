$(function(){  	//setup autocomplete when page loads.
	//addressAutocomplete();
	setupAutocomplete();	
});

function addressAutocomplete(selector) {		 		
	/*if (typeof selector=='undefined') { 		
		selector = 'textarea[name*=address]';
	}	
	$(selector).geo_autocomplete(new google.maps.Geocoder, {
	        mapkey: 'ABQIAAAAbnvDoAoYOSW2iqoXiGTpYBTIx7cuHpcaq3fYV4NM0BaZl8OxDxS9pQpgJkMv0RxjVl6cDGhDNERjaQ',
	        selectFirst: false,
	        minChars: 3,
	        cacheLength: 50,
	        width: 300,
	        scroll: true,
	        scrollHeight: 330
	    }).result(function (_event, _data) {		       
    });*/
}

function setupAutocomplete($models) {
	
	if (typeof $models=='undefined') {
		$models = ['Tag'];
	}
		
	var plurals = {'Tag':'tags'};	
	
	$.each($models, function(index, field)	{		
		//attach autocomplete  
		var autocompFields = $("div.autocomplete > input[id$=Name]");	//TODO: if using models other than Tag for this will need a new selector. 
		//autocompFields = autocompFields.$('.autocomplete');
		if (autocompFields.length > 0)
		{										
			autocompFields.autocomplete({  		
		        //define callback to format results  
		        source: function(req, add){  		
		        		
					//pass request to server				
					var url = APPROOT + plurals[field] + '/autocomplete';
					
		            $.getJSON(url, req, function(data) {		                
		            	add(data);  
		            });  
		    	},
		        select: function(e, ui)
		        {    		
		    		$("input[id$=" + field + "Id]").val(ui.item.id);		    
		    		
		    		$("div.autocomplete > input[id$=Name]").trigger('change');
		    		
		    		//call a custom function defined on given page to act onSelect event.
		    		if (typeof(onAutocompleteSelect) == 'function')
		    		{
		    			onAutocompleteSelect();
		    		}
		        }   
		    });										
		}
	});
}
