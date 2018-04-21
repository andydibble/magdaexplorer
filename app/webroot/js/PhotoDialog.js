$(document).ready(function() {
	if($.browser.msie){		
		$('#photo-dialog').css('left', '25px');		
	}

	$('#photo-dialog-titlebar-close').click(closePhotoDialog);

	$('#photo-dialog').draggable();
	
	//like event handler.
	$('#photo-dialog-like-button').click(function(event) {		 

		var recordIndex = $('#photo-dialog').find('img').attr('record-index');
				
		$.ajax({
	        type: 'POST',
	        url: APPROOT+'photos/like',
	        contentType: 'application/x-www-form-urlencoded',
	        data: {id:recordIndex},
	        dataType: 'json',
	        success: function(data){				
	            if (data.success) {		            		            									
	            	displayNode = $('#photo-dialog').find('.likes-display');					
					var numLikes = displayNode.text();
					numLikes++;
					displayNode.text(numLikes);					

					//also update node on page (if exists).
					pageDisplayNode = $('#photo'+recordIndex).find('.likes-display');
					pageDisplayNode.text(numLikes);
					pageDisplayNode.show();

					//incr num likes in the source img on the page so that subsequent viewigns before page refresh are accurate.
					getBannerImg(dialogPhotoBannerIndex()).attr('likes', numLikes);
					$('#photo'+recordIndex).find('img').attr('likes', numLikes);
					displayNode.parent().show();
		       	}           
	        }
		});
	});		
});

function closePhotoDialog() {
	$('#photo-dialog').find('img').remove();
	$('#photo-dialog-titlebar').find('span').text('');
	$('#photo-dialog').hide();
}

function advancePhotoDialog() {		
	unbindArrowButtonEvents();
		
	var bannerIndex = dialogPhotoBannerIndex();

	if (bannerSize()-1 > bannerIndex) {
		nextIndex = bannerIndex+1;
	} else {
		nextIndex = 0;
	}
			
	var nextImg = getBannerImg(nextIndex);		
	openPhotoDialog(nextImg, nextIndex);		
}

function reversePhotoDialog() {		
	unbindArrowButtonEvents();

	var bannerIndex = dialogPhotoBannerIndex();

	if (0 < bannerIndex) {
		prevIndex = bannerIndex-1;
	} else {
		prevIndex = bannerSize()-1;
	}
								
	var prevImg = getBannerImg(prevIndex);		
	openPhotoDialog(prevImg, prevIndex);		
}

/**
 * Also centers the image in the dialog.
 */
function updateDialogHeight() {	
	var headerHeight = $('#photo-dialog img').offset().top - $(document).scrollTop();// - $('#photo-dialog img').get(0).naturalHeight;
	$('#photo-dialog img')
		.css('max-height', (screen.height-headerHeight)-(200*screen.height/768)+'px')
		.css('margin-left', (($('#photo-dialog').width()-$('#photo-dialog img').width())/2)+'px');	
}

function openPhotoDialog(dialogImg, bannerImgInd) {	
	
	if ($('#photo-dialog').is(':visible')) {
		closePhotoDialog();
	}
	
	$('#photo-dialog-titlebar').find('span').text(dialogImg.attr('alt'));
	dialogImg = dialogImg.clone();
	dialogImg.attr('banner-index', bannerImgInd);		//set the img index, so that the next img can be retrieved
	dialogImg.removeAttr('title');

	//set the width of the titlebar to coincide with that of the image
	$(dialogImg).load(function() {
		//imgWidth = $(this).width();
		var titlebar = $('#photo-dialog-titlebar');				
		updateDialogHeight();
		
	});

	//update likes display for this photo
	var likes = dialogImg.attr('likes');
	var likesDisplay = $('#photo-dialog').find('.likes-display');
	
	likesDisplay.text(likes);
	if (likes && likes != '0') {
		likesDisplay.parent().show();		
	} else {
		likesDisplay.parent().hide();
	}
	
	//update adv link for this photo	
	var advTitle = dialogImg.attr('adv-title');
	if (advTitle) {
		var advId = dialogImg.attr('adv-id');
		var advLink = $('#photo-dialog-adv-link');
		advLink.attr('href', APPROOT+'trips/index/'+tripId+'/adv'+advId+'#adv'+advId);
		advLink.text(advTitle);		
		likesDisplay.show();
	} else {
		likesDisplay.hide();
	}

	bindArrowButtonEvents();
	
	$('#dialog-photo-parent').append(dialogImg);	
	
	$('#photo-dialog').show();		
	
}

function dialogPhotoBannerIndex() {
	return parseInt($('#photo-dialog').find('img').attr('banner-index'));
}

function bannerSize() {
	return $('#banner .scroll-content-item').size();
}

function getBannerImg(index) {
	return $($('.scroll-content-item')[index]).find('img');
}

function bindArrowButtonEvents() {
	advButton = $('#photo-dialog-advance');
	advButton.click(advancePhotoDialog);
	
	revButton = $('#photo-dialog-reverse');
	revButton.click(reversePhotoDialog);
}

function unbindArrowButtonEvents() {

	advButton = $('#photo-dialog-advance');
	advButton.unbind('click');

	revButton = $('#photo-dialog-reverse');
	revButton.unbind('click');
}