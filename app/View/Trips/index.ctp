<?php echo $this->Element('above_main_content'); ?>

<div id="main-content-container" class="row">

	<?php echo $this->Element('sidebar'); ?>


	<div id="main-content" class="col-9">

		<?php foreach ($adventures as $adv): ?>
			<?php echo $this->Element('adventure', array('adv' => $adv)) ?>
		<?php endforeach; ?>

		<div class="paging-parent">
			<?php echo $this->Element('paging', array('model' => 'Adventure', 'label' => 'Adventures in this Trip:')) ?>

			<?php echo $this->Element('footer_trip_nav', array('location' => $locationName, 'tripIds' => $tripNavIds)); ?>
		</div>

	</div>

</div>

<?php echo $this->Element('photo_dialog') ?>

<?php echo $this->Html->scriptStart(array('inline' => false)) ?>

$(document).ready(function() {    //for toggling content

$('#trip-header-parent .hidden-content-toggler').trigger('click');    //have trip header start open
$(window).trigger('resize');

var loadOpen = <?php echo $loadOpen ?>;
if (loadOpen) {
$('#'+loadOpen).each(function(i,v) {
$(v).trigger('click');
});
}

var onloadMessage = '<?php echo $onloadMessage ?>';
if (onloadMessage) {
$.alert(onloadMessage);
}

//set default text for email field.
var emailField = $('#KnownEmailEmail');
emailField.attr('class', 'default-text');
emailField.val('Email');
emailField.focus(function() {
emailField.val('');
emailField.attr('class', 'focused-field');
emailField.off('focus');
});

//set click event for all photos
$('.scroll-content-item, .adv-photo').click(function() {

var dialogImg = $(this).find('img');
var imgSrc = dialogImg.attr('src');
var parts = imgSrc.split('/');
var imgName = parts[parts.length-1];
var bannerImg = $('.scroll-content-item img[src*="'+imgName+'"]');

var bannerImgDiv = bannerImg.parent();
var bannerImgInd = $('.scroll-content-item').index(bannerImgDiv[0]);

openPhotoDialog(dialogImg, bannerImgInd);
});
});

<?php echo $this->Html->scriptEnd() ?>

	
	

	