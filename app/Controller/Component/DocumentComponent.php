<?php

App::uses('AppComponent', 'Controller/Component');
class DocumentComponent extends AppComponent
{
	var $FILES_ROOT = 'files/';	
	
	/**
	 * uploads files to the server.  If formdata may be either an array or a single file upload field.
	 * @param s:
	 *          $folder     = the folder to upload the files e.g. 'img/files'
	 *          $formdata   = the array containing the form files
	 *          $itemId     = id of the item (optional) will create a new sub folder
	 * @return :
	 *          will return an array with the success of each file upload
	 */
	function uploadFiles($folder, $formdata, $options=array()) {
				
		$defaultOptions = array(
				'getExtantFileUrl' => false
		);
		$options = array_merge($defaultOptions, $options);
				
		// setup dir names absolute and relative
		$folder_url = WWW_ROOT.$folder;
		$rel_url = $folder;

		//process nothing and return no errors if no images uploaded.
		$result['urls'] = array();
		$result['errors'] = array();
		$result['successFilenames'] = array();
		$unacceptableFiles = array();
		
		if (!isset($formdata[0])) {
			$formdata = array(0 => $formdata);
		}
		
		if (empty($formdata[0]['file']['name']))
		{
			return $result;
		}

		// create the folder if it does not exist
		if (!is_dir($folder_url)) {
			mkdir($folder_url, 0777, true );
		}		
		
		// loop through and deal with the files
		foreach ($formdata as $i => $file) {
			if (isset($file['file']))
			{
				$file = $file['file'];
			} 			
			// replace spaces with underscores
			$filename = str_replace( ' ' , '_' , $file[ 'name']);
			
			$typeOK = true;
			// assume filetype is false
			//$typeOK = false;
			// check filetype is ok
			/*foreach ($this->ACCEPTABLE_FILE_TYPES as $type) {
				
				if ($type == $file['type']) {
					$typeOK = true;
					break;
				}
			}*/

			// if file type ok upload the file
			if ($typeOK) {
				// switch based on error code
				switch ($file['error' ]) {
					case 0:						
						// create full filename
						$full_url = $folder_url.'/' .$filename;
						$url = $rel_url.'/' .$filename;
						// check filename already exists
						if (!file_exists($folder_url.'/' .$filename)) {							
							// upload the file							
							$success = move_uploaded_file($file['tmp_name' ], $url);
						} else {
							/**case of file with this name already uploaded**/
							if (!$options['getExtantFileUrl']) {
								$result[ 'errors' ][] = $filename." has already been uploaded.";
								$success = false;
								break;
							} else {
								$success = true;
							}
						}
						// if upload was successful
						if ($success) {
							// save the url of the file
							$result[ 'urls' ][] = Configure::read('WEBROOT').$url;
							$result['successFilenames'][$i] = $filename;
						} else {
							$result[ 'errors' ][] = "Error uploading $filename . Please try again.";
						}
						break ;
					case 3:
						// an error occured
						$result[ 'errors' ][] = "Error uploading $filename . Please try again.";
							break ;					
						break;
					default:
						// an error occured
						$result[ 'errors' ][] = "System error uploading $filename . Contact webmaster.";
						break;
				}
			} elseif ($file['error'] == 4) {				
				$result[ 'nofiles' ][] = "No file Selected";				
			} else if ($filename != '') {
				// unacceptable file type
				//$unacceptableFiles[] = $filename;
			}
		}
		//prepare error message.
		/*if (!empty($unacceptableFiles))
		{
			$err = implode(', ', $unacceptableFiles); 
			$err .= count($unacceptableFiles) > 1 ? ' do ' : ' does ';
			$err .= 'not have an acceptable file type.  Acceptable file types are '.implode( ", ", $this->ACCEPTABLE_FILE_EXTENSIONS).'.  Please upload a different file.'; 
			$result['errors'][] = $err;
		}*/
		
		$result['errMessage'] = '';
		if (!empty($result['errors']))
		{
			$result['errMessage'] = implode("\n", $result['errors']);	
		}
		
		unset($result['errors']);
		
		return $result;
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

	public function deleteFiles($dirFromWebroot)
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

	public function deleteFile($filePath)
	{				
		if (file_exists($filePath))
		{
			return unlink($filePath);
		}
		return false;
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
	
	public function renameFilename($path, $newName) {
		$parts = pathinfo($path);
		$newFname = $parts['dirname'].'/'.$newName.'.'.$parts['extension'];
		$this->deleteFile($newFname);		
		return rename($path, $newFname);
	}
}
?>
