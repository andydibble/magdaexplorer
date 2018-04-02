<?php
App::import('Component', 'Email');

class MyEmailComponent extends EmailComponent {

	public $template = 'default_notify';

	public $adminOnly = array();

	public $ownerOnly = array();

	public $templateVars = null;

	public $contentLayout = null;

	public function initialize(Controller $controller) {
		$this->sendAs = 'html';
		$this->from = $this->smtpOptions['username'];
		$this->layout = 'default';
		$this->controller = $controller;
	}

	public function send($content = null, $sendBccToAdmin = true, $template = null, $layout = null) {
		//when in production, magda/admin gets all corresondence.		
		$adminEmail = $this->adminEmail();

		if (!$template) {
			$template = $this->template;
		}

		if ($content) {
			$this->templateVars['message'] = $content;
		}

		$queued = ClassRegistry::init('EmailQueue.EmailQueue')->enqueue($this->to, $this->templateVars, array(
			'template' => $template,
			'subject' => $this->subject,
			'content_layout' => $this->contentLayout,
			'bcc_admin' => $sendBccToAdmin && $this->to != $adminEmail,
			'format' => $this->sendAs
		));

		return $queued;
	}

	public static function adminEmail() {
		return env('HTTP_HOST') != 'localhost' ? array(Configure::read('MAGDA_MALAK_EMAIL'), Configure::read('DEVELOPER_EMAIL')) : Configure::read('DEVELOPER_EMAIL');
	}

}