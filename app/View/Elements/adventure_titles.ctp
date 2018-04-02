<div id="adv-titles">
	<div class="section-header-wrapper">
		<div class="section-header">
			<div class="section-header-title">Adventure Digest</div>
		</div>
		<span id="adv-titles-toggler"
		      class="page-element-toggler hidden-content-toggler rounded-field">+</span><br>
	</div>
	<?php //debug($advTitles); ?>
	<div class="scroll-pane vertical-scroll-pane hidden-content">
		<div class="section-body ">
			<?php if (isset($advTitles[0]['Trip'])): ?>
				<?php foreach ($advTitles as $trip): ?>
					<?php if (!empty($trip['Adventure'])): ?>
						<ul>
							<li><?php echo $this->Html->link($trip['Trip']['display_name'], "/trips/index/{$trip['Trip']['id']}") ?>
								<ul>
									<?php if (count($trip['Adventure']) > 0): ?>
										<?php foreach ($trip['Adventure'] as $adv): ?>
											<?php if (!empty($adv['title'])): ?>
												<li><?php echo $this->Html->link($adv['title'], "/trips/index/{$adv['trip_id']}/adv{$adv['id']}/#adv{$adv['id']}") ?>
												</li>
											<?php endif; ?>
										<?php endforeach; ?>
									<?php else: ?>
										<li class="empty-list-note">None</li>
									<?php endif; ?>
								</ul>
							</li>
						</ul>
					<?php endif; ?>
				<?php endforeach; ?>

			<?php else: ?>
				<ul>
					<?php if (count($advTitles) > 0): ?>
						<?php foreach ($advTitles as $advId => $title): ?>
							<li><?php if (!empty($title)): ?> <?php echo $this->Html->link($title, "/trips/index/$tripId/adv$advId/#adv$advId") ?>
								<?php endif; ?></li>
						<?php endforeach; ?>
					<?php else: ?>
						<li class="empty-list-note">None</li>
					<?php endif; ?>
				</ul>
			<?php endif; ?>
		</div>
	</div>
</div>
