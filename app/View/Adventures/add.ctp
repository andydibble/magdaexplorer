<?php echo $this->Html->script('AmendableList.js', array('inline' => false)); ?>
<?php echo $this->Html->script('SpellCheck.js', array('inline' => false)); ?>

<h2>Create Adventure</h2>

<?php echo $this->Form->create('Adventure', array('type' => 'file', 'style' => 'clear:both')) ?>
<?php echo $this->Form->input('trip_id', array('label' => 'Trip', 'options' => $trips, 'default' => $tripId ? $tripId : 1)) ?>

<?php echo $this->Form->input('is_visible', array('type' => 'checkbox', 'default' => 1)); ?>
<?php echo $this->Form->input('title', array('div' => 'text-field')); ?>
<?php echo $this->Form->input('story', array('type' => 'textarea', 'maxLengeth' => '10000', 'rows' => 10)); ?>

<div id="photo-list">
	<div id="adv-photo0" class="form-entry-set">
	<?php echo $this->Form->input('Photo.0.file', array('type' => 'file', 'label' => 'Photo 1')); ?>
	<?php echo $this->Form->input('Photo.0.title'); ?>
	</div>
</div>
<br>
<br>
<br>
<?php echo $this->Form->input('city', array('div' => 'text-field', 'label' => 'City or location')); ?>
<?php echo $this->Form->input('date'); ?>

<div id="tag-list" class="form-entry-set">
<?php echo $this->Form->input('Tag.0.name', array('label' => 'Tag 1', 'div' => 'autocomplete'))?>
</div>

<div class="submit" style="clear:both">
<span class="button submit-button">Submit</span>
</div>

<?php echo $this->Form->end(); ?>

<script>
$(document).ready(function() {	
	var editors = makeEditors(['AdventureStory']);
	spellCheckOnSubmit('.submit-button', '#AdventureAddForm', editors, saveEditorContent);
	
	var newTag = <?php echo Configure::read('NEW_TAG_HTML'); ?>;
	var newPhoto = <?php echo Configure::read('NEW_PHOTO_HTML'); ?>;
		
	AmendableList.amendListListener('input[id$=0Name]', '#tag-list', newTag, <?php echo Configure::read('MAX_NUM_TAGS') ?>, ['setupAutocomplete()']);
	AmendableList.amendListListener('#adv-photo0', '#photo-list', newPhoto, <?php echo Configure::read('MAX_NUM_PHOTOS') ?>);

	addressAutocomplete('#AdventureCity');	
});
</script>



