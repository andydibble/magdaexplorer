<h2>Edit Site Fields</h2>

<?php echo $this->Form->create('Text', array('type' => 'file', 'style' => 'clear:both')) ?>

<?php foreach($inputTexts as $i => $text): ?>
<?php echo $this->Form->input($i.'.Text.id', array('type' => 'hidden', 'default' => $text['Text']['id'])); ?>
<?php if ($text['Text']['name'] != 'homepage_location'): ?> 
<?php echo $this->Form->input($i.'.Text.value', array('type' => $text['Text']['type'], 'label' => Inflector::humanize($text['Text']['name']), 'default' => $text['Text']['value'], 'id' => $text['Text']['name'].'_field')); ?>
<?php else: //for homepage_location?>
<?php echo $this->Form->input($i.'.Text.value', array('options' => $homePageLocationOptions, 'label' => Inflector::humanize($text['Text']['name']), 'selected' => $text['Text']['value'], 'id' => $text['Text']['name'].'_field')); ?>
<?php endif; ?>
<?php endforeach; ?>

<?php echo $this->Form->end('Submit'); ?>
<?php ?>



<script>
$(document).ready(function() {
	makeEditors();
	addressAutocomplete('#check_in_city_field');
});
</script>