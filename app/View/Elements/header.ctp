<!-- Header -->
<?php if (isset($texts)):
	echo $this->Html->script('Layout.js');
	echo $this->Html->script('ExplorerTracking.js'); ?>

	<script>
		$(document).ready(function () {
			<?php if(!empty($saveAdminLogin) && $saveAdminLogin): ?>
			saveSubjectLogin();
			<?php endif;?>

			setLocalTemp(<?php echo $explorerLocInfo ?>);
		});
	</script>

	<div id="header" class="center" style="color:<?php echo $texts['header_text_color'] ?>">

		<div id="header-row-1" class="row">
			<?php if (!empty($texts['square_header_field'])): ?>
				<div id="modern-day-exploring"
				     class="border-box rounded-field header-field col-2"><?php echo $texts['square_header_field'] ?></div>
			<?php endif; ?>
			<?php if (!empty($texts['rectangle_header_field'])): ?>
				<div id="open-field"
				     class="border-box rounded-field header-field col-6"><?php echo $texts['rectangle_header_field']; ?></div>
			<?php endif; ?>
			
			<div class="col-3 right">
			<?php echo $this->Form->create('Adventure.', array(
				'url' => array('controller' => 'trips', 'action' => "index",3),
				'type' => 'get',
				'id' => 'header-search')) ?>
			<?php 			
			$placeholder = "Search";
			if(isset($searchPerformed)) { 
				$placeholder = "Submit to clear search";
			}
			
			echo $this->Form->input('searchTerm', array(				
				'type' => 'text', 
				'label' => false,
				'placeholder' => $placeholder,
				'id' => "AdventureSearchTerm"
			));					
			echo $this->Form->end(' ');
			?>			
			</div>
			
			

		</div>
		<div id="header-row-2" class="row">
			<div id="check-in-location" class="col-4">						
				<div id="check-in-city" class="check-in-city header-field"><?php echo $texts['check_in_city_label'] ?><b
						id="check-in-city-name"><?php echo $texts['check_in_city'] ?></b></div>
				<div id="check-in-venue" class="header-field"><b id="check-in-venue-name"				                                                 class="check-in-venue-name"><?php echo $texts['check_in_venue'] ?></b>
				</div>
			</div>
			<div id="local col-4" class="right">
				<div id="local-left">
					local time:
					<div id="local-time">
						<?php echo $localTime ?>
					</div>
				</div>
				<!--<div id="local-right">
					local temp:
					<div id="local-temp"></div>
				</div>-->
			</div>
		</div>
		<nav id="nav" class="actions trip-nav" role="navigation">
			<!--<div class="container-fluid">-->
			<!--<a href="#nav" title="Show navigation">Show navigation</a>
			<a href="#" title="Hide navigation">Hide navigation</a>-->
			<ul class="mbtnav row nav navbar-nav">
				<?php if ($isAdmin): ?>
					<li><?php echo $this->Html->link('Create Location', '/locations/add') ?></li>
				<?php endif; ?>

				<?php foreach ($locations as $i => $loc): ?>
					<?php if (count($loc['Trip']) > 0 || $isAdmin): ?>
						<li class="col-2">
							<?php $class = !empty($tripId) && $loc['Location']['id'] == $locationId ? 'active-trip' : ''; ?>

							<?php $locHeaderLink = isset($loc['Trip'][0]['id']) ?
								'/trips/index/' . $loc['Trip'][0]['id'] :
								'/locations/edit/' . $loc['Location']['id']; ?>

							<?php echo $this->Html->link(
								$loc['Location']['name'],
								count($loc['Trip']) > 1 ? '#' : $locHeaderLink,
								array(
									'class' => $class,
									'aria-has-popup' => true
								)
							); ?>

							<ul>
								<?php if (count($loc['Trip']) > 1): ?>
									<?php foreach ($loc['Trip'] as $i => $trip): ?>
										<li>
											<?php echo $this->Html->link(
												$trip['display_name'],
												'/trips/index/' . $trip['id'],
												array('class' => 'wrapword')
											); ?>
										</li>
									<?php endforeach; ?>
								<?php endif; ?>
							</ul>
						</li>
					<?php endif; ?>
				<?php endforeach; ?>
			</ul>
			<!--</div>-->
		</nav>		
	</div>
<?php endif;