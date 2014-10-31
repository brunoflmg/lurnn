<?php
    require_once "config.php";
    checkStudentLogin();
    $sid    = $_SESSION['sid'];
    $student_email = $_SESSION['email'];
    

    if(isset($_GET['id'])){
        $class_id  = intval($_GET['id']);
        $query     = sprintf("SELECT t1.* FROM students_assigned_classes as t1 WHERE t1.class_id='%d'",$class_id);
        $result    = mysql_query($query);
        while($assigned_student  = mysql_fetch_object($result)){
           $assigned_students[] = $assigned_student;
        }
    }
    //d($assigned_students,1);


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
    <link href="//datatables.net/download/build/nightly/jquery.dataTables.css" rel="stylesheet" type="text/css" />
    
    <style>
       select{
        height:30px;
       }
    </style>

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
                       if(is_array($assigned_students) && !empty($assigned_students)){
						
						//echo "<pre>"; print_r($assigned_students);
					   
                          ?>
                            <div class="alert alert-success">
                              <strong>Well done!</strong> The following students are enrolled to this class.
                            </div>
							<h3 id="class_details_head"> <?php echo $assigned_students['0']->class_details;?> </h3>
                            <table id="view_class_students" class="table table-hover table-responsive">
                              <thead>
                                <tr>
                                  <th>#</th>
                                  <th>Student Name</th>
                                  <th>Student Username</th>
                                  <th>Student Email</th>
                                  <th>Grade Level</th>
                                  <th>Class</th>
                                  <th>Teacher</th>
                                  <th>Joined Date</th>
                                </tr>
                              </thead>
                              <tbody>
                               <?php $i=1;
                               foreach ($assigned_students as $assigned_student) {
                               ?>
                                <tr class="<?php echo $assigned_student->status;?>">
                                  <td><?php echo $i++;?></td>
                                  <td><?php echo $assigned_student->student_fname;?> <?php echo $assigned_student->student_lname;?></td>
                                  <td><?php echo $assigned_student->student_user_name;?></td>
                                  <td><?php echo $assigned_student->student_email;?></td>
                                  <td><?php echo $assigned_student->class_grade_level;?></td>
                                  <td><?php echo $assigned_student->class_name;?> 
									<?php 
										$Cl_Code = $assigned_student->class_code;
										if($Cl_Code)
										{
											echo "(".$Cl_Code.")";
										}
									?>
								  </td>
                                  <td><?php echo $assigned_student->teacher_fname;?> <?php echo $assigned_student->teacher_lname;?></td>
                                  <td><?php echo $assigned_student->student_joined_date;?></td>
                                </tr>
                                <?php } ?>
                                </tbody>
                            </table>
                          <?php
                       }else{
                          ?>
                          <div class="alert alert-warning">
                            <strong>Warning!</strong> No students are enrolled in this class.
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
    <script src="//datatables.net/download/build/nightly/jquery.dataTables.js"></script>
    <script type="text/javascript">
        $(function () {

            // jQuery Knobs
            $(".knob").knob();
            $('#view_class_students').DataTable();
            
        });
    </script>
</body>
</html>