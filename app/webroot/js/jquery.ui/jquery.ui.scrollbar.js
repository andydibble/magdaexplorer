$(function() {	//---for scrolling banner---
	    //scrollpane parts
	    //var scrollPane = $( ".scroll-pane" ),
	      //  scrollContent = $( ".scroll-content" );
	     
	    //build slider
	  //build slider
	    var horzScrollbar = $( ".horizontal-scroll-bar" ).slider({
	        slide: function( event, ui ) {
	        	
	        	var scrollPane = $(event.target).closest('.scroll-pane');
	        	var scrollContent = scrollPane.find('.scroll-content');
	        		        	
	        	if ( scrollContent.width() > scrollPane.width() ) {
	                scrollContent.css( "margin-left", Math.round(
	                    ui.value / 100 * ( scrollPane.width() - scrollContent.width() )
	                ) + "px" );
	            } else {
	                scrollContent.css( "margin-left", 0 );
	            }	        	            
	        }
	    });
	  	    
	    var vertScrollbar = 	    	
	    	$( ".vertical-scroll-bar" ).slider({
	    	      orientation: "vertical",	
	    	      value: 100,
	    	      slide: function( event, ui ) {
	  	        	
	  	        	var scrollPane = $(event.target).closest('.scroll-pane');
	  	        	var scrollContent = scrollPane.find('.scroll-content');
	  	        		        	
	  	        	if ( scrollContent.height() > scrollPane.height() ) {
	  	                scrollContent.css( "margin-top", Math.round(
	  	                    (1 - ui.value / 100) * ( scrollPane.height() - scrollContent.height() )
	  	                ) + "px" );
	  	            } else {
	  	                scrollContent.css( "margin-top", 0 );
	  	            }	        	            
	  	        }
	    });
	    
	    $('.scroll-bar').each(function(i, v) {
	    	//append icon to handle
	    	var scrollbar = $(v);
	    	
		    var handleHelper = scrollbar.find( ".ui-slider-handle" )
		    	.mousedown(function() {
		    		scrollbar.width( handleHelper.width() );
		    	})
		    	.mouseup(function() {
		    		scrollbar.width( "100%" );
		    	})
		    	.append( "<span class='ui-icon ui-icon-grip-dotted-vertical'></span>" )
		    	.wrap( "<div class='ui-handle-helper-parent'></div>" ).parent();
		     
		    //change overflow to hidden now that slider handles the scrolling
		     scrollbar.closest('.scroll-pane').css( "overflow", "hidden" );
	    });
	    
	     
	    //size scrollbar and handle proportionally to scroll distance
	    function sizeScrollbar() {
	    	 $('.scroll-bar').each(function(i, v) {
	 	    	//append icon to handle
	 	    	var scrollbar = $(v);
	 	    	var scrollPane = scrollbar.closest('.scroll-pane');
  	        	var scrollContent = scrollPane.find('.scroll-content');
	 	    	
	 	    	if (scrollbar.hasClass('vertical-scroll-bar')) {
	 	    		var remainder = scrollContent.height() - scrollPane.height();
			        var proportion = remainder / scrollContent.height();
			        var handleSize = scrollPane.height() - ( proportion * scrollPane.height() );

			        scrollbar.find( ".ui-slider-handle" ).css({
			            height: handleSize,
			            "min-height": '25px',
			            "margin-top": -handleSize / 2 
			        });
			        //handleHelper.height( "" ).height( scrollbar.height() - handleSize );
	 	    	} else {
	 	    		var remainder = scrollContent.width() - scrollPane.width();
			        var proportion = remainder / scrollContent.width();
			        var handleSize = scrollPane.width() - ( proportion * scrollPane.width() );
			        scrollbar.find( ".ui-slider-handle" ).css({
			            width: handleSize,
			            "min-width": '25px',
			            "margin-left": -handleSize / 2
			        });
			        //handleHelper.width( "" ).width( scrollbar.width() - handleSize );
	 	    	}		        
	    	 });
	    }
	     
	    //reset slider value based on scroll content position
	    function resetValue() {
	    	$('.scroll-bar').each(function(i, v) {
	 	    	//append icon to handle
	 	    	var scrollbar = $(v);
	 	    	var scrollPane = scrollbar.closest('.scroll-pane');
  	        	var scrollContent = scrollPane.find('.scroll-content');
  	        	
  	        	var margin = scrollbar.hasClass('vertical-scroll-bar') ? 'top' : 'left';
  	        	var dimFunction = scrollbar.hasClass('vertical-scroll-bar') ? scrollPane.height : scrollPane.width;
  	        	
  	        	var remainder = dimFunction() - dimFunction();
  	        	var leftVal = scrollContent.css( "margin-"+margin ) === "auto" ? 0 :
  	        		parseInt( scrollContent.css( "margin-"+margin ) );
  	        	var percentage = Math.round( leftVal / remainder * 100 );
  	        	scrollbar.slider( "value", percentage );  	      			    	        	  	      	
	    	});
	    }
	     
	    //if the slider is 100% and window gets larger, reveal content
	    function reflowContent() {
	    	$('.scroll-bar').each(function(i, v) {
	 	    	
	    		//append icon to handle
	 	    	var scrollbar = $(v);
	 	    	var scrollPane = scrollbar.closest('.scroll-pane');
  	        	var scrollContent = scrollPane.find('.scroll-content');   
  	        	
  	        	var margin = scrollbar.hasClass('vertical-scroll-bar') ? 'top' : 'left';
  	        	var dimFunction = scrollbar.hasClass('vertical-scroll-bar') ? scrollPane.height : scrollPane.width;
	    			    				    		 		    
  	        	var showing = dimFunction() + parseInt( scrollContent.css( "margin-"+margin ), 10 );
	            var gap = dimFunction() - showing;
	            if ( gap > 0 ) {
	                scrollContent.css( "margin-top", parseInt( scrollContent.css( "margin-"+margin ), 10 ) + gap );
	            }	    		
	    	});
	    }
	     
	    //change handle position on window resize
	    $( window ).resize(function() {
	        //resetValue();
	        sizeScrollbar();
	        //reflowContent();
	    });
	    //init scrollbar size	    
	    setTimeout( sizeScrollbar, 10 );//safari wants a timeout
});