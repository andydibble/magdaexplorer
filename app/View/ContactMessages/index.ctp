<h2>Contact Messages</h2>
	<?php if(!empty($results)): ?>
	<table>	
		<tr>
			<th style="width:5%"><?php echo $this->Paginator->sort('ContactMessage.id', 'Id'); ?></th>						
			<th style="width:42%"><?php echo $this->Paginator->sort('ContactMessage.short_value', 'Message'); ?></th>
			<th style="width:10%"><?php echo $this->Paginator->sort('ContactMessage.date_created', 'Created'); ?></th>						
			<th style="width:15%"><?php echo $this->Paginator->sort('KnownEmail.name', 'From'); ?></th>
			<th style="width:10%"><?php echo $this->Paginator->sort('Request.confirmation_id', 'About Request'); ?></th>
			<th style="width:13%"></th>																									
		</tr>			
		<?php foreach($results as $i => $r): ?>								
			<tr>						
				<td>					
					<?php echo $r['ContactMessage']['id'] ?>														
				</td>				
				<td>
					<?php echo $r['ContactMessage']['short_value'] ?>
				</td>
				<td>
					<?php echo $r['ContactMessage']['date_created'] ?>
				</td>																
				<td>
					<?php echo $r['KnownEmail']['name'] ?>
				</td>
				<td>
					<?php echo isset($r['Request']['confirmation_id']) ?
						$this->Html->link($r['Request']['confirmation_id'], '/requests/view/'.$r['Request']['id']) :
						'N/A'						
					?>
				</td>
				<td>
					<div class="actions">
					<?php echo $this->Html->link(
							isset($r['RespMessage'][0]) ? 'Resp Again' : 'Respond', 
							'/contactMessages/respond/'.$r['ContactMessage']['id'])?>
					</div>
				</td>								
			</tr>					
		<?php endforeach; ?>
	</table>
	<?php else: ?>
	<label class="empty-list-note"><strong>No results.</strong></label>
	<?php endif; ?>
	
	<div class="paging-parent">				
		<?php echo $this->Element('paging', array('model' => 'ContactMessage'))?>		
	</div>