<?php echo $this->Html->script('TripHeader.js'); ?>

<div id="trip-header">								
	<div class="section-header-wrapper">
		<div class="section-header">
			<div class="section-header-title"><?php 
			if (!$location['is_dummy_location']) {
				echo "$type Details: $name";
				if ($type=='Trip') {
					echo "  ({$texts['start_date']} - {$texts['end_date']})";	
				}
			} else {
				echo $name;
			}
			?>
			
			</div>
			<div class="actions trip-header-buttons">
			<?php 
				if($isAdmin):			
					if($location && ($location['is_dummy_location'] || $type=='Location')):
						echo $this->Html->link('Set as Homepage', '/locations/setAsHomepage/'.$location['id']);
					endif;				
					if($type == 'Location'): 
						echo $this->Html->link('Edit', '/locations/edit/'.$id);
						echo $this->Html->link('Create Trip', '/trips/add/'.$location['id']);
						echo $this->Html->link('Create Adeventure', '/adventures/add/'.$createAdvForTripId);
						
					
					else:
						echo $this->Html->link('Edit', '/trips/edit/'.$id);
						echo $this->Html->link('Create Adeventure', '/adventures/add/'.$id);
				endif;
			endif; ?>
			</div>		
		</div>
	<span class="hidden-content-toggler page-element-toggler rounded-field">+</span><br>	
	</div>	
	
	<div class="scroll-pane vertical-scroll-pane hidden-content">
	<div class="section-body">				
		<div id="message-pane">	
			<div id="message-from-magda">
			<?php echo $texts['message']; ?>
			</div>
			<div id="statistics">
				<div class="left-side">					
					<div><span class="stat"><?php echo $texts['visits']?></span> Visits</div>								
					<?php if($type=="Location"): ?>
					<?php $texts['hugs_term'] = $texts['hugs_term'] ? $texts['hugs_term'] : 'Hug'; ?>
					
					<div><span id="<?php echo strtolower($type).'-hugs-stat'?>" class="stat">
						<?php echo $texts['hugs'] ?></span> <?php echo Inflector::pluralize($texts['hugs_term']) ?>
					</div>
					<?php endif; ?>					
					<div><span class="stat"><?php echo $stats[$type]['photos']?></span> Photos</div>
					<div><span class="stat"><?php echo $stats[$type]['posts']?></span> Posts</div>			
				</div>
								
				<?php if($type=="Location"): ?>
				<?php echo $this->Form->create($type, array( 
						'id' => strtolower($type).'-hug-form', 
						'action' => 'hug',
						'class' => "horz-form"))?>
				
				<div class="submit">
					<span 
						class="button submit-button hug-button" 
						record-id="<?php echo $id ?>">
							<?php echo $texts['hugs_term'] ?>
					</span>
				</div>
				<?php echo $this->Form->end()?>
				
				<div id="stats-note">...for this <?php echo $type ?></div>			
				<?php endif; ?>							
			</div>			
		</div>				
		</div>
	</div>			
</div>