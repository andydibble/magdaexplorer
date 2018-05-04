<div class="comments row">
	<div class="col-12">
	<?php echo $this->Form->create('Comment', array('action'=>'add', 'class'=>'col-12', 'type' => 'post')); ?>
	<?php echo $this->Form->input('Comment.adventure_id', array('type' => 'hidden', 'default' => $parentId));?>	
	<?php echo $this->Form->input('Comment.value', array('div' => 'rounded-field', 'type' => 'textarea', 'label' => '', 'rows' => 3, 'maxLength' => 2000));?>
	<?php echo $this->Form->end('Post');?>
	</div>
</div>
<script>
$(function() {
	
//$("[id^='CommentAddForm'").find('input[type="submit"]').disable();
$("[id^='CommentAddForm'").submit(function(ev) {
	ev.preventDefault();
		$.ajax({
		  type: "POST",
		  url:$(this).attr('action'),
		  data: $(this).serialize(),
		  success: function(result) {
			  $.reenableSubmits();
			  $('#poll-resp-div ul').find('.empty-list-note').remove();
			  $('#poll-resp-div ul').append('<li>'+result+'</li>');
		  },			 
		});
	
	
}
)});
</script>