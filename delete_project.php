<?php
//require ('Connection.php');
require '../../connect.inc';
require 'functions.php';


$conn = Connect();
mysqli_set_charset($conn,'utf8');

$id = $conn->real_escape_string($_GET['id']);

$sql = "SELECT * FROM projects_data WHERE ID = '".$id."'";
$result = mysqli_query($conn, $sql);  
$resultCheck = mysqli_num_rows($result);

if ($resultCheck > 0) {
	while ($row = mysqli_fetch_assoc($result)) {
		$projectname = $row['project_name'];
	}
}

// Deleting from filesystem

$translitname = russian2translit($projectname);

delete_files('uploads/'.$translitname.'/');


// Deleting from database

$query = "
DELETE FROM projects_data WHERE ID = '".$id."'
";

// Error handling
$success = $conn->query($query);

$conn->close();

function delete_files($target) {
    if(is_dir($target)){
        $files = glob( $target . '*', GLOB_MARK ); //GLOB_MARK adds a slash to directories returned

        foreach( $files as $file ){
            delete_files( $file );      
        }

        rmdir( $target );
    } elseif(is_file($target)) {
        unlink( $target );  
    }
}

header("Location: admin.php?deletion=success");

?>