<?php
class HeaderImagesController extends AppController {
	
	var $components = array('Image');
	
	private function delete($tripId) {
		$oldFName = $this->HeaderImage->field('filename', array('trip_id' => $tripId));
		$oldFPath = $this->Image->HEADER_BKGR_DIR.'/'.$oldFName;
		if ($this->HeaderImage->deleteAll(array('trip_id' => $tripId))) {
			$this->Image->deleteFile($oldFPath);
			$this->Image->deleteFile($this->Image->removeCroppedSuffix($oldFPath));
			return true;
		}
		return false;
	}
	
	function add() {
		$this->prepAjax();
			
		if ($this->data) {
			
			if ($this->data['HeaderImage']['filename']['name']) {
				$img = $this->data;
					
				$img['file'] = $img['HeaderImage']['filename'];
					
				$aspectRatio = $img['HeaderImage']['crop_banner_image'] ? Configure::read('HEADER_BACKGROUND_BANNER_ASPECT_RATIO') : null;
				//also crops image evenly on all sides.
				$result = $this->Image->uploadFiles($this->Image->HEADER_BKGR_DIR, $img, array('cropAspectRatio' => $aspectRatio));

				$success = false;
				if ($result['errMessage']) {
					$resp = array('success' => $success, 'error' => $result['errMessage']);
				} else {
					$img['HeaderImage']['filename'] = $result['successFilenames'][0];
														
					$fpath = $this->Image->removeCroppedSuffix($this->Image->HEADER_BKGR_DIR.'/'.$img['HeaderImage']['filename']);

					//compute default crop_y
					list($cropWidth, $cropHeight, $cropRatio) = $this->Image->headerImageCropDimensions($img['HeaderImage']['filename']);					
					list($width, $height) = $this->Image->imageSize($fpath);
					$img['HeaderImage']['crop_y'] = (($height/$cropRatio)-$cropHeight)/2;

					//delete previous records and image for this trip.
					if (!empty($img["HeaderImage"]['trip_id']) && ($tripId = $img["HeaderImage"]['trip_id'])) {						
						$this->delete($tripId);	
					}					
					
					if ($this->HeaderImage->save($img)) {
												
						$success = true;
						$img['HeaderImage']['url'] = $this->Image->headerBackgroundImgSrc($img['HeaderImage']['filename'], true);
						$img['HeaderImage']['crop_ratio'] = $cropRatio;
						$img['HeaderImage']['crop_height'] = $cropHeight;
						$img['HeaderImage']['id'] = $this->HeaderImage->id;
						
						$resp = array('success' => $success, 'img' => $img['HeaderImage']);
					}
				}							
			} else {
				unset($img['HeaderImage']['filename']);
				$resp = array('success' => $success);
			}
			
			return json_encode($resp);
			
		}
	}
}