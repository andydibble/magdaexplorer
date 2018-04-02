function spellCheckOnSubmit(clickSelector, formSelector, editors, editorCallback) {
	$(clickSelector).click(function(ev) {				
		if($Spelling.BinSpellCheckFields('all')){
			submitSpellcheckableForm(formSelector, editors, editorCallback);
		} else {		
			$.confirm("There are spell check errors.  Do you want to perform spell check before submit?",
				function(result) {
					if (result) {												
						var fieldsToCheck = $('textarea');	//TODO: change to body if using yui
						$.merge(fieldsToCheck, $('input[type=text]'));				
						fieldsToCheck.spellCheckInDialog({showThesaurus: false, popUpStyle:'fancybox', theme:'clean'});								
					} else {
						submitSpellcheckableForm(formSelector, editors, editorCallback);
					}
				}
			);
		}						
	});
}

function submitSpellcheckableForm(formSelector, editors, editorCallback) {
	if (typeof editorCallback != 'undefined') {
		if (typeof editors != 'undefined') {
			$(editors).each(function(ind, editor) {
				saveEditorContent(editor);
			});
		}		
	}
	$(formSelector).trigger('submit');
}

