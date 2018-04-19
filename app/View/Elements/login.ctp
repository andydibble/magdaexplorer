<script src="https://www.google.com/recaptcha/api.js" async defer></script>
	<img id="main-logo" class="center"
	     src="<?php echo Configure::read('PHOTO_PATH_PREFIX') . Configure::read('WEBROOT') . 'img/layout/logo.png' ?>"/>	
<div class="center">
<?php echo $this->Session->flash(); ?>
</div>
<div class="row">
	<div class="col-5">
		<?php
		echo $this->Session->flash('auth');
		echo $this->Form->create('Login', array('class' => 'center'));
		echo $this->Form->input('password', array('type' => 'text'));
		echo $this->Form->end('Login');
		?>
	</div>	
	<div class="col-5 no-required right">			
			<?php echo $this->Form->create('KnownEmail', array('action' => 'sign_up', 'class' => 'center')); ?>
			<?php echo $this->Form->input('email', array('placeholder' => "Enter Email", 'label' => 'Need Password?')); ?>
			<?php echo $this->Form->input('send_updates', array('label' => "Send updates about Magda's adventures", 'type' => 'checkbox', 'default' => 0)); ?>
			<div class="g-recaptcha" data-sitekey="6LdSklIUAAAAADvrkn6O28wH0-64fdb_J2Eubg-B"></div>
			<?php echo $this->Form->end('Submit'); ?>
		
	</div>
</div>
<script type="text/javascript">
	jQuery(document).ready(function () {			
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







