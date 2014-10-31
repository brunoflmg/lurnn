<?php
  require_once("config.php");
  if(isset($_POST['submit']) && isset($_POST['type']) && $_POST['type']=="student"){
        //d($_POST,1);
        $user_name    = mysql_real_escape_string(trim($_POST['myusername']));
        
        $password     = trim($_POST['mypassword']);
        //$remember_me  = trim($_POST['remember_me']);
        
        $sql    = sprintf("SELECT * FROM students WHERE (LOWER(user_name)='%s' OR email='%s') AND password='%s'",strtolower($user_name),strtolower($user_name),md5($password));
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
              $error_message[] = "<h3 style='color:red'>You are not verified user.<a href='resend.php?id=".$user->user_name."&type=student'>click here to get verification email</a></h3>";
            }
            else{
              $error_message[] = "<h3 style='color:red'>You have been banned. Please contact at <a href='mailto:info@lurnn.com?Subject=Hello! I am ban in your site' target='_top'>info@lurnn.com</a></h3>";
            }
          } 
        }else{
          $error_message[] = "Invalid login details as a student";
        }
    
    }

    if(isset($_POST['submit']) && (!isset($_POST['type']) || $_POST['type']!='student' || $_POST['type']=='teacher' ))
	{
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
          $error_message = "Invalid login details as a teacher";
        }
    
    }
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>lurnn</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="zion/css/bootstrap.css" rel="stylesheet"/>
    <link href="http://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet"/>
    <!--[if IE 7]>
      <link rel="stylesheet" href="zion/css/font-awesome-ie7.min.css"/>
    <![endif]-->
    <link rel="stylesheet" type="text/css" href="zion/css/style.css" />

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <!-- Fav and touch icons -->
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="zion/zion/img/sample/logo-144.png"/>
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="zion/img/sample/logo-114.png"/>
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="zion/img/sample/logo-72.png"/>
    <link rel="apple-touch-icon-precomposed" href="zion/img/sample/logo-57.png"/>
    <link rel="shortcut icon" href="zion/img/sample/logo.png"/>
    
  </head>

  <body>
  
    <div class="navbar navbar-inverse navbar-fixed-top" id="navbar">
      <div class="navbar-inner">
        <div class="container">
          <div class="row">
            <div class="col-md-12">
              <!-- .btn-navbar is used as the toggle for collapsed navbar content -->
              <a href="#none" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="fa fa-bars"></span>
                <span class="btn-navbar-text">Menu</span>
              </a>
              <a class="brand" href="index.php"><span class="logo"></span></a>
              <div class="navbar-collapse collapse">
                
                <div class="nav-user pull-right">
                  <ul class="nav nav-user-options">
                    <li>
                      

                    </li>
                  </ul>
                </div><!-- end nav-user -->
              </div><!-- end navbar-collapse -->
            </div><!-- end col-md-12 -->
          </div><!-- end row fluid -->
        </div><!-- end container -->
      </div><!-- end navbar-inner -->
    </div><!-- end navbar -->
    
    <div class="clear"></div>
    
    
    <div class="container-wrapper margin-top-50">
      <div class="container">
         <?php 
        $error_message = getFlashData('error_message');
        if(is_array($error_message) && !empty($error_message)){ ?>
          <div class="row">   
              <div class="col-md-4 col-md-offset-4">
                  <div class="alert alert-danger fade in">
                      <button data-dismiss="alert" class="close" type="button">×</button>
                  <?php foreach($error_message as $error){?>
                      <?php echo $error; ?><br/>
                  <?php } ?>
                  </div>
              </div>
          </div>
          <?php } ?>
          <?php 
             $success_message = getFlashData('success_message');
          if(is_array($success_message) && !empty($success_message)){ ?>
          <div class="row">   
              <div class="col-md-4 col-md-offset-4">
                  <div class="alert alert-success fade in">
                      <button data-dismiss="alert" class="close" type="button">×</button>
                  <?php foreach($success_message as $success_mess){?>
                      <?php echo $success_mess; ?><br/>
                  <?php } ?>
                  </div>
              </div>
          </div>
          <?php } ?>
        <div class="row">
          <div class="col-md-4 col-md-offset-4 center well">
            <h3 class="text-info">Sign In Now</h3>
            <form class="form-horizontal" action="signin.php" method="post">
              <div class="form-group">
                
                <div class="col-sm-12">
                  <input type="text" class="form-control" required="required" placeholder="Email or Username" name="myusername" id="myusername" placeholder="Enter email or Username">
                </div>
              </div>
              <div class="form-group">
                
                <div class="col-sm-12">
                  <input type="password" class="form-control" required="required" placeholder="Password" name="mypassword" id="mypassword" placeholder="Password">
                </div>
              </div>
              <div class="form-group">
                  <div class="col-sm-12">
                    <select name="type" class="form-control">
                      <option value="teacher">Teacher</option>
                      <option value="student">Student</option>
                    </select>
                  </div>
              </div>
              
              <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                  <button type="submit" name="submit" class="btn btn-primary">Sign in</button>
                  <a href="forgot.php" class="btn btn-warning">Forgot?</a>
                </div>
              </div>
                
            </form>
          </div><!-- end col -->
        </div><!-- end row -->
      </div><!-- end container -->
    </div><!-- end container-wrapper -->
      
    <footer>
      <div class="container">
        <div class="row">
          <div class="col-md-12">
            <ul class="unstyled center list-circle-icons list-circle-icons-small">
              <li><a href="#none" title="Like us on Facebook" data-rel="tooltip" data-toggle="tooltip"><span class="fa fa-facebook"></span></a></li>
              <li><a href="#none" title="Follow us on Twitter" data-rel="tooltip" data-toggle="tooltip"><span class="fa fa-twitter"></span></a></li>
              <li><a href="#none" title="Follow us on Pinterest" data-rel="tooltip" data-toggle="tooltip"><span class="fa fa-pinterest"></span></a></li>
              <li><a href="#none" title="Follow us on Google Plus" data-rel="tooltip" data-toggle="tooltip"><span class="fa fa-google-plus"></span></a></li>
              <li><a href="#none" title="View Flickr Gallery" data-rel="tooltip" data-toggle="tooltip"><span class="fa fa-flickr"></span></a></li>
            </ul>
            <ul class="list-inline">
              <li><a href="index.html">Home</a></li>
              <li><a href="features.html">Features</a></li>
              <li><a href="portfolio-parent.html">Portfolio</a></li>
              <li><a href="blog-parent.html">Blog</a></li>
              <li><a href="contact.html">Contact</a></li>
              <li><a href="user-signup.html">Sign Up</a></li>
              <li><a href="user-login.html">Login</a></li>
            </ul>
            <p>&copy; Copyright 2013. Zion.</p>
          </div><!-- end col -->
        </div><!-- end row fluid -->
      </div><!-- end container -->
    </footer>

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
    <script src="zion/js/bootstrap.js"></script>
    <script src="zion/js/zion.js"></script>
    <script type="text/javascript">
      
      
      
    </script>

  </body>
</html>
