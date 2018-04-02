<?php echo $this->Html->script('SpellCheck.js', array('inline' => false)); ?>

<h2>Make a New Reference</h2>

<h3>Upload a Document</h3>
<?php echo $this->Form->create('Reference', array('type' => 'file', 'id' => 'DocumentAddForm')) ?>
<?php echo $this->Form->input('trip_id', array('label' => 'Trip', 'options' => $trips)) ?>
<?php echo $this->Form->input('name', array('label' => 'Name (if empty, the document name will be used)', 'div' => 'text-field')) ?>
<?php echo $this->Form->input('file', array('label' => 'Document', 'type' => 'file')) ?>
<?php echo $this->Form->input('description', array('label' => 'Describe this reference', 'id' => 'document-description', 'type' => 'textarea')) ?>

<div class="submit" style="clear:both">
<span class="button" id="document-submit-button">Submit</span>
</div>

<?php echo $this->Form->end()?>

<h3>Record a Link</h3>
<?php echo $this->Form->create('Reference', array('type' => 'file', 'id' => 'LinkAddForm')) ?>
<?php echo $this->Form->input('trip_id', array('label' => 'Trip', 'options' => $trips)) ?>
<?php echo $this->Form->input('name', array('label' => 'Name (if empty, the link url will be used)', 'div' => 'text-field')) ?>
<?php echo $this->Form->input('url', array('label' => 'Link Url (copy and paste from site)', 'default' => 'http://', 'id' => 'reference-link-url')) ?>
<?php echo $this->Form->input('description', array('label' => 'Describe this reference', 'id' => 'link-description', 'type' => 'textarea')) ?>

<div class="submit" style="clear:both">
<span class="button" id="link-submit-button">Submit</span>
</div>

<?php echo $this->Form->end()?>

<script>
$(document).ready(function() {	
	var editors = makeEditors();
	
	spellCheckOnSubmit('#document-submit-button', '#DocumentAddForm', editors, saveEditorContent);
	spellCheckOnSubmit('#link-submit-button', '#LinkAddForm', editors, saveEditorContent);

	$('#reference-link-url').change(function() {
		var urlEntered = $(this).val();
		var patt=new RegExp("^http://");
		
		if (!patt.test(urlEntered)) {
			$(this).val('http://'+urlEntered);
		}
	});
	
});
</script>