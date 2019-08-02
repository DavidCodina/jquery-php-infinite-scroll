<?php require 'config.php'; ?>


<?php
//This file was initially used to populate the blog_posts table.
//However, it's not necessary to use now that one can import infinite_scroll_1.sql into phpmyadmin.

$connection = mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);

if(! $connection){
  die("error ". mysqli_connect_error());
}


////////////////////////////////////////////////////////////////////////////////
//
//  Populate the infinite_scroll_1 database with 50 entries:
//  Presumably, we're not using a prepared statement here because this file
//  simply is used to install the data.
//  That said, it's not a good idea to just trust loripsum.net, but for now
//  it's okay.
//
////////////////////////////////////////////////////////////////////////////////


for ($x = 0; $x < 50; $x++) {
  $file   = file_get_contents('http://loripsum.net/api/3/short', true);
  //echo $file; //Test

  $unix_timestamp  = time();
  $query           = "INSERT INTO blog_posts (content, date) VALUES ('". $file ."' , $unix_timestamp)";
  //echo $query; //Test


  if ( $result = mysqli_query($connection, $query) ) {
    echo "INSERT " . ($x + 1) . " successful! <br>";
  } else {
    echo "Error: " . mysqli_error($connection) . "<br>";
  }
}

mysqli_close($connection);
