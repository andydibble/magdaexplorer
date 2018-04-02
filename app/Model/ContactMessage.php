<?php
class ContactMessage extends AppModel {
	
	public $validate = array(
		'value' => array(
			 'notempty' => array(
			 		'rule' => array('notempty'),
			 		'message' => 'Please enter a question or comment.',
			 		'allowEmpty' => false,
			 		//'required' => false,
			 		//'last' => false, // Stop validation after this rule
			 		//'on' => 'create', // Limit validation to 'create' or 'update' operations
			 ),
		)
	);
	
	public $belongsTo = array(
			'KnownEmail' => array(	
					'className' => 'KnownEmail',
					'foreignKey' => 'known_email_id',
					'conditions' => '',
					'fields' => '',
					'order' => ''
			),
			'Request' => array(
					'className' => 'Request',
					'foreignKey' => 'request_id',
					'conditions' => '',
					'fields' => '',
					'order' => ''
			)
	);
	
	public $hasMany = array(
			'RespMessage' => array(
					'className' => 'RespMessage',
					'foreignKey' => 'contact_message_id',
					'conditions' => '',
					'fields' => '',
					'order' => ''
			)			
	);
	
	public $virtualFields = array(
			'short_value' => "LEFT(`ContactMessage`.`value`, 100)"			
	);
	
	public function afterFind($results, $primary=false)
	{
		$results = $this->formatDates($results, $primary, $this->name, 'date_created', true);		
		return $results;
	}
	
	//TODO: was an attempt to make sorting by related fields work on index page.
	/*function hasField($fieldName, $checkVirtual = false) {
	
		if(parent::hasField($fieldName))
			return true;
		else
			switch($fieldName){
				case "User.username":
					return true;
				default:
					return false;
		}
		return false; // for good measure
	}*/
	
	/*function paginate($conditions, $fields, $order, $limit, $page = 1, $recursive = null, $extra = array()) {
		
		$contain = array(
				'KnownEmail' =>	array(
						'User' => array(
								'City.name' => array(
										'Country.name')
								),
						'ProspectiveUser'
						)
				);
		
		return $this->find('all', compact('conditions', 'contain', 'fields', 'order', 'limit', 'page', 'recursive', 'group'));
	}
	
	function paginateCount($conditions = null, $recursive = 0, $extra = array()) {
		$contain = array(
				'KnownEmail' =>	array(
						'User' => array(
								'City.name' => array(
										'Country.name')
								),
						'ProspectiveUser'
						)
				);
		$this->recursive = $recursive;
		return $this->find('count', compact('conditions', 'contain'));
	}*/
}