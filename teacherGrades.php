<?php
    require_once "config.php";
    checkLogin();
    $filter = "student";
    $tid = $_SESSION['tid'];
	$email = $_SESSION['email'];
	
	//--- Get QUIZES-----------------------	
		$stu_grade = $_SESSION['grade_level'];
		$sql = "SELECT quizes.*, classes.* FROM quizes INNER JOIN classes ON quizes.class_id = classes.class_id WHERE quizes.creator_tid = '$tid' ORDER BY quizes.quiz_id DESC";
		$result = mysql_query($sql);
		//d($sql,1);

		while($feedquiz  = mysql_fetch_object($result))
		{
			$feedquizs[] = $feedquiz;
		} 

		//--- Get ESSAY----------------------------------
		$query  = sprintf("SELECT essays.*, essay_comments.* FROM essays LEFT JOIN essay_comments ON essays.essay_id = essay_comments.essay_id Where essays.user_id = '$tid' ORDER BY essays.essay_id DESC");
		$result = mysql_query($query);
		while($assigned_class  = mysql_fetch_object($result))
		{
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

    <?php include "includes/menu.php"; ?>
    <?php include "includes/left_side_bar.php"; ?>

	<!-- main container -->
    <div class="content">

        
        <div class="container-fluid">


            <div id="pad-wrapper">

                <!-- statistics chart built with jQuery Flot -->

                <div class="row-fluid chart">
					<h2 style="float: left; font-weight: bold; margin: 18px 0px 13px 9px; font-size: 15px;">Grades From <?php echo $_SESSION['fname']." ".$_SESSION['lname'];?> </h2>
					<span style="float:left; height:780px; overflow-y:scroll; width:100%;">
					<table id="student_classes" class="table table-hover table-responsive">
					<tbody>
					<tr class="listingz" style="background:#a3a3a3">
						<td style="color:#000; font-weight:bold;">Name</td>
						<td style="color:#000; font-weight:bold;">Student Name</td>
						<td style="color:#000; font-weight:bold;">Due</td>
						<td style="color:#000; font-weight:bold;">Score</td>
						<td style="color:#000; font-weight:bold;">Out Of</td>
					</tr>
 					<!----SHOW QUIZ------------>
						<?php
						   if(is_array($feedquizs) && !empty($feedquizs))
							{
								//echo "<pre>"; print_r($feedquizs);
							  ?>
								<?php $i=1;
								   foreach ($feedquizs as $assigned_class) {

							//--get achieved points for this quiz----------------
									$quzID = $assigned_class->quiz_id;
									$sql = "SELECT * FROM quiz_answers Where quiz_id = '$quzID'";
									$result = mysql_query($sql);
									$quzGet = mysql_fetch_object($result);
									$countQZ = mysql_num_rows($result);
									if($countQZ !='0')
									{
								?>
									<tr>
									  <td>
										<img src="img/quiz_icon.png" height="16" width="16"/>
										<?/*<a target="_blank" href="start-answer.php?id=<?php echo $assigned_class->quiz_id;?>" style="text-decoration:underline">*/?>
										<a href="#" style="text-decoration:underline">
											<?php echo "Quiz". $i++?>
											<?php
												$qz_sub = $assigned_class->quiz_subject;
												if($qz_sub) { echo " (".$qz_sub.")"; }
											?>
										</a>
									  </td>
									  <td>
										<?php
											$sID = $quzGet->student_id;
											$queryer  = sprintf("SELECT * FROM students WHERE sid = '$sID'");
											$resulter = mysql_query($queryer);
											$er = mysql_fetch_object($resulter);
											echo $er->fname." ".$er->lname;
										?>
									  </td>
									  <td>
										<?php
										$date = date_create($assigned_class->quiz_holding_date_time);
										echo date_format($date, 'F j \a\t g:ia'); ?>
									  </td>
									  <td>
										<?php 
											$sqltot = "SELECT SUM(points) FROM quiz_answers Where student_id = '$sid' && quiz_id = '$quzID'";
											$resulttot = mysql_query($sqltot);
											$quzGettot = mysql_fetch_array($resulttot);
											//echo "<pre>"; print_r($quzGettot);
											echo round($quzGettot['SUM(points)']);
										?>
									  </td>
									  <td>
										<?php echo $assigned_class->points; ?>
									  </td>
									</tr>
									<?php /*} }*/ } } ?>
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
											<a target="_blank" href="essayReviews.php?id=<?php echo $assigned_class->essay_id;?>" style="text-decoration:underline">
											<?php echo "Essay". $i++." (".$assigned_class->essay_title.")";?>
										</a>
										</td>
										<td>
											<?php
												$sID = $assigned_class->for_student_id;
												$queryer  = sprintf("SELECT * FROM students WHERE sid = '$sID'");
												$resulter = mysql_query($queryer);
												$er = mysql_fetch_object($resulter);
												echo $er->fname." ".$er->lname;
											?>
									  </td>
									  <td>
										<?php
										$date = date_create($assigned_class->due_date);
										echo date_format($date, 'F j \a\t g:ia'); ?>
									  </td>
									  <td>
										<?php 
											$Grade = $assigned_class->grade; 
											if($Grade) { echo $Grade; } else { echo '0.00'; }
										?>
									  </td>
									  <td>10</td>
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

