<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* Name:  Libupload
*
* Author: Eko muhammad isa
* 		  ekoisa@gmail.com
*         @eko_isa
*
* Location: http://www.eNotes.web.id/
*
* Created:  30 September 2011
*
* Description:  Alternative upload system PyroCMS
*
*/

class Libupload
{
	/*
	 * 
	 * $param = array(
     * 'file_post',     // name file input
	 * 'file_target',   // list file target contains array
	 * )
	 * 
     * 
     * file_target = (array(
     * 'new_name',      // name file input
     * 'path',      // path upload file 
	 * 'width',         // target width
	 * 'height',        // target height
	 * ), array(
     * 'new_name',     
     * 'path',   
	 * 'width',   
	 * 'height',   
     * ));
	 * */
	 
	public function upload_img($param = array()){
		if(!isset($param)){
		return;
		}
		if(!isset($param['file_post'])){
		return;
		}
		
        $max_size = 1500;
        $file_post = $param['file_post'];
        $file_target = $param['file_target'];
        
        $file_result = array();
        
        $errors=0;
        $dbg_show = '';
        $file_result[0] = false;
        $file_result[1] = $dbg_show;
        if(isset($_FILES[$file_post])){
            $image =$_FILES[$file_post]['name'];
            $uploadedfile = $_FILES[$file_post]['tmp_name'];

            if ($image){
                $filename = stripslashes($_FILES[$file_post]['name']);
                $extension = $this->getExtension($filename);
                $extension = strtolower($extension);
             if (($extension != "jpg") && ($extension != "jpeg") 

            && ($extension != "png") && ($extension != "gif")) 
              {
            $dbg_show .= ' Unknown Image extension <br/>';
            $errors=1;
              }
             else
            {
                $size=filesize($_FILES[$file_post]['tmp_name']);

                if ($size > $max_size*1024)
                {
                $dbg_show .= 'You have exceeded the size limit <br/>';
                $errors=1;
                }

                if($extension=="jpg" || $extension=="jpeg" )
                {
                $uploadedfile = $_FILES[$file_post]['tmp_name'];
                $src = imagecreatefromjpeg($uploadedfile);
                }
                else if($extension=="png")
                {
                $uploadedfile = $_FILES[$file_post]['tmp_name'];
                $src = imagecreatefrompng($uploadedfile);
                }
                else 
                {
                $src = imagecreatefromgif($uploadedfile);
                }

                list($width,$height)=getimagesize($uploadedfile);
                    $startk = 2;
                    foreach($file_target as $vl){
                        $TargetWidth=(intval($vl['width']) > 0) ? intval($vl['width']): false;
                        $TargetHeight=(intval($vl['height']) > 0) ? intval($vl['height']): false;
                        
                        if(!$TargetWidth and !$TargetHeight){
                            $newwidth = $width;
                            $newheight = $height;
                        }else{
                            if(!$TargetHeight and $TargetWidth > 0){
                                // echo $TargetWidth . " |cek a| " . $width . "<br/>";
                                if($TargetWidth < $width){
                                    $newwidth = $TargetWidth;
                                    $newheight = ($TargetWidth * $height) / $width;
                                    $newheight = ceil($newheight);
                                    
                                    
                                   //echo $newwidth . " |cek a rs| " . $newheight . "<br/>";
                                }else{
                                    $newwidth = $width;
                                    $newheight = $height;
                                    //echo $newwidth . " |cek xxx rs| " . $newheight . "<br/>";
                                }
                                //echo $newwidth . " w v0s h " . $newheight . "<br/>";
                            }elseif(!$TargetWidth and $TargetHeight > 0){
                                //echo $TargetHeight . " |cek b| " . $height . "<br/>";
                                if($TargetHeight < $height){
                                    $newheight = $TargetHeight;
                                    $newwidth = ($TargetHeight * $width) / $height;
                                    $newwidth = ceil($newwidth);
                                }else{
                                    $newwidth = $width;
                                    $newheight = $height;
                                }
                                //echo $newwidth . " w v1s h " . $newheight . "<br/>";
                            }else{
                                //echo "cek cek cek<br/>";
                                if($width < $height and $TargetHeight < $height){
                                    // echo $TargetHeight . " |cek c| " . $height . "<br/>";
                                    $newwidth = ($TargetHeight * $width) / $height;
                                    $newwidth = ceil($newwidth);
                                    $newheight = $TargetHeight;
                                }elseif($width >= $height and $TargetWidth < $width){
                                    // echo $TargetWidth . " |cek d| " . $width . "<br/>";
                                    $newheight = ($TargetWidth * $height) / $width;
                                    $newheight = ceil($newheight);
                                    $newwidth = $TargetWidth;
                                }else{
                                    $newwidth = $width;
                                    $newheight = $height;
                                }
                            }
                           //echo $newwidth . " w v2s h " . $newheight . "<br/>";
                        }
                        
                        
                        $tmp=imagecreatetruecolor($newwidth,$newheight);

                        imagecopyresampled($tmp,$src,0,0,0,0,$newwidth,$newheight, $width,$height);

                        $new_filename = $vl['new_name'].'.'.$extension;
                        $filename = $vl['path']. $new_filename;
                        $original_name = $_FILES[$file_post]['name'];
                        
                        $this->createDir($vl['path']);

                        if($extension=="jpg" || $extension=="jpeg" )
                        {
                            imagejpeg($tmp,$filename,90);
                        }
                        else if($extension=="png")
                        {
                            imagepng($tmp,$filename);
                        }
                        else 
                        {
                            imagegif($tmp,$filename);
                        }
                        
                        
                        imagedestroy($tmp);
                        $file_result[$startk] = array('file_new_name'=>$new_filename, 'file_orig_name'=>$original_name);
                        $startk++;
                    }
                    
                    imagedestroy($src);
                }
            }
            
        }else{
            $file_result[0] = false;
            $dbg_show .= 'No File Uploaded.<br/>';
        }
        
        if(strlen(trim($dbg_show)) > 0){
            $file_result[0] = false;
        }else{
            $file_result[0] = true;
        }
        $file_result[1] = $dbg_show;
        
		return $file_result;
	}

    function getExtension($str) {

         $i = strrpos($str,".");
         if (!$i) { return ""; } 

         $l = strlen($str) - $i;
         $ext = substr($str,$i+1,$l);
         return $ext;
    }
    
    function createDir($path) {

        if (!is_file($path) && !is_dir($path)) {
            mkdir($path); //create the directory
            chmod($path, 0777); //make it writable
        }
    }
    
    function RemoveFile($TheTarget){
        if(file_exists($TheTarget)){
            unlink($TheTarget);
        }

    }
    
    function UploadFile($TheFile, $TargetPath, $TargetName = ""){
		$path_parts = pathinfo($TheFile['name']);
        $extn = strtolower($path_parts['extension']);

		if(($extn != "rar") && ($extn != "zip") && ($extn != "doc") && ($extn != "xls") && ($extn != "docx") && ($extn != "xlsx") && ($extn != "pdf"))
        {
            $errors="<br/> Unknown File extension ";
            return "-1";
        }else{
			$size=filesize($TheFile['tmp_name']);

            if ($size > 4800*1024){
				//echo "You have exceeded the size limit";
				$errors="<br/> You have exceeded the size limit.";
				return "-1";
            }else{
//				if (file_exists("upload/" . $TheFile["name"])){
//				  echo $TheFile["name"] . " already exists. ";
//				}else{
				$thefile = $TargetName . "." . $extn;
                $file_save = $TargetPath . $thefile; // $TheFile['name'];
				move_uploaded_file($TheFile["tmp_name"], $file_save);
//				}
				return $thefile;
			}
		}
		
	}
}
