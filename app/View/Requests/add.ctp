<h2>What kinds of services do you want from Magda?</h2>

<p style="font-weight:bold">As you can see from the range of Magda's trips and adventures, she is definitely a world traveler. Now Magda is offering her expertise as a travel planner.</p>

<?php echo $this->Form->create('Request', array('type' => 'post')) ?>

<div id="service-selection">
	<?php foreach($types as $i => $type): ?>
	<?php $type = $type['RequestType']; ?>
	
	<div class="page-element-parent">
	
	<div class="vacation-service-desc">
		<?php if($type['price'] > 0.00):?>
			<b>$<?php echo $type['price'].' ('.$type['payment_type'].')' ?> : </b>
		<?php endif; ?>
		<?php echo $type['description']?>
	</div>
	<?php echo $this->Form->input("RequestType.$i.request_type_id", array(
			'label' => $type['long_name'],
			'type' => 'checkbox',
			'value' => $type['id'],
			'id' => $type['name'].'-type-input',
			'service' => $type['name']			
			)); 
	?>
	
	</div>
	<?php endforeach; ?>
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

<?php echo $this->Form->input('questions_or_comments', array('type' => 'textarea'))?>
<br>
<br>

<?php echo $this->Form->end('Submit'); //TODO:case where location is empty but required ?>


<div class="vacation-service-paypal-button">
<input class="checkout-button" type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_cart_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
</div>


<script>

$(document).ready(function() {
	$('#RequestLocation, #RequestGeneralInterests, #RequestQuestionsOrComments, .checkout-button').parent().hide();
	onItineraryServiceChange();
	onDatabaseServiceChange();
	onContactServiceChange();
	
	$('#itinerary-type-input').change(function() {
		onItineraryServiceChange();
	});

	$('#database-type-input').change(function() {
		onDatabaseServiceChange();
	});

	$('#contact-magda-type-input').change(function() {
		onContactServiceChange();
	});
	
	$('.checkout-button').click(function() {
		$('#RequestAddForm').trigger('submit');		
	});			
});

function onItineraryServiceChange() {
	if ($('#itinerary-type-input').is(':checked')) {
		$('#RequestLocation, #RequestGeneralInterests'/* .checkout-button'*/).parent().show('slow');
		//$('input[type=submit]').parent().hide('slow');						
	} else {
		$('#RequestLocation, #RequestGeneralInterests').parent().hide('slow');
		if (!$('#database-type-input').is(':checked')) {
			//$('.checkout-button').parent().hide('slow');
			/*if ($('#contact-magda-type-input').is(':checked')) {
				$('input[type=submit]').parent().show('slow');
			}*/
		}
	}
}

function onDatabaseServiceChange() {
	
	if ($('#database-type-input').is(':checked')) {
		//$('.checkout-button').parent().show('slow');						
		//$('input[type=submit]').parent().hide('slow');
	} else if (!$('#itinerary-type-input').is(':checked')) {
		/*$('.checkout-button').parent().hide('slow');
		if ($('#contact-magda-type-input').is(':checked')) {
			$('input[type=submit]').parent().show('slow');
		}*/
	} 
	
}

function onContactServiceChange() {
	if ($('#contact-magda-type-input').is(':checked')) {
		$('#RequestQuestionsOrComments').parent().show('slow');
		/*if (!$('#database-type-input').is(':checked') && !$('#itinerary-type-input').is(':checked')) {
			$('input[type=submit]').parent().show('slow');			
		} else {
			$('input[type=submit]').parent().show('hide');
		}*/
							
	} else {
		$('#RequestQuestionsOrComments'/*, input[type=submit]'*/).parent().hide('slow');
	}
}

/*$('#service-selection input[type=checkbox]').each(function(i,v) {				
	if($(v).is(':checked')) {
		var service = $(v).attr('service');

		setTimeout(function() {$('.add-to-cart-button[service='+service+']').trigger('click');}, timeout);
	}
	timeout += 500;
});*/


</script>