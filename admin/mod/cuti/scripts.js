$(document).ready(function() {
  $('#swdatatable').dataTable({
      "iDisplayLength":31,
      "aLengthMenu": [[31, 50, 100, -1], [31, 50, 100, "All"]],
  });
  
  function loading(){
      $(".loading").show();
      $(".loading").delay(1500).fadeOut(500);
  }
  
  loadData();
  function loadData() {
      var id = $('.id').val();
      $.ajax({
        url: 'mod/cuti/cuti.php?action=absensi&id='+id+'',
          type: 'POST',
          success: function(data) {
            $('.loaddata').html(data);
          }
      });
  }
  
  $('.btn-clear').click(function (e) {
      loadData();
      $('.month').val('');
      $('.year').val('');
  });
  
  $('.btn-sortir').click(function (e) {
          var month_d = new Array();
          month_d[0] = "January";
          month_d[1] = "February";
          month_d[2] = "March";
          month_d[3] = "April";
          month_d[4] = "May";
          month_d[5] = "June";
          month_d[6] = "July";
          month_d[7] = "August";
          month_d[8] = "September";
          month_d[9] = "October";
          month_d[10] = "November";
          month_d[11] = "December";
  
          var id    = $('.id').val();
          var month = $('.month').val();
          var year  = $('.year').val();
  
          var d     = new Date(month);
          var n     = month_d[d.getMonth()];
          //document.getElementById("demo").innerHTML = n;
          $('.result-month').html(n);
  
         $.ajax({
          url: 'mod/cuti/cuti.php?action=absensi&id='+id+'',
            method:"POST",
            data:{month:month,year:year},
            dataType:"text",
            cache: false,
            async: false,
              beforeSend: function () { 
               //loading();
              },
              success: function (data) {
                 $('.loaddata').html(data);
              },
          complete: function () {
              //$(".loading").hide();
          },
      });
  });
  
  (function() {
      var $gallery = new SimpleLightbox(".picture a", {});
  })();
  
  
      $('.btn-print').click(function (e) {
              var id    = $('.id').val();
              var month = $('.month').val();
              var year  = $('.year').val();
              var type  = $(this).attr("data-id");
          
              if(type =='pdf'){
                  // cek berdasarkan bulan
                  if(month==''){    
                      var url = "./absensi/print?action=pdfid="+id+"";
                  }else{
                      var url = "./absensi/print?action=pdf&id="+id+"&from="+month+"&to="+year+"";
                  }
              }
  
              if(type=='excel'){
                  if(month==''){    
                      var url = "./absensi/print?action=excel&id="+id+"";
                  }else{
                      var url = "./absensi/print?action=excel&id="+id+"&from="+month+"&to="+year+"";
                  }
              }
  
              if(type=='print'){
                  var url = "./absensi/print?action=excel&id="+id+"&from="+month+"&to="+year+"&print=print";
              }
              window.open(url, '_blank');
      });
  
      $('.btn-print-all').click(function (e) {
              var month = $('.month').val();
              var year  = $('.year').val();
              var type  = $('.type').val();
              if(type =='pdf'){
                  // cek berdasarkan bulan
                  var url = "./absensi/print?action=allpdf&from="+month+"&to="+year+"";
              }
              if(type=='excel'){
                  var url = "./absensi/print?action=allexcel&from="+month+"&to="+year+""; 
              }
              if(type=='print'){
                  var url = "./absensi/print?action=allexcel&from="+month+"&to="+year+"&print=print"; 
              }
  
              window.open(url, '_blank');
      });
      $(document).ready(function() {
        $('#swdatatable').DataTable();
      });
      
      function editLeaveRequest(id, employeeId, leaveType, leaveReason, requestDate, status, leaveDate) {
        // Set the values in the edit modal form
        document.getElementById('txtid').value = id;
        document.getElementById('txtemployee_id').value = employeeId;
        document.getElementById('txtleave_type').value = leaveType;
        document.getElementById('txtleave_reason').value = leaveReason;
        document.getElementById('txtrequest_date').value = requestDate;
        document.getElementById('txtstatus').value = status;
        document.getElementById('txtleave_date').value = leaveDate;
      }
      
      function updateLeaveRequest() {
        // Get the form data
        var id = document.getElementById('txtid').value;
        var employeeId = document.getElementById('txtemployee_id').value;
        var leaveType = document.getElementById('txtleave_type').value;
        var leaveReason = document.getElementById('txtleave_reason').value;
        var requestDate = document.getElementById('txtrequest_date').value;
        var status = document.getElementById('txtstatus').value;
        var leaveDate = document.getElementById('txtleave_date').value;
      
        // Perform the update via AJAX request
        $.ajax({
          url: 'proses.php',
          type: 'POST',
          data: {
            action: 'update',
            id: id,
            employee_id: employeeId,
            leave_type: leaveType,
            leave_reason: leaveReason,
            request_date: requestDate,
            status: status,
            leave_date: leaveDate
          },
          success: function(response) {
            if (response == 'success') {
              // Reload the page or update the table data
              location.reload(); // Reload the page
          
              // You can also update the table data without reloading the page
              // Update the specific row in the table with the new values
          
              // Example code:
              var row = document.getElementById('row_' + id); // Assuming the row has an ID
              row.cells[1].innerHTML = employeeId;
              row.cells[2].innerHTML = leaveType;
              row.cells[3].innerHTML = leaveReason;
              row.cells[4].innerHTML = requestDate;
              row.cells[5].innerHTML = status;
              row.cells[6].innerHTML = leaveDate;
          
              // Close the edit modal
              $('#modalEdit').modal('hide');
            } else {
              // Handle the error case
              alert('Failed to update leave request.');
            }
          }
      
        }

        )
      }
    })