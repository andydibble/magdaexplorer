<?php
/**
 * Static content controller.
 *
 * This file will render views from views/pages/
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('AppController', 'Controller');

/**
 * Static content controller
 *
 * Override this controller by placing a copy in controllers directory of an application
 *
 * @package       app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers/pages-controller.html
 */
class AdventuresController extends AppController
{

	public  $uses = array('Adventure', 'Text', 'Statistic', 'Photo', 'PollResponse', 'KnownEmail', 'Tag', 'Login', 'Comment');

	/**
	 * Controller name
	 *
	 * @var string
	 */
	public $name = 'Adventures';

	/**
	 * Default helper
	 *
	 * @var array
	 */
	public $helpers = array('Html', 'Session');

	var $paginate = array(
		'Adventure' => array(
			'group' => 'Adventure.id',
			'order' => 'Adventure.id DESC',
			'limit' => 10,
			'maxLimit' => 200)
	);

	var $components = array('Image', 'Like');

	var $adminOnly = array('add', 'edit', 'delete');

	public function add($tripId = null)
	{

		$this->set('tripId', $tripId);

		if ($this->data) {
			$adv = $this->data;

			if ($this->Adventure->save($adv['Adventure'])) { //create basic adventure, so that id can be obtained for picture directory.
				$adv['Adventure']['id'] = $this->Adventure->id;

				$tripId = $adv['Adventure']['trip_id'];

				$uploadDir = $this->Image->advImgDir($tripId, $adv['Adventure']['id']);
				$result = $this->Image->uploadFiles($uploadDir, $adv['Photo']);

				if (!empty($result['errMessage'])) {
					$this->Adventure->delete($this->Adventure->id); //delete the basic adv if picture upload fails.
					$this->Session->setFlash($result['errMessage']);
				} else {
					if (!empty($result['successFilenames'][0])) {
						foreach ($result['successFilenames'] as $i => $fname) {
							$adv['Photo'][$i]['filename'] = $fname;
							list($width, $height) = $this->Image->imageSize($this->Image->advImgDir($tripId, $this->Adventure->id) . $fname);
							$adv['Photo'][$i]['width'] = $width;
							$adv['Photo'][$i]['height'] = $height;
						}
						//remove empty photos from post data.
						foreach ($adv['Photo'] as $i => $photo) {
							if (empty($adv['Photo'][$i]['filename'])) {
								unset($adv['Photo'][$i]);
							}
						}
					} else {
						unset($adv['Photo']);
					}

					if ($this->Adventure->saveAll($adv)) { //save photo and tag data.
						$this->Session->setFlash('Your new Adventure has been saved.');

						$this->redirect('/trips/index/' . $tripId);
					} else {
						$this->Session->setFlash('Your new Adventure could not be saved.');
					}
				}
			}
		}
	}

	public function edit($id)
	{
		$adv = $this->Adventure->find('first', array('conditions' => array('Adventure.id' => $id)));

		foreach ($adv['Comment'] as &$cmt) {
			String::htmlToPrettyPrint($cmt['value']);
		}

		$advList = $this->Adventure->find('list', array(
			'conditions' => array('trip_id' => $adv['Adventure']['trip_id']),
			'order' => array('date DESC')
		));
		$this->set('advList', $advList);
		$this->set('adv', $adv);

		if ($this->data) {

			$adv = $this->data;

			$adv = $this->deleteIfSelected($adv, $this->Comment, false);

			$uploadDir = $this->Image->advImgDir($adv['Adventure']['trip_id'], $adv['Adventure']['id']);
			$result = $this->Image->uploadFiles($uploadDir, $adv['Photo']);

			if (!empty($result['errMessage'])) {
				$this->Session->setFlash($result['errMessage']);
			} else {
				$imgData = $adv['Photo'];

				//handle changes to old pictures.
				foreach ($imgData as $pic) {
					if (!empty($pic['id'])) { //for updating title
						$savePhoto = true;
						if ($pic['adventure_id'] != $id) { //move photo to a new adventure if it was switched on the page
							$moveToDir = $this->Image->advImgDir($adv['Adventure']['trip_id'], $pic['adventure_id']);
							$savePhoto = $this->Image->moveFile($uploadDir . '/' . $pic['filename'], $moveToDir);
						}

						$this->Photo->create();
						if ($savePhoto) {
							$this->Photo->save($pic);
						}
					}
				}

				//handle new pictures
				foreach ($imgData as $i => $pic) {
					$this->Photo->create();
					if (empty($pic['id']) && !empty($pic['file']['name'])) {

						$fname = $result['successFilenames'][$i];
						list($width, $height) = $this->Image->imageSize($this->Image->advImgDir($adv['Adventure']['trip_id'], $id) . $fname);
						$new = array( //saving a new picture -- assume all pictures successfully uploaded because there are no error meessages.
							'filename' => $fname,
							'title' => $pic['title'],
							'width' => $width,
							'height' => $height,
							'adventure_id' => $id);

						$this->Photo->save($new);
					}

				}
				unset($adv['Photo']); //avoid saving photos on the saveAll.

				//get tag ids before tags are changed on save
				$tags = $this->Adventure->find('first', array(
					'conditions' => array('Adventure.id' => $id),
					'contain' => array('Tag.id')
				));

				if (isset($adv['Comment'])) {
					foreach ($adv['Comment'] as &$cmt) {
						String::prettyPrintToHtml($cmt['value']);
					}
				}

				if ($this->Adventure->saveAll($adv)) {
					$this->Session->setFlash('Your Adventure has been updated.');

					$this->redirect('/trips/index/' . $adv['Adventure']['trip_id'] . Utility::toAdventure($adv['Adventure']['id']));
				} else {
					$this->Session->setFlash('Your Adventure could not be updated.');
				}
			}
		}

		$this->request->data = $adv;
	}

	public function beforeRender()
	{
		parent::beforeRender();

		if (in_array($this->request->params['action'], array('add', 'edit'))) {
			$trips = $this->tripList();
			$this->set('trips', $trips);
		}
	}

/**
 * Delete adventure and all child records.  Tags are only deleted if they refer to no other adventures.
 * @param unknown_type $id
 */
	public function delete($id)
	{
		$tripId = $this->Adventure->field('trip_id', array('id' => $id));

		if ($this->Adventure->delete(array('id' => $id))) {
			$this->Session->setFlash('Your Adventure has been deleted.');
			$dirPath = $this->Image->advImgDir($tripId, $id);

			$this->Trip->updatePostCount($tripId);
			$this->Trip->updatePhotoCount($tripId);

			$this->Image->deleteDir($dirPath);
		} else {
			$this->Session->setFlash('Your Adventure could not be deleted.  Please try again.');
		}

		$this->redirect('/trips/index/' . $tripId);
	}
}