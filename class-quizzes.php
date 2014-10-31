<?php
    require_once "config.php";
    checkLogin();
    
    $tid    = $_SESSION['tid'];
    $sql    = sprintf("SELECT t1.* FROM classes as t1 WHERE t1.creator_tid='%s'",$tid);
    $result = mysql_query($sql);
    while($class = mysql_fetch_object($result)){
        $classes[$class->class_id] = $class;
        $classes_data[$class->class_id] = $class->class_name." (".$class->class_code.")";
    }
    $sql    = sprintf("SELECT t1.* FROM quizes as t1 WHERE t1.creator_tid='%d'",$tid) ;
    //d($classes,1);
    $result = mysql_query($sql);
    while($class_quiz = mysql_fetch_object($result)){
        $class_quizzes[$class_quiz->quiz_id] = $class_quiz;
    }
    $data_source = json_encode($classes_data);
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
				<br/>
                   <table id="class_lists" class="display" width="100%">
        <thead>
          <tr>
            <th>Quiz Name</th>
            <th>Quiz Details</th>
            <th>Skills</th>
            <th>Class</th>
            <th>Points</th>
            <th>No of Questions</th>
            <th>Quiz Time ( In minute )</th>
            <th>Quiz Date Time</th>
            <th>Start date</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>

        <tfoot>
         <tr>
            <th>Quiz Name</th>
            <th>Quiz Details</th>
            <th>Skills</th>
            <th>Class</th>
            <th>Points</th>
            <th>No of Questions</th>
            <th>Quiz Time ( In minute )</th>
            <th>Quiz Date Time</th>
            <th>Start date</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </tfoot>

        <tbody>
        <?php if(is_array($class_quizzes)&&!empty($class_quizzes)){
				//echo "<pre>"; print_r($class_quizzes); die();
           foreach($class_quizzes as $class_quizz){
          ?>
          <tr id="class_quiz_<?php echo $class_quizz->quiz_id;?>">
           <td>
              <a href="#" class="editable" data-name="quiz_subject" data-type="text" data-pk="<?php echo $class_quizz->quiz_id;?>" data-url="/teacher-ajax.php?action=edit_quiz" data-title="Quiz Name"><?php echo $class_quizz->quiz_subject;?></a>
           </td>
            <td>
              <a href="#" class="editable" data-name="quiz_details" data-type="textarea" data-pk="<?php echo $class_quizz->quiz_id;?>" data-url="/teacher-ajax.php?action=edit_quiz" data-title="Quiz Details"><?php echo $class_quizz->quiz_details;?></a>
            </td>
            <td>
            
            <a href="#" class="editable" data-name="skills" data-type="textarea" data-pk="<?php echo $class_quizz->quiz_id;?>" data-url="/teacher-ajax.php?action=edit_quiz" data-title="Comma Separated Skills">		<?php 
						$qzID = $class_quizz->quiz_id;
						$sqlx = sprintf("SELECT * FROM quiz_skills WHERE creator_tid='$tid' AND quiz_id = '$qzID'");
						$resultx = mysql_query($sqlx);
						$cnt = mysql_num_rows($resultx);
						$i = 1;
						while($skillx = mysql_fetch_object($resultx))
						{
							echo $skillx->skills;
							if($i != $cnt)
							{
								echo ",";
							}
							$i++;
						}
					?>
			</a>
            </td>
            <td>
            
            <a href="#" class="editable" data-name="class_id" data-type="select" data-pk="<?php echo $class_quizz->quiz_id;?>" data-url="/teacher-ajax.php?action=edit_quiz" data-source='<?php echo $data_source;?>' <?php if(isset($classes[$class_quizz->class_id])){ ?> data-value="<?php echo $class_quizz->class_id;?>" <?php } ?> data-title="Choose Class to assign"><?php if(isset($classes[$class_quizz->class_id])){ ?><?php echo $classes[$class_quizz->class_id]->class_name." (".$classes[$class_quizz->class_id]->class_code.")";?> <?php } ?></a>
            </td> 
            <td>
              <a href="#" class="editable" data-name="points" data-type="number" data-pk="<?php echo $class_quizz->quiz_id;?>" data-url="/teacher-ajax.php?action=edit_quiz" data-title="Quiz Points"><?php echo $class_quizz->points;?></a>
            </td>
            <td>
              <a href="#" class="editable" data-name="no_of_question" data-type="number" data-pk="<?php echo $class_quizz->quiz_id;?>" data-url="/teacher-ajax.php?action=edit_quiz" data-title="Number of Questions"><?php echo $class_quizz->no_of_question;?></a>
            </td>
            <td>
              <a href="#" class="editable" data-name="quiz_time" data-type="number" data-pk="<?php echo $class_quizz->quiz_id;?>" data-url="/teacher-ajax.php?action=edit_quiz" data-title="Quiz Time in minute"><?php echo $class_quizz->quiz_time;?></a>
            </td>
            <td>
              <a href="#" class="editable" data-name="quiz_holding_date_time" data-type="datetime" data-format="yyyy-mm-dd HH:ii:ss" data-pk="<?php echo $class_quizz->quiz_id;?>" data-url="/teacher-ajax.php?action=edit_quiz" data-title="Quiz holding Date Time"><?php echo $class_quizz->quiz_holding_date_time;?></a>
            </td>
            <td><?php echo $class_quizz->quiz_created_date;?></td>
            <td id="quiz_status_<?php echo $class_quizz->quiz_id;?>"><?php if($class_quizz->quiz_status=="active") {echo '<span class="label label-success">Active</span>';}elseif($class_quizz->quiz_status=="pending"){echo '<span class="label label-warning">Pending</span>';}else{ echo '<span class="label label-info">Taken</span>';} ?></td>
            <td style="display:inline-block;width:120px;">
             <?php if($class_quizz->quiz_status=='active'){
              ?>
              <a href="#" class="btn btn-info"data-toggle="tooltip" data-title=" Click here to stop this quiz to take and mark it as taken" onclick="stopquiz('<?php echo $class_quizz->quiz_id;?>',this)"><i class="icon icon-lock"></i></a>
              <?php
              } ?>
              <?php if($class_quizz->quiz_status=='pending'){
              ?>
              <a href="#" class="btn btn-success"data-toggle="tooltip" data-title=" Click here to start this quiz to take and mark it as active" onclick="startquiz('<?php echo $class_quizz->quiz_id;?>',this)"><i class="icon icon-play"></i></a>
              <?php
              } ?>

            <a href="#" class="btn btn-danger" data-toggle="tooltip" data-title="Click here to delete this quiz" onclick="deletequiz('<?php echo $class_quizz->quiz_id;?>')"><i class="icon icon-trash"></i></a>
            <a href="quiz-results.php?quiz_id=<?php echo $class_quizz->quiz_id;?>" class="btn btn-primary" data-toggle="tooltip" data-title="Click here to see the quiz results">
              <i class="icon icon-tasks"></i></a>
              </td>
          </tr>
          <?php }
          } ?>
          </tbody>
          </table>
    </div>
            </div>
        </div>
    </div>
    <?php include "includes/ajax-loader.php"; ?>

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
       function deletequiz(quiz_id){
          bootbox.confirm("Are you sure to delete this quiz?", function(result) {
                if(result==false) return;
                $("#ajax-modal").modal('show');
                $.ajax({
                  type: "POST",
                  url: "teacher-ajax.php",
                  data: { action: "delete_quiz", quiz_id: quiz_id }
                })
                .done(function( msg ) {
                   $("#ajax-modal").modal('hide');
                   if(msg=="yes"){
                      bootbox.alert("<h3 class='text-success'>You have successfully deleted this quiz.</h3>");
                      $("#class_quiz_"+quiz_id).remove();
                   }else{
                      bootbox.alert("<h3 class='text-warning'>Sorry!! we could not delete this quiz.Please try again later.</h3>");
                   }
                });
          }); 
       }
       function stopquiz(quiz_id,obj){
        //console.log(obj);
          
          bootbox.confirm("Are you sure to stop this quiz?", function(result) {
                if(result==false) return;
                $("#ajax-modal").modal('show');
                $.ajax({
                  type: "POST",
                  url: "teacher-ajax.php",
                  data: { action: "stop_quiz", quiz_id: quiz_id }
                })
                .done(function( msg ) {
                   $("#ajax-modal").modal('hide');
                   if(msg=="yes"){
                      $("#quiz_status_"+quiz_id).html('<span class="label label-success">Taken</span>');
                      $(obj).replaceWith('<a href="#" class="btn btn-success"data-toggle="tooltip" data-title=" Click here to start this quiz to take and mark it as active" onclick="startquiz('+quiz_id+',this)"><i class="icon icon-play"></i></a>');
                      bootbox.alert("<h3 class='text-success'>You have successfully stopped this quiz.</h3>");
                   }else{
                      bootbox.alert("<h3 class='text-warning'>Sorry!! we could not stop this quiz.Please try again later.</h3>");
                   }
                });
          }); 
       }
       function startquiz(quiz_id,obj){
        //console.log(obj);

          bootbox.confirm("Are you sure to start this quiz?", function(result) {
                if(result==false) return;
                $("#ajax-modal").modal('show');
                $.ajax({
                  type: "POST",
                  url: "teacher-ajax.php",
                  data: { action: "start_quiz", quiz_id: quiz_id }
                })
                .done(function( msg ) {
                   $("#ajax-modal").modal('hide');
                   if(msg=="yes"){
                      bootbox.alert("<h3 class='text-success'>You have successfully started this quiz.</h3>");
                      $("#quiz_status_"+quiz_id).html('<span class="label label-success">Active</span>');
                      $(obj).replaceWith('<a href="#" class="btn btn-info"data-toggle="tooltip" data-title=" Click here to stop this quiz to take and mark it as taken" onclick="stopquiz('+quiz_id+',this)"><i class="icon icon-lock"></i></a>');
                   }else{
                      bootbox.alert("<h3 class='text-warning'>Sorry!! we could not start this quiz. This quiz may be incomplete questions & points.</h3>");
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
            
            $('#class_lists').DataTable();
        });
    </script>
</body>
</html>