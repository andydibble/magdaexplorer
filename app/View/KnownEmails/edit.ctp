<h2>Known Emails</h2>

<?php if(!empty($sendData)): ?>

<?php echo $this->Form->create('KnownEmail'); ?>
	<?php echo $this->Form->input('new', array('type' => 'textarea', 'label' => 'Additional recipients:', 'rows' => 2)); ?>
<?php echo $this->Form->end('Submit'); ?>

<?php endif; ?>



<div class="actions admin-actions">
	<a id="send-updates-select-all">Select All</a>
	<a id="send-updates-deselect-all">Deselect All</a>
</div>

<div style="clear:both">(green emails were added by an administrator)</div>

<?php echo $this->Form->create('KnownEmail') ?>

<table>

<?php foreach($emails as $i => $email): ?>
	<?php $email = $email['KnownEmail']; ?>		
	<tr>		
		<td>		
		<?php echo $this->Form->input($i.'.KnownEmail.delete', array(
				'type' => 'checkbox', 
				'label' => 'Delete', 
				//'checked' => isset($defaultDelete) && $defaultDelete ? 'checked' : ''
				'default' => isset($defaultDelete) ? $defaultDelete : 0,
				'field' => 'delete'
				))?>
		<?php echo $this->Form->input($i.'.KnownEmail.id', array('default' => $email['id']))?>
		</td>
		<td>
		<?php echo $this->Form->input($i.'.KnownEmail.email', array(
				'type' => 'text', 
				'default' => $email['email'], 
				'class' => $email['is_added_by_admin'] ? 'added-by-admin' : '',
				'field' => 'email'
				))?>
		</td>
		
		<td>
		
		
		<?php //default send_updates to select/deselect-all value.  If none, then default to false if email is admin added and false otherwise.
		if(isset($defaultSendUpdates)) {
			$sendUpdatesVal = $defaultSendUpdates;
		} else {
			//$sendUpdatesVal = $email['is_added_by_admin'] ? 0 : 1;
			$sendUpdatesVal = $email['send_updates'];			
		}  	
				
		echo $this->Form->input($i.'.KnownEmail.send_updates', array(
				'type' => 'checkbox', 
				'default' => $sendUpdatesVal,
				'field' => 'send_updates'
		)); ?>
		</td>		
	</tr>
	
<?php endforeach;?>
</tbody>
</table>	

<div class="float-submit-buttons">			
	<?php if(!empty($sendData)): ?>
	<div class="submit">
		<?php echo $this->Html->link('Send Emails', "/knownEmails/send", array('class' => 'button'))?>
	</div>
	<div class="submit">
		<?php echo $this->Html->link('Cancel Emails', '/knownEmails/cancelEmails', array('class' => 'button'))?>
	</div>
	<?php endif; ?>
</div>

<?php echo $this->Form->end()?>

<?php echo $this->Element('paging', array('model' => 'KnownEmail'))?>



<script>
$(document).ready(function() {
	$(':checkbox[name*=delete]').prop('checked', false);
	
	$('#send-updates-select-all').click(function() {
		$.ajax({						
			url: APPROOT+'knownEmails/sendUpdatesToAll',								    			
			success: function(data) {								    				
				$(':checkbox[name*=updates]').prop('checked', true);											
			}    			
		});
	});

	$('#send-updates-deselect-all').click(function() {
		$.ajax({						
			url: APPROOT+'knownEmails/sendUpdatesToNone',								    			
			success: function(data) {								    				
				$(':checkbox[name*=updates]').prop('checked', false);											
			}    			
		});
	});

	$('input').change(function() {
		var row = $(this).closest('tr');
		var id = row.find('[id*=KnownEmailId]').val();
		var field = $(this).attr('field');

		var data = {};
		data['id'] = id;

		if (field == 'send_updates') {
			data[field] = $(this).is(':checked') ? 1 : 0;
		} else {
			data[field] = $(this).val();
		}
		
		if (field == 'delete') {
			var email = row.find('input[field=email]').val();
			$.confirm(
					'Are you sure you want to delete ' + email + ' from the database?',
					updateEmail,
					data
				);
		} else {
			updateEmail(true, data);
		}
				
	});	

	function updateEmail(update, args) {	
		if (update) {
			$.ajax({									
				data: args,
				url: APPROOT+'knownEmails/update',									    			
				success: function(data) {								
					if (data.success) {						
						if (args['delete']) {						
							$('input[value='+args['id']+']').closest('tr').remove();
						}							    				
					}											
				}    			
			});
		}
	}

	/*$('#del-select-all').click(function() {
		$.ajax({						
			url: APPROOT+'knownEmails/deleteSelectAll',								    			
			success: function(data) {								    				
				$(':checkbox[name*=delete]').prop('checked', true);											
			}    			
		});
	});

	$('#del-deselect-all').click(function() {
		$.ajax({						
			url: APPROOT+'knownEmails/deleteDeSelectAll',								    			
			success: function(data) {								    				
				$(':checkbox[name*=delete]').prop('checked', false);											
			}    			
		});
	});*/
});
</script>