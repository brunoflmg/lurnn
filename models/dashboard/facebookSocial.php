<?php
require_once('facebook.php');

  class social {

    function authenticate () {
          $config = array(
          'appId' => '273395506024212',
          'secret' => 'cfc1a793023db31c6cf8682ad142bd8f',
           );

          $facebook = new Facebook($config);
          $user_id = $facebook->getUser();

          if($user_id) {

          try {

          $user_profile = $facebook->api('/me','GET');
          } catch(FacebookApiException $e) {
          $login_url = $facebook->getLoginUrl(); 
               echo 'Please <a href="' . $login_url . '">login.</a>';
          error_log($e->getType());
          error_log($e->getMessage());
                 }   
          } else {
        
          $login_url = $facebook->getLoginUrl();
          echo 'Please <a href="' . $login_url . '">login.</a>';

          }
   }


###THIS FUNCTION LOOKS FOR A LIST OF THE USER'S FRIENDS MAJORING IN BUSINESS

                 function getFriendsMajors () {
                 $config = array(
                 'appId' => '273395506024212',
                 'secret' => 'cfc1a793023db31c6cf8682ad142bd8f',
                 );
                 $facebook = new Facebook($config);
                 $user_id = $facebook->getUser();
                 try {
                 $fql    =   "select uid,name,education from user WHERE uid IN (select uid2 from friend where uid1=($user_id))";
                 $param  =   array(
                 'method'    => 'fql.query',
                 'query'     => $fql,
                 'callback'  => ''
                 );
                 $fqlResult   =   $facebook->api($param);
                 }
                     catch(Exception $o){
                 d($o);
                 }
                 
                 $friends = $fqlResult;
                 $friends_BA = array();

                 foreach ($friends as $friend) {
                 $isBA = false;
                 if (is_array($friend['education'])) {
                     foreach ($friend['education'] as $school) {
                         if (isset($school['concentration'])) {
                            foreach ($school['concentration'] as $concentration) {
                                if (strpos(strtolower($concentration['name']), 'business') !== false) {
                                    $friends_BA[] = $friend['name'];
                                    continue 3; // skip to the next friend
                                    }
                                 }
                             }
                          }
                      }
                   }
                 echo '<pre>';
                 print_r($friends_BA);
                 echo '</pre>';
            }



######################
/* 
<?php if(!empty($user_profile['picture'])){ ?>	
				<img style="float:left;height:38px;margin-top:-8px;margin-right:5px;" src="<?=$user_profile['picture']?>"/>	
			  <?php } ?>
              <div style="float:left;color:white;margin-top:3px;margin-right:5px;"><?=$user_profile['first_name']?></div>
			  <img width="25px" style="cursor:pointer" src="./images/facebook.png" id="login">
			  <a class="btn sign-out small" href="#" style="text-decoration: none; float: left; margin-right: 5px;">Sign Out</a>
*/

##THE FUNCTION BELOW LOOKS FOR FRIENDS OF THE ACTIVE USER WHO GO TO UCF/UNIVERSITY OF CENTRAL FLORIDA
#################################################################################

           
           
           
           function getCollegeFriends () {
           $config = array(
           'appId' => '273395506024212',
           'secret' => 'cfc1a793023db31c6cf8682ad142bd8f',
           );
           $facebook = new Facebook($config);
           $user_id = $facebook->getUser();
           try {
           $fql    =   "select uid,name,education from user WHERE uid IN (select uid2 from friend where uid1=($user_id))";
           $param  =   array(
           'method'    => 'fql.query',
           'query'     => $fql,
           'callback'  => ''
           );
           $fqlResult   =   $facebook->api($param);
           }
           catch(Exception $o){
           d($o);
           }
                 
           $friends = $fqlResult;
           $friends_BA = array();
           foreach ($friends as $friend) {
           $isBA = false;
               if (is_array($friend['education'])) {
                  foreach ($friend['education'] as $school) {
                     if (isset($school)) {
                        foreach ($school['school'] as $name) {
                           $lowerName = strtolower($name);
                           if (strpos($lowerName, 'university of central florida') !== false || strpos($lowerName, 'ucf') !== false) {
                           $friends_BA[] = $friend['name'];
                           continue 3; // skip to the next friend
                           

                           }
                         }
                       }
                    }
                 }
              }
                 echo '<pre>';
                 print_r($friends_BA);
                 echo '</pre>';
          }

##THIS FUNCTION CHECKS WHICH FRIENDS OF THE ACTIVE USER LIVE IN THE SAME CITY
                 
                 function getLocalFriends () {
                 $config = array(
                 'appId' => '273395506024212',
                 'secret' => 'cfc1a793023db31c6cf8682ad142bd8f',
                 );
                 $facebook = new Facebook($config);
                 $user_id = $facebook->getUser();
                 try {
                 $fql    =   "select name,current_location from user WHERE uid IN (select uid2 from friend where uid1=($user_id))";
                 $param  =   array(
                 'method'    => 'fql.query',
                 'query'     => $fql,
                 'callback'  => ''
                 );
                 $fqlResult   =   $facebook->api($param);
                 }
                     catch(Exception $o){
                 d($o);
                 }
                 
                 $friends = $fqlResult;
                 $friends_BA = array();
                 foreach ($friends as $friend) {
                   $isBA = false;
                   if (is_array($friend['current_location'])) {
                      $lowerName = strtolower($friend['current_location']['city']);
                          if (strpos($lowerName, 'orlando') !== false || strpos($lowerName, 'altamonte springs') !== false) {
                          $friends_BA[] = $friend['name'];
                }
             }
           }
                 echo '<pre>';
                 print_r($friends_BA);
                 echo '</pre>';
    }
    
##AND THE DISPLAY FRIENDS FUNCTION MENTIONED IN THE NEXT FUNCTION

function displayfriends($major, $friends) {
        // Whatever markup you want here
        // For example -- unordered list
  if (count($friends) > 0) {
    echo "<h2>Friends with $major major</h2>";
    echo '<ul>';
    foreach ($friends as $friend) {
        echo "<li>$friend</li>";
    }
    echo '</ul>';
  }
}

######THIS IS A TEST FUNCTION TO WORK WITH THE UI

function getFriendsWithMajor($major) {
     $config = array(
     'appId' => '273395506024212',
     'secret' => 'cfc1a793023db31c6cf8682ad142bd8f',
     );
     $facebook = new Facebook($config);
     $user_id = $facebook->getUser();
     try {
     $fql    =   "select uid,name,education from user WHERE uid IN (select uid2 from friend where uid1=($user_id))";
     $param  =   array(
     'method'    => 'fql.query',
     'query'     => $fql,
     'callback'  => ''
     );
     $fqlResult   =   $facebook->api($param);
     } catch(Exception $o) {
        d($o);
     }

     $friends = $fqlResult;
     $friends_BA = array();

     foreach ($friends as $friend) {
         if (is_array($friend['education'])) {
             foreach ($friend['education'] as $school) {
                 if (isset($school['concentration'])) {
                    foreach ($school['concentration'] as $concentration) {
                        if (strpos(strtolower($concentration['name']), $major) !== false) {
                            $friends_BA[] = $friend['name'];
                            continue 3; // skip to the next friend
                        }
                    }
                 }
            }
        }
    }
    $this->displayfriends($major);
    
}



##THIS FUNCTION CHECKS WHICH FRIENDS OF THE ACTIVE USER HAVE A JOB IN THE FIELD OF THE USER'S GOAL
                 
                 function getWorkFriends () {
                 $config = array(
                 'appId' => '273395506024212',
                 'secret' => 'cfc1a793023db31c6cf8682ad142bd8f',
                 );
                 $facebook = new Facebook($config);
                 $user_id = $facebook->getUser();
                 try {
                 $fql    =   "select uid,name,current_location from user WHERE uid IN (select uid2 from friend where uid1=($user_id))";
                 $param  =   array(
                 'method'    => 'fql.query',
                 'query'     => $fql,
                 'callback'  => ''
                 );
                 $fqlResult   =   $facebook->api($param);
                 }
                     catch(Exception $o){
                 d($o);
                 }
                 
                 $friends = $fqlResult;
                 $friends_BA = array();
                 foreach ($friends as $friend) {
                 $isBA = false;
                    if (is_array($friend['current_location'])) {
                    $lowerName = strtolower($friend['current_location']['city']);
                       if (strpos($lowerName, 'orlando') !== false || strpos($lowerName, 'altamonte springs') !== false) {
                       $friends_BA[] = $friend['name'];
                      }
                    }
                 }
                 echo '<pre>';
                 print_r($friends_BA);
                 echo '</pre>';

                 }
                 
##THIS FUNCTION CHECKS WHICH FRIEND SOF THE ACTIVE USER SHARE SIMILAR INTERESTS

  }


    
    ?>