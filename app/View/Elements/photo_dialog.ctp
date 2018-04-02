<?php echo $this->Html->script('PhotoDialog.js', array('inline' => false)); ?>
<?php echo $this->Html->scriptStart(array('inline' => false)); ?>
var tripId = <?php echo $tripId; ?>
<?php echo $this->Html->scriptEnd(); ?>

<div id="photo-dialog" class="hidden-content rounded-field center" style="display:none">	
		<div id="photo-dialog-titlebar">
			<div id="photo-dialog-reverse" class="photo-dialog-titlebar-button rounded-field">&lt;</div>
			<div id="photo-dialog-titlebar-close" class="photo-dialog-titlebar-button rounded-field">&#215;</div>		
			<div id="photo-dialog-advance" class="photo-dialog-titlebar-button rounded-field">&gt;</div>				
			<span id="photo-dialog-title"></span>
		</div>
		<div id="photo-dialog-like-button" class="actions">
			<a>Like</a>
			<span id="likes-display-parent">
				<span class="likes-display rounded-field"></span>
			</span>				
		</div>
		<div id="photo-dialog-adv-link-parent">
			in Adventure <a id="photo-dialog-adv-link"></a>
		</div>	
	<div id="dialog-photo-parent">
	</div>
</div>