<?php echo $this->Html->script('AmendableList.js', array('inline' => false)); ?>
<?php echo $this->Html->script('SpellCheck.js', array('inline' => false)); ?>

<h2>Edit Adventure '<?php echo $adv['Adventure']['title'] ?>'</h2>

<?php echo $this->Form->create('Adventure', array('type' => 'file', 'style' => 'clear:both')) ?>
<?php echo $this->Form->input('id'); ?>

<?php echo $this->Form->input('trip_id', array('label' => 'Trip', 'options' => $trips)) ?>
<?php echo $this->Form->input('is_visible', array('type' => 'checkbox')); ?>
<?php echo $this->Form->input('title', array('div' => 'text-field')); ?>

<?php echo $this->Form->input('story', array('type' => 'textarea', 'class' => 'editable', 'maxLength' => '10000', 'rows' => 10)); ?>

<div id="photo-list">
	<?php foreach($adv['Photo'] as $i => $img):?>
		<div id="adv-photo<?php echo $i ?>" class="form-entry-set">
			<?php echo $this->Form->input('Photo.'.$i.'.title', array('type' => 'text', 'label' => 'Title', 'default' => $img['title'])); ?>
			<?php echo $this->Form->input('Photo.'.$i.'.id', array('default' => $img['id'])); ?>	
			<?php echo $this->Form->input('Photo.'.$i.'.filename', array('default' => $img['filename'], 'type' => 'hidden')); ?>
			<div class="thumbnail">
				<?php //$src = Configure::read('ADV_IMG_PREFIX').'trip'.$adv['Adventure']['trip_id'].'/adventure'.$adv['Adventure']['id'].'/'.$img['filename']; ?>
				<?php $src = Configure::read('ADV_IMG_PREFIX').'/adventure'.$adv['Adventure']['id'].'/'.$img['filename']; ?>
				<img id="pic<?php echo $img['id']; ?>" src="<?php echo $src ?>" title="<?php echo $img['title']; ?>" />
				<?php $picId = +$img['id']; ?>
				<div>
					<?php echo $this->Html->tag('span', 'Delete', array('id' => 'deleteLabel'.$picId)); ?>
					<?php echo $this->Form->checkbox('delete', array('value' => $picId)); ?>
					
				</div>
			</div>
			<?php echo $this->Form->input('Photo.'.$i.'.adventure_id', array('options' => $advList, 'style' => 'width:300px')); ?>
		</div>
	<?php endforeach; ?>
	
	<?php if(isset($this->request->data['Photo'])): ?>
		<?php $lastPhotoIndex = count($this->request->data['Photo']); ?>	
	<?php else: ?>
		<?php $lastPhotoIndex = 0; ?>
	<?php endif;?>
	
	<div id="adv-photo<?php echo $lastPhotoIndex ?>" class="form-entry-set">
	<?php echo $this->Form->input('Photo.'.$lastPhotoIndex.'.file', array('type' => 'file', 'label' => 'Photo '.($lastPhotoIndex+1))); ?>	
	<?php echo $this->Form->input('Photo.'.$lastPhotoIndex.'.title'); ?>	
	</div>
</div>
<br><br><br>
<?php echo $this->Form->input('city', array('div' => 'text-field', 'label' => 'City or location')); ?>
<?php echo $this->Form->input('date'); ?>

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
<br>

<div id="comments" style="clear:both">
	<?php if(count($adv['Comment']) > 0): ?>
	<span class="comments-title">Comments</span>	
	<div class="actions">
	<a id="select-all">Select All</a>
	<a id="deselect-all">Deselect All</a>
	</div>
	<?php endif; ?>
		
	<table>
	<?php foreach($adv['Comment'] as $i => $cmt): ?>	
	<tr>
		<td>		
		<?php echo $this->Form->input('Comment.'.$i.'.delete', array('type' => 'checkbox', 'default' => $cmt['id'], 'label' => 'Delete'))?>		
		</td>
		<td>
		<?php echo $this->Form->input('Comment.'.$i.'.id'); ?>
		<?php echo $this->Form->input('Comment.'.$i.'.value', array('type' => 'textarea', 'rows' => 3)); ?>		
		</td>
	</tr>	
	<?php endforeach;?>
	</table>		
</div>

<div class="submit">
<span class="button submit-button">Submit</span>
</div>

<?php echo $this->Form->end(); ?>

<script>
$(document).ready(function() {

	var editors = makeEditors(['AdventureStory']);
	spellCheckOnSubmit('.submit-button', '#AdventureEditForm', editors, saveEditorContent);

	var newTag = <?php echo Configure::read('NEW_TAG_HTML'); ?>;
	var newPhoto = <?php echo Configure::read('NEW_PHOTO_HTML'); ?>;
		
	AmendableList.amendListListener('input[id$=<?php echo $lastTagIndex ?>Name]', '#tag-list', newTag, <?php echo Configure::read('MAX_NUM_TAGS') ?>, ['setupAutocomplete()']);
	AmendableList.amendListListener('#adv-photo<?php echo $lastPhotoIndex?>', '#photo-list', newPhoto, <?php echo Configure::read('MAX_NUM_PHOTOS') ?>);

	addressAutocomplete('#AdventureCity');
	
	$("input[id=AdventureDelete]").change(function(event) {
		
		var picId = event.target.value;		
		parts = $('#pic'+picId).attr('src').split("/");			
		$.confirm(
			'Are you sure you want to delete image ' + parts[parts.length-1] + ' from this Adventure?',
			function(result) {
				if (result) {
					parts = $('#pic'+picId).attr('src').split('img');				
					
					$.ajax({
						type: "GET",				
		    			url: APPROOT+'photos/delete',
		    			dataType: 'json',
		    			data: {id: picId, filePath: parts[1], advId: <?php echo $adv['Adventure']['id'] ?>, tripId: <?php echo $adv['Adventure']['trip_id'] ?>},
		    			contentType: "application/json; charset=utf-8",    			
		    			success: function(data) {								    				
		    				window.location = window.location;	//refresh		//TODO: right now this clears images the user wants to upload--put flash message in a popup that can be displayed async.											
		    			}    			
		    		});
				}
	    	}
		);		
	});
});


$(document).ready(function() {
	$('#select-all').click(function() {
		$('#comments').find(':checkbox').prop('checked', true);
	});

	$('#deselect-all').click(function() {
		$('#comments').find(':checkbox').prop('checked', false);
	});
});
</script>
