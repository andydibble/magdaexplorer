<?php

class ArticlesController extends AppController {

	public $components = array('Image', 'Like', 'Visit');

	public $uses = array('Article', 'Tag', 'Scan', 'ArticlesTag', 'Trip');

	public $adminOnly = array('add', 'edit', 'delete', 'editTags');

	public $paginate = array(
		'Article' => array(
			'group' => 'Article.id',
			'order' => 'Article.published DESC',
			'fields' => array('Article.published', 'Article.published_display', 'Article.name', 'Article.id', 'Article.is_visible', 'likes', 'visits'),
			'contain' => array('Magazine.name'),
			'limit' => 25,
		));

	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('index');
		$this->Auth->allow('view');
		$this->Auth->allow('like');
	}

	public function beforeRender() {
		$navTrips = $this->Trip->find('list', array(
			'conditions' => array("OR" => array('is_main_page' => 1, 'is_urban_watermelon' => 1))));

		//get random header image.
		$allArticleImgs = glob($this->Image->ARTICLE_HEADER_BKGR_DIR . '/*.*');
		$img = array_rand($allArticleImgs);
		$headerBackground = $allArticleImgs[$img];

		$this->set(compact('headerBackground', 'navTrips'));
	}

	public function index() {
		$this->set('title_for_layout', 'Traveling with Magda');

		//start manage request params//
		$query = $this->request->query;
		if (!empty($query['newSearch'])) {
			//remove old query data, because new published/magazine search means a new query.
			$this->Session->delete('Article.tagQuery');
			$this->Session->delete('Article.searchQuery');
			$this->Session->delete('Article.prevSearchQuery');
		}

		if (isset($query['tags']) &&
			$tagQuery = $query['tags']
		) { //save tag query for later querying by tag ids.
			$tagInclusionKey = !isset($query['withTag']) || $query['withTag'] == 1 ? 'with' : 'without';

			$prevTagQuery = $this->Session->read("Article.tagQuery.{$tagInclusionKey}");
			if ($prevTagQuery) {
				$newTagQuery = array_unique(array_merge($prevTagQuery, explode(',', $tagQuery)));
			} else {
				$newTagQuery = explode(',', $tagQuery);
			}
			$this->Session->write("Article.tagQuery.{$tagInclusionKey}", $newTagQuery);
		}

		if (!empty($this->request->query['newSearch'])) {
			$this->redirect('/articles/index');
		}
		//end manage request params//


		//save query from post date if passed.
		if ($query = $this->data) {

			$query = Set::filter($query['Article']);
			unset($query['see_all']);

			//save unprocessed query in a form suitable for display of search conditions.
			$this->Session->write('Article.prevSearchQuery', $this->prevSearchQuery($query));

			if (isset($query['published'])) { //make query correct if only date or month is passed.
				if (isset($query['published']['year']) && $year = $query['published']['year']) {
					$query['YEAR(`published`)'] = $year;
				}
				if (isset($query['published']['month']) && $month = $query['published']['month']) {
					$query['MONTH(`published`)'] = $month;
				}
			}
			unset($query['published']);

			$this->Session->write('Article.searchQuery', $query);

		}

		//run query and paginate.  If one is saved, use that query.  Otherwise, assume all results.
		$query = $this->Session->check('Article.searchQuery') ?
			$this->Session->read('Article.searchQuery') :
			array();

		if (!$this->isAdmin()) {
			$query['Article.is_visible'] = true;
		}

		$filterTagsQuery = $query;
		if ($this->Session->check('Article.tagQuery')) {
			$filterTagIds = $this->Session->read('Article.tagQuery');
			$this->paginate['Article']['tags'] = $filterTagIds;
			$filterTagsQuery['tags'] = $filterTagIds;
		}
		$filterTags = $this->Article->findFilterTags($filterTagsQuery);

		$this->paginate['Article']['order'] = '';
		if ($this->isAdmin()) { //to display whether article has been tagged properly yet.
			$this->paginate['Article']['fields'][] = 'is_tagged';
			$this->paginate['Article']['order'] = 'Article.is_tagged ASC, ';
		}
		$this->paginate['Article']['order'] .= ' Article.published DESC, Article.name ASC';

		$results = $this->paginate('Article', $query);

		$this->set('tags', $filterTags);
		$this->set('results', $results);


		//so that previous tag query can be amended and displayed.
		$prevTagQuery = array();
		if ($this->Session->check("Article.tagQuery.with")) {
			$prevTagQuery['with'] = $this->Tag->find('list', array(
				'conditions' => array(
					'id' => $this->Session->read("Article.tagQuery.with"),
				)
			));
		}
		if ($this->Session->check("Article.tagQuery.without")) {
			$prevTagQuery['without'] = $this->Tag->find('list', array(
				'conditions' => array(
					'id' => $this->Session->read("Article.tagQuery.without"),
				)
			));
		}
		$this->set('prevTagQuery', $prevTagQuery);

		if ($this->Session->check("Article.prevSearchQuery")) { //TODO: name this better (Article.displayQuery) and merge with prevTagQuery above.
			$this->set('prevSearchQuery', $this->Session->read("Article.prevSearchQuery"));
		}

		//to configure search fields.
		$maxYear = $this->Article->field('published', array(), array('published DESC'));
		$minYear = $this->Article->field('published', array(), array('published ASC'));
		if ($this->isAdmin()) {
			$mags = $this->Article->Magazine->find('all', array('recursive' => -1));
		} else {
			$mags = $this->Article->Magazine->find('all', array('conditions' => array('Magazine.is_visible' => true), 'recursive' => -1));
		}

		$this->set(compact('mags', 'minYear', 'maxYear'));
	}


	public function add($magId = 0) {

		if ($this->data) {
			$art = $this->data;

			$art['Article']['published']['day'] = 1;

			if ($this->Article->save($art['Article'])) { //create basic adventure, so that id can be obtained for picture directory.								

				$art['Article']['id'] = $this->Article->id;

				$uploadDir = $this->Image->articleImgDir($art['Article']['id']);
				$result = $this->Image->uploadFiles($uploadDir, $art['Scan']);

				if (!empty($result['errMessage'])) {
					$this->Article->delete($this->Article->id); //delete the basic art if picture upload fails.
					$this->Session->setFlash($result['errMessage']);
				} else {
					if (!empty($result['successFilenames'][0])) {
						foreach ($result['successFilenames'] as $i => $fname) {

							$imgUrl = $this->Image->articleImgDir($this->Article->id) . $fname;

							//$smallerImgUrl = $this->Image->reduceResolution($imgUrl/*$result['urls'][$i]*/, 960);
							//if ($smallerImgUrl) {
							//$art['Scan'][$i]['filename'] = basename($smallerImgUrl);
							//} else {
							$art['Scan'][$i]['filename'] = $fname;
							//}

							list($width, $height) = $this->Image->imageSize($imgUrl);
							$art['Scan'][$i]['width'] = $width;
							$art['Scan'][$i]['height'] = $height;
						}

						//remove empty photos from post data.
						foreach ($art['Scan'] as $i => $photo) {
							if (empty($art['Scan'][$i]['filename'])) {
								unset($art['Scan'][$i]);
							}
						}
					} else {
						unset($art['Scan']);
					}

					if ($this->Article->saveAll($art)) { //save scan and tag data.
						$this->Session->setFlash('Your new Article has been saved.');

						$this->redirect('/articles/add/' . $magId);
					} else {
						$this->Session->setFlash('Your new Article could not be saved. Please try again.');
					}
				}
			}
		}

		$this->set('mags', $this->Article->Magazine->find('list'));
		$this->set('magId', $magId);
	}

	public function view($id) {

		$this->Visit->visit($id);

		$art = $this->Article->find('first', array(
			'conditions' => array('Article.id' => $id),
			'contain' => array(
				'Magazine.name',
				'Tag.id', 'Tag.name',
				'Scan' => array(
					'order' => array('order')
				)),

		));

		$this->set('title_for_layout', $art['Article']['name']);

		$this->set('art', $art);
	}

	public function searchResults($tagId) {

		$this->Article->bindModel(array('hasOne' => array('ArticlesTag')), false);
		$arts = $this->Article->find('all', array(
			'group' => 'Article.id',
			'conditions' => array('ArticlesTag.tag_id' => $tagId),
			'order' => array('published DESC'),
			'fields' => array('id', 'name', 'published', 'published_display', 'published_year'),
			'contain' => array('Magazine.name', 'ArticlesTag.tag_id')
		));

		$this->set('tagName', $this->Article->Tag->field('name', array('Tag.id' => $tagId)));
		$this->set('arts', $arts);
	}


	public function delete($id) {

		$magId = $this->Article->field('magazine_id', array('id' => $id));

		if ($this->Article->delete(array('id' => $id))) {
			$this->Session->setFlash('Your Article has been deleted.');
			$dirPath = $this->Image->articleImgDir($id); //TODO: test!!

			$this->Image->deleteDir($dirPath);
		} else {
			$this->Session->setFlash('Your Article could not be deleted.  Please try again.');
		}

		$this->redirect('/magazines/view/' . $magId);
	}


	public function editTags() {

		$success = 0;
		if ($tags = $this->data) {
			if ($this->Article->saveAll($tags)) {
				$success = 1;
				$tags = Set::extract('/Tag/.', $tags);
			}
		}
		$tags = $this->ArticlesTag->find('all', array(
			'fields' => array('ArticlesTag.id'),
			'conditions' => array('article_id' => $this->data['Article']['id']),
			'contain' => array('Tag.name', 'Tag.id')
		));


		$tags = Set::extract('/Tag/.', $tags);

		$this->set(compact('tags'));
		$this->render('/Elements/Articles/tags', 'ajax');
	}

	public function edit($id, $returnTo = null) {

		$art = $this->Article->find('first', array(
			'conditions' => array('Article.id' => $id),
			'contain' => array(
				'Magazine.name',
				'Tag.id', 'Tag.name',
				'Scan' => array(
					'order' => array('order')
				)),

		));

		$this->set('art', $art);

		if ($this->data) {
			$art = $this->data;
			$art['Article']['published']['day'] = 1; //so that published saves correctly

			$uploadDir = $this->Image->articleImgDir($art['Article']['id']);
			$result = $this->Image->uploadFiles($uploadDir, $art['Scan']);

			if (!empty($result['errMessage'])) {
				$this->Session->setFlash($result['errMessage']);
			} else {
				$imgData = $art['Scan'];
				debug($imgData);

				//handle changes to old pictures.
				foreach ($imgData as $pic) {
					if (!empty($pic['id'])) { //for updating order, etc.
						$this->Scan->create();
						$this->Scan->save($pic);
					}
				}

				//handle new pictures
				foreach ($imgData as $i => $pic) {
					$this->Scan->create();
					if (empty($pic['id']) && !empty($pic['file']['name'])) {

						$fname = $result['successFilenames'][$i];
						list($width, $height) = $this->Image->imageSize($this->Image->articleImgDir($id) . $fname);
						$new = array( //saving a new picture -- assume all pictures successfully uploaded because there are no error meessages.
							'filename' => $fname,
							'width' => $width,
							'height' => $height,
							'order' => $pic['order'],
							'article_id' => $id);

						$this->Scan->save($new);
					}

				}
				unset($art['Scan']); //avoid saving photos on the saveAll.	*/

				if ($this->Article->saveAll($art)) {
					$this->Session->setFlash('Your Article has been updated.');

					$art = $art['Article'];
					if ($returnTo) {
						$this->redirect('/articles/view/' . $id . '#' . $returnTo);
					} else {
						$this->redirect('/articles/view/' . $id);
					}
				} else {
					$this->Session->setFlash('Your Article could not be updated.');
				}
			}
		}

		$this->request->data = $art;
		$this->set('mags', $this->Article->Magazine->find('list'));
		$this->set('magId', $art['Article']['magazine_id']);
	}

	private function prevSearchQuery($query) {
		if (isset($query['magazine_id'])) {
			$query['magazine'] = $this->Article->Magazine->field('name', array('id' => $query['magazine_id']));
		}
		if (isset($query['published']['month'])) {
			$query['published']['month'] = date("F", mktime(0, 0, 0, $query['published']['month'], 10));
		}
		return $query;
	}

	/**
	 * Ajax action for getting tags to populate edit tags popup.
	 * @return string
	 */
	public function getTags() {

		if ($this->request->is('get')) {
			$artId = $this->request->query['id'];
			$tags = $this->Article->find('first', array(
				'conditions' => array('Article.id' => $artId),
				'contain' => array('Tag.name'),
				'fields' => array('Article.id')
			));
			$tags = Set::extract('/Tag/name', $tags);
			return json_encode($tags);
		}
	}

	public function login() {
		if ($this->data) {
			$pwd = $this->data['Login']['password'];
			if (in_array($pwd, array_values(Configure::read('ADMIN_PASSWORDS')))) {
				$this->Auth->login();

				$this->Session->write('isAdmin', true);
				//used on logout to determine whether homepage should be reset.
				$this->Session->write('loginPassword', $pwd);

				$this->Auth->redirectToPrevious();

				$this->redirect(array('controller' => 'articles', 'action' => 'index'));
			} else {
				$this->Session->setFlash('That is not the right password.  Please try again.');
			}
		}
	}

	/*public function makeImgsSmaller() {
		$scans = $this->Scan->find('all', array(
			'fields' => array('filename', 'article_id', 'id'),
			'order' => array('article_id', 'filename'),
			'conditions' => array('width >' => 960, 'filename' != ''),
			'limit' => 20));

		foreach ($scans as $s) {
			$artDir = $this->Image->articleImgDir($s['Scan']['article_id']);
			$scanUrl = $artDir . $s['Scan']['filename'];

			//$nfname = $pi['filename'].'_s.'.$pi['extension'];
			$nfUrl = $this->Image->reduceResolution($scanUrl, 960, null);

			//if ($nfUrl) {
			list($w, $h) = $this->Image->imageSize($scanUrl);
			if ($w && $h) {
				$this->Scan->save(array(
					'id' => $s['Scan']['id'],
					//'filename' => $nfname,
					'width' => $w,
					'height' => $h
				));
			}
			//}
		}
	}*/
}