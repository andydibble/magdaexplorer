var AmendableList = {

	/**
	 * Assigns a listener to the final list element of a list of input fields (denoted by lastElementSelector). This selector should contain th
	 * index of the item in the list (0-indexed).
	 * listParentSelector: denotes the parent elt of all list elts.
	 * listEltHtml: html to add for a list elt (this html should contain # where the index of the current element should be inserted and ## where the index of the elt +1 should be inserted.
	 * maxSize: max number of elts for this list
	 * onAmendActions: the list of function calls to be evaled on the creation of a new elt (such as assigning autocomplate listeners).
	 */
	amendListListener : function (lastElementSelector, listParentSelector, listEltHtml, maxSize, onAmendActions) {
		
		if ($(listParentSelector).children().length < maxSize) {		
			
			$(lastElementSelector).change(function(event) {
						
				numElts = $(listParentSelector).children().length+1;	//represents the NEW number of child divs in list.			
				var newHtml = AmendableList.createListElement(listEltHtml, numElts-1);
						
				$(listParentSelector).append(newHtml);	
		
				$(lastElementSelector).off("change");
						
				if (typeof maxSize=='undefined' || numElts < maxSize) {				
					newLastEltSelector = lastElementSelector.replace(numElts-2, numElts-1);
					AmendableList.amendListListener(newLastEltSelector, listParentSelector, listEltHtml, maxSize, onAmendActions);			
				}
				if (typeof onAmendActions!='undefined') {
					$.each(onAmendActions, function(index, value) {
						eval(value);					
					});
				}
			});
		}
	},
	
	/**
	 * Makes the template passed have its number fields filled in appropriately for the index passed (e.g. Tag 1...)
	 */
	createListElement : function(eltTmpl, index) {
		return eltTmpl.replace(/##/g, index+1).replace(/#/g, index);
	}
}

 			