<?php
    // You have to write all php code here
   require_once "config.php";
   if(isset($_SESSION['tid'])){
      $user_id = $_SESSION['tid'];
      $type    = "teacher";
   }else if(isset($_SESSION['sid'])){
      $user_id = $_SESSION['sid'];
      $type    = "student";
   }else{
        echo "<script>window.location='/';</script>";
        exit;
   }
   
	if(isset($_GET['page']) && is_numeric($_GET['page'])){
     $page = $_GET['page'];
	   }else{
		 $page = 1; 
	   }
	   if(isset($_GET['display']) && is_numeric($_GET['display'])){
		 $display = $_GET['display'];
	   }else{
		 $display = 10; 
	   }
	   
	   $limit_start = ($page-1)*$display;
      
   if(isset($_POST['submit']))
	{
		$assignment_name = $_POST['assignment_name'];
		$assignment_type = $_POST['assignment_type'];
		$asign_content   = $_POST['asign_content']; 
		$due_date_time   = mysql_real_escape_string($_POST["due_date_time"]);
		$available_until = mysql_real_escape_string($_POST["available_until"]);
		$created_date    = date("Y-m-d H:i:s");
		$user_id; 
		$class_id = $_POST['class_id'];
		
		$sql1 = sprintf("INSERT INTO upcoming_assignments(asign_id,asign_name,asign_type,asign_content,due_date,avlaible_until,created_date,user_id,class_id) VALUES('','$assignment_name','$assignment_type','$asign_content','$due_date_time','$available_until','$created_date','$user_id','$class_id')");
		
		if(mysql_query($sql1))
		{
			header("Location: upcoming_assignments.php"); exit;
		}
		else
		{
			$errors['other'] = "Sorry!!! Internal Problem! Please try again later";
			$_SESSION['flash_errors'] = $errors;
			header("Location: upcoming_assignments.php"); exit;
		}
		
	}
	
	//--- Get Inserted Assignments-----------------------
		$sql = "SELECT upcoming_assignments.*, teachers.* FROM upcoming_assignments INNER JOIN teachers ON upcoming_assignments.user_id = teachers.tid Where upcoming_assignments.user_id = '$user_id' ORDER BY upcoming_assignments.asign_id DESC LIMIT $limit_start, $display";
        $result = mysql_query($sql);
        //d($sql,1);
        while($feed  = mysql_fetch_object($result))
		{
            //$feeds[] = $feed;
        } 
	
	//--- Get QUIZES-----------------------	
		$sql1 = "SELECT quizes.*, teachers.* FROM quizes INNER JOIN teachers ON quizes.creator_tid = teachers.tid Where quizes.creator_tid = '$user_id' ORDER BY quizes.quiz_id DESC LIMIT $limit_start, $display";
        $result1 = mysql_query($sql1);
        //d($sql,1);
        while($feedquiz  = mysql_fetch_object($result1))
		{
            $feedquizs[] = $feedquiz;
        } 
	
	//--- Get ESSAY-----------------------	
		$sql1 = "SELECT essays.*, teachers.* FROM essays INNER JOIN teachers ON essays.user_id = teachers.tid Where essays.user_id = '$user_id' ORDER BY essays.essay_id DESC LIMIT $limit_start, $display";
        $result1 = mysql_query($sql1);
        //d($sql,1);
        while($feedessay  = mysql_fetch_object($result1))
		{
            $feedessays[] = $feedessay;
        }
	
	//---get classes--------------------
		$sql_class   = "SELECT * FROM `classes` WHERE creator_tid ='".$user_id."'";
        $result_class = mysql_query($sql_class);

        while($class = mysql_fetch_object($result_class)){
            $classes[$class->class_id] = $class;
        }
	
	//----Get Essays-------------------------
		
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
    <?php
     if($type=="teacher"){
       include "includes/menu.php";
       include "includes/left_side_bar.php";
     }else{
       include "includes/student_menu.php";
       include "includes/student_left_side_bar.php";
     }
     ?>



	<!-- main container -->
    <div class="content">
        
        <div class="container-fluid">

            <div id="pad-wrapper">
                 
                <!-- statistics chart built with jQuery Flot -->
                <?/*<div class="row-fluid">
                    <p class="span2 offset9">
					<a href="#myModal" data-backdrop="static" role="button" class="btn btn-primary" data-toggle="modal">Create New Assignment</a>
					</p>
                </div>*/?>
                <div class="row-fluid">
                   
                   <div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <form  id="fileupload" method="post" enctype="multipart/form-data">
                    <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3 id="myModalLabel">Create New Assignment</h3>
                    </div>
                    <div class="modal-body">
                   
                        <div class="control-group">
                            <!--<label class="control-label" for="inputEmail">Subject</label>-->
                            <div class="controls">
                                <input type="text" name="assignment_name" required="required" class="span12" placeholder="Type Assignment Name">
                            </div>
                        </div>
						<div class="control-group">
                            <div class="controls">
                                <textarea name="asign_content" required="required" class="animated span12" placeholder="Write Your Assignment Details"></textarea>
                            </div>
                        </div>
                        <div class="control-group" style="float: left; width: 100%;">
                            <!--<label class="control-label" for="input">Description</label>-->
                            <div class="controls">
                                <div class="span6">
                                    <label>Assignment Type:</label>
                                    <div class="ui-select span12">
                                        <select name="assignment_type" required="required">
                                           <option value="type_1">Type 1</option>
										   <option value="type_2">Type 2</option>
										   <option value="type_3">Type 3</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
						
						<div class="control-group" style="float: left; width: 100%;">
                            <!--<label class="control-label" for="input">Description</label>-->
                            <div class="controls">
                                <div class="span6">
                                    <label>Choose Class:</label>
                                    <div class="ui-select span12">
                                        <select name="class_id" required="required">
                                        <?php if(is_array($classes) && !empty($classes) ){ 

                                           foreach($classes as $class){
                                        ?>

                                            <option value="<?php echo $class->class_id;?>"><?php echo $class->class_name;?></option>
                                            <?php }} ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="control-group">
                            <!--<label class="control-label" for="input">Description</label>-->
                            <div class="controls">
                                <div class="span6">
                                    <label>Due Date:</label>
									<div class="input-append date form_datetime">
                                    <span class="add-on"><i class="icon-th"></i></span>
                                    <input size="16" type="text" name="due_date_time" class="span12" required="required" value="" readonly>
                                    
                                </div>
                                </div>
                            </div>
                        </div>
						
						<div class="control-group">
                            <!--<label class="control-label" for="input">Description</label>-->
                            <div class="controls">
                                <div class="span6">
                                    <label>Available Until:</label>
									<div class="input-append date form_datetime">
                                    <span class="add-on"><i class="icon-th"></i></span>
                                    <input size="16" type="text" name="available_until" class="span12" required="required" value="" readonly>
                                    
                                </div>
                                </div>
                            </div>
                        </div>
                     </div>
                    <div class="modal-footer">
                    <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
                     <button type="submit" name="submit" class="btn btn-primary">Create New Assignment</button>
                    </div>
                    </form>
                    </div>
                     <div class="pasignation" style="text-align:center;"></div>
                    
                    <div class="media">
						<h2 style="float: left; font-size: 12px; font-weight: bold; margin: 0px 0px 9px;">Upcoming Assignments</h2>
						<span style="float:left; height:780px; overflow-y:scroll; width:100%;">
						<table id="student_classes" class="table table-hover table-responsive">
						<tbody>
						<?php
						   if(is_array($feeds) && !empty($feeds))
							{
								//echo "<pre>"; print_r($feeds);
							  ?>
								<?php $i=1;
								   foreach ($feeds as $assigned_class) {
								   //echo "<pre>"; print_r($assigned_class);
								   $classID =  $assigned_class->class_id;
								//--- Below code will show the record of only tose classes in which current login student is assigned-------------- 
								   ?>
									<tr class="<?php echo $assigned_class->status;?>">
									  <td>
										<img src="img/assignment_icon.png" height="16" width="16"/>
									  <?php echo $assigned_class->asign_name;?></td>
									  <td>
										<?php
										$date = date_create($assigned_class->avlaible_until);
										echo "<b>Available until </b>".date_format($date, 'F j'); ?>
									  </td>
									  <td>
										<?php
										$date = date_create($assigned_class->due_date);
										echo "<b>Due </b>".date_format($date, 'F j \a\t g:ia'); ?>
									  </td>
									</tr>
									<?php } ?>
								 
							  <?php
							}
						?>
					
					<!----SHOW QUIZ------------>
						<?php
						   if(is_array($feedquizs) && !empty($feedquizs))
							{
								//echo "<pre>"; print_r($feeds);
							  ?>
								<?php $i=1;
								   foreach ($feedquizs as $assigned_class) {
								   //echo "<pre>"; print_r($assigned_class);
								   $classID =  $assigned_class->class_id;
								//--- Below code will show the record of only tose classes in which current login student is assigned-------------- 
								   ?>
									<tr>
									  <td>
										<img src="img/quiz_icon.png" height="16" width="16"/>
										<a href="#" style="text-decoration:underline">
											<?php echo "Quiz". $i++." (".$assigned_class->quiz_subject.")";?>
										</a>
									  </td>
									  <td>
										<?php
										$date = date_create($assigned_class->quiz_created_date);
										echo "<b>Available until </b>".date_format($date, 'F j'); ?>
									  </td>
									  <td>
										<?php
										$date = date_create($assigned_class->quiz_holding_date_time);
										echo "<b>Due </b>".date_format($date, 'F j \a\t g:ia'); ?>
									  </td>
									</tr>
									<?php } ?>
							  <?php
							}
						?>
						
					<!----SHOW ESSAY------------>
						<?php
						   if(is_array($feedessays) && !empty($feedessays))
							{
								
							  ?>
								<?php $i=1;
								   foreach ($feedessays as $assigned_class) {
								   //echo "<pre>"; print_r($assigned_class);
								   $classID =  $assigned_class->class_id;
								//--- Below code will show the record of only tose classes in which current login student is assigned-------------- 
								   ?>
									<tr>
									  <td>
										<img src="img/essay_icon.png" height="16" width="16"/>
											<a target="_blank" href="view_essay.php?id=<?php echo $assigned_class->essay_id;?>" style="text-decoration:underline">
											<?php echo "Essay". $i++." (".$assigned_class->essay_title.")";?>
										</a>
										</td>
									  <td>
										<?php
										$date = date_create($assigned_class->avlaible_until);
										echo "<b>Available until </b>".date_format($date, 'F j'); ?>
									  </td>
									  <td>
										<?php
										$date = date_create($assigned_class->due_date);
										echo "<b>Due </b>".date_format($date, 'F j \a\t g:ia'); ?>
									  </td>
									</tr>
									<?php } ?>
							  <?php
							}
						?>	
						
					 </tbody>
					</table>
					</span>					
                    </div>
                   <div class="pasignation" style="text-align:center;"></div>
                </div>
            </div>
        </div>
    </div>


	
    <!-- The template to display files available for upload -->
<script id="template-upload" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-upload fade">
        <td>
            <span class="preview"></span>
        </td>
        <td>
            <p class="name">{%=file.name%}</p>
            <strong class="error text-danger"></strong>
        </td>
        <td>
            <p class="size">Processing...</p>
            <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><div class="progress-bar progress-bar-success" style="width:0%;"></div></div>
        </td>
        <td>
            {% if (!i && !o.options.autoUpload) { %}
                <button class="btn btn-primary start" disabled>
                    <i class="glyphicon glyphicon-upload"></i>
                    <span>Start</span>
                </button>
            {% } %}
            {% if (!i) { %}
                <button class="btn btn-warning cancel">
                    <i class="glyphicon glyphicon-ban-circle"></i>
                    <span>Cancel</span>
                </button>
            {% } %}
        </td>
    </tr>
{% } %}
</script>

<!-- The template to display files available for download -->
<script id="template-download" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-download fade">
        <td>
            <span class="preview">
                {% if (file.thumbnailUrl) { %}
                    <a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" data-gallery><img src="{%=file.thumbnailUrl%}"></a>
                {% } %}
            </span>
        </td>
        <td>
            <p class="name">
                {% if (file.url) { %}
                    <a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" {%=file.thumbnailUrl?'data-gallery':''%}>{%=file.name%}</a>
                    <input type="hidden" name="attachments[]" value="{%=file.url%}">
                {% } else { %}
                    <span>{%=file.name%}</span>
                    <input type="hidden" name="attachments[]" value="{%=file.name%}">
                {% } %}
            </p>
            {% if (file.error) { %}
                <div><span class="label label-danger">Error</span> {%=file.error%}</div>
            {% } %}
        </td>
        <td>
            <span class="size">{%=o.formatFileSize(file.size)%}</span>
        </td>
        <td>
            {% if (file.deleteUrl) { %}
                <button class="btn btn-danger delete" data-type="{%=file.deleteType%}" data-url="{%=file.deleteUrl%}"{% if (file.deleteWithCredentials) { %} data-xhr-fields='{"withCredentials":true}'{% } %}>
                    <i class="glyphicon glyphicon-trash"></i>
                    <span>Delete</span>
                </button>
                <input type="checkbox" name="delete" value="1" class="toggle">
            {% } else { %}
                <button class="btn btn-warning cancel">
                    <i class="glyphicon glyphicon-ban-circle"></i>
                    <span>Cancel</span>
                </button>
            {% } %}
        </td>
    </tr>
{% } %}
</script>
<script src="http://code.jquery.com/jquery-latest.js"></script>
<!-- The jQuery UI widget factory, can be omitted if jQuery UI is already included -->
<script src="js/jquery-ui-1.10.2.custom.min.js"></script>
<!-- The Templates plugin is included to render the upload/download listings -->
<script src="http://blueimp.github.io/JavaScript-Templates/js/tmpl.min.js"></script>
<!-- The Load Image plugin is included for the preview images and image resizing functionality -->
<script src="http://blueimp.github.io/JavaScript-Load-Image/js/load-image.min.js"></script>
<!-- The Canvas to Blob plugin is included for image resizing functionality -->
<script src="http://blueimp.github.io/JavaScript-Canvas-to-Blob/js/canvas-to-blob.min.js"></script>
<!-- Bootstrap JS is not required, but included for the responsive demo navigation -->
<script src="js/bootstrap.min.js"></script>
<!-- blueimp Gallery script -->
<script src="http://blueimp.github.io/Gallery/js/jquery.blueimp-gallery.min.js"></script>
<!-- The Iframe Transport is required for browsers without support for XHR file uploads -->
<script src="fileupload/js/jquery.iframe-transport.js"></script>
<!-- The basic File Upload plugin -->
<script src="fileupload/js/jquery.fileupload.js"></script>
<!-- The File Upload processing plugin -->
<script src="fileupload/js/jquery.fileupload-process.js"></script>
<!-- The File Upload image preview & resize plugin -->
<script src="fileupload/js/jquery.fileupload-image.js"></script>
<!-- The File Upload audio preview plugin -->
<script src="fileupload/js/jquery.fileupload-audio.js"></script>
<!-- The File Upload video preview plugin -->
<script src="fileupload/js/jquery.fileupload-video.js"></script>
<!-- The File Upload validation plugin -->
<script src="fileupload/js/jquery.fileupload-validate.js"></script>
<!-- The File Upload user interface plugin -->
<script src="fileupload/js/jquery.fileupload-ui.js"></script>
<!-- The main application script -->
<script src="fileupload/js/main.js"></script>
<!-- The XDomainRequest Transport is included for cross-domain file deletion for IE 8 and IE 9 -->
<!--[if (gte IE 8)&(lt IE 10)]>
<script src="fileupload/js/cors/jquery.xdr-transport.js"></script>
<![endif]-->
<!-- scripts -->
    <!-- knob -->
    <script src="js/jquery.knob.js"></script>
    <!-- flot charts -->
    <script src="js/theme.js"></script>
    <script src='js/jquery.autosize.min.js'></script>
    <script src="js/bootstrap-paginator.js"></script>
    <script type="text/javascript">
        $(function () {

            // jQuery Knobs
            $(".knob").knob();
            $('.animated').autosize({append: "\n"});

            var options = {
                currentPage: <?php echo $page;?>,
                totalPages: <?php echo $total_page;?>,
                pageUrl: function(type, page, current){

                    return "discussionboard.php?page="+page;

                }
            }

            $('.pasignation').bootstrapPaginator(options);
        });
    </script>
	   <?php include("includes/ajax-loader.php");?>

	<script src="js/wysihtml5-0.3.0.js"></script>
    <script src="http://code.jquery.com/jquery-latest.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/bootstrap-wysihtml5-0.0.2.js"></script>
    <script src="js/jquery.uniform.min.js"></script>
    <script src="js/select2.min.js"></script>
    <script src="js/theme.js"></script>
    <script type="text/javascript" src="js/bootstrap-datetimepicker.min.js" charset="UTF-8"></script>
    <script type="text/javascript">

        $(function() {
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