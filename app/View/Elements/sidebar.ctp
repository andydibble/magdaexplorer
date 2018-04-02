<div id="sidebar">	
	<?php echo $this->Element('navigation'); ?>
	
	<div id="instant-updates">								
		<label>Receive Instant Updates:</label>		
		<?php echo $this->Form->create('KnownEmail', array('action' => 'sign_up'))?>
		<?php echo $this->Form->input('email', array('label' =>	 '', 'style' => 'clear:none'))?>
		<?php echo $this->Form->input('send_updates', array('type' => 'hidden', 'default' => 1)); ?>
		<?php echo $this->Form->end('Sign Up')?>		
	</div>
	
	<?php if(count($tags) > 0): ?>
	<div id="search">
		<label>Search by Tags:</label>
				
		
		<div class="tags sidebar-adv-tags">
		<?php foreach($tags as $id => $name): ?>
			<?php if ($name) {
				echo $this->Html->link($name, '/trips/index/'.$tripId.'/tag'.$id);
			} ?>
		<?php endforeach;?>
		</div>
	</div>			
	<?php endif;?>
</div>