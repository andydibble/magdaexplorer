<h2>Check-in</h2>

<?php echo $this->Form->create('Login', array()) ?>

<?php echo $this->Form->input('city', array('label' => 'City or location', 'div'=>array('class'=>'required'))); ?>

<?php echo $this->Form->input('venue', array()); ?>

<?php echo $this->Form->input('latitude', array('type' => 'hidden')); ?>

<?php echo $this->Form->input('longitude', array('type' => 'hidden')); ?>

<?php echo $this->Form->input('timezone', array('type' => 'hidden')); ?>

<?php echo $this->Form->end('Submit'); ?>

<script>
$(function() {		
	addressAutocomplete('#LoginCity');	
	$('#LoginCity').data('autocomplete').addListener('place_changed', function() {
		var place=$('#LoginCity').data('autocomplete').getPlace();
		var lat = place.geometry.location.lat();
		var lng = place.geometry.location.lng();
		if (lat && lng) {
			$('#LoginLatitude').val(lat);
			$('#LoginLongitude').val(lng);
			var timestamp=Math.round(Date.now()/1000);	//convert from milliseconds to seconds;
			var timezoneURL = "https://maps.googleapis.com/maps/api/timezone/json?location="+lat+","+lng+"&timestamp="+timestamp+"&key="+GOOGLE_API_KEY;
			$.getJSON(timezoneURL, result => {
				if (result.timeZoneId) {
					$('#LoginTimezone').val(result.timeZoneId);
				}	
			});
		}
	});
	
	//initialize to browser timezone.
	$('#LoginTimezone').val(jstz.determine().name());
	
});


</script>