<?php
/**
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       Cake.View.Layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

//$cakeDescription = __d('cake_dev', 'CakePHP: the rapid development php framework');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<?php echo $this->Html->charset(); ?>
	<title>
		<?php echo $title_for_layout ?>
	</title>
	<meta name="description"
	      content="Magda Explorer, a travel blog by a woman who has been everywhere.  Follow her adventures.">

	<style type="text/css">@media print {
			.gmnoprint {
				display: none
			}
		}

		@media screen {
			.gmnoscreen {
				display: none
			}
		}</style>
	<?php
	echo $this->Html->meta('icon', $this->Html->url('/favicon.png'));

	//--start styles--//
	echo $this->Html->css('me.generic');
	echo $this->Html->css('me.specific');
	echo $this->Html->css('twm.specific');
	echo $this->Html->css('jquery.ui/jquery-ui-1.10.1.custom.min');

	echo $this->Html->css('mbt/mbtnav');

	if (!empty($isAdmin) && $isAdmin):
		echo $this->Html->css('geo-autocomplete/jquery.autocomplete.css'); //has some modifications for place autocomp.

		echo $this->Html->css('/JavaScriptSpellCheck/extensions/fancybox/jquery.fancybox-1.3.4.css');
	endif;

	//--start scripts--//
	echo $this->Html->script('jquery/jquery-1.7.1.js');
	echo $this->Html->script('jquery/utility.js');
	echo $this->Html->script('jquery/jquery.simpleWeather.min.js');

	//echo $this->Html->script("http://maps.google.com/maps/api/js?sensor=false");

	if (!empty($isAdmin) && $isAdmin):
		echo $this->Html->script('geo-autocomplete/jquery.autocomplete_geomod');
		echo $this->Html->script('geo-autocomplete/geo_autocomplete');
		echo $this->Html->script('SetupAutocomplete.js');
		?>

		<?php echo $this->Html->script('http://js.nicedit.com/nicEdit-latest.js', array('inline' => false)); ?>
		<?php echo $this->Html->script('TextEditor.js'); ?>

		<?php echo $this->Html->script('/JavaScriptSpellCheck/include.js'); ?>
		<?php echo $this->Html->script('/JavaScriptSpellCheck/extensions/fancybox/jquery.fancybox-1.3.4.pack.js'); ?>

		<?php echo $this->Html->script('jstz.min.js') ?>

		<?php echo $this->Html->script('jquery/ajax-form.js') ?>

	<?php endif; ?>

	<?php echo $this->Html->script('jquery.ui/jquery-ui-1.10.1.custom.min'); //load this last so that tag autocomplete works ?>
	<?php if ($isAdmin && $isMobile): //must be after jquery.ui ?>
		<?php echo $this->Html->script('jquery.ui/jquery.ui.touch-punch.min.js'); ?>
	<?php endif; ?>

	<?php echo $this->Html->script('Likeable.js'); ?>
	<?php echo $this->Html->script('Toggleable.js'); ?>

	<script type="text/javascript" language="javascript">
		var APPROOT = <?php echo "'".Configure::read('APPROOT')."'"; ?>

			$.ajaxSetup({
				type: "GET",
				dataType: 'json',
				contentType: 'application/x-www-form-urlencoded',//; charset=utf-8",
				<?php if($isAdmin):?>
				beforeSend: function () {
					$('#loader').show();
				},
				complete: function () {
					$('#loader').hide();
				},
				<?php endif; ?>
			});

	</script>

	<?php
	echo $this->fetch('meta');
	echo $this->fetch('css');
	echo $this->fetch('script');
	?>

</head>
<body>
<div id="container" style="width:100%">

	<?php echo $this->Element('header_background_image'); ?>
	<div id="content-parent" class="center">
		<?php echo $this->Session->flash(); ?>

		<?php if ($this->request['action'] !== 'login'): ?>
			<?php if ($this->request['controller'] == 'articles'): ?>
				<?php echo $this->Element('Articles/header'); ?>
			<?php else: ?>
				<?php echo $this->Element('header'); ?>
			<?php endif; ?>
		<?php endif; ?>

		<div id="content">
			<?php echo $this->fetch('content'); ?>
		</div>
		<div id="footer">
		</div>
	</div>
</div>

<?php if ($isAdmin): ?>
	<div id="loader">
		<?php //echo $this->Html->image('static/animation/ajax-loader.gif', array('alt' => 'Wait')); ?>
	</div>
<?php endif; ?>

<?php echo $this->element('sql_dump'); ?>
</body>
</html>
