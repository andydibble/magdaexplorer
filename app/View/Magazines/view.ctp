<h2>Articles in <span class="magazine-name"><?php echo $magName ?></span></h2>
<div class="actions" style="width:100%">
<?php echo $this->Html->link('Back to All Magazines', '/magazines/index'); ?>

<?php if($isAdmin): ?>
	<div class="adv-admin-actions">
	
	<?php echo $this->Element('delete', array(
					'model' => 'Magazine', 
					'title' => $magName, 
					'id' => $magId))?>
	
	
	<?php echo $this->Html->link('Edit', '/magazines/edit/'.$magId); ?>
	<?php echo $this->Html->link('Edit Tags', '/tags/edit/Magazine/'.$magId); ?>
	<?php echo $this->Html->link('Upload new Article', '/articles/add/'.$magId); ?>
	</div>
<?php endif; ?>
</div>




<div id="view-mag-left">
	<?php $prevYear = null; ?>
	<ul class="article-links">
	<?php if(count($arts)): ?>
	<?php foreach($arts as $art): ?>
		<?php if($prevYear != $art['Article']['published_year']): ?>
			
			<?php if($prevYear != null): ?>
			</ul>
			<?php endif; ?>
			
			<?php $prevYear =  $art['Article']['published_year']; ?>
			<li><?php echo $art['Article']['published_year'] ?></li>			
			<ul>
		<?php endif; ?>
		
		<li><?php echo $this->Html->link($art['Article']['name'], '/articles/view/'.$art['Article']['id'], array('class' => 'article-link article-name')); ?></li>
	<?php endforeach; ?>
	<?php else: ?>
	<li class="empty-list-note">None</li>
	<?php endif; ?>
	</ul>
</div>

<div id="view-mag-right" class="page-element-parent">
	<?php if(count($tags)): ?>
	<span>Articles in this Magazine are tagged with:</span>
	<div class="tags sidebar-adv-tags">
	<?php $i = 0?>
	<?php foreach ($tags as $id => $name):?>
	<?php echo $this->Html->link($name, '/articles/searchResults/'.$id)?>
	<?php if ($i++ % 3 == 2): ?>	<?php endif; ?>
	<?php endforeach;?>
	</div>
	<?php endif; ?>
</div>
