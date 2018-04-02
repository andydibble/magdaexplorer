<?php echo $this->Html->script('AmendableList.js', array('inline' => false)); ?>
<?php echo $this->Html->script('SortableList.js', array('inline' => false)); ?>

<h2>Upload Article</h2>

<div class="actions">
<?php if ($magId): ?>
<?php echo $this->Html->link('Back to Magazine', '/magazines/view/'.$magId)?>
<?php else: ?>
<?php echo $this->Html->link('Back to Magazine', '/magazines/index/')?>
<?php endif; ?>
</div>

<?php echo $this->Form->create('Article', array('type' => 'file', 'style' => 'clear:both')) ?>
<?php echo $this->Form->input('magazine_id', array('label' => 'Magazine', 'options' => $mags, 'default' => !empty($magId) ? $magId : 1)) ?>

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
	<div id="article-scan0" class="form-entry-set draggable">
	<?php echo $this->Form->input('Scan.0.file', array('type' => 'file', 'label' => 'Scan 1')); ?>
	<?php echo $this->Form->input('Scan.0.order', array('type' => 'hidden', 'default' => 0)); ?>
	</div>
</div>
<br>
<br>
<br>	

<div id="tag-list" class="form-entry-set">
<?php echo $this->Form->input('Tag.0.name', array('label' => 'Tag 1', 'div' => 'autocomplete'))?>
</div>

<?php echo $this->Form->end('Submit'); ?>

<script>
$(document).ready(function() {	
	var editors = makeEditors(['ArticleComments']);

	reorderOnSort('order', 'Scan', '#scan-list');
		
	var newTag = <?php echo Configure::read('NEW_TAG_HTML'); ?>;
	var newScan = <?php echo Configure::read('NEW_SCAN_HTML'); ?>;
		
	AmendableList.amendListListener('input[id$=0Name]', '#tag-list', newTag, <?php echo Configure::read('MAX_NUM_TAGS') ?>, ['setupAutocomplete()']);
	AmendableList.amendListListener('#article-scan0', '#scan-list', newScan, 20);	
});
</script>