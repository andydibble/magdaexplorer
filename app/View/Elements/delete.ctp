<?php $funcName = 'Delete'; ?>
<?php $confMess = "Are you sure you want to permanently delete \'".str_replace("'", "\'", $title)."\'?"; ?>
<a href="#" onclick="$.confirm('<?php echo $confMess; ?>', function(result) { if (result) { $('#Delete<?php echo $model.$id;?>').submit(); } }); event.returnValue = false; return false;"><?php echo $funcName ?></a>

<?php $controller = Inflector::pluralize($model)?>

<form action="<?php echo Configure::read('APPROOT').$controller.'/'.$funcName.'/'.$id;?>" name="Delete<?php echo $model?>" id="Delete<?php echo $model.$id;?>" style="display:none;" method="post">
	<input type="hidden" name="_method" value="POST">
</form>