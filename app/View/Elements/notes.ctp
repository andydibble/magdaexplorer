<?php if(!isset($adminOnly) || isset($adminOnly) && $isAdmin || isset($adminOnly) && !$adminOnly): ?>
<div style="clear:both">
<h5>Notes <?php if(isset($adminOnly) && $adminOnly): echo '(only visible to admins)'; endif; ?>
</h5>
	<div style="padding-left:15px">
		<ul>
		<?php foreach($notes as $note):?>
		<li><?php echo $note; ?></li>
		<?php endforeach;?>
		</ul>
	</div>
</div>
<?php endif; ?>