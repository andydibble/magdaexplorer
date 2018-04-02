<?php
App::uses('AppModel', 'Model');
/**
 * User Model
 *

 */
class User extends AppModel {
/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array();


	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(		
		'KnownEmail' => array(
				'className' => 'KnownEmail',
				'foreignKey' => 'known_email_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
		)
	);

	
	/**
	 * Should be called before saving user data to prevent good name data from being overwritten
	 * //TODO: try to put this in beforeSave (cann't now because it won't work with saveAll)
	 * @param unknown_type $formData
	 */
	function preserveNames($formData) {
		if ($emailId = $formData['KnownEmail']['id']) {	//TODO: try to put this in beforeSave--right now can't because of saveAll.
			if ($emailId) {
				//make sure old user data does not get overwritten if new data was not entered.
				$user = $this->KnownEmail->User->find('first', array(
						'fields' => array('id', 'first_name', 'last_name'),
						'recursive' => -1,
						'conditions' => array('User.known_email_id' => $emailId)
				));
					
				if ($user['User']) {
					$formData['User']['id'] = $user['User']['id'];
						
					if (!$formData['User']['first_name']) {
						$formData['User']['first_name'] = $user['User']['first_name'];
					}
					if (!$formData['User']['last_name']) {
						$formData['User']['last_name'] = $user['User']['last_name'];
					}
				}
			}
		}

		return $formData; 
	}
}
