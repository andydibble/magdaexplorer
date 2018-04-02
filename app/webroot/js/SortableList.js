/**
 * assigns all child divs of element denoted by parentEltSelector to be sortable (drag and drop).
 * This involves changing the orderField value to the new index of the element in the list (after sorted) 
 * and the label of the element in question to have a number with the index+1. 
 * @param orderField optional (default 'order')
 * @param labelText optional 
 * @param parentEltSelector (default '#sortableList')
 */
function reorderOnSort(orderField, labelText, parentEltSelector) {	//reorder experiences when they are dragged.

	if(typeof orderField=='undefined') {
		orderField='order';		
	}
	if(typeof parentEltSelector=='undefined') {
		parentEltSelector='#sortable-list';		
	}	
	$(parentEltSelector).sortable({ opacity: 0.6, cursor: 'move', update: function() {
		$('input[name*='+orderField+']').each(function(index,value) {				
			$(this).attr('value', index);
		});
		
		if (typeof labelText!='undefined') {
			listEltLabels = $('label:contains('+labelText+')').filter(function() {
				regex='([0-9]+)$';								
				return $(this).text().match(regex);
			});						  
			
			listEltLabels.each(function(index,value) {
			$(this).text(labelText+' '+(index+1));			
			});
		}	
	}});	
}