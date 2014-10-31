<?php
    require_once "config.php";
    checkStudentLogin(FALSE);
    $sid           = $_SESSION['sid'];
    
    $query  = sprintf("SELECT quiz_id,class_name,class_code,quiz_subject,quiz_skills,teacher_fname,teacher_lname,quiz_assined_last_updated FROM quiz_student_extented_info  WHERE sid='%s' AND quiz_assined_status='untaken' AND quiz_status='active'",$sid);
    //d($query);
    $result = mysql_query($query); 
    while($quiz = mysql_fetch_object($result)){
      $quizes[$quiz->quiz_id] = $quiz;
    }
    mysql_free_result($result);
    //d($quizes,1);
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

</head>
<body>

    <?php include "includes/student_menu.php"; ?>
    <?php include "includes/student_left_side_bar.php"; ?>



	<!-- main container -->
    <div class="content">

        
        <div class="container-fluid">


            <div id="pad-wrapper">

                <!-- statistics chart built with jQuery Flot -->

                <div class="row-fluid">
                    <?php if(isset($success_message)){
                         ?>
                          <div class="alert alert-success">
                              <strong><?php echo $success_message;?></strong>
                            </div>
                         <?php
                      } ?>
                      <?php if(isset($error_message)){
                         ?>
                          <div class="alert alert-danger">
                              <strong><?php echo $error_message;?></strong>
                            </div>
                         <?php
                      } ?>
                     <?php
                       if(is_array($quizes) && !empty($quizes)){
                          ?>
                            <div class="alert alert-info">
                              <strong>Well done!</strong> You have pending the following quizes to be taken.
                            </div>

                            <table class="table table-hover table-responsive">
                              <thead>
                                <tr>
                                  <th>#</th>
                                  <th>Class Name</th>
                                  <th>Class Code</th>
                                  <th>Quiz Name</th>
                                  <th>Quiz Skills</th>
                                  <th>Teacher Name</th>
                                  <th>Created date</th>
                                  <th>Action</th>
                                </tr>
                              </thead>
                              <tbody>
                               <?php $i=1;
                               foreach ($quizes as $quiz) {
                               ?>
                                <tr class="<?php echo $quiz->status;?>">
                                  <td><?php echo $i++;?></td>
                                  <td><?php echo $quiz->class_name;?></td>
                                  <td><?php echo $quiz->class_code;?></td>
                                  <td><?php echo $quiz->quiz_subject;?></td>
                                  <td><?php echo $quiz->quiz_skills;?></td>
                                  <td><?php echo $quiz->teacher_fname;?> <?php echo $quiz->teacher_lname;?></td>
                                  <td><?php echo $quiz->quiz_assined_last_updated;?></td>
                                  <td><a class="btn btn-primary" href="self_assessment.php?id=<?php echo $quiz->quiz_id;?>">Start</a></td>
                                </tr>
                                <?php } ?>
                              </tbody>
                            </table>
                          <?php
                       }else{
                          ?>
                          <div class="alert alert-warning">
                            <strong>Warning!</strong> You have no pending quizes to take. Please contact to your teachers.
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

    <script src="js/theme.js"></script>

    <script type="text/javascript">
        $(function () {

            // jQuery Knobs
            $(".knob").knob();


            
        });
    </script>
</body>
</html>