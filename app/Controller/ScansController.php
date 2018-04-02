<?php
class ScansController extends AppController {
	
	var $components = array('Image');
	
	/**
	 * Deletes a given photo from scans.  Ajax action.
	 * @return string
	 */
	function delete()
	{
		$this->prepAjax();
		$picId = $this->request->query['id'];
		$artId = $this->request->query['artId'];
			
		$scan = $this->Scan->find('first', array(
				'conditions' => array('id' => $picId),
				'recursive' => -1
		));
	
		if ($this->Scan->delete($picId))
		{
			$filePath = $this->Image->articleImgDir($artId).$scan['Scan']['filename'];
			$this->Image->deleteFile($filePath);		//TODO: curently the image will still be there but it will not display.			
			return json_encode(array('id' => $picId));
		}
			
		
	}
}