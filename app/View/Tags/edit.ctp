<h2>Edit Tags for <?php echo $tripOrMagName ?> (<?php echo $this->params['pass'][0] ?>)</h2>

<?php if(count($tags) > 0): ?>
<?php echo $this->Form->create('Tag') ?>


<h4>Notes:
	<ul>
		<li>Count values are the number of Articles or Adventures the tag appears under, regardless of Trip or Magazine.</li>
		<li>Deleting a Tag will delete it from all Adventures and Articles it currently appears in</li>	
	</ul>
</h4>


<div class="actions">
<a id="select-all">Select All</a>
<a id="deselect-all">Deselect All</a>
</div>

<table>
<tr>
<th>Delete</th><th><?php echo $this->Paginator->sort('Tag.name', 'Name'); ?></th><th style="width:10%"><?php echo $this->Paginator->sort('Tag.count', 'Count'); ?></th>
<?php if(count($tags) > 1): ?>
<th>Delete</th><th><?php echo $this->Paginator->sort('Tag.name', 'Name'); ?></th><th style="width:10%"><?php echo $this->Paginator->sort('Tag.count', 'Count'); ?></th>
<?php endif; ?>
</tr>
<?php for($i = 0; $i < count($tags); $i += 2): ?>
	<tr>
		<td>
		<?php echo $this->Form->input($i.'.Tag.delete', array('type' => 'checkbox', 'default' => $tags[$i]['Tag']['id'], 'label' => 'Delete'))?>		
		</td>
		<td>
		<?php echo $this->Form->input($i.'.Tag.id', array('default' => $tags[$i]['Tag']['id'])); ?>
		<?php echo $this->Form->input($i.'.Tag.name', array('default' => $tags[$i]['Tag']['name']))?>
		</td>
		<td>
		<?php echo $tags[$i]['Tag']['count']; ?>
		</td>
		
		<?php if($i != count($tags)-1): ?>
		<td>
		<?php echo $this->Form->input(($i+1).'.Tag.delete', array('type' => 'checkbox', 'default' => $tags[$i+1]['Tag']['id'], 'label' => 'Delete', 'selected' => false))?>		
		</td>
		<td>
		<?php echo $this->Form->input(($i+1).'.Tag.id', array('default' => $tags[$i+1]['Tag']['id'])); ?>
		<?php echo $this->Form->input(($i+1).'.Tag.name', array('default' => $tags[$i+1]['Tag']['name']))?>
		</td>
		<td>
		<?php echo $tags[$i+1]['Tag']['count']; ?>
		</td>
		<?php endif; ?>		
	</tr>
<?php endfor;?>
</table>

<?php echo $this->Element('paging', array('model' => 'Tag')); ?>

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