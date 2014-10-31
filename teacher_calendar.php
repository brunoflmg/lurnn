<?php
ob_start();
require_once "config.php";
   require_once "config.php";
   if(isset($_SESSION['tid'])){
      $user_id = $_SESSION['tid'];
      $type    = "teacher";
   }else if(isset($_SESSION['sid'])){
      $user_id = $_SESSION['sid'];
      $type    = "student";
   }else{
        echo "<script>window.location='/';</script>";
        exit;
   }
	
	$stu_grade = $_SESSION['grade_level'];
	
	$sql    = "SELECT * FROM `classes` WHERE creator_tid ='".$_SESSION['tid']."'";
        //d($user,1);
        $result = mysql_query($sql);

        while($class = mysql_fetch_object($result)){
            $classes[$class->class_id] = $class;
        }
	
   $current_date = date("Y-m-d");
   
//----- Get EVENTS-----------------------------
	$sql1x = "SELECT * FROM add_events Where creator_id = '$user_id' ORDER BY event_id DESC";
	$result1x = mysql_query($sql1x);
        //d($sql,1);
        while($eventz  = mysql_fetch_object($result1x))
		{
            $eventzs[] = $eventz;
        } 
		
		
//----- Get QUIZS-----------------------------
	$sql1 = "SELECT quizes.*, teachers.* FROM quizes INNER JOIN teachers ON quizes.creator_tid = teachers.tid Where quizes.creator_tid = '$user_id' ORDER BY quizes.quiz_id DESC";
        $result1 = mysql_query($sql1);
        //d($sql,1);
        while($feedquiz  = mysql_fetch_object($result1))
		{
            $feedquizs[] = $feedquiz;
        } 

//--GET ESSAYS--------------------
	$query  = sprintf("SELECT essays.*, teachers.* FROM essays INNER JOIN teachers ON essays.user_id = teachers.tid Where essays.user_id = '$user_id' ORDER BY essays.essay_id DESC");
		$result = mysql_query($query);
		while($assigned_class  = mysql_fetch_object($result))
		{
			$feedessays[] = $assigned_class;
		}
	//echo "<pre>"; print_r($feedessays); die;
	
//----CODE TO ADD ESSAYS -----------------------------------------
	if((isset($_SESSION['tid']) || isset($_SESSION['sid'])) && isset($_POST['submit'])){	
      if(isset($_POST['post_title']) && $_POST['post_title']!=""){
        $post_title = mysql_real_escape_string(htmlspecialchars(trim($_POST['post_title'])));
      }else{
        $errors['post_title'] = "Essay Title is required";
      }
      if(isset($_POST['post_content']) && $_POST['post_content']!=""){
        $post_content = mysql_real_escape_string(htmlspecialchars(trim($_POST['post_content'])));
      }else{
        $errors['post_content'] = "Essay Description is required";
      }
      if(isset($_POST['class_id']) && $_POST['class_id']!=""){
        $class_id = mysql_real_escape_string(trim($_POST['class_id']));
      }else{
        $errors['class_id'] = "Class is required";
      }
      if(isset($_POST['attachments']) && !empty($_POST['attachments'])){
        $attachments = $_POST['attachments'];
      }
	  
	  $available_to = mysql_real_escape_string(htmlspecialchars(trim($_POST['available_until'])));
	  
	  $getDate = $_POST['dateINFO'];
	  
	  $due_date     = mysql_real_escape_string(htmlspecialchars(trim($_POST['due_date_time'])));
	  
      if(!isset($errors) || empty($errors)){
         $sql = sprintf("INSERT INTO essays(class_id,posted_by,user_id,type,essay_title,essay_content,has_attachment,created_date,avlaible_until,due_date) VALUES('%d','%s','%d','%s','%s','%s','%s','%s','%s','%s')",
                $class_id,
                $_SESSION['email'],
                $user_id,
                $type,
                $post_title,
                $post_content,
                isset($attachments)? TRUE : FALSE,
                date("Y-m-d H:i:s"),
				$getDate,
				$due_date
            );
         if(mysql_query($sql)){
            $post_id = mysql_insert_id();
            if(isset($attachments) && is_array($attachments) && !empty($attachments)){
                foreach ($attachments as $attachment) {
                    $sql = sprintf("INSERT INTO essay_attachment(essay_id, file_path,file_type) VALUES('%d','%s','%s')",
                            $post_id,
                            $attachment,
                            'other'
                        );
                    mysql_query($sql);
                }
                
                @session_regenerate_id();

            }
         }else{
            $errors['other'] = "Sorry!!! Internal Problem! Please try again later";
         }
      }

//---get Last Added Essay Details----------------------------	
	$end_sql    = "SELECT * FROM essays ORDER BY essay_id DESC LIMIT 1";
    $end_result = mysql_query($end_sql);
    $end_id = mysql_fetch_object($end_result);
	//echo "<pre>"; print_r($end_id);
	$Xessay_id = $end_id->essay_id;
	$Xclass_id = $end_id->class_id;
	$Xessay_title = $end_id->essay_title;
	$Xessay_content = $end_id->essay_content;
	$Xhas_attachment = $end_id->has_attachment;
	$Xcreated_date = $end_id->created_date;
	$Xlast_updated = $end_id->last_updated;
	$Xuser_id = $end_id->user_id;
	$Xtypes = $end_id->type;

//---get Teacher/Student Details---------------------------------------	
	if($Xtypes == 'teacher')
	{
		$end_sql1    = "SELECT * FROM teachers where tid='$Xuser_id'";
		$end_result1 = mysql_query($end_sql1);
		$end_id1 = mysql_fetch_object($end_result1);
		//echo "<pre>"; print_r($end_id);
		$Xuser_name = $end_id1->user_name;
		$Xfname = $end_id1->fname;
		$Xlname = $end_id1->lname;
		$Xemail = $end_id1->email;
		$Xprofile_picture = $end_id1->profile_picture;
		$Xtime_zone = $end_id1->time_zone;
		$Xtype      = $Xtypes;
	}
	else
	{
		$end_sql1    = "SELECT * FROM students where sid='$Xuser_id'";
		$end_result1 = mysql_query($end_sql1);
		$end_id1 = mysql_fetch_object($end_result1);
		//echo "<pre>"; print_r($end_id);
		$Xuser_name = $end_id1->user_name;
		$Xfname = $end_id1->fname;
		$Xlname = $end_id1->lname;
		$Xemail = $end_id1->email;
		$Xprofile_picture = $end_id1->profile_picture;
		$Xtime_zone = $end_id1->time_zone;
		$Xtype      = $Xtypes;
	}
	
//---get Class Details---------------------------------------	
	$end_sql2    = "SELECT * FROM classes where class_id='$Xclass_id'";
    $end_result2 = mysql_query($end_sql2);
    $end_id2 = mysql_fetch_object($end_result2);
	//echo "<pre>"; print_r($end_id);
	$Xclass_name = $end_id2->class_name;
	$Xclass_code = $end_id2->class_code;
	$Xclass_details = $end_id2->class_details;
	$Xgrade_level = $end_id2->grade_level;

//---get Total Attachments---------------------------------------	
	$end_sql3    = "SELECT * FROM essay_attachment where essay_id='$Xessay_id'";
    $end_result3 = mysql_query($end_sql3);
    $Xtotal_attachments =  mysql_num_rows($end_result3);
	
//---get Total Comments-------------------------------------------
	$Xtotal_comments = '0';

//--Now Insert----------------------------------------------------
	
	$sql = sprintf("INSERT INTO essay_details_with_comments_attachments(essay_id,class_id,essay_title,essay_content,has_attachment,created_date,last_updated,user_id,user_name,fname,lname,email,profile_picture,time_zone,type,class_name,class_code,class_details,grade_level,total_comments,total_attachments,essayByStudent) VALUES('$Xessay_id','$Xclass_id','$Xessay_title','$Xessay_content','$Xhas_attachment','$Xcreated_date','$Xlast_updated','$Xuser_id','$Xuser_name','$Xfname','$Xlname','$Xemail','$Xprofile_picture','$Xtime_zone','$Xtype','$Xclass_name','$Xclass_code','$Xclass_details','$Xgrade_level','$Xtotal_comments','$Xtotal_attachments','')");
	
	mysql_query($sql);	
	  
	if(isset($errors) && !empty($errors)){
         $_SESSION['flash_errors'] = $errors;
      }else{
         //$_SESSION['flash_success'] = "You have successfully created this post";
      }
      //header('Location: http://www.google.com', true); exit;
	  echo "<script>window.location='teacher_calendar.php';</script>";
        exit;
   }
	
//---AFTER SUBMIT EVENT----------------------------------
	
	if((isset($_SESSION['tid']) || isset($_SESSION['sid'])) && isset($_POST['submitevent'])){	
      if(isset($_POST['event_title']) && $_POST['event_title']!=""){
        $post_title = mysql_real_escape_string(htmlspecialchars(trim($_POST['event_title'])));
      }else{
        $errors['event_title'] = "Essay Title is required";
      }
	  
      if(isset($_POST['event_content']) && $_POST['event_content']!=""){
        $post_content = mysql_real_escape_string(htmlspecialchars(trim($_POST['event_content'])));
      }else{
        $errors['event_content'] = "Essay Description is required";
      }
	  
	  $added_date     = mysql_real_escape_string(htmlspecialchars(trim($_POST['added_date'])));
      
		$event_title   = $_POST['event_title'];
		$event_content = $_POST['event_content'];
		$timestamp     = $_POST['added_date'];
		$date_added    = $_POST['dateINFO'];
		$creator_id    = $_SESSION['tid'];
		
		$sql = sprintf("INSERT INTO add_events(event_id,event_title,event_desc,creator_id,timestamp,created_date) VALUES('','$event_title', '$event_content', '$creator_id', '$timestamp', '$date_added')");
	
		mysql_query($sql);	
		  
		if(isset($errors) && !empty($errors)){
			 $_SESSION['flash_errors'] = $errors;
		  }else{
			 //$_SESSION['flash_success'] = "You have successfully created this post";
		  }
		  //header('Location: http://www.google.com', true); exit;
		  echo "<script>window.location='teacher_calendar.php';</script>";
			exit;
	}
	
	?>

<!DOCTYPE html>
<html>
<head>
	<title>lurnn</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	  <meta name="viewport" content="width=device-width, initial-scale=1.0">
	
    <!-- bootstrap -->
    <link href="css/bootstrap/bootstrap.css" rel="stylesheet" />
    <link href="css/bootstrap/bootstrap-responsive.css" rel="stylesheet" />
    <link href="css/bootstrap/bootstrap-overrides.css" type="text/css" rel="stylesheet" />

    <!-- libraries -->
    <link href="css/lib/jquery-ui-1.10.2.custom.css" rel="stylesheet" type="text/css" />
    <link href="css/lib/font-awesome.css" type="text/css" rel="stylesheet" />

    <!-- global styles -->
    <link rel="stylesheet" type="text/css" href="css/layout.css">
    <link rel="stylesheet" type="text/css" href="css/elements.css">
    <link rel="stylesheet" type="text/css" href="css/icons.css">
	
	<!-- libraries -->
    <link href="css/lib/font-awesome.css" type="text/css" rel="stylesheet" />
    <link href='css/lib/fullcalendar.css' rel='stylesheet' />
    <link href='css/lib/fullcalendar.print.css' rel='stylesheet' media='print' />
	
    <!-- this page specific styles -->
    <link rel="stylesheet" href="css/compiled/index.css" type="text/css" media="screen" />    

    <!-- open sans font -->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800' rel='stylesheet' type='text/css'>

    <!-- lato font -->
    <link href='http://fonts.googleapis.com/css?family=Lato:300,400,700,900,300italic,400italic,700italic,900italic' rel='stylesheet' type='text/css'>

    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    <link rel="shortcut icon" href="./img/favicon.jpg"/>

</head>
<body>
	<?php
		include "includes/menu.php";
		include "includes/left_side_bar.php";
	?>
	
	<!-- main container -->
    <div class="content">
        
        <div class="container-fluid">
            <div id="pad-wrapper">
			<p id="mySEND"></p>
                <div class="row-fluid calendar-wrapper">
                    <div class="span12">

                        <!-- div that fullcalendar plugin uses  -->
                        <div id='calendar'></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end main container -->

	<!-----GET RECORDS-------------------------------------->
	
		
	
	<!------------------------------------------------------>

	<!-- scripts for this page -->
    <script src="http://code.jquery.com/jquery-latest.js"></script>
    <script src="js/jquery-ui-1.10.2.custom.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src='js/fullcalendar.min.js'></script>
    <script src="js/theme.js"></script>
	<link href="css/bootstrap-datetimepicker.css" rel="stylesheet" media="screen">
	<script type="text/javascript" src="js/bootstrap-datetimepicker.min.js" charset="UTF-8"></script>
    <script type="text/javascript">
        $(function () {
           // jQuery Knobs
			$('.form_datetime').datetimepicker({
				//language:  'fr',
				weekStart: 1,
				todayBtn:  1,
				autoclose: 1,
				todayHighlight: 1,
				startView: 2,
				forceParse: 0,
				showMeridian: 1
			});
			
        });
    </script>
    <!-- builds fullcalendar example -->
    <script type="text/javascript">
        $(document).ready(function() {
			
			var date = new Date();
            var d = date.getDate();
            var m = date.getMonth();
            var y = date.getFullYear();
            
            $('#calendar').fullCalendar({
                header: {
                    left: 'month,agendaWeek,agendaDay',
                    center: 'title',
                    right: 'today prev,next'
                },
                selectable: true,
                selectHelper: true,
                editable: false,
				timeFormat: 'H:mm{-H:mm} ',	
				dayClick: function(date, allDay, jsEvent, view) {
					//alert(date);
					//$('#mySEND').html(date);
					clikdDate(date);
				},
				/*eventClick: function(calEvent, allDay, jsEvent, view)
				{
					alert(calEvent.title);
					alert(date);
				},*/
                events: [
					<?php $i='1'; 
					foreach($feedquizs as $quizs) {
						$datez = $quizs->quiz_holding_date_time;
						$exp  = explode(' ',$datez); // remove time
						$fullDate = $exp['0'];
						$fullTime = $exp['1'];
						// get only date
							$exp2 = explode('-',$fullDate);
							$show_year  = $exp2['0'];
							$show_mnth  = $exp2['1'];
							$show_date  = $exp2['2'];
						
						// get only TIME
							$exp3 = explode(':',$fullTime);
							$s_time = $exp3['0'];
							$s_mins = $exp3['1'];
							$trik =  $show_year.", ".$show_mnth.", ".$show_date;
					?>
                    {
                        title: '<?php echo "quiz ".$i++." ".$quizs->quiz_subject.""; ?>',
                        start: '<?php echo $trik; ?>'
                    },
					<?php } 
					//--SHOW ESSAYS------------------------
						foreach($feedessays as $esay) {
						$datezx = $esay->avlaible_until;
						$expx  = explode(' ',$datezx); // remove time
						$fullDatex = $expx['0'];
						$fullTimex = $expx['1'];
						// get only date
							$exp2x = explode('-',$fullDatex);
							$show_yearx = $exp2x['0'];
							$show_mnthx = $exp2x['1'];
							$show_datex = $exp2x['2'];
						
						// get only TIME
							$exp3x = explode(':',$fullTimex);
							$s_timex = $exp3x['0'];
							$s_minsx = $exp3x['1'];
							$trikx =  $show_yearx.", ".$show_mnthx.", ".$show_datex;
					?>
                    {
                        title: '<?php echo $esay->essay_title; ?>',
                        start: '<?php echo $trikx; ?>'
                    },
					<?php }
					//--SHOW EVENTS------------------------
						$i='1';
						foreach($eventzs as $evnt) {
						$datezx = $evnt->created_date;
						$expx  = explode(' ',$datezx); // remove time
						$fullDatex = $expx['0'];
						$fullTimex = $expx['1'];
						// get only date
							$exp2x = explode('-',$fullDatex);
							$show_yearx = $exp2x['0'];
							$show_mnthx = $exp2x['1'];
							$show_datex = $exp2x['2'];
						
						// get only TIME
							$exp3x = explode(':',$fullTimex);
							$s_timex = $exp3x['0'];
							$s_minsx = $exp3x['1'];
							$trikx =  $show_yearx.", ".$show_mnthx.", ".$show_datex;
					?>
                    {
                        title: '<?php echo "Event ".$i.' '.$evnt->event_title; ?>',
                        start: '<?php echo $trikx; ?>'
                    },
					<?php }
					?>
                ],
                eventBackgroundColor: '#278ccf'
            });

            
            // handler to close the new event popup just for displaying purposes
            // more documentation for fullcalendar on http://arshaw.com/fullcalendar/
            $(".popup .close-pop").click(function () {
                $(".new-event").fadeOut("fast");
            });
        });
		
		function clikdDate(str)
		{
			convert(str);
		}
		
		function convert(strs) {
			var date = new Date(strs),
				mnth = ("0" + (date.getMonth()+1)).slice(-2),
				day  = ("0" + date.getDate()).slice(-2);
			var aa = [ date.getFullYear(), mnth, day ].join("-");
			
			$('#InfoByAjax').val(aa);
			$('#fades').show();
			$('.annoucementINF').show();
		}

		function HideMe2()
		{
			$('#fades').hide();
			$('.annoucementINF').hide();
		}
		
		function essayAdd()
		{
			var date = $('#InfoByAjax').val();
			$('#myModalEvent').hide();
			$('#myModal').show();
		}
		
		function eventAdd()
		{
			var date = $('#InfoByAjax').val();
			$('#myModal').hide();
			$('#myModalEvent').show();
		}
		
    </script>
	
<!-- CODE TO SHOW POP-UP AFTER CLiCK ON ANY DATE----->
	<div id="myModal_pOp" class="annoucementINF" style="display:none; height:auto; top:13%; margin:0 0 0 24%; width:50%;">
		<form enctype="multipart/form-data" method="post" id="fileupload">
			<div class="modal-header">
				<span id="CrossME" onclick="return HideMe2();" >x</span>
				<span onclick="return eventAdd();" style="float: left; border: 1px solid #c3c3c3; padding: 0px 6px; cursor: pointer;" class="btn btn-primary">Add Event</span>
				
				<span onclick="return essayAdd();" style="float: left; border: 1px solid #c3c3c3; padding: 0px 6px; cursor: pointer;" class="btn btn-primary">Add Essay</span>
			
			<!---below form to add event -->
				<div id="myModalEvent" style="display: none; width: 100%; margin: 32px 0px 0px; overflow-y: scroll; height: 400px;">
                    <form  id="fileupload" method="post" enctype="multipart/form-data">
                    <div class="modal-header">
                    <h3 id="myModalLabel">Create Event</h3>
                    </div>
					<input type="hidden" id="InfoByAjax" name="dateINFO" value=""/>
                    <div class="modal-body" style="overflow:none;">
                   
                        <div class="control-group">
                            <!--<label class="control-label" for="inputEmail">Subject</label>-->
                            <div class="controls">
                                <input type="text" name="event_title" required="required" class="span12" placeholder="Type Your Event Title" style="width: 100%; float: left;">
                            </div>
                        </div>
                        <div class="control-group">
                            <!--<label class="control-label" for="input">Description</label>-->
                            <div class="controls">
                                <textarea name="event_content" required="required" class="animated span12" placeholder="Write Your Event Description" style="width: 100%; float: left;"></textarea>
                            </div>
                        </div>
                        
						<div class="control-group">
                            <div class="controls">
                                <div class="span6" style="margin-left:4px;">
                                    <label>Date:</label>
									<div class="input-append date form_datetime">
										<span class="add-on"><i class="icon-th"></i></span>
										<input style="width: 100%;" size="16" type="text" name="added_date" class="span12" required="required" value="" readonly>
									</div>
                                </div>
                            </div>
                        </div>
                        <table role="presentation" class="table table-striped"><tbody class="files"></tbody></table>       
                       
                    
                     </div>
                    <div class="modal-footer">
                     <button type="submit" name="submitevent" class="btn btn-primary">Create Event</button>
                    </div>
                    </form>
                    </div>
			
			<!---below form to add essay -->
				<div id="myModal" style="display: none; width: 100%; margin: 32px 0px 0px; overflow-y: scroll; height: 400px;">
                    <form  id="fileupload" method="post" enctype="multipart/form-data">
                    <div class="modal-header">
                    <h3 id="myModalLabel">Create Essay Prompt</h3>
                    </div>
					<input type="hidden" id="InfoByAjax" name="dateINFO" value=""/>
                    <div class="modal-body" style="overflow:none;">
                   
                        <div class="control-group">
                            <!--<label class="control-label" for="inputEmail">Subject</label>-->
                            <div class="controls">
                                <input type="text" name="post_title" required="required" class="span12" placeholder="Type Your Essay Prompt" style="width: 100%; float: left;">
                            </div>
                        </div>
                        <div class="control-group">
                            <!--<label class="control-label" for="input">Description</label>-->
                            <div class="controls">
                                <textarea name="post_content" required="required" class="animated span12" placeholder="Write Your Essay Requirements" style="width: 100%; float: left;"></textarea>
                            </div>
                        </div>
                        <div class="control-group" style="height:70px;">
                            
                            <div class="controls">
                                <div class="span6">
                                    <label>Choose Class:</label>
                                    <div class="ui-select span12">
                                        <select style="width: 100%; float: left;" name="class_id" required="required">
                                        <?php if(is_array($classes) && !empty($classes) ){ 

                                           foreach($classes as $class){
                                        ?>

                                            <option value="<?php echo $class->class_id;?>"><?php echo $class->class_name;?></option>
                                            <?php }} ?>
                                        </select>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
						
						<div class="control-group">
                            <!--<label class="control-label" for="input">Description</label>-->
                            <div class="controls">
                                <div class="span6">
                                    <label>Due Date:</label>
									<div class="input-append date form_datetime">
                                    <span class="add-on"><i class="icon-th"></i></span>
                                    <input style="width: 100%;" size="16" type="text" name="due_date_time" class="span12" required="required" value="" readonly>
                                    
                                </div>
                                </div>
                            </div>
                        </div>
						
						<?/*<div class="control-group">
                            <div class="controls">
                                <div class="span6">
                                    <label>Available Until:</label>
									<div class="input-append date form_datetime">
                                    <span class="add-on"><i class="icon-th"></i></span>
                                    <input style="width: 100%; float: left;" size="16" type="text" name="available_until" class="span12" required="required" value="" readonly>
                                    
                                </div>
                                </div>
                            </div>
                        </div>*/?>

                        <table role="presentation" class="table table-striped"><tbody class="files"></tbody></table>       
                       
                    
                     </div>
                    <div class="modal-footer">
                     <button type="submit" name="submit" class="btn btn-primary">Create Essay Prompt</button>
                    </div>
                    </form>
				</div>
			</div>
		</form>
	</div>
	<div class="modal-backdrop fade in" id="fades" style="display:none;"></div>
</body>
</html>