<?php

class Tag extends AppModel
{

	var $hasAndBelongsToMany = array(
		'Adventure' => array(
			'className' => 'Adventure',
			'joinTable' => 'adventures_tags',
			'foreignKey' => 'tag_id',
			'associationForeignKey' => 'adventure_id',
			'unique' => true,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'finderQuery' => '',
			'deleteQuery' => '',
			'insertQuery' => ''
		),
		'Article' => array(
			'className' => 'Article',
			'joinTable' => 'articles_tags',
			'foreignKey' => 'tag_id',
			'associationForeignKey' => 'article_id',
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

	public $hasMany = array(
		'AdventuresTag' => array(
			'className' => 'AdventuresTag',
			'foreignKey' => 'tag_id',
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
			'foreignKey' => 'tag_id',
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


	public function findIdByName($name)
	{
		return $this->field('id', array('Tag.name' => $name));
	}

	/**
	 * Messy solution to make Tag.count field and sorting by Tag.count work (actually COUNT(AdventuresTag.adventure_id) or COUNT(ArticlesTag.article.id)
	 * @param unknown_type $conditions
	 * @param unknown_type $fields
	 * @param unknown_type $order
	 * @param unknown_type $limit
	 * @param unknown_type $page
	 * @param unknown_type $recursive
	 * @param unknown_type $extra
	 * @return Ambigous <multitype:, NULL, mixed, multitype:unknown >
	 */
	function paginate($conditions, $fields, $order, $limit, $page = 1, $recursive = null, $extra = array())
	{

		if (isset($order['Tag.count'])) {
			$findArr = compact('conditions', 'fields', 'limit', 'page', 'recursive', 'group');
		} else {
			$findArr = compact('conditions', 'fields', 'limit', 'order', 'page', 'recursive', 'group');
		}

		if (array_key_exists('Trip.id', $conditions)) {
			$tags = $this->findTagsByTripId($conditions['Trip.id'], 'all');
			$tableName = 'adventures_tags';
		} else if (array_key_exists('Magazine.id', $conditions)) {
			$tags = $this->findTagsByMagId($conditions['Magazine.id'], 'all');
			$tableName = 'articles_tags';
		} else {
			$tags = $this->find('all', $findArr);
			$tableName = 'adventures_tags'; //TODO: make this all tags option also apply to articles.
		}

		$counts = $this->findCounts($tableName);

		foreach ($tags as $i => &$tag) {
			foreach ($counts as $count) {
				if ($count[$tableName]['tag_id'] == $tag['Tag']['id']) {

					$tags[$i]['Tag']['count'] = $count[0]['count'];
					break;
				}
			}
			if (!isset($tag['Tag']['count'])) {
				$tag['Tag']['count'] = 0;
			}
		}

		if (isset($order['Tag.count'])) { //TODO: really messy--kills off related data.
			if ($order['Tag.count'] == 'asc') {
				$order = SORT_ASC;
			} else {
				$order = SORT_DESC;
			}

			$tags = Set::extract('/Tag/.', $tags);
			$this->arraySortByColumn($tags, 'count', $order);
			foreach ($tags as $i => $tag) {
				$tags[$i] = array('Tag' => $tag);
			}
		}

		return $tags;
	}

	function findCounts($tableName, $tagIds = array())
	{
		if (!empty($tagId)) {
			$havingClause = 'HAVING `tag_id` IN (' . implode(', ', $tagIds) . ')';
		} else {
			$havingClause = '';
		}
		return $this->query('SELECT `tag_id`, COUNT(*) as count FROM `' . $tableName . '` GROUP BY ' . $havingClause . ' `tag_id` ORDER BY `tag_id`');
	}

	function paginateCount($conditions = null, $recursive = 0, $extra = array())
	{
		$this->recursive = $recursive;
		if (array_key_exists('Trip.id', $conditions)) {
			$count = $this->findTagsByTripId($conditions['Trip.id'], 'count');
		} else if (array_key_exists('Magazine.id', $conditions)) {
			$count = $this->findTagsByMagId($conditions['Magazine.id'], 'count');
		} else {
			$count = $this->find('count', array('conditions' => $conditions));
		}
		return $count;
	}

	/**
	 * Retrieve a list of tagId => tagName pairs, considering all adventures for a given trip.
	 * @param unknown_type $tripId
	 * @return unknown
	 */
	function findTagsByTripId($tripId, $findType = 'list')
	{

		$tripTagIds = $this->Adventure->query('SELECT `AdventuresTag`.`tag_id` FROM `adventures` AS `Adventure` JOIN `adventures_tags` AS `AdventuresTag` ON (`AdventuresTag`.`adventure_id` = `Adventure`.`id`) JOIN `tags` AS `Tag` ON (`AdventuresTag`.`tag_id` = `Tag`.`id`) WHERE `Adventure`.`trip_id` = ' . $tripId . ' ORDER BY `Tag`.`name` DESC');
		$tripTagIds = Set::extract('/AdventuresTag/tag_id', $tripTagIds);

		$tags = $this->find($findType, array(
			'conditions' => array('Tag.id' => $tripTagIds),
			'order' => 'Tag.name ASC'
		));

		return $tags;
	}

	function findTagsByLocationId($locId)
	{

		$tripIds = $this->Adventure->Trip->Location->tripIds($locId);
		$tags = array();
		foreach ($tripIds as $id) {
			$tags += $this->findTagsByTripId($id);
		}

		return $tags;
	}

	function findTagsByMagId($magId, $findType = 'list')
	{

		$artIdsForMag = $this->Article->find('list', array(
			'fields' => array('id'),
			'conditions' => array('magazine_id' => $magId)
		));

		$this->loadModel('ArticlesTag');

		$magTagIds = $this->ArticlesTag->find('list', array(
			'fields' => array('tag_id'),
			'conditions' => array('article_id' => $artIdsForMag)
		));

		$tags = $this->find($findType, array(
			'conditions' => array('Tag.id' => $magTagIds),
			'order' => 'Tag.name ASC'
		));

		return $tags;
	}

	/**
	 * Makes tags and adventures,articles, etc. tables agree id-wise.  If a tag name in $modelName is
	 * never-seen, the tag is added to tags (if $syncDb is set); sets tag_ids in $modelData to the tag_ids in
	 * tags
	 * @param unknown_type $modelData data passed.
	 * @param unknown_type $id parent record id of the data passed.
	 * @param unknown_type $syncDb makes new tags be added to
	 * @return unknown
	 */
	public function synchronizeTagData($modelData, $parentId = null, $idFieldName, $syncDb = false)
	{
		$modelName = 'Tag';
		$idFieldName = 'tag_id';

		$listified = false;
		if (!isset($modelData[$modelName][0])) {
			$listified = true;
			$modelData[$modelName][0] = $modelData[$modelName];
		}

		foreach ($modelData[$modelName] as $i => &$record) {

			if ($parentId) {
				$record[$idFieldName] = $parentId;
			}

			if (!empty($record['name'])) {
				if (!isset($record[$idFieldName]) || !$record[$idFieldName]) {
					if ($id = $this->findIdByName($record['name'])) {
						$record[$idFieldName] = $id;
					} else if ($syncDb) {
						$this->create();
						$this->save(array('name' => $record['name']));
						$record[$idFieldName] = $this->id;
					}
				}
			} else { //remove empty tags from post data.
				unset($modelData[$modelName][$i]);
			}
			unset($modelData[$modelName][$i]['id']);
		}

		if ($listified) {
			$modelData[$modelName] = $modelData[$modelName][0];
		}

		return $modelData;
	}

	/**
	 * Delete any tags that refer to no adventures or articles.
	 */
	public function deleteOrphans()
	{
		$this->query("
		delete from tags
		where
			not exists (select * from articles_tags where articles_tags.tag_id = tags.id)
			and	not exists (select * from adventures_tags where adventures_tags.tag_id = tags.id)"
		);
	}
}