<?php
    require_once "config.php";
    checkStudentLogin();
    $sid    = $_SESSION['sid'];
    $student_email = $_SESSION['email'];

    //$query  = sprintf("SELECT t1.* FROM student_assigned_quizes as t1 WHERE t1.sid='%s'",$sid);
	$query = "SELECT * FROM `quiz_student_extented_info` WHERE `student_id` = '$sid'";
    $result = mysql_query($query);
    while($assigned_quiz  = mysql_fetch_object($result)){
       $assigned_quizzes[] = $assigned_quiz;
    }
    //d($query,1);
    //echo "<pre>"; print_r($assigned_quizzes); die();
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
    <link href="//datatables.net/download/build/nightly/jquery.dataTables.css" rel="stylesheet" type="text/css" />
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    <link rel="shortcut icon" href="./img/favicon.jpg"/>
     <style>
       select{
        height:30px;
       }
    </style>

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
                    <?php
                       if(is_array($assigned_quizzes) && !empty($assigned_quizzes)){
                          ?>
                            <div class="alert alert-success">
                              <strong>Well done!</strong> You have taken the following quizzes .
                            </div>
                            <table id="student_quizzes" class="table table-hover table-responsive">
                              <thead>
                                <tr>
                                  <th>#</th>
                                  <th>Class Name</th>
                                  <th>Class Details</th>
                                  <th>Grade Level</th>
                                  <th>Class Code</th>
                                  <th>Quiz Name</th>
                                  <th>Quiz Skills</th>
                                  <th>Quiz Points</th>
                                  <th>Total Questions</th>
                                  <th>Quiz date</th>
                                  <th>Teacher Name</th>
                                  <th>Teacher Email</th>
                                </tr>
                              </thead>
                              <tbody>
                               <?php $i=1;
                               foreach ($assigned_quizzes as $assigned_quiz) {
                               ?>
                                <tr class="<?php echo $assigned_quiz->status;?>">
                                  <td><?php echo $i++;?></td>
                                  <td><?php echo $assigned_quiz->class_name;?></td>
                                  <td><?php echo $assigned_quiz->class_details;?></td>
                                  <td><?php echo $assigned_quiz->class_grade_level;?></td>
                                  <td><?php echo $assigned_quiz->class_code;?></td>
                                  <td><?php echo $assigned_quiz->quiz_subject;?></td>
                                  <td><?php echo $assigned_quiz->quiz_skills;?></td>
                                  <td><?php echo $assigned_quiz->points;?></td>
                                  <td><?php echo $assigned_quiz->no_of_question;?></td>
                                  <td><?php echo $assigned_quiz->quiz_holding_date_time;?></td>
                                  <td><?php echo $assigned_quiz->teacher_fname;?> <?php echo $assigned_quiz->teacher_lname;?></td>
                                  <td><?php echo $assigned_quiz->teacher_email;?></td>
                                </tr>
                                <?php } ?>
                              </tbody>
                            </table>
                          <?php
                       }else{
                          ?>
                          <div class="alert alert-warning">
                            <strong>Warning!</strong> You are not enrolled any couses. Please contact to your teachers.
                          </div>
                          <?php
                       }
                    ?>
                    

                    
                </div>
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
    <script src="//datatables.net/download/build/nightly/jquery.dataTables.js"></script>
    <script src="js/theme.js"></script>

    <script type="text/javascript">
        $(function () {

            // jQuery Knobs
            $(".knob").knob();
            $('#student_quizzes').DataTable();

            
        });
    </script>
</body>
</html>