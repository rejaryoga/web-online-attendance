<?php
session_start();
if (empty($connection)) {
  header('location:../../');
} else {
  include_once 'mod/sw-panel.php';

  echo '
  <div class="content-wrapper">
    <section class="content-header">
      <h1>Data<small> Cuti</small></h1>
      <ol class="breadcrumb">
        <li><a href="./"><i class="fa fa-dashboard"></i> Beranda</a></li>
        <li class="active">Data Cuti</li>
      </ol>
    </section>';

  echo '
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">Data Cuti</h3>
            </div>
            <div class="box-body">
              <div class="table-responsive">
                <table id="swdatatable" class="table table-bordered">
                  <thead>
                    <tr>
                    <th style="width: 10px">No</th>
                      <th>Employee Name</th>
                      <th>Leave Type</th>
                      <th>Leave Reason</th>
                      <th>Request Date</th>

                    </tr>
                  </thead>
                  <tbody>';

  $query = "SELECT leave_requests.id, employees.employees_name, leave_requests.leave_type, leave_requests.leave_reason, leave_requests.request_date, leave_requests.status, leave_requests.leave_date FROM leave_requests INNER JOIN employees ON leave_requests.employee_id = employees.id";
  $result = $connection->query($query);
  
  if ($result->num_rows > 0) {
    $no=0;
    while ($row = $result->fetch_assoc()) {
      $status = ucfirst($row['status']);
      $no++;

      echo '
        <tr>
        <td class="text-center">'.$no.'</td>
          <td>'.$row['employees_name'].'</td>
          <td>'.$row['leave_type'].'</td>
          <td>'.$row['leave_reason'].'</td>
          <td>'.$row['request_date'].'</td>
        </tr>';
    }
  } else {
    echo '<tr><td colspan="7">No leave requests found.</td></tr>';
  }

  echo '
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>';
}
?>

<script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
<script>
$(document).ready(function() {
  $('#swdatatable').DataTable();
});

function editLeaveRequest(id, employeeId, leaveType, leaveReason, requestDate, status,  leaveDate) {
  // Set the values in the edit modal form
  document.getElementById('txtid').value = id;
  document.getElementById('txtemployee_id').value = employeeId;
  document.getElementById('txtleave_type').value = leaveType;
  document.getElementById('txtleave_reason').value = leaveReason;
  document.getElementById('txtrequest_date').value = requestDate;
  document.getElementById('txtstatus').value = status;
  document.getElementById('txtleave_date').value = leaveDate;
}

function deleteLeaveRequest(id) {
  // Confirm deletion
  if (confirm('Are you sure you want to delete this leave request?')) {
    // Perform the deletion via AJAX request
    $.ajax({
      url: 'mod/cuti/proses.php',
      type: 'POST',
      data: { action: 'delete', id: id },
      success: function(response) {
        if (response == 'success') {
          // Reload the page or update the table
          location.reload();
        } else {
          alert('Failed to delete leave request.');
        }
      },
      error: function() {
        alert('An error occurred while processing the request.');
      }
    });
  }
}
</script>

