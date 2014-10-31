<?php
  
    if(!function_exists("d")){

		function d($var,$exit=false){

			$debug = debug_backtrace();

			print("<fieldset> Dumps a Variable at <br/>".$debug[0]['file']."<br/> Line No : ".$debug[0]['line']."<br/></fieldset>");

			echo "<pre><fieldset>";

			print_r($var);

			echo "</fieldset></pre>";

			if($exit) exit("<fieldset>Program Exited at<br/>".$debug[0]['file']."<br/> Line No : ".$debug[0]['line']."<br/></fieldset>");

		}

	}

	if(!function_exists("setFlashData")){

		function setFlashData($item, $value){
			$_SESSION['flash_data'][$item] = $value;
		}
	}

	if(!function_exists("getFlashData")){

		function getFlashData($item){
			$data = $_SESSION['flash_data'][$item];
			unset($_SESSION['flash_data'][$item]);
			return $data;
		}
	}
	
	if(!function_exists("checkLogin")){
		function checkLogin(){
			if(!isset($_SESSION['tid'])){
			   setFlashData("error_message","You don't have access this page. Please login to this page.");
		       echo "<script>window.location='http://".$_SERVER["SERVER_NAME"]."/signin.php';</script>";exit;
		    }
		}
	}
	if(!function_exists("checkStudentLogin")){

		function checkStudentLogin($checkQuiz=TRUE){
			if(!isset($_SESSION['sid'])){
			   setFlashData("error_message","You don't have access this page. Please login to this page.");
		       echo "<script>window.location='http://".$_SERVER["SERVER_NAME"]."/signin.php';</script>";exit;
		    }
		    //d(basename($_SERVER["PHP_SELF"]),1);
		    if(($_SESSION['grade_level']=="" || $_SESSION['grade_level']==NULL) && basename($_SERVER['PHP_SELF'])!="profile-info.php"){
               
               setFlashData("error_message","Please Edit Your Profile at First, You must add your grade level");
		       echo "<script>window.location='http://".$_SERVER["SERVER_NAME"]."/profile-info.php';</script>";exit;
		    }
		    if($checkQuiz){
		    	checkPendingQuiz($_SESSION['sid']);
		    }
		    
		}
	}

	function validateDate($date, $format = 'Y-m-d H:i:s')
    {
      $d = DateTime::createFromFormat($format, $date);
      return $d && $d->format($format) == $date;
    }
	


	function time_elapsed_string($ptime)
	{
	    $etime = time() - $ptime;

	    if ($etime < 1)
	    {
	        return '0 seconds';
	    }

	    $a = array( 12 * 30 * 24 * 60 * 60  =>  'year',
	                30 * 24 * 60 * 60       =>  'month',
	                24 * 60 * 60            =>  'day',
	                60 * 60                 =>  'hour',
	                60                      =>  'minute',
	                1                       =>  'second'
	                );

	    foreach ($a as $secs => $str)
	    {
	        $d = $etime / $secs;
	        if ($d >= 1)
	        {
	            $r = round($d);
	            return $r . ' ' . $str . ($r > 1 ? 's' : '') . ' ago';
	        }
	    }
	}
	function curPageURL() {
	 $pageURL = 'http';
	 if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
	 $pageURL .= "://";
	 if ($_SERVER["SERVER_PORT"] != "80") {
	  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
	 } else {
	  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	 }
	 return $pageURL;
	}
    function formatSizeUnits($bytes)
    {
        if ($bytes >= 1073741824)
        {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        }
        elseif ($bytes >= 1048576)
        {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        }
        elseif ($bytes >= 1024)
        {
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        }
        elseif ($bytes > 1)
        {
            $bytes = $bytes . ' bytes';
        }
        elseif ($bytes == 1)
        {
            $bytes = $bytes . ' byte';
        }
        else
        {
            $bytes = '0 bytes';
        }

        return $bytes; 
    }
    function checkPendingQuiz($sid){
    	$query  = sprintf("SELECT * FROM quiz_student_extented_info  WHERE sid='%s' AND quiz_assined_status='untaken' AND quiz_status='active'",$sid);
	    //d($query);
	    $result = mysql_query($query); 
	    if(mysql_num_rows($result)>0){
	       header("Location: take-quiz.php");exit;
	    }
    }

