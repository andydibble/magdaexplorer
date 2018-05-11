<?php $displayLoc = isset($location) && !$location['is_dummy_location'] ?>

<div id="above-banner-expandable-elements">
	<div class="expandable-page-elements row">
		<div class="page-element-parent no-mobile col-3" id="check-in-location-map-parent">
			<div id="check-in-location-map"></div>
		</div>

		<div class="col-9" id="trip-header-parent">
			<?php echo $this->Element('trip_header', array(
				'name' => $displayLoc ? $location['name'] : $tripDisplayName,
				'texts' => $displayLoc ? $location : $texts,
				'type' => $displayLoc ? 'Location' : 'Trip',
				'id' => $displayLoc ? $locationId : $tripId,
				'createAdvForTripId' => $tripId,
				'location' => isset($location) ? $location : null
			)); ?>
		</div>
	</div>
		<?php $displayPollOnTop = !$displayLoc && !empty($texts['poll_prompt']); ?>
		
	<div class="row" id="below-location-map-row">
		<div class="<?php echo $displayPollOnTop ? "col-6" : "col-12" ?>" id="adv-titles-parent">
			<?php echo $this->Element('adventure_titles'); ?>
		</div>

		<?php if ($displayPollOnTop): ?>
			<div id="poll" class="col-6">
				<?php echo $this->Element('poll'); ?>
			</div>
		<?php endif; ?>
		</div>
	</div>
</div>

<?php echo $this->Element('photo_banner'); ?>

<?php if ($displayLoc): //trip details are below banner if location details are present to be above the banner ?>
	<div id="sub-banner-expandable-elements row">
		<div class="expandable-page-elements col-12">			
			<div class="col-7" id="trip-header-parent">
				<?php echo $this->Element('trip_header', array(
					'name' => $tripDisplayName,
					'title' => 'Trip Details',
					'type' => 'Trip',
					'id' => $tripId,
					'createAdvForTripId' => $tripId
				)); ?>
			</div>

			<?php if (!empty($texts['poll_prompt'])): ?>
				<div id="poll" class="col-5">
					<?php echo $this->Element('poll'); ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
<?php endif; ?>

<script>
var explorerLoc = <?php echo $explorerLocInfo ?>;
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