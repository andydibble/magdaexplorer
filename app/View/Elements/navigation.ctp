<?php if ($isAdmin): ?>
	<div class="actions">		
	<?php echo $this->Html->link('Edit Site Layout', '/texts/edit'); ?>
	<?php echo $this->Html->link('Edit Email Templates', '/emailTexts/index'); ?>
	
	<?php if(!empty($tripId)): ?>								
	<?php echo $this->Html->link('Edit Poll Responses', '/pollResponses/edit/'.$tripId); ?>
	<?php echo $this->Html->link('Edit Tags', '/tags/edit/Trip/'.$tripId); ?>
	<?php endif; ?>
	
	<?php echo $this->Html->link('Edit Known Emails', '/knownEmails/edit'); ?>	
	<?php echo $this->Html->link('Add Known Emails', '/knownEmails/add'); ?>
	<?php echo $this->Html->link('Add Reference', '/references/add'); ?>
	
	<?php echo $this->Html->link('View Contact Messages', '/contactMessages/'); ?>
	
	<?php echo $this->Html->link('Admin Article DB Management', '/magazines/'); ?>	
	</div>
<?php endif; ?>		
<div class="actions" style="clear:both">	
	<?php echo $this->Html->link('Travel Article Database', '/articles/')?>
	<?php if($isAdmin): ?>
	<?php echo $this->Html->link('Request Vacation Itinerary', '/requests/itinerary')?>
	<?php echo $this->Html->link('Edit/View Service Request', 
			$isAdmin ?	'/requests/' : '/requests/login')?>
	<?php endif; ?>
	
	<?php if(!empty($tripId)): ?>
	<?php echo $this->Html->link('References', '/references/index/'.$tripId)?>
	<?php endif; ?>
	<?php echo $this->Html->link('Track Magda\'s Logins', '/logins/index/'); ?>
	<?php echo $this->Html->link('Events Calendar', '/events/index/'); ?>			
	
	<?php echo $this->Html->link('Contact Magda', '/contactMessages/add/'); ?>
	
	<?php echo $this->Html->link('Logout', '/pages/logout', array('id' => 'logout-action'))?>		
</div>	

