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
   
   $essay_IDZ = $_GET['id']; 
   
   if((isset($_SESSION['tid']) || isset($_SESSION['sid'])) && isset($_POST['submit'])){
      if(isset($_POST['post_content']) && $_POST['post_content']!=""){
        $post_content = mysql_real_escape_string(htmlspecialchars(trim($_POST['post_content'])));
      }else{
        $errors['post_content'] = "Essay Information is required";
      }
      
	  if(isset($_POST['attachments']) && !empty($_POST['attachments'])){
        $attachments = $_POST['attachments'];
      }

	  /*echo "<pre>"; print_r($_POST);
	  echo "<pre>"; print_r($_SESSION);
	  die();*/
      if(!isset($errors) || empty($errors)){
		$class_id       = $_POST['pr_essay_id'];
		$post_by        = $_POST['pr_comment_by'];
		$user_id        = $_POST['pr_user_id'];
		$essay_content  = $_POST['post_content'];;
		$has_attachment = isset($attachments)? TRUE : FALSE;
		$created_date   = date("Y-m-d H:i:s");
		
       $sql = sprintf("INSERT INTO essay_write_by_student(on_essay_id,post_by,user_id,essay_content,has_attachment,created_date) VALUES('$class_id','$post_by','$user_id','$essay_content','$has_attachment','$created_date')");
        
		if(mysql_query($sql)){
            $post_id = mysql_insert_id();
            if(isset($attachments) && is_array($attachments) && !empty($attachments)){
                foreach ($attachments as $attachment) {
                    $sql = sprintf("INSERT INTO essay_attachment(student_essay_id, file_path,file_type) VALUES('%d','%s','%s')",
                            $post_id,
                            $attachment,
                            'other'
                        );
                    mysql_query($sql);
                }
                
                @session_regenerate_id();

            }
         }else{
            $errors['other'] = "Sorry!!! Internal Problem! Please try again later";
         }
      }
 
	if(isset($errors) && !empty($errors)){
         $_SESSION['flash_errors'] = $errors;
      }else{
         //$_SESSION['flash_success'] = "You have successfully created this post";
      }
      //header("Location: essay_student.php");exit;
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

       $sql    = "SELECT *FROM essay_details_with_comments_attachments as t1 
                           WHERE t1.class_id IN 
                                             ( SELECT t2.class_id FROM `students_assigned_classes` as t2
                                                WHERE t2.sid='$user_id') and t1.essay_id ='$essay_IDZ'
                            ORDER BY t1.essay_id DESC LIMIT $limit_start, $display";
        $result = mysql_query($sql);

        while($feed = mysql_fetch_object($result))
		{
            $feeds[] = $feed;
        }


        $sql    = "SELECT *FROM essay_details_with_comments_attachments as t1 
                           WHERE t1.class_id IN 
                                             ( SELECT t2.class_id FROM `students_assigned_classes` as t2
                                                WHERE t2.sid='$user_id') and t1.essay_id ='$essay_IDZ'
                            ORDER BY t1.essay_id DESC";
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
        
        $sql    = "SELECT *FROM essay_details_with_comments_attachments
                           WHERE class_id IN ( 
                                            SELECT class_id FROM `classes` WHERE creator_tid ='".$_SESSION['tid']."'
                                             ) 
                           ORDER BY essay_id DESC LIMIT $limit_start, $display";
        $result = mysql_query($sql);
        //d($sql,1);
        while($feed  = mysql_fetch_object($result)){
            $feeds[] = $feed;
        } 
        
        $sql = "SELECT *FROM essay_details_with_comments_attachments
                           WHERE class_id IN ( 
                                            SELECT class_id FROM `classes` WHERE creator_tid ='".$_SESSION['tid']."'
                                             ) 
                           ORDER BY essay_id DESC";

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
    <!-- CSS adjustments for browsers with JavaScript disabled -->
    <noscript><link rel="stylesheet" href="fileupload/css/jquery.fileupload-noscript.css"></noscript>
    <noscript><link rel="stylesheet" href="fileupload/css/jquery.fileupload-ui-noscript.css"></noscript>
    
	<script type="text/javascript">
		function open_prompt_form(str)
		{
			$('.add_prompt').hide();
			$('#main_table #in_tbody').html('');
			$('#prompt_form_'+str).show();
		}
		
		function close_ME(str)
		{
			$('#prompt_form_'+str).hide();
		}
		
//-- hide prompt button-------------
		
		function hide_button(str)
		{
			$('#add_prompt_btn'+str).hide();
			return true;
		}
		
	</script>
    
</head>
<body>

<?php
	
//---ADD PROMPT--------------------------------------------------------------
		if((isset($_SESSION['tid']) || isset($_SESSION['sid'])) && isset($_POST['submit_prompt']))
		{
			//echo "<pre>"; print_r($_POST); 
			$pr_essay_id = $_POST['pr_comment_id']; $prompt_content = $_POST['prompt_content']; $pr_comment_by = $_POST['pr_comment_by']; $pr_user_id = $_POST['pr_user_id']; $created_date = date("Y-m-d H:i:s"); 
		
		// CHECK VALUE IS ALREADY IN DATABSE OR NOT---------------------------
			$sqlCK = "SELECT * FROM `essay_comments` WHERE essay_id  ='$pr_essay_id' && user_id='$pr_user_id'";
			$resultCK = mysql_query($sqlCK);
			$pro_CK = mysql_num_rows($resultCK);
			if($pro_CK == '0')
			{
				$sqlxz = sprintf("INSERT INTO essay_comments(comment_id,essay_id,comment,commented_by,user_id,type,created_date) VALUES('','$pr_essay_id','$prompt_content','$pr_comment_by','$pr_user_id','teacher','$created_date')");
				mysql_query($sqlxz);
			}
		}
	
	
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
                <div class="row-fluid">
                    <div class="pasignation" style="text-align:center;"></div>
                    
                    <?php if(is_array($feeds) && !empty($feeds)){ 
                       foreach ($feeds as $feed) {
				//echo "<pre>"; print_r($feed);		   
                     ?>
                    
                    <div class="media" style="clear:both;">
                      <a class="pull-left" href="#">
                        <img src="uploads/<?php echo $feed->profile_picture?>" style="width: 64px; height: 64px;" alt="64x64" class="media-object" data-src="holder.js/64x64">
                      </a>
                      <div class="media-body">
                        <p class="media-heading"><strong><?php echo $feed->fname?> <?php echo $feed->lname?></strong> created a essay in <strong><?php echo $feed->class_name?> 
						<?php if($feed->class_code) { 
							echo " (".$feed->class_code.")"; 
						} ?> </strong> 
						<?php echo "&nbsp;".time_elapsed_string(strtotime($feed->created_date)); ?>
						</p>
                        
                        <div class="media">
                          <div class="media-body span11" style="padding-left: 30px;">
                            <h3 class="media-heading">
								<?php echo $feed->essay_title;?>
							</h3>
                           <p style="text-align:justify;">
								<?php 
									if(strlen($feed->essay_content) >420 ) 
									{ 
										echo mb_substr(nl2br($feed->essay_content),0,420)."..."; 
									} 
									else 
									{ 
										echo nl2br($feed->essay_content); 
									} 
								?>
							</p>
                            <p>
							<?php
								$sqlP = "SELECT * FROM `essay_write_by_student` WHERE on_essay_id  ='".$feed->essay_id."' && user_id = '$user_id'";
								$resultP = mysql_query($sqlP);
								$pro_countz = mysql_num_rows($resultP);
								if($pro_countz == "0")
								{
							?>
							<span class="btn btn-small pull-right" id="add_prompt_btn<?php echo $feed->essay_id; ?>" onclick="open_prompt_form(<?php echo $feed->essay_id; ?>);">
								Create Essay
							</span>
							<?php } ?>
                            <?/*   <p class="pull-right"> &nbsp; </p>
                               <?php if($feed->total_attachments == 1){ ?>
                               <a class="btn btn-small btn-primary pull-right" href="download_essay.php?id=<?php echo $feed->essay_id;?>" title="Click here to download"><?php echo $feed->total_attachments;?> Attachment</a>
                               <?php }elseif($feed->total_attachments > 1 ){
                                ?>
                                 <a class="btn btn-small btn-primary pull-right" href="download_essay.php?id=<?php echo $feed->essay_id;?>" title="Click here to download"><?php echo $feed->total_attachments;?> Attachments</a>
                                <?php
                                }else{} ?>
                               <p class="pull-right"> &nbsp; </p>
                               <?php if($feed->total_comments == 1) { ?>
                               <a class="btn btn-small btn-primary pull-right" href="discussiondetails.php?id=<?php echo $feed->essay_id;?>#comments"><?php echo $feed->total_comments;?> Comment</a>
                               <?php }elseif($feed->total_comments > 1 ){
                                ?>
                                 <a class="btn btn-small btn-primary pull-right" href="discussiondetails.php?id=<?php echo $feed->essay_id;?>#comments" ><?php echo $feed->total_comments;?> Comments</a>
                                <?php
                                }else{} ?>
                               
                            </p>
						*/?>	
                          </div>
                        </div>
                      </div>
                    </div>
					
					<div class="add_prompt" id="prompt_form_<?php echo $feed->essay_id; ?>" style="float:left; width:100%; margin:15px 0 15px 0; display:none; border:1px solid #a3a3a3;">
					
					<form  id="fileupload" method="post" enctype="multipart/form-data">
                    <div class="modal-header">
                    <a class="closeMe" onclick="close_ME(<?php echo $feed->essay_id; ?>)" style="float: right; color: #555; font-size: 20px; cursor:pointer;">×</a>
                    <h3 id="myModalLabel">Create Essay</h3>
                    </div>
                    <div class="modal-body">
                        <div class="control-group">
                            <!--<label class="control-label" for="input">Description</label>-->
							<input type="hidden" name="pr_essay_id" class="pr_essay_id" value="<?php echo $feed->essay_id; ?>"/>
							<input type="hidden" name="pr_comment_by" class="pr_comment_by" value="<?php echo $_SESSION['email']; ?>"/>
							<input type="hidden" name="pr_user_id" class="pr_comment_by" value="<?php echo $_SESSION['sid']; ?>"/>

                            <div class="controls">
                                <textarea name="post_content" required='required' class="animated span12" placeholder="Write Your Essay Here"></textarea>
                            </div>
                        </div>
                        <h3 id="myModalLabel">Or Upload Essay</h3><br/>
						<div class="control-group fileupload-buttonbar">
                            <div class="span7">
                                <!-- The fileinput-button span is used to style the file input field as button -->
                                <span class="btn btn-success fileinput-button">
                                    <i class="glyphicon glyphicon-plus"></i>
                                    <span>Add Attachment</span>
                                    <input type="file" name="files[]" multiple>
                                </span>
                                <button type="submit" class="btn btn-primary start">
                                    <i class="glyphicon glyphicon-upload"></i>
                                    <span>Start Upload</span>
                                </button>
                                
                                <!-- The global file processing state -->
                                <span class="fileupload-process"></span>
                            </div>
                            <!-- The global progress state -->
                            <div class="span5 fileupload-progress fade">
                                <!-- The global progress bar -->
                                <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100">
                                    <div class="progress-bar progress-bar-success" style="width:0%;"></div>
                                </div>
                                <!-- The extended global progress state -->
                                <div class="progress-extended">&nbsp;</div>
                            </div>
                        </div>
                        <!-- The table listing the files available for upload/download -->
                        <table role="presentation" class="table table-striped" id="main_table"><tbody id="in_tbody" class="files"></tbody></table>       
                     </div>
                    <div class="modal-footer">
                     <button type="submit" name="submit" class="btn btn-primary">Create Essay</button>
                    </div>
                    </form>
					</div>	
					<?php 
					$sqlP = "SELECT * FROM `essay_write_by_student` WHERE on_essay_id  ='".$feed->essay_id."' && user_id = '$user_id'";
					$resultP = mysql_query($sqlP);
					$pro_countz = mysql_num_rows($resultP);
					if($pro_countz == "1") 
					{ 
					?>
					<div class="prompt_show_content">
						<?php 
							$feed_pro  = mysql_fetch_object($resultP);
							//echo "<pre>"; print_r($feed_pro);
						//--get teacher detail---------------------	
							$pro_u_id = $feed_pro->user_id;
							$sqlPRO = "SELECT * FROM `students` WHERE sid  ='".$pro_u_id."'";
							$resultPRO = mysql_query($sqlPRO);
							$teachr_pro  = mysql_fetch_object($resultPRO);
						?>
						<a class="pull-left" href="#" style="margin: 0 10px 0 6px">
                        <img src="uploads/<?php echo $teachr_pro->profile_picture?>" style="width: 64px; height: 64px;" alt="64x64" class="media-object" data-src="holder.js/64x64">
                      </a>
						<p class="media-heading"><strong><?php echo $teachr_pro->fname." ".$teachr_pro->lname;?></strong> submitted an essay <strong><?php echo time_elapsed_string(strtotime($feed_pro->created_date));?></strong></p>
						<p>
							<?php 
								echo $content = $feed_pro->essay_content;	
							?>
						</p>
						<p class="pull-right"> &nbsp; </p>
                               <?php if($feed_pro->has_attachment == 1){ ?>
                               <a class="btn btn-small btn-primary pull-right" href="download_essay.php?id=<?php echo $feed_pro->on_essay_id;?>" title="Click here to download" style="margin-right:7px;">
							   <?php 
									$sqlCNT = "SELECT * FROM `essay_attachment` WHERE student_essay_id  ='".$feed_pro->on_essay_id."'";
									$resultCNT = mysql_query($sqlCNT);
									$countCNT = mysql_num_rows($resultCNT);
									if($countCNT > '1') { echo $countCNT." Attachments"; }
									else { echo $countCNT." Attachment"; }
								?>
								</a>
                               <?php 
							   } else {} ?>
					   <p class="pull-right"> &nbsp; </p>
					</div>
                   <?php } ?>
				   
					<?php 
					$sqlP = "SELECT * FROM `essay_comments` WHERE essay_id  ='".$feed->essay_id."' && for_student_id='$user_id'";
					$resultP = mysql_query($sqlP);
					$pro_countz = mysql_num_rows($resultP);
					if($pro_countz != "0") { 
					?>
					<div class="prompt_show_content">
						<?php 
							$feed_pro  = mysql_fetch_object($resultP);
							//echo "<pre>"; print_r($feed_pro);
						//--get teacher detail---------------------	
							$pro_u_id = $feed_pro->user_id;
							$sqlPRO = "SELECT * FROM `teachers` WHERE tid  ='".$pro_u_id."'";
							$resultPRO = mysql_query($sqlPRO);
							$teachr_pro  = mysql_fetch_object($resultPRO);
						?>
						<a class="pull-left" href="#" style="margin: 0 10px 0 6px">
							<img src="uploads/<?php echo $teachr_pro->profile_picture?>" style="width: 64px; height: 64px;" alt="64x64" class="media-object" data-src="holder.js/64x64">
						</a>
						<p class="media-heading"><strong><?php echo $teachr_pro->fname." ".$teachr_pro->lname;?></strong> commented on this essay <strong><?php echo time_elapsed_string(strtotime($feed_pro->created_date));?></strong></p>
						<p>
							<strong>
								Grade: <?php echo $feed_pro->grade;	?>
							</strong>
						</p>
						<p>
							<?php echo $feed_pro->comment;	?>
						</p>
					</div>
                   <?php } } } ?>
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

                    return "essay_student.php?page="+page;

                }
            }

            $('.pasignation').bootstrapPaginator(options);
        });
    </script>
</body>
</html>