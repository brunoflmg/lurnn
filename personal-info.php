<?php
	require_once "config.php";
    checkLogin();
    $sql    = sprintf("SELECT *FROM teachers WHERE tid='%s'",$_SESSION['tid']);
    $result = mysql_query($sql);
    $user   = mysql_fetch_object($result);
    mysql_free_result($result);
    //d($_POST);
    if(isset($_POST) && !empty($_POST)){
        $fname     = mysql_real_escape_string(trim($_POST['fname']));
        $lname     = mysql_real_escape_string(trim($_POST['lname']));
        $email     = mysql_real_escape_string(trim($_POST['email']));
        $time_zone = mysql_real_escape_string(trim($_POST['time_zone']));
        $password  = trim($_POST['password']);
        $confirm   = trim($_POST['confirm']);
		
        if($fname!=$user->fname && $fname!=""){
            $sqlExtra[] = "fname='".$fname."'";
        }

        if($lname!=$user->lname && $lname!=""){
            $sqlExtra[] = "lname='".$lname."'";
        }

        if($time_zone!=$user->time_zone && $time_zone!=""){
            $sqlExtra[] = "time_zone='".$time_zone."'";
        }

        if($password==$confirm && $password!="******" && $confirm!="******" && $password!="")
		{
            $sqlExtra[] = "password='".md5($password)."'";
        }

        if($email!=$user->email && filter_var($email, FILTER_VALIDATE_EMAIL)){
            $sqlExtra[] = "email='".$email."'";
        }
        //d($_FILES,1);
        if(isset($sqlExtra) && !empty($sqlExtra)){
            $sql = "UPDATE teachers SET ".implode(",", $sqlExtra)." WHERE tid='".$user->tid."'";
            mysql_query($sql);
        }

        if(isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error']==0) {

            $allowed_filetypes = array('.jpg','.jpeg','.png','.gif');
            $max_filesize      = 10485760;
            $upload_path       = 'uploads/';
            
            $upload_error = false;
            $filename = $_FILES['profile_picture']['name'];
            $ext      = strtolower(substr($filename, strpos($filename,'.'), strlen($filename)-1));
            $filename = "profile_picture_".$user->user_name.$ext;

            if(!in_array($ext,$allowed_filetypes)){
              setFlashData("profile_picture","Invalid File Type");
              $upload_error = true;
            }

            if(filesize($_FILES['userfile']['tmp_name']) > $max_filesize){
              setFlashData("profile_picture","Photo should be less than 10 MB");
              $upload_error = true;
            }

            if(!is_writable($upload_path)){
              setFlashData("profile_picture","Internal Problem. Please contact to admin");
              $upload_error = true;
            }
            if($upload_error==false){
                if(move_uploaded_file($_FILES['profile_picture']["tmp_name"],$upload_path . $filename)) {
                    // *** Include the class
                    @include("resize-class.php");

                    // *** 1) Initialise / load image
                    $resizeObj = new resize($upload_path . $filename);

                    // *** 2) Resize image (options: exact, portrait, landscape, auto, crop)
                    $resizeObj->resizeImage(107, 107, 'crop');

                    // *** 3) Save image
                    $resizeObj->saveImage($upload_path . $filename, 100);

                    $sql = "UPDATE teachers SET profile_picture='".$filename."' WHERE tid='".$user->tid."'";
                    mysql_query($sql);

                } else {
                     setFlashData("profile_picture",'There was an error during the file upload.  Please try again.');
                     $upload_error = true;
                }
            }
        }

        $sql    = sprintf("SELECT *FROM teachers WHERE tid='%s'",$_SESSION['tid']);
        $result = mysql_query($sql);
        $user   = mysql_fetch_object($result);
        mysql_free_result($result);
    }
	
    
?>
<!DOCTYPE html>
<html>
<head>
	<title>lurnn</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	
    <!-- bootstrap -->
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
    <link rel="stylesheet" href="css/compiled/personal-info.css" type="text/css" media="screen" />

    <!-- open sans font -->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800' rel='stylesheet' type='text/css'>

    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
</head>
<body>

  <?php include "includes/menu.php";?>
  <?php include "includes/left_side_bar.php";?>
  
  <form method="post" enctype="multipart/form-data">
	<!-- main container .wide-content is used for this layout without sidebar :)  -->
    <div class="content wide-content">
        <div class="container-fluid">
            <div class="settings-wrapper" id="pad-wrapper">
                <!-- avatar column -->
                <div class="span3 avatar-box">
                    <div class="personal-image">
                        <img src="uploads/<?php echo $user->profile_picture;?>" class="avatar img-circle">
                        <p>Upload a different photo...</p>
                        
                        <input type="file" name="profile_picture"/>
                    </div>
                </div>

                <!-- edit form column -->
                <div class="span7 personal-info">
                    <div class="alert alert-info" style="display:none;">
                        <i class="icon-lightbulb"></i>
                        Welcome to your lurnn.com personal profile. Feel free to upload
                        <br> a new photo or update any contact information here. You can
			<br> navigate the site by clicking "Dashboard" at the top of your screen
                    </div>
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
                    <h5 class="personal-title">Personal info</h5>

                   
                        <div class="field-box">
                            <label>First name:</label>
                            <input class="span5 inline-input" name="fname" type="text" value="<?php echo $user->fname ;?>" />
                        </div>
                        <div class="field-box">
                            <label>Last name:</label>
                            <input class="span5 inline-input" name="lname" type="text" value="<?php echo $user->lname ;?>" />
                        </div>
                        <div class="field-box">
                            <label>Email:</label>
                            <input class="span5 inline-input"  name="email" type="email" value="<?php echo $user->email ;?>" />
                        </div>
                        <div class="field-box">
                            <label>Time Zone:</label>
                            <div class="ui-select">
                                <select id="time_zone" name="time_zone">
                                    <?php if($user->time_zone!=NULL) echo '<option value="Central Time (US &amp; Canada)" selected="selected">(GMT-06:00) Central Time (US &amp; Canada)</option>';?>
                                    <option value="Hawaii">(GMT-10:00) Hawaii</option>
                                    <option value="Alaska">(GMT-09:00) Alaska</option>
                                    <option value="Pacific Time (US &amp; Canada)">(GMT-08:00) Pacific Time (US &amp; Canada)</option>
                                    <option value="Arizona">(GMT-07:00) Arizona</option>
                                    <option value="Mountain Time (US &amp; Canada)">(GMT-07:00) Mountain Time (US &amp; Canada)</option>
                                    <option value="Central Time (US &amp; Canada)" selected="selected">(GMT-06:00) Central Time (US &amp; Canada)</option>
                                    <option value="Eastern Time (US &amp; Canada)">(GMT-05:00) Eastern Time (US &amp; Canada)</option>
                                    <option value="Indiana (East)">(GMT-05:00) Indiana (East)</option>                                    
                                </select>
                            </div>
                        </div>
                        <div class="field-box">
                            <label>Username:</label>
                            <input class="span5 inline-input"  disabled="disabled" type="text" value="<?php echo $_SESSION['user_name'] ;?>" />
                        </div>
                        <div class="field-box">
                            <label>Password:</label>
                            <input class="span5 inline-input" type="password" password="password" name="password" value="******" />
                        </div>
                        <div class="field-box">
                            <label>Confirm password:</label>
                            <input class="span5 inline-input" type="password" password="confirm"  name="confirm" value="******" />
                        </div>
                        <div class="span6 field-box actions">
                            <input type="submit" class="btn-glow primary" value="Save Changes">
                            <span>OR</span>
                            <input type="reset" value="Cancel" class="reset">
                        </div>
                   
                </div>
            </div>
        </div>
    </div>
    <!-- end main container -->
 </form>

	<!-- scripts -->
    <script src="http://code.jquery.com/jquery-latest.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/theme.js"></script>
</body>
</html>