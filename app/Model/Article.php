<?php

class Article extends AppModel {

	var $actsAs = array('Sendable');

	public $validate = array(
		'name' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Please enter a name.',
				'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			)
		)
	);

	var $hasAndBelongsToMany = array(
		'Tag' => array(
			'className' => 'Tag',
			'joinTable' => 'articles_tags',
			'foreignKey' => 'article_id',
			'associationForeignKey' => 'tag_id',
			'unique' => true,
			'conditions' => '',
			'fields' => '',
			'order' => 'Tag.name ASC',
			'limit' => '',
			'offset' => '',
			'finderQuery' => '',
			'deleteQuery' => '',
			'insertQuery' => ''
		));

	public $hasMany = array(
		'Scan' => array(
			'className' => 'Scan',
			'foreignKey' => 'article_id',
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
		'ArticlesTag' => array(
			'className' => 'ArticlesTag',
			'foreignKey' => 'article_id',
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

	public $belongsTo = array(
		'Magazine' => array(
			'className' => 'Magazine',
			'foreignKey' => 'magazine_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

	public $virtualFields = array(
		'published_display' => "CONCAT(MONTHNAME(`Article`.`published`), ' ', YEAR(`Article`.`published`))",
		'published_year' => "YEAR(`Article`.`published`)"
	);


	public $findMethods = array('similar' => true);

	function _findSimilar($state, $query, $results = array()) {
		if ($state == 'before' && isset($query['tags'])) {
			/*$query['joins'] = array(
				array(
					'table' => 'articles_tags',
					'alias' => 'ArticlesTag',
					'type' => 'inner',
					'conditions' => array('ArticlesTag.article_id = Article.id')
				)
			);*/
			if (!is_array($query['tags'])) {
				$query['tags'] = array($query['tags']);
			}

			$query['conditions'] = empty($query['conditions']) ? array() : $query['conditions'];
			if (isset($query['tags']['without'])) {
				$query['conditions'] = array_merge($query['conditions'], $this->__findSimilarConditions($query, 'without'));
			}
			if (isset($query['tags']['with'])) {
				$query['conditions'] = array_merge($query['conditions'], $this->__findSimilarConditions($query, 'with'));
			}

			return $query;
		} elseif ($state == 'after' && isset($query['tags'])) {
			$retval = array();
			foreach ($results as $key => $result) {
				$tags = Hash::extract($result, 'ArticlesTag.{n}.tag_id');
				//if (count($query['tags']['with']) == count(array_intersect($tags, $query['tags']['with']))) {   //TODO: need this filter??
				$retval[] = $results[$key];
				//}
			}
			$results = $retval;
		}
		return $results;
	}

	private function __findSimilarConditions($query, $mode = 'with') {
		$db = $this->ArticlesTag->getDataSource();
		if (isset($query['tags'][$mode])) {
			foreach ($query['tags'][$mode] as $tagId) {
				$conditionsSubQuery = array(
					'`Article`.`id` = `ArticlesTag`.`article_id`',
					'`ArticlesTag`.`tag_id`' => $tagId);
				$subQuery = $db->buildStatement(
					array(
						'fields' => array(1),
						'table' => $db->fullTableName($this->ArticlesTag),
						'alias' => 'ArticlesTag',
						'conditions' => $conditionsSubQuery,
					),
					$this->ArticlesTag
				);

				$subQuery = ($mode === 'without' ? 'NOT' : '') . ' EXISTS (' . $subQuery . ') ';
				$subQueryExpression = $db->expression($subQuery);
				$conditions[] = $subQueryExpression;
			}
			return $conditions;
		}
	}

	//TODO: turn this into findType pagination (so I don't have to implement custom pagination)?
	public function paginate($conditions, $fields, $order, $limit, $page = 1, $recursive = null, $extra = array()) {
		//pr(get_defined_vars());
		$query = compact('conditions', 'fields', 'order', 'limit', 'recursive', 'page');
		if (isset($extra['contain'])) {
			$query['contain'] = $extra['contain'];
		}
		if (isset($extra['group'])) {
			$query['group'] = $extra['group'];
		}
		if (isset($extra['tags'])) {
			$query['tags'] = $extra['tags'];
			$results = $this->find('similar', $query);
		} else {
			$results = $this->find('all', $query);
		}
		return $results;
	}

	function paginateCount($conditions = null, $recursive = 0, $extra = array()) {
		$this->recursive = $recursive;

		$query = compact('conditions', 'recursive');

		if (isset($extra['tags'])) {

			$query['tags'] = $extra['tags'];
			//preventing it from pulling back too much.	//TODO: fix this mess.
			$query['contain'] = array('Magazine.name', 'Tag.id');
			$query['fields'] = array('Article.id');

			//was sometimes returning duplicates...
			$results = $this->find('similar', $query);
			$ids = array_unique(Hash::extract($results, '{n}.Article.id'));
			$count = count($ids); //TODO: make a find method that allows a count query to be done--not just count in PHP.
		} else {
			$count = $this->find('count', $query);
		}

		return $count;
	}

	function findFilterTags($filterTagsQuery) {
		if (isset($filterTagsQuery['tags'])) { //tags for a query by tags
			$filterTagsQuery['fields'] = 'DISTINCT Article.id';
			$filterTagsQuery['contain'] = array('Tag.id', 'Tag.name');
			$filterTags = $this->find('similar', $filterTagsQuery);
		} else if (empty($filterTagsQuery)) { //tags for an all-articles query
			$ids = $this->ArticlesTag->find('list', array(
				'fields' => 'tag_id'
			));
			$filterTags = $this->Tag->find('all', array(
				'recursive' => -1,
				'fields' => array('DISTINCT Tag.name', 'Tag.id'),
				'conditions' => array('Tag.id' => $ids),
			));
		} else { //has conditions, but none about which tags articles shoudl have.
			$filterTags = $this->find('all', array(
				'conditions' => $filterTagsQuery,
				'fields' => array('DISTINCT Article.id'),
				'contain' => array('Tag.name', 'Tag.id')));

		}
		$names = array_unique(Set::extract('/Tag/name', $filterTags));
		$ids = array_unique(Set::extract('/Tag/id', $filterTags));

		if (Set::check($filterTags, '{n}.Article.id')) {
			$artIds = Set::extract('/Article/id', $filterTags);
			$whereClause = 'WHERE `article_id` IN (' . implode(',', $artIds) . ')';
		} else { //i.e. if no limitation on articles.
			$whereClause = '';
		}

		$counts = $this->query(
			"SELECT `tag_id`, COUNT(*) as count FROM (SELECT `tag_id` FROM `articles_tags` AS `ArticleTag` $whereClause) as `Tag` GROUP BY `tag_id` ORDER BY `tag_id`");

		$filterTags = array();
		foreach ($ids as $i => $id) {
			foreach ($counts as $count) {
				if ($count['Tag']['tag_id'] == $id) {
					$filterTags[$i]['id'] = $id;
					$filterTags[$i]['name'] = $names[$i];
					$filterTags[$i]['count'] = $count[0]['count'];
					break;
				}
			}
		}
		if (empty($filterTags)) {
			return array();
		} else {
			return Set::sort($filterTags, '/name', 'ASC');
		}
	}

	public function beforeSave($options = array()) {
		if (isset($this->data['Tag'])) {
			$this->data = $this->Tag->synchronizeTagData($this->data, null, 'article_id', true);
		}

		return parent::beforeSave($options);
	}

	public function afterSave($created) {
		if (!$created) {
			$this->Tag->deleteOrphans();
		}
	}

	public function afterDelete() {
		$this->Tag->deleteOrphans();
	}
}