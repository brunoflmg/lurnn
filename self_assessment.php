<?php
    require_once "config.php";
    checkStudentLogin(FALSE);
    $sid           = $_SESSION['sid'];
    $quiz_id       = intval($_GET['id']);
    $student_email = $_SESSION['email'];
    
	if(!empty($quiz_id))
	{
      $query  = sprintf("SELECT * FROM quiz_student_extented_info  WHERE sid='%s' AND quiz_assined_status='untaken' AND quiz_status='active' AND quiz_id='%s'",$sid,$quiz_id);
      $result = mysql_query($query); 
      $quiz   = mysql_fetch_object($result);
      //d($quiz,1);

      if(!$quiz)
	  {
        setFlashData('error_message',array('Access denied to take this quiz. Please contact to your teacher :('));
        header("take-quiz.php");exit;
      }

      $query   = sprintf("SELECT t1.* FROM skills as t1 WHERE quiz_id='%d' AND skill_id NOT IN (SELECT skill_id FROM self_assesment WHERE quiz_id='%d' AND student_id='%d')",$quiz_id,$quiz_id,$quiz->student_id);
      $result  = mysql_query($query);
      while($untaken_skill  = mysql_fetch_object($result)){
         $untaken_skills[$untaken_skill->skill_id]  = $untaken_skill;
      }

      if(!is_array($untaken_skills) || empty($untaken_skills)){
         header("Location: start-answer.php?id=".$quiz_id);exit;
      }  
    }

    if (isset($_REQUEST['class_id'])) {
      $query  = sprintf("SELECT * FROM quizes q,classes c  WHERE c.class_id=q.class_id and c.class_id='%s' and is_aptitude=1", $_REQUEST['class_id']);
      $result = mysql_query($query); 
      $quiz   = mysql_fetch_object($result);
 
      $class_id = intval($_GET['class_id']);
      $query   = sprintf("SELECT t1.* FROM skills as t1 WHERE class_id='%d' and quiz_id='{$quiz->quiz_id}' AND skill_id NOT IN (SELECT skill_id FROM self_assesment WHERE class_id='%d' AND student_id='%d')",$class_id,$class_id,$sid);
      
      $result  = mysql_query($query);
      while($untaken_skill  = mysql_fetch_object($result)){
         $untaken_skills[$untaken_skill->skill_id]  = $untaken_skill;
      }      
    }
/*	
//--- check if quiz id regular quiz--- BY MANMOHIT------------------
	$queryrq   = sprintf("SELECT * from quizes WHERE quiz_id = '$quiz_id' ");
    $resultrq  = mysql_query($queryrq);
	$RQ  = mysql_fetch_object($resultrq);
	$chk = $RQ->is_aptitude;
	
	if($chk == "0")
	{
		foreach($untaken_skills as $un)
		{
			$skill_id = $un->skill_id;
			$sql_values[] ="('".$quiz->creator_tid."','".$quiz->class_id."','".$quiz->quiz_id."','".$skill_id."','".$sid."','0','1','".date("Y-m-d H:i:s")."')";
		}
		
		//--old one --$sql = "REPLACE INTO quiz_assigned_students(quiz_id,class_id,creator_tid,student_id,created_date,status) VALUES"."('".$quiz->quiz_id."','".$quiz->class_id."','".$quiz->creator_tid."','".$sid."','".date("Y-m-d H:i:s")."','untaken')";
		$sql = "REPLACE INTO quiz_assigned_students(quiz_id,class_id,creator_tid,student_id,created_date,status) VALUES"."('".$quiz->quiz_id."','".$quiz->class_id."','".$quiz->creator_tid."','".$sid."','".date("Y-m-d H:i:s")."','taken')";
		mysql_query($sql);         
         $query = "INSERT INTO self_assesment(creator_tid,class_id,quiz_id,skill_id,student_id,self_rating,taken_level,created_date) VALUES".implode(",",$sql_values);
		 
		if(mysql_query($query))
		{
              $success_message ="You are being redirected to now to take the regular quiz within few seconds <script> setTimeout(function(){window.location='start-quiz-answer.php?id=".$quiz->quiz_id."';},300);</script>";   
        }else{
              $error_message ="Sorry! You have done something wrong. Please try again later. :( <script> setTimeout(function(){window.location='start-quiz-answer.php?id=".$quiz->quiz_id."';},300);</script>"; 
        }
	}
//---------END HERE--- BY MANMOHIT------------------------------------------------
	*/
    if(isset($_POST['submit'])){
      //d($_POST);

         $self_rating = $_POST['self_rating'];
         if(is_array($self_rating) && !empty($self_rating))
		 {
            foreach($self_rating as $skill_id =>$selfRate)
			{
              $sql_values[] ="('".$quiz->creator_tid."','".$quiz->class_id."','".$quiz->quiz_id."','".$skill_id."','".$sid."','".intval($selfRate)."','1','".date("Y-m-d H:i:s")."')";
            }
         }

        $sql = "REPLACE INTO quiz_assigned_students(quiz_id,class_id,creator_tid,student_id,created_date,status) VALUES"."('".$quiz->quiz_id."','".$quiz->class_id."','".$quiz->creator_tid."','".$sid."','".date("Y-m-d H:i:s")."','untaken')";
		
         mysql_query($sql);         
         $query = "INSERT INTO self_assesment(creator_tid,class_id,quiz_id,skill_id,student_id,self_rating,taken_level,created_date) VALUES".implode(",",$sql_values);

         if(mysql_query($query)){
              $success_message ="You have successfully taken the self assessment on the skills. You are being redirected to now to take the quiz within few seconds <script> setTimeout(function(){window.location='start-answer.php?id=".$quiz->quiz_id."';},1000);</script>";   
         }else{
              $error_message ="Sorry! You have done something wrong. Please try again later. :(";   
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

                      <div class="alert alert-success">
                        <strong>Well done! Please rate yourself for the following skills.</strong>
                      </div>

                      <form role="form" method="post">
                         <div class="row-fluid">
                          <div class="span8 offset2">
                              <strong>Class: <?php
                               echo $quiz->class_name." ( ".$quiz->class_code.")";
                               ?></strong><br/>
                               <strong>Details: <?php echo $quiz->class_details?></strong>
                               <hr/>
                          </div>
                        </div>
                        <div class="row-fluid">
                          
                          <div class="span8 offset2">
                            <?php $i=1;foreach($untaken_skills as $untaken_skill){ ?>
                            
                              <div class="control-group">
                                <label class="control-label"><span class="badge badge-success"><?php echo $i++;?></span> How do you rate your <strong><?php echo $untaken_skill->skill?></strong> skill out of 10?</label>
                                <div class="controls">                                 
                                  <input type="number" required name="self_rating[<?php echo $untaken_skill->skill_id;?>]" data-max="10" data-min="1" value="" style="border:0;" class="rating"/>                                    
                                </div>
                              </div>
                              <hr/>
                            <?php
                             }?>
                             <div class="control-group">
                                <div class="controls">
                                  <button type="submit" name="submit" class="btn btn-primary">Save Changes</button>
                                </div>
                              </div>
                           </div>
                          </div>
                      </form>         
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