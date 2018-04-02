<?php echo $this->Html->script('SpellCheck.js', array('inline' => false)); ?>
<h2>Edit <?php echo ucfirst($ref['type'])?> Reference</h2>

<?php echo $this->Form->create('Reference', array('style' => 'clear:both')) ?>
<?php echo $this->Form->input('trip_id', array('label' => 'Trip', 'options' => $trips)) ?>
<?php echo $this->Form->input('name', array('label' => 'Name', 'div' => 'text-field', 'default' => $ref['name'])) ?>
<?php echo $this->Form->input('id', array('default' => $ref['id'])) ?>
<?php echo $this->Form->input('description', array('label' => 'Describe this reference', 'type' => 'textarea', 'default' => $ref['description'])) ?>
<?php if($ref['type'] == 'link'):?>
<?php echo $this->Form->input('url', array('label' => 'Link Url', 'default' => $ref['url'])) ?>
<?php endif; ?>

<div class="submit" style="clear:both">
<span class="button" id="submit-button">Submit</span>
</div>
<?php echo $this->Form->end()?>

<script>
$(document).ready(function() {		
	var editors = makeEditors();
	
	spellCheckOnSubmit('#submit-button', '#ReferenceEditForm', editors, saveEditorContent);
	
});
</script>
