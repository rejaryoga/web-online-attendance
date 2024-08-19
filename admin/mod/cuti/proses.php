<?php
session_start();
if (empty($connection)) {
  header('location:../../');
} else {
  // Include database connection and other necessary files

  if (isset($_POST['action'])) {
    $action = $_POST['action'];

    if ($action == 'update') {
      if (isset($_POST['id'])) {
        // Get the form data
        $id = $_POST['id'];
        $employeeId = $_POST['employee_id'];
        $leaveType = $_POST['leave_type'];
        $leaveReason = $_POST['leave_reason'];
        $requestDate = $_POST['request_date'];
        $status = $_POST['status'];
        $leaveDate = $_POST['leave_date'];

        // Perform the update operation using the provided data
        // Modify the SQL query and execution accordingly

        // Example code:
        $query = "UPDATE leave_requests SET employee_id = $employeeId, leave_type = '$leaveType', leave_reason = '$leaveReason', request_date = '$requestDate', status = '$status', leave_date = '$leaveDate' WHERE id = $id";
        $result = $connection->query($query);

        if ($result) {
          echo 'success';
        } else {
          echo 'error';
        }
      }
    } else if ($action == 'delete') {
      if (isset($_POST['id'])) {
        $id = $_POST['id'];
        
        // Perform the delete operation using the provided id
        // Modify the SQL query and execution accordingly

        // Example code:
        $query = "DELETE FROM leave_requests WHERE id = $id";
        $result = $connection->query($query);

        if ($result) {
          echo 'success';
        } else {
          echo 'error';
        }
      }
    }
  }
}
?>