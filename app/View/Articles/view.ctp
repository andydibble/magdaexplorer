<?php echo $this->Html->css('jquery/nivoslider/nivo-slider'); ?>
<?php echo $this->Html->css('jquery/nivoslider/themes/default/default.css'); ?>

<?php echo $this->Html->script('jquery/nivoslider/jquery.nivo.slider.pack.js', array('inline' => false)); ?>

<?php echo $this->Html->script('AmendableList.js', array('inline' => false)); ?>
<?php echo $this->Html->script('Likeable.js', array('inline' => false)); ?>

<?php $artId = $art['Article']['id'] ?>

<div class="actions">
	<?php echo $this->Html->link('Back to Search Results', '/articles/') ?>
</div>
<div class="actions">
	<a class="art-like-button" id="<?php echo $artId ?>">Like</a>
	<?php echo $this->Element('likes-display', array('likes' => $art['Article']['likes'])) ?>
</div>


<div>
	<?php if ($isAdmin): //admin only article actions. ?>
		<div class="actions admin-actions">
			<?php echo $this->Html->link('Back to Magazine', '/magazines/view/' . $art['Article']['magazine_id']) ?>

			<?php echo $this->Html->link('Edit', '/articles/edit/' . $artId) ?>

			<?php $linkName = $art['Article']['is_sent'] ? 'Resend' : 'Send' ?>
			<?php echo $this->Html->link($linkName, '/knownEmails/edit/model:Article/id:' . $artId) ?>

			<?php echo $this->Element('delete', array(
				'model' => 'Article',
				'title' => $art['Article']['name'],
				'id' => $artId))?>
		</div>
	<?php endif; ?>
</div>

<h2><?php echo $art['Article']['name'] ?></h2>
<span class=""><?php echo $art['Article']['published_display'] ?> issue of <span
		class="magazine-name"><?php echo $art['Magazine']['name'] ?></span></span>

<div id="article-tags" class="tags">
	<?php //if(!empty($art['Tag'])): ?>
	<?php echo $this->Element('/Articles/tags', array('tags' => $art['Tag'])) ?>
	<?php //endif; ?>
</div>


<?php if ($art['Article']['comments'] != '<br>'): ?>
	<p style="clear:both" class="article-comments">
		<?php echo $art['Article']['comments'] ?>
	</p>
<?php endif; ?>

<?php if (!empty($art['Scan'])): ?>
	<div id="slider-wrapper" class=" theme-default">
		<div class="nivoSlider">
			<?php foreach ($art['Scan'] as $scan): ?>
				<?php $src = Configure::read('ART_IMG_PREFIX') . 'art' . $artId . '/' . $scan['filename'] ?>
				<img
					src="<?php echo $src ?>"
					class="scan"
					/>
			<?php endforeach; ?>
		</div>
	</div>
<?php endif; ?>

<?php if ($isAdmin): ?>
	<?php echo $this->Element('/Articles/edit_tags_popup', array('artId' => $artId, 'art' => $art)) ?>
<?php endif; ?>

<script>
	$(window).load(function () {
		if ($('.nivoSlider img').length) {
			$('.nivoSlider').nivoSlider({
				manualAdvance: true,
			});
		}
	});
</script>

	


