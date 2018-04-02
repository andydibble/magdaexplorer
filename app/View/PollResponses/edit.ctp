<h2>Edit Poll Responses</h2>

<?php if(count($resps) > 0): ?>
<?php echo $this->Form->create('PollResponse') ?>
<div class="page-actions">
<a id="select-all">Select All</a>
<a id="deselect-all">Deselect All</a>
</div>

<table style="clear:both">
<?php foreach($resps as $i => $resp): ?>
	<tr>
		<td>
		<?php echo $this->Form->input($i.'.PollResponse.delete', array('type' => 'checkbox', 'default' => $resp['PollResponse']['id'], 'label' => 'Delete'))?>
		<?php echo $this->Form->input($i.'.PollResponse.id')?>
		</td>
		<td>
		<?php echo $this->Form->input($i.'.PollResponse.value', array('label' => ''))?>
		</td>
	</tr>
<?php endforeach;?>
</table>	

<?php echo $this->Form->end('Submit')?>
<?php else: ?>
<div class="empty-list-note">None</div>
<?php endif; ?>

<script>
$(document).ready(function() {
	$('#select-all').click(function() {
		$(':checkbox').prop('checked', true);
	});

	$('#deselect-all').click(function() {
		$(':checkbox').prop('checked', false);
	});
});
</script>