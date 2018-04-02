<?php
class Request extends AppModel {
	
	public $validate = array(
			'general_interests' => array(
					'notempty' => array(
							'rule' => array('notempty'),
							'message' => 'Please tell us about your vacation interests.',
							'allowEmpty' => false,
							//'required' => false,
							//'last' => false, // Stop validation after this rule
							//'on' => 'create', // Limit validation to 'create' or 'update' operations
					),
			)
	);
	
	/*var $hasAndBelongsToMany = array(
			'RequestType' => array(
					'className' => 'RequestType',
					'joinTable' => 'requests_request_types',
					'foreignKey' => 'request_id',
					'associationForeignKey' => 'request_type_id',
					'unique' => true,
					'conditions' => '',
					'fields' => '',
					'order' => '',
					'limit' => '',
					'offset' => '',
					'finderQuery' => '',
					'deleteQuery' => '',
					'insertQuery' => ''
			));*/
	
	public $belongsTo = array(
			'KnownEmail' => array(
					'className' => 'KnownEmail',
					'foreignKey' => 'known_email_id',
					'conditions' => '',
					'fields' => '',
					'order' => ''
			),
			'RequestType' => array(
					'className' => 'RequestType',
					'foreignKey' => 'request_type_id',
					'conditions' => '',
					'fields' => '',
					'order' => ''
			)
	);
	
	public $hasMany = array(
			'ContactMessage' => array(
					'className' => 'ContactMessage',
					'foreignKey' => 'request_id',
					'conditions' => '',
					'fields' => '',
					'order' => ''
			)
	);
	
	public $virtualFields = array(
			'status' =>
			"CASE
				WHEN `Request`.`completed` THEN 'Complete'
				WHEN `Request`.`paid` THEN 'Paid'
				WHEN `Request`.`canceled` THEN 'Cancelled'
				WHEN `Request`.`refunded` THEN 'Cancelled and Refunded'
				ELSE 'Unpaid'				
			END"
	
	);
	
	public function beforeSave($options=array()) {
		$this->data['Request']['confirmation_id'] = Utility::randomString(6, 'ABCDEFGHIJKLMNOPQRSTUVWXYZ');
		return true;
	}
	
	public function afterFind($results, $primary=false)
	{
		$results = $this->formatDates($results, $primary, $this->name, array('created', 'completed', 'paid', 'canceled', 'refunded'), true);
		return $results;
	}
}