<?php $displayLoc = isset($location) && !$location['is_dummy_location'] ?>

<div id="above-banner-expandable-elements">
	<div class="expandable-page-elements row">
		<div class="page-element-parent no-mobile col-3" id="check-in-location-map-parent">
			<div id="check-in-location-map"></div>
		</div>

		<div class="page-element-parent right-side-page-element" id="trip-header-parent">
			<?php echo $this->Element('trip_header', array(
				'name' => $displayLoc ? $location['name'] : $tripDisplayName,
				'texts' => $displayLoc ? $location : $texts,
				'type' => $displayLoc ? 'Location' : 'Trip',
				'id' => $displayLoc ? $locationId : $tripId,
				'createAdvForTripId' => $tripId,
				'location' => isset($location) ? $location : null
			)); ?>
		</div>

		<div class="page-element-parent right-side-page-element" id="adv-titles-parent">
			<?php echo $this->Element('adventure_titles'); ?>
		</div>

		<?php if (!$displayLoc && !empty($texts['poll_prompt'])): ?>
			<div id="poll" class="page-element-parent right-side-page-element">
				<?php echo $this->Element('poll'); ?>
			</div>
		<?php endif; ?>
	</div>
</div>

<?php echo $this->Element('photo_banner'); ?>

<?php if ($displayLoc): //trip details are below banner if location details are present to be above the banner ?>
	<div id="sub-banner-expandable-elements">
		<div class="expandable-page-elements">			
			<div class="page-element-parent right-side-page-element col-8" id="trip-header-parent">
				<?php echo $this->Element('trip_header', array(
					'name' => $tripDisplayName,
					'title' => 'Trip Details',
					'type' => 'Trip',
					'id' => $tripId,
					'createAdvForTripId' => $tripId
				)); ?>
			</div>

			<?php if (!empty($texts['poll_prompt'])): ?>
				<div id="poll" class="page-element-parent right-side-page-element">
					<?php echo $this->Element('poll'); ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
<?php endif; ?>

<div id="main-content-container">

	<?php echo $this->Element('sidebar'); ?>


	<div id="main-content">

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

var explorerLoc = <?php echo $explorerLocInfo ?>;

<?php echo $this->Html->scriptEnd() ?>

<script>
      function initMap() {
        var loc = {lat: parseFloat(explorerLoc.latitude), lng: parseFloat(explorerLoc.longitude)};
				
        var map = new google.maps.Map(document.getElementById('check-in-location-map'), {
          zoom: 10,
          center: loc
        });
        var marker = new google.maps.Marker({
          position: loc,
          map: map
        });
      }
 </script>
<script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCdTgvWsM2hWoz-BEYMOo3FFjpxmRWPB_0&callback=initMap">
</script>

	
	

	