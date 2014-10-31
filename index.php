<?php
    require_once "config.php";
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
              <a class="brand" href=""><span class="logo"></span></a>
              <div class="navbar-collapse collapse">
                
                <div class="nav-user pull-right">
                  <ul class="nav nav-user-options margen-left-70">
                    <li>
                      <form class="form-inline" role="form" method="post" action="signin.php">
                        <div class="form-group" style="width:150px;">
                          <label class="sr-only" for="exampleInputEmail2">Email/Username</label>
                          <input type="text" class="form-control" required="required" placeholder="Email or Username" name="myusername" id="myusername" placeholder="Enter email or Username">
                        </div>
                        <div class="form-group"  style="width:150px;">
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
                        </div>
                      </form>

                    </li>
                    <li class="signup">
                      <a href="signup.php" class="btn btn-purple">Sign Up</a>
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
    
    <div id="myCarousel" class="container-wrapper container-top container-wrapper-home carousel slide" data-ride="carousel">
      <!-- Indicators -->
      <ol class="carousel-indicators">
        <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
        <li data-target="#myCarousel" data-slide-to="1" class=""></li>
        <li data-target="#myCarousel" data-slide-to="2" class=""></li>
      </ol>
      <div class="carousel-inner">
        <div class="item active">
          <div class="container">
            <div class="carousel-caption">
              <div class="row">
                <div class="col-md-6 text-left">
                  <h1>Personalized education</h1>
                  <p class="lead">Lurnn features your entire academic experience with one addition, it adjusts its teaching mechanism to how you learn.</p>
                </div><!-- end col -->
                <div class="col-md-6">
                  <img src="img/home-feature-1.png" alt="" />
                </div><!-- end col -->
              </div><!-- end row -->
            </div><!-- end carousel-caption -->
          </div><!-- end container -->
        </div><!-- end item -->
        <div class="item">
          <div class="container">
            <div class="carousel-caption">
              <ul class="unstyled center list-circle-icons">
                <li class=""><span class="fa fa-comments"></span></li>
                <li class="red"><span class="fa fa-columns"></span></li>
                <li class="green"><span class="fa fa-user"></span></li>
                <li class="purple"><span class="fa fa-envelope-o"></span></li>
              </ul>
              <h1>A new way of learning</h1>
              <p>Lurnn is about personalized learning with a focus on skill and understanding.</p>
            </div><!-- end carousel-caption -->
          </div><!-- end container -->
        </div><!-- end item -->
        <div class="item">
          <div class="container">
            <div class="carousel-caption">
              <div class="row">
                <div class="col-md-4 text-left">
                  <h1>Learn how you learn</h1>
                  <p>Lurnn's software learns how you learn and adjusts accordingly. Teachers don't have to do a thing.</p>
                </div><!-- end col -->
                <div class="col-md-8">
                  <img src="img/home-feature-2.png" alt="" />
                </div><!-- end col -->
              </div><!-- end row -->
            </div><!-- end carousel-caption -->
          </div><!-- end container -->
        </div><!-- end item -->
      </div><!-- end carousel-inner -->
      <a class="left carousel-control" href="#myCarousel" data-slide="prev"><span class="fa fa-chevron-left"></span></a>
      <a class="right carousel-control" href="#myCarousel" data-slide="next"><span class="fa fa-chevron-right"></span></a>
    </div><!-- end carousel -->
    
    <div class="clear"></div>
    
      
    <footer>
      <div class="container">
        <div class="row">
          <div class="col-md-12">
          <!--
            <ul class="unstyled center list-circle-icons list-circle-icons-small">
              <li><a href="#none" title="Like us on Facebook" data-rel="tooltip" data-toggle="tooltip"><span class="fa fa-facebook"></span></a></li>
              <li><a href="#none" title="Follow us on Twitter" data-rel="tooltip" data-toggle="tooltip"><span class="fa fa-twitter"></span></a></li>
              <li><a href="#none" title="Follow us on Pinterest" data-rel="tooltip" data-toggle="tooltip"><span class="fa fa-pinterest"></span></a></li>
              <li><a href="#none" title="Follow us on Google Plus" data-rel="tooltip" data-toggle="tooltip"><span class="fa fa-google-plus"></span></a></li>
              <li><a href="#none" title="View Flickr Gallery" data-rel="tooltip" data-toggle="tooltip"><span class="fa fa-flickr"></span></a></li>
            </ul>
            -->
            <ul class="list-inline">
              <li><a href="index.php">Home</a></li>
              <li><a href="signup.php">Sign Up</a></li>
              <li><a href="signin.php">Login</a></li>
            </ul>
            <p>&copy; Copyright 2014. McClintock Investment Group.</p>
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
