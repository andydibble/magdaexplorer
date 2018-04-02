<h2>Request Vacation Itinerary</h2>

<p style="font-weight:bold">As you can see from the range of Magda's trips and adventures, she is definitely a world traveler. Now Magda is offering her expertise as a travel planner.</p>

<?php echo $this->Form->create('Request', array('type' => 'post')) ?>

<div id="service-selection">	
	<?php echo $this->Element('request_type_info', array('type' => $type)); ?>		
</div>

<div class="form-entry-set">
<?php echo $this->Form->input('KnownEmail.first_name')?>
<?php echo $this->Form->input('KnownEmail.last_name')?>
<?php echo $this->Form->input('KnownEmail.email', array('div' => array('style' => 'clear:right')))?>
</div>

<?php echo $this->Form->input('general_interests', array('type' => 'textarea'))?>

<?php echo $this->Form->input('location', array(
		'type' => 'textarea'		
))?>

<?php echo $this->Form->input('other', array('type' => 'textarea', 'label' => 'Other comments, details, or questions'))?>

<?php echo $this->Form->end(); ?>


<div class="vacation-service-paypal-button">
<input class="checkout-button" type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_cart_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
</div>


<script>

$(document).ready(function() {

	$('.checkout-button').click(function() {
		$('#RequestItineraryForm').trigger('submit');		
	});			
});
</script>