<?php
ob_start();
require_once "config.php";
checkStudentLogin();
$sid    = $_SESSION['sid'];
$student_email = $_SESSION['email'];
$stu_grade = $_SESSION['grade_level'];
   $current_date = date("Y-m-d");
//----- Get QUIZS-----------------------------
	//$sql = "SELECT quizes.*, class_students.* FROM quizes INNER JOIN class_students ON quizes.class_id = class_students.class_id Where class_students.email = '$student_email' && MONTH(quizes.quiz_holding_date_time) = MONTH(CURDATE()) ORDER BY quizes.quiz_id DESC";
	//--10-9-14--//$sql = "SELECT quizes.*, class_students.* FROM quizes INNER JOIN class_students ON quizes.class_id = class_students.class_id Where class_students.email = '$student_email' ORDER BY quizes.quiz_id DESC";
	$sql = "SELECT quizes.*, classes.* FROM quizes INNER JOIN classes ON quizes.class_id = classes.class_id Where classes.grade_level = '$stu_grade' AND quizes.is_aptitude = '0' ORDER BY quizes.quiz_id DESC";
	$result = mysql_query($sql);
	//d($sql,1);
	while($feedquiz  = mysql_fetch_object($result))
	{
		$feedquizs[] = $feedquiz;
	}
	

//----- Get EVENTS-----------------------------
	$sql1x = "SELECT * FROM add_events ORDER BY event_id DESC";
	$result1x = mysql_query($sql1x);
        //d($sql,1);
        while($eventz  = mysql_fetch_object($result1x))
		{
            $eventzs[] = $eventz;
        } 
	
	
//--GET ESSAYS--------------------
	//$query  = sprintf("SELECT essays.*, class_students.* FROM essays INNER JOIN class_students ON essays.class_id = class_students.class_id Where class_students.email = '$student_email' && MONTH(essays.avlaible_until) = MONTH(CURDATE()) ORDER BY essays.essay_id DESC");
	$query  = sprintf("SELECT essays.*, class_students.* FROM essays INNER JOIN class_students ON essays.class_id = class_students.class_id Where class_students.email = '$student_email' ORDER BY essays.essay_id DESC");
		$result = mysql_query($query);
		while($assigned_class  = mysql_fetch_object($result))
		{
			$feedessays[] = $assigned_class;
		}
	//echo "<pre>"; print_r($feedessays); die;
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
	<?php include "includes/student_menu.php"; ?>
	<?php include "includes/student_left_side_bar.php"; ?>

	<!-- main container -->
    <div class="content">
        
        <div class="container-fluid">
            <div id="pad-wrapper">
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

    <!-- builds fullcalendar example -->
    <script>
        $(document).ready(function() {
        
            var date = new Date();
            var d 	 = date.getDate();
            var m  	 = date.getMonth();
            var y    = date.getFullYear();
            //alert(y); alert(m);
            $('#calendar').fullCalendar({
                header: {
                    left: 'month,agendaWeek,agendaDay',
                    center: 'title',
                    right: 'today prev,next'
                },
                selectable: true,
                selectHelper: true,
                //editable: true,
                events: [
					<?php $i='1'; 
					foreach($feedquizs as $quizs) {
						$datez = $quizs->quiz_holding_date_time;
						$exp  = explode(' ',$datez); // remove time
						$fullDate = $exp['0'];
						$fullTime = $exp['1'];
						// get only date
							$exp2 = explode('-',$fullDate);
							$show_year = $exp2['0'];
							$show_mnth = $exp2['1'];
							$show_date = $exp2['2'];
						
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
						//alert(<?php echo $trik; ?>);
                        title: '<?php echo $esay->essay_title; ?>',
                        //start: '<?php echo $show_year; ?>, <?php echo show_mnth; ?>, <?php echo show_date; ?>',
						start: '<?php echo $trikx; ?>'
                    },
					<?php }
					//--SHOW EVENTS------------------------
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
                        title: '<?php echo $evnt->event_title; ?>',
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
    </script>
</body>
</html>