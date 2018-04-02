<?php echo $this->Html->css('jquery/spectrum.css'); ?>

<?php echo $this->Html->script('jquery/spectrum.js', array('inline' => false)); ?>
<?php echo $this->Html->script('HeaderImageCrop.js', array('inline' => false)); ?>

<?php $loc = $trip['Location']; ?>
<?php $img = $trip['HeaderImage']; ?>
<?php $trip = $trip['Trip']; ?>


<h2>Edit <?php echo $trip['display_name'] ?> Trip</h2>

<?php if(!$loc['is_dummy_location']): ?>
<div style="clear:both">
<?php echo $this->Form->create('Trip', array('action' => 'delete', 'type' => 'file')) ?>
<?php echo $this->Form->input('Delete', array('type' => 'submit', 'onclick', 'label' => ''))?>
<?php echo $this->Form->end(); ?>
</div>
<?php endif; ?>


<?php echo $this->Form->create('Trip', array('type' => 'file')) ?>

<?php echo $this->Form->input('Trip.id'); ?>

<?php if(!$loc['is_dummy_location']): ?>
<?php echo $this->Form->input('Trip.location_id', array('label' => 'For location', 'options' => $locOptions)); ?>
<?php endif; ?>

<?php foreach($formInputs as $fieldName => $options): ?>
<?php $options['label'] = !isset($options['label']) ? Inflector::humanize($fieldName) : $options['label']?>
<?php echo $this->Form->input('Trip.'.$fieldName, $options) ?>
<?php endforeach; ?>

<?php echo $this->Form->input('HeaderImage.id', array('type' => 'hidden')) ?>
<?php echo $this->Form->input('HeaderImage.crop_y', array('type' => 'hidden', 'id' => 'background-crop-y-input')) //for changing crop position before upload. ?>

<?php echo $this->Form->end('Submit'); ?>


<?php echo $this->Form->create('HeaderImage', array('type' => 'file', 'id' => 'header-background-image-form', 'action' => 'add')) //for ajax upload of background image ?>
<?php echo $this->Form->input('filename', array('type' => 'file', 'label' => 'Header Background Image (.jpg only)', 'id' => 'header-image-file')) ?>
<?php echo $this->Form->input('crop_banner_image',  array('type' => 'checkbox', 'label' => 'Crop Image equally on all sides to fit banner dimensions (i.e. "zoom in" on image center)?', 'default' => 1)) ?>
<?php echo $this->Form->input('trip_id', array('type' => 'hidden', 'default' => $trip['id'])) ?>
<?php echo $this->Form->input('id', array('type' => 'hidden')) ?>
<?php echo $this->Form->end(); ?>

<script>
$(document).ready(function() {
	addressAutocomplete('#check-in-city-field');
	makeEditors();

	$('#TripDeleteForm').submit(function(ev) {
		ev.preventDefault();	//cancel submit so confirm is displayed.
		$.confirm(
			'Are you sure you want to *permanently* delete this Trip and all associated Adventures?  If you only wish to make it invisible to users, uncheck the Is Visible field.',
			 function(result) {
				if (result) {
					$('#TripDeleteForm')	//resubmit
						.unbind('submit')
						.trigger('submit');	
				} else {					
					$.reenableSubmits(); 				
				}
			}
		);
	});
		
	var headerImgCrop = new HeaderImageCrop(<?php echo isset($img['url']) ? json_encode($img) : null; ?>);	
});
</script>
