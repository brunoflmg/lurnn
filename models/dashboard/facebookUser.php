<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class facebookUser extends CI_Model{
		public $facebook;
		public function __construct(){
			//$this->facebook = $facebook;
		}
		public function loadFilters() {
			$filters =  array(
				'searchFriendsBasedOnSchooling'  => 'Friends you went to School with',
				'searchByCurrentLocation'        => 'Friends who live in your current Locality',
				'searchByCompany'                => 'Friends you work with',
				'searchFriendsBasedOnJobTitle'   => 'Friends who share same Job Title',
				'searchByHomeTown'               => 'Friends who shares the same home Town',
				'searchByGraduationDate'         => 'Friends who graduated with you',
				'searchFriendsBasedOnCollege'    => 'Friends you went to College with',
				'searchFriendsBasedOnGraduation' => 'Friends you went to Graduation with',
				'searchFriendsBasedOnMajor'      => 'Friends who did the same major'
			);	
			$returnArray['filters'] = $filters;
			return $returnArray;
		}
		
		public  function searchFriendsBasedOnCompany($companyName){
			$companyName   = explode(",",$companyName);
			$user_profile  = $this->facebook->api('/me');
			$friends       = $this->facebook->api('/me/friends',array('fields'=>'id,name,work,picture,location,education'));
			$friendIds     =  array();
			$userIds       = array();
			d($friends,1);
			for($i=0;$i<count($friends['data']);$i++){
				if(isset($friends['data'][$i]['work'])){
					for($j=0;$j<count($friends['data'][$i]['work']);$j++){
						for($cnt=0;$cnt<count($companyName);$cnt++){	
							$checkString = $companyName[$cnt];
							$pos = strpos(strtolower($friends['data'][$i]['work'][$j]['employer']['name']), trim(strtolower($checkString)));
							if ($pos !== false) {
								if(!in_array($friends['data'][$i]['id'],$userIds)){
									$userIds[]       = $friends['data'][$i]['id'];
									$tempArr['name'] = $friends['data'][$i]['name']; 
									$tempArr['pic']  = $friends['data'][$i]['picture'];
									if(isset($friends['data'][$i]['location']['name'])){
										$tempArr['addInfo'] = $friends['data'][$i]['location']['name']; 
									}else if(isset($friends['data'][$i]['education'])){
										$tempArr['addInfo'] = $friends['data'][$i]['education'][0]['school']['name']; 
									}
									$friendIds[] = $tempArr;break; 
								}	
							}
						}
					}	
				}
			}
			$detailsArray               = array_chunk($friendIds, 15);
			$returnArray['userDetails'] = $detailsArray[0];
			$returnArray['title']       = 'Friends you work with';
			return $returnArray;
		}
		
		public  function searchFriendsBasedOnJobTitle($jobTitle){
			$jobTitle      = explode(",",$jobTitle);
			$user_profile  = $this->facebook->api('/me');
			$friends       = $this->facebook->api('/me/friends',array('fields'=>'id,name,work,picture,location'));
			$friendIds     =  array();
			$userIds       = array();
			for($i=0;$i<count($friends['data']);$i++){
				if(isset($friends['data'][$i]['work'])){
					for($j=0;$j<count($friends['data'][$i]['work']);$j++){
						for($cnt=0;$cnt<count($jobTitle);$cnt++){	
							$checkString = $jobTitle[$cnt];		
							$pos1 = false;
							if(isset($friends['data'][$i]['work'][$j]['position']['name'])){
								$pos1 = strpos(strtolower($friends['data'][$i]['work'][$j]['position']['name']), trim(strtolower($checkString)));
							}
							if ($pos1 !== false) {
								if(!in_array($friends['data'][$i]['id'],$userIds)){
									$userIds[] = $friends['data'][$i]['id'];
									$tempArr['name'] = $friends['data'][$i]['name']; 
									$tempArr['pic'] = $friends['data'][$i]['picture'];
									if(isset($friends['data'][$i]['location']['name'])){
										$tempArr['addInfo'] = $friends['data'][$i]['location']['name']; 
									}else if(isset($friends['data'][$i]['work'])){
										$tempArr['addInfo'] = $friends['data'][$i]['work'][0]['employer']['name']; 
									}
				 
									$friendIds[] = $tempArr;break; 
								}	
							}
						}
					}
					
				}
			}
			$detailsArray               = array_chunk($friendIds, 15);
			$returnArray['userDetails'] = $detailsArray[0];
			$returnArray['title']       = 'Friends who share same Job Title';
			return $returnArray;
		}
		
		
		
		
		public function searchFriendsBasedOnCurrentLocation($placeName){
			$user_profile = $this->facebook->api('/me');
			$friends      = $this->facebook->api('/me/friends',array('fields'=>'name,location,picture,work,education'));
			$friendIds    =  array();
			for($i=0;$i<count($friends['data']);$i++){
				if(isset($friends['data'][$i]['location'])){
					$pos = false;
					if(isset($friends['data'][$i]['location']['name'])){
						$pos = strpos(strtolower($friends['data'][$i]['location']['name']), trim(strtolower($placeName)));
					}
					if ($pos !== false) {
						$tempArr['name'] = $friends['data'][$i]['name']; 
						$tempArr['pic'] = $friends['data'][$i]['picture']; 
						if(isset($friends['data'][$i]['work'])){
							$tempArr['addInfo'] = $friends['data'][$i]['work'][0]['employer']['name']; 
						}else if(isset($friends['data'][$i]['education'])){
							$tempArr['addInfo'] = $friends['data'][$i]['education'][0]['school']['name']; 
						}
						
						$friendIds[] = $tempArr; 
					}
					
				}
			}
			
			$detailsArray               = array_chunk($friendIds, 15);
			$returnArray['userDetails'] = $detailsArray[0];
			$returnArray['title']       = 'Friends who live in your current Locality';
			return $returnArray;
		}
		
		public function searchFriendsBasedOnHomeTown($placeName){
			$user_profile 	= $this->facebook->api('/me');
			$friends 		= $this->facebook->api('/me/friends',array('fields'=>'name,picture,hometown,work,education'));
			$friendIds		=  array();
			for($i=0;$i<count($friends['data']);$i++){
				if(isset($friends['data'][$i]['hometown'])){
					$pos1 = false;		
					if(isset($friends['data'][$i]['hometown']['name'])){
						$pos1 = strpos(strtolower($friends['data'][$i]['hometown']['name']), trim(strtolower($placeName)));
					}
					if ($pos1 !== false) {
						$tempArr['name'] = $friends['data'][$i]['name']; 
						$tempArr['pic'] = $friends['data'][$i]['picture']; 
						if(isset($friends['data'][$i]['work'])){
							$tempArr['addInfo'] = $friends['data'][$i]['work'][0]['employer']['name']; 
						}else if(isset($friends['data'][$i]['education'])){
							$tempArr['addInfo'] = $friends['data'][$i]['education'][0]['school']['name']; 
						}
			
						$friendIds[] = $tempArr; 
					}
				}
			}
			$detailsArray               = array_chunk($friendIds, 15);
			$returnArray['userDetails'] = $detailsArray[0];
			$returnArray['title']       = 'Friends who shares the same home Town';
			return $returnArray;
		}
		
		public function getShortName($text) {
			$words     = explode(" ",$text);
			$shortText = "";
			for($i=0;$i<count($words);$i++) {
				$shortText = $shortText.$words[$i][0];
			}
			return $shortText;
		}
		
		public function searchFriendsBasedOnSchooling($schoolName){
			$schoolName    = explode(",",$schoolName);
			$user_profile  = $this->facebook->api('/me');
			$friends       = $this->facebook->api('/me/friends',array('fields'=>'id,name,education,picture,location,work'));
			$friendIds     =  array();
			$userIds       = array();
			for($i=0;$i<count($friends['data']);$i++){
				if(isset($friends['data'][$i]['education'])){
					for($j=0;$j<count($friends['data'][$i]['education']);$j++){
						for($cnt=0;$cnt<count($schoolName);$cnt++){	
							$checkString = $schoolName[$cnt];
							$pos         = strpos(strtolower($friends['data'][$i]['education'][$j]['school']['name']), trim(strtolower($checkString)));
							if (($pos !== false || strtolower($checkString) == $this->getShortName(strtolower($friends['data'][$i]['education'][$j]['school']['name']))) && (strtolower($friends['data'][$i]['education'][$j]['type']) == 'high school' || strtolower($friends['data'][$i]['education'][$j]['type']) == 'highschool')) {
								if(!in_array($friends['data'][$i]['id'],$userIds)){
									$userIds[] = $friends['data'][$i]['id'];
									$tempArr['name'] = $friends['data'][$i]['name']; 
									$tempArr['pic'] = $friends['data'][$i]['picture']; 
									if(isset($friends['data'][$i]['location']['name'])){
										$tempArr['addInfo'] = $friends['data'][$i]['location']['name']; 
									}else if(isset($friends['data'][$i]['work'])){
										$tempArr['addInfo'] = $friends['data'][$i]['work'][0]['employer']['name']; 
									}
									$friendIds[] = $tempArr;break; 
								}	
							}
						}
					}
					
				}
			}
			$detailsArray               = array_chunk($friendIds, 15);
			$returnArray['userDetails'] = $detailsArray[0];
			$returnArray['title']       = 'Friends you went to School with';
			return $returnArray;
		}
		
		public function searchFriendsBasedOnGraduation($schoolName){
			$schoolName    = explode(",",$schoolName);
			$user_profile  = $this->facebook->api('/me');
			$friends       = $this->facebook->api('/me/friends',array('fields'=>'id,name,education,picture,location,work'));
			$friendIds     =  array();
			$userIds       = array();
			
			for($i=0;$i<count($friends['data']);$i++){
				if(isset($friends['data'][$i]['education'])){
					for($j=0;$j<count($friends['data'][$i]['education']);$j++){
						for($cnt=0;$cnt<count($schoolName);$cnt++){	
							$checkString = $schoolName[$cnt];
							$pos         = strpos(strtolower($friends['data'][$i]['education'][$j]['school']['name']), trim(strtolower($checkString)));
						
							if (($pos !== false || strtolower($checkString) == $this->getShortName(strtolower($friends['data'][$i]['education'][$j]['school']['name']))) && strpos(strtolower($friends['data'][$i]['education'][$j]['type']),'graduate') !== false) {
								if(!in_array($friends['data'][$i]['id'],$userIds)){
									$userIds[] = $friends['data'][$i]['id'];
									$tempArr['name'] = $friends['data'][$i]['name']; 
									$tempArr['pic'] = $friends['data'][$i]['picture']; 
									if(isset($friends['data'][$i]['location']['name'])){
										$tempArr['addInfo'] = $friends['data'][$i]['location']['name']; 
									}else if(isset($friends['data'][$i]['work'])){
										$tempArr['addInfo'] = $friends['data'][$i]['work'][0]['employer']['name']; 
									}
				
									$friendIds[] = $tempArr;break; 
								}	
							}
						}
					}
					
				}
			}
			$detailsArray               = array_chunk($friendIds, 15);
			$returnArray['userDetails'] = $detailsArray[0];
			$returnArray['title']       = 'Friends you went to Graduation with';
			return $returnArray;
		}
		
		
		public function searchFriendsBasedOnCollege($schoolName){
			$schoolName     = explode(",",$schoolName);
			$user_profile   = $this->facebook->api('/me');
			$friends        = $this->facebook->api('/me/friends',array('fields'=>'id,name,education,picture,location,work'));
			$friendIds      =  array();
			$userIds        = array();
			
			for($i=0;$i<count($friends['data']);$i++){
				if(isset($friends['data'][$i]['education'])){
					for($j=0;$j<count($friends['data'][$i]['education']);$j++){
						for($cnt=0;$cnt<count($schoolName);$cnt++){	
							$checkString = $schoolName[$cnt];
							$pos         = strpos(strtolower($friends['data'][$i]['education'][$j]['school']['name']), trim(strtolower($checkString)));
						
							if (($pos !== false || strtolower($checkString) == $this->getShortName(strtolower($friends['data'][$i]['education'][$j]['school']['name']))) && strtolower($friends['data'][$i]['education'][$j]['type']) == 'college') {
								if(!in_array($friends['data'][$i]['id'],$userIds)){
									$userIds[]       = $friends['data'][$i]['id'];
									$tempArr['name'] = $friends['data'][$i]['name']; 
									$tempArr['pic']  = $friends['data'][$i]['picture']; 
									if(isset($friends['data'][$i]['location']['name'])){
										$tempArr['addInfo'] = $friends['data'][$i]['location']['name']; 
									}else if(isset($friends['data'][$i]['work'])){
										$tempArr['addInfo'] = $friends['data'][$i]['work'][0]['employer']['name']; 
									}
				
									$friendIds[] = $tempArr;break; 
								}	
							}
						}
					}
					
				}
			}
			$detailsArray               = array_chunk($friendIds, 15);
			$returnArray['userDetails'] = $detailsArray[0];
			$returnArray['title']       = 'Friends you went to College with';
			return $returnArray;
		}
		
		
		public function searchFriendsBasedOnMajor($searchText){
			$searchText    = explode(",",$searchText);
			$user_profile  = $this->facebook->api('/me');
			$friends       = $this->facebook->api('/me/friends',array('fields'=>'id,name,education,picture,location,work'));
			$friendIds     =  array();
			$userIds       = array();
			for($i=0;$i<count($friends['data']);$i++){
				if(isset($friends['data'][$i]['education'])){
					for($j=0;$j<count($friends['data'][$i]['education']);$j++){
						for($cnt=0;$cnt<count($searchText);$cnt++){	
							$checkString = $searchText[$cnt];
							if(isset($friends['data'][$i]['education'][$j]['concentration'][0]['name'])){	
								$pos = strpos(strtolower($friends['data'][$i]['education'][$j]['concentration'][0]['name']), trim(strtolower($checkString)));		
								if ($pos !== false) {
									if(!in_array($friends['data'][$i]['id'],$userIds)){
										$userIds[] = $friends['data'][$i]['id'];
										$tempArr['name'] = $friends['data'][$i]['name']; 
										$tempArr['pic'] = $friends['data'][$i]['picture']; 
										if(isset($friends['data'][$i]['location']['name'])){
											$tempArr['addInfo'] = $friends['data'][$i]['location']['name']; 
										}else if(isset($friends['data'][$i]['work'])){
											$tempArr['addInfo'] = $friends['data'][$i]['work'][0]['employer']['name']; 
										}
				
										$friendIds[] = $tempArr;break; 
									}	
								}
							}
						}
					}
					
				}
			}
			$detailsArray               = array_chunk($friendIds, 15);
			$returnArray['userDetails'] = $detailsArray[0];
			$returnArray['title']       = 'Friends who did the same major';
			return $returnArray;
		}
		
		
		
		
		public function searchFriendsBasedOnGraduationDate($year){
			$user_profile = $this->facebook->api('/me');
			$friends      = $this->facebook->api('/me/friends',array('fields'=>'id,name,education,picture,location,work'));
			$friendIds    =  array();
			$userIds      = array();
			for($i=0;$i<count($friends['data']);$i++){
				if(isset($friends['data'][$i]['education'])){
					for($j=0;$j<count($friends['data'][$i]['education']);$j++){
						if(isset($friends['data'][$i]['education'][$j]['year']['name'])){
							$pos = strpos(strtolower($friends['data'][$i]['education'][$j]['year']['name']), trim($year));
							if ($pos !== false && strpos(strtolower($friends['data'][$i]['education'][$j]['type']),'graduate') !== false) {
								if(!in_array($friends['data'][$i]['id'],$userIds)){
									$userIds[] = $friends['data'][$i]['id'];
									$tempArr['name'] = $friends['data'][$i]['name']; 
									$tempArr['pic'] = $friends['data'][$i]['picture']; 
									if(isset($friends['data'][$i]['location']['name'])){
										$tempArr['addInfo'] = $friends['data'][$i]['location']['name']; 
									}else if(isset($friends['data'][$i]['work'])){
										$tempArr['addInfo'] = $friends['data'][$i]['work'][0]['employer']['name']; 
									}
									$friendIds[] = $tempArr;
								}	
							}
						}
					}	
				}  
			}
			$detailsArray               = array_chunk($friendIds, 15);
			$returnArray['userDetails'] = $detailsArray[0];
			$returnArray['title']       = 'Friends who graduated with you';
			return $returnArray;
		}
	
	
	}

?>