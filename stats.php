<?php
	$grade_level = $_SESSION['grade_level'];
	//--Count The CLasses
		$query  = sprintf("SELECT classes.*, teachers.* FROM classes LEFT JOIN teachers ON classes.creator_tid = teachers.tid WHERE classes.grade_level = '$grade_level' "); 
		$result = mysql_query($query);
		$classes_count = mysql_num_rows($result);
	
	//---COUNT UPCOMING ASSIGNMENTS-----------------------
		$sqlQuiz = "SELECT quizes.*, classes.* FROM quizes INNER JOIN classes ON quizes.class_id = classes.class_id Where classes.grade_level = '$grade_level' and quizes.is_aptitude = '0' ORDER BY quizes.quiz_id DESC";
		$resultQuiz = mysql_query($sqlQuiz);
		$totQuiz = mysql_num_rows($resultQuiz);
		
		$queryEssay  = sprintf("SELECT essays.*, class_students.* FROM essays INNER JOIN class_students ON essays.class_id = class_students.class_id Where class_students.email = '$student_email' ORDER BY essays.essay_id DESC");
		$resultEssay = mysql_query($queryEssay);
		$totEssay = mysql_num_rows($resultEssay);
		
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
							<?php if($class_progress) { echo $class_progress;} else { echo "0";} ?>
							%</span>
                            Progress
                        </div>
                        <span class="date">last 14 days</span>
                    </div>
                </div>
            </div>