$(function(){  
	$('.adv-like-button').click({controller: 'adventures'}, like);		 
	$('.comment-like-button').click({controller: 'comments'}, like);
	$('.art-like-button').click({controller: 'articles'}, like);
});

function like(event) {		 	
		var displayNode = $(event.target).nextAll('.likes-display');
		$.ajax({
	        type: 'POST',
	        url: APPROOT + event.data.controller + '/like',
	        contentType: 'application/x-www-form-urlencoded',
	        data: {id:event.target.id},
	        dataType: 'json',
	        success: function(data){				
	            if (data.success) {		            		            									
					var numLikes = displayNode.text();
					displayNode.text(++numLikes);
					displayNode.show();
		       	}           
	        }
		});
	}