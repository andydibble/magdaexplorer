<b>Tags:</b>
<?php foreach ($tags as $tag): ?>
	<?php $tagId = isset($tag['id']) ? $tag['id'] : $tag['tag_id']; ?>
	<?php echo $this->Html->link($tag['name'], "/articles/index?tags={$tagId}&newSearch=1") ?>
<?php endforeach;