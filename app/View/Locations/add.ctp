<h2>Add Location</h2>

<?php echo $this->Form->create('Location', array('type' => 'file')) ?>

<p style="clear:both">
<?php foreach($formInputs as $fieldName => $options): ?>
<?php $options['label'] = !isset($options['label']) ? Inflector::humanize($fieldName) : $options['label']?>
<?php echo $this->Form->input('Location.'.$fieldName, $options) ?>
<?php endforeach; ?>
</p>

<?php echo $this->Form->end('Submit'); ?>
<?php ?>

<script>
$(document).ready(function() {	
	makeEditors();
});
</script>