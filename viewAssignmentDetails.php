<?php
    require_once "config.php";
    checkStudentLogin();
    $sid    = $_SESSION['sid'];
	$student_email = $_SESSION['email'];
	$str = $_POST['str'];

    $query  = sprintf("SELECT * from upcoming_assignments WHERE asign_id = '$str'");
    $result = mysql_query($query);
    $row    = mysql_fetch_object($result);
?>
	<div class="ajax_pop_content">
		<h1 style="">Assignment Details</h1>
		<span class="line_one"><b><?php echo $row->asign_name."</b> Of Type <b>".$row->asign_type;?></b></span>
		<span class="line_two">
			<?php
				$classID =  $row->class_id;
				$sql = "SELECT * FROM `classes` WHERE class_id ='$classID'";
				$result = mysql_query($sql);
				$class = mysql_fetch_object($result);
				echo "<b>Class Name: </b>".$class->class_name;
			?>
		</span>
		<span><?php echo "<b>&nbsp;Due Date:</b> ".$row->due_date;?></span>
		<span class="line_two">
			<?php
				$teachrID =  $row->user_id;
				$sql = "SELECT * FROM `teachers` WHERE tid ='$teachrID'";
				$result = mysql_query($sql);
				$class = mysql_fetch_object($result);
				echo "<b>Created By: </b>".$class->fname." ".$class->lname;
			?>
		</span>
		<span class="line_three"><?php echo $row->asign_content;?></span>
	</div>