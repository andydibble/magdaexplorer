<table style="width:100%; font-family: adobe-garamond-pro-1, adobe-garamond-pro-2, 'Times New Roman', Times, serif;	font-size:14px;	margin: 0; color: #666; line-height:25px;">  
    <tr>  
        <td>  
	        <div style="width:600px">									
				<?php $meLink =  $this->Html->link('Traveling with Magda', array(
						'controller' => 'articles',
						'action' => "view/{$art['Article']['id']}",
						'full_base' => true))
				?>	
	        	Magda would like to share an article with you from the <?php echo $art['Article']['published_display'] ?> edition of <?php echo $art['Magazine']['name'] ?>.  To view this article in full or like it, please visit <?php echo $meLink ?>.
									
				<div style="text-align:left; font-weight:bold">
					<?php echo $art['Article']['name']?>
				</div>				
				<div style="width:100%">
					<?php echo $this->Html->image($art['Scan'][0]['url'], array('style' => 'width:600px')) ?>
				</div> 
			</div>
        </td>  
    </tr>  
</table>  


