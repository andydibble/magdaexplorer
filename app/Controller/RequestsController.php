<?php
class RequestsController extends AppController {
	
	var $uses = array('Request', 'RequestType', 'KnownEmail', 'User');
	var $adminOnly = array('index');
	var $LOGGED_IN_REQ_ID = 'Request.id';
	
	var $paginate = array(
			'Request'=>array(
					//'order' => array('RequestType.name DESC', 'KnownEmail.email DESC'),
					'limit' => 30,
					'contain' => array('KnownEmail.email', 'RequestType.name')
			)
	);
	
	function beforeFilter() {		
		//deny access to edit or view pages if user is not an admin and has not logged in for the request for which access is sought.
		if (!$this->isAdmin() && ($this->request['action'] == 'edit' || $this->request['action'] == 'view')) {
			if (!$this->Session->check($this->LOGGED_IN_REQ_ID) || 
					$this->params->pass[0] != $this->Session->read($this->LOGGED_IN_REQ_ID)) {
				$this->redirect($this->referer);
			}
		}
		
		parent::beforeFilter();
	}
	
	function view($id) {		
		
		$req = $this->Request->find('first', array(
				'conditions' => array('Request.id' => $id),
				'contain' => array('KnownEmail', 'RequestType.long_name')
		));
		$this->set('req', $req);
	}
		
	/*function add() {
		if ($this->data) {
			$req = $this->data;
			$req['KnownEmail']['email'] = trim($this->data['KnownEmail']['email']);
			
			$extantEmailId = $this->KnownEmail->field('id', array('email' => $req['KnownEmail']['email']));
			$req['KnownEmail']['id'] = $extantEmailId;
			$req['Request']['known_email_id'] = $extantEmailId;
			
			foreach($req['RequestType'] as $i => $rt) {
				if (!$rt['request_type_id']) {
					unset($req['RequestType'][$i]);
				}
			}
			$req['RequestType'] = array_merge($req['RequestType']);
						
			if ($this->Request->saveAll($req) && $this->KnownEmail->save($req['KnownEmail'])) {								
				$purchasedTypeIds = Set::extract('/RequestType/request_type_id', $req);
				$purchasedServices = $this->RequestType->find('list', array('conditions' => array('id' => $purchasedTypeIds)));								
				$purchasedServices = json_encode(array_merge($purchasedServices));
				$this->Session->write('Request.purchasedServices', $purchasedServices);
				
				$purchasedServicesPrices = $this->RequestType->find('list', array(
					'conditions' => array('id' => $purchasedTypeIds),	
					'fields' => array('price'))
				);
								
				if (array_sum($purchasedServicesPrices) > 0) {
					$this->redirect('/requests/complete/');
				} else {
					$this->redirect('/requests/completeFree/');
				}
			}
		}
	
		$types = $this->RequestType->find('all');
		
		$this->set(compact('types'));		
	}*/
	
	function index() {
		$requests = $this->paginate('Request');		
		$this->set('requests', $requests);
	}
	
	function login() {
		if ($confId = $this->data) {
			$reqId = $this->Request->field('id', array('confirmation_id' => $confId['Request']['confirmation_id']));
			if ($reqId) {
				$this->Session->write('Request.id', $reqId);
				$this->redirect('/requests/view/'.$reqId);
			} else {
				$this->Session->setFlash(
						'There is no request with that Confirmation Id.  Please try again or <a href="'.$this->constructSiteLink('/contactMessages', 'add').'">Contact Magda</a> directly.');
			}
		}
	}
	
	function itinerary() {
		$reqType = $this->RequestType->find('first', array('conditions' => array('name' => 'itinerary'), 'recursive' => -1));
		$reqTypeId = $reqType['RequestType']['id'];
				
		if ($this->data) {
			$req = $this->data;
						
			$req['KnownEmail']['email'] = trim($this->data['KnownEmail']['email']);
				
			$extantEmailId = $this->KnownEmail->field('id', array('email' => $req['KnownEmail']['email']));
			$req['KnownEmail']['id'] = $extantEmailId;
			$req['Request']['known_email_id'] = $extantEmailId;
			$req['Request']['request_type_id'] = $reqTypeId;			
			
			//$req = $this->KnownEmail->User->preserveNames($req);
							
			if ($this->KnownEmail->save($req['KnownEmail']) 
					/*&& $this->User->save($req['User'])*/ 
					&& $this->Request->save($req['Request'])) { //TODO: doing this with save all was producing lots of nonsense records.																							
				$this->redirect('/requests/complete/'.$this->Request->id);				 
			} else {
				$this->Session->setFlash('Your request could not be saved.  Please try again.');
			}
		}
		
		$this->set('type', $reqType);
	}
	
	function complete($id) {
				
		$req = $this->Request->find('first', array('conditions' => array('Request.id' => $id), 'contain' => array('RequestType', 'KnownEmail.email')));					
						
		$this->set(compact('reqType', 'req'));			
	}
	
	function completeFree() {
		
	}
	
	
}