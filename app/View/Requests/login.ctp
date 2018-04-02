<h2>Edit or View Previously Submitted Vacation Service Request</h2>

<?php echo $this->Form->create('Request') ?>
<?php echo $this->Form->input('confirmation_id', array(
		'type' => 'text',
		'style' => 'width:100px', 
		'maxLength' => 6, 
		'div' => 'required'))?>
<?php echo $this->Form->end('Submit');?>

<div>
Please <?php echo $this->Html->link('Contact Magda', '/contactMessages/add')?> if you don't know the Confirmation Id for your request.
</div>