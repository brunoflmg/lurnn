<?php
    require_once "config.php";
?>
<!DOCTYPE html>

<html lang="en">

<head>

    <link href="css/bootstrap.min.css" rel="stylesheet" media="screen">

    <meta charset="utf-8">

    <title>lurnn</title>

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
    <style type="text/css">
    body{
        background-color: #FFFFFF;
    }
    div {
        text-align:justify;
        text-justify:inter-word;
    }
    /* Custom container */
    .container {
        margin: 0 auto;
    }
    .container > hr {
        margin: 60px 0;
    }
    /* Main marketing message and sign up button */
    .jumbotron {
        margin: 80px 0;
        text-align: center;
    }
    .jumbotron h1 {
        font-size: 100px;
        line-height: 1;
    }
    .jumbotron .lead {
        font-size: 24px;
        line-height: 1.25;
    }
    .jumbotron .btn {
        font-size: 21px;
        padding: 14px 24px;
    }
    /* Supporting marketing content */
    .marketing {
        margin: 60px 0;
    }
    .marketing p + h4 {
        margin-top: 28px;
    }
    .p {
        text-align:justify;
    }
   
    </style>
</head>



<body>

    <div class="header" style="height:73px;">
      <div class="container">
        <a href="index.php" style="float:left;margin-left:-25px;">
            <img src="img/lurnn-sizedsm.png" class="logo" />
        </a>
        <form  method="post" action="signin.php" class="form-inline" style="float:right;margin-right:-40px;">
        
        <input type="text" style="height:30px;" autocomplete="off" class="input-medium" required="required" placeholder="Email or Username" name="myusername" id="myusername">

        <input type="password" style="height:30px;" autocomplete="off" class="input-medium" required="required" placeholder="Password" name="mypassword" id="mypassword">
        <input type="checkbox" name="type" value="student" style="width:30px;height:30px;color:#FFFFFF;"/><font color="#FFFFFF">Sign In As Student</font> &nbsp; 
        <button class="btn btn-medium btn-primary" type="submit" name="submit" value="login">Sign in</button>
        &nbsp; <a href="forgot.php" class="btn btn-primary">Forgot ?</a>
      </form>
      </div>
    </div>

    <div class="container">



        
       
        <!-- Example row of columns -->
        <div class="row-fluid">
            <div class="span8">
             <h3>A new way of looking at education.</h3>
             <br/>

                <div class="row-fluid">

                    <div class="span6">

                        <div>

                            <h4>Personalization</h4>

                            <p>Everything on lurnn is personalized, both for the instructor and the student, based on the data they provide or we learn through connecting to Facebook, LinkedIn or numerous academic portals. On lurnn, each student receives an education that is personalized to meet their strengths and weaknesses and help them evolve as students.</p>

                            <p><a class="btn" href="#">View details &raquo;</a>
                            </p>

                        </div>

                    </div>

                    <div class="span6">

                        <h4>Learning Modalities</h4>

                        <p>Every student learns a different way just as every instructor teaches a different way and lurnn not only welcomes that concept but fosters it. On lurnn, students are taught in the way that is most effective for them and teachers are capable of teaching their classes in the ways that they find most effective.</p>

                        <p><a class="btn" href="#">View details &raquo;</a>
                        </p>

                    </div>



                </div>



                <div class="row-fluid">
                    <div class="span6">

                        <h4>Social Learning</h4>

                         <p>Lurnn is very much about social learning. Students have the ability to discuss various assignments, subjects and quizzes when they can’t figure something out, all of which is mediated by a professor. Instructors can also utilize our social platform and exchange ideas on what is and isn’t working in their mode of instruction.</p>

                        <p><a class="btn" href="#">View details &raquo;</a>
                        </p>

                    </div>

                    <div class="span6">

                        <h4>A new kind of online class</h4>

                        
                        <p>Most online classes are composed of faceless instructors and peers. On lurnn.com, you can chat with your professor and your peers and also sit next to them in the hybrid version of lurnn. Lurnn isn’t just a “one size fits all” education experience, it is personalized education.</p>

                        <p><a class="btn" href="#">View details &raquo;</a>
                        </p>

                    </div>



                </div>
                <div class="row-fluid">
                    <div class="span6">

                        <h4>Tracking progress</h4>

                        <p>On lurnn, both instructors and students can track their progress on all kinds of levels. For students, they can track which subjects are their strong suits and which have room for improvement. For instructors, they can monitor which students are performing well in some areas and can use improvement in others, giving instructors more data to help them track and teach their classrooms, be it online or face to face.</p>

                        <p><a class="btn" href="#">View details &raquo;</a>
                        </p>

                    </div>

                    <div class="span6">

                        <h4>Use lurnn at your academic institution.</h4>

                        <p>lurnn isn't just an online platform for online classes. lurnn can be used as a supplement to face to face instruction and is built for use from Pre-K to Graduate School. Contact us to today and find out how lurnn can help your students and instructors achieve their academic goals.</p>

                        <p><a class="btn" href="#">View details &raquo;</a>
                        </p>

                    </div>
                </div>
            </div>
            <div class="span4">
                <div class="login-wrapper">
                    <div class="box">
                        <div class="content-wrap">
                            <h6>Sign Up Today</h6>
                           <p>
                            <?php if(is_array($_SESSION['flash_data']) && !empty($_SESSION['flash_data'])){ ?>
    
                                <div class="alert fade in">
                                        <button data-dismiss="alert" class="close" type="button">×</button>
                                    <?php
                                   $flashfata = $_SESSION['flash_data'];
                                     foreach($flashfata as $key=>$value){?>
                                        <?php echo getFlashData($key); ?><br/>
                                    <?php } ?>
                                </div>
                                    
                            <?php } ?>
                           </p>
                            <form name="form1" action="signup.php" method="post">
                                <p>
                                    <input class="span12" required="required" type="text" autocomplete="off" placeholder="Username" name="user_name" value="<?php echo $_POST['user_name'];?>"/>
                                </p>
                                <p>
                                    <input class="span12" required="required" type="text" autocomplete="off" placeholder="First Name" name="fname" value="<?php echo $_POST[ 'fname']; ?>" />
                                </p>
                                <p>
                                    <input class="span12" required="required" type="text" autocomplete="off" placeholder="Last Name" name="lname" value="<?php echo $_POST[ 'lname']; ?>"/>
                                </p>
                                <p>
                                    <input class="span12" required="required" type="email" autocomplete="off" placeholder="Email Address" name="email" value="<?php echo $_POST[ 'email'];?>"/>
                                </p>
                                <p>
                                    <input class="span12" required="required" type="password" autocomplete="off" placeholder="Password" name="password"/>
                                </p>
                                <p>
                                    <input class="span12" required="required" type="password" autocomplete="off" placeholder="Retype Password" name="re_password"/>
                                </p>
                                <h4 class="muted">
                                    <input type="checkbox" checked="checked" name="type" value="student"/> I am a student (Uncheck if you are a teacher)
                                </h4>
                                <br/>
                                <p style="text-align:center;">
                                    <input type="submit" class="btn btn-primary" name="submit" value="Sign Up"/>
                                </p>
                                <p style="text-align:center;">
                                    <h6>Already Registered?<a href="signin.php"> Sign In</a></h6>
                                </p>
                            </form>


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <hr/>



    <div class="footer">
    <br/><br/>
        <center>
            <h3>&copy; lurnn 2013</center>
        </h3>
        <br/>
    </div>



    </div>
    <!-- /container -->



    <!-- Le javascript

    ================================================== -->

    <!-- scripts -->
    <script src="http://code.jquery.com/jquery-latest.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/theme.js"></script>
    <?php 
        if(isset($_SESSION['sid'])){
                  ?>
                  <script>window.location='http://<?php echo $_SERVER["SERVER_NAME"];?>/studentboard.php';</script>
                  <?php
        }
        if(isset($_SESSION['tid'])){
            ?>
            <script>window.location='http://<?php echo $_SERVER["SERVER_NAME"];?>/dashboard.php';</script>
            <?php
        }
    ?>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-48191715-1', 'lurnn.com');
  ga('send', 'pageview');

</script>

</body>

</html>
