function HeaderImageCrop(forImg) {
	
	if (forImg) {
		this.cropHeight = forImg.crop_height;
		this.cropRatio = forImg.crop_ratio; //scale crop coordinates to the size width of the displayed image.
		
		this.makeCropDisplay(forImg);
		this.eventizeCropArea(this);		
	}
	
	$('#header-background-image-form').ajaxForm({								
		context: this,
		success: function(resp) {												
			if (resp.success) {											
				if ($('#header-background-img').length) {
					$('#header-background-img').attr('src', resp.img.url);					
				} else {
					this.cropHeight = resp.img.crop_height;
					this.cropRatio = resp.img.crop_ratio;
					
					if ($('#HeaderImageId').length) {
						$('#HeaderImageId').val(resp.img.id);
					}
				}
				
				if (!$('#header-background-img-crop-area').length) {
					this.makeCropDisplay(resp.img);
					this.eventizeCropArea(this);
				}
								
				$('#header-background-img-crop-area').css('top', resp.img.crop_y+'px');
				
				this.setCropYInputField(resp.img.crop_y, resp.img.crop_ratio);
			}
		}
	});

	$('#header-image-file').change(function() {
		$(this).closest('form').trigger('submit');
	});	
}

HeaderImageCrop.prototype = {
	eventizeCropArea : function(forHeaderImgCrop) {		
		//make crop area draggable.
		$('#header-background-img-crop-area').draggable({				
			containment: "#header-background-img-wrapper",
			axis: 'y',
			stop: function() {				
				forHeaderImgCrop.setCropYInputField($(this).css('top'));
			}	    		    
		});
	},
	
	setCropYInputField : function(cropAreaTop) {
		var cropY = parseFloat(cropAreaTop);			
		cropY = Math.round(cropY * this.cropRatio);		
			
		$('#background-crop-y-input').val(cropY);
	},
	
	makeCropDisplay : function(forImg) {
		$('#content').append(
				'<div>Current header background image: (drag translucent box to choose new crop region)</div>' +
					'<div id="header-background-img-wrapper" class="header-background-thumbnail">' +
					'<img id="header-background-img" src="'+forImg.url+'" />' +
					'<div id="header-background-img-crop-area"></div>' +					
				'</div>');
		
		$('#header-background-img-crop-area')
			.height(this.cropHeight/this.cropRatio)
			.css('position', 'absolute')
			.css('top', $('#background-crop-y-input').val()/this.cropRatio + 'px');
	}
};