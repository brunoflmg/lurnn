<?php
    require_once "config.php";
    checkLogin();
    
    $filter = "student";
    $tid = $_SESSION['tid'];
	$email = $_SESSION['email'];
	$enrolled_class_id1 = $_GET['Qid'];
	
    $filter = "class";
    if(isset($_GET['filter']) && trim($_GET['filter'])!=""){
        $filter = mysql_real_escape_string($_GET['filter']);
        if(! in_array($filter,array("class","quiz","skill"))){
            $filter = "student";
        }
    }
    
    $duration = "year";
    if(isset($_GET['duration']) && trim($_GET['duration'])!=""){
        $duration = mysql_real_escape_string($_GET['duration']);
        if(! in_array($duration,array("week","month", "quarter", "semester", "year"))){
            $duration = "year";
        }
    }

    $year = date("Y");
    

    include "dashboard.graph.php";
	
	include "teacher_ClassDash_progress.php";
	
    $user_id = $_SESSION['tid'];
    //d($student_grades,1);
 

?>
<!DOCTYPE html>
<html>
<head>
  <title>lurnn</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
    <!-- bootstrap -->
    <link href="css/bootstrap/bootstrap.css" rel="stylesheet" />
    <link href="css/bootstrap/bootstrap-responsive.css" rel="stylesheet" />
    <link href="css/bootstrap/bootstrap-overrides.css" type="text/css" rel="stylesheet" />

    <!-- libraries -->
    <link href="css/lib/jquery-ui-1.10.2.custom.css" rel="stylesheet" type="text/css" />
    <link href="css/lib/font-awesome.css" type="text/css" rel="stylesheet" />

    <!-- global styles -->
    <link rel="stylesheet" type="text/css" href="css/layout.css">
    <link rel="stylesheet" type="text/css" href="css/elements.css">
    <link rel="stylesheet" type="text/css" href="css/icons.css">

    <!-- this page specific styles -->
    <link rel="stylesheet" href="css/compiled/index.css" type="text/css" media="screen" />    

    <!-- open sans font -->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800' rel='stylesheet' type='text/css'>

    <!-- lato font -->
    <link href='http://fonts.googleapis.com/css?family=Lato:300,400,700,900,300italic,400italic,700italic,900italic' rel='stylesheet' type='text/css'>

    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    <link rel="shortcut icon" href="./img/favicon.jpg"/>
    
    </head>
<body>
    <?php include "includes/menu.php"; ?>
    <?php include "includes/left_side_bar.php"; ?>
  <!-- main container -->
    <div class="content">
        
        <div class="container-fluid">

            <!-- upper main stats -->
            <?php include "teacher_stats.php";?>
            <!-- end upper main stats -->

            <div id="pad-wrapper" style="margin-top:1px;">
				
                <!-- statistics chart built with jQuery Flot -->
                <div class="row-fluid chart">
					
					<div class="span8" style="width:100%;">
					<div class="span3" style="margin:15px 0 0 0; float:right;">   
                        <div class="ui-select" style="width:190px;">
                          <select id="progress" onchange="window.location='teacher_class_dashboard.php?Qid=<?php echo $enrolled_class_id1;?>&progress='+$('#progress').val();">
                            <option value="class"  <?php if($progress=='class') echo "selected='selected'";?>>Progress By Class</option>
                            <option value="student" <?php if($progress=='student') echo "selected='selected'";?>>Progress By Student</option>
                            <option value="assignment"  <?php if($progress=='assignment') echo "selected='selected'";?>>Progress By Assignment</option>
                            <option value="skill"  <?php if($progress=='skill') echo "selected='selected'";?>>Progress By Skill</option>
                          </select>
                        </div>
                    </div>
				</div>
					
				<h2 style="float: left; width: 100%; font-size: 18px; margin: 15px 0px 10px; font-weight: bold; background: #f3f3f3; padding: 6px 0px;">
					Class Dashboard
				</h2>
				
				<div class="span12" style="margin-top:25px;">
					<div class="span6 left_one">
						<a href="upcoming_assignments.php">
							<span style="float:left; width:33%">
								<p class="in_head">UPCOMING ASSIGNMENTS</p>
							</span>
						</a>
								<?/*<img src="img/demo-image.png" class="avatar">*/?>
								<?php
									$query  = "SELECT upcoming_assignments.*, teachers.* FROM upcoming_assignments INNER JOIN teachers ON upcoming_assignments.user_id = teachers.tid Where upcoming_assignments.user_id = '$user_id' ORDER BY upcoming_assignments.asign_id DESC";
									$result = mysql_query($query);
									while($assigned_class  = mysql_fetch_object($result)){
									  // $assigned_classes[] = $assigned_class;
									}

									
									//--- Get QUIZES-----------------------	
										$sql = "SELECT quizes.*, teachers.* FROM quizes INNER JOIN teachers ON quizes.creator_tid = teachers.tid Where quizes.creator_tid = '$user_id' AND quizes.class_id = '$enrolled_class_id1' ORDER BY quizes.quiz_id DESC";
										
										$result = mysql_query($sql);
										//d($sql,1);
										while($feedquiz  = mysql_fetch_object($result))
										{
											$feedquizs[] = $feedquiz;
										} 
										
										$query  = sprintf("SELECT class_students.*, classes.* FROM class_students LEFT JOIN classes on class_students.class_id = classes.class_id where class_students.creator_tid = '$user_id' && class_students.added_by = 'teacher' ");
										$result = mysql_query($query);
										while($ext  = mysql_fetch_object($result))
										{
										   $exts[] = $ext;
										}
										
									//--- Get ESSAY----------------------------------
										$query  = "SELECT essays.*, teachers.* FROM essays INNER JOIN teachers ON essays.user_id = teachers.tid Where essays.user_id = '$user_id' AND essays.class_id = '$enrolled_class_id1' ORDER BY essays.essay_id DESC";
										$result = mysql_query($query);
										while($assigned_class  = mysql_fetch_object($result)){
											$feedessays[] = $assigned_class;
										}
								?>
					<span style="float: left; overflow-y: scroll; height: 200px; width: 100%;">
					<table id="student_classes" class="table table-hover table-responsive">
					<tbody>
                    <?php
                       if(is_array($assigned_classes) && !empty($assigned_classes)){
                          ?>
                               <?php $i=1;
                               foreach ($assigned_classes as $assigned_class) {
							   //echo "<pre>"; print_r($assigned_class);
							   $classID =  $assigned_class->class_id;
							//--- Below code will show the record of only tose classes in which current login student is assigned-------------- 
                               ?>
                                <tr class="<?php echo $assigned_class->status;?>">
                                  <td>
									<img src="img/assignment_icon.png" height="16" width="16"/>
									<span onclick="show_Details(<?php echo $assigned_class->asign_id ?>)" style="color:#0088cc; text-decoration:underline; cursor:pointer;">
										<?php echo $assigned_class->asign_name;?>
									</a>
								   </td>
                                  <td>
										<?php
										$date = date_create($assigned_class->avlaible_until);
										echo "<b>Available until </b>".date_format($date, 'F j'); ?>
									  </td>
									  <td>
										<?php
										$date = date_create($assigned_class->due_date);
										echo "<b>Due </b>".date_format($date, 'F j \a\t g:ia'); ?>
									  </td>
                                </tr>
                                <?php } ?>
                          <?php
                       }
					?>
					<!----SHOW QUIZ------------>
						<?php
						   if(is_array($feedquizs) && !empty($feedquizs))
							{
								//echo "<pre>"; print_r($feedquizs);
							  ?>
								<?php $i=1;
								   foreach ($feedquizs as $assigned_class) {
								   //echo "<pre>"; print_r($assigned_class);
								  /* foreach ($exts as $ext) {
									//echo "<pre>"; print_r($ext);
								   $classID  =  $assigned_class->class_id;
								   $classIDZ = 	$ext->class_id;
									
									if($classID == $classIDZ)
									{*/
								?>
									<tr>
									  <td>
										<img src="img/quiz_icon.png" height="16" width="16"/>
										<?/*<a target="_blank" href="start-answer.php?id=<?php echo $assigned_class->quiz_id;?>" style="text-decoration:underline">*/?>
										<a href="#" style="text-decoration:underline">
											<?php echo "Quiz". $i++." (".$assigned_class->quiz_subject.")";?>
										</a>
									  </td>
									  <td>
										<?php
										$date = date_create($assigned_class->quiz_created_date);
										echo "<b>Available until </b>".date_format($date, 'F j'); ?>
									  </td>
									  <td>
										<?php
										$date = date_create($assigned_class->quiz_holding_date_time);
										echo "<b>Due </b>".date_format($date, 'F j \a\t g:ia'); ?>
									  </td>
									</tr>
									<?php /*} }*/ } ?>
							  <?php
							}
						?>
						
					<!----SHOW ESSAY------------>
						<?php
						   if(is_array($feedessays) && !empty($feedessays))
							{
								
							  ?>
								<?php $i=1;
								   foreach ($feedessays as $assigned_class) {
								   //echo "<pre>"; print_r($assigned_class);
								   $classID =  $assigned_class->class_id;
								//--- Below code will show the record of only tose classes in which current login student is assigned-------------- 
								   ?>
									<tr>
									  <td>
										<img src="img/essay_icon.png" height="16" width="16"/>
											<a target="_blank" href="essayReviews.php?id=<?php echo $assigned_class->essay_id;?>" style="text-decoration:underline">
											<?php echo "Essay". $i++." (".$assigned_class->essay_title.")";?>
										</a>
										</td>
									  <td>
										<?php
										$date = date_create($assigned_class->avlaible_until);
										echo "<b>Available until </b>".date_format($date, 'F j'); ?>
									  </td>
									  <td>
										<?php
										$date = date_create($assigned_class->due_date);
										echo "<b>Due </b>".date_format($date, 'F j \a\t g:ia'); ?>
									  </td>
									</tr>
									<?php } ?>
							  <?php
							}
						?>
						
					</tbody>
					</table>
					</span>
							</span>
						
					</div>
					
					<div class="span6 left_one">
						<a href="teacherGrades.php">
							<span style="float:left; width:33%">
								<p class="in_head">RECENTLY GRADED
								</p>
							</span>
						</a>
							<span style="float:left; height:200px; overflow-y:scroll; width:100%;">
								<?php
									//--- Get QUIZES-----------------------	
									$stu_grade = $_SESSION['grade_level'];
									$sqlha = "SELECT quizes.*, classes.* FROM quizes INNER JOIN classes ON quizes.class_id = classes.class_id WHERE quizes.creator_tid = '$tid' AND essays.class_id = '$enrolled_class_id1' ORDER BY quizes.quiz_id DESC";
									$resultha = mysql_query($sqlha);
									//d($sql,1);

									while($feedquizha  = mysql_fetch_object($resultha))
									{
										$feedquizsha[] = $feedquizha;
									} 

									//--- Get ESSAY----------------------------------
									$queryha  = sprintf("SELECT essays.*, essay_comments.* FROM essays LEFT JOIN essay_comments ON essays.essay_id = essay_comments.essay_id Where essays.user_id = '$tid' AND essays.class_id = '$enrolled_class_id1' AND essay_comments.grade !='NULL' || essay_comments.grade !='' ORDER BY essays.essay_id DESC");
									$resultha = mysql_query($queryha);
									while($assigned_classha  = mysql_fetch_object($resultha))
									{
										$feedessaysha[] = $assigned_classha;
									}
							//---SHOW HERE	--------------------------------------
								?>
									<span style="float:left; width:100%;">
										<table id="student_classes" class="table table-hover table-responsive">
										<tbody>
										<tr class="listingz" style="background:#a3a3a3">
											<td style="color:#000; font-weight:bold;">Name</td>
											<td style="color:#000; font-weight:bold;">Student Name</td>
											<td style="color:#000; font-weight:bold;">Due</td>
											<td style="color:#000; font-weight:bold;">Score</td>
											<td style="color:#000; font-weight:bold;">Out Of</td>
										</tr>
										<!----SHOW QUIZ------------>
											<?php
											   if(is_array($feedquizsha) && !empty($feedquizsha))
												{
													//echo "<pre>"; print_r($feedquizs);
												  ?>
													<?php $i=1;
													   foreach ($feedquizsha as $assigned_class) {

												//--get achieved points for this quiz----------------
														$quzID = $assigned_class->quiz_id;
														$sql = "SELECT * FROM quiz_answers Where quiz_id = '$quzID'";
														$result = mysql_query($sql);
														$quzGet = mysql_fetch_object($result);
														$countQZ = mysql_num_rows($result);
														if($countQZ !='0')
														{
													?>
														<tr>
														  <td>
															<img src="img/quiz_icon.png" height="16" width="16"/>
															<?/*<a target="_blank" href="start-answer.php?id=<?php echo $assigned_class->quiz_id;?>" style="text-decoration:underline">*/?>
															<a href="#" style="text-decoration:underline">
																<?php echo "Quiz". $i++?>
																<?php
																	$qz_sub = $assigned_class->quiz_subject;
																	if($qz_sub) { echo " (".$qz_sub.")"; }
																?>
															</a>
														  </td>
														  <td>
															<?php
																$sID = $quzGet->student_id;
																$queryer  = sprintf("SELECT * FROM students WHERE sid = '$sID'");
																$resulter = mysql_query($queryer);
																$er = mysql_fetch_object($resulter);
																echo $er->fname." ".$er->lname;
															?>
														  </td>
														  <td>
															<?php
															$date = date_create($assigned_class->quiz_holding_date_time);
															echo date_format($date, 'F j \a\t g:ia'); ?>
														  </td>
														  <td>
															<?php 
																$sqltot = "SELECT SUM(points) FROM quiz_answers Where student_id = '$sid' && quiz_id = '$quzID'";
																$resulttot = mysql_query($sqltot);
																$quzGettot = mysql_fetch_array($resulttot);
																//echo "<pre>"; print_r($quzGettot);
																echo round($quzGettot['SUM(points)']);
															?>
														  </td>
														  <td>
															<?php echo $assigned_class->points; ?>
														  </td>
														</tr>
														<?php /*} }*/ } } ?>
												  <?php
												}
											?>
											
										<!----SHOW ESSAY------------>
											<?php
											   if(is_array($feedessaysha) && !empty($feedessaysha))
												{
												  ?>
													<?php $i=1;
													   foreach ($feedessaysha as $assigned_class) {
													   //echo "<pre>"; print_r($assigned_class);
													   $classID =  $assigned_class->class_id;
													//--- Below code will show the record of only tose classes in which current login student is assigned-------------- 
													   ?>
														<tr>
														  <td>
															<img src="img/essay_icon.png" height="16" width="16"/>
																<a target="_blank" href="essayReviews.php?id=<?php echo $assigned_class->essay_id;?>" style="text-decoration:underline">
																<?php echo "Essay". $i++." (".$assigned_class->essay_title.")";?>
															</a>
															</td>
															<td>
																<?php
																	$sID = $assigned_class->for_student_id;
																	$queryer  = sprintf("SELECT * FROM students WHERE sid = '$sID'");
																	$resulter = mysql_query($queryer);
																	$er = mysql_fetch_object($resulter);
																	echo $er->fname." ".$er->lname;
																?>
														  </td>
														  <td>
															<?php
															$date = date_create($assigned_class->due_date);
															echo date_format($date, 'F j \a\t g:ia'); ?>
														  </td>
														  <td>
															<?php 
																$Grade = $assigned_class->grade; 
																if($Grade) { echo $Grade; } else { echo '0.00'; }
															?>
														  </td>
														  <td>10</td>
														</tr>
														<?php } ?>
												  <?php
												}
											?>
											
										</tbody>
										</table>
										</span>
							</span>
						
					</div>
					
					<div class="span6 left_one" style="margin-left:0px;">
						<a href="classes.php" alt="Enroll" title="Enroll">
							<span style="float:left; width:33%">
								<p class="in_head">CLASS LIST</p>
							</span>
						</a>
							<span style="clear:both; float:left; width:100%">
								<?/*<img src="img/demo-image.png" class="avatar">*/?>
								<?php
									$query  = sprintf("SELECT * FROM classes WHERE creator_tid='$tid' AND class_id='$enrolled_class_id1'");

									$result = mysql_query($query);
									while($offer_class  = mysql_fetch_object($result)){
									   //$offer_classes[$offer_class->class_id] = $offer_class;
									   $offer_classes[] = $offer_class;
									}
								?>
						<span style="float:left; height:200px; overflow-y:scroll; width:100%;">
						<?php
                       if(is_array($offer_classes) && !empty($offer_classes)){
                          ?>

                            <table class="table table-hover table-responsive">
                              <thead>
                                <tr style="float: left; width: 477px;">
									<th style="float:left; width:39px">Class Name</th>
									<th style="float:left; width:39px">Class Details</th>
									<th style="float:left; width:39px">Grade Level</th>
									<th style="float:left; width:53px">Meeting Hours</th>
									<th style="float:left; width:59px">No of Students</th>
									<th style="float:left; width:39px">Start date</th>
									<th style="float:left; width:39px">Action</th>
                                </tr>
                              </thead>
                              <tbody>
                               <?php $i=1;
                               foreach ($offer_classes as $class) {
          ?>
          <tr id="class_<?php echo $class->class_id;?>">
           <td style="float:left; width:39px">
				<a href="teacher_class_dashboard.php?Qid=<?php echo $class->class_id;?>">
					<?php echo $class->class_name;?>
				</a>
           </td>
            <td style="float:left; width:39px">
				<?php echo substr($class->class_details,0,25)."...";?>
            </td>
            <td style="float:left; width:39px">
				<?php echo $class->grade_level;?>
            </td>
            <td style="float:left; width:53px">
				<?php echo $class->meeting_hours;?>
            </td>
            <td style="float:left; width:43px; text-align:center">
              <?php echo $class->no_of_students;?>
            </td>
            <td style="float:left; width:58px; text-align:center">
				<?php
					$date = date_create($class->created_date);
					echo date_format($date, 'j F'); 
				?>
			</td>
            <td style="float:left; width:39px">
				<a href="#" style="float: left; margin: 8px 0px 0px 10px;" onclick="deleteClass('<?php echo $class->class_id;?>')">
					<img src="close.png"/>
				</a>
			</td>
          </tr>
          <?php } ?>
                              </tbody>
                            </table>
                          <?php
                       }else{
                          ?>
                          <div class="alert alert-warning">
                            <strong>Warning!</strong> No courses available for you to enroll.
                          </div>
                          <?php
                       }
                    ?>
								</span>
							</span>
						
					</div>
					
					<div class="span6 left_one">
						<?php
							//--GET ALL ANNOUCEMENTS-------------	
							$end_sql    = "SELECT * FROM annoucements WHERE created_by='$email' AND class_id='$enrolled_class_id1' ORDER BY ann_id DESC";
							$end_result = mysql_query($end_sql);
							$countxx = mysql_num_rows($end_result);
						?>
						<a href="annoucements.php">
							<span style="float:left; width:33%">
								<p class="in_head">ANNOUNCEMENTS
									<?/*<p style="float: right; color: #fff; background: #0088cc; border-radius: 16px; padding: 0px 0px 0px 8px; width: 15px; margin: -33px 2px 0px 0px;">
										<?php echo $countxx; ?>
									</p>*/?>
								</p>
							</span>	
						</a>
							<?/*<span style="clear:both; float:left">
								<img src="img/demo-image.png" class="avatar">
							</span>*/?>
							<span style="float:left; height:200px; overflow-y:scroll; width:100%;">
						<?php 
						if($countxx != '0')
						{
						?>
						<table class="table table-hover table-responsive">
						<thead>
							<tr>
								<th>#</th>
								<th>Announcement Title</th>
								<th>Announcement Content</th>
								<th>Class Name</th>
								<th>Announcement Timestamp</th>
							</tr>
						</thead>
                    <?php 
						$i="0";
						while($ann = mysql_fetch_object($end_result))
						{ $i++;?>
							<tr>
								<td><?php echo $i; ?></td>
								<td>
									<a href="#" onclick="show_pops(<?php echo $ann->ann_id;?>)">
										<?php echo $ann->ann_title;?>
									</a>
								</td>
								<td><?php echo substr($ann->ann_text,0,30)."...";?></td>
								<td>
									<?php 
										$classId = $ann->class_id;
										$end_s1 = "SELECT * FROM classes WHERE class_id='$classId'";
										$end_res1 = mysql_query($end_s1);
										$ann1 = mysql_fetch_object($end_res1);
										echo $ann1->class_name;
									?>
								</td>
								<td><?php echo $ann->timestamp;?></td>
							</tr>
						<?
						}
						?>
                    
                    </table>
					<?php 
					} else {
						echo "No Announcement Found.";
					}?>
							</span>
					</div>
				</div>
            </div>
        </div>
    </div>


  <!-- scripts -->
    <script src="http://code.jquery.com/jquery-latest.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery-ui-1.10.2.custom.min.js"></script>
    <!-- knob -->
    <script src="js/jquery.knob.js"></script>
    <!-- flot charts -->

	<script src="js/theme.js"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap-editable/js/bootstrap-editable.min.js"></script>
    <script src="js/bootbox.min.js"></script>
    <script type="text/javascript">
       function deleteClass(class_id){
          bootbox.confirm("Are you sure to delete this class?", function(result) {
                if(result==false) return;
                $.ajax({
                  type: "POST",
                  url: "teacher-ajax.php",
                  data: { action: "delete_class", class_id: class_id }
                })
                .done(function( msg ) {
                   if(msg=="yes"){
                      bootbox.alert("<h3 class='text-success'>You have successfully deleted this class.</h3>");
                      $("#class_"+class_id).remove();
                   }else{
                      bootbox.alert("<h3 class='text-danger'>Sorry!! we could not delete this class.Please try again later.</h3>");
                   }
                });
          }); 
       }
    </script>
    <script type="text/javascript">
        $(function () {
            // jQuery Knobs
            $(".knob").knob();
        });
		
		//--Function to show information pop-up 
		function show_pops(str)
		{
			$.ajax({
				type: "POST",
				url: "getAnnouncementInfo.php",
				data: 'str='+str,
				context: document.body
				}).done(function(result){
					//alert(result);
					//return false;
					$('#InfoByAjax').html(result);
					$('#fades').show();
					$('.annoucementINF').show();
				});
			
		}
		
		function HideMe2()
		{
			$('#fades').hide();
			$('.annoucementINF').hide();
		}
    </script>
	
	<!----POP-UP WITH ANNOUNCEMENT INFORMATION---------->
	<div id="myModal_pOp" class="annoucementINF" style="display:none; height:auto;">
		<form enctype="multipart/form-data" method="post" id="fileupload">
			<div class="modal-header">
				<span id="CrossME" onclick="return HideMe2();" >×</span>

				<span id="InfoByAjax"></span>
			</div>
		</form>
	</div>
	<div class="modal-backdrop fade in" id="fades" style="display:none;"></div>
	
</body>
</html>