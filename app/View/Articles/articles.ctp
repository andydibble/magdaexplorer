<h2>Articles in <?php echo $magName ?></h2>

<ul>
<?php foreach($arts as $id => $art): ?>
<li><?php echo $this->Html->link($art, '/magazines/view/'.$id); ?></li>
<?php endforeach; ?>
</ul>