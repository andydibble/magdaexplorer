<div id="edit-tags-popup" style="display:none">
	<?php echo $this->Form->create('Article', array('action' => 'editTags')) ?>
		<?php echo $this->Form->input('id', array('default' => $artId)) ?>
		<?php echo $this->Form->input('is_tagged', array(
				'type' => 'checkbox',
				'default' => $art['Article']['is_tagged'], 
				'label' => 'Tagging is Complete?')) ?>
		<div id="edit-tags-popup-taglist" class="form-entry-set">
		</div>		
	<?php echo $this->Form->end()?>
</div>

<script>
$(document).ready(function() {						    		
    	$( "#edit-tags-popup" ).dialog({
	  		  autoOpen: false,	  	      	  	      
	  	      modal: true,
	  	      title: 'Edit Tags',
	  	      width: 890,
	  	      open: function( event, ui ) {
					var artId = $('#ArticleId').val();					
					$.ajax({									
		    			url: APPROOT+'articles/getTags?id='+artId,		    					    					    			    			
		    			success: function(tags) {
		    				$('#edit-tags-popup-taglist').html('');								    				
		    				var tagTmpl = <?php echo Configure::read('NEW_TAG_HTML'); ?>;
		    				$(tags).each(function(i, v) {		    					
		    					var newTag = AmendableList.createListElement(tagTmpl, i);
			    				
			    				$('#edit-tags-popup-taglist').append(newTag);
			    				$('#Tag'+i+'Name').val(v);
			    			});		    											
							var nextTagInd = tags.length;

							newTag = AmendableList.createListElement(tagTmpl, nextTagInd);							
							
							$('#edit-tags-popup-taglist').append(newTag);

							setupAutocomplete(['Tag']);							
							AmendableList.amendListListener('#Tag'+nextTagInd+'Name', '#edit-tags-popup-taglist', tagTmpl, <?php echo Configure::read('MAX_NUM_TAGS') ?>, ['setupAutocomplete()']);		    						    														
		    			}    			
		    		});
					
		  	  },
	  	      buttons: {
	  	        "Submit": function() {	  	         	  	        		  	        		  	        	
	  	        	$('#ArticleEditTagsForm').trigger('submit');
	  	        	$(this).dialog( "close" );	  	        	
	  	        },
	  	        Cancel: function() {
	  	        	$(this).dialog( "close" );	  	        	
		  	    }  	        
	  	      }
    		});

    	
		$('.nivoSlider').before('<div class="page-actions"><a id="edit-tags-button" href="#">Edit Tags</a></div>');

		$('#edit-tags-button').click(function() {				
			$( "#edit-tags-popup" ).dialog('open');								
		});

		
					
				
	});	

</script>

<?php
	$data = $this->Js->get('#ArticleEditTagsForm')->serializeForm(array('isForm' => true, 'inline' => true));
	$this->Js->get('#ArticleEditTagsForm')->event(
	   'submit',
	   $this->Js->request(
	    array('action' => 'editTags', 'controller' => 'articles'),
	    array(
			'contentType' => 'application/x-www-form-urlencoded',
	        'update' => '#article-tags',
	        'data' => $data,
	        'async' => true,    
	        'dataExpression'=>true,
			'method' => 'POST',
			'dataType' => 'json',			        
	    )
	  )
	);
	echo $this->Js->writeBuffer(); 
	?> 
