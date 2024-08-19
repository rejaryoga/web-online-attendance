<?php
session_start();
if(empty($connection)){
  header('location:../../');
} else {
  // Check if the ID parameter is provided
  if(isset($_GET['id'])){
    $id = $_GET['id'];

    // Delete the leave request from the database based on the ID
    $query = "DELETE FROM leave_requests WHERE id = '$id'";
    $result = $connection->query($query);

    if($result){
      echo 'Leave request deleted successfully.';
    } else {
      echo 'Failed to delete leave request.';
    }
  } else {
    echo 'Leave request ID not provided.';
  }
}
?>