<?php echo $this->Html->script('AmendableList.js', array('inline' => false)); ?>
<?php echo $this->Html->script('SortableList.js', array('inline' => false)); ?>

<h2>Edit Article</h2>

<div class="actions">
<?php if ($magId): ?>
<?php echo $this->Html->link('Back to Magazine', '/magazines/view/'.$magId)?>
<?php else: ?>
<?php echo $this->Html->link('Back to Magazine', '/magazines/index/')?>
<?php endif; ?>
</div>

<?php echo $this->Form->create('Article', array('type' => 'file', 'style' => 'clear:both')) ?>
<?php echo $this->Form->input('id') ?>
<?php echo $this->Form->input('magazine_id', array('label' => 'Magazine', 'options' => $mags)) ?>

<?php echo $this->Form->input('is_visible', array('type' => 'checkbox', 'default' => 1)); ?>
<?php echo $this->Form->input('name', array('div' => 'text-field')); ?>
<?php echo $this->Form->input('comments', array('type' => 'textarea', 'maxLengeth' => '10000', 'rows' => 5)); ?>

<?php echo $this->Form->input('upload_date', array(
		'type' => 'date', 
		'div' => 'text-field', 
		'maxYear' => date('Y'), 
		'minYear' => date('Y') - 5)); ?>
<?php echo $this->Form->input('published', array(
		'type' => 'date', 
		'div' => 'text-field', 
		'dateFormat' => 'MY', 
		'maxYear' => date('Y'), 
		'minYear' => date('Y') - 5)); ?>

<div id="scan-list">	
	<?php foreach($art['Scan'] as $i => $img):?>
	<div id="article-scan<?php echo $i ?>" class="form-entry-set draggable">		
		<?php echo $this->Form->input("Scan.$i.order", array('type' => 'hidden', 'default' => $i)); ?>
		<?php echo $this->Form->input("Scan.$i.id", array('default' => $img['id'])); ?>	
			
		<div class="thumbnail">			
			<?php $src = Configure::read('ART_IMG_PREFIX').'/art'.$art['Article']['id'].'/'.$img['filename']; ?>
			<?php $scanId = $img['id']; ?>
			<img id="scan<?php echo $scanId ?>" src="<?php echo $src ?>" />
			
			<div>
				<?php echo $this->Html->tag('span', 'Delete', array('id' => 'deleteLabel'.$scanId)); ?>
				<?php echo $this->Form->checkbox('delete', array('value' => $scanId)); ?>				
			</div>
		</div>
	</div>			
	<?php endforeach; ?>
	
	<?php $i = count($art['Scan']); //add new scan upload field?>
	<div id="article-scan<?php echo $i ?>" class="form-entry-set draggable">
		<?php echo $this->Form->input("Scan.$i.file", array('type' => 'file', 'label' => "Scan ".($i+1))); ?>
		<?php echo $this->Form->input("Scan.$i.order", array('type' => 'hidden', 'default' => $i)); ?>
	</div>
	
</div>
			
<br>
<br>
<br>	

<?php echo $this->Form->input('is_tagged', array('type' => 'checkbox', 'label' => 'Tagging is complete?')); ?>


<?php if(isset($this->request->data['Tag'])): ?>
	<?php $lastTagIndex = count($this->request->data['Tag']); ?>
	<?php if($lastTagIndex == Configure::read('MAX_NUM_TAGS')):  ?>
		<?php $lastTagIndex = Configure::read('MAX_NUM_TAGS')-1;	//prevent addition of extra field. ?>	
	<?php endif; ?>
<?php else: ?>
	<?php $lastTagIndex = 0; ?>
<?php endif;?>

<div id="tag-list" class="form-entry-set">

<?php for($i = 0; $i <= $lastTagIndex; $i ++):  //extant experiences (+1 for possible new exp) ?>
	<?php 
	if(isset($this->request->data['Tag'][$i])) {
		$tag = $this->request->data['Tag'][$i];
	} else {
		unset($tag);
	} ?>
	<div>
	<?php echo $this->Form->input('Tag.'.$i.'.name', array('label' => 'Tag '.($i+1), 'div' => 'autocomplete', 'default' => isset($tag['name']) ? $tag['name'] : ''))?>
	</div>
<?php endfor; ?>
</div>

<?php echo $this->Form->end('Submit'); ?>

<script>
$(document).ready(function() {	
	setupAutocomplete();

	var editors = makeEditors(['ArticleComments']);

	reorderOnSort('order', 'Scan', '#scan-list');
		
	var newTag = <?php echo Configure::read('NEW_TAG_HTML'); ?>;
	var newScan = <?php echo Configure::read('NEW_SCAN_HTML'); ?>;
	var curNumScans = $('#scan-list').children().length;
	var curNumTags =  $('#tag-list').children().length;
			
	AmendableList.amendListListener('input[id$='+(curNumTags-1)+'Name]', '#tag-list', newTag, <?php echo Configure::read('MAX_NUM_TAGS') ?>, ['setupAutocomplete()']);
	AmendableList.amendListListener('#article-scan'+(curNumScans-1), '#scan-list', newScan, 20);	
});

$(document).ready(function() {		
	$("input[id=ArticleDelete]").change(function(event) {
		
		var scanId = event.target.value;		
		parts = $('#scan'+scanId).attr('src').split("/");			
		$.confirm(
			'Are you sure you want to delete image ' + parts[parts.length-1] + ' from this Article?',
			function(result) {
				if (result) {
					parts = $('#scan'+scanId).attr('src').split('img');				
					
					$.ajax({
						type: "GET",				
		    			url: APPROOT+'scans/delete',		    			
		    			data: {id: scanId, filePath: parts[1], artId: <?php echo $art['Article']['id'] ?>},		    			    			
		    			success: function(data) {								    				
		    				window.location = window.location;	//refresh		//TODO: right now this clears images the user wants to upload--put flash message in a popup that can be displayed async.											
		    			}    			
		    		});
				}
	    	}
		);		
	});
});


</script>
