$(document).ready(function() {
	$('form').submit(function() {	//prevent double submission of forms.
		$(this).find('input[type=submit]').attr('disabled', 'disabled');	
	});

	if (navigator.userAgent.match(/iPhone/i) || navigator.userAgent.match(/iPod/i) || navigator.userAgent.match(/iPad/i)) {
		$.fixLabelClick();
	}
		
	//resize header fields appropriately	
	$('#open-field').fitTextToDiv();
	$('#modern-day-exploring').fitTextToDiv('height', $('#modern-day-exploring').parent().height());

	//resize checkin fields if on more than two lines.	
	if ($('#check-in-location').numLines() > 2) {
		$('#check-in-city').addClass('reduced-check-in-field');
		$('#check-in-venue-name').addClass('reduced-check-in-field');
	}

	//make header trip navigation links wrap correctly
	$.each($('#header-trip-nav a'), function(ind, val) {
		if ($(val).numLines() > 1) {
			$(val)
				.css('float', 'left');
				//.css('margin', '5px 5px 0px 0px');					
		}
	});
});