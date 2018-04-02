<?php echo $this->Html->script('HeaderImageCrop.js'); ?>

<h2>Add Trip</h2>

<?php echo $this->Form->create('Trip', array('type' => 'file')) ?>

<p style="clear:both">
<?php echo $this->Form->input('Trip.location_id', array('label' => 'For location', 'options' => $locOptions, 'default' => $forLocId)); ?>

<?php foreach($formInputs as $fieldName => $options): ?>
<?php $options['label'] = !isset($options['label']) ? Inflector::humanize($fieldName) : $options['label']?>
<?php echo $this->Form->input('Trip.'.$fieldName, $options) ?>
<?php endforeach; ?>

<?php echo $this->Form->input('HeaderImage.id', array('type' => 'hidden')) ?>
<?php echo $this->Form->input('HeaderImage.crop_y', array('type' => 'hidden', 'id' => 'background-crop-y-input')) //for changing crop position before upload. ?>

</p>

<?php echo $this->Form->end('Submit'); ?>

<?php echo $this->Form->create('HeaderImage', array('type' => 'file', 'id' => 'header-background-image-form', 'action' => 'add')) //for ajax upload of background image ?>
<?php echo $this->Form->input('filename', array('type' => 'file', 'label' => 'Header Background Image (.jpg, .png, or .gif)', 'id' => 'header-image-file')) ?>
<?php echo $this->Form->input('crop_banner_image',  array('type' => 'checkbox', 'label' => 'Crop Image equally on all sides to fit banner dimensions (i.e. "zoom in" on image center)?', 'default' => 1)) ?>
<?php echo $this->Form->end(); ?>

<script>
$(document).ready(function() {
	addressAutocomplete('#check-in-city-field');
	makeEditors();

	var headerImgCrop = new HeaderImageCrop();	
	
});
</script>