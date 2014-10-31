<?php
    require_once "config.php";
    checkLogin();
    
    $tid    = $_SESSION['tid'];
    $sql    = sprintf("SELECT t1.* FROM classes as t1 WHERE t1.creator_tid='%s'",$tid);
    $result = mysql_query($sql);
    while($class = mysql_fetch_object($result)){
        $classes[$class->class_id] = $class;
        $classes_data[$class->class_id] = $class->class_name." (".$class->class_code.")";
    }
    $sql    = sprintf("SELECT t1.*,t2.user_name FROM class_students as t1 LEFT JOIN students as t2 ON t1.email=t2.email WHERE t1.creator_tid='%d'",$tid) ;
    //d($classes,1);
    $result = mysql_query($sql);
    while($class_student = mysql_fetch_object($result)){
        $class_students[$class_student->student_id] = $class_student;
    }
    $data_source = json_encode($classes_data);
    //d($data_source,1);
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
                   <table id="class_lists" class="display" width="100%">
        <thead>
          <tr>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Username</th>
            <th>Email</th>
            <th>Class</th>
            <th>Grade Level</th>
            <th>Fcat Score</th>
            <th>Description</th>
            <th>Start date</th>
            <th>Action</th>
          </tr>
        </thead>

        <tfoot>
         <tr>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Username</th>
            <th>Email</th>
            <th>Class</th>
            <th>Grade Level</th>
            <th>Fcat Score</th>
            <th>Description</th>
            <th>Start date</th>
            <th>Action</th>
          </tr>
        </tfoot>

        <tbody>
        <?php if(is_array($class_students)&&!empty($class_students)){ 
           foreach($class_students as $class_student){
          ?>
          <tr id="class_student_<?php echo $class_student->student_id;?>">
           <td>
              <a href="#" class="editable" data-name="fname" data-type="text" data-pk="<?php echo $class_student->student_id;?>" data-url="/teacher-ajax.php?action=edit_student" data-title="Student First Name"><?php echo $class_student->fname;?></a>
           </td>
            <td>
              <a href="#" class="editable" data-name="lname" data-type="text" data-pk="<?php echo $class_student->student_id;?>" data-url="/teacher-ajax.php?action=edit_student" data-title="Student Last Name"><?php echo $class_student->lname;?></a>
            </td>
             <td>
              <?php echo $class_student->user_name;?>
            </td>
            <td>
            
            <a href="#" class="editable" data-name="email" data-type="email" data-pk="<?php echo $class_student->student_id;?>" data-url="/teacher-ajax.php?action=edit_student" data-title="Student Email Address"><?php echo $class_student->email;?></a>
            </td>
            <td>
            
            <a href="#" class="editable" data-name="class_id" data-type="select" data-pk="<?php echo $class_student->student_id;?>" data-url="/teacher-ajax.php?action=edit_student" data-source='<?php echo $data_source;?>' <?php if(isset($classes[$class_student->class_id])){ ?> data-value="<?php echo $class_student->class_id;?>" <?php } ?> data-title="Choose Class to assign"><?php if(isset($classes[$class_student->class_id])){ ?><?php echo $classes[$class_student->class_id]->class_name." (".$classes[$class_student->class_id]->class_code.")";?> <?php } ?></a>
            </td>
            <td>
              <a href="#" class="editable" data-name="grade_level" data-value="<?php echo $class_student->grade_level;?>" data-type="select" data-source='{"6th Grade": "6th Grade", "7th Grade": "7th Grade","8th Grade":"8th Grade","9th Grade":"9th Grade","10th Grade":"10th Grade","11th Grade":"11th Grade","12th Grade":"12th Grade" }' data-pk="<?php echo $class_student->student_id;?>" data-url="/teacher-ajax.php?action=edit_student" data-title="Choose Student Grade Level"><?php echo $class_student->grade_level;?></a>
            </td>
            <td>
              <a href="#" class="editable" data-name="fcat_score" data-type="number" data-pk="<?php echo $class_student->student_id;?>" data-url="/teacher-ajax.php?action=edit_student" data-title="Student Fcat Score"><?php echo $class_student->fcat_score;?></a>
            </td>
            <td>
              <a href="#" class="editable" data-name="description" data-type="textarea" data-pk="<?php echo $class_student->student_id;?>" data-url="/teacher-ajax.php?action=edit_student" data-title="No of students"><?php echo $class_student->description;?></a>
            </td>
            <td><?php echo $class_student->created_date;?></td>
            <td><a href="#" class="btn btn-danger" onclick="deleteStudent('<?php echo $class_student->student_id;?>')">Delete</a></td>
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
       function deleteStudent(student_id){
          bootbox.confirm("Are you sure to delete this Student?", function(result) {
                if(result==false) return;
                $.ajax({
                  type: "POST",
                  url: "teacher-ajax.php",
                  data: { action: "delete_student", student_id: student_id }
                })
                .done(function( msg ) {
                   if(msg=="yes"){
                      bootbox.alert("<h3 class='text-success'>You have successfully deleted this student.</h3>");
                      $("#class_student_"+student_id).remove();
                   }else{
                      bootbox.alert("<h3 class='text-danger'>Sorry!! we could not delete this student.Please try again later.</h3>");
                   }
                });
          }); 
       }
    </script>
    <script type="text/javascript">
        $(function () {

            // jQuery Knobs
            $(".knob").knob();
           
            $.fn.editable.defaults.mode = 'inline';
            //$.fn.editable.defaults.placement = 'right';
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