<script>
$(document).ready(function() {
	if (<?php echo $sendEmails ?>) {
		sendGreetingIter();
	}
	
});

	function sendGreetingIter() {
		$.ajax({
		type: "GET",				
		url: '<?php echo Configure::read('APPROOT') ?>knownEmails/sendGreetingIter',
		dataType: 'json',
		data: {},
		contentType: "application/json; charset=utf-8",	    			
		success: function(data) {
			if (!data.is_complete) {							
				$('#flashMessage').text('Emails were sent to: ' + data.sent_to);								
				sendGreetingIter();
			} else {				
				$('#flashMessage').text('Emails were sent to: ' + data.sent_to + '. Sending emails complete.');
			}
		},
		error: function(jqXHR, textStatus, errorThrown) {			
			$('#flashMessage').text(errorThrown);
		}
		});
	}
</script>
