<?php
    require_once "config.php";
    checkLogin();
    
    $tid    = $_SESSION['tid'];
    $sql    = sprintf("SELECT t1.* FROM classes as t1 WHERE t1.creator_tid='%s'",$tid);
    $result = mysql_query($sql);
    while($class = mysql_fetch_object($result)){
        $classes[$class->class_id] = $class;
    }
    //d($classes,1);
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
    <link href="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap-editable/css/bootstrap-editable.css" rel="stylesheet"/>
    <style>
       select{
        height:30px;
       }
    </style>
</head>
<body>

   

   
    <?php include "includes/menu.php"; ?>
    <?php include "includes/left_side_bar.php"; ?>



	<!-- main container -->
    <div class="content">

        
        <div class="container-fluid">

            <!-- upper main stats -->
            <?php //include "stats.php";?>
            <!-- end upper main stats -->

            <div id="pad-wrapper">

                <!-- statistics chart built with jQuery Flot -->
                <div class="row-fluid chart">
                   <table id="class_lists" class="table display" width="100%">
        <thead>
          <tr>
            <th>Class Name</th>
            <th>Class Details</th>
            <th>Class Code</th>
            <th>Grade Level</th>
            <th>Meeting Hous</th>
            <th>No of Students</th>
            <th>Start date</th>
            <th>Action</th>
          </tr>
        </thead>

        <tfoot>
          <tr>
            <th>Class Name</th>
            <th>Class Details</th>
            <th>Class Code</th>
            <th>Grade Level</th>
            <th>Meeting Hous</th>
            <th>No of Students</th>
            <th>Start date</th>
            <th>Action</th>
          </tr>
        </tfoot>

        <tbody>
        <?php if(is_array($classes)&&!empty($classes)){ 
           foreach($classes as $class){
          ?>
          <tr id="class_<?php echo $class->class_id;?>">
           <td>
              <?/*<a href="#" class="editable" data-name="class_name" data-type="text" data-pk="<?php echo $class->class_id;?>" data-url="/teacher-ajax.php?action=edit_class" data-title="Enter Class Name"><?php echo $class->class_name;?></a>*/?>
			  <a href="teacher_class_dashboard.php?Qid=<?php echo $class->class_id;?>">
				<?php echo $class->class_name;?>
			  </a>
           </td>
            <td>
              <a href="#" class="editable" data-name="class_details" data-type="textarea" data-pk="<?php echo $class->class_id;?>" data-url="/teacher-ajax.php?action=edit_class" data-title="Enter Class Details"><?php echo $class->class_details;?></a>
            </td>
            <td>
            
            <a href="#" class="editable" data-name="class_code" data-type="text" data-pk="<?php echo $class->class_id;?>" data-url="/teacher-ajax.php?action=edit_class" data-title="Enter Class Code"><?php echo $class->class_code;?></a>
            </td>
            <td>
              <a href="#" class="editable" data-name="grade_level" data-value="<?php echo $class->grade_level;?>" data-type="select" data-source='{"6th Grade": "6th Grade", "7th Grade": "7th Grade","8th Grade":"8th Grade","9th Grade":"9th Grade","10th Grade":"10th Grade","11th Grade":"11th Grade","12th Grade":"12th Grade" }' data-pk="<?php echo $class->class_id;?>" data-url="/teacher-ajax.php?action=edit_class" data-title="Choose Class Grade Level"><?php echo $class->grade_level;?></a>
            </td>
            <td>
              <a href="#" class="editable" data-name="meeting_hours" data-type="time" data-pk="<?php echo $class->class_id;?>" data-url="/teacher-ajax.php?action=edit_class" data-title="Enter Class Time"><?php echo $class->meeting_hours;?></a>
            </td>
            <td>
              <a href="#" class="editable" data-name="no_of_students" data-type="number" data-pk="<?php echo $class->class_id;?>" data-url="/teacher-ajax.php?action=edit_class" data-title="No of students"><?php echo $class->no_of_students;?></a>
            </td>
            <td><?php echo $class->created_date;?></td>
            <td><a href="#" class="btn btn-danger" onclick="deleteClass('<?php echo $class->class_id;?>')">Delete</a></td>
          </tr>
          <?php }
          } ?>
          </tbody>
          </table>
    </div>
            </div>
        </div>
    </div>


	<!-- scripts -->
    <script src="http://code.jquery.com/jquery-latest.js"></script>
    <script src="//datatables.net/download/build/nightly/jquery.dataTables.js"></script>

    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery-ui-1.10.2.custom.min.js"></script>
    <!-- knob -->
    <script src="js/jquery.knob.js"></script>

    <script src="js/theme.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap-editable/js/bootstrap-editable.min.js"></script>
    <script src="js/bootbox.min.js"></script>
    <script type="text/javascript">
       function deleteClass(class_id){
          bootbox.confirm("Are you sure to delete this class?", function(result) {
                if(result==false) return;
                $.ajax({
                  type: "POST",
                  url: "teacher-ajax.php",
                  data: { action: "delete_class", class_id: class_id }
                })
                .done(function( msg ) {
                   if(msg=="yes"){
                      bootbox.alert("<h3 class='text-success'>You have successfully deleted this class.</h3>");
                      $("#class_"+class_id).remove();
                   }else{
                      bootbox.alert("<h3 class='text-danger'>Sorry!! we could not delete this class.Please try again later.</h3>");
                   }
                });
          }); 
       }
    </script>
    <script type="text/javascript">
        $(function () {

            // jQuery Knobs
            $(".knob").knob();
            
            $.fn.editable.defaults.mode = 'popup';
            $('.editable').editable({
              validate: function(value) {
                if($.trim(value) == '') {
                  return 'This field is required';
                }
              },
              success: function(response, newValue) {
                //console.log(response);
                //console.log(response.status);
                //console.log(response.msg);
                if(response.status == 'error') return response.msg; //msg will be shown in editable form
              }
            });
            $('#class_lists').DataTable();
        });
    </script>
</body>
</html>