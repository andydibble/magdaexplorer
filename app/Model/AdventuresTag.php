<?php
class AdventuresTag extends AppModel {
	public $belongsTo = array(
			'Adventure' => array(
					'className' => 'Adventure',
					'foreignKey' => 'adventure_id',
					'conditions' => '',
					'fields' => '',
					'order' => ''
			),
			'Tag' => array(
					'className' => 'Tag',
					'foreignKey' => 'tag_id',
					'conditions' => '',
					'fields' => '',
					'order' => ''
			)
	);
	
	/**
	 * Returns list of id => name pairs consisting of all tags in all adventrues.
	 * @return multitype:
	 */
	public function findAllAdvTags() {
		$tags = $this->find('all', array(
				'contain' => array('Tag'),
				'fields' => array()
			));
		
		$names = Set::extract($tags, '/Tag/name');							
		$ids = Set::extract($tags, '/Tag/id');
		
		return array_combine($ids, $names);
	}
}