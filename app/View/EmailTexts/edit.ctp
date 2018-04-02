<h2>Edit <?php echo Inflector::humanize($text['EmailText']['key']) ?> Email Template</h2>

<?php echo $this->Form->create('EmailText', array('type' => 'post', 'style' => 'clear:both')) ?>
<?php echo $this->Form->input('EmailText.id', array('type' => 'hidden', 'default' => $text['EmailText']['id'])); ?>
 
<?php 
	echo $this->Form->input('EmailText.body', array(
		'type' => 'textarea', 
		'label' => Inflector::humanize($text['EmailText']['key']).' Email Body', 
		'default' => $text['EmailText']['body'] 
		));

	echo $this->Form->input('EmailText.subject', array(
		'type' => 'text',
		'label' => Inflector::humanize($text['EmailText']['key']).' Email Subject',
		'default' => $text['EmailText']['subject']
		)); ?>
		
<?php echo $this->Form->end('Submit'); ?>

<script>
$(document).ready(function() {
	makeEditors();
});
</script>