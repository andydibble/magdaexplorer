<h2>Email Templates</h2>

<table>
	<tr>
		<th>Name</th><th>Subject</th><th>Function</th>
	</tr>	
<?php foreach($templs as $text): ?>			
	<tr>
		<td>
		<?php echo $this->Html->link(Inflector::humanize($text['EmailText']['key']), '/emailTexts/edit/'.$text['EmailText']['id']); ?>
		</td>
		<td>
		<?php echo $text['EmailText']['subject'] ?>
		</td>
		<td>
		<?php echo $text['EmailText']['function'] ?>
		</td>						
	</tr>
<?php endforeach;?>
</table>