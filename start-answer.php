<?php
    require_once "config.php";
    checkStudentLogin(FALSE);
    $sid           = $_SESSION['sid'];
    $quiz_id       = intval($_GET['id']);
    
    $query  = sprintf("SELECT * FROM quiz_student_extented_info  WHERE (sid='%s' or student_id ='{$sid}') AND quiz_assined_status='untaken' AND quiz_status='active' AND quiz_id='%s'",$sid,$quiz_id);

    $result = mysql_query($query); 
    $quiz   = mysql_fetch_object($result);
    
	$quiz_time = $quiz->quiz_time;
    
    if(!$quiz)
	{
      setFlashData('error_message',array('Access denied to take this quiz. Please contact to your teacher :('));
      header("Location:take-quiz.php");exit;
    }
    $student_id_val = ($quiz->student_id==0) ? $quiz->student_id : $sid;
    $query   = sprintf("SELECT t1.* FROM skills as t1 WHERE quiz_id='%d' AND skill_id NOT IN (SELECT skill_id FROM self_assesment WHERE quiz_id='%d' AND student_id='%d')",$quiz_id,$quiz_id,$student_id_val);

    $result  = mysql_query($query);
    if(mysql_num_rows($result)>0){
       header("Location: self_assessment.php?id=".$quiz_id);exit;
    }
    
    $query  = sprintf("SELECT *FROM quiz_questions WHERE quiz_id='%d' ORDER BY RAND() ",$quiz_id);
    $result = mysql_query($query);
    while($question = mysql_fetch_object($result)){
        $questions[$question->question_id] = $question;
        $answered_option[$question_id]  = "";
        $points[$question->question_id] = $question->points;
        $right_answer[$question->question_id] = $question->right_answer;
    }
    //d($questions,1);
    
    if(isset($_POST['submit'])){
      extract($_POST);
      $options = array('option_1','option_2','option_3','option_4');
      //d(answers);
      //d($answered_option,1);
      //d($_POST,1);
      $quiz_id     = $quiz->quiz_id;
      $student_id  = $quiz->student_id;
	  
      $queryExt    = "INSERT INTO quiz_answers (student_id,quiz_id,question_id,answer,points,correct,created_time)
      VALUES";

      foreach( $questions as $question){
          if(isset($answered_option[$question->question_id])){
             $answer   = trim($answered_option[$question->question_id]);
             if(strip_tags($answer)==strip_tags($right_answer[$question->question_id])){
                $correct=TRUE;
                $marks = $points[$question->question_id];
             }else{
                $marks = 0;
                $correct = FALSE;
             }
          }else{
             $answer ='';
             $marks = 0;
             $correct=FALSE;
          }
            
          $values[] = sprintf("('".$student_id."','".$quiz_id."','%s','%s','%s','%s','".date("Y-m-d H:i:s")."')",$question->question_id,$answer,$marks,$correct);
          //d($sql,1);
      }
      if(isset($values)){
          $sql = $queryExt.implode(",", $values);
          if(mysql_query($sql)){
              $sql = "UPDATE quiz_assigned_students SET status='taken' WHERE quiz_id='".$quiz_id."' AND student_id='".$student_id."'";
              mysql_query($sql);
              header("Location: show-assessment-results.php?id=".$quiz_id);exit;
          }else{
              setFlashdata('error_message',array('Sorry! Internal Problem, Please try again later.'));
              header("Location: start-answer.php?id=".$quiz_id);exit;
          }
      }else{
        setFlashdata('error_message',array('Sorry! Internal Problem, Please try again later.'));
        header("Locaion: start-answer.php?id=".$quiz_id);exit;
      }
      
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
	

</head>
<body>

   

   
    <?php include "includes/student_menu.php"; ?>
    <?php include "includes/student_left_side_bar.php"; ?>



	<!-- main container -->
    <div class="content">

        
        <div class="container-fluid">


            <div id="pad-wrapper">

                <!-- statistics chart built with jQuery Flot -->

                <div class="row-fluid" style="float:left; margin-top:35px;">
                    <div id="main" style="margin-bottom:15px">
						<h2 style="float: left; font-size: 16px; font-weight: bold; margin: 0px 11px 0px 0px;">Time Left:</h2>
						<table style="border:0px;">
							<tr>
								<td colspan="4">
									<span id="m_timer"></span>
									<h2 id="minute_text" style="float: right; font-size: 16px; font-weight: bold; margin: 0px 11px 0px 0px;"></h2>
								</td>
							</tr>
						</table>
					</div>
                     <?php
                       if(is_array($questions) && !empty($questions)){
                          ?>
                            <div class="alert alert-info">
                              <strong>Well done! You are ready to take the quiz</strong> Please Choose the best answers from the following the MCQ.
                            </div>
                           <div class="row-fluid">
                           <div class="offset3 span6">
                              <table class="table table-bordered">
                                  <tr>
								  
								  <?php
									//$_SESSION['enroll_class_id'] = $quiz->class_id;
								  ?>
								  
                                    <th>Class Name:</th>
                                    <td><?php echo $quiz->class_name;?></td>
                                  </tr>
                                  <tr>
                                    <th>Class Code:</th>
                                    <td><?php echo $quiz->class_code;?></td>
                                  </tr>
                                  <?/*<tr>
                                    <th>Quiz Name:</th>
                                    <td><?php echo $quiz->quiz_subject;?></td>
                                  </tr>*/?>
                                  <tr>
                                    <th>Skills:</th>
                                    <td><?php echo $quiz->quiz_skills;?></td>
                                  </tr>
                                  <tr>
                                    <th>Teacher:</th>
                                    <td><?php echo $quiz->teacher_fname;?> <?php echo $quiz->teacher_lname;?></td>
                                  </tr>
                              </table>
                            </div>
                            <div class="span3">
                            </div>
                            </div>
                            <div class="row-fluid">
                            <form action="" method="post">
                            <table class="table table-condensed">
                              <tbody>
                                <?php
                                $i=1;
                               foreach ($questions as $question) {
                                 ?>
                                <tr id="count_trs">
                                  <td style="vertical-align:top;width:20px;">
                                     <span class="badge badge-success">Q.<?php echo $i++;?></span>
                                  </td>
                                  <td>
                                    <div class="row-fluid" style="font-size:16px;font-weight:bold;"><?php echo $question->question_details?></div>
                                    <div class="row-fluid">
                                       <?php if($question->question_type=="boolean"){
                                       ?>
                                       <table>
                                         <tr>
                                           <td style="vertical-align:top;">
                                              <input class="disable_ME" type="radio" style="margin-top: -2px;" name="answered_option[<?php echo $question->question_id?>]" value="TRUE">
                                           </td>

                                           <td>
                                           TRUE
                                           </td>
                                          </tr>
                                          <tr>
                                           <td>
                                              <input class="disable_ME" type="radio" style="margin-top: -2px;" name="answered_option[<?php echo $question->question_id?>]" value="FALSE">
                                           </td>
                                           <td>
                                             FALSE
                                           </td>
                                         </tr>
                                       </table>
                                       
                                      <?php
                                      }else if($question->question_type=="mcq"){
                                        $options = json_decode($question->options);
                                      ?>
                                      <table>
                                        
                                      <?php
                                         foreach($options as $option_index=>$option_value){
                                          ?>
                                          <tr>
                                            <td style="vertical-align:top;">
                                              <input class="disable_ME" type="radio" style="margin-top: -2px;" name="answered_option[<?php echo $question->question_id?>]" value="option_<?php echo $option_index;?>">
                                            </td>
                                            <td><?php echo $option_value;?></td>
                                          </tr>
                                          <?php
                                         }
                                         ?>
                                         
                                         </table>
                                         <?php
                                      }else if($question->question_type=="self_answer"){
                                        ?>
                                        <div class="span12"><input class="disable_ME" type="text" style="margin-top: -2px;" name="answered_option[<?php echo $question->question_id?>]" value="" placeholder="write your answer"></div>
                                      <?php } ?>
                                    </div>

                                  </td>                                  
                                </tr>
                                <?php } ?>
                                <tr><td></td><td><h3 class="text-left"><input type="submit" name="submit" class="btn btn-primary" value="Finish"></h3></td></tr>
                              </tbody>
                            </table>
                              
                            </form>
                            </div>
                          <?php
                       }else{
                          ?>
                          <div class="alert alert-warning">
                            <strong>Warning!</strong> You are not able to take the quiz right now. Please contact to your respected teacher.
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
    <link rel="stylesheet" type="text/css" href="js-counter-quiz/jquery.countdownTimer.css" />
	
	<script type="text/javascript" src="js-counter-quiz/jquery-2.0.3.js"></script>

	<script type="text/javascript" src="js-counter-quiz/jquery.countdownTimer.js"></script>

	<!-- flot charts -->
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script src="js/ckeditor_basic/ckeditor/ckeditor.js"></script>
    <script src="js/theme.js"></script>
	


	
    <script type="text/javascript">
        
		$(function () {
            // jQuery Knobs
            $(".knob").knob();
			
		//--For counter--------------
		zz('#m_timer').countdowntimer({
				minutes :<?php echo $quiz_time;?>,
				size : "lg",
				timeUp : timeisUp
			});
		$('#minute_text').html('&nbsp;Minutes');	
		function timeisUp() 
			{
				alert("Time Is Up. Now you can't answer any more. Please Finish the Quiz.");
				$(".disable_ME").attr('disabled', 'disabled');
			}
        });
    </script>
</body>
</html>