<?php
App::import('Lib', 'Utility');

class HeaderBackgroundImageComponent extends ImageComponent {
	
	function upload($data) {
			
		$model = Inflector::singularize($this->controller->name);
		
		if ($data[$model]['header_background_image']['name']) {
			$img['file'] = $data[$model]['header_background_image'];
		
			$aspectRatio = $data[$model]['crop_banner_image'] ? Configure::read('HEADER_BACKGROUND_BANNER_ASPECT_RATIO') : null;
		
			$result = $this->uploadFiles($this->HEADER_BKGR_DIR, $img, null, true, true, $aspectRatio);
				
			if ($result['errMessage']) {
				$this->controller->Session->setFlash($result['errMessage']);
			} else {
				$data[$model]['header_background_image'] = $result['successFilenames'][0];
			}
		} else {
			unset($data[$model]['header_background_image']);
		}	
	}
}