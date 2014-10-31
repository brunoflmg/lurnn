<?php
    @require_once("config.php");

    //d($_POST,1);
    if(isset($_POST['submit']) && isset($_POST['type']) && $_POST['type']=="student"){
        
        $user_name    = mysql_real_escape_string(trim($_POST['user_name']));
        $fname        = mysql_real_escape_string(trim($_POST['fname']));
        $lname        = mysql_real_escape_string(trim($_POST['lname']));
        $email        = mysql_real_escape_string(trim($_POST['email']));
		$grade        = mysql_real_escape_string(trim($_POST['grade']));
        $password     = trim($_POST['password']);
        $re_password  = trim($_POST['re_password']);


        if(! preg_match('/^\w{5,}$/', $user_name)) { // \w equals "[0-9A-Za-z_]"
            // valid username, alphanumeric & longer than or equals 5 chars
            $error_message['user_name'] = "Username must have at least 5 chars, chars may be [0-9A-Za-z_]";
        }else{
            $sql    = sprintf("SELECT *FROM students WHERE LOWER(user_name)='%s'",strtolower($user_name));
            $result = mysql_query($sql);
            $user   = mysql_fetch_object($result);
            mysql_free_result($result);
            if(isset($user->user_name)){
                $error_message['user_name'] = "Username has already taken, Please try with another.";
            }
        }

        if($fname==""){
            $error_message["fname"] = "First Name can not be empty";
        }

        if($lname==""){
            $error_message["lname"] = "Last Name can not be empty";
        }

        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
           $error_message['email'] = "You must put valid email address";
        }else{
            $sql    = sprintf("SELECT *FROM students WHERE email='%s'",strtolower($email));
            $result = mysql_query($sql);
            $user   = mysql_fetch_object($result);
            mysql_free_result($result);
            if(isset($user->email)){
                $error_message['email'] = "This email is already registered, Please login with your account.";
            }
        }
		
		if($grade == ""){
            $error_message["grade"] = "Please select your grade level.";
        }

        if(strlen($password)< 6){
            $error_message['password'] = "Password must have at least 6 characters";
        }

        if($re_password!=$password)
		{
            $error_message['re_password'] = "Password must be matched re-type password";
        }

        if(!is_array($error_message) || empty($error_message)){
            $verification_code = md5(md5($user_name.rand(0,999999)));
            $sql = sprintf("INSERT INTO students(user_name,fname,lname,email,grade_level,password,verification_code,joined_date) VALUES ('%s','%s','%s','%s','%s','%s','%s','%s')",
                    strtolower($user_name),
                    $fname,
                    $lname,
                    strtolower($email),
					$grade,
                    md5($password),
                    $verification_code,
                    date("Y-m-d H:i:s")
                  );
            if(! mysql_query($sql)){
                $error_message['other'] = "Sorry! we are facing problem, please try after sometime.";
            }else{


                $email_date_time = date("F d, Y h:i a");
                $addressing = "Hi $fname $lname";

                $subject = 'Welcome to lurnn.com! Verify your account';
                $body    = 'You have successfully registered in lurnn.com as a student. <br/> <p>Please click the following link to verify your lurnn.com account.</p>
                   <a href="http://'.$_SERVER["SERVER_NAME"].'/verify.php?code='.$verification_code.'&type=student">Click Here</a> or copy the following link into your browser & go <br/>
                   http://'.$_SERVER["SERVER_NAME"].'/verify.php?code='.$verification_code.'&type=student';
                $body   .= '<br/> <strong>Feel free to ask to our following support email, if you fetch any problem.</strong>';

                include_once "email_config.php";
                $template = file_get_contents("Gray/index.html");
                $message  = str_replace(
                                        array(
                                          '[WEBSITE_TITLE]',
                                          '[WEBSITE_ADDRESS]',
                                          '[SENDER_NAME]',
                                          '[SENDER_FOOTER]',
                                          '[SENDER_EMAIL]',
                                          '[SUPPORT_EMAIL_ADDRESS]',
                                          '[WEBSITE_LOGO]',
                                          '[EMAIL_DATE_TIME]',
                                          '[EMAIL_SUBJECT]',
                                          '[EMAIL_ADDRESSING]',
                                          '[EMAIL_BODY]'
                                        ), 

                                        array(
                                          WEBSITE_TITLE,
                                          WEBSITE_ADDRESS,
                                          SENDER_NAME,
                                          SENDER_FOOTER,
                                          SENDER_EMAIL,
                                          SUPPORT_EMAIL_ADDRESS,
                                          WEBSITE_LOGO,
                                          $email_date_time,
                                          $subject,
                                          $addressing,
                                          $body
                                        ), $template);
               

                // To send HTML mail, the Content-type header must be set
                $headers  = 'MIME-Version: 1.0' . "\r\n";
                $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

                // Additional headers
                $headers .= 'To: '.$fname.' <'.$email.'>' . "\r\n";
                $headers .= 'From: lurnn.com <'.EMAIL_SENDER.'>' . "\r\n";

                // Mail it
                @mail($email, $subject, $message, $headers);

                $success_message[] = "<h3 style='color:green'>You have successfully registered your account! We have sent a mail to verify your account.<a href='resend.php?id=".$user_name."&type=student'>Don't get email? click here</a></h3>";
               
            }
        }
    }

    if(isset($_POST['submit']) && (!isset($_POST['type']) || $_POST['type']!="student" || $_POST['type']=='teacher')){
        
        $user_name    = mysql_real_escape_string(trim($_POST['user_name']));
        $fname        = mysql_real_escape_string(trim($_POST['fname']));
        $lname        = mysql_real_escape_string(trim($_POST['lname']));
        $email        = mysql_real_escape_string(trim($_POST['email']));
		$grade        = mysql_real_escape_string(trim($_POST['grade']));
        $password     = trim($_POST['password']);
        $re_password  = trim($_POST['re_password']);


        if(! preg_match('/^\w{5,}$/', $user_name)) { // \w equals "[0-9A-Za-z_]"
            // valid username, alphanumeric & longer than or equals 5 chars
            $error_message['user_name'] = "Username must have at least 5 chars, chars may be [0-9A-Za-z_]";
        }else{
            $sql    = sprintf("SELECT *FROM teachers WHERE LOWER(user_name)='%s'",strtolower($user_name));
            $result = mysql_query($sql);
            $user   = mysql_fetch_object($result);
            mysql_free_result($result);
            if(isset($user->user_name)){
                $error_message['user_name'] = "Username has already taken, Please try with another.";
            }
        }

        if($fname==""){
            $error_message["fname"] = "First Name can not be empty";
        }

        if($lname==""){
            $error_message["lname"] = "Last Name can not be empty";
        }

        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
           $error_message['email'] = "You must put valid email address";
        }else{
            $sql    = sprintf("SELECT *FROM teachers WHERE email='%s'",strtolower($email));
            $result = mysql_query($sql);
            $user   = mysql_fetch_object($result);
            mysql_free_result($result);
            if(isset($user->email)){
                $error_message['email'] = "This email is already registered, Please login with your account.";
            }
        }

        if(strlen($password)< 6){
            $error_message['password'] = "Password must have at least 6 characters";
        }

        if($re_password!=$password){
            $error_message['re_password'] = "Password must be matched re-type password";
        }

        if(!is_array($error_message) || empty($error_message)){
            $verification_code = md5(md5($user_name.rand(0,999999)));
            $sql = sprintf("INSERT INTO teachers(user_name,fname,lname,email,password,verification_code,joined_date) VALUES ('%s','%s','%s','%s','%s','%s','%s')",
                    strtolower($user_name),
                    $fname,
                    $lname,
                    strtolower($email),
                    md5($password),
                    $verification_code,
                    date("Y-m-d H:i:s")
                  );
            if(! mysql_query($sql)){
                $error_message['other'] = "Sorry! we are facing problem, please try after sometime.";
            }else{
                

                $email_date_time = date("F d, Y h:i a");
                $addressing = "Hi $fname $lname";

                $subject = 'Welcome to lurnn.com! Verify your account';
                $body    = 'You have successfully registered in lurnn.com as a teacher.<p>Please click the following link to verify your lurnn.com account.</p>
                   <a href="http://'.$_SERVER["SERVER_NAME"].'/verify.php?code='.$verification_code.'&type=teacher">Click Here</a> or copy the following link into your browser & go <br/>
                   http://'.$_SERVER["SERVER_NAME"].'/verify.php?code='.$verification_code.'&type=teacher';
                $body   .= '<br/> <strong>Feel free to ask to our following support email, if you fetch any problem.</strong>';

                include_once "email_config.php";
                $template = file_get_contents("Gray/index.html");
                $message  = str_replace(
                                        array(
                                          '[WEBSITE_TITLE]',
                                          '[WEBSITE_ADDRESS]',
                                          '[SENDER_NAME]',
                                          '[SENDER_FOOTER]',
                                          '[SENDER_EMAIL]',
                                          '[SUPPORT_EMAIL_ADDRESS]',
                                          '[WEBSITE_LOGO]',
                                          '[EMAIL_DATE_TIME]',
                                          '[EMAIL_SUBJECT]',
                                          '[EMAIL_ADDRESSING]',
                                          '[EMAIL_BODY]'
                                        ), 

                                        array(
                                          WEBSITE_TITLE,
                                          WEBSITE_ADDRESS,
                                          SENDER_NAME,
                                          SENDER_FOOTER,
                                          SENDER_EMAIL,
                                          SUPPORT_EMAIL_ADDRESS,
                                          WEBSITE_LOGO,
                                          $email_date_time,
                                          $subject,
                                          $addressing,
                                          $body
                                        ), $template);
               

                // To send HTML mail, the Content-type header must be set
                $headers  = 'MIME-Version: 1.0' . "\r\n";
                $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

                // Additional headers
                $headers .= 'To: '.$fname.' <'.$email.'>' . "\r\n";
                $headers .= 'From: lurnn.com <'.EMAIL_SENDER.'>' . "\r\n";

                // Mail it
                @mail($email, $subject, $message, $headers);

                $success_message[] = "<h3 style='color:green'>You have successfully registered your account! We have sent a mail to verify your account.<a href='resend.php?id=".$user_name."'>Don't get email? click here</a></h3>";
               
            }
        }
    }
    if(is_array($error_message) && !empty($error_message)){ 
       
            setFlashData("error_message",$error_message);
    }
    if(is_array($success_message) && !empty($success_message)){ 
            setFlashData("success_message",$success_message);
    }
    //d($_SESSION);
    //header("Location: http://".$_SERVER["SERVER_NAME"]);exit;


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
                      <form class="form-inline" role="form" method="post" action="signin.php">
                        <div class="form-group">
                          <label class="sr-only" for="exampleInputEmail2">Email/Username</label>
                          <input type="text" class="form-control" required="required" placeholder="Email or Username" name="myusername" id="myusername" placeholder="Enter email or Username">
                        </div>
                        <div class="form-group">
                          <label class="sr-only" for="exampleInputPassword2">Password</label>
                          <input type="password" class="form-control" required="required" placeholder="Password" name="mypassword" id="mypassword" placeholder="Password">
                        </div>
                        <div class="form-group">
                            <select name="type" class="form-control">
                              <option value="teacher">Teacher</option>
                              <option value="student">Student</option>
                            </select>
                        </div>
                        <div class="form-group">
                          &nbsp; <button type="submit" name="submit" class="btn btn-primary">Sign in</button>
                          &nbsp; <a href="forgot.php" class="btn btn-warning">Forgot?</a>
                        </div>
                      </form>

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
            <h3 class="text-info">Sign Up Today!</h3>
            <form class="form-horizontal" role="form" method="post" action="signup.php">
              <div class="form-group">
                
                <div class="col-sm-12">
                  <input type="text" class="form-control" name="user_name" placeholder="Username"  <? if(!$row){echo 'value="'.$_POST['user_name'].'"';} ?> required>
                </div>
              </div>
              <div class="form-group">
                
                <div class="col-sm-12">
                  <input type="text" class="form-control" name="fname" placeholder="First Name" <? echo 'value="'.$_POST['fname'].'"'; ?>  required>
                </div>
              </div>
              <div class="form-group">
                <div class="col-sm-12">
                  <input type="lname" class="form-control" name="lname" placeholder="Last Name" <? echo 'value="'.$_POST['lname'].'"'; ?>  required>
                </div>
              </div>
              <div class="form-group">
                <div class="col-sm-12">
                  <input type="email" class="form-control" name="email" placeholder="Email" <? echo 'value="'.$_POST['femail'].'"'; ?>  required>
                </div>
              </div>
			  <div class="form-group">
                <div class="col-sm-12">
				<p style="float: left; width: 100%; text-align: left; color: rgb(0, 0, 0); margin: 0px 0px 5px 4px;">Select Grade( Ignore if you are a teacher)</p> <br/>
				  <select class="form-control" name="grade" id="grade">
					<option value="6th Grade">6th Grade</option>
					<option value="7th Grade">7th Grade</option>
					<option value="8th Grade">8th Grade</option>
					<option value="9th Grade">9th Grade</option>
					<option value="10th Grade">10th Grade</option>
					<option value="11th Grade">11th Grade</option>
					<option value="12th Grade">12th Grade</option>
                  </select>
                </div>
              </div>
			  
              <div class="form-group">
                <div class="col-sm-12">
                  <input type="password" class="form-control" name="password" placeholder="Password" required>
                </div>
              </div>
              <div class="form-group">
                <div class="col-sm-12">
                   <input type="password" class="form-control" name="re_password" placeholder="Password" required>
                </div>
              </div>
              <div class="form-group">
                <div class="col-sm-12">
                    <input type="checkbox" value="student" name="type" checked="checked"> &nbsp; <strong> I am a student (Uncheck if you are a teacher) </strong>
                </div>
              </div>
              <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                  <button type="submit" name="submit" class="btn btn-primary btn-lg">Submit</button>
                </div>
              </div>
              <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <h6>Already Registered?<a href="signin.php"> Sign In</a></h6>
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
