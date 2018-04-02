<?php
class ContactMessagesController extends AppController {
	
	var $uses = array('KnownEmail', 'ContactMessage', 'EmailText', 'RespMessage', 'Request');
	var $adminOnly = array('index', 'respond');
	
	var $paginate = array(
			'ContactMessage'=>array(					
					'order' => 'date_created DESC',					
					//'fields' => array('id', 'short_value', 'date_created'),					
					'limit' => 25,
			));
		
	function index($reqId=null) {
		$this->paginate['ContactMessage']['contain'] = array('KnownEmail.name', 'RespMessage.id', 'Request.confirmation_id', 'Request.id');		
		
		$conds = array();
		if ($reqId) {
			$conds = array('request_id' => $reqId);
		}
		
		$results = $this->paginate('ContactMessage', $conds);	
				
		$this->set('results', $results);
	}

	function add()		
	{
		if ($this->request->is('post') || $this->request->is('put'))
		{
			if ($this->data['KnownEmail']['email'] == $this->data['KnownEmail']['retype_email'])
			{
				$confId = $this->data['Request']['confirmation_id'];				
				if (!$confId || $reqId = $this->Request->field('id', array('confirmation_id' => $confId))) {
					$formData = $this->data;
					
					if (isset($reqId)) {
						$formData['ContactMessage'][0]['request_id'] = $reqId;
					}
					unset($formData['Request']);
					
					$formData['KnownEmail']['id'] = $this->KnownEmail->field('id', array('KnownEmail.email' => $formData['KnownEmail']['email']));
									
					//$formData = $this->User->preserveNames($formData);				
									
					$formData['ContactMessage'][0]['date_created'] = date(Configure::read('DB_DATE_FORMAT'));
														
					if ($this->KnownEmail->saveAll($formData))
					{
						$this->Session->setFlash(__('Thank you. Magda will be in touch with you soon.'));
	
						if ($formData['KnownEmail']['first_name'] || $formData['KnownEmail']['last_name']) {
							$name = $formData['KnownEmail']['first_name'].' '.$formData['KnownEmail']['last_name'];
						} else {
							$name = $formData['KnownEmail']['email'];
						}
						
						//notify Magda of contact message
						$emailFields = array(
								'contactMessage' => $formData['ContactMessage'][0]['value'],
								'name' => $name,
								'respToEmail' => $formData['KnownEmail']['email'],
								'respToUrl' => $this->constructSiteLink('contactMessages', 'respond', $this->ContactMessage->id)
						);
											
						list($body, $this->Email->subject) = $this->EmailText->constructEmail('contact_message_notify', $emailFields);
							
						$this->Email->to = $this->Email->adminEmail();					
						$this->Email->bcc = false;	//do not send duplicate to magda.										
						$this->Email->send($body);
											
						$this->redirect(array('controller' => 'trips', 'action' => 'index'));
							
					} else {
						$this->Session->setFlash(__('Your information could not be saved.  Please try again.'));
					}
				} else {
					$this->Session->setFlash(__('There is no request with that Confirmation Id.  If you do not know the Id for your request, please leave the field blank.'));
				}
			} else {
				$this->Session->setFlash(__('The emails entered do not match.  Please try again.'));
			}
		}
	}
	
	function respond($id) {
			
		$respTo = $this->ContactMessage->find('first', array(
				'conditions' => array('ContactMessage.id' => $id),
				'contain' => array('KnownEmail.name', 'KnownEmail.email', 'Request.id', 'Request.confirmation_id')
		));
		
		if ($resp = $this->data) {
			$resp['RespMessage']['contact_message_id'] = $id;						
			if ($this->RespMessage->save($resp)) {
				
				$this->Email->to = $respTo['KnownEmail']['email'];						
				$this->Email->subject = $this->data['RespMessage']['subject'];
				
				if ($this->Email->send($this->data['RespMessage']['body'])) {				
					$this->Session->setFlash('Your response message has been sent.');
					$this->redirect('/contactMessages/');
				} else {
					$this->RespMessage->delete($this->RespMessage->id);
				}
			}
			
			$this->Session->setFlash('Your response message could not be sent. Please try again.');
		}
		
		$this->set('respTo', $respTo);
	}
}
