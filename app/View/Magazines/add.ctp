<h2>Add Magazine</h2>

<div class="page-actions">
<?php echo $this->Html->link('Back to Magazines', '/magazines'); ?>
</div>

<?php echo $this->Form->create('Magazine', array('type' => 'post', 'style' => 'clear:both')) ?>

<?php echo $this->Form->input('is_visible', array('type' => 'checkbox', 'default' => 1)); ?>
<?php echo $this->Form->input('name', array('div' => 'text-field')); ?>
<?php echo $this->Form->input('website_url', array('rows' => 1, 'div' => 'text-field', 'label' => 'start all with http://', 'default' => 'http://')); ?>

<?php echo $this->Form->end('Submit'); ?>

