<?php

class Adventure extends AppModel
{
	var $actsAs = array('Sendable');

	public $validate = array(
		'title' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Please enter a title.',
				'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'date' => array(
			'notempty' => array(
				'rule' => array('date'),
				'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		)
	);

	/**
	 * hasMany associations
	 *
	 * @var array
	 */
	public $hasMany = array(
		'Comment' => array(
			'className' => 'Comment',
			'foreignKey' => 'adventure_id',
			'dependent' => true,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'Photo' => array(
			'className' => 'Photo',
			'foreignKey' => 'adventure_id',
			'dependent' => true,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'AdventuresTag' => array(
			'className' => 'AdventuresTag',
			'foreignKey' => 'adventure_id',
			'dependent' => true,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);

	var $hasAndBelongsToMany = array(
		'Tag' => array(
			'className' => 'Tag',
			'joinTable' => 'adventures_tags',
			'foreignKey' => 'adventure_id',
			'associationForeignKey' => 'tag_id',
			'unique' => true,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'finderQuery' => '',
			'deleteQuery' => '',
			'insertQuery' => ''
		));

	public $belongsTo = array(
		'Trip' => array(
			'className' => 'Trip',
			'foreignKey' => 'trip_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

	public function beforeFind($queryData)
	{
		if (!isset($queryData['contain']['AdventuresTag'])) {
			$this->unBindModel(array('hasMany' => array('AdventuresTag')));
			$this->Tag->unBindModel(array('hasMany' => array('AdventuresTag')));
		}
	}

	public function beforeSave($options = array())
	{

		if (isset($this->data['Tag'])) {
			$this->data = $this->Tag->synchronizeTagData($this->data, null, 'adventure_id', true);
		}

		return parent::beforeSave($options);
	}

	public function afterFind($results, $primary = false)
	{

		$results = $this->formatDates($results, $primary, 'Adventure', 'date');
		return $results;
	}

	function afterDelete()
	{
		$this->Tag->deleteOrphans();

		//$this->Trip->updatePostCount($);
		//$this->Trip->updatePhotoCount($tripId);
	}

	public function afterSave($created)
	{
		if (!empty($this->data['Adventure']['trip_id'])) {
			$tripId = $this->data['Adventure']['trip_id'];
			if ($created) {
				$this->Trip->updatePostCount($tripId);
			}
			$this->Trip->updateEndDate($tripId, $this->data['Adventure']['date']);
			$this->Trip->updatePhotoCount($tripId);
		}

		if (!$created) {
			$this->Tag->deleteOrphans();
		}
	}


}