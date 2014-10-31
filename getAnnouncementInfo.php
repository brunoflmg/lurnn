<?php
    // You have to write all php code here
   require_once "config.php";
   if(isset($_SESSION['tid'])){
      $user_id = $_SESSION['tid'];
	  $email = $_SESSION['email'];
      $type    = "teacher";
   }else if(isset($_SESSION['sid'])){
      $user_id = $_SESSION['sid'];
      $type    = "student";
   }else{
        echo "<script>window.location='/';</script>";
        exit;
   }
   
   $ann_id = $_POST['str'];
   
	$end_sql    = "SELECT * FROM annoucements WHERE ann_id='$ann_id'";
	$end_result = mysql_query($end_sql);
	$ann = mysql_fetch_object($end_result)
?>

<div style="float:left; width:100%;">
	<h2 style=" background: none repeat scroll 0 0 #333; color: #fff; float: left; font-size: 16px; font-weight: bold; text-align: center; width: 101%;">
		<?php echo $ann->ann_title;?> Information
	</h2>
	
	<span style="float: left; width: 100%; background: #e3e3e3; padding:8px 0 0 7px; margin: 4px 0px 0px;">
		<p style="float: left; font-weight: bold; margin: 0px 13px 0px 0px;">Created By:</p>
		<p style="float:left;"><?php echo $ann->created_by;?></p>
	</span>
	
	<span style="float: left; width: 100%; background: #d3d3d3; padding:8px 0 0 7px; margin: 4px 0px 0px;">
		<p style="float: left; font-weight: bold; margin: 0px 13px 0px 0px;">Timestamp</p>
		<p style="float:left;"><?php echo $ann->timestamp;?></p>
	</span>
	
	<span style="float: left; width: 100%; background: #d3d3d3; padding:8px 0 0 7px; margin: 4px 0px 17px 0px;">
		<p style="float: left; overflow-y: scroll; width: 100%; height: 80px; margin: 0px;">
			<?php echo $ann->ann_text;?>
		</p>
	</span>
</div>