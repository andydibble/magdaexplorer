<?php  $prev = $tripIds['prev']['Trip']; ?>
<?php  $next = $tripIds['next']['Trip']; ?>
<?php if($next['id'] || $prev['id']): ?>
<div id="footer-trip-nav">
	<label for="trip-paging" class="trip-paging-label">Skip to another <?php echo $location ?> Trip:</label>
	<div id="trip-paging" class="paging">		
		<?php $prevText = '<< previous' ?>
		<?php $nextText = 'next >>' ?>
		<?php if(!empty($prev['id'])): ?>
		<span class="prev">			
			<?php echo $this->Html->link($prevText, '/trips/index/'.$prev['id'])?>
		</span>
		<?php else: ?>
		<span class="prev disabled">
			<?php echo $prevText; ?>
		</span>
		<?php endif; ?>
		
		<?php if(!empty($tripIds['next']['Trip']['id'])): ?>
		<span class="next">
			<?php echo $this->Html->link($nextText, '/trips/index/'.$next['id'])?>
		</span>		
		<?php else: ?>
		<span class="next disabled">
			<?php echo $nextText; ?>
		</span>
		<?php endif; ?>
	</div>
</div>
<?php endif; ?>
