<?php
class EmailTextsController extends AppController {
	
	var $uses = array('EmailText');
	
	var $paginate = array(
			'EmailText'=>array(
					'order' => 'EmailText.subject DESC',
					'limit' => 30					
			)
	);
	
	function index() {
		
		$templs = $this->EmailText->find('all');		
		$this->set('templs', $templs);
	}
	
	function edit($id) {
		if ($this->data) {
			pr($this->data);
			if ($this->EmailText->save($this->data)) {
				$this->Session->setFlash('Your changes were saved.');
				$this->redirect('/trips/index');
			} else {
				$this->Session->setFlash('Your changes could not be saved. Please try again.');
			}
		}
		
		$text = $this->EmailText->find('first', array('conditions' => array('id' => $id)));
		$this->set('text', $text);
	}
}