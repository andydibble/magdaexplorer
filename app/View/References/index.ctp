<h2>References from Magda's Trip</h2>

<div class="page-actions">
	<?php if($isAdmin):?>
	<?php echo $this->Html->link('Add a Reference', '/references/add')?>
	<?php endif;?>
</div>


<h3>Links</h3>
<?php if(count($links) > 0): ?>
<table id="listThemes">	
	<tr>
		<th><?php echo $this->Paginator->sort('Reference.url', 'Link'); ?></th>
		<th><?php echo $this->Paginator->sort('Reference.description', 'Description'); ?></th>
		<th class="updatedCol"><?php echo $this->Paginator->sort('Reference.date_created', 'Date Added'); ?></th>
		<?php if($isAdmin): ?>
		<th class="updatedCol">Actions</th>
		<?php endif;?>		
	</tr>		
	<?php foreach($links as $i => $link): ?>		
		<tr>						
			<td>
				<?php echo $this->Html->link($link['Reference']['name'], $link['Reference']['url'], array('target' => '_blank')); ?>				
			</td>
			<td>
				<?php echo $link['Reference']['description']; ?>
			</td>
			<td>
				<?php echo $link['Reference']['date_created']; ?> 				
			</td>
			<?php if($isAdmin): ?>
			<td>
				<div class="actions">
				<?php echo $this->Html->link('Edit', '/references/edit/'.$link['Reference']['id'])?>
				<?php echo $this->Html->link('Delete', '/references/delete/'.$link['Reference']['id'])?>
				</div>
			</td>
			<?php endif?>						
		</tr>			
	<?php endforeach; ?>
</table>
<?php else: ?>
<div class="empty-list-note">None</div>
<?php endif; ?>

<h3>Documents</h3>
<?php if(count($docs) > 0): ?>
<table id="listThemes">	
	<tr>
		<th><?php echo $this->Paginator->sort('Reference.url', 'Document'); ?></th>
		<th><?php echo $this->Paginator->sort('Reference.description', 'Description'); ?></th>
		<th class="updatedCol"><?php echo $this->Paginator->sort('Reference.date_created', 'Date Added'); ?></th>
		<?php if($isAdmin): ?>
		<th class="updatedCol">Actions</th>
		<?php endif;?>			
	</tr>		
	<?php foreach($docs as $i => $doc): ?>		
		<tr>						
			<td>
				<a href="<?php echo $doc['Reference']['url'];?>"><?php echo $doc['Reference']['name'] ?></a>				
			</td>
			<td>
				<?php echo $doc['Reference']['description']; ?>
			</td>
			<td>
				<?php echo $doc['Reference']['date_created']; ?> 				
			</td>	
			<?php if($isAdmin): ?>
			<td>
				<div class="actions">
				<?php echo $this->Html->link('Edit', '/references/edit/'.$doc['Reference']['id'])?>
				<?php echo $this->Html->link('Delete', '/references/delete/'.$doc['Reference']['id'], array('class' => 'delete-reference'))?>
				</div>
			</td>
			<?php endif?>				
		</tr>			
	<?php endforeach; ?>
</table>
<?php else: ?>
<div class="empty-list-note">None</div>
<?php endif; ?>


<?php echo $this->Html->scriptStart(array('inline' => false)) ?>
	
$(document).ready(function() {
	$('.delete-reference').click(function(ev) {
		ev.preventDefault();		
		$.confirm(
			"Are you sure you want to permanently delete this Reference?", 
			function(result) {
				if (result) {
					window.location = ev.target.href; 								 
				}
			}
		);
		return false;					
	});
});
	        
		
<?php echo $this->Html->scriptEnd()?>
