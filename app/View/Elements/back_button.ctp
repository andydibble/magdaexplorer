<div class="page-actions">
<?php 
if(!empty($tripId)) {
	$url = '/trips/index/'.$tripId;
	if ($isNormalTrip) {
		$message = 'Back to '.$tripName.' Trip';
	} else {
		$message = 'Back to '.$tripName;
	}
} else {
	$url = '/trips';
	$message = 'Back to Trip';
}
 ?>

<?php echo $this->Html->link($message, $url)?>
</div>