<?php 
   if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class DashboardModel extends CI_Model {
		public $userid;
		public function __construct(){
			parent::__construct();
			
		}
		public function getUserGoals($orderby='new'){
			switch($orderby){
				case "new": 
				      $order_by_sql = "goal_id DESC";
					  break;
			    case "old": 
				      $order_by_sql = "goal_id ASC";
					  break;
			    case "progress": 
				      $order_by_sql = "goal_milestone DESC";
					  break;
                default: 
				      $order_by_sql = "goal_id DESC";
					  break; 
			    
			}
			
			$sql    = "SELECT *FROM goal where userid = '".$this->userid."' order by $order_by_sql";
			$query  = $this->db->query($sql);
			$result = $query->result();
			return $result;
		}
		public function addgoal($data){
			$this->db->insert("goal",$data);
			return $this->db->insert_id();
		}
		public function getGoalByGoalId($goal_id){
			$sql    = "SELECT *FROM goal where userid = '".$this->userid."' AND goal_id='$goal_id'";
			$query  = $this->db->query($sql);
			$result = $query->row();
			return $result;
		}
		public function addMilestone($data){
			$this->db->insert("goal_milestone",$data);
			return $this->db->insert_id();
		}
		public function getMilestoneByGoal($goal_id){
			$sql    = "SELECT *FROM goal_milestone where userid = '".$this->userid."' AND goal_id='$goal_id'";
			$query  = $this->db->query($sql);
			$result = $query->result();
			return $result;
		}
		
		public function addTask($data){
			$this->db->insert("goal_task",$data);
			$task_id = $this->db->insert_id();
			$this->updateProgress($task_id);
			return $task_id;
		}
		public function updateTask($data,$task_id){
			$this->db->update("goal_task",$data,array('task_id'=>$task_id)); 
			return $this->db->affected_rows();
		}
		public function addTaskFeed($data){
			$this->db->insert("task_feed",$data);
			return $this->db->insert_id();
		}
		public function getGoalDetails($goal_id=false){
			if($goal_id){
				$goal_sql_ext = "goal_id = '$goal_id'";
			}else{
				$goal_sql_ext = "1 order by goal_last_updated DESC";
			}
			
			$sql                     = "SELECT t1.* from goal as t1 where $goal_sql_ext";
			$query                   = $this->db->query($sql);
			$data['goal']            = $query->row();
			$sql2            		 = "SELECT t1.* from goal_milestone as t1 where t1.goal_id='".$data['goal']->goal_id."'"; 
			$query2          		 = $this->db->query($sql2);
			$data['milestone']       = $query2->result();
			return $data;
		}
		public function getMilestoneTask($milestone_id){
			$sql    = "SELECT *FROM goal_task where userid = '".$this->userid."' AND milestone_id='$milestone_id'";
			$query  = $this->db->query($sql);
			$result = $query->result();
			return $result;
		}
		public function deleteTask($task_id){
			//d($task_id,1);
			$this->db->delete('task_feed',array('task_id'=>$task_id));
			$this->db->delete('goal_task',array('task_id'=>$task_id));
			$this->updateProgress($task_id);
			return $this->db->affected_rows();
		}
		public function markCompleteTask($task_id){
			$this->db->update('goal_task',array('task_status'=>2),array('task_id'=>$task_id));
			$state = $this->db->affected_rows();
			$this->updateProgress($task_id);
			return $state;
		}
		public function markInCompleteTask($task_id){
			$this->db->update('goal_task',array('task_status'=>1),array('task_id'=>$task_id));
			$state = $this->db->affected_rows();
			$this->updateProgress($task_id);
			return $state;
		}
		
		public function updateProgress($task_id){
			$query = $this->db->get_where("goal_task",array("task_id"=>$task_id));
			$task_details = $query->row();
			
			$sql1    = "SELECT ((t2.completed_task/t1.total_task)*100) as milestone_progress from (SELECT milestone_id,count(*) as total_task from goal_task where milestone_id='".$task_details->milestone_id."' ) as t1 INNER JOIN (SELECT milestone_id,count(*) as completed_task from goal_task where milestone_id='".$task_details->milestone_id."' AND task_status='2') as t2 ON t1.milestone_id = t2.milestone_id";
			$query1  = $this->db->query($sql1);
			$result1 = $query1->row();
			$milestone_progress = number_format($result1->milestone_progress, 2, '.', '');
			
			$this->db->update("goal_milestone",array('milestone_progress'=> $milestone_progress),array('milestone_id'=> $task_details->milestone_id));
			//echo $this->db->last_query();
			//d($milestone_progress,1);
			$sql2 = "SELECT ((t1.total_progress/t2.total_milestone)) as goal_progress FROM (SELECT goal_id,sum(milestone_progress) as total_progress from goal_milestone where goal_id ='".$task_details->goal_id."') as t1 INNER JOIN (SELECT goal_id,count(*) as total_milestone from goal_milestone where goal_id ='".$task_details->goal_id."') as t2 ON t1.goal_id = t2.goal_id";
			
			$query2 = $this->db->query($sql2);
			$result2= $query2->row();
				
			$this->db->update("goal",array('goal_milestone'=> number_format($result2->goal_progress, 2, '.', '')),array('goal_id'=> $task_details->goal_id));
					
		}
		public function getFiles($id,$type){
			$sql    = "SELECT task_feed_resources FROM task_feed where userid = '".$this->userid."' AND ".$type."_id='$id' AND task_feed_resources!=''";
			$query  = $this->db->query($sql);
			$result = $query->result();
			return $result;
		}
		public function getGoalByMilestoneId($milestone_id){
			$sql    = "SELECT *FROM goal where userid = '".$this->userid."' AND goal_id IN (SELECT goal_id from goal_milestone where milestone_id = '$milestone_id')";
			$query  = $this->db->query($sql);
			$result = $query->row();
			return $result;
		}
		public function getMilestonesByGoalId($goal_id){
			$sql    = "SELECT *FROM goal_milestone where userid = '".$this->userid."' AND goal_id = '$goal_id'";
			$query  = $this->db->query($sql);
			$result = $query->result();
			return $result;
		}
		public function getMilestonesByMilestoneId($milestone_id){
			$sql    = "SELECT *FROM goal_milestone where userid = '".$this->userid."' AND goal_id IN (SELECT goal_id from goal_milestone where milestone_id = '$milestone_id')";
			$query  = $this->db->query($sql);
			$result = $query->result();
			return $result;
		}
		public function getTaskDetailsByTaskId($task_id){
			$sql    = "SELECT t1.*,t2.*,t3.* FROM goal_task as t1,goal as t2,goal_milestone as t3 where t1.userid = '".$this->userid."' AND t1.goal_id =t2.goal_id AND t1.milestone_id=t3.milestone_id AND t1.task_id ='$task_id'";
			$query  = $this->db->query($sql);
			$result = $query->row();
			return $result;
		}
		public function getTaskFeedsByTaskId($task_id){
			$sql    = "SELECT *FROM task_feed as t1 where t1.userid = '".$this->userid."' AND t1.task_id ='$task_id'";
			$query  = $this->db->query($sql);
			$result = $query->result();
			return $result;
		}
		
	}	