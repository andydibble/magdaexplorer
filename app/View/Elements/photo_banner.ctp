<?php if(count($photos) > 0): ?>
<div id="banner" class="scroll-pane horizontal-scroll-pane ui-widget ui-widget-header ui-corner-all">
				
	<?php foreach($photos as $photo): ?>
		<?php if(!empty($photo['Photo']['filename'])): ?>
		<div class="scroll-content-item ui-widget-header">				
			<?php $src = Configure::read('ADV_IMG_PREFIX').'adventure'.$photo['Adventure']['id'].'/'.$photo['Photo']['filename'] ?>
			<img 
				alt="<?php echo $photo['Photo']['title'] ?>" 
				title="Drag scrollbar to see more photos" 
				src="<?php echo $src ?>"
				record-index="<?php echo $photo['Photo']['id'] ?>"
				likes="<?php echo $photo['Photo']['likes'] ?>"
				adv-title="<?php echo $photo['Adventure']['title']?>"
				adv-id="<?php echo $photo['Adventure']['id'] ?>" 
			/>				
		</div>
		<?php endif; ?>
	<?php endforeach ?>			
</div>
<?php endif; ?>