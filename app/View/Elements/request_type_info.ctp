<?php $type = $type['RequestType']; ?>
	
	<div class="page-element-parent">
	
	<div class="vacation-service-desc">
		<?php if($type['price'] > 0.00):?>
			<b>$<?php echo $type['price'] ?> : </b>
		<?php endif; ?>
		<?php echo $type['description']?>
	</div>
	<?php echo $this->Form->input("RequestType.request_type_id", array(
			'default' => 1,
			'disabled' => 'disabled',
			'label' => $type['long_name'],
			'type' => 'checkbox',
			'value' => $type['id'],
			'id' => $type['name'].'-type-input',
			'service' => $type['name']			
			)); 
	?>
	</div>