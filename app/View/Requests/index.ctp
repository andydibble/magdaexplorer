<h2>Vacation Service Requests</h2>

<table>
	<tr>
		<th><?php echo $this->Paginator->sort('Request.confirmation_id', 'Confirmation Id'); ?></th>
		<th><?php echo $this->Paginator->sort('RequestType.name', 'Type'); ?></th>
		<th><?php echo $this->Paginator->sort('KnownEmail.email', 'Requestor Email'); ?></th>
		<th><?php echo $this->Paginator->sort('Request.status', 'Status'); ?></th>		
	</tr>
<?php foreach($requests as $i => $req): ?>	
	<tr>
		<td>
		<?php echo $this->Html->link($req['Request']['confirmation_id'], '/requests/view/'.$req['Request']['id']); ?>
		</td>
		<td>
		<?php echo $req['RequestType']['name'] ?>
		</td>
		<td>
		<?php echo $req['KnownEmail']['email'] ?>
		</td>
		<td>
		<?php echo $req['Request']['status'] ?>
		</td>		
	</tr>
<?php endforeach;?>
</table>