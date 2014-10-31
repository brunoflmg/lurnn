<?php
    require_once "config.php";
    checkLogin();

    $query = sprintf("SELECT * FROM quizes WHERE  quiz_id='%s'",$_GET['quiz_id']);
    $result = mysql_query($query);
    if(mysql_num_rows($result)==0){
        $error_message['no_quizes_found'] = "You don't have any created Quiz. Please create quiz at first. You are being redirected to create quiz with few seconds.<script>setTimeout(function(){window.location='create_quiz.php';},3000);</script>";
    }else{
        while($quiz = mysql_fetch_object($result)){
            $quizes[] = $quiz; 
        }
    }

    if(isset($_GET['quiz_id'])){
        $selected_quiz_id = intval($_GET['quiz_id']);
    }else{
        $selected_quiz_id = $quizes[0]->quiz_id;
    }
    //d($selected_quiz_id,1);
    $sql       = "SELECT *FROM quiz_questions_stat WHERE quiz_id = '$selected_quiz_id' AND creator_tid='".$_SESSION['tid']."'";
    $result    = mysql_query($sql);
    $quiz_stat = mysql_fetch_object($result);
    
    $sql       = "SELECT * FROM skills WHERE class_id = '{$quizes[0]->class_id}' and quiz_id='{$quizes[0]->quiz_id}'";
    $result    = mysql_query($sql);

    while($skill = mysql_fetch_object($result)){
        $skills[$skill->skill_id] = $skill->skill;
    }

     
	$totQNS = $quiz_stat->no_of_question;
	$askQNS = $quiz_stat->total_asked_question;
	if( $totQNS == $askQNS  )
	{
		echo "<script>window.location='dashboard.php';</script>";
		$success_message = "You have successfully created a quiz.";
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
                    <div id="message_box">
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
                    </div>
                    <h3 style="margin-bottom:20px;">Create Questions for Your Quiz</h3>
                    <form method="post" action="" id="create_new_questions">
                        <div class="span8 column">
                            
                            <div class="field-box">
                                <label> Question:</label>
                                <textarea rows="2" class="span12 ckeditor" id="ckeditor" style='display:inline-block;' placeholder="Question" required="required"  name="question_details"></textarea>
                            </div>

                            <div class="field-box" style="margin-top:50px;" id="possible_mcq_answer_box">
                               <table style="width:100%" class="table" id="possible_mcq_answer">

                               <tr class="possible_answer">
                                    <td class="span1">
                                        <span class="badge badge-info">1</span>
                                    </td>
                                    <td class="span10" role="textbox" style="border:2px solid #CCC; background:#EEE;"><div  contenteditable="true" id="option_1">Possible Answer Option 1</div></td> 
                                    <td class="span1">
                                        <span class="remove_answer badge badge-important" style="cursor:pointer;">X</span>
                                    </td>
                                </tr>
                                <tr class="possible_answer">
                                    <td class="span1">
                                        <span class="badge badge-info">2</span>
                                    </td>
                                    <td class="span10" role="textbox" style="border:2px solid #CCC; background:#EEE;"><div  contenteditable="true" id="option_2"> Possible Answer Option 2</div></td> 
                                    <td class="span1">
                                        <span class="remove_answer badge badge-important" style="cursor:pointer;">X</span>
                                    </td>
                                </tr>
                                <tr class="possible_answer">
                                    <td class="span1">
                                        <span class="badge badge-info">3</span>
                                    </td>
                                    <td class="span10" role="textbox" style="border:2px solid #CCC; background:#EEE;"><div contenteditable="true" id="option_3">Possible Answer Option 3</div></td> 
                                    <td class="span1">
                                        <span class="remove_answer badge badge-important" style="cursor:pointer;">X</span>
                                    </td>
                                </tr>
                                <tr class="possible_answer">
                                    <td class="span1">
                                        <span class="badge badge-info">4</span>
                                    </td>
                                    <td class="span10" role="textbox" style="border:2px solid #CCC; background:#EEE;"><div contenteditable="true" id="option_4">Possible Answer Option 4</div></td> 
                                    <td class="span1">
                                        <span class="remove_answer badge badge-important" style="cursor:pointer;">X</span>
                                    </td>
                                </tr>
                                </table>
                                <span class="btn btn-default pull-right" id="add_new_mcq">+ Add New option</span>                             
                            </div>
                            <div class="field-box" style="margin-top:30px;">
                                <div class="span5 offset2"><button class="btn btn-primary" type="submit" name="submit">Create Question</div>
                            </div>
                        </div>
                        <div class="span4 pulled-right">
                            <div class="field-box" style="margin-bottom:20px;">
                                 <h5 style="font-weight:bold;">Questions : <span id="question_stat" style="border:2px solid #111;padding:2px;"><?php echo $quiz_stat->total_asked_question;?>/<?php echo $quiz_stat->no_of_question;?></span> &nbsp; &nbsp; &nbsp; Points : <span id="points_stat" style="border:2px solid #111;padding:2px;"><?php echo $quiz_stat->total_cost_points;?>/<?php echo $quiz_stat->points;?></span></h5>
                            </div>
                            <div class="field-box" style="display:none;">
                                <label>Choose Your Quiz:</label>
                                <div class="ui-select" style="width:300px;">
                                    <select name="quiz_id" id="quiz_id">
                                      <?php foreach ($quizes as $quiz) { ?>
                                       <option <?php if($selected_quiz_id==$quiz->quiz_id){echo "selected";}?> value="<?php echo $quiz->quiz_id;?>"><?php echo $quiz->quiz_subject;?></option>
                                      <?php } ?>      
                                    </select>
                                </div>
                            </div> 
                                
                            
                            
                            <div class="field-box">
                                <label>Choose Skills:</label>
                                <div id="skills_box">
                                    <select name="skills[]" id="skills" style="width:300px;" multiple>
                                    <?php 
                                       
                                       if(is_array($skills) && !empty($skills)){
                                        foreach ($skills as $skill_id => $skill_value) { ?>
                                       <option value="<?php echo $skill_id;?>"><?php echo $skill_value;?></option>
                                       
                                    <?php  }} ?>
                                    </select>
                                </div>
                            </div>
                            <div class="field_box">
                                <label>Choose Question Type</label>
                                <div class="ui-select" style="width:300px;">
                                   <select name="question_type" id="form_question_type">
                                      <option value="boolean">True / False</option>
                                      <option value="mcq" selected>MCQ</option>
                                      <option value="self_answer">Self Answer</option>
                                   </select>
                                </div>
                            </div>
                            <div class="field-box">
                                <label> Right Answer:</label>
                                <div class="ui-select" style="width:300px;display:none;" id="right_answer_boolean_box">
                                     <select name="right_answer_boolean" id="right_answer_boolean" tabindex="-1">
                                         <option value="TRUE">TRUE</option>
                                         <option value="FALSE">FALSE</option>
                                     </select>
                                </div>
                                <div class="ui-select" style="width:300px;" id="right_answer_mcq_box">
                                     <select name="right_answer_mcq" id="right_answer_mcq" tabindex="-1">
                                         <option value="option_1">Option 1</option>
                                         <option value="option_2">Option 2</option>
                                         <option value="option_3">Option 3</option>
                                         <option value="option_4">Option 4</option>
                                     </select>
                                </div>
                                <textarea class="hide" style="width:280px;" rows="1" name="right_answer_self_evaluated" id="right_answer_self_evaluated"></textarea>

                            </div>
                            <div class="field-box">
                                <label>Points</label>
                                <input type="number" <?php if($quiz_stat->points_of_each_question=="equal"){echo "readonly='readonly'";}?> name="points" id="form_points" value="<?php echo ($quiz_stat->points/$quiz_stat->no_of_question);?>">
                            </div>         
                        </div>
                    
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php include "includes/ajax-loader.php";?>
    
    <!--<script src="http://code.jquery.com/jquery-latest.js"></script>-->
    <script src="js/jquery-1.11.0.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/select2.min.js"></script>
    <script src="js/theme.js"></script>
    <script src="js/ckeditor_basic/ckeditor/ckeditor.js"></script>
    <script type="text/javascript">
        function getQuizStat(quiz_id,skill_load){
            $('#ajax-modal').modal('show');
            $.ajax({
                type: "POST",
                dataType : 'json',
                url: "teacher-ajax.php",
                data: { action: "quiz_questions_stat", quiz_id: quiz_id }
            }).done(function( data ) {
               if(skill_load==true){
                   $("#skills_box").html('<select name="skills[]" id="skills" style="width:300px;" multiple></select>');
                   try{
                       $.each(data.skills, function(index, val) {
                            $("#skills").html('<option value="'+index+'">'+$.trim(val)+'</option>');
                       });
                       $("#skills").select2({
                            placeholder: "Choose Skills"
                       });
                    }catch(err){
                        console.log(err);
                    }
               }
               

               $("#question_stat").html(parseInt(data.stat.total_asked_question)+"/"+parseInt(data.stat.no_of_question));
               $("#points_stat").html(parseInt(data.stat.total_cost_points)+"/"+parseInt(data.stat.points));
               if(data.stat.points_of_each_question=="equal"){
                  $("#form_points").attr('readonly', 'readonly');
                  $("#form_points").val(data.stat.points/data.stat.no_of_question);
               }else{
                  $("#form_points").removeAttr('readonly');
                  $("#form_points").val(parseInt(data.stat.points)/parseInt(data.stat.no_of_question));
               }
               $('#ajax-modal').modal('hide');
            });
        }

        $(function () {
              // select2 plugin for select elements
            $("#skills").select2({
                placeholder: "Choose Skills"
            });
            // select2 plugin for select elements
            $("#quiz_id").change(function(event) {

               getQuizStat($(this).val(),true);
                
            });

            //console.log(this.value);
                //console.log(skills[this.value]);


            $("#add_new_mcq").click(function(){
                var total_answer = $(".badge-info").length;
                $("#possible_mcq_answer").append('<tr class="possible_answer"><td class="span1"><span class="badge badge-info">'+(total_answer+1)+'</span></td><td class="span10" role="textbox" style="border:2px solid #CCC; background:#EEE;"><div contenteditable="true" id="option_'+(total_answer+1)+'">Possible Answer Option '+(total_answer+1)+'</div></td><td class="span1"><span class="remove_answer badge badge-important" style="cursor:pointer;">X</span></td></tr>');
                $("#right_answer_mcq").append('<option value="option_'+(total_answer+1)+'">Option '+(total_answer+1)+'</option>');
                
                $(".remove_answer").click(function(){
                    //console.log(this);
                    $(this).parent().parent().remove();
                    var counter = $(".badge-info");
                    $("#right_answer_mcq").html("");
                    $.each(counter,function(index,value){
                       //console.log("Index : "+index+" Value :"+value);
                       //console.log(value);
                       //console.log(index);
                       $(value).html(index+1);
                       $("#right_answer_mcq").append('<option value="option_'+(index+1)+'">Option '+(index+1)+'</option>');                      
                    });

                    var content_editable = $("div[contenteditable='true']");
                    $.each(content_editable,function(index,value){
                       //console.log("Index : "+index+" Value :"+value);
                       //$(value).attr("id","option_"+(index+1));
                       //$(value).html("Possible Answer Option "+(index+1));
                       var text = $(value).text();
                       //console.log(text);
                       //console.log(text.substr(0, 22).trim());
                       if(text.substr(0, 22).trim()=="Possible Answer Option"){
                          $(value).html("Possible Answer Option "+(index+1));
                       }
                    });
                    return  false;
                   
                });
                CKEDITOR.inline( document.getElementById( 'option_'+(total_answer+1) ) );
                return  false;
            });
            
            $("#form_question_type").change(function(){
                switch($(this).val()){
                    case "boolean" :
                        //$("#possible_boolean_answer").show();
                        $("#possible_mcq_answer_box").hide();
                        $("#right_answer_self_evaluated").hide();
                        $("#right_answer_mcq_box").hide();
                        $("#right_answer_boolean_box").show();
                    break;
                    case "mcq" :
                        $("#possible_mcq_answer_box").show();
                        $("#right_answer_self_evaluated").hide();
                        $("#right_answer_mcq_box").show();
                        $("#right_answer_boolean_box").hide();
                    break;
                    case "self_answer":
                        $("#possible_mcq_answer_box").hide();
                        $("#right_answer_self_evaluated").show();
                        $("#right_answer_mcq_box").hide();
                        $("#right_answer_boolean_box").hide();
                        break;
                    default:
                        $("#possible_mcq_answer_box").show();
                        $("#right_answer_self_evaluated").hide();
                        $("#right_answer_mcq_box").show();
                        $("#right_answer_boolean_box").hide();
                    break;
                }
            });

            $(".remove_answer").click(function(){
                //console.log(this);
                $(this).parent().parent().remove();
                var counter = $(".badge-info");
                $("#right_answer_mcq").html("");
                $.each(counter,function(index,value){
                   //console.log("Index : "+index+" Value :"+value);
                   //console.log(value);
                   //console.log(index);
                   $(value).html(index+1);
                   $("#right_answer_mcq").append('<option value="option_'+(index+1)+'">Option '+(index+1)+'</option>');                      
                });

                var content_editable = $("div[contenteditable='true']");
                $.each(content_editable,function(index,value){
                   //console.log("Index : "+index+" Value :"+value);
                   //$(value).attr("id","option_"+(index+1));
                   //$(value).html("Possible Answer Option "+(index+1));
                   var text = $(value).text();
                   //console.log(text);
                   //console.log(text.substr(0, 22).trim());
                   if(text.substr(0, 22).trim()=="Possible Answer Option"){
                      $(value).html("Possible Answer Option "+(index+1));
                   }
                });
            });

            $( "#create_new_questions" ).submit(function( event ) {
                //console.log(event);
                event.preventDefault();
                var data  = new Object();
                //console.log(data);
                data.question_details = CKEDITOR.instances['ckeditor'].getData();
                data.quiz_id   = $("#quiz_id").val();
                data.skills    = $("#skills").val();
                data.points    = $("#form_points").val();
                data.question_type = $("#form_question_type").val();
                var message  = "";
                if($.trim(data.question_details)==""){
                    message = "<p>Question Can't be empty</p>";
                }

                if($.trim(data.quiz_id)==""){
                    message += "<p>Please Choose Quiz</p>";
                }
                if(data.skills==null){
                    message += "<p>Please Choose Skills</p>";
                }
                if($.trim(data.points)==""){
                    message += "<p>Please Eneter Number of points</p>";
                }
                if($.trim(data.question_type)==""){
                    message += "<p>Please Question Type</p>";
                }
                switch(data.question_type){
                    case "boolean":
                         data.right_answer_boolean = $("#right_answer_boolean").val();
                         if($.trim(data.right_answer_boolean)==""){
                            message += "<p>Please Choose Your Right Answer</p>";
                        }
                    break;
                    case "mcq":
                         data.right_answer_mcq     = $("#right_answer_mcq").val();

                         var editable_content = $('div[contenteditable="true"]');
                         var possible_answers = new Array();
                         $.each(editable_content, function(index, val) {
                              var id = $(val).attr('id');
                              possible_answers[(index+1)]= CKEDITOR.instances[id].getData();
                         });
                         data.possible_answers = possible_answers;

                        if($.trim(data.right_answer_mcq)==""){
                            message += "<p>Please Choose Your Right Answer</p>";
                        }
                        if(possible_answers.length < 2){
                            message += "<p>Please Add more than 1 possible answers</p>";
                        }

                    break;
                    case "self_answer":
                         data.right_answer_self    = $("#right_answer_self_evaluated").val();
                         
                    break;
                    default:
                    break;

                }
                //console.log(message);
                if(message!=""){
                    $("#message_box").append('<div class="alert alert-error"><button data-dismiss="alert" class="close" type="button">×</button> '+message+'</div>');
                }else{
                    //$('#ajax-modal').modal('show');
                    $.ajax({
                        type: "POST",
                        url: "teacher-ajax.php",
                        data: { action: "create_quiz_question", data: data }
                    })
                    .done(function( msg ) {
                       if(msg=="yes"){
                          getQuizStat(data.quiz_id,false);
						  
                          //bootbox.alert("<h3 class='text-success'>You have successfully deleted this comment.</h3>");
                          //$("#message_box").html('<div class="alert alert-success"><button data-dismiss="alert" class="close" type="button">×</button> You have successfully created a questions</div>');
						  window.location = 'http://lurnn.com/create-aptitude-question.php?quiz_id='+data.quiz_id;
                       }else{
                          $("#ajax-modal").modal('hide');
                          $("#message_box").html('<div class="alert alert-error"><button data-dismiss="alert" class="close" type="button">×</button> '+msg+' </div>');
                       }

                    });
                }
                //console.log(data);
                //var question_details = $("#form_question_details").val();
                
            });
                    
        });
    
    </script>
</body>
</html>