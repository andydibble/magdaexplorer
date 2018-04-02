<h2>Events Calendar</h2>

<?php echo $this->Html->script('jstz.min.js', array('inline' => false)) ?>

<?php $this->Html->scriptStart(array('inline' => false))?>
var timezone = jstz.determine().name();
<?php $this->Html->scriptEnd(); ?>

<?php echo $this->Element('notes', array('notes' => array(
	'To edit and add events here login to <b>gmail</b> account <b>justlikeus2</b> with password <b>experience1machine</b>.',
	'To designate the Host of an event, put Host: (username) in one line of the description field.',		
	), 'adminOnly' => true
));
?>

<script>
var urlTimezone = timezone.replace("/", "%2F");
var urlTimezone = timezone.replace(" ", "_");
var src = 'https://www.google.com/calendar/embed?height=600&amp;wkst=1&amp;bgcolor=%23FFFFFF&amp;src=vu7kdudgu5d6dco2hvad8qu1ts%40group.calendar.google.com&amp;color=%23B1440E&amp;ctz='+urlTimezone;
$('#content').append('<iframe src="'+ src +'" style="border:solid 1px #777; clear:none;" width="960" height="700" frameborder="0" scrolling="no"></iframe>');
</script>


