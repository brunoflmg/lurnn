<?php
    require_once("config.php");

    if(isset($_GET['type']) && $_GET['type']=="student"){

        $code = mysql_real_escape_string($_GET['code']);
        $sql  = sprintf("SELECT *FROM students WHERE verification_code='%s' AND status='pending'",$code);
        $result = mysql_query($sql);
        $user   = mysql_fetch_object($result);
        if(isset($user->sid)){
            $sql = sprintf("UPDATE students SET verification_code='%s',status='active' WHERE sid='%s'",'',$user->sid);
            if(mysql_query($sql)){
                $success_message = "You have successfully verified your account, you are being redirected to <a href='signin.php'>login</a> page within 5 second";
                $success_message.= "<script>setTimeout(function(){window.location='signin.php'},5000);</script>";
            }else{
                $error_message  = "Sorry! we could not verify your account, Please try again later. you are being redirected to <a href='signin.php'>login</a> page within 5 second";
                $error_message .= "<script>setTimeout(function(){window.location='signin.php'},5000);</script>";
            }
        }else{
            $error_message  = "Invalid or expired verification code. you are being redirected to <a href='signin.php'>login</a> page within 5 second";
            $error_message .= "<script>setTimeout(function(){window.location='signin.php'},5000);</script>";
        }

    }
    
    if(! isset($_GET['type']) || $_GET['type']!="student"){
        $code = mysql_real_escape_string($_GET['code']);
        $sql  = sprintf("SELECT *FROM teachers WHERE verification_code='%s' AND status='pending'",$code);
        $result = mysql_query($sql);
        $user   = mysql_fetch_object($result);
        if(isset($user->tid)){
            $sql = sprintf("UPDATE teachers SET verification_code='%s',status='active' WHERE tid='%s'",'',$user->tid);
            if(mysql_query($sql)){
                $success_message = "You have successfully verified your account, you are being redirected to <a href='signin.php'>login</a> page within 5 second";
                $success_message .= "<script>setTimeout(function(){window.location='signin.php'},5000);</script>";
            }else{
                $error_message  = "Sorry! we could not verify your account, Please try again later. you are being redirected to <a href='signin.php'>login</a> page within 5 second";
                $error_message .= "<script>setTimeout(function(){window.location='signin.php'},5000);</script>";
            }
        }else{
            $error_message  = "Invalid or expired verification code. you are being redirected to <a href='signin.php'>login</a> page within 5 second";
            $error_message .= "<script>setTimeout(function(){window.location='signin.php'},5000);</script>";
        }
    }
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

    <!-- open sans font -->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800' rel='stylesheet' type='text/css'>

    <!-- lato font -->
    <link href='http://fonts.googleapis.com/css?family=Lato:300,400,700,900,300italic,400italic,700italic,900italic' rel='stylesheet' type='text/css'>

    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    <link rel="shortcut icon" href="./img/favicon.jpg"/>


</head>
<body>

   

   
    <!-- navbar -->
    <div class="navbar navbar-fixed-top navbar-inverse">
        <div class="navbar-inner">
            <a class="brand" href="index.php"><img src="img/lurnn-sizedsmlr.png"></a>
            <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <div class="nav-collapse collapse">
                
            </div>
          
        </div>
    </div>
    <!-- end navbar -->
    <?php //include "includes/student_left_side_bar.php"; ?>



  <!-- main container -->
    <div class="content">

        
        <div class="container-fluid">

            <!-- upper main stats -->
            
            <!-- end upper main stats -->

            <div id="pad-wrapper">

                <!-- statistics chart built with jQuery Flot -->
                <div class="row-fluid">
                    <?php if(isset($success_message)){?>
                          <div class="alert alert-success">
                          <h4>Success!</h4>
              <button data-dismiss="alert" class="close" type="button">×</button>
              <?php echo $success_message;?>
            </div>
                    <?php } ?>
                    <?php if(isset($error_message)){
                        ?>
            <div class="alert alert-error">
              <button data-dismiss="alert" class="close" type="button">×</button>
              <h4>Error!</h4>
              <?php echo $error_message;?>
            </div>
                    <?Php  } ?>
                </div>
            </div>
        </div>
    </div>


  <!-- scripts -->
    <script src="http://code.jquery.com/jquery-latest.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery-ui-1.10.2.custom.min.js"></script>
    <!-- knob -->
    <script src="js/jquery.knob.js"></script>
    <!-- flot charts -->

    <script src="js/theme.js"></script>

    <script type="text/javascript">
        $(function () {

            // jQuery Knobs
            $(".knob").knob();


            
        });
    </script>
</body>
</html>
