<?php
    require_once "config.php";
    checkStudentLogin();
    $sid           = $_SESSION['sid'];
    $quiz_id       = intval($_GET['quiz_id']);
    

    $query  = sprintf("SELECT t1.* FROM students_assigned_classes as t1 WHERE t1.sid='%s'",$sid);
    $result = mysql_query($query); 
    //d($query,1);

    while($class   = mysql_fetch_object($result)){
      $classes[$class->class_id] = $class;
    }

    $query  = sprintf("SELECT t1.quiz_id,t1.quiz_subject,t1.class_id FROM quiz_extended_info AS t1 WHERE t1.quiz_id IN (SELECT t2.quiz_id FROM quiz_student_extented_info as t2 WHERE t2.sid='%s') ",$sid);
    $result = mysql_query($query); 
    //d($query,1);

    while($quiz   = mysql_fetch_object($result)){
       $quizzes[$quiz->quiz_id] = $quiz;
    }

    if(($quiz_id ==0 ) || isset($quizzes[$quiz_id])){
       $class_id = $quizzes[$quiz_id]->class_id;
    }else{
       unset($quizzes);
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

            <div id="pad-wrapper" style="margin-top:40px;">
                <div class="row-fluid text-center">
                  <p class="lead">Student Answer Sheet</p>
                </div>
                <!-- statistics chart built with jQuery Flot -->
                <div class="row-fluid" id="message_display">
                </div>
                <div class="row-fluid">
                    
                   <div class="span3 text-right">
                     <div class="ui-select span12">
                        <select id="all_classess">
                           <option value=''>Choose Class</option>
                           <?php foreach($classes as $class){?>
                              <option value="<?php echo $class->class_id;?>" <?php if($class->class_id==$class_id) echo "selected";?>><?php echo $class->class_name;?> (<?php echo $class->class_code;?>)</option>
                           <?php } ?>
                        </select>
                     </div>
                   </div>
                   <div class="span3 text-left">
                      <div class="ui-select span12">
                        <select id="all_quizzes">
                           <option value=''>Choose Quiz</option>
                           <?php foreach($quizzes as $quiz){?>
                              <option value="<?php echo $quiz->quiz_id;?>" <?php if($quiz->quiz_id==$quiz_id) echo "selected";?>><?php echo $quiz->quiz_subject;?></option>
                           <?php } ?>
                        </select>
                     </div>
                   </div>
                  
                   <div class="span3 text-left">
                      <button type="button" class="btn btn-default" id ="show_answer_sheet"><i class="icon icon-search"></i> Show Answer Sheet</button>
                   </div>
                </div>
                <div class="rwo-fluid">
                      <div  id="answer_script" style="padding-top:20px;">
                      </div>
                </div>
            </div>
        </div>
    </div>
    <?php include "includes/ajax-loader.php"; ?>
	  <!-- scripts -->
    <script src="http://code.jquery.com/jquery-latest.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery-ui-1.10.2.custom.min.js"></script>
    <!-- knob -->
    <script src="js/jquery.knob.js"></script>
    <!-- flot charts -->
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script src="js/ckeditor_basic/ckeditor/ckeditor.js"></script>
    <script src="js/theme.js"></script>

    <script type="text/javascript">
        $(function () {

            // jQuery Knobs
            $(".knob").knob();
            
            $("body").on("change", "#all_classess, #all_quizzes", function(){
                var class_id   = $("#all_classess").val();
                var quiz_id    = $("#all_quizzes").val();
                //console.log(class_id);
                
                switch($(this).attr('id')){
                  case "all_classess":
                          $("#message_display").html('');
                          $("#ajax-modal").modal('show');
                          $.ajax({
                            type: "POST",
                            datatype:"json",
                            url: "student-ajax.php",
                            data: { action: "get_quizzes_by_class", class_id: class_id }
                          })
                          .done(function( msg ) {
                             $("#ajax-modal").modal('hide');
                              $("#all_quizzes").html('');
                             if(msg.data!=false){
                                 $("#all_quizzes").html('<option value="">Choose Quiz</option>');
                                 $.each(msg.data, function(index, value) {
                                     $("#all_quizzes").append('<option value="'+index+'">'+value+'</option>');
                                 });

                             }else{
                                $("#message_display").html('<div class="alert alert-error"><button data-dismiss="alert" class="close" type="button">×</button><strong>'+msg.error+'</strong></div>');
                             }
                             $("#ajax-modal").modal('hide');
                          });
                      break;
                  case "all_quizzes":

                          if(quiz_id==""){
                             $("#message_display").html('<div class="alert alert-error"><button data-dismiss="alert" class="close" type="button">×</button><strong>Oh error!</strong> Please Choose Quiz</div>');
                            break;
                          }
                          $("#message_display").html('')
                          $("#ajax-modal").modal('show');
                          $.ajax({
                            type: "POST",
                            datatype:"json",
                            url: "student-ajax.php",
                            data: { action: "get_student_answer_sheet", quiz_id: quiz_id}
                          })
                          .done(function( msg ) {
                               $("#message_display").html('');
                               $("#answer_script").html(msg);
                               $("#ajax-modal").modal('hide');
                          });


                      break;
                  
                  default:
                          break;
                }

            });
            
            $("#show_answer_sheet").click(function(){
                var class_id   = $("#all_classess").val();
                var quiz_id    = $("#all_quizzes").val();

                if(quiz_id==""){
                   $("#message_display").html('<div class="alert alert-error"><button data-dismiss="alert" class="close" type="button">×</button><strong>Oh error!</strong> Please Choose Quiz</div>');
                   return false;
                }
                

                
                $("#ajax-modal").modal('show');
                $.ajax({
                  type: "POST",
                  datatype:"json",
                  url: "student-ajax.php",
                  data: { action: "get_student_answer_sheet", quiz_id: quiz_id}
                })
                .done(function( msg ) {

                     $("#message_display").html('');
                     $("#answer_script").html(msg);
                     $("#ajax-modal").modal('hide');
                });
            });

        });
    </script>
</body>
</html>