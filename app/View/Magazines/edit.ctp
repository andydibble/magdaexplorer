<h2>Edit Magazine</h2>

<div class="page-actions">
<?php echo $this->Html->link('Back to Magazine', "/magazines/view/{$this->request->data['Magazine']['id']}"); ?>
</div>

<?php echo $this->Form->create('Magazine', array('type' => 'post', 'style' => 'clear:both')) ?>

<?php echo $this->Form->input('id'); ?>
<?php echo $this->Form->input('is_visible', array('type' => 'checkbox')); ?>
<?php echo $this->Form->input('name', array('div' => 'text-field')); ?>
<?php echo $this->Form->input('website_url', array('rows' => 1, 'div' => 'text-field', 'label' => 'start all with http://', 'default' => 'http://')); ?>

<?php echo $this->Form->input('merge_to', array('options' => $mags, 'label' => 'Delete this magazine and merge its articles into (cannot be undone)', 'empty' => "(Choose one)")); ?>

<?php echo $this->Form->end('Submit'); ?>