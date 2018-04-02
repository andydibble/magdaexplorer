<?php
class TextsController extends AppController {

	var $components = array('Image');
	
	var $adminOnly = array('edit');
	
	public function edit() {
	
		if ($this->data) {
			$texts = $this->data;
		
			foreach($texts as $i => &$text) {				
				
				if (is_array($text['Text']['value'])) {						
					
					if ($text['Text']['value']['name']) {
						$img['file'] = $text['Text']['value'];
						
						$img['file'] = $img['HeaderImage']['filename'];
							
						$aspectRatio = Configure::read('HEADER_BACKGROUND_BANNER_ASPECT_RATIO');
						$result = $this->Image->uploadFiles($this->Image->HEADER_BKGR_DIR, $img, array('cropAspectRatio' => $aspectRatio));
							
						if ($result['errMessage']) {
							$this->Session->setFlash($result['errMessage']);
						} else {
							$text['Text']['value'] = $result['successFilenames'][0];
						}
					} else {
						unset($texts[$i]);
					}
				} else if ($text['Text']['value'] == '<br>') {
					$text['Text']['value'] = '';
				}												
			}
				
			if (!empty($result['errMessage'])) {
				$this->Session->setFlash($result['errMessage']);
			} else {				
				if ($this->Text->saveAll($texts)) {
					$this->Session->setFlash('Your new layout fields were saved.');
					$this->redirect('/trips');
				} else {
					$this->Session->setFlash('Your new layout could not be saved. Please try again.');
				}
			}				
		}
		
		$locs = $this->Location->findUserAccessibleLocationList();
		$this->set('homePageLocationOptions', $locs);	//for choosing homepage option		
		$inputTexts = $this->Text->find('all');		
		$this->request->data = $inputTexts;
		$this->set('inputTexts', $inputTexts);
	}
}
?>