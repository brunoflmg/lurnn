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
            $class_skills[$class->class_id] = explode(",",$class->skills_areas);
        }
    }

    if(isset($_POST['create_quiz'])){
        
        $quiz_subject   = mysql_real_escape_string(trim($_POST['quiz_subject']));
        $quiz_details   = mysql_real_escape_string(trim($_POST['quiz_details']));
        //$quiz_grade     = mysql_real_escape_string(trim($_POST['quiz_grade']));
        $class_id       = mysql_real_escape_string(trim($_POST['class_id']));
        $skills         = mysql_real_escape_string(trim($_POST['skills']));

        $points         = mysql_real_escape_string($_POST["points"]);
        $no_of_question = mysql_real_escape_string($_POST["no_of_question"]);
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
            $points_of_each_question = $points/$no_of_question;
            $query = sprintf("INSERT INTO  quizes (
                    quiz_subject,
                    quiz_details,
                    class_id,
                    creator_tid,
                    skills,
                    points,
                    no_of_question,
                    points_of_each_question,
                    quiz_time,
                    quiz_holding_date_time,
                    quiz_created_date
                ) VALUES('%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s')",
                $quiz_subject,
                $quiz_details,
                $class_id,
                $_SESSION['tid'],
                $skills,
                $points,
                $no_of_question,
                $points_of_each_question,
                $quiz_time,
                $quiz_holding_date_time.":00",
                date("Y-m-d H:i:s")
                );

            if(mysql_query($query)){
                $quiz_id = mysql_insert_id();
                $success_message = "You have successfully created quiz.You are being redirected to set questions & answers <script>setTimeout(function(){window.location='create-quiz-question.php?quiz_id=".$quiz_id."';},3000);</script>";
            }else{
                $error_message['failure'] = "Sorry! internal problem, please try again later";
            }
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
                    <h3 style="margin-bottom:20px;">Create Self Assessment for Your Class</h3>
                    <form method="post" action="create-quiz.php">
                        <div class="column well">
                            <div class="field-box">
                                <label>Choose Your Class:</label>
                                <select name="class_id" id="choose_class" class="select2 input-xxlarge" required="required">
                                  <option selected="selected">Choose Your Class</option>
                                <?php foreach ($created_classes as $created_class) { ?>
                                   <option value="<?php echo $created_class->class_id;?>"><?php echo $created_class->class_name;?></option>
                                      
                               <?php   } ?>
                                       
                                        
                                </select>
                            </div>

                            <div class="field-box text-center"><button class="btn btn-primary" type="button" id="add_new_question">Add New questions</button></div>
                            <div id="self_assessment_questions">
                   
                            </div>  
<input type="submit" name="complete" value="Submit"/>							
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>


    <script src="js/wysihtml5-0.3.0.js"></script>
    <script src="http://code.jquery.com/jquery-latest.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/bootstrap-wysihtml5-0.0.2.js"></script>
    <script src="js/jquery.uniform.min.js"></script>
    <script src="js/select2.min.js"></script>
    <script src="js/theme.js"></script>
    <script type="text/javascript" src="js/bootstrap-datetimepicker.min.js" charset="UTF-8"></script>
    <script type="text/javascript">
       var skills = <?php echo json_encode($class_skills); ?>;
    </script>
    <script type="text/javascript">

        $(function () {

            // select2 plugin for select elements
            $(".select2").select2({
                placeholder: "Select a State"
            });

            $( "#choose_class" ).change(function() {

               var skills_class = skills[this.value];
               if(skills_class.length >0){
                  for(var i=0;i<skills_class.length;i++){

                     $("#self_assessment_questions").append('<div class="field-box"><label>Question '+($("#self_assessment_questions").children("div.field-box").length+1)+':</label><input type="text" class="input-xxlarge" name="question[]" value="How do you rate your skills '+skills_class[i]+' out of 10?"></div>');
                  }
               }
            });
            $("#add_new_question").click(function(){
                   $("#self_assessment_questions").append('<div class="field-box"><label>Question '+($("#self_assessment_questions").children("div.field-box").length+1)+':</label><input type="text" class="input-xxlarge" name="question[]" value="How do you rate your skills SKILL NAME out of 10?"></div>');
            });
                           
        });
    
    </script>
</body>
</html>