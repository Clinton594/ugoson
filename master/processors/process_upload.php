<?php
require_once ("../controllers/Controllers.php");
require_once ("../functions/fileserver.php");
require_once ("../functions/html_functions.php");
$cookie		= explode(",", $_COOKIE["siteData"]);
$generic 	= new Generic($cookie[1]);
$uri			= $generic->getURIdata();
$root			=	absolute_filepath($uri->site.$uri->backend);
extract($_POST, EXTR_OVERWRITE);
$datatypes = [
	'picture'=>['gif','jpg','bmp','jpeg','png','svg', 'webp'],
	'audio'=>['mp3','wma','m4a','ogg'],
	'document'=>['doc','docx','txt','pdf','xls','xlsx'],
	'video'=>['mp4','avi','3pg','mkv','wmv'],
	'pdf'=>['pdf'],
	'archive'=>['zip','7z','rar','exe']
];

// REWRITE THIS PAGE WITH A SWITCH CASE
// REWRITE THIS PAGE WITH A SWITCH CASE
// REWRITE THIS PAGE WITH A SWITCH CASE
// REWRITE THIS PAGE WITH A SWITCH CASE
// REWRITE THIS PAGE WITH A SWITCH CASE
// REWRITE THIS PAGE WITH A SWITCH CASE
// REWRITE THIS PAGE WITH A SWITCH CASE
// REWRITE THIS PAGE WITH A SWITCH CASE
// REWRITE THIS PAGE WITH A SWITCH CASE
// REWRITE THIS PAGE WITH A SWITCH CASE
// REWRITE THIS PAGE WITH A SWITCH CASE


//Camera from item widget
if(isset($_POST['cameraPhoto'])){
	$data = $_POST['cameraPhoto'];
  list($type, $data) = explode(';', $data);
  list(, $data)      = explode(',', $data);
  $data = base64_decode($data);
	if(!is_dir($folder)){
		if(!mkdir($folder,0755,true)){echo json_encode(['error'=>'cannot create folder']);die();}
	}
	$filename = $filename.".png";
	$filename = renameIfExists($folder.$filename);
  if(file_put_contents($folder.$filename, $data)){
		$_rsp['src'] = $uri->site.str_replace('../','',$folder . $filename);
		$_rsp['name'] = basename($filename);
		echo json_encode($_rsp);
	}else{echo "an error occured";}
}
elseif(!empty($_FILES)) {
	$response = new stdClass;
	$files 	= [];
	// If THIS folder path is a http path, change to absolute file path of THIS server
	if(is_http_url($folder))$folder = absolute_filepath($folder);
	// Check if THIS upload folder does not exist to auto create it
	if(!is_dir($folder)){
		if(!mkdir($folder,0755,true)){
			$response->error = "Can not create THIS folder";
		}
	}

	// If everything is okay, loop through THIS files and upload THISm
	if(empty($response->error)){
		foreach ($_FILES as $file_key => $thisfile) {
			// crete a temporal file object
			$file = new stdClass;

			// This file's name
		 	$filename =str_replace(' ','_',$thisfile["name"]);

			// This file's extension
			$ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

			// Check if file is within THIS accepted file size
			if($thisfile["size"] < 100045766666666){
				// Check if file matches THIS requestes file type
				if(isset($getype)){
					if(!isset($datatypes[$getype])){
						$file->error = "Unknown filetype";
					}else{
						if(array_search($ext, $datatypes[$getype]) === false){
							$file->error = "Unrecognized {$getype} format";
						}
					}
				}else{
					$getype = getdataType($datatypes, $ext);
					if($getype === false){
						$file->error = "Unknown filetype";
					}
				}
				// If file has passed all neccessary checks
				if(empty($file->error)){
					// Check if file name already exists in THIS folder and reassing a new filename to it
					$filepath = renameIfExists($folder.$filename);
					$filename = basename($filepath);
					$folder   = dirname($filepath);
					// see($filepath);
					if(move_uploaded_file($thisfile["tmp_name"], $filepath)){
						// Run some special operations for picture files like [Creating thumbnails and reducing file sizes]
						if($getype == "picture"){

							list($orig_width, $orig_height) = getimagesize($filepath);

							$icon = returnIcon($filename, $folder);
							if($orig_width > 200 or $orig_height > 200){

								if(checkPictureFormat($filename)){
									if(!is_dir("{$folder}/tbn")){
										mkdir("{$folder}/tbn", 0755);
									}

									$icon = "{$folder}/tbn/{$filename}";
									if(!is_file($icon)){
										resizePicture($filepath, $icon, 128, 128);
										$icon="{$folder}/tbn/{$filename}";
									}
								}
							}
							$width = empty($width) ? 1500 : $width;
							$height = empty($height) ? 1500 : $width;
							resizePicture($filepath, $filepath, $width, $height);
							$file->size = [$orig_width, $orig_height];
							$file->icon = http_filepath($icon);
						}
						$file->src       = http_filepath($filepath);
						$file->name      = basename($filename);
						$file->extension = $ext;
					} else $file->error = "Could not upload ".basename($filename);
				}
			}
			else $file->error = "File size is too much";
			$files[$file_key] = $file;
		}
	}
	if(isset($old_img) && file_exists($old_img)){
		unlink($old_img);
		unlink(dirname($old_img)."tbn/".basename($old_img));
	}
	// Return response as object for file_upload keys or as array for none file_upload keys
	// This is because it's not only upload dialog that uses this upload function;
	$response = !empty($response->error)?
		$response :
		((count($files) == 1) && (isset($files["file_upload"]))?
			reset($files) :
			$files
		);
	echo json_encode($response);
	die();
} else if(!empty($url_file)){
		$dir=$folder;
		$ext=getFileFormat($url_file);
		$icon=returnIcon($url_file,$dir);
		if($icon !=false){

			$bs=basename($url_file);

			echo $bs;

			echo $dir;

			$filenmae="lop.kop";

			$f=getRemoteFiles($url_file);

			if($f !=false)

			{

				$savefile = fopen($dir . $filename, 'w');

				fwrite($savefile, $f);

				fclose($savefile);

				if(!is_dir($dir."tbn"))

				{

					mkdir($dir."tbn",0755);

				}

				$thumbnail=$dir."tbn/".$filename;

				if(!is_file($thumbnail))

				{

					resizePicture($dir . $filename,$thumbnail, 128, 128);

				}

			$name[0] =$dir.$filename;

			$name[1] =$thumbnail;

			$name[2] =basename($filename);

			$name[3] =$ext;

			echo json_encode($name);

		} else echo 0;

	} else echo -1;



}

else if(!empty($url_anchor))

{



		$f=getRemoteFiles($url_anchor);

		if($f !=false)

		{

			$title=get_tag($f,'title');

			$r["i"] =$url_anchor;

			$r["c"][]=array("v"=>$title);



			echo json_encode($r);

		} else echo 0;





}
else if(!empty($moveFolder)){
	$new_name= $dropFolder."/".basename($moveFolder);
	if(rename($moveFolder,$new_name)){
		$arr = [
			'newfile' => $new_name,
			'oldfile' => $moveFolder,
		];
		echo json_encode($arr);
	}else{
		echo 0;
	}
	die();
}

//Delete files or folders in upload dialogue
else if(!empty($delPath))
{
	switch ($type)
	{
		case 1:
		if(is_http_url($delPath))$delPath = absolute_filepath($delPath);
		if(file_exists($delPath)){
			$dir = dirname($delPath);
			$tbn = "{$dir}/tbn/";
			$tbn = str_replace($dir, $tbn, $delPath);
			if(file_exists($tbn))unlink($tbn);
 			echo unlink($delPath);
		}else{
			echo 0;
		}
		break;
		case 0:
			echo delete_files($delPath);
		break;
	}
}
//Create new folder in upload dialogue
else if(!empty($folderName)){
	$count=0;
	$folderName = strtolower(str_replace(' ','_',$folderName));
	mkdir($ucPath.$folderName,0755,true);
	$in[$count]['src']= $ucPath.$folderName;
	$in[$count]['name']= basename($folderName);
	$in[$count]['isFolder']= -1;
	$in[$count]['extension']= -1;
	echo json_encode($in);
}
//Rename files in upload dialogue
else if(!empty($newPath)){
	if(is_http_url($newPath))$newPath = absolute_filepath($newPath);
	if(is_http_url($oldPath))$oldPath = absolute_filepath($oldPath);
	echo rename($oldPath, $newPath);
}
//Delete redundant cropped and uncropped photos uploaded while trying to set generic dp
else if(isset($generic_delete)){
	if(gettype($unlink) == 'array'){
		foreach($unlink as $file){
			if(is_http_url($file))$file = absolute_filepath($file);
			if(file_exists($file))unlink($file);
			$tbn = dirname($file)."/tbn/".basename($file);
			if(file_exists($tbn))unlink($tbn);
		}
		echo json_encode(['done'=>'Images cleared']);die();
	}else{echo json_encode(['done'=>'No Files to delete']);die();}
}
//Crop generic dp images;
else if(isset($generic_crop)){
	if(is_base64_string($image_source)){
		$generated_name = random(9);
		$image_source = save_base64_file($image_source, $img_dir.$generated_name);
	}
	$image_source = is_http_url($image_source) ? absolute_filepath($image_source) : $image_source;
	$img_dir 			= is_http_url($img_dir) ? absolute_filepath($img_dir) : $img_dir;
	if(file_exists($image_source)){
		$file =  realpath($image_source);
		list($orig_width, $orig_height) = getimagesize($file);
		$new_width = ($resizable_width * $orig_width)/$img_width;
		$new_height = ($resizable_height * $orig_height)/$img_height;
		$x_cord = ($orig_width * $resizable_left)/$img_width;
		$y_cord = ($orig_height * $resizable_top)/$img_height;
		$param = [
			'x' => $x_cord,
			'y' => $y_cord,
			'width' => $new_width,
			'height' => $new_height
		];
		$filename = pathinfo($file,PATHINFO_FILENAME);
		$new_dp = $img_dir.random(9).".png";
		$im = imagecreatefrompicture($file);
		//echo ; die();
		if(gettype($im) !== 'resource'){die(50);}
		$im2 = image_crop($im, $param);
		if ($im2 !== FALSE) {
			imagepng($im2, $new_dp);
			if(file_exists($new_dp)){
				$icon = dirname($new_dp)."/"."tbn/".basename($new_dp);
				resizePicture($new_dp, $icon, 128, 128);
				resizePicture($new_dp, $new_dp, 1500, 1500);
				list($new_w, $new_h)  =  getimagesize($new_dp);
				$new_dp = http_filepath($new_dp);
				$icon 	= http_filepath($icon);
				$response = array('src'=>$new_dp, 'size'=>[$new_w, $new_h], 'name'=>basename($new_dp), "icon"=>$icon);
				if(!empty($delete_original) && file_exists($image_source))unlink($image_source);
				echo json_encode($response);
			}
		}else{echo json_encode(['error'=>"This image file can't be cropped"]);die();}
	}else{echo json_encode(['error'=>'File no longer exists']);die();}
}else if(isset($folder)){
	//die();
	if(!is_dir($folder)){
		mkdir($folder, 0755, true);
	}
	$ar=getLocalFiles($folder);
	if(isset($getype) and !empty($getype)){
		$search = $ar;
		$ar = [];
		$arrtype = $datatypes[$getype];
		foreach($search as $c => $item){
			$extx = strtolower(trim($item['extension']));
			if(array_search($extx, $arrtype) === false && $extx != '-1'){
				continue;
			}
			$ar[] = $item;
		}
		//$ar[] = ['../'.$folder,'Back','..','-1'];
	}
	rsort($ar);
  echo json_encode($ar);
}else{
	echo json_encode(["error"=>"An Error Occured"]);
}


?>
