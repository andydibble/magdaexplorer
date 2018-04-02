<div class="comments">
<div>
	<?php echo $this->Form->create('Comment', array('action'=>'add', 'type' => 'post')); ?>
	<?php echo $this->Form->input('Comment.adventure_id', array('type' => 'hidden', 'default' => $parentId));?>	
	<?php echo $this->Form->input('Comment.value', array('div' => 'rounded-field', 'type' => 'textarea', 'label' => '', 'rows' => 3, 'maxLength' => 2000));?>
	<?php echo $this->Form->end('Post');?>
</div>
</div>