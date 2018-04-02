<p>
Thanks!  Your service request has been recorded.  Please continue to Paypal to complete your purchase.
</p>
<p>
Your Confirmation Id is: <b><?php echo $req['Request']['confirmation_id']; ?></b><br>
The email you entered is: <?php echo $req['KnownEmail']['email']; ?>
<br>
<br>
You will need either your Confirmation Number or the email you entered to view or edit your request.  An email including this information has been sent to the above email account.
</p>

<p>
Note: You will have to disable your popup blocker or allow popups from <?php ECHO Configure::read('HTTP_HOST'); ?> in order to visit your Paypal shopping cart.
</p>

<?php $reqType = $req['RequestType']?>
<?php if($reqType['hosted_button_id']): ?>
<div class="vacation-service-hidden-paypal-button">
<form target="paypal" action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="<?php echo $reqType['hosted_button_id'] ?>">
<input class="add-to-cart-button" service="<?php echo $reqType['name'] ?>" type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_cart_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form>
</div>
<?php endif; ?>


<div class="view-cart-paypal-button">
<form target="paypal" action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_cart">
<input type="hidden" name="business" value="payments@magdaexplorer.com">
<input type="image" src="https://www.paypal.com/en_US/i/btn/btn_viewcart_LG.gif" border="0" name="submit" alt="">
<img alt="" border="0" src="https://www.paypal.com/fr_FR/i/scr/pixel.gif" width="1" height="1">
<input type="hidden" name="display" value="1">
</form>
</div>

<script>
$(document).ready(function() {

	$('.view-cart-paypal-button').hide();
	
	var purchasedService = '<?php echo $reqType['name'] ?>';
	//setTimeout(function() {$('.add-to-cart-button[service='+purchasedService+']').trigger('click');}, timeout);
	$('.add-to-cart-button[service='+purchasedService+']').trigger('click');
		
	$('.view-cart-paypal-button').delay(1000).show(500);
});

</script>