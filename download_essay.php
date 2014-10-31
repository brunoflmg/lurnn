<?php
    require_once "config.php";
    if(isset($_SESSION['sid']) || isset($_SESSION['tid'])){
    	if(isset($_GET['id'])){
    		$id = $_GET['id'];
    		if(file_exists("tmp/essay_attachment_".$id.".zip")){
    			header('Content-type: application/zip');
				header("Content-Disposition: attachment; filename=essay_attachment_".$id.".zip");
                header("Content-Length: " . filesize("essay_attachment_".$id.".zip"));
				readfile("tmp/essay_attachment_".$id.".zip");
    		}else{
	    		$sql    = "SELECT *FROM essay_attachment WHERE student_essay_id='$id'";
	    		$result = mysql_query($sql);
	            if(mysql_num_rows($result)>0){
		    		$zip = new ZipArchive();
		    		$file_name = "essay_attachment_".$id.".zip";
					$zip->open("tmp/".$file_name,  ZipArchive::CREATE);

					while($file= mysql_fetch_object($result)) {
						$file_path = mb_substr(parse_url($file->file_path,PHP_URL_PATH), 1);
						//d($file_path);
					   $zip->addFile($file_path,end(explode("/",$file_path)));    
					}
					$zip->close();
					header('Content-type: application/zip');
					header("Content-Disposition: attachment; filename=$file_name");
	                header("Content-Length: " . filesize($file_name));
					readfile("tmp/".$file_name);
				}else{
					exit("404 error. File Not Found");
				}
			}
    	}else{
    		exit("404 error. File Not Found");
    	}

    	
    }else{
    	exit("404 error. File Not Found");
    }
	
?>