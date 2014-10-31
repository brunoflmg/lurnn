<?php
    require_once "config.php";
    checkLogin();

    $query = sprintf("SELECT * FROM classes WHERE creator_tid='%s'",$_SESSION['tid']);
    $result = mysql_query($query);
    if(mysql_num_rows($result)==0){
        $error_message['no_classes_found'] = "You don't have any created class. Please create class at first. You are being redirected to create class with few seconds.<script>setTimeout(function(){window.location='create_class.php';},3000);</script>";
    }else{
        while($class = mysql_fetch_object($result)){
            $created_classes[$class->class_id] = $class; 
        }
    }

    if(isset($_POST['create_quiz'])){
        
        $quiz_subject   = mysql_real_escape_string(trim($_POST['quiz_subject']));
        $quiz_details   = mysql_real_escape_string(trim($_POST['quiz_details']));
        //$quiz_grade     = mysql_real_escape_string(trim($_POST['quiz_grade']));
        $learning_goal  = mysql_real_escape_string(trim($_POST['learning_goal']));


        $class_id       = mysql_real_escape_string(trim($_POST['class_id']));
        $skills         = mysql_real_escape_string(trim($_POST['skills']));

        $points         = mysql_real_escape_string($_POST["points"]);
        $no_of_question = mysql_real_escape_string($_POST["no_of_question"]);
        $points_of_each_question = mysql_real_escape_string($_POST['points_of_each_question']);
        $quiz_time      = mysql_real_escape_string($_POST["quiz_time"]);
        $quiz_holding_date_time = mysql_real_escape_string($_POST["quiz_holding_date_time"]);
        

        if($quiz_subject==""){
            $error_message['quiz_subject'] = "Quiz Name is required";
        }

        if($quiz_details==""){
            $error_message['lname'] = "Quiz Details is required";
        }
        
        if($class_id=="" || !is_numeric($class_id)){
            $error_message['class_id'] = "Class Name is required";
        }

        if($skills==""){
            $error_message['skills'] = "Skills is required";
        }
        if($points=="" || is_int($points)){
            $error_message['points'] = "Total Points should be number";
        }
        if($no_of_question=="" || is_int($no_of_question)){
            $error_message['no_of_question'] = "No of Question should be number";
        } 
        if($quiz_time=="" || is_int($quiz_time)){
            $error_message['quiz_time'] = "Quiz time should be number";
        }
        if($quiz_holding_date_time==""){
            $error_message['quiz_holding_date_time'] = "Quiz Taken Time is required";
        }       
        if(!isset($error_message) && empty($error_message)){
            $query = sprintf("INSERT INTO  quizes (
                    quiz_subject,
                    learning_goal,
                    quiz_details,
                    class_id,
                    creator_tid,
                    points,
                    no_of_question,
                    points_of_each_question,
                    quiz_time,
                    quiz_holding_date_time,
                    quiz_created_date
                ) VALUES('%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s')",
                $quiz_subject,
                $learning_goal,
                $quiz_details,
                $class_id,
                $_SESSION['tid'],
                $points,
                $no_of_question,
                $points_of_each_question,
                $quiz_time,
                $quiz_holding_date_time.":00",
                date("Y-m-d H:i:s")
                );
            if(mysql_query($query)){
                $quiz_id = mysql_insert_id();
                if(isset($_POST['student_id']) && is_array($_POST['student_id']) && !empty($_POST['student_id'])){
                    foreach ($_POST['student_id'] as $student_id) {
                        $sql_values[] = "('".$quiz_id."','".$class_id."','".$_SESSION['tid']."','".$student_id."','".date("Y-m-d H:i:s")."')";
                    }
                    if(isset($sql_values)){
                        $sql = "REPLACE INTO quiz_assigned_students(quiz_id,class_id,creator_tid,student_id,created_date) VALUES".implode(",",$sql_values);
                        mysql_query($sql);
                        //d($sql);
                    }
                }
                $skills_arr = explode(",",$skills);
                if(is_array($skills_arr) && !empty($skills_arr)){
                    foreach($skills_arr as $skill){
                        $skill_values[] = "('".$class_id."','".$quiz_id."','".$_SESSION['tid']."','".mysql_real_escape_string(trim(ucfirst($skill)))."','".date('Y-m-d H:i:s')."')";
                    }
                    $sql = "INSERT INTO skills (class_id,quiz_id,creator_tid,skill,created_date) VALUES".implode(",", $skill_values);
                    mysql_query($sql) or die(mysql_error());
                }

                $success_message = "You have successfully created quiz.You are being redirected to set questions & answers <script>setTimeout(function(){window.location='create-quiz-question.php?quiz_id=".$quiz_id."';},3000);</script>";
            }else{
                $error_message['failure'] = "Sorry! internal problem, please try again later";
            }
        }
        //d($_POST,1);
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
    <link rel="stylesheet" type="text/css" href="css/select2.css">    

    <!-- open sans font -->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800' rel='stylesheet' type='text/css'>

    <!-- lato font -->
    <link href='http://fonts.googleapis.com/css?family=Lato:300,400,700,900,300italic,400italic,700italic,900italic' rel='stylesheet' type='text/css'>

    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    <link href="css/bootstrap-datetimepicker.css" rel="stylesheet" media="screen">
    <link rel="shortcut icon" href="./img/favicon.jpg"> 

</head>
<body>

   <?php include "includes/menu.php"; ?>
   <?php include "includes/left_side_bar.php"; ?>


	<!-- main container -->
    <div class="content">

        

        <div class="container-fluid">

            <!-- upper main stats -->
          <?php include "stats.php";?>
            <!-- end upper main stats -->

            <div id="pad-wrapper" class="form-page">
                <div class="row-fluid form-wrapper">
                    <!-- left column -->
                    <?php
                        if(isset($error_message) && !empty($error_message)){
                            ?>
                            <div class="alert alert-danger">
                            <button data-dismiss="alert" class="close" type="button">×</button>
                            <?php
                            foreach($error_message as $error){
                                echo $error;
                            }
                            ?>
                            </div>
                            <?php
                        }
                        if(isset($success_message) && $success_message!=""){
                            ?>
                            <div class="alert alert-success">
                            <button data-dismiss="alert" class="close" type="button">×</button>
                            <?php   echo $success_message; ?>
                            </div>
                            <?php
                        } 
                    ?>
                    <div class="alert alert-error"  id="class_choose_msg">
						<button data-dismiss="alert" class="close" type="button">×</button>
						<strong>* Choose your class to populate learning goal & students</strong>
                    </div>
                    <h3 style="margin-bottom:20px;">Create Quiz for Your Class</h3>
                    <form method="post" action="create-quiz.php">
                    <div class="span8 column">
                                             
                            <div class="field-box">
                                <label>Quiz Name:</label>
                                <input type="text" name="quiz_subject" placeholder="Quiz Name Here" required="required" class="span12">
                            </div>
                            <div class="field-box row-fluid">
                                <label>Learning Goal:</label>
                                <div class="ui-select span8">
                                    <select name="learning_goal" id="form_learning_goal">
                                      <option selected="">Choose Learning Goal</option>                                       
                                    </select>
                                </div>

                            </div>
                            <div class="field-box" id="skills">
                                    <label>Skills:</label>
                                    <input type="hidden" id="form_skills" name="skills" placeholder="Add Comma Separated Values for Skills" required="required" style="width: 100%;"/>
                            </div> 
                            <div class="field-box">
                                <label> Description:</label>
                                <textarea rows="2" class="span12" placeholder="Describe your Quiz here" required="required" name="quiz_details"></textarea>
                            </div>
                            <div class="field-box" id="students">
                                <label>Assigned Students to This Quiz:</label>
                                <select name="student_id[]" id="form_students_ids" multiple class="select2" style="width:100%">
                                </select>
                            </div>
                            <div class="field-box">
                                <div class="span6 offset6" style="margin-top:20px;">
                                    <input type="submit" name="create_quiz" value="Create Quiz" class="btn-flat"/>
                                </div>
                            </div>  

                    </div>
                    <div class="span4 column pull-right">
                           
                           <div class="field-box">
                                <label>Choose Your Class:</label>
                                <div class="ui-select" style="min-width:225px;">
                                    <select name="class_id" id="form_class_id" required="required" onselect="return hideMsg();">
                                      <option selected="">Choose Your Class</option>
                                      <?php foreach ($created_classes as $created_class) { ?>
                                       <option value="<?php echo $created_class->class_id;?>"><?php echo $created_class->class_name;?> (<?php echo $created_class->class_code;?>) </option>
                                          
                                    <?php   } ?>
                                           
                                    </select>
                                </div>
                            </div>
                            <div class="field-box">
                                <label>Total Points for This Quiz:</label>
                                <input type="text" name="points" id="total_points" placeholder="Total Points for this question" required="required" class="span8">
                            </div>
                            <div class="field-box">
                                <label>Total Number of Questions:</label>
                                <input type="text" name="no_of_question" id="no_of_question" placeholder="Total number of questions" required="required" class="span8">
                            </div>
                            <div class="field-box">
                                <label>Points for each question:</label>
                                <div class="ui-select" style="min-width:225px;">
                                    <select name="points_of_each_question">
                                      <option value="equal">Equally Distributed</option>
                                      <option value="custom">Customized Points</option>
                                    </select>
                                </div>
                            </div>
                            <div class="field-box">
                                <label>Quiz Time(in minute):</label>
                                <input type="text" name="quiz_time" placeholder="Quiz Taken Time in munte, ex. 20" required="required" class="span8">
                            </div>
                            <div class="field-box">
                                <label>Quiz Take date & Time:</label>
                                <div class="input-append date form_datetime">
                                    <span class="add-on"><i class="icon-th"></i></span>
                                    <input size="16" type="text" name="quiz_holding_date_time" class="span12" required="required" value="" readonly>
                                    
                                </div>
                            </div>
                            
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php include "includes/ajax-loader.php";?>

	<script src="js/wysihtml5-0.3.0.js"></script>
    <script src="http://code.jquery.com/jquery-latest.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/bootstrap-wysihtml5-0.0.2.js"></script>
    <script src="js/jquery.uniform.min.js"></script>
    <script src="js/select2.min.js"></script>
    <script src="js/theme.js"></script>
    <script type="text/javascript" src="js/bootstrap-datetimepicker.min.js" charset="UTF-8"></script>
    <script type="text/javascript">

        $(function () {
            
             // select2 plugin for select elements
            $(".select2").select2({
                placeholder: "Choose Students"
            });
            $("#form_skills").select2({
                placeholder: "Type Skill",
                tags:[],
                tokenSeparators: [",", " "]
            });
           
            $( "#total_points" ).change(function() {
                if($.isNumeric(parseInt($("#total_points").val(),10)) && $.isNumeric(parseInt($("#no_of_question").val(),10))){
                    $("#points_of_each_question").val($("#total_points").val()/$("#no_of_question").val());
                }
            });

            $( "#no_of_question" ).change(function() {
                if($.isNumeric(parseInt($("#total_points").val(),10)) && $.isNumeric(parseInt($("#no_of_question").val(),10))){
                    $("#points_of_each_question").val($("#total_points").val()/$("#no_of_question").val());
                }
            });

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

            $("#form_class_id").change(function(){
				$('#class_choose_msg').hide();
                $('#ajax-modal').modal('show');
                $.ajax({
                    type: "POST",
                    cache: false,
                    url: "teacher-ajax.php",
                    data: { action: "get_class_learning_goal", class_id: $(this).val()}
                })
                .done(function( msg ) {

                   $("#form_learning_goal").html(msg);
                });

                $.ajax({
                    type: "POST",
                    cache: false,
                    url: "teacher-ajax.php",
                    data: { action: "get_class_assigned_students", class_id: $(this).val()}
                })
                .done(function( msg ) {
                    $('#ajax-modal').modal('hide');
                    $("#students").html('<label>Assigned Students to This Quiz:</label><select name="student_id[]" id="form_students_ids" multiple class="select2" style="width:100%">'+msg+'</select>');
                    try{
                         $(".select2").select2({
                            placeholder: "Choose Students"
                         });
                    }catch(err){
                      
                    }

                });

            });        
        });
    
    </script>
</body>
</html>