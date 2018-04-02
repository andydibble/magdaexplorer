(function( $ ) {
	$.alert = function(message) {
    	if ($('#alert').length == 0) {
    		$('#container').append('<div id="alert"><p><span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span><span id="alert-message"></span></p></div>');
    		
    		$( "#alert" ).dialog({
	  		  autoOpen: false,
	  	      resizable: false,  	      
	  	      modal: true,
	  	      buttons: {
	  	        "Ok": function() {
	  	          $(this).dialog( "close" );
	  	        }  	        
	  	      }
    		});
    	}
    	
    	$('#alert-message').html(message);
    	$('#alert').dialog('open');
    };    	
	
    $.dialog = function(message, submitCallback, args) {
    	if ($('#dialog').length == 0) {
    		$('#container').append('<div id="dialog"><p><span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span><span id="dialog-message"></span><input id="dialog-input" /></p></div>');
    		
    		$( "#dialog" ).dialog({
	  		  autoOpen: false,
	  	      resizable: false,
	  	      dialogClass: "no-close",
	  	      modal: true,
	  	      buttons: {
	  	        "Submit": function() {	  	         
	  	        	$(this).dialog( "close" );
	  	        	if (typeof submitCallback == 'function') {
	  	        		var userResp = $('#dialog-input').val();
	  	        		submitCallback(true, args, userResp);
	  	        	}
	  	        },
	  	        Cancel: function() {
	  	        	$(this).dialog( "close" );
	  	        	if (typeof submitCallback == 'function') {
	  	        		submitCallback(false, args);	  	        			  	        
	  	        	}
		  	    }  	        
	  	      }
    		});
    	}
    	
    	$('#dialog-message').text(message);
    	$('#dialog').dialog('open');
    };    	  
    
	$.confirm = function(message, confirmCallback, args) {
	    	if ($('#confirm').length == 0) {
	    		$('#container').append('<div id="confirm"><p><span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span><span id="confirm-message"></span></p></div>');
	    		
	    		$( "#confirm" ).dialog({
	    		  dialogClass: "no-close",
	    		  autoOpen: false,
		  	      resizable: false,  	      
		  	      modal: true,
		  	      buttons: {
		  	        "Ok": function() {
		  	          $(this).dialog( "close" );
		  	          confirmCallback(true, args);
		  	        },
		  	        Cancel: function() {
		  	          $(this).dialog( "close" );
		  	          confirmCallback(false, args);	  	          
		  	        }
		  	      }
	    		});
	    	}
	    	
	    	$('#confirm-message').text(message);
	    	$('#confirm').dialog('open');
	    };    	   
	
    $.fn.nextElementInDom = function(selector, options) {
	        var defaults = { stopAt : 'body' };
	        options = $.extend(defaults, options);

	        var parent = $(this).parent();
	        var found = parent.find(selector);

	        switch(true){
	            case (found.length > 0):
	                return found;
	            case (parent.length === 0 || parent.is(options.stopAt)):
	                return $([]);
	            default:
	                return parent.nextElementInDom(selector);
	        }
	    };    
	    
    $.colorToHex = function(color) {	    
			var digits = /(.*?)rgb\((\d+), (\d+), (\d+)\)/.exec(color);
			if (digits) {
				var red = parseInt(digits[2]);
			    var green = parseInt(digits[3]);
			    var blue = parseInt(digits[4]);
			    
			    var rgb = blue | (green << 8) | (red << 16);
			    return digits[1] + rgb.toString(16);
			} 
			return null;
		};
	    
	$.htmlEncode = function(value) {
		//create a in-memory div, set it's inner text(which jQuery automatically encodes)
		//then grab the encoded contents back out.  The div never exists on the page.
		return $('<div/>').text(value).html();
	};

	$.htmlDecode = function(value) {
		//create a in-memory div, set it's inner text(which jQuery automatically encodes)
		//then grab the encoded contents back out.  The div never exists on the page.
		return $('<div/>').html(value).text();
	};

	$.fn.toString = function(selector) {		
		return $('<div>').append(this).clone().html();
	};
	
	$.reenableSubmits = function() {
		$('input[type=submit]').removeAttr('disabled');
	};
	
	$.fixLabelClick = function() {
		$('label[for]').click(function () {
			var el = $(this).attr('for');
			if ($('#' + el + '[type=radio], #' + el + '[type=checkbox]').attr('selected', !$('#' + el).attr('selected'))) {
				return;
			} else {
				$('#' + el)[0].focus();
			}		
		});
	};
	
	$.fn.numLines = function() {
		var lineHeight = parseInt($(this).css('line-height'));
		var height = $(this).height();
		return Math.ceil(height / lineHeight);
	};
	
	$.fn.fitTextToDiv = function(dim, dimVal) {
		if ($('#font-size-test').length == 0) {
			$('#container').append('<div style="visibility:hidden; position: absolute" id="font-size-test"></div>');
		}
		
		var fontSize = $(this).css('font-size');	
		$('#font-size-test')
			.css('font-size', fontSize)
			.css('font-family', $(this).css('font-family'))
			.text($(this).text());
		
		fontSize = parseInt(fontSize);
		
		if (dim == 'height') {
			$('#font-size-test').width($(this).width());
			$('#font-size-test').css('line-height', $(this).css('line-height'));
						
			
			if (typeof dimVal == 'undefined') {
				var dimVal = $(this).height();
			}
			var divHeight = dimVal;
			var testDivHeight = $('#font-size-test').height();
									 			
			while (divHeight < testDivHeight) {					
				$('#font-size-test').css('font-size', (--fontSize)+'px');
				var testDivHeight = $('#font-size-test').height();			
			}
			
		} else {						
			var testDivWidth = $('#font-size-test').width();
			
			if (typeof dimVal == 'undefined') {
				var dimVal = $(this).width();
			}
			var divWidth = dimVal;
			
			while (divWidth < testDivWidth) {					
				$('#font-size-test').css('font-size', (--fontSize)+'px');
				var testDivWidth = $('#font-size-test').width();
			}
		}	
		
		$(this).css('font-size', (--fontSize)+'px');
	};
	
	$.fn.unqiue = function() {
	       var result = [];
	       $.each($(this), function(i,v){
	           if ($.inArray(v, result) == -1) result.push(v);
	       });
	       return result;
	    }
}) ( jQuery );
