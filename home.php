
<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="utf-8">
<title>lurnn</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="">
<!-- Le styles -->
<link href="http://www.lurnn.com/assets/css/bootstrap.css" rel="stylesheet">
<link href="http://www.lurnn.com/assets/css/bootstrap-responsive.css" rel="stylesheet">
<link href="http://www.lurnn.com/assets/css/style.css" rel="stylesheet">
<link rel="stylesheet" href="http://www.lurnn.com/assets/css/font-awesome.min.css">
<!--[if IE 7]>
<link rel="stylesheet" href="http://www.lurnn.com/assets/css/font-awesome-ie7.min.css">
<![endif]-->

<!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
<!--[if lt IE 9]>
      <script src="http://www.lurnn.com/assets/js/html5shiv.js"></script>
    <![endif]-->

<!-- Fav and touch icons -->

<link rel="apple-touch-icon-precomposed" sizes="144x144" href="http://www.lurnn.com/assets/ico/apple-touch-icon-144-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="http://www.lurnn.com/assets/ico/apple-touch-icon-114-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="http://www.lurnn.com/assets/ico/apple-touch-icon-72-precomposed.png">
<link rel="apple-touch-icon-precomposed" href="http://www.lurnn.com/assets/ico/apple-touch-icon-57-precomposed.png">
<link rel="shortcut icon" href="http://www.lurnn.com/assets/ico/favicon.png">
<script src="http://www.lurnn.com/assets/js/jquery.js"></script>
<script src="http://www.lurnn.com/assets/js/bootstrap.min.js"></script>
<script src="http://www.lurnn.com/assets/js/jqBootstrapValidation-1.3.6.min.js"></script></head>

<body>
<!-- NAVBAR
    ================================================== -->
<div class="navbar-wrapper"> 
  <!-- Wrap the .navbar in .container to center it within the absolutely positioned parent. -->
  <div class="container">
    <div class="navbar navbar-inverse">
      <div class="navbar-inner"> 
        <!-- Responsive Navbar Part 1: Button for triggering responsive navbar (not covered in tutorial). Include responsive CSS to utilize. -->
        <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse"> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
        <a class="brand" href="#">lurnn</a> 
        <!-- Responsive Navbar Part 2: Place all navbar contents you want collapsed withing .navbar-collapse.collapse. -->
        <div class="nav-collapse collapse">
          <ul class="nav">
            <li class="active"><a href="#">Home</a></li>
            <li><a href="#about">Classes</a></li>
            <li><a href="#contact">Profile</a></li>
            <!-- Read about Bootstrap dropdowns at http://twitter.github.com/bootstrap/javascript.html#dropdowns -->
            <li class="dropdown"> <a href="#" class="dropdown-toggle" data-toggle="dropdown">Start learning! <b class="caret"></b></a>
              <ul class="dropdown-menu">
                <li><a href="#">Create a class.</a></li>
                <li><a href="#">Join a class.</a></li>
                <li class="divider"></li>
                <li class="nav-header">Help</li>
                <li><a href="#">Contact Us</a></li>
                <li><a href="#">Suggestion & Question Box</a></li>
              </ul>
            </li>
            <li>
              <form class="form-inline well-small" action="http://www.lurnn.com/login.html" method="post" style="margin-bottom:0;">
    <input type="text" class="input-medium"  required="required"  name="email_or_username" placeholder="Email or Username">
    <input type="password" class="input-small" name="password"  placeholder="Password">
    <a href="#myModal" role="button"data-toggle="modal" data-backdrop="">Forgot?</a>
    <button type="submit" class="btn btn-primary">Login</button>
    or
    <a href="http://www.lurnn.com/myfacebook/connect.html" role="button" class="btn btn-primary"> <i class="icon-facebook-sign icon-large"></i> Login With Facebook</a>
</form>            </li>
          </ul>
        </div>
        
        <!--/.nav-collapse --> 
      </div>
      <!-- /.navbar-inner --> 
    </div>
    <!-- /.navbar --> 
    
  </div>
  <!-- /.container --> 
</div><!-- /.navbar-wrapper --> 

<!-- Carousel
    ================================================== -->

<div class="fixed_pos flowing_body well well-small">
  <form class="form-horizontal" id="registration_form" action="http://www.lurnn.com/registration.html" method="post">
    <div class="control-group">
      <label class="control-label" for="name">Name</label>
      <div class="controls">
        <input type="text" required="required" minlength="3" name="name" value=""  id="name" placeholder="Write Your Name">
        <span class="help-block">
                </span> </div>
    </div>
    <div class="control-group">
      <label class="control-label" for="email">Email</label>
      <div class="controls">
        <input type="email" required="required" name="email" id="email" placeholder="yourename@example.com" value="">
        <span class="help-block">
                </span> </div>
    </div>
    <div class="control-group">
      <label class="control-label" for="username">Username</label>
      <div class="controls">
        <input type="text" required="required" pattern="[a-zA-Z0-9]{5,}" name="username" id="username" placeholder="Username" value="">
        <span class="help-block">
                </span> </div>
    </div>
    <div class="control-group">
      <label class="control-label" for="password">Password</label>
      <div class="controls">
        <input type="password" required="required" name="password" id="password" minlength="6" placeholder="Password">
        <span class="help-block">
                </span> </div>
    </div>
    <div class="control-group">
      <label class="control-label" for="confirm">Confirm</label>
      <div class="controls">
        <input type="password" required="required" name="confirm" data-equals="password" id="confirm" placeholder="Confirm Password">
        <span class="help-block">
                </span> </div>
    </div>
    <div class="control-group">
      <div class="controls">
        <button type="submit" class="btn btn-primary">Sign Up</button>
        or
        <a href="http://www.lurnn.com/myfacebook/connect.html" role="button" class="btn btn-primary"> <i class="icon-facebook-sign icon-large"></i> Connect With Facebook</a>
      </div>
    </div>
  </form>
</div>
<div id="myCarousel" class="row-fluid carousel slide">
  <div class="carousel-inner">
    <div class="item active" data-slide-to="0"> <img src="http://www.lurnn.com/assets/img/examples/4.jpg" alt="" width="1500" height="500">
      <div class="container">
        <div class="carousel-caption">
          <h1>Universal education for all.</h1>
          <p class="lead">Lurnn has classes on everything you can think of with thousands of sources of data on every subject. And it's free!</p>
          <a class="btn btn-large btn-primary" href="#">Learn more</a> </div>
      </div>
    </div>
    <div class="item" data-slide-to="1"> <img src="http://www.lurnn.com/assets/img/examples/1.jpg" alt="" width="1500" height="500">
      <div class="container">
        <div class="carousel-caption">
          <h1>Social learning around the globe.</h1>
          <p class="lead">You can collaborate with a classmate in your Economics class or a student in another country, lurnn is global.</p>
          <a class="btn btn-large btn-primary" href="#">Learn more</a> </div>
      </div>
    </div>
    <div class="item" data-slide-to="2"> <img src="http://www.lurnn.com/assets/img/examples/7.jpg" alt="" width="1500" height="500">
      <div class="container">
        <div class="carousel-caption">
          <h1>A new approach to learning.</h1>
          <p class="lead">lurnn uses learning modality algorithms to build models of learning to personalize your education by allowing you to aggregate all of your online accounts.</p>
          <a class="btn btn-large btn-primary" href="#">Browse gallery</a> </div>
      </div>
    </div>
  </div>
  <a class="left carousel-control" href="#myCarousel" data-slide="prev">&lsaquo;</a> <a class="right carousel-control" href="#myCarousel" data-slide="next">&rsaquo;</a> </div>
  
<!-- /.carousel --> 

<!-- Marketing messaging and featurettes
    ================================================== --> 
<!-- Wrap the rest of the page in another container to center all the content. -->

<div class="container marketing"> 
  
  <!-- Three columns of text below the carousel -->
  <div class="row"> </div>
  <!-- /.row -->
  
  <div class="row-fluid marketing">
    <div class="span6">
      <h4>Facebook</h4>
      <p>Connect to Facebook to personalize your academic experience.</p>
      <h4>Google Apps</h4>
      <p>Use Google Apps directly on lurnn and utilize your calendar and task manager.</p>
      <h4>Dropbox</h4>
      <p>Upload docs on any tablet or smartphone device.</p>
      <h4>Facebook</h4>
      <p>Connect to Facebook to personalize your academic experience.</p>
      <h4>Google Apps</h4>
      <p>Use Google Apps directly on lurnn and utilize your calendar and task manager.</p>
      <h4>Dropbox</h4>
      <p>Upload docs on any tablet or smartphone device.</p>
    </div>
    <div class="span6">
      <h4>Evernote</h4>
      <p>Keep all your notes, voice memos and dictations in one place.</p>
      <h4>LinkedIn</h4>
      <p>Utilize LinkedIn to build your profile and expand your social network.</p>
      <h4>Foursquare</h4>
      <p>Let lurnn help out your social life by connecting you with like minded individuals locally.</p>
      <h4>Facebook</h4>
      <p>Connect to Facebook to personalize your academic experience.</p>
      <h4>Google Apps</h4>
      <p>Use Google Apps directly on lurnn and utilize your calendar and task manager.</p>
      <h4>Dropbox</h4>
      <p>Upload docs on any tablet or smartphone device.</p>
    </div>
  </div>
  <hr>
</div><!-- /.row --> 

<!-- START THE FEATURETTES --> 

<!-- /END THE FEATURETTES --> 

<!-- FOOTER -->
<footer>
  <p class="pull-right"><a href="#">Back to top</a></p>
  <p align="center">&copy; lurnn &middot; <a href="#">Privacy</a> &middot; <a href="#">Terms</a></p>
</footer>
</div><!-- /.container --> 

<!-- Le javascript
    ================================================== --> 
<!-- Placed at the end of the document so the pages load faster -->




<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">?</button>
    <h3 id="myModalLabel">Forgot Password?</h3>
  </div>
  <div class="modal-body">
    <form class="form-horizontal" action="http://www.lurnn.com/forgot.html" method="post">
      <div class="control-group">
        <label class="control-label" for="email_or_username">Username / Email</label>
        <div class="controls">
          <input type="text" required="required"  name="email_or_username" id="email_or_username" placeholder="Username Or Email" value="">
          <span class="help-block">
                    </span> </div>
      </div>
      <div class="control-group">
        <div class="controls">
          <button type="submit" class="btn btn-primary">Recover Password</button>
        </div>
      </div>
    </form>
  </div>
</div>
</body>
</html>