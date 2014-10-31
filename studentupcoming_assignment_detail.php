<?php
    require_once "config.php";
    checkStudentLogin();
    $sid    = $_SESSION['sid'];
    $student_email = $_SESSION['email'];


    $query  = sprintf("SELECT upcoming_assignments.*, teachers.* FROM upcoming_assignments INNER JOIN teachers ON upcoming_assignments.user_id = teachers.tid ORDER BY upcoming_assignments.asign_id DESC");
    $result = mysql_query($query);
    while($assigned_class  = mysql_fetch_object($result)){
       $assigned_classes[] = $assigned_class;
    }
    //d($assigned_classes,1);
    
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
	<script type="text/javascript">
		function show_Details(str)
		{
			$('#back_fade').show();
			$.ajax({
				type: "POST",
				url: "viewAssignmentDetails.php",
				data: 'str='+str,
				context: document.body
				}).done(function(result){
					$('#myModalIS').show();
					$('.popUp_content').html(result);
				});
			return false;
		}
		
		function CloseMe()
		{
			$('#myModalIS').hide();
			$('#back_fade').hide();
		}
	</script>
	
</head>
<body>

    <?php include "includes/student_menu.php"; ?>
    <?php include "includes/student_left_side_bar.php"; ?>

	<!-- main container -->
    <div class="content">

        
        <div class="container-fluid">


            <div id="pad-wrapper">

                <!-- statistics chart built with jQuery Flot -->

                <div class="row-fluid chart">
                    <?php
                       if(is_array($assigned_classes) && !empty($assigned_classes)){
                          ?>
                            <div class="alert alert-success">
                              <strong>Well done!</strong> Below are the upcoming assignments.
                            </div>

                            <table id="student_classes" class="table table-hover table-responsive">
                              <thead>
                                <tr>
                                  <th>#</th>
                                  <th>Assignment Name</th>
                                  <th>Assignment Type</th>
								  <th>Class Name</th>
                                  <th>Teacher Name</th>
                                  <th>Teacher Email</th>
                                  <th>Created date</th>
                                  <th>Due Date</th>
                                  <th>Action</th>

                                </tr>
                              </thead>
                              <tbody>
                               <?php $i=1;
                               foreach ($assigned_classes as $assigned_class) {
							   //echo "<pre>"; print_r($assigned_class);
							   $classID =  $assigned_class->class_id;
							//--- Below code will show the record of only tose classes in which current login student is assigned-------------- 
								$sqlzz = "SELECT * FROM `class_students` WHERE class_id ='$classID' and email = '$student_email'";
								$resultzz = mysql_query($sqlzz);
								$count = mysql_num_rows($resultzz);
								if($count != '0')
								{
                               ?>
                                <tr class="<?php echo $assigned_class->status;?>">
                                  <td><?php echo $i++;?></td>
                                  <td><?php echo $assigned_class->asign_name;?></td>
                                  <td><?php echo $assigned_class->asign_type;?></td>
								  <td>
									<?php
										$classID =  $assigned_class->class_id;
										$sql = "SELECT * FROM `classes` WHERE class_id ='$classID'";
										$result = mysql_query($sql);
										$class = mysql_fetch_object($result);
										echo $class->class_name;
									?>
								  </td>
                                  <td><?php echo $assigned_class->fname."".$assigned_class->lname;?></td>
                                  <td><?php echo $assigned_class->email;?></td>
                                  <td><?php echo $assigned_class->created_date;?></td>
                                  <td><?php echo $assigned_class->due_date;?></td>
                                  <td><a class="btn btn-primary" href="#" onclick="show_Details(<?php echo $assigned_class->asign_id ?>);">View Details</a></td>
                                </tr>
                                <?php } } ?>
                              </tbody>
                            </table>
                          <?php
                       }else{
                          ?>
                          <div class="alert alert-warning">
                            <strong>Warning!</strong> You are not enrolled any couses. Please contact to your teachers.
                          </div>
                          <?php
                       }
                    ?>
                </div>
            </div>
        </div>
    </div>

	<div class="row-fluid">
	<div class="modal-backdrop fade in" id="back_fade" style="display:none;"></div>
	<div aria-hidden="false" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" class="modal hide fade in" id="myModalIS" style="display: none; margin-top:30px;">
			<div class="modal-header">
				<span id="CloseMe" onclick="return CloseMe();" style="float: right; color: gray; font-weight: bold;">X</span>
				<div class="popUp_content"></div>
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

            $('#student_classes').DataTable();

            
        });
    </script>
</body>
</html>