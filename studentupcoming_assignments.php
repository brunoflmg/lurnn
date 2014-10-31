<?php
    require_once "config.php";
    checkStudentLogin();
    $sid    = $_SESSION['sid'];
    $student_email = $_SESSION['email'];
	$stu_grade = $_SESSION['grade_level'];
	
    $query  = sprintf("SELECT upcoming_assignments.*, class_students.* FROM upcoming_assignments INNER JOIN class_students ON upcoming_assignments.class_id = class_students.class_id Where class_students.email = '$student_email' ORDER BY upcoming_assignments.asign_id DESC");
    $result = mysql_query($query);
    while($assigned_class  = mysql_fetch_object($result)){
       //$assigned_classes[] = $assigned_class;
    }

    
	//--- Get QUIZES-----------------------	
		//$sql = "SELECT quizes.*, class_students.* FROM quizes INNER JOIN class_students ON quizes.class_id = class_students.class_id Where class_students.email = '$student_email' ORDER BY quizes.quiz_id DESC";
		
		
		//$sql = "SELECT quizes.*, classes.* FROM quizes INNER JOIN classes ON quizes.class_id = classes.class_id Where classes.grade_level = '$stu_grade' ORDER BY quizes.quiz_id DESC";

		$sql = "SELECT quizes.*, classes.* FROM quizes INNER JOIN classes ON quizes.class_id = classes.class_id ORDER BY quizes.quiz_id DESC";
        $result = mysql_query($sql);
        //d($sql,1);
        while($feedquiz  = mysql_fetch_object($result))
		{
            $feedquizs[] = $feedquiz;
        } 
		//echo "<pre>"; print_r($feedquizs); die();
		
		$query  = sprintf("SELECT class_students.*, classes.* FROM class_students LEFT JOIN classes on class_students.class_id = classes.class_id where class_students.email = '$student_email' && class_students.added_by = 'teacher' ");
		$result = mysql_query($query);
		while($ext  = mysql_fetch_object($result))
		{
		   $exts[] = $ext;
		}
		
	//--- Get ESSAY----------------------------------
		$query  = sprintf("SELECT essays.*, class_students.* FROM essays INNER JOIN class_students ON essays.class_id = class_students.class_id Where class_students.grade_level = '$stu_grade' ORDER BY essays.essay_id DESC");
		$result = mysql_query($query);
		while($assigned_class  = mysql_fetch_object($result)){
			$feedessays[] = $assigned_class;
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
    <link href="//datatables.net/download/build/nightly/jquery.dataTables.css" rel="stylesheet" type="text/css" />
    
    <style>
       select{
        height:30px;
       }
    </style>
	<script type="text/javascript">
		function show_Details(str)
		{
			$('#back_fade').show();
			$.ajax({
				type: "POST",
				url: "viewAssignmentDetails.php",
				data: 'str='+str,
				context: document.body
				}).done(function(result){
					$('#myModalIS').show();
					$('.popUp_content').html(result);
				});
			return false;
		}
		
		function CloseMe()
		{
			$('#myModalIS').hide();
			$('#back_fade').hide();
		}
		
	//---SHOW ASSIGNMENT FUNCTION--------------------------
		function show_Details(str)
		{
			$('#back_fade').show();
			$.ajax({
				type: "POST",
				url: "viewAssignmentDetails.php",
				data: 'str='+str,
				context: document.body
				}).done(function(result){
					$('#myModalIS').show();
					$('.popUp_content').html(result);
				});
			return false;
		}
		
	</script>
	
</head>
<body>

    <?php include "includes/student_menu.php"; ?>
    <?php include "includes/student_left_side_bar.php"; ?>

	<!-- main container -->
    <div class="content">

        
        <div class="container-fluid">


            <div id="pad-wrapper">

                <!-- statistics chart built with jQuery Flot -->

                <div class="row-fluid chart">
					<h2 style="float: left; font-size: 12px; font-weight: bold; margin: 0px 0px 9px;">Upcoming Assignments</h2>
					<span style="float:left; height:780px; overflow-y:scroll; width:100%;">
					<table id="student_classes" class="table table-hover table-responsive">
					<tbody>
                    <?php
                       if(is_array($assigned_classes) && !empty($assigned_classes)){
                          ?>
                               <?php $i=1;
                               foreach ($assigned_classes as $assigned_class) {
							   //echo "<pre>"; print_r($assigned_class);
							   $classID =  $assigned_class->class_id;
							//--- Below code will show the record of only tose classes in which current login student is assigned-------------- 
                               ?>
                                <tr class="<?php echo $assigned_class->status;?>">
                                  <td>
									<img src="img/assignment_icon.png" height="16" width="16"/>
									<span onclick="show_Details(<?php echo $assigned_class->asign_id ?>)" style="color:#0088cc; text-decoration:underline; cursor:pointer;">
										<?php echo $assigned_class->asign_name;?>
									</a>
								   </td>
                                  <td>
										<?php
										$date = date_create($assigned_class->avlaible_until);
										echo "<b>Available until </b>".date_format($date, 'F j'); ?>
									  </td>
									  <td>
										<?php
										$date = date_create($assigned_class->due_date);
										echo "<b>Due </b>".date_format($date, 'F j \a\t g:ia'); ?>
									  </td>
                                </tr>
                                <?php } ?>
                          <?php
                       }
					?>
					<!----SHOW QUIZ------------>
						<?php
						   if(is_array($feedquizs) && !empty($feedquizs))
							{
								//echo "<pre>"; print_r($feedquizs);
							  ?>
								<?php $i=1;
								   foreach ($feedquizs as $assigned_class) {
								   $quzID = $assigned_class->quiz_id;
									$sql = "SELECT * FROM quiz_answers Where student_id = '$sid' && quiz_id = '$quzID'";
									$result = mysql_query($sql);
									$quzGet = mysql_fetch_object($result);
									$countQZ = mysql_num_rows($result);
									if($countQZ !='0')
									{
								?>
									<tr>
									  <td>
										<img src="img/quiz_icon.png" height="16" width="16"/>
										<a target="_blank" href="start-quiz-answer.php?id=<?php echo $assigned_class->quiz_id;?>" style="text-decoration:underline">
										<?/*<a href="#" style="text-decoration:underline">*/?>
											<?php echo "Quiz". $i++?>
											<?php 
												$qz_sub = $assigned_class->quiz_subject;
												if($qz_sub) { echo " (".$qz_sub.")"; }
											?>
										</a>
									  </td>
									  <td>
										<?php
										$date = date_create($assigned_class->quiz_created_date);
										echo "<b>Available until </b>".date_format($date, 'F j'); ?>
									  </td>
									  <td>
										<?php
										$date = date_create($assigned_class->quiz_holding_date_time);
										echo "<b>Due </b>".date_format($date, 'F j \a\t g:ia'); ?>
									  </td>
									</tr>
									<?php } } ?>
							  <?php
							}
						?>
						
					<!----SHOW ESSAY------------>
						<?php
						   if(is_array($feedessays) && !empty($feedessays))
							{
								
							  ?>
								<?php $i=1;
								   foreach ($feedessays as $assigned_class) {
								   //echo "<pre>"; print_r($assigned_class);
								   $classID =  $assigned_class->class_id;
								//--- Below code will show the record of only tose classes in which current login student is assigned-------------- 
								   ?>
									<tr>
									  <td>
										<img src="img/essay_icon.png" height="16" width="16"/>
											<a target="_blank" href="view_essay_student.php?id=<?php echo $assigned_class->essay_id;?>" style="text-decoration:underline">
											<?php echo "Essay". $i++." (".$assigned_class->essay_title.")";?>
										</a>
										</td>
									  <td>
										<?php
										$date = date_create($assigned_class->avlaible_until);
										echo "<b>Available until </b>".date_format($date, 'F j'); ?>
									  </td>
									  <td>
										<?php
										$date = date_create($assigned_class->due_date);
										echo "<b>Due </b>".date_format($date, 'F j \a\t g:ia'); ?>
									  </td>
									</tr>
									<?php } ?>
							  <?php
							}
						?>
						
					</tbody>
					</table>
					</span>
                </div>
            </div>
        </div>
    </div>

	<div class="row-fluid">
	<div class="modal-backdrop fade in" id="back_fade" style="display:none;"></div>
	<div aria-hidden="false" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" class="modal hide fade in" id="myModalIS" style="display: none; margin-top:30px;">
			<div class="modal-header">
				<span id="CloseMe" onclick="return CloseMe();" style="float: right; color: gray; font-weight: bold;">X</span>
				<div class="popUp_content"></div>
			</div>
	</div>
</div>

	<!-- scripts -->
    <script src="http://code.jquery.com/jquery-latest.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery-ui-1.10.2.custom.min.js"></script>
    <!-- knob -->
    <script src="js/jquery.knob.js"></script>
    <!-- flot charts -->
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>

    <script src="js/theme.js"></script>
    <script src="//datatables.net/download/build/nightly/jquery.dataTables.js"></script>
    <script type="text/javascript">
        $(function () {

            // jQuery Knobs
            $(".knob").knob();

            $('#student_classes').DataTable();

            
        });
    </script>
</body>
</html>