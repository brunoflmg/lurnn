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
   
   
   if((isset($_SESSION['tid']) || isset($_SESSION['sid'])) && isset($_POST['submit'])){
      if(isset($_POST['post_title']) && $_POST['post_title']!=""){
        $post_title = mysql_real_escape_string(htmlspecialchars(trim($_POST['post_title'])));
      }else{
        $errors['post_title'] = "Post Title is required";
      }
      if(isset($_POST['post_content']) && $_POST['post_content']!=""){
        $post_content = mysql_real_escape_string(htmlspecialchars(trim($_POST['post_content'])));
      }else{
        $errors['post_content'] = "Post Description is required";
      }
      if(isset($_POST['skills']) && $_POST['skills']!=""){
        $skills = mysql_real_escape_string(trim($_POST['skills']));
      }
      if(isset($_POST['class_id']) && $_POST['class_id']!=""){
        $class_id = mysql_real_escape_string(trim($_POST['class_id']));
      }else{
        $errors['class_id'] = "Class is required";
      }
      if(isset($_POST['attachments']) && !empty($_POST['attachments'])){
        $attachments = $_POST['attachments'];
      }
      if(!isset($errors) || empty($errors)){
         $sql = sprintf("INSERT INTO discussions(class_id,posted_by,user_id,type,post_title,post_content,skills,has_attachment,created_date) VALUES('%d','%s','%d','%s','%s','%s','%s','%s','%s')",
                $class_id,
                $_SESSION['email'],
                $user_id,
                $type,
                $post_title,
                $post_content,
                isset($skills)? $skills : "",
                isset($attachments)? TRUE : FALSE,
                date("Y-m-d H:i:s")
            );
         if(mysql_query($sql)){
            $post_id = mysql_insert_id();
            if(isset($attachments) && is_array($attachments) && !empty($attachments)){
                foreach ($attachments as $attachment) {
                    $sql = sprintf("INSERT INTO discussions_attachment(post_id, file_path,file_type) VALUES('%d','%s','%s')",
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
      header("Location: discussionboard.php");exit;
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
                    <p class="span2 offset9"><a href="#myModal" data-backdrop="static" role="button" class="btn btn-primary" data-toggle="modal">Create New Post</a></p>
                </div>
                <div class="row-fluid">
                   
                   <div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <form  id="fileupload" method="post" enctype="multipart/form-data">
                    <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3 id="myModalLabel">Create New Post</h3>
                    </div>
                    <div class="modal-body">
                   
                        <div class="control-group">
                            <!--<label class="control-label" for="inputEmail">Subject</label>-->
                            <div class="controls">
                                <input type="text" name="post_title" required="required" class="span12" placeholder="Type Your Post Title">
                            </div>
                        </div>
                        <div class="control-group">
                            <!--<label class="control-label" for="input">Description</label>-->
                            <div class="controls">
                                <textarea name="post_content" required="required" class="animated span12" placeholder="Write Your Post Details"></textarea>
                            </div>
                        </div>
                        <div class="control-group" style="height:70px;">
                            
                            <div class="controls">
                               <?php if(isset($_SESSION['tid'])){ ?>
                                <div class="span6">
                                    <label>Skills (Comma Separated):</label>
                                    <input type="text" name="skills" required="required" class="span12" />

                                </div>
                                <?php } ?>
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
                        <table role="presentation" class="table table-striped"><tbody class="files"></tbody></table>       
                       
                    
                     </div>
                    <div class="modal-footer">
                    <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
                     <button type="submit" name="submit" class="btn btn-primary">Create New Post</button>
                    </div>
                    </form>
                    </div>
                     <div class="pasignation" style="text-align:center;"></div>
                    
                    <?php if(is_array($feeds) && !empty($feeds)){ 
                       foreach ($feeds as $feed) {
                          
                     ?>
                    
                    <div class="media">
                      <a class="pull-left" href="#">
                        <img src="uploads/<?php echo $feed->profile_picture?>" style="width: 64px; height: 64px;" alt="64x64" class="media-object" data-src="holder.js/64x64">
                      </a>
                      <div class="media-body">
                        <p class="media-heading"><strong><?php echo $feed->fname?> <?php echo $feed->lname?></strong> created a post in <strong><?php echo $feed->class_name?> ( <?php echo $feed->class_code?> )</strong></p>
                        
                        <div class="media">
                          <div class="media-body span11" style="padding-left: 30px;">
                            <h3 class="media-heading"><?php echo $feed->post_title;?></h3>
                            <p style="text-align:justify;"><?php if(strlen($feed->post_content) >420 ){echo mb_substr(nl2br($feed->post_content),0,420)."..."; }else{echo nl2br($feed->post_content);}?></p>
                           
                            <p><strong class="pull-left"><?php echo time_elapsed_string(strtotime($feed->created_date)); ?></strong>
                               <a class="btn btn-small pull-right" href="discussiondetails.php?id=<?php echo $feed->post_id;?>">Add Comments</button></a>
                               <p class="pull-right"> &nbsp; </p>
                               <?php if($feed->total_attachments == 1){ ?>
                               <a class="btn btn-small btn-primary pull-right" href="download.php?id=<?php echo $feed->post_id;?>" title="Click here to download"><?php echo $feed->total_attachments;?> Attachment</a>
                               <?php }elseif($feed->total_attachments > 1 ){
                                ?>
                                 <a class="btn btn-small btn-primary pull-right" href="download.php?id=<?php echo $feed->post_id;?>" title="Click here to download"><?php echo $feed->total_attachments;?> Attachments</a>
                                <?php
                                }else{} ?>
                               <p class="pull-right"> &nbsp; </p>
                               <?php if($feed->total_comments == 1) { ?>
                               <a class="btn btn-small btn-primary pull-right" href="discussiondetails.php?id=<?php echo $feed->post_id;?>#comments"><?php echo $feed->total_comments;?> Comment</a>
                               <?php }elseif($feed->total_comments > 1 ){
                                ?>
                                 <a class="btn btn-small btn-primary pull-right" href="discussiondetails.php?id=<?php echo $feed->post_id;?>#comments" ><?php echo $feed->total_comments;?> Comments</a>
                                <?php
                                }else{} ?>
                               
                            </p>
                          </div>
                        </div>
                      </div>
                    </div>
          
                   <?php } } ?>
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
</body>
</html>