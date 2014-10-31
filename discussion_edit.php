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
        header("Location: /");
        exit;
   }
   
   
   if((isset($_SESSION['tid']) || isset($_SESSION['sid'])) && isset($_POST['submit'])){
      //d($_POST,1);
   	  if(isset($_POST['post_id']) && $_POST['post_id']!=""){
        $post_id = mysql_real_escape_string(htmlspecialchars(trim($_POST['post_id'])));
      }else{
        $errors['post_id'] = "You are not allowed!";
      }

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
         $sql = sprintf("UPDATE discussions SET class_id='%d',
         	                                    post_title='%s',
         	                                    post_content='%s',
         	                                    skills='%s',
         	                                    created_date='%s'
         	                                WHERE posted_by='%s' 
         	                                AND user_id='%s'
         	                                AND type='%s'
         	                                AND post_id='%s'",
	                $class_id,
	                $post_title,
	                $post_content,
	                isset($skills)? $skills : "",
	                date("Y-m-d H:i:s"),
	                $email,
	                $user_id,
	                $type,
	                $post_id
	            );
         //d($sql);
           
         if(!mysql_query($sql)){
            $errors['other'] = "Sorry!!! Internal Problem! Please try again later";
         }
          echo mysql_error();
          if(isset($attachments) && is_array($attachments) && !empty($attachments)){
                foreach ($attachments as $attachment) {
                    $sql = sprintf("INSERT INTO discussions_attachment(post_id, file_path,file_type) VALUES('%d','%s','%s')",
                            $post_id,
                            $attachment,
                            'other'
                        );
                    //d($sql);
                    mysql_query($sql);// or die(mysql_error());
                }

                @session_regenerate_id();
            }
      }

      if(isset($errors) && !empty($errors)){
         //$_SESSION['flash_errors'] = $errors;
      }else{
         //$_SESSION['flash_success'] = "You have successfully created this post";
      }

      //d($errors,1);
      header("Location: discussiondetails.php?id=".$_POST['post_id']);exit;
   }


  
   
   //d($feeds,1);
   ?>