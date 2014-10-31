<?php
    require_once "config.php";
    checkStudentLogin();
    $sid    = $_SESSION['sid'];
    $student_email = $_SESSION['email'];
    
	if(isset($_GET['page']) && is_numeric($_GET['page'])){
     $page = $_GET['page'];
   }else{
     $page = 1; 
   }
   if(isset($_GET['display']) && is_numeric($_GET['display'])){
     $display = $_GET['display'];
   }else{
     $display = 10; 
   }
   
	$limit_start = ($page-1)*$display;
	
	$grade_level = $_SESSION['grade_level'];
	
    
	//--ORIGIONAL// $query  = sprintf("SELECT t1.*,t2.user_name,t2.fname,t2.lname,t2.email FROM classes as t1 LEFT JOIN teachers as t2 ON t1.creator_tid=t2.tid WHERE t1.grade_level='%s' AND t1.class_id NOT IN (SELECT t3.class_id FROM class_students as t3 WHERE t3.email='%s')",$_SESSION['grade_level'],$student_email );
	
	//--Manm 1//$query  = sprintf("SELECT class_students.*, classes.* FROM class_students LEFT JOIN classes on class_students.class_id = classes.class_id where class_students.email = '$student_email' && class_students.added_by = 'teacher' && classes.grade_level = '$grade_level' ");
	
	$query  = sprintf("SELECT t1.*,t2.user_name,t2.fname,t2.lname,t2.email FROM classes as t1 LEFT JOIN teachers as t2 ON t1.creator_tid=t2.tid WHERE t1.grade_level='%s'",$_SESSION['grade_level'],$student_email );
    $result = mysql_query($query);
    while($offer_class  = mysql_fetch_object($result)){
       //$offer_classes[$offer_class->class_id] = $offer_class;
	   $offer_classes[] = $offer_class;
    }
	//echo "<pre>"; print_r($offer_classes);  die();
    //d($offer_classes,1);
    if(isset($_GET['id'])){
        if(isset($offer_classes[$_GET['id']])){
            $class = $offer_classes[$_GET['id']];
            $query = sprintf("INSERT INTO class_students (fname,lname,email,class_id,creator_tid,grade_level,fcat_score,description,created_date,added_by) VALUES('%s','%s','%s','%s','%s','%s','%s','%s','%s','%s')",
                        $_SESSION['fname'],
                        $_SESSION['lname'],
                        $_SESSION['email'],
                        $class->class_id,
                        $class->creator_tid,
                        $_SESSION['grade_level'],
                        0,
                        '',
                        date("Y-m-d H:i:s"),
                        'student'
                        );
                    if(mysql_query($query)){
                        setFlashData("success_message","You have successfully joined this class");
                    }else{
                        setFlashData("error_message","Sorry!! You can not joined this class right now. Please contact your respective teacher.");
                    }
        }else{
            setFlashData("error_message","Sorry!! You can not joined this class right now. You may already joined this class or this class is not for you to join.");
        }
        header("Location: self_assessment.php?class_id={$_GET['id']}");exit;
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

   

   
    <?php include "includes/student_menu.php"; ?>
    <?php include "includes/student_left_side_bar.php"; ?>



	<!-- main container -->
    <div class="content">

        
        <div class="container-fluid">


            <div id="pad-wrapper">

                <!-- statistics chart built with jQuery Flot -->
                <?php $success_message = getFlashData("success_message");
                   if(isset($success_message) && $success_message!=""){
                    ?>
                        <div class="alert alert-success">
                           <button type="button" class="close" data-dismiss="alert">&times;</button>
                          <strong><?php echo $success_message;?></strong>
                        </div>


                    <?php
                   }
                ?>
                 <?php $error_message = getFlashData("error_message");
                   if(isset($error_message) && $error_message!=""){
                    ?>
                        <div class="alert alert-success">
                           <button type="button" class="close" data-dismiss="alert">&times;</button>
                          <strong><?php echo $error_message;?></strong>
                        </div>


                    <?php
                   }
                ?>

                <div class="row-fluid chart">
                    <?php
                       if(is_array($offer_classes) && !empty($offer_classes)){
                          ?>
                            <div class="alert alert-success">
                              <strong>Well done!</strong> You can enroll the following courses .
                            </div>

                            <table class="table table-hover table-responsive">
                              <thead>
                                <tr>
                                  <th>#</th>
                                  <th>Class Name</th>
                                  <th>Class Details</th>
                                  <th>Grade Level</th>
                                  <th>Class Code</th>
                                  <th>Meeting Hours</th>
                                  <th>Teacher Name</th>
                                  <th>Teacher Email</th>
                                  <th>Last Updated</th>
                                  <th>Action</th>

                                </tr>
                              </thead>
                              <tbody>
                               <?php $i=1;
                               foreach ($offer_classes as $offer_class) {
                               ?>
                                <tr class="<?php echo $offer_class->status;?>">
                                  <td><?php echo $i++;?></td>
                                  <td>
									<a href="class_dashboard.php?Qid=<?php echo $offer_class->class_id; ?>">
										<?php echo $offer_class->class_name;?>
									</a>	
								   </td>
                                  <td><?php echo $offer_class->class_details;?></td>
                                  <td><?php echo $offer_class->grade_level;?></td>
                                  <td><?php echo $offer_class->class_code;?></td>
                                  <td><?php echo $offer_class->meeting_hours;?></td>
                                  <td><?php echo $offer_class->fname;?> <?php echo $offer_class->lname;?></td>
                                  <td><?php echo $offer_class->email;?></td>
                                  <td><?php echo $offer_class->last_updated;?></td>
                                  <td><a class="btn btn-primary" href="view-class-students.php?id=<?php echo $offer_class->class_id?>" >View Students</a> &nbsp; <a class="btn btn-primary" href="student-offer-classes.php?id=<?php echo $offer_class->class_id?>">Join</a></td>
                                </tr>
                                <?php } ?>
                              </tbody>
                            </table>
                          <?php
                       }else{
                          ?>
                          <div class="alert alert-warning">
                            <strong>Warning!</strong> No courses available for you to enroll.
                          </div>
                          <?php
                       }
                    ?>
                    

                    
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
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>

    <script src="js/theme.js"></script>

    <script type="text/javascript">
        $(function () {
			// jQuery Knobs
            $(".knob").knob();
        });
    </script>
</body>
</html>