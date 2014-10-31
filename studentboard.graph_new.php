<?php

	switch ($filter) {
        case 'class':
             switch($duration){
                case "year" :
                    $sql     = sprintf("SELECT class_id,
                                        class_name,
                                        class_code,
                                        class_grade_level,
                                        (sum(grade)/count(quiz_id)) as grade,
                                        YEAR( quiz_holding_date_time ) as exam_year
                                    FROM student_grade 
                                    WHERE quiz_id IS NOT NULL AND sid='%s' AND YEAR(quiz_holding_date_time) = YEAR(CURDATE())
                                    GROUP BY class_id, exam_year
                               ",$sid);
					
                    $result = mysql_query($sql);
                    //d($sql,1);
					          $graph_data[] = '["Class Name","Class Average Grade",{ role: "style" }]';
          					$table_data[] = '["Class Name","Class Code","Class Grade Level","Class Average Grade"]';
          					while($grade  = mysql_fetch_object($result)){

          					  $graph_data[] = '["'.$grade->class_name." (".$grade->class_code.')",'.ceil($grade->grade).',"'.str_pad( dechex( mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT). str_pad( dechex( mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT).str_pad( dechex( mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT).'"]';
          					  $table_data[] = '["'.$grade->class_name.'", "'.$grade->class_code.'","'.$grade->class_grade_level.'",'.ceil($grade->grade).']';
          					  
          					}
          					$data_title  = "Yearly Class Average Progress,".$year;
          					$graph_data  = "[".implode(",", $graph_data)."]";
          					$table_data  = "[".implode(",", $table_data)."]";
          					$graph_type  = "ColumnChart";
          					$hAxis_title = "CLasses";
          					$vAxis_title = "Grade %";
                    break;

                case "semester" :
                  $sql     = sprintf("SELECT class_id,
                                        class_name,
                                        class_code,
                                        class_grade_level,
                                        (sum(grade)/count(quiz_id)) as grade,
                                        YEAR( quiz_holding_date_time ) as exam_year,
                                        CEIL(MONTH(quiz_holding_date_time)/6) as exam_semester
                                    FROM student_grade 
                                    WHERE quiz_id IS NOT NULL AND sid='%s' AND YEAR(quiz_holding_date_time) = YEAR(CURDATE())
                                    GROUP BY class_id , exam_year, exam_semester ORDER BY exam_semester
                               ",$sid);
                  
                    $result = mysql_query($sql);
                    //d($sql,1);
          					$graph_data[] = '["Class Name With Semester","Class Average Grade",{ role: "style" }]';
          					$table_data[] = '["Class Name","Semester","Class Code","Class Grade Level","Class Average Grade"]';
          					while($grade  = mysql_fetch_object($result)){

          					  $graph_data[] = '[" Semester '.$grade->exam_semester." : ".$grade->class_name." (".$grade->class_code.')",'.ceil($grade->grade).',"'.str_pad( dechex( mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT). str_pad( dechex( mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT).str_pad( dechex( mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT).'"]';
          					  $table_data[] = '["'.$grade->class_name.'", "'.$grade->exam_semester.'","'.$grade->class_code.'","'.$grade->class_grade_level.'",'.ceil($grade->grade).']';
          					  
          					}
          					$data_title  = "Semesterwise Class Average Progress, ".$year;
          					$graph_data  = "[".implode(",", $graph_data)."]";
          					$table_data  = "[".implode(",", $table_data)."]";
          					$graph_type  = "ColumnChart";
          					$hAxis_title = "CLasses with Semester";
          					$vAxis_title = "Grade %";
                  break;
                case "quarter" :
                  $sql     = sprintf("SELECT class_id,
                                        class_name,
                                        class_code,
                                        class_grade_level,
                                        (sum(grade)/count(quiz_id)) as grade,
                                        YEAR( quiz_holding_date_time ) as exam_year,
                                        QUARTER( quiz_holding_date_time ) as exam_quarter
                                    FROM student_grade 
                                    WHERE quiz_id IS NOT NULL AND sid='%s' AND YEAR(quiz_holding_date_time) = YEAR(CURDATE())
                                    GROUP BY class_id , exam_year , exam_quarter ORDER BY exam_quarter
                               ",$sid);

                    $result = mysql_query($sql);
                    $graph_data[] = '["Class Name with Quarter","Class Average Grade",{ role: "style" }]';
                    $table_data[] = '["Class Name","Quarter","Class Code","Class Grade Level","Class Average Grade"]';
                    while($grade  = mysql_fetch_object($result)){
                      //d($grade,1);
                      $graph_data[] = '[" Quarter '.$grade->exam_quarter." : ".$grade->class_name." (".$grade->class_code.')",'.ceil($grade->grade).',"'.str_pad( dechex( mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT). str_pad( dechex( mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT).str_pad( dechex( mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT).'"]';
                      $table_data[] = '["'.$grade->class_name.'", "'.$grade->exam_quarter.'","'.$grade->class_code.'","'.$grade->class_grade_level.'",'.ceil($grade->grade).']';
                      
                    }
                    //d($graph_data);
                    //d($table_data,1);
                    $data_title  = "Quarterly Class Average Progress, ".$year;
                    $graph_data  = "[".implode(",", $graph_data)."]";
                    $table_data  = "[".implode(",", $table_data)."]";
                    $graph_type  = "ColumnChart";
                    $hAxis_title = "CLasses with Quarter";
                    $vAxis_title = "Grade %";
                  break;
                case "month" :
                  $sql     = sprintf("SELECT class_id,
                                        class_name,
                                        class_code,
                                        class_grade_level,
                                        (sum(grade)/count(quiz_id)) as grade,
                                        YEAR( quiz_holding_date_time ) as exam_year,
                                        MONTHNAME( quiz_holding_date_time ) as exam_month
                                    FROM student_grade 
                                    WHERE quiz_id IS NOT NULL AND sid='%s' AND YEAR(quiz_holding_date_time) = YEAR(CURDATE())
                                    GROUP BY class_id , exam_year , exam_month ORDER BY MONTH(quiz_holding_date_time)
                               ",$sid);
                    $result = mysql_query($sql);
                    $graph_data[] = '["Class Name with Month","Class Average Grade",{ role: "style" }]';
                    $table_data[] = '["Class Name","Month","Class Code","Class Grade Level","Class Average Grade"]';
                    while($grade  = mysql_fetch_object($result)){

                      $graph_data[] = '[" Month '.$grade->exam_month." : ".$grade->class_name." (".$grade->class_code.')",'.ceil($grade->grade).',"'.str_pad( dechex( mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT). str_pad( dechex( mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT).str_pad( dechex( mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT).'"]';
                      $table_data[] = '["'.$grade->class_name.'", "'.$grade->exam_month.'","'.$grade->class_code.'","'.$grade->class_grade_level.'",'.ceil($grade->grade).']';
                      
                    }
                    $data_title  = "Monthy Class Average Progress, ".$year;
                    $graph_data  = "[".implode(",", $graph_data)."]";
                    $table_data  = "[".implode(",", $table_data)."]";
                    $graph_type  = "ColumnChart";
                    $hAxis_title = "CLasses with Month";
                    $vAxis_title = "Grade %";
                  break;
                case "week" :
                  $sql     = sprintf("SELECT class_id,
                                        class_name,
                                        class_code,
                                        class_grade_level,
                                        (sum(grade)/count(quiz_id)) as grade,
                                        YEAR( quiz_holding_date_time ) as exam_year,
                                        WEEK( quiz_holding_date_time ) as exam_week
                                    FROM student_grade 
                                    WHERE quiz_id IS NOT NULL AND sid='%s' AND YEAR(quiz_holding_date_time) = YEAR(CURDATE())
                                    GROUP BY class_id , exam_year , exam_week ORDER BY exam_week
                               ",$sid);
                    $result = mysql_query($sql);
                    $graph_data[] = '["Class Name with Week","Class Average Grade",{ role: "style" }]';
                    $table_data[] = '["Class Name","Week","Class Code","Class Grade Level","Class Average Grade"]';
                    while($grade  = mysql_fetch_object($result)){

                      $graph_data[] = '[" Week '.$grade->exam_week." : ".$grade->class_name." (".$grade->class_code.')",'.ceil($grade->grade).',"'.str_pad( dechex( mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT). str_pad( dechex( mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT).str_pad( dechex( mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT).'"]';
                      $table_data[] = '["'.$grade->class_name.'", "'.$grade->exam_week.'","'.$grade->class_code.'","'.$grade->class_grade_level.'",'.ceil($grade->grade).']';
                      
                    }
                    $data_title  = "Weekly Class Average Progress, ".$year;
                    $graph_data  = "[".implode(",", $graph_data)."]";
                    $table_data  = "[".implode(",", $table_data)."]";
                    $graph_type  = "ColumnChart";
                    $hAxis_title = "CLasses with Week";
                    $vAxis_title = "Grade %";
                  break;
             }
             
            break;
        case 'quiz':

             switch($duration){
                case "year" :
					$sql = "SELECT quizes.*, classes.* FROM quizes INNER JOIN classes ON quizes.class_id = classes.class_id ORDER BY quizes.quiz_id DESC";
					$result = mysql_query($sql);
                    while($feedquiz  = mysql_fetch_object($result))
					{
						$feedquizs[] = $feedquiz;
					}
					if(is_array($feedquizs) && !empty($feedquizs))
							{
								$i=1;
							   foreach ($feedquizs as $assigned_class) 
								{
									$quzID = $assigned_class->quiz_id;
									$sql = "SELECT * FROM quiz_answers Where student_id = '$sid' && quiz_id = '$quzID'";
									$result = mysql_query($sql);
									$quzGet = mysql_fetch_object($result);
									$countQZ = mysql_num_rows($result);
									if($countQZ !='0')
									{
										//--Get points------------
											$qTaken = "SELECT Year(created_time) as cre_year ,SUM(points) as total, created_time from quiz_answers WHERE student_id = '$sid' Group By Year(created_time) asc";
											$res2 = mysql_query($qTaken);
											while($quz2  = mysql_fetch_object($res2))
											{
												$getTot  = $quz2->total;
												
												/*$date    = $quz2->created_time;
												$time    = strtotime($date);
												$month[] = date("F",$time);*/
												
												$year = $quz2->cre_year;
												
												//--Get quiz out of points---
												$qOUT = "SELECT SUM(points) as total from quizes WHERE quiz_id in (SELECT quiz_id FROM quiz_answers Where student_id = '$sid' Group By quiz_id)";
												$res1 = mysql_query($qOUT);
												$quz1  = mysql_fetch_object($res1);
												$outOf = $quz1->total;

												$pointz[$year] = round($getTot*100 / $outOf, 2);
											}
									} 
								} 
							}
                    $graph_year   = $pointz;
					$years = $year;
                  break;
                case "semester" :
                  $sql = "SELECT quizes.*, classes.* FROM quizes INNER JOIN classes ON quizes.class_id = classes.class_id ORDER BY quizes.quiz_id DESC";
					$result = mysql_query($sql);
                    while($feedquiz  = mysql_fetch_object($result))
					{
						$feedquizs[] = $feedquiz;
					}
					if(is_array($feedquizs) && !empty($feedquizs))
							{
								$i=1;
							   foreach ($feedquizs as $assigned_class) 
								{
									$quzID = $assigned_class->quiz_id;
									$sql = "SELECT * FROM quiz_answers Where student_id = '$sid' && quiz_id = '$quzID'";
									$result = mysql_query($sql);
									$quzGet = mysql_fetch_object($result);
									$countQZ = mysql_num_rows($result);
									if($countQZ !='0')
									{
										//--Get points------------
											$qTaken = "SELECT Month(created_time) as cre_month ,SUM(points) as total, created_time from quiz_answers WHERE student_id = '$sid' AND created_time >= (NOW() - INTERVAL 7 MONTH) Group By Month(created_time) asc";
											$res2 = mysql_query($qTaken);
											while($quz2  = mysql_fetch_object($res2))
											{
												$getTot  = $quz2->total;
												
												/*$date    = $quz2->created_time;
												$time    = strtotime($date);
												$month[] = date("F",$time);*/
												
												$sem = $quz2->cre_month;
												
												//--Get quiz out of points---
												$qOUT = "SELECT SUM(points) as total from quizes WHERE quiz_id in (SELECT quiz_id FROM quiz_answers Where student_id = '$sid' Group By quiz_id)";
												$res1 = mysql_query($qOUT);
												$quz1  = mysql_fetch_object($res1);
												$outOf = $quz1->total;

												$pointz[$sem] = round($getTot*100 / $outOf, 2);
											}
									} 
								} 
							}
                    $graph_sem   = $pointz;
                  break;
                case "quarter" :
                  $sql     = sprintf("SELECT class_id,
                                        class_name,
                                        class_code,
                                        class_grade_level,
                                        quiz_id,
                                        quiz_subject,
                                        (sum(grade)/count(quiz_id)) as grade,
                                        YEAR( quiz_holding_date_time ) as exam_year,
                                        QUARTER( quiz_holding_date_time ) as exam_quarter
                                    FROM student_grade 
                                    WHERE quiz_id IS NOT NULL AND sid='%s' AND YEAR(quiz_holding_date_time) = YEAR(CURDATE())
                                    GROUP BY quiz_id , class_id , exam_year , exam_quarter ORDER BY exam_quarter
                               ",$sid);
                    $result = mysql_query($sql);
                    $graph_data[] = '["Quiz Name with Quarter","Quiz Specific Grade",{ role: "style" }]';
                    $table_data[] = '["Quiz Name","Quiz Class Name","Quarter","Class Code","Class Grade Level","Class Average Grade"]';
                    while($grade  = mysql_fetch_object($result)){

                      $graph_data[] = '["Quarter '.$grade->exam_quarter." : ".$grade->quiz_subject." (".$grade->class_name."-".$grade->class_code.')",'.ceil($grade->grade).',"'.str_pad( dechex( mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT). str_pad( dechex( mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT).str_pad( dechex( mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT).'"]';
                      $table_data[] = '["'.$grade->quiz_subject.'","'.$grade->class_name.'", "'.$grade->exam_quarter.'","'.$grade->class_code.'","'.$grade->class_grade_level.'",'.ceil($grade->grade).']';
                      
                    }
                    $data_title  = "Quarterly Quiz Specific Progress, ".$year;
                    $graph_data  = "[".implode(",", $graph_data)."]";
                    $table_data  = "[".implode(",", $table_data)."]";
                    $graph_type  = "ColumnChart";
                    $hAxis_title = "Specific Quizes ";
                    $vAxis_title = "Grade %";
                  break;
                case "month" :
                  $sql = "SELECT quizes.*, classes.* FROM quizes INNER JOIN classes ON quizes.class_id = classes.class_id ORDER BY quizes.quiz_id DESC";
					$result = mysql_query($sql);
                    while($feedquiz  = mysql_fetch_object($result))
					{
						$feedquizs[] = $feedquiz;
					}
					if(is_array($feedquizs) && !empty($feedquizs))
							{
								$i=1;
							   foreach ($feedquizs as $assigned_class) 
								{
									$quzID = $assigned_class->quiz_id;
									$sql = "SELECT * FROM quiz_answers Where student_id = '$sid' && quiz_id = '$quzID'";
									$result = mysql_query($sql);
									$quzGet = mysql_fetch_object($result);
									$countQZ = mysql_num_rows($result);
									if($countQZ !='0')
									{
										//--Get points------------
											$qTaken = "SELECT Month(created_time) as cre_month ,SUM(points) as total, created_time from quiz_answers WHERE student_id = '$sid' Group By Month(created_time) asc";
											$res2 = mysql_query($qTaken);
											while($quz2  = mysql_fetch_object($res2))
											{
												$getTot  = $quz2->total;
												
												/*$date    = $quz2->created_time;
												$time    = strtotime($date);
												$month[] = date("F",$time);*/
												
												$month = $quz2->cre_month;
												
												//--Get quiz out of points---
												$qOUT = "SELECT SUM(points) as total from quizes WHERE quiz_id in (SELECT quiz_id FROM quiz_answers Where student_id = '$sid' Group By quiz_id)";
												$res1 = mysql_query($qOUT);
												$quz1  = mysql_fetch_object($res1);
												$outOf = $quz1->total;

												$pointz[$month] = round($getTot*100 / $outOf, 2);
											}
									} 
								} 
							}
                    $graph_month   = $pointz;
                  break;
                case "week" :
                  $sql = "SELECT quizes.*, classes.* FROM quizes INNER JOIN classes ON quizes.class_id = classes.class_id ORDER BY quizes.quiz_id DESC";
					$result = mysql_query($sql);
                    while($feedquiz  = mysql_fetch_object($result))
					{
						$feedquizs[] = $feedquiz;
					}
					if(is_array($feedquizs) && !empty($feedquizs))
							{
								$i=1;
							   foreach ($feedquizs as $assigned_class) 
								{
									$quzID = $assigned_class->quiz_id;
									$sql = "SELECT * FROM quiz_answers Where student_id = '$sid' && quiz_id = '$quzID'";
									$result = mysql_query($sql);
									$quzGet = mysql_fetch_object($result);
									$countQZ = mysql_num_rows($result);
									if($countQZ !='0')
									{
										//--Get points------------
											//$qTaken = "SELECT WEEKDAY(created_time) as cre_week ,SUM(points) as total, created_time from quiz_answers WHERE student_id = '$sid' AND MONTH(created_time) = MONTH(NOW()) Group By WEEKDAY(created_time) asc";
											$qTaken = "SELECT DAY(created_time) as cre_week ,SUM(points) as total, created_time from quiz_answers WHERE student_id = '$sid' AND DATE_ADD(NOW(), INTERVAL 7 DAY) AND MONTH(created_time) = MONTH(NOW()) Group By DAY(created_time) asc";
											$res2 = mysql_query($qTaken);
											while($quz2  = mysql_fetch_object($res2))
											{
												$getTot  = $quz2->total;
												
												/*$date    = $quz2->created_time;
												$time    = strtotime($date);
												$month[] = date("F",$time);*/
												
												$week = $quz2->cre_week;
												
												//--Get quiz out of points---
												$qOUT = "SELECT SUM(points) as total from quizes WHERE quiz_id in (SELECT quiz_id FROM quiz_answers Where student_id = '$sid' Group By quiz_id)";
												$res1 = mysql_query($qOUT);
												$quz1  = mysql_fetch_object($res1);
												$outOf = $quz1->total;

												$pointz[$week] = round($getTot*100 / $outOf, 2);
											}
									} 
								} 
							}
                    $graph_week   = $pointz;
					$weekz = $week;
                  break;
             }
             break;


        case 'skill':
             //d($filter,1);
             switch($duration){

                case "year" :
                    $sql     = sprintf("SELECT class_id,
                                        class_name,
                                        class_code,
                                        class_grade_level,
                                        quiz_id,
                                        skills,
                                        (sum(grade)/count(quiz_id)) as grade,
                                        YEAR( quiz_holding_date_time ) as exam_year
                                    FROM student_grade 
                                    WHERE quiz_id IS NOT NULL AND sid='%s' AND YEAR(quiz_holding_date_time) = YEAR(CURDATE())
                                    GROUP BY quiz_id , class_id , skills , exam_year
                               ",$sid);
                    //d($sql,1);
                    $result = mysql_query($sql);
                    $graph_data[] = '["Skills","Skills Specific Grade",{ role: "style" }]';
                    $table_data[] = '["Skills","Skills Class Name","Year","Class Code","Class Grade Level","Class Average Grade"]';
                    while($grade  = mysql_fetch_object($result)){

                      $graph_data[] = '["'.$grade->exam_year." : ".$grade->skills." (".$grade->class_name."-".$grade->class_code.')",'.ceil($grade->grade).',"'.str_pad( dechex( mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT). str_pad( dechex( mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT).str_pad( dechex( mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT).'"]';
                      $table_data[] = '["'.$grade->skills.'","'.$grade->class_name.'", "'.$grade->exam_year.'","'.$grade->class_code.'","'.$grade->class_grade_level.'",'.ceil($grade->grade).']';
                      
                    }
                    $data_title  = "Yearly Skills Specific Progress, ".$year;
                    $graph_data  = "[".implode(",", $graph_data)."]";
                    $table_data  = "[".implode(",", $table_data)."]";
                    $graph_type  = "ColumnChart";
                    $hAxis_title = "Specific Skills ";
                    $vAxis_title = "Grade %";

                  break;
                case "semester" :
                  $sql     = sprintf("SELECT class_id,
                                        class_name,
                                        class_code,
                                        class_grade_level,
                                        quiz_id,
                                        skills,
                                        (sum(grade)/count(quiz_id)) as grade,
                                        YEAR( quiz_holding_date_time ) as exam_year,
                                        CEIL(MONTH(quiz_holding_date_time)/6) as exam_semester
                                    FROM student_grade 
                                    WHERE quiz_id IS NOT NULL AND sid='%s' AND YEAR(quiz_holding_date_time) = YEAR(CURDATE())
                                    GROUP BY quiz_id , class_id , skills , exam_year , exam_semester ORDER BY exam_semester
                               ",$sid);
                    $result = mysql_query($sql);
                    $graph_data[] = '["Skills","Skills Specific Grade",{ role: "style" }]';
                    $table_data[] = '["Skills","Skills Class Name","Semester","Class Code","Class Grade Level","Class Average Grade"]';
                    while($grade  = mysql_fetch_object($result)){

                      $graph_data[] = '["'.$grade->exam_semester." : ".$grade->skills." (".$grade->class_name."-".$grade->class_code.')",'.ceil($grade->grade).',"'.str_pad( dechex( mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT). str_pad( dechex( mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT).str_pad( dechex( mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT).'"]';
                      $table_data[] = '["'.$grade->skills.'","'.$grade->class_name.'", "'.$grade->exam_semester.'","'.$grade->class_code.'","'.$grade->class_grade_level.'",'.ceil($grade->grade).']';
                      
                    }
                    $data_title  = "Semesterwise Skills Specific Progress, ".$year;
                    $graph_data  = "[".implode(",", $graph_data)."]";
                    $table_data  = "[".implode(",", $table_data)."]";
                    $graph_type  = "ColumnChart";
                    $hAxis_title = "Specific Skills ";
                    $vAxis_title = "Grade %";
                  break;
                case "quarter" :
                  $sql     = sprintf("SELECT class_id,
                                        class_name,
                                        class_code,
                                        class_grade_level,
                                        quiz_id,
                                        skills,
                                        (sum(grade)/count(quiz_id)) as grade,
                                        YEAR( quiz_holding_date_time ) as exam_year,
                                        QUARTER( quiz_holding_date_time ) as exam_quarter
                                    FROM student_grade 
                                    WHERE quiz_id IS NOT NULL AND sid='%s' AND YEAR(quiz_holding_date_time) = YEAR(CURDATE())
                                    GROUP BY quiz_id , class_id , skills , exam_year , exam_quarter ORDER BY exam_quarter
                               ",$sid);
                    $result = mysql_query($sql);
                    $graph_data[] = '["Skills","Skills Specific Grade",{ role: "style" }]';
                    $table_data[] = '["Skills","Skills Class Name","Quarter","Class Code","Class Grade Level","Class Average Grade"]';
                    while($grade  = mysql_fetch_object($result)){

                      $graph_data[] = '["'.$grade->exam_quarter." : ".$grade->skills." (".$grade->class_name."-".$grade->class_code.')",'.ceil($grade->grade).',"'.str_pad( dechex( mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT). str_pad( dechex( mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT).str_pad( dechex( mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT).'"]';
                      $table_data[] = '["'.$grade->skills.'","'.$grade->class_name.'", "'.$grade->exam_quarter.'","'.$grade->class_code.'","'.$grade->class_grade_level.'",'.ceil($grade->grade).']';
                      
                    }
                    $data_title  = "Quarterly Skills Specific Progress, ".$year;
                    $graph_data  = "[".implode(",", $graph_data)."]";
                    $table_data  = "[".implode(",", $table_data)."]";
                    $graph_type  = "ColumnChart";
                    $hAxis_title = "Specific Skills ";
                    $vAxis_title = "Grade %";
                  break;
                case "month" :
                  $sql     = sprintf("SELECT class_id,
                                        class_name,
                                        class_code,
                                        class_grade_level,
                                        quiz_id,
                                        skills,
                                        (sum(grade)/count(quiz_id)) as grade,
                                        YEAR( quiz_holding_date_time ) as exam_year,
                                        MONTHNAME( quiz_holding_date_time ) as exam_month
                                    FROM student_grade 
                                    WHERE quiz_id IS NOT NULL AND sid='%s' AND YEAR(quiz_holding_date_time) = YEAR(CURDATE())
                                    GROUP BY quiz_id , class_id , skills , exam_year , exam_month ORDER BY MONTH(quiz_holding_date_time)
                               ",$sid);
                    $result = mysql_query($sql);
                    $graph_data[] = '["Skills","Skills Specific Grade",{ role: "style" }]';
                    $table_data[] = '["Skills","Skills Class Name","Month","Class Code","Class Grade Level","Class Average Grade"]';
                    while($grade  = mysql_fetch_object($result)){

                      $graph_data[] = '["'.$grade->exam_month." : ".$grade->skills." (".$grade->class_name."-".$grade->class_code.')",'.ceil($grade->grade).',"'.str_pad( dechex( mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT). str_pad( dechex( mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT).str_pad( dechex( mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT).'"]';
                      $table_data[] = '["'.$grade->skills.'","'.$grade->class_name.'", "'.$grade->exam_month.'","'.$grade->class_code.'","'.$grade->class_grade_level.'",'.ceil($grade->grade).']';
                      
                    }
                    $data_title  = "Monthly Skills Specific Progress, ".$year;
                    $graph_data  = "[".implode(",", $graph_data)."]";
                    $table_data  = "[".implode(",", $table_data)."]";
                    $graph_type  = "ColumnChart";
                    $hAxis_title = "Specific Skills ";
                    $vAxis_title = "Grade %";
                  break;
                case "week" :
                  $sql     = sprintf("SELECT class_id,
                                        class_name,
                                        class_code,
                                        class_grade_level,
                                        quiz_id,
                                        skills,
                                        (sum(grade)/count(quiz_id)) as grade,
                                        YEAR( quiz_holding_date_time ) as exam_year,
                                        WEEK( quiz_holding_date_time ) as exam_week
                                    FROM student_grade 
                                    WHERE quiz_id IS NOT NULL AND sid='%s' AND YEAR(quiz_holding_date_time) = YEAR(CURDATE())
                                    GROUP BY quiz_id , class_id , skills , exam_year , exam_week ORDER BY exam_week
                               ",$sid);
                    $result = mysql_query($sql);
                    $graph_data[] = '["Skills","Skills Specific Grade",{ role: "style" }]';
                    $table_data[] = '["Skills","Skills Class Name","Week","Class Code","Class Grade Level","Class Average Grade"]';
                    while($grade  = mysql_fetch_object($result)){

                      $graph_data[] = '["'.$grade->exam_week." : ".$grade->skills." (".$grade->class_name."-".$grade->class_code.')",'.ceil($grade->grade).',"'.str_pad( dechex( mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT). str_pad( dechex( mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT).str_pad( dechex( mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT).'"]';
                      $table_data[] = '["'.$grade->skills.'","'.$grade->class_name.'", "'.$grade->exam_week.'","'.$grade->class_code.'","'.$grade->class_grade_level.'",'.ceil($grade->grade).']';
                      
                    }
                    $data_title  = "Weekly Skills Specific Progress, ".$year;
                    $graph_data  = "[".implode(",", $graph_data)."]";
                    $table_data  = "[".implode(",", $table_data)."]";
                    $graph_type  = "ColumnChart";
                    $hAxis_title = "Specific Skills ";
                    $vAxis_title = "Grade %";
                  break;
             }
            break;
        default:

          break;
    }