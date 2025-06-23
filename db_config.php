<?php

  $host="localhost:3306";
  $user="root";
  $pass="";
  $db_name="technofab";


  $conn=mysqli_connect($host,$user,$pass,$db_name);


  if($conn)
  {
  	// echo "<br> connection established successfully";

     
  }
  else
  {
  	echo "<br> connection Fail";

  }


?>


