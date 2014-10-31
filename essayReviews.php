<?php
    // You have to write all php code here
   require_once "config.php";
   if(isset($_SESSION['tid'])){
      $user_id = $_SESSION['tid'];
      $type    = "teacher";
      $email   = $_SESSION['email']; 
   }else if(isset($_SESSION['sid'])){
      $user_id = $_SESSION['sid'];
      $type    = "student";
      $email   = $_SESSION['email'];
   }else{
        echo "<script>window.location='/';</script>";
        exit;
   }
   if(!isset($_GET['id']) || is_int($_GET['id'])){
        exit("Page Not Found");
   }

	$post_id  = $_GET['id'];
	
	if((isset($_SESSION['tid']) || isset($_SESSION['sid'])) && isset($_POST['submit']))
	{
		//echo "<pre>"; print_r($_POST);die();
		$essay_id     = $post_id;
		$comment      = $_POST['comments'];
		$comment_by   = $_SESSION['email'];
		$user_id      = $_SESSION['tid'];
		$grade        = $_POST['grades'];
		$post_sid     = $_POST['student_id'];
		$created_date = date("Y-m-d H:i:s");
	
	//--CHECK COMMENT IS ALREADY OR NOT------------------------
		$sqlA = "SELECT * FROM `essay_comments` WHERE essay_id ='".$post_id."' && for_student_id = '$post_sid'";
		$resultA = mysql_query($sqlA);
		$countA  = mysql_num_rows($resultA);
		
		if($countA == "0")
		{
			$sql = sprintf("INSERT INTO essay_comments(essay_id,comment,commented_by,for_student_id,user_id,type,grade,created_date) VALUES('$essay_id','$comment','$comment_by','$post_sid','$user_id','teacher','$grade','$created_date')");
			
			mysql_query($sql);
		}	
		//echo "<script>window.location='create-essay.php';</script>"; exit;
    }
	
	
	//--Get essay Prompt----------------------------------
	$sqlP = "SELECT * FROM `essays` WHERE essay_id  ='".$post_id."'";
	$resultP = mysql_query($sqlP);
	$feed_pro  = mysql_fetch_object($resultP);
	//echo "<pre>"; print_r($feed_pro);
	
	//--Get essay by Student----------------------------------
	$sqlP1 = "SELECT * FROM `essay_write_by_student` WHERE on_essay_id  ='".$post_id."'";
	$resultP1 = mysql_query($sqlP1);
	$feed_pro1  = mysql_fetch_object($resultP1);
	//echo "<pre>"; print_r($feed_pro1); 
	
	//--Get Student Details-----------------------------------
	$post_by = $feed_pro1->user_id;
		$sqlP2 = "SELECT * FROM `students` WHERE sid  ='".$post_by."'";
		$resultP2 = mysql_query($sqlP2);
		$feed_pro2  = mysql_fetch_object($resultP2);
	
	
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
    <link href="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap-editable/css/bootstrap-editable.css" rel="stylesheet"/>
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
            <?php
                if(isset($error)){
                    ?>
                    <div class="alert alert-danger">
                    <button data-dismiss="alert" class="close" type="button">×</button>
                    <?php
                        echo $error;
                    ?>
                    </div>
                    <?php
                }   
            ?>

            <div id="pad-wrapper">
                <!-- statistics chart built with jQuery Flot -->
                <div class="row-fluid">
                    <div class="media" id="post_<?php echo $feed_pro1->on_essay_id?>">
                      <a class="pull-left" href="#">
                        <img src="uploads/<?php echo $feed_pro2->profile_picture?>" style="width: 64px; height: 64px;" alt="64x64" class="media-object" data-src="holder.js/64x64">
                      </a>
                      <div class="media-body">
                        <p class="media-heading"><strong><?php echo $feed_pro2->fname?> <?php echo $feed_pro2->lname?></strong> submitted an essay <strong><?php echo time_elapsed_string(strtotime($feed_pro->created_date));?></strong>
                          <?php/*
                          if($type=="teacher" || ($user_id==$feed->user_id && $email==$feed->email && $type=="student")){
                            ?>
                            &nbsp; &nbsp; <a class="btn btn-info" data-backdrop="static" data-toggle="modal" data-target="#discussion_edit">Edit</a>
                            <a class="btn btn-danger" href="#" onclick="deletePost('<?php echo $feed->post_id;?>');return false;">Delete</a>
                            <?php
                          }*/
                          ?>
                        </p>
                        
                        <div class="media">
                          <div class="media-body span11" style="padding-left: 30px;">
							<h3><?php echo $feed_pro->essay_title; ?></h3> <br/>
                            <p>
								<?php if($feed_pro1->has_attachment == 1){ ?>
                               <a class="btn btn-small btn-primary pull-right" href="download_essay.php?id=<?php echo $feed_pro1->on_essay_id;?>" title="Click here to download" style="margin-right:7px;">
							   <?php 
									$sqlCNT = "SELECT * FROM `essay_attachment` WHERE student_essay_id  ='".$feed_pro1->on_essay_id."'";
									$resultCNT = mysql_query($sqlCNT);
									$countCNT = mysql_num_rows($resultCNT);
									if($countCNT > '1') { echo $countCNT." Attachments"; }
									else { echo $countCNT." Attachment"; }
								?>
								</a>
                               <?php 
							   } else {} ?>
                          </div>
                        </div>
                        <?php 
                            $sqlP = "SELECT * FROM `essay_write_by_student` WHERE on_essay_id  ='".$feed_pro1->on_essay_id."'";
							$resultP = mysql_query($sqlP);
							$pro_countz = mysql_num_rows($resultP);
							if($pro_countz != "0") 
							{ 
								while($feed_pro1  = mysql_fetch_object($resultP))
									{
										$feed_pros[] = $feed_pro1; 
									}
								
								foreach($feed_pros as $feed_pro)
								{
                                ?>
								<div class="prompt_show_content">
									<?php 
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
									
								</div>
							<span style="float: left; clear: both; width: 100%; margin: 0px 0px 0px 72px;">
								<?php
									$eID = $_SESSION['email'];
									$end_sql = "SELECT * FROM essay_comments WHERE essay_id = '$post_id' && commented_by = '$eID' && for_student_id = '$pro_u_id'";
									$end_result = mysql_query($end_sql);
									$end_id = mysql_fetch_object($end_result);
									if($end_id == '0') {
								?>					
								
								<form class="row-fluid" method="post" enctype="multipart/form-data">
								
									<div class="control-group">
										<input type="hidden" name="student_id" value="<?php echo $pro_u_id; ?>"/>
										<div class="controls" style="margin-left:30px;margin-top:30px;">
											<div class="media">
											  <div class="media-body">
												<textarea name="comments" rows="1" required="required" class="animated span10" placeholder="Write Your Comment"></textarea>
												<br/>
												Grades out of 10:
												<select name="grades" style="padding:0">
													<option value="1">1</option>
													<option value="2">2</option>
													<option value="3">3</option>
													<option value="4">4</option>
													<option value="5">5</option>
													<option value="6">6</option>
													<option value="7">7</option>
													<option value="8">8</option>
													<option value="9">9</option>
													<option value="10">10</option>
												</select>
												<button type="submit" name="submit" class="btn btn-primary">Post Comment</button>
											  </div>
											</div>
											
										</div>
									</div>
								</form>
								<?php } 
								else
								{ ?>
								<div class="prompt_show_content">
								<?php 
									$post_id;
									$sqlxx = "SELECT * FROM `essay_comments` WHERE essay_id   ='".$post_id."' && for_student_id = '$pro_u_id' ";
									$resultxx = mysql_query($sqlxx);
									$xx  = mysql_fetch_object($resultxx);
									$teID = $xx->user_id;
									//--GEt TEACHER DETAILS---------------------
									$sqlxz = "SELECT * FROM `teachers` WHERE tid ='".$teID."'";
									$resultxz = mysql_query($sqlxz);
									$xz  = mysql_fetch_object($resultxz);
								?>
								<a class="pull-left" href="#" style="margin: 0 10px 0 6px">
								<img src="uploads/<?php echo $xz->profile_picture?>" style="width: 64px; height: 64px;" alt="64x64" class="media-object" data-src="holder.js/64x64">
							  </a>
								<p class="media-heading"><strong><?php echo $xz->fname." ".$xz->lname;?></strong> commented on this essay <strong><?php echo time_elapsed_string(strtotime($xx->created_date));?></strong></p>
								<p>
									<strong>
										Grade: <?php echo $xx->grade; ?>
									</strong>
								</p>
								<p>
									<?php 
										echo $content = $xx->comment;	
									?>
								</p>
							</div>
								<?php } ?>
						</span>
                                <?
                               }
                            }    
                        ?>
                      </div>
                    </div>
          
                   
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
    <script src="js/bootbox.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap-editable/js/bootstrap-editable.min.js"></script>
   
    <script type="text/javascript">
        $(function () {

            // jQuery Knobs
            $(".knob").knob();
            $('.animated').autosize({append: "\n"});
        });
        function deleteAttachment(attachment_id){
            bootbox.confirm("Are you sure to delete this Attachment?", function(result) {
                if(result==false) return;
                $.ajax({
                    type: "POST",
                    url: "discussion.ajax.php",
                    data: { action: "delete_attachment", attachment_id: attachment_id }
                })
                .done(function( msg ) {
                  if(msg=="yes"){
                      bootbox.alert("<h3 class='text-success'>You have successfully deleted this Attachment.</h3>");
                      $("#attachment_"+attachment_id).hide();
                   }else{
                      bootbox.alert("<h3 class='text-danger'>Sorry!! we could not delete this attachment.Please try again later.</h3>");
                   }
                });
               
          }); 
            
            
        }
        function deletePost(post_id){
             bootbox.confirm("Are you sure to delete this post?", function(result) {
                if(result==false) return;

                $.ajax({
                    type: "POST",
                    url: "discussion.ajax.php",
                    data: { action: "delete_post", post_id: post_id }
                })
                .done(function( msg ) {
                   
                   if(msg=="yes"){
                      bootbox.alert("<h3 class='text-success'>You have successfully deleted this post.</h3>");
                      window.location="discussionboard.php";
                   }else{
                      bootbox.alert("<h3 class='text-danger'>Sorry!! we could not delete this post.Please try again later.</h3>");
                   }
                });
                
          }); 
            
        }

        
        function editComment(id){
            $('#comment_'+id).editable({
              mode:"popup",
              validate: function(value) {
                if($.trim(value) == '') {
                  return 'This field is required';
                }
              },
              onblur: "submit",
              success: function(response, newValue) {
                //console.log(response);
                //console.log(response.status);
                //console.log(response.msg);
                if(response.status == 'error') return response.msg; //msg will be shown in editable form
                $('#comment_'+id).editable("disable");
              },
              error: function(response, newValue) {
                $('#comment_'+id).editable("disable");
              }
            });

            $('#comment_'+id).editable("enable");
        }
        function deleteComment(comment_id){
             bootbox.confirm("Are you sure to delete this comment?", function(result) {
                if(result==false) return;

                $.ajax({
                    type: "POST",
                    url: "discussion.ajax.php",
                    data: { action: "delete_comment", comment_id: comment_id }
                })
                .done(function( msg ) {
                   
                   if(msg=="yes"){
                      //bootbox.alert("<h3 class='text-success'>You have successfully deleted this comment.</h3>");
                      $("#commentbox_"+comment_id).hide();
                      console.log(comment_id);
                   }else{
                      bootbox.alert("<h3 class='text-danger'>Sorry!! we could not delete this comment.Please try again later.</h3>");
                   }
                });
                
          }); 
            
        }
    </script>
</body>
</html>