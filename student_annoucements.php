<?php
    // You have to write all php code here
   require_once "config.php";
   if(isset($_SESSION['tid'])){
      $user_id = $_SESSION['tid'];
	  $email = $_SESSION['email'];
      $type    = "teacher";
   }else if(isset($_SESSION['sid'])){
      $user_id = $_SESSION['sid'];
	  $email = $_SESSION['email'];
	  $grade_level = $_SESSION['grade_level'];
      $type    = "student";
   }else{
        echo "<script>window.location='/';</script>";
        exit;
   }
   
   
   
   if((isset($_SESSION['tid']) || isset($_SESSION['sid'])) && isset($_POST['submit'])){
      if(isset($_POST['post_title']) && $_POST['post_title']!=""){
        $post_title = mysql_real_escape_string(htmlspecialchars(trim($_POST['post_title'])));
      }else{
        $errors['post_title'] = "Annoucement Title is required";
      }
      if(isset($_POST['post_content']) && $_POST['post_content']!=""){
        $post_content = mysql_real_escape_string(htmlspecialchars(trim($_POST['post_content'])));
      }else{
        $errors['post_content'] = "Annoucement Description is required";
      }
      
      if(isset($_POST['class_id']) && $_POST['class_id']!=""){
        $class_id = mysql_real_escape_string(trim($_POST['class_id']));
      }else{
        $errors['class_id'] = "Annoucement is required";
      }
     
      if(!isset($errors) || empty($errors)){
	  $timestamp     = mysql_real_escape_string(htmlspecialchars(trim($_POST['due_date_time'])));
         $sql = sprintf("INSERT INTO annoucements(ann_title,ann_text,created_by,class_id,timestamp,created_date) VALUES('%s','%s','%s','%s','%s','%s')",
                $post_title,
				$post_content,
				$_SESSION['email'],
				$class_id,
                $timestamp,
                date("Y-m-d H:i:s")
            );
         if(mysql_query($sql)){
            $post_id = mysql_insert_id();
         }else{
            $errors['other'] = "Sorry!!! Internal Problem! Please try again later";
         }
      }

      if(isset($errors) && !empty($errors)){
         $_SESSION['flash_errors'] = $errors;
      }else{
         //$_SESSION['flash_success'] = "You have successfully created this post";
      }
      header("Location: annoucements.php");exit;
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


   if(isset($_SESSION['sid'])){

        $sql    = "SELECT * FROM `students_assigned_classes` WHERE sid='$user_id'";
       
        $result = mysql_query($sql);
        while($class = mysql_fetch_object($result)){
            $classes[$class->class_id] = $class;
        }

        $sql    = "SELECT *FROM discussions_details_with_comments_attachments as t1 
                           WHERE t1.class_id IN 
                                             ( SELECT t2.class_id FROM `students_assigned_classes` as t2
                                                WHERE t2.sid='$user_id')
                            ORDER BY t1.post_id DESC LIMIT $limit_start, $display";
        $result = mysql_query($sql);

        while($feed = mysql_fetch_object($result)){
            $feeds[] = $feed;
        }

        $sql    = "SELECT *FROM discussions_details_with_comments_attachments as t1 
                           WHERE t1.class_id IN 
                                             ( SELECT t2.class_id FROM `students_assigned_classes` as t2
                                                WHERE t2.sid='$user_id')
                            ORDER BY t1.post_id DESC";
        $result = mysql_query($sql);
        $total_results = mysql_num_rows($result);
        $total_page    = ceil($total_results/$display);
   }else{

        $sql    = "SELECT * FROM `classes` WHERE creator_tid ='".$_SESSION['tid']."'";
        //d($user,1);
        $result = mysql_query($sql);

        while($class = mysql_fetch_object($result)){
            $classes[$class->class_id] = $class;
        }
        
        $sql    = "SELECT *FROM discussions_details_with_comments_attachments
                           WHERE class_id IN ( 
                                            SELECT class_id FROM `classes` WHERE creator_tid ='".$_SESSION['tid']."'
                                             ) 
                           ORDER BY post_id DESC LIMIT $limit_start, $display";
        $result = mysql_query($sql);
        //d($sql,1);
        while($feed  = mysql_fetch_object($result)){
            $feeds[] = $feed;
        } 
        
        $sql = "SELECT *FROM discussions_details_with_comments_attachments
                           WHERE class_id IN ( 
                                            SELECT class_id FROM `classes` WHERE creator_tid ='".$_SESSION['tid']."'
                                             ) 
                           ORDER BY post_id DESC";

        $result = mysql_query($sql);
        //d($sql,1);
        $total_results = mysql_num_rows($result);
        $total_page    = ceil($total_results/$display);
   }

   
   //d($feeds,1);

?>
<!DOCTYPE html>
<html>
<head>
	<title>lurnn</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<!--[if IE]>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <![endif]-->
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
    <style>

        textarea {
            border:2px solid #ccc;
            padding: 10px;
            vertical-align: top;
        }

        .animated {
            -webkit-transition: height 0.2s;
            -moz-transition: height 0.2s;
            transition: height 0.2s;
        }

    </style>
    <!-- blueimp Gallery styles -->
    <link rel="stylesheet" href="http://blueimp.github.io/Gallery/css/blueimp-gallery.min.css">
    <!-- CSS to style the file input field as button and adjust the Bootstrap progress bars -->
    <link rel="stylesheet" href="fileupload/css/jquery.fileupload.css">
    <link rel="stylesheet" href="fileupload/css/jquery.fileupload-ui.css">
	
	<link href="css/bootstrap-datetimepicker.css" rel="stylesheet" media="screen">
	
    <!-- CSS adjustments for browsers with JavaScript disabled -->
    <noscript><link rel="stylesheet" href="fileupload/css/jquery.fileupload-noscript.css"></noscript>
    <noscript><link rel="stylesheet" href="fileupload/css/jquery.fileupload-ui-noscript.css"></noscript>
        
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
                
                <div class="row-fluid">
					<div class="pasignation" style="text-align:center;"></div>
					<?php
					//--GET ALL ANNOUCEMENTS-------------	
						$end_sql    = "SELECT annoucements.*, classes.* FROM annoucements LEFT JOIN classes ON annoucements.class_id = classes.class_id WHERE classes.grade_level='$grade_level' ORDER BY ann_id DESC";
						$end_result = mysql_query($end_sql);
						$contxx = mysql_num_rows($end_result);
						if($contxx != '0') 
						{
					?>
                    <table class="table table-hover table-responsive">
					<thead>
						<tr>
							<th>#</th>
							<th>Announcement Title</th>
							<th>Announcement Content</th>
							<th>Class Name</th>
							<th>Announcement Timestamp</th>
						</tr>
					</thead>
                    <?php 
						$i="0";
						while($ann = mysql_fetch_object($end_result))
						{ $i++;?>
						
							<tr>
								<td><?php echo $i; ?></td>
								<td>
									<a href="#" onclick="show_pops(<?php echo $ann->ann_id;?>)">
										<?php echo $ann->ann_title;?>
									</a>
								</td>
								<td><?php echo substr($ann->ann_text,0,30)."...";?></td>
								<td>
									<?php 
										$classId = $ann->class_id;
										$end_s1 = "SELECT * FROM classes WHERE class_id='$classId'";
										$end_res1 = mysql_query($end_s1);
										$ann1 = mysql_fetch_object($end_res1);
										echo $ann1->class_name;
									?>
								</td>
								<td><?php echo $ann->timestamp;?></td>
							</tr>
						<?
						}
						?>
                    
                    </table>
					<?php
					}
					else
					{
						echo "No Announcement Found.";
					}
					?>
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
	<script type="text/javascript" src="js/bootstrap-datetimepicker.min.js" charset="UTF-8"></script>
    <script type="text/javascript">
        $(function () {
            // jQuery Knobs
            $(".knob").knob();
            $('.animated').autosize({append: "\n"});
            $('.pasignation').bootstrapPaginator(options);
        });

		//--Function to show information pop-up 
		function show_pops(str)
		{
			$.ajax({
				type: "POST",
				url: "getAnnouncementInfo.php",
				data: 'str='+str,
				context: document.body
				}).done(function(result){
					//alert(result);
					//return false;
					$('#InfoByAjax').html(result);
					$('.modal-backdrop').show();
					$('#myModal_pOp').show();
				});
			
		}
		
		function HideMe()
		{
			$('.modal-backdrop').hide();
			$('#myModal_pOp').hide();
		}
    </script>
	
	<!----POP-UP WITH ANNOUNCEMENT INFORMATION---------->
	<div id="myModal_pOp" style="display:none; margin-left:28%; height:auto;">
		<form enctype="multipart/form-data" method="post" id="fileupload">
			<div class="modal-header">
				<span id="CrossME" onclick="return HideMe();" >×</span>

				<span id="InfoByAjax"></span>
			</div>
		</form>
	</div>
	<div class="modal-backdrop fade in" style="display:none;"></div>
	
</body>
</html>