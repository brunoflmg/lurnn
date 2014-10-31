<?php
	$progress = $_GET['progress'];
	
	if($progress == 'class')
	{
		$query  = "SELECT * FROM teacher_class_stats WHERE creator_tid='$tid'";
		$result = mysql_query($query);
		$assigned_class  = mysql_fetch_object($result);
		$class_progress = $assigned_class->total_class;
	}
	elseif($progress == 'student')
	{
		$sqlSTU    = "SELECT * from teacher_student_stats where creator_tid = '$tid'";
		$resultSTU = mysql_query($sqlSTU);
		$students  = mysql_fetch_object($resultSTU);
		$class_progress = $students->total_students;
	}
	elseif($progress == 'assignment')
	{
		$query  = "SELECT * FROM teacher_quiz_stats WHERE creator_tid='$tid'";
		$result = mysql_query($query);
		$assigned_class  = mysql_fetch_object($result);
		$getQUZ = $assigned_class->total_quiz;
		
		//-class-------------
		$query  = "SELECT * FROM teacher_class_stats WHERE creator_tid='$tid'";
		$result = mysql_query($query);
		$assigned_class  = mysql_fetch_object($result);
		$getCLAS = $assigned_class->total_class;
		
		$class_progress = $getQUZ + $getCLAS;
	}
	elseif($progress == 'skill') // for skill----------
	{
		header('Location: teacherClassDashSkillProgress.php?Qid='.$enrolled_class_id1);
		/*$query  = "SELECT * FROM skills_grade_calculation WHERE creator_tid='$tid'";
		$result = mysql_query($query);
		$class_progress = mysql_num_rows($result);*/
	}
	else
	{
		$query  = "SELECT * FROM teacher_class_stats WHERE creator_tid='$tid'";
		$result = mysql_query($query);
		$assigned_class  = mysql_fetch_object($result);
		$class_progress = $assigned_class->total_class;
	}
?>
