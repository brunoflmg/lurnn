<?php
	$grade_level = $_SESSION['grade_level'];
	$enrolled_class_id = $_SESSION['enroll_class_id'];
	//--Count The CLasses
		$query  = sprintf("SELECT t1.*,t2.user_name,t2.fname,t2.lname,t2.email FROM classes as t1 LEFT JOIN teachers as t2 ON t1.creator_tid=t2.tid WHERE t1.grade_level='%s' AND t1.class_id = '$enrolled_class_id1'",$_SESSION['grade_level'],$student_email );
		$result = mysql_query($query);
		$classes_count = mysql_num_rows($result);
	
	//---COUNT UPCOMING ASSIGNMENTS-----------------------
		$sqlxx = "SELECT quizes.*, classes.* FROM quizes INNER JOIN classes ON quizes.class_id = classes.class_id Where classes.grade_level = '$grade_level' AND quizes.class_id = '$enrolled_class_id1' AND quizes.is_aptitude = '0' ORDER BY quizes.quiz_id DESC";
		$resultxx = mysql_query($sqlxx);
		while($feedquiz  = mysql_fetch_object($resultxx))
		{
			$feedquizx[] = $feedquiz;
		}
		foreach ($feedquizx as $assigned_classx) 
		{
			$quzzID  =  $assigned_classx->quiz_id;
			$sqlz = "SELECT * from quiz_assigned_students WHERE quiz_id = $quzzID and status = 'untaken'";
			$resulz = mysql_query($sqlz);
			$totQuiz   = mysql_num_rows($resulz);
		}
		//$totQuiz   = mysql_num_rows($resultxx);
		
		$queryes  = sprintf("SELECT essays.*, class_students.* FROM essays INNER JOIN class_students ON essays.class_id = class_students.class_id Where class_students.email = '$student_email' AND essays.class_id = '$enrolled_class_id1' ORDER BY essays.essay_id DESC");
		$resultes = mysql_query($queryes);
		$totEssay = mysql_num_rows($resultes);
		
		$totUpcoming = $totQuiz + $totEssay;
 
?>
<div id="main-stats" style="margin-top:99px;">
                <div class="row-fluid stats-row">
                    <div class="span3 stat">
                        <div class="data">
                            <span class="number">
							<?php if($classes_count) { echo $classes_count;} else { echo "0";}?></span>
                            Classes
                        </div>
                        <?/*<span class="date">Today</span>*/?>
                    </div>
                    <div class="span3 stat">
                        <div class="data">
                            <span class="number">
							<?php if($totUpcoming) { echo $totUpcoming;} else { echo "0";} ?></span>
                            Upcoming Assignments
                        </div>
                        <?/*<span class="date">February 2014</span>*/?>
                    </div>
                    <div class="span3 stat last">
                        <div class="data">
                            <span class="number">+
							<?php if($class_progress) { echo $class_progress;} else{ echo "0";} ?>
							%</span>
                            Progress
                        </div>
                        <span class="date">last 14 days</span>
                    </div>
                </div>
            </div>