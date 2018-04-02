<div class="trip-nav actions">
	<?php foreach($trips as $id => $name):?>
		<?php $class = !empty($tripId) && $id == $tripId ? 'active-trip' : '' ?>		
		<?php echo $this->Html->link(
				$name, 
				!empty($tripId) && $id == $tripId ? '#' : '/trips/index/'.$id, 
				array('class' => $class)
		); ?>
	<?php endforeach;?>
</div>