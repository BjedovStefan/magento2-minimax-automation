<?php

// PMA.omnia8.com
// user: root
// pass: ala007***kaktus


$mysqli = new mysqli("localhost","root","pass","database_name");

// Check connection
if ($mysqli -> connect_errno) {
  echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
  exit();
}
else{
    echo("Connection to db successfull!<br><br>");
}


?>