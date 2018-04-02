<?php $loc = $loc['Location']; ?>
<h2>Edit <?php echo $loc['name'] ?> Location</h2>

<div class="actions">
<?php echo $this->Html->link('Create Trip for '.$loc['name'], '/trips/add/'.$loc['id'] )?>
</div>

<div style="clear:both">
<?php echo $this->Form->create('Location', array('action' => 'delete')) ?>
<?php echo $this->Form->input('Delete', array('type' => 'submit', 'onclick', 'label' => ''))?>
<?php echo $this->Form->end(); ?>
</div>

<?php echo $this->Form->create('Trip', array('type' => 'file')) ?>

<?php echo $this->Form->input('Location.id'); ?>
<?php foreach($formInputs as $fieldName => $options): ?>
<?php $options['label'] = !isset($options['label']) ? Inflector::humanize($fieldName) : $options['label']?>
<?php echo $this->Form->input('Location.'.$fieldName, $options) ?>
<?php endforeach; ?>
<?php ?>


<?php echo $this->Form->end('Submit'); ?>



<script>
$(document).ready(function() {
	addressAutocomplete('#check-in-city-field');
	makeEditors();

	$('#LocationDeleteForm').submit(function(ev) {
		ev.preventDefault();	//cancel submit so confirm is displayed.
		$.confirm(
			'Are you sure you want to *permanently* delete this Location and all associated Trips?  If you only wish to make it invisible to users, uncheck the Is Visible field.',
			 function(result) {
				if (result) {
					$('#LocationDeleteForm')	//resubmit
						.unbind('submit')
						.trigger('submit');	
				} else {					
					$.reenableSubmits(); 				
				}
			}
		);
	});
	
});
</script>
