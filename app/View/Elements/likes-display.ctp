<span class="likes-display rounded-field" 
	<?php if(!empty($isForPhoto)):?>title="Click on image to like it"<?php endif; ?>
	<?php if(!$likes):?>style="display:none"<?php endif; ?>
>
	<?php echo $likes ?>
</span>
