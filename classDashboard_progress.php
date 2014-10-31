<?php
	$progress = $_GET['progress'];
	
	if($progress == 'class')
	{
		$query  = "SELECT * FROM students_assigned_classes WHERE sid='$sid' AND class_id='$enrolled_class_id1'";
		$result = mysql_query($query);
		$class_progress = mysql_num_rows($result);
	}
	elseif($progress == 'assignment')
	{
		$query  = "SELECT * FROM quiz_assigned_students WHERE student_id='$sid' AND class_id='$enrolled_class_id1'";
		$result = mysql_query($query);
		$getQUZ = mysql_num_rows($result);
		
		//-class-------------
		$query21  = "SELECT * FROM students_assigned_classes WHERE sid='$sid' AND class_id='$enrolled_class_id1'";
		$result21 = mysql_query($query21);
		$getCLAS = mysql_num_rows($result21);
		
		$class_progress = $getQUZ + $getCLAS;
	}
	elseif($progress == 'skill') // for skill----------
	{
		header('Location: skillProgressClassDash.php?Qid='.$enrolled_class_id1);
		/*$query  = "SELECT * FROM skills_grade_calculation WHERE student_id='$sid' AND class_id='$enrolled_class_id1'";
		$result = mysql_query($query);
		$class_progress = mysql_num_rows($result);*/
	}
	else
	{
		$query  = "SELECT * FROM students_assigned_classes WHERE sid='$sid' AND class_id='$enrolled_class_id1'";
		$result = mysql_query($query);
		$class_progress = mysql_num_rows($result);
	}
?>
