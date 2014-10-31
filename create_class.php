<?php
    require_once "config.php";
    checkLogin();
    if(isset($_POST['create_class'])){
        //d($_POST,1);
        $class_name     = mysql_real_escape_string(trim($_POST['class_name']));
        $class_details  = mysql_real_escape_string(trim($_POST['class_details']));
        $skills_areas   = mysql_real_escape_string(trim($_POST['skills_areas']));
        
        $grade_level    = mysql_real_escape_string(trim($_POST['grade_level']));
        $meeting_hours  = mysql_real_escape_string(trim($_POST['meeting_hours']));
        $class_code     = mysql_real_escape_string(trim($_POST['class_code']));
        $no_of_students = mysql_real_escape_string(trim($_POST['no_of_students']));
        
        if($class_name==""){
            $error_message['class_name'] = "Class Name is required";
        }
        if($class_details==""){
            $error_message['class_details'] = "Class Details is required";
        }
        if($skills_areas==""){
            $error_message['skills_areas'] = "Skills are required";
        }
        if($grade_level==""){
            $error_message['grade_level'] = "Grade Level is required";
        }
        
        if($no_of_students!=""){
            if(!is_numeric($no_of_students) && $no_of_students ==0){
                $error_message['no_of_students'] = "Number of Student must be greater than zero";
            }
        }
        
        if(!isset($error_message) && empty($error_message)){
            $query = sprintf("INSERT INTO classes (class_name,class_details,grade_level,meeting_hours,class_code,no_of_students,creator_tid,created_date) VALUES('%s','%s','%s','%s','%s','%s','%s','%s')",
                $class_name,
                $class_details,
                $grade_level,
                $meeting_hours,
                $class_code,
                $no_of_students,
                $_SESSION['tid'],
                date("Y-m-d H:i:s")
                );
            //d($query,1);
            if(mysql_query($query)){
                $class_id = mysql_insert_id();
                if(isset($_POST['learning_goal']) && is_array($_POST['learning_goal']) && !empty($_POST['learning_goal'])){
                    foreach($_POST['learning_goal'] as $learning_goal){
                        $goals[] = "('".$class_id."','".$_SESSION['tid']."','".mysql_real_escape_string(trim($learning_goal))."','".date('Y-m-d H:i:s')."')";
                    }
                    $sql = "INSERT INTO learning_goals (class_id,creator_tid,learning_goal,created_date) VALUES".implode(",", $goals);
                    mysql_query($sql) or die(mysql_error());
                }

                $skills = explode(",",$skills_areas);
                if(is_array($skills) && !empty($skills)){
                    foreach($skills as $skill){
                        $skill_values[] = "('".$class_id."','".$_SESSION['tid']."','".mysql_real_escape_string(trim(ucfirst($skill)))."','".date('Y-m-d H:i:s')."')";
                    }
                    $sql = "INSERT INTO skills (class_id,creator_tid,skill,created_date) VALUES".implode(",", $skill_values);
                    mysql_query($sql) or die(mysql_error());
                }

                $success_message = "You have successfully created your class";
                header("Location:create-apptitude-quiz.php?class_id={$class_id}");
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

    <!-- open sans font -->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800' rel='stylesheet' type='text/css'>

    <!-- lato font -->
    <link href='http://fonts.googleapis.com/css?family=Lato:300,400,700,900,300italic,400italic,700italic,900italic' rel='stylesheet' type='text/css'>

    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    <link rel="stylesheet" type="text/css" href="css/select2.css"/>
    <link rel="stylesheet" type="text/css" href="css/bootstrap-timepicker.min.css" />
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
                    <!--
                    <h4 style="margin-bottom:20px;">
                    <div class="btn-group">
                        <button class="btn glow">Create</button>
                        <button class="btn glow dropdown-toggle" data-toggle="dropdown">
                            <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a href="#">Subject</a></li>
                            <li><a href="create_class.php">Class</a></li>
                            <li><a href="#">Student</a></li>
                            <li><a href="#">Assessment</a></li>
                        </ul>
                    </div>
                    </h4>
                    -->
                    <h3 style="margin-bottom:20px;">Create Your Class</h3>
                    <form method="post" action="create_class.php">
                    <div class="span8 column">
                                             
                            <div class="field-box">
                                <label>Class Name:</label>
                                <input type="text" name="class_name" placeholder="Class Name" required="required" class="span12">
                            </div>
                            <div class="field-box">
                                <label>Learning Goal:</label>

                                <div id="learning_goal_rows">
                                    <div class="learning_goal">
                                        <span class="badge badge-info">1</span> 
                                        <textarea rows="1" class="span10 animated" style="height:25px;" placeholder="Add Your Class Learning Goal" required="required" name="learning_goal[]"></textarea> 
                                        <span class="remove_goal badge badge-important" style="cursor:pointer;">X</span>
                                    </div>
                                </div>
                                <div class="text-right"><span id="add_new_goal" class="btn btn-default">+ Add New Goal</span></div>
                            </div>
                                <div class="field-box">
                                <label>Class Details:</label>
                                <textarea rows="1" class="span12 animated" placeholder="Describe your class here" required="required" name="class_details"></textarea>
                            </div>

                            <div class="field-box">
                                <label>Skills:</label>
                                <input type="hidden" style="width: 100%;" name="skills_areas" id="form_skills_areas" required>
                            </div>
                            <div class="field-box">
                                <div class="span6 offset6" style="margin-top:40px;">
                                    <input type="submit" name="create_class" value="Create Class" class="btn-flat"/>
                                </div>
                            </div>     
                    </div>
                    <div class="span4 column pull-right">
                           <div class="field-box">
                                <label>Grade Level:</label>
                                <div class="ui-select">
                                    <select name="grade_level" required="required">
                                        <option value="6th Grade">6th Grade</option>
                                        <option value="7th Grade">7th Grade</option>
                                        <option value="8th Grade">8th Grade</option>
                                        <option value="9th Grade">9th Grade</option>
                                        <option value="10th Grade">10th Grade</option>
                                        <option value="11th Grade">11th Grade</option>
                                        <option value="12th Grade">12th Grade</option>
                                    </select>
                                </div>
                            </div>
                            <div class="field-box">
                                    <label>Class Start Time (Optional):</label>
                                    <div class="input-append bootstrap-timepicker">
                                        <input id="timepicker1" type="text" class="input-small" name="meeting_hours" required="required">
                                        <span class="add-on"><i class="icon-time"></i></span>
                                    </div>
                            </div>
                            
                            <div class="field-box">
                                    <label>Class Code (Optional):</label>
                                    <input type="text" name="class_code" placeholder="Class Code">
                            </div>
                            <div class="field-box">
                                <label>Number of Students (Optional):</label>
                                <input type="number" name="no_of_students" placeholder="Number of Students">
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
    
    <script src="js/jquery.flot.js"></script>
    <script src="js/jquery.flot.stack.js"></script>
    <script src="js/jquery.flot.resize.js"></script>
    <script src="js/theme.js"></script>
    <script src="js/select2.min.js"></script>
    <script type="text/javascript" src="js/bootstrap-timepicker.min.js"></script>
    <script src='js/jquery.autosize.min.js'></script>
    <script type="text/javascript">

        $(function () {
            $("#form_skills_areas").select2({
                placeholder: "Type Skill",
                tags:[],
                tokenSeparators: [",", " "]});
            
            $('#timepicker1').timepicker();
            $("#add_new_goal").click(function(){
                var total_goal = $("#learning_goal_rows").children().length;
                $("#learning_goal_rows").append('<div class="learning_goal"><span class="badge badge-info">'+(total_goal+1)+'</span> <textarea rows="1" class="span10 animated" style="height:25px;" placeholder="Add Your Class Learning Goal" required="required" name="learning_goal[]"></textarea> <span class="remove_goal badge badge-important" style="cursor:pointer;">X</span></div>');
                $(".remove_goal").click(function(){
                    //console.log(this);
                    $(this).parent().remove();
                    var counter = $(".badge-info");
                    $.each(counter,function(index,value){
                       //console.log("Index : "+index+" Value :"+value);
                       $(value).html(index+1);
                    });
                });
                $('.animated').autosize({append: "\n"});
            });
            $(".remove_goal").click(function(){
                    $(this).parent().remove();
                    var counter = $(".badge-info");
                    $.each(counter,function(index,value){
                       //console.log("Index : "+index+" Value :"+value);
                       $(value).html(index+1);
                    });
            });
            $('.animated').autosize({append: "\n"});
        });
    
    </script>
    
</body>
</html>