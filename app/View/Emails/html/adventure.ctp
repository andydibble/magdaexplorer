<?php App::import('Lib', 'Utility'); ?>

<table style="width:100%; font-family: adobe-garamond-pro-1, adobe-garamond-pro-2, 'Times New Roman', Times, serif;	font-size:14px;	margin: 0; color: #666; line-height:25px;">  
    <tr>  
        <td>  
	        <div style="width:600px">					
				<?php $meLink =  $this->Html->link(
						'Magda Explorer', 						 
						array(
							'controller' => 'trips',
							'action' => "index/{$adv['Adventure']['trip_id']}".Utility::toAdventure($adv['Adventure']['id']), 
							'full_base' => true)	
				);?>
				<div>Please visit <?php echo $meLink ?> to Like this adventure or comment upon it.</div>
				
				<div>
					<?php echo $adv['Adventure']['city']?>
				</div>	
				<div style="text-align:left; font-weight:bold">
					<?php echo $adv['Adventure']['title']?> <span style="font-size:80%; font-style:normal">(<?php echo $adv['Adventure']['date']?>)</span>
				</div>
				</div>
				<div style="width:100%">
					<div style="width:385px; float:left; clear:none; margin-right:10px">
						<?php echo $adv['Adventure']['story']?>
					</div>
					<div style="width:33%; float:right;">											
						<?php if(!empty($adv['Photo'][0]['filename'])): ?>
						<div>
							<?php foreach($adv['Photo'] as $photo): ?>
								<div style="clear:right; float:right; width:200px; margin-bottom: 7px">
									<?php //$src = Configure::read('ADV_IMG_PREFIX').'trip'.$adv['Adventure']['trip_id'].'/adventure'.$adv['Adventure']['id'].'/'.$photo['filename']?>
									<?php $src = Configure::read('ADV_IMG_PREFIX').'adventure'.$adv['Adventure']['id'].'/'.$photo['filename']?>
									<?php $title = $photo['title']; ?>
									<img style="width:190px; clear:none; border: 5px solid #444;" src="<?php echo $src ?>" alt="<?php echo $title ?>" title="<?php echo $title ?>" />
									<?php if(!empty($title)): ?>
									<div style="width:200px; font-size:.9em; text-align:center; margin-top:-7px; background:#444; color:white; font-family: 'frutiger linotype', 'lucida grande', verdana, sans-serif;"><?php echo $title?></div>				
									<?php endif; ?>
								</div>									
							<?php endforeach; ?>
						</div>
						<?php endif?>	
					</div>
				</div> 
			</div>
        </td>  
    </tr>  
</table>  

