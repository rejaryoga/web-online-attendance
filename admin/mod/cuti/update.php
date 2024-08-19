<?php
session_start();
if(empty($connection)){
  header('location:../../');
} else {
  include_once 'mod/sw-panel.php';

  // Check if the ID parameter is provided
  if(isset($_GET['id'])){
    $id = $_GET['id'];

    // Retrieve the leave request data based on the ID
    $query = "SELECT * FROM leave_requests WHERE id = '$id'";
    $result = $connection->query($query);

    if($result->num_rows > 0){
      $row = $result->fetch_assoc();
      // Display the form to edit the leave request
      echo '
      <div class="content-wrapper">
        <section class="content-header">
          <h1>Edit Data Cuti</h1>
          <ol class="breadcrumb">
            <li><a href="./"><i class="fa fa-dashboard"></i> Beranda</a></li>
            <li><a href="cuti.php">Data Cuti</a></li>
            <li class="active">Edit Data Cuti</li>
          </ol>
        </section>
        <section class="content">
          <div class="row">
            <div class="col-md-12">
              <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title">Edit Leave Request</h3>
                </div>
                <form role="form" method="post" action="update_cuti.php">
                  <div class="box-body">
                    <div class="form-group">
                      <label for="leave_type">Leave Type</label>
                      <input type="text" class="form-control" id="leave_type" name="leave_type" value="' . $row['leave_type'] . '">
                    </div>
                    <div class="form-group">
                      <label for="leave_reason">Leave Reason</label>
                      <input type="text" class="form-control" id="leave_reason" name="leave_reason" value="' . $row['leave_reason'] . '">
                    </div>
                  </div>
                  <div class="box-footer">
                    <input type="hidden" name="id" value="' . $id . '">
                    <button type="submit" class="btn btn-primary">Update</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </section>
      </div>';
    } else {
      echo 'Leave request not found.';
    }
  } else {
    echo 'Leave request ID not provided.';
  }
}
?>