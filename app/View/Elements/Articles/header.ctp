<div id="header" class="center">
	<div id="header-row-1">
		<div id="modern-day-exploring"
		     class="border-box rounded-field header-field">Traveling with Magda
		</div>
		<?php if (isset($mags)): ?>
			<div id="mag-website-links" class="page-element-parent">
				<h4>Articles from:</h4>
				<ul>
					<?php foreach ($mags as $m): ?>
						<?php if (!empty($m['Magazine']['website_url'])): ?>
							<li><?php echo $this->Html->link($m['Magazine']['name'], $m["Magazine"]['website_url']); ?>
							</li>
						<?php endif; ?>
					<?php endforeach; ?>
				</ul>
			</div>
		<?php endif; ?>
	</div>
	<div id="header-row-2"></div>


	<nav class="actions trip-nav" role="navigation"> <!-- id="nav" was wrecking havoc with the height for an unknown reason -->
		<ul class="mbtnav">
			<?php foreach ($navTrips as $id => $trip): ?>
				<li><?php echo $this->Html->link($trip, '/trips/index/' . $id); ?>
				</li>
			<?php endforeach; ?>
		</ul>
	</nav>
</div>
