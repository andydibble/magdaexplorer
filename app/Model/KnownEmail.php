<?php
class KnownEmail extends AppModel {
	
	var $VAL_EMAIL_REGEX = '/[a-z0-9!#$%&\'*+\/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&\'*+\/=?^_`{|}~-]+)*@[a-z0-9!#$%&\'*+\/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&\'*+\/=?^_`{|}~-]+)*/';
	
	public $validate = array(			
			'email' => array(
					'notempty' => array(
							'rule' => array('notempty'),
							'message' => 'Please provide an email.',
							'allowEmpty' => false,
							'required' => false,
							//'last' => false, // Stop validation after this rule
							//'on' => 'create', // Limit validation to 'create' or 'update' operations
					),
					'email' => array(
							'rule' => array('email'),
							'message' => 'The email entered is not a valid email.',
							//'allowEmpty' => false,
							'required' => false,
							//'last' => false, // Stop validation after this rule
							'on' => 'create', // Limit validation to 'create' or 'update' operations
					),
					'isUnique' => array(
							'rule' => array('isUnique'),
							'message' => 'Sorry, the email entered already exists in our records.  Please try another.',
					)
			),
	);
	
	
	public $hasMany = array(
			'ContactMessage' => array(
					'className' => 'ContactMessage',
					'foreignKey' => 'known_email_id',
					'conditions' => '',
					'fields' => '',
					'order' => ''
			),
			'Request' => array(
					'className' => 'Request',
					'foreignKey' => 'known_email_id',
					'conditions' => '',
					'fields' => '',
					'order' => ''
			)
	);
	
	public $hasOne = array(
			'User' => array(
					'className' => 'User',
					'foreignKey' => 'known_email_id',
					'conditions' => '',
					'fields' => '',
					'order' => ''
			)
	);
	
	public $virtualFields = array(
			'name' =>
			"CASE
				WHEN `KnownEmail`.`first_name` != '' OR `KnownEmail`.`last_name` != '' THEN CONCAT(`KnownEmail`.`first_name`, ' ', `KnownEmail`.`last_name`)
				ELSE `KnownEmail`.`email`
			END"
	
	);
	
	public function saveUnknownEmails($emails, $sendUpdates=0, $addedByAdmin=0) {
				
		foreach($emails as $email) {
			$email = trim($email);
			
			$this->create();
			$this->save(array(
					'email' => $email,
					'send_updates' => $sendUpdates,
					'is_added_by_admin' => $addedByAdmin
			));
			
		}
	}	
}