<?php require 'config.php'; ?>


<?php
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

////////////////////////////////////////////////////////////////////////////////
//
//  Test:
//  Up until this point $data has been empty.
//  Here we create a 'limit' and 'offset' keys and assign values $limit and $offset.
//  This is strictly to check that the values we are receiving from
//  the AJAX request are as expected.
//  Thus, there's no need to include it in the final version.
//
//      $data['limit']  = $limit;
//      $data['offset'] = $offset;
//
////////////////////////////////////////////////////////////////////////////////


//In this query, we update the set LIMIT and the OFFSET dynamically with
$query = "SELECT * FROM blog_posts ORDER BY id DESC LIMIT ". $limit ." OFFSET " . $offset;


////////////////////////////////////////////////////////////////////////////////
//
//  Test:
//  Here we create a 'query' key and assign $query to it.
//  This is strictly to check that the $query is being dyanamically constructed
//  as intended. Thus, there's no need to include it in the final version.
//  $data['query'] = $query ;
//
////////////////////////////////////////////////////////////////////////////////


////////////////////////////////////////////////////////////////////////////////
//
//  Loop through each of the $result->fetch_array() and assign it to the $row array.
//  From that $row extract $row['id'], $row['content'], and $row['date'],
//  assigning it to a new array, which then gets nested inside of the $rows array.
//  I'm not sure why we can't just add $row directly to $rows (???).
//
////////////////////////////////////////////////////////////////////////////////


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
