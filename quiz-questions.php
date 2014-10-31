<?php
    require_once "config.php";
    checkLogin();
    
    $tid    = $_SESSION['tid'];
    $sql    = sprintf("SELECT t1.* FROM classes as t1 WHERE t1.creator_tid='%s'",$tid);
    $result = mysql_query($sql);
    while($class = mysql_fetch_object($result)){
        $classes[$class->class_id] = $class;
    }
    $sql    = sprintf("SELECT t1.* FROM quizes as t1 WHERE t1.creator_tid='%d'",$tid) ;
    //d($classes,1);
    //$data_source_class = json_encode($classes_data);

    $result = mysql_query($sql);
    while($class_quiz = mysql_fetch_object($result)){
        $class_quizzes[$class_quiz->quiz_id] = $class_quiz;
        $data_quizzes[$class_quiz->quiz_id]  = $class_quiz->quiz_subject;
        $skills       = explode(",",$class_quiz->skills);
        if(is_array($skills) && !empty($skills)){
          foreach ($skills as $skill) {
            $skill_data[$skill] = $skill;
          }
        }
        $quizzes_skill_data[$class_quiz->quiz_id] = $skill_data;
    }
    $data_source_quiz =  json_encode($data_quizzes);
    $sql    = sprintf("SELECT t1.* FROM quiz_questions as t1 WHERE t1.creator_tid='%d' ORDER BY quiz_id DESC",$tid) ;
    //d($classes,1);
    $result = mysql_query($sql);
    while($quiz_question = mysql_fetch_object($result)){
        $quiz_questions[$quiz_question->question_id] = $quiz_question;
    }
    
    //d($data_source,1);
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
    <link href="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap-editable/css/bootstrap-editable.css" rel="stylesheet"/>
    <link href="css/bootstrap-datetimepicker.css" rel="stylesheet" media="screen">
    <style>
       select{
        height:30px;
       }
    </style>
</head>
<body>

   

   
    <?php include "includes/menu.php"; ?>
    <?php include "includes/left_side_bar.php"; ?>



	<!-- main container -->
    <div class="content">

        
        <div class="container-fluid">

            <!-- upper main stats -->
            <?php //include "stats.php";?>
            <!-- end upper main stats -->

            <div id="pad-wrapper">

                <!-- statistics chart built with jQuery Flot -->
                <div class="row-fluid chart">
                   <table id="class_lists" class="display" width="100%">
        <thead>
          <tr>
            <th>Question</th>
            <th>Option 1</th>
            <th>Option 2</th>
            <th>Option 3</th>
            <th>Option 4</th>
            <th>Right Answer</th>
            <th>Skills</th>
            <th>Quiz</th>
            <th>Class</th>
            <th>Last Updated</th>
            <th>Action</th>
          </tr>
        </thead>

        <tfoot>
         <tr>
            <th>Question</th>
            <th>Option 1</th>
            <th>Option 2</th>
            <th>Option 3</th>
            <th>Option 4</th>
            <th>Right Answer</th>
            <th>Skills</th>
            <th>Quiz</th>
            <th>Class</th>
            <th>Last Updated</th>
            <th>Action</th>
          </tr>
        </tfoot>

        <tbody>
        <?php if(is_array($quiz_questions)&&!empty($quiz_questions)){ 
           foreach($quiz_questions as $quiz_question){
          ?>
          <tr id="quiz_question_<?php echo $quiz_question->question_id;?>">
           <td><a href="#" class="editable" data-name="question_details" data-type="textarea" data-pk="<?php echo $quiz_question->question_id;?>" data-url="/teacher-ajax.php?action=edit_question" data-title="Question details"><?php echo $quiz_question->question_details;?></a></td>
            <td>
              <a href="#" class="editable" data-name="option_1" data-type="textarea" data-pk="<?php echo $quiz_question->question_id;?>" data-url="/teacher-ajax.php?action=edit_question" data-title="Option 1"><?php echo $quiz_question->option_1;?></a>
            </td>
             <td>
              <a href="#" class="editable" data-name="option_2" data-type="textarea" data-pk="<?php echo $quiz_question->question_id;?>" data-url="/teacher-ajax.php?action=edit_question" data-title="Option 2"><?php echo $quiz_question->option_2;?></a>
            </td>
             <td>
              <a href="#" class="editable" data-name="option_3" data-type="textarea" data-pk="<?php echo $quiz_question->question_id;?>" data-url="/teacher-ajax.php?action=edit_question" data-title="Option 3"><?php echo $quiz_question->option_3;?></a>
            </td>
             <td>
              <a href="#" class="editable" data-name="option_4" data-type="textarea" data-pk="<?php echo $quiz_question->question_id;?>" data-url="/teacher-ajax.php?action=edit_question" data-title="Option 4"><?php echo $quiz_question->option_4;?></a>
            </td>
            <td>
              <a href="#" class="editable" data-name="right_answer" data-value="<?php echo $quiz_question->right_answer;?>" data-type="select" data-source='{"option_1": "Option 1", "option_2": "Option 2","option_3":"Option 3","option_4":"Option 4" }' data-pk="<?php echo $quiz_question->question_id;?>" data-url="/teacher-ajax.php?action=edit_question" data-title="Choose Right Answer"><?php echo ucfirst(str_replace("_", " ", $quiz_question->right_answer));?></a>
            </td>
            <td>
            
            <a href="#" class="editable" data-name="skills" data-type="select" data-pk="<?php echo $quiz_question->question_id;?>" data-url="/teacher-ajax.php?action=edit_question" data-source='<?php echo json_encode($quizzes_skill_data[$quiz_question->quiz_id]);?>' data-value="<?php echo $quiz_question->skills;?>" data-title="Choose Skill"><?php echo $quiz_question->skills;?></a>
            </td>
            <td>
            
            <a href="#" class="editable" data-name="class_id" data-type="select" data-pk="<?php echo $quiz_question->question_id;?>" data-url="/teacher-ajax.php?action=edit_question" data-source='<?php echo $data_source_quiz;?>' <?php if(isset($class_quizzes[$quiz_question->quiz_id])){ ?> data-value="<?php echo $quiz_question->quiz_id;?>" <?php } ?> data-title="Choose Class to assign"><?php if(isset($class_quizzes[$quiz_question->quiz_id])){ ?><?php echo $class_quizzes[$quiz_question->quiz_id]->quiz_subject;?> <?php } ?></a>
            </td> 
            <td>
            
            <?php echo $classes[$class_quizzes[$quiz_question->quiz_id]->class_id]->class_name;?>
            </td> 
           
            
           
           
            <td><?php echo $quiz_question->created_date;?></td>
            <td><a href="#" class="btn btn-danger" onclick="deletequiz('<?php echo $quiz_question->question_id;?>')">Delete</a></td>
          </tr>
          <?php }
          } ?>
          </tbody>
          </table>
    </div>
            </div>
        </div>
    </div>


	<!-- scripts -->
    <script src="http://code.jquery.com/jquery-latest.js"></script>
    <script src="//datatables.net/download/build/nightly/jquery.dataTables.js"></script>

    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery-ui-1.10.2.custom.min.js"></script>
    <!-- knob -->
    <script src="js/jquery.knob.js"></script>

    <script src="js/theme.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap-editable/js/bootstrap-editable.min.js"></script>
    <script src="js/bootbox.min.js"></script>
    <script type="text/javascript" src="js/bootstrap-datetimepicker.min.js" charset="UTF-8"></script>
    <script type="text/javascript">
       function deletequiz(question_id){
          bootbox.confirm("Are you sure to delete this Question?", function(result) {
                if(result==false) return;
                $.ajax({
                  type: "POST",
                  url: "teacher-ajax.php",
                  data: { action: "delete_question", question_id: question_id }
                })
                .done(function( msg ) {
                   if(msg=="yes"){
                      bootbox.alert("<h3 class='text-success'>You have successfully deleted this question.</h3>");
                      $("#quiz_question_"+question_id).remove();
                   }else{
                      bootbox.alert("<h3 class='text-danger'>Sorry!! we could not delete this question.Please try again later.</h3>");
                   }
                });
          }); 
       }
    </script>
    <script type="text/javascript">
        $(function () {

            // jQuery Knobs
            $(".knob").knob();
            $('#class_lists').DataTable();
            $.fn.editable.defaults.mode = 'popup';
            //$.fn.editable.defaults.placement = 'right';
            $('.editable').editable({
              validate: function(value) {
                if($.trim(value) == '') {
                  return 'This field is required';
                }
              },
              success: function(response, newValue) {
                //console.log(response);
                //console.log(response.status);
                //console.log(response.msg);
                if(response.status == 'error') return response.msg; //msg will be shown in editable form
              }
            });
        });
    </script>
</body>
</html>