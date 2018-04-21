	<?php 					
	$advId = $adv['Adventure']['id']?>
		
	<div class="adv" id="adv<?php echo $advId ?>">									
		<div class="adv-city adv-field"><?php echo $adv['Adventure']['city']?></div>	
		<div class="adv-title adv-field"><?php echo $adv['Adventure']['title']?> 
			<span class="adv-title-caption adv-date"> <?php if(!empty($adv['Adventure']['date'])): echo '('.$adv['Adventure']['date'].')'; endif; ?> </span>
			<?php if(!$adv['Adventure']['is_visible']):?>
			<span class="adv-title-caption adv-invisible-note">  (Invisible to Users)</span>
			<?php endif; ?>
		</div>
		<div class="main-adv-pane row">
			<div class="adv-story adv-field col-8"><?php echo $adv['Adventure']['story']?></div>
			<?php if(!empty($adv['Photo'][0]['filename'])): ?>
				<div class="adv-photos col-4">
				<?php foreach($adv['Photo'] as $photo): ?>
					<div id="photo<?php echo $photo['id'] ?>" class="adv-photo">						
						<?php $src = Configure::read('ADV_IMG_PREFIX').'adventure'.$adv['Adventure']['id'].'/'.$photo['filename']?>
						<?php $title = $photo['title']; ?>
						<img 
							src="<?php echo $src ?>" 
							alt="<?php echo $title ?>" 
							title="<?php echo $title ?>" 
							likes="<?php echo $photo['likes'] ?>" 
							record-index="<?php echo $photo['id'] ?>" 														
							adv-title="<?php echo $adv['Adventure']['title']?>"
							adv-id="<?php echo $adv['Adventure']['id'] ?>"
						/>
						<?php if(!empty($title) || $photo['likes']): ?>
						<div class="adv-photo-title">
							<?php echo $title ?>
							<div style="float:right">
							<?php echo $this->Element('likes-display', array('likes' => $photo['likes'], 'isForPhoto' => true));	?>
							</div>
						</div>				
						<?php endif; ?>
					</div>									
				<?php endforeach; ?>
				</div>
			<?php endif?>			
		</div>
		
		<?php if(!empty($adv['Tag'])): ?>
		<?php usort($adv['Tag'], function ($item1, $item2) {		
				if ($item1['name'] == $item2['name']) return 0;
				return $item1['name'] < $item2['name'] ? -1 : 1;
			});	?>
				
		<div class="tags col-8">
		<b>Tags:</b>				
		<?php foreach($adv['Tag'] as $tag): ?>		
		<?php echo $this->Html->link($tag['name'], '/trips/index/'.$tripId.'/tag'.$tag['id'])?>
		<?php endforeach;?>		
		</div>
		<?php endif; ?>		
				
		<div class="adv-actions col-12">
			<div id="comments-toggler<?php echo $advId ?>" class="actions hidden-content-toggler">
				<a>Leave Comment</a>								
			</div>
			
			<div class="actions">
				<a class="adv-like-button" id="<?php echo $adv['Adventure']['id']?>">Like</a>
				<?php echo $this->Element('likes-display', array('likes' => $adv['Adventure']['likes']))?>				
			</div>
			
														
			<?php if($isAdmin): //admin only adv actions. ?>
			<div class="actions adv-admin-actions">
			<?php echo $this->Html->link('Edit', '/adventures/edit/'.$adv['Adventure']['id'])?>
			
			<?php echo $this->Element('delete', array(
					'model' => 'Adventure', 
					'title' => $adv['Adventure']['title'], 
					'id' => $advId))?>
			
			<?php if($adv['Adventure']['is_sent']):?>
			<?php $sendButtonLabel = 'Resend to Subscribers'; ?>
			<?php else: ?>
			<?php $sendButtonLabel = 'Send to Subscribers'; ?>
			<?php endif; ?>
			<?php echo $this->Html->link($sendButtonLabel, '/knownEmails/edit/model:Adventure/id:'.$adv['Adventure']['id'])?>
			</div>
			<?php endif; ?>	

			
			<?php echo $this->Element('comments', array('comments' => $adv['Comment'], 'parentId' => $adv['Adventure']['id'], 'title' => 'Comments')); ?>	
			<div class="hidden-content" style="float:left">
			<?php echo $this->Element('add_comment', array('parentId' => $adv['Adventure']['id'])); ?>
			</div>
		</div>				
	</div>
