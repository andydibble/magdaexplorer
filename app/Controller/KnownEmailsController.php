<?php
class KnownEmailsController extends AppController {

	var $uses = array('Adventure', 'Article', 'KnownEmail', 'Text', 'EmailText');

	var $components = array('Image');

	function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('sign_up');
		$this->Auth->allow('unsubscribe');
	}

	var $paginate = array(
			'KnownEmail'=>array(
					'order' => 'KnownEmail.id DESC',
					'limit' => 30,
					'contain' => array()
			)
	);

	var $adminOnly = array('edit', 'sendAdventure', 'sendAdventureIter');

	var $SEND_DATA_KEY = 'KnownEmail.sendData';

	function add() {

		if ($this->data) {
			$updates = $this->data['KnownEmail']['updates'];
			$matches = array();
			if (preg_match_all($this->KnownEmail->VAL_EMAIL_REGEX, $updates, $matches)) {
				$this->KnownEmail->saveUnknownEmails($matches[0], 1, 1);
			}
				
			$noUpdates = $this->data['KnownEmail']['no_updates'];
			$matches = array();
			if (preg_match_all($this->KnownEmail->VAL_EMAIL_REGEX, $noUpdates, $matches)) {
				$this->KnownEmail->saveUnknownEmails($matches[0], 1, 1);
			}
				
			$this->redirect('/knownEmails/edit');
		}
	}

	/**
	 * Edit page for known emails
	 * if $isConfirm is true, then this page is used as final confirm of recipient list before sending of email
	 * @param unknown_type $isConfirm
	 */
	function edit() {
		$sendData = $this->request->params['named'];

		if (!$this->Session->check($this->SEND_DATA_KEY) && $sendData) {
			$this->Session->write($this->SEND_DATA_KEY, $sendData);
		}

		if ($this->data) {
			$emails = $this->data;
				
			//for adding recipients just before sending.
			if (isset($emails['KnownEmail']['new'])) {
				$additionalRecips = $this->data['KnownEmail']['new'];
				$matches = array();
				if (preg_match_all($this->KnownEmail->VAL_EMAIL_REGEX, $additionalRecips, $matches)) {
					$this->KnownEmail->saveUnknownEmails($matches[0], 1, 1);
				}
				//delete the additional recipients data.
				unset($emails['KnownEmail']);
			}
		}

		$emails = $this->paginate('KnownEmail');
		$this->set('emails', $emails);

		if ($this->Session->check($this->SEND_DATA_KEY)) {
			$this->set('sendData', $this->Session->read($this->SEND_DATA_KEY));
		}
	}

	function cancelEmails() {
		$this->Session->delete($this->SEND_DATA_KEY);
		$this->redirect('/locations/index');
	}

	/**
	 * Send an email to $email with the site password.
	 * @param unknown_type $email
	 */
	function sendPassword($email) {

		$this->Email->to = $email;
		$this->Email->sendAs = 'html';
		$this->Email->from = Configure::read('CONTACT_EMAIL');

		$emailFields = array('password' => Configure::read('PASSWORD'));
		list($body, $this->Email->subject) = $this->EmailText->constructEmail('password', $emailFields);

		$this->Email->send($body);
	}

	/**
	 * Add an email to known_emails, if the email posted does not already exist.  The email can be saved either as requesting updates or not.
	 * If updates are not requested, assumes the password should be sent to the user.
	 */
	function sign_up() {
		if($email = $this->data['KnownEmail']['email']) {
			$record = $this->data;
			$curEmail = $this->KnownEmail->find('first', array('conditions' => array('email' => $email)));
			if ($curEmail) {
				$emailId = $curEmail['KnownEmail']['id'];
				$record['KnownEmail']['id'] = $emailId;
				unset($record['KnownEmail']['email']);
			}
				
			$isAskingForUpdates = $record['KnownEmail']['send_updates'];
				
			if ($curEmail['KnownEmail']['send_updates']) {	//prevent un-signing people up if they re-ask for the password.
				$record['KnownEmail']['send_updates'] = 1;
			}
			if ($this->KnownEmail->save($record)) {
				$emailId = $this->Email->id;
				if ($isAskingForUpdates == 1) {
					$this->Session->setFlash("Thank you.  You will now receive updates on Magda's Adventures.");
				} else {
					$this->sendPassword($email);
					$this->Session->setFlash("Please check your email for the password to Magda Explorer.");
				}
			} else {
				$this->Session->setFlash($this->KnownEmail->validationErrors['email'][0]);
			}
		}


		$this->redirect($this->referer());
	}

	/**
	 * Prepares session data for sending email update for a specific adventure to all emails in known_emails which have
	 * send_updates set.  Also sets a flag telling js on the page to begin a series of ajax requests, which call
	 * sendAdventureIter()
	 * @param unknown_type $advId
	 */
	public function send() {

		if ($this->isAdmin()){
				
			$sendData = $this->Session->read($this->SEND_DATA_KEY);
						
			if ($sendData) {
				$this->set('sendData', $sendData);

				$emails = $this->KnownEmail->find('list', array(
						'fields' => array('id', 'email'),
						'conditions' => array('send_updates' => 1)
				));

				switch($sendData['model']) {
					case 'Adventure':
						$adv = $this->Adventure->find('first', array(
						'conditions' => array('Adventure.id' => $sendData['id']),
						'contain' => array('Photo')
						));

						$this->Email->templateVars['adv'] = $adv;
						pr($adv);
						break;
					case 'Article':
						$art = $this->Article->find('first', array(
						'conditions' => array('Article.id' => $sendData['id']),
						'contain' => array('Magazine', 'Scan' => array('limit' => 1))
						));

						$art['Scan'][0]['url'] = Router::url('/'.$this->Image->articleImgDir($sendData['id']).$art['Scan'][0]['filename'], true);

						$this->Email->templateVars['art'] = $art;
						break;
				}

				$templateName = Inflector::lcfirst($sendData['model']);
							
				$this->Email->template = $templateName;
				$this->Email->sendAs = 'html';
				$this->Email->from = Configure::read('CONTACT_EMAIL');
				$this->Email->subject = $this->Text->field('value', array('name' => 'send_'.$templateName.'_email_subject'));

				$this->Email->to = $emails;

				if($this->Email->send(null, false)) {
					$this->{$sendData['model']}->markAsSent($sendData['id']);
					$this->Session->setFlash('Your '.$sendData['model'].' is on its way to subscribers.');
					$this->Session->delete($this->SEND_DATA_KEY);
				}								
			}
			
			$this->redirect('/knownEmails/edit');
		}
	}

	function sendUpdatesToAll() {
		if ($this->RequestHandler->isAjax()) {
			$this->autoRender=false;
			if ($this->KnownEmail->updateAll(array('send_updates' => 1))) {
				return json_encode(array('success' => true));
			} else {
				return json_encode(array('success' => false));
			}
		}
	}

	function sendUpdatesToNone() {
		if ($this->RequestHandler->isAjax()) {
			$this->autoRender=false;
			if ($this->KnownEmail->updateAll(array('send_updates' => 0))) {
				return json_encode(array('success' => true));
			} else {
				return json_encode(array('success' => false));
			}
		}
	}

	function update() {
		$success = false;
		if ($this->RequestHandler->isAjax()) {
			$this->autoRender=false;
			$knownEmail = $this->request->query;
			if (isset($knownEmail['delete'])) {
				$success = $this->KnownEmail->delete($knownEmail['id']);
			} else {
				if($this->KnownEmail->save($knownEmail)) {
					$success = true;
				}
			}
		}
		return json_encode(array('success' => $success));
	}

	function unsubscribe() {
		if ($email = $this->request->query['email']) {
			if($this->KnownEmail->deleteAll(array('email' => urldecode($email)))) {
				$this->Session->setFlash('Your email has been deleted from our database.');
			}
		}
	}
	
}