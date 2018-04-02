<?php if($this->request['action'] != 'login'): ?>
<div id="background-banner">	
	<?php if(isset($headerBackground)): ?>
	<?php if($this->request['controller'] == 'articles'):?>	
	<?php echo $this->Html->image('/'.$headerBackground, array('fullBase' => true, 'id' => 'background-banner-image'))?>
	<?php else: ?>
	<?php echo $this->Html->image('layout/headerBkgr/'.$headerBackground, array('fullBase' => true, 'id' => 'background-banner-image'))?>
	<?php endif; ?>
	<?php endif; ?>	
</div>
<?php endif; ?>

