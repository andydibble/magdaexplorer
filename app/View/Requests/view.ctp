<h2>View Vacation Service Request</h2>
<h3>(<?php echo $req['RequestType']['long_name'] ?>)</h3>

<div class="page-actions actions">
<?php echo $this->Html->link('Edit', '/requests/edit/'.$req['Request']['id'])?>
<?php echo $this->Html->link('Contact Magda', '/contactMessages/add/')?>

<?php if($isAdmin): ?>

<?php echo $this->Html->link('View all Messages about this Request', '/contactMessages/index/'.$req['Request']['id']); ?>

<?php endif; ?>

</div>

<dl>							
	<dt><?php echo __('Confirmation Id'); ?></dt>
	<dd>
		<?php echo $req['Request']['confirmation_id']?>	
		&nbsp;
	</dd>	
	<dt><?php echo __('Requested by'); ?></dt>	
	<dd>
		<?php echo $req['KnownEmail']['name'].' '?>
		&nbsp;
		<?php if ($req['KnownEmail']['name'] != $req['KnownEmail']['email'])?>
		(<?php echo $req['KnownEmail']['email']?>)
		<?php ?>
	</dd>	
	<dt><?php echo __('General Interests'); ?></dt>
	<dd>
		<?php echo $req['Request']['general_interests'] ?
			$req['Request']['general_interests'] :
			'<span class="empty-field-note">None provided.</span>' ?>
		&nbsp;
	</dd>
	<dt><?php echo __('Location'); ?></dt>
	<dd>
		<?php echo $req['Request']['location'] ?
			$req['Request']['location'] :
			'<span class="empty-field-note">None provided.</span>' ?>
		&nbsp;
	</dd>
	<dt><?php echo __('Other comments, details, or questions'); ?></dt>
	<dd>
		<?php echo $req['Request']['other'] ?
			$req['Request']['other'] :
			'<span class="empty-field-note">None provided.</span>' ?>
		&nbsp;
	</dd>
	<?php if($isAdmin):?>
	<dt><?php echo __('Paid for?'); ?></dt>
	<dd>
		<?php echo $req['Request']['paid'] ?
			$req['Request']['paid'] :	'No' ?>
		&nbsp;
	</dd>	
	<dt><?php echo __('Complete?'); ?></dt>
	<dd>
		<?php echo $req['Request']['completed'] ?
			$req['Request']['completed'] :	'No' ?>
		&nbsp;
	</dd>
	<dt><?php echo __('Canceled?'); ?></dt>
	<dd>
		<?php echo $req['Request']['canceled'] ?
			$req['Request']['canceled'] :	'No' ?>
		&nbsp;
	</dd>
	<dt><?php echo __('Refunded?'); ?></dt>
	<dd>
		<?php echo $req['Request']['refunded'] ?
			$req['Request']['refunded'] :	'No' ?>
		&nbsp;
	</dd>	
	<?php endif; ?>
</dl>

