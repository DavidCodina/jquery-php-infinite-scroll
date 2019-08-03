<?php require 'config.php'; ?>


<?php
//sleep(1); //Test the spinner.

if ( $_SERVER['REQUEST_METHOD'] != 'POST' ){
  header("Location: ../../index.html");
  exit();
}


$connection = mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);
if(! $connection){ die("error ". mysqli_connect_error()); }


$data = array(); //$data will be sent back as the response.
$rows = array();


//Declare / initialize $limit
if ( empty($_POST['number_of_items_to_load']) ){
  $limit = 1;
} else {
  $limit = $_POST['number_of_items_to_load'];
}


//Declare / initialize $offset
if (isset($_POST['offset'])) { //Don't use empty() here.
  $offset = $_POST['offset'];
} else {
  $offset = 0;
}


//In this query, we update the set LIMIT and the OFFSET dynamically with
$query  = "SELECT * FROM blog_posts ORDER BY id DESC LIMIT ". $limit ." OFFSET " . $offset;
$result = mysqli_query($connection, $query);


while ($row = mysqli_fetch_array($result)) {
  $rows[] = array(
    "id"      => $row['id'],
    "content" => $row['content'],
    "date"    => $row['date']
  );
}
mysqli_close($connection);


//Create a key of 'rows' and assign the $rows array to it.
$data['rows'] = $rows;
echo json_encode($data);
?>
