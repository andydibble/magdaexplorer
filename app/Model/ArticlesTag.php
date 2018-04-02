<?php
class ArticlesTag extends AppModel {
	public $belongsTo = array(
			'Article' => array(
					'className' => 'Article',
					'foreignKey' => 'article_id',
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
}