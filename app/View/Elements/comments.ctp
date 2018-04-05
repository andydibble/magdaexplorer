<div class="comments">		
	<?php if(isset($title) && count($comments) > 0):?>
	<div class="comments-title"><?php echo $title?></div>
	<?php endif?>
	
	<?php if(count($comments) == 0):?>
		<?php if(isset($noCommentMessage)): ?>
			<?php echo $noCommentMessage ?>
		<?php endif; ?>
	<?php else:?>
		<?php foreach($comments as $comment): ?>							
			<div class="comment">
				<div class="comment-body">
					<div class="comment-value">			
						<?php $value =  isset($comment['Comment']) ? $comment['Comment']['value'] : $comment['value']; ?>
						<?php echo $value ?>				
					</div>
					<div>							
						<?php $dateCreated =  isset($comment['Comment']) ? $comment['Comment']['date_created'] : $comment['date_created']; ?>
						<span class="comment-date"><?php echo $dateCreated; ?></span>
					</div>
				</div>
				<div class="actions comment-like-button">
					<a id="<?php echo $comment['id']?>">Like</a>
					<?php echo $this->Element('likes-display', array('likes' => $comment['likes']))?>				
				</div>													
			</div>
		<?php endforeach; ?>
	<?php endif;?>
</div>
