<h2>Add Known Emails</h2>
<h3>Feel free to enter garbage text that contains emails, as long as the garbage isn't words with internal @'s</h3>

<?php echo $this->Form->create('KnownEmail', array('type' => 'file', 'style' => 'clear:both')) ?>

<?php echo $this->Form->input('updates', array('type' => 'textarea', 'label' => 'Will receive updates:')); ?>
<?php echo $this->Form->input('no_updates', array('type' => 'textarea', 'label' => 'Will <i>not</i> receive updates:')); ?>


<?php echo $this->Form->end('Submit'); ?>
