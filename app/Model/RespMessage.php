<?php
class RespMessage extends AppModel {
	
	public $validate = array(
			'body' => array(
				 'notempty' => array(
				 		'rule' => array('notempty'),
				 		'message' => 'Please enter an email body.',
				 		'allowEmpty' => false,
				 		//'required' => false,
				 		//'last' => false, // Stop validation after this rule
				 		//'on' => 'create', // Limit validation to 'create' or 'update' operations
				 ),
			),
			'subject' => array(
					'notempty' => array(
							'rule' => array('notempty'),
							'message' => 'Please enter an email subject.',
							'allowEmpty' => false,
							//'required' => false,
							//'last' => false, // Stop validation after this rule
							//'on' => 'create', // Limit validation to 'create' or 'update' operations
					),
			)
	);
	
	public $belongsTo = array(
			'ContactMessage' => array(
					'className' => 'ContactMessage',
					'foreignKey' => 'contact_message_id',
					'conditions' => '',
					'fields' => '',
					'order' => ''
			)
	);
}