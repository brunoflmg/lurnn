<?php 
   if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class DashboardModel extends CI_Model {
		
		public function __construct(){
			parent::__construct();
			
		}
		
		public function addNewUser($data){
			$this->db->insert("user",$data);
			return $this->db->insert_id();
		}
		
		public function updateUser($data,$userid){
			$this->db->update("user",$data,array('userid'=>$userid));
			return $this->db->affected_rows();
		}
		
		public function getUserByUserid($userid){
			$sql    = "SELECT *FROM user where userid='$userid'";
			$query  = $this->db->query($sql);
			$result = $query->row();

			return $result;
		}
		
		public function getUserByUsername($username){
			$sql    = "SELECT *FROM user where username='$username'";
			$query  = $this->db->query($sql);
			$result = $query->row();
			return $result;
		}
		
		public function getUserByEmail($email){
			$sql    = "SELECT *FROM user where email='$email'";
			$query  = $this->db->query($sql);
			$result = $query->row();
			return $result;
		}
		
		public function getUserByVerificationKey($verification_key){
			
			$sql    = "SELECT *FROM user where verification_key='$verification_key'";
			
			$query  = $this->db->query($sql);
			$result = $query->row();
			//d($result,1);
			return $result;
			
		}
		public function getUserByEmailOrUsername($email_or_username){
			$sql    = "SELECT *FROM user where username ='$email_or_username' || email='$email_or_username'";
			$query  = $this->db->query($sql);
			$result = $query->row();
			return $result;
		}
		public function getUserByRecoveryKey($recovery_key){
			$sql    = "SELECT *FROM user where recovery_key ='$recovery_key'";
			$query  = $this->db->query($sql);
			$result = $query->row();
			return $result;
		}
		public function getUserByLoginDetails($email_or_username,$password){
			$sql    = "SELECT *FROM user where ( email ='$email_or_username' OR username='$email_or_username') AND password='$password'";
			$query  = $this->db->query($sql);
			
			$result = $query->row_array();
			//d($sql,1);
			return $result;
		}
		public function getUserTwitterProfilesByUserid($userid){
			$sql    = "SELECT *FROM twitter_profile where userid ='$userid'";
			$query  = $this->db->query($sql);
			$result = $query->result();
			return $result;
		}
		public function getUserFacebookProfilesByUserid($userid){
			$sql    = "SELECT *FROM facebook_profile where userid ='$userid'";
			$query  = $this->db->query($sql);
			$result = $query->result();
			return $result;
		}
		public function getFacebookProfileByUid($uid){
			$sql    = "SELECT *FROM facebook_profile where uid ='$uid'";
			$query  = $this->db->query($sql);
			$result = $query->row();
			//d($result,1);
			return $result;
		}
		public function addFacebookProfile($data){
			$new_data = array_filter($data);
			$this->db->insert("facebook_profile",$new_data);
			return $this->db->affected_rows();
		}
		public function getTwitterProfileByUid($twitter_id){
			$sql    = "SELECT *FROM twitter_profile where twitter_id ='$twitter_id'";
			$query  = $this->db->query($sql);
			$result = $query->row();
			return $result;
		}
		public function addTwitterProfile($data){
			$this->db->insert("twitter_profile",$data);
			return $this->db->affected_rows();
		}
		public function updateFacebookProfile($data,$userid){
			$new_data = array_filter($data);
			//d($new_data,1);
			$status = $this->db->update("facebook_profile",$new_data,array("userid"=>$userid));
			//d($status);
			//d($this->db->last_query(),1);
		}
		
		
	}	