<?php
  require_once("config.php");

  if(isset($_POST['submit']) && isset($_POST['type']) && $_POST['type']=="student"){
        //d($_POST,1);
        $user_name    = mysql_real_escape_string(trim($_POST['myusername']));
        
        $password     = trim($_POST['mypassword']);
        //$remember_me  = trim($_POST['remember_me']);
        
        $sql    = sprintf("SELECT *FROM students WHERE (LOWER(user_name)='%s' OR email='%s') AND password='%s'",strtolower($user_name),strtolower($user_name),md5($password));
        $result = mysql_query($sql);
        $user   = mysql_fetch_object($result);
        mysql_free_result($result);
        if(isset($user->user_name)){
          if($user->status=="active"){
            $_SESSION['sid']        = $user->sid;
            $_SESSION['user_name']  = $user->user_name;
            $_SESSION['fname']      = $user->fname;
            $_SESSION['lname']      = $user->lname;
            $_SESSION['email']      = $user->email;
            $_SESSION['grade_level']= $user->grade_level;
            $_SESSION['status']     = $user->status;
            $_SESSION['joined_date']= $user->joined_date;
            echo "<script> window.location='studentboard.php'</script>";
            exit;
          }else{
            if($user->status=="pending"){
              $error_message = "<h3 style='color:red'>You are not verified user.<a href='resend.php?id=".$user->user_name."&type=student'>click here to get verification email</a></h3>";
            }
            else{
              $error_message = "<h3 style='color:red'>You have been banned. Please contact at <a href='mailto:info@lurnn.com?Subject=Hello! I am ban in your site' target='_top'>info@lurnn.com</a></h3>";
            }
          } 
        }else{
          $error_message = "Invalid login details";
        }
    
    }

    if(isset($_POST['submit']) && !isset($_POST['type'])){
        //d($_POST,1);
        $user_name    = mysql_real_escape_string(trim($_POST['myusername']));
        
        $password     = trim($_POST['mypassword']);
        $remember_me  = trim($_POST['remember_me']);
        
        $sql    = sprintf("SELECT *FROM teachers WHERE (LOWER(user_name)='%s' OR email='%s') AND password='%s'",strtolower($user_name),strtolower($user_name),md5($password));
        $result = mysql_query($sql);
        $user   = mysql_fetch_object($result);
        mysql_free_result($result);
        if(isset($user->user_name)){
          if($user->status=="active"){
            $_SESSION['tid']        = $user->tid;
            $_SESSION['user_name']  = $user->user_name;
            $_SESSION['fname']      = $user->fname;
            $_SESSION['lname']      = $user->lname;
            $_SESSION['email']      = $user->email;
            $_SESSION['status']     = $user->status;
            $_SESSION['joined_date']= $user->joined_date;
            echo "<script> window.location='dashboard.php'</script>";
            exit;
          }else{
            if($user->status=="pending"){
              $error_message = "<h3 style='color:red'>You are not verified user.<a href='resend.php?id=".$user->user_name."'>click here to get verification email</a></h3>";
            }
            else{
              $error_message = "<h3 style='color:red'>You have been banned. Please contact at <a href='mailto:info@lurnn.com?Subject=Hello! I am ban in your site' target='_top'>info@lurnn.com</a></h3>";
            }
          } 
        }else{
          $error_message = "Invalid login details";
        }
    
    }
?>
<!DOCTYPE html>

<html lang="en">

  <head>

    <meta charset="utf-8">

    <title>Sign in &middot; </title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="description" content="">

    <meta name="author" content="">
    <link href="css/bootstrap/bootstrap.css" rel="stylesheet">
    <link href="css/bootstrap/bootstrap-responsive.css" rel="stylesheet">
    <link href="css/bootstrap/bootstrap-overrides.css" type="text/css" rel="stylesheet">

    <!-- global styles -->
    <link rel="stylesheet" type="text/css" href="css/layout.css">
    <link rel="stylesheet" type="text/css" href="css/elements.css">
    <link rel="stylesheet" type="text/css" href="css/icons.css">

    <!-- libraries -->
    <link rel="stylesheet" type="text/css" href="css/lib/font-awesome.css">
    
    <!-- this page specific styles -->
    <link rel="stylesheet" href="css/compiled/signup.css" type="text/css" media="screen" />

    <!-- open sans font -->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,700italic,800italic,400,300,600,700,800' rel='stylesheet' type='text/css'>

    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
  </head>



  <body>

     <div class="header">
        <a href="index.html">
            <img src="img/lurnn-sizedsm.png" class="logo" />
        </a>
    </div>

    <div class="container">
    <?php if(isset($error_message) && $error_message!=""){ ?>
    <div class="row-fluid">   
        <div class="span4 offset4">
            <div class="alert fade in">
                <button data-dismiss="alert" class="close" type="button">×</button>
            <?php echo $error_message; ?><br/>
            </div>
        </div>
    </div>
    <?php } ?>
    <div class="row-fluid">
     <div class="login-wrapper span5 offset2">
        <div class="box">
            <div class="content-wrap">
      <form  method="post" form name ="form1">

        <h3 class="muted">Sign In</h3>
        <br/>

        <input type="text" autocomplete="off" class="input-block-level" required="required" placeholder="Email address or Username" name="myusername" id="myusername">

        <input type="password" autocomplete="off" class="input-block-level" required="required" placeholder="Password" name="mypassword" id="mypassword">

        <label class="checkbox" style="text-align:left;">

          <input type="checkbox" value="student" name="type"> I am a student (Uncheck if you are not a student)

        </label>

        <button class="btn btn-medium btn-primary" type="submit" name="submit" value="login">Sign in</button>
        &nbsp; <a href="forgot.php" class="btn btn-primary">Forgot ?</a>
        <br/>
        <br/>
        <a href="/">Don't have Account?</a>
      </form>

     </div>                
            </div>
        </div>
        </div>
    </div> <!-- /container -->



    <!-- Le javascript

    ================================================== -->

    <!-- Placed at the end of the document so the pages load faster -->

    <script src="http://code.jquery.com/jquery-latest.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/theme.js"></script>

  </body>

</html>

