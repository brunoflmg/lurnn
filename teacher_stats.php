<?php
   //--Count The CLasses
		$sql    = sprintf("SELECT t1.* FROM classes as t1 WHERE t1.creator_tid='%s'",$tid);
		$result = mysql_query($sql);
		$classes_count = mysql_num_rows($result);
	
	//--COUNT STUDENT BY THIS TEACHER-------------------------------
		$sqlSTU    = sprintf("SELECT * from teacher_student_stats where creator_tid = '$tid'");
		$resultSTU = mysql_query($sqlSTU);
		$students = mysql_fetch_object($resultSTU);
		
	//--COUNT UPCOMING ASSIGNMENTS---------------------
		$sql1 = "SELECT quizes.*, teachers.* FROM quizes INNER JOIN teachers ON quizes.creator_tid = teachers.tid Where quizes.creator_tid = '$user_id'";
        $result1 = mysql_query($sql1);
		$totQuiz = mysql_num_rows($result1);
		
		$sql2 = "SELECT essays.*, teachers.* FROM essays INNER JOIN teachers ON essays.user_id = teachers.tid Where essays.user_id = '$user_id'";
        $result2 = mysql_query($sql2);
		$totEssay = mysql_num_rows($result2);
		
		$totUpcoming = $totQuiz + $totEssay;
		
?>
<div id="main-stats" style="margin-top:99px;">
                <div class="row-fluid stats-row">
                    <div class="span3 stat">
                        <div class="data">
                            <span class="number">
							<?php if($classes_count) { echo $classes_count; } else { echo "0"; }?></span>
                            Classes
                        </div>
                        <?/*<span class="date">Today</span>*/?>
                    </div>
                    <div class="span3 stat">
                        <div class="data">
                            <span class="number">
							<?php if($students->total_students) { echo $students->total_students;} else { echo "0";}?></span>
                            Students
                        </div>
                        <?/*<span class="date">This quarter</span>*/?>
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
                            <span class="number" id="progress_teacher">+
							<? if($class_progress) { echo $class_progress; } else { echo "0"; }  ?>%</span>
                            Progress
                        </div>
                        <?/*<span class="date">last 30 days</span>*/?>
                    </div>
                </div>
            </div>