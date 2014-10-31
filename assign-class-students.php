<?php
    require_once "config.php";
    checkLogin();

    $query = sprintf("SELECT * FROM classes WHERE creator_tid='%s'",$_SESSION['tid']);
    $result = mysql_query($query);
    if(mysql_num_rows($result)==0){
        $error_message['no_classes_found'] = "You don't have any created class. Please create class at first. You are being redirected to create class with few seconds.<script>setTimeout(function(){window.location='create_class.php';},3000);</script>";
    }else{
        while($class = mysql_fetch_object($result)){
            $created_classes[$class->class_id] = $class;
            $class_grade_level[$class->class_id] = $class->grade_level;
        }
    }

    
    if(isset($_POST['assign_student'])){
        //d($_POST,1);
        $fname          = mysql_real_escape_string(trim($_POST['fname']));
        $lname          = mysql_real_escape_string(trim($_POST['lname']));
        $email          = mysql_real_escape_string(trim($_POST['email']));
        $class_id       = mysql_real_escape_string(trim($_POST['class_id']));
        $grade_level    = mysql_real_escape_string(trim($_POST['grade_level']));
        $description    = $grade_level;
        $fcat_score     = 0;
        $students       = $_POST['students'];

        if($fname!="" && $lname!="" && $email!=""){
            $students[] = $fname."___".$lname."___".$email;
        }
        $success=0;
        $fail=0;
        if(is_array($students) && !empty($students)){
            foreach($students as $student){
                 $student_info = explode("___", $student);
                 $sql    = sprintf("SELECT * FROM class_students WHERE class_id='%s' AND email='%s'",$class_id,$student_info[2]);
                 $result = mysql_query($sql);
                 if(mysql_num_rows($result)==0){
                    $query = sprintf("INSERT INTO class_students (fname,lname,email,class_id,creator_tid,grade_level,fcat_score,description,created_date) VALUES('%s','%s','%s','%s','%s','%s','%s','%s','%s')",
                        $student_info[0],
                        $student_info[1],
                        $student_info[2],
                        $class_id,
                        $_SESSION['tid'],
                        $grade_level,
                        $fcat_score,
                        $description,
                        date("Y-m-d H:i:s")
                        );
                    if(mysql_query($query)){
                        $success++;
                    }else{
                        $$fail++;
                    }
                 }

            }
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
    <link rel="stylesheet" type="text/css" href="css/select2.css">    

    <!-- open sans font -->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800' rel='stylesheet' type='text/css'>

    <!-- lato font -->
    <link href='http://fonts.googleapis.com/css?family=Lato:300,400,700,900,300italic,400italic,700italic,900italic' rel='stylesheet' type='text/css'>

    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    <link rel="shortcut icon" href="./img/favicon.jpg"> 
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

            <div id="pad-wrapper" class="form-page">
                <div class="row-fluid form-wrapper">
                    <!-- left column -->
                    <?php
                        if($fail>0){
                            ?>
                            <div class="alert alert-danger">
                              <button data-dismiss="alert" class="close" type="button">×</button>
                              Sorry! We could not assign <?php echo $fail;?> student(s), you may already assign in this class
                            </div>
                            <?php
                        }
                        if($success>0){
                            ?>
                            <div class="alert alert-success">
                            <button data-dismiss="alert" class="close" type="button">×</button>
                             You have successfully assigned <?php echo $success;?> student(s)
                            </div>
                            <?php
                        } 
                    ?>
                    <h3 style="margin-bottom:20px;">Assign Students in Your Class</h3>
                    <form method="post" action="assign-class-students.php">
                    <div class="span7 column">
                            
                            <div class="field-box">
                                <label>Student First Name:</label>
                                <input type="text" name="fname" placeholder="Student First Name"  class="input-xxlarge">
                            </div>
                            <div class="field-box">
                                <label>Student Last Name:</label>
                                <input type="text" name="lname" placeholder="Student Last Name"  class="input-xxlarge">
                            </div>
                            <div class="field-box">
                                <label>Student Email Address:</label>
                                <input type="text" name="email" placeholder="Student Email Address" class="input-xxlarge">
                            </div>
                            <div class="field-box" id="choose_students_box">

                            </div>
                            <div class="field-box">
                                <div class="span6 offset6" style="margin-top:40px;">
                                    <input type="submit" name="assign_student" value="Assign Student" class="btn-flat"/>
                            
                                </div>
                            </div>     
                    </div>
                    <div class="span5 column pull-right">
                           <div class="field-box">
                                <label>Choose Your Class:</label>
                                 <div class="ui-select" style="width:300px;">
                                    <select name="class_id" required="required" id="form_class_id">
                                      <option selected="selected">Choose your Class</option>
                                      <?php foreach ($created_classes as $created_class) { ?>
                                       <option value="<?php echo $created_class->class_id;?>"><?php echo $created_class->class_name;?> (<?php echo $created_class->class_code ;?>) </option>
                                          
                                   <?php   } ?>
                                           
                                            
                                    </select>
                                </div>
                                
                            </div>
                            <div class="field-box" id="form_grade_level_box">
                            </div>
                            
                            
                            
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php include "includes/ajax-loader.php";?>

	<script src="js/wysihtml5-0.3.0.js"></script>
    <script src="http://code.jquery.com/jquery-latest.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/bootstrap-wysihtml5-0.0.2.js"></script>
    <script src="js/bootstrap.datepicker.js"></script>
    <script src="js/jquery.uniform.min.js"></script>
    <script src="js/select2.min.js"></script>
    <script src="js/theme.js"></script>
    <script type="text/javascript">
        var class_grade_level = new Array();
        var content = "";
        <?php foreach ($class_grade_level as $class_id=>$grade_level) { ?>
             class_grade_level["class_<?php echo $class_id;?>"] = '<?php echo $grade_level;?>';                              
         <?php } ?>
    
    </script>
    <script type="text/javascript">

        $(function () {


            $("#form_class_id").change(function(event) {
                $("#ajax-modal").modal('show');
                var class_id= $("#form_class_id").val();
                if(typeof class_grade_level["class_"+class_id] =="undefined"){
                    return false;
                }
                content  = '<label>Grade Level:</label><div class="ui-select" style="width:300px;"><select name="grade_level" id="form_grade_level">';
                content+='<option value="'+class_grade_level["class_"+class_id]+'">'+class_grade_level["class_"+class_id]+'</option>';
                content +='</select></div>';
                //console.log(content);

                $('#form_grade_level_box').html(content);
                
                $.ajax({
                    type: "POST",
                    url: "teacher-ajax.php",
                    data: { action: "get_students_by_grade_level", class_id:class_id, grade_level: class_grade_level["class_"+class_id] }
                })
                .done(function( msg ) {
                    if(msg!="no"){
                        $('#choose_students_box').html(msg);
                        $("#choose_students_level").select2({
                            placeholder: "Choose Students"
                        });
                    }
                    $("#ajax-modal").modal('hide');
                });
                
            });
          
        });
    
    </script>
</body>
</html>