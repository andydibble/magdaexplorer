<h2>Magazines</h2>


<?php if($isAdmin): ?>

<div class="page-actions">
<?php echo $this->Html->link('Back to Search Page', '/articles/index/'); ?>
<?php echo $this->Html->link('Add new Magazine', '/magazines/add/'); ?>
</div>
<?php endif; ?>


<ul style="clear:both">
<?php foreach($mags as $id => $mag): ?>
<li><?php echo $this->Html->link($mag, '/magazines/view/'.$id, array('class' => 'magazine-name')); ?></li>
<?php endforeach; ?>
</ul>