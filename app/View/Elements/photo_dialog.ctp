<?php echo $this->Html->script('PhotoDialog.js', array('inline' => false)); ?>
<?php echo $this->Html->scriptStart(array('inline' => false)); ?>
var tripId = <?php echo $tripId; ?>
<?php echo $this->Html->scriptEnd(); ?>

<div id="photo-dialog" class="hidden-content rounded-field center" style="display:none">	
		<div id="photo-dialog-titlebar" class="row">
			<div id="photo-dialog-reverse" class="photo-dialog-titlebar-button rounded-field col-2">&lt;</div>
			<div id="photo-dialog-titlebar-close" class="photo-dialog-titlebar-button rounded-field col-2">&#215;</div>		
			<div id="photo-dialog-advance" class="photo-dialog-titlebar-button rounded-field col-2">&gt;</div>							
		</div>
		<div class="row">
			<div id="photo-dialog-like-button" class="actions col-3">
				<a>Like</a>
				<span id="likes-display-parent">
					<span class="likes-display rounded-field"></span>
				</span>				
			</div>
			<div class="col-9">			
				<div id="photo-dialog-adv-link-parent">
					in Adventure <a id="photo-dialog-adv-link"></a>
				</div>	
		</div>
	<div id="dialog-photo-parent">
	</div>
</div>