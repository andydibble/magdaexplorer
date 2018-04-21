<div id="poll-header">
	<div class="section-header" id="poll-desc">
		<div class="poll-prompt">Poll: <?php echo $texts['poll_prompt'] ?>
		</div> <span id="poll-toggler"
		             class="page-element-toggler hidden-content-toggler rounded-field">+</span><br>
		<span class="poll-caption"><?php echo $texts['poll_prompt_caption'] ?>
		</span>
	</div>
	<div class="hidden-content">
		<div id="poll-resp-div">
			<?php if (count($resps) != 0): ?>
				<?php $firstListCount = count($resps) ?>
				<ul class="poll-resps">
					<?php for ($i = 0; $i < $firstListCount; $i++): ?>
						<li class="poll-resp"><?php echo $resps[$i]; ?>
						</li>
					<?php endfor; ?>
				</ul>
			<?php else: ?>
				<ul class="poll-resps">
					<li class="empty-list-note">No responses.</li>
				</ul>
			<?php endif; ?>
		</div>
		<div class="section-footer">
			<div id="poll-resp-form">
				<?php echo $this->Form->create('PollResponse', array('class' => "horz-form", 'action' => 'respond/' . $tripId)) ?>
				<?php echo $this->Form->input('value', array('label' => '', 'style' => 'clear:none')) ?>
				<?php echo $this->Form->end('Submit Response') ?>
			</div>
		</div>
	</div>
</div>

<script>

$(function() {
$("[id^='PollResponseRespond'").submit(function(ev) {
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
