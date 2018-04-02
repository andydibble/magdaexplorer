<?php
class PhotosController extends AppController {
	
	var $components = array('Image', 'Like');
	var $uses = array('Adventure', 'Photo');
	
	/**
	 * Deletes a given photo from photos.  Ajax action.  Currently, this does not delete the photo from the filesystem.
	 * @return string
	 */
	function delete()	
	{
		$this->prepAjax();		
		$picId = $this->request->query['id'];
		$advId = $this->request->query['advId'];
		$tripId = $this->request->query['tripId'];
		
		$photo = $this->Photo->find('first', array(				
			'conditions' => array('id' => $picId),
			'recursive' => -1		
		));	
				
		if ($this->Photo->delete($picId))
		{			
			$this->Adventure->Trip->updatePhotoCount($tripId);
			//$filePath = 'img/trips/trip'.$tripId.'/adventure'.$advId.'/'.$photo['Photo']['filename'];
			$filePath = $this->Image->advImgDir($tripId, $advId).$photo['Photo']['filename'];			
			$this->Image->deleteFile($filePath);		//TODO: curently the image will still be there but it will not display.
			$this->Session->setFlash('Your Photo was deleted from this Adventure');
			return json_encode(array('id' => $picId));
		}
			
		$this->Session->setFlash('Your Photo could not be deleted.');
	}
}