<?php
class LoginsController extends AppController {

	var $uses = array('Login', 'Text');
	var $adminOnly = array('add','delete','log');
	var $paginate = array('Login' => array(
		'order' => 'date DESC',
		'limit' => 10));

	function index() {

		//to fix issue with production code not recognizing venue as a field.
		$this->paginate['Login']['fields'] = array_keys($this->Login->getColumnTypes());

		$logins = $this->paginate('Login');

		$logins = Set::extract('/Login/.', $logins);
		$this->set(compact('logins'));	
	}

	/**
	 * Called on admin login to record location and timezone information.
	 */
	function add() {	
		//override normal non-rendering of view for ajax.
		/*if ($this->RequestHandler->isAjax()) {
			$this->autoRender = true;		
			$this->layout = 'ajax'; 
		}*/
			
		if ($this->data) {			
			$newLogin = $this->data;
			$newLogin[$this->Login->name]['date'] = date(Configure::read('DB_DATE_FORMAT'));
			if ($this->Login->save($newLogin)) {
				//update city text in header to be consistent
				$this->Text->id = $this->Text->field('id', array('name' => 'check_in_city'));						
				$this->Text->save(array('value' => $newLogin[$this->Login->name]['city']));				
				
				//update venue too
				$this->Text->id = $this->Text->field('id', array('name' => 'check_in_venue'));
				$this->Text->save(array('value' => $newLogin[$this->Login->name]['venue']));
				
				$this->Session->setFlash('Your Check-in was saved.');
				$this->redirect('/trips/index/' . $id);				
			} else {
				$this->Session->setFlash('Your Check-in could not be saved.  Please try again.');
			}
			
			
		}			
	}

	/**
	 * Delete via ajax.
	 * @return string
	 */
	public function delete() {
		$this->prepAjax();

		$id = $this->request->query['id'];

		if ($this->Login->delete(array('id' => $id))) {
			$this->Session->setFlash('Your Login has been deleted.');
			return json_encode(array('success' => true));
		} else {
			$this->Session->setFlash('Your Login could not be deleted.  Please try again.');
			return json_encode(array('success' => false));
		}
	}
	
	function logError() {
		$this->log($this->data['message']);
	}

}