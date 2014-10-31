<?php
    require_once "config.php";
    if(!isset($_SESSION['tid']) && !isset($_SESSION['sid'])){
      exit("no");
    }
  
    $action     = $_REQUEST["action"]; 
    $user_id    = isset($_SESSION['tid'])? $_SESSION['tid']:$_SESSION['sid'];
    $type       = isset($_SESSION['tid'])? "teacher":"student";
    $email      = $_SESSION['email'];

    switch($action){


      
      case "delete_post":
        $post_id = intval($_POST['post_id']);
        if($type=="teacher"){
           $sql = sprintf("DELETE FROM discussions WHERE post_id='%d'",$post_id);
        }else{
           $sql = sprintf("DELETE FROM discussions WHERE post_id='%d' AND user_id='%d' AND posted_by='%s' AND type='student'",$post_id,$user_id,$email);
        }
       
        if(mysql_query($sql)){
          exit("yes");
        }else{
           exit("no");
        }
        break;
      
      case "delete_comment":
        $comment_id = intval($_POST['comment_id']);
        if($type=="teacher"){
           $sql = sprintf("DELETE FROM discussions_comments WHERE comment_id='%d'",$comment_id);
        }else{
           $sql = sprintf("DELETE FROM discussions_comments WHERE comment_id='%d' AND user_id='%d' AND commented_by='%s' AND type='student'",$comment_id,$user_id,$email);
        }
       
        if(mysql_query($sql)){
          exit("yes");
        }else{
           exit("no");
        }
        break;
      case "delete_attachment":
        $attachment_id = intval($_POST['attachment_id']);

        if($type=="teacher"){
           

           $sql = sprintf("DELETE FROM discussions_attachment WHERE attachment_id='%d'",$attachment_id);

        }else{

           $sql = sprintf("DELETE FROM discussions_attachment WHERE attachment_id='%d'",$attachment_id);
        }
       
        if(mysql_query($sql)){
          exit("yes");
        }else{
           exit("no");
        }
        break;
      case "edit_post":
        header('Content-Type: application/json');
        $response["status"] = "success";
        $post_id     = intval($_POST['pk']);
        $field_name  = mysql_real_escape_string(trim($_POST['name']));
        $field_value = mysql_real_escape_string(trim($_POST['value']));

        if($field_value==""){
           $response["status"] = "error";
           $response["msg"]    = "This field is required";
           exit(json_encode($response));
        }

        if($type=="teacher"){
           //$sql = sprintf("DELETE FROM discussions_comments WHERE comment_id='%d'",$comment_id);
           $sql = "UPDATE discussions SET $field_name='$field_value' WHERE post_id='$post_id'";
        }else{
           //$sql = sprintf("DELETE FROM discussions_comments WHERE comment_id='%d' AND user_id='%d AN'D commented_by='%s'",$comment_id,$user_id,$email);
           $sql = "UPDATE discussions SET $field_name='$field_value' WHERE post_id='$post_id' AND user_id='$user_id' AND commented_by='$email'  AND type='$type'";
        }
        
        if(!mysql_query($sql)){
           $response["status"] = "error";
           $response["msg"]    = "Sorry!! Internal Problem, Please try again later";
           exit(json_encode($response));
        }
        exit(json_encode($response));
        break;
     
       case "edit_comment":
        header('Content-Type: application/json');
        $response["status"] = "success";
        $comment_id     = intval($_POST['pk']);
        $field_name  = mysql_real_escape_string(trim($_POST['name']));
        $field_value = mysql_real_escape_string(trim($_POST['value']));

        if($field_value==""){
           $response["status"] = "error";
           $response["msg"]    = "This field is required";
           exit(json_encode($response));
        }

        if($type=="teacher"){
           $sql = "UPDATE discussions_comments SET $field_name='$field_value' WHERE comment_id='$comment_id'";
        }else{
           $sql = "UPDATE discussions_comments SET $field_name='$field_value' WHERE comment_id='$comment_id' AND user_id='$user_id' AND commented_by='$email' AND type='$type'";
        }
        
        if(!mysql_query($sql)){
           $response["status"] = "error";
           $response["msg"]    = "Sorry!! Internal Problem, Please try again later";
           exit(json_encode($response));
        }
        exit(json_encode($response));
        break;

      default:
        exit("no");
        break;
    }