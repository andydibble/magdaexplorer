/**
 * Transform all textareas denoted by the ids in the passed arr to Nic text editors.  If the passed array is empty, this funciton assumes all textareas on the page should be transformed.
 * @param textareaIdArr
 */
function makeEditors(textareaIdArr) {
	/*if ($(textareaIdArr).length == 0) {
		textareaIdArr = [];
		 $('textarea').each(function() { textareaIdArr.push(this.id); });
	}
	
	$('body').addClass('yui-skin-sam');
		
	//Setup some private variables
	var Dom = YAHOO.util.Dom,
    	Event = YAHOO.util.Event;

	//The Editor config
    var myConfig = {       
        animate: true,	                          
        autoHeight: true,	 
        handleSubmit: true,
        titlebar: '',
        draggable: false,
        buttonType: 'advanced'
    };

    var editors = [];
    $(textareaIdArr).each(function(ind, val) {    	    	
    	var myEditor = new YAHOO.widget.SimpleEditor(val, myConfig);
    	myEditor._defaultToolbar.titlebar = '';    	
	    
    	myEditor.on('toolbarLoaded', function() {
    	    this.on('afterRender', function() {    	    	    	    	
    	    	$('.yui-toolbar-subcont li:last').remove();	//remove all add image elements.
    	    	//TODO: prepend http:// to links.
    	    }, this, true);
    	}, myEditor, true);
    	myEditor.addClass('body');
    	
    	
    	myEditor.render();
    	$('iframe body').css('line-height', '25px');
    	
    	
    	editors.push(myEditor);	    
    });
    
    return editors;*/
      
		
	editorArgs = {
			buttonList : ['bold','italic','underline','left','center','right','justify','ol','ul','fontSize','fontFamily','fontFormat','indent','outdent','forecolor','bgcolor','link','unlink'],			
		};
	
	editors = [];
	if ($(textareaIdArr).length == 0) {
		textareaIdArr = [];
		$('textarea').each(function(ind, val) {
			textareaIdArr.push(val.id);
		});
		/*bkLib.onDomLoaded(function() { 
			editors = nicEditors.allTextAreas(editorArgs); 
		});*/
	} 	
	
	$(textareaIdArr).each(function(index,value) {
		editors.push(new nicEditor(editorArgs).panelInstance(value));
	});		
		
	$('.nicEdit-main').css('line-height', '25px');
	
	$('.nicEdit-panel').each(function(i, v) {
		$(v).children()
			.css('line-height', '15px')
			.css('margin', '2px 3px');
		
		$(v).children().each(function(j, u) {			
			$(u).children()
				.height(32);
				//.width(32);			
		});
	});
	
	$('.nicEdit-button')
		.height(30)
		//.width(30)
		.css('background-repeat','no-repeat');		
	
	$('.nicEdit-panelContain').draggable();
	
	return editors;
}

function saveEditorContent(editor) {	
	for(var i=0; i < editor.nicInstances.length;i++) {
		editor.nicInstances[i].saveContent();
	}
}


	