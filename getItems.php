<!DOCTYPE html>
<html>
<body>

<?php
/*
  This script pulls the list of items in a MYSQL database,
  and prints them in a readable, HTML format.
*/

# These are the login credentials for the PHP Scripts.
$servername="localhost";
$username="root";
$password="hunger";
$database="test3";

# Connect to the database, and select the appropriate database.
$conn=mysqli_connect($servername, $username, $password, $database);
if (!$conn) {
  die("Connection failed: " . mysqli_connect_errer());
}

# Get all of the data from a specific table in the database.
$query="SELECT * FROM potluck";

$list=mysqli_query($conn, $query);
$data;

for ($i=0; $i < $list->num_rows; $i++) { 
  # code...
  $data[$i] = $list->fetch_object();
}
/*for ($i=0; $i < count($data); $i++) { 
  echo $data[$i];
}*/

$post_data = json_encode($data);
echo $post_data;


mysqli_close($conn);

?>

</body>
</html>