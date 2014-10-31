<?php
    require_once "config.php";
    if(!isset($_SESSION['tid'])){
      exit("no");
    }
  
    $action = $_REQUEST["action"]; 
    $tid    = $_SESSION['tid'];
    switch($action){

      case "get_students_by_grade_level":
          $grade_level = $_POST['grade_level'];
          $class_id    = $_POST['class_id'];
          $query  = "SELECT t1.* FROM all_students_unique as t1 WHERE grade_level='".$grade_level."' AND t1.email NOT IN (SELECT t2.email FROM class_students as t2 WHERE t2.class_id='".$class_id."')";
          $result = mysql_query($query);
          if(mysql_num_rows($result) == 0){
             exit("no");
          }
          ?>
          <label>Choose Students:</label>
          <select name="students[]" id="choose_students_level" multiple class="select2 input-xxlarge">
          <?php
            while($student = mysql_fetch_object($result)){
              ?>
              <option value="<?php echo $student->fname;?>___<?php echo $student->lname;?>___<?php echo $student->email;?>"><?php echo $student->fname;?> <?php echo $student->lname;?> ( <?php echo $student->email;?> <?php echo $student->user_name;?> )</option>
                         
              <?php
            }
          ?>
          </select>              
                      
        <?php
        break;
      
      case "delete_class":
        $class_id = intval($_POST['class_id']);
        $sql = sprintf("DELETE FROM classes WHERE class_id='%d' AND creator_tid='%d'",$class_id,$tid);
        if(mysql_query($sql)){
          exit("yes");
        }else{
           exit("no");
        }
        break;
      

      case "edit_class":
        header('Content-Type: application/json');
        $response["status"] = "success";
        $class_id = intval($_POST['pk']);
        $field_name  = mysql_real_escape_string(trim($_POST['name']));
        $field_value = mysql_real_escape_string(trim($_POST['value']));

        if($field_value==""){
           $response["status"] = "error";
           $response["msg"]    = "This field is required";
           exit(json_encode($response));
        }
        switch($field_name){
            case "grade_level":
                if(! in_array($field_value,array('6th Grade','7th Grade','8th Grade','9th Grade','10th Grade','11th Grade','12th Grade'))){
                   $response["status"] = "error";
                   $response["msg"]    = "This field should be the value from drop down";
                   exit(json_encode($response));
                }
                break;
            case "meeting_hours":
              if(! validateDate($field_value, 'H:i:s')){
                   $response["status"] = "error";
                   $response["msg"]    = "This value should be 24 hours format time value";
                   exit(json_encode($response));
              }
              break;
             case "no_of_students":
              if(! filter_var($_POST['value'],FILTER_VALIDATE_INT)){
                   $response["status"] = "error";
                   $response["msg"]    = "No of students should be postive number";
                   exit(json_encode($response));
              }
              break;
            default: 
              
              break;
        }
        $sql = "UPDATE classes SET $field_name='$field_value' WHERE class_id='$class_id' AND creator_tid='$tid'";
        if(!mysql_query($sql)){
           $response["status"] = "error";
           $response["msg"]    = "Sorry!! Internal Problem, Please try again later";
           exit(json_encode($response));
        }
        exit(json_encode($response));
        break;
     



      case "delete_student":
        $student_id = intval($_POST['student_id']);
        $sql = sprintf("DELETE FROM class_students  WHERE student_id='%d' AND creator_tid='%d'",$student_id,$tid);
        if(mysql_query($sql)){
          exit("yes");
        }else{
           exit("no");
        }
        break;
     




      case "edit_student":
        header('Content-Type: application/json');
        $response["status"] = "success";
        $student_id = intval($_POST['pk']);
        $field_name  = mysql_real_escape_string(trim($_POST['name']));
        $field_value = mysql_real_escape_string(trim($_POST['value']));

        if($field_value==""){
           $response["status"] = "error";
           $response["msg"]    = "This field is required";
           exit(json_encode($response));
        }
        switch($field_name){
            
            case "email":
                if(! filter_var($field_value,FILTER_VALIDATE_EMAIL)){
                   $response["status"] = "error";
                   $response["msg"]    = "This field should be valid email address";
                   exit(json_encode($response));
                }
                break;


            case "grade_level":
                if(! in_array($field_value,array('6th Grade','7th Grade','8th Grade','9th Grade','10th Grade','11th Grade','12th Grade'))){
                   $response["status"] = "error";
                   $response["msg"]    = "This field should be the value from drop down";
                   exit(json_encode($response));
                }
                break;



            case "class_id":
              $sql       = "SELECT GROUP_CONCAT(DISTINCT class_id ORDER BY class_id) AS class_ids FROM classes WHERE creator_tid='$tid' GROUP by creator_tid";
              $result    = mysql_query($sql);
              $class_obj = mysql_fetch_object($result);
              $class_ids = @explode(",",$class_obj->class_ids);
              //$response["sql"] = $sql;
              //$response["class_ids"];
              if(! in_array($field_value, $class_ids)){
                   $response["status"] = "error";
                   $response["msg"]    = "You can not add this student to this class";
                   exit(json_encode($response));
              }
              break;


            default: 
              
              break;
        }
        $sql = "UPDATE class_students SET $field_name='$field_value' WHERE student_id='$student_id' AND creator_tid='$tid'";
        $response["sql"] = $sql;
        if(!mysql_query($sql)){
           $response["status"] = "error";
           $response["msg"]    = "Sorry!! This student may be already enrolled to this class, Please check it again";
           exit(json_encode($response));
        }
        exit(json_encode($response));
        break;






      case "delete_quiz":
        $quiz_id = intval($_POST['quiz_id']);
        $sql = sprintf("DELETE FROM quizes  WHERE quiz_id='%d' AND creator_tid='%d'",$quiz_id,$tid);
        if(mysql_query($sql)){
          exit("yes");
        }else{
           exit("no");
        }
        break;
     


      case "stop_quiz":
        $quiz_id = intval($_POST['quiz_id']);
        $sql = sprintf("UPDATE quizes SET quiz_status='taken'  WHERE quiz_id='%d' AND creator_tid='%d'",$quiz_id,$tid);
        if(mysql_query($sql)){
          exit("yes");
        }else{
           exit("no");
        }
        break;
     
      case "start_quiz":
        $quiz_id   = intval($_POST['quiz_id']);
        $sql       = "SELECT *FROM quiz_questions_stat WHERE quiz_id='$quiz_id' AND creator_tid='$tid'";
        $result    = mysql_query($sql);
        $quiz_stat = mysql_fetch_object($result);
        
        if(($quiz_stat->no_of_question != $quiz_stat->total_asked_question) || ($quiz_stat->points != $quiz_stat->total_cost_points)){
            exit("no");
        }
        //d($quiz_stat,1);
        $sql     = sprintf("UPDATE quizes SET quiz_status='active'  WHERE quiz_id='%d' AND creator_tid='%d'",$quiz_id,$tid);
        if(mysql_query($sql)){
          exit("yes");
        }else{
           exit("no");
        }
        break;
      
      case "edit_quiz":
        header('Content-Type: application/json');
        $response["status"] = "success";
        $quiz_id = intval($_POST['pk']);
        $field_name  = mysql_real_escape_string(trim($_POST['name']));
        $field_value = mysql_real_escape_string(trim($_POST['value']));

        if($field_value==""){
           $response["status"] = "error";
           $response["msg"]    = "This field is required";
           exit(json_encode($response));
        }
        switch($field_name){

            case "class_id":
              $sql       = "SELECT GROUP_CONCAT(DISTINCT class_id ORDER BY class_id) AS class_ids FROM classes WHERE creator_tid='$tid' GROUP by creator_tid";
              $result    = mysql_query($sql);
              $class_obj = mysql_fetch_object($result);
              $class_ids = @explode(",",$class_obj->class_ids);
              //$response["sql"] = $sql;
              //$response["class_ids"];
              if(! in_array($field_value, $class_ids)){
                   $response["status"] = "error";
                   $response["msg"]    = "You can not add this student to this class";
                   exit(json_encode($response));
              }
              break;

            case "points":
              if(! filter_var($_POST['value'],FILTER_VALIDATE_INT)){
                   $response["status"] = "error";
                   $response["msg"]    = "Quiz points should be postive number";
                   exit(json_encode($response));
              }
              break;
            case "no_of_question":
              if(! filter_var($_POST['value'],FILTER_VALIDATE_INT)){
                   $response["status"] = "error";
                   $response["msg"]    = "Number of Questions should be postive number";
                   exit(json_encode($response));
              }
              break;
            case "quiz_time":
              if(! filter_var($_POST['value'],FILTER_VALIDATE_INT)){
                   $response["status"] = "error";
                   $response["msg"]    = "Quiz Time should be postive number";
                   exit(json_encode($response));
              }
              break;
            case "quiz_holding_date_time":
              if(! validateDate($field_value, 'Y-m-d H:i:s')){
                   $response["status"] = "error";
                   $response["msg"]    = "Quiz holding date time should be properly formated";
                   exit(json_encode($response));
              }
              break;
            default: 
              
              break;
        }
        $sql = "UPDATE quizes  SET $field_name='$field_value',points_of_each_question=(points/no_of_question) WHERE quiz_id='$quiz_id' AND creator_tid='$tid'";
        $response["sql"] = $sql;
        if(!mysql_query($sql)){
           $response["status"] = "error";
           $response["msg"]    = "Sorry!! Quiz can not be updated right now, Please try again later";
           exit(json_encode($response));
        }
        exit(json_encode($response));
        break;





      case "edit_question":
        header('Content-Type: application/json');
        $response["status"] = "success";
        $question_id = intval($_POST['pk']);
        $field_name  = mysql_real_escape_string(trim($_POST['name']));
        $field_value = mysql_real_escape_string(trim($_POST['value']));

        if($field_value==""){
           $response["status"] = "error";
           $response["msg"]    = "This field is required";
           exit(json_encode($response));
        }
        
        $sql = "UPDATE quiz_questions  SET $field_name='$field_value' WHERE question_id='$question_id' AND creator_tid='$tid'";
        $response["sql"] = $sql;
        if(!mysql_query($sql)){
           $response["status"] = "error";
           $response["msg"]    = "Sorry!! Quiz can not be updated right now, Please try again later";
           exit(json_encode($response));
        }
        exit(json_encode($response));
        break;




      case "delete_question":
        $question_id = intval($_POST['question_id']);
        $sql = sprintf("DELETE FROM quiz_questions  WHERE question_id='%d' AND creator_tid='%d'",$question_id,$tid);
        if(mysql_query($sql)){
          exit("yes");
        }else{
           exit("no");
        }
        break;
     
      

      case "get_class_learning_goal":
        $class_id = intval($_POST['class_id']);

        $sql      = sprintf("SELECT *FROM `learning_goals`  WHERE class_id='%d' AND creator_tid='%d'",$class_id,$tid);
        $result   = mysql_query($sql);
        while ($learning_goal = mysql_fetch_object($result)) {
          $learning_options[] = '<option value="'.$learning_goal->id.'">'.$learning_goal->learning_goal.'</option>';
        }
        if(isset($learning_options) && is_array($learning_options) && !empty($learning_options)){
           echo implode(" ", $learning_options);
        }else{
          echo "<option>No Goals Found In This Class</option>";
        }
        break;
      case "get_class_assigned_students":
        $class_id = intval($_POST['class_id']);

        $sql      = sprintf("SELECT t1.*, t2.user_name FROM `class_students` as t1 LEFT JOIN student_whole_info as t2 ON t1.student_id=t2.student_id  WHERE t1.class_id='%d' AND t1.creator_tid='%d'",$class_id,$tid);
        //d($sql);
        $result   = mysql_query($sql);
        while ($student = mysql_fetch_object($result)) {
          $students[] = '<option value="'.$student->student_id.'">'.$student->fname." ".$student->lname." (".$student->email." ".$student->user_name." ) ".'</option>';
        }
        if(isset($students) && is_array($students) && !empty($students)){
           echo implode(" ", $students);
        }else{
          echo "<option>No Students Found In This Class</option>";
        }
        break;


      case "create_quiz_question":
        //d($_POST,1);
        

        $quiz_id = mysql_real_escape_string(trim($_POST['data']['quiz_id']));

        $sql     = sprintf("SELECT * FROM quiz_questions_stat WHERE quiz_id='%s'",$quiz_id);
        $result  = mysql_query($sql);
        $quiz_stat = mysql_fetch_object($result);

        if(is_object($quiz_stat) && $quiz_stat->creator_tid != $tid){
            exit("You are not authorised to create questions on this quiz");
        }
       
        if(is_object($quiz_stat) && $quiz_stat->no_of_question <= $quiz_stat->total_asked_question){
            exit("Quiz is full of questions ".$quiz_stat->total_asked_question." of ".$quiz_stat->no_of_question);
        }

        $question_details   = mysql_real_escape_string(trim($_POST['data']['question_details']));
        if(trim(str_replace("&nbsp;","",strip_tags($question_details)))==""){
          exit("Questions can't be empty");
        }

        $points             = mysql_real_escape_string(trim($_POST['data']['points']));

        if(is_object($quiz_stat) && $quiz_stat->points_of_each_question == "equal"){
            $points         = ($quiz_stat->points / $quiz_stat->no_of_question);
        }

        if(is_object($quiz_stat)){
            $total_cost_points = ($quiz_stat->total_cost_points=="NULL")? 0 :$quiz_stat->total_cost_points;
            if($quiz_stat->points <= $total_cost_points ){
              exit("You have already cost the total ".$quiz_stat->points." points");
            }
        }

        if(!is_numeric($points) || intval($points) == 0){
           exit("Points of each question should be a number");
        }

        $question_type      = mysql_real_escape_string(trim($_POST['data']['question_type']));
        if(!in_array($question_type, array('boolean','mcq','self_answer'))){
           exit("Unsupported question  type");
        }

        $skills             = $_POST['data']['skills'];
        if(!is_array($skills) || empty($skills)){
          exit("Please choose Skill");
        }
        switch($question_type){
           case "boolean":
               $right_answer_boolean = mysql_real_escape_string(trim($_POST['data']['right_answer_boolean']));
               if(!in_array($right_answer_boolean, array("TRUE","FALSE"))){
                  exit("Invalid right answer");
               }
               $sql = sprintf("INSERT INTO quiz_questions 
                                (
                                  quiz_id,
                                  creator_tid,
                                  question_details,
                                  question_type,
                                  right_answer,
                                  points,
                                  created_date
                                )
                                VALUES('%d','%d','%s','%s','%s','%s','%s')
                                ",
                                $quiz_id,
                                $tid,
                                $question_details,
                                $question_type,
                                $right_answer_boolean,
                                $points,
                                date("Y-m-d H:i:s")
                            );
               break;
           case "mcq":;
               $right_answer_mcq     = mysql_real_escape_string(trim($_POST['data']['right_answer_mcq']));

               $possible_answers     = $_POST['data']['possible_answers'];
               $right_answer_index   = explode("_",$right_answer_mcq);
               $index                = end($right_answer_index);
               //d($index,1);
               if(!isset($possible_answers[$index])){
                  exit("Please Choose the right answer");
               }
               
               if(is_array($possible_answers) && !empty($possible_answers) && count($possible_answers)>2){
                  for($i=1;$i<count($possible_answers); $i++){
                     $options["option_"+$i] = mysql_real_escape_string(trim($possible_answers[$i]));
                  }
               }else{
                  exit("Possible Answers must be greate than 1");
               }

               $sql = sprintf("INSERT INTO quiz_questions 
                                (
                                  quiz_id,
                                  creator_tid,
                                  question_details,
                                  question_type,
                                  options,
                                  right_answer,
                                  points,
                                  created_date
                                )
                                VALUES('%d','%d','%s','%s','%s','%s','%s','%s')
                                ",
                                $quiz_id,
                                $tid,
                                $question_details,
                                $question_type,
                                json_encode($options),
                                $right_answer_mcq,
                                $points,
                                date("Y-m-d H:i:s")
                            );
               break;

            case "self_answer":
               $right_answer_self = mysql_real_escape_string(trim($_POST['data']['right_answer_self']));
               $sql = sprintf("INSERT INTO quiz_questions 
                                (
                                  quiz_id,
                                  creator_tid,
                                  question_details,
                                  question_type,
                                  right_answer,
                                  points,
                                  created_date
                                )
                                VALUES('%d','%d','%s','%s','%s','%s','%s')
                                ",
                                $quiz_id,
                                $tid,
                                $question_details,
                                $question_type,
                                $right_answer_self,
                                $points,
                                date("Y-m-d H:i:s")
                            );
              break;
            default:
              break;

        }
        
                
        
        if(mysql_query($sql)){
            $question_id = mysql_insert_id();
            foreach($skills as $skill){
              $skill = intval($skill);
              $question_skills[] ="('".$question_id."','".$skill."','".date("Y-m-d-H:i:s")."')";
            }
            if(isset($question_skills) && !empty($question_skills)){
              $query = "REPLACE INTO question_skills (question_id,skill_id,created_date) VALUES".implode(',',$question_skills);
              mysql_query($query) or die(mysql_error());
            }
            exit("yes");
        }else{
            exit("Sorry! internal problem, please try again later");
        }


        break;

      case "quiz_questions_stat":
          $quiz_id   = intval($_POST['quiz_id']);

          $sql               = "SELECT *FROM quiz_questions_stat WHERE quiz_id='$quiz_id' AND creator_tid='$tid'";
          $result            = mysql_query($sql);
          //$quiz_stat['sql_1']= $sql;
          $quiz_stat['stat'] = mysql_fetch_object($result);
         
          $sql               = "SELECT * FROM skills WHERE quiz_id='$quiz_id' AND creator_tid='$tid'";
          //$quiz_stat['sql_1']= $sql;
          $result            = mysql_query($sql);

          while($skill = mysql_fetch_object($result)){
              $skills[$skill->skill_id] = $skill->skill;
          }
          
          $quiz_stat['skills']  = $skills;
          header('Content-Type: application/json');
          echo json_encode($quiz_stat);exit;
          
        break;
      case "get_class_skills":
          $class_id   = intval($_REQUEST['class_id']);

          $sql               = "SELECT *FROM skills WHERE class_id='$class_id' AND creator_tid='$tid'";
          $result            = mysql_query($sql);
          //$quiz_stat['sql_1']= $sql;
          while($skill=mysql_fetch_object($result)){
            $skills[] = $skill->skill;
          }
          
          header('Content-Type: application/json');
          if(is_array($skills)){
            $data['skills'] = implode(",",$skills);
          }else{
            $data['skills'] = false;
          }
          echo json_encode($data);
          exit;
          
          
        break;

      case "get_quizzes_by_class" :
          $class_id  = intval($_REQUEST['class_id']);
          header('Content-Type: application/json');
          $query  = sprintf("SELECT quiz_id,quiz_subject,class_id FROM quiz_extended_info  WHERE creator_tid='%d' AND class_id='%d'",$tid,$class_id);
          $result = mysql_query($query); 
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

      case "get_assined_students_by_quiz" :
          $quiz_id  = intval($_REQUEST['quiz_id']);
          header('Content-Type: application/json');

          $query  = sprintf("SELECT student_id,student_user_name,student_fname,student_lname,student_email FROM quiz_student_extented_info  WHERE quiz_id='%s'",$quiz_id);
          $result = mysql_query($query); 
          //$data['sql'] = $query;

          while($student   = mysql_fetch_object($result)){
            $students[$student->student_id] = $student;
          }

          if(isset($students) && !empty($students)){
             $data['data'] = $students;
          }else{
             $data['data'] = false;
             $data['error'] = "No quizzes found on this class";
          }
          echo json_encode($data);
          exit;
          break;
      case "get_student_answer_sheet" :
          $quiz_id     = intval($_REQUEST['quiz_id']);
          $student_id  = intval($_REQUEST['student_id']);
          // d($quiz_id,1);
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
                              WHERE t1.quiz_id='%d' AND t1.creator_tid='%d' AND t2.student_id ='%d'",$quiz_id,$tid,$student_id);
          //d($query,1);
          $result = mysql_query($query) or die(mysql_error()); 
          //d($query,1);
          while($answer   = mysql_fetch_object($result)){
            $answers[$answer->qa_id] = $answer;
          }
          $query   = "SELECT *FROM quiz_assigned_students WHERE quiz_id='$quiz_id' AND student_id='$student_id'";
          $result  = mysql_query($query);
          $quiz_assigned_students = mysql_fetch_object($result);

          include "includes/answer_sheet.php";
          exit;
          break;
          
      case "post_student_answer_sheet":
          $quiz_id          = intval($_POST["quiz_id"]);
          $quiz_assigned_id = intval($_POST['quiz_assigned_id']);
          $student_id       = intval($_POST["student_id"]);
          if(is_array($_POST['answer_points']) && !empty($_POST['answer_points'])){
             //d($_POST['answer_points'],1);
             foreach($_POST['answer_points'] as $qa_id => $points){
                if($points > 0){
                  $correct=1;
                }else{
                  $correct =0;
                }
                $comments = isset($_POST[$question_id])? mysql_real_escape_string($_POST['tecaher_comments'][$qa_id]):'No Comments';
                $sql = "UPDATE quiz_answers SET points='".floatval($points)."',teacher_comments='".$comments."', status='verified',correct='$correct' WHERE qa_id='".intval($qa_id)."'";
                mysql_query($sql); //or die(mysql_error());
                //echo $sql."<br/>";
             }
             $sql = "UPDATE quiz_assigned_students SET status='verified',teacher_comments='".mysql_real_escape_string($_POST['quiz_comments'])."' WHERE id='$quiz_assigned_id'";
             mysql_query($sql);// or die(mysql_error());
             //echo $sql."<br/>";
          }
          exit('<div class="alert alert-success" style="margin-top:30px;">
              <button data-dismiss="alert" class="close" type="button">×</button>
              <strong>Settings has been successfully saved</strong>.
            </div>');
          break;
      default:
        exit("no");
        break;
    }