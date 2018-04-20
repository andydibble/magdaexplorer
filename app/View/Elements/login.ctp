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
		echo $this->Form->create('Login');
		echo $this->Form->input('password', array('type' => 'text'));
		echo $this->Form->submit(
			'Login', 
				array('div' => array('submit class' => 'center')));
		echo $this->Form->end();			
		?>
	</div>	
	<div class="col-5 no-required right">			
			<?php echo $this->Form->create('KnownEmail', array('action' => 'sign_up')); ?>
			<?php echo $this->Form->input('email', array('placeholder' => "Enter Email", 'label' => 'Need Password?')); ?>
			<?php echo $this->Form->input('send_updates', array('label' => "Receive updates about Magda's adventures?", 'type' => 'checkbox', 'default' => 0)); ?>
			<div class="g-recaptcha" data-sitekey="6LdSklIUAAAAADvrkn6O28wH0-64fdb_J2Eubg-B" data-callback="verifyHumanity"></div>
			<?php echo $this->Form->submit(
			'Submit', 
				array('class' => 'disabled', 
				'disabled' => true,
				'div' => array('class' => 'submit center')));
			echo $this->Form->end();?>
		
	</div>
</div>
<script type="text/javascript">
	$(document).ready(function () {							
		$("#KnownEmailEmail").change(enableBtn);
	});
	
	function verifyHumanity() {			
		$.ajax({
		  type: "POST",
		  url:"../pages/verifyHumanity",
		  data: {"g-recaptcha-response" : $('#g-recaptcha-response').val()},
		  success: function(result) {
			  enableBtn();
		  },			 
		});
	}
	
	
	function enableBtn() {		
		if ($('#g-recaptcha-response').val().length > 0 && $('#KnownEmailEmail').isValidEmailAddress()) {		
			$.reenableSubmits();
		}
	}
	
</script>







