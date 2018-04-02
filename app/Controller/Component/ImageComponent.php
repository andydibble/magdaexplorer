<?php
App::import('Lib', 'Utility');
App::uses('DocumentComponent', 'Controller/Component');
class ImageComponent extends DocumentComponent
{
	var $IMAGE_FILE_ROOT = 'img/' ;
	var $BANNER_DIR = 'img/layout/banner';
	var $BANNER_HEIGHT = 100;
	var $BANNER_BORDER_EXCESS = 6;	
	var $HEADER_BKGR_DIR = 'img/layout/headerBkgr';
	var $ARTICLE_HEADER_BKGR_DIR = 'img/layout/articleHeaderBkgr';
	var $ADVENTURE_DIR = 'img/adventures/';
	var $ACCEPTABLE_FILE_EXTENSIONS = array ('jpg' , 'png' , 'gif' );
	var $ACCEPTABLE_FILE_TYPES = array(IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG);
	static $CROPPED_SUFFIX = '_cropped';

	/**
	 * upload images in $formData to $folder.
	 * Options: (see default options)
	 *  -reduceResTo should be given as a two element array (e.g. array(width, height), use null if one dimension should be determined by the other and aspect ratio.
	 */
	function uploadFiles($folder, $formdata, $options=array()) {
		
		$defaultOptions = array(
			'cropAspectRatio' => false,
			'reorient' => true,
			'reduceResTo' => array(960, null)		
		);		
		$options = array_merge($defaultOptions, $options); 

		//process nothing and return no errors if no images uploaded.
		$result['urls'] = array();
		$result['errors'] = array();
		$result['successFilenames'] = array();
		$result['errMessage'] = '';
		$unacceptableFiles = array();
				
		if (!Utility::hasNumericIndices($formdata)) {
			$formdata = array(0 => $formdata);
		} else {
			$formdata = Utility::reorder($formdata);
		}

		$rel_url = $folder;
		// create the folder if it does not exist
		$folder_url = WWW_ROOT.$folder;
		if (!is_dir($folder_url)) {
			mkdir($folder_url, 0777, true );
		}
		
		// loop through and deal with the files
		foreach ($formdata as $i => $file) {
			if (isset($file['id'])) {
				continue;	//already uploaded.
			}
			
			if (isset($file['file'])) {
				$file = $file['file'];
			} 		
			
			
			if (!empty($file['tmp_name'])) {		//ignore empty file fields
			
				$typeOK = $this->isValidFileType($file['tmp_name'], $this->ACCEPTABLE_FILE_TYPES);
							
				// replace spaces with underscores
				$filename = str_replace( ' ', '_', $file['name']);
				$filename = $this->makeFilenameUnique($filename);
				
				// if file type ok upload the file
				if ($typeOK && !empty($file['tmp_name'])) {
									
					// switch based on error code
					switch ($file['error' ]) {
						case 0:
							// create full filename
							$url = $rel_url.'/' .$filename;
							// check filename already exists
							if (!file_exists($folder_url.'/' .$filename)) {							
								// upload the file
								$success = move_uploaded_file($file['tmp_name' ], $url);
							} else {
								$success = false;
							}
							// if upload was successful
							if ($success) {
								
								if ($options['reorient']) {
									$this->reorient($url, $url, exif_read_data($url, 0, true));
								}
								
								if ($options['cropAspectRatio']) {		
									$filename = $this->addCroppedSuffix($filename);												
									$this->genericCrop($url, $options['cropAspectRatio'], $filename);
								}
								
								if ($options['reduceResTo']) {
									list($smallerWidth, $smallerHeight) = $options['reduceResTo'];
									$this->reduceResolution($url, $smallerWidth, $smallerHeight);
								}
								
								// save the url of the file
								$result[ 'urls' ][] = Configure::read('WEBROOT').$url;
								$result['successFilenames'][$i] = $filename;
							} else {
								$result[ 'errors' ][] = "Error uploading $filename. Please try again.";
							}
							break ;
						case 3:
							// an error occured
							$result[ 'errors' ][] = "Error uploading $filename. Please try again.";
								break ;					
							break;
						default:
							// an error occured
							$result[ 'errors' ][] = "System error uploading $filename. Contact webmaster.";
							break;
					}
				} elseif ($file['error'] == 4) {				
					$result[ 'nofiles' ][] = "No file Selected";				
				} else if ($filename != '') {
					// unacceptable file type
					$unacceptableFiles[] = $filename;
				}
			}
		}
		//prepare error message.
		if (!empty($unacceptableFiles))
		{
			$err = implode(', ', $unacceptableFiles); 
			$err .= count($unacceptableFiles) > 1 ? ' do ' : ' does ';
			$err .= 'not have an acceptable file type.  Acceptable file types are '.String::toList($this->ACCEPTABLE_FILE_EXTENSIONS).'.  Please upload a different file.'; 
			$result['errors'][] = $err;
		}
				
		if (!empty($result['errors']))
		{
			$result['errMessage'] = implode("\n", $result['errors']);	
		}
		
		unset($result['errors']);
		
		return $result;
	}
	
	/**
	 * Directory for adventure images. accepts $tripId as parameter purely for legacy reasons (need to remove)
	 * @param unknown_type $tripId
	 * @param unknown_type $advId
	 * @return string
	 */
	function advImgDir($tripId, $advId) {
		//return $this->IMAGE_FILE_ROOT.'trips/trip'.$tripId.'/adventure'.$advId;
		return $this->IMAGE_FILE_ROOT.'trips/adventure'.$advId.'/';
	}
	
	function articleImgDir($artId) {
		return $this->IMAGE_FILE_ROOT.'magazines/art'.$artId.'/';
	}
	
	function headerBkgrImgDir() {
		return $this->IMAGE_FILE_ROOT.'layout/headerBkgr/';
	}
	
	/** TODO: Put this in a more generic Document or File Component?? */
	/** TODO: validate image size. */
	function isValidFileType($filePath, $valTypes=null) { 
		/*if($_FILES["imagefile"]["size"] >= 2120000) {
			echo "F2";
			die();
		} else {*/
			if ($valTypes==null) {
				$valTypes = $this->ACCEPTABLE_FILE_TYPES;
			}
			$filePath = str_replace('\\', '/', $filePath);			
			$imageData = @getimagesize($filePath);	
				
			return $imageData !== FALSE && in_array($imageData[2], $valTypes);				
		//}
	}
	
	function makeFilenameUnique($filename) {
		$parts = explode('.', $filename);
		$ext = $parts[count($parts)-1];
		unset($parts[count($parts)-1]);
		$extlessFilename = implode('.', $parts);
		//filename is the original + _ + rand int (to solve ipads always uploading image.<ext>).
		
		//$ext = pathinfo($filename, PATHINFO_EXTENSION);
		return $extlessFilename.'_'.rand().'.'.$ext;
	}

	public function moveFile($file, $dest)
	{					
		if (!is_dir($dest)) {
			mkdir($dest, 0777, true );
		}
					
		return rename($file, $dest . '/' . basename($file));
		
	}
	
	public function moveFiles($files, $dest)
	{
		if (!is_dir($dest)) {
			mkdir($dest, 0777, true );
		}
		foreach ($files as $file)
		{
			//have to use relative filepath starting with img/files directory.
			if (env('HTTP_HOST') == 'localhost')
			{
				$file = str_replace(Configure:: read( 'WEBROOT'), '' , $file, $tmp=1);
			}
			else
			{
				$file = substr($file, 1);
			}
	
			rename($file, $dest . '/' . basename($file));
		}
	}

	public function deleteDir($dirFromWebroot)
	{
		$mydir = str_replace( "\\" , "/" , getcwd()).'/' .$dirFromWebroot;

		if (is_dir($mydir))
		{
			$d = dir($mydir);
			while ($entry = $d->read())
			{
				if ($entry!= "." && $entry!= "..")
				{
					unlink($dirFromWebroot. '/' .$entry);
				}
			}
			$d->close();
			rmdir($mydir);
		}
	}

	public function getFilenames($srcDir)
	{
		$images = array ();
		foreach ($this->ACCEPTABLE_FILE_EXTENSIONS as $type)
		{
			$imgsOfType = glob($srcDir . '*.' .$type);
			if (!empty ($imgsOfType))
			{
				$images = array_merge($images, $imgsOfType);
			}
		}

		return $images;
	}

	public function getOwner($filePath)
	{
		$parts = explode( "/theme" , $filePath);
		$parts = explode( "/" , $parts[0]);
		$owner = $parts[count($parts)-1];
		return $owner;
	}

	public function renameImageDir($oldUsename, $newUsernmae)
	{
		$dirPath = $this->IMAGE_FILE_ROOT .$oldUsename;
		if (is_dir($dirPath))
		{
			return rename($dirPath, $this->IMAGE_FILE_ROOT .$newUsernmae);
		}
		return false ;
	}

	public function pollQuestionPictureDirPath($pollId, $fromRoot=true) {
		if ($fromRoot) {
			return Configure::read('WEBROOT').$this->IMAGE_FILE_ROOT.'polls/poll'.$pollId.'/';
		} else {
			return $this->IMAGE_FILE_ROOT.'polls/poll'.$pollId.'/';
		}
	
	}
	
	public function constructImgElement($url, $alt='', $style='') {
		return $elt = '<img src="'.$url.'" alt="'.$alt.'" style="'.$style.'" />';
	}
	
	public function renameFilename($path, $newName) {
		$parts = pathinfo($path);
		$newFname = $parts['dirname'].'/'.$newName.'.'.$parts['extension'];
		$this->deleteFile($newFname);		
		return rename($path, $newFname);
	}
	
	public function getAdventurePhotoFilepathFromWebroot($fname) {
		$parts = explode('/', $fname);
		$fname = $parts[count($parts)-1];
		return $this->ADVENTURE_DIR.$fname;
	}
	
	/**
	 * Resamples the jpgfile given and then changes its orientation to upright if it is not already upright.
	 * @param unknown_type $jpgFile
	 * @param unknown_type $destFname
	 * @param unknown_type $width
	 * @param unknown_type $orientation
	 */
	function reorient($jpgFile, $destFname, $exif) {
		
		if ($exif!==false) {
						
			$width = null;
			if(!empty($exif['EXIF']['ExifImageWidth'])) {
				$width = $exif['EXIF']['ExifImageWidth'];
			}
			$orientation = null;
			if(!empty($exif['IFD0']['Orientation'])) {
				$orientation = $exif['IFD0']['Orientation'];
			}
			else if (!empty($exif['COMPUTED']['Orientation'])) {
				$orientation = $exif['COMPUTED']['Orientation'];
			}
						
			if ($width && $orientation) {
				// Get new dimensions
				list($width_orig, $height_orig) = getimagesize($jpgFile);
				$height = (int) (($width / $width_orig) * $height_orig);
				// Resample
				$image_p = imagecreatetruecolor($width, $height);
				$image   = imagecreatefromjpeg($jpgFile);
				imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
				// Fix Orientation
				switch($orientation) {
					case 3:
						$image_p = imagerotate($image_p, 180, 0);
						break;
					case 6:
						$image_p = imagerotate($image_p, -90, 0);
						break;
					case 8:
						$image_p = imagerotate($image_p, 90, 0);
						break;
				}
				// Output
				return imagejpeg($image_p, $destFname);
			}			
		}
		return false;
	}
	
	/**
	 * adds cropped suffix between the filename base and ext and returns new value.
	 * Has no effect is the filename already has the cropped suffix in its name.
	 * @param unknown_type $imgFile
	 * @return string
	 */
	public function addCroppedSuffix($imgFile) {		
		if (strpos($imgFile, self::$CROPPED_SUFFIX) === false) {
			$parts = explode('.', basename($imgFile));
			$ext = $parts[count($parts)-1];
			unset($parts[count($parts)-1]);
			$base = implode('.', $parts);
			return $base.self::$CROPPED_SUFFIX.'.'.$ext;
		}
		return $imgFile;
	}
	
	/**
	 * removes cropped suffix.
	 * @param unknown_type $imgFile
	 * @return string
	 */
	public function removeCroppedSuffix($imgFile) {		
		return str_replace(self::$CROPPED_SUFFIX, '', $imgFile);
	}
	
	public function cropRatio($imgWidth) {
		return $imgWidth / 960;
	}
	
	public function headerBackgroundImgSrc($filename, $getUncropped=false) {
		if ($getUncropped) {
			$filename = $this->removeCroppedSuffix($filename);			
		}
		return 'http://'.Configure::read('HTTP_HOST').Configure::read('APPROOT').$this->HEADER_BKGR_DIR.'/'.$filename;
	}
			
	/**
	 * Crops $jpgFile to the given aspect ratio centered at image center.
	 * If $newFname is set the cropped image will have that name, otherwise 
	 * the cropepd image will overwrite the old image.
	 * @param unknown_type $jpgFile
	 * @param unknown_type $aspectRatio
	 * @param unknown_type $newfname
	 * @return void|boolean
	 */
	public function genericCrop($jpgFile, $aspectRatio, $newFname=null) {
		//Your Image
		$this->imgSrc = $jpgFile;
		 
		//getting the image dimensions
		list($width, $height) = getimagesize($jpgFile);
		 
		//TODO: case where width is too long.
		
		//create image from the jpeg
		$img = imagecreatefromjpeg($jpgFile);
		if (!$img) {			
			return;
		}
		
		//either an aspect ratio by which width should be greater than height indicates a horizontal crop
		//or a width that is TOO LONG for aspect ratio (otherwise vertical crop) 
				
		/*$isHorzCrop = $aspectRatio <= 1 || $width > $height*$aspectRatio;	
			
		if($isHorzCrop) {
			$cropWidth = $height*$aspectRatio;
			$cropHeight = $height;
		} else {	//vertical crop--keep same width, but force the height to adhere to aspect ratio (inverse because its passed as width:height)
			$cropHeight = $width*(1/$aspectRatio);
			$cropWidth = $width;		
		}*/			
		
		list($cropWidth, $cropHeight) = $this->cropDimensions($width, $height, $aspectRatio);
				
		$cropped = imagecreatetruecolor($cropWidth, $cropHeight);
		
		//'zoom-in' on the middle of hte image, cropping off the bottom and top or the right and left
		imagecopyresampled($cropped, $img, 0, 0, ($width-$cropWidth)/2, ($height-$cropHeight)/2, $cropWidth, $cropHeight, $cropWidth, $cropHeight);
		
		if ($newFname != null) {
			$outputFile = dirname($jpgFile).'/'.$newFname;
		} else{
			$outputFile = $jpgFile;
		}
		return imagejpeg($cropped, $outputFile);		
	}
	
	public function crop($jpgFile, $x, $y, $w, $h) {
			
		//create image from the jpeg
		$img = imagecreatefromjpeg($jpgFile);
		if (!$img) {
			return;
		}
	
		$cropped = imagecreatetruecolor($w, $h);
		//'zoom-in' on the middle of hte image, cropping off the bottom and top or the right and left
		imagecopyresampled($cropped, $img, 0, 0, $x, $y, $w, $h, $w, $h);
	
		return imagejpeg($cropped, $jpgFile);
	}
	
	
	public function imageSize($imgFile) {
		if(file_exists($imgFile)) {
			return getimagesize($imgFile);
		}
		return array(null, null);
		
	}
	
	public function cropDimensions($width, $height, $aspectRatio) {			
		$isHorzCrop = $aspectRatio <= 1 || $width > $height*$aspectRatio;	
			
		if($isHorzCrop) {
			$cropWidth = $height*$aspectRatio;
			$cropHeight = $height;
		} else {	//vertical crop--keep same width, but force the height to adhere to aspect ratio (inverse because its passed as width:height)
			$cropHeight = $width*(1/$aspectRatio);
			$cropWidth = $width;
		}
				
		return array($cropWidth, $cropHeight);
	}
	
	
	public function headerImageCropDimensions($fname) {
		$fpath = $this->removeCroppedSuffix($this->HEADER_BKGR_DIR.'/'.$fname);
		list($width, $height) = $this->imageSize($fpath);
		list($cropWidth, $cropHeight) = $this->cropDimensions($width, $height, Configure::read('HEADER_BACKGROUND_BANNER_ASPECT_RATIO'));
		$cropRatio = $this->cropRatio($width);
		
		return array($cropWidth, $cropHeight, $cropRatio);
	}
	
	/**
	 * change resolution of image.  If only one dim is provided, uses the current dimensions of the image to find the other and maintain aspect ratio.
	 * @param unknown_type $imgUrl
	 * @param unknown_type $width
	 * @param unknown_type $height
	 * @param $outputFile alternative filename for the reduced res image (overwrites old image, if not provided)
	 * TODO: currently, if the image is too small to be set to passed dimensions no file is produced, even if outputFile is passed (problem?)
	 * @return boolean
	 */
	public function reduceResolution($imgUrl, $width=null, $height=null, $outputFile=null) {
		
		if ($width==null && $height==null) {
			return false;
		}
			
		list($imageWidth, $imageHeight) = $this->imageSize($imgUrl);
				
		if ($imageHeight == 0) {
			return false;
		}
		$imageAspectRatio = $imageWidth / $imageHeight;
		
		if ($width==null) {
			$width = $height*$imageAspectRatio;
		} else if ($height==null) {
			$height = $width/$imageAspectRatio;
		}
		
		if ($width >= $imageWidth || $height >= $imageHeight) {
			return false;
		}
		
		$newImg = imagecreatefromjpeg($imgUrl);
		$temp_image = imagecreatetruecolor($width, $height);
		imagecopyresampled($temp_image, $newImg, 0, 0, 0, 0, $width, $height, $imageWidth, $imageHeight);
		$newImg2 = imagecreatetruecolor($width, $height);
		imagecopyresampled($newImg2, $temp_image, 0, 0, 0, 0, $width, $height, $width, $height);
	
		if (!$outputFile) {
			$outputFile = basename($imgUrl);
		
			//$base = basename($imgUrl);
		//$ext = pathinfo($imgUrl);
		//$ext = $ext['extension'];
		//$nFname = $base.'_s'.$ext;
		//$parts = explode('.', $filename);
		//$ext = $parts[count($parts)-1];
		//$outputFile = dirname($imgUrl).'/'.$base;
		}
		$outputUrl = dirname($imgUrl).'/'.$outputFile;
		
		if (imagejpeg($newImg2, $outputUrl)) {
			return $outputFile;
		} else {
			return false;
		}
	}
}
?>