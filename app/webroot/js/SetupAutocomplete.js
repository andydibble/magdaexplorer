

$(function(){  	//setup autocomplete when page loads.
	//addressAutocomplete();
	setupAutocomplete();	
});

function addressAutocomplete(selector) {			
	if (typeof selector=='undefined') { 		
		selector = 'textarea[name*=address]';
	}	
	
	var params = {			
			types: ["geocode"]
		}
		
	
	$(selector).each(function(i,v) {
		var autocomplete = new google.maps.places.Autocomplete(v,params);
		$(v).data("autocomplete",autocomplete);
	});	
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
