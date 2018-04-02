<?php
App::uses('ShellDispatcher', 'Console');
class ChronsController extends AppController {
	var $useTable = false;

	function beforeFilter() {
		
		//TODO: authenticate and see if user is admin here.  Perhaps set $authorize in MyAuthComponent to 'controlelr' and make isAuthorized action in UsersController
				
		$this->Auth->allow('sendEmailBatch', 'sendReminders');					
		
	}

	function sendEmailBatch() {
		//TODO: could alternatively use gethostname() php function and run this from the shell directly. (need hostname to set environment correctly)
		
		$this->autoRender = false;
			
		$command = '-app '.APP.' EmailQueue.Sender -l 8';
				
		$args = explode(' ', $command);
		$dispatcher = new ShellDispatcher($args, false);
		$dispatcher->dispatch();
	}
}