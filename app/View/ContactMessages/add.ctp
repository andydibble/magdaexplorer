<h2>Contact Magda</h2>

<?php echo $this->Form->create('ContactMessage', array('action'=>'add', 'type' => 'post')); ?>		
<div class="form-entry-set">
	<?php echo $this->Form->input('KnownEmail.first_name', array('type' => 'text'));
	echo $this->Form->input('KnownEmail.last_name', array('type' => 'text')); ?>
</div>
<br />
<div class="form-entry-set">
	<?php echo $this->Form->input('KnownEmail.email', array('type' => 'text'));
	echo $this->Form->input('KnownEmail.retype_email', array('type' => 'text', 'div' => 'required')); ?>
</div>
<br />



<div class="">
<?php echo $this->Form->input('ContactMessage.0.value', array('type' => 'textarea', 'label' => 'Message (please limit to 1000 characters)', 'maxLength' => 1000)); ?>
</div>

<?php echo $this->Form->submit("Submit");

echo $this->Form->end(); ?>

<?php if(!empty($auth['User']['id'])): ?> 
<script type="text/javascript">

</script>
<?php endif; ?>