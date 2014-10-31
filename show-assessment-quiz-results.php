<?php
    require_once "config.php";
    checkStudentLogin(FALSE);
    $sid           = $_SESSION['sid'];
    $quiz_id = filter_input(INPUT_GET,"id",FILTER_VALIDATE_INT);
    if(!is_int($quiz_id)){
      header("Location:studentboard.php");exit;
    }

    $query  = sprintf("SELECT t1.*,t2.* FROM `skills` as t1  LEFT JOIN self_assesment as t2 ON t1.skill_id =t2.skill_id WHERE t1.quiz_id='%d' AND t2.student_id IN (SELECT t3.student_id FROM `student_whole_info` as t3 WHERE t3.student_id='%s' )",$quiz_id,$sid);

    $result = mysql_query($query); 
    while($assessment = mysql_fetch_object($result)){
      //d($assessment);
      $assessments[$assessment->skill_id] = $assessment;
    }

    $query  = sprintf("SELECT qa.*,qs.* FROM quiz_answers as qa,question_skills qs  WHERE qa.quiz_id='%d' AND qa.student_id='%d' and qa.question_id=qs.question_id",$quiz_id,$sid);
    $result = mysql_query($query); 
    while($actual_rating = mysql_fetch_object($result)){
      //d($assessment);
      $actual_ratings[$actual_rating->skill_id][] = $actual_rating;
    }

    $skill_rating = array();
    foreach ($actual_ratings as $key => $value) {
      $rating =0;
      foreach ($value as $item) {
        if ($item->correct) {
          $rating=$rating+1;
        }
      }
      if (isset($assessments[$key])) {
        $assessments[$key]->actual_rating = ($rating/count($value))*10;
      }
      
    }
    
    $query  = sprintf("SELECT t1.* FROM quiz_extended_info as t1 WHERE t1.quiz_id='%d'",$quiz_id);
    //d($query);
    $result = mysql_query($query); 
    $quiz   = mysql_fetch_object($result);
    //d($quiz,1);

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
                    
                     
                            <div class="alert alert-info">
                              <strong>Well done! You have completed the quiz.</strong>
                            </div>
                           <div class="row-fluid">
                           <div class="offset3 span6">
                              <table class="table">
                                  <tr>
                                    <th>Class Name:</th>
                                    <td><?php echo $quiz->class_name;?></td>
                                  </tr>
                                  <tr>
                                    <th>Class Code:</th>
                                    <td><?php echo $quiz->class_code;?></td>
                                  </tr>
                                  <tr>
                                    <th>Quiz Name:</th>
                                    <td><?php echo $quiz->quiz_subject;?></td>
                                  </tr>
                                 
                                  
                              </table>
                            </div>
                            <div class="span3">
                            </div>
                            </div>
                            <div class="row-fluid">
                            <?php
                       if(is_array($assessments) && !empty($assessments)){
                          ?>
                            <table class="table">
              <thead>
                <tr>
                  <th>Skills</th>
                  <th>Self Rating</th>
                  <th>Actual Rating</th>
                  <th>Comments</th>
                </tr>
              </thead>
              <tbody>
				<?php

				foreach ($assessments as $assessment) {

				?>
                                
                <tr>

                  <td>
                  <?php echo $assessment->skill;?></td>
                  <td><?php $total = ceil($assessment->self_rating);?>
                    <?php for($i=0;$i<10;$i++){ ?>
                     <?php if($i<$total){ ?>
                      <i class='icon icon-star icon-2x'></i>
                     <?php }else{
                      ?>
                      <i class='icon icon-star-empty icon-2x'></i>
                      <?php
                      } ?>
                    <?php } ?>
                      
                  </td>
                  <td><?php $total = ceil($assessment->actual_rating);?>
                    <?php for($i=0;$i<10;$i++){ ?>
                     <?php if($i<$total){ ?>
                      <i class='icon icon-star icon-2x'></i>
                     <?php }else{
                      ?>
                      <i class='icon icon-star-empty icon-2x'></i>
                      <?php
                      } ?>
                    <?php } ?>
                  </td>
                  <td>
                    <?php if($assessment->actual_rating >=8){
                         echo "<strong class='text-success'>Very Good</p>";
                      }elseif($assessment->actual_rating < 8 && $assessment->actual_rating >=6){
                         echo "<strong class='text-success'>Good</p>";
                      }elseif($assessment->actual_rating < 6 && $assessment->actual_rating >=5){
                         echo "<strong class='text-warning'>Bad</p>";
                      }else{
                         echo "<strong class='text-danger'>Very Bad</p>";
                      }
                      ?>
                  </td>
                </tr>
             
              
            <?php } ?>
            </tbody>
            </table>
            <?php } ?>
                          </div>
            <a title="CONTINUE TO CLASS DASHBOARD" href="class_dashboard.php?Qid=<?php echo $quiz->class_id;?>" class="btn btn-small btn-primary pull-right">CONTINUE TO CLASS DASHBOARD</a>                           
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
    <script src="js/bootstrap-rating-input.min.js"></script>
    <script type="text/javascript">
        $(function () {

            // jQuery Knobs

            $(".knob").knob();

        });
    </script>
</body>
</html>