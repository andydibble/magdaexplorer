<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<div id="login-content" class="center">
	<img id="main-logo"
	     src="<?php echo Configure::read('PHOTO_PATH_PREFIX') . Configure::read('WEBROOT') . 'img/layout/logo.png' ?>"/>

	<div class="center">
		<?php
		echo $this->Session->flash('auth');
		echo $this->Form->create('Login', array('class' => 'center'));
		echo $this->Form->input('password', array());
		echo $this->Form->end('Login');
		?>
	</div>
	<div class="hidden-content-toggler">
		<h5 id="need-password"><a>Need password?</a></h5>
	</div>
	<div class="hidden-content">
		<?php echo $this->Form->create('KnownEmail', array('action' => 'sign_up', 'class' => 'center')); ?>
		<?php echo $this->Form->input('email', array('label' => null)); ?>
		<?php echo $this->Form->input('send_updates', array('type' => 'hidden', 'default' => 0)); ?>
		<div class="g-recaptcha" data-sitekey="6LdSklIUAAAAADvrkn6O28wH0-64fdb_J2Eubg-B"></div>
		<?php echo $this->Form->end('Submit'); ?>
	</div>
</div>
<script type="text/javascript">
	jQuery(document).ready(function () {
		jQuery(".hidden-content").hide();

		jQuery(".hidden-content-displayer").click(function () {
			jQuery(this).nextAll(".hidden-content").slideToggle(500);
		});

		//set default text for email field.
		var emailField = $('#KnownEmailEmail');
		emailField.attr('class', 'default-text');
		emailField.val('Email');
		emailField.focus(function () {
			emailField.val('');
			emailField.attr('class', 'focused-field');
			emailField.off('focus');
		});
		
		$('#KnownEmailSignUpForm').submit(function(ev) {						
			ev.preventDefault();
			$.ajax({
			  type: "POST",
			  url:$(this).attr('action'),
			  data: $(this).serialize(),
			  success: function(result) {
				  console.log(result);
			  },			 
			});
		});
	});
</script>







