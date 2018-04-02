<h2>Edit Statistics</h2>

<div class="page-actions">
<?php echo $this->Html->link('Back to homepage', '/adventures')?>
</div>

<?php echo $this->Form->create('Statistic') ?>
<?php foreach($stats as $i => $stat): ?>
	<?php echo $this->Form->input($i.'.Statistic.id')?>
	<?php echo $this->Form->input($i.'.Statistic.value', array('label' => $stat['Statistic']['name'], 'div' => 'text-field'))?>
<?php endforeach;?>
<?php echo $this->Form->end('Submit')?>
