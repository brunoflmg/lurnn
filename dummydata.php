<?php
    require_once "config.php";
    //checkLogin();
    mysql_query("TRUNCATE TABLE `quiz_answers`");
    $query1  = "SELECT * FROM teachers";
    $result  = mysql_query($query1);
    $answers = array('option_1','option_2','option_3','option_4');

    while($teacher = mysql_fetch_object($result)){

        $query11  = "SELECT * FROM classes WHERE creator_tid='".$teacher->tid."'";
        $result11  = mysql_query($query11);
        while($class = mysql_fetch_object($result11)){

            $query2    = "SELECT * FROM quizes WHERE creator_tid='".$teacher->tid."' AND class_id='".$class->class_id."'";
            $result2   = mysql_query($query2);
           
            while($quiz = mysql_fetch_object($result2)){
                
                $query3   = "SELECT * FROM quiz_questions WHERE quiz_id='".$quiz->quiz_id."'";
                $result3  = mysql_query($query3);
                while($question = mysql_fetch_object($result3)){
                    $query4   = "SELECT * FROM class_students WHERE creator_tid='".$teacher->tid."' AND class_id='".$class->class_id."'";
                    $result4  = mysql_query($query4);
                    while($student = mysql_fetch_object($result4)){
                        $sql  = "INSERT INTO quiz_answers (
                                            student_id,
                                            quiz_id,
                                            question_id,
                                            answered_option,
                                            correct,
                                            created_time
                                            )
                                            VALUES(
                                                '".$student->student_id."',
                                                '".$quiz->quiz_id."',
                                                '".$question->question_id."',
                                                '".$answers[rand(0,3)]."',
                                                '".rand(0,1)."',
                                                '".date('Y-m-d H:i:s')."'
                                            )

                                ";
                        mysql_query($sql) or die(mysql_error());
                    }
                }
            }
        }
    }

    
?>