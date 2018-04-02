<h2><?php echo $tagName ?> Articles</h2>

<ul class="article-links">
<?php foreach($arts as $art): ?>			
	<li>
	<?php echo $this->Html->link($art['Article']['name'], '/articles/view/'.$art['Article']['id'], array('class' => 'article-link article-name')); ?> in 
	<?php echo $this->Html->link($art['Magazine']['name'], '/magazines/view/'.$art['Magazine']['id'], array('class' => 'magazine-name')); ?>
	 (<?php echo $art['Article']['published_display']?>)
	</li>
<?php endforeach; ?>
</ul>
