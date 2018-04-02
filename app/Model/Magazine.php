<?php
class Magazine extends AppModel {
	
	public $validate = array(
			'name' => array(
					'notempty' => array(
							'rule' => array('notempty'),
							'message' => 'Please enter a name.',
							'allowEmpty' => false
					),
					'unique' => array(
							'rule' => 'isUnique',
							'required' => 'create',
							'message' => 'This magazine already exists.',
					)
			)
			
	);
	
	public $hasMany = array(
			'Article' => array(
					'className' => 'Article',
					'foreignKey' => 'magazine_id',
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
	
	public function beforeSave($options=array()) {
		if (!empty($this->data['Magazine']['id'])) {
			$this->id = $this->data['Magazine']['id'];
		}
		if ($mergeToMagId = $this->data['Magazine']['merge_to']) {
			if ($this->Article->updateAll(array('magazine_id' => $mergeToMagId), array('magazine_id' => $this->id))) {				
				return true;
			}
			return false;				
		}
		
	}
}