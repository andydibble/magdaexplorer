<h2>Respond to Contact Message</h2>

<?php 
if(!empty($respTo)):
	$personRespTo = $respTo['KnownEmail']['name'];
	$emailRespTo = $respTo['KnownEmail']['email'];
?>		
	<label><strong><?php echo "$personRespTo ($emailRespTo) said:"?></strong></label>
	<div><?php echo $respTo['ContactMessage']['value'] ?></div>

<?php if(!empty($respTo['ContactMessage']['request_id'])): ?>
	<div>
		This message is about request <?php echo $this->Html->link($respTo['Request']['confirmation_id'], '/requests/view/'.$respTo['Request']['id']) ?>
	</div>

	<div class="actions">
	<?php echo $this->Html->link('View all Messages about this Request', '/contactMessages/index/'.$respTo['Request']['id']); ?>
	</div>
<?php endif; ?>

<?php endif; ?>



<?php echo $this->Form->create('RespMessage', array('type' => 'post', 'style' => 'clear:both')); ?>		

<?php echo $this->Form->input('subject', array('type' => 'text', 'div' => 'text-field', 'label' => 'Email Subject')); ?>

<?php echo $this->Form->input('body', array('type' => 'textarea', 'label' => 'Email Body')); ?>
 
<?php echo $this->Form->end("Submit"); ?>

<script>
$(document).ready(function() {
	makeEditors();
});
</script>