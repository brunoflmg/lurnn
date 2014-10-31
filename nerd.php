<?php

        class idiot {
            #code
        
        
        function CreateUser () {
        $con = mysql_connect("localhost","root","");
        if (!$con)
            {
        die('Could not connect: ' . mysql_error());
            }
            mysql_select_db("test", $con);
            $username = mysql_real_escape_string($_POST['username'], $con);
            $password = mysql_real_escape_string($_POST['password'], $con);
            $sql = "INSERT INTO members (username, password) VALUES ('$username','$password');
            mysql_query( $sql , $con );

            mysql_close($con);
            
            }

        }

    ?>