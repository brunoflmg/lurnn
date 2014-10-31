<?php
    require_once("config.php");
    if(isset($_GET['type']) && $_GET['type']=="student"){

        $user_name = mysql_real_escape_string($_GET['id']);
        $sql    = sprintf("SELECT *FROM students WHERE user_name='%s' AND status='pending'",$user_name);
        $result = mysql_query($sql);
        $user   = mysql_fetch_object($result);
        if(isset($user->status) && $user->status=="pending"){
            
            $verification_code = md5(md5($user->user_name.rand(0,99999)));
            if(mysql_query("UPDATE students SET verification_code='".$verification_code."' WHERE sid='".$user->sid."'")){
                $email = $user->email;
                $fname = $user->fname;
                $lname = $user->lname;

                $email_date_time = date("F d, Y h:i a");
                $addressing = "Hi $fname $lname";

                $subject = 'Welcome to lurnn.com! Verify your account';
                $body    = 'You have successfully registered in lurnn.com as a student.<p>Please click the following link to verify your lurnn.com account.</p>
                   <a href="http://'.$_SERVER["SERVER_NAME"].'/verify.php?code='.$verification_code.'&type=student">Click Here</a> or copy the following link into your browser & go <br/>
                   http://'.$_SERVER["SERVER_NAME"].'/verify.php?code='.$verification_code.'&type=student';
                $body   .= '<br/> <strong>Feel free to ask to our following support email, if you fetch any problem.</strong>';

                include_once "email_config.php";
                $template = file_get_contents("Gray/index.html");
                $message  = str_replace(
                                        array(
                                          '[WEBSITE_TITLE]',
                                          '[WEBSITE_ADDRESS]',
                                          '[SENDER_NAME]',
                                          '[SENDER_FOOTER]',
                                          '[SENDER_EMAIL]',
                                          '[SUPPORT_EMAIL_ADDRESS]',
                                          '[WEBSITE_LOGO]',
                                          '[EMAIL_DATE_TIME]',
                                          '[EMAIL_SUBJECT]',
                                          '[EMAIL_ADDRESSING]',
                                          '[EMAIL_BODY]'
                                        ), 

                                        array(
                                          WEBSITE_TITLE,
                                          WEBSITE_ADDRESS,
                                          SENDER_NAME,
                                          SENDER_FOOTER,
                                          SENDER_EMAIL,
                                          SUPPORT_EMAIL_ADDRESS,
                                          WEBSITE_LOGO,
                                          $email_date_time,
                                          $subject,
                                          $addressing,
                                          $body
                                        ), $template);
               

                // To send HTML mail, the Content-type header must be set
                $headers  = 'MIME-Version: 1.0' . "\r\n";
                $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

                // Additional headers
                $headers .= 'To: '.$fname.' <'.$email.'>' . "\r\n";
                $headers .= 'From: lurnn.com <'.EMAIL_SENDER.'>' . "\r\n";

                // Mail it
                @mail($email, $subject, $message, $headers);

                $success_message[] = "<h3 style='color:green'>You have successfully registered your account! We have sent another mail to verify your account.<a href='resend.php?id=".$user_name."&type=student'>Don't get email? click here</a></h3>";
                setFlashData("success_message",$success_message);
            }else{
                $error_message[] = "<h3 style='color:red'>We are facing problem right now,Please <a href='resend.php?id=".$user_name."&type=student'>click here</a> to after sometime to get verification email";
                setFlashData("error_message",$error_message);
            }

        }else{
            $error_message[] = "<h3 style='color:red'>You have already verified or invalid user";
            setFlashData("error_message",$error_message);

        }
        header("Location: signup.php");exit;
    }



    $user_name = mysql_real_escape_string($_GET['id']);
    $sql    = sprintf("SELECT *FROM teachers WHERE user_name='%s' AND status='pending'",$user_name);
    $result = mysql_query($sql);
    $user   = mysql_fetch_object($result);
    if(isset($user->status) && $user->status=="pending"){
        
        $verification_code = md5(md5($user->user_name.rand(0,99999)));
        if(mysql_query("UPDATE teachers SET verification_code='".$verification_code."' WHERE tid='".$user->tid."'")){
                $email = $user->email;
                $fname = $user->fname;
                $lname = $user->lname;

                $email_date_time = date("F d, Y h:i a");
                $addressing = "Hi $fname $lname";

                $subject = 'Welcome to lurnn.com! Verify your account';
                $body    = 'You have successfully registered in lurnn.com as a teacher.<p>Please click the following link to verify your lurnn.com account.</p>
                   <a href="http://'.$_SERVER["SERVER_NAME"].'/verify.php?code='.$verification_code.'&type=teacher">Click Here</a> or copy the following link into your browser & go <br/>
                   http://'.$_SERVER["SERVER_NAME"].'/verify.php?code='.$verification_code.'&type=teacher';
                $body   .= '<br/> <strong>Feel free to ask to our following support email, if you fetch any problem.</strong>';

                include_once "email_config.php";
                $template = file_get_contents("Gray/index.html");
                $message  = str_replace(
                                        array(
                                          '[WEBSITE_TITLE]',
                                          '[WEBSITE_ADDRESS]',
                                          '[SENDER_NAME]',
                                          '[SENDER_FOOTER]',
                                          '[SENDER_EMAIL]',
                                          '[SUPPORT_EMAIL_ADDRESS]',
                                          '[WEBSITE_LOGO]',
                                          '[EMAIL_DATE_TIME]',
                                          '[EMAIL_SUBJECT]',
                                          '[EMAIL_ADDRESSING]',
                                          '[EMAIL_BODY]'
                                        ), 

                                        array(
                                          WEBSITE_TITLE,
                                          WEBSITE_ADDRESS,
                                          SENDER_NAME,
                                          SENDER_FOOTER,
                                          SENDER_EMAIL,
                                          SUPPORT_EMAIL_ADDRESS,
                                          WEBSITE_LOGO,
                                          $email_date_time,
                                          $subject,
                                          $addressing,
                                          $body
                                        ), $template);
               

                // To send HTML mail, the Content-type header must be set
                $headers  = 'MIME-Version: 1.0' . "\r\n";
                $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

                // Additional headers
                $headers .= 'To: '.$fname.' <'.$email.'>' . "\r\n";
                $headers .= 'From: lurnn.com <'.EMAIL_SENDER.'>' . "\r\n";

                // Mail it
                @mail($email, $subject, $message, $headers);

                $success_message[] = "<h3 style='color:green'>You have successfully registered your account! We have sent another mail to verify your account.<a href='resend.php?id=".$user_name."&type=teacher'>Don't get email? click here</a></h3>";
                setFlashData("success_message",$success_message);
            }else{
                $error_message[] = "<h3 style='color:red'>We are facing problem right now,Please <a href='resend.php?id=".$user_name."&type=teacher'>click here</a> to after sometime to get verification email";
                setFlashData("error_message",$error_message);
            }

        }else{
            $error_message[] = "<h3 style='color:red'>You have already verified or invalid user";
            setFlashData("error_message",$error_message);

        }
        //d($_SERVER,1);
        header("Location: signup.php");exit;
?> 
