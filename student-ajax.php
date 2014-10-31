<?php
    require_once "config.php";
    if(!isset($_SESSION['sid'])){
      exit("no");
    }
  
    $action = $_REQUEST["action"]; 
    $sid    = $_SESSION['sid'];
    switch($action){
      case "get_quizzes_by_class" :
          $class_id  = intval($_REQUEST['class_id']);
          header('Content-Type: application/json');
         $query  = sprintf("SELECT t1.quiz_id,t1.quiz_subject,t1.class_id FROM quiz_extended_info AS t1 WHERE t1.quiz_id IN (SELECT t2.quiz_id FROM quiz_student_extented_info as t2 WHERE t2.sid='%s') AND t1.class_id='%d' ",$sid,$class_id);
          $result = mysql_query($query); 
          //d($query,1);
          //d($query,1);

          while($quiz   = mysql_fetch_object($result)){
             $quizzes[$quiz->quiz_id] = $quiz->quiz_subject;
          }
          if(isset($quizzes) && !empty($quizzes)){
             $data['data'] = $quizzes;
          }else{
             $data['data'] = false;
             $data['error'] = "No quizzes found on this class";
          }
          echo json_encode($data);
          exit;
          break;
      case "get_student_answer_sheet" :
          $quiz_id     = intval($_REQUEST['quiz_id']);
          $query  = sprintf("SELECT t1.question_id,
                              t1.question_details,
                              t1.question_type,
                              t1.options,
                              t1.right_answer,
                              t1.points,
                              t2.qa_id,
                              t2.student_id,
                              t2.answer,
                              t2.points as got_points,
                              t2.correct,
                              t2.teacher_comments,
                              t2.status

                              FROM quiz_questions as t1 LEFT JOIN quiz_answers as t2 ON t1.question_id=t2.question_id
                              WHERE t1.quiz_id='%d' AND t2.student_id IN (SELECT t3.student_id FROM student_whole_info as t3 WHERE t3.sid='%s')",$quiz_id,$sid);
          //d($query,1);
          $result = mysql_query($query) or die(mysql_error()); 
          //d($query,1);
          while($answer   = mysql_fetch_object($result)){
            $answers[$answer->qa_id] = $answer;
          }
          $query   = "SELECT *FROM quiz_assigned_students WHERE quiz_id='$quiz_id' AND student_id IN (SELECT t3.student_id FROM student_whole_info as t3 WHERE t3.sid='$sid')";
          $result  = mysql_query($query);
          $quiz_assigned_students = mysql_fetch_object($result);

          include "includes/student_answer_sheet.php";
          exit;
          break;

      default:

        exit("no");
        break;
    }