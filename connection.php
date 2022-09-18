<?php
function Connect()
{
 $dbhost = "127.0.0.1";
 $dbuser = "root";
 $dbpass = "";
 $dbname = "projects";

 $conn = new mysqli($dbhost,$dbuser,$dbpass,$dbname) or die ($conn->connect_error);

 return $conn; 
}

?>