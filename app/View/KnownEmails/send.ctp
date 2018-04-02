<?php 
switch($sendData['model']) {
	case 'Adventure':
		$redirectUrl = Router::url('/trips/index');
		break;
	case 'Article': 
		$redirectUrl = Router::url('/articles/view/'.$sendData['id']);
		break;
	default:
		//$redirectUrl = Router::url('/trips/index');
	}
?>


<script>

if(<?php echo $sendEmails ?>) {
		$.confirm(
			"Send '<?php echo str_replace("'", "\'", $itemTitle) ?>' to emails marked to receive updates?",												
			function(result) {
				if (result) {
					sendIter();
				} else {
					window.location='<?php echo $redirectUrl ?>';
				}
			}
		);	
	}

function sendIter() { 
	$.ajax({
		type: "GET",				
		url: APPROOT+'knownEmails/sendIter',
		dataType: 'json',		
		contentType: "application/json; charset=utf-8",	    			
		success: function(data) {
			if (!data.is_complete) {							
				$('#flashMessage').text('Emails were sent to: ' + data.sent_to);								
				sendAdventureIter();
			} else {				
				$('#flashMessage').text('Emails were sent to: ' + data.sent_to + '. Sending emails complete.');
			}
		},
		error: function(jqXHR, textStatus, errorThrown) {			
			$('#flashMessage').text(errorThrown);			
			//window.location='<?php //echo $redirectUrl ?>';
		}
	});		   					
}
</script>
